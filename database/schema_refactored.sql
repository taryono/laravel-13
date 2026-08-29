/*
Refactored schema
Basis: laravel-13 dump (28/08/2026)

Perubahan utama dari versi asli:
1. Semua table diseragamkan ke ENGINE = InnoDB (MyISAM tidak support FK & transaction).
2. Kolasi diseragamkan ke utf8mb4_unicode_ci di semua table (sebelumnya ada yang utf8mb4_0900_ai_ci).
3. `deleted_at` diseragamkan jadi `timestamp NULL DEFAULT NULL` di semua table (sebelumnya campur timestamp/datetime).
4. `is_active` diseragamkan jadi `tinyint(1) NOT NULL DEFAULT 1`.
5. Menambahkan FK constraint yang sebelumnya cuma berupa index biasa:
- floors.building_id -> buildings.id
- rooms.floor_id -> floors.id
- facilities.facility_category_id -> facility_categories.id
- facilities.room_id -> rooms.id
- trial_registrations.user_id -> users.id
- trial_registrations.position_id -> positions.id (tipe diubah ke bigint unsigned agar match)
6. student_ranges.id diubah dari int ke bigint unsigned, agar konsisten dan aman jika suatu saat di-FK.
7. Kolom hitungan (total_floors, capacity, quantity) diubah jadi INT UNSIGNED.
8. FIX BUG: unique index `role_user_single_default_per_user` pada (user_id, is_default)
sebelumnya membatasi user maksimal 2 role total (1 default + 1 non-default).
Diganti dengan generated column trick supaya:
- satu user boleh punya banyak role non-default
- tapi maksimal 1 role yang ditandai is_default = 1
9. CATATAN: `total_student` (schools & trial_registrations) masih varchar karena belum jelas
apakah harus di-link ke `student_ranges`. Kalau iya, sebaiknya diganti jadi
`student_range_id BIGINT UNSIGNED` dengan FK ke student_ranges.id — perlu konfirmasi
business rule dulu sebelum diubah, jadi TIDAK saya ubah di file ini.
 */
SET
  NAMES utf8mb4;

SET
  FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for buildings
-- ----------------------------
DROP TABLE IF EXISTS `buildings`;

CREATE TABLE
  `buildings` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `name` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `address` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
      `total_floors` int UNSIGNED NOT NULL DEFAULT 0,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE,
      UNIQUE INDEX `buildings_code_unique` (`code`) USING BTREE
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for education_levels
-- ----------------------------
DROP TABLE IF EXISTS `education_levels`;

CREATE TABLE
  `education_levels` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `name` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE,
      UNIQUE INDEX `code` (`code` ASC) USING BTREE
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for facility_categories
-- ----------------------------
DROP TABLE IF EXISTS `facility_categories`;

CREATE TABLE
  `facility_categories` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `name` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `description` text CHARACTER,
      `is_active` tinyint (1) NOT NULL DEFAULT 1,
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE,
      UNIQUE INDEX `facility_categories_code_unique` (`code`) USING BTREE
  ) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for floors
-- ----------------------------
DROP TABLE IF EXISTS `floors`;

