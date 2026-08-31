<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `claim_generations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_center_id` bigint(20) DEFAULT NULL,
  `month` varchar(255) DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL,
  `claim_number` varchar(255) DEFAULT NULL,
  `claim_amount` double(8,2) DEFAULT NULL,
  `courier_details` varchar(255) DEFAULT NULL,
  `courier_date` date DEFAULT NULL,
  `asc_bill_no` varchar(255) DEFAULT NULL,
  `asc_bill_date` date DEFAULT NULL,
  `asc_bill_amount` double DEFAULT NULL,
  `claim_sattlement_details` text DEFAULT NULL,
  `submitted_by_se` tinyint(4) DEFAULT NULL,
  `claim_approved` tinyint(4) DEFAULT NULL,
  `claim_done` tinyint(4) DEFAULT NULL,
  `claim_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `claim_generations`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `claim_generations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `claim_generations`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
