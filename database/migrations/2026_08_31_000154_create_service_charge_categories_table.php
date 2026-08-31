<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `service_charge_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `ranking` int(11) NOT NULL DEFAULT 1,
  `category_name` varchar(250) NOT NULL,
  `subcategory_image` varchar(350) NOT NULL DEFAULT '',
  `division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `service_charge_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_charge_categories_subcategory_name_index` (`category_name`),
  ADD KEY `service_charge_categories_subcategory_image_index` (`subcategory_image`),
  ADD KEY `service_charge_categories_division_id_index` (`division_id`),
  ADD KEY `service_charge_categories_created_by_index` (`created_by`),
  ADD KEY `service_charge_categories_updated_by_index` (`updated_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `service_charge_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `service_charge_categories`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
