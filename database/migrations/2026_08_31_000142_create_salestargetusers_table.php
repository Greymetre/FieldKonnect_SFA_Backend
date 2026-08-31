<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `salestargetusers` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `month` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `target` decimal(10,2) DEFAULT NULL,
  `achievement` decimal(10,2) DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `achievement_percent` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `qunatity_target` decimal(10,2) DEFAULT NULL,
  `qunatity_achievement` decimal(10,2) DEFAULT NULL,
  `qunatity_achievement_percent` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `salestargetusers`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `salestargetusers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `salestargetusers`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
