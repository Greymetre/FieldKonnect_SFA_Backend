<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lead notes also hold Email and LinkedIn drafts. Existing rows stay "note".
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('lead_notes', 'type')) {
            Schema::table('lead_notes', function (Blueprint $table) {
                $table->string('type', 20)->default('note')->index()->after('lead_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('lead_notes', 'type')) {
            Schema::table('lead_notes', function (Blueprint $table) {
                $table->dropIndex(['type']);
                $table->dropColumn('type');
            });
        }
    }
};
