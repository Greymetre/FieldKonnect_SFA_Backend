<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->timestamp('status_changed_at')->nullable()->after('status');
            $table->index('status_changed_at', 'leads_status_changed_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('leads_status_changed_at_index');
            $table->dropColumn('status_changed_at');
        });
    }
};
