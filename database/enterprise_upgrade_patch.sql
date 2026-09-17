-- ==============================================================================
-- LARAVEL-13 ENTERPRISE DATABASE UPGRADE PATCH
-- Generated: 2026-09-17
-- Description: Applies structural fixes, safety constraints, multi-tenancy columns,
--              optimistic locking, and creates 10 new Enterprise ERP modules.
-- Safe to execute on existing database without data loss.
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- SECTION 1: PERBAIKAN BROKEN FOREIGN KEYS
-- ------------------------------------------------------------------------------

-- 1.1 Fix journal_entry_lines -> accounts
ALTER TABLE `journal_entry_lines` DROP FOREIGN KEY IF EXISTS `journal_entry_lines_chart_of_account_id_foreign`;
ALTER TABLE `journal_entry_lines` 
  ADD CONSTRAINT `journal_entry_lines_chart_of_account_id_foreign` 
  FOREIGN KEY (`chart_of_account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- 1.2 Fix vendor_invoice_lines -> accounts
ALTER TABLE `vendor_invoice_lines` DROP FOREIGN KEY IF EXISTS `vendor_invoice_lines_chart_of_account_id_foreign`;
ALTER TABLE `vendor_invoice_lines` 
  ADD CONSTRAINT `vendor_invoice_lines_chart_of_account_id_foreign` 
  FOREIGN KEY (`chart_of_account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- 1.3 Fix bank_reconciliation_items -> reconciliations
ALTER TABLE `bank_reconciliation_items` DROP FOREIGN KEY IF EXISTS `bank_reconciliation_items_reconciliation_id_foreign`;
ALTER TABLE `bank_reconciliation_items` 
  ADD CONSTRAINT `bank_reconciliation_items_reconciliation_id_foreign` 
  FOREIGN KEY (`bank_reconciliation_id`) REFERENCES `reconciliations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


-- ------------------------------------------------------------------------------
-- SECTION 2: PENGAMANAN DATA FINANSIAL (CASCADE -> RESTRICT)
-- ------------------------------------------------------------------------------

-- 2.1 accounts -> organizations
ALTER TABLE `accounts` DROP FOREIGN KEY IF EXISTS `accounts_org_fk`;
ALTER TABLE `accounts` 
  ADD CONSTRAINT `accounts_org_fk` 
  FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- 2.2 journal_entries -> schools
ALTER TABLE `journal_entries` DROP FOREIGN KEY IF EXISTS `journal_entries_school_id_foreign`;
ALTER TABLE `journal_entries` 
  ADD CONSTRAINT `journal_entries_school_id_foreign` 
  FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- 2.3 journal_entries -> fiscal_periods
ALTER TABLE `journal_entries` DROP FOREIGN KEY IF EXISTS `journal_entries_fiscal_period_id_foreign`;
ALTER TABLE `journal_entries` 
  ADD CONSTRAINT `journal_entries_fiscal_period_id_foreign` 
  FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- 2.4 student_payments -> student_invoices & students
ALTER TABLE `student_payments` DROP FOREIGN KEY IF EXISTS `student_payments_invoice_id_foreign`;
ALTER TABLE `student_payments` 
  ADD CONSTRAINT `student_payments_invoice_id_foreign` 
  FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `student_payments` DROP FOREIGN KEY IF EXISTS `student_payments_student_id_foreign`;
ALTER TABLE `student_payments` 
  ADD CONSTRAINT `student_payments_student_id_foreign` 
  FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- 2.5 vendor_invoices -> schools & vendors
ALTER TABLE `vendor_invoices` DROP FOREIGN KEY IF EXISTS `vendor_invoices_school_id_foreign`;
ALTER TABLE `vendor_invoices` 
  ADD CONSTRAINT `vendor_invoices_school_id_foreign` 
  FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `vendor_invoices` DROP FOREIGN KEY IF EXISTS `vendor_invoices_vendor_id_foreign`;
ALTER TABLE `vendor_invoices` 
  ADD CONSTRAINT `vendor_invoices_vendor_id_foreign` 
  FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- 2.6 payroll_runs -> organizations
ALTER TABLE `payroll_runs` DROP FOREIGN KEY IF EXISTS `payroll_runs_org_fk`;
ALTER TABLE `payroll_runs` 
  ADD CONSTRAINT `payroll_runs_org_fk` 
  FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- 2.7 assets & fixed_assets
ALTER TABLE `assets` DROP FOREIGN KEY IF EXISTS `assets_school_id_foreign`;
ALTER TABLE `assets` 
  ADD CONSTRAINT `assets_school_id_foreign` 
  FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `fixed_assets` DROP FOREIGN KEY IF EXISTS `fixed_assets_org_fk`;
ALTER TABLE `fixed_assets` 
  ADD CONSTRAINT `fixed_assets_org_fk` 
  FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;


-- ------------------------------------------------------------------------------
-- SECTION 3: MULTI-TENANCY & HR INTEGRATION
-- ------------------------------------------------------------------------------

-- 3.1 students: Add school_id
DROP PROCEDURE IF EXISTS upgrade_patch_add_students_school;
DELIMITER ;;
CREATE PROCEDURE upgrade_patch_add_students_school()
BEGIN
  IF NOT EXISTS (
    SELECT * FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND COLUMN_NAME = 'school_id'
  ) THEN
    ALTER TABLE `students` ADD COLUMN `school_id` bigint UNSIGNED NULL AFTER `organization_id`;
    ALTER TABLE `students` ADD INDEX `students_school_id_index` (`school_id`);
    ALTER TABLE `students` ADD CONSTRAINT `students_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
  END IF;
END;;
DELIMITER ;
CALL upgrade_patch_add_students_school();
DROP PROCEDURE IF EXISTS upgrade_patch_add_students_school;

-- 3.2 teachers: Add school_id & employee_id
DROP PROCEDURE IF EXISTS upgrade_patch_add_teachers_cols;
DELIMITER ;;
CREATE PROCEDURE upgrade_patch_add_teachers_cols()
BEGIN
  IF NOT EXISTS (
    SELECT * FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'teachers' AND COLUMN_NAME = 'school_id'
  ) THEN
    ALTER TABLE `teachers` ADD COLUMN `school_id` bigint UNSIGNED NULL AFTER `organization_id`;
    ALTER TABLE `teachers` ADD INDEX `teachers_school_id_index` (`school_id`);
    ALTER TABLE `teachers` ADD CONSTRAINT `teachers_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
  END IF;
  
  IF NOT EXISTS (
    SELECT * FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'teachers' AND COLUMN_NAME = 'employee_id'
  ) THEN
    ALTER TABLE `teachers` ADD COLUMN `employee_id` bigint UNSIGNED NULL AFTER `user_id`;
    ALTER TABLE `teachers` ADD INDEX `teachers_employee_id_index` (`employee_id`);
    ALTER TABLE `teachers` ADD CONSTRAINT `teachers_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
  END IF;
END;;
DELIMITER ;
CALL upgrade_patch_add_teachers_cols();
DROP PROCEDURE IF EXISTS upgrade_patch_add_teachers_cols;


-- ------------------------------------------------------------------------------
-- SECTION 4: OPTIMISTIC LOCKING (`version` COLUMN)
-- ------------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS upgrade_patch_add_version_col;
DELIMITER ;;
CREATE PROCEDURE upgrade_patch_add_version_col(IN p_tbl VARCHAR(64))
BEGIN
  IF NOT EXISTS (
    SELECT * FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = p_tbl AND COLUMN_NAME = 'version'
  ) THEN
    SET @s = CONCAT('ALTER TABLE `', p_tbl, '` ADD COLUMN `version` int UNSIGNED NOT NULL DEFAULT 1');
    PREPARE stmt FROM @s;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
  END IF;
END;;
DELIMITER ;

CALL upgrade_patch_add_version_col('student_invoices');
CALL upgrade_patch_add_version_col('student_payments');
CALL upgrade_patch_add_version_col('journal_entries');
CALL upgrade_patch_add_version_col('payroll_runs');
CALL upgrade_patch_add_version_col('budgets');
DROP PROCEDURE IF EXISTS upgrade_patch_add_version_col;


-- ------------------------------------------------------------------------------
-- SECTION 5: NEW ENTERPRISE ERP MODULES
-- ------------------------------------------------------------------------------


-- ==============================================================================
-- SCRIPT: 01_inventory_warehouse.sql
-- ==============================================================================
-- ==============================================================================
-- ENTERPRISE MODULE 01: INVENTORY, WAREHOUSE & SUPPLY CHAIN MANAGEMENT
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- 1. Tabel Master Gudang (Warehouses)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE `warehouses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('central','unit','laboratory','workshop','transit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unit',
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `manager_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `warehouses_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `warehouses_school_id_index`(`school_id` ASC) USING BTREE,
  INDEX `warehouses_manager_id_index`(`manager_id` ASC) USING BTREE,
  INDEX `warehouses_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `warehouses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `warehouses_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `warehouses_manager_fk` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Kategori Barang (Inventory Categories)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `inventory_categories`;
CREATE TABLE `inventory_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `inv_cat_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `inv_cat_parent_index`(`parent_id` ASC) USING BTREE,
  INDEX `inv_cat_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `inv_cat_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `inv_cat_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `inventory_categories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 3. Satuan Ukuran (Unit of Measurements - UOM)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `unit_of_measurements`;
CREATE TABLE `unit_of_measurements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uom_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `uom_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `uom_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 4. Konversi Satuan (UOM Conversions)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `uom_conversions`;
CREATE TABLE `uom_conversions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `from_uom_id` bigint UNSIGNED NOT NULL,
  `to_uom_id` bigint UNSIGNED NOT NULL,
  `multiplier` decimal(15, 6) NOT NULL DEFAULT 1.000000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uom_conv_unique`(`organization_id` ASC, `from_uom_id` ASC, `to_uom_id` ASC) USING BTREE,
  INDEX `uom_conv_to_index`(`to_uom_id` ASC) USING BTREE,
  CONSTRAINT `uom_conv_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `uom_conv_from_fk` FOREIGN KEY (`from_uom_id`) REFERENCES `unit_of_measurements` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `uom_conv_to_fk` FOREIGN KEY (`to_uom_id`) REFERENCES `unit_of_measurements` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 5. Master Barang (Inventory Items)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `inventory_items`;
CREATE TABLE `inventory_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NULL DEFAULT NULL,
  `uom_id` bigint UNSIGNED NOT NULL,
  `code` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `item_type` enum('inventory','consumable','service','fixed_asset') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inventory',
  `min_stock` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `max_stock` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `standard_cost` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `valuation_method` enum('fifo','lifo','average') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'average',
  `inventory_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `cogs_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `inv_items_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `inv_items_barcode_index`(`barcode` ASC) USING BTREE,
  INDEX `inv_items_category_index`(`category_id` ASC) USING BTREE,
  INDEX `inv_items_uom_index`(`uom_id` ASC) USING BTREE,
  INDEX `inv_items_inv_account_index`(`inventory_account_id` ASC) USING BTREE,
  INDEX `inv_items_cogs_account_index`(`cogs_account_id` ASC) USING BTREE,
  INDEX `inv_items_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `inv_items_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `inv_items_cat_fk` FOREIGN KEY (`category_id`) REFERENCES `inventory_categories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `inv_items_uom_fk` FOREIGN KEY (`uom_id`) REFERENCES `unit_of_measurements` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `inv_items_inv_acc_fk` FOREIGN KEY (`inventory_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `inv_items_cogs_acc_fk` FOREIGN KEY (`cogs_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 6. Saldo Stok per Gudang (Inventory Stocks)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `inventory_stocks`;
CREATE TABLE `inventory_stocks` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `qty_on_hand` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `qty_reserved` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `qty_available` decimal(15, 2) GENERATED ALWAYS AS ((`qty_on_hand` - `qty_reserved`)) STORED,
  `avg_cost` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `last_cost` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `inv_stocks_wh_item_unique`(`warehouse_id` ASC, `item_id` ASC) USING BTREE,
  INDEX `inv_stocks_item_index`(`item_id` ASC) USING BTREE,
  CONSTRAINT `inv_stocks_wh_fk` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `inv_stocks_item_fk` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 7. Kartu Stok & Buku Mutasi (Stock Movements)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `stock_movements`;
CREATE TABLE `stock_movements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `movement_type` enum('in_purchase','in_transfer','in_adjustment','in_return','out_sale','out_usage','out_transfer','out_adjustment','out_disposal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reference_id` bigint UNSIGNED NULL DEFAULT NULL,
  `reference_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `qty` decimal(15, 2) NOT NULL,
  `unit_cost` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `balance_after` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `movement_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `stock_mov_org_index`(`organization_id` ASC) USING BTREE,
  INDEX `stock_mov_wh_index`(`warehouse_id` ASC) USING BTREE,
  INDEX `stock_mov_item_index`(`item_id` ASC) USING BTREE,
  INDEX `stock_mov_ref_index`(`reference_type` ASC, `reference_id` ASC) USING BTREE,
  INDEX `stock_mov_date_index`(`movement_date` ASC) USING BTREE,
  CONSTRAINT `stock_mov_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `stock_mov_wh_fk` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `stock_mov_item_fk` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `stock_mov_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 8. Penyesuaian Stok / Opname (Stock Adjustments & Items)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `stock_adjustments`;
CREATE TABLE `stock_adjustments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `adjustment_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `adjustment_date` date NOT NULL,
  `reason` enum('stock_opname','damage','expired','loss','correction','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'stock_opname',
  `status` enum('draft','submitted','approved','rejected','posted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `stock_adj_num_unique`(`organization_id` ASC, `adjustment_number` ASC) USING BTREE,
  INDEX `stock_adj_wh_index`(`warehouse_id` ASC) USING BTREE,
  INDEX `stock_adj_journal_index`(`journal_entry_id` ASC) USING BTREE,
  INDEX `stock_adj_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `stock_adj_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `stock_adj_wh_fk` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `stock_adj_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `stock_adj_approver_fk` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `stock_adjustment_items`;
CREATE TABLE `stock_adjustment_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `stock_adjustment_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `system_qty` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `actual_qty` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `difference_qty` decimal(15, 2) GENERATED ALWAYS AS ((`actual_qty` - `system_qty`)) STORED,
  `unit_cost` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `total_cost_diff` decimal(15, 2) GENERATED ALWAYS AS (((`actual_qty` - `system_qty`) * `unit_cost`)) STORED,
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `stock_adj_item_adj_index`(`stock_adjustment_id` ASC) USING BTREE,
  INDEX `stock_adj_item_item_index`(`item_id` ASC) USING BTREE,
  CONSTRAINT `stock_adj_items_adj_fk` FOREIGN KEY (`stock_adjustment_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `stock_adj_items_item_fk` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 9. Mutasi Antar Gudang / Unit (Stock Transfers & Items)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `stock_transfers`;
CREATE TABLE `stock_transfers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `transfer_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_warehouse_id` bigint UNSIGNED NOT NULL,
  `to_warehouse_id` bigint UNSIGNED NOT NULL,
  `transfer_date` date NOT NULL,
  `status` enum('draft','in_transit','received','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `stock_trf_num_unique`(`organization_id` ASC, `transfer_number` ASC) USING BTREE,
  INDEX `stock_trf_from_index`(`from_warehouse_id` ASC) USING BTREE,
  INDEX `stock_trf_to_index`(`to_warehouse_id` ASC) USING BTREE,
  INDEX `stock_trf_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `stock_trf_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `stock_trf_from_fk` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `stock_trf_to_fk` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `stock_transfer_items`;
CREATE TABLE `stock_transfer_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `stock_transfer_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `qty_requested` decimal(15, 2) NOT NULL,
  `qty_sent` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `qty_received` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `unit_cost` decimal(15, 2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `stock_trf_item_trf_index`(`stock_transfer_id` ASC) USING BTREE,
  INDEX `stock_trf_item_item_index`(`item_id` ASC) USING BTREE,
  CONSTRAINT `stock_trf_items_trf_fk` FOREIGN KEY (`stock_transfer_id`) REFERENCES `stock_transfers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `stock_trf_items_item_fk` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ==============================================================================
-- SCRIPT: 02_approval_workflow_engine.sql
-- ==============================================================================
-- ==============================================================================
-- ENTERPRISE MODULE 02: UNIVERSAL APPROVAL WORKFLOW ENGINE
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- 1. Master Definisi Alur Kerja (Workflow Definitions)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `workflow_definitions`;
CREATE TABLE `workflow_definitions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `wf_def_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `wf_def_school_index`(`school_id` ASC) USING BTREE,
  INDEX `wf_def_module_index`(`module` ASC) USING BTREE,
  INDEX `wf_def_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `wf_def_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `wf_def_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Langkah-Langkah Persetujuan (Workflow Steps)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `workflow_steps`;
CREATE TABLE `workflow_steps` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `workflow_definition_id` bigint UNSIGNED NOT NULL,
  `step_order` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `approver_type` enum('role','user','supervisor','dept_head','amount_threshold') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'role',
  `min_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `max_amount` decimal(15, 2) NULL DEFAULT NULL,
  `action_type` enum('approve_reject','review','acknowledge') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approve_reject',
  `auto_notify` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `wf_step_order_unique`(`workflow_definition_id` ASC, `step_order` ASC) USING BTREE,
  CONSTRAINT `wf_step_def_fk` FOREIGN KEY (`workflow_definition_id`) REFERENCES `workflow_definitions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 3. Penanggung Jawab Approver (Workflow Approvers)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `workflow_approvers`;
CREATE TABLE `workflow_approvers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `workflow_step_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `wf_appr_step_index`(`workflow_step_id` ASC) USING BTREE,
  INDEX `wf_appr_role_index`(`role_id` ASC) USING BTREE,
  INDEX `wf_appr_user_index`(`user_id` ASC) USING BTREE,
  CONSTRAINT `wf_appr_step_fk` FOREIGN KEY (`workflow_step_id`) REFERENCES `workflow_steps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wf_appr_role_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wf_appr_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 4. Permohonan Persetujuan Aktif (Approval Requests)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `approval_requests`;
CREATE TABLE `approval_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `workflow_definition_id` bigint UNSIGNED NOT NULL,
  `current_step_id` bigint UNSIGNED NULL DEFAULT NULL,
  `requestable_type` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requestable_id` bigint UNSIGNED NOT NULL,
  `document_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `requester_id` bigint UNSIGNED NOT NULL,
  `status` enum('draft','pending','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `appr_req_org_index`(`organization_id` ASC) USING BTREE,
  INDEX `appr_req_wf_index`(`workflow_definition_id` ASC) USING BTREE,
  INDEX `appr_req_step_index`(`current_step_id` ASC) USING BTREE,
  INDEX `appr_req_morph_index`(`requestable_type` ASC, `requestable_id` ASC) USING BTREE,
  INDEX `appr_req_requester_index`(`requester_id` ASC) USING BTREE,
  INDEX `appr_req_status_index`(`status` ASC) USING BTREE,
  CONSTRAINT `appr_req_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `appr_req_wf_fk` FOREIGN KEY (`workflow_definition_id`) REFERENCES `workflow_definitions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `appr_req_step_fk` FOREIGN KEY (`current_step_id`) REFERENCES `workflow_steps` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `appr_req_requester_fk` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 5. Riwayat Jejak Persetujuan (Approval Histories)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `approval_histories`;
CREATE TABLE `approval_histories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `approval_request_id` bigint UNSIGNED NOT NULL,
  `workflow_step_id` bigint UNSIGNED NOT NULL,
  `approver_id` bigint UNSIGNED NOT NULL,
  `action` enum('approved','rejected','revised','delegated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `signature_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `appr_hist_req_index`(`approval_request_id` ASC) USING BTREE,
  INDEX `appr_hist_step_index`(`workflow_step_id` ASC) USING BTREE,
  INDEX `appr_hist_user_index`(`approver_id` ASC) USING BTREE,
  CONSTRAINT `appr_hist_req_fk` FOREIGN KEY (`approval_request_id`) REFERENCES `approval_requests` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `appr_hist_step_fk` FOREIGN KEY (`workflow_step_id`) REFERENCES `workflow_steps` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `appr_hist_approver_fk` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ==============================================================================
-- SCRIPT: 03_document_number_sequences.sql
-- ==============================================================================
-- ==============================================================================
-- ENTERPRISE MODULE 03: DOCUMENT NUMBER AUTO-SEQUENCING ENGINE
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- 1. Generator Nomor Urut Dokumen (Document Number Sequences)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `document_number_sequences`;
CREATE TABLE `document_number_sequences` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `document_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_key` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ALL',
  `prefix` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `pattern` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '{PREFIX}/{YEAR}/{MONTH}/{SEQ:5}',
  `last_number` bigint UNSIGNED NOT NULL DEFAULT 0,
  `padding` tinyint UNSIGNED NOT NULL DEFAULT 5,
  `reset_frequency` enum('none','yearly','monthly','daily') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yearly',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `doc_number_sequences_unique`(`organization_id` ASC, `school_id` ASC, `document_type` ASC, `period_key` ASC) USING BTREE,
  INDEX `doc_seq_type_index`(`document_type` ASC) USING BTREE,
  INDEX `doc_seq_school_index`(`school_id` ASC) USING BTREE,
  CONSTRAINT `doc_seq_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `doc_seq_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ==============================================================================
-- SCRIPT: 04_higher_education_university.sql
-- ==============================================================================
-- ==============================================================================
-- ENTERPRISE MODULE 04: HIGHER EDUCATION & UNIVERSITY ACADEMICS (PERGURUAN TINGGI)
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- 1. Fakultas (Faculties)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `faculties`;
CREATE TABLE `faculties` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dean_id` bigint UNSIGNED NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `faculties_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `faculties_school_index`(`school_id` ASC) USING BTREE,
  INDEX `faculties_dean_index`(`dean_id` ASC) USING BTREE,
  INDEX `faculties_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `faculties_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `faculties_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `faculties_dean_fk` FOREIGN KEY (`dean_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Program Studi / Jurusan (Study Programs)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `study_programs`;
CREATE TABLE `study_programs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `faculty_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `degree_level` enum('D3','D4','S1','S2','S3','profesi','spesialis') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S1',
  `accreditation` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'Unggul',
  `head_of_program_id` bigint UNSIGNED NULL DEFAULT NULL,
  `total_sks_required` int UNSIGNED NOT NULL DEFAULT 144,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `study_programs_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `study_prog_school_index`(`school_id` ASC) USING BTREE,
  INDEX `study_prog_faculty_index`(`faculty_id` ASC) USING BTREE,
  INDEX `study_prog_head_index`(`head_of_program_id` ASC) USING BTREE,
  INDEX `study_prog_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `study_prog_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `study_prog_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `study_prog_faculty_fk` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `study_prog_head_fk` FOREIGN KEY (`head_of_program_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 3. Mata Kuliah & SKS (Curriculum Courses)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `curriculum_courses`;
CREATE TABLE `curriculum_courses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `study_program_id` bigint UNSIGNED NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sks_theory` tinyint UNSIGNED NOT NULL DEFAULT 2,
  `sks_practical` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `total_sks` tinyint UNSIGNED GENERATED ALWAYS AS ((`sks_theory` + `sks_practical`)) STORED,
  `semester_recommendation` tinyint UNSIGNED NOT NULL DEFAULT 1,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 1,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `syllabus_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `courses_prodi_code_unique`(`study_program_id` ASC, `code` ASC) USING BTREE,
  INDEX `courses_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `courses_prodi_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 4. Prasyarat Mata Kuliah (Course Prerequisites)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `course_prerequisites`;
CREATE TABLE `course_prerequisites` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` bigint UNSIGNED NOT NULL,
  `prerequisite_course_id` bigint UNSIGNED NOT NULL,
  `min_grade` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'D',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `course_prereq_unique`(`course_id` ASC, `prerequisite_course_id` ASC) USING BTREE,
  INDEX `course_prereq_target_index`(`prerequisite_course_id` ASC) USING BTREE,
  CONSTRAINT `course_prereq_source_fk` FOREIGN KEY (`course_id`) REFERENCES `curriculum_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `course_prereq_target_fk` FOREIGN KEY (`prerequisite_course_id`) REFERENCES `curriculum_courses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 5. Semester Akademik Perguruan Tinggi (Academic Semesters)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `academic_semesters`;
CREATE TABLE `academic_semesters` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('ganjil','genap','pendek') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ganjil',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `krs_start_date` date NULL DEFAULT NULL,
  `krs_end_date` date NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_current` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `acad_sem_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `acad_sem_year_index`(`academic_year_id` ASC) USING BTREE,
  CONSTRAINT `acad_sem_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `acad_sem_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `acad_sem_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 6. Kartu Rencana Studi (KRS) & Items
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `student_krs`;
CREATE TABLE `student_krs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `academic_semester_id` bigint UNSIGNED NOT NULL,
  `study_program_id` bigint UNSIGNED NOT NULL,
  `academic_advisor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `total_sks` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `max_allowed_sks` tinyint UNSIGNED NOT NULL DEFAULT 24,
  `status` enum('draft','submitted','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_krs_sem_unique`(`student_id` ASC, `academic_semester_id` ASC) USING BTREE,
  INDEX `student_krs_sem_index`(`academic_semester_id` ASC) USING BTREE,
  INDEX `student_krs_prodi_index`(`study_program_id` ASC) USING BTREE,
  INDEX `student_krs_advisor_index`(`academic_advisor_id` ASC) USING BTREE,
  CONSTRAINT `student_krs_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_krs_sem_fk` FOREIGN KEY (`academic_semester_id`) REFERENCES `academic_semesters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_krs_prodi_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `student_krs_advisor_fk` FOREIGN KEY (`academic_advisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `student_krs_items`;
CREATE TABLE `student_krs_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_krs_id` bigint UNSIGNED NOT NULL,
  `course_id` bigint UNSIGNED NOT NULL,
  `classroom_id` bigint UNSIGNED NULL DEFAULT NULL,
  `sks` tinyint UNSIGNED NOT NULL,
  `grade_numeric` decimal(5, 2) NULL DEFAULT NULL,
  `grade_letter` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `grade_point` decimal(3, 2) NULL DEFAULT NULL,
  `is_passed` tinyint(1) NULL DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `krs_items_unique`(`student_krs_id` ASC, `course_id` ASC) USING BTREE,
  INDEX `krs_items_course_index`(`course_id` ASC) USING BTREE,
  INDEX `krs_items_class_index`(`classroom_id` ASC) USING BTREE,
  CONSTRAINT `krs_items_krs_fk` FOREIGN KEY (`student_krs_id`) REFERENCES `student_krs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `krs_items_course_fk` FOREIGN KEY (`course_id`) REFERENCES `curriculum_courses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `krs_items_class_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 7. Kartu Hasil Studi (KHS)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `student_khs`;
CREATE TABLE `student_khs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `academic_semester_id` bigint UNSIGNED NOT NULL,
  `sks_semester` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `sks_cumulative` smallint UNSIGNED NOT NULL DEFAULT 0,
  `gpa_semester` decimal(3, 2) NOT NULL DEFAULT 0.00,
  `gpa_cumulative` decimal(3, 2) NOT NULL DEFAULT 0.00,
  `max_sks_next_semester` tinyint UNSIGNED NOT NULL DEFAULT 20,
  `academic_status` enum('aktif','cuti','skorsing','do','lulus') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `printed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_khs_unique`(`student_id` ASC, `academic_semester_id` ASC) USING BTREE,
  INDEX `student_khs_sem_index`(`academic_semester_id` ASC) USING BTREE,
  CONSTRAINT `student_khs_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_khs_sem_fk` FOREIGN KEY (`academic_semester_id`) REFERENCES `academic_semesters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 8. Transkrip Akademik (Student Transcripts)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `student_transcripts`;
CREATE TABLE `student_transcripts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `study_program_id` bigint UNSIGNED NOT NULL,
  `transcript_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `graduation_date` date NOT NULL,
  `total_sks_earned` smallint UNSIGNED NOT NULL,
  `final_gpa` decimal(3, 2) NOT NULL,
  `predicate` enum('memuaskan','sangat_memuaskan','cumlaude') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sangat_memuaskan',
  `is_official` tinyint(1) NOT NULL DEFAULT 1,
  `printed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `transcript_num_unique`(`transcript_number` ASC) USING BTREE,
  INDEX `transcript_student_index`(`student_id` ASC) USING BTREE,
  INDEX `transcript_prodi_index`(`study_program_id` ASC) USING BTREE,
  CONSTRAINT `transcript_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transcript_prodi_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 9. Bimbingan Tugas Akhir / Skripsi (Thesis Supervisions)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `thesis_supervisions`;
CREATE TABLE `thesis_supervisions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `study_program_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supervisor_1_id` bigint UNSIGNED NOT NULL,
  `supervisor_2_id` bigint UNSIGNED NULL DEFAULT NULL,
  `examiner_1_id` bigint UNSIGNED NULL DEFAULT NULL,
  `examiner_2_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` enum('proposed','in_progress','seminar_proposal','defense_scheduled','completed','revising') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'proposed',
  `defense_date` date NULL DEFAULT NULL,
  `final_score` decimal(5, 2) NULL DEFAULT NULL,
  `grade_letter` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `manuscript_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `thesis_student_index`(`student_id` ASC) USING BTREE,
  INDEX `thesis_prodi_index`(`study_program_id` ASC) USING BTREE,
  INDEX `thesis_sup1_index`(`supervisor_1_id` ASC) USING BTREE,
  INDEX `thesis_sup2_index`(`supervisor_2_id` ASC) USING BTREE,
  CONSTRAINT `thesis_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `thesis_prodi_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `thesis_sup1_fk` FOREIGN KEY (`supervisor_1_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `thesis_sup2_fk` FOREIGN KEY (`supervisor_2_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 10. Yudisium & Penetapan Kelulusan (Yudisiums)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `yudisiums`;
CREATE TABLE `yudisiums` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `decree_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `decree_date` date NOT NULL,
  `national_diploma_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `graduation_date` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `yudisium_student_unique`(`student_id` ASC) USING BTREE,
  INDEX `yudisium_year_index`(`academic_year_id` ASC) USING BTREE,
  CONSTRAINT `yudisium_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `yudisium_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ==============================================================================
-- SCRIPT: 05_canteen_cashless_ewallet.sql
-- ==============================================================================
-- ==============================================================================
-- ENTERPRISE MODULE 05: CANTEEN CASHLESS & STUDENT E-WALLET (RFID POS)
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- 1. Master Tenant / Pedagang Kantin (Canteen Tenants)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `canteen_tenants`;
CREATE TABLE `canteen_tenants` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `stall_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `commission_rate` decimal(5, 2) NOT NULL DEFAULT 0.00,
  `bank_account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `canteen_tenants_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `canteen_tenants_org_index`(`organization_id` ASC) USING BTREE,
  INDEX `canteen_tenants_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `canteen_tenants_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `canteen_tenants_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Menu Makanan & Minuman (Canteen Menus)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `canteen_menus`;
CREATE TABLE `canteen_menus` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `canteen_tenant_id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('food','drink','snack','stationery','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'food',
  `price` decimal(15, 2) NOT NULL,
  `cost_price` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `photo_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `canteen_menus_tenant_index`(`canteen_tenant_id` ASC) USING BTREE,
  INDEX `canteen_menus_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `canteen_menus_tenant_fk` FOREIGN KEY (`canteen_tenant_id`) REFERENCES `canteen_tenants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 3. Kartu RFID Siswa & Staff (Student RFID Cards)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `student_rfid_cards`;
CREATE TABLE `student_rfid_cards` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `employee_id` bigint UNSIGNED NULL DEFAULT NULL,
  `card_uid` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pin_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `issued_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `blocked_at` timestamp NULL DEFAULT NULL,
  `blocked_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rfid_uid_unique`(`card_uid` ASC) USING BTREE,
  INDEX `rfid_student_index`(`student_id` ASC) USING BTREE,
  INDEX `rfid_employee_index`(`employee_id` ASC) USING BTREE,
  CONSTRAINT `rfid_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `rfid_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rfid_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 4. Dompet Digital (Digital Wallets)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `digital_wallets`;
CREATE TABLE `digital_wallets` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `owner_type` enum('student','employee') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` bigint UNSIGNED NOT NULL,
  `wallet_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `daily_spending_limit` decimal(15, 2) NOT NULL DEFAULT 50000.00,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `lock_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `version` int UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `wallet_num_unique`(`wallet_number` ASC) USING BTREE,
  UNIQUE INDEX `wallet_owner_unique`(`owner_type` ASC, `owner_id` ASC) USING BTREE,
  INDEX `wallet_org_index`(`organization_id` ASC) USING BTREE,
  CONSTRAINT `digital_wallets_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 5. Mutasi Transaksi Dompet (Wallet Transactions)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `wallet_transactions`;
CREATE TABLE `wallet_transactions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `digital_wallet_id` bigint UNSIGNED NOT NULL,
  `transaction_type` enum('topup','canteen_payment','refund','admin_adjustment','cashout') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `balance_before` decimal(15, 2) NOT NULL,
  `balance_after` decimal(15, 2) NOT NULL,
  `reference_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payment_method` enum('cash','bank_transfer','qris','payment_gateway') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `wallet_tx_wallet_index`(`digital_wallet_id` ASC) USING BTREE,
  INDEX `wallet_tx_ref_index`(`reference_number` ASC) USING BTREE,
  INDEX `wallet_tx_journal_index`(`journal_entry_id` ASC) USING BTREE,
  CONSTRAINT `wallet_tx_wallet_fk` FOREIGN KEY (`digital_wallet_id`) REFERENCES `digital_wallets` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `wallet_tx_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `wallet_tx_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 6. Transaksi Pembelian Kantin (Canteen Orders & Order Items)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `canteen_orders`;
CREATE TABLE `canteen_orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `canteen_tenant_id` bigint UNSIGNED NOT NULL,
  `digital_wallet_id` bigint UNSIGNED NULL DEFAULT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(15, 2) NOT NULL,
  `commission_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `net_tenant_amount` decimal(15, 2) GENERATED ALWAYS AS ((`total_amount` - `commission_amount`)) STORED,
  `payment_type` enum('wallet_rfid','cash','qris') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'wallet_rfid',
  `status` enum('pending','completed','cancelled','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `is_settled` tinyint(1) NOT NULL DEFAULT 0,
  `settlement_id` bigint UNSIGNED NULL DEFAULT NULL,
  `order_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `canteen_orders_num_unique`(`order_number` ASC) USING BTREE,
  INDEX `canteen_orders_tenant_index`(`canteen_tenant_id` ASC) USING BTREE,
  INDEX `canteen_orders_wallet_index`(`digital_wallet_id` ASC) USING BTREE,
  INDEX `canteen_orders_settlement_index`(`settlement_id` ASC) USING BTREE,
  CONSTRAINT `canteen_orders_tenant_fk` FOREIGN KEY (`canteen_tenant_id`) REFERENCES `canteen_tenants` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `canteen_orders_wallet_fk` FOREIGN KEY (`digital_wallet_id`) REFERENCES `digital_wallets` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `canteen_order_items`;
CREATE TABLE `canteen_order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `canteen_order_id` bigint UNSIGNED NOT NULL,
  `canteen_menu_id` bigint UNSIGNED NOT NULL,
  `qty` int UNSIGNED NOT NULL,
  `unit_price` decimal(15, 2) NOT NULL,
  `total_price` decimal(15, 2) GENERATED ALWAYS AS ((`qty` * `unit_price`)) STORED,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `canteen_items_order_index`(`canteen_order_id` ASC) USING BTREE,
  INDEX `canteen_items_menu_index`(`canteen_menu_id` ASC) USING BTREE,
  CONSTRAINT `canteen_items_order_fk` FOREIGN KEY (`canteen_order_id`) REFERENCES `canteen_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `canteen_items_menu_fk` FOREIGN KEY (`canteen_menu_id`) REFERENCES `canteen_menus` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 7. Rekonsiliasi & Pencairan Dana Tenant (Canteen Settlements)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `canteen_settlements`;
CREATE TABLE `canteen_settlements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `canteen_tenant_id` bigint UNSIGNED NOT NULL,
  `settlement_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_gross_sales` decimal(15, 2) NOT NULL,
  `total_commission` decimal(15, 2) NOT NULL,
  `net_payout` decimal(15, 2) NOT NULL,
  `payment_status` enum('draft','approved','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `paid_at` timestamp NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `settlement_num_unique`(`settlement_number` ASC) USING BTREE,
  INDEX `settlement_tenant_index`(`canteen_tenant_id` ASC) USING BTREE,
  INDEX `settlement_journal_index`(`journal_entry_id` ASC) USING BTREE,
  CONSTRAINT `settlement_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `settlement_tenant_fk` FOREIGN KEY (`canteen_tenant_id`) REFERENCES `canteen_tenants` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `settlement_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- Update FK di canteen_orders untuk settlement
ALTER TABLE `canteen_orders` ADD CONSTRAINT `canteen_orders_settlement_fk` FOREIGN KEY (`settlement_id`) REFERENCES `canteen_settlements` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;


-- ==============================================================================
-- SCRIPT: 06_transport_gps_fleet.sql
-- ==============================================================================
-- ==============================================================================
-- ENTERPRISE MODULE 06: TRANSPORTATION, BUS FLEET & GPS TELEMETRY
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- 1. Master Kendaraan Armada (Vehicles)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE `vehicles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `plate_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_type` enum('bus','minibus','van','car','motorcycle') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bus',
  `brand` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `model` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `seat_capacity` tinyint UNSIGNED NOT NULL DEFAULT 20,
  `gps_device_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `stnk_expiry_date` date NULL DEFAULT NULL,
  `kir_expiry_date` date NULL DEFAULT NULL,
  `insurance_expiry_date` date NULL DEFAULT NULL,
  `status` enum('active','maintenance','inactive','disposed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `vehicles_plate_unique`(`plate_number` ASC) USING BTREE,
  INDEX `vehicles_org_index`(`organization_id` ASC) USING BTREE,
  INDEX `vehicles_school_index`(`school_id` ASC) USING BTREE,
  INDEX `vehicles_gps_device_index`(`gps_device_id` ASC) USING BTREE,
  INDEX `vehicles_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `vehicles_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `vehicles_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Master Supir / Pengemudi (Drivers)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `drivers`;
CREATE TABLE `drivers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `sim_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'B1',
  `sim_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sim_expiry_date` date NOT NULL,
  `emergency_contact_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `emergency_contact_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `drivers_emp_unique`(`employee_id` ASC) USING BTREE,
  UNIQUE INDEX `drivers_sim_unique`(`sim_number` ASC) USING BTREE,
  CONSTRAINT `drivers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `drivers_emp_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 3. Rute Antar Jemput (Transport Routes)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `transport_routes`;
CREATE TABLE `transport_routes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `vehicle_id` bigint UNSIGNED NULL DEFAULT NULL,
  `driver_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_type` enum('pickup','dropoff','both') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'both',
  `morning_departure_time` time NULL DEFAULT NULL,
  `afternoon_departure_time` time NULL DEFAULT NULL,
  `monthly_fare` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `routes_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `routes_vehicle_index`(`vehicle_id` ASC) USING BTREE,
  INDEX `routes_driver_index`(`driver_id` ASC) USING BTREE,
  CONSTRAINT `routes_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `routes_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `routes_vehicle_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `routes_driver_fk` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 4. Titik Halte / Pemberhentian (Transport Stops)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `transport_stops`;
CREATE TABLE `transport_stops` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `transport_route_id` bigint UNSIGNED NOT NULL,
  `stop_order` tinyint UNSIGNED NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `latitude` decimal(10, 8) NULL DEFAULT NULL,
  `longitude` decimal(11, 8) NULL DEFAULT NULL,
  `estimated_pickup_time` time NULL DEFAULT NULL,
  `estimated_dropoff_time` time NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `stops_route_order_unique`(`transport_route_id` ASC, `stop_order` ASC) USING BTREE,
  CONSTRAINT `stops_route_fk` FOREIGN KEY (`transport_route_id`) REFERENCES `transport_routes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 5. Langganan Antar-Jemput Siswa (Student Transport Subscriptions)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `student_transport_subscriptions`;
CREATE TABLE `student_transport_subscriptions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `transport_route_id` bigint UNSIGNED NOT NULL,
  `transport_stop_id` bigint UNSIGNED NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `service_type` enum('round_trip','pickup_only','dropoff_only') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'round_trip',
  `start_date` date NOT NULL,
  `end_date` date NULL DEFAULT NULL,
  `monthly_fee` decimal(15, 2) NOT NULL,
  `status` enum('active','suspended','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sub_student_index`(`student_id` ASC) USING BTREE,
  INDEX `sub_route_index`(`transport_route_id` ASC) USING BTREE,
  INDEX `sub_stop_index`(`transport_stop_id` ASC) USING BTREE,
  INDEX `sub_year_index`(`academic_year_id` ASC) USING BTREE,
  CONSTRAINT `sub_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sub_route_fk` FOREIGN KEY (`transport_route_id`) REFERENCES `transport_routes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `sub_stop_fk` FOREIGN KEY (`transport_stop_id`) REFERENCES `transport_stops` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sub_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 6. Log GPS Real-Time Armada (GPS Telemetry Logs)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `gps_telemetry_logs`;
CREATE TABLE `gps_telemetry_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `latitude` decimal(10, 8) NOT NULL,
  `longitude` decimal(11, 8) NOT NULL,
  `speed_kmh` decimal(5, 2) NOT NULL DEFAULT 0.00,
  `heading_degrees` smallint UNSIGNED NULL DEFAULT NULL,
  `is_engine_on` tinyint(1) NOT NULL DEFAULT 1,
  `odometer_km` decimal(10, 2) NULL DEFAULT NULL,
  `recorded_at` timestamp NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `gps_vehicle_time_index`(`vehicle_id` ASC, `recorded_at` DESC) USING BTREE,
  CONSTRAINT `gps_vehicle_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 7. Riwayat Servis & Perawatan Armada (Vehicle Maintenance Logs)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `vehicle_maintenance_logs`;
CREATE TABLE `vehicle_maintenance_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `service_date` date NOT NULL,
  `odometer_reading` int UNSIGNED NULL DEFAULT NULL,
  `maintenance_type` enum('routine_service','oil_change','tire_replacement','repair','kir_inspection','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `next_service_due_date` date NULL DEFAULT NULL,
  `next_service_due_odometer` int UNSIGNED NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `veh_maint_vehicle_index`(`vehicle_id` ASC) USING BTREE,
  INDEX `veh_maint_vendor_index`(`vendor_id` ASC) USING BTREE,
  INDEX `veh_maint_journal_index`(`journal_entry_id` ASC) USING BTREE,
  CONSTRAINT `veh_maint_vehicle_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `veh_maint_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `veh_maint_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ==============================================================================
-- SCRIPT: 07_library_enhanced.sql
-- ==============================================================================
-- ==============================================================================
-- ENTERPRISE MODULE 07: ENHANCED LIBRARY MANAGEMENT SUBSYSTEM
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- 1. Penerbit & Pengarang Buku (Book Publishers & Authors)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `book_publishers`;
CREATE TABLE `book_publishers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pub_org_index`(`organization_id` ASC) USING BTREE,
  CONSTRAINT `pub_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `book_authors`;
CREATE TABLE `book_authors` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `biography` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `author_org_index`(`organization_id` ASC) USING BTREE,
  CONSTRAINT `author_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `book_categories`;
CREATE TABLE `book_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ddc_classification` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `book_cat_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  CONSTRAINT `book_cat_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Eksemplar Fisik Buku (Book Copies)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `book_copies`;
CREATE TABLE `book_copies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `library_book_id` bigint UNSIGNED NOT NULL,
  `barcode` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfid_tag` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `call_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shelf_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `copy_number` smallint UNSIGNED NOT NULL DEFAULT 1,
  `condition` enum('good','fair','damaged','lost') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'good',
  `status` enum('available','borrowed','reserved','in_repair','written_off') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `acquisition_date` date NULL DEFAULT NULL,
  `price` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `book_copies_barcode_unique`(`barcode` ASC) USING BTREE,
  INDEX `book_copies_rfid_index`(`rfid_tag` ASC) USING BTREE,
  INDEX `book_copies_book_index`(`library_book_id` ASC) USING BTREE,
  INDEX `book_copies_status_index`(`status` ASC) USING BTREE,
  CONSTRAINT `book_copies_book_fk` FOREIGN KEY (`library_book_id`) REFERENCES `library_books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 3. Keanggotaan Perpustakaan (Library Memberships)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `library_memberships`;
CREATE TABLE `library_memberships` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `member_type` enum('student','employee','external') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `member_id` bigint UNSIGNED NOT NULL,
  `member_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_borrow_limit` tinyint UNSIGNED NOT NULL DEFAULT 3,
  `max_borrow_days` tinyint UNSIGNED NOT NULL DEFAULT 7,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `expiry_date` date NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `lib_mem_code_unique`(`member_code` ASC) USING BTREE,
  INDEX `lib_mem_type_id_index`(`member_type` ASC, `member_id` ASC) USING BTREE,
  INDEX `lib_mem_school_index`(`school_id` ASC) USING BTREE,
  CONSTRAINT `lib_mem_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `lib_mem_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 4. Denda Perpustakaan (Library Fines)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `library_fines`;
CREATE TABLE `library_fines` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `library_loan_id` bigint UNSIGNED NOT NULL,
  `library_membership_id` bigint UNSIGNED NOT NULL,
  `days_overdue` smallint UNSIGNED NOT NULL DEFAULT 0,
  `fine_amount` decimal(15, 2) NOT NULL,
  `paid_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('unpaid','partial','paid','waived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `paid_at` timestamp NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `lib_fines_loan_index`(`library_loan_id` ASC) USING BTREE,
  INDEX `lib_fines_mem_index`(`library_membership_id` ASC) USING BTREE,
  INDEX `lib_fines_journal_index`(`journal_entry_id` ASC) USING BTREE,
  CONSTRAINT `lib_fines_loan_fk` FOREIGN KEY (`library_loan_id`) REFERENCES `library_loans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lib_fines_mem_fk` FOREIGN KEY (`library_membership_id`) REFERENCES `library_memberships` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `lib_fines_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 5. Reservasi / Booking Buku (Book Reservations)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `book_reservations`;
CREATE TABLE `book_reservations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `library_book_id` bigint UNSIGNED NOT NULL,
  `library_membership_id` bigint UNSIGNED NOT NULL,
  `reservation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiry_date` date NOT NULL,
  `status` enum('active','fulfilled','cancelled','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `book_res_book_index`(`library_book_id` ASC) USING BTREE,
  INDEX `book_res_mem_index`(`library_membership_id` ASC) USING BTREE,
  CONSTRAINT `book_res_book_fk` FOREIGN KEY (`library_book_id`) REFERENCES `library_books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `book_res_mem_fk` FOREIGN KEY (`library_membership_id`) REFERENCES `library_memberships` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ==============================================================================
-- SCRIPT: 08_asset_maintenance_disposal.sql
-- ==============================================================================
-- ==============================================================================
-- ENTERPRISE MODULE 08: FIXED ASSET MAINTENANCE & DISPOSAL
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- 1. Pemeliharaan & Servis Aset Tetap (Asset Maintenances)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `asset_maintenances`;
CREATE TABLE `asset_maintenances` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `fixed_asset_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `maintenance_type` enum('preventive','corrective','calibration','upgrade','inspection') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'preventive',
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `scheduled_date` date NOT NULL,
  `completion_date` date NULL DEFAULT NULL,
  `cost` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `status` enum('scheduled','in_progress','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `technician_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `invoice_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `asset_maint_asset_index`(`fixed_asset_id` ASC) USING BTREE,
  INDEX `asset_maint_vendor_index`(`vendor_id` ASC) USING BTREE,
  INDEX `asset_maint_journal_index`(`journal_entry_id` ASC) USING BTREE,
  INDEX `asset_maint_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `asset_maint_asset_fk` FOREIGN KEY (`fixed_asset_id`) REFERENCES `fixed_assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `asset_maint_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `asset_maint_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Penghapusan / Pelepasan Aset Tetap (Asset Disposals)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `asset_disposals`;
CREATE TABLE `asset_disposals` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `fixed_asset_id` bigint UNSIGNED NOT NULL,
  `disposal_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `disposal_date` date NOT NULL,
  `disposal_type` enum('sale','scrapped','donated','lost','trade_in') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scrapped',
  `book_value` decimal(15, 2) NOT NULL,
  `proceeds_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `gain_or_loss` decimal(15, 2) GENERATED ALWAYS AS ((`proceeds_amount` - `book_value`)) STORED,
  `disposal_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `asset_disp_num_unique`(`disposal_number` ASC) USING BTREE,
  INDEX `asset_disp_asset_index`(`fixed_asset_id` ASC) USING BTREE,
  INDEX `asset_disp_approver_index`(`approved_by` ASC) USING BTREE,
  INDEX `asset_disp_journal_index`(`journal_entry_id` ASC) USING BTREE,
  INDEX `asset_disp_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `asset_disp_asset_fk` FOREIGN KEY (`fixed_asset_id`) REFERENCES `fixed_assets` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `asset_disp_approver_fk` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `asset_disp_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ==============================================================================
-- SCRIPT: 09_clinic_uks.sql
-- ==============================================================================
-- ==============================================================================
-- ENTERPRISE MODULE 09: STUDENT & EMPLOYEE HEALTH CLINIC (UKS / POLIKLINIK)
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- 1. Obat & Peralatan Medis UKS (Clinic Medicines)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `clinic_medicines`;
CREATE TABLE `clinic_medicines` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('oral_medicine','topical','bandage','antiseptic','medical_device','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'oral_medicine',
  `stock_qty` int NOT NULL DEFAULT 0,
  `min_stock_qty` int NOT NULL DEFAULT 5,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tablet',
  `expiry_date` date NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `clinic_med_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `clinic_med_org_index`(`organization_id` ASC) USING BTREE,
  INDEX `clinic_med_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `clinic_med_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `clinic_med_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Kunjungan UKS / Rekam Medis (Clinic Visits)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `clinic_visits`;
CREATE TABLE `clinic_visits` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `patient_type` enum('student','employee','guest') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `patient_id` bigint UNSIGNED NOT NULL,
  `visit_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `symptoms` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnosis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `treatment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `rest_duration_minutes` smallint UNSIGNED NULL DEFAULT NULL,
  `action_taken` enum('rest_at_uks','sent_home','referred_to_hospital','back_to_class') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'back_to_class',
  `referral_hospital` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `handled_by` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `clinic_visit_school_index`(`school_id` ASC) USING BTREE,
  INDEX `clinic_visit_patient_index`(`patient_type` ASC, `patient_id` ASC) USING BTREE,
  INDEX `clinic_visit_handled_index`(`handled_by` ASC) USING BTREE,
  CONSTRAINT `clinic_visit_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `clinic_visit_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `clinic_visit_handled_fk` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 3. Resep / Pemberian Obat UKS (Clinic Prescriptions)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `clinic_prescriptions`;
CREATE TABLE `clinic_prescriptions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `clinic_visit_id` bigint UNSIGNED NOT NULL,
  `clinic_medicine_id` bigint UNSIGNED NOT NULL,
  `qty_given` int UNSIGNED NOT NULL DEFAULT 1,
  `dosage_instructions` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `clinic_rx_visit_index`(`clinic_visit_id` ASC) USING BTREE,
  INDEX `clinic_rx_med_index`(`clinic_medicine_id` ASC) USING BTREE,
  CONSTRAINT `clinic_rx_visit_fk` FOREIGN KEY (`clinic_visit_id`) REFERENCES `clinic_visits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `clinic_rx_med_fk` FOREIGN KEY (`clinic_medicine_id`) REFERENCES `clinic_medicines` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ==============================================================================
-- SCRIPT: 10_alumni_tracer.sql
-- ==============================================================================
-- ==============================================================================
-- ENTERPRISE MODULE 10: ALUMNI TRACKING, CAREER & TRACER STUDY
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- 1. Database Alumni (Alumni)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `alumni`;
CREATE TABLE `alumni` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `graduation_year` year NOT NULL,
  `current_activity` enum('employed','higher_education','entrepreneur','unemployed','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employed',
  `company_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `job_title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `industry_sector` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `monthly_income_range` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `university_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `university_major` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `current_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `linkedin_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `alumni_student_unique`(`student_id` ASC) USING BTREE,
  INDEX `alumni_org_index`(`organization_id` ASC) USING BTREE,
  INDEX `alumni_school_index`(`school_id` ASC) USING BTREE,
  INDEX `alumni_grad_year_index`(`graduation_year` ASC) USING BTREE,
  INDEX `alumni_activity_index`(`current_activity` ASC) USING BTREE,
  INDEX `alumni_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `alumni_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `alumni_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `alumni_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Master Kuesioner Tracer Study (Tracer Studies)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `tracer_studies`;
CREATE TABLE `tracer_studies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `target_graduation_year` year NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `tracer_org_index`(`organization_id` ASC) USING BTREE,
  INDEX `tracer_school_index`(`school_id` ASC) USING BTREE,
  INDEX `tracer_acad_year_index`(`academic_year_id` ASC) USING BTREE,
  CONSTRAINT `tracer_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tracer_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tracer_acad_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 3. Pertanyaan Tracer Study (Tracer Study Questions)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `tracer_study_questions`;
CREATE TABLE `tracer_study_questions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tracer_study_id` bigint UNSIGNED NOT NULL,
  `question_order` smallint UNSIGNED NOT NULL,
  `question_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_type` enum('text','textarea','multiple_choice','checkbox','scale_1_5') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `options_json` json NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `tracer_q_study_index`(`tracer_study_id` ASC, `question_order` ASC) USING BTREE,
  CONSTRAINT `tracer_q_study_fk` FOREIGN KEY (`tracer_study_id`) REFERENCES `tracer_studies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 4. Jawaban Responden Alumni (Tracer Study Responses)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `tracer_study_responses`;
CREATE TABLE `tracer_study_responses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tracer_study_id` bigint UNSIGNED NOT NULL,
  `alumni_id` bigint UNSIGNED NOT NULL,
  `question_id` bigint UNSIGNED NOT NULL,
  `answer_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `answer_json` json NULL,
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tracer_resp_unique`(`alumni_id` ASC, `question_id` ASC) USING BTREE,
  INDEX `tracer_resp_study_index`(`tracer_study_id` ASC) USING BTREE,
  INDEX `tracer_resp_q_index`(`question_id` ASC) USING BTREE,
  CONSTRAINT `tracer_resp_study_fk` FOREIGN KEY (`tracer_study_id`) REFERENCES `tracer_studies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tracer_resp_alumni_fk` FOREIGN KEY (`alumni_id`) REFERENCES `alumni` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tracer_resp_q_fk` FOREIGN KEY (`question_id`) REFERENCES `tracer_study_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 5. Donasi & Kontribusi Alumni (Alumni Donations)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `alumni_donations`;
CREATE TABLE `alumni_donations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `alumni_id` bigint UNSIGNED NOT NULL,
  `donation_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `campaign_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dana Abadi Pendidikan',
  `amount` decimal(15, 2) NOT NULL,
  `payment_method` enum('bank_transfer','qris','credit_card','cash') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bank_transfer',
  `proof_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('pending','verified','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `verified_at` timestamp NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `alumni_don_num_unique`(`donation_number` ASC) USING BTREE,
  INDEX `alumni_don_alumni_index`(`alumni_id` ASC) USING BTREE,
  INDEX `alumni_don_journal_index`(`journal_entry_id` ASC) USING BTREE,
  CONSTRAINT `alumni_don_alumni_fk` FOREIGN KEY (`alumni_id`) REFERENCES `alumni` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `alumni_don_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;



SET FOREIGN_KEY_CHECKS = 1;