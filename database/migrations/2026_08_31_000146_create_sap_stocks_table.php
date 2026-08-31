<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `sap_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_sap_code` varchar(255) DEFAULT NULL,
  `product_description` varchar(255) DEFAULT NULL,
  `product_category_sap_code` varchar(255) DEFAULT NULL,
  `product_category_name` varchar(255) DEFAULT NULL,
  `warehouse_code` varchar(255) DEFAULT NULL,
  `warehouse_name` varchar(255) DEFAULT NULL,
  `instock_qty` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `itm_remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `sap_stocks`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `sap_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `sap_stocks`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
