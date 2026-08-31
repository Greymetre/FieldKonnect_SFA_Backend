<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'leave',
  `bal_type` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `reason` varchar(255) NOT NULL DEFAULT '',
  `remark_status` varchar(125) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leaves_user_id_index` (`user_id`),
  ADD KEY `leaves_from_date_index` (`from_date`),
  ADD KEY `leaves_to_date_index` (`to_date`),
  ADD KEY `leaves_type_index` (`type`),
  ADD KEY `leaves_reason_index` (`reason`),
  ADD KEY `leaves_created_by_index` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `leaves`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
