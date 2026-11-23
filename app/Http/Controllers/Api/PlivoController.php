<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CallLog;
use App\Models\Lead;
use App\Services\LeadCallService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlivoController extends Controller
{
    private const RECORDING_MAX_LENGTH_SECONDS = 3600;

    public function makeCall(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => ['required', 'integer', 'exists:leads,id'],
            'to' => ['nullable', 'string', 'max:20'],
        ]);

        $user = $request->user();
        abort_unless($user->call_management, 403, 'Plivo calling is not enabled for this user.');
        $lead = Lead::with('contacts')->findOrFail($validated['lead_id']);
        $agentNumber = $this->e164($user->mobile);
        $customerNumber = $this->e164(optional($lead->contacts->first())->phone_number);
        // The app may choose the lead's alternate number (or another contact's
        // number). Only numbers saved on this lead can be dialled.
        if (!empty($validated['to'])) {
            $requested = $this->e164($validated['to']);
            $leadNumbers = $lead->contacts->pluck('phone_number')->push($lead->alternate_number)
                ->map(fn ($number) => $this->e164($number))->filter()->unique();
            if (!$requested || !$leadNumbers->contains($requested)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected number does not belong to this lead.',
                ], 422);
            }
            $customerNumber = $requested;
        }

        if (!$agentNumber || !$customerNumber) {
            return response()->json([
                'success' => false,
                'message' => 'Agent or lead contact has an invalid mobile number.',
            ], 422);
        }

        $service = app(LeadCallService::class);
        $callLog = $service->initiate(
            $lead,
            $user,
            $customerNumber,
            $service->contactForNumber($lead, $customerNumber),
            $user->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Call initiated. The agent phone will ring first.',
            'data' => ['call_log_id' => $callLog->id, 'call_uuid' => $callLog->plivo_call_uuid],
        ], 201);
    }

    public function answer(Request $request)
    {
        $callLog = $this->authorizedCallLog($request);
        $customerNumber = $this->e164($callLog->number);
        $query = http_build_query(['call_log_id' => $callLog->id, 'token' => $callLog->webhook_token]);
        $statusUrl = $this->webhookUrl('status_url', 'api/plivo/status').'?'.$query;
        $recordingUrl = $this->webhookUrl('recording_url', 'api/plivo/recording').'?'.$query;

        $callLog->update([
            'plivo_call_uuid' => $request->input('CallUUID', $callLog->plivo_call_uuid),
            'plivo_status' => 'agent-answered',
            'answered_at' => $callLog->answered_at ?: now(),
        ]);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<Response>'
            .'<Record startOnDialAnswer="true" redirect="false" maxLength="'.self::RECORDING_MAX_LENGTH_SECONDS.'" finishOnKey="none" action="'.e($recordingUrl).'" method="POST" callbackUrl="'.e($recordingUrl).'" callbackMethod="POST" />'
            .'<Dial callerId="'.e($callLog->lead_id ? $this->leadCallerId() : $this->fromNumber()).'" callbackUrl="'.e($statusUrl).'" callbackMethod="POST">'
            .'<Number>'.e($customerNumber).'</Number>'
            .'</Dial>'
            .'</Response>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function browserAnswer(Request $request)
    {
        $callLogId = $this->browserHeaderValue($request, 'calllogid');
        $token = $this->browserHeaderValue($request, 'calltoken');
        $callLog = CallLog::query()
            ->whereKey($callLogId)
            ->where('webhook_token', $token)
            ->whereNotNull('call_management_entry_id')
            ->firstOrFail();
        $customerNumber = $this->e164($callLog->number);
        abort_unless($customerNumber, 422, 'Invalid customer number.');

        $query = http_build_query(['call_log_id' => $callLog->id, 'token' => $callLog->webhook_token]);
        $statusUrl = $this->webhookUrl('status_url', 'api/plivo/status').'?'.$query;
        $recordingUrl = $this->webhookUrl('recording_url', 'api/plivo/recording').'?'.$query;

        $callLog->update([
            'plivo_call_uuid' => $request->input('CallUUID', $callLog->plivo_call_uuid),
            'plivo_status' => 'browser-connected',
        ]);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<Response>'
            .'<Record startOnDialAnswer="true" redirect="false" maxLength="'.self::RECORDING_MAX_LENGTH_SECONDS.'" finishOnKey="none" action="'.e($recordingUrl).'" method="POST" callbackUrl="'.e($recordingUrl).'" callbackMethod="POST" />'
            .'<Dial callerId="'.e($this->fromNumber()).'" callbackUrl="'.e($statusUrl).'" callbackMethod="POST">'
            .'<Number>'.e($customerNumber).'</Number>'
            .'</Dial>'
            .'</Response>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function status(Request $request)
    {
        $callLog = $this->authorizedCallLog($request);
        $dialAction = $request->input('DialAction');
        $status = $request->input('DialBLegStatus')
            ?: $request->input('CallStatus')
            ?: $request->input('Event')
            ?: $dialAction
            ?: 'updated';

        $updates = [
            'plivo_status' => strtolower((string) $status),
            'plivo_b_leg_uuid' => $request->input('DialBLegUUID', $callLog->plivo_b_leg_uuid),
        ];

        // Dial (B-leg) events are the second leg: the customer for agent-first
        // calls, the agent for inbound callbacks. Only when that leg answers did
        // the agent and the customer actually talk.
        $bLegStatus = strtolower((string) $request->input('DialBLegStatus', ''));
        $isDialEvent = $bLegStatus !== '' || $request->filled('DialAction');
        if ($isDialEvent) {
            $bLegAnswered = in_array(strtolower((string) $dialAction), ['answer'], true)
                || in_array($bLegStatus, ['answer', 'answered', 'in-progress'], true);
            // Talk time = real B-leg duration (not the billed, rounded-up value).
            $talkSeconds = $request->input('DialBLegDuration');
            if (is_numeric($talkSeconds)) {
                $updates['duration'] = (int) $talkSeconds;
                if ((int) $talkSeconds > 0) {
                    $bLegAnswered = true;
                }
            }
            if ($bLegAnswered && ! $callLog->connected_at) {
                $updates['connected_at'] = is_numeric($talkSeconds) && (int) $talkSeconds > 0
                    ? now()->subSeconds((int) $talkSeconds)
                    : now();
            }
        } elseif (! $callLog->plivo_b_leg_uuid && ! $callLog->connected_at && ! $request->filled('DialBLegUUID')) {
            // First-leg hangup before the second leg was ever dialled: nobody talked.
            $updates['duration'] = 0;
        }

        $cost = $request->input('TotalCost', $request->input('CallCost'));
        if (is_numeric($cost)) {
            $updates['cost'] = $cost;
        }

        $recordingUrl = $this->recordingUrlFromRequest($request);
        if ($recordingUrl) {
            $updates['recording_url'] = $recordingUrl;
            $updates['recording_id'] = $this->recordingIdFromRequest($request);
            $updates['status'] = 1;
        }

        // Inbound lead callbacks have no answer_url step, so record the agent pickup here.
        if (in_array(strtolower((string) $status), ['answer', 'answered', 'in-progress'], true) && ! $callLog->answered_at) {
            $updates['answered_at'] = now();
        }

        if (in_array(strtolower((string) $status), ['completed', 'hangup', 'failed', 'busy', 'no-answer', 'timeout', 'cancel'], true)) {
            $updates['completed_at'] = now();
        }

        $callLog->update($updates);

        return response('OK', 200);
    }

    public function callStatus(Request $request, CallLog $callLog)
    {
        abort_unless(
            (int) $callLog->user_id === (int) $request->user()->id || $request->user()->hasRole('superadmin'),
            403,
            'You cannot view this call.'
        );

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $callLog->id,
                'status' => $callLog->plivo_status,
                'answered' => (bool) $callLog->answered_at,
                'completed' => (bool) $callLog->completed_at,
                'duration' => (int) $callLog->duration,
                'requires_feedback' => (bool) $callLog->completed_at && empty($callLog->remark),
            ],
        ]);
    }

    public function recording(Request $request)
    {
        $callLog = $this->authorizedCallLog($request);
        $duration = $request->input('RecordingDuration');
        if (! is_numeric($duration) && is_numeric($request->input('RecordingDurationMs'))) {
            $duration = (int) round(((int) $request->input('RecordingDurationMs')) / 1000);
        }
        $recordingUrl = $this->recordingUrlFromRequest($request);

        if (! $recordingUrl) {
            Log::warning('Plivo recording callback received without a recording URL.', [
                'call_log_id' => $callLog->id,
                'recording_id' => $this->recordingIdFromRequest($request),
                'parameter_names' => array_keys($request->all()),
            ]);
        }

        $callLog->update(array_filter([
            'recording_url' => $recordingUrl,
            'recording_id' => $this->recordingIdFromRequest($request),
            // Keep call duration and recording duration independent. Plivo may
            // deliver their webhooks in either order during concurrent calls.
            'recording_duration' => is_numeric($duration) && (int) $duration >= 0 ? (int) $duration : null,
            'status' => $recordingUrl ? 1 : null,
            // The recording starts only when the second leg answers, so a
            // non-empty recording also proves the two sides talked.
            'connected_at' => ! $callLog->connected_at && is_numeric($duration) && (int) $duration > 0
                ? now()->subSeconds((int) $duration)
                : null,
        ], static fn ($value) => $value !== null && $value !== ''));

        return response('OK', 200);
    }

    private function recordingUrlFromRequest(Request $request): ?string
    {
        foreach (['RecordUrl', 'RecordingURL', 'RecordingUrl', 'recording_url', 'record_url'] as $key) {
            $url = trim((string) $request->input($key));
            if ($url !== '' && filter_var($url, FILTER_VALIDATE_URL)) {
                return $url;
            }
        }

        return null;
    }

    private function recordingIdFromRequest(Request $request): ?string
    {
        foreach (['RecordingID', 'RecordingUUID', 'RecordingId', 'recording_id'] as $key) {
            $id = trim((string) $request->input($key));
            if ($id !== '') {
                return $id;
            }
        }

        return null;
    }

    private function authorizedCallLog(Request $request): CallLog
    {
        return CallLog::whereKey($request->query('call_log_id'))
            ->where('webhook_token', $request->query('token'))
            ->firstOrFail();
    }

    private function browserHeaderValue(Request $request, string $expected): ?string
    {
        foreach ($request->all() as $key => $value) {
            $normalized = strtolower(preg_replace('/[^a-z0-9]+/i', '', (string) $key));
            if ($normalized === 'xph'.$expected) return is_scalar($value) ? (string) $value : null;
        }

        return null;
    }

    private function webhookUrl(string $configKey, string $path): string
    {
        return rtrim(config('services.plivo.'.$configKey) ?: url($path), '/');
    }

    private function e164(?string $number): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);
        if (strlen($digits) === 10) {
            $digits = '91'.$digits;
        }

        return strlen($digits) >= 11 && strlen($digits) <= 15 ? '+'.$digits : null;
    }

    private function leadCallerId(): ?string
    {
        return app(LeadCallService::class)->leadCallerId();
    }

    private function fromNumber(): ?string
    {
        return $this->e164(config('services.plivo.from_number'));
    }
}
