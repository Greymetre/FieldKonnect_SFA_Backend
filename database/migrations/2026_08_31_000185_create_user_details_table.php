<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `user_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `marital_status` varchar(50) DEFAULT '',
  `salary` decimal(19,2) NOT NULL DEFAULT 0.00,
  `ctc_annual` double(10,2) NOT NULL DEFAULT 0.00,
  `gross_salary_monthly` double(10,2) NOT NULL DEFAULT 0.00,
  `last_year_increments` varchar(191) DEFAULT NULL,
  `last_year_increment_percent` varchar(191) DEFAULT NULL,
  `last_year_increment_value` double(10,2) NOT NULL DEFAULT 0.00,
  `last_promotion` varchar(191) DEFAULT NULL,
  `order_mails` text DEFAULT NULL,
  `order_mails_type` varchar(200) DEFAULT NULL,
  `probation_period` date DEFAULT NULL,
  `date_of_confirmation` date DEFAULT NULL,
  `notice_period` varchar(191) DEFAULT NULL,
  `date_of_leaving` date DEFAULT NULL,
  `father_name` varchar(191) DEFAULT NULL,
  `father_date_of_birth` date DEFAULT NULL,
  `mother_name` varchar(191) DEFAULT NULL,
  `mother_date_of_birth` date DEFAULT NULL,
  `spouse_name` varchar(191) DEFAULT NULL,
  `spouse_date_of_birth` date DEFAULT NULL,
  `marriage_anniversary` date DEFAULT NULL,
  `children_one` varchar(191) DEFAULT NULL,
  `children_one_date_of_birth` date DEFAULT NULL,
  `children_two` varchar(191) DEFAULT NULL,
  `children_two_date_of_birth` date DEFAULT NULL,
  `children_three` varchar(191) DEFAULT NULL,
  `children_three_date_of_birth` date DEFAULT NULL,
  `children_four` varchar(191) DEFAULT NULL,
  `children_four_date_of_birth` date DEFAULT NULL,
  `children_five` varchar(191) DEFAULT NULL,
  `children_five_date_of_birth` date DEFAULT NULL,
  `pan_number` varchar(191) DEFAULT NULL,
  `aadhar_number` varchar(191) DEFAULT NULL,
  `pan_card_image` varchar(225) DEFAULT NULL,
  `aadhar_card_image` varchar(225) DEFAULT NULL,
  `emergency_number` varchar(191) DEFAULT NULL,
  `current_address` varchar(191) DEFAULT NULL,
  `permanent_address` varchar(191) DEFAULT NULL,
  `biometric_code` varchar(191) DEFAULT NULL,
  `account_number` varchar(191) DEFAULT NULL,
  `bank_name` varchar(191) DEFAULT NULL,
  `ifsc_code` varchar(191) DEFAULT NULL,
  `pf_number` varchar(191) DEFAULT NULL,
  `un_number` varchar(191) DEFAULT NULL,
  `esi_number` varchar(191) DEFAULT NULL,
  `other_education` varchar(191) DEFAULT NULL,
  `previous_exp` varchar(191) DEFAULT NULL,
  `current_company_tenture` int(11) DEFAULT 0,
  `total_exp` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_details_user_id_index` (`user_id`),
  ADD KEY `user_details_date_of_birth_index` (`date_of_birth`),
  ADD KEY `user_details_date_of_joining_index` (`date_of_joining`),
  ADD KEY `user_details_marital_status_index` (`marital_status`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `user_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `user_details`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
