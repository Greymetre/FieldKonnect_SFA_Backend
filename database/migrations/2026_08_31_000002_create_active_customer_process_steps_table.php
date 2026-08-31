<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `active_customer_process_steps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active_customer_process_id` bigint(20) UNSIGNED NOT NULL,
  `customer_process_step_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','active','completed') NOT NULL DEFAULT 'pending',
  `completed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `active_customer_process_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active_customer_process_steps_active_customer_process_id_foreign` (`active_customer_process_id`),
  ADD KEY `active_customer_process_steps_customer_process_step_id_foreign` (`customer_process_step_id`),
  ADD KEY `active_customer_process_steps_completed_by_foreign` (`completed_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `active_customer_process_steps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `active_customer_process_steps`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
