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
            $table->unsignedBigInteger('listing_order')->nullable()->index()->after('follow_up_date');
        });

        DB::table('call_management_entries')->update(['listing_order' => DB::raw('id')]);
    }

    public function down(): void
    {
        Schema::table('call_management_entries', function (Blueprint $table) {
            $table->dropColumn('listing_order');
        });
    }
};
