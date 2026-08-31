<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `master_distributors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `legal_name` varchar(255) NOT NULL,
  `trade_name` varchar(255) DEFAULT NULL,
  `distributor_code` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `business_status` varchar(255) NOT NULL,
  `business_start_date` date NOT NULL,
  `shop_image` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) NOT NULL,
  `alternate_mobile` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `secondary_email` varchar(255) DEFAULT NULL,
  `billing_address` varchar(255) NOT NULL,
  `billing_city` varchar(255) DEFAULT NULL,
  `billing_district` varchar(255) DEFAULT NULL,
  `billing_state` varchar(255) DEFAULT NULL,
  `billing_country` varchar(255) DEFAULT NULL,
  `billing_pincode` varchar(255) DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `shipping_city` varchar(255) DEFAULT NULL,
  `shipping_district` varchar(255) DEFAULT NULL,
  `shipping_state` varchar(255) DEFAULT NULL,
  `shipping_country` varchar(255) DEFAULT NULL,
  `shipping_pincode` varchar(255) DEFAULT NULL,
  `sales_zone` varchar(255) NOT NULL,
  `area_territory` varchar(255) NOT NULL,
  `beat_route` varchar(255) DEFAULT NULL,
  `market_classification` varchar(255) NOT NULL,
  `competitor_brands` text DEFAULT NULL,
  `gst_number` varchar(255) NOT NULL,
  `pan_number` varchar(255) NOT NULL,
  `registration_type` varchar(255) NOT NULL,
  `documents` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_holder` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `ifsc` varchar(255) NOT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `credit_limit` decimal(15,2) NOT NULL,
  `credit_days` int(11) NOT NULL,
  `avg_monthly_purchase` decimal(15,2) DEFAULT NULL,
  `outstanding_balance` decimal(15,2) DEFAULT NULL,
  `preferred_payment_method` varchar(255) DEFAULT NULL,
  `cancelled_cheque` varchar(255) NOT NULL,
  `monthly_sales` decimal(15,2) NOT NULL,
  `product_categories` varchar(255) NOT NULL,
  `secondary_sales_required` varchar(255) DEFAULT NULL,
  `last_12_months_sales` varchar(255) DEFAULT NULL,
  `sales_executive_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sales_executive_id`)),
  `supervisor_id` bigint(20) UNSIGNED NOT NULL,
  `customer_segment` varchar(255) NOT NULL,
  `weekly_tai_alert` varchar(255) NOT NULL,
  `target_vs_achievement` varchar(255) NOT NULL,
  `schemes_updates` varchar(255) NOT NULL,
  `new_launch_update` varchar(255) NOT NULL,
  `payment_alert` varchar(255) NOT NULL,
  `pending_orders` varchar(255) NOT NULL,
  `inventory_status` varchar(255) NOT NULL,
  `turnover` decimal(15,2) NOT NULL,
  `staff_strength` varchar(255) NOT NULL,
  `vehicles_capacity` varchar(255) NOT NULL,
  `area_coverage` varchar(255) NOT NULL,
  `other_brands_handled` varchar(255) NOT NULL,
  `warehouse_size` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mou_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `same_as_billing` tinyint(1) NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `beat_id` bigint(20) DEFAULT NULL,
  `gps_location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `master_distributors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_distributors_distributor_code_unique` (`distributor_code`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `master_distributors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `master_distributors`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
