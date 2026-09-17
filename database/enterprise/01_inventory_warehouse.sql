-- ==============================================================================
-- ENTERPRISE MODULE 01: INVENTORY, WAREHOUSE & SUPPLY CHAIN MANAGEMENT
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;
