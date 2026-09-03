<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $callDoneStatusId = DB::table('statuses')
            ->where('module', 'CallManagementFeedback')
            ->where('active', 'Y')
            ->where(function ($query) {
                $query->whereRaw("LOWER(REPLACE(COALESCE(status_name, ''), ' ', '')) IN (?, ?, ?)", ['calldone', 'complete', 'completed'])
                    ->orWhereRaw("LOWER(REPLACE(COALESCE(display_name, ''), ' ', '')) IN (?, ?, ?)", ['calldone', 'complete', 'completed']);
            })
            ->value('id');

        if (! $callDoneStatusId) {
            return;
        }

        $callLog = DB::table('call_logs')
            ->join('call_management_entries', 'call_management_entries.id', '=', 'call_logs.call_management_entry_id')
            ->join('users', 'users.id', '=', 'call_logs.user_id')
            ->where('call_management_entries.mobile_number', '9460155312')
            ->where('users.name', 'Renu Sisodiya')
            ->whereBetween('call_logs.started_at', ['2026-09-03 16:38:00', '2026-09-03 16:48:59'])
            ->where('call_logs.duration', 120)
            ->whereNull('call_logs.feedback_status_id')
            ->select('call_logs.id', 'call_logs.call_management_entry_id')
            ->latest('call_logs.started_at')
            ->first();

        if (! $callLog) {
            return;
        }

        DB::transaction(function () use ($callLog, $callDoneStatusId) {
            DB::table('call_logs')->where('id', $callLog->id)->update([
                'feedback_status_id' => $callDoneStatusId,
                'remark' => "1.Scheme-ok\n2.Material- no issue\n3.Dealer Name-jodhpur auto sales\n4.Sales Grow Feadback -other company se apne product saste kijiye",
                'updated_at' => now(),
            ]);

            DB::table('call_management_entries')
                ->where('id', $callLog->call_management_entry_id)
                ->update([
                    'status' => 'completed',
                    'follow_up_date' => null,
                    'updated_at' => now(),
                ]);
        });
    }

    public function down(): void
    {
        // Keep repaired production call feedback intact during rollbacks.
    }
};
