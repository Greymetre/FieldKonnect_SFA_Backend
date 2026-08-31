<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `gifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `product_name` varchar(250) NOT NULL,
  `display_name` varchar(250) NOT NULL DEFAULT '',
  `description` varchar(450) NOT NULL DEFAULT '',
  `product_image` varchar(300) NOT NULL DEFAULT '',
  `mrp` decimal(8,2) NOT NULL DEFAULT 0.00,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `points` bigint(20) NOT NULL DEFAULT 0,
  `subcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_type_id` bigint(20) DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `gifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gifts_product_name_index` (`product_name`),
  ADD KEY `gifts_display_name_index` (`display_name`),
  ADD KEY `gifts_description_index` (`description`),
  ADD KEY `gifts_product_image_index` (`product_image`),
  ADD KEY `gifts_mrp_index` (`mrp`),
  ADD KEY `gifts_price_index` (`price`),
  ADD KEY `gifts_points_index` (`points`),
  ADD KEY `gifts_subcategory_id_index` (`subcategory_id`),
  ADD KEY `gifts_category_id_index` (`category_id`),
  ADD KEY `gifts_brand_id_index` (`brand_id`),
  ADD KEY `gifts_unit_id_index` (`unit_id`),
  ADD KEY `gifts_created_by_index` (`created_by`),
  ADD KEY `gifts_updated_by_index` (`updated_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `gifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `gifts`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
