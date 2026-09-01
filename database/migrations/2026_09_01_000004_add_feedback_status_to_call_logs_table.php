<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('call_logs', 'feedback_status_id')) {
            Schema::table('call_logs', function (Blueprint $table) {
                $table->unsignedBigInteger('feedback_status_id')->nullable()->after('remark')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('call_logs', 'feedback_status_id')) {
            Schema::table('call_logs', function (Blueprint $table) {
                $table->dropColumn('feedback_status_id');
            });
        }
    }
};
