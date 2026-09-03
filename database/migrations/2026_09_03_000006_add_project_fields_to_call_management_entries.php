<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('call_management_entries', function (Blueprint $table) {
            $table->string('project_name')->nullable()->before('firm_name');
            $table->string('project_id', 100)->nullable()->after('project_name');
            $table->string('parent_name')->nullable()->after('project_id');
        });
    }

    public function down(): void
    {
        Schema::table('call_management_entries', function (Blueprint $table) {
            $table->dropColumn(['project_name', 'project_id', 'parent_name']);
        });
    }
};
