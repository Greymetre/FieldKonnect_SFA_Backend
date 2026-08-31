<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `branchwise_targets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `div_id` bigint(20) UNSIGNED DEFAULT NULL,
  `division_name` varchar(255) DEFAULT NULL,
  `month` varchar(255) DEFAULT NULL,
  `year` varchar(255) DEFAULT NULL,
  `target` decimal(19,2) NOT NULL DEFAULT 0.00,
  `achievement` decimal(19,2) NOT NULL DEFAULT 0.00,
  `type` varchar(255) DEFAULT NULL,
  `amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `branchwise_targets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branchwise_targets_user_id_index` (`user_id`),
  ADD KEY `branchwise_targets_branch_id_index` (`branch_id`),
  ADD KEY `branchwise_targets_div_id_index` (`div_id`),
  ADD KEY `branchwise_targets_target_index` (`target`),
  ADD KEY `branchwise_targets_achievement_index` (`achievement`),
  ADD KEY `branchwise_targets_amount_index` (`amount`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `branchwise_targets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `branchwise_targets`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
