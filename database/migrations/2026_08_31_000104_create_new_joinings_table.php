<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `new_joinings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `father_occupation` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mother_occupation` varchar(255) DEFAULT NULL,
  `marital_status` varchar(255) DEFAULT NULL,
  `spouse_name` varchar(255) DEFAULT NULL,
  `spouse_dob` date DEFAULT NULL,
  `spouse_education` varchar(255) DEFAULT NULL,
  `spouse_occupation` varchar(255) DEFAULT NULL,
  `anniversary` varchar(255) DEFAULT NULL,
  `present_address` varchar(255) DEFAULT NULL,
  `present_city` varchar(255) DEFAULT NULL,
  `present_state` varchar(255) DEFAULT NULL,
  `present_pincode` varchar(255) DEFAULT NULL,
  `permanent_address` varchar(255) DEFAULT NULL,
  `permanent_city` varchar(255) DEFAULT NULL,
  `permanent_state` varchar(255) DEFAULT NULL,
  `permanent_pincode` varchar(255) DEFAULT NULL,
  `pan` varchar(255) DEFAULT NULL,
  `aadhar` varchar(255) DEFAULT NULL,
  `driving_licence` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `other_language` varchar(255) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `experience` varchar(255) DEFAULT NULL,
  `skill` varchar(255) DEFAULT NULL,
  `occupy` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `new_joinings`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `new_joinings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `new_joinings`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
