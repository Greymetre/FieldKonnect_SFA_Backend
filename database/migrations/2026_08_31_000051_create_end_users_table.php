<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `end_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_number` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_address` varchar(255) DEFAULT NULL,
  `customer_place` varchar(255) DEFAULT NULL,
  `customer_pindcode` varchar(255) DEFAULT NULL,
  `customer_country` varchar(255) DEFAULT NULL,
  `customer_state` varchar(255) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `customer_district` varchar(255) DEFAULT NULL,
  `customer_city` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `end_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `end_users_customer_number_unique` (`customer_number`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `end_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `end_users`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
