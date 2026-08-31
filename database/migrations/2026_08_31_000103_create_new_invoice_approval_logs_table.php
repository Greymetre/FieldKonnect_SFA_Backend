<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `new_invoice_approval_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_date` date DEFAULT NULL,
  `new_invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status_type` varchar(255) DEFAULT NULL,
  `from_status` tinyint(4) DEFAULT NULL,
  `to_status` tinyint(4) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `new_invoice_approval_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `new_invoice_approval_logs_new_invoice_id_index` (`new_invoice_id`),
  ADD KEY `new_invoice_approval_logs_created_by_index` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `new_invoice_approval_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `new_invoice_approval_logs`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
