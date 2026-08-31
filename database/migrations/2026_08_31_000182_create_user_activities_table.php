<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `user_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `userid` bigint(20) UNSIGNED NOT NULL,
  `customerid` bigint(20) UNSIGNED DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `address` varchar(450) NOT NULL DEFAULT '',
  `description` varchar(450) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT '',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `user_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_activities_userid_index` (`userid`),
  ADD KEY `user_activities_latitude_index` (`latitude`),
  ADD KEY `user_activities_longitude_index` (`longitude`),
  ADD KEY `user_activities_time_index` (`time`),
  ADD KEY `user_activities_address_index` (`address`),
  ADD KEY `user_activities_description_index` (`description`),
  ADD KEY `user_activities_type_index` (`type`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `user_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `user_activities`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
