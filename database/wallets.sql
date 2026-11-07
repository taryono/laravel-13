/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80407 (8.4.7)
 Source Host           : localhost:3306
 Source Schema         : laravel

 Target Server Type    : MySQL
 Target Server Version : 80407 (8.4.7)
 File Encoding         : 65001

 Date: 07/11/2026 10:54:10
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for canteen_menus
-- ----------------------------
DROP TABLE IF EXISTS `canteen_menus`;
CREATE TABLE `canteen_menus`  (
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

-- ----------------------------
-- Table structure for canteen_order_items
-- ----------------------------
DROP TABLE IF EXISTS `canteen_order_items`;
CREATE TABLE `canteen_order_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `canteen_order_id` bigint UNSIGNED NOT NULL,
  `canteen_menu_id` bigint UNSIGNED NOT NULL,
  `qty` int UNSIGNED NOT NULL,
  `unit_price` decimal(15, 2) NOT NULL,
  `total_price` decimal(15, 2) GENERATED ALWAYS AS ((`qty` * `unit_price`)) STORED NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `canteen_items_order_index`(`canteen_order_id` ASC) USING BTREE,
  INDEX `canteen_items_menu_index`(`canteen_menu_id` ASC) USING BTREE,
  CONSTRAINT `canteen_items_menu_fk` FOREIGN KEY (`canteen_menu_id`) REFERENCES `canteen_menus` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `canteen_items_order_fk` FOREIGN KEY (`canteen_order_id`) REFERENCES `canteen_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for canteen_orders
-- ----------------------------
DROP TABLE IF EXISTS `canteen_orders`;
CREATE TABLE `canteen_orders`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `canteen_tenant_id` bigint UNSIGNED NOT NULL,
  `digital_wallet_id` bigint UNSIGNED NULL DEFAULT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `idempotency_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `total_amount` decimal(15, 2) NOT NULL,
  `commission_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `commission_rate` decimal(5, 2) NOT NULL DEFAULT 0.00,
  `net_tenant_amount` decimal(15, 2) GENERATED ALWAYS AS ((`total_amount` - `commission_amount`)) STORED NULL,
  `payment_type` enum('wallet_rfid','cash','qris') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'wallet_rfid',
  `status` enum('pending','completed','cancelled','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `is_settled` tinyint(1) NOT NULL DEFAULT 0,
  `settlement_id` bigint UNSIGNED NULL DEFAULT NULL,
  `order_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `wallet_transaction_id` bigint UNSIGNED NULL DEFAULT NULL,
  `payment_reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `refund_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `canteen_orders_num_unique`(`order_number` ASC) USING BTREE,
  INDEX `canteen_orders_tenant_index`(`canteen_tenant_id` ASC) USING BTREE,
  INDEX `canteen_orders_wallet_index`(`digital_wallet_id` ASC) USING BTREE,
  INDEX `canteen_orders_settlement_index`(`settlement_id` ASC) USING BTREE,
  UNIQUE INDEX `canteen_orders_idem_unique`(`idempotency_key` ASC) USING BTREE,
  INDEX `canteen_orders_report_index`(`canteen_tenant_id` ASC, `status` ASC, `order_time` ASC) USING BTREE,
  INDEX `canteen_orders_wallet_time_index`(`digital_wallet_id` ASC, `order_time` ASC) USING BTREE,
  CONSTRAINT `canteen_orders_settlement_fk` FOREIGN KEY (`settlement_id`) REFERENCES `canteen_settlements` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `canteen_orders_tenant_fk` FOREIGN KEY (`canteen_tenant_id`) REFERENCES `canteen_tenants` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `canteen_orders_wallet_fk` FOREIGN KEY (`digital_wallet_id`) REFERENCES `digital_wallets` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for canteen_settlements
-- ----------------------------
DROP TABLE IF EXISTS `canteen_settlements`;
CREATE TABLE `canteen_settlements`  (
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
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `settlement_num_unique`(`settlement_number` ASC) USING BTREE,
  INDEX `settlement_tenant_index`(`canteen_tenant_id` ASC) USING BTREE,
  INDEX `settlement_journal_index`(`journal_entry_id` ASC) USING BTREE,
  INDEX `settlement_org_fk`(`organization_id` ASC) USING BTREE,
  CONSTRAINT `settlement_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `settlement_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `settlement_tenant_fk` FOREIGN KEY (`canteen_tenant_id`) REFERENCES `canteen_tenants` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for canteen_tenant_users
-- ----------------------------
DROP TABLE IF EXISTS `canteen_tenant_users`;
CREATE TABLE `canteen_tenant_users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `canteen_tenant_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role` enum('owner','cashier') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'owner',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_tenant_user`(`canteen_tenant_id`, `user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for canteen_tenants
-- ----------------------------
DROP TABLE IF EXISTS `canteen_tenants`;
CREATE TABLE `canteen_tenants`  (
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


-- ----------------------------
-- Table structure for wallet_topups
-- ----------------------------
DROP TABLE IF EXISTS `wallet_topups`;
CREATE TABLE `wallet_topups`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `digital_wallet_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `method` enum('qris','cash','transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','paid','expired','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `wallet_transaction_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_topup_ref`(`payment_reference`) USING BTREE,
  INDEX `idx_topup_wallet`(`digital_wallet_id`, `status`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for digital_wallets
-- ----------------------------
DROP TABLE IF EXISTS `digital_wallets`;
CREATE TABLE `digital_wallets`  (
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
  CONSTRAINT `digital_wallets_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `chk_wallet_balance` CHECK (`balance` >= 0)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wallet_cards
-- ----------------------------
DROP TABLE IF EXISTS `wallet_cards`;
CREATE TABLE `wallet_cards`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `digital_wallet_id` bigint UNSIGNED NOT NULL,
  `card_uid` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','blocked','lost','replaced') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `issued_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_card_uid`(`card_uid`) USING BTREE,
  INDEX `idx_card_wallet`(`digital_wallet_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wallet_transactions
-- ----------------------------
DROP TABLE IF EXISTS `wallet_transactions`;
CREATE TABLE `wallet_transactions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `digital_wallet_id` bigint UNSIGNED NOT NULL,
  `transaction_type` enum('topup','canteen_payment','refund','cashout','adjusment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `balance_before` decimal(15, 2) NOT NULL,
  `balance_after` decimal(15, 2) NOT NULL,
  `direction` enum('in','out') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payment_method` enum('cash','bank_transfer','qris','payment_gateway') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `reference_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reference_id` bigint UNSIGNED NULL DEFAULT NULL,
  `idempotency_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `wallet_tx_wallet_index`(`digital_wallet_id` ASC) USING BTREE,
  INDEX `wallet_tx_ref_index`(`reference_number` ASC) USING BTREE,
  INDEX `wallet_tx_journal_index`(`journal_entry_id` ASC) USING BTREE,
  INDEX `wallet_tx_created_by_fk`(`created_by` ASC) USING BTREE,
  CONSTRAINT `wallet_tx_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `wallet_tx_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `wallet_tx_wallet_fk` FOREIGN KEY (`digital_wallet_id`) REFERENCES `digital_wallets` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ----------------------------
-- Table structure for virtual_accounts
-- ----------------------------
DROP TABLE IF EXISTS `virtual_accounts`;
CREATE TABLE `virtual_accounts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `payment_transaction_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `applicant_id` bigint UNSIGNED NULL DEFAULT NULL,
  `bank_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `va_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `va_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `billing_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FIXED',
  `expiry_time` timestamp NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `paid_at` timestamp NULL DEFAULT NULL,
  `inquiry_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payment_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `raw_response` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `virtual_accounts_bank_va_unique`(`bank_code` ASC, `va_number` ASC) USING BTREE,
  INDEX `virtual_accounts_school_index`(`school_id` ASC) USING BTREE,
  INDEX `virtual_accounts_transaction_index`(`payment_transaction_id` ASC) USING BTREE,
  INDEX `virtual_accounts_student_index`(`student_id` ASC) USING BTREE,
  INDEX `virtual_accounts_applicant_index`(`applicant_id` ASC) USING BTREE,
  INDEX `virtual_accounts_bank_index`(`bank_code` ASC) USING BTREE,
  INDEX `virtual_accounts_status_index`(`status` ASC) USING BTREE,
  INDEX `virtual_accounts_expiry_index`(`expiry_time` ASC) USING BTREE,
  INDEX `virtual_accounts_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `virtual_accounts_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `virtual_accounts_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `virtual_accounts_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `virtual_accounts_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `virtual_accounts_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `virtual_accounts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of virtual_accounts
-- ----------------------------


SET FOREIGN_KEY_CHECKS = 1;
 