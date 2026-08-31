<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `comp_off_leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `leave_id` varchar(225) DEFAULT NULL,
  `comp_off_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `balance` decimal(19,2) NOT NULL DEFAULT 1.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `comp_off_leaves`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `comp_off_leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `comp_off_leaves`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
