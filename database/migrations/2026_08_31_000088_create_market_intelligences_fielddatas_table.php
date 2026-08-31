<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `market_intelligences_fielddatas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `field_id` bigint(20) UNSIGNED DEFAULT NULL,
  `value` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `market_intelligences_fielddatas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `market_intelligences_fielddatas_field_id_index` (`field_id`),
  ADD KEY `market_intelligences_fielddatas_value_index` (`value`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `market_intelligences_fielddatas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `market_intelligences_fielddatas`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
