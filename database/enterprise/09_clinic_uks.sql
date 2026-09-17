-- ==============================================================================
-- ENTERPRISE MODULE 09: STUDENT & EMPLOYEE HEALTH CLINIC (UKS / POLIKLINIK)
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;
