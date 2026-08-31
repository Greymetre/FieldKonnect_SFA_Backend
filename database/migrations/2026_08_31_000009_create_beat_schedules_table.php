<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `beat_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `beat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `beat_date` date DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tourid` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `beat_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beat_schedules_beat_id_index` (`beat_id`),
  ADD KEY `beat_schedules_beat_date_index` (`beat_date`),
  ADD KEY `beat_schedules_user_id_index` (`user_id`),
  ADD KEY `beat_schedules_tourid_index` (`tourid`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `beat_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `beat_schedules`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
