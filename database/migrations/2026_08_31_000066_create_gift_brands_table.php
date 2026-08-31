<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `gift_brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `ranking` int(11) NOT NULL DEFAULT 1,
  `brand_name` varchar(250) NOT NULL,
  `brand_image` varchar(350) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `gift_brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gift_brands_ranking_index` (`ranking`),
  ADD KEY `gift_brands_brand_name_index` (`brand_name`),
  ADD KEY `gift_brands_brand_image_index` (`brand_image`),
  ADD KEY `gift_brands_created_by_index` (`created_by`),
  ADD KEY `gift_brands_updated_by_index` (`updated_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `gift_brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `gift_brands`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
