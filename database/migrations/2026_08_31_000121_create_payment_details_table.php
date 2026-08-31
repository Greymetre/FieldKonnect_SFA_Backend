<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `payment_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sales_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_no` varchar(200) NOT NULL DEFAULT '',
  `amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_details_payment_id_index` (`payment_id`),
  ADD KEY `payment_details_sales_id_index` (`sales_id`),
  ADD KEY `payment_details_invoice_no_index` (`invoice_no`),
  ADD KEY `payment_details_amount_index` (`amount`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `payment_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `payment_details`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
