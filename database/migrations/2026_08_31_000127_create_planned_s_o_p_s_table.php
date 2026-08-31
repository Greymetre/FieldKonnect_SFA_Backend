<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `planned_s_o_p_s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `planning_month` date DEFAULT NULL,
  `order_id` varchar(100) DEFAULT NULL,
  `division_id` bigint(20) DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `opening_stock` varchar(255) DEFAULT NULL,
  `open_order_qty` varchar(255) DEFAULT '0',
  `production_qty` varchar(255) DEFAULT '0',
  `plan_next_month` int(11) DEFAULT NULL,
  `budget_for_month` bigint(20) DEFAULT NULL,
  `last_month_sale` bigint(20) DEFAULT NULL,
  `last_three_month_avg` bigint(20) DEFAULT NULL,
  `last_year_month_sale` bigint(20) DEFAULT NULL,
  `sku_unit_price` int(11) DEFAULT NULL,
  `s_op_val` int(11) DEFAULT NULL,
  `top_sku` varchar(255) DEFAULT NULL,
  `plan_next_month_value` varchar(255) DEFAULT NULL,
  `dispatch_against_plan` int(11) DEFAULT 0,
  `created_by` varchar(255) DEFAULT NULL,
  `verify_by` varchar(255) DEFAULT NULL,
  `view_only` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `planned_s_o_p_s`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_planning_month` (`planning_month`),
  ADD KEY `idx_division_id` (`division_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `planned_s_o_p_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `planned_s_o_p_s`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
