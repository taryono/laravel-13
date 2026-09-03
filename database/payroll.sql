/*
Navicat Premium Data Transfer

Source Server         : localhost
Source Server Type    : MySQL
Source Server Version : 80031 (8.0.31)
Source Host           : localhost:3306
Source Schema         : laravel

Target Server Type    : MySQL
Target Server Version : 80031 (8.0.31)
File Encoding         : 65001

Date: 03/09/2026 07:17:32
 */
SET
    NAMES utf8mb4;

SET
    FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for account_categories
-- ----------------------------
DROP TABLE IF EXISTS `account_categories`;

CREATE TABLE
    `account_categories` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `code` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `type` enum (
                'asset',
                'liability',
                'equity',
                'revenue',
                'expense'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `normal_balance` enum ('debit', 'credit') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `description` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `account_categories_org_code_unique` (`organization_id` ASC, `code` ASC) USING BTREE,
            CONSTRAINT `account_categories_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for account_mappings
-- ----------------------------
DROP TABLE IF EXISTS `account_mappings`;

CREATE TABLE
    `account_mappings` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `source_type` enum (
            'payroll_component',
            'sales',
            'purchase',
            'expense',
            'tax',
            'loan',
            'bank',
            'other'
        ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `source_code` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `debit_account_id` bigint UNSIGNED NULL DEFAULT NULL,
            `credit_account_id` bigint UNSIGNED NULL DEFAULT NULL,
            `description` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `account_mappings_org_source_unique` (
                `organization_id` ASC,
                `source_type` ASC,
                `source_code` ASC
            ) USING BTREE,
            INDEX `account_mappings_debit_fk` (`debit_account_id` ASC) USING BTREE,
            INDEX `account_mappings_credit_fk` (`credit_account_id` ASC) USING BTREE,
            CONSTRAINT `account_mappings_credit_fk` FOREIGN KEY (`credit_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `account_mappings_debit_fk` FOREIGN KEY (`debit_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `account_mappings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for accounting_periods
-- ----------------------------
DROP TABLE IF EXISTS `accounting_periods`;

CREATE TABLE
    `accounting_periods` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `fiscal_year_id` bigint UNSIGNED NOT NULL,
        `period` varchar(7) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `start_date` date NOT NULL,
            `end_date` date NOT NULL,
            `status` enum ('open', 'soft_closed', 'closed') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'open',
            `closed_at` timestamp NULL DEFAULT NULL,
            `closed_by` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `accounting_periods_org_period_unique` (`organization_id` ASC, `period` ASC) USING BTREE,
            INDEX `accounting_periods_year_fk` (`fiscal_year_id` ASC) USING BTREE,
            CONSTRAINT `accounting_periods_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `accounting_periods_year_fk` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE
    `accounts` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `code` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `account_category_id` bigint UNSIGNED NOT NULL,
            `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
            `account_type` enum ('header', 'detail') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'detail',
            `normal_balance` enum ('debit', 'credit') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `currency_code` varchar(10) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'IDR',
            `description` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `opening_balance` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `current_balance` decimal(15, 2) NOT NULL DEFAULT 0.00 COMMENT 'Cache only; journal is source of truth',
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `is_system` tinyint (1) NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `accounts_org_code_unique` (`organization_id` ASC, `code` ASC) USING BTREE,
            INDEX `accounts_category_index` (`account_category_id` ASC) USING BTREE,
            INDEX `accounts_parent_index` (`parent_id` ASC) USING BTREE,
            CONSTRAINT `accounts_category_fk` FOREIGN KEY (`account_category_id`) REFERENCES `account_categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `accounts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `accounts_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for asset_depreciations
-- ----------------------------
DROP TABLE IF EXISTS `asset_depreciations`;

CREATE TABLE
    `asset_depreciations` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `fixed_asset_id` bigint UNSIGNED NOT NULL,
        `accounting_period_id` bigint UNSIGNED NOT NULL,
        `depreciation_date` date NOT NULL,
        `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
        `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`) USING BTREE,
        UNIQUE INDEX `asset_depreciations_asset_period_unique` (`fixed_asset_id` ASC, `accounting_period_id` ASC) USING BTREE,
        INDEX `asset_depreciations_period_fk` (`accounting_period_id` ASC) USING BTREE,
        INDEX `asset_depreciations_journal_fk` (`journal_entry_id` ASC) USING BTREE,
        CONSTRAINT `asset_depreciations_asset_fk` FOREIGN KEY (`fixed_asset_id`) REFERENCES `fixed_assets` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
        CONSTRAINT `asset_depreciations_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
        CONSTRAINT `asset_depreciations_period_fk` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for attendance_dailies
-- ----------------------------
DROP TABLE IF EXISTS `attendance_dailies`;

CREATE TABLE
    `attendance_dailies` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `employee_id` bigint UNSIGNED NOT NULL,
        `attendance_date` date NOT NULL,
        `status` enum (
            'present',
            'absent',
            'late',
            'leave',
            'holiday',
            'off',
            'half_day'
        ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `work_hours` decimal(8, 2) NOT NULL DEFAULT 0.00,
            `overtime_hours` decimal(8, 2) NOT NULL DEFAULT 0.00,
            `late_minutes` int NOT NULL DEFAULT 0,
            `source_log_id` bigint UNSIGNED NULL DEFAULT NULL,
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `attendance_dailies_employee_date_unique` (`employee_id` ASC, `attendance_date` ASC) USING BTREE,
            INDEX `attendance_dailies_org_fk` (`organization_id` ASC) USING BTREE,
            INDEX `attendance_dailies_log_fk` (`source_log_id` ASC) USING BTREE,
            CONSTRAINT `attendance_dailies_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `attendance_dailies_log_fk` FOREIGN KEY (`source_log_id`) REFERENCES `attendance_logs` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `attendance_dailies_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for attendance_logs
-- ----------------------------
DROP TABLE IF EXISTS `attendance_logs`;

CREATE TABLE
    `attendance_logs` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `employee_id` bigint UNSIGNED NOT NULL,
        `attendance_date` date NOT NULL,
        `check_in` datetime NULL DEFAULT NULL,
        `check_out` datetime NULL DEFAULT NULL,
        `source` enum ('manual', 'machine', 'import', 'api') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'manual',
            `source_reference` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `metadata` json NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `attendance_logs_employee_date_index` (`employee_id` ASC, `attendance_date` ASC) USING BTREE,
            INDEX `attendance_logs_org_fk` (`organization_id` ASC) USING BTREE,
            CONSTRAINT `attendance_logs_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `attendance_logs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bank_accounts
-- ----------------------------
DROP TABLE IF EXISTS `bank_accounts`;

CREATE TABLE
    `bank_accounts` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `account_id` bigint UNSIGNED NOT NULL,
        `bank_name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `account_number` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `account_name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `currency_code` varchar(10) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'IDR',
            `current_balance` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `is_default` tinyint (1) NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `bank_accounts_org_number_unique` (`organization_id` ASC, `account_number` ASC) USING BTREE,
            INDEX `bank_accounts_account_fk` (`account_id` ASC) USING BTREE,
            CONSTRAINT `bank_accounts_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `bank_accounts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bank_transactions
-- ----------------------------
DROP TABLE IF EXISTS `bank_transactions`;

CREATE TABLE
    `bank_transactions` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `bank_account_id` bigint UNSIGNED NOT NULL,
        `transaction_date` date NOT NULL,
        `transaction_type` enum ('debit', 'credit', 'transfer') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `description` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `balance_after` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `reference_number` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
            `is_reconciled` tinyint (1) NOT NULL DEFAULT 0,
            `reconciled_at` timestamp NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `bank_transactions_account_date_index` (`bank_account_id` ASC, `transaction_date` ASC) USING BTREE,
            INDEX `bank_transactions_journal_fk` (`journal_entry_id` ASC) USING BTREE,
            CONSTRAINT `bank_transactions_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `bank_transactions_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for budget_details
-- ----------------------------
DROP TABLE IF EXISTS `budget_details`;

CREATE TABLE
    `budget_details` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `budget_id` bigint UNSIGNED NOT NULL,
        `accounting_period_id` bigint UNSIGNED NULL DEFAULT NULL,
        `account_id` bigint UNSIGNED NOT NULL,
        `cost_center_id` bigint UNSIGNED NULL DEFAULT NULL,
        `budget_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
        `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `budget_details_budget_fk` (`budget_id` ASC) USING BTREE,
            INDEX `budget_details_period_fk` (`accounting_period_id` ASC) USING BTREE,
            INDEX `budget_details_account_fk` (`account_id` ASC) USING BTREE,
            INDEX `budget_details_cost_center_fk` (`cost_center_id` ASC) USING BTREE,
            CONSTRAINT `budget_details_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `budget_details_budget_fk` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `budget_details_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `budget_details_period_fk` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for budgets
-- ----------------------------
DROP TABLE IF EXISTS `budgets`;

CREATE TABLE
    `budgets` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `fiscal_year_id` bigint UNSIGNED NOT NULL,
        `code` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `status` enum ('draft', 'approved', 'locked', 'cancelled') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
            `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
            `approved_at` timestamp NULL DEFAULT NULL,
            `created_by` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `budgets_org_code_unique` (`organization_id` ASC, `code` ASC) USING BTREE,
            INDEX `budgets_year_fk` (`fiscal_year_id` ASC) USING BTREE,
            CONSTRAINT `budgets_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `budgets_year_fk` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cost_centers
-- ----------------------------
DROP TABLE IF EXISTS `cost_centers`;

CREATE TABLE
    `cost_centers` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `code` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
            `department_id` bigint UNSIGNED NULL DEFAULT NULL,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `cost_centers_org_code_unique` (`organization_id` ASC, `code` ASC) USING BTREE,
            INDEX `cost_centers_parent_fk` (`parent_id` ASC) USING BTREE,
            INDEX `cost_centers_department_fk` (`department_id` ASC) USING BTREE,
            CONSTRAINT `cost_centers_department_fk` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `cost_centers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `cost_centers_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;

CREATE TABLE
    `customers` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `code` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `phone` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `email` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `tax_number` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `address` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `receivable_account_id` bigint UNSIGNED NULL DEFAULT NULL,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `customers_org_code_unique` (`organization_id` ASC, `code` ASC) USING BTREE,
            INDEX `customers_receivable_account_fk` (`receivable_account_id` ASC) USING BTREE,
            CONSTRAINT `customers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `customers_receivable_account_fk` FOREIGN KEY (`receivable_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for departments
-- ----------------------------
DROP TABLE IF EXISTS `departments`;

CREATE TABLE
    `departments` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `code` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `departments_org_code_unique` (`organization_id` ASC, `code` ASC) USING BTREE,
            INDEX `departments_parent_id_index` (`parent_id` ASC) USING BTREE,
            CONSTRAINT `departments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `departments_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for employee_bonuses
-- ----------------------------
DROP TABLE IF EXISTS `employee_bonuses`;

CREATE TABLE
    `employee_bonuses` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `employee_id` bigint UNSIGNED NOT NULL,
        `organization_id` bigint UNSIGNED NOT NULL,
        `bonus_number` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `bonus_date` date NOT NULL,
            `bonus_type` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `payroll_detail_id` bigint UNSIGNED NULL DEFAULT NULL,
            `status` enum ('draft', 'approved', 'paid', 'cancelled') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
            `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
            `approved_at` timestamp NULL DEFAULT NULL,
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `employee_bonuses_org_number_unique` (`organization_id` ASC, `bonus_number` ASC) USING BTREE,
            INDEX `employee_bonuses_employee_fk` (`employee_id` ASC) USING BTREE,
            INDEX `employee_bonuses_payroll_fk` (`payroll_detail_id` ASC) USING BTREE,
            INDEX `employee_bonuses_organization_id_index` (`organization_id` ASC) USING BTREE,
            CONSTRAINT `employee_bonuses_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `employee_bonuses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `employee_bonuses_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for employee_contracts
-- ----------------------------
DROP TABLE IF EXISTS `employee_contracts`;

CREATE TABLE
    `employee_contracts` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `employee_id` bigint UNSIGNED NOT NULL,
        `organization_id` bigint UNSIGNED NOT NULL,
        `contract_number` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `contract_type` enum ('tetap', 'kontrak', 'probation', 'freelance') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `start_date` date NOT NULL,
            `end_date` date NULL DEFAULT NULL,
            `salary` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `salary_structure_id` bigint UNSIGNED NULL DEFAULT NULL,
            `status` enum ('draft', 'active', 'expired', 'terminated') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
            `document_path` varchar(500) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_by` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `employee_contracts_org_number_unique` (`organization_id` ASC, `contract_number` ASC) USING BTREE,
            INDEX `employee_contracts_employee_id_index` (`employee_id` ASC) USING BTREE,
            INDEX `employee_contracts_salary_structure_id_index` (`salary_structure_id` ASC) USING BTREE,
            INDEX `employee_contracts_organization_id_index` (`organization_id` ASC) USING BTREE,
            CONSTRAINT `employee_contracts_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `employee_contracts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `employee_contracts_salary_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for employee_deductions
-- ----------------------------
DROP TABLE IF EXISTS `employee_deductions`;

CREATE TABLE
    `employee_deductions` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `employee_id` bigint UNSIGNED NOT NULL,
        `organization_id` bigint UNSIGNED NOT NULL,
        `deduction_number` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `deduction_date` date NOT NULL,
            `deduction_type` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `recurring` tinyint (1) NOT NULL DEFAULT 0,
            `start_date` date NULL DEFAULT NULL,
            `end_date` date NULL DEFAULT NULL,
            `payroll_detail_id` bigint UNSIGNED NULL DEFAULT NULL,
            `status` enum (
                'draft',
                'approved',
                'active',
                'paid',
                'cancelled'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `employee_deductions_org_number_unique` (`organization_id` ASC, `deduction_number` ASC) USING BTREE,
            INDEX `employee_deductions_employee_fk` (`employee_id` ASC) USING BTREE,
            INDEX `employee_deductions_payroll_fk` (`payroll_detail_id` ASC) USING BTREE,
            INDEX `employee_deductions_organization_id_index` (`organization_id` ASC) USING BTREE,
            CONSTRAINT `employee_deductions_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `employee_deductions_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `employee_deductions_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for employee_leaves
-- ----------------------------
DROP TABLE IF EXISTS `employee_leaves`;

CREATE TABLE
    `employee_leaves` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `employee_id` bigint UNSIGNED NOT NULL,
        `leave_type_id` bigint UNSIGNED NOT NULL,
        `start_date` date NOT NULL,
        `end_date` date NOT NULL,
        `total_days` decimal(8, 2) NOT NULL DEFAULT 0.00,
        `reason` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `status` enum (
                'draft',
                'submitted',
                'approved',
                'rejected',
                'cancelled'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
            `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
            `approved_at` timestamp NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `employee_leaves_employee_fk` (`employee_id` ASC) USING BTREE,
            INDEX `employee_leaves_type_fk` (`leave_type_id` ASC) USING BTREE,
            CONSTRAINT `employee_leaves_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `employee_leaves_type_fk` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for employee_salary_components
-- ----------------------------
DROP TABLE IF EXISTS `employee_salary_components`;

CREATE TABLE
    `employee_salary_components` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `employee_id` bigint UNSIGNED NOT NULL,
        `salary_component_id` bigint UNSIGNED NOT NULL,
        `value` decimal(15, 4) NOT NULL DEFAULT 0.0000,
        `effective_from` date NOT NULL,
        `effective_to` date NULL DEFAULT NULL,
        `source` enum ('structure', 'override', 'manual') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'override',
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `esc_employee_component_index` (`employee_id` ASC, `salary_component_id` ASC) USING BTREE,
            INDEX `esc_component_fk` (`salary_component_id` ASC) USING BTREE,
            CONSTRAINT `esc_component_fk` FOREIGN KEY (`salary_component_id`) REFERENCES `salary_components` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `esc_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for employees
-- ----------------------------
DROP TABLE IF EXISTS `employees`;

CREATE TABLE
    `employees` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `nik` varchar(20) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `employee_number` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `user_id` bigint UNSIGNED NULL DEFAULT NULL,
            `department_id` bigint UNSIGNED NULL DEFAULT NULL,
            `position_id` bigint UNSIGNED NULL DEFAULT NULL,
            `supervisor_id` bigint UNSIGNED NULL DEFAULT NULL,
            `salary_structure_id` bigint UNSIGNED NULL DEFAULT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `nick_name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `gender` enum ('L', 'P') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `birth_place` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `birth_date` date NULL DEFAULT NULL,
            `address` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `phone` varchar(20) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `email` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `join_date` date NOT NULL,
            `resign_date` date NULL DEFAULT NULL,
            `employment_status` enum ('tetap', 'kontrak', 'probation', 'freelance') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'probation',
            `bank_name` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `bank_account_number` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `bank_account_name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `npwp` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `bpjs_ketenagakerjaan` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `bpjs_kesehatan` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `employees_org_number_unique` (`organization_id` ASC, `employee_number` ASC) USING BTREE,
            UNIQUE INDEX `employees_org_nik_unique` (`organization_id` ASC, `nik` ASC) USING BTREE,
            INDEX `employees_user_id_index` (`user_id` ASC) USING BTREE,
            INDEX `employees_department_id_index` (`department_id` ASC) USING BTREE,
            INDEX `employees_position_id_index` (`position_id` ASC) USING BTREE,
            INDEX `employees_supervisor_id_index` (`supervisor_id` ASC) USING BTREE,
            INDEX `employees_salary_structure_id_index` (`salary_structure_id` ASC) USING BTREE,
            CONSTRAINT `employees_department_fk` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `employees_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `employees_position_fk` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `employees_salary_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `employees_supervisor_fk` FOREIGN KEY (`supervisor_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for expense_items
-- ----------------------------
DROP TABLE IF EXISTS `expense_items`;

CREATE TABLE
    `expense_items` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `expense_id` bigint UNSIGNED NOT NULL,
        `description` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `quantity` decimal(15, 4) NOT NULL DEFAULT 1.0000,
            `unit_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `account_id` bigint UNSIGNED NOT NULL,
            `cost_center_id` bigint UNSIGNED NULL DEFAULT NULL,
            `tax_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `expense_items_expense_fk` (`expense_id` ASC) USING BTREE,
            INDEX `expense_items_account_fk` (`account_id` ASC) USING BTREE,
            INDEX `expense_items_cost_center_fk` (`cost_center_id` ASC) USING BTREE,
            CONSTRAINT `expense_items_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `expense_items_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `expense_items_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for expense_payments
-- ----------------------------
DROP TABLE IF EXISTS `expense_payments`;

CREATE TABLE
    `expense_payments` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `expense_id` bigint UNSIGNED NOT NULL,
        `organization_id` bigint UNSIGNED NOT NULL,
        `payment_number` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `payment_date` date NOT NULL,
            `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `payment_method` enum ('cash', 'bank_transfer', 'check', 'other') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `bank_account_id` bigint UNSIGNED NULL DEFAULT NULL,
            `reference_number` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
            `created_by` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `expense_payments_org_number_unique` (`organization_id` ASC, `payment_number` ASC) USING BTREE,
            INDEX `expense_payments_expense_fk` (`expense_id` ASC) USING BTREE,
            INDEX `expense_payments_bank_fk` (`bank_account_id` ASC) USING BTREE,
            INDEX `expense_payments_journal_fk` (`journal_entry_id` ASC) USING BTREE,
            INDEX `expense_payments_organization_id_index` (`organization_id` ASC) USING BTREE,
            CONSTRAINT `expense_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `expense_payments_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `expense_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `expense_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for expenses
-- ----------------------------
DROP TABLE IF EXISTS `expenses`;

CREATE TABLE
    `expenses` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `vendor_id` bigint UNSIGNED NULL DEFAULT NULL,
        `expense_number` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `expense_date` date NOT NULL,
            `vendor_name_snapshot` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `description` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `tax` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `payment_status` enum ('unpaid', 'partial', 'paid') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'unpaid',
            `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
            `attachment_path` varchar(500) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
            `approved_at` timestamp NULL DEFAULT NULL,
            `created_by` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `expenses_org_number_unique` (`organization_id` ASC, `expense_number` ASC) USING BTREE,
            INDEX `expenses_vendor_fk` (`vendor_id` ASC) USING BTREE,
            INDEX `expenses_journal_fk` (`journal_entry_id` ASC) USING BTREE,
            CONSTRAINT `expenses_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `expenses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `expenses_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for finance_audit_logs
-- ----------------------------
DROP TABLE IF EXISTS `finance_audit_logs`;

CREATE TABLE
    `finance_audit_logs` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
        `user_id` bigint UNSIGNED NULL DEFAULT NULL,
        `action` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `table_name` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `record_id` bigint UNSIGNED NULL DEFAULT NULL,
            `old_data` json NULL,
            `new_data` json NULL,
            `ip_address` varchar(45) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `user_agent` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `finance_audit_logs_org_index` (`organization_id` ASC) USING BTREE,
            INDEX `finance_audit_logs_table_record_index` (`table_name` ASC, `record_id` ASC) USING BTREE,
            INDEX `finance_audit_logs_created_index` (`created_at` ASC) USING BTREE,
            CONSTRAINT `finance_audit_logs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fiscal_years
-- ----------------------------
DROP TABLE IF EXISTS `fiscal_years`;

CREATE TABLE
    `fiscal_years` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `year` int NOT NULL,
        `start_date` date NOT NULL,
        `end_date` date NOT NULL,
        `status` enum ('open', 'closed') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'open',
            `is_closed` tinyint (1) NOT NULL DEFAULT 0,
            `closed_at` timestamp NULL DEFAULT NULL,
            `closed_by` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `fiscal_years_org_year_unique` (`organization_id` ASC, `year` ASC) USING BTREE,
            CONSTRAINT `fiscal_years_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fixed_assets
-- ----------------------------
DROP TABLE IF EXISTS `fixed_assets`;

CREATE TABLE
    `fixed_assets` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `asset_code` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `acquisition_date` date NOT NULL,
            `acquisition_cost` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `residual_value` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `useful_life_months` int NOT NULL DEFAULT 0,
            `depreciation_method` enum ('straight_line', 'declining_balance') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'straight_line',
            `accumulated_depreciation` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `book_value` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `asset_account_id` bigint UNSIGNED NOT NULL,
            `accumulated_depreciation_account_id` bigint UNSIGNED NULL DEFAULT NULL,
            `depreciation_expense_account_id` bigint UNSIGNED NULL DEFAULT NULL,
            `status` enum (
                'draft',
                'active',
                'disposed',
                'fully_depreciated'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
            `disposed_at` date NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `fixed_assets_org_code_unique` (`organization_id` ASC, `asset_code` ASC) USING BTREE,
            INDEX `fixed_assets_asset_account_fk` (`asset_account_id` ASC) USING BTREE,
            INDEX `fixed_assets_accum_account_fk` (`accumulated_depreciation_account_id` ASC) USING BTREE,
            INDEX `fixed_assets_depr_expense_account_fk` (`depreciation_expense_account_id` ASC) USING BTREE,
            CONSTRAINT `fixed_assets_accum_account_fk` FOREIGN KEY (`accumulated_depreciation_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `fixed_assets_asset_account_fk` FOREIGN KEY (`asset_account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `fixed_assets_depr_expense_account_fk` FOREIGN KEY (`depreciation_expense_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `fixed_assets_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for invoice_items
-- ----------------------------
DROP TABLE IF EXISTS `invoice_items`;

CREATE TABLE
    `invoice_items` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `invoice_id` bigint UNSIGNED NOT NULL,
        `product_code` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `product_name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `quantity` decimal(15, 4) NOT NULL DEFAULT 1.0000,
            `unit_price` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `discount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `tax_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_price` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `revenue_account_id` bigint UNSIGNED NULL DEFAULT NULL,
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `invoice_items_invoice_fk` (`invoice_id` ASC) USING BTREE,
            INDEX `invoice_items_revenue_account_fk` (`revenue_account_id` ASC) USING BTREE,
            CONSTRAINT `invoice_items_invoice_fk` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `invoice_items_revenue_account_fk` FOREIGN KEY (`revenue_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for invoice_payments
-- ----------------------------
DROP TABLE IF EXISTS `invoice_payments`;

CREATE TABLE
    `invoice_payments` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `invoice_id` bigint UNSIGNED NOT NULL,
        `organization_id` bigint UNSIGNED NOT NULL,
        `payment_number` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `payment_date` date NOT NULL,
            `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `payment_method` enum ('cash', 'bank_transfer', 'check', 'other') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `bank_account_id` bigint UNSIGNED NULL DEFAULT NULL,
            `reference_number` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_by` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `invoice_payments_org_number_unique` (`organization_id` ASC, `payment_number` ASC) USING BTREE,
            INDEX `invoice_payments_invoice_fk` (`invoice_id` ASC) USING BTREE,
            INDEX `invoice_payments_bank_fk` (`bank_account_id` ASC) USING BTREE,
            INDEX `invoice_payments_journal_fk` (`journal_entry_id` ASC) USING BTREE,
            INDEX `invoice_payments_organization_id_index` (`organization_id` ASC) USING BTREE,
            CONSTRAINT `invoice_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `invoice_payments_invoice_fk` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `invoice_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `invoice_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for invoices
-- ----------------------------
DROP TABLE IF EXISTS `invoices`;

CREATE TABLE
    `invoices` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `customer_id` bigint UNSIGNED NULL DEFAULT NULL,
        `invoice_number` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `customer_name_snapshot` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `customer_phone_snapshot` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `customer_email_snapshot` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `invoice_date` date NOT NULL,
            `due_date` date NOT NULL,
            `subtotal` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `tax` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `discount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `paid_amount` decimal(15, 2) NOT NULL DEFAULT 0.00 COMMENT 'Cache/summary; payments are source of truth',
            `remaining_amount` decimal(15, 2) NOT NULL DEFAULT 0.00 COMMENT 'Cache/summary',
            `status` enum (
                'draft',
                'sent',
                'partial',
                'paid',
                'overdue',
                'cancelled'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
            `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_by` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `invoices_org_number_unique` (`organization_id` ASC, `invoice_number` ASC) USING BTREE,
            INDEX `invoices_customer_fk` (`customer_id` ASC) USING BTREE,
            INDEX `invoices_journal_fk` (`journal_entry_id` ASC) USING BTREE,
            CONSTRAINT `invoices_customer_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `invoices_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `invoices_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for journal_details
-- ----------------------------
DROP TABLE IF EXISTS `journal_details`;

CREATE TABLE
    `journal_details` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `journal_entry_id` bigint UNSIGNED NOT NULL,
        `account_id` bigint UNSIGNED NOT NULL,
        `cost_center_id` bigint UNSIGNED NULL DEFAULT NULL,
        `description` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `debit` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `credit` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `reference_type` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `reference_id` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `journal_details_entry_index` (`journal_entry_id` ASC) USING BTREE,
            INDEX `journal_details_account_index` (`account_id` ASC) USING BTREE,
            INDEX `journal_details_cost_center_index` (`cost_center_id` ASC) USING BTREE,
            CONSTRAINT `journal_details_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `journal_details_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `journal_details_entry_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `journal_details_debit_credit_chk` CHECK (
                (`debit` >= 0)
                and (`credit` >= 0)
                and (
                    (`debit` <= 0)
                    or (`credit` <= 0)
                )
            )
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for journal_entries
-- ----------------------------
DROP TABLE IF EXISTS `journal_entries`;

CREATE TABLE
    `journal_entries` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `accounting_period_id` bigint UNSIGNED NOT NULL,
        `journal_number` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `journal_date` date NOT NULL,
            `description` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `source_type` enum (
                'manual',
                'payroll',
                'sales',
                'purchase',
                'expense',
                'transfer',
                'tax',
                'opening',
                'adjustment',
                'closing'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'manual',
            `source_id` bigint UNSIGNED NULL DEFAULT NULL,
            `reference_number` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `total_debit` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_credit` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `status` enum ('draft', 'approved', 'posted', 'void') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
            `reversal_of_id` bigint UNSIGNED NULL DEFAULT NULL,
            `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
            `approved_at` timestamp NULL DEFAULT NULL,
            `posted_by` bigint UNSIGNED NULL DEFAULT NULL,
            `posted_at` timestamp NULL DEFAULT NULL,
            `created_by` bigint UNSIGNED NULL DEFAULT NULL,
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `journal_entries_number_unique` (`organization_id` ASC, `journal_number` ASC) USING BTREE,
            INDEX `journal_entries_date_index` (`organization_id` ASC, `journal_date` ASC) USING BTREE,
            INDEX `journal_entries_source_index` (`source_type` ASC, `source_id` ASC) USING BTREE,
            INDEX `journal_entries_period_index` (`accounting_period_id` ASC) USING BTREE,
            INDEX `journal_entries_reversal_fk` (`reversal_of_id` ASC) USING BTREE,
            CONSTRAINT `journal_entries_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `journal_entries_period_fk` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `journal_entries_reversal_fk` FOREIGN KEY (`reversal_of_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for leave_balances
-- ----------------------------
DROP TABLE IF EXISTS `leave_balances`;

CREATE TABLE
    `leave_balances` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `employee_id` bigint UNSIGNED NOT NULL,
        `leave_type_id` bigint UNSIGNED NOT NULL,
        `year` int NOT NULL,
        `allocated_days` decimal(8, 2) NOT NULL DEFAULT 0.00,
        `used_days` decimal(8, 2) NOT NULL DEFAULT 0.00,
        `remaining_days` decimal(8, 2) NOT NULL DEFAULT 0.00,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`) USING BTREE,
        UNIQUE INDEX `leave_balances_unique` (
            `employee_id` ASC,
            `leave_type_id` ASC,
            `year` ASC
        ) USING BTREE,
        INDEX `leave_balances_type_fk` (`leave_type_id` ASC) USING BTREE,
        CONSTRAINT `leave_balances_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
        CONSTRAINT `leave_balances_type_fk` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for leave_types
-- ----------------------------
DROP TABLE IF EXISTS `leave_types`;

CREATE TABLE
    `leave_types` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `code` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `default_days` decimal(8, 2) NOT NULL DEFAULT 0.00,
            `is_paid` tinyint (1) NOT NULL DEFAULT 1,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `leave_types_org_code_unique` (`organization_id` ASC, `code` ASC) USING BTREE,
            CONSTRAINT `leave_types_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for loan_installments
-- ----------------------------
DROP TABLE IF EXISTS `loan_installments`;

CREATE TABLE
    `loan_installments` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `loan_id` bigint UNSIGNED NOT NULL,
        `installment_number` int NOT NULL,
        `due_date` date NOT NULL,
        `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
        `paid_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
        `status` enum ('pending', 'paid', 'partial', 'cancelled') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
            `payroll_detail_id` bigint UNSIGNED NULL DEFAULT NULL,
            `paid_at` timestamp NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `loan_installments_number_unique` (`loan_id` ASC, `installment_number` ASC) USING BTREE,
            INDEX `loan_installments_payroll_fk` (`payroll_detail_id` ASC) USING BTREE,
            CONSTRAINT `loan_installments_loan_fk` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `loan_installments_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for loans
-- ----------------------------
DROP TABLE IF EXISTS `loans`;

CREATE TABLE
    `loans` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `employee_id` bigint UNSIGNED NOT NULL,
        `loan_number` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `loan_date` date NOT NULL,
            `principal_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `installment_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_installments` int NOT NULL DEFAULT 0,
            `remaining_installments` int NOT NULL DEFAULT 0,
            `status` enum ('draft', 'active', 'completed', 'cancelled') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `loans_org_number_unique` (`organization_id` ASC, `loan_number` ASC) USING BTREE,
            INDEX `loans_employee_fk` (`employee_id` ASC) USING BTREE,
            CONSTRAINT `loans_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `loans_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;

CREATE TABLE
    `migrations` (
        `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
        `migration` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            `batch` int NOT NULL,
            PRIMARY KEY (`id`) USING BTREE
    ) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for navigation
-- ----------------------------
DROP TABLE IF EXISTS `navigation`;

CREATE TABLE
    `navigation` (
        `id` int NOT NULL AUTO_INCREMENT,
        `title` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `subtitle` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `intro` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `description` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `parent_id` int NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` datetime NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
    ) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for opening_balances
-- ----------------------------
DROP TABLE IF EXISTS `opening_balances`;

CREATE TABLE
    `opening_balances` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `fiscal_year_id` bigint UNSIGNED NOT NULL,
        `account_id` bigint UNSIGNED NOT NULL,
        `cost_center_id` bigint UNSIGNED NULL DEFAULT NULL,
        `debit` decimal(15, 2) NOT NULL DEFAULT 0.00,
        `credit` decimal(15, 2) NOT NULL DEFAULT 0.00,
        `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`) USING BTREE,
        UNIQUE INDEX `opening_balances_unique` (
            `organization_id` ASC,
            `fiscal_year_id` ASC,
            `account_id` ASC,
            `cost_center_id` ASC
        ) USING BTREE,
        INDEX `opening_balances_year_fk` (`fiscal_year_id` ASC) USING BTREE,
        INDEX `opening_balances_account_fk` (`account_id` ASC) USING BTREE,
        INDEX `opening_balances_cost_center_fk` (`cost_center_id` ASC) USING BTREE,
        INDEX `opening_balances_journal_fk` (`journal_entry_id` ASC) USING BTREE,
        CONSTRAINT `opening_balances_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
        CONSTRAINT `opening_balances_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
        CONSTRAINT `opening_balances_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
        CONSTRAINT `opening_balances_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
        CONSTRAINT `opening_balances_year_fk` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for organization_users
-- ----------------------------
DROP TABLE IF EXISTS `organization_users`;

CREATE TABLE
    `organization_users` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `user_id` bigint UNSIGNED NOT NULL,
        `is_default` tinyint (1) NOT NULL DEFAULT 0,
        `is_active` tinyint (1) NOT NULL DEFAULT 1,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`) USING BTREE,
        UNIQUE INDEX `organization_users_unique` (`organization_id` ASC, `user_id` ASC) USING BTREE,
        INDEX `organization_users_user_id_index` (`user_id` ASC) USING BTREE,
        CONSTRAINT `organization_users_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for organizations
-- ----------------------------
DROP TABLE IF EXISTS `organizations`;

CREATE TABLE
    `organizations` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `code` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `legal_name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `organization_type` enum (
                'foundation',
                'company',
                'school',
                'unit',
                'other'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'foundation',
            `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
            `currency_code` varchar(10) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'IDR',
            `timezone` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Asia/Jakarta',
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `organizations_code_unique` (`code` ASC) USING BTREE,
            INDEX `organizations_parent_id_index` (`parent_id` ASC) USING BTREE,
            CONSTRAINT `organizations_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for overtimes
-- ----------------------------
DROP TABLE IF EXISTS `overtimes`;

CREATE TABLE
    `overtimes` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `employee_id` bigint UNSIGNED NOT NULL,
        `attendance_daily_id` bigint UNSIGNED NULL DEFAULT NULL,
        `overtime_date` date NOT NULL,
        `start_at` datetime NULL DEFAULT NULL,
        `end_at` datetime NULL DEFAULT NULL,
        `hours` decimal(8, 2) NOT NULL DEFAULT 0.00,
        `hourly_rate` decimal(15, 2) NOT NULL DEFAULT 0.00 COMMENT 'Snapshot nominal upah per jam saat lembur dihitung',
        `rate_multiplier` decimal(8, 4) NOT NULL DEFAULT 1.0000 COMMENT 'Multiplier lembur, misalnya 1.5x atau 2x',
        `amount` decimal(15, 2) NOT NULL DEFAULT 0.00 COMMENT 'Snapshot total nominal lembur',
        `status` enum (
            'draft',
            'submitted',
            'approved',
            'rejected',
            'cancelled'
        ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
            `submitted_at` timestamp NULL DEFAULT NULL,
            `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
            `approved_at` timestamp NULL DEFAULT NULL,
            `rejected_by` bigint UNSIGNED NULL DEFAULT NULL,
            `rejected_at` timestamp NULL DEFAULT NULL,
            `rejection_reason` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `overtimes_org_idx` (`organization_id` ASC) USING BTREE,
            INDEX `overtimes_employee_idx` (`employee_id` ASC) USING BTREE,
            INDEX `overtimes_daily_idx` (`attendance_daily_id` ASC) USING BTREE,
            INDEX `overtimes_date_idx` (`overtime_date` ASC) USING BTREE,
            INDEX `overtimes_status_idx` (`status` ASC) USING BTREE,
            INDEX `overtimes_employee_date_idx` (`employee_id` ASC, `overtime_date` ASC) USING BTREE,
            CONSTRAINT `overtimes_daily_fk` FOREIGN KEY (`attendance_daily_id`) REFERENCES `attendance_dailies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `overtimes_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `overtimes_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payroll_detail_components
-- ----------------------------
DROP TABLE IF EXISTS `payroll_detail_components`;

CREATE TABLE
    `payroll_detail_components` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `payroll_detail_id` bigint UNSIGNED NOT NULL,
        `salary_component_id` bigint UNSIGNED NULL DEFAULT NULL,
        `component_code` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `component_name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `component_type` enum (
                'earning',
                'deduction',
                'company_contribution',
                'tax'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `quantity` decimal(15, 4) NOT NULL DEFAULT 1.0000,
            `rate` decimal(15, 4) NOT NULL DEFAULT 0.0000,
            `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `calculation_source` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `metadata` json NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `pdc_detail_index` (`payroll_detail_id` ASC) USING BTREE,
            INDEX `pdc_component_index` (`salary_component_id` ASC) USING BTREE,
            CONSTRAINT `pdc_component_fk` FOREIGN KEY (`salary_component_id`) REFERENCES `salary_components` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `pdc_detail_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payroll_details
-- ----------------------------
DROP TABLE IF EXISTS `payroll_details`;

CREATE TABLE
    `payroll_details` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `payroll_run_id` bigint UNSIGNED NOT NULL,
        `employee_id` bigint UNSIGNED NOT NULL,
        `employee_number_snapshot` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `employee_name_snapshot` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `department_name_snapshot` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `position_name_snapshot` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `gross_salary` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_deduction` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_company_contribution` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_tax` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `net_salary` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `component_details` json NULL,
            `calculation_metadata` json NULL,
            `status` enum ('calculated', 'approved', 'paid', 'cancelled') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'calculated',
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `payroll_details_run_employee_unique` (`payroll_run_id` ASC, `employee_id` ASC) USING BTREE,
            INDEX `payroll_details_employee_index` (`employee_id` ASC) USING BTREE,
            CONSTRAINT `payroll_details_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `payroll_details_run_fk` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payroll_integrations
-- ----------------------------
DROP TABLE IF EXISTS `payroll_integrations`;

CREATE TABLE
    `payroll_integrations` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `payroll_run_id` bigint UNSIGNED NOT NULL,
        `payroll_period_id` bigint UNSIGNED NOT NULL,
        `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
        `integration_code` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `total_gross_salary` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_net_salary` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_tax` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_bpjs_company` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_bpjs_employee` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_loans` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_other_deductions` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `mapping_config` json NULL,
            `status` enum ('pending', 'mapped', 'synced', 'failed') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
            `synced_at` timestamp NULL DEFAULT NULL,
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_by` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `payroll_integrations_run_unique` (`payroll_run_id` ASC) USING BTREE,
            UNIQUE INDEX `payroll_integrations_org_code_unique` (`organization_id` ASC, `integration_code` ASC) USING BTREE,
            INDEX `payroll_integrations_period_fk` (`payroll_period_id` ASC) USING BTREE,
            INDEX `payroll_integrations_journal_fk` (`journal_entry_id` ASC) USING BTREE,
            CONSTRAINT `payroll_integrations_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `payroll_integrations_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `payroll_integrations_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `payroll_integrations_run_fk` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payroll_payment_batches
-- ----------------------------
DROP TABLE IF EXISTS `payroll_payment_batches`;

CREATE TABLE
    `payroll_payment_batches` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `payroll_run_id` bigint UNSIGNED NOT NULL,
        `bank_account_id` bigint UNSIGNED NOT NULL,
        `batch_number` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `payment_date` date NOT NULL,
            `total_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `employee_count` int NOT NULL DEFAULT 0,
            `status` enum (
                'draft',
                'approved',
                'processing',
                'completed',
                'partial',
                'failed',
                'cancelled'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
            `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
            `approved_at` timestamp NULL DEFAULT NULL,
            `processed_at` timestamp NULL DEFAULT NULL,
            `created_by` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `payroll_payment_batches_org_number_unique` (`organization_id` ASC, `batch_number` ASC) USING BTREE,
            INDEX `ppb_run_fk` (`payroll_run_id` ASC) USING BTREE,
            INDEX `ppb_bank_fk` (`bank_account_id` ASC) USING BTREE,
            CONSTRAINT `ppb_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `ppb_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `ppb_run_fk` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payroll_payments
-- ----------------------------
DROP TABLE IF EXISTS `payroll_payments`;

CREATE TABLE
    `payroll_payments` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `payroll_payment_batch_id` bigint UNSIGNED NOT NULL,
        `payroll_detail_id` bigint UNSIGNED NOT NULL,
        `employee_id` bigint UNSIGNED NOT NULL,
        `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
        `status` enum (
            'pending',
            'processing',
            'success',
            'failed',
            'cancelled'
        ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
            `bank_reference` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `paid_at` timestamp NULL DEFAULT NULL,
            `failure_reason` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `payroll_payments_batch_detail_unique` (
                `payroll_payment_batch_id` ASC,
                `payroll_detail_id` ASC
            ) USING BTREE,
            INDEX `payroll_payments_detail_fk` (`payroll_detail_id` ASC) USING BTREE,
            INDEX `payroll_payments_employee_fk` (`employee_id` ASC) USING BTREE,
            INDEX `payroll_payments_journal_fk` (`journal_entry_id` ASC) USING BTREE,
            CONSTRAINT `payroll_payments_batch_fk` FOREIGN KEY (`payroll_payment_batch_id`) REFERENCES `payroll_payment_batches` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `payroll_payments_detail_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `payroll_payments_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `payroll_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payroll_periods
-- ----------------------------
DROP TABLE IF EXISTS `payroll_periods`;

CREATE TABLE
    `payroll_periods` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `period` varchar(7) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `start_date` date NOT NULL,
            `end_date` date NOT NULL,
            `payment_date` date NULL DEFAULT NULL,
            `status` enum (
                'open',
                'processing',
                'approved',
                'paid',
                'closed',
                'cancelled'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'open',
            `is_closed` tinyint (1) NOT NULL DEFAULT 0,
            `closed_at` timestamp NULL DEFAULT NULL,
            `closed_by` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `payroll_periods_org_period_unique` (`organization_id` ASC, `period` ASC) USING BTREE,
            CONSTRAINT `payroll_periods_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payroll_runs
-- ----------------------------
DROP TABLE IF EXISTS `payroll_runs`;

CREATE TABLE
    `payroll_runs` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `payroll_period_id` bigint UNSIGNED NOT NULL,
        `run_number` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `run_type` enum ('regular', 'off_cycle', 'correction') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'regular',
            `status` enum (
                'draft',
                'calculating',
                'calculated',
                'reviewed',
                'approved',
                'posted',
                'paid',
                'cancelled',
                'failed'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
            `total_gross` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_deduction` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_company_contribution` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_tax` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `total_net` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `calculated_at` timestamp NULL DEFAULT NULL,
            `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
            `approved_at` timestamp NULL DEFAULT NULL,
            `posted_by` bigint UNSIGNED NULL DEFAULT NULL,
            `posted_at` timestamp NULL DEFAULT NULL,
            `locked_by` bigint UNSIGNED NULL DEFAULT NULL,
            `locked_at` timestamp NULL DEFAULT NULL,
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_by` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `payroll_runs_org_number_unique` (`organization_id` ASC, `run_number` ASC) USING BTREE,
            INDEX `payroll_runs_period_index` (`payroll_period_id` ASC) USING BTREE,
            CONSTRAINT `payroll_runs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `payroll_runs_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payslips
-- ----------------------------
DROP TABLE IF EXISTS `payslips`;

CREATE TABLE
    `payslips` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `payroll_detail_id` bigint UNSIGNED NOT NULL,
        `employee_id` bigint UNSIGNED NOT NULL,
        `organization_id` bigint UNSIGNED NOT NULL,
        `payroll_period_id` bigint UNSIGNED NOT NULL,
        `payslip_number` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `generated_at` timestamp NULL DEFAULT NULL,
            `pdf_path` varchar(500) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `is_sent` tinyint (1) NOT NULL DEFAULT 0,
            `sent_at` timestamp NULL DEFAULT NULL,
            `is_downloaded` tinyint (1) NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `payslips_detail_unique` (`payroll_detail_id` ASC) USING BTREE,
            UNIQUE INDEX `payslips_org_number_unique` (`organization_id` ASC, `payslip_number` ASC) USING BTREE,
            INDEX `payslips_employee_fk` (`employee_id` ASC) USING BTREE,
            INDEX `payslips_period_fk` (`payroll_period_id` ASC) USING BTREE,
            INDEX `payslips_organization_id_index` (`organization_id` ASC) USING BTREE,
            CONSTRAINT `payslips_detail_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `payslips_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `payslips_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `payslips_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for positions
-- ----------------------------
DROP TABLE IF EXISTS `positions`;

CREATE TABLE
    `positions` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `code` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `level` int NULL DEFAULT NULL,
            `salary_range_min` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `salary_range_max` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `positions_org_code_unique` (`organization_id` ASC, `code` ASC) USING BTREE,
            CONSTRAINT `positions_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for reconciliation_details
-- ----------------------------
DROP TABLE IF EXISTS `reconciliation_details`;

CREATE TABLE
    `reconciliation_details` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `reconciliation_id` bigint UNSIGNED NOT NULL,
        `bank_transaction_id` bigint UNSIGNED NULL DEFAULT NULL,
        `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
        `transaction_date` date NOT NULL,
        `description` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `type` enum ('match', 'unmatch', 'adjustment') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `reconciliation_details_recon_fk` (`reconciliation_id` ASC) USING BTREE,
            INDEX `reconciliation_details_bank_fk` (`bank_transaction_id` ASC) USING BTREE,
            INDEX `reconciliation_details_journal_fk` (`journal_entry_id` ASC) USING BTREE,
            CONSTRAINT `reconciliation_details_bank_fk` FOREIGN KEY (`bank_transaction_id`) REFERENCES `bank_transactions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `reconciliation_details_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `reconciliation_details_recon_fk` FOREIGN KEY (`reconciliation_id`) REFERENCES `reconciliations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for reconciliations
-- ----------------------------
DROP TABLE IF EXISTS `reconciliations`;

CREATE TABLE
    `reconciliations` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `bank_account_id` bigint UNSIGNED NOT NULL,
        `reconciliation_date` date NOT NULL,
        `statement_balance` decimal(15, 2) NOT NULL DEFAULT 0.00,
        `book_balance` decimal(15, 2) NOT NULL DEFAULT 0.00,
        `difference` decimal(15, 2) NOT NULL DEFAULT 0.00,
        `status` enum ('pending', 'completed', 'failed') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
            `notes` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `completed_by` bigint UNSIGNED NULL DEFAULT NULL,
            `completed_at` timestamp NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `reconciliations_bank_fk` (`bank_account_id` ASC) USING BTREE,
            CONSTRAINT `reconciliations_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for salary_components
-- ----------------------------
DROP TABLE IF EXISTS `salary_components`;

CREATE TABLE
    `salary_components` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `code` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `type` enum (
                'earning',
                'deduction',
                'company_contribution',
                'tax'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `calculation_type` enum (
                'fixed',
                'percentage',
                'formula',
                'attendance',
                'loan',
                'tax',
                'manual'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'fixed',
            `calculation_base` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `default_value` decimal(15, 4) NOT NULL DEFAULT 0.0000,
            `is_taxable` tinyint (1) NOT NULL DEFAULT 0,
            `is_bpjs_base` tinyint (1) NOT NULL DEFAULT 0,
            `affects_net_salary` tinyint (1) NOT NULL DEFAULT 1,
            `is_recurring` tinyint (1) NOT NULL DEFAULT 1,
            `sort_order` int NOT NULL DEFAULT 0,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `salary_components_org_code_unique` (`organization_id` ASC, `code` ASC) USING BTREE,
            CONSTRAINT `salary_components_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for salary_structure_components
-- ----------------------------
DROP TABLE IF EXISTS `salary_structure_components`;

CREATE TABLE
    `salary_structure_components` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `salary_structure_id` bigint UNSIGNED NOT NULL,
        `salary_component_id` bigint UNSIGNED NOT NULL,
        `calculation_type` enum ('fixed', 'percentage', 'formula', 'inherit') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'inherit',
            `value` decimal(15, 4) NOT NULL DEFAULT 0.0000,
            `calculation_base` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `calculation_order` int NOT NULL DEFAULT 0,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `salary_structure_components_unique` (
                `salary_structure_id` ASC,
                `salary_component_id` ASC
            ) USING BTREE,
            INDEX `ssc_component_fk` (`salary_component_id` ASC) USING BTREE,
            CONSTRAINT `ssc_component_fk` FOREIGN KEY (`salary_component_id`) REFERENCES `salary_components` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT `ssc_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for salary_structures
-- ----------------------------
DROP TABLE IF EXISTS `salary_structures`;

CREATE TABLE
    `salary_structures` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `code` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `description` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `salary_structures_org_code_unique` (`organization_id` ASC, `code` ASC) USING BTREE,
            CONSTRAINT `salary_structures_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tax_liabilities
-- ----------------------------
DROP TABLE IF EXISTS `tax_liabilities`;

CREATE TABLE
    `tax_liabilities` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `tax_type` enum (
            'pph21',
            'pph23',
            'pph25',
            'ppn',
            'ppnbm',
            'other'
        ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `tax_period` varchar(7) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `tax_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `paid_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `due_date` date NOT NULL,
            `status` enum (
                'pending',
                'partial',
                'paid',
                'overdue',
                'cancelled'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
            `source_type` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `source_id` bigint UNSIGNED NULL DEFAULT NULL,
            `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            INDEX `tax_liabilities_org_period_index` (
                `organization_id` ASC,
                `tax_type` ASC,
                `tax_period` ASC
            ) USING BTREE,
            INDEX `tax_liabilities_journal_fk` (`journal_entry_id` ASC) USING BTREE,
            CONSTRAINT `tax_liabilities_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `tax_liabilities_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tax_payments
-- ----------------------------
DROP TABLE IF EXISTS `tax_payments`;

CREATE TABLE
    `tax_payments` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `tax_liability_id` bigint UNSIGNED NOT NULL,
        `payment_number` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `payment_date` date NOT NULL,
            `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
            `payment_reference` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `bank_account_id` bigint UNSIGNED NULL DEFAULT NULL,
            `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
            `status` enum ('draft', 'paid', 'cancelled') CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
            `created_by` bigint UNSIGNED NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `tax_payments_number_unique` (`payment_number` ASC) USING BTREE,
            INDEX `tax_payments_liability_fk` (`tax_liability_id` ASC) USING BTREE,
            INDEX `tax_payments_bank_fk` (`bank_account_id` ASC) USING BTREE,
            INDEX `tax_payments_journal_fk` (`journal_entry_id` ASC) USING BTREE,
            CONSTRAINT `tax_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `tax_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
            CONSTRAINT `tax_payments_liability_fk` FOREIGN KEY (`tax_liability_id`) REFERENCES `tax_liabilities` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tax_settings
-- ----------------------------
DROP TABLE IF EXISTS `tax_settings`;

CREATE TABLE
    `tax_settings` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `tax_code` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `tax_name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `tax_type` enum (
                'pph21',
                'pph23',
                'pph25',
                'ppn',
                'ppnbm',
                'other'
            ) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `tax_rate` decimal(7, 4) NOT NULL DEFAULT 0.0000,
            `min_amount` decimal(15, 2) NULL DEFAULT NULL,
            `max_amount` decimal(15, 2) NULL DEFAULT NULL,
            `effective_date` date NOT NULL,
            `expiry_date` date NULL DEFAULT NULL,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `description` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `tax_settings_org_code_unique` (`organization_id` ASC, `tax_code` ASC) USING BTREE,
            CONSTRAINT `tax_settings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;

CREATE TABLE
    `users` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            `email` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            `email_verified_at` timestamp NULL DEFAULT NULL,
            `password` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            `remember_token` varchar(100) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
    ) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for vendors
-- ----------------------------
DROP TABLE IF EXISTS `vendors`;

CREATE TABLE
    `vendors` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `organization_id` bigint UNSIGNED NOT NULL,
        `code` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `name` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
            `phone` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `email` varchar(255) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `tax_number` varchar(50) CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
            `address` text CHARACTER
        SET
            utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
            `payable_account_id` bigint UNSIGNED NULL DEFAULT NULL,
            `is_active` tinyint (1) NOT NULL DEFAULT 1,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE,
            UNIQUE INDEX `vendors_org_code_unique` (`organization_id` ASC, `code` ASC) USING BTREE,
            INDEX `vendors_payable_account_fk` (`payable_account_id` ASC) USING BTREE,
            CONSTRAINT `vendors_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `vendors_payable_account_fk` FOREIGN KEY (`payable_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
    ) ENGINE = InnoDB CHARACTER
SET
    = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

SET
    FOREIGN_KEY_CHECKS = 1;