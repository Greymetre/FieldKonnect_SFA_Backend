<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `order_schemes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `scheme_name` varchar(250) NOT NULL,
  `scheme_description` longtext DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `repetition` int(11) DEFAULT NULL,
  `day_repeat` longtext DEFAULT NULL,
  `week_repeat` longtext DEFAULT NULL,
  `customer_type` varchar(255) DEFAULT NULL,
  `scheme_type` varchar(200) DEFAULT NULL,
  `scheme_basedon` varchar(200) DEFAULT NULL,
  `assign_to` varchar(200) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `customer` varchar(255) DEFAULT NULL,
  `minimum` bigint(20) DEFAULT NULL,
  `maximum` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `order_schemes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_schemes_scheme_name_index` (`scheme_name`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `order_schemes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `order_schemes`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
