<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `buyer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `seller_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_qty` bigint(20) NOT NULL DEFAULT 0,
  `shipped_qty` bigint(20) NOT NULL DEFAULT 0,
  `orderno` varchar(250) NOT NULL DEFAULT '',
  `fiscal_year` varchar(50) NOT NULL DEFAULT '',
  `sales_no` varchar(250) NOT NULL DEFAULT '',
  `invoice_no` varchar(250) NOT NULL DEFAULT '',
  `invoice_date` date DEFAULT NULL,
  `transport_details` text DEFAULT NULL,
  `total_gst` decimal(19,2) NOT NULL DEFAULT 0.00,
  `total_discount` decimal(19,2) DEFAULT NULL,
  `extra_discount` decimal(8,2) DEFAULT NULL,
  `extra_discount_amount` decimal(19,2) DEFAULT NULL,
  `sub_total` decimal(19,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(19,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `description` varchar(400) NOT NULL DEFAULT '',
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `transport_name` varchar(200) DEFAULT NULL,
  `lr_no` varchar(125) DEFAULT NULL,
  `dispatch_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_buyer_id_index` (`buyer_id`),
  ADD KEY `sales_seller_id_index` (`seller_id`),
  ADD KEY `sales_order_id_index` (`order_id`),
  ADD KEY `sales_total_qty_index` (`total_qty`),
  ADD KEY `sales_orderno_index` (`orderno`),
  ADD KEY `sales_sales_no_index` (`sales_no`),
  ADD KEY `sales_invoice_no_index` (`invoice_no`),
  ADD KEY `sales_invoice_date_index` (`invoice_date`),
  ADD KEY `sales_sub_total_index` (`sub_total`),
  ADD KEY `sales_grand_total_index` (`grand_total`),
  ADD KEY `sales_description_index` (`description`),
  ADD KEY `sales_status_id_index` (`status_id`),
  ADD KEY `sales_created_by_index` (`created_by`),
  ADD KEY `sales_updated_by_index` (`updated_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `sales`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
