<?php

namespace App\Services;

use App\Models\ClientCallLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClientCallingService
{
    public function number(?string $number): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);
        if (strlen($digits) === 10) $digits = '91'.$digits;

        return strlen($digits) >= 11 && strlen($digits) <= 15 ? '+'.$digits : null;
    }

    public function configuredNumber(): ?string
    {
        return $this->number(config('services.client_calling.number'));
    }

    public function ensureConfigured(): void
    {
        abort_unless(
            config('services.client_calling.enabled')
                && config('services.client_calling.auth_id')
                && config('services.client_calling.auth_token')
                && $this->configuredNumber(),
            500,
            'Client Calling Plivo configuration is incomplete.'
        );
    }

    public function initiateAgentCall(ClientCallLog $call): array
    {
        $query = http_build_query(['client_call_id' => $call->id, 'token' => $call->webhook_token]);
        $answerUrl = $this->url('outbound_answer_url', 'api/client-calling/webhooks/outbound-answer').'?'.$query;
        $statusUrl = $this->url('status_url', 'api/client-calling/webhooks/status').'?'.$query;

        $response = Http::withBasicAuth(
            config('services.client_calling.auth_id'),
            config('services.client_calling.auth_token')
        )->acceptJson()->asJson()->connectTimeout(5)->timeout(20)
            ->post('https://api.plivo.com/v1/Account/'.config('services.client_calling.auth_id').'/Call/', [
                'from' => $this->configuredNumber(),
                'to' => $call->agent_number,
                'answer_url' => $answerUrl,
                'answer_method' => 'POST',
                'ring_url' => $statusUrl,
                'ring_method' => 'POST',
                'hangup_url' => $statusUrl,
                'hangup_method' => 'POST',
            ]);

        abort_unless($response->successful(), 502, 'Plivo rejected the Client Calling request.');

        return $response->json();
    }

    public function validateWebhook(Request $request): bool
    {
        if (! config('services.client_calling.validate_signature', true)) return true;

        $signatureHeader = trim((string) $request->header('X-Plivo-Signature-V3'));
        $nonce = trim((string) $request->header('X-Plivo-Signature-V3-Nonce'));
        if ($signatureHeader === '' || $nonce === '') return false;

        $parameters = $request->isMethod('post') ? $request->post() : [];
        ksort($parameters, SORT_STRING);

        $payload = '';
        foreach ($parameters as $key => $value) {
            if (is_scalar($value)) $payload .= $key.$value;
        }

        $signatures = array_map('trim', explode(',', $signatureHeader));
        foreach ($this->signatureUrlCandidates($request) as $url) {
            $expected = base64_encode(hash_hmac(
                'sha256', $url.$payload.$nonce, (string) config('services.client_calling.auth_token'), true
            ));

            foreach ($signatures as $signature) {
                if (hash_equals($expected, $signature)) return true;
            }
        }

        return false;
    }

    private function signatureUrlCandidates(Request $request): array
    {
        $candidates = [$request->fullUrl()];

        // Cloudflare/shared-hosting proxies can terminate HTTPS before the
        // request reaches Laravel. Plivo signs the public HTTPS URL, not the
        // internal URL observed by PHP, so also verify against the explicitly
        // configured webhook URL for this route.
        $requestPath = '/'.ltrim($request->path(), '/');
        $query = $request->getQueryString();
        foreach ([
            'inbound_url',
            'outbound_answer_url',
            'status_url',
            'recording_url',
            'fallback_url',
        ] as $key) {
            $configuredUrl = trim((string) config('services.client_calling.'.$key));
            if ($configuredUrl === '') continue;
            if ('/'.ltrim((string) parse_url($configuredUrl, PHP_URL_PATH), '/') !== $requestPath) continue;

            $configuredUrl = strtok($configuredUrl, '?');
            $candidates[] = $query ? $configuredUrl.'?'.$query : $configuredUrl;
        }

        return array_values(array_unique($candidates));
    }

    public function url(string $key, string $fallback): string
    {
        return rtrim(config('services.client_calling.'.$key) ?: url($fallback), '/');
    }

    public function xml(string $body)
    {
        return response('<?xml version="1.0" encoding="UTF-8"?><Response>'.$body.'</Response>', 200)
            ->header('Content-Type', 'application/xml');
    }
}
