<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `shipping_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `address1` varchar(250) NOT NULL DEFAULT '',
  `address2` varchar(250) NOT NULL DEFAULT '',
  `landmark` varchar(250) NOT NULL DEFAULT '',
  `locality` varchar(250) NOT NULL DEFAULT '',
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `state_id` bigint(20) UNSIGNED DEFAULT NULL,
  `district_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pincode_id` bigint(20) UNSIGNED DEFAULT NULL,
  `zipcode` varchar(250) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `shipping_addresses`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
