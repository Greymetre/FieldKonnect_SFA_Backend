<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `call_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(255) DEFAULT NULL,
  `started_at` datetime NOT NULL COMMENT 'Call start date & time',
  `duration` int(11) NOT NULL DEFAULT 0 COMMENT 'Duration in seconds',
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = No Response, 1 = Received',
  `remark` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `plivo_status` varchar(50) DEFAULT NULL,
  `plivo_call_uuid` varchar(255) DEFAULT NULL,
  `plivo_b_leg_uuid` varchar(255) DEFAULT NULL,
  `recording_url` text DEFAULT NULL,
  `recording_id` varchar(255) DEFAULT NULL,
  `cost` decimal(12,6) DEFAULT NULL,
  `answered_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `webhook_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `call_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `call_logs_plivo_call_uuid_unique` (`plivo_call_uuid`),
  ADD UNIQUE KEY `call_logs_webhook_token_unique` (`webhook_token`),
  ADD KEY `call_logs_plivo_status_index` (`plivo_status`),
  ADD KEY `call_logs_plivo_b_leg_uuid_index` (`plivo_b_leg_uuid`),
  ADD KEY `call_logs_recording_id_index` (`recording_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `call_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `call_logs`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
