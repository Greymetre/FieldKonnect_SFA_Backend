<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_code` varchar(255) DEFAULT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `party_name` varchar(255) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `bp_code` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_description` text DEFAULT NULL,
  `product_store` varchar(125) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `new_group` varchar(255) DEFAULT NULL,
  `serial_no` varchar(255) DEFAULT NULL,
  `narration` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serial_no` (`serial_no`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `services`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
