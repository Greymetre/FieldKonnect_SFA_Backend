<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `coupon_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `profile_name` varchar(250) NOT NULL,
  `coupon_length` varchar(250) NOT NULL DEFAULT '8',
  `excluding_character` varchar(450) NOT NULL DEFAULT '',
  `coupon_count` varchar(50) NOT NULL DEFAULT '',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `coupon_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_profiles_profile_name_index` (`profile_name`),
  ADD KEY `coupon_profiles_created_by_index` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `coupon_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `coupon_profiles`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
