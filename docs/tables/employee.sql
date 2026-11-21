/*
 * Table Group: employee
 * Total Tables: 13
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `departments`
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `manager_id` bigint unsigned DEFAULT NULL,
  `cost_center_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `departments_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `departments_parent_id_index` (`parent_id`) USING BTREE,
  KEY `departments_manager_id_index` (`manager_id`) USING BTREE,
  KEY `departments_is_active_index` (`is_active`) USING BTREE,
  KEY `departments_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `departments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `departments_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `employee_bank_accounts`
-- ----------------------------
DROP TABLE IF EXISTS `employee_bank_accounts`;
CREATE TABLE `employee_bank_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `bank_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_bank_primary_unique` (`employee_id`,`is_primary`,`deleted_at`),
  KEY `employee_bank_org_index` (`organization_id`),
  CONSTRAINT `employee_bank_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_bank_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `employee_contracts`
-- ----------------------------
DROP TABLE IF EXISTS `employee_contracts`;
CREATE TABLE `employee_contracts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `contract_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contract_type` enum('tetap','kontrak','probation','freelance','project') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `salary` decimal(15,2) NOT NULL DEFAULT '0.00',
  `salary_structure_id` bigint unsigned DEFAULT NULL,
  `status` enum('draft','active','expired','terminated','extended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `document_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `employee_contracts_org_number_unique` (`organization_id`,`contract_number`) USING BTREE,
  KEY `employee_contracts_employee_id_index` (`employee_id`) USING BTREE,
  KEY `employee_contracts_salary_structure_id_index` (`salary_structure_id`) USING BTREE,
  KEY `employee_contracts_organization_id_index` (`organization_id`) USING BTREE,
  KEY `employee_contracts_status_index` (`status`) USING BTREE,
  KEY `employee_contracts_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `employee_contracts_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_contracts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_contracts_salary_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `employee_mutations`
-- ----------------------------
DROP TABLE IF EXISTS `employee_mutations`;
CREATE TABLE `employee_mutations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `mutation_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mutation_type` enum('promotion','demotion','transfer','department_change','position_change','salary_change') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_department_id` bigint unsigned DEFAULT NULL,
  `new_department_id` bigint unsigned DEFAULT NULL,
  `old_position_id` bigint unsigned DEFAULT NULL,
  `new_position_id` bigint unsigned DEFAULT NULL,
  `old_salary` decimal(15,2) DEFAULT NULL,
  `new_salary` decimal(15,2) DEFAULT NULL,
  `effective_date` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','submitted','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint unsigned DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `employee_mutations_mutation_code_unique` (`mutation_code`) USING BTREE,
  KEY `employee_mutations_employee_id_index` (`employee_id`) USING BTREE,
  KEY `employee_mutations_organization_id_index` (`organization_id`) USING BTREE,
  KEY `employee_mutations_status_index` (`status`) USING BTREE,
  KEY `employee_mutations_effective_date_index` (`effective_date`) USING BTREE,
  KEY `employee_mutations_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `employee_mutations_old_dept_fk` (`old_department_id`) USING BTREE,
  KEY `employee_mutations_new_dept_fk` (`new_department_id`) USING BTREE,
  KEY `employee_mutations_old_pos_fk` (`old_position_id`) USING BTREE,
  KEY `employee_mutations_new_pos_fk` (`new_position_id`) USING BTREE,
  CONSTRAINT `employee_mutations_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_mutations_new_dept_fk` FOREIGN KEY (`new_department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_mutations_new_pos_fk` FOREIGN KEY (`new_position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_mutations_old_dept_fk` FOREIGN KEY (`old_department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_mutations_old_pos_fk` FOREIGN KEY (`old_position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_mutations_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `employee_number_sequences`
-- ----------------------------
DROP TABLE IF EXISTS `employee_number_sequences`;
CREATE TABLE `employee_number_sequences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `last_number` bigint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_number_sequences_organization_id_unique` (`organization_id`),
  CONSTRAINT `employee_number_sequences_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `employee_types`
-- ----------------------------
DROP TABLE IF EXISTS `employee_types`;
CREATE TABLE `employee_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Nama Tipe Karyawan',
  `code` varchar(50) DEFAULT NULL COMMENT 'Kode Singkat',
  `description` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_types_code_unique` (`code`),
  KEY `employee_types_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for employees
-- ----------------------------
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `employee_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `npwp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bpjs_ketenagakerjaan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bpjs_kesehatan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `department_id` bigint UNSIGNED NULL DEFAULT NULL,
  `position_id` bigint UNSIGNED NULL DEFAULT NULL,
  `supervisor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `salary_structure_id` bigint UNSIGNED NULL DEFAULT NULL,
  `employment_status_id` bigint UNSIGNED NOT NULL,
  `employment_type_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nick_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gender` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birth_place` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birth_date` date NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `photo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `join_date` date NOT NULL,
  `resign_date` date NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `employees_org_number_unique`(`organization_id` ASC, `employee_number` ASC) USING BTREE,
  UNIQUE INDEX `employees_org_nik_unique`(`organization_id` ASC, `nik` ASC) USING BTREE,
  UNIQUE INDEX `employees_org_email_unique`(`organization_id` ASC, `email` ASC) USING BTREE,
  UNIQUE INDEX `employees_org_user_unique`(`organization_id` ASC, `user_id` ASC) USING BTREE,
  INDEX `employees_org_active_index`(`organization_id` ASC, `is_active` ASC, `deleted_at` ASC) USING BTREE,
  INDEX `employees_org_join_date_index`(`organization_id` ASC, `join_date` ASC) USING BTREE,
  INDEX `employees_department_id_index`(`department_id` ASC) USING BTREE,
  INDEX `employees_position_id_index`(`position_id` ASC) USING BTREE,
  INDEX `employees_supervisor_id_index`(`supervisor_id` ASC) USING BTREE,
  INDEX `employees_salary_structure_id_index`(`salary_structure_id` ASC) USING BTREE,
  INDEX `employees_employment_status_id_index`(`employment_status_id` ASC) USING BTREE,
  INDEX `employees_name_index`(`name` ASC) USING BTREE,
  INDEX `employees_phone_index`(`phone` ASC) USING BTREE,
  INDEX `employees_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `employees_employment_type_id_index`(`employment_type_id` ASC) USING BTREE,
  CONSTRAINT `employees_department_fk` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employees_employment_status_fk` FOREIGN KEY (`employment_status_id`) REFERENCES `employment_statuses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `employees_employment_type_fk` FOREIGN KEY (`employment_type_id`) REFERENCES `employment_types` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employees_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employees_position_fk` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employees_salary_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employees_supervisor_fk` FOREIGN KEY (`supervisor_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `chk_employees_birth_before_join` CHECK ((`birth_date` is null) or (`birth_date` < `join_date`)),
  CONSTRAINT `chk_employees_resign_after_join` CHECK ((`resign_date` is null) or (`resign_date` >= `join_date`))
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for `employment_statuses`
-- ----------------------------
DROP TABLE IF EXISTS `employment_statuses`;
CREATE TABLE `employment_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employment_statuses_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `homeroom_teachers`
-- ----------------------------
DROP TABLE IF EXISTS `homeroom_teachers`;
CREATE TABLE `homeroom_teachers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL COMMENT 'Merujuk ke tabel teachers',
  `classroom_id` bigint unsigned NOT NULL COMMENT 'Merujuk ke tabel classrooms',
  `academic_year_id` bigint unsigned NOT NULL COMMENT 'Tahun ajaran penugasan',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `assignment_date` date DEFAULT NULL COMMENT 'Tanggal ditunjuk sebagai wali kelas',
  `end_date` date DEFAULT NULL COMMENT 'Tanggal berakhirnya tugas (jika diganti)',
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Catatan tambahan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `homeroom_class_year_unique` (`classroom_id`,`academic_year_id`) USING BTREE,
  UNIQUE KEY `homeroom_teacher_class_unique` (`teacher_id`,`classroom_id`,`academic_year_id`) USING BTREE,
  KEY `idx_teacher_id` (`teacher_id`) USING BTREE,
  KEY `idx_classroom_id` (`classroom_id`) USING BTREE,
  KEY `idx_academic_year` (`academic_year_id`) USING BTREE,
  KEY `idx_organization` (`organization_id`) USING BTREE,
  KEY `idx_is_active` (`is_active`) USING BTREE,
  CONSTRAINT `hr_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `hr_classroom_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `hr_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `hr_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='Penugasan wali kelas per tahun ajaran';

-- ----------------------------
-- Table structure for `position_user`
-- ----------------------------
DROP TABLE IF EXISTS `position_user`;
CREATE TABLE `position_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `position_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `user_position_unique` (`user_id`,`position_id`) USING BTREE,
  KEY `position_user_position_id_index` (`position_id`) USING BTREE,
  KEY `position_user_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `position_user_position_fk` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `position_user_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `positions`
-- ----------------------------
DROP TABLE IF EXISTS `positions`;
CREATE TABLE `positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int DEFAULT NULL,
  `salary_range_min` decimal(15,2) NOT NULL DEFAULT '0.00',
  `salary_range_max` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `positions_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `positions_is_active_index` (`is_active`) USING BTREE,
  KEY `positions_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `positions_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `teacher_comments`
-- ----------------------------
DROP TABLE IF EXISTS `teacher_comments`;
CREATE TABLE `teacher_comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `period_id` bigint unsigned NOT NULL,
  `classroom_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `teacher_comments_student_index` (`student_id`) USING BTREE,
  KEY `teacher_comments_period_index` (`period_id`) USING BTREE,
  KEY `teacher_comments_classroom_index` (`classroom_id`) USING BTREE,
  KEY `teacher_comments_teacher_index` (`teacher_id`) USING BTREE,
  KEY `teacher_comments_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `teacher_comments_created_by_index` (`created_by`) USING BTREE,
  KEY `teacher_comments_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `teacher_comments_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `teacher_comments_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `teacher_comments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `teacher_comments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `teachers`
-- ----------------------------
DROP TABLE IF EXISTS `teachers`;
CREATE TABLE `teachers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialization` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `education_level_id` bigint DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `status` enum('ACTIVE','RESIGNED','RETIRED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `employee_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `teachers_org_nip_unique` (`organization_id`,`nip`) USING BTREE,
  KEY `idx_teachers_organization_id` (`organization_id`) USING BTREE,
  KEY `idx_teachers_status` (`status`) USING BTREE,
  KEY `idx_teachers_deleted_at` (`deleted_at`) USING BTREE,
  KEY `teachers_school_id_index` (`school_id`) USING BTREE,
  CONSTRAINT `teachers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `teachers_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `teaching_loads`
-- ----------------------------
DROP TABLE IF EXISTS `teaching_loads`;
CREATE TABLE `teaching_loads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint unsigned NOT NULL,
  `classroom_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `semester` enum('SEMESTER_1','SEMESTER_2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_hours` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `teaching_loads_unique` (`teacher_id`,`classroom_id`,`subject_id`,`academic_year_id`,`semester`) USING BTREE,
  KEY `idx_teaching_loads_teacher` (`teacher_id`) USING BTREE,
  KEY `idx_teaching_loads_classroom` (`classroom_id`) USING BTREE,
  KEY `idx_teaching_loads_academic_year` (`academic_year_id`) USING BTREE,
  KEY `idx_teaching_loads_deleted_at` (`deleted_at`) USING BTREE,
  KEY `teaching_loads_subject_fk` (`subject_id`) USING BTREE,
  CONSTRAINT `teaching_loads_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `teaching_loads_classroom_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `teaching_loads_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `teaching_loads_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
