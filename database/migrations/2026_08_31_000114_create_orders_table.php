<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `buyer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `seller_id` bigint(20) UNSIGNED DEFAULT NULL,
  `executive_id` bigint(20) DEFAULT NULL,
  `total_qty` bigint(20) NOT NULL DEFAULT 0,
  `shipped_qty` bigint(20) NOT NULL DEFAULT 0,
  `orderno` varchar(250) NOT NULL DEFAULT '',
  `order_date` date DEFAULT NULL,
  `completed_date` datetime DEFAULT NULL,
  `estimated_date` date DEFAULT NULL,
  `total_gst` decimal(19,2) NOT NULL DEFAULT 0.00,
  `total_discount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `extra_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `extra_discount_amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `sub_total` decimal(19,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(19,2) NOT NULL DEFAULT 0.00,
  `order_taking` varchar(250) NOT NULL DEFAULT '',
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `suc_del` varchar(191) DEFAULT NULL,
  `gst_amount` varchar(125) DEFAULT NULL,
  `schme_amount` decimal(19,2) DEFAULT NULL,
  `schme_val` decimal(19,2) DEFAULT NULL,
  `ebd_amount` decimal(19,2) DEFAULT NULL,
  `ebd_discount` decimal(19,2) DEFAULT NULL,
  `special_discount` int(11) DEFAULT NULL,
  `special_amount` decimal(19,2) DEFAULT NULL,
  `cluster_discount` int(11) DEFAULT NULL,
  `cluster_amount` decimal(19,2) DEFAULT NULL,
  `deal_discount` int(11) DEFAULT NULL,
  `deal_amount` decimal(19,2) DEFAULT NULL,
  `distributor_discount` int(11) DEFAULT NULL,
  `distributor_amount` decimal(19,2) DEFAULT NULL,
  `frieght_discount` int(11) DEFAULT NULL,
  `frieght_amount` decimal(19,2) DEFAULT NULL,
  `product_cat_id` int(11) DEFAULT NULL,
  `dod_discount` decimal(10,2) DEFAULT 0.00,
  `special_distribution_discount` decimal(10,2) DEFAULT 0.00,
  `distribution_margin_discount` decimal(10,2) DEFAULT 0.00,
  `total_fan_discount` decimal(10,2) DEFAULT 0.00,
  `total_fan_discount_amount` decimal(10,2) DEFAULT 0.00,
  `dod_discount_amount` decimal(19,2) DEFAULT 0.00,
  `special_distribution_discount_amount` decimal(19,2) DEFAULT 0.00,
  `distribution_margin_discount_amount` decimal(19,2) DEFAULT 0.00,
  `fan_extra_discount` decimal(19,2) DEFAULT 0.00,
  `fan_extra_discount_amount` decimal(19,2) DEFAULT 0.00,
  `cash_discount` int(11) DEFAULT 0,
  `cash_amount` decimal(10,2) DEFAULT 0.00,
  `agri_standard_discount` decimal(10,2) DEFAULT 0.00,
  `agri_standard_discount_amount` decimal(10,2) DEFAULT 0.00,
  `advance` decimal(19,2) DEFAULT 0.00,
  `gst5_amt` decimal(10,2) DEFAULT 0.00,
  `gst12_amt` decimal(10,2) DEFAULT 0.00,
  `gst18_amt` decimal(10,2) DEFAULT 0.00,
  `gst28_amt` decimal(10,2) DEFAULT 0.00,
  `order_remark` varchar(255) DEFAULT NULL,
  `discount_status` tinyint(4) NOT NULL DEFAULT 0,
  `sp_discount_status` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `beatscheduleid` bigint(20) UNSIGNED DEFAULT NULL,
  `order_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_buyer_id_index` (`buyer_id`),
  ADD KEY `orders_seller_id_index` (`seller_id`),
  ADD KEY `orders_total_qty_index` (`total_qty`),
  ADD KEY `orders_orderno_index` (`orderno`),
  ADD KEY `orders_order_date_index` (`order_date`),
  ADD KEY `orders_sub_total_index` (`sub_total`),
  ADD KEY `orders_grand_total_index` (`grand_total`),
  ADD KEY `orders_status_id_index` (`status_id`),
  ADD KEY `orders_address_id_index` (`address_id`),
  ADD KEY `orders_beatscheduleid_index` (`beatscheduleid`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `orders`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
