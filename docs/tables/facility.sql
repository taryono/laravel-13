/*
 * Table Group: facility
 * Total Tables: 34
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `asset_categories`
-- ----------------------------
DROP TABLE IF EXISTS `asset_categories`;
CREATE TABLE `asset_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `account_categories_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `account_categories_is_active_index` (`is_active`) USING BTREE,
  KEY `account_categories_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `asset_categories_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `asset_depreciation_logs`
-- ----------------------------
DROP TABLE IF EXISTS `asset_depreciation_logs`;
CREATE TABLE `asset_depreciation_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` bigint unsigned NOT NULL,
  `period_year` smallint unsigned NOT NULL,
  `period_month` tinyint unsigned NOT NULL,
  `period_date` date NOT NULL,
  `opening_book_value` decimal(18,2) NOT NULL,
  `depreciation_amount` decimal(18,2) NOT NULL,
  `accumulated_depreciation` decimal(18,2) NOT NULL,
  `closing_book_value` decimal(18,2) NOT NULL,
  `depreciation_method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_price` decimal(18,2) NOT NULL,
  `salvage_value` decimal(18,2) NOT NULL DEFAULT '0.00',
  `useful_life_months` int unsigned NOT NULL,
  `is_posted` tinyint(1) NOT NULL DEFAULT '0',
  `posted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `asset_period_unique` (`asset_id`,`period_year`,`period_month`) USING BTREE,
  KEY `asset_depreciation_logs_created_by_foreign` (`created_by`) USING BTREE,
  KEY `asset_depreciation_logs_updated_by_foreign` (`updated_by`) USING BTREE,
  KEY `asset_depreciation_logs_asset_id_period_date_index` (`asset_id`,`period_date`) USING BTREE,
  KEY `asset_depreciation_logs_is_posted_index` (`is_posted`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `asset_depreciations`
-- ----------------------------
DROP TABLE IF EXISTS `asset_depreciations`;
CREATE TABLE `asset_depreciations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fixed_asset_id` bigint unsigned NOT NULL,
  `accounting_period_id` bigint unsigned NOT NULL,
  `depreciation_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `asset_depreciations_asset_period_unique` (`fixed_asset_id`,`accounting_period_id`) USING BTREE,
  KEY `asset_depreciations_period_fk` (`accounting_period_id`) USING BTREE,
  KEY `asset_depreciations_journal_fk` (`journal_entry_id`) USING BTREE,
  KEY `asset_depreciations_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `asset_depreciations_asset_fk` FOREIGN KEY (`fixed_asset_id`) REFERENCES `fixed_assets` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `asset_depreciations_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `asset_depreciations_period_fk` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `asset_disposals`
-- ----------------------------
DROP TABLE IF EXISTS `asset_disposals`;
CREATE TABLE `asset_disposals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fixed_asset_id` bigint unsigned NOT NULL,
  `disposal_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `disposal_date` date NOT NULL,
  `disposal_type` enum('sale','scrapped','donated','lost','trade_in') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scrapped',
  `book_value` decimal(15,2) NOT NULL,
  `proceeds_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `gain_or_loss` decimal(15,2) GENERATED ALWAYS AS ((`proceeds_amount` - `book_value`)) STORED,
  `disposal_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `asset_disp_num_unique` (`disposal_number`) USING BTREE,
  KEY `asset_disp_asset_index` (`fixed_asset_id`) USING BTREE,
  KEY `asset_disp_approver_index` (`approved_by`) USING BTREE,
  KEY `asset_disp_journal_index` (`journal_entry_id`) USING BTREE,
  KEY `asset_disp_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `asset_disp_approver_fk` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `asset_disp_asset_fk` FOREIGN KEY (`fixed_asset_id`) REFERENCES `fixed_assets` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `asset_disp_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `asset_maintenances`
-- ----------------------------
DROP TABLE IF EXISTS `asset_maintenances`;
CREATE TABLE `asset_maintenances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fixed_asset_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `maintenance_type` enum('preventive','corrective','calibration','upgrade','inspection') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'preventive',
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `scheduled_date` date NOT NULL,
  `completion_date` date DEFAULT NULL,
  `cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('scheduled','in_progress','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `technician_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `asset_maint_asset_index` (`fixed_asset_id`) USING BTREE,
  KEY `asset_maint_vendor_index` (`vendor_id`) USING BTREE,
  KEY `asset_maint_journal_index` (`journal_entry_id`) USING BTREE,
  KEY `asset_maint_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `asset_maint_asset_fk` FOREIGN KEY (`fixed_asset_id`) REFERENCES `fixed_assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asset_maint_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `asset_maint_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `assets`
-- ----------------------------
DROP TABLE IF EXISTS `assets`;
CREATE TABLE `assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL,
  `school_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `asset_category_id` bigint unsigned DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `location_id` bigint unsigned DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specification` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `serial_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_price` decimal(15,2) DEFAULT '0.00',
  `salvage_value` decimal(15,2) DEFAULT '0.00',
  `useful_life_months` int unsigned DEFAULT NULL,
  `depreciation_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'straight_line',
  `accumulated_depreciation` decimal(15,2) DEFAULT '0.00',
  `asset_chart_of_account_id` bigint unsigned DEFAULT NULL,
  `accumulated_depreciation_chart_of_account_id` bigint unsigned DEFAULT NULL,
  `depreciation_expense_chart_of_account_id` bigint unsigned DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'IN_USE',
  `condition` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GOOD',
  `assigned_to` bigint unsigned DEFAULT NULL,
  `warranty_end` date DEFAULT NULL,
  `disposed_at` datetime DEFAULT NULL,
  `disposal_value` decimal(15,2) DEFAULT '0.00',
  `disposal_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `photo_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `assets_organization_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `assets_organization_index` (`organization_id`) USING BTREE,
  KEY `assets_school_index` (`school_id`) USING BTREE,
  KEY `assets_category_index` (`asset_category_id`) USING BTREE,
  KEY `assets_supplier_index` (`supplier_id`) USING BTREE,
  KEY `assets_location_index` (`location_id`) USING BTREE,
  KEY `assets_assigned_to_index` (`assigned_to`) USING BTREE,
  KEY `assets_status_index` (`status`) USING BTREE,
  KEY `assets_condition_index` (`condition`) USING BTREE,
  KEY `assets_purchase_date_index` (`purchase_date`) USING BTREE,
  KEY `assets_disposed_at_index` (`disposed_at`) USING BTREE,
  KEY `assets_is_active_index` (`is_active`) USING BTREE,
  KEY `assets_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `assets_asset_coa_foreign` (`asset_chart_of_account_id`) USING BTREE,
  KEY `assets_accum_depr_coa_foreign` (`accumulated_depreciation_chart_of_account_id`) USING BTREE,
  KEY `assets_depr_expense_coa_foreign` (`depreciation_expense_chart_of_account_id`) USING BTREE,
  CONSTRAINT `assets_accum_depr_coa_foreign` FOREIGN KEY (`accumulated_depreciation_chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_asset_coa_foreign` FOREIGN KEY (`asset_chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_category_id_foreign` FOREIGN KEY (`asset_category_id`) REFERENCES `asset_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_depr_expense_coa_foreign` FOREIGN KEY (`depreciation_expense_chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `buildings`
-- ----------------------------
DROP TABLE IF EXISTS `buildings`;
CREATE TABLE `buildings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_floors` int NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `buildings_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `buildings_is_active_index` (`is_active`) USING BTREE,
  KEY `buildings_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `buildings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `clinic_medicines`
-- ----------------------------
DROP TABLE IF EXISTS `clinic_medicines`;
CREATE TABLE `clinic_medicines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('oral_medicine','topical','bandage','antiseptic','medical_device','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'oral_medicine',
  `stock_qty` int NOT NULL DEFAULT '0',
  `min_stock_qty` int NOT NULL DEFAULT '5',
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tablet',
  `expiry_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `clinic_med_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `clinic_med_org_index` (`organization_id`) USING BTREE,
  KEY `clinic_med_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `clinic_med_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `clinic_med_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `clinic_prescriptions`
-- ----------------------------
DROP TABLE IF EXISTS `clinic_prescriptions`;
CREATE TABLE `clinic_prescriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_visit_id` bigint unsigned NOT NULL,
  `clinic_medicine_id` bigint unsigned NOT NULL,
  `qty_given` int unsigned NOT NULL DEFAULT '1',
  `dosage_instructions` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `clinic_rx_visit_index` (`clinic_visit_id`) USING BTREE,
  KEY `clinic_rx_med_index` (`clinic_medicine_id`) USING BTREE,
  CONSTRAINT `clinic_rx_med_fk` FOREIGN KEY (`clinic_medicine_id`) REFERENCES `clinic_medicines` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `clinic_rx_visit_fk` FOREIGN KEY (`clinic_visit_id`) REFERENCES `clinic_visits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `clinic_visits`
-- ----------------------------
DROP TABLE IF EXISTS `clinic_visits`;
CREATE TABLE `clinic_visits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `patient_type` enum('student','employee','guest') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `patient_id` bigint unsigned NOT NULL,
  `visit_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `symptoms` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnosis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `treatment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rest_duration_minutes` smallint unsigned DEFAULT NULL,
  `action_taken` enum('rest_at_uks','sent_home','referred_to_hospital','back_to_class') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'back_to_class',
  `referral_hospital` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handled_by` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `clinic_visit_school_index` (`school_id`) USING BTREE,
  KEY `clinic_visit_patient_index` (`patient_type`,`patient_id`) USING BTREE,
  KEY `clinic_visit_handled_index` (`handled_by`) USING BTREE,
  KEY `clinic_visit_org_fk` (`organization_id`) USING BTREE,
  CONSTRAINT `clinic_visit_handled_fk` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `clinic_visit_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `clinic_visit_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `facilities`
-- ----------------------------
DROP TABLE IF EXISTS `facilities`;
CREATE TABLE `facilities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `facility_category_id` bigint unsigned NOT NULL,
  `room_id` bigint unsigned NOT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quantity` int NOT NULL DEFAULT '1',
  `is_available` tinyint(1) DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `facilities_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `facilities_facility_category_id_foreign` (`facility_category_id`) USING BTREE,
  KEY `facilities_room_id_foreign` (`room_id`) USING BTREE,
  KEY `facilities_is_active_index` (`is_active`) USING BTREE,
  KEY `facilities_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `facilities_category_fk` FOREIGN KEY (`facility_category_id`) REFERENCES `facility_categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `facilities_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `facilities_room_fk` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `facility_categories`
-- ----------------------------
DROP TABLE IF EXISTS `facility_categories`;
CREATE TABLE `facility_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `facility_categories_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `facility_categories_is_active_index` (`is_active`) USING BTREE,
  KEY `facility_categories_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `facility_categories_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `fixed_assets`
-- ----------------------------
DROP TABLE IF EXISTS `fixed_assets`;
CREATE TABLE `fixed_assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int DEFAULT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `asset_category_id` int DEFAULT NULL,
  `brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asset_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acquisition_date` date NOT NULL,
  `acquisition_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `residual_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `useful_life_months` int NOT NULL DEFAULT '0',
  `useful_life_years` int DEFAULT NULL,
  `depreciation_method` enum('straight_line','declining_balance') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'straight_line',
  `accumulated_depreciation` decimal(15,2) NOT NULL DEFAULT '0.00',
  `book_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `asset_account_id` bigint unsigned NOT NULL,
  `accumulated_depreciation_account_id` bigint unsigned DEFAULT NULL,
  `depreciation_expense_account_id` bigint unsigned DEFAULT NULL,
  `status` enum('draft','active','disposed','fully_depreciated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `condition` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disposed_at` date DEFAULT NULL,
  `disposal_value` decimal(15,2) DEFAULT NULL,
  `disposal_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `fixed_assets_org_code_unique` (`organization_id`,`asset_code`) USING BTREE,
  KEY `fixed_assets_asset_account_fk` (`asset_account_id`) USING BTREE,
  KEY `fixed_assets_accum_account_fk` (`accumulated_depreciation_account_id`) USING BTREE,
  KEY `fixed_assets_depr_expense_account_fk` (`depreciation_expense_account_id`) USING BTREE,
  KEY `fixed_assets_status_index` (`status`) USING BTREE,
  KEY `fixed_assets_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `fixed_assets_accum_account_fk` FOREIGN KEY (`accumulated_depreciation_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fixed_assets_asset_account_fk` FOREIGN KEY (`asset_account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fixed_assets_depr_expense_account_fk` FOREIGN KEY (`depreciation_expense_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fixed_assets_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `floors`
-- ----------------------------
DROP TABLE IF EXISTS `floors`;
CREATE TABLE `floors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `building_id` bigint unsigned NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `floor_number` int NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `floors_building_code_unique` (`building_id`,`code`) USING BTREE,
  KEY `floors_building_id_foreign` (`building_id`) USING BTREE,
  KEY `floors_is_active_index` (`is_active`) USING BTREE,
  KEY `floors_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `floors_building_fk` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `goods_receipt_items`
-- ----------------------------
DROP TABLE IF EXISTS `goods_receipt_items`;
CREATE TABLE `goods_receipt_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `goods_receipt_id` bigint unsigned NOT NULL,
  `purchase_order_item_id` bigint unsigned NOT NULL,
  `account_id` bigint unsigned DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordered_quantity` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `received_quantity` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `rejected_quantity` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `unit_price` decimal(20,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(20,2) NOT NULL DEFAULT '0.00',
  `condition` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GOOD',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_asset` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `goods_receipt_items_receipt_index` (`goods_receipt_id`) USING BTREE,
  KEY `goods_receipt_items_po_item_index` (`purchase_order_item_id`) USING BTREE,
  KEY `goods_receipt_items_coa_index` (`account_id`) USING BTREE,
  KEY `goods_receipt_items_is_asset_index` (`is_asset`) USING BTREE,
  KEY `goods_receipt_items_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `goods_receipt_items_created_by_index` (`created_by`) USING BTREE,
  KEY `goods_receipt_items_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `goods_receipt_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `goods_receipt_items_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goods_receipt_items_purchase_order_item_id_foreign` FOREIGN KEY (`purchase_order_item_id`) REFERENCES `purchase_order_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `goods_receipts`
-- ----------------------------
DROP TABLE IF EXISTS `goods_receipts`;
CREATE TABLE `goods_receipts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `purchase_order_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `receipt_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_date` date NOT NULL,
  `received_by` bigint unsigned DEFAULT NULL,
  `checked_by` bigint unsigned DEFAULT NULL,
  `delivery_note_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GOOD',
  `condition_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'RECEIVED',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `goods_receipts_school_number_unique` (`school_id`,`receipt_number`) USING BTREE,
  KEY `goods_receipts_school_index` (`school_id`) USING BTREE,
  KEY `goods_receipts_po_index` (`purchase_order_id`) USING BTREE,
  KEY `goods_receipts_vendor_index` (`vendor_id`) USING BTREE,
  KEY `goods_receipts_date_index` (`receipt_date`) USING BTREE,
  KEY `goods_receipts_received_by_index` (`received_by`) USING BTREE,
  KEY `goods_receipts_checked_by_index` (`checked_by`) USING BTREE,
  KEY `goods_receipts_status_index` (`status`) USING BTREE,
  KEY `goods_receipts_condition_index` (`condition`) USING BTREE,
  KEY `goods_receipts_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `goods_receipts_created_by_index` (`created_by`) USING BTREE,
  KEY `goods_receipts_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `goods_receipts_checked_by_foreign` FOREIGN KEY (`checked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `goods_receipts_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goods_receipts_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `goods_receipts_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goods_receipts_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `inventory_categories`
-- ----------------------------
DROP TABLE IF EXISTS `inventory_categories`;
CREATE TABLE `inventory_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `inv_cat_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `inv_cat_parent_index` (`parent_id`) USING BTREE,
  KEY `inv_cat_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `inv_cat_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `inv_cat_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `inventory_categories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `inventory_items`
-- ----------------------------
DROP TABLE IF EXISTS `inventory_items`;
CREATE TABLE `inventory_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `uom_id` bigint unsigned NOT NULL,
  `code` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `item_type` enum('inventory','consumable','service','fixed_asset') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inventory',
  `min_stock` decimal(15,2) NOT NULL DEFAULT '0.00',
  `max_stock` decimal(15,2) NOT NULL DEFAULT '0.00',
  `standard_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `valuation_method` enum('fifo','lifo','average') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'average',
  `inventory_account_id` bigint unsigned DEFAULT NULL,
  `cogs_account_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `inv_items_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `inv_items_barcode_index` (`barcode`) USING BTREE,
  KEY `inv_items_category_index` (`category_id`) USING BTREE,
  KEY `inv_items_uom_index` (`uom_id`) USING BTREE,
  KEY `inv_items_inv_account_index` (`inventory_account_id`) USING BTREE,
  KEY `inv_items_cogs_account_index` (`cogs_account_id`) USING BTREE,
  KEY `inv_items_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `inv_items_cat_fk` FOREIGN KEY (`category_id`) REFERENCES `inventory_categories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `inv_items_cogs_acc_fk` FOREIGN KEY (`cogs_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `inv_items_inv_acc_fk` FOREIGN KEY (`inventory_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `inv_items_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `inv_items_uom_fk` FOREIGN KEY (`uom_id`) REFERENCES `unit_of_measurements` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `inventory_stocks`
-- ----------------------------
DROP TABLE IF EXISTS `inventory_stocks`;
CREATE TABLE `inventory_stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint unsigned NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `qty_on_hand` decimal(15,2) NOT NULL DEFAULT '0.00',
  `qty_reserved` decimal(15,2) NOT NULL DEFAULT '0.00',
  `qty_available` decimal(15,2) GENERATED ALWAYS AS ((`qty_on_hand` - `qty_reserved`)) STORED,
  `avg_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `last_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `inv_stocks_wh_item_unique` (`warehouse_id`,`item_id`) USING BTREE,
  KEY `inv_stocks_item_index` (`item_id`) USING BTREE,
  CONSTRAINT `inv_stocks_item_fk` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `inv_stocks_wh_fk` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `purchase_order_items`
-- ----------------------------
DROP TABLE IF EXISTS `purchase_order_items`;
CREATE TABLE `purchase_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint unsigned NOT NULL,
  `purchase_request_item_id` bigint unsigned DEFAULT NULL,
  `account_id` bigint unsigned DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(20,4) NOT NULL DEFAULT '1.0000',
  `unit_price` decimal(20,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(20,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `received_quantity` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `line_number` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `purchase_order_items_po_index` (`purchase_order_id`) USING BTREE,
  KEY `purchase_order_items_request_item_index` (`purchase_request_item_id`) USING BTREE,
  KEY `purchase_order_items_coa_index` (`account_id`) USING BTREE,
  KEY `purchase_order_items_line_number_index` (`line_number`) USING BTREE,
  KEY `purchase_order_items_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `purchase_order_items_created_by_index` (`created_by`) USING BTREE,
  KEY `purchase_order_items_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `purchase_order_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_order_items_purchase_request_item_id_foreign` FOREIGN KEY (`purchase_request_item_id`) REFERENCES `purchase_request_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `purchase_orders`
-- ----------------------------
DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE `purchase_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `purchase_request_id` bigint unsigned DEFAULT NULL,
  `fiscal_period_id` bigint unsigned NOT NULL,
  `currency_id` bigint unsigned NOT NULL,
  `exchange_rate` decimal(20,8) NOT NULL DEFAULT '1.00000000',
  `po_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `po_date` date NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `delivery_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_terms` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(20,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `tax_base` decimal(20,2) NOT NULL DEFAULT '0.00',
  `ppn_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `ppn_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `shipping_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `functional_total` decimal(20,2) NOT NULL DEFAULT '0.00',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sip_lah_order_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sip_lah_order_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `purchase_orders_school_number_unique` (`school_id`,`po_number`) USING BTREE,
  KEY `purchase_orders_school_index` (`school_id`) USING BTREE,
  KEY `purchase_orders_vendor_index` (`vendor_id`) USING BTREE,
  KEY `purchase_orders_request_index` (`purchase_request_id`) USING BTREE,
  KEY `purchase_orders_fiscal_period_index` (`fiscal_period_id`) USING BTREE,
  KEY `purchase_orders_currency_index` (`currency_id`) USING BTREE,
  KEY `purchase_orders_date_index` (`po_date`) USING BTREE,
  KEY `purchase_orders_status_index` (`status`) USING BTREE,
  KEY `purchase_orders_approved_by_index` (`approved_by`) USING BTREE,
  KEY `purchase_orders_siplah_order_index` (`sip_lah_order_id`) USING BTREE,
  KEY `purchase_orders_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `purchase_orders_created_by_index` (`created_by`) USING BTREE,
  KEY `purchase_orders_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `purchase_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_orders_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_orders_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_orders_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_orders_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_orders_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `purchase_request_items`
-- ----------------------------
DROP TABLE IF EXISTS `purchase_request_items`;
CREATE TABLE `purchase_request_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_request_id` bigint unsigned NOT NULL,
  `rkas_item_id` bigint unsigned DEFAULT NULL,
  `account_id` bigint unsigned DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(20,4) NOT NULL DEFAULT '1.0000',
  `estimated_unit_price` decimal(20,2) NOT NULL DEFAULT '0.00',
  `estimated_subtotal` decimal(20,2) NOT NULL DEFAULT '0.00',
  `estimated_total` decimal(20,2) NOT NULL DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `purchase_request_items_request_index` (`purchase_request_id`) USING BTREE,
  KEY `purchase_request_items_rkas_item_index` (`rkas_item_id`) USING BTREE,
  KEY `purchase_request_items_coa_index` (`account_id`) USING BTREE,
  KEY `purchase_request_items_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `purchase_request_items_created_by_index` (`created_by`) USING BTREE,
  KEY `purchase_request_items_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `purchase_request_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_request_items_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_request_items_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `purchase_requests`
-- ----------------------------
DROP TABLE IF EXISTS `purchase_requests`;
CREATE TABLE `purchase_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `rkas_item_id` bigint unsigned DEFAULT NULL,
  `request_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `requested_by` bigint unsigned DEFAULT NULL,
  `requested_at` timestamp NULL DEFAULT NULL,
  `needed_by` date DEFAULT NULL,
  `total_estimated_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `purchase_requests_school_number_unique` (`school_id`,`request_number`) USING BTREE,
  KEY `purchase_requests_school_index` (`school_id`) USING BTREE,
  KEY `purchase_requests_rkas_item_index` (`rkas_item_id`) USING BTREE,
  KEY `purchase_requests_requested_by_index` (`requested_by`) USING BTREE,
  KEY `purchase_requests_approved_by_index` (`approved_by`) USING BTREE,
  KEY `purchase_requests_status_index` (`status`) USING BTREE,
  KEY `purchase_requests_needed_by_index` (`needed_by`) USING BTREE,
  KEY `purchase_requests_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `purchase_requests_created_by_index` (`created_by`) USING BTREE,
  KEY `purchase_requests_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `purchase_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_requests_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_requests_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `room_constraints`
-- ----------------------------
DROP TABLE IF EXISTS `room_constraints`;
CREATE TABLE `room_constraints` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint unsigned NOT NULL,
  `day_of_week` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_slot_start` time DEFAULT NULL,
  `time_slot_end` time DEFAULT NULL,
  `constraint_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `room_constraints_room_index` (`room_id`) USING BTREE,
  KEY `room_constraints_day_index` (`day_of_week`) USING BTREE,
  KEY `room_constraints_type_index` (`constraint_type`) USING BTREE,
  KEY `room_constraints_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `room_constraints_created_by_index` (`created_by`) USING BTREE,
  KEY `room_constraints_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `room_constraints_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `timetable_rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `rooms`
-- ----------------------------
DROP TABLE IF EXISTS `rooms`;
CREATE TABLE `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `floor_id` bigint unsigned NOT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `rooms_floor_code_unique` (`floor_id`,`code`) USING BTREE,
  KEY `rooms_floor_id_foreign` (`floor_id`) USING BTREE,
  KEY `rooms_is_active_index` (`is_active`) USING BTREE,
  KEY `rooms_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `rooms_floor_fk` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `stock_adjustment_items`
-- ----------------------------
DROP TABLE IF EXISTS `stock_adjustment_items`;
CREATE TABLE `stock_adjustment_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stock_adjustment_id` bigint unsigned NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `system_qty` decimal(15,2) NOT NULL DEFAULT '0.00',
  `actual_qty` decimal(15,2) NOT NULL DEFAULT '0.00',
  `difference_qty` decimal(15,2) GENERATED ALWAYS AS ((`actual_qty` - `system_qty`)) STORED,
  `unit_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_cost_diff` decimal(15,2) GENERATED ALWAYS AS (((`actual_qty` - `system_qty`) * `unit_cost`)) STORED,
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `stock_adj_item_adj_index` (`stock_adjustment_id`) USING BTREE,
  KEY `stock_adj_item_item_index` (`item_id`) USING BTREE,
  CONSTRAINT `stock_adj_items_adj_fk` FOREIGN KEY (`stock_adjustment_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `stock_adj_items_item_fk` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `stock_adjustments`
-- ----------------------------
DROP TABLE IF EXISTS `stock_adjustments`;
CREATE TABLE `stock_adjustments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `warehouse_id` bigint unsigned NOT NULL,
  `adjustment_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `adjustment_date` date NOT NULL,
  `reason` enum('stock_opname','damage','expired','loss','correction','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'stock_opname',
  `status` enum('draft','submitted','approved','rejected','posted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `stock_adj_num_unique` (`organization_id`,`adjustment_number`) USING BTREE,
  KEY `stock_adj_wh_index` (`warehouse_id`) USING BTREE,
  KEY `stock_adj_journal_index` (`journal_entry_id`) USING BTREE,
  KEY `stock_adj_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `stock_adj_approver_fk` (`approved_by`) USING BTREE,
  CONSTRAINT `stock_adj_approver_fk` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `stock_adj_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `stock_adj_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `stock_adj_wh_fk` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `stock_movements`
-- ----------------------------
DROP TABLE IF EXISTS `stock_movements`;
CREATE TABLE `stock_movements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `warehouse_id` bigint unsigned NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `movement_type` enum('in_purchase','in_transfer','in_adjustment','in_return','out_sale','out_usage','out_transfer','out_adjustment','out_disposal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `reference_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` decimal(15,2) NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_after` decimal(15,2) NOT NULL DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `movement_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `stock_mov_org_index` (`organization_id`) USING BTREE,
  KEY `stock_mov_wh_index` (`warehouse_id`) USING BTREE,
  KEY `stock_mov_item_index` (`item_id`) USING BTREE,
  KEY `stock_mov_ref_index` (`reference_type`,`reference_id`) USING BTREE,
  KEY `stock_mov_date_index` (`movement_date`) USING BTREE,
  KEY `stock_mov_created_by_fk` (`created_by`) USING BTREE,
  CONSTRAINT `stock_mov_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `stock_mov_item_fk` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `stock_mov_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `stock_mov_wh_fk` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `stock_transfer_items`
-- ----------------------------
DROP TABLE IF EXISTS `stock_transfer_items`;
CREATE TABLE `stock_transfer_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stock_transfer_id` bigint unsigned NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `qty_requested` decimal(15,2) NOT NULL,
  `qty_sent` decimal(15,2) NOT NULL DEFAULT '0.00',
  `qty_received` decimal(15,2) NOT NULL DEFAULT '0.00',
  `unit_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `stock_trf_item_trf_index` (`stock_transfer_id`) USING BTREE,
  KEY `stock_trf_item_item_index` (`item_id`) USING BTREE,
  CONSTRAINT `stock_trf_items_item_fk` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `stock_trf_items_trf_fk` FOREIGN KEY (`stock_transfer_id`) REFERENCES `stock_transfers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `stock_transfers`
-- ----------------------------
DROP TABLE IF EXISTS `stock_transfers`;
CREATE TABLE `stock_transfers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `transfer_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_warehouse_id` bigint unsigned NOT NULL,
  `to_warehouse_id` bigint unsigned NOT NULL,
  `transfer_date` date NOT NULL,
  `status` enum('draft','in_transit','received','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `stock_trf_num_unique` (`organization_id`,`transfer_number`) USING BTREE,
  KEY `stock_trf_from_index` (`from_warehouse_id`) USING BTREE,
  KEY `stock_trf_to_index` (`to_warehouse_id`) USING BTREE,
  KEY `stock_trf_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `stock_trf_from_fk` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `stock_trf_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `stock_trf_to_fk` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `unit_of_measurements`
-- ----------------------------
DROP TABLE IF EXISTS `unit_of_measurements`;
CREATE TABLE `unit_of_measurements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uom_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `uom_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `uom_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `uom_conversions`
-- ----------------------------
DROP TABLE IF EXISTS `uom_conversions`;
CREATE TABLE `uom_conversions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `from_uom_id` bigint unsigned NOT NULL,
  `to_uom_id` bigint unsigned NOT NULL,
  `multiplier` decimal(15,6) NOT NULL DEFAULT '1.000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uom_conv_unique` (`organization_id`,`from_uom_id`,`to_uom_id`) USING BTREE,
  KEY `uom_conv_to_index` (`to_uom_id`) USING BTREE,
  KEY `uom_conv_from_fk` (`from_uom_id`) USING BTREE,
  CONSTRAINT `uom_conv_from_fk` FOREIGN KEY (`from_uom_id`) REFERENCES `unit_of_measurements` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `uom_conv_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `uom_conv_to_fk` FOREIGN KEY (`to_uom_id`) REFERENCES `unit_of_measurements` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `vendors`
-- ----------------------------
DROP TABLE IF EXISTS `vendors`;
CREATE TABLE `vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `legal_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'COMPANY',
  `npwp` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_pkp` tinyint(1) NOT NULL DEFAULT '0',
  `pkp_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ppn_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `contact_person` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms_days` int NOT NULL DEFAULT '30',
  `payable_account_id` bigint unsigned NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `vendors_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `vendors_school_index` (`school_id`) USING BTREE,
  KEY `vendors_name_index` (`name`) USING BTREE,
  KEY `vendors_npwp_index` (`npwp`) USING BTREE,
  KEY `vendors_is_pkp_index` (`is_pkp`) USING BTREE,
  KEY `vendors_vendor_type_index` (`vendor_type`) USING BTREE,
  KEY `vendors_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `vendors_created_by_index` (`created_by`) USING BTREE,
  KEY `vendors_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `vendors_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `warehouses`
-- ----------------------------
DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE `warehouses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('central','unit','laboratory','workshop','transit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unit',
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `manager_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `warehouses_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `warehouses_school_id_index` (`school_id`) USING BTREE,
  KEY `warehouses_manager_id_index` (`manager_id`) USING BTREE,
  KEY `warehouses_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `warehouses_manager_fk` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `warehouses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `warehouses_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
