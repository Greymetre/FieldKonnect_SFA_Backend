<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('plivo_endpoint_id', 100)->nullable()->unique()->after('call_management');
            $table->string('plivo_endpoint_username', 100)->nullable()->unique()->after('plivo_endpoint_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['plivo_endpoint_id']);
            $table->dropUnique(['plivo_endpoint_username']);
            $table->dropColumn(['plivo_endpoint_id', 'plivo_endpoint_username']);
        });
    }
};
