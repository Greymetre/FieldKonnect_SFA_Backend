<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `ranking` int(11) NOT NULL DEFAULT 1,
  `product_name` varchar(250) NOT NULL,
  `product_code` varchar(125) DEFAULT NULL,
  `new_group` varchar(125) DEFAULT NULL,
  `sub_group` varchar(125) DEFAULT NULL,
  `display_name` varchar(250) NOT NULL DEFAULT '',
  `description` varchar(450) NOT NULL DEFAULT '',
  `subcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci DEFAULT NULL,
  `product_image` varchar(300) NOT NULL DEFAULT '',
  `expiry_interval` varchar(125) DEFAULT NULL,
  `expiry_interval_preiod` int(11) NOT NULL DEFAULT 0,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hsn_sac_no` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `specification` varchar(255) NOT NULL DEFAULT '',
  `phase` text DEFAULT NULL,
  `part_no` varchar(250) NOT NULL DEFAULT '',
  `product_no` varchar(250) NOT NULL DEFAULT '',
  `model_no` varchar(250) NOT NULL DEFAULT '',
  `suc_del` varchar(191) DEFAULT NULL,
  `sap_code` varchar(225) DEFAULT NULL,
  `hsn_sac` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_product_name_index` (`product_name`),
  ADD KEY `products_display_name_index` (`display_name`),
  ADD KEY `products_description_index` (`description`),
  ADD KEY `products_subcategory_id_index` (`subcategory_id`),
  ADD KEY `products_category_id_index` (`category_id`),
  ADD KEY `products_brand_id_index` (`brand_id`),
  ADD KEY `products_product_image_index` (`product_image`),
  ADD KEY `products_unit_id_index` (`unit_id`),
  ADD KEY `products_created_by_index` (`created_by`),
  ADD KEY `products_updated_by_index` (`updated_by`),
  ADD KEY `products_specification_index` (`specification`),
  ADD KEY `products_part_no_index` (`part_no`),
  ADD KEY `products_product_no_index` (`product_no`),
  ADD KEY `products_model_no_index` (`model_no`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `products`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
