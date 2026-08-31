<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `primary_schemes_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `primary_scheme_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sap_code` varchar(225) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `group_type` varchar(125) DEFAULT NULL,
  `groups` varchar(125) DEFAULT NULL,
  `min` bigint(20) DEFAULT NULL,
  `max` bigint(20) DEFAULT NULL,
  `slab_min` decimal(19,2) DEFAULT NULL,
  `slab_max` decimal(19,2) DEFAULT NULL,
  `gift` varchar(225) DEFAULT NULL,
  `points` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `primary_schemes_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `primary_schemes_details_primary_scheme_id_index` (`primary_scheme_id`),
  ADD KEY `primary_schemes_details_product_id_index` (`product_id`),
  ADD KEY `primary_schemes_details_category_id_index` (`category_id`),
  ADD KEY `primary_schemes_details_subcategory_id_index` (`subcategory_id`),
  ADD KEY `primary_schemes_details_points_index` (`points`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `primary_schemes_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `primary_schemes_details`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
