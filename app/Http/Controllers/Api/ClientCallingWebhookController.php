<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CallManagementEntry;
use App\Models\ClientCallEvent;
use App\Models\ClientCallLog;
use App\Services\ClientCallingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientCallingWebhookController extends Controller
{
    public function inbound(Request $request, ClientCallingService $service)
    {
        $this->validateRequest($request, $service);
        $service->ensureConfigured();
        $customerNumber = $service->number($request->input('From'));
        $calledNumber = $service->number($request->input('To'));
        abort_unless($customerNumber && hash_equals($service->configuredNumber(), (string) $calledNumber), 404);

        $entry = $this->entryForCustomer($customerNumber);
        $agent = $entry?->assignedUser;
        $agentNumber = $service->number($agent?->mobile);
        $providerUuid = trim((string) $request->input('CallUUID')) ?: null;
        abort_unless($providerUuid, 422, 'CallUUID is required.');

        $call = ClientCallLog::firstOrCreate(
            ['provider_call_uuid' => $providerUuid],
            [
                'public_id' => (string) Str::uuid(),
                'call_management_entry_id' => $entry?->id,
                'assigned_user_id' => $agent?->id,
                'direction' => 'inbound',
                'customer_number' => $customerNumber,
                'agent_number' => $agentNumber,
                'virtual_number' => $service->configuredNumber(),
                'status' => 'received',
                'started_at' => now(),
                'webhook_token' => Str::random(64),
            ]
        );
        $this->event($call, 'inbound', $request);

        if (! $entry) return $this->missed($call, 'unknown_missed', 'We could not find your assigned representative. Please try again later.', $service);
        if (! $agent) return $this->missed($call, 'unassigned_missed', 'No representative is assigned. Please try again later.', $service);

        // Do not infer physical-phone availability from database call state.
        // Provider callbacks can be delayed or missed and would falsely block
        // later callbacks. Always dial the one assigned agent; Plivo/the mobile
        // network will report the real busy or no-answer outcome.
        if ($agent->active !== 'Y' || ! $agent->call_management || ! $agentNumber) {
            return $this->missed($call, 'agent_unavailable', 'Your representative is unavailable. Please try again later.', $service);
        }

        $query = http_build_query(['client_call_id' => $call->id, 'token' => $call->webhook_token]);
        $statusUrl = $service->url('status_url', 'api/client-calling/webhooks/status').'?'.$query;
        $recordingUrl = $service->url('recording_url', 'api/client-calling/webhooks/recording').'?'.$query;
        $call->update(['status' => 'agent_ringing', 'ringing_at' => now()]);

        $record = config('services.client_calling.recording_enabled')
            ? '<Record startOnDialAnswer="true" redirect="false" maxLength="3600" finishOnKey="none" action="'.e($recordingUrl).'" method="POST" callbackUrl="'.e($recordingUrl).'" callbackMethod="POST" />'
            : '';
        $dial = '<Dial callerId="'.e($service->configuredNumber()).'" timeout="'.(int) config('services.client_calling.ring_timeout', 30).'" callbackUrl="'.e($statusUrl).'" callbackMethod="POST"><Number>'.e($agentNumber).'</Number></Dial>';

        return $service->xml('<Speak>Please wait while we connect you to your representative.</Speak>'.$record.$dial);
    }

    public function outboundAnswer(Request $request, ClientCallingService $service)
    {
        $this->validateRequest($request, $service);
        $call = $this->tokenCall($request);
        abort_unless($call->direction === 'outbound', 404);
        $this->event($call, 'outbound_answer', $request);
        $call->update([
            'provider_call_uuid' => $request->input('CallUUID', $call->provider_call_uuid),
            'status' => 'agent_answered',
        ]);

        $query = http_build_query(['client_call_id' => $call->id, 'token' => $call->webhook_token]);
        $statusUrl = $service->url('status_url', 'api/client-calling/webhooks/status').'?'.$query;
        $recordingUrl = $service->url('recording_url', 'api/client-calling/webhooks/recording').'?'.$query;
        $record = config('services.client_calling.recording_enabled')
            ? '<Record startOnDialAnswer="true" redirect="false" maxLength="3600" finishOnKey="none" action="'.e($recordingUrl).'" method="POST" callbackUrl="'.e($recordingUrl).'" callbackMethod="POST" />'
            : '';

        return $service->xml($record.'<Dial callerId="'.e($service->configuredNumber()).'" timeout="'.(int) config('services.client_calling.ring_timeout', 30).'" callbackUrl="'.e($statusUrl).'" callbackMethod="POST"><Number>'.e($call->customer_number).'</Number></Dial>');
    }

    public function status(Request $request, ClientCallingService $service)
    {
        $this->validateRequest($request, $service);
        $call = $this->findCall($request);
        $this->event($call, 'status', $request);
        $rawStatus = strtolower((string) ($request->input('DialBLegStatus') ?: $request->input('CallStatus') ?: $request->input('DialAction') ?: $request->input('Event') ?: 'updated'));
        $updates = ['status' => str_replace(' ', '_', $rawStatus)];
        $bLeg = $request->input('DialBLegUUID');
        if ($bLeg) $updates[$call->direction === 'inbound' ? 'provider_agent_leg_uuid' : 'provider_customer_leg_uuid'] = $bLeg;

        $duration = $request->input('DialBLegBillDuration', $request->input('BillDuration', $request->input('Duration')));
        if (is_numeric($duration)) $updates['duration'] = max((int) $call->duration, (int) $duration);
        if (in_array($rawStatus, ['answer', 'answered', 'in-progress', 'completed'], true) && ! $call->answered_at) $updates['answered_at'] = now();
        if (in_array($rawStatus, ['completed', 'busy', 'no-answer', 'no_answer', 'failed', 'cancel', 'cancelled', 'timeout', 'rejected', 'hangup'], true)) {
            $updates['completed_at'] = $call->completed_at ?: now();
            $updates['hangup_cause'] = $request->input('HangupCauseName', $request->input('HangupCause'));
            if (! $call->answered_at && $call->direction === 'inbound') $updates['status'] = $rawStatus === 'completed' ? 'missed' : 'missed_'.$updates['status'];
        }
        $call->update($updates);

        return response('OK', 200);
    }

    public function recording(Request $request, ClientCallingService $service)
    {
        $this->validateRequest($request, $service);
        $call = $this->findCall($request);
        $this->event($call, 'recording', $request);
        $url = $request->input('RecordUrl', $request->input('RecordingURL', $request->input('RecordingUrl')));
        $duration = $request->input('RecordingDuration');
        $call->update(array_filter([
            'recording_url' => filter_var($url, FILTER_VALIDATE_URL) ? $url : null,
            'recording_id' => $request->input('RecordingID', $request->input('RecordingUUID')),
            'recording_duration' => is_numeric($duration) ? (int) $duration : null,
        ], fn ($value) => $value !== null && $value !== ''));

        return response('OK', 200);
    }

    public function fallback(Request $request, ClientCallingService $service)
    {
        $this->validateRequest($request, $service);
        if ($call = $this->findCall($request, false)) {
            $this->event($call, 'fallback', $request);
            $call->update(['status' => 'failed', 'completed_at' => now(), 'hangup_cause' => 'answer_url_failed']);
        }

        return $service->xml('<Speak>We cannot connect your call right now. Please try again later.</Speak><Hangup />');
    }

    private function entryForCustomer(string $number): ?CallManagementEntry
    {
        $digits = preg_replace('/\D+/', '', $number);
        $national = substr($digits, -10);
        return CallManagementEntry::with('assignedUser')
            ->whereRaw("RIGHT(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(mobile_number, '+', ''), ' ', ''), '-', ''), '(', ''), ')', ''), 10) = ?", [$national])
            ->latest('updated_at')->first();
    }

    private function missed(ClientCallLog $call, string $status, string $message, ClientCallingService $service)
    {
        $call->update(['status' => $status, 'completed_at' => now(), 'hangup_cause' => $status]);
        return $service->xml('<Speak>'.e($message).'</Speak><Hangup />');
    }

    private function tokenCall(Request $request): ClientCallLog
    {
        return ClientCallLog::whereKey($request->query('client_call_id'))->where('webhook_token', $request->query('token'))->firstOrFail();
    }

    private function findCall(Request $request, bool $fail = true): ?ClientCallLog
    {
        if ($request->query('client_call_id') && $request->query('token')) return $this->tokenCall($request);
        $uuid = $request->input('CallUUID');
        $call = ClientCallLog::where('provider_call_uuid', $uuid)
            ->orWhere('provider_agent_leg_uuid', $uuid)->orWhere('provider_customer_leg_uuid', $uuid)->first();
        if (! $call && $fail) abort(404);
        return $call;
    }

    private function validateRequest(Request $request, ClientCallingService $service): void
    {
        // Tokened callbacks are bound to one call. This also avoids false V3
        // signature failures when a hosting proxy changes HTTPS to HTTP before
        // Laravel sees the request. Untokened inbound calls still require V3.
        if ($request->query('client_call_id') && $request->query('token')) {
            $tokenMatches = ClientCallLog::whereKey($request->query('client_call_id'))
                ->where('webhook_token', $request->query('token'))
                ->exists();
            if ($tokenMatches) return;
        }

        abort_unless($service->validateWebhook($request), 403, 'Invalid Plivo webhook signature.');
    }

    private function event(ClientCallLog $call, string $type, Request $request): void
    {
        ClientCallEvent::create([
            'client_call_log_id' => $call->id,
            'provider_event_id' => $request->input('RecordingID', $request->input('CallUUID')),
            'event_type' => $type,
            'payload' => $request->all(),
            'received_at' => now(),
            'processed_at' => now(),
        ]);
    }
}
