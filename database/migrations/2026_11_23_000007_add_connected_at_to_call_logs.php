<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * connected_at: when the agent and the customer were actually connected (both
 * call legs answered). Until now a call counted as "connected" as soon as
 * Plivo sent a recording URL, which also happens when the customer never
 * picked up.
 *
 * Backfill for old calls: the recording starts only when the dialled party
 * answers (startOnDialAnswer), so a recording with a length > 0 means the two
 * legs talked.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('call_logs', 'connected_at')) {
            Schema::table('call_logs', function (Blueprint $table) {
                $table->timestamp('connected_at')->nullable()->after('answered_at');
            });
        }

        DB::table('call_logs')
            ->whereNull('connected_at')
            ->whereNotNull('recording_url')->where('recording_url', '!=', '')
            ->where('recording_duration', '>', 0)
            ->update(['connected_at' => DB::raw('COALESCE(answered_at, started_at, created_at)')]);

        $connected = DB::table('call_logs')->whereNotNull('connected_at')->count();
        $withRecording = DB::table('call_logs')->whereNotNull('recording_url')->where('recording_url', '!=', '')->count();
        echo "  call_logs: {$connected} connected (of {$withRecording} with a recording URL)\n";
    }

    public function down(): void
    {
        if (Schema::hasColumn('call_logs', 'connected_at')) {
            Schema::table('call_logs', function (Blueprint $table) {
                $table->dropColumn('connected_at');
            });
        }
    }
};
