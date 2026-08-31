<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `punchin_date` date NOT NULL,
  `punchin_time` time NOT NULL,
  `punchin_longitude` varchar(250) DEFAULT NULL,
  `punchin_latitude` varchar(250) DEFAULT NULL,
  `punchin_address` varchar(250) NOT NULL DEFAULT '',
  `punchin_image` varchar(400) NOT NULL DEFAULT '',
  `punchout_date` date DEFAULT NULL,
  `punchout_time` time DEFAULT NULL,
  `punchout_latitude` varchar(250) DEFAULT NULL,
  `punchout_longitude` varchar(250) DEFAULT NULL,
  `punchout_address` varchar(250) NOT NULL DEFAULT '',
  `punchout_image` varchar(400) NOT NULL DEFAULT '',
  `punchin_summary` varchar(255) NOT NULL DEFAULT '',
  `punchout_summary` varchar(255) NOT NULL DEFAULT '',
  `flag` varchar(255) DEFAULT NULL,
  `worked_time` varchar(50) NOT NULL DEFAULT '',
  `attendance_status` tinyint(4) NOT NULL DEFAULT 0,
  `beat_id` varchar(125) DEFAULT NULL,
  `punchin_from` varchar(125) DEFAULT NULL,
  `remark_status` varchar(191) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `approve_reject_by` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `working_type` varchar(400) NOT NULL DEFAULT 'fields',
  `tourid` varchar(255) DEFAULT NULL,
  `city` varchar(3000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_user_id_index` (`user_id`),
  ADD KEY `attendances_punchin_date_index` (`punchin_date`),
  ADD KEY `attendances_punchin_time_index` (`punchin_time`),
  ADD KEY `attendances_punchin_longitude_index` (`punchin_longitude`),
  ADD KEY `attendances_punchin_latitude_index` (`punchin_latitude`),
  ADD KEY `attendances_punchin_address_index` (`punchin_address`),
  ADD KEY `attendances_punchin_image_index` (`punchin_image`),
  ADD KEY `attendances_punchout_date_index` (`punchout_date`),
  ADD KEY `attendances_punchout_time_index` (`punchout_time`),
  ADD KEY `attendances_punchout_latitude_index` (`punchout_latitude`),
  ADD KEY `attendances_punchout_longitude_index` (`punchout_longitude`),
  ADD KEY `attendances_punchout_address_index` (`punchout_address`),
  ADD KEY `attendances_punchout_image_index` (`punchout_image`),
  ADD KEY `attendances_punchin_summary_index` (`punchin_summary`),
  ADD KEY `attendances_punchout_summary_index` (`punchout_summary`),
  ADD KEY `attendances_worked_time_index` (`worked_time`),
  ADD KEY `attendances_working_type_index` (`working_type`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `attendances`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
