<?php

namespace App\Http\Controllers;

use App\Exports\CallManagementEntryExport;
use App\Imports\CallManagementEntryImport;
use App\Models\CallManagementEntry;
use App\Models\CallLog;
use App\Models\Pincode;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    public function index()
    {
        abort_if(
            Gate::denies('call_management_access'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden'
        );

        $entries = CallManagementEntry::with('assignedUser:id,name')
            ->where('status', 'pending')
            ->latest('id')
            ->get();

        $pincodes = Pincode::with([
                'cityname:id,city_name,district_id,state_id',
                'cityname.districtname:id,district_name,state_id',
                'cityname.districtname.statename:id,state_name',
                'cityname.statename:id,state_name',
            ])
            ->where('active', 'Y')
            ->orderByDesc('id')
            ->get();

        $pincodeOptions = $pincodes->map(function ($pincode) {
            $city = $pincode->cityname;
            $district = optional($city)->districtname;
            $state = optional($district)->statename ?: optional($city)->statename;

            return [
                'id' => $pincode->id,
                'pincode' => $pincode->pincode,
                'city' => optional($city)->city_name ?: '',
                'district' => optional($district)->district_name ?: '',
                'state' => optional($state)->state_name ?: '',
            ];
        })->values();

        $callers = User::permission('call_management_access')
            ->where('active', 'Y')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('calls.index', compact('entries', 'pincodeOptions', 'callers'));
    }

    public function customerCalling(Request $request)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = CallManagementEntry::query()
            ->where('assigned_user_id', auth()->id())
            ->where('status', 'assigned');

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('firm_name', 'like', '%'.$search.'%')
                    ->orWhere('contact_person_name', 'like', '%'.$search.'%')
                    ->orWhere('mobile_number', 'like', '%'.$search.'%');
            });
        }

        if ($request->input('status') === 'assigned') {
            $query->where('status', 'assigned');
        }

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($fromDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)) {
            $query->whereDate('updated_at', '>=', $fromDate);
        }

        if ($toDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)) {
            $query->whereDate('updated_at', '<=', $toDate);
        }

        $entries = $query
            ->latest('id')
            ->get();
        $feedbackStatuses = Status::query()
            ->where('module', Status::MODULE_CALL_MANAGEMENT_FEEDBACK)
            ->where('active', 'Y')
            ->orderBy('id')
            ->get(['id', 'status_name', 'display_name']);

        return view('calls.customer-calling', compact('entries', 'feedbackStatuses'));
    }

    public function customerCallHistory()
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = CallLog::with(['user:id,name', 'feedbackStatus:id,status_name,display_name', 'callManagementEntry:id,firm_name,contact_person_name,mobile_number'])
            ->whereNotNull('call_management_entry_id');

        if (! auth()->user()->hasRole('superadmin') && ! auth()->user()->hasRole('Admin')) {
            $query->whereIn('user_id', getUsersReportingToAuth());
        }

        $callLogs = $query->latest('started_at')->get();

        return view('calls.history', compact('callLogs'));
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

        try {
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

            return response()->json([
                'success' => true,
                'message' => 'Call initiated. Your phone will ring first.',
                'data' => [
                    'call_log_id' => $callLog->id,
                    'status_url' => route('customer-calling.call-status', $callLog),
                    'feedback_url' => route('customer-calling.call-feedback', $callLog),
                    'customer_name' => $callManagementEntry->contact_person_name ?: $callManagementEntry->firm_name,
                ],
            ]);
        } catch (Throwable $exception) {
            report($exception);
            $callLog->update(['plivo_status' => 'failed']);
            return response()->json(['success' => false, 'message' => 'Unable to connect to Plivo.'], 502);
        }
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
        ]);
        $status = Status::query()
            ->whereKey($validated['feedback_status_id'])
            ->where('module', Status::MODULE_CALL_MANAGEMENT_FEEDBACK)
            ->where('active', 'Y')
            ->firstOrFail();
        $feedbackOutcome = $this->callManagementFeedbackOutcome($status);

        DB::transaction(function () use ($callLog, $status, $validated, $feedbackOutcome) {
            $callLog->update([
                'feedback_status_id' => $status->id,
                'remark' => trim($validated['message']),
            ]);

            if ($feedbackOutcome) {
                CallManagementEntry::whereKey($callLog->call_management_entry_id)->update([
                    'status' => $feedbackOutcome,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Call record saved successfully.',
            'data' => [
                'queue_removed' => $feedbackOutcome !== null,
                'entry_status' => $feedbackOutcome ?: 'assigned',
            ],
        ]);
    }

    private function callManagementFeedbackOutcome(Status $status): ?string
    {
        $labels = [$status->status_name, $status->display_name];

        foreach ($labels as $label) {
            $normalized = preg_replace('/[^a-z0-9]+/', '', strtolower((string) $label));

            if (str_contains($normalized, 'followup')) {
                return 'pending';
            }

            if (in_array($normalized, ['complete', 'completed', 'callcomplete', 'callcompleted', 'done'], true)) {
                return 'completed';
            }
        }

        return null;
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
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]));

        return redirect()->route('calls.index')->with('message_success', 'Call entry added successfully.');
    }

    public function bulkAssign(Request $request)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $callerIds = User::permission('call_management_access')
            ->where('active', 'Y')
            ->pluck('id')
            ->all();

        $validated = $request->validateWithBag('bulkAssign', [
            'entry_ids' => ['required', 'array', 'min:1'],
            'entry_ids.*' => ['required', 'integer', 'distinct', 'exists:call_management_entries,id'],
            'bulk_assigned_user_id' => ['required', 'integer', Rule::in($callerIds)],
            'overrides' => ['nullable', 'array'],
            'overrides.*' => ['nullable', 'integer', Rule::in($callerIds)],
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['entry_ids'] as $entryId) {
                $override = $validated['overrides'][$entryId] ?? null;

                CallManagementEntry::whereKey($entryId)->update([
                    'assigned_user_id' => $override ?: $validated['bulk_assigned_user_id'],
                    'status' => 'assigned',
                ]);
            }
        });

        return redirect()->route('calls.index')->with(
            'message_success',
            count($validated['entry_ids']).' call entries assigned successfully.'
        );
    }

    public function update(Request $request, CallManagementEntry $callManagementEntry)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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

        return redirect()->route('calls.index')->with('message_success', 'Call entry updated successfully.');
    }

    public function destroy(CallManagementEntry $callManagementEntry)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $callManagementEntry->delete();

        return redirect()->route('calls.index')->with('message_success', 'Call entry deleted successfully.');
    }

    public function import(Request $request)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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

            $redirect = redirect()->route('calls.index')->with(
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
            return redirect()->route('calls.index')->withErrors(
                $exception->errors(),
                'importCall'
            );
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('calls.index')->withErrors([
                'import_file' => $exception->getMessage(),
            ], 'importCall');
        }
    }

    public function export()
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Excel::download(new CallManagementEntryExport, 'call-management-entries.xlsx');
    }
}
