/*
 * Table Group: alumni
 * Total Tables: 7
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `alumni`
-- ----------------------------
DROP TABLE IF EXISTS `alumni`;
CREATE TABLE `alumni` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `graduation_year` year NOT NULL,
  `current_activity` enum('employed','higher_education','entrepreneur','unemployed','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employed',
  `company_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry_sector` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monthly_income_range` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university_major` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `alumni_student_unique` (`student_id`) USING BTREE,
  KEY `alumni_org_index` (`organization_id`) USING BTREE,
  KEY `alumni_school_index` (`school_id`) USING BTREE,
  KEY `alumni_grad_year_index` (`graduation_year`) USING BTREE,
  KEY `alumni_activity_index` (`current_activity`) USING BTREE,
  KEY `alumni_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `alumni_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `alumni_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `alumni_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `alumni_donations`
-- ----------------------------
DROP TABLE IF EXISTS `alumni_donations`;
CREATE TABLE `alumni_donations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `alumni_id` bigint unsigned NOT NULL,
  `donation_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `campaign_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dana Abadi Pendidikan',
  `amount` decimal(15,2) NOT NULL,
  `payment_method` enum('bank_transfer','qris','credit_card','cash') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bank_transfer',
  `proof_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','verified','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `verified_at` timestamp NULL DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `alumni_don_num_unique` (`donation_number`) USING BTREE,
  KEY `alumni_don_alumni_index` (`alumni_id`) USING BTREE,
  KEY `alumni_don_journal_index` (`journal_entry_id`) USING BTREE,
  CONSTRAINT `alumni_don_alumni_fk` FOREIGN KEY (`alumni_id`) REFERENCES `alumni` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `alumni_don_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `graduations`
-- ----------------------------
DROP TABLE IF EXISTS `graduations`;
CREATE TABLE `graduations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `classroom_id` bigint unsigned DEFAULT NULL,
  `graduation_date` date DEFAULT NULL,
  `certificate_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate_issued_at` date DEFAULT NULL,
  `skhu_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skhu_issued_at` date DEFAULT NULL,
  `transcript_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_score_average` decimal(5,2) DEFAULT NULL,
  `predicate` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'LULUS',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `graduations_student_year_unique` (`student_id`,`academic_year_id`) USING BTREE,
  UNIQUE KEY `graduations_certificate_number_unique` (`certificate_number`) USING BTREE,
  UNIQUE KEY `graduations_skhu_number_unique` (`skhu_number`) USING BTREE,
  KEY `graduations_student_index` (`student_id`) USING BTREE,
  KEY `graduations_school_index` (`school_id`) USING BTREE,
  KEY `graduations_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `graduations_classroom_index` (`classroom_id`) USING BTREE,
  KEY `graduations_status_index` (`status`) USING BTREE,
  KEY `graduations_graduation_date_index` (`graduation_date`) USING BTREE,
  KEY `graduations_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `graduations_created_by_index` (`created_by`) USING BTREE,
  KEY `graduations_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `graduations_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `graduations_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `graduations_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `graduations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `tracer_studies`
-- ----------------------------
DROP TABLE IF EXISTS `tracer_studies`;
CREATE TABLE `tracer_studies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `target_graduation_year` year NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `tracer_org_index` (`organization_id`) USING BTREE,
  KEY `tracer_school_index` (`school_id`) USING BTREE,
  KEY `tracer_acad_year_index` (`academic_year_id`) USING BTREE,
  CONSTRAINT `tracer_acad_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tracer_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tracer_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `tracer_study_questions`
-- ----------------------------
DROP TABLE IF EXISTS `tracer_study_questions`;
CREATE TABLE `tracer_study_questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tracer_study_id` bigint unsigned NOT NULL,
  `question_order` smallint unsigned NOT NULL,
  `question_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_type` enum('text','textarea','multiple_choice','checkbox','scale_1_5') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `options_json` json DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `tracer_q_study_index` (`tracer_study_id`,`question_order`) USING BTREE,
  CONSTRAINT `tracer_study_questions_ibfk_1` FOREIGN KEY (`tracer_study_id`) REFERENCES `tracer_studies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `tracer_study_responses`
-- ----------------------------
DROP TABLE IF EXISTS `tracer_study_responses`;
CREATE TABLE `tracer_study_responses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tracer_study_id` bigint unsigned NOT NULL,
  `alumni_id` bigint unsigned NOT NULL,
  `question_id` bigint unsigned NOT NULL,
  `answer_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `answer_json` json DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `tracer_resp_unique` (`alumni_id`,`question_id`) USING BTREE,
  KEY `tracer_resp_study_index` (`tracer_study_id`) USING BTREE,
  KEY `tracer_resp_q_index` (`question_id`) USING BTREE,
  CONSTRAINT `tracer_resp_alumni_fk` FOREIGN KEY (`alumni_id`) REFERENCES `alumni` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tracer_resp_q_fk` FOREIGN KEY (`question_id`) REFERENCES `tracer_study_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tracer_resp_study_fk` FOREIGN KEY (`tracer_study_id`) REFERENCES `tracer_studies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `yudisiums`
-- ----------------------------
DROP TABLE IF EXISTS `yudisiums`;
CREATE TABLE `yudisiums` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `decree_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `decree_date` date NOT NULL,
  `national_diploma_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `graduation_date` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `yudisium_student_unique` (`student_id`) USING BTREE,
  KEY `yudisium_year_index` (`academic_year_id`) USING BTREE,
  CONSTRAINT `yudisium_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `yudisium_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
