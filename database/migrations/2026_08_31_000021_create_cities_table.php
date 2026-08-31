<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `city_name` varchar(250) NOT NULL,
  `district_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `state_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `grade` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cities_district_id_city_name_unique` (`district_id`,`city_name`),
  ADD KEY `cities_city_name_index` (`city_name`),
  ADD KEY `cities_district_id_index` (`district_id`),
  ADD KEY `cities_created_by_index` (`created_by`),
  ADD KEY `cities_updated_by_index` (`updated_by`),
  ADD KEY `cities_state_id_index` (`state_id`),
  ADD KEY `cities_grade_index` (`grade`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `cities`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
