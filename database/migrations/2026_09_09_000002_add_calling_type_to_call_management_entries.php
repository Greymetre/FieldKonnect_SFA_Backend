<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('call_management_entries', function (Blueprint $table) {
            $table->string('calling_type', 30)->default('customer_calling')->after('customer_type')->index();
        });

        // Every record that existed before Client Calling was introduced
        // belongs to the original Customer Calling queue.
        DB::table('call_management_entries')
            ->whereNull('calling_type')
            ->orWhere('calling_type', '')
            ->update(['calling_type' => 'customer_calling']);
    }

    public function down(): void
    {
        Schema::table('call_management_entries', function (Blueprint $table) {
            $table->dropIndex(['calling_type']);
            $table->dropColumn('calling_type');
        });
    }
};
