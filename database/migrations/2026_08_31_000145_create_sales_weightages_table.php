<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `sales_weightages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `weightage` varchar(191) DEFAULT NULL,
  `division_id` bigint(20) DEFAULT NULL,
  `department_id` bigint(20) DEFAULT NULL,
  `designation_id` varchar(191) DEFAULT NULL,
  `category_name` varchar(191) DEFAULT NULL,
  `indicator` longtext DEFAULT NULL,
  `annum_target` varchar(191) DEFAULT NULL,
  `display_name` varchar(191) DEFAULT NULL,
  `financial_year` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `sales_weightages`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `sales_weightages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `sales_weightages`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
