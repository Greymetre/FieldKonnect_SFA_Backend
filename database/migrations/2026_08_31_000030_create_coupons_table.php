<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `coupon` varchar(50) NOT NULL,
  `points` bigint(20) NOT NULL DEFAULT 0,
  `expiry_date` date DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `coupon_profile_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_coupon_unique` (`coupon`),
  ADD KEY `coupons_points_index` (`points`),
  ADD KEY `coupons_expiry_date_index` (`expiry_date`),
  ADD KEY `coupons_product_id_index` (`product_id`),
  ADD KEY `coupons_coupon_profile_id_index` (`coupon_profile_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `coupons`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
