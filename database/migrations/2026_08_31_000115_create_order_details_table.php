<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_detail_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` bigint(20) NOT NULL DEFAULT 0,
  `shipped_qty` bigint(20) NOT NULL DEFAULT 0,
  `price` decimal(19,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `gst` decimal(19,2) NOT NULL DEFAULT 0.00,
  `gst_amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(19,2) NOT NULL DEFAULT 0.00,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `scheme_name` varchar(255) DEFAULT NULL,
  `scheme_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `scheme_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cluster_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cluster_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deal_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deal_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `distributor_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `distributor_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `frieght_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `frieght_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cash_dis` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cash_amounts` decimal(10,2) NOT NULL DEFAULT 0.00,
  `agri_standard_dis` decimal(10,2) DEFAULT 0.00,
  `agri_standard_dis_amounts` decimal(10,2) DEFAULT 0.00,
  `scheme_type` varchar(191) DEFAULT NULL,
  `scheme_value_type` varchar(191) DEFAULT NULL,
  `minimum` bigint(20) NOT NULL DEFAULT 0,
  `maximum` bigint(20) NOT NULL DEFAULT 0,
  `ebd_dis` int(11) DEFAULT NULL,
  `special_dis` int(11) DEFAULT NULL,
  `special_amounts` decimal(10,2) DEFAULT NULL,
  `ebd_amount` decimal(10,2) DEFAULT NULL,
  `start_date` varchar(191) DEFAULT NULL,
  `end_date` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `subcategory_id` int(20) DEFAULT NULL,
  `category_id` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_product_id_index` (`product_id`),
  ADD KEY `order_details_product_detail_id_index` (`product_detail_id`),
  ADD KEY `order_details_quantity_index` (`quantity`),
  ADD KEY `order_details_price_index` (`price`),
  ADD KEY `order_details_line_total_index` (`line_total`),
  ADD KEY `order_details_status_id_index` (`status_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `order_details`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
