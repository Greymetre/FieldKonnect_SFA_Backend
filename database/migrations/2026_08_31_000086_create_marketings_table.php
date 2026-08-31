<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `marketings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_date` date DEFAULT NULL,
  `event_center` varchar(255) DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `place_of_participant` varchar(255) DEFAULT NULL,
  `event_district` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `event_under_type` varchar(255) DEFAULT NULL,
  `event_under_name` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `responsible_for_event` varchar(255) DEFAULT NULL,
  `branding_team_member` varchar(255) DEFAULT NULL,
  `name_of_participant` varchar(255) DEFAULT NULL,
  `category_of_participant` varchar(255) DEFAULT NULL,
  `mob_no_of_participant` varchar(255) DEFAULT NULL,
  `count_of_participant` bigint(20) DEFAULT NULL,
  `google_drivelink` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `marketings`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `marketings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `marketings`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
