<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `dealer_appointment_kycs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `appointment_id` bigint(20) DEFAULT NULL,
  `channel_partner` varchar(255) DEFAULT NULL,
  `place` varchar(125) DEFAULT NULL,
  `concerned_branch` varchar(255) DEFAULT NULL,
  `dealer_code` varchar(255) DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `proprietary_concern` varchar(255) DEFAULT NULL,
  `partnership_firm` varchar(255) DEFAULT NULL,
  `ltd_pvt` varchar(255) DEFAULT NULL,
  `distribution_channel` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `dealer_appointment_kycs`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `dealer_appointment_kycs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `dealer_appointment_kycs`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
