<?php

namespace App\Http\Controllers;

use App\Exports\CallManagementEntryExport;
use App\Exports\CustomerCallHistoryExport;
use App\Imports\CallManagementEntryImport;
use App\Models\CallManagementEntry;
use App\Models\CallLog;
use App\Models\Pincode;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class CallManagementController extends Controller
{
    public function customerCalling(Request $request)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $canCreateCall = auth()->user()->can('call_management_create');
        $canImportExport = auth()->user()->can('call_management_import_export');
        $canEditDelete = auth()->user()->can('call_management_edit_delete');
        $feedbackStatuses = Status::query()
            ->where('module', Status::MODULE_CALL_MANAGEMENT_FEEDBACK)
            ->where('active', 'Y')
            ->orderBy('id')
            ->get(['id', 'status_name', 'display_name']);

        $selectedStatus = (string) $request->input('status');
        $selectedFeedbackStatus = str_starts_with($selectedStatus, 'feedback:')
            ? $feedbackStatuses->firstWhere('id', (int) substr($selectedStatus, 9))
            : null;
        $showCompleted = $selectedFeedbackStatus
            && $this->callManagementFeedbackOutcome($selectedFeedbackStatus) === 'completed';

        $query = CallManagementEntry::query()
            ->with([
                'assignedUser:id,name',
                'latestCallLog.feedbackStatus:id,status_name,display_name',
                'latestNotedCallLog',
            ])
            ->where('status', $showCompleted ? 'completed' : 'assigned');

        if (! auth()->user()->hasRole('superadmin')) {
            $query->where('assigned_user_id', auth()->id());
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('firm_name', 'like', '%'.$search.'%')
                    ->orWhere('contact_person_name', 'like', '%'.$search.'%')
                    ->orWhere('mobile_number', 'like', '%'.$search.'%');
            });
        }

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($fromDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)) {
            $query->whereDate('updated_at', '>=', $fromDate);
        }

        if ($toDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)) {
            $query->whereDate('updated_at', '<=', $toDate);
        }

        $entryCollection = $query
            ->orderByDesc('listing_order')
            ->orderByDesc('id')
            ->get()
            ->filter(function (CallManagementEntry $entry) use ($request) {
                $selectedStatus = (string) $request->input('status');

                if ($selectedStatus === '') {
                    return true;
                }

                $feedbackStatusId = optional($entry->latestCallLog)->feedback_status_id;

                if ($selectedStatus === 'assigned') {
                    return ! $feedbackStatusId;
                }

                if (str_starts_with($selectedStatus, 'feedback:')) {
                    return (int) $feedbackStatusId === (int) substr($selectedStatus, 9);
                }

                return false;
            })
            ->sortBy(function (CallManagementEntry $entry) {
                $feedbackStatus = optional($entry->latestCallLog)->feedbackStatus;

                if (! $this->isFollowUpFeedback($feedbackStatus)) {
                    return 1;
                }

                return $entry->follow_up_date && $entry->follow_up_date->lte(today()) ? 0 : 2;
            })
            ->values();

        $perPage = 10;
        $totalEntries = $entryCollection->count();
        $lastPage = max(1, (int) ceil($totalEntries / $perPage));
        $currentPage = min(max(1, $request->integer('page', 1)), $lastPage);
        $entries = new LengthAwarePaginator(
            $entryCollection->forPage($currentPage, $perPage)->values(),
            $totalEntries,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
        $feedbackStatuses->each(function (Status $status) {
            $status->setAttribute('is_follow_up', $this->isFollowUpFeedback($status));
        });
        $callers = collect();

        if ($canCreateCall || $canEditDelete) {
            $callers = User::permission('call_management_access')
                ->where('active', 'Y')
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        return view('calls.customer-calling', compact(
            'entries',
            'feedbackStatuses',
            'canCreateCall',
            'canImportExport',
            'canEditDelete',
            'callers'
        ));
    }

    public function searchPincodes(Request $request)
    {
        abort_if(Gate::denies('call_management_create') && Gate::denies('call_management_edit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pincodes = Pincode::with([
                'cityname:id,city_name,district_id,state_id',
                'cityname.districtname:id,district_name,state_id',
                'cityname.districtname.statename:id,state_name',
                'cityname.statename:id,state_name',
            ])
            ->where('active', 'Y')
            ->when(trim((string) $request->input('q')), function ($query, $search) {
                $query->where('pincode', 'like', '%'.$search.'%');
            })
            ->orderBy('pincode')
            ->paginate(20);

        return response()->json([
            'results' => $pincodes->map(function ($pincode) {
                $city = $pincode->cityname;
                $district = optional($city)->districtname;
                $state = optional($district)->statename ?: optional($city)->statename;

                return [
                    'id' => $pincode->id,
                    'text' => $pincode->pincode,
                    'city' => optional($city)->city_name ?: '',
                    'district' => optional($district)->district_name ?: '',
                    'state' => optional($state)->state_name ?: '',
                ];
            })->values(),
            'pagination' => ['more' => $pincodes->hasMorePages()],
        ]);
    }

    public function customerCallHistory(Request $request)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $callLogs = $this->customerCallHistoryQuery($request)->latest('started_at')->get();
        $agents = auth()->user()->hasRole('superadmin')
            ? User::whereIn('id', CallLog::whereNotNull('call_management_entry_id')->select('user_id'))
                ->orderBy('name')
                ->get(['id', 'name'])
            : collect();
        $feedbackStatuses = Status::query()
            ->where('module', Status::MODULE_CALL_MANAGEMENT_FEEDBACK)
            ->where('active', 'Y')
            ->orderBy('id')
            ->get(['id', 'status_name', 'display_name']);

        return view('calls.history', compact('callLogs', 'agents', 'feedbackStatuses'));
    }

    public function exportCustomerCallHistory(Request $request)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $callLogs = $this->customerCallHistoryQuery($request)->latest('started_at')->get();

        return Excel::download(new CustomerCallHistoryExport($callLogs), 'customer-call-history.xlsx');
    }

    private function customerCallHistoryQuery(Request $request)
    {
        $query = CallLog::with([
                'user:id,name',
                'feedbackStatus:id,status_name,display_name',
                'callManagementEntry:id,firm_name,contact_person_name,mobile_number',
            ])
            ->whereNotNull('call_management_entry_id');

        if (! auth()->user()->hasRole('superadmin')) {
            $query->where('user_id', auth()->id());
        } elseif ($request->filled('agent_id')) {
            $query->where('user_id', $request->input('agent_id'));
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('number', 'like', '%'.$search.'%')
                    ->orWhere('remark', 'like', '%'.$search.'%')
                    ->orWhereHas('callManagementEntry', function ($entryQuery) use ($search) {
                        $entryQuery->where('firm_name', 'like', '%'.$search.'%')
                            ->orWhere('contact_person_name', 'like', '%'.$search.'%')
                            ->orWhere('mobile_number', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($request->filled('feedback_status_id')) {
            $query->where('feedback_status_id', $request->input('feedback_status_id'));
        }

        if ($request->filled('from_date') && preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->input('from_date'))) {
            $query->whereDate('started_at', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date') && preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->input('to_date'))) {
            $query->whereDate('started_at', '<=', $request->input('to_date'));
        }

        if ($request->input('call_status') === 'completed') {
            $query->where(function ($statusQuery) {
                $statusQuery->where('duration', '>', 0)
                    ->orWhereNotNull('recording_url')
                    ->orWhere('status', 1);
            });
        } elseif ($request->filled('call_status')) {
            $query->where('plivo_status', $request->input('call_status'))
                ->where('duration', '<=', 0)
                ->whereNull('recording_url')
                ->where('status', '!=', 1);
        }

        return $query;
    }

    public function initiateCustomerCall(CallManagementEntry $callManagementEntry)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = auth()->user();
        abort_unless($user->call_management, Response::HTTP_FORBIDDEN, 'Plivo calling is not enabled for this user.');
        abort_unless((int) $callManagementEntry->assigned_user_id === (int) $user->id, Response::HTTP_FORBIDDEN, 'This call is not assigned to you.');

        $agentNumber = $this->e164($user->mobile);
        $customerNumber = $this->e164($callManagementEntry->mobile_number);
        if (! $agentNumber || ! $customerNumber) {
            return response()->json(['success' => false, 'message' => 'Agent or customer mobile number is invalid.'], 422);
        }

        $callLog = null;
        try {
            $this->ensurePlivoConfigured();
            $callLog = CallLog::create([
                'call_management_entry_id' => $callManagementEntry->id,
                'user_id' => $user->id,
                'number' => $customerNumber,
                'started_at' => now(),
                'duration' => 0,
                'status' => 0,
                'plivo_status' => 'initiating',
                'webhook_token' => Str::random(64),
            ]);
            $query = http_build_query(['call_log_id' => $callLog->id, 'token' => $callLog->webhook_token]);

            $response = Http::withBasicAuth(config('services.plivo.auth_id'), config('services.plivo.auth_token'))
                ->asJson()
                ->post('https://api.plivo.com/v1/Account/'.config('services.plivo.auth_id').'/Call/', [
                    'from' => config('services.plivo.from_number'),
                    'to' => $agentNumber,
                    'answer_url' => $this->plivoWebhookUrl('answer_url', 'api/plivo/answer').'?'.$query,
                    'answer_method' => 'POST',
                    'ring_url' => $this->plivoWebhookUrl('status_url', 'api/plivo/status').'?'.$query,
                    'ring_method' => 'POST',
                    'hangup_url' => $this->plivoWebhookUrl('status_url', 'api/plivo/status').'?'.$query,
                    'hangup_method' => 'POST',
                ]);

            if (! $response->successful()) {
                $callLog->update(['plivo_status' => 'failed']);
                return response()->json(['success' => false, 'message' => 'Plivo rejected the call request.'], 502);
            }

            $callUuid = $response->json('request_uuid.0') ?: $response->json('request_uuid');
            $callLog->update(['plivo_call_uuid' => $callUuid, 'plivo_status' => 'queued']);
            $previousNotes = collect();
            try {
                $previousNotes = CallLog::with('feedbackStatus:id,status_name,display_name')
                    ->where('call_management_entry_id', $callManagementEntry->id)
                    ->where('id', '!=', $callLog->id)
                    ->whereNotNull('remark')
                    ->where('remark', '!=', '')
                    ->latest('started_at')
                    ->get()
                    ->map(function (CallLog $previousCall) {
                        return [
                            'note' => $previousCall->remark,
                            'status' => optional($previousCall->feedbackStatus)->display_name
                                ?: optional($previousCall->feedbackStatus)->status_name
                                ?: '—',
                            'date' => optional($previousCall->started_at)->format('d M Y, h:i A') ?: '—',
                        ];
                    })
                    ->values();
            } catch (Throwable $exception) {
                report($exception);
            }

            return response()->json([
                'success' => true,
                'message' => 'Call initiated. Your phone will ring first.',
                'data' => [
                    'call_log_id' => $callLog->id,
                    'status_url' => route('customer-calling.call-status', $callLog),
                    'feedback_url' => route('customer-calling.call-feedback', $callLog),
                    'customer_name' => $callManagementEntry->contact_person_name ?: $callManagementEntry->firm_name,
                    'project_name' => $callManagementEntry->project_name,
                    'project_id' => $callManagementEntry->project_id,
                    'parent_name' => $callManagementEntry->parent_name,
                    'firm_name' => $callManagementEntry->firm_name,
                    'contact_person' => $callManagementEntry->contact_person_name,
                    'mobile' => $callManagementEntry->mobile_number,
                    'customer_type' => $callManagementEntry->customer_type,
                    'address' => $callManagementEntry->address,
                    'pincode' => $callManagementEntry->pincode,
                    'city' => $callManagementEntry->city,
                    'district' => $callManagementEntry->district,
                    'state' => $callManagementEntry->state,
                    'assigned_to' => $user->name,
                    'custom_column_1' => $callManagementEntry->custom_column_1,
                    'custom_column_2' => $callManagementEntry->custom_column_2,
                    'custom_column_3' => $callManagementEntry->custom_column_3,
                    'custom_column_4' => $callManagementEntry->custom_column_4,
                    'previous_notes' => $previousNotes,
                ],
            ]);
        } catch (Throwable $exception) {
            report($exception);
            if ($callLog) $callLog->update(['plivo_status' => 'failed']);

            return response()->json([
                'success' => false,
                'message' => 'Calling service is currently unavailable. Please contact the administrator.',
            ], 502);
        }
    }

    public function customerCallNotes(CallManagementEntry $callManagementEntry)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_unless(
            auth()->user()->hasRole('superadmin') || (int) $callManagementEntry->assigned_user_id === (int) auth()->id(),
            Response::HTTP_FORBIDDEN,
            'You cannot view these notes.'
        );

        $notes = CallLog::with('feedbackStatus:id,status_name,display_name')
            ->where('call_management_entry_id', $callManagementEntry->id)
            ->whereNotNull('remark')
            ->where('remark', '!=', '')
            ->latest('started_at')
            ->get()
            ->map(function (CallLog $callLog) {
                return [
                    'note' => $callLog->remark,
                    'status' => optional($callLog->feedbackStatus)->display_name
                        ?: optional($callLog->feedbackStatus)->status_name
                        ?: '—',
                    'date' => optional($callLog->started_at)->format('d M Y, h:i A') ?: '—',
                ];
            });

        return response()->json(['success' => true, 'data' => $notes]);
    }

    public function customerCallStatus(CallLog $callLog)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_unless((int) $callLog->user_id === (int) auth()->id() && $callLog->call_management_entry_id, Response::HTTP_FORBIDDEN, 'You cannot view this call.');

        return response()->json([
            'success' => true,
            'data' => [
                'completed' => (bool) $callLog->completed_at,
                'duration' => (int) $callLog->duration,
                'status' => $callLog->plivo_status,
                'requires_feedback' => (bool) $callLog->completed_at && ! $callLog->feedback_status_id,
            ],
        ]);
    }

    public function saveCustomerCallFeedback(Request $request, CallLog $callLog)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_unless((int) $callLog->user_id === (int) auth()->id() && $callLog->call_management_entry_id, Response::HTTP_FORBIDDEN, 'You cannot update this call.');

        $validated = $request->validate([
            'feedback_status_id' => ['required', 'integer'],
            'message' => ['required', 'string', 'max:1000'],
            'follow_up_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);
        $status = Status::query()
            ->whereKey($validated['feedback_status_id'])
            ->where('module', Status::MODULE_CALL_MANAGEMENT_FEEDBACK)
            ->where('active', 'Y')
            ->firstOrFail();
        $feedbackOutcome = $this->callManagementFeedbackOutcome($status);
        $isFollowUp = $this->isFollowUpFeedback($status);

        if ($isFollowUp && empty($validated['follow_up_date'])) {
            throw ValidationException::withMessages([
                'follow_up_date' => 'Please select a follow-up date.',
            ]);
        }

        DB::transaction(function () use ($callLog, $status, $validated, $feedbackOutcome, $isFollowUp) {
            $callLog->update([
                'feedback_status_id' => $status->id,
                'remark' => trim($validated['message']),
            ]);

            $entryUpdates = [
                'follow_up_date' => $isFollowUp ? $validated['follow_up_date'] : null,
            ];
            if ($feedbackOutcome) $entryUpdates['status'] = $feedbackOutcome;

            CallManagementEntry::whereKey($callLog->call_management_entry_id)->update($entryUpdates);
        });

        return response()->json([
            'success' => true,
            'message' => 'Call record saved successfully.',
            'data' => [
                'queue_removed' => $feedbackOutcome !== null,
                'entry_status' => $feedbackOutcome ?: 'assigned',
                'follow_up_date' => $isFollowUp ? $validated['follow_up_date'] : null,
            ],
        ]);
    }

    private function callManagementFeedbackOutcome(Status $status): ?string
    {
        $labels = [$status->status_name, $status->display_name];

        foreach ($labels as $label) {
            $normalized = preg_replace('/[^a-z0-9]+/', '', strtolower((string) $label));

            if (in_array($normalized, ['complete', 'completed', 'callcomplete', 'callcompleted', 'done'], true)) {
                return 'completed';
            }
        }

        return null;
    }

    private function isFollowUpFeedback(?Status $status): bool
    {
        if (! $status) {
            return false;
        }

        foreach ([$status->status_name, $status->display_name] as $label) {
            $normalized = preg_replace('/[^a-z0-9]+/', '', strtolower((string) $label));

            if (str_contains($normalized, 'followup')) {
                return true;
            }
        }

        return false;
    }

    private function e164(?string $number): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);
        if (strlen($digits) === 10) $digits = '91'.$digits;

        return strlen($digits) >= 11 && strlen($digits) <= 15 ? '+'.$digits : null;
    }

    private function plivoWebhookUrl(string $configKey, string $path): string
    {
        return rtrim(config('services.plivo.'.$configKey) ?: url($path), '/');
    }

    private function ensurePlivoConfigured(): void
    {
        abort_unless(config('services.plivo.auth_id') && config('services.plivo.auth_token') && config('services.plivo.from_number'), 500, 'Plivo credentials are not configured on the server.');
    }

    public function store(Request $request)
    {
        abort_unless(
            Gate::allows('call_management_create'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden'
        );

        $callerIds = User::permission('call_management_access')
            ->where('active', 'Y')
            ->pluck('id');

        $validated = $request->validateWithBag('addCall', [
            'firm_name' => ['required', 'string', 'max:200'],
            'contact_person_name' => ['required', 'string', 'max:200'],
            'mobile_number' => ['required', 'digits:10'],
            'customer_type' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'pincode_id' => ['required', 'integer', 'exists:pincodes,id'],
            'assigned_user_id' => ['required', 'integer', Rule::in($callerIds->all())],
            'custom_column_1' => ['nullable', 'string', 'max:255'],
            'custom_column_2' => ['nullable', 'string', 'max:255'],
            'custom_column_3' => ['nullable', 'string', 'max:255'],
            'custom_column_4' => ['nullable', 'string', 'max:255'],
        ]);

        $pincode = Pincode::with(['cityname.districtname.statename', 'cityname.statename'])
            ->findOrFail($validated['pincode_id']);

        $city = $pincode->cityname;
        $district = optional($city)->districtname;
        $state = optional($district)->statename ?: optional($city)->statename;

        CallManagementEntry::create(array_merge($validated, [
            'pincode' => $pincode->pincode,
            'city' => optional($city)->city_name,
            'district' => optional($district)->district_name,
            'state' => optional($state)->state_name,
            'status' => 'assigned',
            'created_by' => auth()->id(),
        ]));

        return redirect()->route('customer-calling.index')
            ->with('message_success', 'Call entry added successfully.');
    }

    public function update(Request $request, CallManagementEntry $callManagementEntry)
    {
        abort_if(Gate::denies('call_management_edit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $callerIds = User::permission('call_management_access')
            ->where('active', 'Y')
            ->pluck('id');

        $validated = $request->validateWithBag('editCall', [
            'firm_name' => ['required', 'string', 'max:200'],
            'contact_person_name' => ['required', 'string', 'max:200'],
            'mobile_number' => ['required', 'digits:10'],
            'customer_type' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'pincode_id' => ['required', 'integer', 'exists:pincodes,id'],
            'assigned_user_id' => ['required', 'integer', Rule::in($callerIds->all())],
            'custom_column_1' => ['nullable', 'string', 'max:255'],
            'custom_column_2' => ['nullable', 'string', 'max:255'],
            'custom_column_3' => ['nullable', 'string', 'max:255'],
            'custom_column_4' => ['nullable', 'string', 'max:255'],
        ]);

        $pincode = Pincode::with(['cityname.districtname.statename', 'cityname.statename'])
            ->findOrFail($validated['pincode_id']);
        $city = $pincode->cityname;
        $district = optional($city)->districtname;
        $state = optional($district)->statename ?: optional($city)->statename;

        $callManagementEntry->update(array_merge($validated, [
            'pincode' => $pincode->pincode,
            'city' => optional($city)->city_name,
            'district' => optional($district)->district_name,
            'state' => optional($state)->state_name,
        ]));

        return redirect()->route('customer-calling.index')
            ->with('message_success', 'Call entry updated successfully.');
    }

    public function destroy(CallManagementEntry $callManagementEntry)
    {
        abort_if(Gate::denies('call_management_edit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $callManagementEntry->delete();

        return redirect()->back()->with('message_success', 'Call entry deleted successfully.');
    }

    public function import(Request $request)
    {
        abort_if(Gate::denies('call_management_import_export'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $redirectRoute = 'customer-calling.index';

        $request->validateWithBag('importCall', [
            'import_file' => [
                'bail',
                'required',
                'file',
                'max:10240',
                function ($attribute, $file, $fail) {
                    if (! in_array(strtolower($file->getClientOriginalExtension()), ['xlsx', 'xls', 'csv'], true)) {
                        $fail('Please select a valid XLSX, XLS or CSV file.');
                    }
                },
            ],
        ]);

        try {
            if (ob_get_contents()) {
                ob_end_clean();
            }
            ob_start();

            $import = new CallManagementEntryImport(auth()->id());
            Excel::import($import, $request->file('import_file'));

            if (($import->createdCount() + $import->updatedCount() + $import->skippedCount()) === 0) {
                throw ValidationException::withMessages([
                    'import_file' => 'No data rows found. Please use the exported Excel column headings.',
                ]);
            }

            $redirect = redirect()->route($redirectRoute)->with(
                'message_success',
                $import->createdCount().' call entries created and '
                .$import->updatedCount().' call entries updated successfully.'
            );

            if ($import->skippedCount()) {
                $redirect->with(
                    'message_error',
                    $import->skippedCount().' rows skipped. '.implode(' | ', array_slice($import->errors(), 0, 3))
                );
            }

            return $redirect;
        } catch (ValidationException $exception) {
            return redirect()->route($redirectRoute)->withErrors(
                $exception->errors(),
                'importCall'
            );
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route($redirectRoute)->withErrors([
                'import_file' => $exception->getMessage(),
            ], 'importCall');
        }
    }

    public function export()
    {
        abort_if(Gate::denies('call_management_import_export'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Excel::download(new CallManagementEntryExport, 'call-management-entries.xlsx');
    }
}
