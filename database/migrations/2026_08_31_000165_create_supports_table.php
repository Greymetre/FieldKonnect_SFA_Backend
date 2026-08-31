<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `supports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `subject` varchar(200) NOT NULL DEFAULT '',
  `description` varchar(450) NOT NULL DEFAULT '',
  `full_name` varchar(450) NOT NULL DEFAULT '',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `isoverdue` int(11) NOT NULL DEFAULT 0,
  `reopened` int(11) NOT NULL DEFAULT 0,
  `isanswered` int(11) NOT NULL DEFAULT 0,
  `is_transferred` tinyint(4) NOT NULL DEFAULT 0,
  `assigned_at` datetime DEFAULT NULL,
  `transferred_at` datetime DEFAULT NULL,
  `reopened_at` datetime DEFAULT NULL,
  `duedate` datetime DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `last_message_at` datetime DEFAULT NULL,
  `lock_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `supports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supports_subject_index` (`subject`),
  ADD KEY `supports_description_index` (`description`),
  ADD KEY `supports_full_name_index` (`full_name`),
  ADD KEY `supports_user_id_index` (`user_id`),
  ADD KEY `supports_status_id_index` (`status_id`),
  ADD KEY `supports_customer_id_index` (`customer_id`),
  ADD KEY `supports_assigned_to_index` (`assigned_to`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `supports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `supports`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
