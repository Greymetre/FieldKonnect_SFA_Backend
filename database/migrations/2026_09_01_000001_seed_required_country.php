<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('countries')) {
            return;
        }

        DB::table('countries')->updateOrInsert(
            ['id' => 1],
            [
                'active' => 'Y',
                'country_name' => 'India',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        // Keep shared reference data intact on rollback.
    }
};
