<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `complaints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `complaint_number` varchar(255) DEFAULT NULL,
  `complaint_date` date DEFAULT NULL,
  `claim_amount` varchar(125) DEFAULT NULL,
  `complaint_status` int(11) NOT NULL DEFAULT 0,
  `seller` varchar(225) DEFAULT NULL,
  `end_user_id` bigint(20) DEFAULT NULL,
  `party_name` bigint(20) DEFAULT NULL,
  `product_laying` varchar(255) DEFAULT NULL,
  `service_center` bigint(20) DEFAULT NULL,
  `assign_user` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `product_serail_number` varchar(255) DEFAULT NULL,
  `product_code` varchar(255) DEFAULT NULL,
  `product_name` varchar(125) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `specification` varchar(255) DEFAULT NULL,
  `product_no` varchar(255) DEFAULT NULL,
  `phase` varchar(255) DEFAULT NULL,
  `seller_branch` varchar(255) DEFAULT NULL,
  `purchased_branch` varchar(255) DEFAULT NULL,
  `product_group` varchar(255) DEFAULT NULL,
  `company_sale_bill_no` varchar(255) DEFAULT NULL,
  `company_sale_bill_date` date DEFAULT NULL,
  `customer_bill_date` date DEFAULT NULL,
  `customer_bill_no` varchar(255) DEFAULT NULL,
  `company_bill_date_month` varchar(255) DEFAULT NULL,
  `under_warranty` varchar(255) DEFAULT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `customer_bill_date_month` varchar(255) DEFAULT NULL,
  `warranty_bill` varchar(255) DEFAULT NULL,
  `fault_type` varchar(255) DEFAULT NULL,
  `service_centre_remark` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `register_by` varchar(255) DEFAULT NULL,
  `complaint_type` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_by_device` varchar(125) DEFAULT NULL,
  `complaint_recieve_via` varchar(125) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `complaints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `complaints`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
