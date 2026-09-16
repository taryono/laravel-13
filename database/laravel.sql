/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80200 (8.2.0)
 Source Host           : localhost:3306
 Source Schema         : laravel

 Target Server Type    : MySQL
 Target Server Version : 80200 (8.2.0)
 File Encoding         : 65001

 Date: 17/09/2026 01:46:20
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for academic_calendar_events
-- ----------------------------
DROP TABLE IF EXISTS `academic_calendar_events`;
CREATE TABLE `academic_calendar_events`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `event_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NULL DEFAULT NULL,
  `start_time` time NULL DEFAULT NULL,
  `end_time` time NULL DEFAULT NULL,
  `location` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_holiday` tinyint(1) NOT NULL DEFAULT 0,
  `is_all_day` tinyint(1) NOT NULL DEFAULT 0,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `academic_calendar_events_school_index`(`school_id` ASC) USING BTREE,
  INDEX `academic_calendar_events_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `academic_calendar_events_type_index`(`event_type` ASC) USING BTREE,
  INDEX `academic_calendar_events_dates_index`(`start_date` ASC, `end_date` ASC) USING BTREE,
  INDEX `academic_calendar_events_is_holiday_index`(`is_holiday` ASC) USING BTREE,
  INDEX `academic_calendar_events_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `academic_calendar_events_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `academic_calendar_events_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `academic_calendar_events_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `academic_calendar_events_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of academic_calendar_events
-- ----------------------------

