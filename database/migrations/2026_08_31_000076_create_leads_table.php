<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `leads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_url` varchar(255) DEFAULT NULL,
  `address_id` varchar(255) DEFAULT NULL,
  `lead_source` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT '0',
  `assign_to` int(22) DEFAULT NULL,
  `lead_generation_date` date DEFAULT NULL,
  `conversion_date` date DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `on_location` tinyint(1) NOT NULL DEFAULT 0,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `location_address` varchar(255) DEFAULT NULL,
  `others` text DEFAULT NULL,
  `created_by` int(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `leads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `leads`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
