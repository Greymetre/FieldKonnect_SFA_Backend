<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `secondary_customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `sub_type` varchar(255) DEFAULT NULL,
  `owner_name` varchar(255) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `whatsapp_number` varchar(255) DEFAULT NULL,
  `owner_photo` varchar(255) DEFAULT NULL,
  `shop_photo` varchar(255) DEFAULT NULL,
  `vehicle_segment` varchar(255) DEFAULT NULL,
  `address_line` text NOT NULL,
  `belt_area_market_name` varchar(255) DEFAULT NULL,
  `saathi_awareness_status` enum('Done','Not Done') NOT NULL DEFAULT 'Not Done',
  `nistha_awareness_status` enum('Done','Not Done') DEFAULT NULL,
  `opportunity_status` enum('HOT','WARM','COLD','LOST') NOT NULL DEFAULT 'COLD',
  `status` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  `active` enum('Y','N') DEFAULT 'Y',
  `gps_location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `district_id` bigint(20) UNSIGNED NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `pincode_id` bigint(20) UNSIGNED NOT NULL,
  `beat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `saathi_awareness` tinyint(4) NOT NULL DEFAULT 1,
  `distributor_name` varchar(255) DEFAULT NULL,
  `agri_distributor` varchar(255) DEFAULT NULL,
  `gst_number` varchar(255) DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `gst_attachment` varchar(255) DEFAULT NULL,
  `pan_attachment` varchar(255) DEFAULT NULL,
  `bank_proof` varchar(255) DEFAULT NULL,
  `bank_account_type` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `ifsc_code` varchar(255) DEFAULT NULL,
  `account_holder_name` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `remark` varchar(255) NOT NULL,
  `approve_reject_by` bigint(20) DEFAULT NULL,
  `gmap` varchar(255) DEFAULT NULL,
  `status_updated_at` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `secondary_customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `secondary_customers_mobile_number_unique` (`mobile_number`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `secondary_customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `secondary_customers`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
