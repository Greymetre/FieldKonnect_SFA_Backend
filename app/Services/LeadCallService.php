<?php

namespace App\Services;

use App\Models\CallLog;
use App\Models\Lead;
use App\Models\LeadContact;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Starts a lead click-to-call through Plivo: the agent's phone rings first,
 * and once answered Plivo's answer_url (PlivoController::answer) dials the
 * lead contact using the Client Calling inbound number as caller ID.
 */
class LeadCallService
{
    public function e164(?string $number): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);
        if (strlen($digits) === 10) {
            $digits = '91'.$digits;
        }

        return strlen($digits) >= 11 && strlen($digits) <= 15 ? '+'.$digits : null;
    }

    /** Find which saved contact of this lead owns a number (for history). */
    public function contactForNumber(Lead $lead, ?string $number): ?LeadContact
    {
        $target = $this->e164($number);
        if (! $target) {
            return null;
        }

        return $lead->contacts->first(fn (LeadContact $contact) => $this->e164($contact->phone_number) === $target);
    }

    /**
     * @throws HttpResponseException with a JSON error response on failure
     */
    public function initiate(Lead $lead, User $agent, string $customerNumber, ?LeadContact $contact, ?int $initiatedBy): CallLog
    {
        $agentNumber = $this->e164($agent->mobile);
        if (! $agentNumber) {
            $this->fail('The assigned user does not have a valid mobile number.', 422);
        }

        $this->ensureConfigured();

        $callLog = CallLog::create([
            'user_id' => $agent->id,
            'initiated_by' => $initiatedBy,
            'lead_id' => $lead->id,
            'lead_contact_id' => $contact?->id,
            'direction' => 'outbound',
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
                    'from' => $this->leadCallerId(),
                    'to' => $agentNumber,
                    'answer_url' => $this->webhookUrl('answer_url', 'api/plivo/answer').'?'.$query,
                    'answer_method' => 'POST',
                    'ring_url' => $this->webhookUrl('status_url', 'api/plivo/status').'?'.$query,
                    'ring_method' => 'POST',
                    'hangup_url' => $this->webhookUrl('status_url', 'api/plivo/status').'?'.$query,
                    'hangup_method' => 'POST',
                ]);
        } catch (Throwable $exception) {
            report($exception);
            $callLog->update(['plivo_status' => 'failed', 'completed_at' => now()]);
            $this->fail('Unable to connect to Plivo.', 502);
        }

        if (! $response->successful()) {
            $callLog->update(['plivo_status' => 'failed', 'completed_at' => now()]);
            $this->fail('Plivo rejected the call request.', 502, ['error' => $response->json()]);
        }

        $callUuid = $response->json('request_uuid.0') ?: $response->json('request_uuid');
        $callLog->update(['plivo_call_uuid' => $callUuid, 'plivo_status' => 'queued']);

        return $callLog;
    }

    /**
     * Lead calls show the Client Calling inbound number so customers can call
     * back and be routed to the lead's assigned agent.
     */
    public function leadCallerId(): ?string
    {
        $service = app(ClientCallingService::class);
        if (config('services.client_calling.enabled') && $service->configuredNumber()) {
            return $service->configuredNumber();
        }

        Log::warning('Client Calling number is not configured; lead call is using the legacy Plivo caller ID.');

        return $this->e164(config('services.plivo.from_number'));
    }

    private function ensureConfigured(): void
    {
        if (! config('services.plivo.auth_id') || ! config('services.plivo.auth_token') || ! $this->e164(config('services.plivo.from_number'))) {
            $this->fail('Plivo credentials or caller number are not configured correctly on the server.', 500);
        }

        foreach (['answer_url', 'status_url', 'recording_url'] as $configKey) {
            $url = $this->webhookUrl($configKey, 'api/plivo/'.str_replace('_url', '', $configKey));
            $host = parse_url($url, PHP_URL_HOST);

            if (! filter_var($url, FILTER_VALIDATE_URL)
                || parse_url($url, PHP_URL_SCHEME) !== 'https'
                || ! $host
                || in_array(strtolower($host), ['localhost', '127.0.0.1', '::1'], true)) {
                $this->fail('Plivo webhook URLs must use a public HTTPS domain.', 500);
            }
        }
    }

    private function webhookUrl(string $configKey, string $path): string
    {
        return rtrim(config('services.plivo.'.$configKey) ?: url($path), '/');
    }

    private function fail(string $message, int $status, array $extra = []): never
    {
        throw new HttpResponseException(response()->json(['success' => false, 'message' => $message] + $extra, $status));
    }
}
