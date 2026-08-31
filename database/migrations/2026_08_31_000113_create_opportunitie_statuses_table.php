<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `opportunitie_statuses` (
  `id` int(11) NOT NULL,
  `status_name` varchar(255) DEFAULT NULL,
  `ordering` int(22) NOT NULL DEFAULT 1,
  `created_by` int(22) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `opportunitie_statuses`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `opportunitie_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `opportunitie_statuses`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
