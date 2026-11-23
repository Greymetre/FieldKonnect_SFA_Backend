<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Lead call outcomes shown after a call: Call Done, No Response, Switch Off,
 * Wrong Number, Call Back (with a follow-up date/time). Other lead call
 * feedback statuses (e.g. Interested) are deactivated, not deleted, so past
 * calls keep their label.
 */
return new class extends Migration
{
    private const MODULE = 'CallFeedbackStatus';

    // status_name is unique across the whole statuses table, hence the prefix.
    private const OUTCOMES = [
        'lead_call_done' => 'Call Done',
        'lead_call_no_response' => 'No Response',
        'lead_call_switch_off' => 'Switch Off',
        'lead_call_wrong_number' => 'Wrong Number',
        'lead_call_back' => 'Call Back',
    ];

    public function up(): void
    {
        if (! Schema::hasColumn('call_logs', 'follow_up_at')) {
            Schema::table('call_logs', function (Blueprint $table) {
                $table->timestamp('follow_up_at')->nullable()->after('remark');
            });
        }

        foreach (self::OUTCOMES as $name => $label) {
            $existing = DB::table('statuses')->where('status_name', $name)->first();
            if ($existing) {
                DB::table('statuses')->where('id', $existing->id)->update([
                    'display_name' => $label,
                    'module' => self::MODULE,
                    'active' => 'Y',
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('statuses')->insert([
                    'status_name' => $name,
                    'display_name' => $label,
                    'status_message' => '',
                    'module' => self::MODULE,
                    'active' => 'Y',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        DB::table('statuses')
            ->where('module', self::MODULE)
            ->whereNotIn('status_name', array_keys(self::OUTCOMES))
            ->update(['active' => 'N', 'updated_at' => now()]);
    }

    public function down(): void
    {
        DB::table('statuses')->whereIn('status_name', array_keys(self::OUTCOMES))->update(['active' => 'N']);

        if (Schema::hasColumn('call_logs', 'follow_up_at')) {
            Schema::table('call_logs', function (Blueprint $table) {
                $table->dropColumn('follow_up_at');
            });
        }
    }
};
