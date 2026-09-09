/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80031 (8.0.31)
 Source Host           : localhost:3306
 Source Schema         : l-13

 Target Server Type    : MySQL
 Target Server Version : 80031 (8.0.31)
 File Encoding         : 65001

 Date: 10/09/2026 03:51:51
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for academic_years
-- ----------------------------
DROP TABLE IF EXISTS `academic_years`;
CREATE TABLE `academic_years`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `semester_start_date` date NULL DEFAULT NULL,
  `semester_end_date` date NULL DEFAULT NULL,
  `is_registration_open` tinyint(1) NULL DEFAULT 0,
  `registration_start_date` date NULL DEFAULT NULL,
  `registration_end_date` date NULL DEFAULT NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `is_current` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `academic_years_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `idx_academic_years_date_range`(`start_date` ASC, `end_date` ASC) USING BTREE,
  INDEX `idx_academic_years_is_current`(`is_current` ASC) USING BTREE,
  INDEX `idx_academic_years_is_active`(`is_active` ASC) USING BTREE,
  INDEX `idx_academic_years_deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `academic_years_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of academic_years
-- ----------------------------

-- ----------------------------
-- Table structure for account_categories
-- ----------------------------
DROP TABLE IF EXISTS `account_categories`;
CREATE TABLE `account_categories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('aset','kewajiban','ekuitas','pendapatan','beban') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `normal_balance` enum('debit','kredit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account_categories_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `account_categories_type_index`(`type` ASC) USING BTREE,
  INDEX `account_categories_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `account_categories_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `account_categories_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of account_categories
-- ----------------------------

-- ----------------------------
-- Table structure for account_mappings
-- ----------------------------
DROP TABLE IF EXISTS `account_mappings`;
CREATE TABLE `account_mappings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `source_type` enum('payroll_component','sales','purchase','expense','tax','loan','bank','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `debit_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `credit_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account_mappings_org_source_unique`(`organization_id` ASC, `source_type` ASC, `source_code` ASC) USING BTREE,
  INDEX `account_mappings_debit_fk`(`debit_account_id` ASC) USING BTREE,
  INDEX `account_mappings_credit_fk`(`credit_account_id` ASC) USING BTREE,
  INDEX `account_mappings_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `account_mappings_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `account_mappings_credit_fk` FOREIGN KEY (`credit_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `account_mappings_debit_fk` FOREIGN KEY (`debit_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `account_mappings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of account_mappings
-- ----------------------------

-- ----------------------------
-- Table structure for accounting_periods
-- ----------------------------
DROP TABLE IF EXISTS `accounting_periods`;
CREATE TABLE `accounting_periods`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `fiscal_year_id` bigint UNSIGNED NOT NULL,
  `period` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('open','soft_closed','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `accounting_periods_org_period_unique`(`organization_id` ASC, `period` ASC) USING BTREE,
  INDEX `accounting_periods_year_fk`(`fiscal_year_id` ASC) USING BTREE,
  INDEX `accounting_periods_status_index`(`status` ASC) USING BTREE,
  INDEX `accounting_periods_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `accounting_periods_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `accounting_periods_year_fk` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of accounting_periods
-- ----------------------------

-- ----------------------------
-- Table structure for accounts
-- ----------------------------
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_category_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `account_type` enum('header','detail') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'detail',
  `normal_balance` enum('debit','kredit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `opening_balance` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `accounts_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `accounts_category_index`(`account_category_id` ASC) USING BTREE,
  INDEX `accounts_parent_index`(`parent_id` ASC) USING BTREE,
  INDEX `accounts_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `accounts_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `accounts_category_fk` FOREIGN KEY (`account_category_id`) REFERENCES `account_categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `accounts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `accounts_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of accounts
-- ----------------------------

-- ----------------------------
-- Table structure for action_role
-- ----------------------------
DROP TABLE IF EXISTS `action_role`;
CREATE TABLE `action_role`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `action_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `action_role_action_id_role_id_unique`(`action_id` ASC, `role_id` ASC) USING BTREE,
  INDEX `action_role_action_id_foreign`(`action_id` ASC) USING BTREE,
  INDEX `action_role_role_id_foreign`(`role_id` ASC) USING BTREE,
  INDEX `action_role_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `action_role_action_id_foreign` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `action_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of action_role
-- ----------------------------
INSERT INTO `action_role` VALUES (1, 5, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `action_role` VALUES (2, 4, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `action_role` VALUES (3, 3, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `action_role` VALUES (4, 2, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `action_role` VALUES (5, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `action_role` VALUES (6, 1, 2, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `action_role` VALUES (7, 2, 2, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `action_role` VALUES (8, 4, 3, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `action_role` VALUES (9, 5, 3, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for action_user
-- ----------------------------
DROP TABLE IF EXISTS `action_user`;
CREATE TABLE `action_user`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `action_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `action_user_action_id_user_id_unique`(`action_id` ASC, `user_id` ASC) USING BTREE,
  INDEX `action_user_action_id_foreign`(`action_id` ASC) USING BTREE,
  INDEX `action_user_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `action_user_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `action_user_action_id_foreign` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `action_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of action_user
-- ----------------------------

-- ----------------------------
-- Table structure for actions
-- ----------------------------
DROP TABLE IF EXISTS `actions`;
CREATE TABLE `actions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `menu_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `actions_org_name_unique`(`organization_id` ASC, `name` ASC) USING BTREE,
  INDEX `actions_menu_id_foreign`(`menu_id` ASC) USING BTREE,
  INDEX `actions_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `actions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `actions_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `actions_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of actions
-- ----------------------------
INSERT INTO `actions` VALUES (1, 1, 3, 'user-view', 'Lihat Daftar Pengguna', 'Melihat daftar pengguna', 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `actions` VALUES (2, 1, 3, 'user-manage', 'Kelola Pengguna', 'Membuat, mengedit, dan menghapus pengguna', 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `actions` VALUES (3, 1, 4, 'roles-manage', 'Kelola Peran', 'Mengelola peran dan izin', 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `actions` VALUES (4, 1, 5, 'profile-view', 'Lihat Profil', 'Melihat profil pengguna', 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `actions` VALUES (5, 1, 5, 'profile-edit', 'Edit Profil', 'Mengedit profil pengguna', 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for api_rate_limits
-- ----------------------------
DROP TABLE IF EXISTS `api_rate_limits`;
CREATE TABLE `api_rate_limits`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `api_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `endpoint` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requests` int NOT NULL DEFAULT 0,
  `limit` int NOT NULL DEFAULT 1000,
  `reset_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_api_rate_limits_key_endpoint`(`api_key` ASC, `endpoint` ASC) USING BTREE,
  INDEX `idx_api_rate_limits_org`(`organization_id` ASC) USING BTREE,
  CONSTRAINT `api_rate_limits_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of api_rate_limits
-- ----------------------------

-- ----------------------------
-- Table structure for asset_depreciations
-- ----------------------------
DROP TABLE IF EXISTS `asset_depreciations`;
CREATE TABLE `asset_depreciations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `fixed_asset_id` bigint UNSIGNED NOT NULL,
  `accounting_period_id` bigint UNSIGNED NOT NULL,
  `depreciation_date` date NOT NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `asset_depreciations_asset_period_unique`(`fixed_asset_id` ASC, `accounting_period_id` ASC) USING BTREE,
  INDEX `asset_depreciations_period_fk`(`accounting_period_id` ASC) USING BTREE,
  INDEX `asset_depreciations_journal_fk`(`journal_entry_id` ASC) USING BTREE,
  INDEX `asset_depreciations_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `asset_depreciations_asset_fk` FOREIGN KEY (`fixed_asset_id`) REFERENCES `fixed_assets` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `asset_depreciations_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `asset_depreciations_period_fk` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of asset_depreciations
-- ----------------------------

-- ----------------------------
-- Table structure for attendance_dailies
-- ----------------------------
DROP TABLE IF EXISTS `attendance_dailies`;
CREATE TABLE `attendance_dailies`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('present','absent','late','leave','holiday','off','half_day','sick','permit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_hours` decimal(8, 2) NOT NULL DEFAULT 0.00,
  `overtime_hours` decimal(8, 2) NOT NULL DEFAULT 0.00,
  `late_minutes` int NOT NULL DEFAULT 0,
  `early_leave_minutes` int NOT NULL DEFAULT 0,
  `source_log_id` bigint UNSIGNED NULL DEFAULT NULL,
  `check_in_time` datetime NULL DEFAULT NULL,
  `check_out_time` datetime NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `attendance_dailies_employee_date_unique`(`employee_id` ASC, `attendance_date` ASC) USING BTREE,
  INDEX `attendance_dailies_org_fk`(`organization_id` ASC) USING BTREE,
  INDEX `attendance_dailies_log_fk`(`source_log_id` ASC) USING BTREE,
  INDEX `attendance_dailies_status_index`(`status` ASC) USING BTREE,
  INDEX `attendance_dailies_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `attendance_dailies_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `attendance_dailies_log_fk` FOREIGN KEY (`source_log_id`) REFERENCES `attendance_logs` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `attendance_dailies_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of attendance_dailies
-- ----------------------------

-- ----------------------------
-- Table structure for attendance_import_batches
-- ----------------------------
DROP TABLE IF EXISTS `attendance_import_batches`;
CREATE TABLE `attendance_import_batches`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `batch_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int NULL DEFAULT NULL,
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `machine_source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `total_records` int NULL DEFAULT 0,
  `processed_records` int NULL DEFAULT 0,
  `failed_records` int NULL DEFAULT 0,
  `status` enum('uploaded','processing','completed','failed','partial') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'uploaded',
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `errors` json NULL,
  `imported_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `attendance_import_batches_batch_code_unique`(`batch_code` ASC) USING BTREE,
  INDEX `attendance_import_batches_org_fk`(`organization_id` ASC) USING BTREE,
  INDEX `attendance_import_batches_status_index`(`status` ASC) USING BTREE,
  INDEX `attendance_import_batches_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `attendance_import_batches_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of attendance_import_batches
-- ----------------------------

-- ----------------------------
-- Table structure for attendance_logs
-- ----------------------------
DROP TABLE IF EXISTS `attendance_logs`;
CREATE TABLE `attendance_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `attendance_date` date NOT NULL,
  `check_in` datetime NULL DEFAULT NULL,
  `check_out` datetime NULL DEFAULT NULL,
  `source` enum('manual','machine','import','api','mobile') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `source_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `device_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `location` json NULL,
  `metadata` json NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `attendance_logs_employee_date_index`(`employee_id` ASC, `attendance_date` ASC) USING BTREE,
  INDEX `attendance_logs_org_fk`(`organization_id` ASC) USING BTREE,
  INDEX `attendance_logs_source_index`(`source` ASC) USING BTREE,
  INDEX `attendance_logs_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `attendance_logs_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `attendance_logs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of attendance_logs
-- ----------------------------

-- ----------------------------
-- Table structure for attendances
-- ----------------------------
DROP TABLE IF EXISTS `attendances`;
CREATE TABLE `attendances`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `attendance_daily_id` bigint UNSIGNED NULL DEFAULT NULL,
  `payroll_period_id` bigint UNSIGNED NULL DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('hadir','sakit','izin','alpha','cuti','dinas_luar','training','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hadir',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `attachment_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `attendance_employee_date_unique`(`employee_id` ASC, `attendance_date` ASC) USING BTREE,
  INDEX `attendances_employee_id_index`(`employee_id` ASC) USING BTREE,
  INDEX `attendances_daily_fk_index`(`attendance_daily_id` ASC) USING BTREE,
  INDEX `attendances_period_index`(`payroll_period_id` ASC) USING BTREE,
  INDEX `attendances_status_index`(`status` ASC) USING BTREE,
  INDEX `attendances_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `attendances_daily_fk` FOREIGN KEY (`attendance_daily_id`) REFERENCES `attendance_dailies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `attendances_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `attendances_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of attendances
-- ----------------------------

-- ----------------------------
-- Table structure for audit_trails
-- ----------------------------
DROP TABLE IF EXISTS `audit_trails`;
CREATE TABLE `audit_trails`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `table_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` bigint UNSIGNED NOT NULL,
  `action` enum('INSERT','UPDATE','DELETE','RESTORE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` json NULL,
  `new_values` json NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_audit_trails_org`(`organization_id` ASC) USING BTREE,
  INDEX `idx_audit_trails_table_record`(`table_name` ASC, `record_id` ASC) USING BTREE,
  INDEX `idx_audit_trails_created`(`created_at` ASC) USING BTREE,
  CONSTRAINT `audit_trails_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of audit_trails
-- ----------------------------

-- ----------------------------
-- Table structure for bank_accounts
-- ----------------------------
DROP TABLE IF EXISTS `bank_accounts`;
CREATE TABLE `bank_accounts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `current_balance` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `bank_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `branch_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `swift_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bank_accounts_org_number_unique`(`organization_id` ASC, `account_number` ASC) USING BTREE,
  INDEX `bank_accounts_account_fk`(`account_id` ASC) USING BTREE,
  INDEX `bank_accounts_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `bank_accounts_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `bank_accounts_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `bank_accounts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bank_accounts
-- ----------------------------

-- ----------------------------
-- Table structure for bank_transactions
-- ----------------------------
DROP TABLE IF EXISTS `bank_transactions`;
CREATE TABLE `bank_transactions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bank_account_id` bigint UNSIGNED NOT NULL,
  `transaction_date` date NOT NULL,
  `transaction_type` enum('debit','credit','transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `balance_after` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_reconciled` tinyint(1) NOT NULL DEFAULT 0,
  `reconciled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `bank_transactions_account_date_index`(`bank_account_id` ASC, `transaction_date` ASC) USING BTREE,
  INDEX `bank_transactions_journal_fk`(`journal_entry_id` ASC) USING BTREE,
  INDEX `bank_transactions_is_reconciled_index`(`is_reconciled` ASC) USING BTREE,
  INDEX `bank_transactions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `bank_transactions_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `bank_transactions_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bank_transactions
-- ----------------------------

-- ----------------------------
-- Table structure for blood_types
-- ----------------------------
DROP TABLE IF EXISTS `blood_types`;
CREATE TABLE `blood_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rhesus` enum('+','-','') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `blood_types_name_unique`(`name` ASC) USING BTREE,
  INDEX `blood_types_deleted_at_index`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of blood_types
-- ----------------------------

-- ----------------------------
-- Table structure for bonuses
-- ----------------------------
DROP TABLE IF EXISTS `bonuses`;
CREATE TABLE `bonuses`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `bonus_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bonus_type` enum('performance','thr','bonus_year_end','project_bonus','referral','attendance','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `payment_date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payroll_period_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bonuses_org_code_unique`(`organization_id` ASC, `bonus_code` ASC) USING BTREE,
  INDEX `bonuses_employee_id_index`(`employee_id` ASC) USING BTREE,
  INDEX `bonuses_payroll_period_id_index`(`payroll_period_id` ASC) USING BTREE,
  INDEX `bonuses_bonus_type_index`(`bonus_type` ASC) USING BTREE,
  INDEX `bonuses_is_paid_index`(`is_paid` ASC) USING BTREE,
  INDEX `bonuses_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `bonuses_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `bonuses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `bonuses_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bonuses
-- ----------------------------

-- ----------------------------
-- Table structure for budget_details
-- ----------------------------
DROP TABLE IF EXISTS `budget_details`;
CREATE TABLE `budget_details`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `budget_id` bigint UNSIGNED NOT NULL,
  `accounting_period_id` bigint UNSIGNED NULL DEFAULT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `cost_center_id` bigint UNSIGNED NULL DEFAULT NULL,
  `budget_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `actual_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `variance_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `variance_percentage` decimal(7, 2) NOT NULL DEFAULT 0.00,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `budget_details_budget_fk`(`budget_id` ASC) USING BTREE,
  INDEX `budget_details_period_fk`(`accounting_period_id` ASC) USING BTREE,
  INDEX `budget_details_account_fk`(`account_id` ASC) USING BTREE,
  INDEX `budget_details_cost_center_fk`(`cost_center_id` ASC) USING BTREE,
  INDEX `budget_details_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `budget_details_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `budget_details_budget_fk` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `budget_details_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `budget_details_period_fk` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of budget_details
-- ----------------------------

-- ----------------------------
-- Table structure for budgets
-- ----------------------------
DROP TABLE IF EXISTS `budgets`;
CREATE TABLE `budgets`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `fiscal_year_id` bigint UNSIGNED NOT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','approved','locked','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `budgets_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `budgets_year_fk`(`fiscal_year_id` ASC) USING BTREE,
  INDEX `budgets_status_index`(`status` ASC) USING BTREE,
  INDEX `budgets_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `budgets_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `budgets_year_fk` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of budgets
-- ----------------------------

-- ----------------------------
-- Table structure for buildings
-- ----------------------------
DROP TABLE IF EXISTS `buildings`;
CREATE TABLE `buildings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `total_floors` int NOT NULL DEFAULT 0,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `buildings_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `buildings_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `buildings_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `buildings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of buildings
-- ----------------------------

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache
-- ----------------------------
INSERT INTO `cache` VALUES ('laravel-cache-acl-all-actions', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:1;s:9:\"user-view\";i:2;s:11:\"user-manage\";i:3;s:12:\"roles-manage\";i:4;s:12:\"profile-view\";i:5;s:12:\"profile-edit\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1788987442);
INSERT INTO `cache` VALUES ('laravel-cache-acl-all-menus', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:7:{i:1;s:9:\"dashboard\";i:2;s:6:\"master\";i:3;s:4:\"user\";i:4;s:5:\"roles\";i:5;s:7:\"profile\";i:6;s:4:\"menu\";i:7;s:16:\"module-generator\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1788987442);
INSERT INTO `cache` VALUES ('laravel-cache-user_1_role_manager', 'b:0;', 1788989908);
INSERT INTO `cache` VALUES ('laravel-cache-user_1_role_special_role', 'b:0;', 1788989908);

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for class_levels
-- ----------------------------
DROP TABLE IF EXISTS `class_levels`;
CREATE TABLE `class_levels`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `education_level_id` bigint UNSIGNED NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_order` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `class_levels_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `idx_class_levels_education`(`education_level_id` ASC) USING BTREE,
  INDEX `idx_class_levels_order`(`level_order` ASC) USING BTREE,
  INDEX `idx_class_levels_is_active`(`is_active` ASC) USING BTREE,
  INDEX `idx_class_levels_deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `class_levels_education_fk` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `class_levels_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of class_levels
-- ----------------------------

-- ----------------------------
-- Table structure for classrooms
-- ----------------------------
DROP TABLE IF EXISTS `classrooms`;
CREATE TABLE `classrooms`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `class_level_id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NULL DEFAULT 30,
  `homeroom_teacher_id` bigint UNSIGNED NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `classrooms_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `idx_classrooms_level`(`class_level_id` ASC) USING BTREE,
  INDEX `idx_classrooms_academic_year`(`academic_year_id` ASC) USING BTREE,
  INDEX `idx_classrooms_teacher`(`homeroom_teacher_id` ASC) USING BTREE,
  INDEX `idx_classrooms_is_active`(`is_active` ASC) USING BTREE,
  INDEX `idx_classrooms_deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `classrooms_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `classrooms_level_fk` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `classrooms_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of classrooms
-- ----------------------------

-- ----------------------------
-- Table structure for cost_centers
-- ----------------------------
DROP TABLE IF EXISTS `cost_centers`;
CREATE TABLE `cost_centers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `department_id` bigint UNSIGNED NULL DEFAULT NULL,
  `manager_id` bigint UNSIGNED NULL DEFAULT NULL,
  `budget_holder_id` bigint UNSIGNED NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cost_centers_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `cost_centers_parent_fk`(`parent_id` ASC) USING BTREE,
  INDEX `cost_centers_department_fk`(`department_id` ASC) USING BTREE,
  INDEX `cost_centers_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `cost_centers_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `cost_centers_department_fk` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `cost_centers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cost_centers_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cost_centers
-- ----------------------------

-- ----------------------------
-- Table structure for courses
-- ----------------------------
DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `teacher_id` bigint UNSIGNED NULL DEFAULT NULL,
  `subject_id` bigint UNSIGNED NULL DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `thumbnail_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `level` enum('BEGINNER','INTERMEDIATE','ADVANCED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `duration_hours` int NULL DEFAULT 0,
  `is_published` tinyint(1) NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `enrollment_count` int NULL DEFAULT 0,
  `rating` decimal(3, 2) NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `courses_org_slug_unique`(`organization_id` ASC, `slug` ASC) USING BTREE,
  INDEX `idx_courses_teacher`(`teacher_id` ASC) USING BTREE,
  INDEX `idx_courses_subject`(`subject_id` ASC) USING BTREE,
  INDEX `idx_courses_is_published`(`is_published` ASC) USING BTREE,
  INDEX `idx_courses_deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `courses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `courses_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `courses_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of courses
-- ----------------------------

-- ----------------------------
-- Table structure for curriculum_structures
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_structures`;
CREATE TABLE `curriculum_structures`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `class_level_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `semester` enum('SEMESTER_1','SEMESTER_2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `hours_per_week` int NOT NULL,
  `hour_duration` int NULL DEFAULT 45,
  `is_core` tinyint(1) NULL DEFAULT 1,
  `min_student` int NULL DEFAULT 1,
  `max_student` int NULL DEFAULT 40,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `curriculum_structures_unique`(`organization_id` ASC, `class_level_id` ASC, `subject_id` ASC, `academic_year_id` ASC, `semester` ASC) USING BTREE,
  INDEX `idx_curriculum_class`(`class_level_id` ASC) USING BTREE,
  INDEX `idx_curriculum_subject`(`subject_id` ASC) USING BTREE,
  INDEX `idx_curriculum_deleted_at`(`deleted_at` ASC) USING BTREE,
  INDEX `curriculum_structures_academic_year_fk`(`academic_year_id` ASC) USING BTREE,
  CONSTRAINT `curriculum_structures_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `curriculum_structures_class_fk` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `curriculum_structures_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `curriculum_structures_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of curriculum_structures
-- ----------------------------

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tax_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `receivable_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `customers_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `customers_receivable_account_fk`(`receivable_account_id` ASC) USING BTREE,
  INDEX `customers_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `customers_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `customers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `customers_receivable_account_fk` FOREIGN KEY (`receivable_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of customers
-- ----------------------------

-- ----------------------------
-- Table structure for deduction_categories
-- ----------------------------
DROP TABLE IF EXISTS `deduction_categories`;
CREATE TABLE `deduction_categories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_type` enum('mandatory','voluntary','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `deduction_group` enum('insurance','tax','saving','loan','penalty','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `calculation_type` enum('fixed','percentage','sliding','custom') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `default_amount` decimal(15, 2) NULL DEFAULT NULL,
  `default_percentage` decimal(5, 2) NULL DEFAULT NULL,
  `min_amount` decimal(15, 2) NULL DEFAULT NULL,
  `max_amount` decimal(15, 2) NULL DEFAULT NULL,
  `is_based_on_salary` tinyint(1) NOT NULL DEFAULT 0,
  `is_taxable` tinyint(1) NOT NULL DEFAULT 0,
  `tax_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `deduction_categories_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `deduction_categories_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `deduction_categories_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `deduction_categories_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of deduction_categories
-- ----------------------------

-- ----------------------------
-- Table structure for deductions
-- ----------------------------
DROP TABLE IF EXISTS `deductions`;
CREATE TABLE `deductions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `payroll_period_id` bigint UNSIGNED NULL DEFAULT NULL,
  `deduction_category_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT 0,
  `recurring_until` date NULL DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `deductions_employee_id_index`(`employee_id` ASC) USING BTREE,
  INDEX `deductions_payroll_period_id_index`(`payroll_period_id` ASC) USING BTREE,
  INDEX `deductions_deduction_category_id_index`(`deduction_category_id` ASC) USING BTREE,
  INDEX `deductions_organization_id_index`(`organization_id` ASC) USING BTREE,
  INDEX `deductions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `deductions_category_fk` FOREIGN KEY (`deduction_category_id`) REFERENCES `deduction_categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `deductions_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `deductions_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `deductions_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of deductions
-- ----------------------------

-- ----------------------------
-- Table structure for departments
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `manager_id` bigint UNSIGNED NULL DEFAULT NULL,
  `cost_center_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `departments_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `departments_parent_id_index`(`parent_id` ASC) USING BTREE,
  INDEX `departments_manager_id_index`(`manager_id` ASC) USING BTREE,
  INDEX `departments_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `departments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `departments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `departments_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of departments
-- ----------------------------

-- ----------------------------
-- Table structure for dictionaries
-- ----------------------------
DROP TABLE IF EXISTS `dictionaries`;
CREATE TABLE `dictionaries`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `en_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `id_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `metadata` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `dictionaries_org_key_group_unique`(`organization_id` ASC, `key` ASC, `group` ASC) USING BTREE,
  INDEX `dictionaries_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `dictionaries_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `dictionaries_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dictionaries
-- ----------------------------

-- ----------------------------
-- Table structure for education_levels
-- ----------------------------
DROP TABLE IF EXISTS `education_levels`;
CREATE TABLE `education_levels`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `education_levels_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `education_levels_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `education_levels_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `education_levels_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of education_levels
-- ----------------------------

-- ----------------------------
-- Table structure for employee_bonuses
-- ----------------------------
DROP TABLE IF EXISTS `employee_bonuses`;
CREATE TABLE `employee_bonuses`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `bonus_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bonus_date` date NOT NULL,
  `bonus_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `payroll_detail_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` enum('draft','approved','paid','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employee_bonuses_org_number_unique`(`organization_id` ASC, `bonus_number` ASC) USING BTREE,
  INDEX `employee_bonuses_employee_fk`(`employee_id` ASC) USING BTREE,
  INDEX `employee_bonuses_payroll_fk`(`payroll_detail_id` ASC) USING BTREE,
  INDEX `employee_bonuses_organization_id_index`(`organization_id` ASC) USING BTREE,
  INDEX `employee_bonuses_status_index`(`status` ASC) USING BTREE,
  INDEX `employee_bonuses_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `employee_bonuses_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_bonuses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_bonuses_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of employee_bonuses
-- ----------------------------

-- ----------------------------
-- Table structure for employee_contracts
-- ----------------------------
DROP TABLE IF EXISTS `employee_contracts`;
CREATE TABLE `employee_contracts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `contract_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contract_type` enum('tetap','kontrak','probation','freelance','project') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NULL DEFAULT NULL,
  `salary` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `salary_structure_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` enum('draft','active','expired','terminated','extended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `document_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employee_contracts_org_number_unique`(`organization_id` ASC, `contract_number` ASC) USING BTREE,
  INDEX `employee_contracts_employee_id_index`(`employee_id` ASC) USING BTREE,
  INDEX `employee_contracts_salary_structure_id_index`(`salary_structure_id` ASC) USING BTREE,
  INDEX `employee_contracts_organization_id_index`(`organization_id` ASC) USING BTREE,
  INDEX `employee_contracts_status_index`(`status` ASC) USING BTREE,
  INDEX `employee_contracts_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `employee_contracts_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_contracts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_contracts_salary_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of employee_contracts
-- ----------------------------

-- ----------------------------
-- Table structure for employee_deductions
-- ----------------------------
DROP TABLE IF EXISTS `employee_deductions`;
CREATE TABLE `employee_deductions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `deduction_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deduction_date` date NOT NULL,
  `deduction_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `recurring` tinyint(1) NOT NULL DEFAULT 0,
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `payroll_detail_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` enum('draft','approved','active','paid','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employee_deductions_org_number_unique`(`organization_id` ASC, `deduction_number` ASC) USING BTREE,
  INDEX `employee_deductions_employee_fk`(`employee_id` ASC) USING BTREE,
  INDEX `employee_deductions_payroll_fk`(`payroll_detail_id` ASC) USING BTREE,
  INDEX `employee_deductions_organization_id_index`(`organization_id` ASC) USING BTREE,
  INDEX `employee_deductions_status_index`(`status` ASC) USING BTREE,
  INDEX `employee_deductions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `employee_deductions_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_deductions_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_deductions_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of employee_deductions
-- ----------------------------

-- ----------------------------
-- Table structure for employee_leaves
-- ----------------------------
DROP TABLE IF EXISTS `employee_leaves`;
CREATE TABLE `employee_leaves`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `leave_type_id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` decimal(8, 2) NOT NULL DEFAULT 0.00,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `attachment_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` enum('draft','submitted','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint UNSIGNED NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `employee_leaves_employee_fk`(`employee_id` ASC) USING BTREE,
  INDEX `employee_leaves_type_fk`(`leave_type_id` ASC) USING BTREE,
  INDEX `employee_leaves_status_index`(`status` ASC) USING BTREE,
  INDEX `employee_leaves_date_range_index`(`start_date` ASC, `end_date` ASC) USING BTREE,
  INDEX `employee_leaves_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `employee_leaves_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_leaves_type_fk` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of employee_leaves
-- ----------------------------

-- ----------------------------
-- Table structure for employee_mutations
-- ----------------------------
DROP TABLE IF EXISTS `employee_mutations`;
CREATE TABLE `employee_mutations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `mutation_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mutation_type` enum('promotion','demotion','transfer','department_change','position_change','salary_change') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_department_id` bigint UNSIGNED NULL DEFAULT NULL,
  `new_department_id` bigint UNSIGNED NULL DEFAULT NULL,
  `old_position_id` bigint UNSIGNED NULL DEFAULT NULL,
  `new_position_id` bigint UNSIGNED NULL DEFAULT NULL,
  `old_salary` decimal(15, 2) NULL DEFAULT NULL,
  `new_salary` decimal(15, 2) NULL DEFAULT NULL,
  `effective_date` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('draft','submitted','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint UNSIGNED NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employee_mutations_mutation_code_unique`(`mutation_code` ASC) USING BTREE,
  INDEX `employee_mutations_employee_id_index`(`employee_id` ASC) USING BTREE,
  INDEX `employee_mutations_organization_id_index`(`organization_id` ASC) USING BTREE,
  INDEX `employee_mutations_status_index`(`status` ASC) USING BTREE,
  INDEX `employee_mutations_effective_date_index`(`effective_date` ASC) USING BTREE,
  INDEX `employee_mutations_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `employee_mutations_old_dept_fk`(`old_department_id` ASC) USING BTREE,
  INDEX `employee_mutations_new_dept_fk`(`new_department_id` ASC) USING BTREE,
  INDEX `employee_mutations_old_pos_fk`(`old_position_id` ASC) USING BTREE,
  INDEX `employee_mutations_new_pos_fk`(`new_position_id` ASC) USING BTREE,
  CONSTRAINT `employee_mutations_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_mutations_new_dept_fk` FOREIGN KEY (`new_department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_mutations_new_pos_fk` FOREIGN KEY (`new_position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_mutations_old_dept_fk` FOREIGN KEY (`old_department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_mutations_old_pos_fk` FOREIGN KEY (`old_position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_mutations_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of employee_mutations
-- ----------------------------

-- ----------------------------
-- Table structure for employee_salary_components
-- ----------------------------
DROP TABLE IF EXISTS `employee_salary_components`;
CREATE TABLE `employee_salary_components`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `salary_component_id` bigint UNSIGNED NOT NULL,
  `value` decimal(15, 4) NOT NULL DEFAULT 0.0000,
  `effective_from` date NOT NULL,
  `effective_to` date NULL DEFAULT NULL,
  `source` enum('structure','override','manual') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'override',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `esc_employee_component_index`(`employee_id` ASC, `salary_component_id` ASC) USING BTREE,
  INDEX `esc_component_fk`(`salary_component_id` ASC) USING BTREE,
  INDEX `esc_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `esc_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `esc_component_fk` FOREIGN KEY (`salary_component_id`) REFERENCES `salary_components` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `esc_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of employee_salary_components
-- ----------------------------

-- ----------------------------
-- Table structure for employee_shifts
-- ----------------------------
DROP TABLE IF EXISTS `employee_shifts`;
CREATE TABLE `employee_shifts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `shift_id` bigint UNSIGNED NOT NULL,
  `effective_date` date NOT NULL,
  `end_date` date NULL DEFAULT NULL,
  `day_of_week` tinyint NULL DEFAULT NULL,
  `is_rotational` tinyint(1) NULL DEFAULT 0,
  `rotation_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employee_shift_effective_unique`(`employee_id` ASC, `effective_date` ASC, `shift_id` ASC) USING BTREE,
  INDEX `employee_shifts_employee_id_index`(`employee_id` ASC) USING BTREE,
  INDEX `employee_shifts_shift_id_index`(`shift_id` ASC) USING BTREE,
  INDEX `employee_shifts_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `employee_shifts_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_shifts_shift_fk` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of employee_shifts
-- ----------------------------

-- ----------------------------
-- Table structure for employees
-- ----------------------------
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `nik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `department_id` bigint UNSIGNED NULL DEFAULT NULL,
  `position_id` bigint UNSIGNED NULL DEFAULT NULL,
  `supervisor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `salary_structure_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nick_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gender` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birth_place` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birth_date` date NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `join_date` date NOT NULL,
  `resign_date` date NULL DEFAULT NULL,
  `employment_status` enum('tetap','kontrak','probation','freelance') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'probation',
  `bank_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bank_account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bank_account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `npwp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bpjs_ketenagakerjaan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bpjs_kesehatan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employees_org_number_unique`(`organization_id` ASC, `employee_number` ASC) USING BTREE,
  UNIQUE INDEX `employees_org_nik_unique`(`organization_id` ASC, `nik` ASC) USING BTREE,
  UNIQUE INDEX `employees_email_unique`(`email` ASC) USING BTREE,
  INDEX `employees_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `employees_department_id_index`(`department_id` ASC) USING BTREE,
  INDEX `employees_position_id_index`(`position_id` ASC) USING BTREE,
  INDEX `employees_supervisor_id_index`(`supervisor_id` ASC) USING BTREE,
  INDEX `employees_salary_structure_id_index`(`salary_structure_id` ASC) USING BTREE,
  INDEX `employees_join_date_index`(`join_date` ASC) USING BTREE,
  INDEX `employees_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `employees_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `employees_department_fk` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employees_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employees_position_fk` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employees_salary_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employees_supervisor_fk` FOREIGN KEY (`supervisor_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of employees
-- ----------------------------

-- ----------------------------
-- Table structure for expense_categories
-- ----------------------------
DROP TABLE IF EXISTS `expense_categories`;
CREATE TABLE `expense_categories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `expense_categories_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  UNIQUE INDEX `expense_categories_org_name_unique`(`organization_id` ASC, `name` ASC) USING BTREE,
  INDEX `expense_categories_parent_id_index`(`parent_id` ASC) USING BTREE,
  INDEX `expense_categories_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `expense_categories_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `expense_categories_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `expense_categories_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of expense_categories
-- ----------------------------

-- ----------------------------
-- Table structure for expense_items
-- ----------------------------
DROP TABLE IF EXISTS `expense_items`;
CREATE TABLE `expense_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `expense_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(15, 4) NOT NULL DEFAULT 1.0000,
  `unit_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `account_id` bigint UNSIGNED NOT NULL,
  `cost_center_id` bigint UNSIGNED NULL DEFAULT NULL,
  `tax_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `expense_items_expense_fk`(`expense_id` ASC) USING BTREE,
  INDEX `expense_items_account_fk`(`account_id` ASC) USING BTREE,
  INDEX `expense_items_cost_center_fk`(`cost_center_id` ASC) USING BTREE,
  INDEX `expense_items_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `expense_items_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `expense_items_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `expense_items_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of expense_items
-- ----------------------------

-- ----------------------------
-- Table structure for expense_payments
-- ----------------------------
DROP TABLE IF EXISTS `expense_payments`;
CREATE TABLE `expense_payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `expense_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `payment_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','bank_transfer','check','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `expense_payments_org_number_unique`(`organization_id` ASC, `payment_number` ASC) USING BTREE,
  INDEX `expense_payments_expense_fk`(`expense_id` ASC) USING BTREE,
  INDEX `expense_payments_bank_fk`(`bank_account_id` ASC) USING BTREE,
  INDEX `expense_payments_journal_fk`(`journal_entry_id` ASC) USING BTREE,
  INDEX `expense_payments_organization_id_index`(`organization_id` ASC) USING BTREE,
  INDEX `expense_payments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `expense_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `expense_payments_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `expense_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `expense_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of expense_payments
-- ----------------------------

-- ----------------------------
-- Table structure for expenses
-- ----------------------------
DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `expense_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_date` date NOT NULL,
  `vendor_name_snapshot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `tax` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('unpaid','partial','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `attachment_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `expenses_org_number_unique`(`organization_id` ASC, `expense_number` ASC) USING BTREE,
  INDEX `expenses_vendor_fk`(`vendor_id` ASC) USING BTREE,
  INDEX `expenses_journal_fk`(`journal_entry_id` ASC) USING BTREE,
  INDEX `expenses_payment_status_index`(`payment_status` ASC) USING BTREE,
  INDEX `expenses_expense_date_index`(`expense_date` ASC) USING BTREE,
  INDEX `expenses_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `expenses_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `expenses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `expenses_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of expenses
-- ----------------------------

-- ----------------------------
-- Table structure for facilities
-- ----------------------------
DROP TABLE IF EXISTS `facilities`;
CREATE TABLE `facilities`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `facility_category_id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED NOT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `quantity` int NOT NULL DEFAULT 1,
  `is_available` tinyint(1) NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `facilities_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `facilities_facility_category_id_foreign`(`facility_category_id` ASC) USING BTREE,
  INDEX `facilities_room_id_foreign`(`room_id` ASC) USING BTREE,
  INDEX `facilities_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `facilities_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `facilities_category_fk` FOREIGN KEY (`facility_category_id`) REFERENCES `facility_categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `facilities_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `facilities_room_fk` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of facilities
-- ----------------------------

-- ----------------------------
-- Table structure for facility_categories
-- ----------------------------
DROP TABLE IF EXISTS `facility_categories`;
CREATE TABLE `facility_categories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `facility_categories_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `facility_categories_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `facility_categories_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `facility_categories_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of facility_categories
-- ----------------------------

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for finance_audit_logs
-- ----------------------------
DROP TABLE IF EXISTS `finance_audit_logs`;
CREATE TABLE `finance_audit_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` bigint UNSIGNED NULL DEFAULT NULL,
  `old_data` json NULL,
  `new_data` json NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `finance_audit_logs_org_index`(`organization_id` ASC) USING BTREE,
  INDEX `finance_audit_logs_table_record_index`(`table_name` ASC, `record_id` ASC) USING BTREE,
  INDEX `finance_audit_logs_created_index`(`created_at` ASC) USING BTREE,
  CONSTRAINT `finance_audit_logs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of finance_audit_logs
-- ----------------------------

-- ----------------------------
-- Table structure for fiscal_years
-- ----------------------------
DROP TABLE IF EXISTS `fiscal_years`;
CREATE TABLE `fiscal_years`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `year` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('open','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `fiscal_years_org_year_unique`(`organization_id` ASC, `year` ASC) USING BTREE,
  INDEX `fiscal_years_status_index`(`status` ASC) USING BTREE,
  INDEX `fiscal_years_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `fiscal_years_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of fiscal_years
-- ----------------------------

-- ----------------------------
-- Table structure for fixed_assets
-- ----------------------------
DROP TABLE IF EXISTS `fixed_assets`;
CREATE TABLE `fixed_assets`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `asset_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `acquisition_date` date NOT NULL,
  `acquisition_cost` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `residual_value` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `useful_life_months` int NOT NULL DEFAULT 0,
  `depreciation_method` enum('straight_line','declining_balance') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'straight_line',
  `accumulated_depreciation` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `book_value` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `asset_account_id` bigint UNSIGNED NOT NULL,
  `accumulated_depreciation_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `depreciation_expense_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` enum('draft','active','disposed','fully_depreciated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `disposed_at` date NULL DEFAULT NULL,
  `disposal_value` decimal(15, 2) NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `fixed_assets_org_code_unique`(`organization_id` ASC, `asset_code` ASC) USING BTREE,
  INDEX `fixed_assets_asset_account_fk`(`asset_account_id` ASC) USING BTREE,
  INDEX `fixed_assets_accum_account_fk`(`accumulated_depreciation_account_id` ASC) USING BTREE,
  INDEX `fixed_assets_depr_expense_account_fk`(`depreciation_expense_account_id` ASC) USING BTREE,
  INDEX `fixed_assets_status_index`(`status` ASC) USING BTREE,
  INDEX `fixed_assets_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `fixed_assets_accum_account_fk` FOREIGN KEY (`accumulated_depreciation_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fixed_assets_asset_account_fk` FOREIGN KEY (`asset_account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fixed_assets_depr_expense_account_fk` FOREIGN KEY (`depreciation_expense_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fixed_assets_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of fixed_assets
-- ----------------------------

-- ----------------------------
-- Table structure for floors
-- ----------------------------
DROP TABLE IF EXISTS `floors`;
CREATE TABLE `floors`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `building_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `floor_number` int NOT NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `floors_building_code_unique`(`building_id` ASC, `code` ASC) USING BTREE,
  INDEX `floors_building_id_foreign`(`building_id` ASC) USING BTREE,
  INDEX `floors_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `floors_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `floors_building_fk` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of floors
-- ----------------------------

-- ----------------------------
-- Table structure for genders
-- ----------------------------
DROP TABLE IF EXISTS `genders`;
CREATE TABLE `genders`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `genders_code_unique`(`code` ASC) USING BTREE,
  INDEX `genders_deleted_at_index`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of genders
-- ----------------------------

-- ----------------------------
-- Table structure for holidays
-- ----------------------------
DROP TABLE IF EXISTS `holidays`;
CREATE TABLE `holidays`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('national','special','company','religious') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'national',
  `is_recurring` tinyint(1) NULL DEFAULT 0,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `holidays_org_date_type_unique`(`organization_id` ASC, `date` ASC, `type` ASC) USING BTREE,
  INDEX `holidays_date_index`(`date` ASC) USING BTREE,
  INDEX `holidays_type_index`(`type` ASC) USING BTREE,
  INDEX `holidays_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `holidays_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of holidays
-- ----------------------------

-- ----------------------------
-- Table structure for identity_types
-- ----------------------------
DROP TABLE IF EXISTS `identity_types`;
CREATE TABLE `identity_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_length` tinyint UNSIGNED NULL DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `identity_types_code_unique`(`code` ASC) USING BTREE,
  INDEX `identity_types_deleted_at_index`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of identity_types
-- ----------------------------

-- ----------------------------
-- Table structure for invoice_items
-- ----------------------------
DROP TABLE IF EXISTS `invoice_items`;
CREATE TABLE `invoice_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `product_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(15, 4) NOT NULL DEFAULT 1.0000,
  `unit_price` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `revenue_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `invoice_items_invoice_fk`(`invoice_id` ASC) USING BTREE,
  INDEX `invoice_items_revenue_account_fk`(`revenue_account_id` ASC) USING BTREE,
  INDEX `invoice_items_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `invoice_items_invoice_fk` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `invoice_items_revenue_account_fk` FOREIGN KEY (`revenue_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of invoice_items
-- ----------------------------

-- ----------------------------
-- Table structure for invoice_payments
-- ----------------------------
DROP TABLE IF EXISTS `invoice_payments`;
CREATE TABLE `invoice_payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `payment_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','bank_transfer','check','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `invoice_payments_org_number_unique`(`organization_id` ASC, `payment_number` ASC) USING BTREE,
  INDEX `invoice_payments_invoice_fk`(`invoice_id` ASC) USING BTREE,
  INDEX `invoice_payments_bank_fk`(`bank_account_id` ASC) USING BTREE,
  INDEX `invoice_payments_journal_fk`(`journal_entry_id` ASC) USING BTREE,
  INDEX `invoice_payments_organization_id_index`(`organization_id` ASC) USING BTREE,
  INDEX `invoice_payments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `invoice_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `invoice_payments_invoice_fk` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `invoice_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `invoice_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of invoice_payments
-- ----------------------------

-- ----------------------------
-- Table structure for invoices
-- ----------------------------
DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NULL DEFAULT NULL,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name_snapshot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone_snapshot` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `customer_email_snapshot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `tax` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','sent','partial','paid','overdue','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `invoices_org_number_unique`(`organization_id` ASC, `invoice_number` ASC) USING BTREE,
  INDEX `invoices_customer_fk`(`customer_id` ASC) USING BTREE,
  INDEX `invoices_journal_fk`(`journal_entry_id` ASC) USING BTREE,
  INDEX `invoices_status_index`(`status` ASC) USING BTREE,
  INDEX `invoices_invoice_date_index`(`invoice_date` ASC) USING BTREE,
  INDEX `invoices_due_date_index`(`due_date` ASC) USING BTREE,
  INDEX `invoices_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `invoices_customer_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `invoices_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `invoices_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of invoices
-- ----------------------------

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for journal_details
-- ----------------------------
DROP TABLE IF EXISTS `journal_details`;
CREATE TABLE `journal_details`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `journal_entry_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `cost_center_id` bigint UNSIGNED NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `debit` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `credit` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `reference_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reference_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `journal_details_entry_index`(`journal_entry_id` ASC) USING BTREE,
  INDEX `journal_details_account_index`(`account_id` ASC) USING BTREE,
  INDEX `journal_details_cost_center_index`(`cost_center_id` ASC) USING BTREE,
  CONSTRAINT `journal_details_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `journal_details_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `journal_details_entry_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `journal_details_debit_credit_chk` CHECK ((`debit` >= 0) and (`credit` >= 0) and ((`debit` <= 0) or (`credit` <= 0)))
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of journal_details
-- ----------------------------

-- ----------------------------
-- Table structure for journal_entries
-- ----------------------------
DROP TABLE IF EXISTS `journal_entries`;
CREATE TABLE `journal_entries`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `accounting_period_id` bigint UNSIGNED NOT NULL,
  `journal_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `journal_date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_type` enum('manual','payroll','sales','purchase','expense','transfer','tax','opening','adjustment','closing') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `source_id` bigint UNSIGNED NULL DEFAULT NULL,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `total_debit` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_credit` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','approved','posted','void') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `reversal_of_id` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `posted_by` bigint UNSIGNED NULL DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `journal_entries_number_unique`(`organization_id` ASC, `journal_number` ASC) USING BTREE,
  INDEX `journal_entries_date_index`(`organization_id` ASC, `journal_date` ASC) USING BTREE,
  INDEX `journal_entries_source_index`(`source_type` ASC, `source_id` ASC) USING BTREE,
  INDEX `journal_entries_period_index`(`accounting_period_id` ASC) USING BTREE,
  INDEX `journal_entries_reversal_fk`(`reversal_of_id` ASC) USING BTREE,
  INDEX `journal_entries_status_index`(`status` ASC) USING BTREE,
  INDEX `journal_entries_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `journal_entries_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `journal_entries_period_fk` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `journal_entries_reversal_fk` FOREIGN KEY (`reversal_of_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of journal_entries
-- ----------------------------

-- ----------------------------
-- Table structure for leave_balances
-- ----------------------------
DROP TABLE IF EXISTS `leave_balances`;
CREATE TABLE `leave_balances`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `leave_type_id` bigint UNSIGNED NOT NULL,
  `year` int NOT NULL,
  `allocated_days` decimal(8, 2) NOT NULL DEFAULT 0.00,
  `used_days` decimal(8, 2) NOT NULL DEFAULT 0.00,
  `remaining_days` decimal(8, 2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `leave_balances_unique`(`employee_id` ASC, `leave_type_id` ASC, `year` ASC) USING BTREE,
  INDEX `leave_balances_type_fk`(`leave_type_id` ASC) USING BTREE,
  INDEX `leave_balances_year_index`(`year` ASC) USING BTREE,
  INDEX `leave_balances_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `leave_balances_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `leave_balances_type_fk` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of leave_balances
-- ----------------------------

-- ----------------------------
-- Table structure for leave_types
-- ----------------------------
DROP TABLE IF EXISTS `leave_types`;
CREATE TABLE `leave_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_days` decimal(8, 2) NOT NULL DEFAULT 0.00,
  `max_consecutive_days` int NULL DEFAULT NULL,
  `min_working_days_before` int NULL DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 1,
  `requires_attachment` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `leave_types_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `leave_types_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `leave_types_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `leave_types_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of leave_types
-- ----------------------------

-- ----------------------------
-- Table structure for lessons
-- ----------------------------
DROP TABLE IF EXISTS `lessons`;
CREATE TABLE `lessons`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` bigint UNSIGNED NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `video_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `duration_minutes` int NULL DEFAULT 0,
  `order_index` int NOT NULL,
  `is_free_preview` tinyint(1) NULL DEFAULT 0,
  `is_published` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_lessons_course`(`course_id` ASC) USING BTREE,
  INDEX `idx_lessons_order`(`order_index` ASC) USING BTREE,
  INDEX `idx_lessons_deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `lessons_course_fk` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of lessons
-- ----------------------------

-- ----------------------------
-- Table structure for loan_installments
-- ----------------------------
DROP TABLE IF EXISTS `loan_installments`;
CREATE TABLE `loan_installments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `loan_id` bigint UNSIGNED NOT NULL,
  `installment_number` int NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','paid','partial','cancelled','overdue') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payroll_detail_id` bigint UNSIGNED NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `loan_installments_number_unique`(`loan_id` ASC, `installment_number` ASC) USING BTREE,
  INDEX `loan_installments_payroll_fk`(`payroll_detail_id` ASC) USING BTREE,
  INDEX `loan_installments_status_index`(`status` ASC) USING BTREE,
  INDEX `loan_installments_due_date_index`(`due_date` ASC) USING BTREE,
  INDEX `loan_installments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `loan_installments_loan_fk` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `loan_installments_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of loan_installments
-- ----------------------------

-- ----------------------------
-- Table structure for loans
-- ----------------------------
DROP TABLE IF EXISTS `loans`;
CREATE TABLE `loans`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `loan_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `loan_date` date NOT NULL,
  `principal_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `installment_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_installments` int NOT NULL DEFAULT 0,
  `remaining_installments` int NOT NULL DEFAULT 0,
  `status` enum('draft','active','completed','cancelled','defaulted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `loans_org_number_unique`(`organization_id` ASC, `loan_number` ASC) USING BTREE,
  INDEX `loans_employee_fk`(`employee_id` ASC) USING BTREE,
  INDEX `loans_status_index`(`status` ASC) USING BTREE,
  INDEX `loans_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `loans_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `loans_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of loans
-- ----------------------------

-- ----------------------------
-- Table structure for marital_statuses
-- ----------------------------
DROP TABLE IF EXISTS `marital_statuses`;
CREATE TABLE `marital_statuses`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `marital_statuses_name_unique`(`name` ASC) USING BTREE,
  INDEX `marital_statuses_deleted_at_index`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of marital_statuses
-- ----------------------------

-- ----------------------------
-- Table structure for menu_role
-- ----------------------------
DROP TABLE IF EXISTS `menu_role`;
CREATE TABLE `menu_role`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `is_view` tinyint(1) NOT NULL DEFAULT 0,
  `is_create` tinyint(1) NOT NULL DEFAULT 0,
  `is_edit` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `menu_role_menu_id_role_id_unique`(`menu_id` ASC, `role_id` ASC) USING BTREE,
  INDEX `menu_role_menu_id_foreign`(`menu_id` ASC) USING BTREE,
  INDEX `menu_role_role_id_foreign`(`role_id` ASC) USING BTREE,
  INDEX `menu_role_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `menu_role_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `menu_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu_role
-- ----------------------------
INSERT INTO `menu_role` VALUES (1, 1, 1, 0, 0, 0, 0, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (2, 1, 2, 0, 0, 0, 0, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (3, 2, 1, 0, 0, 0, 0, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (4, 3, 1, 0, 0, 0, 0, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (5, 3, 2, 0, 0, 0, 0, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (6, 4, 1, 0, 0, 0, 0, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (7, 5, 1, 0, 0, 0, 0, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (8, 5, 2, 0, 0, 0, 0, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (9, 5, 3, 0, 0, 0, 0, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (10, 6, 1, 1, 1, 1, 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (11, 7, 1, 1, 1, 1, 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for menus
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `flyout_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `icon_type` enum('material','fa') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `route` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `type` enum('topbar','sidebar','module') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `sequence` int NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `en_text` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_text` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `menus_slug_unique`(`slug` ASC) USING BTREE,
  INDEX `menus_parent_id_foreign`(`parent_id` ASC) USING BTREE,
  INDEX `menus_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `menus_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menus
-- ----------------------------
INSERT INTO `menus` VALUES (1, 1, NULL, 'dashboard', 'Dasbor', 'Dasbor', 'dashboard', 'fas fa-tachometer-alt', 'fa', 'dashboard', 'dashboard', 'sidebar', 1, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (2, 1, NULL, NULL, 'Data Utama', 'Data Utama', 'master', 'fas fa-database', 'fa', NULL, NULL, 'sidebar', 2, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (3, 1, 2, 'user', 'Pengguna', 'Pengguna', 'user', 'fas fa-users', 'fa', 'user.index', 'user', 'sidebar', 1, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (4, 1, 2, 'role', 'Peran & Izin', 'Peran & Izin', 'roles', 'fas fa-user-shield', 'fa', 'role.index', 'role', 'sidebar', 2, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (5, 1, NULL, 'profile', 'Profil', 'Profil', 'profile', 'fas fa-user-circle', 'fa', 'profile.edit', 'profile', 'topbar', 1, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (6, 1, 2, 'menu management', 'Manage Menu', 'Manage Menu', 'menu', 'fas fa-user-circle', 'fa', 'menu_management.index', 'menu_management', 'sidebar', 1, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (7, 1, 2, 'module-generator', 'Module Generator', 'Module Generator', 'module-generator', 'fas fa-user-circle', 'fa', 'crud_generator.index', 'crud-generator', 'sidebar', 1, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------

-- ----------------------------
-- Table structure for modules
-- ----------------------------
DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `price` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `modules_code_unique`(`code` ASC) USING BTREE,
  INDEX `modules_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `modules_deleted_at_index`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of modules
-- ----------------------------

-- ----------------------------
-- Table structure for navigation
-- ----------------------------
DROP TABLE IF EXISTS `navigation`;
CREATE TABLE `navigation`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `intro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `parent_id` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `navigation_parent_id_index`(`parent_id` ASC) USING BTREE,
  INDEX `navigation_deleted_at_index`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of navigation
-- ----------------------------

-- ----------------------------
-- Table structure for opening_balances
-- ----------------------------
DROP TABLE IF EXISTS `opening_balances`;
CREATE TABLE `opening_balances`  (
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
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `opening_balances_unique`(`organization_id` ASC, `fiscal_year_id` ASC, `account_id` ASC, `cost_center_id` ASC) USING BTREE,
  INDEX `opening_balances_year_fk`(`fiscal_year_id` ASC) USING BTREE,
  INDEX `opening_balances_account_fk`(`account_id` ASC) USING BTREE,
  INDEX `opening_balances_cost_center_fk`(`cost_center_id` ASC) USING BTREE,
  INDEX `opening_balances_journal_fk`(`journal_entry_id` ASC) USING BTREE,
  INDEX `opening_balances_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `opening_balances_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `opening_balances_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `opening_balances_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `opening_balances_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `opening_balances_year_fk` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of opening_balances
-- ----------------------------

-- ----------------------------
-- Table structure for organization_types
-- ----------------------------
DROP TABLE IF EXISTS `organization_types`;
CREATE TABLE `organization_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `organization_types_code_unique`(`code` ASC) USING BTREE,
  INDEX `organization_types_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `organization_types_deleted_at_index`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of organization_types
-- ----------------------------

-- ----------------------------
-- Table structure for organization_user
-- ----------------------------
DROP TABLE IF EXISTS `organization_user`;
CREATE TABLE `organization_user`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `organization_users_unique`(`organization_id` ASC, `user_id` ASC) USING BTREE,
  INDEX `organization_users_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `organization_users_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `organization_users_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `organization_user_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of organization_user
-- ----------------------------
INSERT INTO `organization_user` VALUES (1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `organization_user` VALUES (2, 1, 2, 1, 1, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for organization_users
-- ----------------------------
DROP TABLE IF EXISTS `organization_users`;
CREATE TABLE `organization_users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `organization_users_unique`(`organization_id` ASC, `user_id` ASC) USING BTREE,
  INDEX `organization_users_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `organization_users_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `organization_users_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `organization_users_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of organization_users
-- ----------------------------

-- ----------------------------
-- Table structure for organizations
-- ----------------------------
DROP TABLE IF EXISTS `organizations`;
CREATE TABLE `organizations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `legal_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `organization_type` enum('foundation','company','school','unit','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'foundation',
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `currency_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `timezone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Jakarta',
  `schema_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `config` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `organizations_code_unique`(`code` ASC) USING BTREE,
  INDEX `organizations_parent_id_index`(`parent_id` ASC) USING BTREE,
  INDEX `organizations_organization_type_index`(`organization_type` ASC) USING BTREE,
  INDEX `organizations_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `organizations_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `organizations_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of organizations
-- ----------------------------
INSERT INTO `organizations` VALUES (1, 'PT-UTAMA', 'Perusahaan Utama', 'PT. Perusahaan Utama (Persero)', 'company', NULL, 'IDR', 'Asia/Jakarta', NULL, NULL, 1, '2026-09-09 17:00:30', '2026-09-09 17:00:30', NULL, NULL, NULL);
INSERT INTO `organizations` VALUES (2, 'CAB-JKT', 'Kantor Cabang Jakarta', 'PT. Perusahaan Utama - Cabang Jakarta', 'unit', 1, 'IDR', 'Asia/Jakarta', NULL, NULL, 1, '2026-09-09 17:00:31', '2026-09-09 17:00:31', NULL, NULL, NULL);
INSERT INTO `organizations` VALUES (3, 'CAB-SBY', 'Kantor Cabang Surabaya', 'PT. Perusahaan Utama - Cabang Surabaya', 'unit', 1, 'IDR', 'Asia/Jakarta', NULL, NULL, 1, '2026-09-09 17:00:31', '2026-09-09 17:00:31', NULL, NULL, NULL);
INSERT INTO `organizations` VALUES (4, 'CAB-MDN', 'Kantor Cabang Medan', 'PT. Perusahaan Utama - Cabang Medan', 'unit', 1, 'IDR', 'Asia/Jakarta', NULL, NULL, 1, '2026-09-09 17:00:31', '2026-09-09 17:00:31', NULL, NULL, NULL);
INSERT INTO `organizations` VALUES (5, 'DIV-PUSAT', 'Divisi Pusat', NULL, 'unit', 1, 'IDR', 'Asia/Jakarta', NULL, NULL, 1, '2026-09-09 17:00:31', '2026-09-09 17:00:31', NULL, NULL, NULL);
INSERT INTO `organizations` VALUES (6, 'DIV-OPERASIONAL', 'Divisi Operasional', NULL, 'unit', 1, 'IDR', 'Asia/Jakarta', NULL, NULL, 1, '2026-09-09 17:00:31', '2026-09-09 17:00:31', NULL, NULL, NULL);
INSERT INTO `organizations` VALUES (7, 'DIV-EXPORT', 'Divisi Ekspor / Internasional', 'PT. Perusahaan Utama - Divisi Ekspor', 'unit', 1, 'USD', 'Asia/Jakarta', NULL, NULL, 1, '2026-09-09 17:00:31', '2026-09-09 17:00:31', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for overtimes
-- ----------------------------
DROP TABLE IF EXISTS `overtimes`;
CREATE TABLE `overtimes`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `attendance_daily_id` bigint UNSIGNED NULL DEFAULT NULL,
  `overtime_date` date NOT NULL,
  `start_at` datetime NULL DEFAULT NULL,
  `end_at` datetime NULL DEFAULT NULL,
  `hours` decimal(8, 2) NOT NULL DEFAULT 0.00,
  `hourly_rate` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `rate_multiplier` decimal(8, 4) NOT NULL DEFAULT 1.0000,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','submitted','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint UNSIGNED NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `overtimes_org_idx`(`organization_id` ASC) USING BTREE,
  INDEX `overtimes_employee_idx`(`employee_id` ASC) USING BTREE,
  INDEX `overtimes_daily_idx`(`attendance_daily_id` ASC) USING BTREE,
  INDEX `overtimes_date_idx`(`overtime_date` ASC) USING BTREE,
  INDEX `overtimes_status_idx`(`status` ASC) USING BTREE,
  INDEX `overtimes_employee_date_idx`(`employee_id` ASC, `overtime_date` ASC) USING BTREE,
  INDEX `overtimes_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `overtimes_daily_fk` FOREIGN KEY (`attendance_daily_id`) REFERENCES `attendance_dailies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `overtimes_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `overtimes_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of overtimes
-- ----------------------------

-- ----------------------------
-- Table structure for packages
-- ----------------------------
DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL DEFAULT 0,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `features` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `packages_code_unique`(`code` ASC) USING BTREE,
  INDEX `packages_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `packages_deleted_at_index`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of packages
-- ----------------------------

-- ----------------------------
-- Table structure for parents
-- ----------------------------
DROP TABLE IF EXISTS `parents`;
CREATE TABLE `parents`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `full_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `relationship` enum('FATHER','MOTHER','GUARDIAN') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `work` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_primary` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_parents_organization_id`(`organization_id` ASC) USING BTREE,
  INDEX `idx_parents_user_id`(`user_id` ASC) USING BTREE,
  INDEX `idx_parents_phone`(`phone` ASC) USING BTREE,
  INDEX `idx_parents_deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `parents_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of parents
-- ----------------------------

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for payroll_audit_logs
-- ----------------------------
DROP TABLE IF EXISTS `payroll_audit_logs`;
CREATE TABLE `payroll_audit_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` bigint UNSIGNED NULL DEFAULT NULL,
  `old_data` json NULL,
  `new_data` json NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `payroll_audit_logs_org_fk`(`organization_id` ASC) USING BTREE,
  INDEX `payroll_audit_logs_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `payroll_audit_logs_table_name_index`(`table_name` ASC) USING BTREE,
  INDEX `payroll_audit_logs_created_at_index`(`created_at` ASC) USING BTREE,
  CONSTRAINT `payroll_audit_logs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payroll_audit_logs
-- ----------------------------

-- ----------------------------
-- Table structure for payroll_detail_components
-- ----------------------------
DROP TABLE IF EXISTS `payroll_detail_components`;
CREATE TABLE `payroll_detail_components`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payroll_detail_id` bigint UNSIGNED NOT NULL,
  `salary_component_id` bigint UNSIGNED NULL DEFAULT NULL,
  `component_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_type` enum('earning','deduction','company_contribution','tax') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(15, 4) NOT NULL DEFAULT 1.0000,
  `rate` decimal(15, 4) NOT NULL DEFAULT 0.0000,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `calculation_source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `metadata` json NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pdc_detail_index`(`payroll_detail_id` ASC) USING BTREE,
  INDEX `pdc_component_index`(`salary_component_id` ASC) USING BTREE,
  INDEX `pdc_component_type_index`(`component_type` ASC) USING BTREE,
  INDEX `pdc_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `pdc_component_fk` FOREIGN KEY (`salary_component_id`) REFERENCES `salary_components` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pdc_detail_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payroll_detail_components
-- ----------------------------

-- ----------------------------
-- Table structure for payroll_details
-- ----------------------------
DROP TABLE IF EXISTS `payroll_details`;
CREATE TABLE `payroll_details`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payroll_run_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `employee_number_snapshot` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_name_snapshot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_name_snapshot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `position_name_snapshot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gross_salary` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_deduction` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_company_contribution` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_tax` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `net_salary` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `component_details` json NULL,
  `calculation_metadata` json NULL,
  `status` enum('calculated','approved','paid','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'calculated',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payroll_details_run_employee_unique`(`payroll_run_id` ASC, `employee_id` ASC) USING BTREE,
  INDEX `payroll_details_employee_index`(`employee_id` ASC) USING BTREE,
  INDEX `payroll_details_status_index`(`status` ASC) USING BTREE,
  INDEX `payroll_details_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `payroll_details_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payroll_details_run_fk` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payroll_details
-- ----------------------------

-- ----------------------------
-- Table structure for payroll_integrations
-- ----------------------------
DROP TABLE IF EXISTS `payroll_integrations`;
CREATE TABLE `payroll_integrations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `payroll_run_id` bigint UNSIGNED NOT NULL,
  `payroll_period_id` bigint UNSIGNED NOT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `integration_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_gross_salary` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_net_salary` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_tax` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_bpjs_company` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_bpjs_employee` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_loans` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_other_deductions` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `mapping_config` json NULL,
  `status` enum('pending','mapped','synced','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `synced_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payroll_integrations_run_unique`(`payroll_run_id` ASC) USING BTREE,
  UNIQUE INDEX `payroll_integrations_org_code_unique`(`organization_id` ASC, `integration_code` ASC) USING BTREE,
  INDEX `payroll_integrations_period_fk`(`payroll_period_id` ASC) USING BTREE,
  INDEX `payroll_integrations_journal_fk`(`journal_entry_id` ASC) USING BTREE,
  INDEX `payroll_integrations_status_index`(`status` ASC) USING BTREE,
  INDEX `payroll_integrations_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `payroll_integrations_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `payroll_integrations_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `payroll_integrations_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payroll_integrations_run_fk` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payroll_integrations
-- ----------------------------

-- ----------------------------
-- Table structure for payroll_payment_batches
-- ----------------------------
DROP TABLE IF EXISTS `payroll_payment_batches`;
CREATE TABLE `payroll_payment_batches`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `payroll_run_id` bigint UNSIGNED NOT NULL,
  `bank_account_id` bigint UNSIGNED NOT NULL,
  `batch_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `total_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `employee_count` int NOT NULL DEFAULT 0,
  `status` enum('draft','approved','processing','completed','partial','failed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `failed_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payroll_payment_batches_org_number_unique`(`organization_id` ASC, `batch_number` ASC) USING BTREE,
  INDEX `ppb_run_fk`(`payroll_run_id` ASC) USING BTREE,
  INDEX `ppb_bank_fk`(`bank_account_id` ASC) USING BTREE,
  INDEX `ppb_status_index`(`status` ASC) USING BTREE,
  INDEX `ppb_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `ppb_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `ppb_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ppb_run_fk` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payroll_payment_batches
-- ----------------------------

-- ----------------------------
-- Table structure for payroll_payments
-- ----------------------------
DROP TABLE IF EXISTS `payroll_payments`;
CREATE TABLE `payroll_payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payroll_payment_batch_id` bigint UNSIGNED NOT NULL,
  `payroll_detail_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','processing','success','failed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `bank_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `failure_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payroll_payments_batch_detail_unique`(`payroll_payment_batch_id` ASC, `payroll_detail_id` ASC) USING BTREE,
  INDEX `payroll_payments_detail_fk`(`payroll_detail_id` ASC) USING BTREE,
  INDEX `payroll_payments_employee_fk`(`employee_id` ASC) USING BTREE,
  INDEX `payroll_payments_journal_fk`(`journal_entry_id` ASC) USING BTREE,
  INDEX `payroll_payments_status_index`(`status` ASC) USING BTREE,
  INDEX `payroll_payments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `payroll_payments_batch_fk` FOREIGN KEY (`payroll_payment_batch_id`) REFERENCES `payroll_payment_batches` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `payroll_payments_detail_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payroll_payments_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payroll_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payroll_payments
-- ----------------------------

-- ----------------------------
-- Table structure for payroll_periods
-- ----------------------------
DROP TABLE IF EXISTS `payroll_periods`;
CREATE TABLE `payroll_periods`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `period` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `payment_date` date NULL DEFAULT NULL,
  `status` enum('open','processing','approved','paid','closed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payroll_periods_org_period_unique`(`organization_id` ASC, `period` ASC) USING BTREE,
  INDEX `payroll_periods_status_index`(`status` ASC) USING BTREE,
  INDEX `payroll_periods_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `payroll_periods_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payroll_periods
-- ----------------------------

-- ----------------------------
-- Table structure for payroll_permissions
-- ----------------------------
DROP TABLE IF EXISTS `payroll_permissions`;
CREATE TABLE `payroll_permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `module` enum('payroll','finance','hr','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission` enum('view','create','edit','delete','process','approve','export') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint UNSIGNED NULL DEFAULT NULL,
  `employee_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payroll_permissions_user_module_permission_unique`(`user_id` ASC, `module` ASC, `permission` ASC, `department_id` ASC, `employee_id` ASC) USING BTREE,
  INDEX `payroll_permissions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `payroll_permissions_module_index`(`module` ASC) USING BTREE,
  INDEX `payroll_permissions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `payroll_permissions_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payroll_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for payroll_runs
-- ----------------------------
DROP TABLE IF EXISTS `payroll_runs`;
CREATE TABLE `payroll_runs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `payroll_period_id` bigint UNSIGNED NOT NULL,
  `run_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `run_type` enum('regular','off_cycle','correction','bonus') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
  `status` enum('draft','calculating','calculated','reviewed','approved','posted','paid','cancelled','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
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
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payroll_runs_org_number_unique`(`organization_id` ASC, `run_number` ASC) USING BTREE,
  INDEX `payroll_runs_period_index`(`payroll_period_id` ASC) USING BTREE,
  INDEX `payroll_runs_status_index`(`status` ASC) USING BTREE,
  INDEX `payroll_runs_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `payroll_runs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `payroll_runs_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payroll_runs
-- ----------------------------

-- ----------------------------
-- Table structure for payroll_settings
-- ----------------------------
DROP TABLE IF EXISTS `payroll_settings`;
CREATE TABLE `payroll_settings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `group` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payroll_settings_org_key_unique`(`organization_id` ASC, `key` ASC) USING BTREE,
  INDEX `payroll_settings_group_index`(`group` ASC) USING BTREE,
  INDEX `payroll_settings_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `payroll_settings_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `payroll_settings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payroll_settings
-- ----------------------------

-- ----------------------------
-- Table structure for payslips
-- ----------------------------
DROP TABLE IF EXISTS `payslips`;
CREATE TABLE `payslips`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payroll_detail_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `payroll_period_id` bigint UNSIGNED NOT NULL,
  `payslip_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `generated_at` timestamp NULL DEFAULT NULL,
  `pdf_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `html_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_sent` tinyint(1) NOT NULL DEFAULT 0,
  `sent_at` timestamp NULL DEFAULT NULL,
  `is_downloaded` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payslips_detail_unique`(`payroll_detail_id` ASC) USING BTREE,
  UNIQUE INDEX `payslips_org_number_unique`(`organization_id` ASC, `payslip_number` ASC) USING BTREE,
  INDEX `payslips_employee_fk`(`employee_id` ASC) USING BTREE,
  INDEX `payslips_period_fk`(`payroll_period_id` ASC) USING BTREE,
  INDEX `payslips_organization_id_index`(`organization_id` ASC) USING BTREE,
  INDEX `payslips_is_sent_index`(`is_sent` ASC) USING BTREE,
  INDEX `payslips_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `payslips_detail_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `payslips_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payslips_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `payslips_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payslips
-- ----------------------------

-- ----------------------------
-- Table structure for position_user
-- ----------------------------
DROP TABLE IF EXISTS `position_user`;
CREATE TABLE `position_user`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `position_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_position_unique`(`user_id` ASC, `position_id` ASC) USING BTREE,
  INDEX `position_user_position_id_index`(`position_id` ASC) USING BTREE,
  INDEX `position_user_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `position_user_position_fk` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `position_user_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of position_user
-- ----------------------------

-- ----------------------------
-- Table structure for positions
-- ----------------------------
DROP TABLE IF EXISTS `positions`;
CREATE TABLE `positions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int NULL DEFAULT NULL,
  `salary_range_min` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `salary_range_max` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `positions_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `positions_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `positions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `positions_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of positions
-- ----------------------------

-- ----------------------------
-- Table structure for ptkp_statuses
-- ----------------------------
DROP TABLE IF EXISTS `ptkp_statuses`;
CREATE TABLE `ptkp_statuses`  (
  `id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ptkp_statuses_deleted_at_index`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ptkp_statuses
-- ----------------------------

-- ----------------------------
-- Table structure for reconciliation_details
-- ----------------------------
DROP TABLE IF EXISTS `reconciliation_details`;
CREATE TABLE `reconciliation_details`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `reconciliation_id` bigint UNSIGNED NOT NULL,
  `bank_transaction_id` bigint UNSIGNED NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `type` enum('match','unmatch','adjustment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `reconciliation_details_recon_fk`(`reconciliation_id` ASC) USING BTREE,
  INDEX `reconciliation_details_bank_fk`(`bank_transaction_id` ASC) USING BTREE,
  INDEX `reconciliation_details_journal_fk`(`journal_entry_id` ASC) USING BTREE,
  CONSTRAINT `reconciliation_details_bank_fk` FOREIGN KEY (`bank_transaction_id`) REFERENCES `bank_transactions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `reconciliation_details_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `reconciliation_details_recon_fk` FOREIGN KEY (`reconciliation_id`) REFERENCES `reconciliations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reconciliation_details
-- ----------------------------

-- ----------------------------
-- Table structure for reconciliations
-- ----------------------------
DROP TABLE IF EXISTS `reconciliations`;
CREATE TABLE `reconciliations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bank_account_id` bigint UNSIGNED NOT NULL,
  `reconciliation_date` date NOT NULL,
  `statement_balance` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `book_balance` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `difference` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','completed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `completed_by` bigint UNSIGNED NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `reconciliations_bank_fk`(`bank_account_id` ASC) USING BTREE,
  INDEX `reconciliations_status_index`(`status` ASC) USING BTREE,
  INDEX `reconciliations_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `reconciliations_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reconciliations
-- ----------------------------

-- ----------------------------
-- Table structure for religions
-- ----------------------------
DROP TABLE IF EXISTS `religions`;
CREATE TABLE `religions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `religions_name_unique`(`name` ASC) USING BTREE,
  INDEX `religions_deleted_at_index`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of religions
-- ----------------------------

-- ----------------------------
-- Table structure for role_user
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `role_user_user_id_role_id_org_unique`(`user_id` ASC, `role_id` ASC, `organization_id` ASC) USING BTREE,
  UNIQUE INDEX `role_user_single_default_per_user_org`(`user_id` ASC, `organization_id` ASC, `is_default` ASC) USING BTREE,
  INDEX `role_user_role_id_foreign`(`role_id` ASC) USING BTREE,
  INDEX `role_user_organization_id_index`(`organization_id` ASC) USING BTREE,
  INDEX `role_user_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `role_user_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_user
-- ----------------------------
INSERT INTO `role_user` VALUES (1, 1, 1, 1, 1, '2026-09-09 17:08:23', '2026-09-09 17:08:23', NULL, NULL, NULL);
INSERT INTO `role_user` VALUES (2, 1, 2, 1, 0, '2026-09-09 17:08:23', '2026-09-09 17:08:23', NULL, NULL, NULL);
INSERT INTO `role_user` VALUES (3, 2, 3, 1, 1, '2026-09-09 17:08:24', '2026-09-09 17:08:24', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_org_name_unique`(`organization_id` ASC, `name` ASC) USING BTREE,
  INDEX `roles_is_system_index`(`is_system` ASC) USING BTREE,
  INDEX `roles_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `roles_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 1, 'super-admin', 'Super Administrator', 'Memiliki akses penuh ke seluruh sistem', 1, 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `roles` VALUES (2, 1, 'admin', 'Administrator', 'Mengelola konten dan pengguna biasa', 1, 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `roles` VALUES (3, 1, 'user', 'Pengguna', 'Pengguna biasa', 1, 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for rooms
-- ----------------------------
DROP TABLE IF EXISTS `rooms`;
CREATE TABLE `rooms`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `floor_id` bigint UNSIGNED NOT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `capacity` int NULL DEFAULT 0,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rooms_floor_code_unique`(`floor_id` ASC, `code` ASC) USING BTREE,
  INDEX `rooms_floor_id_foreign`(`floor_id` ASC) USING BTREE,
  INDEX `rooms_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `rooms_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `rooms_floor_fk` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of rooms
-- ----------------------------

-- ----------------------------
-- Table structure for salary_components
-- ----------------------------
DROP TABLE IF EXISTS `salary_components`;
CREATE TABLE `salary_components`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('earning','deduction','company_contribution','tax') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `calculation_type` enum('fixed','percentage','formula','attendance','loan','tax','manual') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `calculation_base` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `default_value` decimal(15, 4) NOT NULL DEFAULT 0.0000,
  `is_taxable` tinyint(1) NOT NULL DEFAULT 0,
  `is_bpjs_base` tinyint(1) NOT NULL DEFAULT 0,
  `affects_net_salary` tinyint(1) NOT NULL DEFAULT 1,
  `is_recurring` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `salary_components_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `salary_components_type_index`(`type` ASC) USING BTREE,
  INDEX `salary_components_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `salary_components_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `salary_components_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of salary_components
-- ----------------------------

-- ----------------------------
-- Table structure for salary_structure_components
-- ----------------------------
DROP TABLE IF EXISTS `salary_structure_components`;
CREATE TABLE `salary_structure_components`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `salary_structure_id` bigint UNSIGNED NOT NULL,
  `salary_component_id` bigint UNSIGNED NOT NULL,
  `calculation_type` enum('fixed','percentage','formula','inherit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inherit',
  `value` decimal(15, 4) NOT NULL DEFAULT 0.0000,
  `calculation_base` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `calculation_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `salary_structure_components_unique`(`salary_structure_id` ASC, `salary_component_id` ASC) USING BTREE,
  INDEX `ssc_component_fk`(`salary_component_id` ASC) USING BTREE,
  INDEX `ssc_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `ssc_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `ssc_component_fk` FOREIGN KEY (`salary_component_id`) REFERENCES `salary_components` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `ssc_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of salary_structure_components
-- ----------------------------

-- ----------------------------
-- Table structure for salary_structures
-- ----------------------------
DROP TABLE IF EXISTS `salary_structures`;
CREATE TABLE `salary_structures`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `salary_structures_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `salary_structures_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `salary_structures_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `salary_structures_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of salary_structures
-- ----------------------------

-- ----------------------------
-- Table structure for scheduled_jobs
-- ----------------------------
DROP TABLE IF EXISTS `scheduled_jobs`;
CREATE TABLE `scheduled_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `command` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_run` timestamp NULL DEFAULT NULL,
  `next_run` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'active',
  `last_error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `scheduled_jobs_org_name_unique`(`organization_id` ASC, `name` ASC) USING BTREE,
  INDEX `scheduled_jobs_status_index`(`status` ASC) USING BTREE,
  INDEX `scheduled_jobs_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `scheduled_jobs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of scheduled_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for schedules
-- ----------------------------
DROP TABLE IF EXISTS `schedules`;
CREATE TABLE `schedules`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `classroom_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `teacher_id` bigint UNSIGNED NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `day_of_week` enum('MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `room_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_locked` tinyint(1) NULL DEFAULT 0,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_schedules_classroom`(`classroom_id` ASC) USING BTREE,
  INDEX `idx_schedules_teacher`(`teacher_id` ASC) USING BTREE,
  INDEX `idx_schedules_day_time`(`day_of_week` ASC, `start_time` ASC) USING BTREE,
  INDEX `idx_schedules_academic_year`(`academic_year_id` ASC) USING BTREE,
  INDEX `idx_schedules_deleted_at`(`deleted_at` ASC) USING BTREE,
  INDEX `schedules_subject_fk`(`subject_id` ASC) USING BTREE,
  CONSTRAINT `schedules_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `schedules_classroom_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `schedules_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `schedules_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of schedules
-- ----------------------------

-- ----------------------------
-- Table structure for schools
-- ----------------------------
DROP TABLE IF EXISTS `schools`;
CREATE TABLE `schools`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `npsn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `total_student` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('trial','active','suspended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'trial',
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `schools_organization_id_index`(`organization_id` ASC) USING BTREE,
  INDEX `schools_status_index`(`status` ASC) USING BTREE,
  INDEX `schools_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `schools_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of schools
-- ----------------------------

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('ZorZPS7EqCumR0UCE9NcJe8q5J8X28OrhePqKMoh', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiM2pYOTNUd2J1SlU3UlNiSmdndFViYnA4WEVyRHQybGxBVTFPZ0V1UyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jcnVkLWdlbmVyYXRvciI7czo1OiJyb3V0ZSI7czoyMDoiY3J1ZF9nZW5lcmF0b3IuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6OToidXNlcl9hdXRoIjthOjc6e3M6ODoibG9naW5fYXMiO3M6MTE6InN1cGVyLWFkbWluIjtzOjE0OiJhY3RpdmVfcm9sZV9pZCI7aToxO3M6MTM6ImF1dGhfcm9sZV9pZHMiO086Mjk6IklsbHVtaW5hdGVcU3VwcG9ydFxDb2xsZWN0aW9uIjoyOntzOjg6IgAqAGl0ZW1zIjthOjI6e2k6MDtpOjE7aToxO2k6Mjt9czoyODoiACoAZXNjYXBlV2hlbkNhc3RpbmdUb1N0cmluZyI7YjowO31zOjEwOiJhdXRoX3JvbGVzIjtPOjI5OiJJbGx1bWluYXRlXFN1cHBvcnRcQ29sbGVjdGlvbiI6Mjp7czo4OiIAKgBpdGVtcyI7YToyOntpOjA7czoxMToic3VwZXItYWRtaW4iO2k6MTtzOjU6ImFkbWluIjt9czoyODoiACoAZXNjYXBlV2hlbkNhc3RpbmdUb1N0cmluZyI7YjowO31zOjk6ImF1dGhfcm9sZSI7TzoxMToiTW9kZWxzXFJvbGUiOjM2OntzOjEzOiIAKgBjb25uZWN0aW9uIjtzOjU6Im15c3FsIjtzOjg6IgAqAHRhYmxlIjtzOjU6InJvbGVzIjtzOjEzOiIAKgBwcmltYXJ5S2V5IjtzOjI6ImlkIjtzOjEwOiIAKgBrZXlUeXBlIjtzOjM6ImludCI7czoxMjoiaW5jcmVtZW50aW5nIjtiOjE7czo3OiIAKgB3aXRoIjthOjA6e31zOjEyOiIAKgB3aXRoQ291bnQiO2E6MDp7fXM6MTk6InByZXZlbnRzTGF6eUxvYWRpbmciO2I6MDtzOjEwOiIAKgBwZXJQYWdlIjtpOjE1O3M6NjoiZXhpc3RzIjtiOjE7czoxODoid2FzUmVjZW50bHlDcmVhdGVkIjtiOjA7czoyODoiACoAZXNjYXBlV2hlbkNhc3RpbmdUb1N0cmluZyI7YjowO3M6MTM6IgAqAGF0dHJpYnV0ZXMiO2E6MTE6e3M6MjoiaWQiO2k6MTtzOjE1OiJvcmdhbml6YXRpb25faWQiO2k6MTtzOjQ6Im5hbWUiO3M6MTE6InN1cGVyLWFkbWluIjtzOjEyOiJkaXNwbGF5X25hbWUiO3M6MTk6IlN1cGVyIEFkbWluaXN0cmF0b3IiO3M6MTE6ImRlc2NyaXB0aW9uIjtzOjM4OiJNZW1pbGlraSBha3NlcyBwZW51aCBrZSBzZWx1cnVoIHNpc3RlbSI7czo5OiJpc19zeXN0ZW0iO2k6MTtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDI2LTA5LTA5IDE3OjA3OjQxIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDI2LTA5LTA5IDE3OjA3OjQxIjtzOjEwOiJkZWxldGVkX2F0IjtOO3M6MTA6ImNyZWF0ZWRfYnkiO047czoxMDoidXBkYXRlZF9ieSI7Tjt9czoxMToiACoAb3JpZ2luYWwiO2E6MTY6e3M6MjoiaWQiO2k6MTtzOjE1OiJvcmdhbml6YXRpb25faWQiO2k6MTtzOjQ6Im5hbWUiO3M6MTE6InN1cGVyLWFkbWluIjtzOjEyOiJkaXNwbGF5X25hbWUiO3M6MTk6IlN1cGVyIEFkbWluaXN0cmF0b3IiO3M6MTE6ImRlc2NyaXB0aW9uIjtzOjM4OiJNZW1pbGlraSBha3NlcyBwZW51aCBrZSBzZWx1cnVoIHNpc3RlbSI7czo5OiJpc19zeXN0ZW0iO2k6MTtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDI2LTA5LTA5IDE3OjA3OjQxIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDI2LTA5LTA5IDE3OjA3OjQxIjtzOjEwOiJkZWxldGVkX2F0IjtOO3M6MTA6ImNyZWF0ZWRfYnkiO047czoxMDoidXBkYXRlZF9ieSI7Tjtz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', 1788986964);

-- ----------------------------
-- Table structure for shift_rates
-- ----------------------------
DROP TABLE IF EXISTS `shift_rates`;
CREATE TABLE `shift_rates`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `shift_id` bigint UNSIGNED NOT NULL,
  `day_type` enum('weekday','saturday','sunday','national_holiday','special_holiday') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_rate_per_shift` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `overtime_multiplier` decimal(5, 2) NOT NULL DEFAULT 1.50,
  `overtime_rate_per_hour` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `night_allowance_per_shift` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `meal_allowance_per_shift` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `transport_allowance_per_shift` decimal(12, 2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `shift_rates_shift_day_unique`(`shift_id` ASC, `day_type` ASC) USING BTREE,
  INDEX `shift_rates_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `shift_rates_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `shift_rates_shift_fk` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of shift_rates
-- ----------------------------

-- ----------------------------
-- Table structure for shift_swaps
-- ----------------------------
DROP TABLE IF EXISTS `shift_swaps`;
CREATE TABLE `shift_swaps`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `employee_id_a` bigint UNSIGNED NOT NULL,
  `original_shift_a` bigint UNSIGNED NOT NULL,
  `target_shift_a` bigint UNSIGNED NOT NULL,
  `employee_id_b` bigint UNSIGNED NOT NULL,
  `original_shift_b` bigint UNSIGNED NOT NULL,
  `target_shift_b` bigint UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'pending',
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint UNSIGNED NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `swap_per_date_unique`(`date` ASC, `employee_id_a` ASC) USING BTREE,
  UNIQUE INDEX `swap_per_date_b_unique`(`date` ASC, `employee_id_b` ASC) USING BTREE,
  INDEX `shift_swaps_employee_id_a_index`(`employee_id_a` ASC) USING BTREE,
  INDEX `shift_swaps_employee_id_b_index`(`employee_id_b` ASC) USING BTREE,
  INDEX `shift_swaps_status_index`(`status` ASC) USING BTREE,
  INDEX `shift_swaps_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `shift_swaps_org_fk`(`organization_id` ASC) USING BTREE,
  INDEX `shift_swaps_orig_shift_a_fk`(`original_shift_a` ASC) USING BTREE,
  INDEX `shift_swaps_target_shift_a_fk`(`target_shift_a` ASC) USING BTREE,
  INDEX `shift_swaps_orig_shift_b_fk`(`original_shift_b` ASC) USING BTREE,
  INDEX `shift_swaps_target_shift_b_fk`(`target_shift_b` ASC) USING BTREE,
  CONSTRAINT `shift_swaps_employee_a_fk` FOREIGN KEY (`employee_id_a`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `shift_swaps_employee_b_fk` FOREIGN KEY (`employee_id_b`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `shift_swaps_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `shift_swaps_orig_shift_a_fk` FOREIGN KEY (`original_shift_a`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `shift_swaps_orig_shift_b_fk` FOREIGN KEY (`original_shift_b`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `shift_swaps_target_shift_a_fk` FOREIGN KEY (`target_shift_a`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `shift_swaps_target_shift_b_fk` FOREIGN KEY (`target_shift_b`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of shift_swaps
-- ----------------------------

-- ----------------------------
-- Table structure for shifts
-- ----------------------------
DROP TABLE IF EXISTS `shifts`;
CREATE TABLE `shifts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `crosses_midnight` tinyint(1) NULL DEFAULT NULL,
  `grace_period_start` int NULL DEFAULT 15,
  `grace_period_end` int NULL DEFAULT 15,
  `break_start` time NULL DEFAULT NULL,
  `break_end` time NULL DEFAULT NULL,
  `break_duration` int NULL DEFAULT 0,
  `night_shift_start` time NULL DEFAULT NULL,
  `overtime_min_threshold` int NULL DEFAULT 60,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `shifts_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `shifts_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `shifts_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `shifts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of shifts
-- ----------------------------

-- ----------------------------
-- Table structure for student_classrooms
-- ----------------------------
DROP TABLE IF EXISTS `student_classrooms`;
CREATE TABLE `student_classrooms`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `classroom_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `enrollment_date` date NULL DEFAULT NULL,
  `status` enum('ACTIVE','TRANSFERRED','DROPPED','GRADUATED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_classrooms_unique`(`student_id` ASC, `classroom_id` ASC, `academic_year_id` ASC) USING BTREE,
  INDEX `idx_student_classrooms_student`(`student_id` ASC) USING BTREE,
  INDEX `idx_student_classrooms_classroom`(`classroom_id` ASC) USING BTREE,
  INDEX `idx_student_classrooms_academic_year`(`academic_year_id` ASC) USING BTREE,
  INDEX `idx_student_classrooms_deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `student_classrooms_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `student_classrooms_classroom_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `student_classrooms_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of student_classrooms
-- ----------------------------

-- ----------------------------
-- Table structure for student_parents
-- ----------------------------
DROP TABLE IF EXISTS `student_parents`;
CREATE TABLE `student_parents`  (
  `student_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED NOT NULL,
  `is_primary` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`student_id`, `parent_id`) USING BTREE,
  INDEX `idx_student_parents_student`(`student_id` ASC) USING BTREE,
  INDEX `idx_student_parents_parent`(`parent_id` ASC) USING BTREE,
  INDEX `idx_student_parents_deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `student_parents_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `student_parents_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of student_parents
-- ----------------------------

-- ----------------------------
-- Table structure for student_ranges
-- ----------------------------
DROP TABLE IF EXISTS `student_ranges`;
CREATE TABLE `student_ranges`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_value` int NULL DEFAULT NULL,
  `max_value` int NULL DEFAULT NULL,
  `sort_order` int NOT NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `student_ranges_org_fk`(`organization_id` ASC) USING BTREE,
  INDEX `student_ranges_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `student_ranges_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `student_ranges_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of student_ranges
-- ----------------------------

-- ----------------------------
-- Table structure for students
-- ----------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `nis` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nisn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `registration_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `full_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_place` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birth_date` date NULL DEFAULT NULL,
  `gender` enum('MALE','FEMALE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `religion` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `parents_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `emergency_contact` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `medical_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `join_date` date NULL DEFAULT NULL,
  `status` enum('ACTIVE','GRADUATED','TRANSFERRED','DROPPED','SUSPENDED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'ACTIVE',
  `admission_status` enum('NEW','TRANSFER','RETURNING') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `enrollment_year` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `students_org_nis_unique`(`organization_id` ASC, `nis` ASC) USING BTREE,
  UNIQUE INDEX `students_org_nisn_unique`(`organization_id` ASC, `nisn` ASC) USING BTREE,
  INDEX `idx_students_organization_id`(`organization_id` ASC) USING BTREE,
  INDEX `idx_students_status`(`status` ASC) USING BTREE,
  INDEX `idx_students_birth_date`(`birth_date` ASC) USING BTREE,
  INDEX `idx_students_deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `students_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of students
-- ----------------------------

-- ----------------------------
-- Table structure for subjects
-- ----------------------------
DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `category` enum('CORE','ELECTIVE','EXTRACURRICULAR','RELIGION','LOCAL_CONTENT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `subjects_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `idx_subjects_category`(`category` ASC) USING BTREE,
  INDEX `idx_subjects_is_active`(`is_active` ASC) USING BTREE,
  INDEX `idx_subjects_deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `subjects_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of subjects
-- ----------------------------

-- ----------------------------
-- Table structure for system_logs
-- ----------------------------
DROP TABLE IF EXISTS `system_logs`;
CREATE TABLE `system_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `level` enum('debug','info','warning','error','critical') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `context` json NULL,
  `channel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_system_logs_level`(`level` ASC) USING BTREE,
  INDEX `idx_system_logs_created`(`created_at` ASC) USING BTREE,
  INDEX `idx_system_logs_org`(`organization_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_logs
-- ----------------------------

-- ----------------------------
-- Table structure for tax_liabilities
-- ----------------------------
DROP TABLE IF EXISTS `tax_liabilities`;
CREATE TABLE `tax_liabilities`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `tax_type` enum('pph21','pph23','pph25','ppn','ppnbm','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_period` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `due_date` date NOT NULL,
  `status` enum('pending','partial','paid','overdue','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `source_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `source_id` bigint UNSIGNED NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `tax_liabilities_org_period_index`(`organization_id` ASC, `tax_type` ASC, `tax_period` ASC) USING BTREE,
  INDEX `tax_liabilities_journal_fk`(`journal_entry_id` ASC) USING BTREE,
  INDEX `tax_liabilities_status_index`(`status` ASC) USING BTREE,
  INDEX `tax_liabilities_due_date_index`(`due_date` ASC) USING BTREE,
  INDEX `tax_liabilities_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `tax_liabilities_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `tax_liabilities_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tax_liabilities
-- ----------------------------

-- ----------------------------
-- Table structure for tax_payments
-- ----------------------------
DROP TABLE IF EXISTS `tax_payments`;
CREATE TABLE `tax_payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tax_liability_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `payment_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `payment_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bank_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` enum('draft','paid','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tax_payments_org_number_unique`(`organization_id` ASC, `payment_number` ASC) USING BTREE,
  INDEX `tax_payments_liability_fk`(`tax_liability_id` ASC) USING BTREE,
  INDEX `tax_payments_bank_fk`(`bank_account_id` ASC) USING BTREE,
  INDEX `tax_payments_journal_fk`(`journal_entry_id` ASC) USING BTREE,
  INDEX `tax_payments_status_index`(`status` ASC) USING BTREE,
  INDEX `tax_payments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `tax_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `tax_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `tax_payments_liability_fk` FOREIGN KEY (`tax_liability_id`) REFERENCES `tax_liabilities` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tax_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tax_payments
-- ----------------------------

-- ----------------------------
-- Table structure for tax_settings
-- ----------------------------
DROP TABLE IF EXISTS `tax_settings`;
CREATE TABLE `tax_settings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `tax_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_type` enum('pph21','pph23','pph25','ppn','ppnbm','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_rate` decimal(7, 4) NOT NULL DEFAULT 0.0000,
  `min_amount` decimal(15, 2) NULL DEFAULT NULL,
  `max_amount` decimal(15, 2) NULL DEFAULT NULL,
  `effective_date` date NOT NULL,
  `expiry_date` date NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tax_settings_org_code_unique`(`organization_id` ASC, `tax_code` ASC) USING BTREE,
  INDEX `tax_settings_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `tax_settings_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `tax_settings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tax_settings
-- ----------------------------

-- ----------------------------
-- Table structure for teachers
-- ----------------------------
DROP TABLE IF EXISTS `teachers`;
CREATE TABLE `teachers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `full_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialization` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `education_level` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `join_date` date NULL DEFAULT NULL,
  `status` enum('ACTIVE','RESIGNED','RETIRED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `teachers_org_nip_unique`(`organization_id` ASC, `nip` ASC) USING BTREE,
  INDEX `idx_teachers_organization_id`(`organization_id` ASC) USING BTREE,
  INDEX `idx_teachers_status`(`status` ASC) USING BTREE,
  INDEX `idx_teachers_deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `teachers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of teachers
-- ----------------------------

-- ----------------------------
-- Table structure for teaching_loads
-- ----------------------------
DROP TABLE IF EXISTS `teaching_loads`;
CREATE TABLE `teaching_loads`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `classroom_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `semester` enum('SEMESTER_1','SEMESTER_2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `total_hours` int NULL DEFAULT 0,
  `is_active` tinyint(1) NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `teaching_loads_unique`(`teacher_id` ASC, `classroom_id` ASC, `subject_id` ASC, `academic_year_id` ASC, `semester` ASC) USING BTREE,
  INDEX `idx_teaching_loads_teacher`(`teacher_id` ASC) USING BTREE,
  INDEX `idx_teaching_loads_classroom`(`classroom_id` ASC) USING BTREE,
  INDEX `idx_teaching_loads_academic_year`(`academic_year_id` ASC) USING BTREE,
  INDEX `idx_teaching_loads_deleted_at`(`deleted_at` ASC) USING BTREE,
  INDEX `teaching_loads_subject_fk`(`subject_id` ASC) USING BTREE,
  CONSTRAINT `teaching_loads_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `teaching_loads_classroom_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `teaching_loads_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `teaching_loads_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of teaching_loads
-- ----------------------------

-- ----------------------------
-- Table structure for trial_registration_education_level
-- ----------------------------
DROP TABLE IF EXISTS `trial_registration_education_level`;
CREATE TABLE `trial_registration_education_level`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trial_registration_id` bigint UNSIGNED NOT NULL,
  `education_level_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tr_education_level_unique`(`trial_registration_id` ASC, `education_level_id` ASC) USING BTREE,
  INDEX `trial_registration_education_level_education_fk`(`education_level_id` ASC) USING BTREE,
  CONSTRAINT `tr_education_level_edu_fk` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tr_education_level_reg_fk` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of trial_registration_education_level
-- ----------------------------

-- ----------------------------
-- Table structure for trial_registration_module
-- ----------------------------
DROP TABLE IF EXISTS `trial_registration_module`;
CREATE TABLE `trial_registration_module`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trial_registration_id` bigint UNSIGNED NOT NULL,
  `module_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tr_module_unique`(`trial_registration_id` ASC, `module_id` ASC) USING BTREE,
  INDEX `trial_registration_module_module_fk`(`module_id` ASC) USING BTREE,
  CONSTRAINT `tr_module_module_fk` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tr_module_reg_fk` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of trial_registration_module
-- ----------------------------

-- ----------------------------
-- Table structure for trial_registration_role
-- ----------------------------
DROP TABLE IF EXISTS `trial_registration_role`;
CREATE TABLE `trial_registration_role`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trial_registration_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tr_role_unique`(`trial_registration_id` ASC, `role_id` ASC) USING BTREE,
  INDEX `trial_registration_role_role_fk`(`role_id` ASC) USING BTREE,
  CONSTRAINT `tr_role_reg_fk` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tr_role_role_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of trial_registration_role
-- ----------------------------

-- ----------------------------
-- Table structure for trial_registrations
-- ----------------------------
DROP TABLE IF EXISTS `trial_registrations`;
CREATE TABLE `trial_registrations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `token` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_step` tinyint UNSIGNED NOT NULL DEFAULT 1,
  `status` enum('in_progress','completed','abandoned') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `nama_sekolah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `npsn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `total_student` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nama_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `position_id` int NULL DEFAULT NULL,
  `notif_whatsapp` tinyint(1) NOT NULL DEFAULT 0,
  `notif_email` tinyint(1) NOT NULL DEFAULT 0,
  `notif_sms` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `no_hp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `trial_registrations_token_unique`(`token` ASC) USING BTREE,
  INDEX `trial_registrations_status_index`(`status` ASC) USING BTREE,
  INDEX `trial_registrations_deleted_at_index`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of trial_registrations
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `two_factor_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `two_factor_recovery_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  INDEX `users_email_verified_at_index`(`email_verified_at` ASC) USING BTREE,
  INDEX `users_deleted_at_index`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Administrator Utama', 'admin@contoh.id', '2026-09-09 17:08:23', '$2y$12$ApZZYsuECqSAx2dlOEwlL.bKcKCaD.ilKo3kLJ850otsXcntr7X0S', NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-09 17:08:23', '2026-09-09 17:08:23', NULL, NULL, NULL);
INSERT INTO `users` VALUES (2, 'Pengguna Biasa', 'user@contoh.id', '2026-09-09 17:08:24', '$2y$12$n75I8iQeGGpOxxKLuT/B2uzArGBZeWRDJneqatSDIVJOtFCPIeLCi', NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-09 17:08:24', '2026-09-09 17:08:24', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for vendors
-- ----------------------------
DROP TABLE IF EXISTS `vendors`;
CREATE TABLE `vendors`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tax_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payable_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `vendors_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `vendors_payable_account_fk`(`payable_account_id` ASC) USING BTREE,
  INDEX `vendors_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `vendors_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `vendors_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `vendors_payable_account_fk` FOREIGN KEY (`payable_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of vendors
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
