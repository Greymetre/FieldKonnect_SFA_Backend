<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `new_dealer_targets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `target_month` date NOT NULL,
  `target` int(10) UNSIGNED NOT NULL,
  `achievement` int(10) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `new_dealer_targets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `new_dealer_targets_user_id_target_month_unique` (`user_id`,`target_month`),
  ADD KEY `new_dealer_targets_user_id_index` (`user_id`),
  ADD KEY `new_dealer_targets_target_month_index` (`target_month`),
  ADD KEY `new_dealer_targets_created_by_index` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `new_dealer_targets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `new_dealer_targets`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
