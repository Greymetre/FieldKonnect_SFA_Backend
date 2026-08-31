<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `beats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `beat_name` varchar(250) NOT NULL,
  `description` varchar(450) NOT NULL DEFAULT '',
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `state_id` bigint(20) UNSIGNED DEFAULT NULL,
  `district_id` varchar(225) DEFAULT NULL,
  `city_id` varchar(225) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `beats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beats_beat_name_index` (`beat_name`),
  ADD KEY `beats_description_index` (`description`),
  ADD KEY `beats_region_id_index` (`region_id`),
  ADD KEY `beats_country_id_index` (`country_id`),
  ADD KEY `beats_state_id_index` (`state_id`),
  ADD KEY `beats_created_by_index` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `beats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `beats`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
