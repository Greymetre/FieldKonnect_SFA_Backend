<?php

namespace App\Services;

use App\Models\CallLog;
use App\Models\Lead;
use App\Models\LeadContact;
use App\Models\LeadLog;
use App\Models\LeadTask;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
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

    /**
     * Validate a post-call remark the same way on web and in the mobile app.
     *
     * Fields: call_status (call outcome, "Lead Call Feedback" status), status
     * (lead type, 0 = Pending), remark, details_via[] (for "Call Done":
     * whatsapp / linkedin / email / none) and follow_up_at (Y-m-d\TH:i, needed
     * for "Call Back" or when details must be sent).
     *
     * @return array{lead_type: int, call_status: ?Status, remark: string, channels: array, follow_up: ?\Carbon\Carbon}
     */
    public function validatedOutcome(Request $request, bool $requireCallStatus = true): array
    {
        $callStatusesExist = Status::where('module', Status::MODULE_LEAD_CALL_FEEDBACK)->where('active', 'Y')->exists();
        $validated = $request->validate([
            'call_status' => [$requireCallStatus && $callStatusesExist ? 'required' : 'nullable', 'integer'],
            'status' => ['required', 'integer', 'min:0'],
            'remark' => ['required', 'string', 'max:1000'],
        ], ['call_status.required' => 'Please select the call status.']);

        $leadType = (int) $validated['status'];
        if ($leadType !== 0 && ! Status::whereKey($leadType)->where('module', 'LeadStatus')->where('active', 'Y')->exists()) {
            throw ValidationException::withMessages(['status' => 'Please select a valid lead type.']);
        }

        $callStatus = ! empty($validated['call_status'])
            ? Status::whereKey((int) $validated['call_status'])->where('module', Status::MODULE_LEAD_CALL_FEEDBACK)->where('active', 'Y')->first()
            : null;
        if (! empty($validated['call_status']) && ! $callStatus) {
            throw ValidationException::withMessages(['call_status' => 'Please select a valid call status.']);
        }

        $isCallBack = $callStatus && $callStatus->status_name === Status::LEAD_CALL_BACK;
        $isCallDone = $callStatus && $callStatus->status_name === Status::LEAD_CALL_DONE;
        $channels = [];
        if ($isCallDone) {
            $request->validate(
                ['details_via' => ['required', 'array', 'min:1'], 'details_via.*' => ['in:whatsapp,linkedin,email,none']],
                ['details_via.required' => 'Choose where the customer wants details, or "No follow-up needed".']
            );
            $channels = array_values(array_diff(array_unique($request->details_via), ['none']));
        }

        $followUp = null;
        if ($isCallBack || $channels) {
            $request->validate(
                ['follow_up_at' => ['required', 'date_format:Y-m-d\\TH:i', 'after:now']],
                [
                    'follow_up_at.required' => $isCallBack ? 'Please choose the call back date and time.' : 'Please choose when to send the details.',
                    'follow_up_at.after' => 'The follow-up time must be in the future.',
                ]
            );
            $followUp = \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $request->follow_up_at);
        }

        return [
            'lead_type' => $leadType,
            'call_status' => $callStatus,
            'remark' => trim($validated['remark']),
            'channels' => $channels,
            'follow_up' => $followUp,
        ];
    }

    /**
     * Save a validated call outcome: call remark + lead type, the follow-up as a
     * task for the agent who made the call, and — when the contact was not
     * reached — the next not-yet-called contact of the lead to suggest.
     *
     * @param  array  $outcome  result of validatedOutcome()
     * @return array{remaining: int, contact_id: int, name: ?string, phone: string}|null
     */
    public function recordOutcome(CallLog $callLog, array $outcome, User $by): ?array
    {
        $callStatus = $outcome['call_status'];
        $channels = $outcome['channels'];
        $followUp = $outcome['follow_up'];

        $this->saveFeedback($callLog, $outcome['lead_type'], $outcome['remark'], $by, $callStatus?->id);
        $callLog->update(['follow_up_at' => $followUp, 'follow_up_channels' => $channels ?: null]);

        if ($followUp && $callLog->lead_id) {
            $contactName = $callLog->contact?->name ?: $callLog->number;
            $channelNames = collect($channels)->map(fn ($channel) => ['whatsapp' => 'WhatsApp', 'linkedin' => 'LinkedIn', 'email' => 'Email'][$channel])->implode(' & ');
            $isCallBack = $callStatus && $callStatus->status_name === Status::LEAD_CALL_BACK;
            $description = $isCallBack ? 'Call back '.$contactName : 'Send details on '.$channelNames.' to '.$contactName;
            $assignee = $callLog->user_id ?: $by->id;
            $task = LeadTask::create([
                'lead_id' => $callLog->lead_id,
                'assigned_to' => $assignee,
                'created_by' => $by->id,
                'description' => $description,
                'date' => $followUp->toDateString(),
                'time' => $followUp->format('H:i:s'),
                'priority' => 'high',
                'status' => 'pending',
                'remark' => Str::limit($outcome['remark'], 250),
            ]);
            if ((int) $assignee !== (int) $by->id) {
                $msg = '📝 '.$description.' on '.$followUp->format('d M, h:i A').'.';
                SendPushNotification($assignee, $msg, 'task');
                StoreLeadNotification($task->id, 'Follow-up Task', $msg, $assignee, 'task');
            }
        }

        // Not reached (switch off / no response / wrong number) and other contacts
        // still to call: suggest the next one.
        if (! $callLog->lead_id || ! $callStatus || ! in_array($callStatus->status_name, ['lead_call_no_response', 'lead_call_switch_off', 'lead_call_wrong_number'], true)) {
            return null;
        }
        $remaining = LeadContact::where('lead_id', $callLog->lead_id)
            ->whereNotNull('phone_number')->where('phone_number', '!=', '')
            ->whereDoesntHave('callLogs')
            ->orderBy('id')
            ->get(['id', 'name', 'phone_number']);
        if ($remaining->isEmpty()) {
            return null;
        }
        $first = $remaining->first();

        return [
            'remaining' => $remaining->count(),
            'contact_id' => $first->id,
            'name' => $first->name,
            'phone' => $first->phone_number,
        ];
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
     * Save the post-call remark and (optionally) move the lead to a new status.
     * Status 0 means "Pending". Older app builds may still send a lead call
     * feedback status, which is stored on the call but does not change the lead.
     */
    public function saveFeedback(CallLog $callLog, int $statusId, string $message, User $by, ?int $callStatusId = null): ?Status
    {
        $status = $statusId === 0 ? null : Status::query()
            ->whereKey($statusId)
            ->whereIn('module', ['LeadStatus', Status::MODULE_LEAD_CALL_FEEDBACK])
            ->where('active', 'Y')
            ->firstOrFail();
        $isLeadStatus = $statusId === 0 || $status->module === 'LeadStatus';

        // Web sends the call outcome ("Lead Call Feedback" status) separately from
        // the lead type; the call stores the outcome, the lead gets the type.
        $callStatus = $callStatusId ? Status::query()
            ->whereKey($callStatusId)
            ->where('module', Status::MODULE_LEAD_CALL_FEEDBACK)
            ->where('active', 'Y')
            ->firstOrFail() : null;

        DB::transaction(function () use ($callLog, $status, $callStatus, $statusId, $isLeadStatus, $message, $by) {
            $callLog->update([
                'feedback_status_id' => $callStatus?->id ?? $status?->id,
                'remark' => trim($message),
            ]);

            $lead = $isLeadStatus && $callLog->lead_id ? Lead::find($callLog->lead_id) : null;
            if ($lead && (int) $lead->status !== $statusId) {
                $oldStatus = Status::find($lead->status);
                LeadLog::create([
                    'lead_id' => $lead->id,
                    'message' => 'Lead move from '.($oldStatus->display_name ?? 'Pending').' to '.($status->display_name ?? 'Pending').' by '.$by->name,
                    'created_by' => $by->id,
                ]);
                $lead->update(['status' => $statusId]);
            }
        });

        return $status;
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
