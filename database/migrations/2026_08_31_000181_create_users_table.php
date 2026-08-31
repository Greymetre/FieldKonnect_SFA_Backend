<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `password_string` varchar(225) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `notification_id` varchar(450) NOT NULL DEFAULT '',
  `device_type` varchar(50) NOT NULL DEFAULT '',
  `gender` varchar(20) NOT NULL DEFAULT '',
  `profile_image` varchar(350) NOT NULL DEFAULT '',
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `user_code` varchar(50) NOT NULL DEFAULT '',
  `location` varchar(250) NOT NULL DEFAULT '',
  `reportingid` bigint(20) UNSIGNED DEFAULT NULL,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_codes` varchar(255) DEFAULT NULL,
  `branch_id` varchar(255) DEFAULT NULL,
  `primary_branch_id` varchar(255) DEFAULT NULL,
  `branch_show` varchar(125) DEFAULT NULL,
  `designation_id` bigint(20) DEFAULT NULL,
  `division_id` bigint(20) DEFAULT NULL,
  `department_id` bigint(20) DEFAULT NULL,
  `warehouse_id` bigint(20) DEFAULT NULL,
  `leave_balance` decimal(10,2) DEFAULT 0.00,
  `compb_off` decimal(10,2) DEFAULT 0.00,
  `show_attandance_report` int(11) NOT NULL DEFAULT 1,
  `call_management` tinyint(1) NOT NULL DEFAULT 0,
  `payroll` bigint(20) DEFAULT NULL,
  `sales_type` varchar(20) DEFAULT NULL,
  `customerid` varchar(125) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `grade` varchar(125) DEFAULT NULL,
  `personal_number` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `earned_leave_balance` decimal(8,2) NOT NULL,
  `casual_leave_balance` decimal(8,2) NOT NULL,
  `sick_leave_balance` decimal(8,2) NOT NULL,
  `claimable_earned_leave_balance` decimal(8,2) NOT NULL,
  `date_of_joining` date DEFAULT NULL,
  `last_leave_accrual_date` date DEFAULT NULL,
  `earned_leave_claim_activated_at` date DEFAULT NULL,
  `login_at` timestamp NULL DEFAULT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_mobile_unique` (`mobile`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_name_index` (`name`),
  ADD KEY `users_first_name_index` (`first_name`),
  ADD KEY `users_last_name_index` (`last_name`),
  ADD KEY `users_profile_image_index` (`profile_image`),
  ADD KEY `users_location_index` (`location`),
  ADD KEY `users_reportingid_index` (`reportingid`),
  ADD KEY `users_region_id_index` (`region_id`),
  ADD KEY `users_created_by_index` (`created_by`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `users`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
