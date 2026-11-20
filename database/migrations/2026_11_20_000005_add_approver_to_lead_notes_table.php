<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Email / LinkedIn drafts are sent to one chosen approver.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('lead_notes', 'approver_id')) {
            Schema::table('lead_notes', function (Blueprint $table) {
                $table->unsignedBigInteger('approver_id')->nullable()->index()->after('created_by');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('lead_notes', 'approver_id')) {
            Schema::table('lead_notes', function (Blueprint $table) {
                $table->dropIndex(['approver_id']);
                $table->dropColumn('approver_id');
            });
        }
    }
};
