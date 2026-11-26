/*
 * Table Group: academic
 * Total Tables: 45
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `academic_calendar_events`
-- ----------------------------
DROP TABLE IF EXISTS `academic_calendar_events`;
CREATE TABLE `academic_calendar_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint DEFAULT NULL,
  `school_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `academic_semester_id` bigint unsigned DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `event_type` enum('holiday','exam','lecture','registration','meeting','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_holiday` tinyint(1) NOT NULL DEFAULT '0',
  `is_all_day` tinyint(1) NOT NULL DEFAULT '0',
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `ace_school_year_semester_title_start_unique` (`school_id`,`academic_year_id`,`academic_semester_id`,`title`,`start_date`),
  KEY `academic_calendar_events_school_index` (`school_id`) USING BTREE,
  KEY `academic_calendar_events_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `academic_calendar_events_type_index` (`event_type`) USING BTREE,
  KEY `academic_calendar_events_dates_index` (`start_date`,`end_date`) USING BTREE,
  KEY `academic_calendar_events_is_holiday_index` (`is_holiday`) USING BTREE,
  KEY `academic_calendar_events_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `academic_calendar_events_created_by_index` (`created_by`) USING BTREE,
  KEY `academic_calendar_events_updated_by_index` (`updated_by`) USING BTREE,
  KEY `academic_calendar_events_semester_index` (`academic_semester_id`),
  CONSTRAINT `academic_calendar_events_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `academic_calendar_events_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `academic_calendar_events_semester_id_foreign` FOREIGN KEY (`academic_semester_id`) REFERENCES `academic_semesters` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `academic_semesters`
-- ----------------------------
DROP TABLE IF EXISTS `academic_semesters`;
CREATE TABLE `academic_semesters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('ganjil','genap','pendek') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ganjil',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `krs_start_date` date DEFAULT NULL,
  `krs_end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `acad_sem_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `acad_sem_year_index` (`academic_year_id`) USING BTREE,
  KEY `acad_sem_org_fk` (`organization_id`) USING BTREE,
  KEY `acad_sem_deleted_at_index` (`deleted_at`),
  CONSTRAINT `acad_sem_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `acad_sem_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `acad_sem_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `academic_years`
-- ----------------------------
DROP TABLE IF EXISTS `academic_years`;
CREATE TABLE `academic_years` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint DEFAULT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_registration_open` tinyint(1) DEFAULT '0',
  `registration_start_date` date DEFAULT NULL,
  `registration_end_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_current` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `academic_years_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `idx_academic_years_date_range` (`start_date`,`end_date`) USING BTREE,
  KEY `idx_academic_years_is_current` (`is_current`) USING BTREE,
  KEY `idx_academic_years_is_active` (`is_active`) USING BTREE,
  KEY `idx_academic_years_deleted_at` (`deleted_at`) USING BTREE,
  CONSTRAINT `academic_years_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `class_levels`
-- ----------------------------
DROP TABLE IF EXISTS `class_levels`;
CREATE TABLE `class_levels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_order` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `class_levels_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `idx_class_levels_order` (`level_order`) USING BTREE,
  KEY `idx_class_levels_is_active` (`is_active`) USING BTREE,
  KEY `idx_class_levels_deleted_at` (`deleted_at`) USING BTREE,
  CONSTRAINT `class_levels_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `classrooms`
-- ----------------------------
DROP TABLE IF EXISTS `classrooms`;
CREATE TABLE `classrooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `class_level_id` bigint unsigned NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int DEFAULT '30',
  `academic_year_id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `classrooms_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `idx_classrooms_level` (`class_level_id`) USING BTREE,
  KEY `idx_classrooms_academic_year` (`academic_year_id`) USING BTREE,
  KEY `idx_classrooms_is_active` (`is_active`) USING BTREE,
  KEY `idx_classrooms_deleted_at` (`deleted_at`) USING BTREE,
  CONSTRAINT `classrooms_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `classrooms_level_fk` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `classrooms_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `course_prerequisites`
-- ----------------------------
DROP TABLE IF EXISTS `course_prerequisites`;
CREATE TABLE `course_prerequisites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `prerequisite_course_id` bigint unsigned NOT NULL,
  `min_grade` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'D',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `course_prereq_unique` (`course_id`,`prerequisite_course_id`) USING BTREE,
  KEY `course_prereq_target_index` (`prerequisite_course_id`) USING BTREE,
  CONSTRAINT `course_prereq_source_fk` FOREIGN KEY (`course_id`) REFERENCES `curriculum_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `course_prereq_target_fk` FOREIGN KEY (`prerequisite_course_id`) REFERENCES `curriculum_courses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `courses`
-- ----------------------------
DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `thumbnail_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `level` enum('BEGINNER','INTERMEDIATE','ADVANCED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_hours` int DEFAULT '0',
  `is_published` tinyint(1) DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `enrollment_count` int DEFAULT '0',
  `rating` decimal(3,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `courses_org_slug_unique` (`organization_id`,`slug`) USING BTREE,
  KEY `idx_courses_teacher` (`teacher_id`) USING BTREE,
  KEY `idx_courses_subject` (`subject_id`) USING BTREE,
  KEY `idx_courses_is_published` (`is_published`) USING BTREE,
  KEY `idx_courses_deleted_at` (`deleted_at`) USING BTREE,
  CONSTRAINT `courses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `courses_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `courses_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `curriculum_assignments`
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_assignments`;
CREATE TABLE `curriculum_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `curriculum_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `class_level_id` bigint unsigned DEFAULT NULL,
  `academic_year_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ca_curriculum_index` (`curriculum_id`),
  KEY `ca_school_index` (`school_id`),
  KEY `ca_class_level_index` (`class_level_id`),
  KEY `ca_academic_year_index` (`academic_year_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `curriculum_competencies`
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_competencies`;
CREATE TABLE `curriculum_competencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `curriculum_phase_id` bigint unsigned NOT NULL,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `element` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cc_phase_index` (`curriculum_phase_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `curriculum_courses`
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_courses`;
CREATE TABLE `curriculum_courses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `study_program_id` bigint unsigned NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sks_theory` tinyint unsigned NOT NULL DEFAULT '2',
  `sks_practical` tinyint unsigned NOT NULL DEFAULT '0',
  `total_sks` tinyint unsigned GENERATED ALWAYS AS ((`sks_theory` + `sks_practical`)) STORED,
  `semester_recommendation` tinyint unsigned NOT NULL DEFAULT '1',
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '1',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `syllabus_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `courses_prodi_code_unique` (`study_program_id`,`code`) USING BTREE,
  KEY `courses_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `courses_prodi_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `curriculum_learning_outcomes`
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_learning_outcomes`;
CREATE TABLE `curriculum_learning_outcomes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `curriculum_phase_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `element` enum('sikap','pengetahuan','keterampilan','spiritual','sosial') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pengetahuan',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `curriculum_learning_outcomes_unique` (`curriculum_phase_id`,`subject_id`,`code`) USING BTREE,
  KEY `curriculum_learning_outcomes_school_index` (`school_id`) USING BTREE,
  KEY `curriculum_learning_outcomes_subject_index` (`subject_id`) USING BTREE,
  KEY `curriculum_learning_outcomes_is_active_index` (`is_active`) USING BTREE,
  KEY `curriculum_learning_outcomes_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `curriculum_learning_outcomes_created_by_index` (`created_by`) USING BTREE,
  KEY `curriculum_learning_outcomes_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `curriculum_learning_outcomes_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_learning_outcomes_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_learning_outcomes_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `curriculum_objectives`
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_objectives`;
CREATE TABLE `curriculum_objectives` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `learning_outcome_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `criteria` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `curriculum_objectives_unique` (`learning_outcome_id`,`code`) USING BTREE,
  KEY `curriculum_objectives_school_index` (`school_id`) USING BTREE,
  KEY `curriculum_objectives_is_active_index` (`is_active`) USING BTREE,
  KEY `curriculum_objectives_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `curriculum_objectives_created_by_index` (`created_by`) USING BTREE,
  KEY `curriculum_objectives_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `curriculum_objectives_learning_outcome_id_foreign` FOREIGN KEY (`learning_outcome_id`) REFERENCES `curriculum_learning_outcomes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_objectives_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `curriculum_phase_class_level`
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_phase_class_level`;
CREATE TABLE `curriculum_phase_class_level` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `curriculum_phase_id` bigint unsigned NOT NULL,
  `class_level_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpcl_unique` (`curriculum_phase_id`,`class_level_id`),
  KEY `cpcl_class_level_fk` (`class_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `curriculum_phases`
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_phases`;
CREATE TABLE `curriculum_phases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `curriculum_id` bigint unsigned DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `age_range` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_levels` json DEFAULT NULL,
  `competencies` json DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `curriculum_phases_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `curriculum_phases_school_index` (`school_id`) USING BTREE,
  KEY `curriculum_phases_curriculum_index` (`curriculum_id`) USING BTREE,
  KEY `curriculum_phases_is_active_index` (`is_active`) USING BTREE,
  KEY `curriculum_phases_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `curriculum_phases_created_by_index` (`created_by`) USING BTREE,
  KEY `curriculum_phases_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `curriculum_phases_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `curriculum_phases_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `curriculum_structures`
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_structures`;
CREATE TABLE `curriculum_structures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned DEFAULT NULL,
  `class_level_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `curriculum_phase_id` bigint unsigned DEFAULT NULL,
  `curriculum_id` bigint unsigned DEFAULT NULL,
  `semester` tinyint NOT NULL DEFAULT '1',
  `hours_per_week` int DEFAULT NULL,
  `hour_duration` int DEFAULT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT '1',
  `min_student` int DEFAULT NULL,
  `max_student` int DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `curriculum_structures_unique` (`school_id`,`academic_year_id`,`class_level_id`,`subject_id`,`semester`) USING BTREE,
  KEY `curriculum_structures_school_index` (`school_id`) USING BTREE,
  KEY `curriculum_structures_org_index` (`organization_id`) USING BTREE,
  KEY `curriculum_structures_class_level_index` (`class_level_id`) USING BTREE,
  KEY `curriculum_structures_subject_index` (`subject_id`) USING BTREE,
  KEY `curriculum_structures_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `curriculum_structures_phase_index` (`curriculum_phase_id`) USING BTREE,
  KEY `curriculum_structures_curriculum_index` (`curriculum_id`) USING BTREE,
  KEY `curriculum_structures_is_active_index` (`is_active`) USING BTREE,
  KEY `curriculum_structures_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `curriculum_structures_created_by_index` (`created_by`) USING BTREE,
  KEY `curriculum_structures_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `curriculum_structures_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_org_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `curriculum_subjects`
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_subjects`;
CREATE TABLE `curriculum_subjects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `curriculum_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('umum','khusus','muatan_lokal','peminatan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'umum',
  `is_required` tinyint(1) NOT NULL DEFAULT '1',
  `is_elective` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `curriculum_subjects_unique` (`curriculum_id`,`subject_id`) USING BTREE,
  KEY `curriculum_subjects_school_index` (`school_id`) USING BTREE,
  KEY `curriculum_subjects_subject_index` (`subject_id`) USING BTREE,
  KEY `curriculum_subjects_is_active_index` (`is_active`) USING BTREE,
  KEY `curriculum_subjects_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `curriculum_subjects_created_by_index` (`created_by`) USING BTREE,
  KEY `curriculum_subjects_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `curriculum_subjects_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_subjects_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_subjects_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `curriculums`
-- ----------------------------
DROP TABLE IF EXISTS `curriculums`;
CREATE TABLE `curriculums` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('national','international','local','hybrid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'national',
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_year` year DEFAULT NULL,
  `end_year` year DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `curriculums_org_code_unique` (`organization_id`,`code`),
  UNIQUE KEY `curriculums_org_name_unique` (`organization_id`,`name`),
  KEY `curriculums_org_index` (`organization_id`),
  KEY `curriculums_type_index` (`type`),
  KEY `curriculums_is_active_index` (`is_active`),
  KEY `curriculums_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `education_levels`
-- ----------------------------
DROP TABLE IF EXISTS `education_levels`;
CREATE TABLE `education_levels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `stage` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `duration_years` tinyint unsigned DEFAULT NULL,
  `min_age` tinyint unsigned DEFAULT NULL,
  `max_age` tinyint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `education_levels_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `education_levels_org_active_sort_index` (`organization_id`,`is_active`,`sort_order`) USING BTREE,
  KEY `education_levels_stage_index` (`stage`) USING BTREE,
  KEY `education_levels_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `education_levels_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `education_levels_stage_check` CHECK ((`stage` in (_utf8mb4'paud',_utf8mb4'dasar',_utf8mb4'menengah',_utf8mb4'tinggi')))
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `enrollment_conversions`
-- ----------------------------
DROP TABLE IF EXISTS `enrollment_conversions`;
CREATE TABLE `enrollment_conversions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `admission_id` bigint unsigned NOT NULL,
  `converted_at` timestamp NULL DEFAULT NULL,
  `converted_by` bigint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `enrollment_conversions_applicant_unique` (`applicant_id`) USING BTREE,
  UNIQUE KEY `enrollment_conversions_student_unique` (`student_id`) USING BTREE,
  KEY `enrollment_conversions_school_index` (`school_id`) USING BTREE,
  KEY `enrollment_conversions_admission_index` (`admission_id`) USING BTREE,
  KEY `enrollment_conversions_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `enrollment_conversions_created_by_index` (`created_by`) USING BTREE,
  KEY `enrollment_conversions_updated_by_index` (`updated_by`) USING BTREE,
  KEY `enrollment_conversions_converted_by_foreign` (`converted_by`) USING BTREE,
  CONSTRAINT `enrollment_conversions_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `enrollment_conversions_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `enrollment_conversions_converted_by_foreign` FOREIGN KEY (`converted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `enrollment_conversions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `enrollment_conversions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `faculties`
-- ----------------------------
DROP TABLE IF EXISTS `faculties`;
CREATE TABLE `faculties` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dean_id` bigint unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `faculties_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `faculties_school_index` (`school_id`) USING BTREE,
  KEY `faculties_dean_index` (`dean_id`) USING BTREE,
  KEY `faculties_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `faculties_dean_fk` FOREIGN KEY (`dean_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `faculties_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `faculties_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `learning_materials`
-- ----------------------------
DROP TABLE IF EXISTS `learning_materials`;
CREATE TABLE `learning_materials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `lesson_plan_id` bigint unsigned DEFAULT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `class_level_id` bigint unsigned DEFAULT NULL,
  `curriculum_phase_id` bigint unsigned DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint DEFAULT NULL,
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_index` int NOT NULL DEFAULT '0',
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `learning_materials_school_index` (`school_id`) USING BTREE,
  KEY `learning_materials_lesson_plan_index` (`lesson_plan_id`) USING BTREE,
  KEY `learning_materials_subject_index` (`subject_id`) USING BTREE,
  KEY `learning_materials_class_level_index` (`class_level_id`) USING BTREE,
  KEY `learning_materials_phase_index` (`curriculum_phase_id`) USING BTREE,
  KEY `learning_materials_type_index` (`type`) USING BTREE,
  KEY `learning_materials_order_index` (`order_index`) USING BTREE,
  KEY `learning_materials_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `learning_materials_created_by_index` (`created_by`) USING BTREE,
  KEY `learning_materials_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `learning_materials_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `learning_materials_lesson_plan_id_foreign` FOREIGN KEY (`lesson_plan_id`) REFERENCES `lesson_plans` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `learning_materials_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `learning_materials_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `learning_materials_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `learning_outcomes`
-- ----------------------------
DROP TABLE IF EXISTS `learning_outcomes`;
CREATE TABLE `learning_outcomes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned DEFAULT NULL,
  `curriculum_phase_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `element` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `competency` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_index` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `learning_outcomes_school_index` (`school_id`) USING BTREE,
  KEY `learning_outcomes_phase_index` (`curriculum_phase_id`) USING BTREE,
  KEY `learning_outcomes_subject_index` (`subject_id`) USING BTREE,
  KEY `learning_outcomes_element_index` (`element`) USING BTREE,
  KEY `learning_outcomes_code_index` (`code`) USING BTREE,
  KEY `learning_outcomes_order_index` (`order_index`) USING BTREE,
  KEY `learning_outcomes_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `learning_outcomes_created_by_index` (`created_by`) USING BTREE,
  KEY `learning_outcomes_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `learning_outcomes_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `learning_outcomes_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `learning_outcomes_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `lesson_plans`
-- ----------------------------
DROP TABLE IF EXISTS `lesson_plans`;
CREATE TABLE `lesson_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `class_level_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `curriculum_phase_id` bigint unsigned DEFAULT NULL,
  `period_id` bigint unsigned DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `learning_objectives` json DEFAULT NULL,
  `learning_outcomes` json DEFAULT NULL,
  `activities` json DEFAULT NULL,
  `assessment_plan` json DEFAULT NULL,
  `media` json DEFAULT NULL,
  `resources` json DEFAULT NULL,
  `duration_minutes` int DEFAULT NULL,
  `meeting_number` int DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `version` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `lesson_plans_school_index` (`school_id`) USING BTREE,
  KEY `lesson_plans_teacher_index` (`teacher_id`) USING BTREE,
  KEY `lesson_plans_subject_index` (`subject_id`) USING BTREE,
  KEY `lesson_plans_class_level_index` (`class_level_id`) USING BTREE,
  KEY `lesson_plans_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `lesson_plans_phase_index` (`curriculum_phase_id`) USING BTREE,
  KEY `lesson_plans_period_index` (`period_id`) USING BTREE,
  KEY `lesson_plans_status_index` (`status`) USING BTREE,
  KEY `lesson_plans_approved_by_index` (`approved_by`) USING BTREE,
  KEY `lesson_plans_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `lesson_plans_created_by_index` (`created_by`) USING BTREE,
  KEY `lesson_plans_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `lesson_plans_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lesson_plans_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `lesson_progress`
-- ----------------------------
DROP TABLE IF EXISTS `lesson_progress`;
CREATE TABLE `lesson_progress` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `lesson_id` bigint unsigned NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `progress_percent` int NOT NULL DEFAULT '0',
  `last_position_seconds` int NOT NULL DEFAULT '0',
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `lesson_progress_student_lesson_unique` (`student_id`,`lesson_id`) USING BTREE,
  KEY `lesson_progress_student_index` (`student_id`) USING BTREE,
  KEY `lesson_progress_lesson_index` (`lesson_id`) USING BTREE,
  KEY `lesson_progress_completed_index` (`is_completed`) USING BTREE,
  KEY `lesson_progress_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `lesson_progress_created_by_index` (`created_by`) USING BTREE,
  KEY `lesson_progress_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `lesson_progress_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lesson_progress_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `lessons`
-- ----------------------------
DROP TABLE IF EXISTS `lessons`;
CREATE TABLE `lessons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `video_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `duration_minutes` int DEFAULT '0',
  `order_index` int NOT NULL,
  `is_free_preview` tinyint(1) DEFAULT '0',
  `is_published` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_lessons_course` (`course_id`) USING BTREE,
  KEY `idx_lessons_order` (`order_index`) USING BTREE,
  KEY `idx_lessons_deleted_at` (`deleted_at`) USING BTREE,
  CONSTRAINT `lessons_course_fk` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `mastery_tracking`
-- ----------------------------
DROP TABLE IF EXISTS `mastery_tracking`;
CREATE TABLE `mastery_tracking` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `curriculum_objective_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `status` enum('not_started','in_progress','mastered','needs_review') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_started',
  `mastered_at` date DEFAULT NULL,
  `observation_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `mastery_tracking_unique` (`student_id`,`curriculum_objective_id`,`academic_year_id`) USING BTREE,
  KEY `mastery_tracking_school_index` (`school_id`) USING BTREE,
  KEY `mastery_tracking_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `mastery_tracking_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `mastery_tracking_objective_id_foreign` (`curriculum_objective_id`) USING BTREE,
  CONSTRAINT `mastery_tracking_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mastery_tracking_objective_id_foreign` FOREIGN KEY (`curriculum_objective_id`) REFERENCES `curriculum_objectives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mastery_tracking_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mastery_tracking_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `periods`
-- ----------------------------
DROP TABLE IF EXISTS `periods`;
CREATE TABLE `periods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `academic_year_id` bigint unsigned NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `semester` int unsigned DEFAULT NULL,
  `weight` int DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `grading_start` date DEFAULT NULL,
  `grading_end` date DEFAULT NULL,
  `assessment_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `semester_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_periods_year_code` (`academic_year_id`,`code`) USING BTREE,
  KEY `created_by` (`created_by`) USING BTREE,
  KEY `updated_by` (`updated_by`) USING BTREE,
  KEY `idx_periods_academic_year` (`academic_year_id`) USING BTREE,
  KEY `idx_periods_date_range` (`start_date`,`end_date`) USING BTREE,
  CONSTRAINT `periods_ibfk_1` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `periods_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `periods_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `chk_periods_semester` CHECK ((`semester` in (1,2,3,4))),
  CONSTRAINT `chk_periods_type` CHECK ((`type` in (_utf8mb4'SEMESTER',_utf8mb4'QUARTER',_utf8mb4'TERM')))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `promotion_approvals`
-- ----------------------------
DROP TABLE IF EXISTS `promotion_approvals`;
CREATE TABLE `promotion_approvals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `promotion_id` bigint unsigned NOT NULL,
  `approver_id` bigint unsigned NOT NULL,
  `approver_role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `approval_level` int unsigned DEFAULT '1',
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `promotion_id` (`promotion_id`) USING BTREE,
  CONSTRAINT `promotion_approvals_ibfk_1` FOREIGN KEY (`promotion_id`) REFERENCES `student_promotions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `promotion_batches`
-- ----------------------------
DROP TABLE IF EXISTS `promotion_batches`;
CREATE TABLE `promotion_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_academic_year_id` bigint unsigned NOT NULL,
  `to_academic_year_id` bigint unsigned NOT NULL,
  `total_students` int unsigned DEFAULT '0',
  `processed_count` int unsigned DEFAULT '0',
  `promoted_count` int unsigned DEFAULT '0',
  `retained_count` int unsigned DEFAULT '0',
  `graduated_count` int unsigned DEFAULT '0',
  `status` enum('draft','processing','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `code` (`code`) USING BTREE,
  KEY `from_academic_year_id` (`from_academic_year_id`) USING BTREE,
  KEY `to_academic_year_id` (`to_academic_year_id`) USING BTREE,
  CONSTRAINT `promotion_batches_ibfk_1` FOREIGN KEY (`from_academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `promotion_batches_ibfk_2` FOREIGN KEY (`to_academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `promotion_criteria`
-- ----------------------------
DROP TABLE IF EXISTS `promotion_criteria`;
CREATE TABLE `promotion_criteria` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `academic_year_id` bigint unsigned NOT NULL,
  `class_level_id` bigint unsigned DEFAULT NULL,
  `min_final_score` decimal(5,2) DEFAULT NULL,
  `min_attendance_rate` decimal(5,2) DEFAULT NULL,
  `min_behavior_score` decimal(5,2) DEFAULT NULL,
  `max_failed_subjects` int unsigned DEFAULT '0',
  `auto_promote` tinyint(1) DEFAULT '1',
  `allow_manual_override` tinyint(1) DEFAULT '1',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `academic_year_id` (`academic_year_id`) USING BTREE,
  KEY `class_level_id` (`class_level_id`) USING BTREE,
  CONSTRAINT `promotion_criteria_ibfk_1` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `promotion_criteria_ibfk_2` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `schedules`
-- ----------------------------
DROP TABLE IF EXISTS `schedules`;
CREATE TABLE `schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `classroom_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `day_of_week` enum('MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `room_id` bigint unsigned DEFAULT NULL,
  `generation_id` bigint unsigned DEFAULT NULL,
  `is_locked` tinyint(1) DEFAULT '0',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_schedules_classroom` (`classroom_id`) USING BTREE,
  KEY `idx_schedules_teacher` (`teacher_id`) USING BTREE,
  KEY `idx_schedules_day_time` (`day_of_week`,`start_time`) USING BTREE,
  KEY `idx_schedules_academic_year` (`academic_year_id`) USING BTREE,
  KEY `idx_schedules_deleted_at` (`deleted_at`) USING BTREE,
  KEY `schedules_subject_fk` (`subject_id`) USING BTREE,
  KEY `schedules_room_index` (`room_id`) USING BTREE,
  KEY `schedules_generation_index` (`generation_id`) USING BTREE,
  CONSTRAINT `schedules_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `schedules_classroom_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `schedules_generation_id_foreign` FOREIGN KEY (`generation_id`) REFERENCES `timetable_generations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `schedules_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `timetable_rooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `schedules_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `schedules_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=336 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_classrooms`
-- ----------------------------
DROP TABLE IF EXISTS `student_classrooms`;
CREATE TABLE `student_classrooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `classroom_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `enrollment_date` date DEFAULT NULL,
  `status` enum('ACTIVE','TRANSFERRED','DROPPED','GRADUATED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `student_classrooms_unique` (`student_id`,`classroom_id`,`academic_year_id`) USING BTREE,
  KEY `idx_student_classrooms_student` (`student_id`) USING BTREE,
  KEY `idx_student_classrooms_classroom` (`classroom_id`) USING BTREE,
  KEY `idx_student_classrooms_academic_year` (`academic_year_id`) USING BTREE,
  KEY `idx_student_classrooms_deleted_at` (`deleted_at`) USING BTREE,
  CONSTRAINT `student_classrooms_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `student_classrooms_classroom_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `student_classrooms_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_curriculums`
-- ----------------------------
DROP TABLE IF EXISTS `student_curriculums`;
CREATE TABLE `student_curriculums` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `curriculum_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `class_level_id` bigint unsigned NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `student_curriculums_unique` (`student_id`,`academic_year_id`) USING BTREE,
  KEY `student_curriculums_school_index` (`school_id`) USING BTREE,
  KEY `student_curriculums_curriculum_index` (`curriculum_id`) USING BTREE,
  KEY `student_curriculums_class_level_index` (`class_level_id`) USING BTREE,
  KEY `student_curriculums_is_active_index` (`is_active`) USING BTREE,
  KEY `student_curriculums_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `student_curriculums_created_by_index` (`created_by`) USING BTREE,
  KEY `student_curriculums_updated_by_index` (`updated_by`) USING BTREE,
  KEY `student_curriculums_academic_year_id_foreign` (`academic_year_id`) USING BTREE,
  CONSTRAINT `student_curriculums_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_curriculums_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_curriculums_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_curriculums_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_curriculums_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_krs`
-- ----------------------------
DROP TABLE IF EXISTS `student_krs`;
CREATE TABLE `student_krs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `academic_semester_id` bigint unsigned NOT NULL,
  `study_program_id` bigint unsigned NOT NULL,
  `academic_advisor_id` bigint unsigned DEFAULT NULL,
  `total_sks` tinyint unsigned NOT NULL DEFAULT '0',
  `max_allowed_sks` tinyint unsigned NOT NULL DEFAULT '24',
  `status` enum('draft','submitted','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `student_krs_sem_unique` (`student_id`,`academic_semester_id`) USING BTREE,
  KEY `student_krs_sem_index` (`academic_semester_id`) USING BTREE,
  KEY `student_krs_prodi_index` (`study_program_id`) USING BTREE,
  KEY `student_krs_advisor_index` (`academic_advisor_id`) USING BTREE,
  CONSTRAINT `student_krs_advisor_fk` FOREIGN KEY (`academic_advisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_krs_prodi_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `student_krs_sem_fk` FOREIGN KEY (`academic_semester_id`) REFERENCES `academic_semesters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_krs_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_krs_items`
-- ----------------------------
DROP TABLE IF EXISTS `student_krs_items`;
CREATE TABLE `student_krs_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_krs_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `classroom_id` bigint unsigned DEFAULT NULL,
  `sks` tinyint unsigned NOT NULL,
  `grade_numeric` decimal(5,2) DEFAULT NULL,
  `grade_letter` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grade_point` decimal(3,2) DEFAULT NULL,
  `is_passed` tinyint(1) DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `krs_items_unique` (`student_krs_id`,`course_id`) USING BTREE,
  KEY `krs_items_course_index` (`course_id`) USING BTREE,
  KEY `krs_items_class_index` (`classroom_id`) USING BTREE,
  CONSTRAINT `krs_items_class_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `krs_items_course_fk` FOREIGN KEY (`course_id`) REFERENCES `curriculum_courses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `krs_items_krs_fk` FOREIGN KEY (`student_krs_id`) REFERENCES `student_krs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_learning_plans`
-- ----------------------------
DROP TABLE IF EXISTS `student_learning_plans`;
CREATE TABLE `student_learning_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `curriculum_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `age_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan_data` json DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `student_learning_plans_school_index` (`school_id`) USING BTREE,
  KEY `student_learning_plans_student_index` (`student_id`) USING BTREE,
  KEY `student_learning_plans_curriculum_index` (`curriculum_id`) USING BTREE,
  KEY `student_learning_plans_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `student_learning_plans_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_learning_plans_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_learning_plans_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_promotions`
-- ----------------------------
DROP TABLE IF EXISTS `student_promotions`;
CREATE TABLE `student_promotions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `from_classroom_id` bigint unsigned DEFAULT NULL,
  `to_classroom_id` bigint unsigned DEFAULT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `decision` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `decision_date` date DEFAULT NULL,
  `meeting_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `final_score_average` decimal(5,2) DEFAULT NULL,
  `attendance_percentage` decimal(5,2) DEFAULT NULL,
  `decided_by` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `student_promotions_student_year_unique` (`student_id`,`academic_year_id`) USING BTREE,
  KEY `student_promotions_student_index` (`student_id`) USING BTREE,
  KEY `student_promotions_school_index` (`school_id`) USING BTREE,
  KEY `student_promotions_from_classroom_index` (`from_classroom_id`) USING BTREE,
  KEY `student_promotions_to_classroom_index` (`to_classroom_id`) USING BTREE,
  KEY `student_promotions_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `student_promotions_decision_index` (`decision`) USING BTREE,
  KEY `student_promotions_decided_by_index` (`decided_by`) USING BTREE,
  KEY `student_promotions_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `student_promotions_created_by_index` (`created_by`) USING BTREE,
  KEY `student_promotions_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `student_promotions_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_decided_by_foreign` FOREIGN KEY (`decided_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_from_classroom_id_foreign` FOREIGN KEY (`from_classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_to_classroom_id_foreign` FOREIGN KEY (`to_classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `study_programs`
-- ----------------------------
DROP TABLE IF EXISTS `study_programs`;
CREATE TABLE `study_programs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `faculty_id` bigint unsigned DEFAULT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `degree_level` enum('D3','D4','S1','S2','S3','profesi','spesialis') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S1',
  `accreditation` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Unggul',
  `head_of_program_id` bigint unsigned DEFAULT NULL,
  `total_sks_required` int unsigned NOT NULL DEFAULT '144',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `study_programs_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `study_prog_school_index` (`school_id`) USING BTREE,
  KEY `study_prog_faculty_index` (`faculty_id`) USING BTREE,
  KEY `study_prog_head_index` (`head_of_program_id`) USING BTREE,
  KEY `study_prog_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `study_prog_faculty_fk` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `study_prog_head_fk` FOREIGN KEY (`head_of_program_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `study_prog_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `study_prog_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `subject_categories`
-- ----------------------------
DROP TABLE IF EXISTS `subject_categories`;
CREATE TABLE `subject_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` int DEFAULT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `subj_cats_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `idx_subj_cats_is_active` (`is_active`) USING BTREE,
  KEY `idx_subj_cats_deleted_at` (`deleted_at`) USING BTREE,
  CONSTRAINT `subj_cats_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `subjects`
-- ----------------------------
DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `parent_id` int DEFAULT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` enum('CORE','ELECTIVE','EXTRACURRICULAR','RELIGION','LOCAL_CONTENT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `subjects_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `idx_subjects_category` (`category`) USING BTREE,
  KEY `idx_subjects_is_active` (`is_active`) USING BTREE,
  KEY `idx_subjects_deleted_at` (`deleted_at`) USING BTREE,
  CONSTRAINT `subjects_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `submissions`
-- ----------------------------
DROP TABLE IF EXISTS `submissions`;
CREATE TABLE `submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `assignment_quiz_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `submission_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file_urls` json DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `graded_by` bigint unsigned DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SUBMITTED',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `submissions_assignment_student_unique` (`assignment_quiz_id`,`student_id`) USING BTREE,
  KEY `submissions_assignment_index` (`assignment_quiz_id`) USING BTREE,
  KEY `submissions_student_index` (`student_id`) USING BTREE,
  KEY `submissions_status_index` (`status`) USING BTREE,
  KEY `submissions_graded_by_index` (`graded_by`) USING BTREE,
  KEY `submissions_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `submissions_created_by_index` (`created_by`) USING BTREE,
  KEY `submissions_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `submissions_assignment_quiz_id_foreign` FOREIGN KEY (`assignment_quiz_id`) REFERENCES `assignments_quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `submissions_graded_by_foreign` FOREIGN KEY (`graded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `submissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `thesis_supervisions`
-- ----------------------------
DROP TABLE IF EXISTS `thesis_supervisions`;
CREATE TABLE `thesis_supervisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `study_program_id` bigint unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supervisor_1_id` bigint unsigned NOT NULL,
  `supervisor_2_id` bigint unsigned DEFAULT NULL,
  `examiner_1_id` bigint unsigned DEFAULT NULL,
  `examiner_2_id` bigint unsigned DEFAULT NULL,
  `status` enum('proposed','in_progress','seminar_proposal','defense_scheduled','completed','revising') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'proposed',
  `defense_date` date DEFAULT NULL,
  `final_score` decimal(5,2) DEFAULT NULL,
  `grade_letter` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manuscript_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `thesis_student_index` (`student_id`) USING BTREE,
  KEY `thesis_prodi_index` (`study_program_id`) USING BTREE,
  KEY `thesis_sup1_index` (`supervisor_1_id`) USING BTREE,
  KEY `thesis_sup2_index` (`supervisor_2_id`) USING BTREE,
  CONSTRAINT `thesis_prodi_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `thesis_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `thesis_sup1_fk` FOREIGN KEY (`supervisor_1_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `thesis_sup2_fk` FOREIGN KEY (`supervisor_2_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `timetable_constraints`
-- ----------------------------
DROP TABLE IF EXISTS `timetable_constraints`;
CREATE TABLE `timetable_constraints` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `day_of_week` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_slot_start` time DEFAULT NULL,
  `time_slot_end` time DEFAULT NULL,
  `constraint_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `timetable_constraints_school_index` (`school_id`) USING BTREE,
  KEY `timetable_constraints_teacher_index` (`teacher_id`) USING BTREE,
  KEY `timetable_constraints_day_index` (`day_of_week`) USING BTREE,
  KEY `timetable_constraints_type_index` (`constraint_type`) USING BTREE,
  KEY `timetable_constraints_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `timetable_constraints_created_by_index` (`created_by`) USING BTREE,
  KEY `timetable_constraints_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `timetable_constraints_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `timetable_constraints_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `timetable_generations`
-- ----------------------------
DROP TABLE IF EXISTS `timetable_generations`;
CREATE TABLE `timetable_generations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `semester` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generation_date` timestamp NULL DEFAULT NULL,
  `algorithm_version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_conflicts` int NOT NULL DEFAULT '0',
  `total_assignments` int NOT NULL DEFAULT '0',
  `total_teachers` int NOT NULL DEFAULT '0',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GENERATED',
  `generation_data` json DEFAULT NULL,
  `generated_by` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `timetable_generations_school_index` (`school_id`) USING BTREE,
  KEY `timetable_generations_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `timetable_generations_status_index` (`status`) USING BTREE,
  KEY `timetable_generations_generated_by_index` (`generated_by`) USING BTREE,
  KEY `timetable_generations_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `timetable_generations_created_by_index` (`created_by`) USING BTREE,
  KEY `timetable_generations_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `timetable_generations_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `timetable_generations_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `timetable_generations_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `timetable_rooms`
-- ----------------------------
DROP TABLE IF EXISTS `timetable_rooms`;
CREATE TABLE `timetable_rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `room_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL DEFAULT '30',
  `room_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `floor_number` int DEFAULT NULL,
  `building` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facilities` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `timetable_rooms_school_code_unique` (`school_id`,`room_code`) USING BTREE,
  KEY `timetable_rooms_school_index` (`school_id`) USING BTREE,
  KEY `timetable_rooms_type_index` (`room_type`) USING BTREE,
  KEY `timetable_rooms_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `timetable_rooms_created_by_index` (`created_by`) USING BTREE,
  KEY `timetable_rooms_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `timetable_rooms_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
