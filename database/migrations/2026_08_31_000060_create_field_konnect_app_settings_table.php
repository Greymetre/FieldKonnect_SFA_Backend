<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `field_konnect_app_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `app_version` varchar(255) NOT NULL,
  `order_discount_limit` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `app_ios_version` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `field_konnect_app_settings`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `field_konnect_app_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `field_konnect_app_settings`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
