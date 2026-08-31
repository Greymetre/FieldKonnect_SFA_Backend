<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `warranty_timelines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warranty_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `warranty_timelines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warranty_timelines_warranty_id_index` (`warranty_id`),
  ADD KEY `warranty_timelines_created_by_index` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `warranty_timelines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `warranty_timelines`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