-- ----------------------------
-- Table structure for academic_years
-- ----------------------------
DROP TABLE IF EXISTS `academic_years`;
CREATE TABLE `academic_years`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint NULL DEFAULT NULL,
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
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of academic_years
-- ----------------------------
INSERT INTO `academic_years` VALUES (1, 1, 1, 'Tahun Ajaran 2023/2024 Ganjil', '2023-2024-GANJIL', '2023-07-01', '2023-12-31', '2023-07-15', '2023-12-20', 0, '2023-05-01', '2023-06-30', 1, 0, '2026-09-13 17:06:39', '2026-09-13 17:06:39', NULL, NULL, NULL);
INSERT INTO `academic_years` VALUES (2, 1, 1, 'Tahun Ajaran 2023/2024 Genap', '2023-2024-GENAP', '2024-01-01', '2024-06-30', '2024-01-10', '2024-06-15', 0, '2023-11-01', '2023-12-15', 1, 0, '2026-09-13 17:06:39', '2026-09-13 17:06:39', NULL, NULL, NULL);
INSERT INTO `academic_years` VALUES (3, 1, 1, 'Tahun Ajaran 2024/2025 Ganjil', '2024-2025-GANJIL', '2024-07-01', '2024-12-31', '2024-07-15', '2024-12-20', 0, '2024-05-01', '2024-06-30', 1, 0, '2026-09-13 17:06:39', '2026-09-13 17:06:39', NULL, NULL, NULL);
INSERT INTO `academic_years` VALUES (4, 1, 1, 'Tahun Ajaran 2024/2025 Genap', '2024-2025-GENAP', '2025-01-01', '2025-06-30', '2025-01-10', '2025-06-15', 0, '2024-11-01', '2024-12-15', 1, 0, '2026-09-13 17:06:39', '2026-09-13 17:06:39', NULL, NULL, NULL);
INSERT INTO `academic_years` VALUES (5, 1, 1, 'Tahun Ajaran 2025/2026 Ganjil', '2025-2026-GANJIL', '2025-07-01', '2025-12-31', '2025-07-15', '2025-12-20', 1, '2025-05-01', '2025-06-30', 1, 1, '2026-09-13 17:06:39', '2026-09-13 17:06:39', NULL, NULL, NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of account_categories
-- ----------------------------
INSERT INTO `account_categories` VALUES (1, 1, 'ASSET', 'Aset (Aktiva)', 'aset', 'debit', 'Segala sesuatu yang dimiliki yayasan yang memiliki nilai ekonomis', 1, '2026-09-12 14:13:59', '2026-09-12 14:13:59', NULL, NULL, NULL);
INSERT INTO `account_categories` VALUES (2, 1, 'LIABILITY', 'Kewajiban (Liabilitas)', 'kewajiban', 'kredit', 'Semua utang yayasan kepada pihak ketiga', 1, '2026-09-12 14:13:59', '2026-09-12 14:13:59', NULL, NULL, NULL);
INSERT INTO `account_categories` VALUES (3, 1, 'EQUITY', 'Ekuitas / Aset Bersih', 'ekuitas', 'kredit', 'Kekayaan bersih yayasan (Aset - Kewajiban)', 1, '2026-09-12 14:13:59', '2026-09-12 14:13:59', NULL, NULL, NULL);
INSERT INTO `account_categories` VALUES (4, 1, 'REVENUE', 'Pendapatan', 'pendapatan', 'kredit', 'Semua pemasukan yayasan dari berbagai sumber', 1, '2026-09-12 14:13:59', '2026-09-12 14:13:59', NULL, NULL, NULL);
INSERT INTO `account_categories` VALUES (5, 1, 'EXPENSE', 'Beban', 'beban', 'debit', 'Semua pengeluaran yayasan untuk operasional', 1, '2026-09-12 14:13:59', '2026-09-12 14:13:59', NULL, NULL, NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 73 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of account_mappings
-- ----------------------------
INSERT INTO `account_mappings` VALUES (1, 1, 'payroll_component', 'SALARY_TK', 73, 42, 'Gaji pokok guru TK', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (2, 1, 'payroll_component', 'SALARY_SD', 74, 42, 'Gaji pokok guru SD', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (3, 1, 'payroll_component', 'SALARY_SMP', 75, 42, 'Gaji pokok guru SMP', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (4, 1, 'payroll_component', 'SALARY_SMA', 76, 42, 'Gaji pokok guru SMA', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (5, 1, 'payroll_component', 'SALARY_UNIV', 77, 42, 'Gaji pokok dosen universitas', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (6, 1, 'payroll_component', 'SALARY_STAFF', 84, 42, 'Gaji staf administrasi & TU', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (7, 1, 'payroll_component', 'ALLOWANCE_CERTIFICATION', 73, 42, 'Tunjangan sertifikasi guru', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (8, 1, 'payroll_component', 'ALLOWANCE_FAMILY', 84, 42, 'Tunjangan keluarga', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (9, 1, 'payroll_component', 'ALLOWANCE_FUNCTIONAL', 84, 42, 'Tunjangan fungsional', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (10, 1, 'payroll_component', 'BONUS_THR', 84, 42, 'THR (Tunjangan Hari Raya)', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (11, 1, 'payroll_component', 'BONUS_ANNUAL', 84, 42, 'Bonus tahunan', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (12, 1, 'sales', 'SPP_TK', 21, 54, 'Pendapatan SPP TK', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (13, 1, 'sales', 'SPP_SD', 22, 55, 'Pendapatan SPP SD', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (14, 1, 'sales', 'SPP_SMP', 23, 56, 'Pendapatan SPP SMP', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (15, 1, 'sales', 'SPP_SMA', 24, 57, 'Pendapatan SPP SMA', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (16, 1, 'sales', 'UKT_UNIV', 25, 58, 'Pendapatan UKT Universitas', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (17, 1, 'sales', 'FEE_DEVELOPMENT', 21, 59, 'Pendapatan uang gedung / pengembangan', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (18, 1, 'sales', 'FEE_REGISTRATION_TK', 21, 60, 'Pendapatan pendaftaran siswa baru TK', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (19, 1, 'sales', 'FEE_REGISTRATION_SD', 22, 60, 'Pendapatan pendaftaran siswa baru SD', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (20, 1, 'sales', 'FEE_REGISTRATION_SMP', 23, 60, 'Pendapatan pendaftaran siswa baru SMP', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (21, 1, 'sales', 'FEE_REGISTRATION_SMA', 24, 60, 'Pendapatan pendaftaran siswa baru SMA', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (22, 1, 'sales', 'FEE_REGISTRATION_UNIV', 25, 60, 'Pendapatan pendaftaran mahasiswa baru', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (23, 1, 'sales', 'RENTAL_FACILITY', 18, 61, 'Pendapatan sewa fasilitas (gedung, lapangan)', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (24, 1, 'sales', 'CANTEEN_RENT', 18, 62, 'Pendapatan sewa kantin', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (25, 1, 'sales', 'STUDY_TOUR', 21, 63, 'Pendapatan kegiatan study tour', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (26, 1, 'sales', 'GRANT_BOS', 18, 64, 'Pendapatan hibah BOS', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (27, 1, 'sales', 'GRANT_BOP', 18, 65, 'Pendapatan hibah BOP', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (28, 1, 'sales', 'GRANT_BOPTN', 18, 66, 'Pendapatan hibah BOPTN', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (29, 1, 'sales', 'DONATION_ALUMNI', 18, 67, 'Donasi dari alumni', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (30, 1, 'sales', 'DONATION_CORPORATE', 18, 68, 'Donasi dari perusahaan', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (31, 1, 'sales', 'DONATION_PARENTS', 18, 69, 'Sumbangan sukarela orang tua', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (32, 1, 'purchase', 'PURCHASE_ATK', 86, 41, 'Pembelian ATK kantor', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (33, 1, 'purchase', 'PURCHASE_PRACTICUM', 78, 41, 'Pembelian bahan praktikum lab', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (34, 1, 'purchase', 'PURCHASE_BOOKS', 79, 41, 'Pembelian buku perpustakaan', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (35, 1, 'purchase', 'PURCHASE_EQUIPMENT', 36, 41, 'Pembelian peralatan & mesin', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (36, 1, 'purchase', 'PURCHASE_IT', 37, 41, 'Pembelian komputer & IT equipment', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (37, 1, 'purchase', 'PURCHASE_VEHICLE', 35, 46, 'Pembelian kendaraan operasional', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (38, 1, 'purchase', 'PURCHASE_FURNITURE', 36, 41, 'Pembelian meubelair kantor', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (39, 1, 'purchase', 'PURCHASE_INVENTORY_ATK', 27, 41, 'Pembelian persediaan ATK', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (40, 1, 'purchase', 'PURCHASE_INVENTORY_PRACTICUM', 28, 41, 'Pembelian persediaan bahan praktikum', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (41, 1, 'expense', 'EXP_ELECTRICITY', 85, 41, 'Tagihan listrik', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (42, 1, 'expense', 'EXP_WATER', 85, 41, 'Tagihan air', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (43, 1, 'expense', 'EXP_INTERNET', 90, 41, 'Tagihan internet', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (44, 1, 'expense', 'EXP_PHONE', 90, 41, 'Tagihan telepon', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (45, 1, 'expense', 'EXP_MAINTENANCE', 87, 41, 'Biaya perawatan gedung', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (46, 1, 'expense', 'EXP_TRANSPORT', 88, 41, 'Biaya transportasi & perjalanan dinas', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (47, 1, 'expense', 'EXP_INSURANCE', 89, 41, 'Premi asuransi', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (48, 1, 'expense', 'EXP_TRAINING', 91, 41, 'Biaya pelatihan & pengembangan SDM', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (49, 1, 'expense', 'EXP_MARKETING', 92, 41, 'Biaya pemasaran & promosi', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (50, 1, 'expense', 'EXP_EXTRACURRICULAR', 80, 41, 'Biaya kegiatan ekstrakurikuler', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (51, 1, 'expense', 'EXP_GRADUATION', 81, 41, 'Biaya wisuda & pelepasan', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (52, 1, 'expense', 'EXP_COMPETITION', 82, 41, 'Biaya perlombaan & kompetisi', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (53, 1, 'expense', 'EXP_SCHOLARSHIP', 83, 41, 'Biaya pemberian beasiswa', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (54, 1, 'expense', 'EXP_TAX_INCOME', 96, 45, 'Pembayaran PPh', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (55, 1, 'expense', 'EXP_TAX_ART21', 96, 45, 'PPh Pasal 21 (karyawan)', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (56, 1, 'expense', 'EXP_BANK_ADMIN', 97, 18, 'Biaya administrasi bank', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (57, 1, 'expense', 'EXP_BANK_TRANSFER', 97, 18, 'Biaya transfer bank', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (58, 1, 'expense', 'EXP_PENALTY', 98, 41, 'Denda keterlambatan pembayaran', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (59, 1, 'expense', 'EXP_BAD_DEBT', 99, 21, 'Penghapusan piutang tak tertagih', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (60, 1, 'expense', 'EXP_DEPRECIATION_BUILDING', 93, 38, 'Penyusutan gedung', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (61, 1, 'expense', 'EXP_DEPRECIATION_VEHICLE', 94, 38, 'Penyusutan kendaraan', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (62, 1, 'expense', 'EXP_DEPRECIATION_EQUIPMENT', 95, 38, 'Penyusutan peralatan & IT', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (63, 1, 'expense', 'EXP_OTHER', 100, 41, 'Beban lain-lain', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (64, 1, 'expense', 'ADJ_BAD_DEBT', 99, 21, 'Jurnal penyesuaian cadangan piutang tak tertagih', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (65, 1, 'expense', 'ADJ_DEPRECIATION', 93, 38, 'Jurnal penyesuaian penyusutan aset', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (66, 1, 'expense', 'ADJ_PREPAID_INCOME', 44, 54, 'Jurnal penyesuaian pendapatan diterima di muka', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (67, 1, 'expense', 'ADJ_ACCRUED_EXPENSE', 85, 41, 'Jurnal penyesuaian beban yang masih harus dibayar', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (68, 1, 'expense', 'CASH_RECEIPT_SPP', 17, 21, 'Penerimaan kas dari SPP', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (69, 1, 'expense', 'CASH_RECEIPT_DONATION', 17, 67, 'Penerimaan kas dari donasi', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (70, 1, 'expense', 'CASH_PAYMENT_EXPENSE', 86, 17, 'Pembayaran beban dengan kas', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (71, 1, 'expense', 'BANK_TRANSFER_IN', 18, 17, 'Transfer masuk ke rekening bank', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);
INSERT INTO `account_mappings` VALUES (72, 1, 'expense', 'BANK_TRANSFER_OUT', 17, 18, 'Transfer keluar dari rekening bank', 1, '2026-09-13 17:40:18', '2026-09-13 17:40:18', NULL, NULL, NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
  `account_type` enum('header','detail','postable') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'detail',
  `normal_balance` enum('debit','kredit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `opening_balance` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `currency_id` int NULL DEFAULT NULL,
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
) ENGINE = InnoDB AUTO_INCREMENT = 101 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of accounts
-- ----------------------------
INSERT INTO `accounts` VALUES (1, 1, '1-1000', 'ASET LANCAR', 1, NULL, 'header', 'debit', 'IDR', 'Aset yang dapat dicairkan dalam waktu kurang dari 1 tahun', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (2, 1, '1-2000', 'ASET TETAP', 1, NULL, 'header', 'debit', 'IDR', 'Aset berwujud yang dimiliki untuk operasional jangka panjang', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (3, 1, '1-3000', 'ASET LAINNYA', 1, NULL, 'header', 'debit', 'IDR', 'Aset non-lancar lainnya seperti dana abadi', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (4, 1, '2-1000', 'KEWAJIBAN LANCAR', 2, NULL, 'header', 'kredit', 'IDR', 'Utang yang harus dibayar kurang dari 1 tahun', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (5, 1, '2-2000', 'KEWAJIBAN JANGKA PANJANG', 2, NULL, 'header', 'kredit', 'IDR', 'Utang dengan jatuh tempo lebih dari 1 tahun', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (6, 1, '3-1000', 'ASET BERSIH TIDAK TERIKAT', 3, NULL, 'header', 'kredit', 'IDR', 'Surplus yang bisa digunakan bebas oleh yayasan', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (7, 1, '3-2000', 'ASET BERSIH TERIKAT TEMPORER', 3, NULL, 'header', 'kredit', 'IDR', 'Dana hibah yang hanya boleh digunakan untuk tujuan tertentu dalam waktu tertentu', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (8, 1, '3-3000', 'ASET BERSIH TERIKAT PERMANEN', 3, NULL, 'header', 'kredit', 'IDR', 'Dana abadi yang pokoknya tidak boleh dipakai', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (9, 1, '4-1000', 'PENDAPATAN SPP / UKT', 4, NULL, 'header', 'kredit', 'IDR', 'Pendapatan dari SPP dan UKT semua jenjang', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (10, 1, '4-2000', 'PENDAPATAN NON-SPP', 4, NULL, 'header', 'kredit', 'IDR', 'Pendapatan selain SPP/UKT', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (11, 1, '4-3000', 'PENDAPATAN HIBAH & DONASI', 4, NULL, 'header', 'kredit', 'IDR', 'Pendapatan dari hibah pemerintah, donasi, sumbangan', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (12, 1, '4-4000', 'PENDAPATAN LAIN-LAIN', 4, NULL, 'header', 'kredit', 'IDR', 'Pendapatan investasi, sewa, dan lainnya', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (13, 1, '5-1000', 'BEBAN PROGRAM PENDIDIKAN', 5, NULL, 'header', 'debit', 'IDR', 'Biaya langsung untuk kegiatan belajar-mengajar', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (14, 1, '5-2000', 'BEBAN ADMINISTRASI & UMUM', 5, NULL, 'header', 'debit', 'IDR', 'Biaya operasional kantor dan administrasi', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (15, 1, '5-3000', 'BEBAN PENYUSUTAN', 5, NULL, 'header', 'debit', 'IDR', 'Penyusutan aset tetap', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (16, 1, '5-4000', 'BEBAN PAJAK & NON-OPERASIONAL', 5, NULL, 'header', 'debit', 'IDR', 'Beban pajak dan biaya non-operasional lainnya', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:41', '2026-09-12 14:25:41', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (17, 1, '1-1100', 'Kas', 1, 1, 'detail', 'debit', 'IDR', 'Uang tunai di tangan', 50000000.00, 50000000.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (18, 1, '1-1200', 'Bank BCA', 1, 1, 'detail', 'debit', 'IDR', 'Rekening Yayasan di BCA', 250000000.00, 250000000.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (19, 1, '1-1210', 'Bank Mandiri', 1, 1, 'detail', 'debit', 'IDR', 'Rekening Yayasan di Bank Mandiri', 150000000.00, 150000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (20, 1, '1-1220', 'Bank BRI', 1, 1, 'detail', 'debit', 'IDR', 'Rekening Yayasan di Bank BRI', 100000000.00, 100000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (21, 1, '1-1300', 'Piutang SPP TK', 1, 1, 'detail', 'debit', 'IDR', 'Tagihan SPP siswa TK yang belum dibayar', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (22, 1, '1-1301', 'Piutang SPP SD', 1, 1, 'detail', 'debit', 'IDR', 'Tagihan SPP siswa SD yang belum dibayar', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (23, 1, '1-1302', 'Piutang SPP SMP', 1, 1, 'detail', 'debit', 'IDR', 'Tagihan SPP siswa SMP yang belum dibayar', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (24, 1, '1-1303', 'Piutang SPP SMA', 1, 1, 'detail', 'debit', 'IDR', 'Tagihan SPP siswa SMA yang belum dibayar', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (25, 1, '1-1304', 'Piutang UKT Universitas', 1, 1, 'detail', 'debit', 'IDR', 'Tagihan UKT mahasiswa yang belum dibayar', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (26, 1, '1-1400', 'Piutang Hibah', 1, 1, 'detail', 'debit', 'IDR', 'Hibah yang sudah dijanjikan tapi belum diterima', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (27, 1, '1-1500', 'Persediaan ATK', 1, 1, 'detail', 'debit', 'IDR', 'Stok alat tulis kantor dan sekolah', 15000000.00, 15000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (28, 1, '1-1600', 'Persediaan Bahan Praktikum', 1, 1, 'detail', 'debit', 'IDR', 'Stok bahan praktikum laboratorium', 5000000.00, 5000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (29, 1, '1-2100', 'Tanah', 1, 2, 'detail', 'debit', 'IDR', 'Tanah milik yayasan', 5000000000.00, 5000000000.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (30, 1, '1-2200', 'Gedung TK', 1, 2, 'detail', 'debit', 'IDR', 'Gedung untuk jenjang TK', 500000000.00, 500000000.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (31, 1, '1-2210', 'Gedung SD', 1, 2, 'detail', 'debit', 'IDR', 'Gedung untuk jenjang SD', 800000000.00, 800000000.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (32, 1, '1-2220', 'Gedung SMP', 1, 2, 'detail', 'debit', 'IDR', 'Gedung untuk jenjang SMP', 1000000000.00, 1000000000.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (33, 1, '1-2230', 'Gedung SMA', 1, 2, 'detail', 'debit', 'IDR', 'Gedung untuk jenjang SMA', 1200000000.00, 1200000000.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (34, 1, '1-2240', 'Gedung Universitas', 1, 2, 'detail', 'debit', 'IDR', 'Gedung kampus universitas', 2000000000.00, 2000000000.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (35, 1, '1-2300', 'Kendaraan Operasional', 1, 2, 'detail', 'debit', 'IDR', 'Mobil dan kendaraan dinas yayasan', 350000000.00, 350000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (36, 1, '1-2400', 'Peralatan & Mesin', 1, 2, 'detail', 'debit', 'IDR', 'Mesin fotokopi, AC, peralatan dapur, dll', 200000000.00, 200000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (37, 1, '1-2500', 'Komputer & IT Equipment', 1, 2, 'detail', 'debit', 'IDR', 'Komputer, laptop, server, projector', 300000000.00, 300000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (38, 1, '1-2600', 'Akumulasi Penyusutan Aset Tetap', 1, 2, 'detail', 'kredit', 'IDR', 'Akumulasi penyusutan semua aset tetap (contra asset)', -50000000.00, -50000000.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (39, 1, '1-3100', 'Dana Abadi (Endowment)', 1, 3, 'detail', 'debit', 'IDR', 'Dana yang pokoknya tidak boleh dipakai, hanya hasil investasinya', 1000000000.00, 1000000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (40, 1, '1-3200', 'Investasi Jangka Panjang', 1, 3, 'detail', 'debit', 'IDR', 'Investasi saham, obligasi, reksadana', 500000000.00, 500000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (41, 1, '2-1100', 'Utang Usaha', 2, 4, 'detail', 'kredit', 'IDR', 'Utang kepada pemasok barang/jasa', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (42, 1, '2-1200', 'Utang Gaji & Tunjangan', 2, 4, 'detail', 'kredit', 'IDR', 'Gaji guru/staf yang belum dibayar', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (43, 1, '2-1300', 'Utang BPJS Ketenagakerjaan', 2, 4, 'detail', 'kredit', 'IDR', 'Iuran BPJS yang belum disetor', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (44, 1, '2-1400', 'Pendapatan Diterima di Muka (SPP)', 2, 4, 'detail', 'kredit', 'IDR', 'SPP yang sudah dibayar tapi belum menjadi hak yayasan (periode mendatang)', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (45, 1, '2-1500', 'Utang Pajak', 2, 4, 'detail', 'kredit', 'IDR', 'Pajak yang sudah dipotong/dihitung tapi belum disetor ke negara', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (46, 1, '2-2100', 'Utang Bank Jangka Panjang', 2, 5, 'detail', 'kredit', 'IDR', 'Utang bank untuk pembangunan gedung', 2000000000.00, 2000000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (47, 1, '3-1100', 'Saldo Aset Bersih Tidak Terikat', 3, 6, 'detail', 'kredit', 'IDR', 'Akumulasi surplus bebas dari tahun-tahun sebelumnya', 2500000000.00, 2500000000.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (48, 1, '3-1200', 'Surplus/(Defisit) Tahun Berjalan', 3, 6, 'detail', 'kredit', 'IDR', 'Selisih pendapatan - beban tahun ini (akan ditutup ke 3-1100)', 0.00, 0.00, 1, 1, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (49, 1, '3-2100', 'Dana BOS Terikat', 3, 7, 'detail', 'kredit', 'IDR', 'Dana BOS dari pemerintah yang penggunaannya diatur', 500000000.00, 500000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (50, 1, '3-2200', 'Dana BOPTN Terikat', 3, 7, 'detail', 'kredit', 'IDR', 'Dana BOPTN untuk universitas', 300000000.00, 300000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (51, 1, '3-2300', 'Dana Hibah Beasiswa Terikat', 3, 7, 'detail', 'kredit', 'IDR', 'Hibah khusus untuk beasiswa siswa/mahasiswa', 300000000.00, 300000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (52, 1, '3-2400', 'Dana Pembangunan Gedung Terikat', 3, 7, 'detail', 'kredit', 'IDR', 'Dana hibah khusus untuk pembangunan gedung', 200000000.00, 200000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (53, 1, '3-3100', 'Dana Abadi (Endowment Fund)', 3, 8, 'detail', 'kredit', 'IDR', 'Pokok dana abadi yang tidak boleh dikurangi', 1000000000.00, 1000000000.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (54, 1, '4-1100', 'Pendapatan SPP TK', 4, 9, 'detail', 'kredit', 'IDR', 'SPP siswa TK per bulan', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (55, 1, '4-1101', 'Pendapatan SPP SD', 4, 9, 'detail', 'kredit', 'IDR', 'SPP siswa SD per bulan', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (56, 1, '4-1102', 'Pendapatan SPP SMP', 4, 9, 'detail', 'kredit', 'IDR', 'SPP siswa SMP per bulan', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (57, 1, '4-1103', 'Pendapatan SPP SMA', 4, 9, 'detail', 'kredit', 'IDR', 'SPP siswa SMA per bulan', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (58, 1, '4-1104', 'Pendapatan UKT Universitas', 4, 9, 'detail', 'kredit', 'IDR', 'UKT mahasiswa per semester', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (59, 1, '4-2100', 'Pendapatan Uang Gedung', 4, 10, 'detail', 'kredit', 'IDR', 'Pendapatan dari uang pembangunan/gedung (daftar ulang)', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (60, 1, '4-2200', 'Pendapatan Pendaftaran Siswa/Mahasiswa Baru', 4, 10, 'detail', 'kredit', 'IDR', 'Biaya pendaftaran calon siswa/mahasiswa baru', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (61, 1, '4-2300', 'Pendapatan Sewa Fasilitas', 4, 10, 'detail', 'kredit', 'IDR', 'Sewa gedung aula, lapangan, ruang pertemuan', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (62, 1, '4-2400', 'Pendapatan Kantin & Koperasi', 4, 10, 'detail', 'kredit', 'IDR', 'Sewa kantin dan koperasi sekolah', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (63, 1, '4-2500', 'Pendapatan Kegiatan Khusus', 4, 10, 'detail', 'kredit', 'IDR', 'Study tour, camping, outbound, dll', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (64, 1, '4-3100', 'Pendapatan Hibah BOS', 4, 11, 'detail', 'kredit', 'IDR', 'Dana BOS dari pemerintah', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (65, 1, '4-3110', 'Pendapatan Hibah BOP', 4, 11, 'detail', 'kredit', 'IDR', 'Dana BOP untuk jenjang SD/SMP', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (66, 1, '4-3120', 'Pendapatan Hibah BOPTN', 4, 11, 'detail', 'kredit', 'IDR', 'Dana BOPTN untuk universitas', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (67, 1, '4-3200', 'Pendapatan Donasi Alumni', 4, 11, 'detail', 'kredit', 'IDR', 'Donasi dari alumni yayasan', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (68, 1, '4-3210', 'Pendapatan Donasi Corporate', 4, 11, 'detail', 'kredit', 'IDR', 'Donasi dari perusahaan/swasta', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (69, 1, '4-3220', 'Pendapatan Donasi Orang Tua', 4, 11, 'detail', 'kredit', 'IDR', 'Sumbangan sukarela dari orang tua siswa', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (70, 1, '4-4100', 'Pendapatan Bunga Bank', 4, 12, 'detail', 'kredit', 'IDR', 'Bunga dari deposito dan tabungan', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (71, 1, '4-4200', 'Pendapatan Dividen', 4, 12, 'detail', 'kredit', 'IDR', 'Dividen dari investasi saham', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (72, 1, '4-4300', 'Pendapatan Lain-lain', 4, 12, 'detail', 'kredit', 'IDR', 'Pendapatan tidak terduga lainnya', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (73, 1, '5-1100', 'Gaji Guru TK', 5, 13, 'detail', 'debit', 'IDR', 'Gaji dan tunjangan guru TK', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (74, 1, '5-1101', 'Gaji Guru SD', 5, 13, 'detail', 'debit', 'IDR', 'Gaji dan tunjangan guru SD', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (75, 1, '5-1102', 'Gaji Guru SMP', 5, 13, 'detail', 'debit', 'IDR', 'Gaji dan tunjangan guru SMP', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (76, 1, '5-1103', 'Gaji Guru SMA', 5, 13, 'detail', 'debit', 'IDR', 'Gaji dan tunjangan guru SMA', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (77, 1, '5-1104', 'Gaji Dosen Universitas', 5, 13, 'detail', 'debit', 'IDR', 'Gaji dan honorarium dosen', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (78, 1, '5-1200', 'Beban Praktikum & Laboratorium', 5, 13, 'detail', 'debit', 'IDR', 'Biaya bahan praktikum, alat lab, dan pemeliharaan lab', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (79, 1, '5-1300', 'Beban Perpustakaan', 5, 13, 'detail', 'debit', 'IDR', 'Pembelian buku, jurnal, langganan database, pemeliharaan perpustakaan', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (80, 1, '5-1400', 'Beban Kegiatan Ekstrakurikuler', 5, 13, 'detail', 'debit', 'IDR', 'Biaya pramuka, olahraga, seni, lomba, study tour', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (81, 1, '5-1500', 'Beban Wisuda & Pelepasan', 5, 13, 'detail', 'debit', 'IDR', 'Biaya wisuda siswa/mahasiswa dan pelepasan', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (82, 1, '5-1600', 'Beban Perlombaan & Kompetisi', 5, 13, 'detail', 'debit', 'IDR', 'Biaya mengikuti lomba akademik dan non-akademik', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (83, 1, '5-1700', 'Beban Beasiswa', 5, 13, 'detail', 'debit', 'IDR', 'Biaya pemberian beasiswa kepada siswa/mahasiswa berprestasi', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (84, 1, '5-2100', 'Gaji Staf Administrasi & TU', 5, 14, 'detail', 'debit', 'IDR', 'Gaji staf tata usaha, HRD, akuntan, IT', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (85, 1, '5-2200', 'Beban Listrik & Air', 5, 14, 'detail', 'debit', 'IDR', 'Tagihan listrik, air, dan internet', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (86, 1, '5-2300', 'Beban ATK & Peralatan Kantor', 5, 14, 'detail', 'debit', 'IDR', 'Pembelian alat tulis, kertas, tinta printer, dll', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (87, 1, '5-2400', 'Beban Perawatan Gedung & Fasilitas', 5, 14, 'detail', 'debit', 'IDR', 'Biaya perbaikan, renovasi ringan, kebersihan gedung', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (88, 1, '5-2500', 'Beban Transportasi & Perjalanan Dinas', 5, 14, 'detail', 'debit', 'IDR', 'Biaya perjalanan dinas guru/staf, BBM, tol', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (89, 1, '5-2600', 'Beban Asuransi', 5, 14, 'detail', 'debit', 'IDR', 'Premi asuransi aset dan kesehatan karyawan', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (90, 1, '5-2700', 'Beban Komunikasi & Internet', 5, 14, 'detail', 'debit', 'IDR', 'Tagihan telepon, internet, dan langganan sistem', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (91, 1, '5-2800', 'Beban Pelatihan & Pengembangan SDM', 5, 14, 'detail', 'debit', 'IDR', 'Biaya seminar, workshop, pelatihan guru/staf', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (92, 1, '5-2900', 'Beban Pemasaran & Promosi', 5, 14, 'detail', 'debit', 'IDR', 'Biaya promosi penerimaan siswa/mahasiswa baru', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (93, 1, '5-3100', 'Beban Penyusutan Gedung', 5, 15, 'detail', 'debit', 'IDR', 'Penyusutan gedung sekolah dan kampus', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (94, 1, '5-3200', 'Beban Penyusutan Kendaraan', 5, 15, 'detail', 'debit', 'IDR', 'Penyusutan kendaraan operasional', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (95, 1, '5-3300', 'Beban Penyusutan Peralatan & IT', 5, 15, 'detail', 'debit', 'IDR', 'Penyusutan komputer, mesin, peralatan', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (96, 1, '5-4100', 'Beban Pajak Penghasilan', 5, 16, 'detail', 'debit', 'IDR', 'PPh terutang yayasan', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (97, 1, '5-4200', 'Beban Administrasi Bank', 5, 16, 'detail', 'debit', 'IDR', 'Biaya admin bank, transfer, materai', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (98, 1, '5-4300', 'Beban Denda & Penalti', 5, 16, 'detail', 'debit', 'IDR', 'Denda keterlambatan pembayaran pajak atau pinjaman', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (99, 1, '5-4400', 'Beban Kerugian Piutang', 5, 16, 'detail', 'debit', 'IDR', 'Piutang SPP/UKT yang dihapuskan karena tidak tertagih', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);
INSERT INTO `accounts` VALUES (100, 1, '5-4500', 'Beban Lain-lain', 5, 16, 'detail', 'debit', 'IDR', 'Biaya tidak terduga lainnya', 0.00, 0.00, 1, 0, NULL, '2026-09-12 14:25:42', '2026-09-12 14:25:42', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for achievements
-- ----------------------------
DROP TABLE IF EXISTS `achievements`;
CREATE TABLE `achievements`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `achievement_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `level` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `date_achieved` date NULL DEFAULT NULL,
  `certificate_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `points` int NOT NULL DEFAULT 0,
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `achievements_student_index`(`student_id` ASC) USING BTREE,
  INDEX `achievements_type_index`(`achievement_type` ASC) USING BTREE,
  INDEX `achievements_level_index`(`level` ASC) USING BTREE,
  INDEX `achievements_date_index`(`date_achieved` ASC) USING BTREE,
  INDEX `achievements_is_verified_index`(`is_verified` ASC) USING BTREE,
  INDEX `achievements_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `achievements_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `achievements_updated_by_index`(`updated_by` ASC) USING BTREE,
  INDEX `achievements_verified_by_foreign`(`verified_by` ASC) USING BTREE,
  CONSTRAINT `achievements_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `achievements_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of achievements
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
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of actions
-- ----------------------------
INSERT INTO `actions` VALUES (1, 1, 3, 'user-view', 'Lihat Daftar Pengguna', 'Melihat daftar pengguna', 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `actions` VALUES (2, 1, 3, 'user-manage', 'Kelola Pengguna', 'Membuat, mengedit, dan menghapus pengguna', 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `actions` VALUES (3, 1, 4, 'roles-manage', 'Kelola Peran', 'Mengelola peran dan izin', 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `actions` VALUES (4, 1, 5, 'profile-view', 'Lihat Profil', 'Melihat profil pengguna', 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `actions` VALUES (5, 1, 5, 'profile-edit', 'Edit Profil', 'Mengedit profil pengguna', 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for admission_selections
-- ----------------------------
DROP TABLE IF EXISTS `admission_selections`;
CREATE TABLE `admission_selections`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `selection_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `score` decimal(5, 2) NULL DEFAULT NULL,
  `result` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `evaluator_id` bigint UNSIGNED NULL DEFAULT NULL,
  `evaluation_date` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admission_selections_admission_index`(`admission_id` ASC) USING BTREE,
  INDEX `admission_selections_applicant_index`(`applicant_id` ASC) USING BTREE,
  INDEX `admission_selections_type_index`(`selection_type` ASC) USING BTREE,
  INDEX `admission_selections_result_index`(`result` ASC) USING BTREE,
  INDEX `admission_selections_evaluator_index`(`evaluator_id` ASC) USING BTREE,
  INDEX `admission_selections_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `admission_selections_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `admission_selections_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `admission_selections_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `admission_selections_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `admission_selections_evaluator_id_foreign` FOREIGN KEY (`evaluator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admission_selections
-- ----------------------------

-- ----------------------------
-- Table structure for admission_timelines
-- ----------------------------
DROP TABLE IF EXISTS `admission_timelines`;
CREATE TABLE `admission_timelines`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `phase_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `order_number` int NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admission_timelines_admission_index`(`admission_id` ASC) USING BTREE,
  INDEX `admission_timelines_order_index`(`order_number` ASC) USING BTREE,
  INDEX `admission_timelines_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `admission_timelines_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `admission_timelines_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `admission_timelines_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admission_timelines
-- ----------------------------

-- ----------------------------
-- Table structure for admissions
-- ----------------------------
DROP TABLE IF EXISTS `admissions`;
CREATE TABLE `admissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `class_level_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `quota` int NOT NULL DEFAULT 0,
  `registration_fee` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `min_age` int NULL DEFAULT NULL,
  `max_age` int NULL DEFAULT NULL,
  `requirements` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `admissions_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `admissions_school_index`(`school_id` ASC) USING BTREE,
  INDEX `admissions_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `admissions_class_level_index`(`class_level_id` ASC) USING BTREE,
  INDEX `admissions_dates_index`(`start_date` ASC, `end_date` ASC) USING BTREE,
  INDEX `admissions_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `admissions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `admissions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `admissions_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `admissions_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `admissions_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `admissions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admissions
-- ----------------------------

-- ----------------------------
-- Table structure for announcements
-- ----------------------------
DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `audience` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `priority` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NORMAL',
  `published_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `announcements_school_index`(`school_id` ASC) USING BTREE,
  INDEX `announcements_audience_index`(`audience` ASC) USING BTREE,
  INDEX `announcements_priority_index`(`priority` ASC) USING BTREE,
  INDEX `announcements_published_at_index`(`published_at` ASC) USING BTREE,
  INDEX `announcements_expired_at_index`(`expired_at` ASC) USING BTREE,
  INDEX `announcements_is_pinned_index`(`is_pinned` ASC) USING BTREE,
  INDEX `announcements_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `announcements_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `announcements_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `announcements_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of announcements
-- ----------------------------

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of api_rate_limits
-- ----------------------------

-- ----------------------------
-- Table structure for applicant_invoices
-- ----------------------------
DROP TABLE IF EXISTS `applicant_invoices`;
CREATE TABLE `applicant_invoices`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `due_date` date NULL DEFAULT NULL,
  `total_amount` decimal(15, 2) NOT NULL,
  `discount_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `penalty_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(15, 2) NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `applicant_invoices_school_number_unique`(`school_id` ASC, `invoice_number` ASC) USING BTREE,
  INDEX `applicant_invoices_applicant_index`(`applicant_id` ASC) USING BTREE,
  INDEX `applicant_invoices_school_index`(`school_id` ASC) USING BTREE,
  INDEX `applicant_invoices_admission_index`(`admission_id` ASC) USING BTREE,
  INDEX `applicant_invoices_status_index`(`status` ASC) USING BTREE,
  INDEX `applicant_invoices_due_date_index`(`due_date` ASC) USING BTREE,
  INDEX `applicant_invoices_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `applicant_invoices_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `applicant_invoices_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `applicant_invoices_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applicant_invoices_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applicant_invoices_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of applicant_invoices
-- ----------------------------

-- ----------------------------
-- Table structure for applicant_payments
-- ----------------------------
DROP TABLE IF EXISTS `applicant_payments`;
CREATE TABLE `applicant_payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `payment_method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payment_date` date NULL DEFAULT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `reference_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payment_proof_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verification_date` timestamp NULL DEFAULT NULL,
  `refund_date` timestamp NULL DEFAULT NULL,
  `refund_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `applicant_payments_invoice_index`(`invoice_id` ASC) USING BTREE,
  INDEX `applicant_payments_applicant_index`(`applicant_id` ASC) USING BTREE,
  INDEX `applicant_payments_status_index`(`status` ASC) USING BTREE,
  INDEX `applicant_payments_verified_by_index`(`verified_by` ASC) USING BTREE,
  INDEX `applicant_payments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `applicant_payments_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `applicant_payments_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `applicant_payments_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applicant_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `applicant_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applicant_payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of applicant_payments
-- ----------------------------

-- ----------------------------
-- Table structure for applicants
-- ----------------------------
DROP TABLE IF EXISTS `applicants`;
CREATE TABLE `applicants`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birth_place` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birth_date` date NULL DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `parent_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `parent_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `parent_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `previous_school` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `registration_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'REGISTERED',
  `test_score` decimal(5, 2) NULL DEFAULT NULL,
  `interview_score` decimal(5, 2) NULL DEFAULT NULL,
  `total_score` decimal(5, 2) NULL DEFAULT NULL,
  `ranking` int NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `registered_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `applicants_school_registration_number_unique`(`school_id` ASC, `registration_number` ASC) USING BTREE,
  UNIQUE INDEX `applicants_school_nik_unique`(`school_id` ASC, `nik` ASC) USING BTREE,
  INDEX `applicants_school_index`(`school_id` ASC) USING BTREE,
  INDEX `applicants_admission_index`(`admission_id` ASC) USING BTREE,
  INDEX `applicants_status_index`(`status` ASC) USING BTREE,
  INDEX `applicants_registration_number_index`(`registration_number` ASC) USING BTREE,
  INDEX `applicants_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `applicants_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `applicants_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `applicants_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applicants_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of applicants
-- ----------------------------

-- ----------------------------
-- Table structure for application_documents
-- ----------------------------
DROP TABLE IF EXISTS `application_documents`;
CREATE TABLE `application_documents`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `document_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `document_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `file_size` bigint NULL DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verification_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `application_documents_applicant_index`(`applicant_id` ASC) USING BTREE,
  INDEX `application_documents_type_index`(`document_type` ASC) USING BTREE,
  INDEX `application_documents_verified_index`(`verified` ASC) USING BTREE,
  INDEX `application_documents_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `application_documents_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `application_documents_updated_by_index`(`updated_by` ASC) USING BTREE,
  INDEX `application_documents_verified_by_foreign`(`verified_by` ASC) USING BTREE,
  CONSTRAINT `application_documents_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `application_documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of application_documents
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of asset_depreciations
-- ----------------------------

-- ----------------------------
-- Table structure for assets
-- ----------------------------
DROP TABLE IF EXISTS `assets`;
CREATE TABLE `assets`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `brand` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `model` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `serial_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `purchase_date` date NULL DEFAULT NULL,
  `purchase_price` decimal(15, 2) NULL DEFAULT NULL,
  `supplier` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `location` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `condition` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GOOD',
  `assigned_to` bigint UNSIGNED NULL DEFAULT NULL,
  `warranty_end` date NULL DEFAULT NULL,
  `depreciation_value` decimal(15, 2) NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `photo_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `assets_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `assets_school_index`(`school_id` ASC) USING BTREE,
  INDEX `assets_category_index`(`category` ASC) USING BTREE,
  INDEX `assets_condition_index`(`condition` ASC) USING BTREE,
  INDEX `assets_location_index`(`location` ASC) USING BTREE,
  INDEX `assets_assigned_to_index`(`assigned_to` ASC) USING BTREE,
  INDEX `assets_purchase_date_index`(`purchase_date` ASC) USING BTREE,
  INDEX `assets_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `assets_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `assets_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `assets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assets_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of assets
-- ----------------------------

-- ----------------------------
-- Table structure for assignments_quizzes
-- ----------------------------
DROP TABLE IF EXISTS `assignments_quizzes`;
CREATE TABLE `assignments_quizzes`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` bigint UNSIGNED NOT NULL,
  `lesson_id` bigint UNSIGNED NULL DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `max_score` int NOT NULL DEFAULT 100,
  `time_limit_minutes` int NULL DEFAULT NULL,
  `passing_score` int NOT NULL DEFAULT 70,
  `due_date` timestamp NULL DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `question_data` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `assignments_quizzes_course_index`(`course_id` ASC) USING BTREE,
  INDEX `assignments_quizzes_lesson_index`(`lesson_id` ASC) USING BTREE,
  INDEX `assignments_quizzes_type_index`(`type` ASC) USING BTREE,
  INDEX `assignments_quizzes_due_date_index`(`due_date` ASC) USING BTREE,
  INDEX `assignments_quizzes_is_published_index`(`is_published` ASC) USING BTREE,
  INDEX `assignments_quizzes_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `assignments_quizzes_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `assignments_quizzes_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `assignments_quizzes_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assignments_quizzes_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of assignments_quizzes
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of audit_trails
-- ----------------------------

-- ----------------------------
-- Table structure for bank_accounts
-- ----------------------------
DROP TABLE IF EXISTS `bank_accounts`;
CREATE TABLE `bank_accounts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` bigint UNSIGNED NOT NULL,
  `opening_balance` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `is_cash_account` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `swift_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bank_accounts_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `bank_accounts_school_index`(`school_id` ASC) USING BTREE,
  INDEX `bank_accounts_coa_index`(`account_id` ASC) USING BTREE,
  INDEX `bank_accounts_currency_index`(`currency_id` ASC) USING BTREE,
  INDEX `bank_accounts_number_index`(`account_number` ASC) USING BTREE,
  INDEX `bank_accounts_is_cash_index`(`is_cash_account` ASC) USING BTREE,
  INDEX `bank_accounts_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `bank_accounts_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `bank_accounts_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `bank_accounts_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_accounts_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_accounts_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bank_accounts
-- ----------------------------

-- ----------------------------
-- Table structure for bank_mutation_imports
-- ----------------------------
DROP TABLE IF EXISTS `bank_mutation_imports`;
CREATE TABLE `bank_mutation_imports`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `bank_account_id` bigint UNSIGNED NOT NULL,
  `file_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `file_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `file_size` bigint NULL DEFAULT NULL,
  `total_rows` int NOT NULL DEFAULT 0,
  `success_rows` int NOT NULL DEFAULT 0,
  `failed_rows` int NOT NULL DEFAULT 0,
  `duplicate_rows` int NOT NULL DEFAULT 0,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `error_log` json NULL,
  `imported_at` timestamp NULL DEFAULT NULL,
  `imported_by` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `bank_mutation_imports_school_index`(`school_id` ASC) USING BTREE,
  INDEX `bank_mutation_imports_bank_account_index`(`bank_account_id` ASC) USING BTREE,
  INDEX `bank_mutation_imports_status_index`(`status` ASC) USING BTREE,
  INDEX `bank_mutation_imports_imported_by_index`(`imported_by` ASC) USING BTREE,
  INDEX `bank_mutation_imports_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `bank_mutation_imports_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `bank_mutation_imports_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `bank_mutation_imports_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_mutation_imports_imported_by_foreign` FOREIGN KEY (`imported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bank_mutation_imports_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bank_mutation_imports
-- ----------------------------

-- ----------------------------
-- Table structure for bank_mutations
-- ----------------------------
DROP TABLE IF EXISTS `bank_mutations`;
CREATE TABLE `bank_mutations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `bank_account_id` bigint UNSIGNED NOT NULL,
  `import_batch_id` bigint UNSIGNED NULL DEFAULT NULL,
  `mutation_date` date NOT NULL,
  `value_date` date NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `reference_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `debit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `credit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `balance` decimal(20, 2) NULL DEFAULT NULL,
  `currency_id` bigint UNSIGNED NOT NULL,
  `exchange_rate` decimal(20, 8) NOT NULL DEFAULT 1.00000000,
  `functional_debit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `functional_credit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `reconciliation_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UNMATCHED',
  `matched_journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `matched_at` timestamp NULL DEFAULT NULL,
  `matched_by` bigint UNSIGNED NULL DEFAULT NULL,
  `match_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `raw_data` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `bank_mutations_school_index`(`school_id` ASC) USING BTREE,
  INDEX `bank_mutations_bank_account_index`(`bank_account_id` ASC) USING BTREE,
  INDEX `bank_mutations_import_batch_index`(`import_batch_id` ASC) USING BTREE,
  INDEX `bank_mutations_date_index`(`mutation_date` ASC) USING BTREE,
  INDEX `bank_mutations_reference_index`(`reference_number` ASC) USING BTREE,
  INDEX `bank_mutations_status_index`(`reconciliation_status` ASC) USING BTREE,
  INDEX `bank_mutations_matched_entry_index`(`matched_journal_entry_id` ASC) USING BTREE,
  INDEX `bank_mutations_matched_by_index`(`matched_by` ASC) USING BTREE,
  INDEX `bank_mutations_currency_index`(`currency_id` ASC) USING BTREE,
  INDEX `bank_mutations_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `bank_mutations_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `bank_mutations_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `bank_mutations_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_mutations_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_mutations_matched_by_foreign` FOREIGN KEY (`matched_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bank_mutations_matched_journal_entry_id_foreign` FOREIGN KEY (`matched_journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bank_mutations_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bank_mutations
-- ----------------------------

-- ----------------------------
-- Table structure for bank_reconciliation_items
-- ----------------------------
DROP TABLE IF EXISTS `bank_reconciliation_items`;
CREATE TABLE `bank_reconciliation_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bank_reconciliation_id` bigint UNSIGNED NOT NULL,
  `bank_mutation_id` bigint UNSIGNED NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `match_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AUTO',
  `match_score` decimal(5, 2) NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MATCHED',
  `difference_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `bank_reconciliation_items_reconciliation_index`(`bank_reconciliation_id` ASC) USING BTREE,
  INDEX `bank_reconciliation_items_mutation_index`(`bank_mutation_id` ASC) USING BTREE,
  INDEX `bank_reconciliation_items_entry_index`(`journal_entry_id` ASC) USING BTREE,
  INDEX `bank_reconciliation_items_match_type_index`(`match_type` ASC) USING BTREE,
  INDEX `bank_reconciliation_items_status_index`(`status` ASC) USING BTREE,
  INDEX `bank_reconciliation_items_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `bank_reconciliation_items_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `bank_reconciliation_items_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `bank_reconciliation_items_bank_mutation_id_foreign` FOREIGN KEY (`bank_mutation_id`) REFERENCES `bank_mutations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_reconciliation_items_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bank_reconciliation_items_reconciliation_id_foreign` FOREIGN KEY (`bank_reconciliation_id`) REFERENCES `bank_reconciliations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bank_reconciliation_items
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bonuses
-- ----------------------------

-- ----------------------------
-- Table structure for bos_allocations
-- ----------------------------
DROP TABLE IF EXISTS `bos_allocations`;
CREATE TABLE `bos_allocations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bos_period_id` bigint UNSIGNED NOT NULL,
  `bos_component_id` bigint UNSIGNED NOT NULL,
  `allocation_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5, 2) NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bos_allocations_period_component_unique`(`bos_period_id` ASC, `bos_component_id` ASC) USING BTREE,
  INDEX `bos_allocations_period_index`(`bos_period_id` ASC) USING BTREE,
  INDEX `bos_allocations_component_index`(`bos_component_id` ASC) USING BTREE,
  INDEX `bos_allocations_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `bos_allocations_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `bos_allocations_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `bos_allocations_bos_component_id_foreign` FOREIGN KEY (`bos_component_id`) REFERENCES `bos_components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_allocations_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bos_allocations
-- ----------------------------

-- ----------------------------
-- Table structure for bos_components
-- ----------------------------
DROP TABLE IF EXISTS `bos_components`;
CREATE TABLE `bos_components`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `authority` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'KEMENDIKBUD',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `regulation_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `regulation_version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `max_percentage` decimal(5, 2) NULL DEFAULT NULL,
  `min_percentage` decimal(5, 2) NULL DEFAULT NULL,
  `calculation_base` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 0,
  `is_prohibited` tinyint(1) NOT NULL DEFAULT 0,
  `order_index` int NOT NULL DEFAULT 0,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bos_components_authority_code_unique`(`authority` ASC, `code` ASC) USING BTREE,
  INDEX `bos_components_authority_index`(`authority` ASC) USING BTREE,
  INDEX `bos_components_type_index`(`component_type` ASC) USING BTREE,
  INDEX `bos_components_regulation_index`(`regulation_code` ASC, `regulation_version` ASC) USING BTREE,
  INDEX `bos_components_order_index`(`order_index` ASC) USING BTREE,
  INDEX `bos_components_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `bos_components_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `bos_components_updated_by_index`(`updated_by` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bos_components
-- ----------------------------

-- ----------------------------
-- Table structure for bos_periods
-- ----------------------------
DROP TABLE IF EXISTS `bos_periods`;
CREATE TABLE `bos_periods`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `fiscal_period_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `authority` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'KEMENDIKBUD',
  `regulation_version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `period_number` int NULL DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_allocation` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_received` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_used` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_remaining` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PLANNING',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bos_periods_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `bos_periods_school_index`(`school_id` ASC) USING BTREE,
  INDEX `bos_periods_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `bos_periods_fiscal_period_index`(`fiscal_period_id` ASC) USING BTREE,
  INDEX `bos_periods_authority_index`(`authority` ASC) USING BTREE,
  INDEX `bos_periods_status_index`(`status` ASC) USING BTREE,
  INDEX `bos_periods_dates_index`(`start_date` ASC, `end_date` ASC) USING BTREE,
  INDEX `bos_periods_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `bos_periods_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `bos_periods_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `bos_periods_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_periods_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_periods_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bos_periods
-- ----------------------------

-- ----------------------------
-- Table structure for bos_realizations
-- ----------------------------
DROP TABLE IF EXISTS `bos_realizations`;
CREATE TABLE `bos_realizations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bos_period_id` bigint UNSIGNED NOT NULL,
  `bos_usage_plan_id` bigint UNSIGNED NULL DEFAULT NULL,
  `rkas_realization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `vendor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `vendor_invoice_id` bigint UNSIGNED NULL DEFAULT NULL,
  `receipt_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `realization_date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'RECORDED',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `bos_realizations_period_index`(`bos_period_id` ASC) USING BTREE,
  INDEX `bos_realizations_usage_plan_index`(`bos_usage_plan_id` ASC) USING BTREE,
  INDEX `bos_realizations_rkas_realization_index`(`rkas_realization_id` ASC) USING BTREE,
  INDEX `bos_realizations_journal_entry_index`(`journal_entry_id` ASC) USING BTREE,
  INDEX `bos_realizations_vendor_index`(`vendor_id` ASC) USING BTREE,
  INDEX `bos_realizations_vendor_invoice_index`(`vendor_invoice_id` ASC) USING BTREE,
  INDEX `bos_realizations_date_index`(`realization_date` ASC) USING BTREE,
  INDEX `bos_realizations_status_index`(`status` ASC) USING BTREE,
  INDEX `bos_realizations_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `bos_realizations_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `bos_realizations_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `bos_realizations_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_realizations_bos_usage_plan_id_foreign` FOREIGN KEY (`bos_usage_plan_id`) REFERENCES `bos_usage_plans` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_realizations_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_realizations_rkas_realization_id_foreign` FOREIGN KEY (`rkas_realization_id`) REFERENCES `rkas_realizations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_realizations_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_realizations_vendor_invoice_id_foreign` FOREIGN KEY (`vendor_invoice_id`) REFERENCES `vendor_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bos_realizations
-- ----------------------------

-- ----------------------------
-- Table structure for bos_receipts
-- ----------------------------
DROP TABLE IF EXISTS `bos_receipts`;
CREATE TABLE `bos_receipts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bos_period_id` bigint UNSIGNED NOT NULL,
  `bank_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `receipt_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_date` date NOT NULL,
  `amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `transfer_reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `transfer_proof_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `received_by` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bos_receipts_period_number_unique`(`bos_period_id` ASC, `receipt_number` ASC) USING BTREE,
  INDEX `bos_receipts_period_index`(`bos_period_id` ASC) USING BTREE,
  INDEX `bos_receipts_bank_account_index`(`bank_account_id` ASC) USING BTREE,
  INDEX `bos_receipts_date_index`(`receipt_date` ASC) USING BTREE,
  INDEX `bos_receipts_received_by_index`(`received_by` ASC) USING BTREE,
  INDEX `bos_receipts_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `bos_receipts_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `bos_receipts_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `bos_receipts_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_receipts_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_receipts_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bos_receipts
-- ----------------------------

-- ----------------------------
-- Table structure for bos_report_items
-- ----------------------------
DROP TABLE IF EXISTS `bos_report_items`;
CREATE TABLE `bos_report_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bos_report_id` bigint UNSIGNED NOT NULL,
  `bos_component_id` bigint UNSIGNED NULL DEFAULT NULL,
  `rkas_source_id` bigint UNSIGNED NULL DEFAULT NULL,
  `account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `allocation_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `received_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `realization_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5, 2) NOT NULL DEFAULT 0.00,
  `item_data` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `bos_report_items_report_index`(`bos_report_id` ASC) USING BTREE,
  INDEX `bos_report_items_component_index`(`bos_component_id` ASC) USING BTREE,
  INDEX `bos_report_items_source_index`(`rkas_source_id` ASC) USING BTREE,
  INDEX `bos_report_items_coa_index`(`account_id` ASC) USING BTREE,
  INDEX `bos_report_items_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `bos_report_items_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `bos_report_items_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `bos_report_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_report_items_bos_component_id_foreign` FOREIGN KEY (`bos_component_id`) REFERENCES `bos_components` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_report_items_bos_report_id_foreign` FOREIGN KEY (`bos_report_id`) REFERENCES `bos_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_report_items_rkas_source_id_foreign` FOREIGN KEY (`rkas_source_id`) REFERENCES `rkas_sources` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bos_report_items
-- ----------------------------

-- ----------------------------
-- Table structure for bos_reports
-- ----------------------------
DROP TABLE IF EXISTS `bos_reports`;
CREATE TABLE `bos_reports`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `bos_period_id` bigint UNSIGNED NOT NULL,
  `fiscal_period_id` bigint UNSIGNED NULL DEFAULT NULL,
  `report_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PERIODIC',
  `period_number` int NULL DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_allocation` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_received` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_used` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_remaining` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5, 2) NOT NULL DEFAULT 0.00,
  `file_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `report_data` json NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `submitted_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bos_reports_school_period_code_unique`(`school_id` ASC, `bos_period_id` ASC, `report_code` ASC) USING BTREE,
  INDEX `bos_reports_school_index`(`school_id` ASC) USING BTREE,
  INDEX `bos_reports_period_index`(`bos_period_id` ASC) USING BTREE,
  INDEX `bos_reports_fiscal_period_index`(`fiscal_period_id` ASC) USING BTREE,
  INDEX `bos_reports_code_index`(`report_code` ASC) USING BTREE,
  INDEX `bos_reports_type_index`(`report_type` ASC) USING BTREE,
  INDEX `bos_reports_status_index`(`status` ASC) USING BTREE,
  INDEX `bos_reports_submitted_by_index`(`submitted_by` ASC) USING BTREE,
  INDEX `bos_reports_verified_by_index`(`verified_by` ASC) USING BTREE,
  INDEX `bos_reports_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `bos_reports_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `bos_reports_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `bos_reports_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_reports_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_reports_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_reports_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_reports_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bos_reports
-- ----------------------------

-- ----------------------------
-- Table structure for bos_usage_plans
-- ----------------------------
DROP TABLE IF EXISTS `bos_usage_plans`;
CREATE TABLE `bos_usage_plans`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bos_period_id` bigint UNSIGNED NOT NULL,
  `rkas_document_id` bigint UNSIGNED NULL DEFAULT NULL,
  `rkas_item_id` bigint UNSIGNED NULL DEFAULT NULL,
  `bos_component_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `planned_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `execution_month` tinyint UNSIGNED NULL DEFAULT NULL,
  `execution_quarter` tinyint UNSIGNED NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PLANNED',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bos_usage_plans_period_code_unique`(`bos_period_id` ASC, `code` ASC) USING BTREE,
  INDEX `bos_usage_plans_period_index`(`bos_period_id` ASC) USING BTREE,
  INDEX `bos_usage_plans_rkas_document_index`(`rkas_document_id` ASC) USING BTREE,
  INDEX `bos_usage_plans_rkas_item_index`(`rkas_item_id` ASC) USING BTREE,
  INDEX `bos_usage_plans_component_index`(`bos_component_id` ASC) USING BTREE,
  INDEX `bos_usage_plans_month_index`(`execution_month` ASC) USING BTREE,
  INDEX `bos_usage_plans_status_index`(`status` ASC) USING BTREE,
  INDEX `bos_usage_plans_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `bos_usage_plans_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `bos_usage_plans_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `bos_usage_plans_bos_component_id_foreign` FOREIGN KEY (`bos_component_id`) REFERENCES `bos_components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_usage_plans_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bos_usage_plans_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bos_usage_plans_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of bos_usage_plans
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cache
-- ----------------------------
INSERT INTO `cache` VALUES ('laravel-cache-acl-all-actions', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:1;s:9:\"user-view\";i:2;s:11:\"user-manage\";i:3;s:12:\"roles-manage\";i:4;s:12:\"profile-view\";i:5;s:12:\"profile-edit\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1789587759);
INSERT INTO `cache` VALUES ('laravel-cache-acl-all-menus', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:40:{i:1;s:9:\"dashboard\";i:2;s:6:\"master\";i:3;s:4:\"user\";i:4;s:5:\"roles\";i:5;s:7:\"profile\";i:6;s:4:\"menu\";i:7;s:16:\"module-generator\";i:9;s:23:\"academic-calendar-event\";i:10;s:15:\"account-mapping\";i:11;s:8:\"academic\";i:12;s:8:\"students\";i:13;s:13:\"academic-year\";i:14;s:14:\"reference-type\";i:15;s:15:\"reference-value\";i:16;s:6:\"period\";i:17;s:7:\"account\";i:18;s:11:\"tax-setting\";i:19;s:10:\"shift-swap\";i:20;s:9:\"referensi\";i:21;s:10:\"accounting\";i:22;s:16:\"expense-category\";i:23;s:16:\"account-category\";i:24;s:8:\"tax-type\";i:25;s:11:\"cbt-session\";i:26;s:16:\"cbt-activity-log\";i:27;s:14:\"cbt-proctoring\";i:28;s:9:\"classroom\";i:29;s:16:\"homeroom-teacher\";i:30;s:7:\"teacher\";i:31;s:15:\"menu-management\";i:32;s:7:\"student\";i:33;s:18:\"Pendaftaran (PPDB)\";i:34;s:9:\"applicant\";i:35;s:9:\"admission\";i:36;s:19:\"admission-selection\";i:37;s:18:\"admission-timeline\";i:38;s:20:\"application-document\";i:39;s:11:\"class-level\";i:40;s:9:\"test-type\";i:41;s:13:\"test-schedule\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1789587759);
INSERT INTO `cache` VALUES ('laravel-cache-formlib_cols_tax_types', 'O:8:\"stdClass\":6:{s:2:\"id\";O:8:\"stdClass\":7:{s:4:\"type\";s:15:\"bigint unsigned\";s:7:\"is_null\";s:2:\"NO\";s:3:\"key\";s:3:\"PRI\";s:7:\"default\";N;s:5:\"extra\";s:14:\"auto_increment\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}s:15:\"organization_id\";O:8:\"stdClass\":7:{s:4:\"type\";s:6:\"bigint\";s:7:\"is_null\";s:3:\"YES\";s:3:\"key\";s:0:\"\";s:7:\"default\";N;s:5:\"extra\";s:0:\"\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}s:4:\"code\";O:8:\"stdClass\":7:{s:4:\"type\";s:11:\"varchar(20)\";s:7:\"is_null\";s:2:\"NO\";s:3:\"key\";s:3:\"UNI\";s:7:\"default\";N;s:5:\"extra\";s:0:\"\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}s:4:\"name\";O:8:\"stdClass\":7:{s:4:\"type\";s:12:\"varchar(191)\";s:7:\"is_null\";s:2:\"NO\";s:3:\"key\";s:0:\"\";s:7:\"default\";N;s:5:\"extra\";s:0:\"\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}s:11:\"description\";O:8:\"stdClass\":7:{s:4:\"type\";s:12:\"varchar(191)\";s:7:\"is_null\";s:3:\"YES\";s:3:\"key\";s:0:\"\";s:7:\"default\";N;s:5:\"extra\";s:0:\"\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}s:9:\"is_active\";O:8:\"stdClass\":7:{s:4:\"type\";s:10:\"tinyint(1)\";s:7:\"is_null\";s:2:\"NO\";s:3:\"key\";s:0:\"\";s:7:\"default\";s:1:\"1\";s:5:\"extra\";s:0:\"\";s:10:\"privileges\";s:31:\"select,insert,update,references\";s:7:\"comment\";s:0:\"\";}}', 1789587771);
INSERT INTO `cache` VALUES ('laravel-cache-formlib_fields_tax_types', 'a:6:{i:0;s:2:\"id\";i:1;s:15:\"organization_id\";i:2;s:4:\"code\";i:3;s:4:\"name\";i:4;s:11:\"description\";i:5;s:9:\"is_active\";}', 1789587771);
INSERT INTO `cache` VALUES ('laravel-cache-formlib_rel_organization', 'a:7:{i:1;s:16:\"Perusahaan Utama\";i:2;s:21:\"Kantor Cabang Jakarta\";i:3;s:22:\"Kantor Cabang Surabaya\";i:4;s:19:\"Kantor Cabang Medan\";i:5;s:12:\"Divisi Pusat\";i:6;s:18:\"Divisi Operasional\";i:7;s:29:\"Divisi Ekspor / Internasional\";}', 1789587771);
INSERT INTO `cache` VALUES ('laravel-cache-user_1_role_manager', 'b:0;', 1789587759);
INSERT INTO `cache` VALUES ('laravel-cache-user_1_role_special_role', 'b:0;', 1789587759);

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for cbt_activity_logs
-- ----------------------------
DROP TABLE IF EXISTS `cbt_activity_logs`;
CREATE TABLE `cbt_activity_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` bigint UNSIGNED NOT NULL,
  `activity_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `activity_data` json NULL,
  `recorded_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cbt_activity_logs_session_index`(`session_id` ASC) USING BTREE,
  INDEX `cbt_activity_logs_type_index`(`activity_type` ASC) USING BTREE,
  INDEX `cbt_activity_logs_recorded_at_index`(`recorded_at` ASC) USING BTREE,
  INDEX `cbt_activity_logs_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `cbt_activity_logs_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `cbt_activity_logs_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `cbt_activity_logs_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cbt_activity_logs
-- ----------------------------

-- ----------------------------
-- Table structure for cbt_proctoring
-- ----------------------------
DROP TABLE IF EXISTS `cbt_proctoring`;
CREATE TABLE `cbt_proctoring`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` bigint UNSIGNED NOT NULL,
  `proctor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `start_proctoring` timestamp NULL DEFAULT NULL,
  `end_proctoring` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `flag_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cbt_proctoring_session_index`(`session_id` ASC) USING BTREE,
  INDEX `cbt_proctoring_proctor_index`(`proctor_id` ASC) USING BTREE,
  INDEX `cbt_proctoring_status_index`(`status` ASC) USING BTREE,
  INDEX `cbt_proctoring_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `cbt_proctoring_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `cbt_proctoring_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `cbt_proctoring_proctor_id_foreign` FOREIGN KEY (`proctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cbt_proctoring_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cbt_proctoring
-- ----------------------------

-- ----------------------------
-- Table structure for cbt_sessions
-- ----------------------------
DROP TABLE IF EXISTS `cbt_sessions`;
CREATE TABLE `cbt_sessions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `exam_package_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `schedule_id` bigint UNSIGNED NULL DEFAULT NULL,
  `classroom_id` bigint UNSIGNED NULL DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `duration_seconds` int NULL DEFAULT NULL,
  `current_question_index` int NOT NULL DEFAULT 0,
  `answers` json NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `proctor_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_submitted` tinyint(1) NOT NULL DEFAULT 0,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `final_score` decimal(5, 2) NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cbt_sessions_exam_package_index`(`exam_package_id` ASC) USING BTREE,
  INDEX `cbt_sessions_student_index`(`student_id` ASC) USING BTREE,
  INDEX `cbt_sessions_schedule_index`(`schedule_id` ASC) USING BTREE,
  INDEX `cbt_sessions_class_index`(`classroom_id` ASC) USING BTREE,
  INDEX `cbt_sessions_status_index`(`status` ASC) USING BTREE,
  INDEX `cbt_sessions_is_submitted_index`(`is_submitted` ASC) USING BTREE,
  INDEX `cbt_sessions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `cbt_sessions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `cbt_sessions_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `cbt_sessions_class_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cbt_sessions_exam_package_id_foreign` FOREIGN KEY (`exam_package_id`) REFERENCES `exam_packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cbt_sessions_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cbt_sessions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cbt_sessions
-- ----------------------------

-- ----------------------------
-- Table structure for class_levels
-- ----------------------------
DROP TABLE IF EXISTS `class_levels`;
CREATE TABLE `class_levels`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
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
  INDEX `idx_class_levels_order`(`level_order` ASC) USING BTREE,
  INDEX `idx_class_levels_is_active`(`is_active` ASC) USING BTREE,
  INDEX `idx_class_levels_deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `class_levels_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of class_levels
-- ----------------------------
INSERT INTO `class_levels` VALUES (1, 1, 'PAUD', 'Pendidikan Anak Usia Dini', 10, 'Jenjang pendidikan anak usia dini sebelum memasuki pendidikan dasar.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (2, 1, 'TK', 'Taman Kanak-Kanak', 20, 'Jenjang pendidikan anak usia dini yang ditujukan untuk anak sebelum memasuki sekolah dasar.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (3, 1, 'SD', 'Sekolah Dasar', 30, 'Jenjang pendidikan dasar tingkat pertama yang umumnya ditempuh selama enam tahun.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (4, 1, 'MI', 'Madrasah Ibtidaiyah', 40, 'Jenjang pendidikan dasar yang setara dengan Sekolah Dasar dan berciri khas pendidikan Islam.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (5, 1, 'SMP', 'Sekolah Menengah Pertama', 50, 'Jenjang pendidikan dasar tingkat lanjutan setelah Sekolah Dasar.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (6, 1, 'MTs', 'Madrasah Tsanawiyah', 60, 'Jenjang pendidikan dasar tingkat lanjutan yang setara dengan Sekolah Menengah Pertama dan berciri khas pendidikan Islam.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (7, 1, 'SMA', 'Sekolah Menengah Atas', 70, 'Jenjang pendidikan menengah umum setelah pendidikan dasar.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (8, 1, 'MA', 'Madrasah Aliyah', 80, 'Jenjang pendidikan menengah yang setara dengan Sekolah Menengah Atas dan berciri khas pendidikan Islam.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (9, 1, 'SMK', 'Sekolah Menengah Kejuruan', 90, 'Jenjang pendidikan menengah yang berfokus pada pendidikan dan keterampilan kejuruan.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (10, 1, 'MAK', 'Madrasah Aliyah Kejuruan', 100, 'Jenjang pendidikan menengah kejuruan yang setara dengan Sekolah Menengah Kejuruan dan berciri khas pendidikan Islam.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (11, 1, 'D1', 'Diploma I', 110, 'Jenjang pendidikan tinggi program diploma dengan masa studi umumnya satu tahun.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (12, 1, 'D2', 'Diploma II', 120, 'Jenjang pendidikan tinggi program diploma dengan masa studi umumnya dua tahun.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (13, 1, 'D3', 'Diploma III', 130, 'Jenjang pendidikan tinggi program diploma dengan masa studi umumnya tiga tahun.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (14, 1, 'D4', 'Diploma IV / Sarjana Terapan', 140, 'Jenjang pendidikan tinggi vokasi yang setara dengan program sarjana.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (15, 1, 'S1', 'Sarjana', 150, 'Jenjang pendidikan tinggi program akademik tingkat sarjana.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (16, 1, 'PROF', 'Profesi', 160, 'Program pendidikan tinggi setelah program sarjana untuk memperoleh keahlian profesi tertentu.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (17, 1, 'S2', 'Magister', 170, 'Jenjang pendidikan tinggi program akademik tingkat magister.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (18, 1, 'SP1', 'Spesialis I', 180, 'Jenjang pendidikan profesi spesialis tingkat pertama.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (19, 1, 'S3', 'Doktor', 190, 'Jenjang pendidikan tinggi program akademik tingkat doktoral.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);
INSERT INTO `class_levels` VALUES (20, 1, 'SP2', 'Spesialis II', 200, 'Jenjang pendidikan profesi spesialis tingkat lanjutan.', 1, NULL, '2026-09-16 12:24:57', NULL, NULL, NULL);

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
  INDEX `idx_classrooms_is_active`(`is_active` ASC) USING BTREE,
  INDEX `idx_classrooms_deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `classrooms_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `classrooms_level_fk` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `classrooms_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of classrooms
-- ----------------------------
INSERT INTO `classrooms` VALUES (1, 1, 20, 'Kelas 6 A', 'SD-A', 30, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);
INSERT INTO `classrooms` VALUES (2, 1, 20, 'Kelas 6 B', 'SD-B', 30, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);
INSERT INTO `classrooms` VALUES (3, 1, 20, 'Kelas 7 A', 'SMP7-A', 32, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);
INSERT INTO `classrooms` VALUES (4, 1, 20, 'Kelas 7 B', 'SMP7-B', 32, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);
INSERT INTO `classrooms` VALUES (5, 1, 20, 'Kelas 8 A', 'SMP8-A', 32, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);
INSERT INTO `classrooms` VALUES (6, 1, 20, 'Kelas 8 B', 'SMP8-B', 32, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);
INSERT INTO `classrooms` VALUES (7, 1, 20, 'Kelas 9 A', 'SMP9-A', 32, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);
INSERT INTO `classrooms` VALUES (8, 1, 20, 'Kelas 9 B', 'SMP9-B', 32, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);
INSERT INTO `classrooms` VALUES (9, 1, 20, 'Kelas 10 A', 'SMA10-A', 36, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);
INSERT INTO `classrooms` VALUES (10, 1, 20, 'Kelas 10 B', 'SMA10-B', 36, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);
INSERT INTO `classrooms` VALUES (11, 1, 20, 'Kelas 11 A', 'SMA11-A', 36, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);
INSERT INTO `classrooms` VALUES (12, 1, 20, 'Kelas 11 B', 'SMA11-B', 36, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);
INSERT INTO `classrooms` VALUES (13, 1, 20, 'Kelas 12 A', 'SMA12-A', 36, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);
INSERT INTO `classrooms` VALUES (14, 1, 20, 'Kelas 12 B', 'SMA12-B', 36, 5, 1, NULL, '2026-09-16 12:41:37', NULL, NULL, NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cost_centers
-- ----------------------------

-- ----------------------------
-- Table structure for counseling_sessions
-- ----------------------------
DROP TABLE IF EXISTS `counseling_sessions`;
CREATE TABLE `counseling_sessions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `counselor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `session_date` timestamp NULL DEFAULT NULL,
  `category` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `problem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `action` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `follow_up` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `follow_up_date` date NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OPEN',
  `is_confidential` tinyint(1) NOT NULL DEFAULT 1,
  `parent_involved` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `counseling_sessions_student_index`(`student_id` ASC) USING BTREE,
  INDEX `counseling_sessions_school_index`(`school_id` ASC) USING BTREE,
  INDEX `counseling_sessions_counselor_index`(`counselor_id` ASC) USING BTREE,
  INDEX `counseling_sessions_category_index`(`category` ASC) USING BTREE,
  INDEX `counseling_sessions_status_index`(`status` ASC) USING BTREE,
  INDEX `counseling_sessions_session_date_index`(`session_date` ASC) USING BTREE,
  INDEX `counseling_sessions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `counseling_sessions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `counseling_sessions_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `counseling_sessions_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `counseling_sessions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `counseling_sessions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of counseling_sessions
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of courses
-- ----------------------------

-- ----------------------------
-- Table structure for currencies
-- ----------------------------
DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `decimal_places` tinyint UNSIGNED NOT NULL DEFAULT 2,
  `is_base` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `currencies_code_unique`(`code` ASC) USING BTREE,
  INDEX `currencies_is_base_index`(`is_base` ASC) USING BTREE,
  INDEX `currencies_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `currencies_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `currencies_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `currencies_updated_by_index`(`updated_by` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of currencies
-- ----------------------------

-- ----------------------------
-- Table structure for curriculum_phases
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_phases`;
CREATE TABLE `curriculum_phases`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `age_range` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `class_levels` json NULL,
  `competencies` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `curriculum_phases_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `curriculum_phases_school_index`(`school_id` ASC) USING BTREE,
  INDEX `curriculum_phases_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `curriculum_phases_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `curriculum_phases_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `curriculum_phases_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `curriculum_phases_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of curriculum_phases
-- ----------------------------

-- ----------------------------
-- Table structure for curriculum_structures
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_structures`;
CREATE TABLE `curriculum_structures`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `class_level_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `curriculum_phase_id` bigint UNSIGNED NULL DEFAULT NULL,
  `semester` enum('SEMESTER_1','SEMESTER_2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `hours_per_week` int NOT NULL,
  `hour_duration` int NOT NULL DEFAULT 45,
  `is_core` tinyint(1) NOT NULL DEFAULT 1,
  `min_student` int NOT NULL DEFAULT 1,
  `max_student` int NOT NULL DEFAULT 40,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `curriculum_structures_unique`(`school_id` ASC, `class_level_id` ASC, `subject_id` ASC, `academic_year_id` ASC, `semester` ASC) USING BTREE,
  INDEX `curriculum_structures_school_index`(`school_id` ASC) USING BTREE,
  INDEX `curriculum_structures_class_index`(`class_level_id` ASC) USING BTREE,
  INDEX `curriculum_structures_subject_index`(`subject_id` ASC) USING BTREE,
  INDEX `curriculum_structures_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `curriculum_structures_phase_index`(`curriculum_phase_id` ASC) USING BTREE,
  INDEX `curriculum_structures_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `curriculum_structures_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `curriculum_structures_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `curriculum_structures_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of customers
-- ----------------------------

-- ----------------------------
-- Table structure for dapodik_mappings
-- ----------------------------
DROP TABLE IF EXISTS `dapodik_mappings`;
CREATE TABLE `dapodik_mappings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `local_table` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `local_id` bigint UNSIGNED NOT NULL,
  `dapodik_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dapodik_table` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hash_value` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `last_sync_at` timestamp NULL DEFAULT NULL,
  `sync_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `retry_count` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `dapodik_mappings_local_unique`(`local_table` ASC, `local_id` ASC) USING BTREE,
  UNIQUE INDEX `dapodik_mappings_dapodik_unique`(`dapodik_table` ASC, `dapodik_id` ASC) USING BTREE,
  INDEX `dapodik_mappings_school_index`(`school_id` ASC) USING BTREE,
  INDEX `dapodik_mappings_local_index`(`local_table` ASC, `local_id` ASC) USING BTREE,
  INDEX `dapodik_mappings_dapodik_index`(`dapodik_table` ASC, `dapodik_id` ASC) USING BTREE,
  INDEX `dapodik_mappings_status_index`(`sync_status` ASC) USING BTREE,
  INDEX `dapodik_mappings_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `dapodik_mappings_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `dapodik_mappings_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `dapodik_mappings_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of dapodik_mappings
-- ----------------------------

-- ----------------------------
-- Table structure for dapodik_sync_logs
-- ----------------------------
DROP TABLE IF EXISTS `dapodik_sync_logs`;
CREATE TABLE `dapodik_sync_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `sync_date` timestamp NULL DEFAULT NULL,
  `sync_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `total_records` int NOT NULL DEFAULT 0,
  `total_synced` int NOT NULL DEFAULT 0,
  `total_failed` int NOT NULL DEFAULT 0,
  `total_conflicts` int NOT NULL DEFAULT 0,
  `sync_duration_seconds` int NULL DEFAULT NULL,
  `error_log` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `details` json NULL,
  `triggered_by` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `dapodik_sync_logs_school_index`(`school_id` ASC) USING BTREE,
  INDEX `dapodik_sync_logs_status_index`(`status` ASC) USING BTREE,
  INDEX `dapodik_sync_logs_sync_type_index`(`sync_type` ASC) USING BTREE,
  INDEX `dapodik_sync_logs_sync_date_index`(`sync_date` ASC) USING BTREE,
  INDEX `dapodik_sync_logs_triggered_by_index`(`triggered_by` ASC) USING BTREE,
  INDEX `dapodik_sync_logs_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `dapodik_sync_logs_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `dapodik_sync_logs_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `dapodik_sync_logs_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dapodik_sync_logs_triggered_by_foreign` FOREIGN KEY (`triggered_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of dapodik_sync_logs
-- ----------------------------

-- ----------------------------
-- Table structure for dapodik_sync_schedules
-- ----------------------------
DROP TABLE IF EXISTS `dapodik_sync_schedules`;
CREATE TABLE `dapodik_sync_schedules`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `schedule_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sync_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `frequency` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `last_run` timestamp NULL DEFAULT NULL,
  `next_run` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `dapodik_sync_schedules_school_index`(`school_id` ASC) USING BTREE,
  INDEX `dapodik_sync_schedules_next_run_index`(`next_run` ASC) USING BTREE,
  INDEX `dapodik_sync_schedules_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `dapodik_sync_schedules_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `dapodik_sync_schedules_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `dapodik_sync_schedules_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `dapodik_sync_schedules_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of dapodik_sync_schedules
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of dictionaries
-- ----------------------------

-- ----------------------------
-- Table structure for discussion_replies
-- ----------------------------
DROP TABLE IF EXISTS `discussion_replies`;
CREATE TABLE `discussion_replies`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `discussion_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_solution` tinyint(1) NOT NULL DEFAULT 0,
  `likes_count` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `discussion_replies_discussion_index`(`discussion_id` ASC) USING BTREE,
  INDEX `discussion_replies_user_index`(`user_id` ASC) USING BTREE,
  INDEX `discussion_replies_is_solution_index`(`is_solution` ASC) USING BTREE,
  INDEX `discussion_replies_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `discussion_replies_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `discussion_replies_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `discussion_replies_discussion_id_foreign` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `discussion_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of discussion_replies
-- ----------------------------

-- ----------------------------
-- Table structure for discussions
-- ----------------------------
DROP TABLE IF EXISTS `discussions`;
CREATE TABLE `discussions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `views_count` int NOT NULL DEFAULT 0,
  `replies_count` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `discussions_course_index`(`course_id` ASC) USING BTREE,
  INDEX `discussions_user_index`(`user_id` ASC) USING BTREE,
  INDEX `discussions_created_at_index`(`created_at` ASC) USING BTREE,
  INDEX `discussions_is_pinned_index`(`is_pinned` ASC) USING BTREE,
  INDEX `discussions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `discussions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `discussions_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `discussions_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `discussions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of discussions
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
  `level` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
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
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of education_levels
-- ----------------------------
INSERT INTO `education_levels` VALUES (1, 1, 'TK', 'Taman Kanak-kanak', NULL, 1, 1, '1', NULL, '2026-09-16 12:13:56', NULL, NULL, NULL);
INSERT INTO `education_levels` VALUES (2, 1, 'SD', 'Sekolah Dasar / Sederajat', NULL, 2, 1, '2', '2026-09-16 12:15:49', '2026-09-16 12:15:49', NULL, NULL, NULL);
INSERT INTO `education_levels` VALUES (3, 1, 'SMP', 'Sekolah Menengah Pertama / Sederajat', NULL, 3, 1, '3', '2026-09-16 12:15:49', '2026-09-16 12:15:49', NULL, NULL, NULL);
INSERT INTO `education_levels` VALUES (4, 1, 'SMA', 'Sekolah Menengah Atas / Sederajat', NULL, 4, 1, '4', '2026-09-16 12:15:49', '2026-09-16 12:15:49', NULL, NULL, NULL);
INSERT INTO `education_levels` VALUES (5, 1, 'D1', 'Diploma I', NULL, 5, 1, '5', '2026-09-16 12:15:49', '2026-09-16 12:15:49', NULL, NULL, NULL);
INSERT INTO `education_levels` VALUES (6, 1, 'D2', 'Diploma II', NULL, 6, 1, '6', '2026-09-16 12:15:49', '2026-09-16 12:15:49', NULL, NULL, NULL);
INSERT INTO `education_levels` VALUES (7, 1, 'D3', 'Diploma III', NULL, 7, 1, '7', '2026-09-16 12:15:49', '2026-09-16 12:15:49', NULL, NULL, NULL);
INSERT INTO `education_levels` VALUES (8, 1, 'D4', 'Diploma IV / Sarjana Terapan', NULL, 8, 1, '8', '2026-09-16 12:15:49', '2026-09-16 12:15:49', NULL, NULL, NULL);
INSERT INTO `education_levels` VALUES (9, 1, 'S1', 'Sarjana (S1)', NULL, 9, 1, '9', '2026-09-16 12:15:49', '2026-09-16 12:15:49', NULL, NULL, NULL);
INSERT INTO `education_levels` VALUES (10, 1, 'S2', 'Magister (S2)', NULL, 10, 1, '10', '2026-09-16 12:15:49', '2026-09-16 12:15:49', NULL, NULL, NULL);
INSERT INTO `education_levels` VALUES (11, 1, 'S3', 'Doktor (S3)', NULL, 11, 1, '11', '2026-09-16 12:15:49', '2026-09-16 12:15:49', NULL, NULL, NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of employees
-- ----------------------------

-- ----------------------------
-- Table structure for enrollment_conversions
-- ----------------------------
DROP TABLE IF EXISTS `enrollment_conversions`;
CREATE TABLE `enrollment_conversions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `converted_at` timestamp NULL DEFAULT NULL,
  `converted_by` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `enrollment_conversions_applicant_unique`(`applicant_id` ASC) USING BTREE,
  UNIQUE INDEX `enrollment_conversions_student_unique`(`student_id` ASC) USING BTREE,
  INDEX `enrollment_conversions_school_index`(`school_id` ASC) USING BTREE,
  INDEX `enrollment_conversions_admission_index`(`admission_id` ASC) USING BTREE,
  INDEX `enrollment_conversions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `enrollment_conversions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `enrollment_conversions_updated_by_index`(`updated_by` ASC) USING BTREE,
  INDEX `enrollment_conversions_converted_by_foreign`(`converted_by` ASC) USING BTREE,
  CONSTRAINT `enrollment_conversions_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `enrollment_conversions_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `enrollment_conversions_converted_by_foreign` FOREIGN KEY (`converted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `enrollment_conversions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `enrollment_conversions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of enrollment_conversions
-- ----------------------------

-- ----------------------------
-- Table structure for exam_package_questions
-- ----------------------------
DROP TABLE IF EXISTS `exam_package_questions`;
CREATE TABLE `exam_package_questions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `package_id` bigint UNSIGNED NOT NULL,
  `question_id` bigint UNSIGNED NOT NULL,
  `question_order` int NOT NULL DEFAULT 0,
  `points` int NOT NULL DEFAULT 10,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `exam_package_questions_unique`(`package_id` ASC, `question_id` ASC) USING BTREE,
  INDEX `exam_package_questions_package_index`(`package_id` ASC) USING BTREE,
  INDEX `exam_package_questions_question_index`(`question_id` ASC) USING BTREE,
  INDEX `exam_package_questions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `exam_package_questions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `exam_packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exam_package_questions_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of exam_package_questions
-- ----------------------------

-- ----------------------------
-- Table structure for exam_packages
-- ----------------------------
DROP TABLE IF EXISTS `exam_packages`;
CREATE TABLE `exam_packages`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `duration_minutes` int NOT NULL DEFAULT 60,
  `total_questions` int NOT NULL DEFAULT 0,
  `passing_score` int NOT NULL DEFAULT 70,
  `is_randomized` tinyint(1) NOT NULL DEFAULT 0,
  `shuffle_options` tinyint(1) NOT NULL DEFAULT 0,
  `show_results` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `exam_packages_school_index`(`school_id` ASC) USING BTREE,
  INDEX `exam_packages_subject_index`(`subject_id` ASC) USING BTREE,
  INDEX `exam_packages_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `exam_packages_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `exam_packages_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `exam_packages_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `exam_packages_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exam_packages_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of exam_packages
-- ----------------------------

-- ----------------------------
-- Table structure for exchange_rates
-- ----------------------------
DROP TABLE IF EXISTS `exchange_rates`;
CREATE TABLE `exchange_rates`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `currency_id` bigint UNSIGNED NOT NULL,
  `base_currency_id` bigint UNSIGNED NOT NULL,
  `rate_date` date NOT NULL,
  `rate` decimal(20, 8) NOT NULL,
  `source` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `exchange_rates_currency_date_unique`(`currency_id` ASC, `base_currency_id` ASC, `rate_date` ASC) USING BTREE,
  INDEX `exchange_rates_currency_index`(`currency_id` ASC) USING BTREE,
  INDEX `exchange_rates_base_index`(`base_currency_id` ASC) USING BTREE,
  INDEX `exchange_rates_date_index`(`rate_date` ASC) USING BTREE,
  INDEX `exchange_rates_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `exchange_rates_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `exchange_rates_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `exchange_rates_base_currency_id_foreign` FOREIGN KEY (`base_currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exchange_rates_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of exchange_rates
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of expenses
-- ----------------------------

-- ----------------------------
-- Table structure for extracurricular_attendances
-- ----------------------------
DROP TABLE IF EXISTS `extracurricular_attendances`;
CREATE TABLE `extracurricular_attendances`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_extracurricular_id` bigint UNSIGNED NOT NULL,
  `meeting_date` date NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `recorded_by` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `extracurricular_attendances_unique`(`student_extracurricular_id` ASC, `meeting_date` ASC) USING BTREE,
  INDEX `extracurricular_attendances_student_extracurricular_index`(`student_extracurricular_id` ASC) USING BTREE,
  INDEX `extracurricular_attendances_date_index`(`meeting_date` ASC) USING BTREE,
  INDEX `extracurricular_attendances_status_index`(`status` ASC) USING BTREE,
  INDEX `extracurricular_attendances_recorded_by_index`(`recorded_by` ASC) USING BTREE,
  INDEX `extracurricular_attendances_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `extracurricular_attendances_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `extracurricular_attendances_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `extracurricular_attendances_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `extracurricular_attendances_student_extracurricular_id_foreign` FOREIGN KEY (`student_extracurricular_id`) REFERENCES `student_extracurriculars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of extracurricular_attendances
-- ----------------------------

-- ----------------------------
-- Table structure for extracurriculars
-- ----------------------------
DROP TABLE IF EXISTS `extracurriculars`;
CREATE TABLE `extracurriculars`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `teacher_id` bigint UNSIGNED NULL DEFAULT NULL,
  `day_of_week` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `start_time` time NULL DEFAULT NULL,
  `end_time` time NULL DEFAULT NULL,
  `max_students` int NOT NULL DEFAULT 30,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `extracurriculars_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `extracurriculars_school_index`(`school_id` ASC) USING BTREE,
  INDEX `extracurriculars_teacher_index`(`teacher_id` ASC) USING BTREE,
  INDEX `extracurriculars_day_index`(`day_of_week` ASC) USING BTREE,
  INDEX `extracurriculars_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `extracurriculars_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `extracurriculars_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `extracurriculars_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `extracurriculars_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of extracurriculars
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for fee_masters
-- ----------------------------
DROP TABLE IF EXISTS `fee_masters`;
CREATE TABLE `fee_masters`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `class_level_id` bigint UNSIGNED NULL DEFAULT NULL,
  `fee_category` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fee_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `frequency` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `fee_masters_school_code_unique`(`school_id` ASC, `fee_code` ASC) USING BTREE,
  INDEX `fee_masters_school_index`(`school_id` ASC) USING BTREE,
  INDEX `fee_masters_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `fee_masters_class_level_index`(`class_level_id` ASC) USING BTREE,
  INDEX `fee_masters_category_index`(`fee_category` ASC) USING BTREE,
  INDEX `fee_masters_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `fee_masters_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `fee_masters_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `fee_masters_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fee_masters_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fee_masters_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of fee_masters
-- ----------------------------

-- ----------------------------
-- Table structure for fee_structures
-- ----------------------------
DROP TABLE IF EXISTS `fee_structures`;
CREATE TABLE `fee_structures`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `fee_master_id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `due_date` date NULL DEFAULT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `fee_structures_master_period_unique`(`fee_master_id` ASC, `period_id` ASC) USING BTREE,
  INDEX `fee_structures_master_index`(`fee_master_id` ASC) USING BTREE,
  INDEX `fee_structures_period_index`(`period_id` ASC) USING BTREE,
  INDEX `fee_structures_due_date_index`(`due_date` ASC) USING BTREE,
  INDEX `fee_structures_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `fee_structures_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `fee_structures_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `fee_structures_fee_master_id_foreign` FOREIGN KEY (`fee_master_id`) REFERENCES `fee_masters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fee_structures_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of fee_structures
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of finance_audit_logs
-- ----------------------------

-- ----------------------------
-- Table structure for fiscal_period_closings
-- ----------------------------
DROP TABLE IF EXISTS `fiscal_period_closings`;
CREATE TABLE `fiscal_period_closings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `fiscal_period_id` bigint UNSIGNED NOT NULL,
  `closing_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `closing_date` date NOT NULL,
  `total_debit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_credit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `net_income` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `retained_earnings` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `snapshot_data` json NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fiscal_period_closings_period_index`(`fiscal_period_id` ASC) USING BTREE,
  INDEX `fiscal_period_closings_type_index`(`closing_type` ASC) USING BTREE,
  INDEX `fiscal_period_closings_date_index`(`closing_date` ASC) USING BTREE,
  INDEX `fiscal_period_closings_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `fiscal_period_closings_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `fiscal_period_closings_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `fiscal_period_closings_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of fiscal_period_closings
-- ----------------------------

-- ----------------------------
-- Table structure for fiscal_periods
-- ----------------------------
DROP TABLE IF EXISTS `fiscal_periods`;
CREATE TABLE `fiscal_periods`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `year` smallint UNSIGNED NOT NULL,
  `month` tinyint UNSIGNED NULL DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `fiscal_periods_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `fiscal_periods_school_index`(`school_id` ASC) USING BTREE,
  INDEX `fiscal_periods_year_index`(`year` ASC) USING BTREE,
  INDEX `fiscal_periods_month_index`(`month` ASC) USING BTREE,
  INDEX `fiscal_periods_date_range_index`(`start_date` ASC, `end_date` ASC) USING BTREE,
  INDEX `fiscal_periods_is_closed_index`(`is_closed` ASC) USING BTREE,
  INDEX `fiscal_periods_closed_by_index`(`closed_by` ASC) USING BTREE,
  INDEX `fiscal_periods_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `fiscal_periods_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `fiscal_periods_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `fiscal_periods_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fiscal_periods_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of fiscal_periods
-- ----------------------------

-- ----------------------------
-- Table structure for fiscal_years
-- ----------------------------
DROP TABLE IF EXISTS `fiscal_years`;
CREATE TABLE `fiscal_years`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint NULL DEFAULT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `year` int NOT NULL,
  `month` int NULL DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('open','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NULL DEFAULT NULL,
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of fiscal_years
-- ----------------------------

-- ----------------------------
-- Table structure for fixed_assets
-- ----------------------------
DROP TABLE IF EXISTS `fixed_assets`;
CREATE TABLE `fixed_assets`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` int NULL DEFAULT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `asset_category_id` int NULL DEFAULT NULL,
  `brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `asset_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `acquisition_date` date NOT NULL,
  `acquisition_cost` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `residual_value` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `useful_life_months` int NOT NULL DEFAULT 0,
  `useful_life_years` int NULL DEFAULT NULL,
  `depreciation_method` enum('straight_line','declining_balance') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'straight_line',
  `accumulated_depreciation` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `book_value` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `asset_account_id` bigint UNSIGNED NOT NULL,
  `accumulated_depreciation_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `depreciation_expense_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` enum('draft','active','disposed','fully_depreciated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `condition` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `disposed_at` date NULL DEFAULT NULL,
  `disposal_value` decimal(15, 2) NULL DEFAULT NULL,
  `disposal_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `photo_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of genders
-- ----------------------------

-- ----------------------------
-- Table structure for generated_reports
-- ----------------------------
DROP TABLE IF EXISTS `generated_reports`;
CREATE TABLE `generated_reports`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `template_id` bigint UNSIGNED NULL DEFAULT NULL,
  `pdf_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_data` json NULL,
  `generated_at` timestamp NULL DEFAULT NULL,
  `downloaded_count` int NOT NULL DEFAULT 0,
  `last_download_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `generated_reports_student_index`(`student_id` ASC) USING BTREE,
  INDEX `generated_reports_period_index`(`period_id` ASC) USING BTREE,
  INDEX `generated_reports_template_index`(`template_id` ASC) USING BTREE,
  INDEX `generated_reports_generated_at_index`(`generated_at` ASC) USING BTREE,
  INDEX `generated_reports_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `generated_reports_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `generated_reports_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `generated_reports_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `generated_reports_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `generated_reports_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `report_card_templates` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of generated_reports
-- ----------------------------

-- ----------------------------
-- Table structure for goods_receipt_items
-- ----------------------------
DROP TABLE IF EXISTS `goods_receipt_items`;
CREATE TABLE `goods_receipt_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `goods_receipt_id` bigint UNSIGNED NOT NULL,
  `purchase_order_item_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ordered_quantity` decimal(20, 4) NOT NULL DEFAULT 0.0000,
  `received_quantity` decimal(20, 4) NOT NULL DEFAULT 0.0000,
  `rejected_quantity` decimal(20, 4) NOT NULL DEFAULT 0.0000,
  `unit_price` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `condition` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GOOD',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_asset` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `goods_receipt_items_receipt_index`(`goods_receipt_id` ASC) USING BTREE,
  INDEX `goods_receipt_items_po_item_index`(`purchase_order_item_id` ASC) USING BTREE,
  INDEX `goods_receipt_items_coa_index`(`account_id` ASC) USING BTREE,
  INDEX `goods_receipt_items_is_asset_index`(`is_asset` ASC) USING BTREE,
  INDEX `goods_receipt_items_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `goods_receipt_items_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `goods_receipt_items_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `goods_receipt_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `goods_receipt_items_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goods_receipt_items_purchase_order_item_id_foreign` FOREIGN KEY (`purchase_order_item_id`) REFERENCES `purchase_order_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of goods_receipt_items
-- ----------------------------

-- ----------------------------
-- Table structure for goods_receipts
-- ----------------------------
DROP TABLE IF EXISTS `goods_receipts`;
CREATE TABLE `goods_receipts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `receipt_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_date` date NOT NULL,
  `received_by` bigint UNSIGNED NULL DEFAULT NULL,
  `checked_by` bigint UNSIGNED NULL DEFAULT NULL,
  `delivery_note_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `vehicle_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `condition` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GOOD',
  `condition_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'RECEIVED',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `goods_receipts_school_number_unique`(`school_id` ASC, `receipt_number` ASC) USING BTREE,
  INDEX `goods_receipts_school_index`(`school_id` ASC) USING BTREE,
  INDEX `goods_receipts_po_index`(`purchase_order_id` ASC) USING BTREE,
  INDEX `goods_receipts_vendor_index`(`vendor_id` ASC) USING BTREE,
  INDEX `goods_receipts_date_index`(`receipt_date` ASC) USING BTREE,
  INDEX `goods_receipts_received_by_index`(`received_by` ASC) USING BTREE,
  INDEX `goods_receipts_checked_by_index`(`checked_by` ASC) USING BTREE,
  INDEX `goods_receipts_status_index`(`status` ASC) USING BTREE,
  INDEX `goods_receipts_condition_index`(`condition` ASC) USING BTREE,
  INDEX `goods_receipts_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `goods_receipts_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `goods_receipts_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `goods_receipts_checked_by_foreign` FOREIGN KEY (`checked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `goods_receipts_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goods_receipts_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `goods_receipts_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `goods_receipts_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of goods_receipts
-- ----------------------------

-- ----------------------------
-- Table structure for grade_scales
-- ----------------------------
DROP TABLE IF EXISTS `grade_scales`;
CREATE TABLE `grade_scales`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_score` decimal(5, 2) NOT NULL,
  `max_score` decimal(5, 2) NOT NULL,
  `grade_letter` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `predicate` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `grade_scales_school_letter_unique`(`school_id` ASC, `grade_letter` ASC) USING BTREE,
  INDEX `grade_scales_school_index`(`school_id` ASC) USING BTREE,
  INDEX `grade_scales_is_default_index`(`is_default` ASC) USING BTREE,
  INDEX `grade_scales_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `grade_scales_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `grade_scales_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `grade_scales_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of grade_scales
-- ----------------------------

-- ----------------------------
-- Table structure for grades
-- ----------------------------
DROP TABLE IF EXISTS `grades`;
CREATE TABLE `grades`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_classroom_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `teacher_id` bigint UNSIGNED NULL DEFAULT NULL,
  `score` decimal(5, 2) NULL DEFAULT NULL,
  `grade_letter` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `predicate` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `assessment_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `assessment_category` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `weight` int NOT NULL DEFAULT 1,
  `is_final` tinyint(1) NOT NULL DEFAULT 0,
  `is_counted_in_report` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `grades_unique`(`student_classroom_id` ASC, `subject_id` ASC, `period_id` ASC, `assessment_type` ASC) USING BTREE,
  INDEX `grades_student_classroom_index`(`student_classroom_id` ASC) USING BTREE,
  INDEX `grades_subject_index`(`subject_id` ASC) USING BTREE,
  INDEX `grades_period_index`(`period_id` ASC) USING BTREE,
  INDEX `grades_teacher_index`(`teacher_id` ASC) USING BTREE,
  INDEX `grades_is_final_index`(`is_final` ASC) USING BTREE,
  INDEX `grades_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `grades_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `grades_updated_by_index`(`updated_by` ASC) USING BTREE,
  INDEX `grades_assessment_category_index`(`assessment_category` ASC) USING BTREE,
  INDEX `grades_is_counted_in_report_index`(`is_counted_in_report` ASC) USING BTREE,
  CONSTRAINT `grades_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grades_student_classroom_id_foreign` FOREIGN KEY (`student_classroom_id`) REFERENCES `student_classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grades_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grades_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of grades
-- ----------------------------

-- ----------------------------
-- Table structure for graduations
-- ----------------------------
DROP TABLE IF EXISTS `graduations`;
CREATE TABLE `graduations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `classroom_id` bigint UNSIGNED NULL DEFAULT NULL,
  `graduation_date` date NULL DEFAULT NULL,
  `certificate_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `certificate_issued_at` date NULL DEFAULT NULL,
  `skhu_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `skhu_issued_at` date NULL DEFAULT NULL,
  `transcript_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `final_score_average` decimal(5, 2) NULL DEFAULT NULL,
  `predicate` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'LULUS',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `graduations_student_year_unique`(`student_id` ASC, `academic_year_id` ASC) USING BTREE,
  UNIQUE INDEX `graduations_certificate_number_unique`(`certificate_number` ASC) USING BTREE,
  UNIQUE INDEX `graduations_skhu_number_unique`(`skhu_number` ASC) USING BTREE,
  INDEX `graduations_student_index`(`student_id` ASC) USING BTREE,
  INDEX `graduations_school_index`(`school_id` ASC) USING BTREE,
  INDEX `graduations_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `graduations_classroom_index`(`classroom_id` ASC) USING BTREE,
  INDEX `graduations_status_index`(`status` ASC) USING BTREE,
  INDEX `graduations_graduation_date_index`(`graduation_date` ASC) USING BTREE,
  INDEX `graduations_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `graduations_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `graduations_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `graduations_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `graduations_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `graduations_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `graduations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of graduations
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of holidays
-- ----------------------------

-- ----------------------------
-- Table structure for homeroom_teachers
-- ----------------------------
DROP TABLE IF EXISTS `homeroom_teachers`;
CREATE TABLE `homeroom_teachers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `teacher_id` bigint UNSIGNED NOT NULL COMMENT 'Merujuk ke tabel teachers',
  `classroom_id` bigint UNSIGNED NOT NULL COMMENT 'Merujuk ke tabel classrooms',
  `academic_year_id` bigint UNSIGNED NOT NULL COMMENT 'Tahun ajaran penugasan',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `assignment_date` date NULL DEFAULT NULL COMMENT 'Tanggal ditunjuk sebagai wali kelas',
  `end_date` date NULL DEFAULT NULL COMMENT 'Tanggal berakhirnya tugas (jika diganti)',
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Catatan tambahan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `homeroom_class_year_unique`(`classroom_id` ASC, `academic_year_id` ASC) USING BTREE,
  UNIQUE INDEX `homeroom_teacher_class_unique`(`teacher_id` ASC, `classroom_id` ASC, `academic_year_id` ASC) USING BTREE,
  INDEX `idx_teacher_id`(`teacher_id` ASC) USING BTREE,
  INDEX `idx_classroom_id`(`classroom_id` ASC) USING BTREE,
  INDEX `idx_academic_year`(`academic_year_id` ASC) USING BTREE,
  INDEX `idx_organization`(`organization_id` ASC) USING BTREE,
  INDEX `idx_is_active`(`is_active` ASC) USING BTREE,
  CONSTRAINT `hr_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `hr_classroom_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `hr_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `hr_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Penugasan wali kelas per tahun ajaran' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of homeroom_teachers
-- ----------------------------
INSERT INTO `homeroom_teachers` VALUES (1, 1, 1, 1, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);
INSERT INTO `homeroom_teachers` VALUES (2, 1, 2, 2, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);
INSERT INTO `homeroom_teachers` VALUES (3, 1, 1, 3, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);
INSERT INTO `homeroom_teachers` VALUES (4, 1, 2, 4, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);
INSERT INTO `homeroom_teachers` VALUES (5, 1, 1, 5, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);
INSERT INTO `homeroom_teachers` VALUES (6, 1, 2, 6, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);
INSERT INTO `homeroom_teachers` VALUES (7, 1, 1, 7, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);
INSERT INTO `homeroom_teachers` VALUES (8, 1, 2, 8, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);
INSERT INTO `homeroom_teachers` VALUES (9, 1, 1, 9, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);
INSERT INTO `homeroom_teachers` VALUES (10, 1, 2, 10, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);
INSERT INTO `homeroom_teachers` VALUES (11, 1, 1, 11, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);
INSERT INTO `homeroom_teachers` VALUES (12, 1, 2, 12, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);
INSERT INTO `homeroom_teachers` VALUES (13, 1, 1, 13, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);
INSERT INTO `homeroom_teachers` VALUES (14, 1, 2, 14, 5, 1, '2026-09-16', '2026-09-16', NULL, '2026-09-16 13:14:08', '2026-09-16 13:14:08', NULL, NULL, NULL);

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of journal_details
-- ----------------------------

-- ----------------------------
-- Table structure for journal_entries
-- ----------------------------
DROP TABLE IF EXISTS `journal_entries`;
CREATE TABLE `journal_entries`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `accounting_period_id` bigint UNSIGNED NOT NULL,
  `fiscal_period_id` bigint UNSIGNED NOT NULL,
  `journal_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `journal_date` date NOT NULL,
  `journal_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GENERAL',
  `reference_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reference_id` bigint UNSIGNED NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `source_type` enum('manual','payroll','sales','purchase','expense','transfer','tax','opening','adjustment','closing') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `source_id` bigint UNSIGNED NULL DEFAULT NULL,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `total_debit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_credit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','approved','posted','void') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `reversal_of_id` bigint UNSIGNED NULL DEFAULT NULL,
  `reversed_by_id` bigint UNSIGNED NULL DEFAULT NULL,
  `reversal_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `posted_at` timestamp NULL DEFAULT NULL,
  `posted_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `journal_entries_school_number_unique`(`school_id` ASC, `journal_number` ASC) USING BTREE,
  INDEX `journal_entries_school_index`(`school_id` ASC) USING BTREE,
  INDEX `journal_entries_fiscal_period_index`(`fiscal_period_id` ASC) USING BTREE,
  INDEX `journal_entries_date_index`(`journal_date` ASC) USING BTREE,
  INDEX `journal_entries_type_index`(`journal_type` ASC) USING BTREE,
  INDEX `journal_entries_status_index`(`status` ASC) USING BTREE,
  INDEX `journal_entries_reference_index`(`reference_type` ASC, `reference_id` ASC) USING BTREE,
  INDEX `journal_entries_posted_by_index`(`posted_by` ASC) USING BTREE,
  INDEX `journal_entries_reversed_by_index`(`reversed_by_id` ASC) USING BTREE,
  INDEX `journal_entries_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `journal_entries_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `journal_entries_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `journal_entries_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `journal_entries_posted_by_foreign` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `journal_entries_reversed_by_id_foreign` FOREIGN KEY (`reversed_by_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `journal_entries_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of journal_entries
-- ----------------------------

-- ----------------------------
-- Table structure for journal_entry_lines
-- ----------------------------
DROP TABLE IF EXISTS `journal_entry_lines`;
CREATE TABLE `journal_entry_lines`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `journal_entry_id` bigint UNSIGNED NOT NULL,
  `chart_of_account_id` bigint UNSIGNED NOT NULL,
  `line_number` int NOT NULL DEFAULT 1,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `debit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `credit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `currency_id` bigint UNSIGNED NOT NULL,
  `exchange_rate` decimal(20, 8) NOT NULL DEFAULT 1.00000000,
  `foreign_debit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `foreign_credit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `functional_debit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `functional_credit` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `vendor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `journal_entry_lines_entry_index`(`journal_entry_id` ASC) USING BTREE,
  INDEX `journal_entry_lines_account_index`(`chart_of_account_id` ASC) USING BTREE,
  INDEX `journal_entry_lines_currency_index`(`currency_id` ASC) USING BTREE,
  INDEX `journal_entry_lines_vendor_index`(`vendor_id` ASC) USING BTREE,
  INDEX `journal_entry_lines_student_index`(`student_id` ASC) USING BTREE,
  INDEX `journal_entry_lines_line_number_index`(`line_number` ASC) USING BTREE,
  INDEX `journal_entry_lines_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `journal_entry_lines_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `journal_entry_lines_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `journal_entry_lines_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `journal_entry_lines_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `journal_entry_lines_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `journal_entry_lines_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `journal_entry_lines_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of journal_entry_lines
-- ----------------------------

-- ----------------------------
-- Table structure for learning_materials
-- ----------------------------
DROP TABLE IF EXISTS `learning_materials`;
CREATE TABLE `learning_materials`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `lesson_plan_id` bigint UNSIGNED NULL DEFAULT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `class_level_id` bigint UNSIGNED NULL DEFAULT NULL,
  `curriculum_phase_id` bigint UNSIGNED NULL DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `file_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `file_size` bigint NULL DEFAULT NULL,
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `order_index` int NOT NULL DEFAULT 0,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `learning_materials_school_index`(`school_id` ASC) USING BTREE,
  INDEX `learning_materials_lesson_plan_index`(`lesson_plan_id` ASC) USING BTREE,
  INDEX `learning_materials_subject_index`(`subject_id` ASC) USING BTREE,
  INDEX `learning_materials_class_level_index`(`class_level_id` ASC) USING BTREE,
  INDEX `learning_materials_phase_index`(`curriculum_phase_id` ASC) USING BTREE,
  INDEX `learning_materials_type_index`(`type` ASC) USING BTREE,
  INDEX `learning_materials_order_index`(`order_index` ASC) USING BTREE,
  INDEX `learning_materials_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `learning_materials_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `learning_materials_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `learning_materials_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `learning_materials_lesson_plan_id_foreign` FOREIGN KEY (`lesson_plan_id`) REFERENCES `lesson_plans` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `learning_materials_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `learning_materials_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `learning_materials_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of learning_materials
-- ----------------------------

-- ----------------------------
-- Table structure for learning_outcomes
-- ----------------------------
DROP TABLE IF EXISTS `learning_outcomes`;
CREATE TABLE `learning_outcomes`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `curriculum_phase_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `element` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `competency` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `order_index` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `learning_outcomes_school_index`(`school_id` ASC) USING BTREE,
  INDEX `learning_outcomes_phase_index`(`curriculum_phase_id` ASC) USING BTREE,
  INDEX `learning_outcomes_subject_index`(`subject_id` ASC) USING BTREE,
  INDEX `learning_outcomes_element_index`(`element` ASC) USING BTREE,
  INDEX `learning_outcomes_code_index`(`code` ASC) USING BTREE,
  INDEX `learning_outcomes_order_index`(`order_index` ASC) USING BTREE,
  INDEX `learning_outcomes_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `learning_outcomes_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `learning_outcomes_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `learning_outcomes_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `learning_outcomes_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `learning_outcomes_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of learning_outcomes
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of leave_types
-- ----------------------------

-- ----------------------------
-- Table structure for lesson_plans
-- ----------------------------
DROP TABLE IF EXISTS `lesson_plans`;
CREATE TABLE `lesson_plans`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `class_level_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `curriculum_phase_id` bigint UNSIGNED NULL DEFAULT NULL,
  `period_id` bigint UNSIGNED NULL DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `learning_objectives` json NULL,
  `learning_outcomes` json NULL,
  `activities` json NULL,
  `assessment_plan` json NULL,
  `media` json NULL,
  `resources` json NULL,
  `duration_minutes` int NULL DEFAULT NULL,
  `meeting_number` int NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `version` int NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `lesson_plans_school_index`(`school_id` ASC) USING BTREE,
  INDEX `lesson_plans_teacher_index`(`teacher_id` ASC) USING BTREE,
  INDEX `lesson_plans_subject_index`(`subject_id` ASC) USING BTREE,
  INDEX `lesson_plans_class_level_index`(`class_level_id` ASC) USING BTREE,
  INDEX `lesson_plans_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `lesson_plans_phase_index`(`curriculum_phase_id` ASC) USING BTREE,
  INDEX `lesson_plans_period_index`(`period_id` ASC) USING BTREE,
  INDEX `lesson_plans_status_index`(`status` ASC) USING BTREE,
  INDEX `lesson_plans_approved_by_index`(`approved_by` ASC) USING BTREE,
  INDEX `lesson_plans_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `lesson_plans_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `lesson_plans_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `lesson_plans_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of lesson_plans
-- ----------------------------

-- ----------------------------
-- Table structure for lesson_progress
-- ----------------------------
DROP TABLE IF EXISTS `lesson_progress`;
CREATE TABLE `lesson_progress`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `lesson_id` bigint UNSIGNED NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `progress_percent` int NOT NULL DEFAULT 0,
  `last_position_seconds` int NOT NULL DEFAULT 0,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `lesson_progress_student_lesson_unique`(`student_id` ASC, `lesson_id` ASC) USING BTREE,
  INDEX `lesson_progress_student_index`(`student_id` ASC) USING BTREE,
  INDEX `lesson_progress_lesson_index`(`lesson_id` ASC) USING BTREE,
  INDEX `lesson_progress_completed_index`(`is_completed` ASC) USING BTREE,
  INDEX `lesson_progress_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `lesson_progress_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `lesson_progress_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `lesson_progress_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lesson_progress_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of lesson_progress
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of lessons
-- ----------------------------

-- ----------------------------
-- Table structure for library_books
-- ----------------------------
DROP TABLE IF EXISTS `library_books`;
CREATE TABLE `library_books`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `isbn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `publisher` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `year_published` int NULL DEFAULT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cover_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `total_copies` int NOT NULL DEFAULT 1,
  `available_copies` int NOT NULL DEFAULT 1,
  `shelf_location` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `library_books_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `library_books_school_index`(`school_id` ASC) USING BTREE,
  INDEX `library_books_isbn_index`(`isbn` ASC) USING BTREE,
  INDEX `library_books_title_index`(`title` ASC) USING BTREE,
  INDEX `library_books_category_index`(`category` ASC) USING BTREE,
  INDEX `library_books_author_index`(`author` ASC) USING BTREE,
  INDEX `library_books_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `library_books_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `library_books_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `library_books_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of library_books
-- ----------------------------

-- ----------------------------
-- Table structure for library_loans
-- ----------------------------
DROP TABLE IF EXISTS `library_loans`;
CREATE TABLE `library_loans`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `book_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `teacher_id` bigint UNSIGNED NULL DEFAULT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `loan_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BORROWED',
  `fine_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `fine_paid` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `handled_by` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `library_loans_book_index`(`book_id` ASC) USING BTREE,
  INDEX `library_loans_student_index`(`student_id` ASC) USING BTREE,
  INDEX `library_loans_teacher_index`(`teacher_id` ASC) USING BTREE,
  INDEX `library_loans_school_index`(`school_id` ASC) USING BTREE,
  INDEX `library_loans_status_index`(`status` ASC) USING BTREE,
  INDEX `library_loans_loan_date_index`(`loan_date` ASC) USING BTREE,
  INDEX `library_loans_due_date_index`(`due_date` ASC) USING BTREE,
  INDEX `library_loans_handled_by_index`(`handled_by` ASC) USING BTREE,
  INDEX `library_loans_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `library_loans_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `library_loans_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `library_loans_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `library_books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `library_loans_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `library_loans_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `library_loans_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `library_loans_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of library_loans
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 87 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
INSERT INTO `menu_role` VALUES (12, 9, 2, 1, 0, 0, 0, '2026-09-12 06:56:01', '2026-09-12 06:56:01', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (13, 9, 1, 1, 0, 0, 0, '2026-09-12 06:56:01', '2026-09-12 06:56:01', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (14, 9, 3, 1, 0, 0, 0, '2026-09-12 06:56:01', '2026-09-12 06:56:01', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (15, 10, 2, 1, 1, 1, 1, '2026-09-12 07:02:09', '2026-09-12 07:02:09', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (16, 10, 1, 1, 1, 1, 1, '2026-09-12 07:02:09', '2026-09-12 07:02:09', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (17, 10, 3, 1, 1, 1, 1, '2026-09-12 07:02:09', '2026-09-12 07:02:09', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (18, 12, 2, 1, 1, 1, 1, '2026-09-12 07:05:35', '2026-09-12 07:05:35', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (19, 12, 1, 1, 1, 1, 1, '2026-09-12 07:05:35', '2026-09-12 07:05:35', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (20, 12, 3, 1, 1, 1, 1, '2026-09-12 07:05:35', '2026-09-12 07:05:35', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (21, 13, 2, 1, 0, 0, 0, '2026-09-12 07:29:52', '2026-09-12 07:29:52', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (22, 13, 1, 1, 0, 0, 0, '2026-09-12 07:29:52', '2026-09-12 07:29:52', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (23, 13, 3, 1, 0, 0, 0, '2026-09-12 07:29:52', '2026-09-12 07:29:52', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (24, 14, 1, 1, 1, 1, 1, '2026-09-12 07:33:13', '2026-09-12 07:33:13', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (25, 15, 1, 1, 1, 1, 1, '2026-09-12 07:35:16', '2026-09-12 07:35:16', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (26, 16, 1, 1, 1, 1, 1, '2026-09-12 16:03:41', '2026-09-12 16:03:41', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (27, 17, 2, 1, 0, 0, 0, '2026-09-12 23:53:01', '2026-09-12 23:53:01', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (28, 17, 1, 1, 0, 0, 0, '2026-09-12 23:53:01', '2026-09-12 23:53:01', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (29, 18, 2, 1, 0, 0, 0, '2026-09-12 23:56:28', '2026-09-12 23:56:28', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (30, 18, 1, 1, 0, 0, 0, '2026-09-12 23:56:28', '2026-09-12 23:56:28', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (31, 18, 3, 1, 0, 0, 0, '2026-09-12 23:56:28', '2026-09-12 23:56:28', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (32, 19, 2, 1, 0, 0, 0, '2026-09-12 23:56:55', '2026-09-12 23:56:55', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (33, 19, 1, 1, 0, 0, 0, '2026-09-12 23:56:55', '2026-09-12 23:56:55', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (34, 19, 3, 1, 0, 0, 0, '2026-09-12 23:56:55', '2026-09-12 23:56:55', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (35, 22, 2, 1, 0, 0, 0, '2026-09-13 10:24:58', '2026-09-13 10:24:58', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (36, 22, 1, 1, 0, 0, 0, '2026-09-13 10:24:58', '2026-09-13 10:24:58', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (37, 23, 2, 1, 0, 0, 0, '2026-09-13 10:29:58', '2026-09-13 10:29:58', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (38, 23, 1, 1, 0, 0, 0, '2026-09-13 10:29:58', '2026-09-13 10:29:58', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (39, 21, 2, 1, 1, 1, 1, '2026-09-13 16:29:10', '2026-09-13 16:29:10', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (40, 21, 1, 1, 1, 1, 1, '2026-09-13 16:29:10', '2026-09-13 16:29:10', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (41, 21, 3, 1, 1, 1, 1, '2026-09-13 16:29:10', '2026-09-13 16:29:10', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (43, 11, 2, 1, 1, 1, 1, '2026-09-13 16:49:59', '2026-09-13 16:49:59', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (44, 11, 1, 1, 1, 1, 1, '2026-09-13 16:49:59', '2026-09-13 16:49:59', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (45, 11, 3, 1, 1, 1, 1, '2026-09-13 16:49:59', '2026-09-13 16:49:59', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (47, 24, 1, 1, 1, 1, 1, '2026-09-16 18:42:27', '2026-09-16 18:42:27', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (52, 25, 1, 1, 1, 1, 1, '2026-09-16 02:49:45', '2026-09-16 02:49:45', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (53, 26, 1, 1, 1, 1, 1, '2026-09-16 02:51:41', '2026-09-16 02:51:41', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (54, 27, 1, 1, 1, 1, 1, '2026-09-16 02:54:03', '2026-09-16 02:54:03', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (55, 28, 1, 1, 1, 1, 1, '2026-09-16 05:49:42', '2026-09-16 05:49:42', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (56, 29, 1, 1, 1, 1, 1, '2026-09-16 05:52:19', '2026-09-16 05:52:19', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (57, 30, 1, 1, 1, 1, 1, '2026-09-16 05:58:00', '2026-09-16 05:58:00', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (58, 31, 2, 1, 0, 0, 0, '2026-09-16 16:52:10', '2026-09-16 16:52:10', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (59, 31, 1, 1, 0, 0, 0, '2026-09-16 16:52:10', '2026-09-16 16:52:10', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (60, 31, 3, 1, 0, 0, 0, '2026-09-16 16:52:10', '2026-09-16 16:52:10', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (61, 32, 2, 1, 1, 1, 1, '2026-09-16 17:24:47', '2026-09-16 17:24:47', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (62, 32, 1, 1, 1, 1, 1, '2026-09-16 17:24:47', '2026-09-16 17:24:47', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (63, 32, 3, 1, 1, 1, 1, '2026-09-16 17:24:47', '2026-09-16 17:24:47', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (64, 33, 2, 1, 1, 1, 1, '2026-09-16 17:24:59', '2026-09-16 17:24:59', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (65, 33, 1, 1, 1, 1, 1, '2026-09-16 17:24:59', '2026-09-16 17:24:59', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (66, 33, 3, 1, 1, 1, 1, '2026-09-16 17:24:59', '2026-09-16 17:24:59', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (67, 34, 1, 1, 1, 1, 1, '2026-09-16 17:50:15', '2026-09-16 17:50:15', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (68, 35, 1, 1, 1, 1, 1, '2026-09-16 17:50:45', '2026-09-16 17:50:45', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (69, 36, 1, 1, 1, 1, 1, '2026-09-16 17:50:35', '2026-09-16 17:50:35', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (70, 37, 1, 1, 1, 1, 1, '2026-09-16 17:50:23', '2026-09-16 17:50:23', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (71, 34, 2, 1, 1, 1, 1, '2026-09-16 17:50:15', '2026-09-16 17:50:15', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (72, 34, 3, 1, 1, 1, 1, '2026-09-16 17:50:15', '2026-09-16 17:50:15', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (73, 37, 2, 1, 1, 1, 1, '2026-09-16 17:50:23', '2026-09-16 17:50:23', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (74, 37, 3, 1, 1, 1, 1, '2026-09-16 17:50:23', '2026-09-16 17:50:23', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (75, 36, 2, 1, 1, 1, 1, '2026-09-16 17:50:35', '2026-09-16 17:50:35', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (76, 36, 3, 1, 1, 1, 1, '2026-09-16 17:50:35', '2026-09-16 17:50:35', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (77, 35, 2, 1, 1, 1, 1, '2026-09-16 17:50:45', '2026-09-16 17:50:45', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (78, 35, 3, 1, 1, 1, 1, '2026-09-16 17:50:45', '2026-09-16 17:50:45', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (79, 38, 1, 1, 1, 1, 1, '2026-09-16 17:52:44', '2026-09-16 17:52:44', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (80, 38, 2, 1, 1, 1, 1, '2026-09-16 17:52:44', '2026-09-16 17:52:44', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (81, 38, 3, 1, 1, 1, 1, '2026-09-16 17:52:44', '2026-09-16 17:52:44', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (82, 39, 1, 1, 1, 1, 1, '2026-09-16 17:57:39', '2026-09-16 17:57:39', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (83, 40, 1, 1, 1, 1, 1, '2026-09-16 18:13:52', '2026-09-16 18:13:52', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (84, 41, 1, 1, 1, 1, 1, '2026-09-16 18:16:34', '2026-09-16 18:16:34', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (85, 24, 2, 1, 1, 1, 1, '2026-09-16 18:42:27', '2026-09-16 18:42:27', NULL, NULL, NULL);
INSERT INTO `menu_role` VALUES (86, 24, 3, 1, 1, 1, 1, '2026-09-16 18:42:27', '2026-09-16 18:42:27', NULL, NULL, NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of menus
-- ----------------------------
INSERT INTO `menus` VALUES (1, 1, NULL, 'dashboard', 'Dasbord', 'Dasbord', 'dashboard', 'fas fa-home', 'fa', 'dashboard', 'dashboard', 'sidebar', 1, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-11 12:56:37', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (2, 1, NULL, NULL, 'Data Utama', 'Data Utama', 'master', 'fas fa-database', 'fa', NULL, NULL, 'sidebar', 2, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (3, 1, 2, 'user', 'Pengguna', 'Pengguna', 'user', 'fas fa-users', 'fa', 'user.index', 'user', 'sidebar', 1, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (4, 1, 2, 'role', 'Peran & Izin', 'Peran & Izin', 'roles', 'fas fa-user-shield', 'fa', 'role.index', 'role', 'sidebar', 2, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (5, 1, NULL, 'profile', 'Profil', 'Profil', 'profile', 'fas fa-user-circle', 'fa', 'profile.edit', 'profile', 'topbar', 1, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (6, 1, 2, 'menu management', 'Manage Menu', 'Manage Menu', 'menu', 'fas fa-user-circle', 'fa', 'menu_management.index', 'menu_management', 'sidebar', 1, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (7, 1, 2, 'module-generator', 'Module Generator', 'Module Generator', 'module-generator', 'fas fa-user-circle', 'fa', 'crud_generator.index', 'crud-generator', 'sidebar', 1, NULL, 1, NULL, NULL, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (9, 1, 11, 'Event', 'Event', 'Event', 'academic-calendar-event', 'fas fa-calendar', 'fa', 'academic_calendar_event.index', 'academic_calendar_event', 'sidebar', 0, NULL, 1, NULL, NULL, '2026-09-12 06:56:01', '2026-09-16 18:39:36', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (10, 1, 21, 'Account Mapping', 'COA Mapping', 'ACOA Mapping', 'account-mapping', 'fas fa-th-large', 'fa', 'account_mapping.index', 'account-mapping', 'sidebar', 0, NULL, 1, NULL, NULL, '2026-09-12 07:00:11', '2026-09-13 02:59:13', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (11, 1, NULL, NULL, 'Academic', 'Academic', 'academic', 'fas fa-box', 'fa', NULL, 'academic', 'sidebar', 3, NULL, 1, NULL, NULL, '2026-09-12 07:04:21', '2026-09-12 07:04:21', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (12, 1, NULL, 'Kesiswaan', 'Kesiswaan', 'Kesiswaan', 'students', 'fas fa-user', 'fa', NULL, 'student', 'sidebar', 4, NULL, 1, NULL, NULL, '2026-09-12 07:05:17', '2026-09-16 17:22:32', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (13, 1, 11, NULL, 'Academic Year', 'Academic Year', 'academic-year', 'fas fa-th-large', 'fa', 'academic_year.index', 'academic-year', 'sidebar', 0, NULL, 1, NULL, NULL, '2026-09-12 07:29:52', '2026-09-13 00:34:43', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (14, 1, 20, NULL, 'Reference Type', NULL, 'reference-type', 'list_alt', 'material', 'reference-type.index', 'reference-type', 'sidebar', 5, NULL, 1, NULL, NULL, '2026-09-12 07:33:13', '2026-09-12 23:59:59', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (15, 1, 20, NULL, 'Reference Value', NULL, 'reference-value', 'list_alt', 'material', 'reference-value.index', 'reference-value', 'sidebar', 6, NULL, 1, NULL, NULL, '2026-09-12 07:35:16', '2026-09-13 00:00:27', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (16, 1, 11, 'Period', 'Period', 'Semester', 'period', 'fas fa-calendar', 'fa', 'period.index', 'period', 'sidebar', 7, NULL, 1, NULL, NULL, '2026-09-12 16:03:41', '2026-09-13 16:50:48', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (17, 1, 21, 'COA', 'COA', 'COA', 'account', 'fas fa-th-large', 'fa', 'account.index', 'account', 'sidebar', 0, NULL, 1, NULL, NULL, '2026-09-12 23:53:01', '2026-09-13 16:51:58', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (18, 1, 21, 'Tax Setting', 'Tax Setting', 'Tax Setting', 'tax-setting', 'fas fa-th-large', 'fa', 'tax_setting.index', 'tax-setting', 'sidebar', 0, NULL, 1, NULL, NULL, '2026-09-12 23:56:28', '2026-09-13 16:39:15', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (19, 1, NULL, NULL, 'Shift Swap', 'Shift Swap', 'shift-swap', 'fas fa-th-large', 'fa', 'shift_swap.index', 'shift-swap', 'sidebar', 0, NULL, 1, NULL, NULL, '2026-09-12 23:56:55', '2026-09-12 23:56:55', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (20, 1, NULL, NULL, 'Referensi', 'Reference', 'referensi', 'fas fa-chart-pie', 'fa', NULL, 'reference', 'sidebar', 8, NULL, 1, NULL, NULL, '2026-09-12 23:58:17', '2026-09-13 00:01:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (21, 1, NULL, 'Accounting', 'Accounting', 'Accounting', 'accounting', 'dashboard', 'material', NULL, NULL, 'sidebar', 9, NULL, 1, NULL, NULL, '2026-09-13 00:04:55', '2026-09-13 16:37:53', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (22, 1, 21, NULL, 'Expense Category', 'Expense Category', 'expense-category', 'fas fa-th-large', 'fa', 'expense_category.index', 'expense-category', 'sidebar', 0, NULL, 1, NULL, NULL, '2026-09-13 10:24:58', '2026-09-13 10:24:58', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (23, 1, 21, 'COA Category', 'COA Category', 'COA Category', 'account-category', 'fas fa-th-large', 'fa', 'account_category.index', 'account-category', 'sidebar', 0, NULL, 1, NULL, NULL, '2026-09-13 10:29:58', '2026-09-16 18:40:22', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (24, 1, 21, NULL, 'Tax Type', 'Tax Type', 'tax-type', 'list_alt', 'material', 'tax-type.index', 'tax-type', 'sidebar', 10, NULL, 1, NULL, NULL, '2026-09-13 10:29:58', '2026-09-13 10:29:58', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (25, NULL, NULL, NULL, 'Cbt Session', NULL, 'cbt-session', 'list_alt', 'material', 'cbt-session.index', 'cbt-session', 'sidebar', 11, NULL, 1, NULL, NULL, '2026-09-16 02:49:45', '2026-09-16 02:49:45', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (26, NULL, NULL, NULL, 'Cbt Activity Log', NULL, 'cbt-activity-log', 'list_alt', 'material', 'cbt-activity-log.index', 'cbt-activity-log', 'sidebar', 12, NULL, 1, NULL, NULL, '2026-09-16 02:51:41', '2026-09-16 02:51:41', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (27, NULL, NULL, NULL, 'Cbt Proctoring', NULL, 'cbt-proctoring', 'list_alt', 'material', 'cbt-proctoring.index', 'cbt-proctoring', 'sidebar', 13, NULL, 1, NULL, NULL, '2026-09-16 02:54:03', '2026-09-16 02:54:03', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (28, NULL, NULL, NULL, 'Classroom', NULL, 'classroom', 'list_alt', 'material', 'classroom.index', 'classroom', 'sidebar', 14, NULL, 1, NULL, NULL, '2026-09-16 05:49:42', '2026-09-16 05:49:42', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (29, NULL, NULL, NULL, 'Homeroom Teacher', NULL, 'homeroom-teacher', 'list_alt', 'material', 'homeroom-teacher.index', 'homeroom-teacher', 'sidebar', 15, NULL, 1, NULL, NULL, '2026-09-16 05:52:19', '2026-09-16 05:52:19', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (30, NULL, NULL, NULL, 'Teacher', NULL, 'teacher', 'list_alt', 'material', 'teacher.index', 'teacher', 'sidebar', 16, NULL, 1, NULL, NULL, '2026-09-16 05:58:00', '2026-09-16 05:58:00', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (31, 1, 2, 'Menu Management', 'Menu Management', 'Menu Management', 'menu-management', 'fas fa-th-large', 'fa', 'menu_management.index', 'menu-management', 'sidebar', 0, NULL, 1, NULL, NULL, '2026-09-16 16:52:10', '2026-09-16 16:52:10', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (32, 1, 12, 'Students', 'Data Siswa', 'Students', 'student', 'fas fa-user', 'fa', 'student.index', 'student', 'sidebar', 11, NULL, 1, NULL, NULL, '2026-09-16 17:23:28', '2026-09-16 17:23:28', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (33, 1, 12, 'Pendaftaran (PPDB)', 'Pendaftaran (PPDB)', 'Pendaftaran (PPDB)', 'Pendaftaran (PPDB)', 'edit', 'material', NULL, NULL, 'sidebar', 12, NULL, 1, NULL, NULL, '2026-09-16 17:24:36', '2026-09-16 18:24:02', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (34, 1, 33, 'Pendaftar', 'Pendaftar', 'Applicant', 'applicant', 'list_alt', 'material', 'applicant.index', 'applicant', 'sidebar', 13, NULL, 1, NULL, NULL, '2026-09-16 17:39:34', '2026-09-16 17:42:20', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (35, 1, 33, 'Admission', 'Admission', 'Admission', 'admission', 'list_alt', 'material', 'admission.index', 'admission', 'sidebar', 14, NULL, 1, NULL, NULL, '2026-09-16 17:43:12', '2026-09-16 17:48:01', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (36, 1, 33, 'Admission Selection', 'Admission Selection', 'Admission Selection', 'admission-selection', 'list_alt', 'material', 'admission-selection.index', 'admission-selection', 'sidebar', 15, NULL, 1, NULL, NULL, '2026-09-16 17:44:23', '2026-09-16 17:48:43', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (37, 1, 33, 'Admission Timeline', 'Admission Timeline', 'Admission Timeline', 'admission-timeline', 'list_alt', 'material', 'admission-timeline.index', 'admission-timeline', 'sidebar', 16, NULL, 1, NULL, NULL, '2026-09-16 17:45:11', '2026-09-16 17:49:53', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (38, 1, 33, 'Application Document', 'Application Document', 'Application Document', 'application-document', 'list_alt', 'material', 'application-document.index', 'application-document', 'sidebar', 17, NULL, 1, NULL, NULL, '2026-09-16 17:51:18', '2026-09-16 17:52:37', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (39, 1, NULL, NULL, 'Class Level', NULL, 'class-level', 'list_alt', 'material', 'class-level.index', 'class-level', 'sidebar', 18, NULL, 1, NULL, NULL, '2026-09-16 17:57:39', '2026-09-16 17:57:39', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (40, 1, 33, 'Test Type', 'Test Type', 'Test Type', 'test-type', 'list_alt', 'material', 'test-type.index', 'test-type', 'sidebar', 19, NULL, 1, NULL, NULL, '2026-09-16 18:13:52', '2026-09-16 18:21:38', NULL, NULL, NULL);
INSERT INTO `menus` VALUES (41, 1, 33, 'Test Schedule', 'Test Schedule', 'Test Schedule', 'test-schedule', 'list_alt', 'material', 'test-schedule.index', 'test-schedule', 'sidebar', 20, NULL, 1, NULL, NULL, '2026-09-16 18:16:34', '2026-09-16 18:21:07', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for messages
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_id` bigint UNSIGNED NOT NULL,
  `receiver_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_message_id` bigint UNSIGNED NULL DEFAULT NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `messages_sender_index`(`sender_id` ASC) USING BTREE,
  INDEX `messages_receiver_index`(`receiver_id` ASC) USING BTREE,
  INDEX `messages_parent_index`(`parent_message_id` ASC) USING BTREE,
  INDEX `messages_is_read_index`(`is_read` ASC) USING BTREE,
  INDEX `messages_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `messages_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `messages_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `messages_parent_message_id_foreign` FOREIGN KEY (`parent_message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of messages
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2026_09_14_151743_create_tax_types_table', 1);

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of navigation
-- ----------------------------

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `reference_table` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reference_id` bigint UNSIGNED NULL DEFAULT NULL,
  `channel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IN_APP',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `notifications_user_index`(`user_id` ASC) USING BTREE,
  INDEX `notifications_type_index`(`type` ASC) USING BTREE,
  INDEX `notifications_channel_index`(`channel` ASC) USING BTREE,
  INDEX `notifications_is_read_index`(`is_read` ASC) USING BTREE,
  INDEX `notifications_reference_index`(`reference_table` ASC, `reference_id` ASC) USING BTREE,
  INDEX `notifications_sent_at_index`(`sent_at` ASC) USING BTREE,
  INDEX `notifications_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `notifications_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `notifications_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of notifications
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of organization_user
-- ----------------------------
INSERT INTO `organization_user` VALUES (1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `organization_user` VALUES (2, 1, 2, 1, 1, NULL, NULL, NULL, NULL, NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for payment_channels
-- ----------------------------
DROP TABLE IF EXISTS `payment_channels`;
CREATE TABLE `payment_channels`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_gateway_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `logo_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `admin_fee` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `admin_fee_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FIXED',
  `min_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `max_amount` decimal(20, 2) NULL DEFAULT NULL,
  `expiry_minutes` int NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `config` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payment_channels_gateway_code_unique`(`payment_gateway_id` ASC, `code` ASC) USING BTREE,
  INDEX `payment_channels_gateway_index`(`payment_gateway_id` ASC) USING BTREE,
  INDEX `payment_channels_code_index`(`code` ASC) USING BTREE,
  INDEX `payment_channels_type_index`(`channel_type` ASC) USING BTREE,
  INDEX `payment_channels_bank_index`(`bank_code` ASC) USING BTREE,
  INDEX `payment_channels_sort_index`(`sort_order` ASC) USING BTREE,
  INDEX `payment_channels_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `payment_channels_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `payment_channels_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `payment_channels_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `payment_channels_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of payment_channels
-- ----------------------------

-- ----------------------------
-- Table structure for payment_gateways
-- ----------------------------
DROP TABLE IF EXISTS `payment_gateways`;
CREATE TABLE `payment_gateways`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `provider` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MIDTRANS',
  `environment` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SANDBOX',
  `merchant_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `client_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `server_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_production` tinyint(1) NOT NULL DEFAULT 0,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `default_currency_id` bigint UNSIGNED NOT NULL,
  `expiry_minutes` int NOT NULL DEFAULT 1440,
  `config` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payment_gateways_school_provider_env_unique`(`school_id` ASC, `provider` ASC, `environment` ASC) USING BTREE,
  INDEX `payment_gateways_school_index`(`school_id` ASC) USING BTREE,
  INDEX `payment_gateways_provider_index`(`provider` ASC) USING BTREE,
  INDEX `payment_gateways_environment_index`(`environment` ASC) USING BTREE,
  INDEX `payment_gateways_is_primary_index`(`is_primary` ASC) USING BTREE,
  INDEX `payment_gateways_currency_index`(`default_currency_id` ASC) USING BTREE,
  INDEX `payment_gateways_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `payment_gateways_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `payment_gateways_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `payment_gateways_default_currency_id_foreign` FOREIGN KEY (`default_currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payment_gateways_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of payment_gateways
-- ----------------------------

-- ----------------------------
-- Table structure for payment_notifications
-- ----------------------------
DROP TABLE IF EXISTS `payment_notifications`;
CREATE TABLE `payment_notifications`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `payment_transaction_id` bigint UNSIGNED NULL DEFAULT NULL,
  `student_invoice_id` bigint UNSIGNED NULL DEFAULT NULL,
  `applicant_invoice_id` bigint UNSIGNED NULL DEFAULT NULL,
  `recipient_user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `recipient_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `recipient_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `recipient_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `channel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WHATSAPP',
  `template_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `subject` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `provider` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `provider_message_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `provider_response` json NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `sent_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `failed_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `retry_count` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `payment_notifications_school_index`(`school_id` ASC) USING BTREE,
  INDEX `payment_notifications_transaction_index`(`payment_transaction_id` ASC) USING BTREE,
  INDEX `payment_notifications_student_invoice_index`(`student_invoice_id` ASC) USING BTREE,
  INDEX `payment_notifications_applicant_invoice_index`(`applicant_invoice_id` ASC) USING BTREE,
  INDEX `payment_notifications_recipient_user_index`(`recipient_user_id` ASC) USING BTREE,
  INDEX `payment_notifications_phone_index`(`recipient_phone` ASC) USING BTREE,
  INDEX `payment_notifications_email_index`(`recipient_email` ASC) USING BTREE,
  INDEX `payment_notifications_channel_index`(`channel` ASC) USING BTREE,
  INDEX `payment_notifications_status_index`(`status` ASC) USING BTREE,
  INDEX `payment_notifications_sent_at_index`(`sent_at` ASC) USING BTREE,
  INDEX `payment_notifications_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `payment_notifications_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `payment_notifications_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `payment_notifications_applicant_invoice_id_foreign` FOREIGN KEY (`applicant_invoice_id`) REFERENCES `applicant_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_notifications_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_notifications_recipient_user_id_foreign` FOREIGN KEY (`recipient_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_notifications_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payment_notifications_student_invoice_id_foreign` FOREIGN KEY (`student_invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of payment_notifications
-- ----------------------------

-- ----------------------------
-- Table structure for payment_transactions
-- ----------------------------
DROP TABLE IF EXISTS `payment_transactions`;
CREATE TABLE `payment_transactions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `payment_gateway_id` bigint UNSIGNED NOT NULL,
  `payment_channel_id` bigint UNSIGNED NULL DEFAULT NULL,
  `student_invoice_id` bigint UNSIGNED NULL DEFAULT NULL,
  `applicant_invoice_id` bigint UNSIGNED NULL DEFAULT NULL,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `applicant_id` bigint UNSIGNED NULL DEFAULT NULL,
  `order_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `midtrans_transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `transaction_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `currency_id` bigint UNSIGNED NOT NULL,
  `gross_amount` decimal(20, 2) NOT NULL,
  `admin_fee` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `exchange_rate` decimal(20, 8) NOT NULL DEFAULT 1.00000000,
  `functional_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `transaction_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `fraud_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `transaction_time` timestamp NULL DEFAULT NULL,
  `settlement_time` timestamp NULL DEFAULT NULL,
  `expiry_time` timestamp NULL DEFAULT NULL,
  `va_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `va_bank` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `qris_string` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `qris_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payment_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `pdf_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `raw_request` json NULL,
  `raw_response` json NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `student_payment_id` bigint UNSIGNED NULL DEFAULT NULL,
  `applicant_payment_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_posted` tinyint(1) NOT NULL DEFAULT 0,
  `posted_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payment_transactions_order_id_unique`(`order_id` ASC) USING BTREE,
  UNIQUE INDEX `payment_transactions_midtrans_id_unique`(`midtrans_transaction_id` ASC) USING BTREE,
  INDEX `payment_transactions_school_index`(`school_id` ASC) USING BTREE,
  INDEX `payment_transactions_gateway_index`(`payment_gateway_id` ASC) USING BTREE,
  INDEX `payment_transactions_channel_index`(`payment_channel_id` ASC) USING BTREE,
  INDEX `payment_transactions_student_invoice_index`(`student_invoice_id` ASC) USING BTREE,
  INDEX `payment_transactions_applicant_invoice_index`(`applicant_invoice_id` ASC) USING BTREE,
  INDEX `payment_transactions_student_index`(`student_id` ASC) USING BTREE,
  INDEX `payment_transactions_applicant_index`(`applicant_id` ASC) USING BTREE,
  INDEX `payment_transactions_type_index`(`transaction_type` ASC) USING BTREE,
  INDEX `payment_transactions_payment_type_index`(`payment_type` ASC) USING BTREE,
  INDEX `payment_transactions_status_index`(`transaction_status` ASC) USING BTREE,
  INDEX `payment_transactions_fraud_index`(`fraud_status` ASC) USING BTREE,
  INDEX `payment_transactions_va_index`(`va_number` ASC) USING BTREE,
  INDEX `payment_transactions_transaction_time_index`(`transaction_time` ASC) USING BTREE,
  INDEX `payment_transactions_expiry_time_index`(`expiry_time` ASC) USING BTREE,
  INDEX `payment_transactions_journal_entry_index`(`journal_entry_id` ASC) USING BTREE,
  INDEX `payment_transactions_student_payment_index`(`student_payment_id` ASC) USING BTREE,
  INDEX `payment_transactions_applicant_payment_index`(`applicant_payment_id` ASC) USING BTREE,
  INDEX `payment_transactions_is_posted_index`(`is_posted` ASC) USING BTREE,
  INDEX `payment_transactions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `payment_transactions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `payment_transactions_updated_by_index`(`updated_by` ASC) USING BTREE,
  INDEX `payment_transactions_currency_id_foreign`(`currency_id` ASC) USING BTREE,
  CONSTRAINT `payment_transactions_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_applicant_invoice_id_foreign` FOREIGN KEY (`applicant_invoice_id`) REFERENCES `applicant_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_applicant_payment_id_foreign` FOREIGN KEY (`applicant_payment_id`) REFERENCES `applicant_payments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_payment_channel_id_foreign` FOREIGN KEY (`payment_channel_id`) REFERENCES `payment_channels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_student_invoice_id_foreign` FOREIGN KEY (`student_invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `payment_transactions_student_payment_id_foreign` FOREIGN KEY (`student_payment_id`) REFERENCES `student_payments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of payment_transactions
-- ----------------------------

-- ----------------------------
-- Table structure for payment_webhook_logs
-- ----------------------------
DROP TABLE IF EXISTS `payment_webhook_logs`;
CREATE TABLE `payment_webhook_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_gateway_id` bigint UNSIGNED NULL DEFAULT NULL,
  `order_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `event_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `signature_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `signature_valid` tinyint(1) NOT NULL DEFAULT 0,
  `is_processed` tinyint(1) NOT NULL DEFAULT 0,
  `processed_at` timestamp NULL DEFAULT NULL,
  `processing_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `retry_count` int NOT NULL DEFAULT 0,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `headers` json NULL,
  `payload` json NOT NULL,
  `response_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `response_status` int NULL DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `payment_webhook_logs_gateway_index`(`payment_gateway_id` ASC) USING BTREE,
  INDEX `payment_webhook_logs_order_index`(`order_id` ASC) USING BTREE,
  INDEX `payment_webhook_logs_transaction_index`(`transaction_id` ASC) USING BTREE,
  INDEX `payment_webhook_logs_event_index`(`event_type` ASC) USING BTREE,
  INDEX `payment_webhook_logs_is_processed_index`(`is_processed` ASC) USING BTREE,
  INDEX `payment_webhook_logs_received_at_index`(`received_at` ASC) USING BTREE,
  INDEX `payment_webhook_logs_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `payment_webhook_logs_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `payment_webhook_logs_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `payment_webhook_logs_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of payment_webhook_logs
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of payslips
-- ----------------------------

-- ----------------------------
-- Table structure for periods
-- ----------------------------
DROP TABLE IF EXISTS `periods`;
CREATE TABLE `periods`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `semester` int UNSIGNED NULL DEFAULT NULL,
  `weight` int NULL DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `grading_start` date NULL DEFAULT NULL,
  `grading_end` date NULL DEFAULT NULL,
  `assessment_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `semester_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_periods_year_code`(`academic_year_id` ASC, `code` ASC) USING BTREE,
  INDEX `created_by`(`created_by` ASC) USING BTREE,
  INDEX `updated_by`(`updated_by` ASC) USING BTREE,
  INDEX `idx_periods_academic_year`(`academic_year_id` ASC) USING BTREE,
  INDEX `idx_periods_date_range`(`start_date` ASC, `end_date` ASC) USING BTREE,
  CONSTRAINT `periods_ibfk_1` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `periods_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `periods_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `chk_periods_semester` CHECK (`semester` in (1,2,3,4)),
  CONSTRAINT `chk_periods_type` CHECK (`type` in (_utf8mb4'SEMESTER',_utf8mb4'QUARTER',_utf8mb4'TERM'))
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of periods
-- ----------------------------
INSERT INTO `periods` VALUES (1, 1, 'Semester Ganjil', '2023-2024-GANJIL-S1', 'semester', 1, 1, '2023-07-15', '2023-12-20', '2023-09-09', '2023-12-06', '1', 'Semester', 1, '2026-09-13 17:09:48', '2026-09-13 17:36:46', NULL, NULL, NULL);
INSERT INTO `periods` VALUES (2, 2, 'Semester Genap', '2023-2024-GENAP-S2', 'semester', 2, 1, '2024-01-10', '2024-06-15', '2024-03-06', '2024-06-01', 'Sumatif', 'Semester', 0, '2026-09-13 17:09:48', '2026-09-14 00:30:05', NULL, NULL, NULL);
INSERT INTO `periods` VALUES (3, 3, 'Semester Ganjil', '2024-2025-GANJIL-S1', 'semester', 1, 1, '2024-07-15', '2024-12-20', '2024-09-09', '2024-12-06', 'Sumatif', 'Semester', 0, '2026-09-13 17:09:48', '2026-09-14 00:30:05', NULL, NULL, NULL);
INSERT INTO `periods` VALUES (4, 4, 'Semester Genap', '2024-2025-GENAP-S2', 'semester', 2, 1, '2025-01-10', '2025-06-15', '2025-03-07', '2025-06-01', 'Sumatif', 'Semester', 0, '2026-09-13 17:09:48', '2026-09-14 00:30:05', NULL, NULL, NULL);
INSERT INTO `periods` VALUES (5, 5, 'Semester Ganjil', '2025-2026-GANJIL-S1', 'semester', 1, 1, '2025-07-15', '2025-12-20', '2025-09-09', '2025-12-06', 'Sumatif', 'Semester', 1, '2026-09-13 17:09:48', '2026-09-14 00:30:05', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for permission_requests
-- ----------------------------
DROP TABLE IF EXISTS `permission_requests`;
CREATE TABLE `permission_requests`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `request_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `permission_requests_student_index`(`student_id` ASC) USING BTREE,
  INDEX `permission_requests_school_index`(`school_id` ASC) USING BTREE,
  INDEX `permission_requests_type_index`(`request_type` ASC) USING BTREE,
  INDEX `permission_requests_status_index`(`status` ASC) USING BTREE,
  INDEX `permission_requests_date_index`(`start_date` ASC, `end_date` ASC) USING BTREE,
  INDEX `permission_requests_approved_by_index`(`approved_by` ASC) USING BTREE,
  INDEX `permission_requests_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `permission_requests_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `permission_requests_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `permission_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `permission_requests_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of permission_requests
-- ----------------------------

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_code_unique`(`code` ASC) USING BTREE,
  INDEX `permissions_module_index`(`module` ASC) USING BTREE,
  INDEX `permissions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `permissions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `permissions_updated_by_index`(`updated_by` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of permissions
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ptkp_statuses
-- ----------------------------

-- ----------------------------
-- Table structure for purchase_order_items
-- ----------------------------
DROP TABLE IF EXISTS `purchase_order_items`;
CREATE TABLE `purchase_order_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint UNSIGNED NOT NULL,
  `purchase_request_item_id` bigint UNSIGNED NULL DEFAULT NULL,
  `account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `quantity` decimal(20, 4) NOT NULL DEFAULT 1.0000,
  `unit_price` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `received_quantity` decimal(20, 4) NOT NULL DEFAULT 0.0000,
  `line_number` int NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `purchase_order_items_po_index`(`purchase_order_id` ASC) USING BTREE,
  INDEX `purchase_order_items_request_item_index`(`purchase_request_item_id` ASC) USING BTREE,
  INDEX `purchase_order_items_coa_index`(`account_id` ASC) USING BTREE,
  INDEX `purchase_order_items_line_number_index`(`line_number` ASC) USING BTREE,
  INDEX `purchase_order_items_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `purchase_order_items_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `purchase_order_items_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `purchase_order_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_order_items_purchase_request_item_id_foreign` FOREIGN KEY (`purchase_request_item_id`) REFERENCES `purchase_request_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of purchase_order_items
-- ----------------------------

-- ----------------------------
-- Table structure for purchase_orders
-- ----------------------------
DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE `purchase_orders`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `purchase_request_id` bigint UNSIGNED NULL DEFAULT NULL,
  `fiscal_period_id` bigint UNSIGNED NOT NULL,
  `currency_id` bigint UNSIGNED NOT NULL,
  `exchange_rate` decimal(20, 8) NOT NULL DEFAULT 1.00000000,
  `po_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `po_date` date NOT NULL,
  `expected_delivery_date` date NULL DEFAULT NULL,
  `delivery_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payment_terms` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `subtotal` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `tax_base` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `ppn_rate` decimal(5, 2) NOT NULL DEFAULT 0.00,
  `ppn_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `shipping_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `functional_total` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sip_lah_order_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sip_lah_order_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `purchase_orders_school_number_unique`(`school_id` ASC, `po_number` ASC) USING BTREE,
  INDEX `purchase_orders_school_index`(`school_id` ASC) USING BTREE,
  INDEX `purchase_orders_vendor_index`(`vendor_id` ASC) USING BTREE,
  INDEX `purchase_orders_request_index`(`purchase_request_id` ASC) USING BTREE,
  INDEX `purchase_orders_fiscal_period_index`(`fiscal_period_id` ASC) USING BTREE,
  INDEX `purchase_orders_currency_index`(`currency_id` ASC) USING BTREE,
  INDEX `purchase_orders_date_index`(`po_date` ASC) USING BTREE,
  INDEX `purchase_orders_status_index`(`status` ASC) USING BTREE,
  INDEX `purchase_orders_approved_by_index`(`approved_by` ASC) USING BTREE,
  INDEX `purchase_orders_siplah_order_index`(`sip_lah_order_id` ASC) USING BTREE,
  INDEX `purchase_orders_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `purchase_orders_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `purchase_orders_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `purchase_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_orders_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_orders_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_orders_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_orders_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_orders_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of purchase_orders
-- ----------------------------

-- ----------------------------
-- Table structure for purchase_request_items
-- ----------------------------
DROP TABLE IF EXISTS `purchase_request_items`;
CREATE TABLE `purchase_request_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_request_id` bigint UNSIGNED NOT NULL,
  `rkas_item_id` bigint UNSIGNED NULL DEFAULT NULL,
  `account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `quantity` decimal(20, 4) NOT NULL DEFAULT 1.0000,
  `estimated_unit_price` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `estimated_subtotal` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `estimated_total` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `purchase_request_items_request_index`(`purchase_request_id` ASC) USING BTREE,
  INDEX `purchase_request_items_rkas_item_index`(`rkas_item_id` ASC) USING BTREE,
  INDEX `purchase_request_items_coa_index`(`account_id` ASC) USING BTREE,
  INDEX `purchase_request_items_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `purchase_request_items_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `purchase_request_items_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `purchase_request_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_request_items_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `purchase_request_items_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of purchase_request_items
-- ----------------------------

-- ----------------------------
-- Table structure for purchase_requests
-- ----------------------------
DROP TABLE IF EXISTS `purchase_requests`;
CREATE TABLE `purchase_requests`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `rkas_item_id` bigint UNSIGNED NULL DEFAULT NULL,
  `request_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `requested_by` bigint UNSIGNED NULL DEFAULT NULL,
  `requested_at` timestamp NULL DEFAULT NULL,
  `needed_by` date NULL DEFAULT NULL,
  `total_estimated_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `purchase_requests_school_number_unique`(`school_id` ASC, `request_number` ASC) USING BTREE,
  INDEX `purchase_requests_school_index`(`school_id` ASC) USING BTREE,
  INDEX `purchase_requests_rkas_item_index`(`rkas_item_id` ASC) USING BTREE,
  INDEX `purchase_requests_requested_by_index`(`requested_by` ASC) USING BTREE,
  INDEX `purchase_requests_approved_by_index`(`approved_by` ASC) USING BTREE,
  INDEX `purchase_requests_status_index`(`status` ASC) USING BTREE,
  INDEX `purchase_requests_needed_by_index`(`needed_by` ASC) USING BTREE,
  INDEX `purchase_requests_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `purchase_requests_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `purchase_requests_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `purchase_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_requests_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `purchase_requests_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of purchase_requests
-- ----------------------------

-- ----------------------------
-- Table structure for qris_transactions
-- ----------------------------
DROP TABLE IF EXISTS `qris_transactions`;
CREATE TABLE `qris_transactions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `payment_transaction_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `applicant_id` bigint UNSIGNED NULL DEFAULT NULL,
  `issuer` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `acquirer` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `qris_string` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `qris_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `expiry_time` timestamp NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `paid_at` timestamp NULL DEFAULT NULL,
  `raw_response` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `qris_transactions_school_index`(`school_id` ASC) USING BTREE,
  INDEX `qris_transactions_transaction_index`(`payment_transaction_id` ASC) USING BTREE,
  INDEX `qris_transactions_student_index`(`student_id` ASC) USING BTREE,
  INDEX `qris_transactions_applicant_index`(`applicant_id` ASC) USING BTREE,
  INDEX `qris_transactions_status_index`(`status` ASC) USING BTREE,
  INDEX `qris_transactions_expiry_index`(`expiry_time` ASC) USING BTREE,
  INDEX `qris_transactions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `qris_transactions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `qris_transactions_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `qris_transactions_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `qris_transactions_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `qris_transactions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `qris_transactions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of qris_transactions
-- ----------------------------

-- ----------------------------
-- Table structure for question_banks
-- ----------------------------
DROP TABLE IF EXISTS `question_banks`;
CREATE TABLE `question_banks`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `question_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `difficulty_level` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cognitive_level` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `question_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` json NULL,
  `correct_answer` json NULL,
  `explanation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `tags` json NULL,
  `points` int NOT NULL DEFAULT 10,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `question_banks_school_index`(`school_id` ASC) USING BTREE,
  INDEX `question_banks_subject_index`(`subject_id` ASC) USING BTREE,
  INDEX `question_banks_type_index`(`question_type` ASC) USING BTREE,
  INDEX `question_banks_difficulty_index`(`difficulty_level` ASC) USING BTREE,
  INDEX `question_banks_cognitive_index`(`cognitive_level` ASC) USING BTREE,
  INDEX `question_banks_is_public_index`(`is_public` ASC) USING BTREE,
  INDEX `question_banks_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `question_banks_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `question_banks_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `question_banks_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `question_banks_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `question_banks_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of question_banks
-- ----------------------------

-- ----------------------------
-- Table structure for quiz_attempts
-- ----------------------------
DROP TABLE IF EXISTS `quiz_attempts`;
CREATE TABLE `quiz_attempts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `assignment_quiz_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `answers` json NOT NULL,
  `score` decimal(5, 2) NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `time_spent_seconds` int NULL DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `quiz_attempts_assignment_index`(`assignment_quiz_id` ASC) USING BTREE,
  INDEX `quiz_attempts_student_index`(`student_id` ASC) USING BTREE,
  INDEX `quiz_attempts_is_completed_index`(`is_completed` ASC) USING BTREE,
  INDEX `quiz_attempts_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `quiz_attempts_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `quiz_attempts_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `quiz_attempts_assignment_quiz_id_foreign` FOREIGN KEY (`assignment_quiz_id`) REFERENCES `assignments_quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `quiz_attempts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of quiz_attempts
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of reconciliations
-- ----------------------------

-- ----------------------------
-- Table structure for reference_types
-- ----------------------------
DROP TABLE IF EXISTS `reference_types`;
CREATE TABLE `reference_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `reference_types_code_unique`(`code` ASC) USING BTREE,
  INDEX `reference_types_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `reference_types_deleted_at_index`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of reference_types
-- ----------------------------
INSERT INTO `reference_types` VALUES (1, 'STUDENT_STATUS', 'Student Status', 'Status siswa', 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_types` VALUES (2, 'ADMISSION_STATUS', 'Admission Status', 'Status penerimaan siswa', 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_types` VALUES (3, 'ATTENDANCE_STATUS', 'Attendance Status', 'Status kehadiran', 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_types` VALUES (4, 'PARENT_RELATIONSHIP', 'Parent Relationship', 'Hubungan orang tua/wali', 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_types` VALUES (5, 'PAYMENT_STATUS', 'Payment Status', 'Status pembayaran', 1, 1, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for reference_values
-- ----------------------------
DROP TABLE IF EXISTS `reference_values`;
CREATE TABLE `reference_values`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference_type_id` bigint UNSIGNED NOT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `badge_color` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'secondary',
  `badge_class` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `text_color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `reference_values_type_code_unique`(`reference_type_id` ASC, `code` ASC) USING BTREE,
  INDEX `reference_values_type_index`(`reference_type_id` ASC) USING BTREE,
  INDEX `reference_values_active_index`(`is_active` ASC) USING BTREE,
  INDEX `reference_values_sort_order_index`(`sort_order` ASC) USING BTREE,
  INDEX `reference_values_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `reference_values_type_fk` FOREIGN KEY (`reference_type_id`) REFERENCES `reference_types` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of reference_values
-- ----------------------------
INSERT INTO `reference_values` VALUES (1, 1, 'ACTIVE', 'Active', NULL, 'success', 'badge bg-success', 'text-white', 1, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (2, 1, 'GRADUATED', 'Graduated', NULL, 'primary', 'badge bg-primary', 'text-white', 2, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (3, 1, 'TRANSFERRED', 'Transferred', NULL, 'warning', 'badge bg-warning', 'text-dark', 3, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (4, 1, 'DROPPED', 'Dropped', NULL, 'danger', 'badge bg-danger', 'text-white', 4, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (5, 1, 'SUSPENDED', 'Suspended', NULL, 'secondary', 'badge bg-secondary', 'text-white', 5, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (6, 2, 'NEW', 'New', NULL, 'primary', 'badge bg-primary', 'text-white', 1, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (7, 2, 'TRANSFER', 'Transfer', NULL, 'warning', 'badge bg-warning', 'text-dark', 2, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (8, 2, 'RETURNING', 'Returning', NULL, 'info', 'badge bg-info', 'text-dark', 3, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (9, 3, 'PRESENT', 'Present', NULL, 'success', 'badge bg-success', 'text-white', 1, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (10, 3, 'ABSENT', 'Absent', NULL, 'danger', 'badge bg-danger', 'text-white', 2, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (11, 3, 'SICK', 'Sick', NULL, 'warning', 'badge bg-warning', 'text-dark', 3, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (12, 3, 'PERMIT', 'Permit', NULL, 'info', 'badge bg-info', 'text-dark', 4, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (13, 4, 'FATHER', 'Father', NULL, 'primary', 'badge bg-primary', 'text-white', 1, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (14, 4, 'MOTHER', 'Mother', NULL, 'danger', 'badge bg-danger', 'text-white', 2, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (15, 4, 'GUARDIAN', 'Guardian', NULL, 'secondary', 'badge bg-secondary', 'text-white', 3, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (16, 5, 'PENDING', 'Pending', NULL, 'warning', 'badge bg-warning', 'text-dark', 1, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (17, 5, 'PROCESSING', 'Processing', NULL, 'info', 'badge bg-info', 'text-dark', 2, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (18, 5, 'SUCCESS', 'Success', NULL, 'success', 'badge bg-success', 'text-white', 3, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (19, 5, 'FAILED', 'Failed', NULL, 'danger', 'badge bg-danger', 'text-white', 4, 1, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `reference_values` VALUES (20, 5, 'CANCELLED', 'Cancelled', NULL, 'secondary', 'badge bg-secondary', 'text-white', 5, 1, 1, NULL, NULL, NULL, NULL, NULL);

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of religions
-- ----------------------------

-- ----------------------------
-- Table structure for report_card_templates
-- ----------------------------
DROP TABLE IF EXISTS `report_card_templates`;
CREATE TABLE `report_card_templates`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_data` json NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `version` int NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `report_card_templates_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `report_card_templates_school_index`(`school_id` ASC) USING BTREE,
  INDEX `report_card_templates_is_default_index`(`is_default` ASC) USING BTREE,
  INDEX `report_card_templates_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `report_card_templates_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `report_card_templates_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `report_card_templates_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `report_card_templates_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of report_card_templates
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_activities
-- ----------------------------
DROP TABLE IF EXISTS `rkas_activities`;
CREATE TABLE `rkas_activities`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `rkas_program_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `order_index` int NOT NULL DEFAULT 0,
  `total_budget` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_realization` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rkas_activities_program_code_unique`(`rkas_program_id` ASC, `code` ASC) USING BTREE,
  INDEX `rkas_activities_program_index`(`rkas_program_id` ASC) USING BTREE,
  INDEX `rkas_activities_order_index`(`order_index` ASC) USING BTREE,
  INDEX `rkas_activities_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_activities_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_activities_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `rkas_activities_rkas_program_id_foreign` FOREIGN KEY (`rkas_program_id`) REFERENCES `rkas_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_activities
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_approvals
-- ----------------------------
DROP TABLE IF EXISTS `rkas_approvals`;
CREATE TABLE `rkas_approvals`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `rkas_document_id` bigint UNSIGNED NOT NULL,
  `approval_level` int NOT NULL DEFAULT 1,
  `approval_role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `approver_id` bigint UNSIGNED NULL DEFAULT NULL,
  `approver_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `signature_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `rkas_approvals_document_index`(`rkas_document_id` ASC) USING BTREE,
  INDEX `rkas_approvals_level_index`(`approval_level` ASC) USING BTREE,
  INDEX `rkas_approvals_role_index`(`approval_role` ASC) USING BTREE,
  INDEX `rkas_approvals_approver_index`(`approver_id` ASC) USING BTREE,
  INDEX `rkas_approvals_status_index`(`status` ASC) USING BTREE,
  INDEX `rkas_approvals_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_approvals_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_approvals_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `rkas_approvals_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_approvals_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_approvals
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_budgets
-- ----------------------------
DROP TABLE IF EXISTS `rkas_budgets`;
CREATE TABLE `rkas_budgets`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `rkas_item_id` bigint UNSIGNED NOT NULL,
  `rkas_source_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5, 2) NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rkas_budgets_item_source_unique`(`rkas_item_id` ASC, `rkas_source_id` ASC) USING BTREE,
  INDEX `rkas_budgets_item_index`(`rkas_item_id` ASC) USING BTREE,
  INDEX `rkas_budgets_source_index`(`rkas_source_id` ASC) USING BTREE,
  INDEX `rkas_budgets_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_budgets_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_budgets_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `rkas_budgets_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_budgets_rkas_source_id_foreign` FOREIGN KEY (`rkas_source_id`) REFERENCES `rkas_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_budgets
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_components
-- ----------------------------
DROP TABLE IF EXISTS `rkas_components`;
CREATE TABLE `rkas_components`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `regulation_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `regulation_version` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `max_percentage` decimal(5, 2) NULL DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 0,
  `order_index` int NOT NULL DEFAULT 0,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rkas_components_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `rkas_components_school_index`(`school_id` ASC) USING BTREE,
  INDEX `rkas_components_type_index`(`component_type` ASC) USING BTREE,
  INDEX `rkas_components_account_index`(`account_code` ASC) USING BTREE,
  INDEX `rkas_components_coa_index`(`account_id` ASC) USING BTREE,
  INDEX `rkas_components_regulation_index`(`regulation_code` ASC, `regulation_version` ASC) USING BTREE,
  INDEX `rkas_components_order_index`(`order_index` ASC) USING BTREE,
  INDEX `rkas_components_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_components_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_components_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `rkas_components_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_components_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_components
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_documents
-- ----------------------------
DROP TABLE IF EXISTS `rkas_documents`;
CREATE TABLE `rkas_documents`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `fiscal_period_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` int NOT NULL DEFAULT 1,
  `revision_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INITIAL',
  `parent_document_id` bigint UNSIGNED NULL DEFAULT NULL,
  `total_budget` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_realization` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rkas_documents_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `rkas_documents_school_index`(`school_id` ASC) USING BTREE,
  INDEX `rkas_documents_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `rkas_documents_fiscal_period_index`(`fiscal_period_id` ASC) USING BTREE,
  INDEX `rkas_documents_revision_type_index`(`revision_type` ASC) USING BTREE,
  INDEX `rkas_documents_parent_index`(`parent_document_id` ASC) USING BTREE,
  INDEX `rkas_documents_status_index`(`status` ASC) USING BTREE,
  INDEX `rkas_documents_approved_by_index`(`approved_by` ASC) USING BTREE,
  INDEX `rkas_documents_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_documents_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_documents_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `rkas_documents_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_documents_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_documents_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_documents_parent_document_id_foreign` FOREIGN KEY (`parent_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_documents_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_documents
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_items
-- ----------------------------
DROP TABLE IF EXISTS `rkas_items`;
CREATE TABLE `rkas_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `rkas_sub_activity_id` bigint UNSIGNED NOT NULL,
  `rkas_component_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specification` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `volume` decimal(20, 4) NOT NULL DEFAULT 0.0000,
  `unit_price` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `shipping_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `admin_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `execution_month` tinyint UNSIGNED NULL DEFAULT NULL,
  `execution_quarter` tinyint UNSIGNED NULL DEFAULT NULL,
  `order_index` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rkas_items_sub_activity_code_unique`(`rkas_sub_activity_id` ASC, `code` ASC) USING BTREE,
  INDEX `rkas_items_sub_activity_index`(`rkas_sub_activity_id` ASC) USING BTREE,
  INDEX `rkas_items_component_index`(`rkas_component_id` ASC) USING BTREE,
  INDEX `rkas_items_month_index`(`execution_month` ASC) USING BTREE,
  INDEX `rkas_items_quarter_index`(`execution_quarter` ASC) USING BTREE,
  INDEX `rkas_items_order_index`(`order_index` ASC) USING BTREE,
  INDEX `rkas_items_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_items_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_items_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `rkas_items_rkas_component_id_foreign` FOREIGN KEY (`rkas_component_id`) REFERENCES `rkas_components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_items_rkas_sub_activity_id_foreign` FOREIGN KEY (`rkas_sub_activity_id`) REFERENCES `rkas_sub_activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_items
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_programs
-- ----------------------------
DROP TABLE IF EXISTS `rkas_programs`;
CREATE TABLE `rkas_programs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `rkas_document_id` bigint UNSIGNED NOT NULL,
  `standard_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `order_index` int NOT NULL DEFAULT 0,
  `total_budget` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_realization` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rkas_programs_document_code_unique`(`rkas_document_id` ASC, `code` ASC) USING BTREE,
  INDEX `rkas_programs_document_index`(`rkas_document_id` ASC) USING BTREE,
  INDEX `rkas_programs_standard_index`(`standard_id` ASC) USING BTREE,
  INDEX `rkas_programs_order_index`(`order_index` ASC) USING BTREE,
  INDEX `rkas_programs_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_programs_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_programs_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `rkas_programs_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_programs_standard_id_foreign` FOREIGN KEY (`standard_id`) REFERENCES `rkas_standards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_programs
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_realizations
-- ----------------------------
DROP TABLE IF EXISTS `rkas_realizations`;
CREATE TABLE `rkas_realizations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `rkas_item_id` bigint UNSIGNED NOT NULL,
  `rkas_source_id` bigint UNSIGNED NOT NULL,
  `fiscal_period_id` bigint UNSIGNED NOT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `realization_date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `vendor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `vendor_invoice_id` bigint UNSIGNED NULL DEFAULT NULL,
  `payment_reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'RECORDED',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `rkas_realizations_item_index`(`rkas_item_id` ASC) USING BTREE,
  INDEX `rkas_realizations_source_index`(`rkas_source_id` ASC) USING BTREE,
  INDEX `rkas_realizations_fiscal_period_index`(`fiscal_period_id` ASC) USING BTREE,
  INDEX `rkas_realizations_journal_entry_index`(`journal_entry_id` ASC) USING BTREE,
  INDEX `rkas_realizations_date_index`(`realization_date` ASC) USING BTREE,
  INDEX `rkas_realizations_vendor_index`(`vendor_id` ASC) USING BTREE,
  INDEX `rkas_realizations_vendor_invoice_index`(`vendor_invoice_id` ASC) USING BTREE,
  INDEX `rkas_realizations_status_index`(`status` ASC) USING BTREE,
  INDEX `rkas_realizations_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_realizations_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_realizations_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `rkas_realizations_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_realizations_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_realizations_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_realizations_rkas_source_id_foreign` FOREIGN KEY (`rkas_source_id`) REFERENCES `rkas_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_realizations_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_realizations_vendor_invoice_id_foreign` FOREIGN KEY (`vendor_invoice_id`) REFERENCES `vendor_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_realizations
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_report_items
-- ----------------------------
DROP TABLE IF EXISTS `rkas_report_items`;
CREATE TABLE `rkas_report_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `rkas_report_id` bigint UNSIGNED NOT NULL,
  `rkas_component_id` bigint UNSIGNED NULL DEFAULT NULL,
  `rkas_source_id` bigint UNSIGNED NULL DEFAULT NULL,
  `account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `budget_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `realization_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5, 2) NOT NULL DEFAULT 0.00,
  `item_data` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `rkas_report_items_report_index`(`rkas_report_id` ASC) USING BTREE,
  INDEX `rkas_report_items_component_index`(`rkas_component_id` ASC) USING BTREE,
  INDEX `rkas_report_items_source_index`(`rkas_source_id` ASC) USING BTREE,
  INDEX `rkas_report_items_coa_index`(`account_id` ASC) USING BTREE,
  INDEX `rkas_report_items_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_report_items_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_report_items_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `rkas_report_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_report_items_rkas_component_id_foreign` FOREIGN KEY (`rkas_component_id`) REFERENCES `rkas_components` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_report_items_rkas_report_id_foreign` FOREIGN KEY (`rkas_report_id`) REFERENCES `rkas_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_report_items_rkas_source_id_foreign` FOREIGN KEY (`rkas_source_id`) REFERENCES `rkas_sources` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_report_items
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_reports
-- ----------------------------
DROP TABLE IF EXISTS `rkas_reports`;
CREATE TABLE `rkas_reports`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `rkas_document_id` bigint UNSIGNED NOT NULL,
  `fiscal_period_id` bigint UNSIGNED NOT NULL,
  `report_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PERIODIC',
  `period_number` int NULL DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_budget` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_realization` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_remaining` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5, 2) NOT NULL DEFAULT 0.00,
  `file_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `report_data` json NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `submitted_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rkas_reports_school_document_code_period_unique`(`school_id` ASC, `rkas_document_id` ASC, `report_code` ASC, `period_number` ASC) USING BTREE,
  INDEX `rkas_reports_school_index`(`school_id` ASC) USING BTREE,
  INDEX `rkas_reports_document_index`(`rkas_document_id` ASC) USING BTREE,
  INDEX `rkas_reports_fiscal_period_index`(`fiscal_period_id` ASC) USING BTREE,
  INDEX `rkas_reports_code_index`(`report_code` ASC) USING BTREE,
  INDEX `rkas_reports_type_index`(`report_type` ASC) USING BTREE,
  INDEX `rkas_reports_status_index`(`status` ASC) USING BTREE,
  INDEX `rkas_reports_submitted_by_index`(`submitted_by` ASC) USING BTREE,
  INDEX `rkas_reports_verified_by_index`(`verified_by` ASC) USING BTREE,
  INDEX `rkas_reports_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_reports_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_reports_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `rkas_reports_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_reports_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_reports_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rkas_reports_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_reports_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_reports
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_revisions
-- ----------------------------
DROP TABLE IF EXISTS `rkas_revisions`;
CREATE TABLE `rkas_revisions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `rkas_document_id` bigint UNSIGNED NOT NULL,
  `parent_revision_id` bigint UNSIGNED NULL DEFAULT NULL,
  `revision_number` int NOT NULL DEFAULT 1,
  `revision_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `revision_date` date NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `total_before` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_after` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `difference` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `changes` json NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approval_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rkas_revisions_document_number_unique`(`rkas_document_id` ASC, `revision_number` ASC) USING BTREE,
  INDEX `rkas_revisions_document_index`(`rkas_document_id` ASC) USING BTREE,
  INDEX `rkas_revisions_parent_index`(`parent_revision_id` ASC) USING BTREE,
  INDEX `rkas_revisions_type_index`(`revision_type` ASC) USING BTREE,
  INDEX `rkas_revisions_date_index`(`revision_date` ASC) USING BTREE,
  INDEX `rkas_revisions_status_index`(`status` ASC) USING BTREE,
  INDEX `rkas_revisions_approved_by_index`(`approved_by` ASC) USING BTREE,
  INDEX `rkas_revisions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_revisions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_revisions_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `rkas_revisions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_revisions_parent_revision_id_foreign` FOREIGN KEY (`parent_revision_id`) REFERENCES `rkas_revisions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_revisions_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_revisions
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_sources
-- ----------------------------
DROP TABLE IF EXISTS `rkas_sources`;
CREATE TABLE `rkas_sources`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `authority` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `allocation_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `regulation_version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rkas_sources_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `rkas_sources_school_index`(`school_id` ASC) USING BTREE,
  INDEX `rkas_sources_type_index`(`source_type` ASC) USING BTREE,
  INDEX `rkas_sources_authority_index`(`authority` ASC) USING BTREE,
  INDEX `rkas_sources_coa_index`(`account_id` ASC) USING BTREE,
  INDEX `rkas_sources_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_sources_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_sources_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `rkas_sources_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `rkas_sources_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_sources
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_standards
-- ----------------------------
DROP TABLE IF EXISTS `rkas_standards`;
CREATE TABLE `rkas_standards`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_index` int NOT NULL DEFAULT 0,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rkas_standards_code_unique`(`code` ASC) USING BTREE,
  INDEX `rkas_standards_order_index`(`order_index` ASC) USING BTREE,
  INDEX `rkas_standards_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_standards_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_standards_updated_by_index`(`updated_by` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_standards
-- ----------------------------

-- ----------------------------
-- Table structure for rkas_sub_activities
-- ----------------------------
DROP TABLE IF EXISTS `rkas_sub_activities`;
CREATE TABLE `rkas_sub_activities`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `rkas_activity_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `order_index` int NOT NULL DEFAULT 0,
  `total_budget` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_realization` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `rkas_sub_activities_activity_code_unique`(`rkas_activity_id` ASC, `code` ASC) USING BTREE,
  INDEX `rkas_sub_activities_activity_index`(`rkas_activity_id` ASC) USING BTREE,
  INDEX `rkas_sub_activities_order_index`(`order_index` ASC) USING BTREE,
  INDEX `rkas_sub_activities_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `rkas_sub_activities_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `rkas_sub_activities_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `rkas_sub_activities_rkas_activity_id_foreign` FOREIGN KEY (`rkas_activity_id`) REFERENCES `rkas_activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rkas_sub_activities
-- ----------------------------

-- ----------------------------
-- Table structure for role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `role_permissions_unique`(`role_id` ASC, `permission_id` ASC) USING BTREE,
  INDEX `role_permissions_role_index`(`role_id` ASC) USING BTREE,
  INDEX `role_permissions_permission_index`(`permission_id` ASC) USING BTREE,
  INDEX `role_permissions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of role_permissions
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
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of role_user
-- ----------------------------
INSERT INTO `role_user` VALUES (7, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 1, 'super-admin', 'Super Administrator', 'Memiliki akses penuh ke seluruh sistem', 1, 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `roles` VALUES (2, 1, 'admin', 'Administrator', 'Mengelola konten dan pengguna biasa', 1, 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);
INSERT INTO `roles` VALUES (3, 1, 'user', 'Pengguna', 'Pengguna biasa', 1, 1, '2026-09-09 17:07:41', '2026-09-09 17:07:41', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for room_constraints
-- ----------------------------
DROP TABLE IF EXISTS `room_constraints`;
CREATE TABLE `room_constraints`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `room_id` bigint UNSIGNED NOT NULL,
  `day_of_week` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `time_slot_start` time NULL DEFAULT NULL,
  `time_slot_end` time NULL DEFAULT NULL,
  `constraint_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `room_constraints_room_index`(`room_id` ASC) USING BTREE,
  INDEX `room_constraints_day_index`(`day_of_week` ASC) USING BTREE,
  INDEX `room_constraints_type_index`(`constraint_type` ASC) USING BTREE,
  INDEX `room_constraints_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `room_constraints_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `room_constraints_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `room_constraints_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `timetable_rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of room_constraints
-- ----------------------------

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
  `generation_id` bigint UNSIGNED NULL DEFAULT NULL,
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
  INDEX `schedules_room_index`(`room_id` ASC) USING BTREE,
  INDEX `schedules_generation_index`(`generation_id` ASC) USING BTREE,
  CONSTRAINT `schedules_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `schedules_classroom_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `schedules_generation_id_foreign` FOREIGN KEY (`generation_id`) REFERENCES `timetable_generations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `schedules_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `timetable_rooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `schedules_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `schedules_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('0RlRyLc28PLq28vmbxaQ8V0zk0rlImptnF7PHnT2', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YToxNDp7czo2OiJfdG9rZW4iO3M6NDA6ImJhanNBT3JwRDdLVFFrdWFDZVRlMGsxUFp1dFNGdmlxWmRxeGRkdTkiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvY3J1ZC1nZW5lcmF0b3IiO3M6NToicm91dGUiO3M6MjA6ImNydWRfZ2VuZXJhdG9yLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6InVzZXJfYXV0aCI7YTo3OntzOjg6ImxvZ2luX2FzIjtzOjExOiJzdXBlci1hZG1pbiI7czoxNDoiYWN0aXZlX3JvbGVfaWQiO2k6MTtzOjEzOiJhdXRoX3JvbGVfaWRzIjtPOjI5OiJJbGx1bWluYXRlXFN1cHBvcnRcQ29sbGVjdGlvbiI6Mjp7czo4OiIAKgBpdGVtcyI7YToxOntpOjA7aToxO31zOjI4OiIAKgBlc2NhcGVXaGVuQ2FzdGluZ1RvU3RyaW5nIjtiOjA7fXM6MTA6ImF1dGhfcm9sZXMiO086Mjk6IklsbHVtaW5hdGVcU3VwcG9ydFxDb2xsZWN0aW9uIjoyOntzOjg6IgAqAGl0ZW1zIjthOjE6e2k6MDtzOjExOiJzdXBlci1hZG1pbiI7fXM6Mjg6IgAqAGVzY2FwZVdoZW5DYXN0aW5nVG9TdHJpbmciO2I6MDt9czo5OiJhdXRoX3JvbGUiO086MTE6Ik1vZGVsc1xSb2xlIjozNjp7czoxMzoiACoAY29ubmVjdGlvbiI7czo1OiJteXNxbCI7czo4OiIAKgB0YWJsZSI7czo1OiJyb2xlcyI7czoxMzoiACoAcHJpbWFyeUtleSI7czoyOiJpZCI7czoxMDoiACoAa2V5VHlwZSI7czozOiJpbnQiO3M6MTI6ImluY3JlbWVudGluZyI7YjoxO3M6NzoiACoAd2l0aCI7YTowOnt9czoxMjoiACoAd2l0aENvdW50IjthOjA6e31zOjE5OiJwcmV2ZW50c0xhenlMb2FkaW5nIjtiOjA7czoxMDoiACoAcGVyUGFnZSI7aToxNTtzOjY6ImV4aXN0cyI7YjoxO3M6MTg6Indhc1JlY2VudGx5Q3JlYXRlZCI7YjowO3M6Mjg6IgAqAGVzY2FwZVdoZW5DYXN0aW5nVG9TdHJpbmciO2I6MDtzOjEzOiIAKgBhdHRyaWJ1dGVzIjthOjEyOntzOjI6ImlkIjtpOjE7czoxNToib3JnYW5pemF0aW9uX2lkIjtpOjE7czo0OiJuYW1lIjtzOjExOiJzdXBlci1hZG1pbiI7czoxMjoiZGlzcGxheV9uYW1lIjtzOjE5OiJTdXBlciBBZG1pbmlzdHJhdG9yIjtzOjExOiJkZXNjcmlwdGlvbiI7czozODoiTWVtaWxpa2kgYWtzZXMgcGVudWgga2Ugc2VsdXJ1aCBzaXN0ZW0iO3M6OToiaXNfc3lzdGVtIjtpOjE7czo5OiJpc19hY3RpdmUiO2k6MTtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDI2LTA5LTA5IDE3OjA3OjQxIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDI2LTA5LTA5IDE3OjA3OjQxIjtzOjEwOiJkZWxldGVkX2F0IjtOO3M6MTA6ImNyZWF0ZWRfYnkiO047czoxMDoidXBkYXRlZF9ieSI7Tjt9czoxMToiACoAb3JpZ2luYWwiO2E6MTc6e3M6MjoiaWQiO2k6MTtzOjE1OiJvcmdhbml6YXRpb25faWQiO2k6MTtzOjQ6Im5hbWUiO3M6MTE6InN1cGVyLWFkbWluIjtzOjEyOiJkaXNwbGF5X25hbWUiO3M6MTk6IlN1cGVyIEFkbWluaXN0cmF0b3IiO3M6MTE6ImRlc2NyaXB0aW9uIjtzOjM4OiJNZW1pbGlraSBha3NlcyBwZW51aCBrZSBzZWx1cnVoIHNpc3RlbSI7czo5OiJpc19zeXN0ZW0iO2k6MTtzOjk6ImlzX2FjdGl2ZSI7aToxO3M6MTA6ImNyZWF0ZWRfYXQiO3M6MTk6IjIwMjYtMDktMDkgMTc6MDc6NDEiO3M6MTA6InVwZGF0ZWRfYXQiO3M6MTk6IjIwMjYtMDktMDkgMTc6MDc6NDEiO3M6MTA6ImRlbGV0ZWRfYXQiO047czoxMDoiY3JlYXRlZF9ieSI7TjtzOjEwOiJ1cGRhdGVkX2J5IjtOO3M6MTM6InBpdm90X3VzZXJfaWQiO2k6MTtzOjEzOiJwaXZvdF9yb2xlX2lkIjtpOjE7czoxNjoicGl2b3RfaXNfZGVmYXVsdCI7aToxO3M6MTY6InBpdm90X2NyZWF0ZWRfYXQiO047czoxNjoicGl2b3RfdXBkYXRlZF9hdCI7Tjt9czoxMDoiACoAY2hhbmdlcyI7YTowOnt9czoxMToiACoAcHJldmlvdXMiO2E6MDp7fXM6ODoiACoAY2FzdHMiO2E6Mzp7czoxMDoiY3JlYXRlZF9hdCI7czo4OiJkYXRldGltZSI7czoxMDoidXBkYXRlZF9hdCI7czo4OiJkYXRldGltZSI7czoxMDoiZGVsZXRlZF9hdCI7czo4OiJkYXRldGltZSI7fXM6MTc6IgAqAGNsYXNzQ2FzdENhY2hlIjthOjA6e31zOjIxOiIAKgBhdHRyaWJ1dGVDYXN0Q2FjaGUiO2E6MDp7fXM6MTM6IgAqAGRhdGVGb3JtYXQiO047czoxMDoiACoAYXBwZW5kcyI7YTowOnt9czoxOToiACoAZGlzcGF0Y2hlc0V2ZW50cyI7YTowOnt9czoxNDoiACoAb2JzZXJ2YWJsZXMiO2E6MDp7fXM6MTI6IgAqAHJlbGF0aW9ucyI7YToxOntzOjU6InBpdm90IjtPOjQ0OiJJbGx1bWluYXRlXERhdGFiYXNlXEVsb3F1ZW50XFJlbGF0aW9uc1xQaXZvdCI6Mzc6e3M6MTM6IgAqAGNvbm5lY3Rpb24iO3M6NToibXlzcWwiO3M6ODoiACoAdGFibGUiO3M6OToicm9sZV91c2VyIjtzOjEzOiIAKgBwcmltYXJ5S2V5IjtzOjI6ImlkIjtzOjEwOiIAKgBrZXlUeXBlIjtzOjM6ImludCI7czoxMjoiaW5jcmVtZW50aW5nIjtiOjA7czo3OiIAKgB3aXRoIjthOjA6e31zOjEyOiIAKgB3aXRoQ291bnQiO2E6MDp7fXM6MTk6InByZXZlbnRzTGF6eUxvYWRpbmciO2I6MDtzOjEwOiIAKgBwZXJQYWdlIjtpOjE1O3M6NjoiZXhpc3RzIjtiOjE7czoxODoid2FzUmVjZW50bHlDcmVhdGVkIjtiOjA7czoyODoiACoAZXNjYXBlV2hlbkNhc3RpbmdUb1N0cmluZyI7YjowO3M6MTM6IgAqAGF0dHJpYnV0ZXMiO2E6NTp7czo3OiJ1c2VyX2lkIjtpOjE7czo3OiJyb2xlX2lkIjtpOjE7czoxMDoiaXNfZGVmYXVsdCI7aToxO3M6MTA6ImNyZWF0ZWRfYXQiO047czoxMDoidXBkYXRlZF9hdCI7Tjt9czoxMToiACoAb3JpZ2luYWwiO2E6NTp7czo3OiJ1c2VyX2lkIjtpOjE7czo3OiJyb2xlX2lkIjtpOjE7czoxMDoiaXNfZGVmYXVsdCI7aToxO3M6MTA6ImNyZWF0ZWRfYXQiO047czoxMDoidXBkYXRlZF9hdCI7Tjt9czoxMDoiACoAY2hhbmdlcyI7YTowOnt9czoxMToiACoAcHJldmlvdXMiO2E6MDp7fXM6ODoiACoAY2FzdHMiO2E6MDp7fXM6MTc6IgAqAGNsYXNzQ2FzdENhY2hlIjthOjA6e31zOjIxOiIAKgBhdHRyaWJ1dGVDYXN0Q2FjaGUiO2E6MDp7fXM6MTM6IgAqAGRhdGVGb3JtYXQiO047czoxMDoiACoAYXBwZW5kcyI7YTowOnt9czoxOToiACoAZGlzcGF0Y2hlc0V2ZW50cyI7YTowOnt9czoxNDoiACoAb2JzZXJ2YWJsZXMiO2E6MDp7fXM6MTI6IgAqAHJlbGF0aW9ucyI7YTowOnt9czoxMDoiACoAdG91Y2hlcyI7YTowOnt9czoyNzoiACoAcmVsYXRpb25BdXRvbG9hZENhbGxiYWNrIjtOO3M6MjY6IgAqAHJlbGF0aW9uQXV0b2xvYWRDb250ZXh0IjtOO3M6MTA6InRpbWVzdGFtcHMiO2I6MTtzOjEzOiJ1c2VzVW5pcXVlSWRzIjtiOjA7czo5OiIAKgBoaWRkZW4iO2E6MDp7fXM6MTA6IgAqAHZpc2libGUiO2E6MDp7fXM6MTE6IgAqAGZpbGxhYmxlIjthOjA6e31zOjEwOiIAKgBndWFyZGVkIjthOjA6e31zOjExOiJwaXZvdFBhcmVudCI7TzoxNToiQXBwXE1vZGVsc1xVc2VyIjozNzp7czoxMzoiACoAY29ubmVjdGlvbiI7czo1OiJteXNxbCI7czo4OiIAKgB0YWJsZSI7czo1OiJ1c2VycyI7czoxMzoiACoAcHJpbWFyeUtleSI7czoyOiJpZCI7czoxMDoiACoAa2V5VHlwZSI7czozOiJpbnQiO3M6MTI6ImluY3JlbWVudGluZyI7YjoxO3M6NzoiACoAd2l0aCI7YTowOnt9czoxMjoiACoAd2l0aENvdW50IjthOjA6e31zOjE5OiJwcmV2ZW50c0xhenlMb2FkaW5nIjtiOjA7czoxMDoiACoAcGVyUGFnZSI7aToxNTtzOjY6ImV4aXN0cyI7YjoxO3M6MTg6Indhc1JlY2VudGx5Q3JlYXRlZCI7YjowO3M6Mjg6IgAqAGVzY2FwZVdoZW5DYXN0aW5nVG9TdHJpbmciO2I6MDtzOjEzOiIAKgBhdHRyaWJ1dGVzIjthOjE2OntzOjI6ImlkIjtpOjE7czo0OiJuYW1lIjtzOjE5OiJBZG1pbmlzdHJhdG9yIFV0YW1hIjtzOjU6ImVtYWlsIjtzOjE1OiJhZG1pbkBjb250b2guaWQiO3M6MTc6ImVtYWlsX3ZlcmlmaWVkX2F0IjtzOjE5OiIyMDI2LTA5LTA5IDE3OjA4OjIzIjtzOjg6InBhc3N3b3JkIjtzOjYwOiIkMnkkMTIkQXBaWllzdUVDcVNBeDJkbE9Fd2xMLmJLY0tDYUQuaWxLbzNrTEo4NTBvdHNYY250cjdYMFMiO3M6MTQ6InJlbWVtYmVyX3Rva2VuIjtOO3M6MTc6InR3b19mYWN0b3Jfc2VjcmV0IjtOO3M6MjU6InR3b19mYWN0b3JfcmVjb3ZlcnlfY29kZXMiO047czoyMzoidHdvX2ZhY3Rvcl9jb25maXJtZWRfYXQiO047czoxMzoibGFzdF9sb2dpbl9hdCI7TjtzOjEzOiJsYXN0X2xvZ2luX2lwIjtOO3M6MTA6ImNyZWF0ZWRfYXQiO3M6MTk6IjIwMjYtMDktMDkgMTc6MDg6MjMiO3M6MTA6InVwZGF0ZWRfYXQiO3M6MTk6IjIwMjYtMDktMDkgMTc6MDg6MjMiO3M6MTA6ImRlbGV0ZWRfYXQiO047czoxMDoiY3JlYXRlZF9ieSI7TjtzOjEwOiJ1cGRhdGVkX2J5IjtOO31zOjExOiIAKgBvcmlnaW5hbCI7YToxNjp7czoyOiJpZCI7aToxO3M6NDoibmFtZSI7czoxOToiQWRtaW5pc3RyYXRvciBVdGFtYSI7czo1OiJlbWFpbCI7czoxNToiYWRtaW5AY29udG9oLmlkIjtzOjE3OiJlbWFpbF92ZXJpZmllZF9hdCI7czoxOToiMjAyNi0wOS0wOSAxNzowODoyMyI7czo4OiJwYXNzd29yZCI7czo2MDoiJDJ5JDEyJEFwWlpZc3VFQ3FTQXgyZGxPRXdsTC5iS2NLQ2FELmlsS28za0xKODUwb3RzWGNudHI3WDBTIjtzOjE0OiJyZW1lbWJlcl90b2tlbiI7TjtzOjE3OiJ0d29fZmFjdG9yX3NlY3JldCI7TjtzOjI1OiJ0d29fZmFjdG9yX3JlY292ZXJ5X2NvZGVzIjtOO3M6MjM6InR3b19mYWN0b3JfY29uZmlybWVkX2F0IjtOO3M6MTM6Imxhc3RfbG9naW5fYXQiO047czoxMzoibGFzdF9sb2dpbl9pcCI7TjtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDI2LTA5LTA5IDE3OjA4OjIzIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDI2LTA5LTA5IDE3OjA4OjIzIjtzOjEwOiJkZWxldGVkX2F0IjtOO3M6MTA6ImNyZWF0ZWRfYnkiO047czoxMDoidXBkYXRlZF9ieSI7Tjt9czoxMDoiACoAY2hhbmdlcyI7YTowOnt9czoxMToiACoAcHJldmlvdXMiO2E6MDp7fXM6ODoiACoAY2FzdHMiO2E6Mzp7czoxNzoiZW1haWxfdmVyaWZpZWRfYXQiO3M6ODoiZGF0ZXRpbWUiO3M6ODoicGFzc3dvcmQiO3M6NjoiaGFzaGVkIjtzOjEwOiJkZWxldGVkX2F0IjtzOjg6ImRhdGV0aW1lIjt9czoxNzoiACoAY2xhc3NDYXN0Q2FjaGUiO2E6MDp7fXM6MjE6IgAqAGF0dHJpYnV0ZUNhc3RDYWNoZSI7YTowOnt9czoxMzoiACoAZGF0ZUZvcm1hdCI7TjtzOjEwOiIAKgBhcHBlbmRzIjthOjA6e31zOjE5OiIAKgBkaXNwYXRjaGVzRXZlbnRzIjthOjA6e31zOjE0OiIAKgBvYnNlcnZhYmxlcyI7YTowOnt9czoxMjoiACoAcmVsYXRpb25zIjthOjE6e3M6NToicm9sZXMiO086Mzk6IklsbHVtaW5hdGVcRGF0YWJhc2VcRWxvcXVlbnRcQ29sbGVjdGlvbiI6Mjp7czo4OiIAKgBpdGVtcyI7YToxOntpOjA7cjoyMTt9czoyODoiACoAZXNjYXBlV2hlbkNhc3RpbmdUb1N0cmluZyI7YjowO319czoxMDoiACoAdG91Y2hlcyI7YTowOnt9czoyNzoiACoAcmVsYXRpb25BdXRvbG9hZENhbGxiYWNrIjtOO3M6MjY6IgAqAHJlbGF0aW9uQXV0b2xvYWRDb250ZXh0IjtOO3M6MTA6InRpbWVzdGFtcHMiO2I6MTtzOjEzOiJ1c2VzVW5pcXVlSWRzIjtiOjA7czo5OiIAKgBoaWRkZW4iO2E6Mjp7aTowO3M6ODoicGFzc3dvcmQiO2k6MTtzOjE0OiJyZW1lbWJlcl90b2tlbiI7fXM6MTA6IgAqAHZpc2libGUiO2E6MDp7fXM6MTE6IgAqAGZpbGxhYmxlIjthOjA6e31zOjEwOiIAKgBndWFyZGVkIjthOjE6e2k6MDtzOjI6ImlkIjt9czoxOToiACoAYXV0aFBhc3N3b3JkTmFtZSI7czo4OiJwYXNzd29yZCI7czoyMDoiACoAcmVtZW1iZXJUb2tlbk5hbWUiO3M6MTQ6InJlbWVtYmVyX3Rva2VuIjtzOjE0OiIAKgBhY2Nlc3NUb2tlbiI7TjtzOjE2OiIAKgBmb3JjZURlbGV0aW5nIjtiOjA7fXM6MTI6InBpdm90UmVsYXRlZCI7TzoxMToiTW9kZWxzXFJvbGUiOjM2OntzOjEzOiIAKgBjb25uZWN0aW9uIjtzOjU6Im15c3FsIjtzOjg6IgAqAHRhYmxlIjtOO3M6MTM6IgAqAHByaW1hcnlLZXkiO3M6MjoiaWQiO3M6MTA6IgAqAGtleVR5cGUiO3M6MzoiaW50IjtzOjEyOiJpbmNyZW1lbnRpbmciO2I6MTtzOjc6IgAqAHdpdGgiO2E6MDp7fXM6MTI6IgAqAHdpdGhDb3VudCI7YTowOnt9czoxOToicHJldmVudHNMYXp5TG9hZGluZyI7YjowO3M6MTA6IgAqAHBlclBhZ2UiO2k6MTU7czo2OiJleGlzdHMiO2I6MDtzOjE4OiJ3YXNSZWNlbnRseUNyZWF0ZWQiO2I6MDtzOjI4OiIAKgBlc2NhcGVXaGVuQ2FzdGluZ1RvU3RyaW5nIjtiOjA7czoxMzoiACoAYXR0cmlidXRlcyI7YTowOnt9czoxMToiACoAb3JpZ2luYWwiO2E6MDp7fXM6MTA6IgAqAGNoYW5nZXMiO2E6MDp7fXM6MTE6IgAqAHByZXZpb3VzIjthOjA6e31zOjg6IgAqAGNhc3RzIjthOjM6e3M6MTA6ImNyZWF0ZWRfYXQiO3M6ODoiZGF0ZXRpbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6ODoiZGF0ZXRpbWUiO3M6MTA6ImRlbGV0ZWRfYXQiO3M6ODoiZGF0ZXRpbWUiO31zOjE3OiIAKgBjbGFzc0Nhc3RDYWNoZSI7YTowOnt9czoyMToiACoAYXR0cmlidXRlQ2FzdENhY2hlIjthOjA6e31zOjEzOiIAKgBkYXRlRm9ybWF0IjtOO3M6MTA6IgAqAGFwcGVuZHMiO2E6MDp7fXM6MTk6IgAqAGRpc3BhdGNoZXNFdmVudHMiO2E6MDp7fXM6MTQ6IgAqAG9ic2VydmFibGVzIjthOjA6e31zOjEyOiIAKgByZWxhdGlvbnMiO2E6MDp7fXM6MTA6IgAqAHRvdWNoZXMiO2E6MDp7fXM6Mjc6IgAqAHJlbGF0aW9uQXV0b2xvYWRDYWxsYmFjayI7TjtzOjI2OiIAKgByZWxhdGlvbkF1dG9sb2FkQ29udGV4dCI7TjtzOjEwOiJ0aW1lc3RhbXBzIjtiOjE7czoxMzoidXNlc1VuaXF1ZUlkcyI7YjowO3M6OToiACoAaGlkZGVuIjthOjM6e2k6MDtzOjEwOiJjcmVhdGVkX2F0IjtpOjE7czoxMDoidXBkYXRlZF9hdCI7aToyO3M6MTA6ImRlbGV0ZWRfYXQiO31zOjEwOiIAKgB2aXNpYmxlIjthOjA6e31zOjExOiIAKgBmaWxsYWJsZSI7YTowOnt9czoxMDoiACoAZ3VhcmRlZCI7YToxOntpOjA7czoyOiJpZCI7fXM6MTM6IgAqAGV4Y2VwdGlvbnMiO2E6OTp7aTowO3M6NzoiX21ldGhvZCI7aToxO3M6NjoiX3Rva2VuIjtpOjI7czoyOiJpZCI7aTozO3M6MjE6InBhc3N3b3JkX2NvbmZpcm1hdGlvbiI7aTo0O3M6NToiaW1hZ2UiO2k6NTtzOjU6InBob3RvIjtpOjY7czo0OiJmaWxlIjtpOjc7czo0OiJsb2dvIjtpOjg7czo1OiJ0aHVtYiI7fXM6MTA6IgAqAGlnbm9yZWQiO2E6MDp7fXM6MTY6IgAqAGZvcmNlRGVsZXRpbmciO2I6MDt9czoxMzoiACoAZm9yZWlnbktleSI7czo3OiJ1c2VyX2lkIjtzOjEzOiIAKgByZWxhdGVkS2V5IjtzOjc6InJvbGVfaWQiO319czoxMDoiACoAdG91Y2hlcyI7YTowOnt9czoyNzoiACoAcmVsYXRpb25BdXRvbG9hZENhbGxiYWNrIjtOO3M6MjY6IgAqAHJlbGF0aW9uQXV0b2xvYWRDb250ZXh0IjtOO3M6MTA6InRpbWVzdGFtcHMiO2I6MTtzOjEzOiJ1c2VzVW5pcXVlSWRzIjtiOjA7czo5OiIAKgBoaWRkZW4iO2E6Mzp7aTowO3M6MTA6ImNyZWF0ZWRfYXQiO2k6MTtzOjEwOiJ1cGRhdGVkX2F0IjtpOjI7czoxMDoiZGVsZXRlZF9hdCI7fXM6MTA6IgAqAHZpc2libGUiO2E6MDp7fXM6MTE6IgAqAGZpbGxhYmxlIjthOjA6e31zOjEwOiIAKgBndWFyZGVkIjthOjE6e2k6MDtzOjI6ImlkIjt9czoxMzoiACoAZXhjZXB0aW9ucyI7YTo5OntpOjA7czo3OiJfbWV0aG9kIjtpOjE7czo2OiJfdG9rZW4iO2k6MjtzOjI6ImlkIjtpOjM7czoyMToicGFzc3dvcmRfY29uZmlybWF0aW9uIjtpOjQ7czo1OiJpbWFnZSI7aTo1O3M6NToicGhvdG8iO2k6NjtzOjQ6ImZpbGUiO2k6NztzOjQ6ImxvZ28iO2k6ODtzOjU6InRodW1iIjt9czoxMDoiACoAaWdub3JlZCI7YTowOnt9czoxNjoiACoAZm9yY2VEZWxldGluZyI7YjowO31zOjY6ImxheW91dCI7TjtzOjExOiJvcmlnaW5fcm9sZSI7cjoyMTt9czo4OiJyb2xlX2lkcyI7TzoyOToiSWxsdW1pbmF0ZVxTdXBwb3J0XENvbGxlY3Rpb24iOjI6e3M6ODoiACoAaXRlbXMiO2E6MTp7aTowO2k6MTt9czoyODoiACoAZXNjYXBlV2hlbkNhc3RpbmdUb1N0cmluZyI7YjowO31zOjMyOiJjcnVkX3Byb2Nlc3NfY3J1ZF82YWFhZDQ1NjY2ZTI0MSI7YToxOntpOjk3MTA3NjEzO2E6Mzp7czo3OiJjb21tYW5kIjtzOjMzODoibWFrZTpjcnVkLXlhanJhIEFwcGxpY2FudCAtLWNvbHVtbnM9aWQsc2Nob29sX2lkLGFkbWlzc2lvbl9pZCxmdWxsX25hbWUsbmlrLGJpcnRoX3BsYWNlLGJpcnRoX2RhdGUsZ2VuZGVyLGFkZHJlc3MscGhvbmUsZW1haWwscGFyZW50X25hbWUscGFyZW50X3Bob25lLHBhcmVudF9lbWFpbCxwcmV2aW91c19zY2hvb2wscmVnaXN0cmF0aW9uX251bWJlcixzdGF0dXMsdGVzdF9zY29yZSxpbnRlcnZpZXdfc2NvcmUsdG90YWxfc2NvcmUscmFua2luZyxub3RlcyxyZWdpc3RlcmVkX2F0LGlzX2FjdGl2ZSxjcmVhdGVkX2F0LHVwZGF0ZWRfYXQsZGVsZXRlZF9hdCxjcmVhdGVkX2J5LHVwZGF0ZWRfYnkiO3M6Njoic3RhdHVzIjtzOjc6InJ1bm5pbmciO3M6MTA6InN0YXJ0ZWRfYXQiO086MjU6IklsbHVtaW5hdGVcU3VwcG9ydFxDYXJib24iOjM6e3M6NDoiZGF0ZSI7czoyNjoiMjAyNi0wOS0xNiAxNzozOTozNC40MjE0NzAiO3M6MTM6InRpbWV6b25lX3R5cGUiO2k6MztzOjg6InRpbWV6b25lIjtzOjM6IlVUQyI7fX19czozMjoiY3J1ZF9wcm9jZXNzX2NydWRfNmFhYWQ1MzAzNTMzNTkiO2E6MTp7aToxNTUyMzE3MjthOjM6e3M6NzoiY29tbWFuZCI7czoyMzI6Im1ha2U6Y3J1ZC15YWpyYSBBZG1pc3Npb24gLS1jb2x1bW5zPWlkLHNjaG9vbF9pZCxhY2FkZW1pY195ZWFyX2lkLGNsYXNzX2xldmVsX2lkLG5hbWUsY29kZSx0eXBlLHN0YXJ0X2RhdGUsZW5kX2RhdGUscXVvdGEscmVnaXN0cmF0aW9uX2ZlZSxtaW5fYWdlLG1heF9hZ2UscmVxdWlyZW1lbnRzLGlzX2FjdGl2ZSxjcmVhdGVkX2F0LHVwZGF0ZWRfYXQsZGVsZXRlZF9hdCxjcmVhdGVkX2J5LHVwZGF0ZWRfYnkiO3M6Njoic3RhdHVzIjtzOjc6InJ1bm5pbmciO3M6MTA6InN0YXJ0ZWRfYXQiO086MjU6IklsbHVtaW5hdGVcU3VwcG9ydFxDYXJib24iOjM6e3M6NDoiZGF0ZSI7czoyNjoiMjAyNi0wOS0xNiAxNzo0MzoxMi4yMTc5NzIiO3M6MTM6InRpbWV6b25lX3R5cGUiO2k6MztzOjg6InRpbWV6b25lIjtzOjM6IlVUQyI7fX19czozMjoiY3J1ZF9wcm9jZXNzX2NydWRfNmFhYWQ1Nzc5Mjk5YzQiO2E6MTp7aTo5MDgxOTk5NDthOjM6e3M6NzoiY29tbWFuZCI7czoyMDE6Im1ha2U6Y3J1ZC15YWpyYSBBZG1pc3Npb25TZWxlY3Rpb24gLS1jb2x1bW5zPWlkLGFkbWlzc2lvbl9pZCxhcHBsaWNhbnRfaWQsc2VsZWN0aW9uX3R5cGUsc2NvcmUscmVzdWx0LGV2YWx1YXRvcl9pZCxldmFsdWF0aW9uX2RhdGUsbm90ZXMsaXNfYWN0aXZlLGNyZWF0ZWRfYXQsdXBkYXRlZF9hdCxkZWxldGVkX2F0LGNyZWF0ZWRfYnksdXBkYXRlZF9ieSI7czo2OiJzdGF0dXMiO3M6NzoicnVubmluZyI7czoxMDoic3RhcnRlZF9hdCI7TzoyNToiSWxsdW1pbmF0ZVxTdXBwb3J0XENhcmJvbiI6Mzp7czo0OiJkYXRlIjtzOjI2OiIyMDI2LTA5LTE2IDE3OjQ0OjIzLjYwMDU1MCI7czoxMzoidGltZXpvbmVfdHlwZSI7aTozO3M6ODoidGltZXpvbmUiO3M6MzoiVVRDIjt9fX1zOjMyOiJjcnVkX3Byb2Nlc3NfY3J1ZF82YWFhZDVhNzEzMzhiMiI7YToxOntpOjc2ODYzNTUzO2E6Mzp7czo3OiJjb21tYW5kIjtzOjE4MDoibWFrZTpjcnVkLXlhanJhIEFkbWlzc2lvblRpbWVsaW5lIC0tY29sdW1ucz1pZCxhZG1pc3Npb25faWQscGhhc2VfbmFtZSxzdGFydF9kYXRlLGVuZF9kYXRlLGRlc2NyaXB0aW9uLG9yZGVyX251bWJlcixpc19hY3RpdmUsY3JlYXRlZF9hdCx1cGRhdGVkX2F0LGRlbGV0ZWRfYXQsY3JlYXRlZF9ieSx1cGRhdGVkX2J5IjtzOjY6InN0YXR1cyI7czo3OiJydW5uaW5nIjtzOjEwOiJzdGFydGVkX2F0IjtPOjI1OiJJbGx1bWluYXRlXFN1cHBvcnRcQ2FyYm9uIjozOntzOjQ6ImRhdGUiO3M6MjY6IjIwMjYtMDktMTYgMTc6NDU6MTEuMDc4Nzg5IjtzOjEzOiJ0aW1lem9uZV90eXBlIjtpOjM7czo4OiJ0aW1lem9uZSI7czozOiJVVEMiO319fXM6MzI6ImNydWRfcHJvY2Vzc19jcnVkXzZhYWFkNzE2MjgyMDg3IjthOjE6e2k6NTY4ODM3Nzk7YTozOntzOjc6ImNvbW1hbmQiO3M6MjI1OiJtYWtlOmNydWQteWFqcmEgQXBwbGljYXRpb25Eb2N1bWVudCAtLWNvbHVtbnM9aWQsYXBwbGljYW50X2lkLGRvY3VtZW50X3R5cGUsZG9jdW1lbnRfdXJsLGZpbGVfbmFtZSxmaWxlX3NpemUsdXBsb2FkZWRfYXQsdmVyaWZpZWQsdmVyaWZpZWRfYnksdmVyaWZpY2F0aW9uX25vdGVzLGlzX2FjdGl2ZSxjcmVhdGVkX2F0LHVwZGF0ZWRfYXQsZGVsZXRlZF9hdCxjcmVhdGVkX2J5LHVwZGF0ZWRfYnkiO3M6Njoic3RhdHVzIjtzOjc6InJ1bm5pbmciO3M6MTA6InN0YXJ0ZWRfYXQiO086MjU6IklsbHVtaW5hdGVcU3VwcG9ydFxDYXJib24iOjM6e3M6NDoiZGF0ZSI7czoyNjoiMjAyNi0wOS0xNiAxNzo1MToxOC4xNjQ1NjMiO3M6MTM6InRpbWV6b25lX3R5cGUiO2k6MztzOjg6InRpbWV6b25lIjtzOjM6IlVUQyI7fX19czozMjoiY3J1ZF9wcm9jZXNzX2NydWRfNmFhYWQ4OTMzMzY1YTIiO2E6MTp7aTozNzI5MDMxMTthOjM6e3M6NzoiY29tbWFuZCI7czoxNTQ6Im1ha2U6Y3J1ZC15YWpyYSBDbGFzc0xldmVsIC0tY29sdW1ucz1pZCxvcmdhbml6YXRpb25faWQsY29kZSxuYW1lLGxldmVsX29yZGVyLGRlc2NyaXB0aW9uLGlzX2FjdGl2ZSxjcmVhdGVkX2F0LHVwZGF0ZWRfYXQsZGVsZXRlZF9hdCxjcmVhdGVkX2J5LHVwZGF0ZWRfYnkiO3M6Njoic3RhdHVzIjtzOjc6InJ1bm5pbmciO3M6MTA6InN0YXJ0ZWRfYXQiO086MjU6IklsbHVtaW5hdGVcU3VwcG9ydFxDYXJib24iOjM6e3M6NDoiZGF0ZSI7czoyNjoiMjAyNi0wOS0xNiAxNzo1NzozOS4yMTA1OTQiO3M6MTM6InRpbWV6b25lX3R5cGUiO2k6MztzOjg6InRpbWV6b25lIjtzOjM6IlVUQyI7fX19czozMjoiY3J1ZF9wcm9jZXNzX2NydWRfNmFhYWRjNjA4MmQ3NDUiO2E6MTp7aToxMDMxNTY3ODthOjM6e3M6NzoiY29tbWFuZCI7czozNzU6Im1ha2U6Y3J1ZC15YWpyYSBUZXN0VHlwZSAtLWNvbHVtbnM9aWQsY29kZSxuYW1lLG5hbWVfZW4sc2x1ZyxkZXNjcmlwdGlvbixjYXRlZ29yeSxwYXJlbnRfaWQsaWNvbixjb2xvcixiYWRnZV9jbGFzcyxkZWZhdWx0X2R1cmF0aW9uX21pbnV0ZXMsZGVmYXVsdF9wYXNzaW5nX3Njb3JlLGRlZmF1bHRfbWF4X3Njb3JlLGRlZmF1bHRfd2VpZ2h0LGRlZmF1bHRfbWV0aG9kLHJlcXVpcmVzX3Jvb20scmVxdWlyZXNfc3VwZXJ2aXNvcixyZXF1aXJlc19jYnQsc2NvcmluZ19jb21wb25lbnRzLHNldHRpbmdzLG9yZGVyLGlzX2FjdGl2ZSxpc19zeXN0ZW0sc2Nob29sX2lkLGNyZWF0ZWRfYnksdXBkYXRlZF9ieSxjcmVhdGVkX2F0LHVwZGF0ZWRfYXQsZGVsZXRlZF9hdCI7czo2OiJzdGF0dXMiO3M6NzoicnVubmluZyI7czoxMDoic3RhcnRlZF9hdCI7TzoyNToiSWxsdW1pbmF0ZVxTdXBwb3J0XENhcmJvbiI6Mzp7czo0OiJkYXRlIjtzOjI2OiIyMDI2LTA5LTE2IDE4OjEzOjUyLjUzNTk3NyI7czoxMzoidGltZXpvbmVfdHlwZSI7aTozO3M6ODoidGltZXpvbmUiO3M6MzoiVVRDIjt9fX1zOjMyOiJjcnVkX3Byb2Nlc3NfY3J1ZF82YWFhZGQwMjIzMTlkMyI7YToxOntpOjExNDE5MzM3O2E6Mzp7czo3OiJjb21tYW5kIjtzOjY0ODoibWFrZTpjcnVkLXlhanJhIFRlc3RTY2hlZHVsZSAtLWNvbHVtbnM9aWQsYWRtaXNzaW9uX2lkLGFkbWlzc2lvbl9zZWxlY3Rpb25faWQsYWNhZGVtaWNfeWVhcl9pZCxzY2hvb2xfaWQsY29kZSxuYW1lLGRlc2NyaXB0aW9uLHRlc3RfdHlwZSx0ZXN0X21ldGhvZCx0ZXN0X2RhdGUsc3RhcnRfdGltZSxlbmRfdGltZSxkdXJhdGlvbl9taW51dGVzLHJlZ2lzdHJhdGlvbl9zdGFydCxyZWdpc3RyYXRpb25fZW5kLHJvb21faWQsYnVpbGRpbmdfaWQsbG9jYXRpb25fZGV0YWlsLG9ubGluZV9tZWV0aW5nX3VybCxvbmxpbmVfbWVldGluZ19pZCxvbmxpbmVfcGFzc3dvcmQsY2J0X3Nlc3Npb25faWQsZXhhbV9wYWNrYWdlX2lkLHF1b3RhLGJvb2tlZF9jb3VudCxtaW5fcGFydGljaXBhbnRzLHN1cGVydmlzb3JfaWQsY29vcmRpbmF0b3JfaWQscHJvY3Rvcl9pZHMsYWxsb3dfc2VsZl9yZWdpc3RlcixhbGxvd19yZXNjaGVkdWxlLGxhdGVfdG9sZXJhbmNlX21pbixzdGF0dXMsaXNfYWN0aXZlLHBhc3Npbmdfc2NvcmUsbWF4X3Njb3JlLHNjb3JlX2lucHV0X2RlYWRsaW5lLHJlc3VsdF9hbm5vdW5jZW1lbnQsbm90ZXMsYXR0YWNobWVudHMsc2V0dGluZ3MsY3JlYXRlZF9ieSx1cGRhdGVkX2J5LGNyZWF0ZWRfYXQsdXBkYXRlZF9hdCxkZWxldGVkX2F0IjtzOjY6InN0YXR1cyI7czo3OiJydW5uaW5nIjtzOjEwOiJzdGFydGVkX2F0IjtPOjI1OiJJbGx1bWluYXRlXFN1cHBvcnRcQ2FyYm9uIjozOntzOjQ6ImRhdGUiO3M6MjY6IjIwMjYtMDktMTYgMTg6MTY6MzQuMTQzODcyIjtzOjEzOiJ0aW1lem9uZV90eXBlIjtpOjM7czo4OiJ0aW1lem9uZSI7czozOiJVVEMiO319fX0=', 1789584178);

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of shifts
-- ----------------------------

-- ----------------------------
-- Table structure for student_attendances
-- ----------------------------
DROP TABLE IF EXISTS `student_attendances`;
CREATE TABLE `student_attendances`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `schedule_id` bigint UNSIGNED NULL DEFAULT NULL,
  `classroom_id` bigint UNSIGNED NULL DEFAULT NULL,
  `date` date NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `check_in_time` time NULL DEFAULT NULL,
  `check_out_time` time NULL DEFAULT NULL,
  `late_minutes` int NOT NULL DEFAULT 0,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `attendances_student_schedule_date_unique`(`student_id` ASC, `schedule_id` ASC, `date` ASC) USING BTREE,
  INDEX `attendances_student_index`(`student_id` ASC) USING BTREE,
  INDEX `attendances_schedule_index`(`schedule_id` ASC) USING BTREE,
  INDEX `attendances_classroom_index`(`classroom_id` ASC) USING BTREE,
  INDEX `attendances_date_index`(`date` ASC) USING BTREE,
  INDEX `attendances_status_index`(`status` ASC) USING BTREE,
  INDEX `attendances_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `attendances_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `attendances_updated_by_index`(`updated_by` ASC) USING BTREE,
  INDEX `attendances_verified_by_foreign`(`verified_by` ASC) USING BTREE,
  CONSTRAINT `attendances_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `attendances_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attendances_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of student_attendances
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of student_classrooms
-- ----------------------------

-- ----------------------------
-- Table structure for student_disciplines
-- ----------------------------
DROP TABLE IF EXISTS `student_disciplines`;
CREATE TABLE `student_disciplines`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `points` int NOT NULL DEFAULT 0,
  `action_taken` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `incident_date` date NULL DEFAULT NULL,
  `reported_by` bigint UNSIGNED NULL DEFAULT NULL,
  `witness` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `parent_notified` tinyint(1) NOT NULL DEFAULT 0,
  `parent_notified_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `student_disciplines_student_index`(`student_id` ASC) USING BTREE,
  INDEX `student_disciplines_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_disciplines_category_index`(`category` ASC) USING BTREE,
  INDEX `student_disciplines_incident_date_index`(`incident_date` ASC) USING BTREE,
  INDEX `student_disciplines_reported_by_index`(`reported_by` ASC) USING BTREE,
  INDEX `student_disciplines_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_disciplines_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_disciplines_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_disciplines_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_disciplines_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_disciplines_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of student_disciplines
-- ----------------------------

-- ----------------------------
-- Table structure for student_extracurriculars
-- ----------------------------
DROP TABLE IF EXISTS `student_extracurriculars`;
CREATE TABLE `student_extracurriculars`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `extracurricular_id` bigint UNSIGNED NOT NULL,
  `enrollment_date` date NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `grade_letter` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `predicate` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `certificate_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `achievement_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `evaluated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `evaluated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_extracurriculars_unique`(`student_id` ASC, `extracurricular_id` ASC) USING BTREE,
  INDEX `student_extracurriculars_student_index`(`student_id` ASC) USING BTREE,
  INDEX `student_extracurriculars_extracurricular_index`(`extracurricular_id` ASC) USING BTREE,
  INDEX `student_extracurriculars_status_index`(`status` ASC) USING BTREE,
  INDEX `student_extracurriculars_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_extracurriculars_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_extracurriculars_updated_by_index`(`updated_by` ASC) USING BTREE,
  INDEX `student_extracurriculars_evaluated_by_index`(`evaluated_by` ASC) USING BTREE,
  CONSTRAINT `student_extracurriculars_evaluated_by_foreign` FOREIGN KEY (`evaluated_by`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_extracurriculars_extracurricular_id_foreign` FOREIGN KEY (`extracurricular_id`) REFERENCES `extracurriculars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_extracurriculars_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of student_extracurriculars
-- ----------------------------

-- ----------------------------
-- Table structure for student_health_records
-- ----------------------------
DROP TABLE IF EXISTS `student_health_records`;
CREATE TABLE `student_health_records`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `record_date` date NOT NULL,
  `complaint` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `diagnosis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `treatment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `height_cm` decimal(5, 2) NULL DEFAULT NULL,
  `weight_kg` decimal(5, 2) NULL DEFAULT NULL,
  `blood_type` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `blood_pressure` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `allergies` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `vaccination_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `handled_by` bigint UNSIGNED NULL DEFAULT NULL,
  `referred_to` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `student_health_records_student_index`(`student_id` ASC) USING BTREE,
  INDEX `student_health_records_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_health_records_record_date_index`(`record_date` ASC) USING BTREE,
  INDEX `student_health_records_handled_by_index`(`handled_by` ASC) USING BTREE,
  INDEX `student_health_records_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_health_records_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_health_records_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_health_records_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_health_records_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_health_records_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of student_health_records
-- ----------------------------

-- ----------------------------
-- Table structure for student_installments
-- ----------------------------
DROP TABLE IF EXISTS `student_installments`;
CREATE TABLE `student_installments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `installment_number` int NOT NULL,
  `due_date` date NULL DEFAULT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `paid_date` date NULL DEFAULT NULL,
  `paid_amount` decimal(15, 2) NULL DEFAULT NULL,
  `payment_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_installments_invoice_number_unique`(`invoice_id` ASC, `installment_number` ASC) USING BTREE,
  INDEX `student_installments_invoice_index`(`invoice_id` ASC) USING BTREE,
  INDEX `student_installments_due_date_index`(`due_date` ASC) USING BTREE,
  INDEX `student_installments_is_paid_index`(`is_paid` ASC) USING BTREE,
  INDEX `student_installments_payment_index`(`payment_id` ASC) USING BTREE,
  INDEX `student_installments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_installments_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_installments_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_installments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of student_installments
-- ----------------------------

-- ----------------------------
-- Table structure for student_invoice_batches
-- ----------------------------
DROP TABLE IF EXISTS `student_invoice_batches`;
CREATE TABLE `student_invoice_batches`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `batch_id` bigint UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_invoice_batches_unique`(`invoice_id` ASC, `batch_id` ASC) USING BTREE,
  INDEX `student_invoice_batches_invoice_index`(`invoice_id` ASC) USING BTREE,
  INDEX `student_invoice_batches_batch_index`(`batch_id` ASC) USING BTREE,
  INDEX `student_invoice_batches_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `student_invoice_batches_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `student_payment_batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_invoice_batches_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of student_invoice_batches
-- ----------------------------

-- ----------------------------
-- Table structure for student_invoices
-- ----------------------------
DROP TABLE IF EXISTS `student_invoices`;
CREATE TABLE `student_invoices`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `fee_master_id` bigint UNSIGNED NOT NULL,
  `student_classroom_id` bigint UNSIGNED NULL DEFAULT NULL,
  `invoice_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_id` bigint UNSIGNED NULL DEFAULT NULL,
  `invoice_date` date NULL DEFAULT NULL,
  `due_date` date NULL DEFAULT NULL,
  `total_amount` decimal(15, 2) NOT NULL,
  `discount_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `penalty_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(15, 2) NOT NULL,
  `paid_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_invoices_school_number_unique`(`school_id` ASC, `invoice_number` ASC) USING BTREE,
  INDEX `student_invoices_student_index`(`student_id` ASC) USING BTREE,
  INDEX `student_invoices_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_invoices_fee_master_index`(`fee_master_id` ASC) USING BTREE,
  INDEX `student_invoices_student_classroom_index`(`student_classroom_id` ASC) USING BTREE,
  INDEX `student_invoices_status_index`(`status` ASC) USING BTREE,
  INDEX `student_invoices_due_date_index`(`due_date` ASC) USING BTREE,
  INDEX `student_invoices_period_index`(`period_id` ASC) USING BTREE,
  INDEX `student_invoices_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_invoices_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_invoices_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_invoices_fee_master_id_foreign` FOREIGN KEY (`fee_master_id`) REFERENCES `fee_masters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_invoices_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_invoices_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_invoices_student_classroom_id_foreign` FOREIGN KEY (`student_classroom_id`) REFERENCES `student_classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_invoices_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of student_invoices
-- ----------------------------

-- ----------------------------
-- Table structure for student_outcome_achievements
-- ----------------------------
DROP TABLE IF EXISTS `student_outcome_achievements`;
CREATE TABLE `student_outcome_achievements`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `learning_outcome_id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `achievement_level` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `score` decimal(5, 2) NULL DEFAULT NULL,
  `narrative` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `teacher_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_outcome_achievements_unique`(`student_id` ASC, `learning_outcome_id` ASC, `period_id` ASC) USING BTREE,
  INDEX `student_outcome_achievements_student_index`(`student_id` ASC) USING BTREE,
  INDEX `student_outcome_achievements_outcome_index`(`learning_outcome_id` ASC) USING BTREE,
  INDEX `student_outcome_achievements_period_index`(`period_id` ASC) USING BTREE,
  INDEX `student_outcome_achievements_subject_index`(`subject_id` ASC) USING BTREE,
  INDEX `student_outcome_achievements_level_index`(`achievement_level` ASC) USING BTREE,
  INDEX `student_outcome_achievements_teacher_index`(`teacher_id` ASC) USING BTREE,
  INDEX `student_outcome_achievements_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_outcome_achievements_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_outcome_achievements_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_outcome_achievements_outcome_id_foreign` FOREIGN KEY (`learning_outcome_id`) REFERENCES `learning_outcomes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_outcome_achievements_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_outcome_achievements_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_outcome_achievements_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_outcome_achievements_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of student_outcome_achievements
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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of student_parents
-- ----------------------------

-- ----------------------------
-- Table structure for student_payment_batches
-- ----------------------------
DROP TABLE IF EXISTS `student_payment_batches`;
CREATE TABLE `student_payment_batches`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `batch_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `total_invoices` int NOT NULL DEFAULT 0,
  `total_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `processed_by` bigint UNSIGNED NULL DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_payment_batches_school_number_unique`(`school_id` ASC, `batch_number` ASC) USING BTREE,
  INDEX `student_payment_batches_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_payment_batches_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `student_payment_batches_period_index`(`period_id` ASC) USING BTREE,
  INDEX `student_payment_batches_status_index`(`status` ASC) USING BTREE,
  INDEX `student_payment_batches_processed_by_index`(`processed_by` ASC) USING BTREE,
  INDEX `student_payment_batches_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_payment_batches_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_payment_batches_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_payment_batches_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_payment_batches_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_payment_batches_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_payment_batches_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of student_payment_batches
-- ----------------------------

-- ----------------------------
-- Table structure for student_payments
-- ----------------------------
DROP TABLE IF EXISTS `student_payments`;
CREATE TABLE `student_payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `payment_method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payment_date` date NULL DEFAULT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `reference_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payment_proof_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verification_date` timestamp NULL DEFAULT NULL,
  `refund_date` timestamp NULL DEFAULT NULL,
  `refund_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `student_payments_invoice_index`(`invoice_id` ASC) USING BTREE,
  INDEX `student_payments_student_index`(`student_id` ASC) USING BTREE,
  INDEX `student_payments_status_index`(`status` ASC) USING BTREE,
  INDEX `student_payments_verified_by_index`(`verified_by` ASC) USING BTREE,
  INDEX `student_payments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_payments_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_payments_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of student_payments
-- ----------------------------

-- ----------------------------
-- Table structure for student_promotions
-- ----------------------------
DROP TABLE IF EXISTS `student_promotions`;
CREATE TABLE `student_promotions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `from_classroom_id` bigint UNSIGNED NULL DEFAULT NULL,
  `to_classroom_id` bigint UNSIGNED NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `decision` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `decision_date` date NULL DEFAULT NULL,
  `meeting_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `final_score_average` decimal(5, 2) NULL DEFAULT NULL,
  `attendance_percentage` decimal(5, 2) NULL DEFAULT NULL,
  `decided_by` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_promotions_student_year_unique`(`student_id` ASC, `academic_year_id` ASC) USING BTREE,
  INDEX `student_promotions_student_index`(`student_id` ASC) USING BTREE,
  INDEX `student_promotions_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_promotions_from_classroom_index`(`from_classroom_id` ASC) USING BTREE,
  INDEX `student_promotions_to_classroom_index`(`to_classroom_id` ASC) USING BTREE,
  INDEX `student_promotions_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `student_promotions_decision_index`(`decision` ASC) USING BTREE,
  INDEX `student_promotions_decided_by_index`(`decided_by` ASC) USING BTREE,
  INDEX `student_promotions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_promotions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_promotions_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_promotions_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_decided_by_foreign` FOREIGN KEY (`decided_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_from_classroom_id_foreign` FOREIGN KEY (`from_classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_to_classroom_id_foreign` FOREIGN KEY (`to_classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of student_promotions
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
  `applicant_id` bigint UNSIGNED NULL DEFAULT NULL,
  `nis` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nisn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `registration_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  INDEX `students_applicant_index`(`applicant_id` ASC) USING BTREE,
  CONSTRAINT `students_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `students_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of subjects
-- ----------------------------

-- ----------------------------
-- Table structure for submissions
-- ----------------------------
DROP TABLE IF EXISTS `submissions`;
CREATE TABLE `submissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `assignment_quiz_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `submission_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `file_urls` json NULL,
  `score` decimal(5, 2) NULL DEFAULT NULL,
  `feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `graded_by` bigint UNSIGNED NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SUBMITTED',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `submissions_assignment_student_unique`(`assignment_quiz_id` ASC, `student_id` ASC) USING BTREE,
  INDEX `submissions_assignment_index`(`assignment_quiz_id` ASC) USING BTREE,
  INDEX `submissions_student_index`(`student_id` ASC) USING BTREE,
  INDEX `submissions_status_index`(`status` ASC) USING BTREE,
  INDEX `submissions_graded_by_index`(`graded_by` ASC) USING BTREE,
  INDEX `submissions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `submissions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `submissions_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `submissions_assignment_quiz_id_foreign` FOREIGN KEY (`assignment_quiz_id`) REFERENCES `assignments_quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `submissions_graded_by_foreign` FOREIGN KEY (`graded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `submissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of submissions
-- ----------------------------

-- ----------------------------
-- Table structure for system_logs
-- ----------------------------
DROP TABLE IF EXISTS `system_logs`;
CREATE TABLE `system_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `record_id` bigint UNSIGNED NULL DEFAULT NULL,
  `old_data` json NULL,
  `new_data` json NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
  `tax_type_id` bigint UNSIGNED NOT NULL,
  `tax_rate` decimal(12, 4) NOT NULL DEFAULT 0.0000,
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
) ENGINE = InnoDB AUTO_INCREMENT = 37 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tax_settings
-- ----------------------------
INSERT INTO `tax_settings` VALUES (19, 1, 'PPN-001', 'PPN 11% (Tarif Normal)', 'ppn', 4, 11.0000, NULL, NULL, '2022-04-01', '2025-03-31', 0, 'Tarif PPN 11% berlaku mulai 1 April 2022 sesuai UU HPP', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (20, 1, 'PPN-002', 'PPN 12% (Tarif Baru)', 'ppn', 4, 12.0000, NULL, NULL, '2025-04-01', NULL, 1, 'Tarif PPN 12% berlaku mulai 1 April 2025 sesuai UU HPP', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (21, 1, 'PPh21-01', 'PPh Pasal 21 - 0% (PTKP)', 'pph21', 1, 0.0000, 0.00, 54000000.00, '2024-01-01', NULL, 1, 'Penghasilan kena pajak sampai dengan Rp 54.000.000 (PTKP)', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (22, 1, 'PPh21-02', 'PPh Pasal 21 - 5%', 'pph21', 1, 5.0000, 54000000.00, 60000000.00, '2024-01-01', NULL, 1, 'Tarif 5% untuk PKP Rp 54.000.001 - Rp 60.000.000', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (23, 1, 'PPh21-03', 'PPh Pasal 21 - 15%', 'pph21', 1, 15.0000, 60000000.00, 250000000.00, '2024-01-01', NULL, 1, 'Tarif 15% untuk PKP Rp 60.000.001 - Rp 250.000.000', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (24, 1, 'PPh21-04', 'PPh Pasal 21 - 25%', 'pph21', 1, 25.0000, 250000000.00, 500000000.00, '2024-01-01', NULL, 1, 'Tarif 25% untuk PKP Rp 250.000.001 - Rp 500.000.000', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (25, 1, 'PPh21-05', 'PPh Pasal 21 - 30%', 'pph21', 1, 30.0000, 500000000.00, 5000000000.00, '2024-01-01', NULL, 1, 'Tarif 30% untuk PKP Rp 500.000.001 - Rp 5.000.000.000', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (26, 1, 'PPh21-06', 'PPh Pasal 21 - 35%', 'pph21', 1, 35.0000, 5000000000.00, NULL, '2024-01-01', NULL, 1, 'Tarif 35% untuk PKP di atas Rp 5.000.000.000', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (27, 1, 'PPh23-01', 'PPh Pasal 23 - 2% (Jasa)', 'pph23', 2, 2.0000, NULL, NULL, '2024-01-01', NULL, 1, 'PPh 23 untuk jasa (2% dari bruto) - tidak termasuk PPN', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (28, 1, 'PPh23-02', 'PPh Pasal 23 - 15% (Sewa)', 'pph23', 2, 15.0000, NULL, NULL, '2024-01-01', NULL, 1, 'PPh 23 untuk sewa (15% dari bruto)', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (29, 1, 'PPh22-01', 'PPh Pasal 22 - 1.5% (Pembelian)', 'other', 6, 1.5000, 2000000.00, NULL, '2024-01-01', NULL, 1, 'PPh 22 untuk pembelian barang (1.5% dari harga) - minimal Rp 2.000.000', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (30, 1, 'PPh4-2-01', 'PPh Final - 10% (Sewa Tanah/Bangunan)', 'other', 6, 10.0000, NULL, NULL, '2024-01-01', NULL, 1, 'PPh Final 10% untuk sewa tanah dan/atau bangunan', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (31, 1, 'PPh4-2-02', 'PPh Final - 0.5% (UMKM)', 'other', 6, 0.5000, NULL, 4800000000.00, '2024-01-01', NULL, 1, 'PPh Final 0.5% untuk UMKM dengan omzet maksimal Rp 4.8M per tahun', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (32, 1, 'PBB-001', 'Pajak Bumi dan Bangunan (PBB)', 'other', 6, 0.1000, NULL, NULL, '2024-01-01', NULL, 1, 'PBB untuk bangunan yayasan (0.1% dari NJOP)', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (33, 1, 'BPHTB-001', 'Bea Perolehan Hak atas Tanah dan Bangunan', 'other', 6, 5.0000, NULL, NULL, '2024-01-01', NULL, 1, 'BPHTB 5% untuk perolehan tanah/bangunan', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (34, 1, 'MTR-001', 'Bea Meterai Rp 10.000', 'other', 6, 10000.0000, NULL, NULL, '2024-01-01', NULL, 1, 'Bea meterai untuk dokumen tertentu (Rp 10.000)', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (35, 1, 'MTR-002', 'Bea Meterai Rp 6.000', 'other', 6, 6000.0000, NULL, NULL, '2024-01-01', NULL, 1, 'Bea meterai untuk dokumen tertentu (Rp 6.000)', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);
INSERT INTO `tax_settings` VALUES (36, 1, 'PPN-OLD', 'PPN 10% (Tarif Lama)', 'ppn', 4, 10.0000, NULL, NULL, '2000-01-01', '2022-03-31', 0, 'Tarif PPN 10% berlaku sebelum 1 April 2022 (sudah diganti 11%)', '2026-09-14 16:08:53', '2026-09-14 16:08:53', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for tax_types
-- ----------------------------
DROP TABLE IF EXISTS `tax_types`;
CREATE TABLE `tax_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint NULL DEFAULT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tax_types_code_unique`(`code`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tax_types
-- ----------------------------
INSERT INTO `tax_types` VALUES (1, 1, 'pph21', 'PPh Pasal 21', 'Pajak Penghasilan Pasal 21', 1, NULL, NULL, NULL);
INSERT INTO `tax_types` VALUES (2, 1, 'pph23', 'PPh Pasal 23', 'Pajak Penghasilan Pasal 23', 1, NULL, NULL, NULL);
INSERT INTO `tax_types` VALUES (3, 1, 'pph25', 'PPh Pasal 25', 'Pajak Penghasilan Pasal 25', 1, NULL, NULL, NULL);
INSERT INTO `tax_types` VALUES (4, 1, 'ppn', 'PPN', 'Pajak Pertambahan Nilai', 1, NULL, NULL, NULL);
INSERT INTO `tax_types` VALUES (5, 1, 'ppnbm', 'PPnBM', 'Pajak Penjualan Barang Mewah', 1, NULL, NULL, NULL);
INSERT INTO `tax_types` VALUES (6, 1, 'other', 'Lainnya', 'Jenis pajak lainnya', 1, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for teacher_comments
-- ----------------------------
DROP TABLE IF EXISTS `teacher_comments`;
CREATE TABLE `teacher_comments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `classroom_id` bigint UNSIGNED NOT NULL,
  `teacher_id` bigint UNSIGNED NULL DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `teacher_comments_student_index`(`student_id` ASC) USING BTREE,
  INDEX `teacher_comments_period_index`(`period_id` ASC) USING BTREE,
  INDEX `teacher_comments_classroom_index`(`classroom_id` ASC) USING BTREE,
  INDEX `teacher_comments_teacher_index`(`teacher_id` ASC) USING BTREE,
  INDEX `teacher_comments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `teacher_comments_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `teacher_comments_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `teacher_comments_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `teacher_comments_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `teacher_comments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `teacher_comments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of teacher_comments
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
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialization` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `education_level_id` bigint NULL DEFAULT NULL,
  `join_date` date NULL DEFAULT NULL,
  `status` enum('ACTIVE','RESIGNED','RETIRED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'ACTIVE',
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NULL DEFAULT NULL,
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
) ENGINE = InnoDB AUTO_INCREMENT = 71 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of teachers
-- ----------------------------
INSERT INTO `teachers` VALUES (1, 1, NULL, '198501012010011001', 'Ahmad Fauzi, S.Pd.', 'Pendidikan Agama Islam', NULL, '2010-01-04', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (2, 1, NULL, '198603152011012002', 'Siti Aminah, S.Pd.', 'Bahasa Indonesia', NULL, '2011-07-01', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (3, 1, NULL, '198709202012011003', 'Budi Santoso, S.Pd.', 'Matematika', NULL, '2012-01-02', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (4, 1, NULL, '198804102013012004', 'Dewi Lestari, S.Pd.', 'Bahasa Inggris', NULL, '2013-07-01', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (5, 1, NULL, '198902252014011005', 'Rizky Hidayat, S.Pd.', 'Ilmu Pengetahuan Alam', NULL, '2014-01-06', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (6, 1, NULL, '198905122015012006', 'Nurul Hidayah, S.Pd.', 'Ilmu Pengetahuan Sosial', NULL, '2015-07-01', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (7, 1, NULL, '199001182016011007', 'Agus Setiawan, S.Pd.', 'Pendidikan Jasmani', NULL, '2016-01-04', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (8, 1, NULL, '199102052017012008', 'Maya Sari, S.Pd.', 'Seni Budaya', NULL, '2017-07-03', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (9, 1, NULL, '199203102018011009', 'Fajar Nugroho, S.Pd.', 'Teknologi Informasi', NULL, '2018-01-08', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (10, 1, NULL, '199305202019012010', 'Rina Wulandari, S.Pd.', 'Bahasa Indonesia', NULL, '2019-07-01', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (11, 1, NULL, '199407152020011011', 'Dedi Kurniawan, S.Pd.', 'Matematika', NULL, '2020-01-06', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (12, 1, NULL, '199508122021012012', 'Ayu Pratiwi, S.Pd.', 'Bahasa Inggris', NULL, '2021-07-01', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (13, 1, NULL, '199609252022011013', 'Hendra Wijaya, S.Pd.', 'Ilmu Pengetahuan Alam', NULL, '2022-01-03', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (14, 1, NULL, '199701182023012014', 'Lina Marlina, S.Pd.', 'Pendidikan Agama Islam', NULL, '2023-07-03', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);
INSERT INTO `teachers` VALUES (15, 1, NULL, '199802052024011015', 'Andi Pratama, S.Pd.', 'Pendidikan Jasmani', NULL, '2024-01-08', 'ACTIVE', NULL, NULL, '2026-09-16 12:51:16', '2026-09-16 12:51:16', NULL, NULL, NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of teaching_loads
-- ----------------------------

-- ----------------------------
-- Table structure for test_schedules
-- ----------------------------
DROP TABLE IF EXISTS `test_schedules`;
CREATE TABLE `test_schedules`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `admission_selection_id` bigint UNSIGNED NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `test_type` enum('tulis','wawancara','praktek','kesehatan','psikotes','lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'tulis',
  `test_method` enum('offline','online','hybrid') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'offline',
  `test_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_minutes` int UNSIGNED NULL DEFAULT NULL,
  `registration_start` datetime NULL DEFAULT NULL,
  `registration_end` datetime NULL DEFAULT NULL,
  `room_id` bigint UNSIGNED NULL DEFAULT NULL,
  `building_id` bigint UNSIGNED NULL DEFAULT NULL,
  `location_detail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `online_meeting_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `online_meeting_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `online_password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `cbt_session_id` bigint UNSIGNED NULL DEFAULT NULL,
  `exam_package_id` bigint UNSIGNED NULL DEFAULT NULL,
  `quota` int UNSIGNED NOT NULL DEFAULT 0,
  `booked_count` int UNSIGNED NOT NULL DEFAULT 0,
  `min_participants` int UNSIGNED NULL DEFAULT 0,
  `supervisor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `coordinator_id` bigint UNSIGNED NULL DEFAULT NULL,
  `proctor_ids` json NULL,
  `allow_self_register` tinyint(1) NOT NULL DEFAULT 0,
  `allow_reschedule` tinyint(1) NOT NULL DEFAULT 0,
  `late_tolerance_min` int UNSIGNED NULL DEFAULT 15,
  `status` enum('draft','published','ongoing','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `passing_score` decimal(5, 2) NULL DEFAULT NULL,
  `max_score` decimal(5, 2) NULL DEFAULT NULL,
  `score_input_deadline` datetime NULL DEFAULT NULL,
  `result_announcement` datetime NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `attachments` json NULL,
  `settings` json NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code`) USING BTREE,
  INDEX `idx_admission`(`admission_id`) USING BTREE,
  INDEX `idx_selection`(`admission_selection_id`) USING BTREE,
  INDEX `idx_academic_year`(`academic_year_id`) USING BTREE,
  INDEX `idx_test_date`(`test_date`, `start_time`) USING BTREE,
  INDEX `idx_status`(`status`, `is_active`) USING BTREE,
  INDEX `idx_test_type`(`test_type`) USING BTREE,
  INDEX `idx_cbt_session`(`cbt_session_id`) USING BTREE,
  INDEX `fk_test_schedules_room`(`room_id`) USING BTREE,
  INDEX `fk_test_schedules_exam_package`(`exam_package_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of test_schedules
-- ----------------------------

-- ----------------------------
-- Table structure for test_types
-- ----------------------------
DROP TABLE IF EXISTS `test_types`;
CREATE TABLE `test_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name_en` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `slug` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `category` enum('akademik','non_akademik','kesehatan','administrasi','lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'akademik',
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `badge_class` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `default_duration_minutes` int UNSIGNED NULL DEFAULT NULL,
  `default_passing_score` decimal(5, 2) NULL DEFAULT NULL,
  `default_max_score` decimal(5, 2) NULL DEFAULT NULL,
  `default_weight` decimal(5, 2) NULL DEFAULT 100.00,
  `default_method` enum('offline','online','hybrid') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'offline',
  `requires_room` tinyint(1) NOT NULL DEFAULT 1,
  `requires_supervisor` tinyint(1) NOT NULL DEFAULT 1,
  `requires_cbt` tinyint(1) NOT NULL DEFAULT 0,
  `scoring_components` json NULL,
  `settings` json NULL,
  `order` int UNSIGNED NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code`) USING BTREE,
  UNIQUE INDEX `slug`(`slug`) USING BTREE,
  INDEX `idx_code`(`code`) USING BTREE,
  INDEX `idx_category`(`category`, `is_active`) USING BTREE,
  INDEX `idx_parent`(`parent_id`) USING BTREE,
  INDEX `idx_order`(`order`) USING BTREE,
  INDEX `idx_school`(`school_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of test_types
-- ----------------------------

-- ----------------------------
-- Table structure for timetable_constraints
-- ----------------------------
DROP TABLE IF EXISTS `timetable_constraints`;
CREATE TABLE `timetable_constraints`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `day_of_week` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `time_slot_start` time NULL DEFAULT NULL,
  `time_slot_end` time NULL DEFAULT NULL,
  `constraint_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `timetable_constraints_school_index`(`school_id` ASC) USING BTREE,
  INDEX `timetable_constraints_teacher_index`(`teacher_id` ASC) USING BTREE,
  INDEX `timetable_constraints_day_index`(`day_of_week` ASC) USING BTREE,
  INDEX `timetable_constraints_type_index`(`constraint_type` ASC) USING BTREE,
  INDEX `timetable_constraints_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `timetable_constraints_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `timetable_constraints_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `timetable_constraints_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `timetable_constraints_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of timetable_constraints
-- ----------------------------

-- ----------------------------
-- Table structure for timetable_generations
-- ----------------------------
DROP TABLE IF EXISTS `timetable_generations`;
CREATE TABLE `timetable_generations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `semester` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `generation_date` timestamp NULL DEFAULT NULL,
  `algorithm_version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `total_conflicts` int NOT NULL DEFAULT 0,
  `total_assignments` int NOT NULL DEFAULT 0,
  `total_teachers` int NOT NULL DEFAULT 0,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GENERATED',
  `generation_data` json NULL,
  `generated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `timetable_generations_school_index`(`school_id` ASC) USING BTREE,
  INDEX `timetable_generations_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `timetable_generations_status_index`(`status` ASC) USING BTREE,
  INDEX `timetable_generations_generated_by_index`(`generated_by` ASC) USING BTREE,
  INDEX `timetable_generations_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `timetable_generations_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `timetable_generations_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `timetable_generations_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `timetable_generations_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `timetable_generations_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of timetable_generations
-- ----------------------------

-- ----------------------------
-- Table structure for timetable_rooms
-- ----------------------------
DROP TABLE IF EXISTS `timetable_rooms`;
CREATE TABLE `timetable_rooms`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `room_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL DEFAULT 30,
  `room_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `floor_number` int NULL DEFAULT NULL,
  `building` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `facilities` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `timetable_rooms_school_code_unique`(`school_id` ASC, `room_code` ASC) USING BTREE,
  INDEX `timetable_rooms_school_index`(`school_id` ASC) USING BTREE,
  INDEX `timetable_rooms_type_index`(`room_type` ASC) USING BTREE,
  INDEX `timetable_rooms_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `timetable_rooms_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `timetable_rooms_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `timetable_rooms_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of timetable_rooms
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of trial_registrations
-- ----------------------------

-- ----------------------------
-- Table structure for user_roles
-- ----------------------------
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `assigned_by` bigint UNSIGNED NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_roles_user_role_school_unique`(`user_id` ASC, `role_id` ASC, `school_id` ASC) USING BTREE,
  INDEX `user_roles_user_index`(`user_id` ASC) USING BTREE,
  INDEX `user_roles_role_index`(`role_id` ASC) USING BTREE,
  INDEX `user_roles_school_index`(`school_id` ASC) USING BTREE,
  INDEX `user_roles_assigned_by_index`(`assigned_by` ASC) USING BTREE,
  INDEX `user_roles_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `user_roles_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `user_roles_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `user_roles_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_roles_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_roles
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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Administrator Utama', 'admin@contoh.id', '2026-09-09 17:08:23', '$2y$12$ApZZYsuECqSAx2dlOEwlL.bKcKCaD.ilKo3kLJ850otsXcntr7X0S', NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-09 17:08:23', '2026-09-09 17:08:23', NULL, NULL, NULL);
INSERT INTO `users` VALUES (2, 'Pengguna Biasa', 'user@contoh.id', '2026-09-09 17:08:24', '$2y$12$n75I8iQeGGpOxxKLuT/B2uzArGBZeWRDJneqatSDIVJOtFCPIeLCi', NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-09 17:08:24', '2026-09-09 17:08:24', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for vendor_invoice_lines
-- ----------------------------
DROP TABLE IF EXISTS `vendor_invoice_lines`;
CREATE TABLE `vendor_invoice_lines`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_invoice_id` bigint UNSIGNED NOT NULL,
  `chart_of_account_id` bigint UNSIGNED NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `quantity` decimal(20, 4) NOT NULL DEFAULT 1.0000,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `unit_price` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `line_number` int NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `vendor_invoice_lines_invoice_index`(`vendor_invoice_id` ASC) USING BTREE,
  INDEX `vendor_invoice_lines_coa_index`(`chart_of_account_id` ASC) USING BTREE,
  INDEX `vendor_invoice_lines_line_number_index`(`line_number` ASC) USING BTREE,
  INDEX `vendor_invoice_lines_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `vendor_invoice_lines_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `vendor_invoice_lines_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `vendor_invoice_lines_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_invoice_lines_vendor_invoice_id_foreign` FOREIGN KEY (`vendor_invoice_id`) REFERENCES `vendor_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of vendor_invoice_lines
-- ----------------------------

-- ----------------------------
-- Table structure for vendor_invoices
-- ----------------------------
DROP TABLE IF EXISTS `vendor_invoices`;
CREATE TABLE `vendor_invoices`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `fiscal_period_id` bigint UNSIGNED NOT NULL,
  `currency_id` bigint UNSIGNED NOT NULL,
  `exchange_rate` decimal(20, 8) NOT NULL DEFAULT 1.00000000,
  `invoice_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `internal_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NULL DEFAULT NULL,
  `received_date` date NULL DEFAULT NULL,
  `subtotal` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `tax_base` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `ppn_rate` decimal(5, 2) NOT NULL DEFAULT 0.00,
  `ppn_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `pph_rate` decimal(5, 2) NOT NULL DEFAULT 0.00,
  `pph_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `outstanding_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `functional_total` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UNPAID',
  `payment_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `posted_journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `vendor_invoices_school_internal_unique`(`school_id` ASC, `internal_number` ASC) USING BTREE,
  UNIQUE INDEX `vendor_invoices_vendor_number_unique`(`vendor_id` ASC, `invoice_number` ASC) USING BTREE,
  INDEX `vendor_invoices_school_index`(`school_id` ASC) USING BTREE,
  INDEX `vendor_invoices_vendor_index`(`vendor_id` ASC) USING BTREE,
  INDEX `vendor_invoices_fiscal_period_index`(`fiscal_period_id` ASC) USING BTREE,
  INDEX `vendor_invoices_currency_index`(`currency_id` ASC) USING BTREE,
  INDEX `vendor_invoices_date_index`(`invoice_date` ASC) USING BTREE,
  INDEX `vendor_invoices_due_date_index`(`due_date` ASC) USING BTREE,
  INDEX `vendor_invoices_status_index`(`status` ASC) USING BTREE,
  INDEX `vendor_invoices_payment_status_index`(`payment_status` ASC) USING BTREE,
  INDEX `vendor_invoices_posted_entry_index`(`posted_journal_entry_id` ASC) USING BTREE,
  INDEX `vendor_invoices_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `vendor_invoices_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `vendor_invoices_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `vendor_invoices_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_invoices_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_invoices_posted_journal_entry_id_foreign` FOREIGN KEY (`posted_journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `vendor_invoices_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_invoices_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of vendor_invoices
-- ----------------------------

-- ----------------------------
-- Table structure for vendor_payments
-- ----------------------------
DROP TABLE IF EXISTS `vendor_payments`;
CREATE TABLE `vendor_payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `vendor_invoice_id` bigint UNSIGNED NULL DEFAULT NULL,
  `bank_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `fiscal_period_id` bigint UNSIGNED NOT NULL,
  `currency_id` bigint UNSIGNED NOT NULL,
  `exchange_rate` decimal(20, 8) NOT NULL DEFAULT 1.00000000,
  `payment_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reference_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `pph_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `functional_amount` decimal(20, 2) NOT NULL DEFAULT 0.00,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `posted_journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'POSTED',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `vendor_payments_school_number_unique`(`school_id` ASC, `payment_number` ASC) USING BTREE,
  INDEX `vendor_payments_school_index`(`school_id` ASC) USING BTREE,
  INDEX `vendor_payments_vendor_index`(`vendor_id` ASC) USING BTREE,
  INDEX `vendor_payments_invoice_index`(`vendor_invoice_id` ASC) USING BTREE,
  INDEX `vendor_payments_bank_account_index`(`bank_account_id` ASC) USING BTREE,
  INDEX `vendor_payments_fiscal_period_index`(`fiscal_period_id` ASC) USING BTREE,
  INDEX `vendor_payments_currency_index`(`currency_id` ASC) USING BTREE,
  INDEX `vendor_payments_date_index`(`payment_date` ASC) USING BTREE,
  INDEX `vendor_payments_status_index`(`status` ASC) USING BTREE,
  INDEX `vendor_payments_posted_entry_index`(`posted_journal_entry_id` ASC) USING BTREE,
  INDEX `vendor_payments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `vendor_payments_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `vendor_payments_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `vendor_payments_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `vendor_payments_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_payments_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_payments_posted_journal_entry_id_foreign` FOREIGN KEY (`posted_journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `vendor_payments_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_payments_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vendor_payments_vendor_invoice_id_foreign` FOREIGN KEY (`vendor_invoice_id`) REFERENCES `vendor_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of vendor_payments
-- ----------------------------

-- ----------------------------
-- Table structure for vendors
-- ----------------------------
DROP TABLE IF EXISTS `vendors`;
CREATE TABLE `vendors`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `legal_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `vendor_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'COMPANY',
  `npwp` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_pkp` tinyint(1) NOT NULL DEFAULT 0,
  `pkp_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ppn_rate` decimal(5, 2) NOT NULL DEFAULT 0.00,
  `contact_person` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `website` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `province` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `postal_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bank_account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bank_account_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payment_terms_days` int NOT NULL DEFAULT 30,
  `payable_account_id` bigint UNSIGNED NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `vendors_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `vendors_school_index`(`school_id` ASC) USING BTREE,
  INDEX `vendors_name_index`(`name` ASC) USING BTREE,
  INDEX `vendors_npwp_index`(`npwp` ASC) USING BTREE,
  INDEX `vendors_is_pkp_index`(`is_pkp` ASC) USING BTREE,
  INDEX `vendors_vendor_type_index`(`vendor_type` ASC) USING BTREE,
  INDEX `vendors_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `vendors_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `vendors_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `vendors_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of vendors
-- ----------------------------

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of virtual_accounts
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
