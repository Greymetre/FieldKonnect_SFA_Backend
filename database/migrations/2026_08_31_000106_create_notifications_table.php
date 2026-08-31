<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `type` varchar(250) NOT NULL DEFAULT '',
  `data` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `model` varchar(100) NOT NULL DEFAULT 'general',
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delivery_status` varchar(30) NOT NULL DEFAULT 'pending',
  `sent_at` timestamp NULL DEFAULT NULL,
  `failure_reason` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_type_index` (`type`),
  ADD KEY `notifications_user_id_index` (`user_id`),
  ADD KEY `notifications_customer_id_index` (`customer_id`),
  ADD KEY `notifications_read_index` (`read`),
  ADD KEY `notifications_model_index` (`model`),
  ADD KEY `notifications_model_id_index` (`model_id`),
  ADD KEY `notifications_delivery_status_index` (`delivery_status`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `notifications`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
