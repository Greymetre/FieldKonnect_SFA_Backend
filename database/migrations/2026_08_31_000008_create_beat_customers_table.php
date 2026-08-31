<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `beat_customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `beat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `distributor_id` bigint(20) DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `beat_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beat_customers_beat_id_index` (`beat_id`),
  ADD KEY `beat_customers_customer_id_index` (`customer_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `beat_customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `beat_customers`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
