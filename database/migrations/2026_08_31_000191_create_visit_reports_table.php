<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `visit_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `checkin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `visit_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `report_title` varchar(200) NOT NULL DEFAULT '',
  `description` varchar(450) NOT NULL DEFAULT '',
  `visit_image` varchar(450) NOT NULL DEFAULT '',
  `next_visit` datetime DEFAULT NULL,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `visit_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visit_reports_checkin_id_index` (`checkin_id`),
  ADD KEY `visit_reports_user_id_index` (`user_id`),
  ADD KEY `visit_reports_customer_id_index` (`customer_id`),
  ADD KEY `visit_reports_visit_type_id_index` (`visit_type_id`),
  ADD KEY `visit_reports_report_title_index` (`report_title`),
  ADD KEY `visit_reports_description_index` (`description`),
  ADD KEY `visit_reports_visit_image_index` (`visit_image`),
  ADD KEY `visit_reports_next_visit_index` (`next_visit`),
  ADD KEY `visit_reports_status_id_index` (`status_id`),
  ADD KEY `visit_reports_created_by_index` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `visit_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `visit_reports`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
