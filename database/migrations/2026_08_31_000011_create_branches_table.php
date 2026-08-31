<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `branch_name` varchar(250) NOT NULL,
  `branch_code` varchar(125) DEFAULT NULL,
  `warehouse_id` varchar(125) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branches_branch_name_index` (`branch_name`),
  ADD KEY `branches_created_by_index` (`created_by`),
  ADD KEY `branches_updated_by_index` (`updated_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `branches`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
