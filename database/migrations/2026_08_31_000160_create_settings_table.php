<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `title` varchar(250) NOT NULL DEFAULT '',
  `key_name` varchar(250) NOT NULL DEFAULT '',
  `value` varchar(250) NOT NULL DEFAULT '',
  `module` varchar(200) NOT NULL DEFAULT '',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `settings`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
