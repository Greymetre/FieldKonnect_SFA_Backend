<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` varchar(1) NOT NULL DEFAULT 'Y',
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sales_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_path` varchar(450) NOT NULL DEFAULT '',
  `document_name` varchar(250) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachments_product_id_index` (`product_id`),
  ADD KEY `attachments_user_id_index` (`user_id`),
  ADD KEY `attachments_customer_id_index` (`customer_id`),
  ADD KEY `attachments_order_id_index` (`order_id`),
  ADD KEY `attachments_sales_id_index` (`sales_id`),
  ADD KEY `attachments_file_path_index` (`file_path`),
  ADD KEY `attachments_document_name_index` (`document_name`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `attachments`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
