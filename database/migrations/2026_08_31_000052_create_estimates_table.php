<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `estimates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `place_of_supply` varchar(255) DEFAULT NULL,
  `estimate_no` varchar(255) NOT NULL,
  `order_no` varchar(255) DEFAULT NULL,
  `estimate_date` date NOT NULL,
  `payment_term` varchar(255) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('amount','percentage') DEFAULT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tds` int(15) DEFAULT 0,
  `tds_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `adjustment` decimal(15,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `customer_notes` text DEFAULT NULL,
  `t_c` text DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `estimates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `estimate_no` (`estimate_no`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `user_id` (`user_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `estimates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `estimates`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
