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

 Date: 20/09/2026 16:52:53
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for parents
-- ----------------------------
DROP TABLE IF EXISTS `parents`; 
CREATE TABLE `parents`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NULL DEFAULT NULL,
  `address` text NULL,
  `work` varchar(100) NULL DEFAULT NULL,
  -- HAPUS `is_primary` dan `relationship` dari sini
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_parents_user_id_unique`(`user_id`),
  INDEX `idx_parents_organization_id`(`organization_id`),
  INDEX `idx_parents_phone`(`phone`),
  INDEX `idx_parents_deleted_at`(`deleted_at`),
  CONSTRAINT `parents_org_fk` FOREIGN KEY (`organization_id`) 
    REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
-- ----------------------------
-- Table structure for student_parents
-- ----------------------------
DROP TABLE IF EXISTS `student_parents`;
CREATE TABLE `student_parents`  (
  `student_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED NOT NULL,
  `relationship` enum('FATHER','MOTHER','GUARDIAN') NULL DEFAULT NULL, -- PINDAH KE SINI
  `is_primary` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`student_id`, `parent_id`),
  -- Cegah lebih dari satu orang tua utama per siswa
  UNIQUE INDEX `idx_unique_primary` (`student_id`, `is_primary`),
  INDEX `idx_student_parents_student`(`student_id`),
  INDEX `idx_student_parents_parent`(`parent_id`),
  INDEX `idx_student_parents_deleted_at`(`deleted_at`),
  CONSTRAINT `sp_parent_fk` FOREIGN KEY (`parent_id`) 
    REFERENCES `parents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sp_student_fk` FOREIGN KEY (`student_id`) 
    REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
