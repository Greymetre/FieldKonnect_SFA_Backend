<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Track when the assignee first saw, opened and completed a lead task.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead_tasks', function (Blueprint $table) {
            foreach (['seen_at', 'opened_at', 'completed_at'] as $column) {
                if (! Schema::hasColumn('lead_tasks', $column)) {
                    $table->timestamp($column)->nullable()->after('close_date');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('lead_tasks', function (Blueprint $table) {
            foreach (['seen_at', 'opened_at', 'completed_at'] as $column) {
                if (Schema::hasColumn('lead_tasks', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
