<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `unit_measures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `unit_name` varchar(250) NOT NULL,
  `unit_code` varchar(250) NOT NULL DEFAULT '',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `unit_measures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_measures_unit_name_index` (`unit_name`),
  ADD KEY `unit_measures_unit_code_index` (`unit_code`),
  ADD KEY `unit_measures_created_by_index` (`created_by`),
  ADD KEY `unit_measures_updated_by_index` (`updated_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `unit_measures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `unit_measures`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
