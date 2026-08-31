<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `scheme_id` bigint(20) UNSIGNED DEFAULT NULL,
  `schemedetail_id` bigint(20) UNSIGNED DEFAULT NULL,
  `points` bigint(20) NOT NULL DEFAULT 0,
  `point_type` varchar(20) NOT NULL DEFAULT '',
  `invoice_amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `invoice_no` varchar(200) NOT NULL DEFAULT '',
  `coupon_code` varchar(250) NOT NULL DEFAULT '',
  `invoice_date` date DEFAULT NULL,
  `transaction_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `transaction_type` varchar(20) NOT NULL DEFAULT '',
  `sales_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `checkinid` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` bigint(20) NOT NULL DEFAULT 0,
  `userid` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallets_customer_id_index` (`customer_id`),
  ADD KEY `wallets_scheme_id_index` (`scheme_id`),
  ADD KEY `wallets_schemedetail_id_index` (`schemedetail_id`),
  ADD KEY `wallets_points_index` (`points`),
  ADD KEY `wallets_point_type_index` (`point_type`),
  ADD KEY `wallets_invoice_amount_index` (`invoice_amount`),
  ADD KEY `wallets_invoice_no_index` (`invoice_no`),
  ADD KEY `wallets_coupon_code_index` (`coupon_code`),
  ADD KEY `wallets_invoice_date_index` (`invoice_date`),
  ADD KEY `wallets_transaction_type_index` (`transaction_type`),
  ADD KEY `wallets_sales_id_index` (`sales_id`),
  ADD KEY `wallets_status_id_index` (`status_id`),
  ADD KEY `wallets_checkinid_index` (`checkinid`),
  ADD KEY `wallets_quantity_index` (`quantity`),
  ADD KEY `wallets_userid_index` (`userid`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `wallets`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
