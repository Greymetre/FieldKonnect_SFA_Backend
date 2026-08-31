<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `appraisals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `weightage_id` bigint(20) DEFAULT NULL,
  `kra` varchar(191) DEFAULT NULL,
  `year` varchar(110) DEFAULT NULL,
  `target` int(11) DEFAULT NULL,
  `achivment` int(11) DEFAULT NULL,
  `acual` varchar(150) DEFAULT NULL,
  `rating` text DEFAULT NULL,
  `rating_by` text DEFAULT NULL,
  `appraisal_type` varchar(120) DEFAULT NULL,
  `appraisal_session` varchar(120) DEFAULT NULL,
  `promotion` text DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `grade_percentage` int(11) DEFAULT NULL,
  `grade` varchar(120) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `appraisals`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `appraisals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `appraisals`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
