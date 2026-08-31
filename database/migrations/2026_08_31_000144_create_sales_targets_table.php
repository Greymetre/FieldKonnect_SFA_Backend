<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `sales_targets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `userid` bigint(20) UNSIGNED DEFAULT NULL,
  `startdate` datetime DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  `amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `achievement` decimal(19,2) NOT NULL DEFAULT 0.00,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `sales_targets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_targets_userid_index` (`userid`),
  ADD KEY `sales_targets_startdate_index` (`startdate`),
  ADD KEY `sales_targets_enddate_index` (`enddate`),
  ADD KEY `sales_targets_amount_index` (`amount`),
  ADD KEY `sales_targets_achievement_index` (`achievement`),
  ADD KEY `sales_targets_created_by_index` (`created_by`),
  ADD KEY `sales_targets_updated_by_index` (`updated_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `sales_targets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `sales_targets`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
