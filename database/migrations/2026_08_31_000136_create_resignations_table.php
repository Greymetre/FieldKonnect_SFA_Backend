<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `resignations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submit_date` date DEFAULT NULL,
  `division_id` bigint(20) DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `employee_code` varchar(125) DEFAULT NULL,
  `notice` int(11) DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `last_working_date` date DEFAULT NULL,
  `cug_sim_no` varchar(255) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `persoanla_email` varchar(255) DEFAULT NULL,
  `persoanla_mobile` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `remark` varchar(225) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `resignations`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `resignations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `resignations`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
