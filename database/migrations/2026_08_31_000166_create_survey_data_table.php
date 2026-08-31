<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `survey_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `field_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `value` varchar(400) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `survey_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_data_field_id_index` (`field_id`),
  ADD KEY `survey_data_customer_id_index` (`customer_id`),
  ADD KEY `survey_data_value_index` (`value`),
  ADD KEY `survey_data_created_by_index` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `survey_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `survey_data`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
