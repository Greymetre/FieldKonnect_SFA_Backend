<?php

namespace App\Http\Controllers;

use App\Models\CallManagementEntry;
use App\Models\ClientCallLog;
use App\Models\Pincode;
use App\Models\Status;
use App\Services\ClientCallingService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class ClientCallingController extends Controller
{
    public function initiate(CallManagementEntry $callManagementEntry, ClientCallingService $service)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user = auth()->user();
        abort_unless($user->call_management, Response::HTTP_FORBIDDEN, 'Calling is not enabled for this user.');
        abort_unless((int) $callManagementEntry->assigned_user_id === (int) $user->id, Response::HTTP_FORBIDDEN, 'This lead is not assigned to you.');
        abort_unless(
            $callManagementEntry->calling_type === CallManagementEntry::TYPE_CLIENT_CALLING,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            'This lead belongs to Customer Calling.'
        );

        $agentNumber = $service->number($user->mobile);
        $customerNumber = $service->number($callManagementEntry->mobile_number);
        if (! $agentNumber || ! $customerNumber) {
            return response()->json(['success' => false, 'message' => 'Agent or customer mobile number is invalid.'], 422);
        }

        $service->ensureConfigured();
        $call = ClientCallLog::create([
            'public_id' => (string) Str::uuid(),
            'call_management_entry_id' => $callManagementEntry->id,
            'assigned_user_id' => $user->id,
            'direction' => 'outbound',
            'customer_number' => $customerNumber,
            'agent_number' => $agentNumber,
            'virtual_number' => $service->configuredNumber(),
            'status' => 'initiating',
            'started_at' => now(),
            'webhook_token' => Str::random(64),
        ]);

        try {
            $provider = $service->initiateAgentCall($call);
            $uuid = data_get($provider, 'request_uuid.0') ?: data_get($provider, 'request_uuid');
            $call->update(['provider_call_uuid' => $uuid, 'status' => 'queued']);

            return response()->json([
                'success' => true,
                'message' => 'Client call initiated. Your phone will ring first.',
                'data' => $this->callData($callManagementEntry, $call, $user),
            ], 201);
        } catch (Throwable $exception) {
            report($exception);
            $call->update(['status' => 'failed', 'completed_at' => now(), 'hangup_cause' => 'provider_request_failed']);

            return response()->json(['success' => false, 'message' => 'Client Calling service is currently unavailable.'], 502);
        }
    }

    public function status(ClientCallLog $clientCallLog)
    {
        $this->authorizeCall($clientCallLog);

        return response()->json(['success' => true, 'data' => [
            'completed' => (bool) $clientCallLog->completed_at,
            'duration' => (int) $clientCallLog->duration,
            'status' => $clientCallLog->status,
            'requires_feedback' => (bool) $clientCallLog->completed_at && ! $clientCallLog->feedback_status_id,
        ]]);
    }

    public function feedback(Request $request, ClientCallLog $clientCallLog)
    {
        $this->authorizeCall($clientCallLog);
        $validated = $request->validate([
            'feedback_status_id' => ['required', 'integer'],
            'message' => ['required', 'string', 'max:1000'],
            'follow_up_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'pincode_id' => ['required', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:150'],
            'district' => ['nullable', 'string', 'max:150'],
            'state' => ['nullable', 'string', 'max:150'],
        ]);
        $status = Status::whereKey($validated['feedback_status_id'])
            ->where('module', Status::MODULE_CALL_MANAGEMENT_FEEDBACK)->where('active', 'Y')->firstOrFail();
        $feedbackOutcome = $this->feedbackOutcome($status);
        $isFollowUp = $this->isFollowUp($status);
        if ($isFollowUp && empty($validated['follow_up_date'])) {
            throw ValidationException::withMessages(['follow_up_date' => 'Please select a follow-up date.']);
        }
        $pincode = Pincode::where('active', 'Y')->where('pincode', trim($validated['pincode_id']))->first();
        if (! $pincode && ctype_digit((string) $validated['pincode_id'])) $pincode = Pincode::where('active', 'Y')->find((int) $validated['pincode_id']);
        if (! $pincode) throw ValidationException::withMessages(['pincode_id' => 'Please select a valid pincode.']);

        DB::transaction(function () use ($clientCallLog, $validated, $status, $pincode, $feedbackOutcome, $isFollowUp) {
            // Feedback is submitted from the post-call workspace. Treat it as a
            // terminal signal as well, so a delayed/missing provider hangup
            // callback cannot leave the agent falsely marked busy.
            $clientCallLog->update([
                'feedback_status_id' => $status->id,
                'remark' => trim($validated['message']),
                'completed_at' => $clientCallLog->completed_at ?: now(),
                'status' => $clientCallLog->completed_at ? $clientCallLog->status : 'completed',
            ]);
            $entryUpdates = [
                'follow_up_date' => $isFollowUp ? $validated['follow_up_date'] : null,
                'parent_name' => $validated['parent_name'] ?? null,
                'address' => $validated['address'] ?? null,
                'pincode_id' => $pincode->id, 'pincode' => $pincode->pincode,
                'city' => $validated['city'] ?? null, 'district' => $validated['district'] ?? null,
                'state' => $validated['state'] ?? null,
            ];
            if ($feedbackOutcome) $entryUpdates['status'] = $feedbackOutcome;
            CallManagementEntry::whereKey($clientCallLog->call_management_entry_id)->update($entryUpdates);
        });

        return response()->json(['success' => true, 'message' => 'Client call record saved successfully.', 'data' => ['queue_removed' => $feedbackOutcome !== null, 'entry_status' => $feedbackOutcome ?: 'assigned', 'follow_up_date' => $isFollowUp ? $validated['follow_up_date'] : null]]);
    }

    public function recording(ClientCallLog $clientCallLog)
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (! auth()->user()->hasRole('superadmin') && ! auth()->user()->hasRole('Admin')) {
            abort_unless((int) $clientCallLog->assigned_user_id === (int) auth()->id(), Response::HTTP_FORBIDDEN, 'You cannot access this recording.');
        }
        abort_if(empty($clientCallLog->recording_url), Response::HTTP_NOT_FOUND, 'Recording not available.');

        $headers = request()->hasHeader('Range') ? ['Range' => request()->header('Range')] : [];
        $recording = Http::withBasicAuth(
            config('services.client_calling.auth_id'),
            config('services.client_calling.auth_token')
        )->withHeaders($headers)->timeout(30)->get($clientCallLog->recording_url);
        abort_unless($recording->successful(), Response::HTTP_BAD_GATEWAY, 'Unable to load recording from Plivo.');

        return response($recording->body(), $recording->status(), array_filter([
            'Content-Type' => $recording->header('Content-Type') ?: 'audio/mpeg',
            'Content-Disposition' => 'inline; filename="client-call-'.$clientCallLog->id.'"',
            'Cache-Control' => 'private, max-age=3600',
            'Accept-Ranges' => $recording->header('Accept-Ranges') ?: 'bytes',
            'Content-Length' => $recording->header('Content-Length'),
            'Content-Range' => $recording->header('Content-Range'),
        ]));
    }

    private function authorizeCall(ClientCallLog $call): void
    {
        abort_if(Gate::denies('call_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_unless((int) $call->assigned_user_id === (int) auth()->id(), Response::HTTP_FORBIDDEN, 'You cannot access this call.');
    }

    private function callData(CallManagementEntry $entry, ClientCallLog $call, $user): array
    {
        return [
            'call_log_id' => $call->id,
            'status_url' => route('client-calling.status', $call),
            'feedback_url' => route('client-calling.feedback', $call),
            'customer_name' => $entry->contact_person_name ?: $entry->firm_name,
            'project_name' => $entry->project_name, 'project_id' => $entry->project_id,
            'parent_name' => $entry->parent_name, 'firm_name' => $entry->firm_name,
            'contact_person' => $entry->contact_person_name, 'mobile' => $entry->mobile_number,
            'customer_type' => $entry->customer_type, 'address' => $entry->address,
            'pincode_id' => $entry->pincode_id, 'pincode' => $entry->pincode,
            'city' => $entry->city, 'district' => $entry->district, 'state' => $entry->state,
            'assigned_to' => $user->name,
            'custom_column_1' => $entry->custom_column_1, 'custom_column_2' => $entry->custom_column_2,
            'custom_column_3' => $entry->custom_column_3, 'custom_column_4' => $entry->custom_column_4,
            'previous_notes' => [],
        ];
    }

    private function feedbackOutcome(Status $status): ?string
    {
        foreach ([$status->status_name, $status->display_name] as $label) {
            $normalized = preg_replace('/[^a-z0-9]+/', '', strtolower((string) $label));
            if (str_contains($normalized, 'notcomplete') || str_contains($normalized, 'incomplete') || str_contains($normalized, 'uncomplete')) continue;
            if (str_contains($normalized, 'complete') || in_array($normalized, ['done', 'calldone'], true)) return 'completed';
        }
        return null;
    }

    private function isFollowUp(Status $status): bool
    {
        foreach ([$status->status_name, $status->display_name] as $label) {
            if (str_contains(preg_replace('/[^a-z0-9]+/', '', strtolower((string) $label)), 'followup')) return true;
        }
        return false;
    }
}
