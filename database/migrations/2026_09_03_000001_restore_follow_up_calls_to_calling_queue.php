<?php

use App\Models\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $followUpStatusIds = DB::table('statuses')
            ->where('module', Status::MODULE_CALL_MANAGEMENT_FEEDBACK)
            ->get(['id', 'status_name', 'display_name'])
            ->filter(function ($status) {
                foreach ([$status->status_name, $status->display_name] as $label) {
                    $normalized = preg_replace('/[^a-z0-9]+/', '', strtolower((string) $label));

                    if (str_contains($normalized, 'followup')) {
                        return true;
                    }
                }

                return false;
            })
            ->pluck('id');

        if ($followUpStatusIds->isEmpty()) {
            return;
        }

        $entryIds = DB::table('call_logs')
            ->whereNotNull('call_management_entry_id')
            ->whereIn('feedback_status_id', $followUpStatusIds)
            ->pluck('call_management_entry_id')
            ->unique();

        DB::table('call_management_entries')
            ->whereIn('id', $entryIds)
            ->where('status', 'pending')
            ->update(['status' => 'assigned']);
    }

    public function down(): void
    {
        // This migration repairs records affected by the previous follow-up behaviour.
    }
};
