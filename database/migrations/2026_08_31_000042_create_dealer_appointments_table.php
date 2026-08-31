<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE `dealer_appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `city` varchar(125) DEFAULT NULL,
  `place` varchar(125) DEFAULT NULL,
  `appointment_date` varchar(255) DEFAULT NULL,
  `customertype` varchar(255) DEFAULT NULL,
  `old_user` varchar(125) DEFAULT NULL,
  `old_division` varchar(125) DEFAULT NULL,
  `old_firm_name` varchar(125) DEFAULT NULL,
  `old_gst` varchar(125) DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `asc_divi` varchar(225) DEFAULT NULL,
  `parent_id` varchar(125) DEFAULT NULL,
  `security_deposit` varchar(125) DEFAULT NULL,
  `SDservicecenterd` bigint(20) DEFAULT NULL,
  `SDPUMPMOTORS` varchar(255) DEFAULT NULL,
  `SDF&A` varchar(255) DEFAULT NULL,
  `SDAGRI` varchar(125) NOT NULL DEFAULT '100000',
  `gst_type` varchar(255) DEFAULT NULL,
  `gst_no` varchar(255) DEFAULT NULL,
  `firm_type` varchar(255) DEFAULT NULL,
  `firm_name` varchar(255) DEFAULT NULL,
  `cin_no` varchar(255) DEFAULT NULL,
  `related_firm_name` varchar(255) DEFAULT NULL,
  `line_business` text DEFAULT NULL,
  `office_address` text DEFAULT NULL,
  `office_pincode` text DEFAULT NULL,
  `office_mobile` text DEFAULT NULL,
  `office_email` text DEFAULT NULL,
  `godown_address` text DEFAULT NULL,
  `godown_pincode` text DEFAULT NULL,
  `godown_mobile` text DEFAULT NULL,
  `godown_email` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `ppd_name_1` text DEFAULT NULL,
  `ppd_adhar_1` text DEFAULT NULL,
  `ppd_pan_1` text DEFAULT NULL,
  `ppd_name_2` text DEFAULT NULL,
  `ppd_adhar_2` text DEFAULT NULL,
  `ppd_pan_2` text DEFAULT NULL,
  `ppd_name_3` text DEFAULT NULL,
  `ppd_adhar_3` text DEFAULT NULL,
  `ppd_pan_3` text DEFAULT NULL,
  `ppd_name_4` text DEFAULT NULL,
  `ppd_adhar_4` text DEFAULT NULL,
  `ppd_pan_4` text DEFAULT NULL,
  `contact_person_name` text DEFAULT NULL,
  `mobile_email` text DEFAULT NULL,
  `bank_name` text DEFAULT NULL,
  `bank_address` text DEFAULT NULL,
  `account_type` text DEFAULT NULL,
  `account_number` text DEFAULT NULL,
  `ifsc_code` text DEFAULT NULL,
  `payment_term` text DEFAULT NULL,
  `credit_period` text DEFAULT NULL,
  `cheque_no_1` text DEFAULT NULL,
  `cheque_account_number_1` text DEFAULT NULL,
  `cheque_bank_1` text DEFAULT NULL,
  `cheque_no_2` text DEFAULT NULL,
  `cheque_account_number_2` text DEFAULT NULL,
  `cheque_bank_2` text DEFAULT NULL,
  `manufacture_company_1` text DEFAULT NULL,
  `manufacture_product_1` text DEFAULT NULL,
  `manufacture_business_1` text DEFAULT NULL,
  `manufacture_turn_over_1` text DEFAULT NULL,
  `manufacture_company_2` text DEFAULT NULL,
  `manufacture_product_2` text DEFAULT NULL,
  `manufacture_business_2` text DEFAULT NULL,
  `manufacture_turn_over_2` text DEFAULT NULL,
  `present_annual_turnover` text DEFAULT NULL,
  `motor_anticipated_business_1` text DEFAULT NULL,
  `motor_next_year_business_1` text DEFAULT NULL,
  `pump_anticipated_business_1` text DEFAULT NULL,
  `pump_next_year_business_1` text DEFAULT NULL,
  `F&A_anticipated_business_1` text DEFAULT NULL,
  `F&A_next_year_business_1` varchar(255) DEFAULT NULL,
  `lighting_anticipated_business_1` varchar(255) DEFAULT NULL,
  `lighting_next_year_business_1` varchar(255) DEFAULT NULL,
  `agri_anticipated_business_1` varchar(255) DEFAULT NULL,
  `agri_next_year_business_1` varchar(255) DEFAULT NULL,
  `solar_anticipated_business_1` varchar(255) DEFAULT NULL,
  `solar_next_year_business_1` varchar(255) DEFAULT NULL,
  `anticipated_business_total` varchar(255) DEFAULT NULL,
  `approval_status` tinyint(4) NOT NULL DEFAULT 0,
  `dealer_board` tinyint(4) NOT NULL DEFAULT 0,
  `welcome_kit` tinyint(4) NOT NULL DEFAULT 0,
  `sales_approve` int(11) DEFAULT NULL,
  `account_approve` int(11) DEFAULT NULL,
  `ho_approve` int(11) DEFAULT NULL,
  `ho_approve_date` date DEFAULT NULL,
  `board_install_date` date DEFAULT NULL,
  `welcome_kit_date` date DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `bm_remark` varchar(255) DEFAULT NULL,
  `bm_remark_user` int(11) DEFAULT NULL,
  `payment_term_bm` text DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `dealer_appointments`
  ADD PRIMARY KEY (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `dealer_appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
SQL);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('DROP TABLE IF EXISTS `dealer_appointments`');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
