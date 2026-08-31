<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `customer_custom_fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `field_key` varchar(255) DEFAULT NULL,
  `field_type` varchar(255) NOT NULL DEFAULT 'Input',
  `description` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `customer_custom_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_custom_fields_field_key_unique` (`field_key`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `customer_custom_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `customer_custom_fields`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
