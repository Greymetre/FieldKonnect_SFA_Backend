<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `user_logins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entry_from` varchar(250) NOT NULL DEFAULT '',
  `provider` varchar(250) NOT NULL DEFAULT '',
  `mobile` varchar(250) NOT NULL DEFAULT '',
  `login_at` datetime DEFAULT NULL,
  `logout_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `user_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_logins_user_id_index` (`user_id`),
  ADD KEY `user_logins_entry_from_index` (`entry_from`),
  ADD KEY `user_logins_provider_index` (`provider`),
  ADD KEY `user_logins_mobile_index` (`mobile`),
  ADD KEY `user_logins_login_at_index` (`login_at`),
  ADD KEY `user_logins_logout_at_index` (`logout_at`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `user_logins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `user_logins`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
