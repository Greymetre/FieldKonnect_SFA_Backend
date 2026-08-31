<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `primary_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) DEFAULT 'Y',
  `invoiceno` varchar(250) DEFAULT '',
  `invoice_date` date DEFAULT NULL,
  `month` varchar(50) DEFAULT '',
  `division` varchar(50) DEFAULT '',
  `dealer` varchar(125) DEFAULT '',
  `customer_id` varchar(125) DEFAULT NULL,
  `city` varchar(50) DEFAULT '',
  `state` varchar(50) DEFAULT '',
  `final_branch` varchar(250) DEFAULT '',
  `branch_id` varchar(125) DEFAULT NULL,
  `sales_person` varchar(250) DEFAULT '',
  `emp_code` varchar(225) DEFAULT NULL,
  `model_name` varchar(225) DEFAULT NULL,
  `item_no` varchar(125) DEFAULT NULL,
  `product_name` varchar(250) DEFAULT '',
  `group_code` varchar(250) DEFAULT NULL,
  `itm_group_name` varchar(250) DEFAULT NULL,
  `tax_code` varchar(250) DEFAULT NULL,
  `quantity` bigint(20) DEFAULT 0,
  `rate` decimal(19,2) DEFAULT 0.00,
  `lp` decimal(19,2) DEFAULT NULL,
  `net_amount` decimal(19,2) DEFAULT 0.00,
  `tax_amount` decimal(19,2) DEFAULT 0.00,
  `cgst_amount` decimal(19,2) DEFAULT 0.00,
  `sgst_amount` decimal(19,2) DEFAULT 0.00,
  `igst_amount` decimal(19,2) DEFAULT 0.00,
  `sinv_gst_amt` decimal(19,2) DEFAULT 0.00,
  `total_amount` decimal(19,2) DEFAULT 0.00,
  `store_name` varchar(250) DEFAULT '',
  `group_name` varchar(250) DEFAULT '',
  `new_group` varchar(255) DEFAULT NULL,
  `branch` varchar(250) DEFAULT '',
  `new_group_name` varchar(250) DEFAULT '',
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `group_1` varchar(225) DEFAULT NULL,
  `group_2` varchar(225) DEFAULT NULL,
  `group_3` varchar(225) DEFAULT NULL,
  `group_4` varchar(225) DEFAULT NULL,
  `sap_code` varchar(225) DEFAULT NULL,
  `new_product` varchar(225) DEFAULT NULL,
  `new_dealer` varchar(225) DEFAULT NULL,
  `bp_code` varchar(225) DEFAULT NULL,
  `document_status` varchar(225) DEFAULT NULL,
  `canceled` varchar(225) DEFAULT NULL,
  `remarks` varchar(225) DEFAULT NULL,
  `serial_no` text DEFAULT NULL,
  `sell_from` text DEFAULT NULL,
  `delete_this` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `primary_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `primary_sales_invoiceno_index` (`invoiceno`),
  ADD KEY `primary_sales_invoice_date_index` (`invoice_date`),
  ADD KEY `primary_sales_quantity_index` (`quantity`),
  ADD KEY `primary_sales_rate_index` (`rate`),
  ADD KEY `primary_sales_net_amount_index` (`net_amount`),
  ADD KEY `primary_sales_total_amount_index` (`total_amount`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `primary_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `primary_sales`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
