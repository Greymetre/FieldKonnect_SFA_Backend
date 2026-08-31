<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `ranking` int(11) NOT NULL DEFAULT 1,
  `subcategory_name` varchar(250) NOT NULL,
  `subcategory_image` varchar(350) NOT NULL DEFAULT '',
  `sap_code` varchar(350) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_category_id` varchar(191) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subcategories_subcategory_name_index` (`subcategory_name`),
  ADD KEY `subcategories_subcategory_image_index` (`subcategory_image`),
  ADD KEY `subcategories_category_id_index` (`category_id`),
  ADD KEY `subcategories_created_by_index` (`created_by`),
  ADD KEY `subcategories_updated_by_index` (`updated_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `subcategories`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
