<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Scheduled attendance should appear as App-generated in reports.
        DB::table('attendances')
            ->where('punchin_from', 'Cron')
            ->update(['punchin_from' => 'App']);

        // Normalize the one-off attendance added for Abhishek Soni.
        DB::table('attendances')
            ->where('user_id', 29)
            ->where('punchin_date', '2026-09-07')
            ->where('punchin_time', '10:12:00')
            ->where('punchin_from', 'Data Migration')
            ->update(['punchin_from' => 'Web']);
    }

    public function down(): void
    {
        // This display-source normalization is intentionally not reversed because
        // existing Web attendance cannot be distinguished safely after the update.
    }
};