CREATE TABLE
  `floors` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `building_id` bigint UNSIGNED NOT NULL,
    `code` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `name` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `floor_number` int NOT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE,
      UNIQUE INDEX `floors_code_unique` (`code`) USING BTREE,
      INDEX `floors_building_id_foreign` (`building_id`) USING BTREE,
      CONSTRAINT `floors_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rooms
-- ----------------------------
DROP TABLE IF EXISTS `rooms`;

CREATE TABLE
  `rooms` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `floor_id` bigint UNSIGNED NOT NULL,
    `code` varchar(100) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `name` varchar(191) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `room_number` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
      `capacity` int UNSIGNED NULL DEFAULT 0,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE,
      UNIQUE INDEX `rooms_code_unique` (`code`) USING BTREE,
      INDEX `rooms_floor_id_foreign` (`floor_id`) USING BTREE,
      CONSTRAINT `rooms_floor_id_foreign` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for facilities
-- ----------------------------
DROP TABLE IF EXISTS `facilities`;

CREATE TABLE
  `facilities` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `facility_category_id` bigint UNSIGNED NOT NULL,
    `room_id` bigint UNSIGNED NOT NULL,
    `code` varchar(100) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `name` varchar(191) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `description` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
      `is_active` tinyint (1) NOT NULL DEFAULT 1,
      `quantity` int UNSIGNED NOT NULL DEFAULT 1,
      `is_available` tinyint (1) NULL DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE,
      UNIQUE INDEX `facilities_code_unique` (`code`) USING BTREE,
      INDEX `facilities_facility_category_id_foreign` (`facility_category_id`) USING BTREE,
      INDEX `facilities_room_id_foreign` (`room_id`) USING BTREE,
      CONSTRAINT `facilities_facility_category_id_foreign` FOREIGN KEY (`facility_category_id`) REFERENCES `facility_categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
      CONSTRAINT `facilities_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
  ) ENGINE = InnoDB AUTO_INCREMENT = 52 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for modules
-- ----------------------------
DROP TABLE IF EXISTS `modules`;

CREATE TABLE
  `modules` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `name` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `description` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
      `icon` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
      `is_active` tinyint (1) NOT NULL DEFAULT 1,
      `price` decimal(10, 2) NULL DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE,
      UNIQUE INDEX `code` (`code` ASC) USING BTREE
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for packages
-- ----------------------------
DROP TABLE IF EXISTS `packages`;

CREATE TABLE
  `packages` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `category_id` bigint UNSIGNED NULL,
      `name` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `price` int NOT NULL DEFAULT 0,
      `description` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `features` json NULL,
      `is_active` tinyint (1) NOT NULL DEFAULT 1,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE,
      FOREIGN KEY (`category_id`) REFERENCES `package_categories` (`id`),
      UNIQUE INDEX `packages_code_unique` (`code`) USING BTREE
  ) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for positions
-- ----------------------------
DROP TABLE IF EXISTS `positions`;

CREATE TABLE
  `positions` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `name` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `description` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
      `is_active` tinyint (1) NOT NULL DEFAULT 1,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE,
      UNIQUE INDEX `code` (`code` ASC) USING BTREE
  ) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for position_user
-- ----------------------------
DROP TABLE IF EXISTS `position_user`;

CREATE TABLE
  `position_user` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` bigint UNSIGNED NOT NULL,
    `position_id` bigint UNSIGNED NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE INDEX `user_position_unique` (`user_id` ASC, `position_id` ASC) USING BTREE,
    INDEX `position_id` (`position_id` ASC) USING BTREE,
    CONSTRAINT `position_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT `position_user_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;

CREATE TABLE
  `roles` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(191) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'contoh: admin, kasir, gudang',
      `display_name` varchar(191) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `description` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE,
      UNIQUE INDEX `roles_name_unique` (`name` ASC) USING BTREE
  ) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for role_user
-- FIX: sebelumnya unique(user_id, is_default) membatasi max 2 role/user.
-- Sekarang pakai generated column supaya hanya membatasi is_default = 1,
-- role non-default tetap boleh banyak per user.
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;

CREATE TABLE
  `role_user` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` bigint UNSIGNED NOT NULL,
    `role_id` bigint UNSIGNED NOT NULL,
    `is_default` tinyint (1) NOT NULL DEFAULT 0 COMMENT 'Peran utama yang aktif saat login',
    `only_default_user_id` bigint UNSIGNED GENERATED ALWAYS AS (IF (`is_default` = 1, `user_id`, NULL)) VIRTUAL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE INDEX `role_user_user_id_role_id_unique` (`user_id` ASC, `role_id` ASC) USING BTREE,
    UNIQUE INDEX `role_user_one_default_per_user` (`only_default_user_id` ASC) USING BTREE,
    INDEX `role_user_role_id_foreign` (`role_id` ASC) USING BTREE,
    CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
  ) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for schools
-- ----------------------------
DROP TABLE IF EXISTS `schools`;

CREATE TABLE
  `schools` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `npsn` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
      `total_student` varchar(191) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
      `address` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
      `status` enum ('trial', 'active', 'suspended') CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'trial',
      `trial_ends_at` timestamp NULL DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for student_ranges
-- (id diubah ke bigint unsigned agar konsisten dgn tabel lain)
-- ----------------------------
DROP TABLE IF EXISTS `student_ranges`;

CREATE TABLE
  `student_ranges` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(50) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `min_value` int NULL DEFAULT NULL,
      `max_value` int NULL DEFAULT NULL,
      `sort_order` int NOT NULL,
      `is_active` tinyint (1) NOT NULL DEFAULT 1,
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      `deleted_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE
  ) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trial_registrations
