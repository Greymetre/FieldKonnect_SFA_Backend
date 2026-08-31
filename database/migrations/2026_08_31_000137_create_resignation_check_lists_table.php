<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `resignation_check_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resignation_id` bigint(20) NOT NULL,
  `document_file` varchar(255) DEFAULT NULL,
  `exit_interview` varchar(255) DEFAULT NULL,
  `advance` varchar(255) DEFAULT NULL,
  `laptop` varchar(255) DEFAULT NULL,
  `sim_card` varchar(255) DEFAULT NULL,
  `keys` varchar(255) DEFAULT NULL,
  `visiting_card` varchar(255) DEFAULT NULL,
  `income_tax` varchar(255) DEFAULT NULL,
  `laptop_bag` varchar(255) DEFAULT NULL,
  `expense_voucher` varchar(255) DEFAULT NULL,
  `crm_id` varchar(255) DEFAULT NULL,
  `unpaid_salary` varchar(255) DEFAULT NULL,
  `data_email` varchar(255) DEFAULT NULL,
  `id_card` varchar(255) DEFAULT NULL,
  `payable_expense` varchar(255) DEFAULT NULL,
  `pen_drive` varchar(255) DEFAULT NULL,
  `bouns` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `resignation_check_lists`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `resignation_check_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `resignation_check_lists`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
