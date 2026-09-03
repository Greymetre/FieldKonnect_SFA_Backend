<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('call_management_entries')
            ->where('status', 'pending')
            ->whereNotNull('assigned_user_id')
            ->update(['status' => 'assigned']);
    }

    public function down(): void
    {
        // Historical statuses cannot be identified safely after assignment.
    }
};
