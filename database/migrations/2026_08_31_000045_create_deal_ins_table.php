<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `deal_ins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `types` varchar(150) NOT NULL,
  `hcv` tinyint(1) NOT NULL DEFAULT 0,
  `mav` tinyint(1) NOT NULL DEFAULT 0,
  `lmv` tinyint(1) NOT NULL DEFAULT 0,
  `lcv` tinyint(1) NOT NULL DEFAULT 0,
  `other` tinyint(1) NOT NULL DEFAULT 0,
  `tractor` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `deal_ins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deal_ins_customer_id_index` (`customer_id`),
  ADD KEY `deal_ins_types_index` (`types`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `deal_ins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `deal_ins`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
