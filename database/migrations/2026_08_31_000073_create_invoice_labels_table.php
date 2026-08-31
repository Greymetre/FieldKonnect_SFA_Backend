<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `invoice_labels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_setting_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `page_heading` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `page` tinyint(4) NOT NULL CHECK (`page` between 2 and 5),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `invoice_labels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_labels_invoice_setting_id_foreign` (`invoice_setting_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `invoice_labels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `invoice_labels`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
