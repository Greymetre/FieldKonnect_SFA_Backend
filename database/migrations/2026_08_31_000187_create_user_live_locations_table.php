<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `user_live_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `userid` bigint(20) UNSIGNED NOT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `address` varchar(450) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `user_live_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_live_locations_userid_index` (`userid`),
  ADD KEY `user_live_locations_latitude_index` (`latitude`),
  ADD KEY `user_live_locations_longitude_index` (`longitude`),
  ADD KEY `user_live_locations_time_index` (`time`),
  ADD KEY `user_live_locations_address_index` (`address`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `user_live_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `user_live_locations`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
