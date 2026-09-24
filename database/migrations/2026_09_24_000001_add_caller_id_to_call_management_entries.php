<?php

use App\Models\CallManagementEntry;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('call_management_entries', function (Blueprint $table) {
            $table->string('caller_id', 30)->nullable()->unique()->after('project_id');
        });

        // Give every existing entry the same system-generated Caller ID new
        // entries receive, so exported files can be re-imported as updates.
        DB::table('call_management_entries')
            ->whereNull('caller_id')
            ->orderBy('id')
            ->select('id')
            ->chunkById(500, function ($entries) {
                foreach ($entries as $entry) {
                    DB::table('call_management_entries')
                        ->where('id', $entry->id)
                        ->update(['caller_id' => CallManagementEntry::callerIdFor($entry->id)]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('call_management_entries', function (Blueprint $table) {
            $table->dropUnique(['caller_id']);
            $table->dropColumn('caller_id');
        });
    }
};
