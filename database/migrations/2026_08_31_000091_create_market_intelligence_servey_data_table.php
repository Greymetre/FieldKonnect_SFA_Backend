<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `market_intelligence_servey_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `servey_id` bigint(20) UNSIGNED DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `market_intelligence_servey_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `market_intelligence_servey_data_servey_id_index` (`servey_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `market_intelligence_servey_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `market_intelligence_servey_data`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
