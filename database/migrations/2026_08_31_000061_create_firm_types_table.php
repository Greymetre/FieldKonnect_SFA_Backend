<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `firm_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `firmtype_name` varchar(250) NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `firm_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `firm_types_firmtype_name_unique` (`firmtype_name`),
  ADD KEY `firm_types_created_by_index` (`created_by`),
  ADD KEY `firm_types_updated_by_index` (`updated_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `firm_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `firm_types`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
