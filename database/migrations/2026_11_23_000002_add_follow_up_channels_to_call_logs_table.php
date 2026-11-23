<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "Call Done" follow-up: where the customer wants details (whatsapp / linkedin / email).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('call_logs', 'follow_up_channels')) {
            Schema::table('call_logs', function (Blueprint $table) {
                $table->string('follow_up_channels', 100)->nullable()->after('follow_up_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('call_logs', 'follow_up_channels')) {
            Schema::table('call_logs', function (Blueprint $table) {
                $table->dropColumn('follow_up_channels');
            });
        }
    }
};
