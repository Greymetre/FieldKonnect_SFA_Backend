<?php

namespace App\Console\Commands;

use App\Models\CallLog;
use App\Models\ClientCallEvent;
use App\Models\ClientCallLog;
use App\Models\Lead;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * One-time cleanup: inbound callbacks from lead contacts that were logged in
 * Client Calling history before lead callback routing existed are moved to
 * the lead call history (call_logs).
 */
class MoveLeadInboundCalls extends Command
{
    protected $signature = 'calls:move-lead-inbound {--force : Move the calls. Without it the command only lists them.}';

    protected $description = 'Move inbound lead callbacks from Client Calling history to lead call history';

    public function handle(): int
    {
        $candidates = ClientCallLog::where('direction', 'inbound')->orderBy('id')->get()
            ->map(fn (ClientCallLog $call) => [$call, $this->leadFor($call)])
            ->filter(fn ($pair) => $pair[1] !== null);

        if ($candidates->isEmpty()) {
            $this->info('No inbound Client Calling records belong to leads.');
            return self::SUCCESS;
        }

        $this->table(
            ['Client call ID', 'Number', 'Started', 'Status', 'Lead ID', 'Lead', 'Agent ID'],
            $candidates->map(fn ($pair) => [
                $pair[0]->id, $pair[0]->customer_number, $pair[0]->started_at, $pair[0]->status,
                $pair[1]->id, $pair[1]->company_name, $pair[0]->assigned_user_id ?: $pair[1]->assign_to,
            ])->all()
        );

        if (! $this->option('force')) {
            $this->warn($candidates->count().' call(s) would be moved. Run again with --force to move them.');
            return self::SUCCESS;
        }

        $moved = 0;
        foreach ($candidates as [$call, $lead]) {
            DB::transaction(function () use ($call, $lead, &$moved) {
                if ($call->provider_call_uuid && CallLog::where('plivo_call_uuid', $call->provider_call_uuid)->exists()) {
                    return;
                }

                CallLog::create([
                    'lead_id' => $lead->id,
                    'direction' => 'inbound',
                    'user_id' => $call->assigned_user_id ?: $lead->assign_to,
                    'number' => $call->customer_number,
                    'started_at' => $call->started_at ?: $call->created_at,
                    'duration' => (int) $call->duration,
                    'recording_duration' => $call->recording_duration,
                    'status' => empty($call->recording_url) ? 0 : 1,
                    // Client Calling feedback statuses belong to a different status module, so only the note is kept.
                    'remark' => $call->remark,
                    'plivo_status' => $call->status,
                    'plivo_call_uuid' => $call->provider_call_uuid,
                    'plivo_b_leg_uuid' => $call->provider_agent_leg_uuid,
                    'recording_url' => $call->recording_url,
                    'recording_id' => $call->recording_id,
                    'transcription_status' => $call->transcription_status,
                    'transcript' => $call->transcript,
                    'diarized_transcript' => $call->diarized_transcript,
                    'sarvam_job_id' => $call->sarvam_job_id,
                    'transcription_error' => $call->transcription_error,
                    'cost' => $call->cost,
                    'answered_at' => $call->answered_at,
                    'completed_at' => $call->completed_at,
                    'webhook_token' => Str::random(64),
                ]);

                ClientCallEvent::where('client_call_log_id', $call->id)->delete();
                $call->delete();
                $moved++;
            });
        }

        $this->info("Moved {$moved} call(s) to lead call history.");

        return self::SUCCESS;
    }

    private function leadFor(ClientCallLog $call): ?Lead
    {
        $national = substr(preg_replace('/\D+/', '', (string) $call->customer_number), -10);
        if (strlen($national) !== 10) return null;

        $normalized = fn (string $column) => "RIGHT(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE({$column}, '+', ''), ' ', ''), '-', ''), '(', ''), ')', ''), 10) = ?";
        $lead = Lead::where(function ($query) use ($normalized, $national) {
            $query->whereHas('contacts', fn ($contacts) => $contacts->whereRaw($normalized('phone_number'), [$national]))
                ->orWhereRaw($normalized('alternate_number'), [$national]);
        })->orderByRaw('assign_to IS NULL')->latest('updated_at')->first();

        if (! $lead || ! $call->call_management_entry_id) return $lead;

        // The number is also a Client Calling entry: only move the call when a
        // lead call reached this customer more recently than a Client Calling call.
        $before = $call->started_at ?: $call->created_at;
        $lastLeadCall = CallLog::whereNotNull('lead_id')->whereNull('call_management_entry_id')
            ->where('direction', 'outbound')
            ->whereRaw($normalized('number'), [$national])
            ->where('started_at', '<=', $before)->max('started_at');
        if (! $lastLeadCall) return null;

        $lastClientCall = ClientCallLog::where('call_management_entry_id', $call->call_management_entry_id)
            ->where('direction', 'outbound')->where('started_at', '<=', $before)->max('started_at');

        return ! $lastClientCall || strtotime($lastLeadCall) >= strtotime($lastClientCall) ? $lead : null;
    }
}
