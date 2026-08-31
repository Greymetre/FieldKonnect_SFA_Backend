<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
ALTER TABLE `active_customer_processes`
  ADD CONSTRAINT `active_customer_processes_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `active_customer_processes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `active_customer_processes_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `customer_processes` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `active_customer_process_steps`
  ADD CONSTRAINT `active_customer_process_steps_active_customer_process_id_foreign` FOREIGN KEY (`active_customer_process_id`) REFERENCES `active_customer_processes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `active_customer_process_steps_completed_by_foreign` FOREIGN KEY (`completed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `active_customer_process_steps_customer_process_step_id_foreign` FOREIGN KEY (`customer_process_step_id`) REFERENCES `customer_process_steps` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `addresses_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `addresses_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`),
  ADD CONSTRAINT `addresses_pincode_id_foreign` FOREIGN KEY (`pincode_id`) REFERENCES `pincodes` (`id`),
  ADD CONSTRAINT `addresses_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`),
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `beats`
  ADD CONSTRAINT `beats_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `beats_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `beats_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `beat_customers`
  ADD CONSTRAINT `beat_customers_beat_id_foreign` FOREIGN KEY (`beat_id`) REFERENCES `beats` (`id`),
  ADD CONSTRAINT `beat_customers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `beat_schedules`
  ADD CONSTRAINT `beat_schedules_beat_id_foreign` FOREIGN KEY (`beat_id`) REFERENCES `beats` (`id`),
  ADD CONSTRAINT `beat_schedules_tourid_foreign` FOREIGN KEY (`tourid`) REFERENCES `tour_programmes` (`id`),
  ADD CONSTRAINT `beat_schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `beat_users`
  ADD CONSTRAINT `beat_users_beat_id_foreign` FOREIGN KEY (`beat_id`) REFERENCES `beats` (`id`),
  ADD CONSTRAINT `beat_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `branches_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `branch_holiday`
  ADD CONSTRAINT `branch_holiday_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_holiday_holiday_id_foreign` FOREIGN KEY (`holiday_id`) REFERENCES `holidays` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `brands_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `countries`
  ADD CONSTRAINT `countries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `countries_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_coupon_profile_id_foreign` FOREIGN KEY (`coupon_profile_id`) REFERENCES `coupon_profiles` (`id`),
  ADD CONSTRAINT `coupons_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `coupon_profiles`
  ADD CONSTRAINT `coupon_profiles_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `customer_custom_field_values`
  ADD CONSTRAINT `customer_custom_field_values_custom_field_id_foreign` FOREIGN KEY (`custom_field_id`) REFERENCES `customer_custom_fields` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `customer_process_steps`
  ADD CONSTRAINT `customer_process_steps_customer_process_id_foreign` FOREIGN KEY (`customer_process_id`) REFERENCES `customer_processes` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `custom_pdf_values`
  ADD CONSTRAINT `custom_pdf_values_estimate_id_foreign` FOREIGN KEY (`estimate_id`) REFERENCES `estimates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `custom_pdf_values_label_id_foreign` FOREIGN KEY (`label_id`) REFERENCES `invoice_labels` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `deal_ins`
  ADD CONSTRAINT `deal_ins_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `divisions`
  ADD CONSTRAINT `divisions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `divisions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `estimates`
  ADD CONSTRAINT `estimates_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `estimates_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `estimate_details`
  ADD CONSTRAINT `estimate_details_ibfk_1` FOREIGN KEY (`estimate_id`) REFERENCES `estimates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `estimate_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `fields`
  ADD CONSTRAINT `fields_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `fieldsdata`
  ADD CONSTRAINT `fieldsdata_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `firm_types`
  ADD CONSTRAINT `firm_types_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `firm_types_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `gamifications`
  ADD CONSTRAINT `gamifications_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gamifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `gifts`
  ADD CONSTRAINT `gifts_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `gift_brands` (`id`),
  ADD CONSTRAINT `gifts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `gift_categories` (`id`),
  ADD CONSTRAINT `gifts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `gifts_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `giftsubcategories` (`id`),
  ADD CONSTRAINT `gifts_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `gift_models` (`id`),
  ADD CONSTRAINT `gifts_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `giftsubcategories`
  ADD CONSTRAINT `giftsubcategories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `gift_categories` (`id`),
  ADD CONSTRAINT `giftsubcategories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `giftsubcategories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `gift_brands`
  ADD CONSTRAINT `gift_brands_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `gift_brands_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `gift_categories`
  ADD CONSTRAINT `gift_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `gift_categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `invoice_details`
  ADD CONSTRAINT `invoice_details_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `invoice_labels`
  ADD CONSTRAINT `invoice_labels_invoice_setting_id_foreign` FOREIGN KEY (`invoice_setting_id`) REFERENCES `invoice_settings` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `lead_check_in`
  ADD CONSTRAINT `check_in_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `check_in_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `lead_contacts`
  ADD CONSTRAINT `lead_contacts_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `lead_notes`
  ADD CONSTRAINT `lead_notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lead_notes_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `lead_notifications`
  ADD CONSTRAINT `lead_notifications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `lead_opportunities`
  ADD CONSTRAINT `lead_opportunities_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lead_opportunities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lead_opportunities_lead_contact_id_foreign` FOREIGN KEY (`lead_contact_id`) REFERENCES `lead_contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lead_opportunities_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `lead_tasks`
  ADD CONSTRAINT `lead_tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lead_tasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lead_tasks_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `new_invoices`
  ADD CONSTRAINT `new_invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `new_invoices_secondary_customer_id_foreign` FOREIGN KEY (`secondary_customer_id`) REFERENCES `secondary_customers` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `notes_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`),
  ADD CONSTRAINT `orders_beatscheduleid_foreign` FOREIGN KEY (`beatscheduleid`) REFERENCES `beat_schedules` (`id`),
  ADD CONSTRAINT `orders_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `order_scheme_details`
  ADD CONSTRAINT `order_scheme_details_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `order_scheme_details_order_scheme_id_foreign` FOREIGN KEY (`order_scheme_id`) REFERENCES `order_schemes` (`id`),
  ADD CONSTRAINT `order_scheme_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_scheme_details_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `parent_details`
  ADD CONSTRAINT `parent_details_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `parent_details_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `payments_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `payment_details`
  ADD CONSTRAINT `payment_details_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`),
  ADD CONSTRAINT `payment_details_sales_id_foreign` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `primary_schemes_details`
  ADD CONSTRAINT `primary_schemes_details_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `primary_schemes_details_primary_scheme_id_foreign` FOREIGN KEY (`primary_scheme_id`) REFERENCES `primary_schemes` (`id`),
  ADD CONSTRAINT `primary_schemes_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `primary_schemes_details_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `sales_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sales_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `sales_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `sales_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `sales_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `sales_details`
  ADD CONSTRAINT `sales_details_product_detail_id_foreign` FOREIGN KEY (`product_detail_id`) REFERENCES `product_details` (`id`),
  ADD CONSTRAINT `sales_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sales_details_sales_id_foreign` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `sales_details_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `sales_targets`
  ADD CONSTRAINT `sales_targets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_targets_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_targets_userid_foreign` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `service_complaint_reasons`
  ADD CONSTRAINT `service_complaint_reasons_service_bill_complaint_id_foreign` FOREIGN KEY (`service_bill_complaint_id`) REFERENCES `service_bill_complaint_types` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `service_group_complaints`
  ADD CONSTRAINT `service_group_complaints_service_bill_complaint_id_foreign` FOREIGN KEY (`service_bill_complaint_id`) REFERENCES `service_bill_complaint_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_group_complaints_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `states`
  ADD CONSTRAINT `states_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `states_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `states_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `statuses`
  ADD CONSTRAINT `statuses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `statuses_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `supports`
  ADD CONSTRAINT `supports_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supports_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `survey_data`
  ADD CONSTRAINT `survey_data_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `survey_data_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `survey_data_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `tour_logs`
  ADD CONSTRAINT `fk_tour_log_programme` FOREIGN KEY (`tour_programme_id`) REFERENCES `tour_programmes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tour_log_user` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `tour_programmes`
  ADD CONSTRAINT `tour_programmes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `unit_measures`
  ADD CONSTRAINT `unit_measures_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `unit_measures_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `user_activities`
  ADD CONSTRAINT `user_activities_userid_foreign` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE;
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `user_city_assigns`
  ADD CONSTRAINT `user_city_assigns_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `user_city_assigns_reportingid_foreign` FOREIGN KEY (`reportingid`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_city_assigns_userid_foreign` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `user_live_locations`
  ADD CONSTRAINT `user_live_locations_userid_foreign` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `visit_reports`
  ADD CONSTRAINT `visit_reports_checkin_id_foreign` FOREIGN KEY (`checkin_id`) REFERENCES `check_in` (`id`),
  ADD CONSTRAINT `visit_reports_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visit_reports_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visit_reports_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `visit_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visit_reports_visit_type_id_foreign` FOREIGN KEY (`visit_type_id`) REFERENCES `visit_types` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `visit_types`
  ADD CONSTRAINT `visit_types_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `wallets_sales_id_foreign` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `wallets_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);
SQL);

        DB::unprepared(<<<'SQL'
ALTER TABLE `wallet_details`
  ADD CONSTRAINT `wallet_details_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `wallet_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `wallet_details_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `wallet_details_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`),
  ADD CONSTRAINT `wallet_details_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`);
SQL);
    }

    public function down(): void
    {
        // Foreign keys are removed automatically when the owning tables are dropped.
    }
};
