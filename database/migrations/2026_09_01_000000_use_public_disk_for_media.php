<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('media')) {
            return;
        }

        DB::table('media')->update([
            'disk' => 'public',
            'conversions_disk' => 'public',
        ]);
    }

    public function down(): void
    {
        // S3 support was intentionally removed; local media remains local.
    }
};
