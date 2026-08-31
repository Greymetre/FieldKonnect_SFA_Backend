<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `scheme_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `scheme_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `active_point` bigint(20) DEFAULT NULL,
  `provision_point` bigint(20) DEFAULT NULL,
  `points` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `scheme_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scheme_details_scheme_id_index` (`scheme_id`),
  ADD KEY `scheme_details_product_id_index` (`product_id`),
  ADD KEY `scheme_details_category_id_index` (`category_id`),
  ADD KEY `scheme_details_subcategory_id_index` (`subcategory_id`),
  ADD KEY `scheme_details_minimum_index` (`active_point`),
  ADD KEY `scheme_details_maximum_index` (`provision_point`),
  ADD KEY `scheme_details_points_index` (`points`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `scheme_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `scheme_details`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
