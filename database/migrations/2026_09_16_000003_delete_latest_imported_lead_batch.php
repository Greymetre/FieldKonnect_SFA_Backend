<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('leads', 'import_batch_order')) {
            throw new \RuntimeException('The lead import tracking columns are missing. Run the import-order migration first.');
        }

        $latestBatch = DB::table('leads')
            ->whereNotNull('import_batch_order')
            ->max('import_batch_order');

        if ($latestBatch === null) {
            throw new \RuntimeException('No tracked lead import batch was found. No leads were deleted.');
        }

        $leadIds = DB::table('leads')
            ->where('import_batch_order', $latestBatch)
            ->pluck('id');

        if ($leadIds->isEmpty()) {
            throw new \RuntimeException('The latest tracked lead import batch is empty. No leads were deleted.');
        }

        DB::transaction(function () use ($leadIds) {
            foreach ([
                'call_logs',
                'lead_check_in',
                'lead_contacts',
                'lead_logs',
                'lead_notes',
                'lead_opportunities',
                'lead_tasks',
                'tasks',
            ] as $table) {
                if (Schema::hasTable($table) && Schema::hasColumn($table, 'lead_id')) {
                    DB::table($table)->whereIn('lead_id', $leadIds)->delete();
                }
            }

            if (Schema::hasTable('lead_notifications')) {
                DB::table('lead_notifications')
                    ->whereIn('model_id', $leadIds)
                    ->where('model', 'lead')
                    ->delete();
            }

            if (Schema::hasTable('addresses')) {
                DB::table('addresses')
                    ->where('model_type', 'App\\Models\\Lead')
                    ->whereIn('model_id', $leadIds)
                    ->delete();
            }

            if (Schema::hasTable('media')) {
                DB::table('media')
                    ->where('model_type', 'App\\Models\\Lead')
                    ->whereIn('model_id', $leadIds)
                    ->delete();
            }

            DB::table('leads')->whereIn('id', $leadIds)->delete();
        });
    }

    public function down(): void
    {
        // Deleted production data cannot be reconstructed safely.
    }
};
