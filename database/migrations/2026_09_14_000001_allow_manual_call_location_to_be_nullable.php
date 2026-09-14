<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('call_management_entries', function (Blueprint $table) {
            $table->unsignedBigInteger('pincode_id')->nullable()->change();
            $table->string('pincode', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('call_management_entries', function (Blueprint $table) {
            $table->unsignedBigInteger('pincode_id')->nullable(false)->change();
            $table->string('pincode', 20)->nullable(false)->change();
        });
    }
};
