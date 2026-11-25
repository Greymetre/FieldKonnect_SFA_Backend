<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Email / LinkedIn drafts can be rejected by the approver (final, with a reason).
 * A rejected draft is locked for the agent; they write a new one instead.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead_notes', function (Blueprint $table) {
            if (! Schema::hasColumn('lead_notes', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('changes_requested_at');
            }
            if (! Schema::hasColumn('lead_notes', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
            }
            if (! Schema::hasColumn('lead_notes', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lead_notes', function (Blueprint $table) {
            foreach (['rejection_reason', 'rejected_by', 'rejected_at'] as $column) {
                if (Schema::hasColumn('lead_notes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
