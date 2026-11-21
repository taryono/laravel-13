/*
 * Table Group: canteen
 * Total Tables: 6
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `canteen_menus`
-- ----------------------------
DROP TABLE IF EXISTS `canteen_menus`;
CREATE TABLE `canteen_menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `canteen_tenant_id` bigint unsigned NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('food','drink','snack','stationery','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'food',
  `price` decimal(15,2) NOT NULL,
  `cost_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `photo_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `canteen_menus_tenant_index` (`canteen_tenant_id`) USING BTREE,
  KEY `canteen_menus_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `canteen_menus_tenant_fk` FOREIGN KEY (`canteen_tenant_id`) REFERENCES `canteen_tenants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `canteen_order_items`
-- ----------------------------
DROP TABLE IF EXISTS `canteen_order_items`;
CREATE TABLE `canteen_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `canteen_order_id` bigint unsigned NOT NULL,
  `canteen_menu_id` bigint unsigned NOT NULL,
  `qty` int unsigned NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) GENERATED ALWAYS AS ((`qty` * `unit_price`)) STORED,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `canteen_items_order_index` (`canteen_order_id`) USING BTREE,
  KEY `canteen_items_menu_index` (`canteen_menu_id`) USING BTREE,
  CONSTRAINT `canteen_items_menu_fk` FOREIGN KEY (`canteen_menu_id`) REFERENCES `canteen_menus` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `canteen_items_order_fk` FOREIGN KEY (`canteen_order_id`) REFERENCES `canteen_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `canteen_orders`
-- ----------------------------
DROP TABLE IF EXISTS `canteen_orders`;
CREATE TABLE `canteen_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `canteen_tenant_id` bigint unsigned NOT NULL,
  `digital_wallet_id` bigint unsigned DEFAULT NULL,
  `order_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `idempotency_key` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `commission_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `commission_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `net_tenant_amount` decimal(15,2) GENERATED ALWAYS AS ((`total_amount` - `commission_amount`)) STORED,
  `payment_type` enum('wallet_rfid','cash','qris') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'wallet_rfid',
  `status` enum('pending','completed','cancelled','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `is_settled` tinyint(1) NOT NULL DEFAULT '0',
  `settlement_id` bigint unsigned DEFAULT NULL,
  `order_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `wallet_transaction_id` bigint unsigned DEFAULT NULL,
  `payment_reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `refund_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `canteen_orders_num_unique` (`order_number`) USING BTREE,
  UNIQUE KEY `canteen_orders_idem_unique` (`idempotency_key`),
  UNIQUE KEY `canteen_orders_payref_unique` (`payment_reference`),
  KEY `canteen_orders_tenant_index` (`canteen_tenant_id`) USING BTREE,
  KEY `canteen_orders_wallet_index` (`digital_wallet_id`) USING BTREE,
  KEY `canteen_orders_settlement_index` (`settlement_id`) USING BTREE,
  KEY `canteen_orders_report_index` (`canteen_tenant_id`,`status`,`order_time`),
  KEY `canteen_orders_wallet_time_index` (`digital_wallet_id`,`order_time`),
  KEY `canteen_orders_wtx_fk` (`wallet_transaction_id`),
  CONSTRAINT `canteen_orders_settlement_fk` FOREIGN KEY (`settlement_id`) REFERENCES `canteen_settlements` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `canteen_orders_tenant_fk` FOREIGN KEY (`canteen_tenant_id`) REFERENCES `canteen_tenants` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `canteen_orders_wallet_fk` FOREIGN KEY (`digital_wallet_id`) REFERENCES `digital_wallets` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `canteen_orders_wtx_fk` FOREIGN KEY (`wallet_transaction_id`) REFERENCES `wallet_transactions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `chk_order_commission` CHECK (((`commission_amount` >= 0) and (`commission_amount` <= `total_amount`))),
  CONSTRAINT `chk_order_total` CHECK ((`total_amount` > 0)),
  CONSTRAINT `chk_order_wallet` CHECK (((`payment_type` <> _utf8mb4'wallet_rfid') or (`digital_wallet_id` is not null)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `canteen_settlements`
-- ----------------------------
DROP TABLE IF EXISTS `canteen_settlements`;
CREATE TABLE `canteen_settlements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `canteen_tenant_id` bigint unsigned NOT NULL,
  `settlement_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_gross_sales` decimal(15,2) NOT NULL,
  `total_commission` decimal(15,2) NOT NULL,
  `net_payout` decimal(15,2) NOT NULL,
  `payment_status` enum('draft','approved','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `paid_at` timestamp NULL DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `settlement_num_unique` (`settlement_number`) USING BTREE,
  KEY `settlement_tenant_index` (`canteen_tenant_id`) USING BTREE,
  KEY `settlement_journal_index` (`journal_entry_id`) USING BTREE,
  KEY `settlement_org_fk` (`organization_id`) USING BTREE,
  CONSTRAINT `settlement_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `settlement_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `settlement_tenant_fk` FOREIGN KEY (`canteen_tenant_id`) REFERENCES `canteen_tenants` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `canteen_tenant_users`
-- ----------------------------
DROP TABLE IF EXISTS `canteen_tenant_users`;
CREATE TABLE `canteen_tenant_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `canteen_tenant_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role` enum('owner','cashier') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'owner',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tenant_user` (`canteen_tenant_id`,`user_id`),
  KEY `ctu_user_fk` (`user_id`),
  CONSTRAINT `ctu_tenant_fk` FOREIGN KEY (`canteen_tenant_id`) REFERENCES `canteen_tenants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ctu_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `canteen_tenants`
-- ----------------------------
DROP TABLE IF EXISTS `canteen_tenants`;
CREATE TABLE `canteen_tenants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stall_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `bank_account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `canteen_tenants_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `canteen_tenants_org_index` (`organization_id`) USING BTREE,
  KEY `canteen_tenants_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `canteen_tenants_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `canteen_tenants_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
