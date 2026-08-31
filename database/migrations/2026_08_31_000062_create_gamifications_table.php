<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `gamifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(150) NOT NULL,
  `points` bigint(20) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `gamifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gamifications_user_id_index` (`user_id`),
  ADD KEY `gamifications_customer_id_index` (`customer_id`),
  ADD KEY `gamifications_type_index` (`type`),
  ADD KEY `gamifications_points_index` (`points`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `gamifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `gamifications`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
