/*
 * Table Group: accounting
 * Total Tables: 17
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `account_categories`
-- ----------------------------
DROP TABLE IF EXISTS `account_categories`;
CREATE TABLE `account_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('aset','kewajiban','ekuitas','pendapatan','beban') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `normal_balance` enum('debit','kredit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `account_categories_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `account_categories_type_index` (`type`) USING BTREE,
  KEY `account_categories_is_active_index` (`is_active`) USING BTREE,
  KEY `account_categories_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `account_categories_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `account_mappings`
-- ----------------------------
DROP TABLE IF EXISTS `account_mappings`;
CREATE TABLE `account_mappings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `source_type` enum('payroll_component','sales','purchase','expense','tax','loan','bank','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `debit_account_id` bigint unsigned DEFAULT NULL,
  `credit_account_id` bigint unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `account_mappings_org_source_unique` (`organization_id`,`source_type`,`source_code`) USING BTREE,
  KEY `account_mappings_debit_fk` (`debit_account_id`) USING BTREE,
  KEY `account_mappings_credit_fk` (`credit_account_id`) USING BTREE,
  KEY `account_mappings_is_active_index` (`is_active`) USING BTREE,
  KEY `account_mappings_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `account_mappings_credit_fk` FOREIGN KEY (`credit_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `account_mappings_debit_fk` FOREIGN KEY (`debit_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `account_mappings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `accounting_periods`
-- ----------------------------
DROP TABLE IF EXISTS `accounting_periods`;
CREATE TABLE `accounting_periods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `fiscal_year_id` bigint unsigned NOT NULL,
  `period` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('open','soft_closed','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `accounting_periods_org_period_unique` (`organization_id`,`period`) USING BTREE,
  KEY `accounting_periods_year_fk` (`fiscal_year_id`) USING BTREE,
  KEY `accounting_periods_status_index` (`status`) USING BTREE,
  KEY `accounting_periods_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `accounting_periods_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `accounting_periods_year_fk` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `chart_of_accounts`
-- ----------------------------
DROP TABLE IF EXISTS `chart_of_accounts`;
CREATE TABLE `chart_of_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_category_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `account_type` enum('header','detail','postable') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'detail',
  `normal_balance` enum('debit','kredit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `current_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `currency_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `accounts_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `accounts_category_index` (`account_category_id`) USING BTREE,
  KEY `accounts_parent_index` (`parent_id`) USING BTREE,
  KEY `accounts_is_active_index` (`is_active`) USING BTREE,
  KEY `accounts_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `accounts_category_fk` FOREIGN KEY (`account_category_id`) REFERENCES `account_categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `accounts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `accounts_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `fiscal_period_closings`
-- ----------------------------
DROP TABLE IF EXISTS `fiscal_period_closings`;
CREATE TABLE `fiscal_period_closings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fiscal_period_id` bigint unsigned NOT NULL,
  `closing_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `closing_date` date NOT NULL,
  `total_debit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_credit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `net_income` decimal(20,2) NOT NULL DEFAULT '0.00',
  `retained_earnings` decimal(20,2) NOT NULL DEFAULT '0.00',
  `snapshot_data` json DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fiscal_period_closings_period_index` (`fiscal_period_id`) USING BTREE,
  KEY `fiscal_period_closings_type_index` (`closing_type`) USING BTREE,
  KEY `fiscal_period_closings_date_index` (`closing_date`) USING BTREE,
  KEY `fiscal_period_closings_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `fiscal_period_closings_created_by_index` (`created_by`) USING BTREE,
  KEY `fiscal_period_closings_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `fiscal_period_closings_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `fiscal_periods`
-- ----------------------------
DROP TABLE IF EXISTS `fiscal_periods`;
CREATE TABLE `fiscal_periods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `year` smallint unsigned NOT NULL,
  `month` tinyint unsigned DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT '0',
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `fiscal_periods_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `fiscal_periods_school_index` (`school_id`) USING BTREE,
  KEY `fiscal_periods_year_index` (`year`) USING BTREE,
  KEY `fiscal_periods_month_index` (`month`) USING BTREE,
  KEY `fiscal_periods_date_range_index` (`start_date`,`end_date`) USING BTREE,
  KEY `fiscal_periods_is_closed_index` (`is_closed`) USING BTREE,
  KEY `fiscal_periods_closed_by_index` (`closed_by`) USING BTREE,
  KEY `fiscal_periods_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `fiscal_periods_created_by_index` (`created_by`) USING BTREE,
  KEY `fiscal_periods_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `fiscal_periods_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fiscal_periods_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `fiscal_years`
-- ----------------------------
DROP TABLE IF EXISTS `fiscal_years`;
CREATE TABLE `fiscal_years` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint DEFAULT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `year` int NOT NULL,
  `month` int DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('open','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `is_closed` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `fiscal_years_org_year_unique` (`organization_id`,`year`) USING BTREE,
  KEY `fiscal_years_status_index` (`status`) USING BTREE,
  KEY `fiscal_years_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `fiscal_years_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `journal_details`
-- ----------------------------
DROP TABLE IF EXISTS `journal_details`;
CREATE TABLE `journal_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `journal_entry_id` bigint unsigned NOT NULL,
  `account_id` bigint unsigned NOT NULL,
  `cost_center_id` bigint unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `reference_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `journal_details_entry_index` (`journal_entry_id`) USING BTREE,
  KEY `journal_details_account_index` (`account_id`) USING BTREE,
  KEY `journal_details_cost_center_index` (`cost_center_id`) USING BTREE,
  CONSTRAINT `journal_details_account_fk` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `journal_details_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `journal_details_entry_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `journal_details_debit_credit_chk` CHECK (((`debit` >= 0) and (`credit` >= 0) and ((`debit` <= 0) or (`credit` <= 0))))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `journal_entries`
-- ----------------------------
DROP TABLE IF EXISTS `journal_entries`;
CREATE TABLE `journal_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `accounting_period_id` bigint unsigned NOT NULL,
  `fiscal_period_id` bigint unsigned NOT NULL,
  `journal_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `journal_date` date NOT NULL,
  `journal_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GENERAL',
  `reference_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `source_type` enum('manual','payroll','sales','purchase','expense','transfer','tax','opening','adjustment','closing') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `source_id` bigint unsigned DEFAULT NULL,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_debit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_credit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','approved','posted','void') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `reversal_of_id` bigint unsigned DEFAULT NULL,
  `reversed_by_id` bigint unsigned DEFAULT NULL,
  `reversal_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `posted_at` timestamp NULL DEFAULT NULL,
  `posted_by` bigint unsigned DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `version` int unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `journal_entries_school_number_unique` (`school_id`,`journal_number`) USING BTREE,
  KEY `journal_entries_school_index` (`school_id`) USING BTREE,
  KEY `journal_entries_fiscal_period_index` (`fiscal_period_id`) USING BTREE,
  KEY `journal_entries_date_index` (`journal_date`) USING BTREE,
  KEY `journal_entries_type_index` (`journal_type`) USING BTREE,
  KEY `journal_entries_status_index` (`status`) USING BTREE,
  KEY `journal_entries_reference_index` (`reference_type`,`reference_id`) USING BTREE,
  KEY `journal_entries_posted_by_index` (`posted_by`) USING BTREE,
  KEY `journal_entries_reversed_by_index` (`reversed_by_id`) USING BTREE,
  KEY `journal_entries_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `journal_entries_created_by_index` (`created_by`) USING BTREE,
  KEY `journal_entries_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `journal_entries_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `journal_entries_posted_by_foreign` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `journal_entries_reversed_by_id_foreign` FOREIGN KEY (`reversed_by_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `journal_entries_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `journal_entry_lines`
-- ----------------------------
DROP TABLE IF EXISTS `journal_entry_lines`;
CREATE TABLE `journal_entry_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `journal_entry_id` bigint unsigned NOT NULL,
  `chart_of_account_id` bigint unsigned NOT NULL,
  `line_number` int NOT NULL DEFAULT '1',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `debit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `currency_id` bigint unsigned NOT NULL,
  `exchange_rate` decimal(20,8) NOT NULL DEFAULT '1.00000000',
  `foreign_debit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `foreign_credit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `functional_debit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `functional_credit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `vendor_id` bigint unsigned DEFAULT NULL,
  `student_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `journal_entry_lines_entry_index` (`journal_entry_id`) USING BTREE,
  KEY `journal_entry_lines_account_index` (`chart_of_account_id`) USING BTREE,
  KEY `journal_entry_lines_currency_index` (`currency_id`) USING BTREE,
  KEY `journal_entry_lines_vendor_index` (`vendor_id`) USING BTREE,
  KEY `journal_entry_lines_student_index` (`student_id`) USING BTREE,
  KEY `journal_entry_lines_line_number_index` (`line_number`) USING BTREE,
  KEY `journal_entry_lines_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `journal_entry_lines_created_by_index` (`created_by`) USING BTREE,
  KEY `journal_entry_lines_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `journal_entry_lines_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `journal_entry_lines_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `journal_entry_lines_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `journal_entry_lines_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `journal_entry_lines_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `opening_balances`
-- ----------------------------
DROP TABLE IF EXISTS `opening_balances`;
CREATE TABLE `opening_balances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `fiscal_year_id` bigint unsigned NOT NULL,
  `account_id` bigint unsigned NOT NULL,
  `cost_center_id` bigint unsigned DEFAULT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `opening_balances_unique` (`organization_id`,`fiscal_year_id`,`account_id`,`cost_center_id`) USING BTREE,
  KEY `opening_balances_year_fk` (`fiscal_year_id`) USING BTREE,
  KEY `opening_balances_account_fk` (`account_id`) USING BTREE,
  KEY `opening_balances_cost_center_fk` (`cost_center_id`) USING BTREE,
  KEY `opening_balances_journal_fk` (`journal_entry_id`) USING BTREE,
  KEY `opening_balances_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `opening_balances_account_fk` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `opening_balances_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `opening_balances_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `opening_balances_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `opening_balances_year_fk` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `reconciliation_details`
-- ----------------------------
DROP TABLE IF EXISTS `reconciliation_details`;
CREATE TABLE `reconciliation_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reconciliation_id` bigint unsigned NOT NULL,
  `bank_transaction_id` bigint unsigned DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `type` enum('match','unmatch','adjustment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `reconciliation_details_recon_fk` (`reconciliation_id`) USING BTREE,
  KEY `reconciliation_details_bank_fk` (`bank_transaction_id`) USING BTREE,
  KEY `reconciliation_details_journal_fk` (`journal_entry_id`) USING BTREE,
  CONSTRAINT `reconciliation_details_bank_fk` FOREIGN KEY (`bank_transaction_id`) REFERENCES `bank_transactions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `reconciliation_details_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `reconciliation_details_recon_fk` FOREIGN KEY (`reconciliation_id`) REFERENCES `reconciliations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `reconciliations`
-- ----------------------------
DROP TABLE IF EXISTS `reconciliations`;
CREATE TABLE `reconciliations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bank_account_id` bigint unsigned NOT NULL,
  `reconciliation_date` date NOT NULL,
  `statement_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `book_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `difference` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','completed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `completed_by` bigint unsigned DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `reconciliations_bank_fk` (`bank_account_id`) USING BTREE,
  KEY `reconciliations_status_index` (`status`) USING BTREE,
  KEY `reconciliations_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `reconciliations_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `tax_liabilities`
-- ----------------------------
DROP TABLE IF EXISTS `tax_liabilities`;
CREATE TABLE `tax_liabilities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `tax_type` enum('pph21','pph23','pph25','ppn','ppnbm','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_period` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `due_date` date NOT NULL,
  `status` enum('pending','partial','paid','overdue','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `source_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_id` bigint unsigned DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `tax_liabilities_org_period_index` (`organization_id`,`tax_type`,`tax_period`) USING BTREE,
  KEY `tax_liabilities_journal_fk` (`journal_entry_id`) USING BTREE,
  KEY `tax_liabilities_status_index` (`status`) USING BTREE,
  KEY `tax_liabilities_due_date_index` (`due_date`) USING BTREE,
  KEY `tax_liabilities_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `tax_liabilities_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `tax_liabilities_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `tax_payments`
-- ----------------------------
DROP TABLE IF EXISTS `tax_payments`;
CREATE TABLE `tax_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tax_liability_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `payment_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_id` bigint unsigned DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `status` enum('draft','paid','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `tax_payments_org_number_unique` (`organization_id`,`payment_number`) USING BTREE,
  KEY `tax_payments_liability_fk` (`tax_liability_id`) USING BTREE,
  KEY `tax_payments_bank_fk` (`bank_account_id`) USING BTREE,
  KEY `tax_payments_journal_fk` (`journal_entry_id`) USING BTREE,
  KEY `tax_payments_status_index` (`status`) USING BTREE,
  KEY `tax_payments_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `tax_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `tax_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `tax_payments_liability_fk` FOREIGN KEY (`tax_liability_id`) REFERENCES `tax_liabilities` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tax_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `tax_settings`
-- ----------------------------
DROP TABLE IF EXISTS `tax_settings`;
CREATE TABLE `tax_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `tax_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_type` enum('pph21','pph23','pph25','ppn','ppnbm','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_type_id` bigint unsigned NOT NULL,
  `tax_rate` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `min_amount` decimal(15,2) DEFAULT NULL,
  `max_amount` decimal(15,2) DEFAULT NULL,
  `effective_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `tax_settings_org_code_unique` (`organization_id`,`tax_code`) USING BTREE,
  KEY `tax_settings_is_active_index` (`is_active`) USING BTREE,
  KEY `tax_settings_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `tax_settings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `tax_types`
-- ----------------------------
DROP TABLE IF EXISTS `tax_types`;
CREATE TABLE `tax_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint DEFAULT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `tax_types_code_unique` (`code`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
