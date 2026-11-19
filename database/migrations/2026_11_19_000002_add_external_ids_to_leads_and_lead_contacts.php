<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('leads', 'external_lead_id')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->string('external_lead_id', 100)->nullable()->index()->after('id');
            });
        }

        if (! Schema::hasColumn('lead_contacts', 'external_contact_id')) {
            Schema::table('lead_contacts', function (Blueprint $table) {
                $table->string('external_contact_id', 100)->nullable()->index()->after('lead_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('lead_contacts', 'external_contact_id')) {
            Schema::table('lead_contacts', function (Blueprint $table) {
                $table->dropColumn('external_contact_id');
            });
        }

        if (Schema::hasColumn('leads', 'external_lead_id')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropColumn('external_lead_id');
            });
        }
    }
};
