-- ==============================================================================
-- ENTERPRISE MODULE 08: FIXED ASSET MAINTENANCE & DISPOSAL
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;
