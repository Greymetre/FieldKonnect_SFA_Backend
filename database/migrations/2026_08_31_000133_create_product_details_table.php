<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `product_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `detail_title` varchar(200) NOT NULL DEFAULT '',
  `detail_description` varchar(450) NOT NULL DEFAULT '',
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `detail_image` varchar(400) NOT NULL DEFAULT '',
  `mrp` decimal(8,2) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `discount` decimal(8,2) DEFAULT NULL COMMENT 'in percent',
  `max_discount` decimal(8,2) DEFAULT NULL COMMENT 'in percent',
  `rmc` decimal(8,2) DEFAULT 0.00,
  `selling_price` decimal(8,2) DEFAULT NULL,
  `gst` decimal(8,2) DEFAULT NULL COMMENT 'gst in percent',
  `isprimary` tinyint(4) NOT NULL DEFAULT 0,
  `stock_qty` bigint(20) DEFAULT 0,
  `hsn_code` varchar(250) DEFAULT NULL,
  `budget_for_month` varchar(250) DEFAULT NULL,
  `top_sku` varchar(250) DEFAULT NULL,
  `ean_code` varchar(250) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `product_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_details_hsn_code_unique` (`hsn_code`),
  ADD UNIQUE KEY `product_details_ean_code_unique` (`ean_code`),
  ADD KEY `product_details_detail_title_index` (`detail_title`),
  ADD KEY `product_details_detail_description_index` (`detail_description`),
  ADD KEY `product_details_product_id_index` (`product_id`),
  ADD KEY `product_details_detail_image_index` (`detail_image`),
  ADD KEY `product_details_mrp_index` (`mrp`),
  ADD KEY `product_details_price_index` (`price`),
  ADD KEY `product_details_selling_price_index` (`selling_price`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `product_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `product_details`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
