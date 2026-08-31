<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `service_group_complaints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subcategory_id` bigint(20) UNSIGNED NOT NULL,
  `service_bill_complaint_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `service_group_complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_group_complaints_subcategory_id_foreign` (`subcategory_id`),
  ADD KEY `service_group_complaints_service_bill_complaint_id_foreign` (`service_bill_complaint_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `service_group_complaints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `service_group_complaints`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
