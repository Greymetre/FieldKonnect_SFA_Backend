<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `tour_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tourid` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `visited_date` date DEFAULT NULL,
  `visited_cityid` bigint(20) UNSIGNED DEFAULT NULL,
  `last_visited` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `tour_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_details_tourid_index` (`tourid`),
  ADD KEY `tour_details_city_id_index` (`city_id`),
  ADD KEY `tour_details_visited_date_index` (`visited_date`),
  ADD KEY `tour_details_visited_cityid_index` (`visited_cityid`),
  ADD KEY `tour_details_last_visited_index` (`last_visited`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `tour_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `tour_details`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
