/*
 * Table Group: finance
 * Total Tables: 40
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `bank_accounts`
-- ----------------------------
DROP TABLE IF EXISTS `bank_accounts`;
CREATE TABLE `bank_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `chart_of_account_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` bigint unsigned NOT NULL,
  `opening_balance` decimal(20,2) NOT NULL DEFAULT '0.00',
  `current_balance` decimal(20,2) NOT NULL DEFAULT '0.00',
  `is_cash_account` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `swift_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `bank_accounts_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `bank_accounts_school_index` (`school_id`) USING BTREE,
  KEY `bank_accounts_coa_index` (`chart_of_account_id`) USING BTREE,
  KEY `bank_accounts_currency_index` (`currency_id`) USING BTREE,
  KEY `bank_accounts_number_index` (`account_number`) USING BTREE,
  KEY `bank_accounts_is_cash_index` (`is_cash_account`) USING BTREE,
  KEY `bank_accounts_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `bank_accounts_created_by_index` (`created_by`) USING BTREE,
  KEY `bank_accounts_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `bank_accounts_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_accounts_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_accounts_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `bank_mutation_imports`
-- ----------------------------
DROP TABLE IF EXISTS `bank_mutation_imports`;
CREATE TABLE `bank_mutation_imports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `bank_account_id` bigint unsigned NOT NULL,
  `file_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file_size` bigint DEFAULT NULL,
  `total_rows` int NOT NULL DEFAULT '0',
  `success_rows` int NOT NULL DEFAULT '0',
  `failed_rows` int NOT NULL DEFAULT '0',
  `duplicate_rows` int NOT NULL DEFAULT '0',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `error_log` json DEFAULT NULL,
  `imported_at` timestamp NULL DEFAULT NULL,
  `imported_by` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `bank_mutation_imports_school_index` (`school_id`) USING BTREE,
  KEY `bank_mutation_imports_bank_account_index` (`bank_account_id`) USING BTREE,
  KEY `bank_mutation_imports_status_index` (`status`) USING BTREE,
  KEY `bank_mutation_imports_imported_by_index` (`imported_by`) USING BTREE,
  KEY `bank_mutation_imports_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `bank_mutation_imports_created_by_index` (`created_by`) USING BTREE,
  KEY `bank_mutation_imports_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `bank_mutation_imports_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_mutation_imports_imported_by_foreign` FOREIGN KEY (`imported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bank_mutation_imports_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `bank_mutations`
-- ----------------------------
DROP TABLE IF EXISTS `bank_mutations`;
CREATE TABLE `bank_mutations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `bank_account_id` bigint unsigned NOT NULL,
  `import_batch_id` bigint unsigned DEFAULT NULL,
  `mutation_date` date NOT NULL,
  `value_date` date DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `debit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(20,2) DEFAULT NULL,
  `currency_id` bigint unsigned NOT NULL,
  `exchange_rate` decimal(20,8) NOT NULL DEFAULT '1.00000000',
  `functional_debit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `functional_credit` decimal(20,2) NOT NULL DEFAULT '0.00',
  `reconciliation_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UNMATCHED',
  `matched_journal_entry_id` bigint unsigned DEFAULT NULL,
  `matched_at` timestamp NULL DEFAULT NULL,
  `matched_by` bigint unsigned DEFAULT NULL,
  `match_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `raw_data` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `bank_mutations_school_index` (`school_id`) USING BTREE,
  KEY `bank_mutations_bank_account_index` (`bank_account_id`) USING BTREE,
  KEY `bank_mutations_import_batch_index` (`import_batch_id`) USING BTREE,
  KEY `bank_mutations_date_index` (`mutation_date`) USING BTREE,
  KEY `bank_mutations_reference_index` (`reference_number`) USING BTREE,
  KEY `bank_mutations_status_index` (`reconciliation_status`) USING BTREE,
  KEY `bank_mutations_matched_entry_index` (`matched_journal_entry_id`) USING BTREE,
  KEY `bank_mutations_matched_by_index` (`matched_by`) USING BTREE,
  KEY `bank_mutations_currency_index` (`currency_id`) USING BTREE,
  KEY `bank_mutations_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `bank_mutations_created_by_index` (`created_by`) USING BTREE,
  KEY `bank_mutations_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `bank_mutations_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_mutations_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_mutations_matched_by_foreign` FOREIGN KEY (`matched_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bank_mutations_matched_journal_entry_id_foreign` FOREIGN KEY (`matched_journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bank_mutations_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `bank_reconciliation_items`
-- ----------------------------
DROP TABLE IF EXISTS `bank_reconciliation_items`;
CREATE TABLE `bank_reconciliation_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bank_reconciliation_id` bigint unsigned NOT NULL,
  `bank_mutation_id` bigint unsigned DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `match_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AUTO',
  `match_score` decimal(5,2) DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MATCHED',
  `difference_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `bank_reconciliation_items_reconciliation_index` (`bank_reconciliation_id`) USING BTREE,
  KEY `bank_reconciliation_items_mutation_index` (`bank_mutation_id`) USING BTREE,
  KEY `bank_reconciliation_items_entry_index` (`journal_entry_id`) USING BTREE,
  KEY `bank_reconciliation_items_match_type_index` (`match_type`) USING BTREE,
  KEY `bank_reconciliation_items_status_index` (`status`) USING BTREE,
  KEY `bank_reconciliation_items_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `bank_reconciliation_items_created_by_index` (`created_by`) USING BTREE,
  KEY `bank_reconciliation_items_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `bank_reconciliation_items_bank_mutation_id_foreign` FOREIGN KEY (`bank_mutation_id`) REFERENCES `bank_mutations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_reconciliation_items_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_reconciliation_items_reconciliation_id_foreign` FOREIGN KEY (`bank_reconciliation_id`) REFERENCES `reconciliations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `bank_transactions`
-- ----------------------------
DROP TABLE IF EXISTS `bank_transactions`;
CREATE TABLE `bank_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bank_account_id` bigint unsigned NOT NULL,
  `transaction_date` date NOT NULL,
  `transaction_type` enum('debit','credit','transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_after` decimal(15,2) NOT NULL DEFAULT '0.00',
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `is_reconciled` tinyint(1) NOT NULL DEFAULT '0',
  `reconciled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `bank_transactions_account_date_index` (`bank_account_id`,`transaction_date`) USING BTREE,
  KEY `bank_transactions_journal_fk` (`journal_entry_id`) USING BTREE,
  KEY `bank_transactions_is_reconciled_index` (`is_reconciled`) USING BTREE,
  KEY `bank_transactions_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `bank_transactions_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `bank_transactions_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `currencies`
-- ----------------------------
DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `decimal_places` tinyint unsigned NOT NULL DEFAULT '2',
  `is_base` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `currencies_code_unique` (`code`) USING BTREE,
  KEY `currencies_is_base_index` (`is_base`) USING BTREE,
  KEY `currencies_is_active_index` (`is_active`) USING BTREE,
  KEY `currencies_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `currencies_created_by_index` (`created_by`) USING BTREE,
  KEY `currencies_updated_by_index` (`updated_by`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `customers`
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `receivable_account_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `customers_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `customers_receivable_account_fk` (`receivable_account_id`) USING BTREE,
  KEY `customers_is_active_index` (`is_active`) USING BTREE,
  KEY `customers_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `customers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `customers_receivable_account_fk` FOREIGN KEY (`receivable_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `digital_wallets`
-- ----------------------------
DROP TABLE IF EXISTS `digital_wallets`;
CREATE TABLE `digital_wallets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `owner_type` enum('student','employee') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` bigint unsigned NOT NULL,
  `wallet_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `daily_spending_limit` decimal(15,2) NOT NULL DEFAULT '50000.00',
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `lock_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `wallet_num_unique` (`wallet_number`) USING BTREE,
  UNIQUE KEY `wallet_owner_unique` (`owner_type`,`owner_id`) USING BTREE,
  KEY `wallet_org_index` (`organization_id`) USING BTREE,
  CONSTRAINT `digital_wallets_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `chk_wallet_balance` CHECK ((`balance` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `exchange_rates`
-- ----------------------------
DROP TABLE IF EXISTS `exchange_rates`;
CREATE TABLE `exchange_rates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `currency_id` bigint unsigned NOT NULL,
  `base_currency_id` bigint unsigned NOT NULL,
  `rate_date` date NOT NULL,
  `rate` decimal(20,8) NOT NULL,
  `source` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `exchange_rates_currency_date_unique` (`currency_id`,`base_currency_id`,`rate_date`) USING BTREE,
  KEY `exchange_rates_currency_index` (`currency_id`) USING BTREE,
  KEY `exchange_rates_base_index` (`base_currency_id`) USING BTREE,
  KEY `exchange_rates_date_index` (`rate_date`) USING BTREE,
  KEY `exchange_rates_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `exchange_rates_created_by_index` (`created_by`) USING BTREE,
  KEY `exchange_rates_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `exchange_rates_base_currency_id_foreign` FOREIGN KEY (`base_currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exchange_rates_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `expense_categories`
-- ----------------------------
DROP TABLE IF EXISTS `expense_categories`;
CREATE TABLE `expense_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `parent_id` bigint unsigned DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `expense_categories_org_code_unique` (`organization_id`,`code`) USING BTREE,
  UNIQUE KEY `expense_categories_org_name_unique` (`organization_id`,`name`) USING BTREE,
  KEY `expense_categories_parent_id_index` (`parent_id`) USING BTREE,
  KEY `expense_categories_is_active_index` (`is_active`) USING BTREE,
  KEY `expense_categories_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `expense_categories_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `expense_categories_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `expense_items`
-- ----------------------------
DROP TABLE IF EXISTS `expense_items`;
CREATE TABLE `expense_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `expense_id` bigint unsigned NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(15,4) NOT NULL DEFAULT '1.0000',
  `unit_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `account_id` bigint unsigned NOT NULL,
  `cost_center_id` bigint unsigned DEFAULT NULL,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `expense_items_expense_fk` (`expense_id`) USING BTREE,
  KEY `expense_items_account_fk` (`account_id`) USING BTREE,
  KEY `expense_items_cost_center_fk` (`cost_center_id`) USING BTREE,
  KEY `expense_items_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `expense_items_account_fk` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `expense_items_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `expense_items_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `expense_payments`
-- ----------------------------
DROP TABLE IF EXISTS `expense_payments`;
CREATE TABLE `expense_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `expense_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `payment_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('cash','bank_transfer','check','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account_id` bigint unsigned DEFAULT NULL,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `expense_payments_org_number_unique` (`organization_id`,`payment_number`) USING BTREE,
  KEY `expense_payments_expense_fk` (`expense_id`) USING BTREE,
  KEY `expense_payments_bank_fk` (`bank_account_id`) USING BTREE,
  KEY `expense_payments_journal_fk` (`journal_entry_id`) USING BTREE,
  KEY `expense_payments_organization_id_index` (`organization_id`) USING BTREE,
  KEY `expense_payments_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `expense_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `expense_payments_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `expense_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `expense_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `expenses`
-- ----------------------------
DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `expense_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_date` date NOT NULL,
  `vendor_name_snapshot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('unpaid','partial','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `attachment_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `expenses_org_number_unique` (`organization_id`,`expense_number`) USING BTREE,
  KEY `expenses_vendor_fk` (`vendor_id`) USING BTREE,
  KEY `expenses_journal_fk` (`journal_entry_id`) USING BTREE,
  KEY `expenses_payment_status_index` (`payment_status`) USING BTREE,
  KEY `expenses_expense_date_index` (`expense_date`) USING BTREE,
  KEY `expenses_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `expenses_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `expenses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `expenses_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `fee_masters`
-- ----------------------------
DROP TABLE IF EXISTS `fee_masters`;
CREATE TABLE `fee_masters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `class_level_id` bigint unsigned DEFAULT NULL,
  `fee_category` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fee_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `frequency` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `fee_masters_school_code_unique` (`school_id`,`fee_code`) USING BTREE,
  KEY `fee_masters_school_index` (`school_id`) USING BTREE,
  KEY `fee_masters_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `fee_masters_class_level_index` (`class_level_id`) USING BTREE,
  KEY `fee_masters_category_index` (`fee_category`) USING BTREE,
  KEY `fee_masters_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `fee_masters_created_by_index` (`created_by`) USING BTREE,
  KEY `fee_masters_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `fee_masters_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fee_masters_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fee_masters_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `fee_structures`
-- ----------------------------
DROP TABLE IF EXISTS `fee_structures`;
CREATE TABLE `fee_structures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fee_master_id` bigint unsigned NOT NULL,
  `period_id` bigint unsigned NOT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `fee_structures_master_period_unique` (`fee_master_id`,`period_id`) USING BTREE,
  KEY `fee_structures_master_index` (`fee_master_id`) USING BTREE,
  KEY `fee_structures_period_index` (`period_id`) USING BTREE,
  KEY `fee_structures_due_date_index` (`due_date`) USING BTREE,
  KEY `fee_structures_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `fee_structures_created_by_index` (`created_by`) USING BTREE,
  KEY `fee_structures_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `fee_structures_fee_master_id_foreign` FOREIGN KEY (`fee_master_id`) REFERENCES `fee_masters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fee_structures_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `finance_audit_logs`
-- ----------------------------
DROP TABLE IF EXISTS `finance_audit_logs`;
CREATE TABLE `finance_audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` bigint unsigned DEFAULT NULL,
  `old_data` json DEFAULT NULL,
  `new_data` json DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `finance_audit_logs_org_index` (`organization_id`) USING BTREE,
  KEY `finance_audit_logs_table_record_index` (`table_name`,`record_id`) USING BTREE,
  KEY `finance_audit_logs_created_index` (`created_at`) USING BTREE,
  CONSTRAINT `finance_audit_logs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `financial_aid_requests`
-- ----------------------------
DROP TABLE IF EXISTS `financial_aid_requests`;
CREATE TABLE `financial_aid_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL COMMENT 'FK ke students',
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `academic_year_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke academic_years',
  `academic_semester_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke academic_semesters',
  `fee_master_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke fee_masters (biaya yang diminta keringanan)',
  `student_invoice_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke student_invoices',
  `request_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nomor permohonan',
  `aid_type` enum('tuition_reduction','tuition_waiver','installment','deferment','scholarship_substitute','emergency_aid','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tuition_reduction' COMMENT 'Jenis bantuan',
  `requested_amount` decimal(15,2) DEFAULT NULL COMMENT 'Nominal yang diminta',
  `requested_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Persentase yang diminta',
  `approved_amount` decimal(15,2) DEFAULT NULL COMMENT 'Nominal yang disetujui',
  `approved_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Persentase yang disetujui',
  `currency_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke currencies',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Alasan permohonan',
  `family_income` decimal(15,2) DEFAULT NULL COMMENT 'Penghasilan keluarga',
  `family_dependents` int unsigned DEFAULT NULL COMMENT 'Jumlah tanggungan',
  `father_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father_occupation` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_occupation` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supporting_documents` json DEFAULT NULL COMMENT 'Dokumen pendukung (SKTM, slip gaji, dll)',
  `status` enum('draft','submitted','under_review','document_verification','interview','approved','partially_approved','rejected','cancelled','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft' COMMENT 'Status permohonan',
  `current_stage` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tahap saat ini',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang review',
  `review_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `decision` enum('pending','approved','partially_approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `decision_date` date DEFAULT NULL,
  `decision_by` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang memutuskan',
  `decision_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `valid_from` date DEFAULT NULL COMMENT 'Berlaku mulai',
  `valid_until` date DEFAULT NULL COMMENT 'Berlaku sampai',
  `installment_count` int unsigned DEFAULT NULL COMMENT 'Jumlah cicilan (jika installment)',
  `installment_amount` decimal(15,2) DEFAULT NULL COMMENT 'Nominal per cicilan',
  `applied_to_invoice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika sudah diterapkan ke invoice',
  `applied_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_financial_aid_number` (`request_number`) USING BTREE,
  KEY `idx_financial_aid_student` (`student_id`) USING BTREE,
  KEY `idx_financial_aid_org` (`organization_id`) USING BTREE,
  KEY `idx_financial_aid_year` (`academic_year_id`) USING BTREE,
  KEY `idx_financial_aid_semester` (`academic_semester_id`) USING BTREE,
  KEY `idx_financial_aid_fee` (`fee_master_id`) USING BTREE,
  KEY `idx_financial_aid_invoice` (`student_invoice_id`) USING BTREE,
  KEY `idx_financial_aid_type` (`aid_type`) USING BTREE,
  KEY `idx_financial_aid_status` (`status`) USING BTREE,
  KEY `idx_financial_aid_decision` (`decision`) USING BTREE,
  KEY `idx_financial_aid_submitted` (`submitted_at`) USING BTREE,
  KEY `fk_financial_aid_currency` (`currency_id`) USING BTREE,
  KEY `fk_financial_aid_reviewed_by` (`reviewed_by`) USING BTREE,
  KEY `fk_financial_aid_decision_by` (`decision_by`) USING BTREE,
  KEY `fk_financial_aid_created_by` (`created_by`) USING BTREE,
  KEY `fk_financial_aid_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `financial_aid_requests_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `financial_aid_requests_ibfk_10` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `financial_aid_requests_ibfk_11` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `financial_aid_requests_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `financial_aid_requests_ibfk_3` FOREIGN KEY (`decision_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `financial_aid_requests_ibfk_4` FOREIGN KEY (`fee_master_id`) REFERENCES `fee_masters` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `financial_aid_requests_ibfk_5` FOREIGN KEY (`student_invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `financial_aid_requests_ibfk_6` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `financial_aid_requests_ibfk_7` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `financial_aid_requests_ibfk_8` FOREIGN KEY (`academic_semester_id`) REFERENCES `academic_semesters` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `financial_aid_requests_ibfk_9` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `invoice_items`
-- ----------------------------
DROP TABLE IF EXISTS `invoice_items`;
CREATE TABLE `invoice_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `product_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(15,4) NOT NULL DEFAULT '1.0000',
  `unit_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `revenue_account_id` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `invoice_items_invoice_fk` (`invoice_id`) USING BTREE,
  KEY `invoice_items_revenue_account_fk` (`revenue_account_id`) USING BTREE,
  KEY `invoice_items_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `invoice_items_invoice_fk` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `invoice_items_revenue_account_fk` FOREIGN KEY (`revenue_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `invoice_payments`
-- ----------------------------
DROP TABLE IF EXISTS `invoice_payments`;
CREATE TABLE `invoice_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `payment_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('cash','bank_transfer','check','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account_id` bigint unsigned DEFAULT NULL,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `invoice_payments_org_number_unique` (`organization_id`,`payment_number`) USING BTREE,
  KEY `invoice_payments_invoice_fk` (`invoice_id`) USING BTREE,
  KEY `invoice_payments_bank_fk` (`bank_account_id`) USING BTREE,
  KEY `invoice_payments_journal_fk` (`journal_entry_id`) USING BTREE,
  KEY `invoice_payments_organization_id_index` (`organization_id`) USING BTREE,
  KEY `invoice_payments_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `invoice_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `invoice_payments_invoice_fk` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `invoice_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `invoice_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `invoices`
-- ----------------------------
DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name_snapshot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone_snapshot` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email_snapshot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `remaining_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','sent','partial','paid','overdue','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `invoices_org_number_unique` (`organization_id`,`invoice_number`) USING BTREE,
  KEY `invoices_customer_fk` (`customer_id`) USING BTREE,
  KEY `invoices_journal_fk` (`journal_entry_id`) USING BTREE,
  KEY `invoices_status_index` (`status`) USING BTREE,
  KEY `invoices_invoice_date_index` (`invoice_date`) USING BTREE,
  KEY `invoices_due_date_index` (`due_date`) USING BTREE,
  KEY `invoices_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `invoices_customer_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `invoices_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `invoices_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `loan_installments`
-- ----------------------------
DROP TABLE IF EXISTS `loan_installments`;
CREATE TABLE `loan_installments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `loan_id` bigint unsigned NOT NULL,
  `installment_number` int NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','paid','partial','cancelled','overdue') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payroll_detail_id` bigint unsigned DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `loan_installments_number_unique` (`loan_id`,`installment_number`) USING BTREE,
  KEY `loan_installments_payroll_fk` (`payroll_detail_id`) USING BTREE,
  KEY `loan_installments_status_index` (`status`) USING BTREE,
  KEY `loan_installments_due_date_index` (`due_date`) USING BTREE,
  KEY `loan_installments_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `loan_installments_loan_fk` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `loan_installments_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `loans`
-- ----------------------------
DROP TABLE IF EXISTS `loans`;
CREATE TABLE `loans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `loan_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `loan_date` date NOT NULL,
  `principal_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `installment_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_installments` int NOT NULL DEFAULT '0',
  `remaining_installments` int NOT NULL DEFAULT '0',
  `status` enum('draft','active','completed','cancelled','defaulted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `loans_org_number_unique` (`organization_id`,`loan_number`) USING BTREE,
  KEY `loans_employee_fk` (`employee_id`) USING BTREE,
  KEY `loans_status_index` (`status`) USING BTREE,
  KEY `loans_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `loans_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `loans_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `payment_channels`
-- ----------------------------
DROP TABLE IF EXISTS `payment_channels`;
CREATE TABLE `payment_channels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payment_gateway_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `admin_fee` decimal(20,2) NOT NULL DEFAULT '0.00',
  `admin_fee_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FIXED',
  `min_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `max_amount` decimal(20,2) DEFAULT NULL,
  `expiry_minutes` int DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `config` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `payment_channels_gateway_code_unique` (`payment_gateway_id`,`code`) USING BTREE,
  KEY `payment_channels_gateway_index` (`payment_gateway_id`) USING BTREE,
  KEY `payment_channels_code_index` (`code`) USING BTREE,
  KEY `payment_channels_type_index` (`channel_type`) USING BTREE,
  KEY `payment_channels_bank_index` (`bank_code`) USING BTREE,
  KEY `payment_channels_sort_index` (`sort_order`) USING BTREE,
  KEY `payment_channels_is_active_index` (`is_active`) USING BTREE,
  KEY `payment_channels_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `payment_channels_created_by_index` (`created_by`) USING BTREE,
  KEY `payment_channels_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `payment_channels_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `payment_gateways`
-- ----------------------------
DROP TABLE IF EXISTS `payment_gateways`;
CREATE TABLE `payment_gateways` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `provider` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MIDTRANS',
  `environment` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SANDBOX',
  `merchant_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `server_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_production` tinyint(1) NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `default_currency_id` bigint unsigned NOT NULL,
  `expiry_minutes` int NOT NULL DEFAULT '1440',
  `config` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `payment_gateways_school_provider_env_unique` (`school_id`,`provider`,`environment`) USING BTREE,
  KEY `payment_gateways_school_index` (`school_id`) USING BTREE,
  KEY `payment_gateways_provider_index` (`provider`) USING BTREE,
  KEY `payment_gateways_environment_index` (`environment`) USING BTREE,
  KEY `payment_gateways_is_primary_index` (`is_primary`) USING BTREE,
  KEY `payment_gateways_currency_index` (`default_currency_id`) USING BTREE,
  KEY `payment_gateways_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `payment_gateways_created_by_index` (`created_by`) USING BTREE,
  KEY `payment_gateways_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `payment_gateways_default_currency_id_foreign` FOREIGN KEY (`default_currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payment_gateways_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `payment_notifications`
-- ----------------------------
DROP TABLE IF EXISTS `payment_notifications`;
CREATE TABLE `payment_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `payment_transaction_id` bigint unsigned DEFAULT NULL,
  `student_invoice_id` bigint unsigned DEFAULT NULL,
  `applicant_invoice_id` bigint unsigned DEFAULT NULL,
  `recipient_user_id` bigint unsigned DEFAULT NULL,
  `recipient_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WHATSAPP',
  `template_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `provider` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_message_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_response` json DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `sent_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `failed_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `retry_count` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `payment_notifications_school_index` (`school_id`) USING BTREE,
  KEY `payment_notifications_transaction_index` (`payment_transaction_id`) USING BTREE,
  KEY `payment_notifications_student_invoice_index` (`student_invoice_id`) USING BTREE,
  KEY `payment_notifications_applicant_invoice_index` (`applicant_invoice_id`) USING BTREE,
  KEY `payment_notifications_recipient_user_index` (`recipient_user_id`) USING BTREE,
  KEY `payment_notifications_phone_index` (`recipient_phone`) USING BTREE,
  KEY `payment_notifications_email_index` (`recipient_email`) USING BTREE,
  KEY `payment_notifications_channel_index` (`channel`) USING BTREE,
  KEY `payment_notifications_status_index` (`status`) USING BTREE,
  KEY `payment_notifications_sent_at_index` (`sent_at`) USING BTREE,
  KEY `payment_notifications_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `payment_notifications_created_by_index` (`created_by`) USING BTREE,
  KEY `payment_notifications_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `payment_notifications_applicant_invoice_id_foreign` FOREIGN KEY (`applicant_invoice_id`) REFERENCES `applicant_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_notifications_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_notifications_recipient_user_id_foreign` FOREIGN KEY (`recipient_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_notifications_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payment_notifications_student_invoice_id_foreign` FOREIGN KEY (`student_invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `payment_transactions`
-- ----------------------------
DROP TABLE IF EXISTS `payment_transactions`;
CREATE TABLE `payment_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `payment_gateway_id` bigint unsigned NOT NULL,
  `payment_channel_id` bigint unsigned DEFAULT NULL,
  `student_invoice_id` bigint unsigned DEFAULT NULL,
  `applicant_invoice_id` bigint unsigned DEFAULT NULL,
  `student_id` bigint unsigned DEFAULT NULL,
  `applicant_id` bigint unsigned DEFAULT NULL,
  `order_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `midtrans_transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_id` bigint unsigned NOT NULL,
  `gross_amount` decimal(20,2) NOT NULL,
  `admin_fee` decimal(20,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `exchange_rate` decimal(20,8) NOT NULL DEFAULT '1.00000000',
  `functional_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `transaction_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `fraud_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `transaction_time` timestamp NULL DEFAULT NULL,
  `settlement_time` timestamp NULL DEFAULT NULL,
  `expiry_time` timestamp NULL DEFAULT NULL,
  `va_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `va_bank` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qris_string` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `qris_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `raw_request` json DEFAULT NULL,
  `raw_response` json DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `student_payment_id` bigint unsigned DEFAULT NULL,
  `applicant_payment_id` bigint unsigned DEFAULT NULL,
  `is_posted` tinyint(1) NOT NULL DEFAULT '0',
  `posted_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `payment_transactions_order_id_unique` (`order_id`) USING BTREE,
  UNIQUE KEY `payment_transactions_midtrans_id_unique` (`midtrans_transaction_id`) USING BTREE,
  KEY `payment_transactions_school_index` (`school_id`) USING BTREE,
  KEY `payment_transactions_gateway_index` (`payment_gateway_id`) USING BTREE,
  KEY `payment_transactions_channel_index` (`payment_channel_id`) USING BTREE,
  KEY `payment_transactions_student_invoice_index` (`student_invoice_id`) USING BTREE,
  KEY `payment_transactions_applicant_invoice_index` (`applicant_invoice_id`) USING BTREE,
  KEY `payment_transactions_student_index` (`student_id`) USING BTREE,
  KEY `payment_transactions_applicant_index` (`applicant_id`) USING BTREE,
  KEY `payment_transactions_type_index` (`transaction_type`) USING BTREE,
  KEY `payment_transactions_payment_type_index` (`payment_type`) USING BTREE,
  KEY `payment_transactions_status_index` (`transaction_status`) USING BTREE,
  KEY `payment_transactions_fraud_index` (`fraud_status`) USING BTREE,
  KEY `payment_transactions_va_index` (`va_number`) USING BTREE,
  KEY `payment_transactions_transaction_time_index` (`transaction_time`) USING BTREE,
  KEY `payment_transactions_expiry_time_index` (`expiry_time`) USING BTREE,
  KEY `payment_transactions_journal_entry_index` (`journal_entry_id`) USING BTREE,
  KEY `payment_transactions_student_payment_index` (`student_payment_id`) USING BTREE,
  KEY `payment_transactions_applicant_payment_index` (`applicant_payment_id`) USING BTREE,
  KEY `payment_transactions_is_posted_index` (`is_posted`) USING BTREE,
  KEY `payment_transactions_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `payment_transactions_created_by_index` (`created_by`) USING BTREE,
  KEY `payment_transactions_updated_by_index` (`updated_by`) USING BTREE,
  KEY `payment_transactions_currency_id_foreign` (`currency_id`) USING BTREE,
  CONSTRAINT `payment_transactions_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_applicant_invoice_id_foreign` FOREIGN KEY (`applicant_invoice_id`) REFERENCES `applicant_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_applicant_payment_id_foreign` FOREIGN KEY (`applicant_payment_id`) REFERENCES `applicant_payments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_payment_channel_id_foreign` FOREIGN KEY (`payment_channel_id`) REFERENCES `payment_channels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_student_invoice_id_foreign` FOREIGN KEY (`student_invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_student_payment_id_foreign` FOREIGN KEY (`student_payment_id`) REFERENCES `student_payments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `payment_webhook_logs`
-- ----------------------------
DROP TABLE IF EXISTS `payment_webhook_logs`;
CREATE TABLE `payment_webhook_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payment_gateway_id` bigint unsigned DEFAULT NULL,
  `order_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_valid` tinyint(1) NOT NULL DEFAULT '0',
  `is_processed` tinyint(1) NOT NULL DEFAULT '0',
  `processed_at` timestamp NULL DEFAULT NULL,
  `processing_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `retry_count` int NOT NULL DEFAULT '0',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `headers` json DEFAULT NULL,
  `payload` json NOT NULL,
  `response_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `response_status` int DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `payment_webhook_logs_gateway_index` (`payment_gateway_id`) USING BTREE,
  KEY `payment_webhook_logs_order_index` (`order_id`) USING BTREE,
  KEY `payment_webhook_logs_transaction_index` (`transaction_id`) USING BTREE,
  KEY `payment_webhook_logs_event_index` (`event_type`) USING BTREE,
  KEY `payment_webhook_logs_is_processed_index` (`is_processed`) USING BTREE,
  KEY `payment_webhook_logs_received_at_index` (`received_at`) USING BTREE,
  KEY `payment_webhook_logs_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `payment_webhook_logs_created_by_index` (`created_by`) USING BTREE,
  KEY `payment_webhook_logs_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `payment_webhook_logs_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `qris_transactions`
-- ----------------------------
DROP TABLE IF EXISTS `qris_transactions`;
CREATE TABLE `qris_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `payment_transaction_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned DEFAULT NULL,
  `applicant_id` bigint unsigned DEFAULT NULL,
  `issuer` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acquirer` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qris_string` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `qris_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `expiry_time` timestamp NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `paid_at` timestamp NULL DEFAULT NULL,
  `raw_response` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `qris_transactions_school_index` (`school_id`) USING BTREE,
  KEY `qris_transactions_transaction_index` (`payment_transaction_id`) USING BTREE,
  KEY `qris_transactions_student_index` (`student_id`) USING BTREE,
  KEY `qris_transactions_applicant_index` (`applicant_id`) USING BTREE,
  KEY `qris_transactions_status_index` (`status`) USING BTREE,
  KEY `qris_transactions_expiry_index` (`expiry_time`) USING BTREE,
  KEY `qris_transactions_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `qris_transactions_created_by_index` (`created_by`) USING BTREE,
  KEY `qris_transactions_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `qris_transactions_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `qris_transactions_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `qris_transactions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `qris_transactions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_installments`
-- ----------------------------
DROP TABLE IF EXISTS `student_installments`;
CREATE TABLE `student_installments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `installment_number` int NOT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `paid_date` date DEFAULT NULL,
  `paid_amount` decimal(15,2) DEFAULT NULL,
  `payment_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `student_installments_invoice_number_unique` (`invoice_id`,`installment_number`) USING BTREE,
  KEY `student_installments_invoice_index` (`invoice_id`) USING BTREE,
  KEY `student_installments_due_date_index` (`due_date`) USING BTREE,
  KEY `student_installments_is_paid_index` (`is_paid`) USING BTREE,
  KEY `student_installments_payment_index` (`payment_id`) USING BTREE,
  KEY `student_installments_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `student_installments_created_by_index` (`created_by`) USING BTREE,
  KEY `student_installments_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `student_installments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_invoice_batches`
-- ----------------------------
DROP TABLE IF EXISTS `student_invoice_batches`;
CREATE TABLE `student_invoice_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `batch_id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `student_invoice_batches_unique` (`invoice_id`,`batch_id`) USING BTREE,
  KEY `student_invoice_batches_invoice_index` (`invoice_id`) USING BTREE,
  KEY `student_invoice_batches_batch_index` (`batch_id`) USING BTREE,
  KEY `student_invoice_batches_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `student_invoice_batches_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `student_payment_batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_invoice_batches_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_invoices`
-- ----------------------------
DROP TABLE IF EXISTS `student_invoices`;
CREATE TABLE `student_invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `fee_master_id` bigint unsigned NOT NULL,
  `student_classroom_id` bigint unsigned DEFAULT NULL,
  `invoice_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_id` bigint unsigned DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `penalty_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `final_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `version` int unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `student_invoices_school_number_unique` (`school_id`,`invoice_number`) USING BTREE,
  KEY `student_invoices_student_index` (`student_id`) USING BTREE,
  KEY `student_invoices_school_index` (`school_id`) USING BTREE,
  KEY `student_invoices_fee_master_index` (`fee_master_id`) USING BTREE,
  KEY `student_invoices_student_classroom_index` (`student_classroom_id`) USING BTREE,
  KEY `student_invoices_status_index` (`status`) USING BTREE,
  KEY `student_invoices_due_date_index` (`due_date`) USING BTREE,
  KEY `student_invoices_period_index` (`period_id`) USING BTREE,
  KEY `student_invoices_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `student_invoices_created_by_index` (`created_by`) USING BTREE,
  KEY `student_invoices_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `student_invoices_fee_master_id_foreign` FOREIGN KEY (`fee_master_id`) REFERENCES `fee_masters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_invoices_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_invoices_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_invoices_student_classroom_id_foreign` FOREIGN KEY (`student_classroom_id`) REFERENCES `student_classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_invoices_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_payment_batches`
-- ----------------------------
DROP TABLE IF EXISTS `student_payment_batches`;
CREATE TABLE `student_payment_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `batch_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `period_id` bigint unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_invoices` int NOT NULL DEFAULT '0',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `processed_by` bigint unsigned DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `student_payment_batches_school_number_unique` (`school_id`,`batch_number`) USING BTREE,
  KEY `student_payment_batches_school_index` (`school_id`) USING BTREE,
  KEY `student_payment_batches_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `student_payment_batches_period_index` (`period_id`) USING BTREE,
  KEY `student_payment_batches_status_index` (`status`) USING BTREE,
  KEY `student_payment_batches_processed_by_index` (`processed_by`) USING BTREE,
  KEY `student_payment_batches_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `student_payment_batches_created_by_index` (`created_by`) USING BTREE,
  KEY `student_payment_batches_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `student_payment_batches_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_payment_batches_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_payment_batches_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_payment_batches_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_payments`
-- ----------------------------
DROP TABLE IF EXISTS `student_payments`;
CREATE TABLE `student_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `payment_method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reference_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_proof_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `verified_by` bigint unsigned DEFAULT NULL,
  `verification_date` timestamp NULL DEFAULT NULL,
  `refund_date` timestamp NULL DEFAULT NULL,
  `refund_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `version` int unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `student_payments_invoice_index` (`invoice_id`) USING BTREE,
  KEY `student_payments_student_index` (`student_id`) USING BTREE,
  KEY `student_payments_status_index` (`status`) USING BTREE,
  KEY `student_payments_verified_by_index` (`verified_by`) USING BTREE,
  KEY `student_payments_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `student_payments_created_by_index` (`created_by`) USING BTREE,
  KEY `student_payments_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `student_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `student_payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `student_payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `vendor_invoice_lines`
-- ----------------------------
DROP TABLE IF EXISTS `vendor_invoice_lines`;
CREATE TABLE `vendor_invoice_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_invoice_id` bigint unsigned NOT NULL,
  `chart_of_account_id` bigint unsigned NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quantity` decimal(20,4) NOT NULL DEFAULT '1.0000',
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(20,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(20,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `line_number` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `vendor_invoice_lines_invoice_index` (`vendor_invoice_id`) USING BTREE,
  KEY `vendor_invoice_lines_coa_index` (`chart_of_account_id`) USING BTREE,
  KEY `vendor_invoice_lines_line_number_index` (`line_number`) USING BTREE,
  KEY `vendor_invoice_lines_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `vendor_invoice_lines_created_by_index` (`created_by`) USING BTREE,
  KEY `vendor_invoice_lines_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `vendor_invoice_lines_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `vendor_invoice_lines_vendor_invoice_id_foreign` FOREIGN KEY (`vendor_invoice_id`) REFERENCES `vendor_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `vendor_invoices`
-- ----------------------------
DROP TABLE IF EXISTS `vendor_invoices`;
CREATE TABLE `vendor_invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `fiscal_period_id` bigint unsigned NOT NULL,
  `currency_id` bigint unsigned NOT NULL,
  `exchange_rate` decimal(20,8) NOT NULL DEFAULT '1.00000000',
  `invoice_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `internal_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `subtotal` decimal(20,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `tax_base` decimal(20,2) NOT NULL DEFAULT '0.00',
  `ppn_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `ppn_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `pph_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `pph_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `outstanding_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `functional_total` decimal(20,2) NOT NULL DEFAULT '0.00',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UNPAID',
  `payment_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `posted_journal_entry_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `vendor_invoices_school_internal_unique` (`school_id`,`internal_number`) USING BTREE,
  UNIQUE KEY `vendor_invoices_vendor_number_unique` (`vendor_id`,`invoice_number`) USING BTREE,
  KEY `vendor_invoices_school_index` (`school_id`) USING BTREE,
  KEY `vendor_invoices_vendor_index` (`vendor_id`) USING BTREE,
  KEY `vendor_invoices_fiscal_period_index` (`fiscal_period_id`) USING BTREE,
  KEY `vendor_invoices_currency_index` (`currency_id`) USING BTREE,
  KEY `vendor_invoices_date_index` (`invoice_date`) USING BTREE,
  KEY `vendor_invoices_due_date_index` (`due_date`) USING BTREE,
  KEY `vendor_invoices_status_index` (`status`) USING BTREE,
  KEY `vendor_invoices_payment_status_index` (`payment_status`) USING BTREE,
  KEY `vendor_invoices_posted_entry_index` (`posted_journal_entry_id`) USING BTREE,
  KEY `vendor_invoices_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `vendor_invoices_created_by_index` (`created_by`) USING BTREE,
  KEY `vendor_invoices_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `vendor_invoices_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_invoices_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_invoices_posted_journal_entry_id_foreign` FOREIGN KEY (`posted_journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `vendor_invoices_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `vendor_invoices_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `vendor_payments`
-- ----------------------------
DROP TABLE IF EXISTS `vendor_payments`;
CREATE TABLE `vendor_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `vendor_invoice_id` bigint unsigned DEFAULT NULL,
  `bank_account_id` bigint unsigned DEFAULT NULL,
  `fiscal_period_id` bigint unsigned NOT NULL,
  `currency_id` bigint unsigned NOT NULL,
  `exchange_rate` decimal(20,8) NOT NULL DEFAULT '1.00000000',
  `payment_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `pph_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `functional_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `posted_journal_entry_id` bigint unsigned DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'POSTED',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `vendor_payments_school_number_unique` (`school_id`,`payment_number`) USING BTREE,
  KEY `vendor_payments_school_index` (`school_id`) USING BTREE,
  KEY `vendor_payments_vendor_index` (`vendor_id`) USING BTREE,
  KEY `vendor_payments_invoice_index` (`vendor_invoice_id`) USING BTREE,
  KEY `vendor_payments_bank_account_index` (`bank_account_id`) USING BTREE,
  KEY `vendor_payments_fiscal_period_index` (`fiscal_period_id`) USING BTREE,
  KEY `vendor_payments_currency_index` (`currency_id`) USING BTREE,
  KEY `vendor_payments_date_index` (`payment_date`) USING BTREE,
  KEY `vendor_payments_status_index` (`status`) USING BTREE,
  KEY `vendor_payments_posted_entry_index` (`posted_journal_entry_id`) USING BTREE,
  KEY `vendor_payments_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `vendor_payments_created_by_index` (`created_by`) USING BTREE,
  KEY `vendor_payments_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `vendor_payments_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `vendor_payments_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_payments_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_payments_posted_journal_entry_id_foreign` FOREIGN KEY (`posted_journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `vendor_payments_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_payments_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_payments_vendor_invoice_id_foreign` FOREIGN KEY (`vendor_invoice_id`) REFERENCES `vendor_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `virtual_accounts`
-- ----------------------------
DROP TABLE IF EXISTS `virtual_accounts`;
CREATE TABLE `virtual_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `payment_transaction_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned DEFAULT NULL,
  `applicant_id` bigint unsigned DEFAULT NULL,
  `bank_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `va_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `va_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `billing_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FIXED',
  `expiry_time` timestamp NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `paid_at` timestamp NULL DEFAULT NULL,
  `inquiry_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `raw_response` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `virtual_accounts_bank_va_unique` (`bank_code`,`va_number`) USING BTREE,
  KEY `virtual_accounts_school_index` (`school_id`) USING BTREE,
  KEY `virtual_accounts_transaction_index` (`payment_transaction_id`) USING BTREE,
  KEY `virtual_accounts_student_index` (`student_id`) USING BTREE,
  KEY `virtual_accounts_applicant_index` (`applicant_id`) USING BTREE,
  KEY `virtual_accounts_bank_index` (`bank_code`) USING BTREE,
  KEY `virtual_accounts_status_index` (`status`) USING BTREE,
  KEY `virtual_accounts_expiry_index` (`expiry_time`) USING BTREE,
  KEY `virtual_accounts_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `virtual_accounts_created_by_index` (`created_by`) USING BTREE,
  KEY `virtual_accounts_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `virtual_accounts_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `virtual_accounts_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `virtual_accounts_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `virtual_accounts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `wallet_cards`
-- ----------------------------
DROP TABLE IF EXISTS `wallet_cards`;
CREATE TABLE `wallet_cards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `digital_wallet_id` bigint unsigned NOT NULL,
  `card_uid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','blocked','lost','replaced') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `issued_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_card_uid` (`card_uid`),
  KEY `idx_card_wallet` (`digital_wallet_id`),
  CONSTRAINT `wallet_cards_wallet_fk` FOREIGN KEY (`digital_wallet_id`) REFERENCES `digital_wallets` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `wallet_topups`
-- ----------------------------
DROP TABLE IF EXISTS `wallet_topups`;
CREATE TABLE `wallet_topups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `digital_wallet_id` bigint unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `method` enum('qris','cash','transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','paid','expired','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `wallet_transaction_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_topup_ref` (`payment_reference`),
  KEY `idx_topup_wallet` (`digital_wallet_id`,`status`),
  KEY `wallet_topups_wtx_fk` (`wallet_transaction_id`),
  KEY `wallet_topups_user_fk` (`created_by`),
  CONSTRAINT `wallet_topups_user_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `wallet_topups_wallet_fk` FOREIGN KEY (`digital_wallet_id`) REFERENCES `digital_wallets` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `wallet_topups_wtx_fk` FOREIGN KEY (`wallet_transaction_id`) REFERENCES `wallet_transactions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `wallet_transactions`
-- ----------------------------
DROP TABLE IF EXISTS `wallet_transactions`;
CREATE TABLE `wallet_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `digital_wallet_id` bigint unsigned NOT NULL,
  `transaction_type` enum('topup','canteen_payment','refund','cashout','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `balance_before` decimal(15,2) NOT NULL,
  `balance_after` decimal(15,2) NOT NULL,
  `direction` enum('in','out') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` enum('cash','bank_transfer','qris','payment_gateway') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `reference_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `idempotency_key` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uq_wtx_idem` (`idempotency_key`),
  KEY `wallet_tx_wallet_index` (`digital_wallet_id`) USING BTREE,
  KEY `wallet_tx_ref_index` (`reference_number`) USING BTREE,
  KEY `wallet_tx_journal_index` (`journal_entry_id`) USING BTREE,
  KEY `wallet_tx_created_by_fk` (`created_by`) USING BTREE,
  KEY `wallet_tx_wallet_time_index` (`digital_wallet_id`,`created_at`),
  KEY `wallet_tx_morph_index` (`reference_type`,`reference_id`),
  CONSTRAINT `wallet_tx_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `wallet_tx_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `wallet_tx_wallet_fk` FOREIGN KEY (`digital_wallet_id`) REFERENCES `digital_wallets` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `chk_wtx_amount` CHECK ((`amount` > 0)),
  CONSTRAINT `chk_wtx_balance` CHECK ((((`direction` = _utf8mb4'in') and (`balance_after` = (`balance_before` + `amount`))) or ((`direction` = _utf8mb4'out') and (`balance_after` = (`balance_before` - `amount`)))))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
