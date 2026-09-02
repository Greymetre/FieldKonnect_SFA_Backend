<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->dropUnique('statuses_status_name_unique');
            $table->unique(['status_name', 'module'], 'statuses_status_name_module_unique');
        });
    }

    public function down(): void
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->dropUnique('statuses_status_name_module_unique');
            $table->unique('status_name', 'statuses_status_name_unique');
        });
    }
};
