<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `scheme_headers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `scheme_name` varchar(250) NOT NULL,
  `scheme_description` varchar(450) NOT NULL DEFAULT '',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `scheme_basedon` varchar(125) DEFAULT NULL,
  `assign_to` varchar(125) DEFAULT NULL,
  `branch` varchar(125) DEFAULT NULL,
  `state` varchar(125) DEFAULT NULL,
  `customer` varchar(125) DEFAULT NULL,
  `customer_type` int(11) DEFAULT NULL,
  `points_start_date` date DEFAULT NULL,
  `points_end_date` date DEFAULT NULL,
  `block_points` bigint(20) NOT NULL DEFAULT 0,
  `block_percents` bigint(20) NOT NULL DEFAULT 0,
  `scheme_image` varchar(450) NOT NULL DEFAULT '',
  `scheme_type` varchar(200) NOT NULL DEFAULT '',
  `point_value` decimal(8,2) NOT NULL DEFAULT 0.00,
  `regions` varchar(450) NOT NULL DEFAULT '',
  `redeem_percents` tinyint(4) NOT NULL DEFAULT 0,
  `schemes` varchar(450) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `scheme_headers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scheme_headers_scheme_name_index` (`scheme_name`),
  ADD KEY `scheme_headers_scheme_description_index` (`scheme_description`),
  ADD KEY `scheme_headers_start_date_index` (`start_date`),
  ADD KEY `scheme_headers_end_date_index` (`end_date`),
  ADD KEY `scheme_headers_scheme_image_index` (`scheme_image`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `scheme_headers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `scheme_headers`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
