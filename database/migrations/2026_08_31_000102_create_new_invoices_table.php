<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `new_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `secondary_customer_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `points` decimal(15,2) NOT NULL DEFAULT 0.00,
  `approval_status` tinyint(4) NOT NULL DEFAULT 0,
  `approval_remark` text DEFAULT NULL,
  `approved_ss_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_ss_at` timestamp NULL DEFAULT NULL,
  `approved_sales_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_sales_at` timestamp NULL DEFAULT NULL,
  `approved_ho_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_ho_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `new_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `new_invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `new_invoices_secondary_customer_id_foreign` (`secondary_customer_id`),
  ADD KEY `new_invoices_created_by_foreign` (`created_by`),
  ADD KEY `new_invoices_approval_status_index` (`approval_status`),
  ADD KEY `new_invoices_approved_ss_by_index` (`approved_ss_by`),
  ADD KEY `new_invoices_approved_sales_by_index` (`approved_sales_by`),
  ADD KEY `new_invoices_approved_ho_by_index` (`approved_ho_by`),
  ADD KEY `new_invoices_rejected_by_index` (`rejected_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `new_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `new_invoices`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
