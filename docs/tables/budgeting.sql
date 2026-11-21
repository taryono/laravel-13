/*
 * Table Group: budgeting
 * Total Tables: 25
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `bos_allocations`
-- ----------------------------
DROP TABLE IF EXISTS `bos_allocations`;
CREATE TABLE `bos_allocations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bos_period_id` bigint unsigned NOT NULL,
  `bos_component_id` bigint unsigned NOT NULL,
  `allocation_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `percentage` decimal(5,2) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `bos_allocations_period_component_unique` (`bos_period_id`,`bos_component_id`) USING BTREE,
  KEY `bos_allocations_period_index` (`bos_period_id`) USING BTREE,
  KEY `bos_allocations_component_index` (`bos_component_id`) USING BTREE,
  KEY `bos_allocations_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `bos_allocations_created_by_index` (`created_by`) USING BTREE,
  KEY `bos_allocations_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `bos_allocations_bos_component_id_foreign` FOREIGN KEY (`bos_component_id`) REFERENCES `bos_components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_allocations_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `bos_components`
-- ----------------------------
DROP TABLE IF EXISTS `bos_components`;
CREATE TABLE `bos_components` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `authority` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'KEMENDIKBUD',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `regulation_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regulation_version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_percentage` decimal(5,2) DEFAULT NULL,
  `min_percentage` decimal(5,2) DEFAULT NULL,
  `calculation_base` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `is_prohibited` tinyint(1) NOT NULL DEFAULT '0',
  `order_index` int NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `bos_components_authority_code_unique` (`authority`,`code`) USING BTREE,
  KEY `bos_components_authority_index` (`authority`) USING BTREE,
  KEY `bos_components_type_index` (`component_type`) USING BTREE,
  KEY `bos_components_regulation_index` (`regulation_code`,`regulation_version`) USING BTREE,
  KEY `bos_components_order_index` (`order_index`) USING BTREE,
  KEY `bos_components_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `bos_components_created_by_index` (`created_by`) USING BTREE,
  KEY `bos_components_updated_by_index` (`updated_by`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `bos_periods`
-- ----------------------------
DROP TABLE IF EXISTS `bos_periods`;
CREATE TABLE `bos_periods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `fiscal_period_id` bigint unsigned DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `authority` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'KEMENDIKBUD',
  `regulation_version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `period_number` int DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_allocation` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_received` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_used` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_remaining` decimal(20,2) NOT NULL DEFAULT '0.00',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PLANNING',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `bos_periods_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `bos_periods_school_index` (`school_id`) USING BTREE,
  KEY `bos_periods_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `bos_periods_fiscal_period_index` (`fiscal_period_id`) USING BTREE,
  KEY `bos_periods_authority_index` (`authority`) USING BTREE,
  KEY `bos_periods_status_index` (`status`) USING BTREE,
  KEY `bos_periods_dates_index` (`start_date`,`end_date`) USING BTREE,
  KEY `bos_periods_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `bos_periods_created_by_index` (`created_by`) USING BTREE,
  KEY `bos_periods_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `bos_periods_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_periods_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_periods_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `bos_realizations`
-- ----------------------------
DROP TABLE IF EXISTS `bos_realizations`;
CREATE TABLE `bos_realizations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bos_period_id` bigint unsigned NOT NULL,
  `bos_usage_plan_id` bigint unsigned DEFAULT NULL,
  `rkas_realization_id` bigint unsigned DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `vendor_invoice_id` bigint unsigned DEFAULT NULL,
  `receipt_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `realization_date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'RECORDED',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `bos_realizations_period_index` (`bos_period_id`) USING BTREE,
  KEY `bos_realizations_usage_plan_index` (`bos_usage_plan_id`) USING BTREE,
  KEY `bos_realizations_rkas_realization_index` (`rkas_realization_id`) USING BTREE,
  KEY `bos_realizations_journal_entry_index` (`journal_entry_id`) USING BTREE,
  KEY `bos_realizations_vendor_index` (`vendor_id`) USING BTREE,
  KEY `bos_realizations_vendor_invoice_index` (`vendor_invoice_id`) USING BTREE,
  KEY `bos_realizations_date_index` (`realization_date`) USING BTREE,
  KEY `bos_realizations_status_index` (`status`) USING BTREE,
  KEY `bos_realizations_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `bos_realizations_created_by_index` (`created_by`) USING BTREE,
  KEY `bos_realizations_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `bos_realizations_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_realizations_bos_usage_plan_id_foreign` FOREIGN KEY (`bos_usage_plan_id`) REFERENCES `bos_usage_plans` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_realizations_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_realizations_rkas_realization_id_foreign` FOREIGN KEY (`rkas_realization_id`) REFERENCES `rkas_realizations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_realizations_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_realizations_vendor_invoice_id_foreign` FOREIGN KEY (`vendor_invoice_id`) REFERENCES `vendor_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `bos_receipts`
-- ----------------------------
DROP TABLE IF EXISTS `bos_receipts`;
CREATE TABLE `bos_receipts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bos_period_id` bigint unsigned NOT NULL,
  `bank_account_id` bigint unsigned DEFAULT NULL,
  `receipt_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_date` date NOT NULL,
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `transfer_reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transfer_proof_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `received_by` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `bos_receipts_period_number_unique` (`bos_period_id`,`receipt_number`) USING BTREE,
  KEY `bos_receipts_period_index` (`bos_period_id`) USING BTREE,
  KEY `bos_receipts_bank_account_index` (`bank_account_id`) USING BTREE,
  KEY `bos_receipts_date_index` (`receipt_date`) USING BTREE,
  KEY `bos_receipts_received_by_index` (`received_by`) USING BTREE,
  KEY `bos_receipts_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `bos_receipts_created_by_index` (`created_by`) USING BTREE,
  KEY `bos_receipts_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `bos_receipts_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_receipts_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_receipts_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `bos_report_items`
-- ----------------------------
DROP TABLE IF EXISTS `bos_report_items`;
CREATE TABLE `bos_report_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bos_report_id` bigint unsigned NOT NULL,
  `bos_component_id` bigint unsigned DEFAULT NULL,
  `rkas_source_id` bigint unsigned DEFAULT NULL,
  `account_id` bigint unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `allocation_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `received_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `realization_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `remaining_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `item_data` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `bos_report_items_report_index` (`bos_report_id`) USING BTREE,
  KEY `bos_report_items_component_index` (`bos_component_id`) USING BTREE,
  KEY `bos_report_items_source_index` (`rkas_source_id`) USING BTREE,
  KEY `bos_report_items_coa_index` (`account_id`) USING BTREE,
  KEY `bos_report_items_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `bos_report_items_created_by_index` (`created_by`) USING BTREE,
  KEY `bos_report_items_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `bos_report_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_report_items_bos_component_id_foreign` FOREIGN KEY (`bos_component_id`) REFERENCES `bos_components` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_report_items_bos_report_id_foreign` FOREIGN KEY (`bos_report_id`) REFERENCES `bos_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_report_items_rkas_source_id_foreign` FOREIGN KEY (`rkas_source_id`) REFERENCES `rkas_sources` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `bos_reports`
-- ----------------------------
DROP TABLE IF EXISTS `bos_reports`;
CREATE TABLE `bos_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `bos_period_id` bigint unsigned NOT NULL,
  `fiscal_period_id` bigint unsigned DEFAULT NULL,
  `report_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PERIODIC',
  `period_number` int DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_allocation` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_received` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_used` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_remaining` decimal(20,2) NOT NULL DEFAULT '0.00',
  `percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `file_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `report_data` json DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `submitted_by` bigint unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `bos_reports_school_period_code_unique` (`school_id`,`bos_period_id`,`report_code`) USING BTREE,
  KEY `bos_reports_school_index` (`school_id`) USING BTREE,
  KEY `bos_reports_period_index` (`bos_period_id`) USING BTREE,
  KEY `bos_reports_fiscal_period_index` (`fiscal_period_id`) USING BTREE,
  KEY `bos_reports_code_index` (`report_code`) USING BTREE,
  KEY `bos_reports_type_index` (`report_type`) USING BTREE,
  KEY `bos_reports_status_index` (`status`) USING BTREE,
  KEY `bos_reports_submitted_by_index` (`submitted_by`) USING BTREE,
  KEY `bos_reports_verified_by_index` (`verified_by`) USING BTREE,
  KEY `bos_reports_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `bos_reports_created_by_index` (`created_by`) USING BTREE,
  KEY `bos_reports_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `bos_reports_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_reports_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_reports_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_reports_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_reports_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `bos_usage_plans`
-- ----------------------------
DROP TABLE IF EXISTS `bos_usage_plans`;
CREATE TABLE `bos_usage_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bos_period_id` bigint unsigned NOT NULL,
  `rkas_document_id` bigint unsigned DEFAULT NULL,
  `rkas_item_id` bigint unsigned DEFAULT NULL,
  `bos_component_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `planned_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `execution_month` tinyint unsigned DEFAULT NULL,
  `execution_quarter` tinyint unsigned DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PLANNED',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `bos_usage_plans_period_code_unique` (`bos_period_id`,`code`) USING BTREE,
  KEY `bos_usage_plans_period_index` (`bos_period_id`) USING BTREE,
  KEY `bos_usage_plans_rkas_document_index` (`rkas_document_id`) USING BTREE,
  KEY `bos_usage_plans_rkas_item_index` (`rkas_item_id`) USING BTREE,
  KEY `bos_usage_plans_component_index` (`bos_component_id`) USING BTREE,
  KEY `bos_usage_plans_month_index` (`execution_month`) USING BTREE,
  KEY `bos_usage_plans_status_index` (`status`) USING BTREE,
  KEY `bos_usage_plans_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `bos_usage_plans_created_by_index` (`created_by`) USING BTREE,
  KEY `bos_usage_plans_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `bos_usage_plans_bos_component_id_foreign` FOREIGN KEY (`bos_component_id`) REFERENCES `bos_components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_usage_plans_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_usage_plans_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_usage_plans_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `budget_details`
-- ----------------------------
DROP TABLE IF EXISTS `budget_details`;
CREATE TABLE `budget_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `budget_id` bigint unsigned NOT NULL,
  `accounting_period_id` bigint unsigned DEFAULT NULL,
  `account_id` bigint unsigned NOT NULL,
  `cost_center_id` bigint unsigned DEFAULT NULL,
  `budget_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `actual_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `variance_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `variance_percentage` decimal(7,2) NOT NULL DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `budget_details_budget_fk` (`budget_id`) USING BTREE,
  KEY `budget_details_period_fk` (`accounting_period_id`) USING BTREE,
  KEY `budget_details_account_fk` (`account_id`) USING BTREE,
  KEY `budget_details_cost_center_fk` (`cost_center_id`) USING BTREE,
  KEY `budget_details_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `budget_details_account_fk` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `budget_details_budget_fk` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `budget_details_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `budget_details_period_fk` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `budgets`
-- ----------------------------
DROP TABLE IF EXISTS `budgets`;
CREATE TABLE `budgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `fiscal_year_id` bigint unsigned NOT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','approved','locked','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `version` int unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `budgets_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `budgets_year_fk` (`fiscal_year_id`) USING BTREE,
  KEY `budgets_status_index` (`status`) USING BTREE,
  KEY `budgets_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `budgets_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `budgets_year_fk` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `cost_centers`
-- ----------------------------
DROP TABLE IF EXISTS `cost_centers`;
CREATE TABLE `cost_centers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `manager_id` bigint unsigned DEFAULT NULL,
  `budget_holder_id` bigint unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `cost_centers_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `cost_centers_parent_fk` (`parent_id`) USING BTREE,
  KEY `cost_centers_department_fk` (`department_id`) USING BTREE,
  KEY `cost_centers_is_active_index` (`is_active`) USING BTREE,
  KEY `cost_centers_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `cost_centers_department_fk` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `cost_centers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cost_centers_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_activities`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_activities`;
CREATE TABLE `rkas_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rkas_program_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_index` int NOT NULL DEFAULT '0',
  `total_budget` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_realization` decimal(20,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `rkas_activities_program_code_unique` (`rkas_program_id`,`code`) USING BTREE,
  KEY `rkas_activities_program_index` (`rkas_program_id`) USING BTREE,
  KEY `rkas_activities_order_index` (`order_index`) USING BTREE,
  KEY `rkas_activities_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_activities_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_activities_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `rkas_activities_rkas_program_id_foreign` FOREIGN KEY (`rkas_program_id`) REFERENCES `rkas_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_approvals`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_approvals`;
CREATE TABLE `rkas_approvals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rkas_document_id` bigint unsigned NOT NULL,
  `approval_level` int NOT NULL DEFAULT '1',
  `approval_role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approver_id` bigint unsigned DEFAULT NULL,
  `approver_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `signature_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `rkas_approvals_document_index` (`rkas_document_id`) USING BTREE,
  KEY `rkas_approvals_level_index` (`approval_level`) USING BTREE,
  KEY `rkas_approvals_role_index` (`approval_role`) USING BTREE,
  KEY `rkas_approvals_approver_index` (`approver_id`) USING BTREE,
  KEY `rkas_approvals_status_index` (`status`) USING BTREE,
  KEY `rkas_approvals_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_approvals_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_approvals_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `rkas_approvals_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_approvals_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_budgets`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_budgets`;
CREATE TABLE `rkas_budgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rkas_item_id` bigint unsigned NOT NULL,
  `rkas_source_id` bigint unsigned NOT NULL,
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `percentage` decimal(5,2) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `rkas_budgets_item_source_unique` (`rkas_item_id`,`rkas_source_id`) USING BTREE,
  KEY `rkas_budgets_item_index` (`rkas_item_id`) USING BTREE,
  KEY `rkas_budgets_source_index` (`rkas_source_id`) USING BTREE,
  KEY `rkas_budgets_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_budgets_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_budgets_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `rkas_budgets_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_budgets_rkas_source_id_foreign` FOREIGN KEY (`rkas_source_id`) REFERENCES `rkas_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_components`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_components`;
CREATE TABLE `rkas_components` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` bigint unsigned DEFAULT NULL,
  `regulation_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regulation_version` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_percentage` decimal(5,2) DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `order_index` int NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `rkas_components_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `rkas_components_school_index` (`school_id`) USING BTREE,
  KEY `rkas_components_type_index` (`component_type`) USING BTREE,
  KEY `rkas_components_account_index` (`account_code`) USING BTREE,
  KEY `rkas_components_coa_index` (`account_id`) USING BTREE,
  KEY `rkas_components_regulation_index` (`regulation_code`,`regulation_version`) USING BTREE,
  KEY `rkas_components_order_index` (`order_index`) USING BTREE,
  KEY `rkas_components_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_components_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_components_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `rkas_components_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_components_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_documents`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_documents`;
CREATE TABLE `rkas_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `fiscal_period_id` bigint unsigned DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` int NOT NULL DEFAULT '1',
  `revision_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INITIAL',
  `parent_document_id` bigint unsigned DEFAULT NULL,
  `total_budget` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_realization` decimal(20,2) NOT NULL DEFAULT '0.00',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `rkas_documents_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `rkas_documents_school_index` (`school_id`) USING BTREE,
  KEY `rkas_documents_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `rkas_documents_fiscal_period_index` (`fiscal_period_id`) USING BTREE,
  KEY `rkas_documents_revision_type_index` (`revision_type`) USING BTREE,
  KEY `rkas_documents_parent_index` (`parent_document_id`) USING BTREE,
  KEY `rkas_documents_status_index` (`status`) USING BTREE,
  KEY `rkas_documents_approved_by_index` (`approved_by`) USING BTREE,
  KEY `rkas_documents_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_documents_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_documents_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `rkas_documents_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_documents_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_documents_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_documents_parent_document_id_foreign` FOREIGN KEY (`parent_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_documents_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_items`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_items`;
CREATE TABLE `rkas_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rkas_sub_activity_id` bigint unsigned NOT NULL,
  `rkas_component_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `volume` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `unit_price` decimal(20,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(20,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `shipping_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `admin_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `execution_month` tinyint unsigned DEFAULT NULL,
  `execution_quarter` tinyint unsigned DEFAULT NULL,
  `order_index` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `rkas_items_sub_activity_code_unique` (`rkas_sub_activity_id`,`code`) USING BTREE,
  KEY `rkas_items_sub_activity_index` (`rkas_sub_activity_id`) USING BTREE,
  KEY `rkas_items_component_index` (`rkas_component_id`) USING BTREE,
  KEY `rkas_items_month_index` (`execution_month`) USING BTREE,
  KEY `rkas_items_quarter_index` (`execution_quarter`) USING BTREE,
  KEY `rkas_items_order_index` (`order_index`) USING BTREE,
  KEY `rkas_items_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_items_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_items_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `rkas_items_rkas_component_id_foreign` FOREIGN KEY (`rkas_component_id`) REFERENCES `rkas_components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_items_rkas_sub_activity_id_foreign` FOREIGN KEY (`rkas_sub_activity_id`) REFERENCES `rkas_sub_activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_programs`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_programs`;
CREATE TABLE `rkas_programs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rkas_document_id` bigint unsigned NOT NULL,
  `standard_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_index` int NOT NULL DEFAULT '0',
  `total_budget` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_realization` decimal(20,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `rkas_programs_document_code_unique` (`rkas_document_id`,`code`) USING BTREE,
  KEY `rkas_programs_document_index` (`rkas_document_id`) USING BTREE,
  KEY `rkas_programs_standard_index` (`standard_id`) USING BTREE,
  KEY `rkas_programs_order_index` (`order_index`) USING BTREE,
  KEY `rkas_programs_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_programs_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_programs_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `rkas_programs_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_programs_standard_id_foreign` FOREIGN KEY (`standard_id`) REFERENCES `rkas_standards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_realizations`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_realizations`;
CREATE TABLE `rkas_realizations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rkas_item_id` bigint unsigned NOT NULL,
  `rkas_source_id` bigint unsigned NOT NULL,
  `fiscal_period_id` bigint unsigned NOT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `realization_date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `vendor_id` bigint unsigned DEFAULT NULL,
  `vendor_invoice_id` bigint unsigned DEFAULT NULL,
  `payment_reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'RECORDED',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `rkas_realizations_item_index` (`rkas_item_id`) USING BTREE,
  KEY `rkas_realizations_source_index` (`rkas_source_id`) USING BTREE,
  KEY `rkas_realizations_fiscal_period_index` (`fiscal_period_id`) USING BTREE,
  KEY `rkas_realizations_journal_entry_index` (`journal_entry_id`) USING BTREE,
  KEY `rkas_realizations_date_index` (`realization_date`) USING BTREE,
  KEY `rkas_realizations_vendor_index` (`vendor_id`) USING BTREE,
  KEY `rkas_realizations_vendor_invoice_index` (`vendor_invoice_id`) USING BTREE,
  KEY `rkas_realizations_status_index` (`status`) USING BTREE,
  KEY `rkas_realizations_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_realizations_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_realizations_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `rkas_realizations_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_realizations_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_realizations_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_realizations_rkas_source_id_foreign` FOREIGN KEY (`rkas_source_id`) REFERENCES `rkas_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_realizations_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_realizations_vendor_invoice_id_foreign` FOREIGN KEY (`vendor_invoice_id`) REFERENCES `vendor_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_report_items`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_report_items`;
CREATE TABLE `rkas_report_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rkas_report_id` bigint unsigned NOT NULL,
  `rkas_component_id` bigint unsigned DEFAULT NULL,
  `rkas_source_id` bigint unsigned DEFAULT NULL,
  `account_id` bigint unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `budget_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `realization_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `remaining_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `item_data` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `rkas_report_items_report_index` (`rkas_report_id`) USING BTREE,
  KEY `rkas_report_items_component_index` (`rkas_component_id`) USING BTREE,
  KEY `rkas_report_items_source_index` (`rkas_source_id`) USING BTREE,
  KEY `rkas_report_items_coa_index` (`account_id`) USING BTREE,
  KEY `rkas_report_items_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_report_items_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_report_items_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `rkas_report_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_report_items_rkas_component_id_foreign` FOREIGN KEY (`rkas_component_id`) REFERENCES `rkas_components` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_report_items_rkas_report_id_foreign` FOREIGN KEY (`rkas_report_id`) REFERENCES `rkas_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_report_items_rkas_source_id_foreign` FOREIGN KEY (`rkas_source_id`) REFERENCES `rkas_sources` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_reports`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_reports`;
CREATE TABLE `rkas_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `rkas_document_id` bigint unsigned NOT NULL,
  `fiscal_period_id` bigint unsigned NOT NULL,
  `report_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PERIODIC',
  `period_number` int DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_budget` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_realization` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_remaining` decimal(20,2) NOT NULL DEFAULT '0.00',
  `percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `file_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `report_data` json DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `submitted_by` bigint unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `rkas_reports_school_document_code_period_unique` (`school_id`,`rkas_document_id`,`report_code`,`period_number`) USING BTREE,
  KEY `rkas_reports_school_index` (`school_id`) USING BTREE,
  KEY `rkas_reports_document_index` (`rkas_document_id`) USING BTREE,
  KEY `rkas_reports_fiscal_period_index` (`fiscal_period_id`) USING BTREE,
  KEY `rkas_reports_code_index` (`report_code`) USING BTREE,
  KEY `rkas_reports_type_index` (`report_type`) USING BTREE,
  KEY `rkas_reports_status_index` (`status`) USING BTREE,
  KEY `rkas_reports_submitted_by_index` (`submitted_by`) USING BTREE,
  KEY `rkas_reports_verified_by_index` (`verified_by`) USING BTREE,
  KEY `rkas_reports_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_reports_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_reports_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `rkas_reports_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_reports_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_reports_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_reports_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_reports_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_revisions`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_revisions`;
CREATE TABLE `rkas_revisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rkas_document_id` bigint unsigned NOT NULL,
  `parent_revision_id` bigint unsigned DEFAULT NULL,
  `revision_number` int NOT NULL DEFAULT '1',
  `revision_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `revision_date` date NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_before` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_after` decimal(20,2) NOT NULL DEFAULT '0.00',
  `difference` decimal(20,2) NOT NULL DEFAULT '0.00',
  `changes` json DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approval_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `rkas_revisions_document_number_unique` (`rkas_document_id`,`revision_number`) USING BTREE,
  KEY `rkas_revisions_document_index` (`rkas_document_id`) USING BTREE,
  KEY `rkas_revisions_parent_index` (`parent_revision_id`) USING BTREE,
  KEY `rkas_revisions_type_index` (`revision_type`) USING BTREE,
  KEY `rkas_revisions_date_index` (`revision_date`) USING BTREE,
  KEY `rkas_revisions_status_index` (`status`) USING BTREE,
  KEY `rkas_revisions_approved_by_index` (`approved_by`) USING BTREE,
  KEY `rkas_revisions_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_revisions_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_revisions_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `rkas_revisions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_revisions_parent_revision_id_foreign` FOREIGN KEY (`parent_revision_id`) REFERENCES `rkas_revisions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_revisions_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_sources`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_sources`;
CREATE TABLE `rkas_sources` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `authority` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` bigint unsigned DEFAULT NULL,
  `allocation_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `regulation_version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `rkas_sources_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `rkas_sources_school_index` (`school_id`) USING BTREE,
  KEY `rkas_sources_type_index` (`source_type`) USING BTREE,
  KEY `rkas_sources_authority_index` (`authority`) USING BTREE,
  KEY `rkas_sources_coa_index` (`account_id`) USING BTREE,
  KEY `rkas_sources_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_sources_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_sources_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `rkas_sources_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_sources_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_standards`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_standards`;
CREATE TABLE `rkas_standards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_index` int NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `rkas_standards_code_unique` (`code`) USING BTREE,
  KEY `rkas_standards_order_index` (`order_index`) USING BTREE,
  KEY `rkas_standards_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_standards_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_standards_updated_by_index` (`updated_by`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rkas_sub_activities`
-- ----------------------------
DROP TABLE IF EXISTS `rkas_sub_activities`;
CREATE TABLE `rkas_sub_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rkas_activity_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_index` int NOT NULL DEFAULT '0',
  `total_budget` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_realization` decimal(20,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `rkas_sub_activities_activity_code_unique` (`rkas_activity_id`,`code`) USING BTREE,
  KEY `rkas_sub_activities_activity_index` (`rkas_activity_id`) USING BTREE,
  KEY `rkas_sub_activities_order_index` (`order_index`) USING BTREE,
  KEY `rkas_sub_activities_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `rkas_sub_activities_created_by_index` (`created_by`) USING BTREE,
  KEY `rkas_sub_activities_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `rkas_sub_activities_rkas_activity_id_foreign` FOREIGN KEY (`rkas_activity_id`) REFERENCES `rkas_activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
