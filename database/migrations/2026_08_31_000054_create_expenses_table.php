<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expenses_type` bigint(20) DEFAULT NULL,
  `rate` decimal(12,2) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `claim_amount` double(8,2) DEFAULT NULL,
  `approve_amount` double(10,2) DEFAULT NULL,
  `start_km` varchar(255) DEFAULT NULL,
  `stop_km` varchar(255) DEFAULT NULL,
  `total_km` varchar(255) DEFAULT NULL,
  `total_distance` decimal(15,3) DEFAULT NULL,
  `distance_calculated` tinyint(1) NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `checker_status` tinyint(4) NOT NULL DEFAULT 0,
  `accountant_status` tinyint(4) NOT NULL DEFAULT 0,
  `approve_reject_by` bigint(20) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `expenses`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
