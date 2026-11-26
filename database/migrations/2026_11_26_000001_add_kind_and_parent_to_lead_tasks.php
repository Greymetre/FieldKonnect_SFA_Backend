<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * lead_tasks.kind: what the task is for — send_details (share info on WhatsApp /
 * Email / LinkedIn), call_back, follow_up (auto-created 3 days after a
 * send_details task is completed) or null (manual task).
 * lead_tasks.parent_task_id: for a follow_up, the task it follows up on.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead_tasks', function (Blueprint $table) {
            if (! Schema::hasColumn('lead_tasks', 'kind')) {
                $table->string('kind', 30)->nullable()->index()->after('status');
            }
            if (! Schema::hasColumn('lead_tasks', 'parent_task_id')) {
                $table->unsignedBigInteger('parent_task_id')->nullable()->index()->after('kind');
            }
        });

        // Tasks the call feedback already created.
        DB::table('lead_tasks')->whereNull('kind')->where('description', 'like', 'Send details on %')->update(['kind' => 'send_details']);
        DB::table('lead_tasks')->whereNull('kind')->where('description', 'like', 'Call back %')->update(['kind' => 'call_back']);
    }

    public function down(): void
    {
        Schema::table('lead_tasks', function (Blueprint $table) {
            if (Schema::hasColumn('lead_tasks', 'parent_task_id')) {
                $table->dropIndex(['parent_task_id']);
                $table->dropColumn('parent_task_id');
            }
            if (Schema::hasColumn('lead_tasks', 'kind')) {
                $table->dropIndex(['kind']);
                $table->dropColumn('kind');
            }
        });
    }
};
