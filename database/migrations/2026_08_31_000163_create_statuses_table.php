<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `status_name` varchar(200) NOT NULL,
  `display_name` varchar(250) NOT NULL DEFAULT '',
  `status_message` varchar(400) NOT NULL,
  `module` varchar(250) NOT NULL DEFAULT '',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `statuses_status_name_unique` (`status_name`),
  ADD KEY `statuses_display_name_index` (`display_name`),
  ADD KEY `statuses_created_by_index` (`created_by`),
  ADD KEY `statuses_updated_by_index` (`updated_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `statuses`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
