<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `service_bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_no` varchar(255) DEFAULT NULL,
  `complaint_id` varchar(255) DEFAULT NULL,
  `complaint_no` varchar(255) DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `complaint_type` varchar(255) DEFAULT NULL,
  `complaint_reason` varchar(255) DEFAULT NULL,
  `condition_of_service` varchar(255) DEFAULT NULL,
  `received_product` varchar(255) DEFAULT NULL,
  `nature_of_fault` varchar(255) DEFAULT NULL,
  `service_location` varchar(255) DEFAULT NULL,
  `repaired_replacement` varchar(255) DEFAULT NULL,
  `replacement_tag` varchar(255) DEFAULT NULL,
  `replacement_tag_number` varchar(255) DEFAULT NULL,
  `line_voltage` varchar(255) DEFAULT NULL,
  `load_voltage` varchar(255) DEFAULT NULL,
  `current` varchar(255) DEFAULT NULL,
  `water_source` varchar(255) DEFAULT NULL,
  `panel_rating_running` varchar(255) DEFAULT NULL,
  `panel_rating_starting` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `service_bills`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `service_bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `service_bills`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
