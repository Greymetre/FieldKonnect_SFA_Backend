<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE call_logs MODIFY lead_id BIGINT UNSIGNED NULL');

        Schema::table('call_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('call_management_entry_id')->nullable()->after('lead_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('call_logs', function (Blueprint $table) {
            $table->dropColumn('call_management_entry_id');
        });
    }
};
