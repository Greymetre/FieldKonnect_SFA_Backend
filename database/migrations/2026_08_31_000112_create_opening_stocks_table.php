<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `opening_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_code` varchar(255) DEFAULT NULL,
  `item_description` varchar(255) DEFAULT NULL,
  `item_group` varchar(255) DEFAULT NULL,
  `ware_house_name` varchar(255) DEFAULT NULL,
  `branch_id` varchar(255) DEFAULT NULL,
  `opening_stocks` int(11) NOT NULL DEFAULT 0,
  `open_order_qty` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `opening_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `opening_stocks_branch_id_foreign` (`branch_id`(8));
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `opening_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `opening_stocks`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
