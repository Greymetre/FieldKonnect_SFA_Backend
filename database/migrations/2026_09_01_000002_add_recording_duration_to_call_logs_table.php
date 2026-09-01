<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('call_logs', 'recording_duration')) {
            Schema::table('call_logs', function (Blueprint $table) {
                $table->unsignedInteger('recording_duration')
                    ->nullable()
                    ->after('duration')
                    ->comment('Final Plivo recording length in seconds');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('call_logs', 'recording_duration')) {
            Schema::table('call_logs', function (Blueprint $table) {
                $table->dropColumn('recording_duration');
            });
        }
    }
};
