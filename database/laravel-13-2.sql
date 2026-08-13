/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80031 (8.0.31)
 Source Host           : localhost:3306
 Source Schema         : laravel-13

 Target Server Type    : MySQL
 Target Server Version : 80031 (8.0.31)
 File Encoding         : 65001

 Date: 14/08/2026 01:43:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `action_role_action_id_role_id_unique`(`action_id` ASC, `role_id` ASC) USING BTREE,
  INDEX `action_role_action_id_foreign`(`action_id` ASC) USING BTREE,
  INDEX `action_role_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `action_role_action_id_foreign` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `action_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of action_role
-- ----------------------------
INSERT INTO `action_role` VALUES (1, 1, 1, NULL, NULL, NULL);
INSERT INTO `action_role` VALUES (2, 2, 1, NULL, NULL, NULL);
INSERT INTO `action_role` VALUES (3, 3, 1, NULL, NULL, NULL);
INSERT INTO `action_role` VALUES (4, 2, 2, NULL, NULL, NULL);
INSERT INTO `action_role` VALUES (5, 1, 2, NULL, NULL, NULL);

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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `action_user_action_id_user_id_unique`(`action_id` ASC, `user_id` ASC) USING BTREE,
  INDEX `action_user_action_id_foreign`(`action_id` ASC) USING BTREE,
  INDEX `action_user_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `action_user_action_id_foreign` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `action_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of action_user
-- ----------------------------

