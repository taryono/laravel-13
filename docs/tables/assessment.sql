/*
 * Table Group: assessment
 * Total Tables: 30
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `assessment_components`
-- ----------------------------
DROP TABLE IF EXISTS `assessment_components`;
CREATE TABLE `assessment_components` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `assessment_id` bigint unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` decimal(5,2) NOT NULL DEFAULT '0.00',
  `max_score` decimal(6,2) NOT NULL DEFAULT '100.00',
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `assessment_components_assessment_index` (`assessment_id`) USING BTREE,
  KEY `assessment_components_is_active_index` (`is_active`) USING BTREE,
  KEY `assessment_components_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `assessment_components_created_by_index` (`created_by`) USING BTREE,
  KEY `assessment_components_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `assessment_components_assessment_id_foreign` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `assessment_methods`
-- ----------------------------
DROP TABLE IF EXISTS `assessment_methods`;
CREATE TABLE `assessment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scale_type` enum('numeric','letter','rubric','descriptive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'numeric',
  `scale_config` json DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `assessment_methods_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `assessment_methods_school_index` (`school_id`) USING BTREE,
  KEY `assessment_methods_is_active_index` (`is_active`) USING BTREE,
  KEY `assessment_methods_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `assessment_methods_created_by_index` (`created_by`) USING BTREE,
  KEY `assessment_methods_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `assessment_methods_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `assessment_rubrics`
-- ----------------------------
DROP TABLE IF EXISTS `assessment_rubrics`;
CREATE TABLE `assessment_rubrics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `criteria` json NOT NULL,
  `levels` json NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `assessment_rubrics_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `assessment_rubrics_school_index` (`school_id`) USING BTREE,
  KEY `assessment_rubrics_is_active_index` (`is_active`) USING BTREE,
  KEY `assessment_rubrics_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `assessment_rubrics_created_by_index` (`created_by`) USING BTREE,
  KEY `assessment_rubrics_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `assessment_rubrics_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `assessment_types`
-- ----------------------------
DROP TABLE IF EXISTS `assessment_types`;
CREATE TABLE `assessment_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` enum('numeric','rubric','descriptive','letter') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'numeric',
  `weight_default` decimal(5,2) NOT NULL DEFAULT '0.00',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `assessment_types_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `assessment_types_school_index` (`school_id`) USING BTREE,
  KEY `assessment_types_is_active_index` (`is_active`) USING BTREE,
  KEY `assessment_types_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `assessment_types_created_by_index` (`created_by`) USING BTREE,
  KEY `assessment_types_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `assessment_types_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `assessments`
-- ----------------------------
DROP TABLE IF EXISTS `assessments`;
CREATE TABLE `assessments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `curriculum_structure_id` bigint unsigned NOT NULL,
  `assessment_type_id` bigint unsigned NOT NULL,
  `assessment_method_id` bigint unsigned DEFAULT NULL,
  `assessment_rubric_id` bigint unsigned DEFAULT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `semester` tinyint NOT NULL DEFAULT '1',
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `max_score` decimal(6,2) NOT NULL DEFAULT '100.00',
  `weight` decimal(5,2) NOT NULL DEFAULT '0.00',
  `assessment_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `assessments_unique` (`school_id`,`curriculum_structure_id`,`academic_year_id`,`semester`,`code`) USING BTREE,
  KEY `assessments_school_index` (`school_id`) USING BTREE,
  KEY `assessments_structure_index` (`curriculum_structure_id`) USING BTREE,
  KEY `assessments_type_index` (`assessment_type_id`) USING BTREE,
  KEY `assessments_method_index` (`assessment_method_id`) USING BTREE,
  KEY `assessments_rubric_index` (`assessment_rubric_id`) USING BTREE,
  KEY `assessments_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `assessments_is_published_index` (`is_published`) USING BTREE,
  KEY `assessments_is_active_index` (`is_active`) USING BTREE,
  KEY `assessments_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `assessments_created_by_index` (`created_by`) USING BTREE,
  KEY `assessments_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `assessments_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assessments_method_id_foreign` FOREIGN KEY (`assessment_method_id`) REFERENCES `assessment_methods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assessments_rubric_id_foreign` FOREIGN KEY (`assessment_rubric_id`) REFERENCES `assessment_rubrics` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assessments_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assessments_structure_id_foreign` FOREIGN KEY (`curriculum_structure_id`) REFERENCES `curriculum_structures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assessments_type_id_foreign` FOREIGN KEY (`assessment_type_id`) REFERENCES `assessment_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `generated_reports`
-- ----------------------------
DROP TABLE IF EXISTS `generated_reports`;
CREATE TABLE `generated_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `report_card_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `period_id` bigint unsigned NOT NULL,
  `report_card_template_id` bigint unsigned NOT NULL,
  `curriculum_id` bigint unsigned DEFAULT NULL,
  `version` int NOT NULL DEFAULT '1',
  `is_current` tinyint(1) NOT NULL DEFAULT '1',
  `pdf_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_data` json DEFAULT NULL,
  `generated_at` timestamp NULL DEFAULT NULL,
  `downloaded_count` int NOT NULL DEFAULT '0',
  `last_download_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `generated_reports_unique` (`report_card_id`,`version`) USING BTREE,
  KEY `generated_reports_school_index` (`school_id`) USING BTREE,
  KEY `generated_reports_student_index` (`student_id`) USING BTREE,
  KEY `generated_reports_period_index` (`period_id`) USING BTREE,
  KEY `generated_reports_template_index` (`report_card_template_id`) USING BTREE,
  KEY `generated_reports_curriculum_index` (`curriculum_id`) USING BTREE,
  KEY `generated_reports_is_current_index` (`is_current`) USING BTREE,
  KEY `generated_reports_is_active_index` (`is_active`) USING BTREE,
  KEY `generated_reports_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `generated_reports_created_by_index` (`created_by`) USING BTREE,
  KEY `generated_reports_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `generated_reports_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `generated_reports_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `report_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `generated_reports_report_card_id_foreign` FOREIGN KEY (`report_card_id`) REFERENCES `report_cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `generated_reports_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `generated_reports_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `generated_reports_template_id_foreign` FOREIGN KEY (`report_card_template_id`) REFERENCES `report_card_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `grade_boundaries`
-- ----------------------------
DROP TABLE IF EXISTS `grade_boundaries`;
CREATE TABLE `grade_boundaries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `curriculum_id` bigint unsigned NOT NULL,
  `assessment_id` bigint unsigned DEFAULT NULL,
  `academic_year_id` bigint unsigned DEFAULT NULL,
  `grade_letter` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_score` decimal(6,2) NOT NULL,
  `max_score` decimal(6,2) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `grade_boundaries_school_index` (`school_id`) USING BTREE,
  KEY `grade_boundaries_curriculum_index` (`curriculum_id`) USING BTREE,
  KEY `grade_boundaries_assessment_index` (`assessment_id`) USING BTREE,
  KEY `grade_boundaries_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `grade_boundaries_assessment_id_foreign` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_boundaries_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_boundaries_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `grade_scales`
-- ----------------------------
DROP TABLE IF EXISTS `grade_scales`;
CREATE TABLE `grade_scales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `curriculum_id` bigint unsigned DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_score` decimal(6,2) NOT NULL DEFAULT '0.00',
  `max_score` decimal(6,2) NOT NULL DEFAULT '100.00',
  `grade_letter` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grade_point` decimal(4,2) DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `grade_scales_unique` (`school_id`,`curriculum_id`,`code`,`min_score`) USING BTREE,
  KEY `grade_scales_school_index` (`school_id`) USING BTREE,
  KEY `grade_scales_curriculum_index` (`curriculum_id`) USING BTREE,
  KEY `grade_scales_is_active_index` (`is_active`) USING BTREE,
  KEY `grade_scales_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `grade_scales_created_by_index` (`created_by`) USING BTREE,
  KEY `grade_scales_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `grade_scales_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_scales_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `grade_weight_configs`
-- ----------------------------
DROP TABLE IF EXISTS `grade_weight_configs`;
CREATE TABLE `grade_weight_configs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `curriculum_id` bigint unsigned DEFAULT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `assessment_type_id` bigint unsigned NOT NULL,
  `weight` decimal(5,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `grade_weight_configs_unique` (`school_id`,`academic_year_id`,`assessment_type_id`,`curriculum_id`) USING BTREE,
  KEY `grade_weight_configs_school_index` (`school_id`) USING BTREE,
  KEY `grade_weight_configs_curriculum_index` (`curriculum_id`) USING BTREE,
  KEY `grade_weight_configs_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `grade_weight_configs_assessment_type_index` (`assessment_type_id`) USING BTREE,
  KEY `grade_weight_configs_is_active_index` (`is_active`) USING BTREE,
  KEY `grade_weight_configs_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `grade_weight_configs_created_by_index` (`created_by`) USING BTREE,
  KEY `grade_weight_configs_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `grade_weight_configs_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_weight_configs_assessment_type_id_foreign` FOREIGN KEY (`assessment_type_id`) REFERENCES `assessment_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_weight_configs_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_weight_configs_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `grades`
-- ----------------------------
DROP TABLE IF EXISTS `grades`;
CREATE TABLE `grades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_classroom_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `period_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `grade_letter` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `predicate` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `assessment_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assessment_category` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` int NOT NULL DEFAULT '1',
  `is_final` tinyint(1) NOT NULL DEFAULT '0',
  `is_counted_in_report` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `grades_unique` (`student_classroom_id`,`subject_id`,`period_id`,`assessment_type`) USING BTREE,
  KEY `grades_student_classroom_index` (`student_classroom_id`) USING BTREE,
  KEY `grades_subject_index` (`subject_id`) USING BTREE,
  KEY `grades_period_index` (`period_id`) USING BTREE,
  KEY `grades_teacher_index` (`teacher_id`) USING BTREE,
  KEY `grades_is_final_index` (`is_final`) USING BTREE,
  KEY `grades_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `grades_created_by_index` (`created_by`) USING BTREE,
  KEY `grades_updated_by_index` (`updated_by`) USING BTREE,
  KEY `grades_assessment_category_index` (`assessment_category`) USING BTREE,
  KEY `grades_is_counted_in_report_index` (`is_counted_in_report`) USING BTREE,
  CONSTRAINT `grades_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grades_student_classroom_id_foreign` FOREIGN KEY (`student_classroom_id`) REFERENCES `student_classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grades_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grades_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `report_builder_components`
-- ----------------------------
DROP TABLE IF EXISTS `report_builder_components`;
CREATE TABLE `report_builder_components` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `curriculum_id` bigint unsigned DEFAULT NULL,
  `academic_year_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `default_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `report_card_components`
-- ----------------------------
DROP TABLE IF EXISTS `report_card_components`;
CREATE TABLE `report_card_components` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_card_template_section_id` bigint unsigned NOT NULL,
  `component_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `config` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `report_card_components_section_index` (`report_card_template_section_id`) USING BTREE,
  KEY `report_card_components_is_active_index` (`is_active`) USING BTREE,
  KEY `report_card_components_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `report_card_components_created_by_index` (`created_by`) USING BTREE,
  KEY `report_card_components_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `report_card_components_section_id_foreign` FOREIGN KEY (`report_card_template_section_id`) REFERENCES `report_card_template_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `report_card_grades`
-- ----------------------------
DROP TABLE IF EXISTS `report_card_grades`;
CREATE TABLE `report_card_grades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `report_card_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `curriculum_structure_id` bigint unsigned DEFAULT NULL,
  `knowledge_score` decimal(6,2) DEFAULT NULL,
  `knowledge_predicate` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `knowledge_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `skill_score` decimal(6,2) DEFAULT NULL,
  `skill_predicate` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skill_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `final_score` decimal(6,2) DEFAULT NULL,
  `final_predicate` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rank` int DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `report_card_grades_unique` (`report_card_id`,`subject_id`) USING BTREE,
  KEY `report_card_grades_school_index` (`school_id`) USING BTREE,
  KEY `report_card_grades_subject_index` (`subject_id`) USING BTREE,
  KEY `report_card_grades_structure_index` (`curriculum_structure_id`) USING BTREE,
  KEY `report_card_grades_is_active_index` (`is_active`) USING BTREE,
  KEY `report_card_grades_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `report_card_grades_created_by_index` (`created_by`) USING BTREE,
  KEY `report_card_grades_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `report_card_grades_report_card_id_foreign` FOREIGN KEY (`report_card_id`) REFERENCES `report_cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_card_grades_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_card_grades_structure_id_foreign` FOREIGN KEY (`curriculum_structure_id`) REFERENCES `curriculum_structures` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `report_card_grades_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `report_card_notes`
-- ----------------------------
DROP TABLE IF EXISTS `report_card_notes`;
CREATE TABLE `report_card_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_card_id` bigint unsigned NOT NULL,
  `note_type` enum('wali_kelas','kepsek','orang_tua','lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `report_card_notes_report_index` (`report_card_id`) USING BTREE,
  KEY `report_card_notes_author_index` (`author_id`) USING BTREE,
  KEY `report_card_notes_is_active_index` (`is_active`) USING BTREE,
  KEY `report_card_notes_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `report_card_notes_created_by_index` (`created_by`) USING BTREE,
  KEY `report_card_notes_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `report_card_notes_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `report_card_notes_report_id_foreign` FOREIGN KEY (`report_card_id`) REFERENCES `report_cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `report_card_signatures`
-- ----------------------------
DROP TABLE IF EXISTS `report_card_signatures`;
CREATE TABLE `report_card_signatures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_card_id` bigint unsigned NOT NULL,
  `role` enum('wali_kelas','kepsek','orang_tua','lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `signed_at` timestamp NULL DEFAULT NULL,
  `signature_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `report_card_signatures_report_index` (`report_card_id`) USING BTREE,
  KEY `report_card_signatures_user_index` (`user_id`) USING BTREE,
  KEY `report_card_signatures_is_active_index` (`is_active`) USING BTREE,
  KEY `report_card_signatures_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `report_card_signatures_created_by_index` (`created_by`) USING BTREE,
  KEY `report_card_signatures_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `report_card_signatures_report_id_foreign` FOREIGN KEY (`report_card_id`) REFERENCES `report_cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_card_signatures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `report_card_template_curriculums`
-- ----------------------------
DROP TABLE IF EXISTS `report_card_template_curriculums`;
CREATE TABLE `report_card_template_curriculums` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_card_template_id` bigint unsigned NOT NULL,
  `curriculum_id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `report_card_template_curriculums_unique` (`report_card_template_id`,`curriculum_id`) USING BTREE,
  KEY `report_card_template_curriculums_curriculum_index` (`curriculum_id`) USING BTREE,
  KEY `report_card_template_curriculums_is_active_index` (`is_active`) USING BTREE,
  KEY `report_card_template_curriculums_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `report_card_template_curriculums_created_by_index` (`created_by`) USING BTREE,
  KEY `report_card_template_curriculums_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `report_card_template_curriculums_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_card_template_curriculums_template_id_foreign` FOREIGN KEY (`report_card_template_id`) REFERENCES `report_card_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `report_card_template_sections`
-- ----------------------------
DROP TABLE IF EXISTS `report_card_template_sections`;
CREATE TABLE `report_card_template_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_card_template_id` bigint unsigned NOT NULL,
  `section_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `config` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `report_card_template_sections_template_index` (`report_card_template_id`) USING BTREE,
  KEY `report_card_template_sections_is_active_index` (`is_active`) USING BTREE,
  KEY `report_card_template_sections_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `report_card_template_sections_created_by_index` (`created_by`) USING BTREE,
  KEY `report_card_template_sections_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `report_card_template_sections_template_id_foreign` FOREIGN KEY (`report_card_template_id`) REFERENCES `report_card_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `report_card_templates`
-- ----------------------------
DROP TABLE IF EXISTS `report_card_templates`;
CREATE TABLE `report_card_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_data` json NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `version` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `report_card_templates_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `report_card_templates_school_index` (`school_id`) USING BTREE,
  KEY `report_card_templates_is_default_index` (`is_default`) USING BTREE,
  KEY `report_card_templates_is_active_index` (`is_active`) USING BTREE,
  KEY `report_card_templates_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `report_card_templates_created_by_index` (`created_by`) USING BTREE,
  KEY `report_card_templates_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `report_card_templates_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `report_cards`
-- ----------------------------
DROP TABLE IF EXISTS `report_cards`;
CREATE TABLE `report_cards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `period_id` bigint unsigned NOT NULL,
  `class_level_id` bigint unsigned NOT NULL,
  `curriculum_id` bigint unsigned NOT NULL,
  `report_card_template_id` bigint unsigned NOT NULL,
  `student_curriculum_id` bigint unsigned DEFAULT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` tinyint NOT NULL DEFAULT '1',
  `status` enum('draft','published','locked','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `average_score` decimal(6,2) DEFAULT NULL,
  `total_score` decimal(8,2) DEFAULT NULL,
  `rank_in_class` int DEFAULT NULL,
  `rank_in_level` int DEFAULT NULL,
  `total_subjects` int NOT NULL DEFAULT '0',
  `homeroom_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `principal_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `homeroom_teacher_id` bigint unsigned DEFAULT NULL,
  `principal_id` bigint unsigned DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `report_cards_unique` (`student_id`,`period_id`) USING BTREE,
  UNIQUE KEY `report_cards_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `report_cards_school_index` (`school_id`) USING BTREE,
  KEY `report_cards_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `report_cards_period_index` (`period_id`) USING BTREE,
  KEY `report_cards_class_level_index` (`class_level_id`) USING BTREE,
  KEY `report_cards_curriculum_index` (`curriculum_id`) USING BTREE,
  KEY `report_cards_template_index` (`report_card_template_id`) USING BTREE,
  KEY `report_cards_student_curriculum_index` (`student_curriculum_id`) USING BTREE,
  KEY `report_cards_status_index` (`status`) USING BTREE,
  KEY `report_cards_is_active_index` (`is_active`) USING BTREE,
  KEY `report_cards_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `report_cards_created_by_index` (`created_by`) USING BTREE,
  KEY `report_cards_updated_by_index` (`updated_by`) USING BTREE,
  KEY `report_cards_homeroom_teacher_id_foreign` (`homeroom_teacher_id`) USING BTREE,
  KEY `report_cards_principal_id_foreign` (`principal_id`) USING BTREE,
  CONSTRAINT `report_cards_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_cards_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_cards_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_cards_homeroom_teacher_id_foreign` FOREIGN KEY (`homeroom_teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `report_cards_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `report_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_cards_principal_id_foreign` FOREIGN KEY (`principal_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `report_cards_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_cards_student_curriculum_id_foreign` FOREIGN KEY (`student_curriculum_id`) REFERENCES `student_curriculums` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `report_cards_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_cards_template_id_foreign` FOREIGN KEY (`report_card_template_id`) REFERENCES `report_card_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `report_data_sources`
-- ----------------------------
DROP TABLE IF EXISTS `report_data_sources`;
CREATE TABLE `report_data_sources` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL,
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'academic',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fields` json DEFAULT NULL,
  `sample_data` json DEFAULT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `report_data_sources_key_unique` (`key`),
  KEY `report_data_sources_organization_id_index` (`organization_id`),
  KEY `report_data_sources_category_index` (`category`),
  KEY `report_data_sources_is_active_index` (`is_active`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `report_periods`
-- ----------------------------
DROP TABLE IF EXISTS `report_periods`;
CREATE TABLE `report_periods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('mid','end','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'end',
  `semester` tinyint NOT NULL DEFAULT '1',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `report_periods_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `report_periods_school_index` (`school_id`) USING BTREE,
  KEY `report_periods_academic_year_index` (`academic_year_id`) USING BTREE,
  KEY `report_periods_is_active_index` (`is_active`) USING BTREE,
  KEY `report_periods_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `report_periods_created_by_index` (`created_by`) USING BTREE,
  KEY `report_periods_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `report_periods_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_periods_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `report_template_curriculums`
-- ----------------------------
DROP TABLE IF EXISTS `report_template_curriculums`;
CREATE TABLE `report_template_curriculums` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_template_id` bigint unsigned NOT NULL,
  `curriculum_id` bigint unsigned DEFAULT NULL,
  `academic_year_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `report_template_curriculums_report_template_id_foreign` (`report_template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `report_template_signatories`
-- ----------------------------
DROP TABLE IF EXISTS `report_template_signatories`;
CREATE TABLE `report_template_signatories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_template_id` bigint unsigned NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'headmaster',
  `label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_source` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'automatic',
  `teacher_id` bigint unsigned DEFAULT NULL,
  `manual_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manual_employee_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_image_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `report_template_signatories_report_template_id_foreign` (`report_template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `report_template_versions`
-- ----------------------------
DROP TABLE IF EXISTS `report_template_versions`;
CREATE TABLE `report_template_versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `template_id` bigint unsigned NOT NULL,
  `version` int NOT NULL DEFAULT '1',
  `configuration` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `report_template_versions_template_id_foreign` (`template_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `report_templates`
-- ----------------------------
DROP TABLE IF EXISTS `report_templates`;
CREATE TABLE `report_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'academic',
  `curriculum_id` bigint unsigned DEFAULT NULL,
  `academic_year_id` bigint unsigned DEFAULT NULL,
  `paper_size` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A4',
  `custom_paper_width` decimal(8,2) DEFAULT NULL,
  `custom_paper_height` decimal(8,2) DEFAULT NULL,
  `orientation` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'portrait',
  `margin_top` decimal(4,2) NOT NULL DEFAULT '2.00',
  `margin_right` decimal(4,2) NOT NULL DEFAULT '2.00',
  `margin_bottom` decimal(4,2) NOT NULL DEFAULT '2.00',
  `margin_left` decimal(4,2) NOT NULL DEFAULT '2.50',
  `configuration` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `compiled_html` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `compiled_css` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `student_assessments`
-- ----------------------------
DROP TABLE IF EXISTS `student_assessments`;
CREATE TABLE `student_assessments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `assessment_id` bigint unsigned NOT NULL,
  `assessment_component_id` bigint unsigned DEFAULT NULL,
  `student_id` bigint unsigned NOT NULL,
  `score` decimal(6,2) DEFAULT NULL,
  `grade_letter` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grade_point` decimal(4,2) DEFAULT NULL,
  `rubric_data` json DEFAULT NULL,
  `teacher_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `assessed_by` bigint unsigned DEFAULT NULL,
  `assessed_at` timestamp NULL DEFAULT NULL,
  `is_remedial` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `student_assessments_unique` (`assessment_id`,`assessment_component_id`,`student_id`) USING BTREE,
  KEY `student_assessments_school_index` (`school_id`) USING BTREE,
  KEY `student_assessments_student_index` (`student_id`) USING BTREE,
  KEY `student_assessments_component_index` (`assessment_component_id`) USING BTREE,
  KEY `student_assessments_assessed_by_index` (`assessed_by`) USING BTREE,
  KEY `student_assessments_is_remedial_index` (`is_remedial`) USING BTREE,
  KEY `student_assessments_is_active_index` (`is_active`) USING BTREE,
  KEY `student_assessments_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `student_assessments_created_by_index` (`created_by`) USING BTREE,
  KEY `student_assessments_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `student_assessments_assessed_by_foreign` FOREIGN KEY (`assessed_by`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_assessments_assessment_id_foreign` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_assessments_component_id_foreign` FOREIGN KEY (`assessment_component_id`) REFERENCES `assessment_components` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_assessments_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_assessments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_khs`
-- ----------------------------
DROP TABLE IF EXISTS `student_khs`;
CREATE TABLE `student_khs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `academic_semester_id` bigint unsigned NOT NULL,
  `sks_semester` tinyint unsigned NOT NULL DEFAULT '0',
  `sks_cumulative` smallint unsigned NOT NULL DEFAULT '0',
  `gpa_semester` decimal(3,2) NOT NULL DEFAULT '0.00',
  `gpa_cumulative` decimal(3,2) NOT NULL DEFAULT '0.00',
  `max_sks_next_semester` tinyint unsigned NOT NULL DEFAULT '20',
  `academic_status` enum('aktif','cuti','skorsing','do','lulus') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `printed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `student_khs_unique` (`student_id`,`academic_semester_id`) USING BTREE,
  KEY `student_khs_sem_index` (`academic_semester_id`) USING BTREE,
  CONSTRAINT `student_khs_sem_fk` FOREIGN KEY (`academic_semester_id`) REFERENCES `academic_semesters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_khs_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_outcome_achievements`
-- ----------------------------
DROP TABLE IF EXISTS `student_outcome_achievements`;
CREATE TABLE `student_outcome_achievements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `learning_outcome_id` bigint unsigned NOT NULL,
  `period_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `achievement_level` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `narrative` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `student_outcome_achievements_unique` (`student_id`,`learning_outcome_id`,`period_id`) USING BTREE,
  KEY `student_outcome_achievements_student_index` (`student_id`) USING BTREE,
  KEY `student_outcome_achievements_outcome_index` (`learning_outcome_id`) USING BTREE,
  KEY `student_outcome_achievements_period_index` (`period_id`) USING BTREE,
  KEY `student_outcome_achievements_subject_index` (`subject_id`) USING BTREE,
  KEY `student_outcome_achievements_level_index` (`achievement_level`) USING BTREE,
  KEY `student_outcome_achievements_teacher_index` (`teacher_id`) USING BTREE,
  KEY `student_outcome_achievements_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `student_outcome_achievements_created_by_index` (`created_by`) USING BTREE,
  KEY `student_outcome_achievements_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `student_outcome_achievements_outcome_id_foreign` FOREIGN KEY (`learning_outcome_id`) REFERENCES `learning_outcomes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_outcome_achievements_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_outcome_achievements_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_outcome_achievements_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_outcome_achievements_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_rubric_scores`
-- ----------------------------
DROP TABLE IF EXISTS `student_rubric_scores`;
CREATE TABLE `student_rubric_scores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `student_assessment_id` bigint unsigned NOT NULL,
  `criteria_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` decimal(6,2) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `student_rubric_scores_unique` (`student_assessment_id`,`criteria_key`) USING BTREE,
  KEY `student_rubric_scores_school_index` (`school_id`) USING BTREE,
  KEY `student_rubric_scores_is_active_index` (`is_active`) USING BTREE,
  KEY `student_rubric_scores_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `student_rubric_scores_created_by_index` (`created_by`) USING BTREE,
  KEY `student_rubric_scores_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `student_rubric_scores_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_rubric_scores_student_assessment_id_foreign` FOREIGN KEY (`student_assessment_id`) REFERENCES `student_assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_transcripts`
-- ----------------------------
DROP TABLE IF EXISTS `student_transcripts`;
CREATE TABLE `student_transcripts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `study_program_id` bigint unsigned NOT NULL,
  `transcript_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `graduation_date` date NOT NULL,
  `total_sks_earned` smallint unsigned NOT NULL,
  `final_gpa` decimal(3,2) NOT NULL,
  `predicate` enum('memuaskan','sangat_memuaskan','cumlaude') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sangat_memuaskan',
  `is_official` tinyint(1) NOT NULL DEFAULT '1',
  `printed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `transcript_num_unique` (`transcript_number`) USING BTREE,
  KEY `transcript_student_index` (`student_id`) USING BTREE,
  KEY `transcript_prodi_index` (`study_program_id`) USING BTREE,
  CONSTRAINT `transcript_prodi_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `transcript_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
