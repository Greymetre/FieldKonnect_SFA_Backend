<?php

namespace App\Http\Controllers;

use App\Exports\CallManagementEntryExport;
use App\Imports\CallManagementEntryImport;
use App\Models\CallManagementEntry;
use App\Models\CallLog;
use App\Models\Pincode;
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

    public function customerCalling()
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entries = CallManagementEntry::where('assigned_user_id', auth()->id())
            ->latest('id')
            ->get();

        return view('calls.customer-calling', compact('entries'));
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

            return response()->json(['success' => true, 'message' => 'Call initiated. Your phone will ring first.']);
        } catch (Throwable $exception) {
            report($exception);
            $callLog->update(['plivo_status' => 'failed']);
            return response()->json(['success' => false, 'message' => 'Unable to connect to Plivo.'], 502);
        }
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
