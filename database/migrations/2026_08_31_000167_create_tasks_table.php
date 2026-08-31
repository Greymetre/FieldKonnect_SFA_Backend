<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(300) NOT NULL DEFAULT '',
  `descriptions` varchar(255) NOT NULL DEFAULT '',
  `task_department_id` bigint(20) DEFAULT NULL,
  `task_type` varchar(50) DEFAULT NULL,
  `task_project_id` bigint(50) DEFAULT NULL,
  `task_priority_id` bigint(50) DEFAULT NULL,
  `lead_id` bigint(50) DEFAULT NULL,
  `due_datetime` datetime DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `reminder` datetime DEFAULT NULL,
  `open_datetime` datetime DEFAULT NULL,
  `inprogress_datetime` datetime DEFAULT NULL,
  `reopen_datetime` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `task_status` enum('Pending','Open','In progress','Completed','Reopen') NOT NULL DEFAULT 'Pending',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_user_id_index` (`user_id`),
  ADD KEY `tasks_title_index` (`title`),
  ADD KEY `tasks_descriptions_index` (`descriptions`),
  ADD KEY `tasks_datetime_index` (`datetime`),
  ADD KEY `tasks_reminder_index` (`reminder`),
  ADD KEY `tasks_customer_id_index` (`customer_id`),
  ADD KEY `tasks_status_id_index` (`status_id`),
  ADD KEY `tasks_created_by_index` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `tasks`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
