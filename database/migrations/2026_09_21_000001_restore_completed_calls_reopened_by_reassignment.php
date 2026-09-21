<?php

use App\Models\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $completedStatusIds = DB::table('statuses')
            ->where('module', Status::MODULE_CALL_MANAGEMENT_FEEDBACK)
            ->get(['id', 'status_name', 'display_name'])
            ->filter(function ($status) {
                foreach ([$status->status_name, $status->display_name] as $label) {
                    $normalized = preg_replace('/[^a-z0-9]+/', '', strtolower((string) $label));

                    if (
                        str_contains($normalized, 'notcomplete')
                        || str_contains($normalized, 'incomplete')
                        || str_contains($normalized, 'uncomplete')
                    ) {
                        continue;
                    }

                    if (str_contains($normalized, 'complete') || in_array($normalized, ['done', 'calldone'], true)) {
                        return true;
                    }
                }

                return false;
            })
            ->pluck('id');

        if ($completedStatusIds->isEmpty()) {
            return;
        }

        // An import that reassigned calls to another agent reset completed
        // entries to "assigned". Restore every open entry whose latest call
        // feedback is a completed outcome.
        $entryIds = collect([
            'customer_calling' => 'call_logs',
            'client_calling' => 'client_call_logs',
        ])->flatMap(function ($logTable, $callingType) use ($completedStatusIds) {
            $latestFeedbackLogs = DB::table($logTable)
                ->selectRaw('call_management_entry_id, MAX(id) as latest_id')
                ->whereNotNull('call_management_entry_id')
                ->whereNotNull('feedback_status_id')
                ->groupBy('call_management_entry_id');

            return DB::table('call_management_entries')
                ->joinSub($latestFeedbackLogs, 'latest_feedback', function ($join) {
                    $join->on('latest_feedback.call_management_entry_id', '=', 'call_management_entries.id');
                })
                ->join($logTable.' as feedback_log', 'feedback_log.id', '=', 'latest_feedback.latest_id')
                ->where('call_management_entries.status', 'assigned')
                ->where('call_management_entries.calling_type', $callingType)
                ->whereIn('feedback_log.feedback_status_id', $completedStatusIds)
                ->pluck('call_management_entries.id');
        })->unique()->values();

        if ($entryIds->isEmpty()) {
            return;
        }

        DB::table('call_management_entries')
            ->whereIn('id', $entryIds)
            ->update([
                'status' => 'completed',
                'follow_up_date' => null,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Keep restored completed calls intact during rollbacks.
    }
};
