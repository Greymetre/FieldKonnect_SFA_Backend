<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `warranty_activations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_serail_number` varchar(255) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `end_user_id` bigint(20) DEFAULT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `sale_bill_no` varchar(255) DEFAULT NULL,
  `sale_bill_date` date DEFAULT NULL,
  `warranty_date` date DEFAULT NULL,
  `remark` varchar(125) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `warranty_activations`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `warranty_activations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `warranty_activations`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