-- (position_id diubah ke bigint unsigned + ditambah FK ke positions & users)
-- ----------------------------
DROP TABLE IF EXISTS `trial_registrations`;

CREATE TABLE
  `trial_registrations` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `token` char(36) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      `current_step` tinyint UNSIGNED NOT NULL DEFAULT 1,
      `status` enum ('in_progress', 'completed', 'abandoned') CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
      `nama_sekolah` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
      `npsn` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
      `total_student` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
      `address` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
      `nama_user` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
      `position_id` bigint UNSIGNED NULL DEFAULT NULL,
      `notif_whatsapp` tinyint (1) NOT NULL DEFAULT 0,
      `notif_email` tinyint (1) NOT NULL DEFAULT 0,
      `notif_sms` tinyint (1) NOT NULL DEFAULT 0,
      `email` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
      `no_hp` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
      `password` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
      `school_id` bigint UNSIGNED NULL DEFAULT NULL,
      `user_id` bigint UNSIGNED NULL DEFAULT NULL,
      `completed_at` timestamp NULL DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`) USING BTREE,
      UNIQUE INDEX `token` (`token` ASC) USING BTREE,
      INDEX `school_id` (`school_id` ASC) USING BTREE,
      INDEX `trial_registrations_user_id_foreign` (`user_id` ASC) USING BTREE,
      INDEX `trial_registrations_position_id_foreign` (`position_id` ASC) USING BTREE,
      CONSTRAINT `trial_registrations_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
      CONSTRAINT `trial_registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
      CONSTRAINT `trial_registrations_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
  ) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trial_registration_education_level
-- ----------------------------
DROP TABLE IF EXISTS `trial_registration_education_level`;

CREATE TABLE
  `trial_registration_education_level` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `trial_registration_id` bigint UNSIGNED NOT NULL,
    `education_level_id` bigint UNSIGNED NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE INDEX `tr_education_level_unique` (
      `trial_registration_id` ASC,
      `education_level_id` ASC
    ) USING BTREE,
    INDEX `education_level_id` (`education_level_id` ASC) USING BTREE,
    CONSTRAINT `trial_registration_education_level_ibfk_1` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT `trial_registration_education_level_ibfk_2` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trial_registration_module
-- ----------------------------
DROP TABLE IF EXISTS `trial_registration_module`;

CREATE TABLE
  `trial_registration_module` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `trial_registration_id` bigint UNSIGNED NOT NULL,
    `module_id` bigint UNSIGNED NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE INDEX `tr_module_unique` (`trial_registration_id` ASC, `module_id` ASC) USING BTREE,
    INDEX `module_id` (`module_id` ASC) USING BTREE,
    CONSTRAINT `trial_registration_module_ibfk_1` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT `trial_registration_module_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trial_registration_role
-- ----------------------------
DROP TABLE IF EXISTS `trial_registration_role`;

CREATE TABLE
  `trial_registration_role` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `trial_registration_id` bigint UNSIGNED NOT NULL,
    `role_id` bigint UNSIGNED NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE INDEX `tr_role_unique` (`trial_registration_id` ASC, `role_id` ASC) USING BTREE,
    INDEX `role_id` (`role_id` ASC) USING BTREE,
    CONSTRAINT `trial_registration_role_ibfk_1` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT `trial_registration_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
  ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trial_registration_role
-- ----------------------------
DROP TABLE IF EXISTS `package_modules`;

CREATE TABLE
  `package_modules` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `package_id` bigint UNSIGNED NOT NULL,
    `module_id` bigint UNSIGNED NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `package_module_unique` (`package_id`, `module_id`),
    FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `package_categories`;

CREATE TABLE
  `package_categories` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description` text COLLATE utf8mb4_unicode_ci NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `subscriptions`;

CREATE TABLE
  `subscriptions` (
    `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` bigint UNSIGNED NOT NULL,
    `package_id` bigint UNSIGNED NOT NULL,
    `started_at` timestamp NOT NULL,
    `expires_at` timestamp NULL,
    `status` enum ('active', 'expired', 'cancelled') NOT NULL DEFAULT 'active',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `deleted_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

SET
  FOREIGN_KEY_CHECKS = 1;