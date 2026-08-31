<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `sales_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `sales_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_detail_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` bigint(20) NOT NULL DEFAULT 0,
  `shipped_qty` bigint(20) NOT NULL DEFAULT 0,
  `price` decimal(19,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(19,2) DEFAULT NULL,
  `discount_amount` decimal(19,2) DEFAULT NULL,
  `tax_amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(19,2) NOT NULL DEFAULT 0.00,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `sales_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_details_sales_id_index` (`sales_id`),
  ADD KEY `sales_details_product_id_index` (`product_id`),
  ADD KEY `sales_details_product_detail_id_index` (`product_detail_id`),
  ADD KEY `sales_details_quantity_index` (`quantity`),
  ADD KEY `sales_details_price_index` (`price`),
  ADD KEY `sales_details_line_total_index` (`line_total`),
  ADD KEY `sales_details_status_id_index` (`status_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `sales_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `sales_details`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
