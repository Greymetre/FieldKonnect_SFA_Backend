<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $leadIds = DB::table('leads')->pluck('id');

            if (Schema::hasTable('tasks') && Schema::hasColumn('tasks', 'lead_id')) {
                $taskIds = DB::table('tasks')->whereIn('lead_id', $leadIds)->pluck('id');

                foreach (['task_assignments', 'task_comments', 'task_status_logs'] as $table) {
                    if (Schema::hasTable($table) && Schema::hasColumn($table, 'task_id')) {
                        DB::table($table)->whereIn('task_id', $taskIds)->delete();
                    }
                }

                DB::table('tasks')->whereIn('id', $taskIds)->delete();
            }

            foreach ([
                'call_logs',
                'lead_check_in',
                'lead_logs',
                'lead_notes',
                'lead_opportunities',
                'lead_tasks',
                'lead_contacts',
            ] as $table) {
                if (Schema::hasTable($table) && Schema::hasColumn($table, 'lead_id')) {
                    DB::table($table)->whereIn('lead_id', $leadIds)->delete();
                }
            }

            // Includes assignment notifications whose model_id is null.
            if (Schema::hasTable('lead_notifications')) {
                DB::table('lead_notifications')->delete();
            }

            if (Schema::hasTable('addresses')) {
                DB::table('addresses')
                    ->where('model_type', 'App\\Models\\Lead')
                    ->whereIn('model_id', $leadIds)
                    ->delete();
            }

            if (Schema::hasTable('media')) {
                \Spatie\MediaLibrary\MediaCollections\Models\Media::query()
                    ->where('model_type', 'App\\Models\\Lead')
                    ->whereIn('model_id', $leadIds)
                    ->eachById(function ($media) {
                        $media->delete();
                    });
            }

            DB::table('leads')->whereIn('id', $leadIds)->delete();
        });
    }

    public function down(): void
    {
        // Deleted production data cannot be reconstructed safely.
    }
};
