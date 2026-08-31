<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `user_city_assigns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `userid` bigint(20) UNSIGNED NOT NULL,
  `reportingid` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `user_city_assigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_city_assigns_userid_index` (`userid`),
  ADD KEY `user_city_assigns_reportingid_index` (`reportingid`),
  ADD KEY `user_city_assigns_city_id_index` (`city_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `user_city_assigns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `user_city_assigns`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
