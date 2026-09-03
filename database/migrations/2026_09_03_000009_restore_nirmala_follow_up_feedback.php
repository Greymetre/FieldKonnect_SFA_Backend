<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $followUpStatusId = DB::table('statuses')
            ->where('module', 'CallManagementFeedback')
            ->where('active', 'Y')
            ->where(function ($query) {
                $query->whereRaw("LOWER(REPLACE(COALESCE(status_name, ''), ' ', '')) LIKE ?", ['%followup%'])
                    ->orWhereRaw("LOWER(REPLACE(COALESCE(display_name, ''), ' ', '')) LIKE ?", ['%followup%']);
            })
            ->value('id');

        if (! $followUpStatusId) {
            return;
        }

        $callLog = DB::table('call_logs')
            ->join('call_management_entries', 'call_management_entries.id', '=', 'call_logs.call_management_entry_id')
            ->join('users', 'users.id', '=', 'call_logs.user_id')
            ->where('call_management_entries.mobile_number', '9420175521')
            ->where('users.name', 'Nirmala Prajapati')
            ->whereBetween('call_logs.started_at', ['2026-09-03 16:25:00', '2026-09-03 16:35:59'])
            ->where('call_logs.duration', 180)
            ->whereNull('call_logs.feedback_status_id')
            ->select('call_logs.id', 'call_logs.call_management_entry_id')
            ->latest('call_logs.started_at')
            ->first();

        if (! $callLog) {
            return;
        }

        DB::transaction(function () use ($callLog, $followUpStatusId) {
            DB::table('call_logs')->where('id', $callLog->id)->update([
                'feedback_status_id' => $followUpStatusId,
                'remark' => "1.Scheme- Ok\n2.Material- No Issue\n3.Dealer Name- V-Sons\n4.Sales Grow Feedback Pahle Yah Distributors The Enko Peyment Nahi",
                'updated_at' => now(),
            ]);

            DB::table('call_management_entries')
                ->where('id', $callLog->call_management_entry_id)
                ->update([
                    'status' => 'assigned',
                    'follow_up_date' => '2026-09-05',
                    'updated_at' => now(),
                ]);
        });
    }

    public function down(): void
    {
        // This migration repairs a production business record and must not
        // erase agent feedback during an application rollback.
    }
};
