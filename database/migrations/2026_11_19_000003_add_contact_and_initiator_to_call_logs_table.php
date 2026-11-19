<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('call_logs', function (Blueprint $table) {
            // Which lead contact was dialled, and who clicked "call" (the
            // assigned agent is user_id and may differ from the initiator).
            if (! Schema::hasColumn('call_logs', 'lead_contact_id')) {
                $table->unsignedBigInteger('lead_contact_id')->nullable()->index()->after('lead_id');
            }
            if (! Schema::hasColumn('call_logs', 'initiated_by')) {
                $table->unsignedBigInteger('initiated_by')->nullable()->index()->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('call_logs', function (Blueprint $table) {
            foreach (['lead_contact_id', 'initiated_by'] as $column) {
                if (Schema::hasColumn('call_logs', $column)) {
                    $table->dropIndex('call_logs_'.$column.'_index');
                    $table->dropColumn($column);
                }
            }
        });
    }
};
