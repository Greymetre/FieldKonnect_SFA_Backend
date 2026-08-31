<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `lead_check_in` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `lead_id` bigint(20) UNSIGNED DEFAULT NULL,
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
  `checkout_note` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `distance` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `lead_check_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `check_in_lead_id_foreign` (`lead_id`),
  ADD KEY `check_in_user_id_foreign` (`user_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `lead_check_in`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `lead_check_in`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
