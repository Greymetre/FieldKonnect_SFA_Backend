<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `planned_sop_sale_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `planned_sop_id` bigint(20) DEFAULT NULL,
  `month_1` varchar(255) DEFAULT NULL,
  `month_2` varchar(255) DEFAULT NULL,
  `month_3` varchar(255) DEFAULT NULL,
  `month_4` varchar(255) DEFAULT NULL,
  `month_5` varchar(255) DEFAULT NULL,
  `month_6` varchar(255) DEFAULT NULL,
  `month_7` varchar(255) DEFAULT NULL,
  `month_8` varchar(255) DEFAULT NULL,
  `month_9` varchar(255) DEFAULT NULL,
  `month_10` varchar(255) DEFAULT NULL,
  `month_11` varchar(255) DEFAULT NULL,
  `month_12` varchar(255) DEFAULT NULL,
  `min` varchar(255) DEFAULT NULL,
  `max` varchar(255) DEFAULT NULL,
  `avg` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `planned_sop_sale_data`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `planned_sop_sale_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `planned_sop_sale_data`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
