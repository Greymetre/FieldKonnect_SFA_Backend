<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `check_in` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entity_type` varchar(255) DEFAULT NULL,
  `entity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `checkin_date` date NOT NULL,
  `checkin_time` time NOT NULL,
  `checkin_latitude` varchar(250) DEFAULT NULL,
  `checkin_longitude` varchar(250) DEFAULT NULL,
  `checkin_address` varchar(250) DEFAULT NULL,
  `checkout_date` date DEFAULT NULL,
  `checkout_time` time DEFAULT NULL,
  `time_interval` time DEFAULT NULL,
  `checkout_latitude` varchar(250) DEFAULT NULL,
  `checkout_longitude` varchar(250) DEFAULT NULL,
  `checkout_address` varchar(250) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `distance` varchar(250) DEFAULT NULL,
  `beatscheduleid` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `check_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `check_in_user_id_index` (`user_id`),
  ADD KEY `check_in_checkin_date_index` (`checkin_date`),
  ADD KEY `check_in_checkin_time_index` (`checkin_time`),
  ADD KEY `check_in_checkin_latitude_index` (`checkin_latitude`),
  ADD KEY `check_in_checkin_longitude_index` (`checkin_longitude`),
  ADD KEY `check_in_checkin_address_index` (`checkin_address`),
  ADD KEY `check_in_checkout_date_index` (`checkout_date`),
  ADD KEY `check_in_checkout_time_index` (`checkout_time`),
  ADD KEY `check_in_checkout_latitude_index` (`checkout_latitude`),
  ADD KEY `check_in_checkout_longitude_index` (`checkout_longitude`),
  ADD KEY `check_in_checkout_address_index` (`checkout_address`),
  ADD KEY `check_in_distance_index` (`distance`),
  ADD KEY `check_in_beatscheduleid_index` (`beatscheduleid`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `check_in`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `check_in`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
