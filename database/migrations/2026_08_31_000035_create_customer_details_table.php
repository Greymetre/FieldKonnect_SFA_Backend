<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `customer_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gstin_no` varchar(250) DEFAULT NULL,
  `pan_no` varchar(250) DEFAULT NULL,
  `aadhar_no` varchar(250) DEFAULT NULL,
  `account_holder` varchar(125) DEFAULT NULL,
  `bank_account_type` varchar(20) DEFAULT NULL,
  `account_number` varchar(125) DEFAULT NULL,
  `bank_name` varchar(125) DEFAULT NULL,
  `ifsc_code` varchar(125) DEFAULT NULL,
  `otherid_no` varchar(250) DEFAULT NULL,
  `gstin_no_status` tinyint(4) NOT NULL DEFAULT 0,
  `pan_no_status` tinyint(4) NOT NULL DEFAULT 0,
  `aadhar_no_status` tinyint(4) NOT NULL DEFAULT 0,
  `bank_status` tinyint(4) NOT NULL DEFAULT 0,
  `otherid_no_status` tinyint(4) NOT NULL DEFAULT 0,
  `status_update_by` bigint(20) DEFAULT NULL,
  `enrollment_date` datetime DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `shop_image` varchar(250) NOT NULL DEFAULT '',
  `visiting_card` varchar(250) DEFAULT NULL,
  `grade` varchar(250) NOT NULL DEFAULT '',
  `visit_status` varchar(250) NOT NULL DEFAULT '',
  `fcm_token` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `customer_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_details_gstin_no_index` (`gstin_no`),
  ADD KEY `customer_details_pan_no_index` (`pan_no`),
  ADD KEY `customer_details_aadhar_no_index` (`aadhar_no`),
  ADD KEY `customer_details_otherid_no_index` (`otherid_no`),
  ADD KEY `customer_details_shop_image_index` (`shop_image`),
  ADD KEY `customer_details_visiting_card_index` (`visiting_card`),
  ADD KEY `customer_details_grade_index` (`grade`),
  ADD KEY `customer_details_visit_status_index` (`visit_status`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `customer_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `customer_details`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
