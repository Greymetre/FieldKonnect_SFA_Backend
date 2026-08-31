<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `redemptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `redeem_mode` varchar(255) NOT NULL,
  `account_holder` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `ifsc_code` varchar(255) DEFAULT NULL,
  `redeem_amount` varchar(255) DEFAULT NULL,
  `gift_id` bigint(20) DEFAULT NULL,
  `product_send` varchar(225) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `dispatch_number` varchar(125) DEFAULT NULL,
  `remark` varchar(125) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `approve_date` date DEFAULT NULL,
  `dispatch_date` date DEFAULT NULL,
  `gift_recived_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deatils` varchar(225) DEFAULT NULL,
  `invoice_number` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `redemptions`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `redemptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `redemptions`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
