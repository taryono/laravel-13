-- ==============================================================================
-- ENTERPRISE MODULE 04: HIGHER EDUCATION & UNIVERSITY ACADEMICS (PERGURUAN TINGGI)
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 1. Fakultas (Faculties)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `faculties`;
CREATE TABLE `faculties` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dean_id` bigint UNSIGNED NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `faculties_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `faculties_school_index`(`school_id` ASC) USING BTREE,
  INDEX `faculties_dean_index`(`dean_id` ASC) USING BTREE,
  INDEX `faculties_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `faculties_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `faculties_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `faculties_dean_fk` FOREIGN KEY (`dean_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Program Studi / Jurusan (Study Programs)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `study_programs`;
CREATE TABLE `study_programs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `faculty_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `degree_level` enum('D3','D4','S1','S2','S3','profesi','spesialis') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S1',
  `accreditation` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'Unggul',
  `head_of_program_id` bigint UNSIGNED NULL DEFAULT NULL,
  `total_sks_required` int UNSIGNED NOT NULL DEFAULT 144,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `study_programs_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `study_prog_school_index`(`school_id` ASC) USING BTREE,
  INDEX `study_prog_faculty_index`(`faculty_id` ASC) USING BTREE,
  INDEX `study_prog_head_index`(`head_of_program_id` ASC) USING BTREE,
  INDEX `study_prog_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `study_prog_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `study_prog_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `study_prog_faculty_fk` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `study_prog_head_fk` FOREIGN KEY (`head_of_program_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 3. Mata Kuliah & SKS (Curriculum Courses)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `curriculum_courses`;
CREATE TABLE `curriculum_courses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `study_program_id` bigint UNSIGNED NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sks_theory` tinyint UNSIGNED NOT NULL DEFAULT 2,
  `sks_practical` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `total_sks` tinyint UNSIGNED GENERATED ALWAYS AS ((`sks_theory` + `sks_practical`)) STORED,
  `semester_recommendation` tinyint UNSIGNED NOT NULL DEFAULT 1,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 1,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `syllabus_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `courses_prodi_code_unique`(`study_program_id` ASC, `code` ASC) USING BTREE,
  INDEX `courses_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `courses_prodi_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 4. Prasyarat Mata Kuliah (Course Prerequisites)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `course_prerequisites`;
CREATE TABLE `course_prerequisites` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` bigint UNSIGNED NOT NULL,
  `prerequisite_course_id` bigint UNSIGNED NOT NULL,
  `min_grade` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'D',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `course_prereq_unique`(`course_id` ASC, `prerequisite_course_id` ASC) USING BTREE,
  INDEX `course_prereq_target_index`(`prerequisite_course_id` ASC) USING BTREE,
  CONSTRAINT `course_prereq_source_fk` FOREIGN KEY (`course_id`) REFERENCES `curriculum_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `course_prereq_target_fk` FOREIGN KEY (`prerequisite_course_id`) REFERENCES `curriculum_courses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 5. Semester Akademik Perguruan Tinggi (Academic Semesters)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `academic_semesters`;
CREATE TABLE `academic_semesters` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('ganjil','genap','pendek') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ganjil',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `krs_start_date` date NULL DEFAULT NULL,
  `krs_end_date` date NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_current` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `acad_sem_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `acad_sem_year_index`(`academic_year_id` ASC) USING BTREE,
  CONSTRAINT `acad_sem_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `acad_sem_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `acad_sem_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 6. Kartu Rencana Studi (KRS) & Items
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `student_krs`;
CREATE TABLE `student_krs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `academic_semester_id` bigint UNSIGNED NOT NULL,
  `study_program_id` bigint UNSIGNED NOT NULL,
  `academic_advisor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `total_sks` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `max_allowed_sks` tinyint UNSIGNED NOT NULL DEFAULT 24,
  `status` enum('draft','submitted','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_krs_sem_unique`(`student_id` ASC, `academic_semester_id` ASC) USING BTREE,
  INDEX `student_krs_sem_index`(`academic_semester_id` ASC) USING BTREE,
  INDEX `student_krs_prodi_index`(`study_program_id` ASC) USING BTREE,
  INDEX `student_krs_advisor_index`(`academic_advisor_id` ASC) USING BTREE,
  CONSTRAINT `student_krs_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_krs_sem_fk` FOREIGN KEY (`academic_semester_id`) REFERENCES `academic_semesters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_krs_prodi_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `student_krs_advisor_fk` FOREIGN KEY (`academic_advisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `student_krs_items`;
CREATE TABLE `student_krs_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_krs_id` bigint UNSIGNED NOT NULL,
  `course_id` bigint UNSIGNED NOT NULL,
  `classroom_id` bigint UNSIGNED NULL DEFAULT NULL,
  `sks` tinyint UNSIGNED NOT NULL,
  `grade_numeric` decimal(5, 2) NULL DEFAULT NULL,
  `grade_letter` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `grade_point` decimal(3, 2) NULL DEFAULT NULL,
  `is_passed` tinyint(1) NULL DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `krs_items_unique`(`student_krs_id` ASC, `course_id` ASC) USING BTREE,
  INDEX `krs_items_course_index`(`course_id` ASC) USING BTREE,
  INDEX `krs_items_class_index`(`classroom_id` ASC) USING BTREE,
  CONSTRAINT `krs_items_krs_fk` FOREIGN KEY (`student_krs_id`) REFERENCES `student_krs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `krs_items_course_fk` FOREIGN KEY (`course_id`) REFERENCES `curriculum_courses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `krs_items_class_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 7. Kartu Hasil Studi (KHS)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `student_khs`;
CREATE TABLE `student_khs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `academic_semester_id` bigint UNSIGNED NOT NULL,
  `sks_semester` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `sks_cumulative` smallint UNSIGNED NOT NULL DEFAULT 0,
  `gpa_semester` decimal(3, 2) NOT NULL DEFAULT 0.00,
  `gpa_cumulative` decimal(3, 2) NOT NULL DEFAULT 0.00,
  `max_sks_next_semester` tinyint UNSIGNED NOT NULL DEFAULT 20,
  `academic_status` enum('aktif','cuti','skorsing','do','lulus') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `printed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_khs_unique`(`student_id` ASC, `academic_semester_id` ASC) USING BTREE,
  INDEX `student_khs_sem_index`(`academic_semester_id` ASC) USING BTREE,
  CONSTRAINT `student_khs_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_khs_sem_fk` FOREIGN KEY (`academic_semester_id`) REFERENCES `academic_semesters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 8. Transkrip Akademik (Student Transcripts)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `student_transcripts`;
CREATE TABLE `student_transcripts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `study_program_id` bigint UNSIGNED NOT NULL,
  `transcript_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `graduation_date` date NOT NULL,
  `total_sks_earned` smallint UNSIGNED NOT NULL,
  `final_gpa` decimal(3, 2) NOT NULL,
  `predicate` enum('memuaskan','sangat_memuaskan','cumlaude') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sangat_memuaskan',
  `is_official` tinyint(1) NOT NULL DEFAULT 1,
  `printed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `transcript_num_unique`(`transcript_number` ASC) USING BTREE,
  INDEX `transcript_student_index`(`student_id` ASC) USING BTREE,
  INDEX `transcript_prodi_index`(`study_program_id` ASC) USING BTREE,
  CONSTRAINT `transcript_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transcript_prodi_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 9. Bimbingan Tugas Akhir / Skripsi (Thesis Supervisions)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `thesis_supervisions`;
CREATE TABLE `thesis_supervisions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `study_program_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supervisor_1_id` bigint UNSIGNED NOT NULL,
  `supervisor_2_id` bigint UNSIGNED NULL DEFAULT NULL,
  `examiner_1_id` bigint UNSIGNED NULL DEFAULT NULL,
  `examiner_2_id` bigint UNSIGNED NULL DEFAULT NULL,
  `status` enum('proposed','in_progress','seminar_proposal','defense_scheduled','completed','revising') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'proposed',
  `defense_date` date NULL DEFAULT NULL,
  `final_score` decimal(5, 2) NULL DEFAULT NULL,
  `grade_letter` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `manuscript_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `thesis_student_index`(`student_id` ASC) USING BTREE,
  INDEX `thesis_prodi_index`(`study_program_id` ASC) USING BTREE,
  INDEX `thesis_sup1_index`(`supervisor_1_id` ASC) USING BTREE,
  INDEX `thesis_sup2_index`(`supervisor_2_id` ASC) USING BTREE,
  CONSTRAINT `thesis_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `thesis_prodi_fk` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `thesis_sup1_fk` FOREIGN KEY (`supervisor_1_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `thesis_sup2_fk` FOREIGN KEY (`supervisor_2_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 10. Yudisium & Penetapan Kelulusan (Yudisiums)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `yudisiums`;
CREATE TABLE `yudisiums` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `decree_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `decree_date` date NOT NULL,
  `national_diploma_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `graduation_date` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `yudisium_student_unique`(`student_id` ASC) USING BTREE,
  INDEX `yudisium_year_index`(`academic_year_id` ASC) USING BTREE,
  CONSTRAINT `yudisium_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `yudisium_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
