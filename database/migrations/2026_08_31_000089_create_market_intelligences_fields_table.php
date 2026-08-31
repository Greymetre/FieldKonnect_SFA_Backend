<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `market_intelligences_fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `ranking` int(11) NOT NULL DEFAULT 1,
  `field_name` varchar(250) NOT NULL DEFAULT '',
  `field_type` varchar(250) NOT NULL DEFAULT '',
  `is_required` varchar(10) NOT NULL DEFAULT 'false',
  `is_multiple` varchar(10) NOT NULL DEFAULT 'false',
  `label_name` varchar(250) DEFAULT '',
  `placeholder` varchar(250) DEFAULT '',
  `key` varchar(250) DEFAULT NULL,
  `module` varchar(250) DEFAULT '',
  `input_type` varchar(250) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `market_intelligences_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `market_intelligences_fields_field_name_index` (`field_name`),
  ADD KEY `market_intelligences_fields_field_type_index` (`field_type`),
  ADD KEY `market_intelligences_fields_label_name_index` (`label_name`),
  ADD KEY `market_intelligences_fields_placeholder_index` (`placeholder`),
  ADD KEY `market_intelligences_fields_module_index` (`module`),
  ADD KEY `market_intelligences_fields_created_by_index` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `market_intelligences_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `market_intelligences_fields`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
