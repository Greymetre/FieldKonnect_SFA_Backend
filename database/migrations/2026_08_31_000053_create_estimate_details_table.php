<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `estimate_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estimate_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_dec` varchar(255) DEFAULT NULL,
  `hsn_sac` varchar(255) DEFAULT NULL,
  `hsn_sac_type` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `mrp` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax` int(22) DEFAULT NULL,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `estimate_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estimate_id` (`estimate_id`),
  ADD KEY `product_id` (`product_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `estimate_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `estimate_details`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
