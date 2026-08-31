<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `customer_process_steps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_process_id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `customer_process_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_process_steps_customer_process_id_foreign` (`customer_process_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `customer_process_steps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `customer_process_steps`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