-- ----------------------------
-- Table structure for actions
-- ----------------------------
DROP TABLE IF EXISTS `actions`;
CREATE TABLE `actions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'nama unik: master-produk-create',
  `display_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `actions_name_unique`(`name` ASC) USING BTREE,
  INDEX `actions_menu_id_foreign`(`menu_id` ASC) USING BTREE,
  CONSTRAINT `actions_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of actions
-- ----------------------------
INSERT INTO `actions` VALUES (1, 3, 'user-view', 'Lihat Daftar Pengguna', NULL, 1, '2026-08-01 08:02:24', '2026-08-01 08:02:24', NULL);
INSERT INTO `actions` VALUES (2, 3, 'user-manage', 'Kelola Pengguna', NULL, 1, '2026-08-01 08:02:24', '2026-08-01 08:02:24', NULL);
INSERT INTO `actions` VALUES (3, 4, 'role-manage', 'Kelola Peran', NULL, 1, '2026-08-01 08:02:24', '2026-08-01 08:02:24', NULL);
INSERT INTO `actions` VALUES (4, 4, 'role-index', 'Halaman Role', NULL, 1, '2026-08-01 15:14:58', '2026-08-01 15:14:58', NULL);

-- ----------------------------
-- Table structure for buildings
-- ----------------------------
DROP TABLE IF EXISTS `buildings`;
CREATE TABLE `buildings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `total_floors` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache
-- ----------------------------
INSERT INTO `cache` VALUES ('laravel-cache-acl-all-actions', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:4:{i:1;s:9:\"user-view\";i:2;s:11:\"user-manage\";i:3;s:11:\"role-manage\";i:4;s:10:\"role-index\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1786649214);
INSERT INTO `cache` VALUES ('laravel-cache-acl-all-menus', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:21:{i:1;s:9:\"dashboard\";i:2;s:6:\"master\";i:3;s:5:\"users\";i:4;s:5:\"roles\";i:5;s:15:\"menu-management\";i:6;s:14:\"crud-generator\";i:8;s:4:\"menu\";i:10;s:18:\"trial-registration\";i:11;s:6:\"school\";i:12;s:14:\"eduation-level\";i:13;s:8:\"position\";i:14;s:6:\"module\";i:15;s:17:\"facility-category\";i:16;s:8:\"facility\";i:17;s:8:\"building\";i:18;s:5:\"floor\";i:19;s:4:\"room\";i:20;s:7:\"package\";i:21;s:19:\"management-property\";i:22;s:10:\"dictionary\";i:23;s:22:\"management-master-data\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1786649214);
INSERT INTO `cache` VALUES ('laravel-cache-formlib_fields_facilities', 'a:8:{i:0;s:2:\"id\";i:1;s:20:\"facility_category_id\";i:2;s:7:\"room_id\";i:3;s:4:\"code\";i:4;s:4:\"name\";i:5;s:11:\"description\";i:6;s:9:\"is_active\";i:7;s:8:\"quantity\";}', 1786649239);
INSERT INTO `cache` VALUES ('laravel-cache-formlib_cols_facilities', 'O:8:\"stdClass\":8:{s:2:\"id\";O:8:\"stdClass\":7:{s:4:\"type\";s:15:\"bigint unsigned\";s:7:\"is_null\";s:2:\"NO\";s:3:\"key\";s:3:\"PRI\";s:7:\"default\";N;s:5:\"extra\";s:14:\"auto_increment\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}s:20:\"facility_category_id\";O:8:\"stdClass\":7:{s:4:\"type\";s:15:\"bigint unsigned\";s:7:\"is_null\";s:2:\"NO\";s:3:\"key\";s:3:\"MUL\";s:7:\"default\";N;s:5:\"extra\";s:0:\"\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}s:7:\"room_id\";O:8:\"stdClass\":7:{s:4:\"type\";s:15:\"bigint unsigned\";s:7:\"is_null\";s:2:\"NO\";s:3:\"key\";s:3:\"MUL\";s:7:\"default\";N;s:5:\"extra\";s:0:\"\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}s:4:\"code\";O:8:\"stdClass\":7:{s:4:\"type\";s:12:\"varchar(100)\";s:7:\"is_null\";s:2:\"NO\";s:3:\"key\";s:3:\"UNI\";s:7:\"default\";N;s:5:\"extra\";s:0:\"\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}s:4:\"name\";O:8:\"stdClass\":7:{s:4:\"type\";s:12:\"varchar(191)\";s:7:\"is_null\";s:2:\"NO\";s:3:\"key\";s:0:\"\";s:7:\"default\";N;s:5:\"extra\";s:0:\"\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}s:11:\"description\";O:8:\"stdClass\":7:{s:4:\"type\";s:4:\"text\";s:7:\"is_null\";s:3:\"YES\";s:3:\"key\";s:0:\"\";s:7:\"default\";N;s:5:\"extra\";s:0:\"\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}s:9:\"is_active\";O:8:\"stdClass\":7:{s:4:\"type\";s:3:\"int\";s:7:\"is_null\";s:2:\"NO\";s:3:\"key\";s:0:\"\";s:7:\"default\";s:1:\"1\";s:5:\"extra\";s:0:\"\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}s:8:\"quantity\";O:8:\"stdClass\":7:{s:4:\"type\";s:3:\"int\";s:7:\"is_null\";s:2:\"NO\";s:3:\"key\";s:0:\"\";s:7:\"default\";s:1:\"1\";s:5:\"extra\";s:0:\"\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}}', 1786649239);

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for dictionaries
-- ----------------------------
DROP TABLE IF EXISTS `dictionaries`;
CREATE TABLE `dictionaries`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `in` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `metadata` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dictionaries
-- ----------------------------

-- ----------------------------
-- Table structure for education_levels
-- ----------------------------
DROP TABLE IF EXISTS `education_levels`;
CREATE TABLE `education_levels`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of education_levels
-- ----------------------------

-- ----------------------------
-- Table structure for facilities
-- ----------------------------
DROP TABLE IF EXISTS `facilities`;
CREATE TABLE `facilities`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `facility_category_id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED NOT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` int NOT NULL DEFAULT 1,
  `quantity` int NOT NULL DEFAULT 1,
  `is_available` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `facilities_code_unique`(`code`) USING BTREE,
  INDEX `facilities_facility_category_id_foreign`(`facility_category_id`) USING BTREE,
  INDEX `facilities_room_id_foreign`(`room_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 52 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of facilities
-- ----------------------------
INSERT INTO `facilities` VALUES (1, 2, 1, 'FAS-KLS-SD-1-001', 'Whiteboard', 'Whiteboard ukuran 120x200 cm', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (2, 2, 1, 'FAS-KLS-SD-1-002', 'Proyektor', 'Proyektor LCD 4000 lumen', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (3, 2, 1, 'FAS-KLS-SD-1-003', 'AC', 'AC Split 2 PK', 1, 2, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (4, 2, 1, 'FAS-KLS-SD-1-004', 'Meja Siswa', 'Meja siswa kayu ukuran 60x40 cm', 1, 32, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (5, 2, 1, 'FAS-KLS-SD-1-005', 'Kursi Siswa', 'Kursi siswa plastik', 1, 32, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (6, 2, 2, 'FAS-KLS-SD-2-001', 'Whiteboard', 'Whiteboard ukuran 120x200 cm', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (7, 2, 2, 'FAS-KLS-SD-2-002', 'Proyektor', 'Proyektor LCD 4000 lumen', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (8, 2, 2, 'FAS-KLS-SD-2-003', 'AC', 'AC Split 2 PK', 1, 2, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (9, 2, 2, 'FAS-KLS-SD-2-004', 'Meja Siswa', 'Meja siswa kayu ukuran 60x40 cm', 1, 32, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (10, 2, 2, 'FAS-KLS-SD-2-005', 'Kursi Siswa', 'Kursi siswa plastik', 1, 32, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (11, 2, 3, 'FAS-KLS-SD-3-001', 'Whiteboard', 'Whiteboard ukuran 120x200 cm', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (12, 2, 3, 'FAS-KLS-SD-3-002', 'Proyektor', 'Proyektor LCD 4000 lumen', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (13, 2, 3, 'FAS-KLS-SD-3-003', 'AC', 'AC Split 2 PK', 1, 2, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (14, 2, 3, 'FAS-KLS-SD-3-004', 'Meja Siswa', 'Meja siswa kayu ukuran 60x40 cm', 1, 32, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (15, 2, 3, 'FAS-KLS-SD-3-005', 'Kursi Siswa', 'Kursi siswa plastik', 1, 32, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (16, 2, 4, 'FAS-KLS-SD-4-001', 'Whiteboard', 'Whiteboard ukuran 120x200 cm', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (17, 2, 4, 'FAS-KLS-SD-4-002', 'Proyektor', 'Proyektor LCD 4000 lumen', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (18, 2, 4, 'FAS-KLS-SD-4-003', 'AC', 'AC Split 2 PK', 1, 2, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (19, 2, 4, 'FAS-KLS-SD-4-004', 'Meja Siswa', 'Meja siswa kayu ukuran 60x40 cm', 1, 32, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (20, 2, 4, 'FAS-KLS-SD-4-005', 'Kursi Siswa', 'Kursi siswa plastik', 1, 32, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (21, 2, 5, 'FAS-KLS-SD-5-001', 'Whiteboard', 'Whiteboard ukuran 120x200 cm', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (22, 2, 5, 'FAS-KLS-SD-5-002', 'Proyektor', 'Proyektor LCD 4000 lumen', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (23, 2, 5, 'FAS-KLS-SD-5-003', 'AC', 'AC Split 2 PK', 1, 2, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (24, 2, 5, 'FAS-KLS-SD-5-004', 'Meja Siswa', 'Meja siswa kayu ukuran 60x40 cm', 1, 32, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (25, 2, 5, 'FAS-KLS-SD-5-005', 'Kursi Siswa', 'Kursi siswa plastik', 1, 32, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (26, 2, 6, 'FAS-KLS-SD-6-001', 'Whiteboard', 'Whiteboard ukuran 120x200 cm', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (27, 2, 6, 'FAS-KLS-SD-6-002', 'Proyektor', 'Proyektor LCD 4000 lumen', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (28, 2, 6, 'FAS-KLS-SD-6-003', 'AC', 'AC Split 2 PK', 1, 2, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (29, 2, 6, 'FAS-KLS-SD-6-004', 'Meja Siswa', 'Meja siswa kayu ukuran 60x40 cm', 1, 32, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (30, 2, 6, 'FAS-KLS-SD-6-005', 'Kursi Siswa', 'Kursi siswa plastik', 1, 32, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (31, 1, 7, 'FAS-LAB-IPA-001', 'Mikroskop', 'Mikroskop binokuler', 1, 15, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (32, 1, 7, 'FAS-LAB-IPA-002', 'Meja Laboratorium', 'Meja laboratorium tahan asam', 1, 15, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (33, 1, 7, 'FAS-LAB-IPA-003', 'Alat Peraga IPA', 'Set alat peraga fisika dan biologi', 1, 5, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (34, 1, 8, 'FAS-LAB-KOM-001', 'Komputer PC', 'PC Core i5, RAM 8GB, SSD 256GB', 1, 30, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (35, 1, 8, 'FAS-LAB-KOM-002', 'Meja Komputer', 'Meja komputer dengan rak CPU', 1, 30, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (36, 1, 8, 'FAS-LAB-KOM-003', 'Proyektor', 'Proyektor LCD untuk presentasi', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (37, 2, 9, 'FAS-RG-001', 'Meja Guru', 'Meja kerja guru ukuran 120x60 cm', 1, 20, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (38, 2, 9, 'FAS-RG-002', 'Kursi Guru', 'Kursi kerja ergonomis', 1, 20, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (39, 2, 9, 'FAS-RG-003', 'Lemari Arsip', 'Lemari arsip 4 laci', 1, 5, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (40, 3, 9, 'FAS-RG-004', 'Dispenser Air', 'Dispenser air minum', 1, 2, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (41, 2, 10, 'FAS-KS-001', 'Meja Kerja', 'Meja kerja eksekutif', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (42, 2, 10, 'FAS-KS-002', 'Kursi Kerja', 'Kursi kerja eksekutif', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (43, 2, 10, 'FAS-KS-003', 'Komputer PC', 'PC Core i7 untuk administrasi', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (44, 2, 10, 'FAS-KS-004', 'Lemari Arsip', 'Lemari arsip 4 laci', 1, 2, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (45, 1, 27, 'FAS-PUST-001', 'Rak Buku', 'Rak buku besi 5 susun', 1, 20, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (46, 1, 27, 'FAS-PUST-002', 'Meja Baca', 'Meja baca panjang', 1, 10, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (47, 1, 27, 'FAS-PUST-003', 'Kursi Baca', 'Kursi baca', 1, 50, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (48, 2, 29, 'FAS-PUST-004', 'Komputer PC', 'PC untuk akses digital library', 1, 15, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (49, 2, 1, 'FAS-UMUM-001', 'Kantin', 'Area kantin dengan meja dan kursi', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (50, 2, 1, 'FAS-UMUM-002', 'Musholla', 'Tempat ibadah', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);
INSERT INTO `facilities` VALUES (51, 3, 1, 'FAS-UMUM-003', 'Area Parkir', 'Area parkir mobil dan motor', 1, 1, NULL, '2026-08-13 18:23:25', '2026-08-13 18:23:25', NULL);

-- ----------------------------
-- Table structure for facility_categories
-- ----------------------------
DROP TABLE IF EXISTS `facility_categories`;
CREATE TABLE `facility_categories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of facility_categories
-- ----------------------------
INSERT INTO `facility_categories` VALUES (1, 'EDU', 'Sarana Pendidikan Inti', 'Fasilitas utama untuk kegiatan belajar-mengajar', '2026-08-13 18:29:11', '2026-08-13 18:29:11', NULL);
INSERT INTO `facility_categories` VALUES (2, 'SUPPORT', 'Fasilitas Penunjang Kegiatan', 'Fasilitas untuk menunjang kegiatan non-akademik dan pengembangan diri', '2026-08-13 18:29:11', '2026-08-13 18:29:11', NULL);
INSERT INTO `facility_categories` VALUES (3, 'OPERATIONAL', 'Sarana Umum & Penunjang Operasional', 'Fasilitas untuk mendukung operasional yayasan dan kenyamanan', '2026-08-13 18:29:11', '2026-08-13 18:29:11', NULL);

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
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `floors_building_id_foreign`(`building_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of floors
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
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
  INDEX `jobs_queue_index`(`queue`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for menu_role
-- ----------------------------
DROP TABLE IF EXISTS `menu_role`;
CREATE TABLE `menu_role`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `is_view` int NULL DEFAULT NULL,
  `is_create` int NULL DEFAULT NULL,
  `is_edit` int NULL DEFAULT NULL,
  `is_delete` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `menu_role_menu_id_role_id_unique`(`menu_id` ASC, `role_id` ASC) USING BTREE,
  INDEX `menu_role_menu_id_foreign`(`menu_id` ASC) USING BTREE,
  INDEX `menu_role_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `menu_role_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `menu_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu_role
-- ----------------------------
INSERT INTO `menu_role` VALUES (1, 1, 1, 1, 1, 1, 1, '2026-08-02 04:59:25', '2026-08-02 04:59:25', NULL);
INSERT INTO `menu_role` VALUES (2, 1, 2, 1, 1, 1, 1, '2026-08-02 04:59:25', '2026-08-02 04:59:25', NULL);
INSERT INTO `menu_role` VALUES (3, 2, 1, NULL, NULL, NULL, NULL, '2026-08-01 08:02:24', '2026-08-01 08:02:24', NULL);
INSERT INTO `menu_role` VALUES (4, 3, 1, 1, 1, 1, 1, '2026-08-01 13:59:06', '2026-08-01 13:59:06', NULL);
INSERT INTO `menu_role` VALUES (5, 3, 2, 1, 1, 1, 1, '2026-08-01 13:59:06', '2026-08-01 13:59:06', NULL);
INSERT INTO `menu_role` VALUES (6, 4, 1, 1, 1, 1, 1, '2026-08-01 13:59:28', '2026-08-01 13:59:28', NULL);
INSERT INTO `menu_role` VALUES (7, 5, 1, 1, 1, 1, 1, '2026-08-01 13:27:12', '2026-08-01 13:27:12', NULL);
INSERT INTO `menu_role` VALUES (8, 6, 1, 1, 1, 1, 1, '2026-08-01 14:18:06', '2026-08-01 14:18:06', NULL);
INSERT INTO `menu_role` VALUES (9, 8, 2, 1, 1, 1, 1, '2026-08-01 14:58:47', '2026-08-01 14:58:47', NULL);
INSERT INTO `menu_role` VALUES (10, 8, 1, 1, 1, 1, 1, '2026-08-01 14:58:47', '2026-08-01 14:58:47', NULL);
INSERT INTO `menu_role` VALUES (11, 10, 1, 1, 1, 1, 1, '2026-08-13 06:24:24', '2026-08-13 06:24:24', NULL);
INSERT INTO `menu_role` VALUES (12, 11, 1, 1, 1, 1, 1, '2026-08-13 06:30:18', '2026-08-13 06:30:18', NULL);
INSERT INTO `menu_role` VALUES (13, 12, 1, 1, 1, 1, 1, '2026-08-13 06:32:29', '2026-08-13 06:32:29', NULL);
INSERT INTO `menu_role` VALUES (14, 13, 1, 1, 1, 1, 1, '2026-08-13 07:03:18', '2026-08-13 07:03:18', NULL);
INSERT INTO `menu_role` VALUES (15, 14, 1, 1, 1, 1, 1, '2026-08-13 09:33:46', '2026-08-13 09:33:46', NULL);
INSERT INTO `menu_role` VALUES (16, 15, 1, 1, 1, 1, 1, '2026-08-13 18:26:53', '2026-08-13 18:26:53', NULL);
INSERT INTO `menu_role` VALUES (17, 16, 1, 1, 1, 1, 1, '2026-08-13 11:05:56', '2026-08-13 11:05:56', NULL);
INSERT INTO `menu_role` VALUES (18, 17, 1, 1, 1, 1, 1, '2026-08-13 11:32:58', '2026-08-13 11:32:58', NULL);
INSERT INTO `menu_role` VALUES (19, 18, 1, 1, 1, 1, 1, '2026-08-13 11:34:28', '2026-08-13 11:34:28', NULL);
INSERT INTO `menu_role` VALUES (20, 19, 1, 1, 1, 1, 1, '2026-08-13 11:37:24', '2026-08-13 11:37:24', NULL);
INSERT INTO `menu_role` VALUES (21, 20, 1, 1, 1, 1, 1, '2026-08-13 16:07:42', '2026-08-13 16:07:42', NULL);
INSERT INTO `menu_role` VALUES (22, 21, 2, 1, 1, 1, 1, '2026-08-13 17:19:31', '2026-08-13 17:19:31', NULL);
INSERT INTO `menu_role` VALUES (23, 21, 1, 1, 1, 1, 1, '2026-08-13 17:19:31', '2026-08-13 17:19:31', NULL);
INSERT INTO `menu_role` VALUES (24, 22, 1, 1, 1, 1, 1, '2026-08-13 17:27:36', '2026-08-13 17:27:36', NULL);

-- ----------------------------
-- Table structure for menus
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `flyout_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'unik untuk pencarian',
  `icon` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `icon_type` enum('material','fa') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `route` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `type` enum('topbar','sidebar') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `sequence` int NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `menus_slug_unique`(`slug` ASC) USING BTREE,
  INDEX `menus_parent_id_foreign`(`parent_id` ASC) USING BTREE,
  CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menus
-- ----------------------------
INSERT INTO `menus` VALUES (1, NULL, 'Dasbor', 'Dasbor', NULL, 'dashboard', 'fas fa-tachometer-alt', 'fa', 'dashboard', 'dashboard', 'sidebar', 8, NULL, 1, '2026-08-01 08:02:24', '2026-08-13 18:07:41', NULL);
INSERT INTO `menus` VALUES (2, NULL, 'Data Utama', 'ACL', NULL, 'master', 'fas fa-database', 'fa', NULL, NULL, 'sidebar', 9, NULL, 1, '2026-08-01 08:02:24', '2026-08-13 18:07:41', NULL);
INSERT INTO `menus` VALUES (3, 2, 'Pengguna', 'Users', NULL, 'users', 'fas fa-users', 'fa', 'user.index', 'user', 'sidebar', 1, NULL, 1, '2026-08-01 08:02:24', '2026-08-13 18:11:53', NULL);
INSERT INTO `menus` VALUES (4, 2, 'Peran & Izin', 'Roles', NULL, 'roles', 'fas fa-user-shield', 'fa', 'role.index', 'role', 'sidebar', 2, NULL, 1, '2026-08-01 08:02:24', '2026-08-13 18:12:02', NULL);
INSERT INTO `menus` VALUES (5, NULL, 'Manajemen Menu', 'Manajemen Menu', NULL, 'menu-management', 'list_alt', 'material', NULL, NULL, 'sidebar', 10, NULL, 1, '2026-08-01 13:27:12', '2026-08-13 18:07:41', NULL);
INSERT INTO `menus` VALUES (6, 5, 'Crud Generator', 'Crud Generator', NULL, 'crud-generator', 'list_alt', 'material', 'crud-generator.index', 'crud-generator', 'sidebar', 1, NULL, 1, '2026-08-01 14:18:06', '2026-08-13 18:07:41', NULL);
INSERT INTO `menus` VALUES (8, 5, 'Manajemen Menu', 'Menus', NULL, 'menu', 'list_alt', 'material', 'menu_management.index', 'menu_management', 'sidebar', 2, NULL, 1, '2026-08-01 13:27:12', '2026-08-13 18:12:12', NULL);
INSERT INTO `menus` VALUES (10, NULL, NULL, 'Trial Registration', NULL, 'trial-registration', 'list_alt', 'material', 'trial-registration.index', 'trial-registration', 'sidebar', 1, NULL, 1, '2026-08-13 06:24:24', '2026-08-13 18:07:41', NULL);
INSERT INTO `menus` VALUES (11, 21, NULL, 'Schools', NULL, 'school', 'list_alt', 'material', 'school.index', 'school', 'sidebar', 1, NULL, 1, '2026-08-13 06:30:18', '2026-08-13 18:10:42', NULL);
INSERT INTO `menus` VALUES (12, 23, NULL, 'Eduation Levels', NULL, 'eduation-level', 'list_alt', 'material', 'eduation-level.index', 'eduation-level', 'sidebar', 2, NULL, 1, '2026-08-13 06:32:29', '2026-08-13 18:10:16', NULL);
INSERT INTO `menus` VALUES (13, NULL, NULL, 'Positions', NULL, 'position', 'list_alt', 'material', 'position.index', 'position', 'sidebar', 3, NULL, 1, '2026-08-13 07:03:18', '2026-08-13 18:10:30', NULL);
INSERT INTO `menus` VALUES (14, 23, NULL, 'Modules', NULL, 'module', 'list_alt', 'material', 'module.index', 'module', 'sidebar', 4, NULL, 1, '2026-08-13 09:33:46', '2026-08-13 18:11:24', NULL);
INSERT INTO `menus` VALUES (15, NULL, NULL, 'Facility Category', NULL, 'facility-category', 'list_alt', 'material', 'facility-category.index', 'facility-category', 'sidebar', 11, NULL, 1, '2026-08-13 10:59:07', '2026-08-13 18:26:52', NULL);
INSERT INTO `menus` VALUES (16, 21, NULL, 'Facilities', NULL, 'facility', 'list_alt', 'material', 'facility.index', 'facility', 'sidebar', 3, NULL, 1, '2026-08-13 11:05:56', '2026-08-13 18:11:01', NULL);
INSERT INTO `menus` VALUES (17, 21, NULL, 'Buildings', NULL, 'building', 'list_alt', 'material', 'building.index', 'building', 'sidebar', 4, NULL, 1, '2026-08-13 11:32:58', '2026-08-13 18:11:12', NULL);
INSERT INTO `menus` VALUES (18, 21, NULL, 'Floors', NULL, 'floor', 'list_alt', 'material', 'floor.index', 'floor', 'sidebar', 5, NULL, 1, '2026-08-13 11:34:28', '2026-08-13 18:12:35', NULL);
INSERT INTO `menus` VALUES (19, 21, NULL, 'Rooms', NULL, 'room', 'list_alt', 'material', 'room.index', 'room', 'sidebar', 6, NULL, 1, '2026-08-13 11:37:24', '2026-08-13 18:12:26', NULL);
INSERT INTO `menus` VALUES (20, 23, NULL, 'Packages', NULL, 'package', 'list_alt', 'material', 'package.index', 'package', 'sidebar', 1, NULL, 1, '2026-08-13 16:07:41', '2026-08-13 18:11:32', NULL);
INSERT INTO `menus` VALUES (21, NULL, NULL, 'Management Properties', NULL, 'management-property', 'home', 'material', 'mangement-property', NULL, 'sidebar', 5, NULL, 1, '2026-08-13 17:17:05', '2026-08-13 18:12:57', NULL);
INSERT INTO `menus` VALUES (22, 23, NULL, 'Dictionaries', NULL, 'dictionary', 'list_alt', 'material', 'dictionary.index', 'dictionary', 'sidebar', 6, NULL, 1, '2026-08-13 17:27:36', '2026-08-13 18:11:42', NULL);
INSERT INTO `menus` VALUES (23, NULL, NULL, 'Management Master Data', NULL, 'management-master-data', 'settings', 'material', 'management_master', NULL, 'sidebar', 7, NULL, 1, '2026-08-13 18:07:17', '2026-08-13 18:08:24', NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2026_08_13_110555_create_facilities_table', 1);
INSERT INTO `migrations` VALUES (2, '2026_08_13_113724_create_rooms_table', 2);

-- ----------------------------
-- Table structure for modules
-- ----------------------------
DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `price` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of modules
-- ----------------------------

-- ----------------------------
-- Table structure for packages
-- ----------------------------
DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL DEFAULT 0,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `features` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of packages
-- ----------------------------
INSERT INTO `packages` VALUES (1, 'starter', 'Paket Starter', 300000, 'Solusi tepat untuk sekolah yang baru memulai digitalisasi. Nikmati fitur dasar pengelolaan data siswa, guru, dan jadwal pelajaran dengan antarmuka yang mudah digunakan. Cocok untuk sekolah dengan jumlah siswa hingga 500.', '\"[\\\"Manajemen Data Siswa (maks. 500)\\\",\\\"Manajemen Data Guru\\\",\\\"Jadwal Pelajaran Digital\\\",\\\"Absensi Siswa\\\",\\\"Laporan Dasar\\\",\\\"Customer Support via Email\\\"]\"', 1, '2026-08-13 18:33:32', '2026-08-13 18:33:32', NULL);
INSERT INTO `packages` VALUES (2, 'professional', 'Paket Profesional', 700000, 'Tingkatkan efisiensi manajemen sekolah dengan fitur lengkap yang terintegrasi. Dapatkan akses ke modul keuangan, perpustakaan, dan sistem penilaian yang canggih. Ideal untuk sekolah menengah dengan kebutuhan administrasi yang lebih kompleks.', '\"[\\\"Semua Fitur Paket Starter\\\",\\\"Manajemen Data Siswa (tanpa batas)\\\",\\\"Modul Keuangan & Pembayaran\\\",\\\"Sistem Penilaian & Raport\\\",\\\"Manajemen Perpustakaan\\\",\\\"Modul Ekstrakurikuler\\\",\\\"Notifikasi & Pengingat Otomatis\\\",\\\"Customer Support Prioritas (WhatsApp)\\\"]\"', 1, '2026-08-13 18:33:32', '2026-08-13 18:33:32', NULL);
INSERT INTO `packages` VALUES (3, 'enterprise', 'Paket Enterprise', 0, 'Solusi komprehensif untuk institusi pendidikan berskala besar dengan kebutuhan spesifik. Dapatkan semua fitur premium, integrasi dengan sistem yang sudah ada, dan pengembangan kustom sesuai kebutuhan. Tersedia dedicated support dan pelatihan untuk staf.', '\"[\\\"Semua Fitur Paket Profesional\\\",\\\"Integrasi dengan Aplikasi Pihak Ketiga\\\",\\\"Kustomisasi Fitur Sesuai Kebutuhan\\\",\\\"API Akses untuk Pengembangan Lanjutan\\\",\\\"Multi-Cabang \\\\/ Multi-Yayasan\\\",\\\"Sistem Manajemen Aset & Inventaris\\\",\\\"Dashboard Analitik Lanjutan\\\",\\\"Dedicated Account Manager\\\",\\\"Pelatihan & Onboarding Tim\\\",\\\"Support 24\\\\/7 via Telepon & WhatsApp\\\"]\"', 1, '2026-08-13 18:33:32', '2026-08-13 18:33:32', NULL);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_position_unique`(`user_id` ASC, `position_id` ASC) USING BTREE,
  INDEX `position_id`(`position_id` ASC) USING BTREE,
  CONSTRAINT `position_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `position_user_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
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
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of positions
-- ----------------------------
INSERT INTO `positions` VALUES (1, 'chairman', 'Ketua Yayasan', 'Pemimpin tertinggi yayasan, bertanggung jawab atas seluruh kebijakan dan operasional yayasan.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (2, 'vice_chairman', 'Wakil Ketua Yayasan', 'Membantu Ketua Yayasan dan menggantikan posisi ketua jika berhalangan.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (3, 'secretary', 'Sekretaris Yayasan', 'Mengelola administrasi, surat-menyurat, kearsipan dan persiapan rapat.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (4, 'treasurer', 'Bendahara Yayasan', 'Mengelola keuangan, penyusunan anggaran, dan pelaporan keuangan yayasan.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (5, 'supervisor', 'Pengawas Yayasan', 'Mengawasi kinerja, kepatuhan dan jalannya operasional yayasan.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (6, 'school_principal', 'Kepala Sekolah', 'Pemimpin tertinggi di lingkungan sekolah, bertanggung jawab atas pendidikan dan manajemen sekolah.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (7, 'vice_principal', 'Wakil Kepala Sekolah', 'Membantu Kepala Sekolah di bidang kurikulum, kesiswaan, sarana prasarana, dll.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (8, 'head_administration', 'Kepala Tata Usaha', 'Pemimpin dan koordinator staf tata usaha sekolah.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (9, 'teacher', 'Guru', 'Tenaga pendidik yang bertugas merencanakan dan melaksanakan pembelajaran.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (10, 'honorary_teacher', 'Guru Honorer', 'Guru dengan status tenaga pengajar tidak tetap/honorer.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (11, 'admin_staff', 'Staf Tata Usaha', 'Melaksanakan kegiatan administrasi dan ketatausahaan sekolah.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (12, 'finance_staff', 'Staf Keuangan', 'Membantu pengelolaan keuangan, pembukuan dan transaksi harian.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (13, 'it_staff', 'Staf IT / Admin IT', 'Mengelola sistem informasi, jaringan, dan perangkat teknologi.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (14, 'librarian', 'Petugas Perpustakaan', 'Mengelola layanan dan koleksi perpustakaan sekolah.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (15, 'cleaner', 'Petugas Kebersihan', 'Menjaga kebersihan dan kerapian lingkungan sekolah.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (16, 'security_staff', 'Satpam / Penjaga Sekolah', 'Menjaga keamanan dan ketertiban lingkungan sekolah.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);
INSERT INTO `positions` VALUES (17, 'other', 'Lainnya', 'Jabatan lain yang belum tercantum dalam daftar.', 1, '2026-08-13 17:05:04', '2026-08-13 17:05:04', NULL);

-- ----------------------------
-- Table structure for role_user
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Peran utama yang aktif saat login',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `role_user_user_id_role_id_unique`(`user_id` ASC, `role_id` ASC) USING BTREE,
  UNIQUE INDEX `role_user_single_default_per_user`(`user_id` ASC, `is_default` ASC) USING BTREE,
  INDEX `role_user_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_user
-- ----------------------------
INSERT INTO `role_user` VALUES (1, 1, 1, 1, '2026-08-01 08:28:57', '2026-08-01 08:28:57', NULL);
INSERT INTO `role_user` VALUES (2, 1, 2, 0, '2026-08-01 08:28:57', '2026-08-01 08:28:57', NULL);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'contoh: admin, kasir, gudang',
  `display_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_unique`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'super-admin', 'Super Administrator', 'Memiliki akses penuh ke seluruh sistem', '2026-08-01 08:02:24', '2026-08-01 08:02:24', NULL);
INSERT INTO `roles` VALUES (2, 'admin', 'Administrator', 'Mengelola konten dan pengguna biasa', '2026-08-01 08:02:24', '2026-08-01 08:02:24', NULL);
INSERT INTO `roles` VALUES (3, 'user', 'Pengguna', 'Pengguna biasa', '2026-08-01 08:02:24', '2026-08-01 08:02:24', NULL);

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rooms_code_unique`(`code`) USING BTREE,
  INDEX `rooms_floor_id_foreign`(`floor_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of rooms
-- ----------------------------

-- ----------------------------
-- Table structure for schools
-- ----------------------------
DROP TABLE IF EXISTS `schools`;
CREATE TABLE `schools`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `npsn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `total_student` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('trial','active','suspended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'trial',
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
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
  INDEX `sessions_user_id_index`(`user_id`) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('bqPjeEvC4Zdbby5YLaNoCtGQQsoneLxnPdBg55Ux', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoiQ1REMVp6ZVl6RTJqMHBVeFpUQnJnRjR5SUNwUGNsV2U4V25MeW1pTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yb29tIjtzOjU6InJvdXRlIjtzOjEwOiJyb29tLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxMToidHJpYWxfdG9rZW4iO086MzU6IlJhbXNleVxVdWlkXExhenlcTGF6eVV1aWRGcm9tU3RyaW5nIjoxOntzOjY6InN0cmluZyI7czozNjoiM2E1ZWM5NTEtMmZkOS00MWYxLWFlZmUtN2JiZjE0YTE1OGQxIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6InVzZXJfYXV0aCI7YTo3OntzOjg6ImxvZ2luX2FzIjtzOjExOiJzdXBlci1hZG1pbiI7czoxNDoiYWN0aXZlX3JvbGVfaWQiO2k6MTtzOjEzOiJhdXRoX3JvbGVfaWRzIjtPOjI5OiJJbGx1bWluYXRlXFN1cHBvcnRcQ29sbGVjdGlvbiI6Mjp7czo4OiIAKgBpdGVtcyI7YToyOntpOjA7aToxO2k6MTtpOjI7fXM6Mjg6IgAqAGVzY2FwZVdoZW5DYXN0aW5nVG9TdHJpbmciO2I6MDt9czoxMDoiYXV0aF9yb2xlcyI7TzoyOToiSWxsdW1pbmF0ZVxTdXBwb3J0XENvbGxlY3Rpb24iOjI6e3M6ODoiACoAaXRlbXMiO2E6Mjp7aTowO3M6MTE6InN1cGVyLWFkbWluIjtpOjE7czo1OiJhZG1pbiI7fXM6Mjg6IgAqAGVzY2FwZVdoZW5DYXN0aW5nVG9TdHJpbmciO2I6MDt9czo5OiJhdXRoX3JvbGUiO086MTE6Ik1vZGVsc1xSb2xlIjozNjp7czoxMzoiACoAY29ubmVjdGlvbiI7czo1OiJteXNxbCI7czo4OiIAKgB0YWJsZSI7czo1OiJyb2xlcyI7czoxMzoiACoAcHJpbWFyeUtleSI7czoy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Mjg6IgAqAGVzY2FwZVdoZW5DYXN0aW5nVG9TdHJpbmciO2I6MDtzOjEzOiIAKgBhdHRyaWJ1dGVzIjthOjc6e3M6MjoiaWQiO2k6MjtzOjQ6Im5hbWUiO3M6NToiYWRtaW4iO3M6MTI6ImRpc3BsYXlfbmFtZSI7czoxMzoiQWRtaW5pc3RyYXRvciI7czoxMToiZGVzY3JpcHRpb24iO3M6MzU6Ik1lbmdlbG9sYSBrb250ZW4gZGFuIHBlbmdndW5hIGJpYXNhIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDI2LTA4LTAxIDA4OjAyOjI0IjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDI2LTA4LTAxIDA4OjAyOjI0IjtzOjEwOiJkZWxldGVkX2F0IjtOO31zOjExOiIAKgBvcmlnaW5hbCI7YToxMjp7czoyOiJpZCI7aToyO3M6NDoibmFtZSI7czo1OiJhZG1pbiI7czoxMjoiZGlzcGxheV9uYW1lIjtzOjEzOiJBZG1pbmlzdHJhdG9yIjtzOjExOiJkZXNjcmlwdGlvbiI7czozNToiTWVuZ2Vsb2xhIGtvbnRlbiBkYW4gcGVuZ2d1bmEgYmlhc2EiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6MTk6IjIwMjYtMDgtMDEgMDg6MDI6MjQiO3M6MTA6InVwZGF0ZWRfYXQiO3M6MTk6IjIwMjYtMDgtMDEgMDg6MDI6MjQiO3M6MTA6ImRlbGV0ZWRfYXQiO047czoxMzoicGl2b3RfdXNlcl9pZCI7aToxO3M6MTM6InBpdm90X3JvbGVfaWQiO2k6MjtzOjE2OiJwaXZvdF9pc19kZWZhdWx0IjtpOjA7czoxNjoicGl2b3RfY3JlYXRlZF9hdCI7czoxOToiMjAyNi0wOC0wMSAwODoyODo1NyI7czoxNjoicGl2b3RfdXBkYXRlZF9hdCI7czoxOToiMjAyNi0wOC0wMSAwODoyODo1NyI7fXM6MTA6IgAqAGNoYW5nZXMiO2E6MDp7fXM6MTE6IgAqAHByZXZpb3VzIjthOjA6e31zOjg6IgAqAGNhc3RzIjthOjM6e3M6MTA6ImNyZWF0ZWRfYXQiO3M6ODoiZGF0ZXRpbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6ODoiZGF0ZXRp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', 1786646561);

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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tr_education_level_unique`(`trial_registration_id` ASC, `education_level_id` ASC) USING BTREE,
  INDEX `education_level_id`(`education_level_id` ASC) USING BTREE,
  CONSTRAINT `trial_registration_education_level_ibfk_1` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `trial_registration_education_level_ibfk_2` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tr_module_unique`(`trial_registration_id` ASC, `module_id` ASC) USING BTREE,
  INDEX `module_id`(`module_id` ASC) USING BTREE,
  CONSTRAINT `trial_registration_module_ibfk_1` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `trial_registration_module_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tr_role_unique`(`trial_registration_id` ASC, `role_id` ASC) USING BTREE,
  INDEX `role_id`(`role_id` ASC) USING BTREE,
  CONSTRAINT `trial_registration_role_ibfk_1` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `trial_registration_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
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
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `token`(`token` ASC) USING BTREE,
  INDEX `school_id`(`school_id` ASC) USING BTREE,
  CONSTRAINT `trial_registrations_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of trial_registrations
-- ----------------------------
INSERT INTO `trial_registrations` VALUES (1, 'e10745cd-9189-4600-8ce3-627260763d67', 1, 'in_progress', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-13 09:30:33', '2026-08-13 09:30:33', NULL);
INSERT INTO `trial_registrations` VALUES (2, '3a5ec951-2fd9-41f1-aefe-7bbf14a158d1', 1, 'in_progress', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-13 16:11:52', '2026-08-13 16:11:52', NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Administrator Utama', 'admin@contoh.id', '2026-08-02 05:02:17', '$2y$12$5XKCmwhgZgZnIVp.Z1CWLOI3VuDFMgAsYMeRtx4ZS4zvu4x/XA14q', NULL, '2026-08-01 08:02:24', '2026-08-01 08:02:24', NULL);
INSERT INTO `users` VALUES (2, 'Eldridge Hane', 'fritsch.jett@example.com', '2026-08-02 05:02:17', '$2y$12$St7A5cqO00HDKftLmEvWPOz69aVV2iCTgd8dbbiqgSg4KjiwRq1na', 'L3AiUo0Yji', '2026-08-02 05:02:18', '2026-08-02 11:50:02', NULL);
INSERT INTO `users` VALUES (3, 'Nina Schmeler V', 'ernser.dee@example.com', '2026-08-02 05:02:17', '$2y$12$St7A5cqO00HDKftLmEvWPOz69aVV2iCTgd8dbbiqgSg4KjiwRq1na', 'Qz1Jv8zypA', '2026-08-02 05:02:18', '2026-08-02 11:47:12', NULL);
INSERT INTO `users` VALUES (4, 'Kiara Schuster', 'brionna.johnston@example.com', '2026-08-02 05:02:17', '$2y$12$St7A5cqO00HDKftLmEvWPOz69aVV2iCTgd8dbbiqgSg4KjiwRq1na', 'FQkJctwhOK', '2026-08-02 05:02:18', '2026-08-02 08:17:36', NULL);
INSERT INTO `users` VALUES (5, 'Freddy Crooks III', 'edgardo.buckridge@example.org', '2026-08-02 05:02:17', '$2y$12$St7A5cqO00HDKftLmEvWPOz69aVV2iCTgd8dbbiqgSg4KjiwRq1na', 'qmE3Tc78b2', '2026-08-02 05:02:18', '2026-08-02 12:13:05', NULL);
INSERT INTO `users` VALUES (6, 'Prof. Carlo Rogahn', 'adrienne.monahan@example.net', '2026-08-02 05:02:17', '$2y$12$St7A5cqO00HDKftLmEvWPOz69aVV2iCTgd8dbbiqgSg4KjiwRq1na', '8P3bRQAVry', '2026-08-02 05:02:18', '2026-08-02 11:48:33', NULL);
INSERT INTO `users` VALUES (7, 'Kattie McLaughlin', 'avery16@example.org', '2026-08-02 05:02:17', '$2y$12$St7A5cqO00HDKftLmEvWPOz69aVV2iCTgd8dbbiqgSg4KjiwRq1na', 'R39WBP2d20', '2026-08-02 05:02:18', '2026-08-02 12:17:07', NULL);
INSERT INTO `users` VALUES (8, 'Miss Antoinette Robel', 'kavon.botsford@example.org', '2026-08-02 05:02:17', '$2y$12$St7A5cqO00HDKftLmEvWPOz69aVV2iCTgd8dbbiqgSg4KjiwRq1na', '9igI2BqRes', '2026-08-02 05:02:18', '2026-08-02 08:30:32', NULL);
INSERT INTO `users` VALUES (9, 'Irving Mraz json', 'schroeder.camila@example.org', '2026-08-02 05:02:17', '$2y$12$St7A5cqO00HDKftLmEvWPOz69aVV2iCTgd8dbbiqgSg4KjiwRq1na', 'GalGTRywfN', '2026-08-02 05:02:18', '2026-08-03 03:39:04', NULL);
INSERT INTO `users` VALUES (10, 'Sydnie Lemke', 'ihamill@example.com', '2026-08-02 05:02:17', '$2y$12$St7A5cqO00HDKftLmEvWPOz69aVV2iCTgd8dbbiqgSg4KjiwRq1na', 'hNzt3smI1D', '2026-08-02 05:02:18', '2026-08-03 03:37:49', NULL);
INSERT INTO `users` VALUES (11, 'Kristin Kessler', 'glenna.breitenberg@example.org', '2026-08-02 05:02:17', '$2y$12$St7A5cqO00HDKftLmEvWPOz69aVV2iCTgd8dbbiqgSg4KjiwRq1na', 'PDnOWZ4M9O', '2026-08-02 05:02:18', '2026-08-02 08:24:53', NULL);

SET FOREIGN_KEY_CHECKS = 1;
