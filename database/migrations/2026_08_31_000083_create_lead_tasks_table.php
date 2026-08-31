<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `lead_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `priority` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `open_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `close_date` date DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `lead_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_tasks_lead_id_foreign` (`lead_id`),
  ADD KEY `lead_tasks_assigned_to_foreign` (`assigned_to`),
  ADD KEY `lead_tasks_created_by_foreign` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `lead_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `lead_tasks`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
