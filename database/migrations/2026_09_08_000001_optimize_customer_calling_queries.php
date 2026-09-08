<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('call_management_entries', function (Blueprint $table) {
            $table->index(
                ['status', 'assigned_user_id', 'listing_order'],
                'call_entries_queue_lookup_index'
            );
            $table->index('updated_at', 'call_entries_updated_at_index');
        });

        Schema::table('call_logs', function (Blueprint $table) {
            $table->index(
                ['call_management_entry_id', 'id', 'feedback_status_id'],
                'call_logs_latest_feedback_index'
            );
        });

        Schema::table('pincodes', function (Blueprint $table) {
            $table->index(['active', 'pincode'], 'pincodes_active_search_index');
        });
    }

    public function down(): void
    {
        Schema::table('call_management_entries', function (Blueprint $table) {
            $table->dropIndex('call_entries_queue_lookup_index');
            $table->dropIndex('call_entries_updated_at_index');
        });

        Schema::table('call_logs', function (Blueprint $table) {
            $table->dropIndex('call_logs_latest_feedback_index');
        });

        Schema::table('pincodes', function (Blueprint $table) {
            $table->dropIndex('pincodes_active_search_index');
        });
    }
};
