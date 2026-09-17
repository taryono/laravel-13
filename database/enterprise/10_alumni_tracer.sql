-- ==============================================================================
-- ENTERPRISE MODULE 10: ALUMNI TRACKING, CAREER & TRACER STUDY
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 1. Database Alumni (Alumni)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `alumni`;
CREATE TABLE `alumni` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `graduation_year` year NOT NULL,
  `current_activity` enum('employed','higher_education','entrepreneur','unemployed','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employed',
  `company_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `job_title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `industry_sector` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `monthly_income_range` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `university_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `university_major` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `current_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `linkedin_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `alumni_student_unique`(`student_id` ASC) USING BTREE,
  INDEX `alumni_org_index`(`organization_id` ASC) USING BTREE,
  INDEX `alumni_school_index`(`school_id` ASC) USING BTREE,
  INDEX `alumni_grad_year_index`(`graduation_year` ASC) USING BTREE,
  INDEX `alumni_activity_index`(`current_activity` ASC) USING BTREE,
  INDEX `alumni_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `alumni_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `alumni_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `alumni_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Master Kuesioner Tracer Study (Tracer Studies)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `tracer_studies`;
CREATE TABLE `tracer_studies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `target_graduation_year` year NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `tracer_org_index`(`organization_id` ASC) USING BTREE,
  INDEX `tracer_school_index`(`school_id` ASC) USING BTREE,
  INDEX `tracer_acad_year_index`(`academic_year_id` ASC) USING BTREE,
  CONSTRAINT `tracer_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tracer_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tracer_acad_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 3. Pertanyaan Tracer Study (Tracer Study Questions)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `tracer_study_questions`;
CREATE TABLE `tracer_study_questions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tracer_study_id` bigint UNSIGNED NOT NULL,
  `question_order` smallint UNSIGNED NOT NULL,
  `question_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_type` enum('text','textarea','multiple_choice','checkbox','scale_1_5') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `options_json` json NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `tracer_q_study_index`(`tracer_study_id` ASC, `question_order` ASC) USING BTREE,
  CONSTRAINT `tracer_q_study_fk` FOREIGN KEY (`tracer_study_id`) REFERENCES `tracer_studies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 4. Jawaban Responden Alumni (Tracer Study Responses)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `tracer_study_responses`;
CREATE TABLE `tracer_study_responses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tracer_study_id` bigint UNSIGNED NOT NULL,
  `alumni_id` bigint UNSIGNED NOT NULL,
  `question_id` bigint UNSIGNED NOT NULL,
  `answer_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `answer_json` json NULL,
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tracer_resp_unique`(`alumni_id` ASC, `question_id` ASC) USING BTREE,
  INDEX `tracer_resp_study_index`(`tracer_study_id` ASC) USING BTREE,
  INDEX `tracer_resp_q_index`(`question_id` ASC) USING BTREE,
  CONSTRAINT `tracer_resp_study_fk` FOREIGN KEY (`tracer_study_id`) REFERENCES `tracer_studies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tracer_resp_alumni_fk` FOREIGN KEY (`alumni_id`) REFERENCES `alumni` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tracer_resp_q_fk` FOREIGN KEY (`question_id`) REFERENCES `tracer_study_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 5. Donasi & Kontribusi Alumni (Alumni Donations)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `alumni_donations`;
CREATE TABLE `alumni_donations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `alumni_id` bigint UNSIGNED NOT NULL,
  `donation_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `campaign_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dana Abadi Pendidikan',
  `amount` decimal(15, 2) NOT NULL,
  `payment_method` enum('bank_transfer','qris','credit_card','cash') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bank_transfer',
  `proof_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('pending','verified','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `verified_at` timestamp NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `alumni_don_num_unique`(`donation_number` ASC) USING BTREE,
  INDEX `alumni_don_alumni_index`(`alumni_id` ASC) USING BTREE,
  INDEX `alumni_don_journal_index`(`journal_entry_id` ASC) USING BTREE,
  CONSTRAINT `alumni_don_alumni_fk` FOREIGN KEY (`alumni_id`) REFERENCES `alumni` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `alumni_don_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
