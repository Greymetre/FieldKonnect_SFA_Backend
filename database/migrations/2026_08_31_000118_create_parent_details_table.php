<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `parent_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `customer_id` bigint(20) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `parent_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_details_created_by_index` (`created_by`),
  ADD KEY `parent_details_updated_by_index` (`updated_by`),
  ADD KEY `parent_details_customer_id_index` (`customer_id`),
  ADD KEY `parent_details_parent_id_index` (`parent_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `parent_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `parent_details`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
