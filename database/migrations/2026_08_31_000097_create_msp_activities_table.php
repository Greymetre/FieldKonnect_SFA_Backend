<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `msp_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `emp_code` varchar(255) DEFAULT NULL,
  `activity_date` date DEFAULT NULL,
  `fyear` varchar(255) DEFAULT NULL,
  `month` varchar(255) DEFAULT NULL,
  `msp_count` bigint(20) DEFAULT NULL,
  `activity_type` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `msp_activities`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `msp_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `msp_activities`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
