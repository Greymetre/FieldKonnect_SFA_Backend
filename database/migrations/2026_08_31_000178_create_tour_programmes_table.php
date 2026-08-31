<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `tour_programmes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `userid` bigint(20) UNSIGNED DEFAULT NULL,
  `town` varchar(250) NOT NULL DEFAULT '',
  `district` bigint(20) NOT NULL,
  `objectives` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `tour_programmes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_programmes_date_index` (`date`),
  ADD KEY `tour_programmes_userid_index` (`userid`),
  ADD KEY `tour_programmes_town_index` (`town`),
  ADD KEY `tour_programmes_type_index` (`type`),
  ADD KEY `tour_programmes_status_index` (`status`),
  ADD KEY `tour_programmes_created_by_index` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `tour_programmes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `tour_programmes`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
