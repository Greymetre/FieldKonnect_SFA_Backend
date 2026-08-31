<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `tour_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tour_programme_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `performed_by` bigint(20) UNSIGNED NOT NULL,
  `remark` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `tour_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tour_log_programme` (`tour_programme_id`),
  ADD KEY `fk_tour_log_user` (`performed_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `tour_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `tour_logs`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
