<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `name` varchar(200) NOT NULL,
  `first_name` varchar(250) NOT NULL DEFAULT '',
  `last_name` varchar(250) NOT NULL DEFAULT '',
  `mobile` varchar(15) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `contact_number` varchar(191) DEFAULT NULL,
  `notification_id` varchar(450) NOT NULL DEFAULT '',
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `device_type` varchar(50) NOT NULL DEFAULT '',
  `gender` varchar(20) NOT NULL DEFAULT '',
  `same_address` tinyint(4) NOT NULL DEFAULT 1,
  `profile_image` varchar(350) DEFAULT NULL,
  `shop_image` varchar(350) DEFAULT NULL,
  `customer_code` varchar(250) NOT NULL DEFAULT '',
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customertype` bigint(20) UNSIGNED DEFAULT NULL,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `firmtype` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `executive_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  `otp` int(11) DEFAULT NULL,
  `working_status` varchar(255) DEFAULT NULL,
  `sap_code` varchar(225) DEFAULT NULL,
  `creation_date` varchar(255) DEFAULT NULL,
  `custom_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_fields`)),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `beatscheduleid` bigint(20) UNSIGNED DEFAULT NULL,
  `manager_name` varchar(250) NOT NULL DEFAULT '',
  `manager_phone` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_mobile_unique` (`mobile`),
  ADD KEY `customers_name_index` (`name`),
  ADD KEY `customers_first_name_index` (`first_name`),
  ADD KEY `customers_last_name_index` (`last_name`),
  ADD KEY `customers_latitude_index` (`latitude`),
  ADD KEY `customers_longitude_index` (`longitude`),
  ADD KEY `customers_gender_index` (`gender`),
  ADD KEY `customers_profile_image_index` (`profile_image`),
  ADD KEY `customers_customer_code_index` (`customer_code`),
  ADD KEY `customers_status_id_index` (`status_id`),
  ADD KEY `customers_customertype_index` (`customertype`),
  ADD KEY `customers_region_id_index` (`region_id`),
  ADD KEY `customers_firmtype_index` (`firmtype`),
  ADD KEY `customers_created_by_index` (`created_by`),
  ADD KEY `customers_updated_by_index` (`updated_by`),
  ADD KEY `customers_executive_id_index` (`executive_id`),
  ADD KEY `customers_beatscheduleid_index` (`beatscheduleid`),
  ADD KEY `customers_manager_name_index` (`manager_name`),
  ADD KEY `customers_manager_phone_index` (`manager_phone`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `customers`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
