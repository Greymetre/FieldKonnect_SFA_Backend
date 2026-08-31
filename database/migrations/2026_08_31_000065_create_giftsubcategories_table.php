<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `giftsubcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `ranking` int(11) NOT NULL DEFAULT 1,
  `subcategory_name` varchar(250) NOT NULL,
  `subcategory_image` varchar(350) NOT NULL DEFAULT '',
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `giftsubcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `giftsubcategories_subcategory_name_index` (`subcategory_name`),
  ADD KEY `giftsubcategories_subcategory_image_index` (`subcategory_image`),
  ADD KEY `giftsubcategories_category_id_index` (`category_id`),
  ADD KEY `giftsubcategories_created_by_index` (`created_by`),
  ADD KEY `giftsubcategories_updated_by_index` (`updated_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `giftsubcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `giftsubcategories`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
