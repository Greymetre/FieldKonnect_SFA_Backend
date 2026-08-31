<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `branch_holiday` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `holiday_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `branch_holiday`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branch_holiday_holiday_id_branch_id_unique` (`holiday_id`,`branch_id`),
  ADD KEY `branch_holiday_branch_id_foreign` (`branch_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `branch_holiday`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `branch_holiday`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
