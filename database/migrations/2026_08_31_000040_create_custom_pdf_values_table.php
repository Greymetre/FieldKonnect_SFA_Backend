<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `custom_pdf_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estimate_id` bigint(20) UNSIGNED NOT NULL,
  `label_id` bigint(20) UNSIGNED NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `custom_pdf_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_pdf_values_estimate_id_index` (`estimate_id`),
  ADD KEY `custom_pdf_values_label_id_index` (`label_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `custom_pdf_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `custom_pdf_values`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
