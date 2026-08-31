<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `ranking` int(11) NOT NULL DEFAULT 1,
  `field_name` varchar(250) NOT NULL DEFAULT '',
  `field_type` varchar(250) NOT NULL DEFAULT '',
  `is_required` varchar(10) NOT NULL DEFAULT 'false',
  `is_multiple` varchar(10) NOT NULL DEFAULT 'false',
  `label_name` varchar(250) NOT NULL DEFAULT '',
  `placeholder` varchar(250) NOT NULL DEFAULT '',
  `module` varchar(250) NOT NULL DEFAULT '',
  `division_id` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fields_field_name_index` (`field_name`),
  ADD KEY `fields_field_type_index` (`field_type`),
  ADD KEY `fields_label_name_index` (`label_name`),
  ADD KEY `fields_placeholder_index` (`placeholder`),
  ADD KEY `fields_module_index` (`module`),
  ADD KEY `fields_created_by_index` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `fields`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
