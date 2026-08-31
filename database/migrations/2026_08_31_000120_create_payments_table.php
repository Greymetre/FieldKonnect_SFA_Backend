<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(200) NOT NULL DEFAULT '',
  `payment_date` date DEFAULT NULL,
  `payment_mode` varchar(200) NOT NULL DEFAULT '',
  `payment_type` varchar(200) NOT NULL DEFAULT '',
  `bank_name` varchar(200) NOT NULL DEFAULT '',
  `reference_no` varchar(200) NOT NULL DEFAULT '',
  `amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `response` varchar(500) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `file_path` varchar(500) NOT NULL DEFAULT '',
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_user_id_index` (`user_id`),
  ADD KEY `payments_customer_id_index` (`customer_id`),
  ADD KEY `payments_customer_name_index` (`customer_name`),
  ADD KEY `payments_payment_date_index` (`payment_date`),
  ADD KEY `payments_payment_mode_index` (`payment_mode`),
  ADD KEY `payments_payment_type_index` (`payment_type`),
  ADD KEY `payments_bank_name_index` (`bank_name`),
  ADD KEY `payments_reference_no_index` (`reference_no`),
  ADD KEY `payments_amount_index` (`amount`),
  ADD KEY `payments_response_index` (`response`),
  ADD KEY `payments_description_index` (`description`),
  ADD KEY `payments_file_path_index` (`file_path`),
  ADD KEY `payments_status_id_index` (`status_id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `payments`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
