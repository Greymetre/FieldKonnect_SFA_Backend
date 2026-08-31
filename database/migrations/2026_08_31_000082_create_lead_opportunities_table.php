<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `lead_opportunities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `lead_contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` double(10,2) NOT NULL DEFAULT 0.00,
  `type` varchar(255) DEFAULT NULL,
  `estimated_close_date` date DEFAULT NULL,
  `confidence` int(11) NOT NULL DEFAULT 0 COMMENT 'Confidence level from 0 to 100 in percentage',
  `note` longtext DEFAULT NULL,
  `status` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `lead_opportunities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_opportunities_lead_id_foreign` (`lead_id`),
  ADD KEY `lead_opportunities_assigned_to_foreign` (`assigned_to`),
  ADD KEY `lead_opportunities_lead_contact_id_foreign` (`lead_contact_id`),
  ADD KEY `lead_opportunities_created_by_foreign` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `lead_opportunities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `lead_opportunities`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
