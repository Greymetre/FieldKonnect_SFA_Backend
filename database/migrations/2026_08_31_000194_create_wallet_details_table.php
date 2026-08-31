<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `wallet_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `wallet_id` bigint(20) UNSIGNED NOT NULL,
  `points` bigint(20) NOT NULL DEFAULT 0,
  `coupon_code` varchar(250) NOT NULL DEFAULT '',
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` bigint(20) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `wallet_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallet_details_wallet_id_index` (`wallet_id`),
  ADD KEY `wallet_details_points_index` (`points`),
  ADD KEY `wallet_details_coupon_code_index` (`coupon_code`),
  ADD KEY `wallet_details_product_id_index` (`product_id`),
  ADD KEY `wallet_details_category_id_index` (`category_id`),
  ADD KEY `wallet_details_subcategory_id_index` (`subcategory_id`),
  ADD KEY `wallet_details_quantity_index` (`quantity`),
  ADD KEY `wallet_details_status_id_index` (`status_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `wallet_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `wallet_details`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
