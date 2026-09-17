-- ==============================================================================
-- ENTERPRISE MODULE 05: CANTEEN CASHLESS & STUDENT E-WALLET (RFID POS)
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;
