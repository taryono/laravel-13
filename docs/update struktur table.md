📦 BATCH 1 — Tabel Inti & Master Akademik
-- ============================================
-- 1. USERS
-- ============================================
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password_hash` TEXT NOT NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `birth_date` DATE NULL,
    `gender` VARCHAR(10) NULL,
    `avatar_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `last_login_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `users_username_unique` (`username`),
    UNIQUE INDEX `users_email_unique` (`email`),
    INDEX `users_is_active_index` (`is_active`),
    INDEX `users_deleted_at_index` (`deleted_at`),
    INDEX `users_created_by_index` (`created_by`),
    INDEX `users_updated_by_index` (`updated_by`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 2. SCHOOLS
-- ============================================
CREATE TABLE `schools` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `type` VARCHAR(20) NULL,
    `level_education` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `phone` VARCHAR(20) NULL,
    `email` VARCHAR(100) NULL,
    `website` VARCHAR(100) NULL,
    `logo_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `schools_code_unique` (`code`),
    INDEX `schools_type_index` (`type`),
    INDEX `schools_is_active_index` (`is_active`),
    INDEX `schools_deleted_at_index` (`deleted_at`),
    INDEX `schools_created_by_index` (`created_by`),
    INDEX `schools_updated_by_index` (`updated_by`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 3. STUDENTS
-- ============================================
CREATE TABLE `students` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `nis` VARCHAR(50) NOT NULL,
    `nisn` VARCHAR(50) NULL,
    `registration_number` VARCHAR(50) NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `birth_place` VARCHAR(100) NULL,
    `birth_date` DATE NULL,
    `gender` VARCHAR(10) NULL,
    `address` TEXT NULL,
    `religion` VARCHAR(20) NULL,
    `parents_phone` VARCHAR(20) NULL,
    `emergency_contact` VARCHAR(20) NULL,
    `medical_notes` TEXT NULL,
    `join_date` DATE NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'ACTIVE',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `students_nis_unique` (`nis`),
    UNIQUE INDEX `students_nisn_unique` (`nisn`),
    INDEX `students_school_id_index` (`school_id`),
    INDEX `students_user_id_index` (`user_id`),
    INDEX `students_status_index` (`status`),
    INDEX `students_birth_date_index` (`birth_date`),
    INDEX `students_is_deleted_index` (`deleted_at`),
    INDEX `students_created_by_index` (`created_by`),
    INDEX `students_updated_by_index` (`updated_by`),
    CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `students_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 4. TEACHERS
-- ============================================
CREATE TABLE `teachers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `nip` VARCHAR(50) NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `specialization` VARCHAR(100) NULL,
    `education_level` VARCHAR(50) NULL,
    `join_date` DATE NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'ACTIVE',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `teachers_nip_unique` (`nip`),
    INDEX `teachers_school_id_index` (`school_id`),
    INDEX `teachers_user_id_index` (`user_id`),
    INDEX `teachers_status_index` (`status`),
    INDEX `teachers_deleted_at_index` (`deleted_at`),
    INDEX `teachers_created_by_index` (`created_by`),
    INDEX `teachers_updated_by_index` (`updated_by`),
    CONSTRAINT `teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `teachers_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 5. PARENTS
-- ============================================
CREATE TABLE `parents` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `email` VARCHAR(100) NULL,
    `address` TEXT NULL,
    `relationship` VARCHAR(20) NULL,
    `work` VARCHAR(100) NULL,
    `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `parents_user_id_index` (`user_id`),
    INDEX `parents_phone_index` (`phone`),
    INDEX `parents_deleted_at_index` (`deleted_at`),
    INDEX `parents_created_by_index` (`created_by`),
    INDEX `parents_updated_by_index` (`updated_by`),
    CONSTRAINT `parents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 6. STUDENT_PARENTS (Pivot)
-- ============================================
CREATE TABLE `student_parents` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `parent_id` BIGINT UNSIGNED NOT NULL,
    `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_parents_student_parent_unique` (`student_id`, `parent_id`),
    INDEX `student_parents_student_index` (`student_id`),
    INDEX `student_parents_parent_index` (`parent_id`),
    INDEX `student_parents_deleted_at_index` (`deleted_at`),
    CONSTRAINT `student_parents_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_parents_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 7. ACADEMIC_YEARS
-- ============================================
CREATE TABLE `academic_years` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `semester_start_date` DATE NULL,
    `semester_end_date` DATE NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `is_current` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `academic_years_school_code_unique` (`school_id`, `code`),
    INDEX `academic_years_school_index` (`school_id`),
    INDEX `academic_years_date_range_index` (`start_date`, `end_date`),
    INDEX `academic_years_is_current_index` (`is_current`),
    INDEX `academic_years_deleted_at_index` (`deleted_at`),
    INDEX `academic_years_created_by_index` (`created_by`),
    INDEX `academic_years_updated_by_index` (`updated_by`),
    CONSTRAINT `academic_years_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 8. CLASS_LEVELS
-- ============================================
CREATE TABLE `class_levels` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `level_order` INTEGER NOT NULL,
    `education_level` VARCHAR(20) NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `class_levels_school_code_unique` (`school_id`, `code`),
    INDEX `class_levels_school_index` (`school_id`),
    INDEX `class_levels_order_index` (`level_order`),
    INDEX `class_levels_deleted_at_index` (`deleted_at`),
    INDEX `class_levels_created_by_index` (`created_by`),
    INDEX `class_levels_updated_by_index` (`updated_by`),
    CONSTRAINT `class_levels_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 9. CLASSROOMS
-- ============================================
CREATE TABLE `classrooms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `capacity` INTEGER NOT NULL DEFAULT 30,
    `homeroom_teacher_id` BIGINT UNSIGNED NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `classrooms_school_code_unique` (`school_id`, `code`),
    INDEX `classrooms_school_index` (`school_id`),
    INDEX `classrooms_level_index` (`class_level_id`),
    INDEX `classrooms_academic_year_index` (`academic_year_id`),
    INDEX `classrooms_teacher_index` (`homeroom_teacher_id`),
    INDEX `classrooms_deleted_at_index` (`deleted_at`),
    INDEX `classrooms_created_by_index` (`created_by`),
    INDEX `classrooms_updated_by_index` (`updated_by`),
    CONSTRAINT `classrooms_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `classrooms_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `classrooms_homeroom_teacher_id_foreign` FOREIGN KEY (`homeroom_teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `classrooms_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 10. SUBJECTS
-- ============================================
CREATE TABLE `subjects` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `abbreviation` VARCHAR(20) NULL,
    `description` TEXT NULL,
    `category` VARCHAR(50) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `subjects_school_code_unique` (`school_id`, `code`),
    INDEX `subjects_school_index` (`school_id`),
    INDEX `subjects_category_index` (`category`),
    INDEX `subjects_deleted_at_index` (`deleted_at`),
    INDEX `subjects_created_by_index` (`created_by`),
    INDEX `subjects_updated_by_index` (`updated_by`),
    CONSTRAINT `subjects_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📦 BATCH 2 — Jadwal, Kurikulum, Kelas & Penilaian

-- ============================================
-- 11. SCHEDULES
-- ============================================
CREATE TABLE `schedules` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `day_of_week` VARCHAR(10) NULL,
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `room` VARCHAR(50) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `schedules_classroom_index` (`classroom_id`),
    INDEX `schedules_teacher_index` (`teacher_id`),
    INDEX `schedules_day_time_index` (`day_of_week`, `start_time`),
    INDEX `schedules_academic_year_index` (`academic_year_id`),
    INDEX `schedules_subject_index` (`subject_id`),
    INDEX `schedules_deleted_at_index` (`deleted_at`),
    INDEX `schedules_created_by_index` (`created_by`),
    INDEX `schedules_updated_by_index` (`updated_by`),
    CONSTRAINT `schedules_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `schedules_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `schedules_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `schedules_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 12. CURRICULUM_PHASES (Kurikulum Merdeka)
-- ============================================
CREATE TABLE `curriculum_phases` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `description` TEXT NULL,
    `age_range` VARCHAR(20) NULL,
    `class_levels` JSON NULL,
    `competencies` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `curriculum_phases_school_code_unique` (`school_id`, `code`),
    INDEX `curriculum_phases_school_index` (`school_id`),
    INDEX `curriculum_phases_is_active_index` (`is_active`),
    INDEX `curriculum_phases_deleted_at_index` (`deleted_at`),
    INDEX `curriculum_phases_created_by_index` (`created_by`),
    INDEX `curriculum_phases_updated_by_index` (`updated_by`),
    CONSTRAINT `curriculum_phases_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 13. CURRICULUM_STRUCTURES
-- ============================================
CREATE TABLE `curriculum_structures` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `curriculum_phase_id` BIGINT UNSIGNED NULL,
    `semester` VARCHAR(10) NULL,
    `hours_per_week` INTEGER NOT NULL,
    `hour_duration` INTEGER NOT NULL DEFAULT 45,
    `is_core` TINYINT(1) NOT NULL DEFAULT 1,
    `min_student` INTEGER NOT NULL DEFAULT 1,
    `max_student` INTEGER NOT NULL DEFAULT 40,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `curriculum_structures_unique` (`school_id`, `class_level_id`, `subject_id`, `academic_year_id`, `semester`),
    INDEX `curriculum_structures_school_index` (`school_id`),
    INDEX `curriculum_structures_class_index` (`class_level_id`),
    INDEX `curriculum_structures_subject_index` (`subject_id`),
    INDEX `curriculum_structures_academic_year_index` (`academic_year_id`),
    INDEX `curriculum_structures_phase_index` (`curriculum_phase_id`),
    INDEX `curriculum_structures_deleted_at_index` (`deleted_at`),
    INDEX `curriculum_structures_created_by_index` (`created_by`),
    INDEX `curriculum_structures_updated_by_index` (`updated_by`),
    CONSTRAINT `curriculum_structures_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `curriculum_structures_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `curriculum_structures_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `curriculum_structures_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `curriculum_structures_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 14. TEACHING_LOADS (Beban Mengajar Guru)
-- ============================================
CREATE TABLE `teaching_loads` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `teacher_id` BIGINT UNSIGNED NOT NULL,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `semester` VARCHAR(10) NULL,
    `total_hours` INTEGER NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `teaching_loads_unique` (`teacher_id`, `classroom_id`, `subject_id`, `academic_year_id`, `semester`),
    INDEX `teaching_loads_teacher_index` (`teacher_id`),
    INDEX `teaching_loads_classroom_index` (`classroom_id`),
    INDEX `teaching_loads_subject_index` (`subject_id`),
    INDEX `teaching_loads_academic_year_index` (`academic_year_id`),
    INDEX `teaching_loads_deleted_at_index` (`deleted_at`),
    INDEX `teaching_loads_created_by_index` (`created_by`),
    INDEX `teaching_loads_updated_by_index` (`updated_by`),
    CONSTRAINT `teaching_loads_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `teaching_loads_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `teaching_loads_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `teaching_loads_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 15. STUDENT_CLASSROOMS (Siswa per Kelas)
-- ============================================
CREATE TABLE `student_classrooms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `enrollment_date` DATE NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'ACTIVE',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_classrooms_unique` (`student_id`, `classroom_id`, `academic_year_id`),
    INDEX `student_classrooms_student_index` (`student_id`),
    INDEX `student_classrooms_classroom_index` (`classroom_id`),
    INDEX `student_classrooms_academic_year_index` (`academic_year_id`),
    INDEX `student_classrooms_status_index` (`status`),
    INDEX `student_classrooms_deleted_at_index` (`deleted_at`),
    INDEX `student_classrooms_created_by_index` (`created_by`),
    INDEX `student_classrooms_updated_by_index` (`updated_by`),
    CONSTRAINT `student_classrooms_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_classrooms_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_classrooms_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 16. PERIODS / SEMESTERS
-- ============================================
CREATE TABLE `periods` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `type` VARCHAR(20) NULL,
    `semester` INTEGER NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `grading_start` DATE NULL,
    `grading_end` DATE NULL,
    `assessment_type` VARCHAR(30) NULL,
    `weight` INTEGER NOT NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `periods_academic_year_code_unique` (`academic_year_id`, `code`),
    INDEX `periods_academic_year_index` (`academic_year_id`),
    INDEX `periods_date_range_index` (`start_date`, `end_date`),
    INDEX `periods_is_active_index` (`is_active`),
    INDEX `periods_deleted_at_index` (`deleted_at`),
    INDEX `periods_created_by_index` (`created_by`),
    INDEX `periods_updated_by_index` (`updated_by`),
    CONSTRAINT `periods_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 17. GRADES (Nilai Siswa)
-- ============================================
CREATE TABLE `grades` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_classroom_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `score` DECIMAL(5,2) NULL,
    `grade_letter` VARCHAR(2) NULL,
    `predicate` VARCHAR(50) NULL,
    `description` TEXT NULL,
    `assessment_type` VARCHAR(20) NULL,
    `weight` INTEGER NOT NULL DEFAULT 1,
    `is_final` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `grades_unique` (`student_classroom_id`, `subject_id`, `period_id`, `assessment_type`),
    INDEX `grades_student_classroom_index` (`student_classroom_id`),
    INDEX `grades_subject_index` (`subject_id`),
    INDEX `grades_period_index` (`period_id`),
    INDEX `grades_teacher_index` (`teacher_id`),
    INDEX `grades_is_final_index` (`is_final`),
    INDEX `grades_deleted_at_index` (`deleted_at`),
    INDEX `grades_created_by_index` (`created_by`),
    INDEX `grades_updated_by_index` (`updated_by`),
    CONSTRAINT `grades_student_classroom_id_foreign` FOREIGN KEY (`student_classroom_id`) REFERENCES `student_classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `grades_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `grades_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `grades_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 18. GRADE_SCALES (Konversi Nilai)
-- ============================================
CREATE TABLE `grade_scales` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `min_score` DECIMAL(5,2) NOT NULL,
    `max_score` DECIMAL(5,2) NOT NULL,
    `grade_letter` VARCHAR(2) NOT NULL,
    `predicate` VARCHAR(50) NOT NULL,
    `description` TEXT NULL,
    `is_default` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `grade_scales_school_letter_unique` (`school_id`, `grade_letter`),
    INDEX `grade_scales_school_index` (`school_id`),
    INDEX `grade_scales_is_default_index` (`is_default`),
    INDEX `grade_scales_deleted_at_index` (`deleted_at`),
    INDEX `grade_scales_created_by_index` (`created_by`),
    INDEX `grade_scales_updated_by_index` (`updated_by`),
    CONSTRAINT `grade_scales_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📦 BATCH 3 — e-Learning & Diskusi

-- ============================================
-- 19. COURSES (e-Learning)
-- ============================================
CREATE TABLE `courses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `thumbnail_url` TEXT NULL,
    `level` VARCHAR(20) NULL,
    `duration_hours` INTEGER NOT NULL DEFAULT 0,
    `is_published` TINYINT(1) NOT NULL DEFAULT 0,
    `published_at` TIMESTAMP NULL,
    `enrollment_count` INTEGER NOT NULL DEFAULT 0,
    `rating` DECIMAL(3,2) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `courses_school_slug_unique` (`school_id`, `slug`),
    INDEX `courses_school_index` (`school_id`),
    INDEX `courses_teacher_index` (`teacher_id`),
    INDEX `courses_subject_index` (`subject_id`),
    INDEX `courses_is_published_index` (`is_published`),
    INDEX `courses_deleted_at_index` (`deleted_at`),
    INDEX `courses_created_by_index` (`created_by`),
    INDEX `courses_updated_by_index` (`updated_by`),
    CONSTRAINT `courses_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `courses_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `courses_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 20. LESSONS (Pertemuan/Bab)
-- ============================================
CREATE TABLE `lessons` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `content` TEXT NULL,
    `video_url` TEXT NULL,
    `duration_minutes` INTEGER NOT NULL DEFAULT 0,
    `order_index` INTEGER NOT NULL,
    `is_free_preview` TINYINT(1) NOT NULL DEFAULT 0,
    `is_published` TINYINT(1) NOT NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `lessons_course_index` (`course_id`),
    INDEX `lessons_order_index` (`order_index`),
    INDEX `lessons_is_published_index` (`is_published`),
    INDEX `lessons_deleted_at_index` (`deleted_at`),
    INDEX `lessons_created_by_index` (`created_by`),
    INDEX `lessons_updated_by_index` (`updated_by`),
    CONSTRAINT `lessons_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 21. ASSIGNMENTS_QUIZZES (STI: ASSIGNMENT / QUIZ / EXAM)
-- ============================================
CREATE TABLE `assignments_quizzes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id` BIGINT UNSIGNED NOT NULL,
    `lesson_id` BIGINT UNSIGNED NULL,
    `type` VARCHAR(20) NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `max_score` INTEGER NOT NULL DEFAULT 100,
    `time_limit_minutes` INTEGER NULL,
    `passing_score` INTEGER NOT NULL DEFAULT 70,
    `due_date` TIMESTAMP NULL,
    `is_published` TINYINT(1) NOT NULL DEFAULT 0,
    `question_data` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `assignments_quizzes_course_index` (`course_id`),
    INDEX `assignments_quizzes_lesson_index` (`lesson_id`),
    INDEX `assignments_quizzes_type_index` (`type`),
    INDEX `assignments_quizzes_due_date_index` (`due_date`),
    INDEX `assignments_quizzes_is_published_index` (`is_published`),
    INDEX `assignments_quizzes_deleted_at_index` (`deleted_at`),
    INDEX `assignments_quizzes_created_by_index` (`created_by`),
    INDEX `assignments_quizzes_updated_by_index` (`updated_by`),
    CONSTRAINT `assignments_quizzes_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `assignments_quizzes_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 22. SUBMISSIONS (Pengumpulan Tugas)
-- ============================================
CREATE TABLE `submissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `assignment_quiz_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `submission_text` TEXT NULL,
    `file_urls` JSON NULL,
    `score` DECIMAL(5,2) NULL,
    `feedback` TEXT NULL,
    `graded_by` BIGINT UNSIGNED NULL,
    `submitted_at` TIMESTAMP NULL,
    `graded_at` TIMESTAMP NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'SUBMITTED',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `submissions_assignment_student_unique` (`assignment_quiz_id`, `student_id`),
    INDEX `submissions_assignment_index` (`assignment_quiz_id`),
    INDEX `submissions_student_index` (`student_id`),
    INDEX `submissions_status_index` (`status`),
    INDEX `submissions_graded_by_index` (`graded_by`),
    INDEX `submissions_deleted_at_index` (`deleted_at`),
    INDEX `submissions_created_by_index` (`created_by`),
    INDEX `submissions_updated_by_index` (`updated_by`),
    CONSTRAINT `submissions_assignment_quiz_id_foreign` FOREIGN KEY (`assignment_quiz_id`) REFERENCES `assignments_quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `submissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `submissions_graded_by_foreign` FOREIGN KEY (`graded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 23. QUIZ_ATTEMPTS (Jawaban Kuis)
-- ============================================
CREATE TABLE `quiz_attempts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `assignment_quiz_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `answers` JSON NOT NULL,
    `score` DECIMAL(5,2) NULL,
    `started_at` TIMESTAMP NULL,
    `submitted_at` TIMESTAMP NULL,
    `time_spent_seconds` INTEGER NULL,
    `is_completed` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `quiz_attempts_assignment_index` (`assignment_quiz_id`),
    INDEX `quiz_attempts_student_index` (`student_id`),
    INDEX `quiz_attempts_is_completed_index` (`is_completed`),
    INDEX `quiz_attempts_deleted_at_index` (`deleted_at`),
    INDEX `quiz_attempts_created_by_index` (`created_by`),
    INDEX `quiz_attempts_updated_by_index` (`updated_by`),
    CONSTRAINT `quiz_attempts_assignment_quiz_id_foreign` FOREIGN KEY (`assignment_quiz_id`) REFERENCES `assignments_quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `quiz_attempts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 24. LESSON_PROGRESS (Progres Belajar)
-- ============================================
CREATE TABLE `lesson_progress` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `lesson_id` BIGINT UNSIGNED NOT NULL,
    `is_completed` TINYINT(1) NOT NULL DEFAULT 0,
    `progress_percent` INTEGER NOT NULL DEFAULT 0,
    `last_position_seconds` INTEGER NOT NULL DEFAULT 0,
    `started_at` TIMESTAMP NULL,
    `completed_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `lesson_progress_student_lesson_unique` (`student_id`, `lesson_id`),
    INDEX `lesson_progress_student_index` (`student_id`),
    INDEX `lesson_progress_lesson_index` (`lesson_id`),
    INDEX `lesson_progress_completed_index` (`is_completed`),
    INDEX `lesson_progress_deleted_at_index` (`deleted_at`),
    INDEX `lesson_progress_created_by_index` (`created_by`),
    INDEX `lesson_progress_updated_by_index` (`updated_by`),
    CONSTRAINT `lesson_progress_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lesson_progress_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 25. DISCUSSIONS (Forum Diskusi)
-- ============================================
CREATE TABLE `discussions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `content` TEXT NOT NULL,
    `is_pinned` TINYINT(1) NOT NULL DEFAULT 0,
    `is_locked` TINYINT(1) NOT NULL DEFAULT 0,
    `views_count` INTEGER NOT NULL DEFAULT 0,
    `replies_count` INTEGER NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `discussions_course_index` (`course_id`),
    INDEX `discussions_user_index` (`user_id`),
    INDEX `discussions_created_at_index` (`created_at`),
    INDEX `discussions_is_pinned_index` (`is_pinned`),
    INDEX `discussions_deleted_at_index` (`deleted_at`),
    INDEX `discussions_created_by_index` (`created_by`),
    INDEX `discussions_updated_by_index` (`updated_by`),
    CONSTRAINT `discussions_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `discussions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 26. DISCUSSION_REPLIES
-- ============================================
CREATE TABLE `discussion_replies` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `discussion_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `content` TEXT NOT NULL,
    `is_solution` TINYINT(1) NOT NULL DEFAULT 0,
    `likes_count` INTEGER NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `discussion_replies_discussion_index` (`discussion_id`),
    INDEX `discussion_replies_user_index` (`user_id`),
    INDEX `discussion_replies_is_solution_index` (`is_solution`),
    INDEX `discussion_replies_deleted_at_index` (`deleted_at`),
    INDEX `discussion_replies_created_by_index` (`created_by`),
    INDEX `discussion_replies_updated_by_index` (`updated_by`),
    CONSTRAINT `discussion_replies_discussion_id_foreign` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `discussion_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📦 BATCH 4 — Rapor, Prestasi, Kehadiran & Audit

-- ============================================
-- 27. REPORT_CARD_TEMPLATES
-- ============================================
CREATE TABLE `report_card_templates` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `template_data` JSON NOT NULL,
    `is_default` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `version` INTEGER NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `report_card_templates_school_code_unique` (`school_id`, `code`),
    INDEX `report_card_templates_school_index` (`school_id`),
    INDEX `report_card_templates_is_default_index` (`is_default`),
    INDEX `report_card_templates_is_active_index` (`is_active`),
    INDEX `report_card_templates_deleted_at_index` (`deleted_at`),
    INDEX `report_card_templates_created_by_index` (`created_by`),
    INDEX `report_card_templates_updated_by_index` (`updated_by`),
    CONSTRAINT `report_card_templates_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 28. GENERATED_REPORTS (Rapor yang Dihasilkan)
-- ============================================
CREATE TABLE `generated_reports` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NOT NULL,
    `template_id` BIGINT UNSIGNED NULL,
    `pdf_path` TEXT NOT NULL,
    `report_data` JSON NULL,
    `generated_at` TIMESTAMP NULL,
    `downloaded_count` INTEGER NOT NULL DEFAULT 0,
    `last_download_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `generated_reports_student_index` (`student_id`),
    INDEX `generated_reports_period_index` (`period_id`),
    INDEX `generated_reports_template_index` (`template_id`),
    INDEX `generated_reports_generated_at_index` (`generated_at`),
    INDEX `generated_reports_deleted_at_index` (`deleted_at`),
    INDEX `generated_reports_created_by_index` (`created_by`),
    INDEX `generated_reports_updated_by_index` (`updated_by`),
    CONSTRAINT `generated_reports_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `generated_reports_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `generated_reports_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `report_card_templates` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 29. TEACHER_COMMENTS (Catatan Wali Kelas)
-- ============================================
CREATE TABLE `teacher_comments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NOT NULL,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `comment` TEXT NOT NULL,
    `comment_type` VARCHAR(20) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `teacher_comments_student_index` (`student_id`),
    INDEX `teacher_comments_period_index` (`period_id`),
    INDEX `teacher_comments_classroom_index` (`classroom_id`),
    INDEX `teacher_comments_teacher_index` (`teacher_id`),
    INDEX `teacher_comments_deleted_at_index` (`deleted_at`),
    INDEX `teacher_comments_created_by_index` (`created_by`),
    INDEX `teacher_comments_updated_by_index` (`updated_by`),
    CONSTRAINT `teacher_comments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `teacher_comments_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `teacher_comments_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `teacher_comments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 30. ACHIEVEMENTS (Prestasi Siswa)
-- ============================================
CREATE TABLE `achievements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `achievement_type` VARCHAR(50) NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `level` VARCHAR(20) NULL,
    `date_achieved` DATE NULL,
    `certificate_url` TEXT NULL,
    `points` INTEGER NOT NULL DEFAULT 0,
    `verified_by` BIGINT UNSIGNED NULL,
    `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `achievements_student_index` (`student_id`),
    INDEX `achievements_type_index` (`achievement_type`),
    INDEX `achievements_level_index` (`level`),
    INDEX `achievements_date_index` (`date_achieved`),
    INDEX `achievements_is_verified_index` (`is_verified`),
    INDEX `achievements_deleted_at_index` (`deleted_at`),
    INDEX `achievements_created_by_index` (`created_by`),
    INDEX `achievements_updated_by_index` (`updated_by`),
    CONSTRAINT `achievements_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `achievements_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 31. ATTENDANCES (Kehadiran Siswa)
-- ============================================
CREATE TABLE `attendances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `schedule_id` BIGINT UNSIGNED NULL,
    `classroom_id` BIGINT UNSIGNED NULL,
    `date` DATE NOT NULL,
    `status` VARCHAR(20) NULL,
    `check_in_time` TIME NULL,
    `check_out_time` TIME NULL,
    `late_minutes` INTEGER NOT NULL DEFAULT 0,
    `reason` TEXT NULL,
    `verified_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `attendances_student_schedule_date_unique` (`student_id`, `schedule_id`, `date`),
    INDEX `attendances_student_index` (`student_id`),
    INDEX `attendances_schedule_index` (`schedule_id`),
    INDEX `attendances_classroom_index` (`classroom_id`),
    INDEX `attendances_date_index` (`date`),
    INDEX `attendances_status_index` (`status`),
    INDEX `attendances_deleted_at_index` (`deleted_at`),
    INDEX `attendances_created_by_index` (`created_by`),
    INDEX `attendances_updated_by_index` (`updated_by`),
    CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `attendances_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `attendances_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `attendances_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 32. EXTRACURRICULARS (Ekstrakurikuler)
-- ============================================
CREATE TABLE `extracurriculars` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `description` TEXT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `day_of_week` VARCHAR(10) NULL,
    `start_time` TIME NULL,
    `end_time` TIME NULL,
    `max_students` INTEGER NOT NULL DEFAULT 30,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `extracurriculars_school_code_unique` (`school_id`, `code`),
    INDEX `extracurriculars_school_index` (`school_id`),
    INDEX `extracurriculars_teacher_index` (`teacher_id`),
    INDEX `extracurriculars_day_index` (`day_of_week`),
    INDEX `extracurriculars_deleted_at_index` (`deleted_at`),
    INDEX `extracurriculars_created_by_index` (`created_by`),
    INDEX `extracurriculars_updated_by_index` (`updated_by`),
    CONSTRAINT `extracurriculars_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `extracurriculars_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 33. STUDENT_EXTRACURRICULARS (Pivot)
-- ============================================
CREATE TABLE `student_extracurriculars` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `extracurricular_id` BIGINT UNSIGNED NOT NULL,
    `enrollment_date` DATE NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'ACTIVE',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_extracurriculars_unique` (`student_id`, `extracurricular_id`),
    INDEX `student_extracurriculars_student_index` (`student_id`),
    INDEX `student_extracurriculars_extracurricular_index` (`extracurricular_id`),
    INDEX `student_extracurriculars_status_index` (`status`),
    INDEX `student_extracurriculars_deleted_at_index` (`deleted_at`),
    INDEX `student_extracurriculars_created_by_index` (`created_by`),
    INDEX `student_extracurriculars_updated_by_index` (`updated_by`),
    CONSTRAINT `student_extracurriculars_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_extracurriculars_extracurricular_id_foreign` FOREIGN KEY (`extracurricular_id`) REFERENCES `extracurriculars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 34. SYSTEM_LOGS (Audit Trail)
-- ============================================
CREATE TABLE `system_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `action` VARCHAR(50) NOT NULL,
    `table_name` VARCHAR(50) NULL,
    `record_id` BIGINT UNSIGNED NULL,
    `old_data` JSON NULL,
    `new_data` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `system_logs_user_index` (`user_id`),
    INDEX `system_logs_action_index` (`action`),
    INDEX `system_logs_table_name_index` (`table_name`),
    INDEX `system_logs_record_id_index` (`record_id`),
    INDEX `system_logs_created_at_index` (`created_at`),
    INDEX `system_logs_deleted_at_index` (`deleted_at`),
    INDEX `system_logs_created_by_index` (`created_by`),
    INDEX `system_logs_updated_by_index` (`updated_by`),
    CONSTRAINT `system_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📦 BATCH 5 — Timetabling, Bank Soal & CBT

-- ============================================
-- 35. TIMETABLE_CONSTRAINTS (Constraint Guru)
-- ============================================
CREATE TABLE `timetable_constraints` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NOT NULL,
    `day_of_week` VARCHAR(10) NULL,
    `time_slot_start` TIME NULL,
    `time_slot_end` TIME NULL,
    `constraint_type` VARCHAR(20) NULL,
    `reason` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `timetable_constraints_school_index` (`school_id`),
    INDEX `timetable_constraints_teacher_index` (`teacher_id`),
    INDEX `timetable_constraints_day_index` (`day_of_week`),
    INDEX `timetable_constraints_type_index` (`constraint_type`),
    INDEX `timetable_constraints_deleted_at_index` (`deleted_at`),
    INDEX `timetable_constraints_created_by_index` (`created_by`),
    INDEX `timetable_constraints_updated_by_index` (`updated_by`),
    CONSTRAINT `timetable_constraints_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `timetable_constraints_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 36. TIMETABLE_ROOMS (Ruangan)
-- ============================================
CREATE TABLE `timetable_rooms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `room_code` VARCHAR(20) NOT NULL,
    `room_name` VARCHAR(100) NOT NULL,
    `capacity` INTEGER NOT NULL DEFAULT 30,
    `room_type` VARCHAR(20) NULL,
    `floor_number` INTEGER NULL,
    `building` VARCHAR(50) NULL,
    `facilities` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `timetable_rooms_school_code_unique` (`school_id`, `room_code`),
    INDEX `timetable_rooms_school_index` (`school_id`),
    INDEX `timetable_rooms_type_index` (`room_type`),
    INDEX `timetable_rooms_deleted_at_index` (`deleted_at`),
    INDEX `timetable_rooms_created_by_index` (`created_by`),
    INDEX `timetable_rooms_updated_by_index` (`updated_by`),
    CONSTRAINT `timetable_rooms_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 37. ROOM_CONSTRAINTS (Constraint Ruangan)
-- ============================================
CREATE TABLE `room_constraints` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `room_id` BIGINT UNSIGNED NOT NULL,
    `day_of_week` VARCHAR(10) NULL,
    `time_slot_start` TIME NULL,
    `time_slot_end` TIME NULL,
    `constraint_type` VARCHAR(20) NULL,
    `reason` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `room_constraints_room_index` (`room_id`),
    INDEX `room_constraints_day_index` (`day_of_week`),
    INDEX `room_constraints_type_index` (`constraint_type`),
    INDEX `room_constraints_deleted_at_index` (`deleted_at`),
    INDEX `room_constraints_created_by_index` (`created_by`),
    INDEX `room_constraints_updated_by_index` (`updated_by`),
    CONSTRAINT `room_constraints_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `timetable_rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 38. TIMETABLE_GENERATIONS (Hasil Generate Timetable)
-- ============================================
CREATE TABLE `timetable_generations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `semester` VARCHAR(10) NULL,
    `generation_date` TIMESTAMP NULL,
    `algorithm_version` VARCHAR(50) NULL,
    `total_conflicts` INTEGER NOT NULL DEFAULT 0,
    `total_assignments` INTEGER NOT NULL DEFAULT 0,
    `total_teachers` INTEGER NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'GENERATED',
    `generation_data` JSON NULL,
    `generated_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `timetable_generations_school_index` (`school_id`),
    INDEX `timetable_generations_academic_year_index` (`academic_year_id`),
    INDEX `timetable_generations_status_index` (`status`),
    INDEX `timetable_generations_generated_by_index` (`generated_by`),
    INDEX `timetable_generations_deleted_at_index` (`deleted_at`),
    INDEX `timetable_generations_created_by_index` (`created_by`),
    INDEX `timetable_generations_updated_by_index` (`updated_by`),
    CONSTRAINT `timetable_generations_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `timetable_generations_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `timetable_generations_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 39. ALTER SCHEDULES — Tambah room_id, generation_id, dll
-- ============================================
ALTER TABLE `schedules`
    ADD COLUMN `room_id` BIGINT UNSIGNED NULL AFTER `room`,
    ADD COLUMN `generation_id` BIGINT UNSIGNED NULL AFTER `room_id`,
    ADD COLUMN `is_locked` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_active`,
    ADD COLUMN `notes` TEXT NULL AFTER `is_locked`,
    ADD INDEX `schedules_room_index` (`room_id`),
    ADD INDEX `schedules_generation_index` (`generation_id`),
    ADD CONSTRAINT `schedules_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `timetable_rooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    ADD CONSTRAINT `schedules_generation_id_foreign` FOREIGN KEY (`generation_id`) REFERENCES `timetable_generations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- ============================================
-- 40. QUESTION_BANKS (Bank Soal)
-- ============================================
CREATE TABLE `question_banks` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `question_type` VARCHAR(30) NULL,
    `difficulty_level` VARCHAR(20) NULL,
    `cognitive_level` VARCHAR(20) NULL,
    `question_text` TEXT NOT NULL,
    `options` JSON NULL,
    `correct_answer` JSON NULL,
    `explanation` TEXT NULL,
    `tags` JSON NULL,
    `points` INTEGER NOT NULL DEFAULT 10,
    `is_public` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `question_banks_school_index` (`school_id`),
    INDEX `question_banks_subject_index` (`subject_id`),
    INDEX `question_banks_type_index` (`question_type`),
    INDEX `question_banks_difficulty_index` (`difficulty_level`),
    INDEX `question_banks_cognitive_index` (`cognitive_level`),
    INDEX `question_banks_is_public_index` (`is_public`),
    INDEX `question_banks_is_active_index` (`is_active`),
    INDEX `question_banks_deleted_at_index` (`deleted_at`),
    INDEX `question_banks_created_by_index` (`created_by`),
    INDEX `question_banks_updated_by_index` (`updated_by`),
    CONSTRAINT `question_banks_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `question_banks_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 41. EXAM_PACKAGES (Paket Soal)
-- ============================================
CREATE TABLE `exam_packages` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `duration_minutes` INTEGER NOT NULL DEFAULT 60,
    `total_questions` INTEGER NOT NULL DEFAULT 0,
    `passing_score` INTEGER NOT NULL DEFAULT 70,
    `is_randomized` TINYINT(1) NOT NULL DEFAULT 0,
    `shuffle_options` TINYINT(1) NOT NULL DEFAULT 0,
    `show_results` TINYINT(1) NOT NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `exam_packages_school_index` (`school_id`),
    INDEX `exam_packages_subject_index` (`subject_id`),
    INDEX `exam_packages_is_active_index` (`is_active`),
    INDEX `exam_packages_deleted_at_index` (`deleted_at`),
    INDEX `exam_packages_created_by_index` (`created_by`),
    INDEX `exam_packages_updated_by_index` (`updated_by`),
    CONSTRAINT `exam_packages_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `exam_packages_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 42. EXAM_PACKAGE_QUESTIONS (Pivot)
-- ============================================
CREATE TABLE `exam_package_questions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `package_id` BIGINT UNSIGNED NOT NULL,
    `question_id` BIGINT UNSIGNED NOT NULL,
    `question_order` INTEGER NOT NULL DEFAULT 0,
    `points` INTEGER NOT NULL DEFAULT 10,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `exam_package_questions_unique` (`package_id`, `question_id`),
    INDEX `exam_package_questions_package_index` (`package_id`),
    INDEX `exam_package_questions_question_index` (`question_id`),
    INDEX `exam_package_questions_deleted_at_index` (`deleted_at`),
    CONSTRAINT `exam_package_questions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `exam_packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `exam_package_questions_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 43. CBT_SESSIONS (Sesi Ujian)
-- ============================================
CREATE TABLE `cbt_sessions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `exam_package_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `schedule_id` BIGINT UNSIGNED NULL,
    `class_id` BIGINT UNSIGNED NULL,
    `start_time` TIMESTAMP NULL,
    `end_time` TIMESTAMP NULL,
    `duration_seconds` INTEGER NULL,
    `current_question_index` INTEGER NOT NULL DEFAULT 0,
    `answers` JSON NULL,
    `status` VARCHAR(20) NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `proctor_notes` TEXT NULL,
    `is_submitted` TINYINT(1) NOT NULL DEFAULT 0,
    `submitted_at` TIMESTAMP NULL,
    `final_score` DECIMAL(5,2) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `cbt_sessions_exam_package_index` (`exam_package_id`),
    INDEX `cbt_sessions_student_index` (`student_id`),
    INDEX `cbt_sessions_schedule_index` (`schedule_id`),
    INDEX `cbt_sessions_class_index` (`class_id`),
    INDEX `cbt_sessions_status_index` (`status`),
    INDEX `cbt_sessions_is_submitted_index` (`is_submitted`),
    INDEX `cbt_sessions_deleted_at_index` (`deleted_at`),
    INDEX `cbt_sessions_created_by_index` (`created_by`),
    INDEX `cbt_sessions_updated_by_index` (`updated_by`),
    CONSTRAINT `cbt_sessions_exam_package_id_foreign` FOREIGN KEY (`exam_package_id`) REFERENCES `exam_packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `cbt_sessions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `cbt_sessions_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `cbt_sessions_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 44. CBT_ACTIVITY_LOGS (Anti-Cheating Log)
-- ============================================
CREATE TABLE `cbt_activity_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `session_id` BIGINT UNSIGNED NOT NULL,
    `activity_type` VARCHAR(30) NULL,
    `activity_data` JSON NULL,
    `recorded_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `cbt_activity_logs_session_index` (`session_id`),
    INDEX `cbt_activity_logs_type_index` (`activity_type`),
    INDEX `cbt_activity_logs_recorded_at_index` (`recorded_at`),
    INDEX `cbt_activity_logs_deleted_at_index` (`deleted_at`),
    INDEX `cbt_activity_logs_created_by_index` (`created_by`),
    INDEX `cbt_activity_logs_updated_by_index` (`updated_by`),
    CONSTRAINT `cbt_activity_logs_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 45. CBT_PROCTORING (Dashboard Pengawas)
-- ============================================
CREATE TABLE `cbt_proctoring` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `session_id` BIGINT UNSIGNED NOT NULL,
    `proctor_id` BIGINT UNSIGNED NULL,
    `start_proctoring` TIMESTAMP NULL,
    `end_proctoring` TIMESTAMP NULL,
    `notes` TEXT NULL,
    `status` VARCHAR(20) NULL,
    `flag_reason` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `cbt_proctoring_session_index` (`session_id`),
    INDEX `cbt_proctoring_proctor_index` (`proctor_id`),
    INDEX `cbt_proctoring_status_index` (`status`),
    INDEX `cbt_proctoring_deleted_at_index` (`deleted_at`),
    INDEX `cbt_proctoring_created_by_index` (`created_by`),
    INDEX `cbt_proctoring_updated_by_index` (`updated_by`),
    CONSTRAINT `cbt_proctoring_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `cbt_proctoring_proctor_id_foreign` FOREIGN KEY (`proctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📦 BATCH 6 — PPDB, Billing & Dapodik

-- ============================================
-- 46. ADMISSIONS (Master PPDB)
-- ============================================
CREATE TABLE `admissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `type` VARCHAR(20) NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `quota` INTEGER NOT NULL DEFAULT 0,
    `registration_fee` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `min_age` INTEGER NULL,
    `max_age` INTEGER NULL,
    `requirements` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `admissions_school_code_unique` (`school_id`, `code`),
    INDEX `admissions_school_index` (`school_id`),
    INDEX `admissions_academic_year_index` (`academic_year_id`),
    INDEX `admissions_class_level_index` (`class_level_id`),
    INDEX `admissions_dates_index` (`start_date`, `end_date`),
    INDEX `admissions_is_active_index` (`is_active`),
    INDEX `admissions_deleted_at_index` (`deleted_at`),
    INDEX `admissions_created_by_index` (`created_by`),
    INDEX `admissions_updated_by_index` (`updated_by`),
    CONSTRAINT `admissions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `admissions_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `admissions_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 47. APPLICANTS (Pendaftar)
-- ============================================
CREATE TABLE `applicants` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `admission_id` BIGINT UNSIGNED NOT NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `nik` VARCHAR(50) NULL,
    `birth_place` VARCHAR(100) NULL,
    `birth_date` DATE NULL,
    `gender` VARCHAR(10) NULL,
    `address` TEXT NULL,
    `phone` VARCHAR(20) NULL,
    `email` VARCHAR(100) NULL,
    `parent_name` VARCHAR(150) NULL,
    `parent_phone` VARCHAR(20) NULL,
    `parent_email` VARCHAR(100) NULL,
    `previous_school` VARCHAR(150) NULL,
    `registration_number` VARCHAR(50) NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'REGISTERED',
    `test_score` DECIMAL(5,2) NULL,
    `interview_score` DECIMAL(5,2) NULL,
    `total_score` DECIMAL(5,2) NULL,
    `ranking` INTEGER NULL,
    `notes` TEXT NULL,
    `registered_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `applicants_school_registration_number_unique` (`school_id`, `registration_number`),
    UNIQUE INDEX `applicants_school_nik_unique` (`school_id`, `nik`),
    INDEX `applicants_school_index` (`school_id`),
    INDEX `applicants_admission_index` (`admission_id`),
    INDEX `applicants_status_index` (`status`),
    INDEX `applicants_registration_number_index` (`registration_number`),
    INDEX `applicants_deleted_at_index` (`deleted_at`),
    INDEX `applicants_created_by_index` (`created_by`),
    INDEX `applicants_updated_by_index` (`updated_by`),
    CONSTRAINT `applicants_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `applicants_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 48. APPLICATION_DOCUMENTS (Dokumen Pendaftaran)
-- ============================================
CREATE TABLE `application_documents` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `applicant_id` BIGINT UNSIGNED NOT NULL,
    `document_type` VARCHAR(30) NULL,
    `document_url` TEXT NOT NULL,
    `file_name` VARCHAR(200) NULL,
    `file_size` BIGINT NULL,
    `uploaded_at` TIMESTAMP NULL,
    `verified` TINYINT(1) NOT NULL DEFAULT 0,
    `verified_by` BIGINT UNSIGNED NULL,
    `verification_notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `application_documents_applicant_index` (`applicant_id`),
    INDEX `application_documents_type_index` (`document_type`),
    INDEX `application_documents_verified_index` (`verified`),
    INDEX `application_documents_deleted_at_index` (`deleted_at`),
    INDEX `application_documents_created_by_index` (`created_by`),
    INDEX `application_documents_updated_by_index` (`updated_by`),
    CONSTRAINT `application_documents_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `application_documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 49. ADMISSION_SELECTIONS (Proses Seleksi)
-- ============================================
CREATE TABLE `admission_selections` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `admission_id` BIGINT UNSIGNED NOT NULL,
    `applicant_id` BIGINT UNSIGNED NOT NULL,
    `selection_type` VARCHAR(20) NULL,
    `score` DECIMAL(5,2) NULL,
    `result` VARCHAR(20) NULL,
    `evaluator_id` BIGINT UNSIGNED NULL,
    `evaluation_date` TIMESTAMP NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `admission_selections_admission_index` (`admission_id`),
    INDEX `admission_selections_applicant_index` (`applicant_id`),
    INDEX `admission_selections_type_index` (`selection_type`),
    INDEX `admission_selections_result_index` (`result`),
    INDEX `admission_selections_evaluator_index` (`evaluator_id`),
    INDEX `admission_selections_deleted_at_index` (`deleted_at`),
    INDEX `admission_selections_created_by_index` (`created_by`),
    INDEX `admission_selections_updated_by_index` (`updated_by`),
    CONSTRAINT `admission_selections_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `admission_selections_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `admission_selections_evaluator_id_foreign` FOREIGN KEY (`evaluator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 50. ADMISSION_TIMELINES (Timeline Pendaftaran)
-- ============================================
CREATE TABLE `admission_timelines` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `admission_id` BIGINT UNSIGNED NOT NULL,
    `phase_name` VARCHAR(50) NOT NULL,
    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `description` TEXT NULL,
    `order_number` INTEGER NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `admission_timelines_admission_index` (`admission_id`),
    INDEX `admission_timelines_order_index` (`order_number`),
    INDEX `admission_timelines_deleted_at_index` (`deleted_at`),
    INDEX `admission_timelines_created_by_index` (`created_by`),
    INDEX `admission_timelines_updated_by_index` (`updated_by`),
    CONSTRAINT `admission_timelines_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 51. APPLICANT_INVOICES (Invoice Pendaftar)
-- ============================================
CREATE TABLE `applicant_invoices` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `applicant_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `admission_id` BIGINT UNSIGNED NOT NULL,
    `invoice_number` VARCHAR(50) NOT NULL,
    `invoice_type` VARCHAR(20) NULL,
    `due_date` DATE NULL,
    `total_amount` DECIMAL(15,2) NOT NULL,
    `discount_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `penalty_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `final_amount` DECIMAL(15,2) NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `applicant_invoices_school_number_unique` (`school_id`, `invoice_number`),
    INDEX `applicant_invoices_applicant_index` (`applicant_id`),
    INDEX `applicant_invoices_school_index` (`school_id`),
    INDEX `applicant_invoices_admission_index` (`admission_id`),
    INDEX `applicant_invoices_status_index` (`status`),
    INDEX `applicant_invoices_due_date_index` (`due_date`),
    INDEX `applicant_invoices_deleted_at_index` (`deleted_at`),
    INDEX `applicant_invoices_created_by_index` (`created_by`),
    INDEX `applicant_invoices_updated_by_index` (`updated_by`),
    CONSTRAINT `applicant_invoices_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `applicant_invoices_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `applicant_invoices_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 52. APPLICANT_PAYMENTS (Pembayaran Pendaftar)
-- ============================================
CREATE TABLE `applicant_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` BIGINT UNSIGNED NOT NULL,
    `applicant_id` BIGINT UNSIGNED NOT NULL,
    `payment_method` VARCHAR(30) NULL,
    `payment_date` DATE NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `reference_number` VARCHAR(100) NULL,
    `bank_name` VARCHAR(50) NULL,
    `payment_proof_url` TEXT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `verified_by` BIGINT UNSIGNED NULL,
    `verification_date` TIMESTAMP NULL,
    `refund_date` TIMESTAMP NULL,
    `refund_reason` TEXT NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `applicant_payments_invoice_index` (`invoice_id`),
    INDEX `applicant_payments_applicant_index` (`applicant_id`),
    INDEX `applicant_payments_status_index` (`status`),
    INDEX `applicant_payments_verified_by_index` (`verified_by`),
    INDEX `applicant_payments_deleted_at_index` (`deleted_at`),
    INDEX `applicant_payments_created_by_index` (`created_by`),
    INDEX `applicant_payments_updated_by_index` (`updated_by`),
    CONSTRAINT `applicant_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `applicant_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `applicant_payments_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `applicant_payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 53. FEE_MASTERS (Master Biaya)
-- ============================================
CREATE TABLE `fee_masters` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NULL,
    `fee_category` VARCHAR(30) NULL,
    `fee_name` VARCHAR(100) NOT NULL,
    `fee_code` VARCHAR(50) NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `frequency` VARCHAR(20) NULL,
    `is_mandatory` TINYINT(1) NOT NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `description` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fee_masters_school_code_unique` (`school_id`, `fee_code`),
    INDEX `fee_masters_school_index` (`school_id`),
    INDEX `fee_masters_academic_year_index` (`academic_year_id`),
    INDEX `fee_masters_class_level_index` (`class_level_id`),
    INDEX `fee_masters_category_index` (`fee_category`),
    INDEX `fee_masters_deleted_at_index` (`deleted_at`),
    INDEX `fee_masters_created_by_index` (`created_by`),
    INDEX `fee_masters_updated_by_index` (`updated_by`),
    CONSTRAINT `fee_masters_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fee_masters_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fee_masters_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 54. FEE_STRUCTURES (Detail Biaya per Periode)
-- ============================================
CREATE TABLE `fee_structures` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `fee_master_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NOT NULL,
    `due_date` DATE NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fee_structures_master_period_unique` (`fee_master_id`, `period_id`),
    INDEX `fee_structures_master_index` (`fee_master_id`),
    INDEX `fee_structures_period_index` (`period_id`),
    INDEX `fee_structures_due_date_index` (`due_date`),
    INDEX `fee_structures_deleted_at_index` (`deleted_at`),
    INDEX `fee_structures_created_by_index` (`created_by`),
    INDEX `fee_structures_updated_by_index` (`updated_by`),
    CONSTRAINT `fee_structures_fee_master_id_foreign` FOREIGN KEY (`fee_master_id`) REFERENCES `fee_masters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fee_structures_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 55. STUDENT_INVOICES (Invoice Siswa)
-- ============================================
CREATE TABLE `student_invoices` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `fee_master_id` BIGINT UNSIGNED NOT NULL,
    `student_classroom_id` BIGINT UNSIGNED NULL,
    `invoice_number` VARCHAR(50) NOT NULL,
    `period_id` BIGINT UNSIGNED NULL,
    `invoice_date` DATE NULL,
    `due_date` DATE NULL,
    `total_amount` DECIMAL(15,2) NOT NULL,
    `discount_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `penalty_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `final_amount` DECIMAL(15,2) NOT NULL,
    `paid_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_invoices_school_number_unique` (`school_id`, `invoice_number`),
    INDEX `student_invoices_student_index` (`student_id`),
    INDEX `student_invoices_school_index` (`school_id`),
    INDEX `student_invoices_fee_master_index` (`fee_master_id`),
    INDEX `student_invoices_student_classroom_index` (`student_classroom_id`),
    INDEX `student_invoices_status_index` (`status`),
    INDEX `student_invoices_due_date_index` (`due_date`),
    INDEX `student_invoices_period_index` (`period_id`),
    INDEX `student_invoices_deleted_at_index` (`deleted_at`),
    INDEX `student_invoices_created_by_index` (`created_by`),
    INDEX `student_invoices_updated_by_index` (`updated_by`),
    CONSTRAINT `student_invoices_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_invoices_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_invoices_fee_master_id_foreign` FOREIGN KEY (`fee_master_id`) REFERENCES `fee_masters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_invoices_student_classroom_id_foreign` FOREIGN KEY (`student_classroom_id`) REFERENCES `student_classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_invoices_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 56. STUDENT_INSTALLMENTS (Cicilan Siswa)
-- ============================================
CREATE TABLE `student_installments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` BIGINT UNSIGNED NOT NULL,
    `installment_number` INTEGER NOT NULL,
    `due_date` DATE NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `is_paid` TINYINT(1) NOT NULL DEFAULT 0,
    `paid_date` DATE NULL,
    `paid_amount` DECIMAL(15,2) NULL,
    `payment_id` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_installments_invoice_number_unique` (`invoice_id`, `installment_number`),
    INDEX `student_installments_invoice_index` (`invoice_id`),
    INDEX `student_installments_due_date_index` (`due_date`),
    INDEX `student_installments_is_paid_index` (`is_paid`),
    INDEX `student_installments_payment_index` (`payment_id`),
    INDEX `student_installments_deleted_at_index` (`deleted_at`),
    INDEX `student_installments_created_by_index` (`created_by`),
    INDEX `student_installments_updated_by_index` (`updated_by`),
    CONSTRAINT `student_installments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 57. STUDENT_PAYMENTS (Pembayaran Siswa)
-- ============================================
CREATE TABLE `student_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `payment_method` VARCHAR(30) NULL,
    `payment_date` DATE NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `reference_number` VARCHAR(100) NULL,
    `bank_name` VARCHAR(50) NULL,
    `payment_proof_url` TEXT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `verified_by` BIGINT UNSIGNED NULL,
    `verification_date` TIMESTAMP NULL,
    `refund_date` TIMESTAMP NULL,
    `refund_reason` TEXT NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `student_payments_invoice_index` (`invoice_id`),
    INDEX `student_payments_student_index` (`student_id`),
    INDEX `student_payments_status_index` (`status`),
    INDEX `student_payments_verified_by_index` (`verified_by`),
    INDEX `student_payments_deleted_at_index` (`deleted_at`),
    INDEX `student_payments_created_by_index` (`created_by`),
    INDEX `student_payments_updated_by_index` (`updated_by`),
    CONSTRAINT `student_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 58. STUDENT_PAYMENT_BATCHES (Batch Pembayaran Massal)
-- ============================================
CREATE TABLE `student_payment_batches` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `batch_number` VARCHAR(50) NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NULL,
    `description` TEXT NULL,
    `total_invoices` INTEGER NOT NULL DEFAULT 0,
    `total_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `paid_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `processed_by` BIGINT UNSIGNED NULL,
    `processed_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_payment_batches_school_number_unique` (`school_id`, `batch_number`),
    INDEX `student_payment_batches_school_index` (`school_id`),
    INDEX `student_payment_batches_academic_year_index` (`academic_year_id`),
    INDEX `student_payment_batches_period_index` (`period_id`),
    INDEX `student_payment_batches_status_index` (`status`),
    INDEX `student_payment_batches_processed_by_index` (`processed_by`),
    INDEX `student_payment_batches_deleted_at_index` (`deleted_at`),
    INDEX `student_payment_batches_created_by_index` (`created_by`),
    INDEX `student_payment_batches_updated_by_index` (`updated_by`),
    CONSTRAINT `student_payment_batches_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_payment_batches_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_payment_batches_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `student_payment_batches_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 59. STUDENT_INVOICE_BATCHES (Pivot Invoice-Batch)
-- ============================================
CREATE TABLE `student_invoice_batches` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` BIGINT UNSIGNED NOT NULL,
    `batch_id` BIGINT UNSIGNED NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_invoice_batches_unique` (`invoice_id`, `batch_id`),
    INDEX `student_invoice_batches_invoice_index` (`invoice_id`),
    INDEX `student_invoice_batches_batch_index` (`batch_id`),
    INDEX `student_invoice_batches_deleted_at_index` (`deleted_at`),
    CONSTRAINT `student_invoice_batches_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_invoice_batches_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `student_payment_batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 60. DAPODIK_MAPPINGS (Mapping ke Dapodik)
-- ============================================
CREATE TABLE `dapodik_mappings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `local_table` VARCHAR(50) NOT NULL,
    `local_id` BIGINT UNSIGNED NOT NULL,
    `dapodik_id` VARCHAR(50) NOT NULL,
    `dapodik_table` VARCHAR(50) NOT NULL,
    `hash_value` VARCHAR(64) NULL,
    `last_sync_at` TIMESTAMP NULL,
    `sync_status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `error_message` TEXT NULL,
    `retry_count` INTEGER NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `dapodik_mappings_local_unique` (`local_table`, `local_id`),
    UNIQUE INDEX `dapodik_mappings_dapodik_unique` (`dapodik_table`, `dapodik_id`),
    INDEX `dapodik_mappings_school_index` (`school_id`),
    INDEX `dapodik_mappings_local_index` (`local_table`, `local_id`),
    INDEX `dapodik_mappings_dapodik_index` (`dapodik_table`, `dapodik_id`),
    INDEX `dapodik_mappings_status_index` (`sync_status`),
    INDEX `dapodik_mappings_deleted_at_index` (`deleted_at`),
    INDEX `dapodik_mappings_created_by_index` (`created_by`),
    INDEX `dapodik_mappings_updated_by_index` (`updated_by`),
    CONSTRAINT `dapodik_mappings_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 61. DAPODIK_SYNC_LOGS
-- ============================================
CREATE TABLE `dapodik_sync_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `sync_date` TIMESTAMP NULL,
    `sync_type` VARCHAR(20) NULL,
    `status` VARCHAR(20) NULL,
    `total_records` INTEGER NOT NULL DEFAULT 0,
    `total_synced` INTEGER NOT NULL DEFAULT 0,
    `total_failed` INTEGER NOT NULL DEFAULT 0,
    `total_conflicts` INTEGER NOT NULL DEFAULT 0,
    `sync_duration_seconds` INTEGER NULL,
    `error_log` TEXT NULL,
    `details` JSON NULL,
    `triggered_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `dapodik_sync_logs_school_index` (`school_id`),
    INDEX `dapodik_sync_logs_status_index` (`status`),
    INDEX `dapodik_sync_logs_sync_type_index` (`sync_type`),
    INDEX `dapodik_sync_logs_sync_date_index` (`sync_date`),
    INDEX `dapodik_sync_logs_triggered_by_index` (`triggered_by`),
    INDEX `dapodik_sync_logs_deleted_at_index` (`deleted_at`),
    INDEX `dapodik_sync_logs_created_by_index` (`created_by`),
    INDEX `dapodik_sync_logs_updated_by_index` (`updated_by`),
    CONSTRAINT `dapodik_sync_logs_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `dapodik_sync_logs_triggered_by_foreign` FOREIGN KEY (`triggered_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 62. DAPODIK_SYNC_SCHEDULES
-- ============================================
CREATE TABLE `dapodik_sync_schedules` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `schedule_name` VARCHAR(100) NOT NULL,
    `sync_type` VARCHAR(20) NULL,
    `frequency` VARCHAR(20) NULL,
    `last_run` TIMESTAMP NULL,
    `next_run` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `dapodik_sync_schedules_school_index` (`school_id`),
    INDEX `dapodik_sync_schedules_next_run_index` (`next_run`),
    INDEX `dapodik_sync_schedules_is_active_index` (`is_active`),
    INDEX `dapodik_sync_schedules_deleted_at_index` (`deleted_at`),
    INDEX `dapodik_sync_schedules_created_by_index` (`created_by`),
    INDEX `dapodik_sync_schedules_updated_by_index` (`updated_by`),
    CONSTRAINT `dapodik_sync_schedules_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

🔴 GAP #1 — Jembatan Applicant → Student (KRITIS)

ALTER TABLE `students`
    ADD COLUMN `applicant_id` BIGINT UNSIGNED NULL AFTER `user_id`,
    ADD INDEX `students_applicant_index` (`applicant_id`),
    ADD CONSTRAINT `students_applicant_id_foreign`
        FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE;
🔴 GAP #2 — Rencana Pembelajaran (RPP/Modul Ajar) & Materi

CREATE TABLE `lesson_plans` (          -- RPP / Modul Ajar
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `curriculum_phase_id` BIGINT UNSIGNED NULL,
    `title` VARCHAR(200) NOT NULL,
    `learning_objectives` JSON NULL,       -- TP
    `learning_outcomes` JSON NULL,         -- CP
    `activities` JSON NULL,                -- Langkah pembelajaran
    `assessment_plan` JSON NULL,
    `media` JSON NULL,
    `duration_minutes` INTEGER NULL,
    `meeting_number` INTEGER NULL,
    `status` VARCHAR(20) DEFAULT 'DRAFT',  -- DRAFT, REVIEW, APPROVED
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    -- + kolom audit standar
);
-- Materi ajar
CREATE TABLE `learning_materials` (    
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `lesson_plan_id` BIGINT UNSIGNED NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `type` VARCHAR(30),                    -- PDF, VIDEO, LINK, SLIDE
    `url` TEXT,
    `content` TEXT,
    -- + audit
);

🔴 GAP #3 — Asesmen Formatif vs Sumatif (Kurikulum Merdeka)

ALTER TABLE `grades`
    ADD COLUMN `assessment_category` VARCHAR(20) NULL AFTER `assessment_type`,
    -- FORMATIF, SUMATIF, DIAGNOSTIK, AKM, SURVEI_KARAKTER
    ADD COLUMN `is_counted_in_report` TINYINT(1) NOT NULL DEFAULT 1 AFTER `is_final`;

🔴 GAP #4 — Capaian Pembelajaran / Deskripsi Capaian
-- CP per fase/mapel
CREATE TABLE `learning_outcomes` (       
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `curriculum_phase_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `element` VARCHAR(100),              -- Bilangan, Aljabar, Geometri, dll
    `code` VARCHAR(50),
    `description` TEXT,
    -- + audit
);
-- Capaian per siswa
CREATE TABLE `student_outcome_achievements` (  
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `learning_outcome_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NOT NULL,
    `achievement_level` VARCHAR(20),      -- BELUM_TERCAPAI, CUKUP, BAIK, SANGAT_BAIK
    `narrative` TEXT,                     -- Deskripsi kualitatif
    -- + audit
);

🔴 GAP #5 — Kenaikan Kelas & Kelulusan
CREATE TABLE `student_promotions` (      -- Kenaikan kelas
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `from_classroom_id` BIGINT UNSIGNED,
    `to_classroom_id` BIGINT UNSIGNED,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `decision` VARCHAR(20),              -- NAIK, TINGGAL, LULUS
    `decision_date` DATE,
    `meeting_notes` TEXT,
    -- + audit
);

CREATE TABLE `graduations` (             -- Kelulusan
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `graduation_date` DATE,
    `certificate_number` VARCHAR(50),    -- No Ijazah
    `skhu_number` VARCHAR(50),           -- No SKHU
    `final_score_average` DECIMAL(5,2),
    `predicate` VARCHAR(50),
    `status` VARCHAR(20),                -- LULUS, TIDAK_LULUS
    -- + audit
);

🔴 GAP #6 — Ekstrakurikuler: Penilaian & Sertifikat
ALTER TABLE `student_extracurriculars`
    ADD COLUMN `grade_letter` VARCHAR(2) NULL,
    ADD COLUMN `predicate` VARCHAR(50) NULL,
    ADD COLUMN `description` TEXT NULL,
    ADD COLUMN `certificate_url` TEXT NULL,
    ADD COLUMN `achievement_notes` TEXT NULL;

🔴 GAP #7 — Komunikasi Sekolah ↔ Orang Tua
CREATE TABLE `announcements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `content` TEXT,
    `audience` VARCHAR(30),              -- ALL, TEACHERS, STUDENTS, PARENTS
    `published_at` TIMESTAMP,
    `expired_at` TIMESTAMP,
    -- + audit
);

CREATE TABLE `notifications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `type` VARCHAR(30),
    `title` VARCHAR(200),
    `message` TEXT,
    `is_read` TINYINT(1) DEFAULT 0,
    `read_at` TIMESTAMP,
    -- + audit
);

🔴 GAP #8 — Disiplin / Pelanggaran Siswa
CREATE TABLE `student_disciplines` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `category` VARCHAR(50),              -- TERLAMBAT, BOLOS, ATRIBUT, dll
    `description` TEXT,
    `points` INTEGER,
    `action_taken` TEXT,                 -- Tindakan
    `incident_date` DATE,
    `reported_by` BIGINT UNSIGNED,
    -- + audit
);

🔴 GAP #9 — Kesehatan & BK (Bimbingan Konseling)
CREATE TABLE `student_health_records` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `record_date` DATE,
    `complaint` TEXT,
    `diagnosis` TEXT,
    `treatment` TEXT,
    `height_cm` DECIMAL(5,2),
    `weight_kg` DECIMAL(5,2),
    `blood_type` VARCHAR(3),
    -- + audit
);

CREATE TABLE `counseling_sessions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `counselor_id` BIGINT UNSIGNED,
    `session_date` TIMESTAMP,
    `category` VARCHAR(30),              -- AKADEMIK, SOSIAL, KARIR, PRIBADI
    `problem` TEXT,
    `action` TEXT,
    `follow_up` TEXT,
    `is_confidential` TINYINT(1) DEFAULT 1,
    -- + audit
);

🔴 GAP #10 — Perpustakaan & Inventaris (opsional)

🟡 GAP #11 — Kalender Akademik

CREATE TABLE `academic_calendar_events` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200),
    `event_type` VARCHAR(30),            -- HOLIDAY, EXAM, ACTIVITY, MEETING
    `start_date` DATE,
    `end_date` DATE,
    `is_holiday` TINYINT(1) DEFAULT 0,
    `description` TEXT,
    -- + audit
);



UPDATE STRUKTUR LAGI 

📦 BATCH 7 — Akademik & Kurikulum Merdeka

-- ============================================
-- 63. ALTER STUDENTS — Jembatan ke Applicants (GAP #1)
-- ============================================
ALTER TABLE `students`
    ADD COLUMN `applicant_id` BIGINT UNSIGNED NULL AFTER `user_id`,
    ADD INDEX `students_applicant_index` (`applicant_id`),
    ADD CONSTRAINT `students_applicant_id_foreign`
        FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE;

-- Tabel audit konversi pendaftar → siswa
CREATE TABLE `enrollment_conversions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `applicant_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `admission_id` BIGINT UNSIGNED NOT NULL,
    `converted_at` TIMESTAMP NULL,
    `converted_by` BIGINT UNSIGNED NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `enrollment_conversions_applicant_unique` (`applicant_id`),
    UNIQUE INDEX `enrollment_conversions_student_unique` (`student_id`),
    INDEX `enrollment_conversions_school_index` (`school_id`),
    INDEX `enrollment_conversions_admission_index` (`admission_id`),
    INDEX `enrollment_conversions_deleted_at_index` (`deleted_at`),
    INDEX `enrollment_conversions_created_by_index` (`created_by`),
    INDEX `enrollment_conversions_updated_by_index` (`updated_by`),
    CONSTRAINT `enrollment_conversions_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `enrollment_conversions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `enrollment_conversions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `enrollment_conversions_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `enrollment_conversions_converted_by_foreign` FOREIGN KEY (`converted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 64. LESSON_PLANS (RPP / Modul Ajar) — GAP #2
-- ============================================
CREATE TABLE `lesson_plans` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `curriculum_phase_id` BIGINT UNSIGNED NULL,
    `period_id` BIGINT UNSIGNED NULL,
    `title` VARCHAR(200) NOT NULL,
    `code` VARCHAR(50) NULL,
    `learning_objectives` JSON NULL,
    `learning_outcomes` JSON NULL,
    `activities` JSON NULL,
    `assessment_plan` JSON NULL,
    `media` JSON NULL,
    `resources` JSON NULL,
    `duration_minutes` INTEGER NULL,
    `meeting_number` INTEGER NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'DRAFT',
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `rejection_notes` TEXT NULL,
    `version` INTEGER NOT NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `lesson_plans_school_index` (`school_id`),
    INDEX `lesson_plans_teacher_index` (`teacher_id`),
    INDEX `lesson_plans_subject_index` (`subject_id`),
    INDEX `lesson_plans_class_level_index` (`class_level_id`),
    INDEX `lesson_plans_academic_year_index` (`academic_year_id`),
    INDEX `lesson_plans_phase_index` (`curriculum_phase_id`),
    INDEX `lesson_plans_period_index` (`period_id`),
    INDEX `lesson_plans_status_index` (`status`),
    INDEX `lesson_plans_approved_by_index` (`approved_by`),
    INDEX `lesson_plans_deleted_at_index` (`deleted_at`),
    INDEX `lesson_plans_created_by_index` (`created_by`),
    INDEX `lesson_plans_updated_by_index` (`updated_by`),
    CONSTRAINT `lesson_plans_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lesson_plans_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lesson_plans_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lesson_plans_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lesson_plans_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lesson_plans_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `lesson_plans_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `lesson_plans_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 65. LEARNING_MATERIALS (Materi Ajar) — GAP #2
-- ============================================
CREATE TABLE `learning_materials` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `lesson_plan_id` BIGINT UNSIGNED NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NULL,
    `curriculum_phase_id` BIGINT UNSIGNED NULL,
    `title` VARCHAR(200) NOT NULL,
    `type` VARCHAR(30) NULL,
    `url` TEXT NULL,
    `content` LONGTEXT NULL,
    `file_name` VARCHAR(200) NULL,
    `file_size` BIGINT NULL,
    `mime_type` VARCHAR(100) NULL,
    `order_index` INTEGER NOT NULL DEFAULT 0,
    `is_public` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `learning_materials_school_index` (`school_id`),
    INDEX `learning_materials_lesson_plan_index` (`lesson_plan_id`),
    INDEX `learning_materials_subject_index` (`subject_id`),
    INDEX `learning_materials_class_level_index` (`class_level_id`),
    INDEX `learning_materials_phase_index` (`curriculum_phase_id`),
    INDEX `learning_materials_type_index` (`type`),
    INDEX `learning_materials_order_index` (`order_index`),
    INDEX `learning_materials_deleted_at_index` (`deleted_at`),
    INDEX `learning_materials_created_by_index` (`created_by`),
    INDEX `learning_materials_updated_by_index` (`updated_by`),
    CONSTRAINT `learning_materials_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `learning_materials_lesson_plan_id_foreign` FOREIGN KEY (`lesson_plan_id`) REFERENCES `lesson_plans` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `learning_materials_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `learning_materials_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `learning_materials_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 66. ALTER GRADES — Formatif vs Sumatif (GAP #3)
-- ============================================
ALTER TABLE `grades`
    ADD COLUMN `assessment_category` VARCHAR(20) NULL AFTER `assessment_type`,
    ADD COLUMN `is_counted_in_report` TINYINT(1) NOT NULL DEFAULT 1 AFTER `is_final`,
    ADD INDEX `grades_assessment_category_index` (`assessment_category`),
    ADD INDEX `grades_is_counted_in_report_index` (`is_counted_in_report`);

-- ============================================
-- 67. LEARNING_OUTCOMES (CP per Fase & Mapel) — GAP #4
-- ============================================
CREATE TABLE `learning_outcomes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NULL,
    `curriculum_phase_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `element` VARCHAR(100) NULL,
    `code` VARCHAR(50) NULL,
    `description` TEXT NOT NULL,
    `competency` TEXT NULL,
    `order_index` INTEGER NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `learning_outcomes_school_index` (`school_id`),
    INDEX `learning_outcomes_phase_index` (`curriculum_phase_id`),
    INDEX `learning_outcomes_subject_index` (`subject_id`),
    INDEX `learning_outcomes_element_index` (`element`),
    INDEX `learning_outcomes_code_index` (`code`),
    INDEX `learning_outcomes_order_index` (`order_index`),
    INDEX `learning_outcomes_deleted_at_index` (`deleted_at`),
    INDEX `learning_outcomes_created_by_index` (`created_by`),
    INDEX `learning_outcomes_updated_by_index` (`updated_by`),
    CONSTRAINT `learning_outcomes_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `learning_outcomes_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `learning_outcomes_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 68. STUDENT_OUTCOME_ACHIEVEMENTS (Capaian Siswa) — GAP #4
-- ============================================
CREATE TABLE `student_outcome_achievements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `learning_outcome_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `achievement_level` VARCHAR(20) NULL,
    `score` DECIMAL(5,2) NULL,
    `narrative` TEXT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_outcome_achievements_unique` (`student_id`, `learning_outcome_id`, `period_id`),
    INDEX `student_outcome_achievements_student_index` (`student_id`),
    INDEX `student_outcome_achievements_outcome_index` (`learning_outcome_id`),
    INDEX `student_outcome_achievements_period_index` (`period_id`),
    INDEX `student_outcome_achievements_subject_index` (`subject_id`),
    INDEX `student_outcome_achievements_level_index` (`achievement_level`),
    INDEX `student_outcome_achievements_teacher_index` (`teacher_id`),
    INDEX `student_outcome_achievements_deleted_at_index` (`deleted_at`),
    INDEX `student_outcome_achievements_created_by_index` (`created_by`),
    INDEX `student_outcome_achievements_updated_by_index` (`updated_by`),
    CONSTRAINT `student_outcome_achievements_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_outcome_achievements_outcome_id_foreign` FOREIGN KEY (`learning_outcome_id`) REFERENCES `learning_outcomes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_outcome_achievements_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_outcome_achievements_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_outcome_achievements_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 69. STUDENT_PROMOTIONS (Kenaikan Kelas) — GAP #5
-- ============================================
CREATE TABLE `student_promotions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `from_classroom_id` BIGINT UNSIGNED NULL,
    `to_classroom_id` BIGINT UNSIGNED NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `decision` VARCHAR(20) NULL,
    `decision_date` DATE NULL,
    `meeting_notes` TEXT NULL,
    `final_score_average` DECIMAL(5,2) NULL,
    `attendance_percentage` DECIMAL(5,2) NULL,
    `decided_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_promotions_student_year_unique` (`student_id`, `academic_year_id`),
    INDEX `student_promotions_student_index` (`student_id`),
    INDEX `student_promotions_school_index` (`school_id`),
    INDEX `student_promotions_from_classroom_index` (`from_classroom_id`),
    INDEX `student_promotions_to_classroom_index` (`to_classroom_id`),
    INDEX `student_promotions_academic_year_index` (`academic_year_id`),
    INDEX `student_promotions_decision_index` (`decision`),
    INDEX `student_promotions_decided_by_index` (`decided_by`),
    INDEX `student_promotions_deleted_at_index` (`deleted_at`),
    INDEX `student_promotions_created_by_index` (`created_by`),
    INDEX `student_promotions_updated_by_index` (`updated_by`),
    CONSTRAINT `student_promotions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_promotions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_promotions_from_classroom_id_foreign` FOREIGN KEY (`from_classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `student_promotions_to_classroom_id_foreign` FOREIGN KEY (`to_classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `student_promotions_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_promotions_decided_by_foreign` FOREIGN KEY (`decided_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 70. GRADUATIONS (Kelulusan) — GAP #5
-- ============================================
CREATE TABLE `graduations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `classroom_id` BIGINT UNSIGNED NULL,
    `graduation_date` DATE NULL,
    `certificate_number` VARCHAR(50) NULL,
    `certificate_issued_at` DATE NULL,
    `skhu_number` VARCHAR(50) NULL,
    `skhu_issued_at` DATE NULL,
    `transcript_number` VARCHAR(50) NULL,
    `final_score_average` DECIMAL(5,2) NULL,
    `predicate` VARCHAR(50) NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'LULUS',
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `graduations_student_year_unique` (`student_id`, `academic_year_id`),
    UNIQUE INDEX `graduations_certificate_number_unique` (`certificate_number`),
    UNIQUE INDEX `graduations_skhu_number_unique` (`skhu_number`),
    INDEX `graduations_student_index` (`student_id`),
    INDEX `graduations_school_index` (`school_id`),
    INDEX `graduations_academic_year_index` (`academic_year_id`),
    INDEX `graduations_classroom_index` (`classroom_id`),
    INDEX `graduations_status_index` (`status`),
    INDEX `graduations_graduation_date_index` (`graduation_date`),
    INDEX `graduations_deleted_at_index` (`deleted_at`),
    INDEX `graduations_created_by_index` (`created_by`),
    INDEX `graduations_updated_by_index` (`updated_by`),
    CONSTRAINT `graduations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `graduations_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `graduations_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `graduations_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 71. ALTER STUDENT_EXTRACURRICULARS — Penilaian Ekskul (GAP #6)
-- ============================================
ALTER TABLE `student_extracurriculars`
    ADD COLUMN `grade_letter` VARCHAR(2) NULL AFTER `status`,
    ADD COLUMN `predicate` VARCHAR(50) NULL AFTER `grade_letter`,
    ADD COLUMN `description` TEXT NULL AFTER `predicate`,
    ADD COLUMN `certificate_url` TEXT NULL AFTER `description`,
    ADD COLUMN `achievement_notes` TEXT NULL AFTER `certificate_url`,
    ADD COLUMN `evaluated_by` BIGINT UNSIGNED NULL AFTER `achievement_notes`,
    ADD COLUMN `evaluated_at` TIMESTAMP NULL AFTER `evaluated_by`,
    ADD INDEX `student_extracurriculars_evaluated_by_index` (`evaluated_by`),
    ADD CONSTRAINT `student_extracurriculars_evaluated_by_foreign`
        FOREIGN KEY (`evaluated_by`) REFERENCES `teachers` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE;

-- Tabel kehadiran ekskul (opsional tapi berguna)
CREATE TABLE `extracurricular_attendances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_extracurricular_id` BIGINT UNSIGNED NOT NULL,
    `meeting_date` DATE NOT NULL,
    `status` VARCHAR(20) NULL,
    `notes` TEXT NULL,
    `recorded_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `extracurricular_attendances_unique` (`student_extracurricular_id`, `meeting_date`),
    INDEX `extracurricular_attendances_student_extracurricular_index` (`student_extracurricular_id`),
    INDEX `extracurricular_attendances_date_index` (`meeting_date`),
    INDEX `extracurricular_attendances_status_index` (`status`),
    INDEX `extracurricular_attendances_recorded_by_index` (`recorded_by`),
    INDEX `extracurricular_attendances_deleted_at_index` (`deleted_at`),
    INDEX `extracurricular_attendances_created_by_index` (`created_by`),
    INDEX `extracurricular_attendances_updated_by_index` (`updated_by`),
    CONSTRAINT `extracurricular_attendances_student_extracurricular_id_foreign`
        FOREIGN KEY (`student_extracurricular_id`) REFERENCES `student_extracurriculars` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `extracurricular_attendances_recorded_by_foreign`
        FOREIGN KEY (`recorded_by`) REFERENCES `teachers` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📦 BATCH 8 — Operasional, Komunikasi & Keamanan

-- ============================================
-- 72. ANNOUNCEMENTS (Pengumuman) — GAP #7
-- ============================================
CREATE TABLE `announcements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `content` LONGTEXT NULL,
    `audience` VARCHAR(30) NULL,
    `attachment_url` TEXT NULL,
    `priority` VARCHAR(20) NOT NULL DEFAULT 'NORMAL',
    `published_at` TIMESTAMP NULL,
    `expired_at` TIMESTAMP NULL,
    `is_pinned` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `announcements_school_index` (`school_id`),
    INDEX `announcements_audience_index` (`audience`),
    INDEX `announcements_priority_index` (`priority`),
    INDEX `announcements_published_at_index` (`published_at`),
    INDEX `announcements_expired_at_index` (`expired_at`),
    INDEX `announcements_is_pinned_index` (`is_pinned`),
    INDEX `announcements_deleted_at_index` (`deleted_at`),
    INDEX `announcements_created_by_index` (`created_by`),
    INDEX `announcements_updated_by_index` (`updated_by`),
    CONSTRAINT `announcements_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 73. NOTIFICATIONS — GAP #7
-- ============================================
CREATE TABLE `notifications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `type` VARCHAR(30) NULL,
    `title` VARCHAR(200) NULL,
    `message` TEXT NULL,
    `reference_table` VARCHAR(50) NULL,
    `reference_id` BIGINT UNSIGNED NULL,
    `channel` VARCHAR(20) NOT NULL DEFAULT 'IN_APP',
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `read_at` TIMESTAMP NULL,
    `sent_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `notifications_user_index` (`user_id`),
    INDEX `notifications_type_index` (`type`),
    INDEX `notifications_channel_index` (`channel`),
    INDEX `notifications_is_read_index` (`is_read`),
    INDEX `notifications_reference_index` (`reference_table`, `reference_id`),
    INDEX `notifications_sent_at_index` (`sent_at`),
    INDEX `notifications_deleted_at_index` (`deleted_at`),
    INDEX `notifications_created_by_index` (`created_by`),
    INDEX `notifications_updated_by_index` (`updated_by`),
    CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 74. MESSAGES (Pesan Langsung) — GAP #7
-- ============================================
CREATE TABLE `messages` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `sender_id` BIGINT UNSIGNED NOT NULL,
    `receiver_id` BIGINT UNSIGNED NOT NULL,
    `subject` VARCHAR(200) NULL,
    `content` TEXT NOT NULL,
    `parent_message_id` BIGINT UNSIGNED NULL,
    `attachment_url` TEXT NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `read_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `messages_sender_index` (`sender_id`),
    INDEX `messages_receiver_index` (`receiver_id`),
    INDEX `messages_parent_index` (`parent_message_id`),
    INDEX `messages_is_read_index` (`is_read`),
    INDEX `messages_deleted_at_index` (`deleted_at`),
    INDEX `messages_created_by_index` (`created_by`),
    INDEX `messages_updated_by_index` (`updated_by`),
    CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `messages_parent_message_id_foreign` FOREIGN KEY (`parent_message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 75. PERMISSION_REQUESTS (Surat Izin / Dispensasi) — GAP #7
-- ============================================
CREATE TABLE `permission_requests` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `request_type` VARCHAR(30) NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `reason` TEXT NOT NULL,
    `attachment_url` TEXT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `rejection_notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `permission_requests_student_index` (`student_id`),
    INDEX `permission_requests_school_index` (`school_id`),
    INDEX `permission_requests_type_index` (`request_type`),
    INDEX `permission_requests_status_index` (`status`),
    INDEX `permission_requests_date_index` (`start_date`, `end_date`),
    INDEX `permission_requests_approved_by_index` (`approved_by`),
    INDEX `permission_requests_deleted_at_index` (`deleted_at`),
    INDEX `permission_requests_created_by_index` (`created_by`),
    INDEX `permission_requests_updated_by_index` (`updated_by`),
    CONSTRAINT `permission_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `permission_requests_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `permission_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 76. STUDENT_DISCIPLINES (Pelanggaran & Poin Disiplin) — GAP #8
-- ============================================
CREATE TABLE `student_disciplines` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `category` VARCHAR(50) NULL,
    `description` TEXT NULL,
    `points` INTEGER NOT NULL DEFAULT 0,
    `action_taken` TEXT NULL,
    `incident_date` DATE NULL,
    `reported_by` BIGINT UNSIGNED NULL,
    `witness` VARCHAR(200) NULL,
    `attachment_url` TEXT NULL,
    `parent_notified` TINYINT(1) NOT NULL DEFAULT 0,
    `parent_notified_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `student_disciplines_student_index` (`student_id`),
    INDEX `student_disciplines_school_index` (`school_id`),
    INDEX `student_disciplines_category_index` (`category`),
    INDEX `student_disciplines_incident_date_index` (`incident_date`),
    INDEX `student_disciplines_reported_by_index` (`reported_by`),
    INDEX `student_disciplines_deleted_at_index` (`deleted_at`),
    INDEX `student_disciplines_created_by_index` (`created_by`),
    INDEX `student_disciplines_updated_by_index` (`updated_by`),
    CONSTRAINT `student_disciplines_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_disciplines_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_disciplines_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 77. STUDENT_HEALTH_RECORDS (Riwayat Kesehatan / UKS) — GAP #9
-- ============================================
CREATE TABLE `student_health_records` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `record_date` DATE NOT NULL,
    `complaint` TEXT NULL,
    `diagnosis` TEXT NULL,
    `treatment` TEXT NULL,
    `height_cm` DECIMAL(5,2) NULL,
    `weight_kg` DECIMAL(5,2) NULL,
    `blood_type` VARCHAR(3) NULL,
    `blood_pressure` VARCHAR(20) NULL,
    `allergies` TEXT NULL,
    `vaccination_notes` TEXT NULL,
    `handled_by` BIGINT UNSIGNED NULL,
    `referred_to` VARCHAR(200) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `student_health_records_student_index` (`student_id`),
    INDEX `student_health_records_school_index` (`school_id`),
    INDEX `student_health_records_record_date_index` (`record_date`),
    INDEX `student_health_records_handled_by_index` (`handled_by`),
    INDEX `student_health_records_deleted_at_index` (`deleted_at`),
    INDEX `student_health_records_created_by_index` (`created_by`),
    INDEX `student_health_records_updated_by_index` (`updated_by`),
    CONSTRAINT `student_health_records_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_health_records_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_health_records_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 78. COUNSELING_SESSIONS (Bimbingan Konseling) — GAP #9
-- ============================================
CREATE TABLE `counseling_sessions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `counselor_id` BIGINT UNSIGNED NULL,
    `session_date` TIMESTAMP NULL,
    `category` VARCHAR(30) NULL,
    `problem` TEXT NULL,
    `action` TEXT NULL,
    `follow_up` TEXT NULL,
    `follow_up_date` DATE NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'OPEN',
    `is_confidential` TINYINT(1) NOT NULL DEFAULT 1,
    `parent_involved` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `counseling_sessions_student_index` (`student_id`),
    INDEX `counseling_sessions_school_index` (`school_id`),
    INDEX `counseling_sessions_counselor_index` (`counselor_id`),
    INDEX `counseling_sessions_category_index` (`category`),
    INDEX `counseling_sessions_status_index` (`status`),
    INDEX `counseling_sessions_session_date_index` (`session_date`),
    INDEX `counseling_sessions_deleted_at_index` (`deleted_at`),
    INDEX `counseling_sessions_created_by_index` (`created_by`),
    INDEX `counseling_sessions_updated_by_index` (`updated_by`),
    CONSTRAINT `counseling_sessions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `counseling_sessions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `counseling_sessions_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 79. LIBRARY_BOOKS (Perpustakaan) — GAP #10
-- ============================================
CREATE TABLE `library_books` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `isbn` VARCHAR(20) NULL,
    `code` VARCHAR(50) NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `author` VARCHAR(150) NULL,
    `publisher` VARCHAR(150) NULL,
    `year_published` INTEGER NULL,
    `category` VARCHAR(50) NULL,
    `description` TEXT NULL,
    `cover_url` TEXT NULL,
    `total_copies` INTEGER NOT NULL DEFAULT 1,
    `available_copies` INTEGER NOT NULL DEFAULT 1,
    `shelf_location` VARCHAR(50) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `library_books_school_code_unique` (`school_id`, `code`),
    INDEX `library_books_school_index` (`school_id`),
    INDEX `library_books_isbn_index` (`isbn`),
    INDEX `library_books_title_index` (`title`),
    INDEX `library_books_category_index` (`category`),
    INDEX `library_books_author_index` (`author`),
    INDEX `library_books_deleted_at_index` (`deleted_at`),
    INDEX `library_books_created_by_index` (`created_by`),
    INDEX `library_books_updated_by_index` (`updated_by`),
    CONSTRAINT `library_books_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 80. LIBRARY_LOANS (Peminjaman Buku) — GAP #10
-- ============================================
CREATE TABLE `library_loans` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `book_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `loan_date` DATE NOT NULL,
    `due_date` DATE NOT NULL,
    `return_date` DATE NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'BORROWED',
    `fine_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `fine_paid` TINYINT(1) NOT NULL DEFAULT 0,
    `notes` TEXT NULL,
    `handled_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `library_loans_book_index` (`book_id`),
    INDEX `library_loans_student_index` (`student_id`),
    INDEX `library_loans_teacher_index` (`teacher_id`),
    INDEX `library_loans_school_index` (`school_id`),
    INDEX `library_loans_status_index` (`status`),
    INDEX `library_loans_loan_date_index` (`loan_date`),
    INDEX `library_loans_due_date_index` (`due_date`),
    INDEX `library_loans_handled_by_index` (`handled_by`),
    INDEX `library_loans_deleted_at_index` (`deleted_at`),
    INDEX `library_loans_created_by_index` (`created_by`),
    INDEX `library_loans_updated_by_index` (`updated_by`),
    CONSTRAINT `library_loans_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `library_books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `library_loans_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `library_loans_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `library_loans_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `library_loans_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 81. ASSETS (Inventaris Aset) — GAP #10
-- ============================================
CREATE TABLE `assets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `category` VARCHAR(50) NULL,
    `brand` VARCHAR(100) NULL,
    `model` VARCHAR(100) NULL,
    `serial_number` VARCHAR(100) NULL,
    `purchase_date` DATE NULL,
    `purchase_price` DECIMAL(15,2) NULL,
    `supplier` VARCHAR(150) NULL,
    `location` VARCHAR(150) NULL,
    `condition` VARCHAR(20) NOT NULL DEFAULT 'GOOD',
    `assigned_to` BIGINT UNSIGNED NULL,
    `warranty_end` DATE NULL,
    `depreciation_value` DECIMAL(15,2) NULL,
    `notes` TEXT NULL,
    `photo_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `assets_school_code_unique` (`school_id`, `code`),
    INDEX `assets_school_index` (`school_id`),
    INDEX `assets_category_index` (`category`),
    INDEX `assets_condition_index` (`condition`),
    INDEX `assets_location_index` (`location`),
    INDEX `assets_assigned_to_index` (`assigned_to`),
    INDEX `assets_purchase_date_index` (`purchase_date`),
    INDEX `assets_deleted_at_index` (`deleted_at`),
    INDEX `assets_created_by_index` (`created_by`),
    INDEX `assets_updated_by_index` (`updated_by`),
    CONSTRAINT `assets_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `assets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 82. ACADEMIC_CALENDAR_EVENTS (Kalender Akademik) — GAP #11
-- ============================================
CREATE TABLE `academic_calendar_events` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `event_type` VARCHAR(30) NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NULL,
    `start_time` TIME NULL,
    `end_time` TIME NULL,
    `location` VARCHAR(200) NULL,
    `is_holiday` TINYINT(1) NOT NULL DEFAULT 0,
    `is_all_day` TINYINT(1) NOT NULL DEFAULT 0,
    `color` VARCHAR(20) NULL,
    `attachment_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `academic_calendar_events_school_index` (`school_id`),
    INDEX `academic_calendar_events_academic_year_index` (`academic_year_id`),
    INDEX `academic_calendar_events_type_index` (`event_type`),
    INDEX `academic_calendar_events_dates_index` (`start_date`, `end_date`),
    INDEX `academic_calendar_events_is_holiday_index` (`is_holiday`),
    INDEX `academic_calendar_events_deleted_at_index` (`deleted_at`),
    INDEX `academic_calendar_events_created_by_index` (`created_by`),
    INDEX `academic_calendar_events_updated_by_index` (`updated_by`),
    CONSTRAINT `academic_calendar_events_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `academic_calendar_events_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 83. ROLES (Peran) — GAP #12
-- ============================================
CREATE TABLE `roles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `level` INTEGER NOT NULL DEFAULT 0,
    `is_system` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `roles_school_code_unique` (`school_id`, `code`),
    INDEX `roles_school_index` (`school_id`),
    INDEX `roles_code_index` (`code`),
    INDEX `roles_is_system_index` (`is_system`),
    INDEX `roles_deleted_at_index` (`deleted_at`),
    INDEX `roles_created_by_index` (`created_by`),
    INDEX `roles_updated_by_index` (`updated_by`),
    CONSTRAINT `roles_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 84. PERMISSIONS (Hak Akses) — GAP #12
-- ============================================
CREATE TABLE `permissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(100) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `module` VARCHAR(50) NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `permissions_code_unique` (`code`),
    INDEX `permissions_module_index` (`module`),
    INDEX `permissions_deleted_at_index` (`deleted_at`),
    INDEX `permissions_created_by_index` (`created_by`),
    INDEX `permissions_updated_by_index` (`updated_by`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 85. ROLE_PERMISSIONS (Pivot) — GAP #12
-- ============================================
CREATE TABLE `role_permissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `role_id` BIGINT UNSIGNED NOT NULL,
    `permission_id` BIGINT UNSIGNED NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `role_permissions_unique` (`role_id`, `permission_id`),
    INDEX `role_permissions_role_index` (`role_id`),
    INDEX `role_permissions_permission_index` (`permission_id`),
    INDEX `role_permissions_deleted_at_index` (`deleted_at`),
    CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 86. USER_ROLES (Pivot) — GAP #12
-- ============================================
CREATE TABLE `user_roles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NULL,
    `assigned_at` TIMESTAMP NULL,
    `assigned_by` BIGINT UNSIGNED NULL,
    `expired_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `user_roles_user_role_school_unique` (`user_id`, `role_id`, `school_id`),
    INDEX `user_roles_user_index` (`user_id`),
    INDEX `user_roles_role_index` (`role_id`),
    INDEX `user_roles_school_index` (`school_id`),
    INDEX `user_roles_assigned_by_index` (`assigned_by`),
    INDEX `user_roles_deleted_at_index` (`deleted_at`),
    INDEX `user_roles_created_by_index` (`created_by`),
    INDEX `user_roles_updated_by_index` (`updated_by`),
    CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `user_roles_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `user_roles_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

🗂️ 1. Master Mata Uang & Kurs

-- ============================================
-- 87. CURRENCIES (Master Mata Uang)
-- ============================================
CREATE TABLE `currencies` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(3) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `symbol` VARCHAR(10) NULL,
    `decimal_places` TINYINT UNSIGNED NOT NULL DEFAULT 2,
    `is_base` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `currencies_code_unique` (`code`),
    INDEX `currencies_is_base_index` (`is_base`),
    INDEX `currencies_is_active_index` (`is_active`),
    INDEX `currencies_deleted_at_index` (`deleted_at`),
    INDEX `currencies_created_by_index` (`created_by`),
    INDEX `currencies_updated_by_index` (`updated_by`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 88. EXCHANGE_RATES (Kurs Harian)
-- ============================================
CREATE TABLE `exchange_rates` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `currency_id` BIGINT UNSIGNED NOT NULL,
    `base_currency_id` BIGINT UNSIGNED NOT NULL,
    `rate_date` DATE NOT NULL,
    `rate` DECIMAL(20,8) NOT NULL,
    `source` VARCHAR(50) NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `exchange_rates_currency_date_unique` (`currency_id`, `base_currency_id`, `rate_date`),
    INDEX `exchange_rates_currency_index` (`currency_id`),
    INDEX `exchange_rates_base_index` (`base_currency_id`),
    INDEX `exchange_rates_date_index` (`rate_date`),
    INDEX `exchange_rates_deleted_at_index` (`deleted_at`),
    INDEX `exchange_rates_created_by_index` (`created_by`),
    INDEX `exchange_rates_updated_by_index` (`updated_by`),
    CONSTRAINT `exchange_rates_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `exchange_rates_base_currency_id_foreign` FOREIGN KEY (`base_currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📊 2. Chart of Accounts (COA) — Hierarki Custom
-- ============================================
-- 89. ACCOUNT_TYPES (Tipe Akun: Aset, Kewajiban, Ekuitas, Pendapatan, Beban)
-- ============================================
CREATE TABLE `account_types` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `normal_balance` VARCHAR(10) NOT NULL,
    `report_group` VARCHAR(30) NULL,
    `order_index` INTEGER NOT NULL DEFAULT 0,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `account_types_code_unique` (`code`),
    INDEX `account_types_report_group_index` (`report_group`),
    INDEX `account_types_order_index` (`order_index`),
    INDEX `account_types_deleted_at_index` (`deleted_at`),
    INDEX `account_types_created_by_index` (`created_by`),
    INDEX `account_types_updated_by_index` (`updated_by`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 90. accountS (COA Custom dengan Hierarki)
-- ============================================
CREATE TABLE `accounts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NULL,
    `account_type_id` BIGINT UNSIGNED NOT NULL,
    `parent_id` BIGINT UNSIGNED NULL,
    `account_code` VARCHAR(20) NOT NULL,
    `account_name` VARCHAR(150) NOT NULL,
    `level` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `is_header` TINYINT(1) NOT NULL DEFAULT 0,
    `is_postable` TINYINT(1) NOT NULL DEFAULT 1,
    `currency_id` BIGINT UNSIGNED NULL,
    `normal_balance` VARCHAR(10) NOT NULL,
    `opening_balance` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `current_balance` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `accounts_school_code_unique` (`school_id`, `account_code`),
    INDEX `accounts_school_index` (`school_id`),
    INDEX `accounts_type_index` (`account_type_id`),
    INDEX `accounts_parent_index` (`parent_id`),
    INDEX `accounts_code_index` (`account_code`),
    INDEX `accounts_level_index` (`level`),
    INDEX `accounts_is_header_index` (`is_header`),
    INDEX `accounts_is_postable_index` (`is_postable`),
    INDEX `accounts_currency_index` (`currency_id`),
    INDEX `accounts_deleted_at_index` (`deleted_at`),
    INDEX `accounts_created_by_index` (`created_by`),
    INDEX `accounts_updated_by_index` (`updated_by`),
    CONSTRAINT `accounts_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `accounts_account_type_id_foreign` FOREIGN KEY (`account_type_id`) REFERENCES `account_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `accounts_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📅 3. Periode Akuntansi & Tutup Buku

-- ============================================
-- 91. FISCAL_PERIODS (Periode Akuntansi Januari-Desember)
-- ============================================
CREATE TABLE `fiscal_periods` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `year` SMALLINT UNSIGNED NOT NULL,
    `month` TINYINT UNSIGNED NULL,
    `is_closed` TINYINT(1) NOT NULL DEFAULT 0,
    `closed_at` TIMESTAMP NULL,
    `closed_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fiscal_periods_school_code_unique` (`school_id`, `code`),
    INDEX `fiscal_periods_school_index` (`school_id`),
    INDEX `fiscal_periods_year_index` (`year`),
    INDEX `fiscal_periods_month_index` (`month`),
    INDEX `fiscal_periods_date_range_index` (`start_date`, `end_date`),
    INDEX `fiscal_periods_is_closed_index` (`is_closed`),
    INDEX `fiscal_periods_closed_by_index` (`closed_by`),
    INDEX `fiscal_periods_deleted_at_index` (`deleted_at`),
    INDEX `fiscal_periods_created_by_index` (`created_by`),
    INDEX `fiscal_periods_updated_by_index` (`updated_by`),
    CONSTRAINT `fiscal_periods_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fiscal_periods_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 92. FISCAL_PERIOD_CLOSINGS (Tutup Buku)
-- ============================================
CREATE TABLE `fiscal_period_closings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `fiscal_period_id` BIGINT UNSIGNED NOT NULL,
    `closing_type` VARCHAR(20) NOT NULL,
    `closing_date` DATE NOT NULL,
    `total_debit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_credit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `net_income` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `retained_earnings` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `snapshot_data` JSON NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `fiscal_period_closings_period_index` (`fiscal_period_id`),
    INDEX `fiscal_period_closings_type_index` (`closing_type`),
    INDEX `fiscal_period_closings_date_index` (`closing_date`),
    INDEX `fiscal_period_closings_deleted_at_index` (`deleted_at`),
    INDEX `fiscal_period_closings_created_by_index` (`created_by`),
    INDEX `fiscal_period_closings_updated_by_index` (`updated_by`),
    CONSTRAINT `fiscal_period_closings_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📓 4. Jurnal Umum (Double-Entry dengan Multicurrency)

-- ============================================
-- 93. JOURNAL_ENTRIES (Header Jurnal)
-- ============================================
CREATE TABLE `journal_entries` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `accounting_period_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_period_id` BIGINT UNSIGNED NOT NULL,
    `journal_number` VARCHAR(50) NOT NULL,
    `journal_date` DATE NOT NULL,
    `journal_type` VARCHAR(30) NOT NULL DEFAULT 'GENERAL',
    `reference_type` VARCHAR(50) NULL,
    `reference_id` BIGINT UNSIGNED NULL,
    `description` TEXT NULL,
    `total_debit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_credit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'DRAFT',
    `posted_at` TIMESTAMP NULL,
    `posted_by` BIGINT UNSIGNED NULL,
    `reversed_by_id` BIGINT UNSIGNED NULL,
    `reversal_reason` TEXT NULL,
    `attachment_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `journal_entries_school_number_unique` (`school_id`, `journal_number`),
    INDEX `journal_entries_school_index` (`school_id`),
    INDEX `journal_entries_fiscal_period_index` (`fiscal_period_id`),
    INDEX `journal_entries_date_index` (`journal_date`),
    INDEX `journal_entries_type_index` (`journal_type`),
    INDEX `journal_entries_status_index` (`status`),
    INDEX `journal_entries_reference_index` (`reference_type`, `reference_id`),
    INDEX `journal_entries_posted_by_index` (`posted_by`),
    INDEX `journal_entries_reversed_by_index` (`reversed_by_id`),
    INDEX `journal_entries_deleted_at_index` (`deleted_at`),
    INDEX `journal_entries_created_by_index` (`created_by`),
    INDEX `journal_entries_updated_by_index` (`updated_by`),
    CONSTRAINT `journal_entries_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `journal_entries_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `journal_entries_posted_by_foreign` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `journal_entries_reversed_by_id_foreign` FOREIGN KEY (`reversed_by_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 94. JOURNAL_ENTRY_LINES (Detail Jurnal dengan Multicurrency)
-- ============================================
CREATE TABLE `journal_entry_lines` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `journal_entry_id` BIGINT UNSIGNED NOT NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `line_number` INTEGER NOT NULL DEFAULT 1,
    `description` TEXT NULL,
    `debit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `credit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `currency_id` BIGINT UNSIGNED NOT NULL,
    `exchange_rate` DECIMAL(20,8) NOT NULL DEFAULT 1,
    `foreign_debit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `foreign_credit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `functional_debit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `functional_credit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `vendor_id` BIGINT UNSIGNED NULL,
    `student_id` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `journal_entry_lines_entry_index` (`journal_entry_id`),
    INDEX `journal_entry_lines_account_index` (`account_id`),
    INDEX `journal_entry_lines_currency_index` (`currency_id`),
    INDEX `journal_entry_lines_vendor_index` (`vendor_id`),
    INDEX `journal_entry_lines_student_index` (`student_id`),
    INDEX `journal_entry_lines_line_number_index` (`line_number`),
    INDEX `journal_entry_lines_deleted_at_index` (`deleted_at`),
    INDEX `journal_entry_lines_created_by_index` (`created_by`),
    INDEX `journal_entry_lines_updated_by_index` (`updated_by`),
    CONSTRAINT `journal_entry_lines_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `journal_entry_lines_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `journal_entry_lines_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `journal_entry_lines_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `journal_entry_lines_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

🏦 5. Rekening Bank & Rekonsiliasi (Upload CSV)

-- ============================================
-- 95. VENDORS (Supplier/Vendor dengan NPWP & PKP)
-- ============================================
CREATE TABLE `vendors` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `legal_name` VARCHAR(200) NULL,
    `vendor_type` VARCHAR(20) NOT NULL DEFAULT 'COMPANY',
    `npwp` VARCHAR(30) NULL,
    `is_pkp` TINYINT(1) NOT NULL DEFAULT 0,
    `pkp_number` VARCHAR(50) NULL,
    `ppn_rate` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `contact_person` VARCHAR(150) NULL,
    `phone` VARCHAR(20) NULL,
    `email` VARCHAR(100) NULL,
    `website` VARCHAR(100) NULL,
    `address` TEXT NULL,
    `city` VARCHAR(100) NULL,
    `province` VARCHAR(100) NULL,
    `postal_code` VARCHAR(10) NULL,
    `bank_name` VARCHAR(50) NULL,
    `bank_account_number` VARCHAR(50) NULL,
    `bank_account_name` VARCHAR(150) NULL,
    `payment_terms_days` INTEGER NOT NULL DEFAULT 30,
    `payable_account_id` BIGINT UNSIGNED NOT NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `vendors_school_code_unique` (`school_id`, `code`),
    INDEX `vendors_school_index` (`school_id`),
    INDEX `vendors_name_index` (`name`),
    INDEX `vendors_npwp_index` (`npwp`),
    INDEX `vendors_is_pkp_index` (`is_pkp`),
    INDEX `vendors_vendor_type_index` (`vendor_type`),
    INDEX `vendors_deleted_at_index` (`deleted_at`),
    INDEX `vendors_created_by_index` (`created_by`),
    INDEX `vendors_updated_by_index` (`updated_by`),
    CONSTRAINT `vendors_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 96. BANK_ACCOUNTS (Rekening Bank Sekolah)
-- ============================================
CREATE TABLE `bank_accounts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `bank_name` VARCHAR(50) NOT NULL,
    `branch` VARCHAR(100) NULL,
    `account_number` VARCHAR(50) NOT NULL,
    `account_name` VARCHAR(150) NOT NULL,
    `currency_id` BIGINT UNSIGNED NOT NULL,
    `opening_balance` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `current_balance` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `is_cash_account` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `swift_code` VARCHAR(50) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `bank_accounts_school_code_unique` (`school_id`, `code`),
    INDEX `bank_accounts_school_index` (`school_id`),
    INDEX `bank_accounts_coa_index` (`account_id`),
    INDEX `bank_accounts_currency_index` (`currency_id`),
    INDEX `bank_accounts_number_index` (`account_number`),
    INDEX `bank_accounts_is_cash_index` (`is_cash_account`),
    INDEX `bank_accounts_deleted_at_index` (`deleted_at`),
    INDEX `bank_accounts_created_by_index` (`created_by`),
    INDEX `bank_accounts_updated_by_index` (`updated_by`),
    CONSTRAINT `bank_accounts_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bank_accounts_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bank_accounts_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 97. BANK_MUTATIONS (Mutasi Bank dari Upload CSV)
-- ============================================
CREATE TABLE `bank_mutations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `bank_account_id` BIGINT UNSIGNED NOT NULL,
    `import_batch_id` BIGINT UNSIGNED NULL,
    `mutation_date` DATE NOT NULL,
    `value_date` DATE NULL,
    `description` TEXT NULL,
    `reference_number` VARCHAR(100) NULL,
    `debit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `credit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `balance` DECIMAL(20,2) NULL,
    `currency_id` BIGINT UNSIGNED NOT NULL,
    `exchange_rate` DECIMAL(20,8) NOT NULL DEFAULT 1,
    `functional_debit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `functional_credit` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `reconciliation_status` VARCHAR(20) NOT NULL DEFAULT 'UNMATCHED',
    `matched_journal_entry_id` BIGINT UNSIGNED NULL,
    `matched_at` TIMESTAMP NULL,
    `matched_by` BIGINT UNSIGNED NULL,
    `match_notes` TEXT NULL,
    `raw_data` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `bank_mutations_school_index` (`school_id`),
    INDEX `bank_mutations_bank_account_index` (`bank_account_id`),
    INDEX `bank_mutations_import_batch_index` (`import_batch_id`),
    INDEX `bank_mutations_date_index` (`mutation_date`),
    INDEX `bank_mutations_reference_index` (`reference_number`),
    INDEX `bank_mutations_status_index` (`reconciliation_status`),
    INDEX `bank_mutations_matched_entry_index` (`matched_journal_entry_id`),
    INDEX `bank_mutations_matched_by_index` (`matched_by`),
    INDEX `bank_mutations_currency_index` (`currency_id`),
    INDEX `bank_mutations_deleted_at_index` (`deleted_at`),
    INDEX `bank_mutations_created_by_index` (`created_by`),
    INDEX `bank_mutations_updated_by_index` (`updated_by`),
    CONSTRAINT `bank_mutations_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bank_mutations_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bank_mutations_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bank_mutations_matched_journal_entry_id_foreign` FOREIGN KEY (`matched_journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `bank_mutations_matched_by_foreign` FOREIGN KEY (`matched_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 98. BANK_MUTATION_IMPORTS (Batch Upload CSV)
-- ============================================
CREATE TABLE `bank_mutation_imports` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `bank_account_id` BIGINT UNSIGNED NOT NULL,
    `file_name` VARCHAR(200) NULL,
    `file_url` TEXT NULL,
    `file_size` BIGINT NULL,
    `total_rows` INTEGER NOT NULL DEFAULT 0,
    `success_rows` INTEGER NOT NULL DEFAULT 0,
    `failed_rows` INTEGER NOT NULL DEFAULT 0,
    `duplicate_rows` INTEGER NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `error_log` JSON NULL,
    `imported_at` TIMESTAMP NULL,
    `imported_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `bank_mutation_imports_school_index` (`school_id`),
    INDEX `bank_mutation_imports_bank_account_index` (`bank_account_id`),
    INDEX `bank_mutation_imports_status_index` (`status`),
    INDEX `bank_mutation_imports_imported_by_index` (`imported_by`),
    INDEX `bank_mutation_imports_deleted_at_index` (`deleted_at`),
    INDEX `bank_mutation_imports_created_by_index` (`created_by`),
    INDEX `bank_mutation_imports_updated_by_index` (`updated_by`),
    CONSTRAINT `bank_mutation_imports_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bank_mutation_imports_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bank_mutation_imports_imported_by_foreign` FOREIGN KEY (`imported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 99. BANK_RECONCILIATIONS (Sesi Rekonsiliasi)
-- ============================================
CREATE TABLE `bank_reconciliations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `bank_account_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_period_id` BIGINT UNSIGNED NOT NULL,
    `reconciliation_number` VARCHAR(50) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `opening_balance` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `closing_balance` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `system_balance` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `difference` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `matched_count` INTEGER NOT NULL DEFAULT 0,
    `unmatched_count` INTEGER NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'DRAFT',
    `completed_at` TIMESTAMP NULL,
    `completed_by` BIGINT UNSIGNED NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `bank_reconciliations_school_number_unique` (`school_id`, `reconciliation_number`),
    INDEX `bank_reconciliations_school_index` (`school_id`),
    INDEX `bank_reconciliations_bank_account_index` (`bank_account_id`),
    INDEX `bank_reconciliations_fiscal_period_index` (`fiscal_period_id`),
    INDEX `bank_reconciliations_status_index` (`status`),
    INDEX `bank_reconciliations_date_index` (`start_date`, `end_date`),
    INDEX `bank_reconciliations_completed_by_index` (`completed_by`),
    INDEX `bank_reconciliations_deleted_at_index` (`deleted_at`),
    INDEX `bank_reconciliations_created_by_index` (`created_by`),
    INDEX `bank_reconciliations_updated_by_index` (`updated_by`),
    CONSTRAINT `bank_reconciliations_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bank_reconciliations_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bank_reconciliations_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bank_reconciliations_completed_by_foreign` FOREIGN KEY (`completed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 100. BANK_RECONCILIATION_ITEMS (Detail Item Rekonsiliasi)
-- ============================================
CREATE TABLE `bank_reconciliation_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `bank_reconciliation_id` BIGINT UNSIGNED NOT NULL,
    `bank_mutation_id` BIGINT UNSIGNED NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `match_type` VARCHAR(20) NOT NULL DEFAULT 'AUTO',
    `match_score` DECIMAL(5,2) NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'MATCHED',
    `difference_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `bank_reconciliation_items_reconciliation_index` (`bank_reconciliation_id`),
    INDEX `bank_reconciliation_items_mutation_index` (`bank_mutation_id`),
    INDEX `bank_reconciliation_items_entry_index` (`journal_entry_id`),
    INDEX `bank_reconciliation_items_match_type_index` (`match_type`),
    INDEX `bank_reconciliation_items_status_index` (`status`),
    INDEX `bank_reconciliation_items_deleted_at_index` (`deleted_at`),
    INDEX `bank_reconciliation_items_created_by_index` (`created_by`),
    INDEX `bank_reconciliation_items_updated_by_index` (`updated_by`),
    CONSTRAINT `bank_reconciliation_items_reconciliation_id_foreign` FOREIGN KEY (`bank_reconciliation_id`) REFERENCES `bank_reconciliations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bank_reconciliation_items_bank_mutation_id_foreign` FOREIGN KEY (`bank_mutation_id`) REFERENCES `bank_mutations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bank_reconciliation_items_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

🧾 6. Vendor Invoice & Payment

-- ============================================
-- 101. VENDOR_INVOICES (Invoice dari Vendor)
-- ============================================
CREATE TABLE `vendor_invoices` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `vendor_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_period_id` BIGINT UNSIGNED NOT NULL,
    `currency_id` BIGINT UNSIGNED NOT NULL,
    `exchange_rate` DECIMAL(20,8) NOT NULL DEFAULT 1,
    `invoice_number` VARCHAR(100) NOT NULL,
    `internal_number` VARCHAR(50) NOT NULL,
    `invoice_date` DATE NOT NULL,
    `due_date` DATE NULL,
    `received_date` DATE NULL,
    `subtotal` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `discount_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `tax_base` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `ppn_rate` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `ppn_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `pph_rate` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `pph_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `paid_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `outstanding_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `functional_total` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'UNPAID',
    `payment_status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `notes` TEXT NULL,
    `attachment_url` TEXT NULL,
    `posted_journal_entry_id` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `vendor_invoices_school_internal_unique` (`school_id`, `internal_number`),
    UNIQUE INDEX `vendor_invoices_vendor_number_unique` (`vendor_id`, `invoice_number`),
    INDEX `vendor_invoices_school_index` (`school_id`),
    INDEX `vendor_invoices_vendor_index` (`vendor_id`),
    INDEX `vendor_invoices_fiscal_period_index` (`fiscal_period_id`),
    INDEX `vendor_invoices_currency_index` (`currency_id`),
    INDEX `vendor_invoices_date_index` (`invoice_date`),
    INDEX `vendor_invoices_due_date_index` (`due_date`),
    INDEX `vendor_invoices_status_index` (`status`),
    INDEX `vendor_invoices_payment_status_index` (`payment_status`),
    INDEX `vendor_invoices_posted_entry_index` (`posted_journal_entry_id`),
    INDEX `vendor_invoices_deleted_at_index` (`deleted_at`),
    INDEX `vendor_invoices_created_by_index` (`created_by`),
    INDEX `vendor_invoices_updated_by_index` (`updated_by`),
    CONSTRAINT `vendor_invoices_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `vendor_invoices_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `vendor_invoices_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `vendor_invoices_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `vendor_invoices_posted_journal_entry_id_foreign` FOREIGN KEY (`posted_journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 102. VENDOR_INVOICE_LINES (Detail Invoice Vendor)
-- ============================================
CREATE TABLE `vendor_invoice_lines` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `vendor_invoice_id` BIGINT UNSIGNED NOT NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `description` TEXT NULL,
    `quantity` DECIMAL(20,4) NOT NULL DEFAULT 1,
    `unit` VARCHAR(20) NULL,
    `unit_price` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `discount_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `subtotal` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `tax_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `line_number` INTEGER NOT NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `vendor_invoice_lines_invoice_index` (`vendor_invoice_id`),
    INDEX `vendor_invoice_lines_coa_index` (`account_id`),
    INDEX `vendor_invoice_lines_line_number_index` (`line_number`),
    INDEX `vendor_invoice_lines_deleted_at_index` (`deleted_at`),
    INDEX `vendor_invoice_lines_created_by_index` (`created_by`),
    INDEX `vendor_invoice_lines_updated_by_index` (`updated_by`),
    CONSTRAINT `vendor_invoice_lines_vendor_invoice_id_foreign` FOREIGN KEY (`vendor_invoice_id`) REFERENCES `vendor_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `vendor_invoice_lines_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 103. VENDOR_PAYMENTS (Pembayaran ke Vendor)
-- ============================================
CREATE TABLE `vendor_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `vendor_id` BIGINT UNSIGNED NOT NULL,
    `vendor_invoice_id` BIGINT UNSIGNED NULL,
    `bank_account_id` BIGINT UNSIGNED NULL,
    `fiscal_period_id` BIGINT UNSIGNED NOT NULL,
    `currency_id` BIGINT UNSIGNED NOT NULL,
    `exchange_rate` DECIMAL(20,8) NOT NULL DEFAULT 1,
    `payment_number` VARCHAR(50) NOT NULL,
    `payment_date` DATE NOT NULL,
    `payment_method` VARCHAR(30) NULL,
    `reference_number` VARCHAR(100) NULL,
    `amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `pph_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `functional_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `notes` TEXT NULL,
    `attachment_url` TEXT NULL,
    `posted_journal_entry_id` BIGINT UNSIGNED NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'POSTED',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `vendor_payments_school_number_unique` (`school_id`, `payment_number`),
    INDEX `vendor_payments_school_index` (`school_id`),
    INDEX `vendor_payments_vendor_index` (`vendor_id`),
    INDEX `vendor_payments_invoice_index` (`vendor_invoice_id`),
    INDEX `vendor_payments_bank_account_index` (`bank_account_id`),
    INDEX `vendor_payments_fiscal_period_index` (`fiscal_period_id`),
    INDEX `vendor_payments_currency_index` (`currency_id`),
    INDEX `vendor_payments_date_index` (`payment_date`),
    INDEX `vendor_payments_status_index` (`status`),
    INDEX `vendor_payments_posted_entry_index` (`posted_journal_entry_id`),
    INDEX `vendor_payments_deleted_at_index` (`deleted_at`),
    INDEX `vendor_payments_created_by_index` (`created_by`),
    INDEX `vendor_payments_updated_by_index` (`updated_by`),
    CONSTRAINT `vendor_payments_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `vendor_payments_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `vendor_payments_vendor_invoice_id_foreign` FOREIGN KEY (`vendor_invoice_id`) REFERENCES `vendor_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `vendor_payments_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `vendor_payments_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `vendor_payments_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `vendor_payments_posted_journal_entry_id_foreign` FOREIGN KEY (`posted_journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

🧾 7. Konfigurasi Payment Gateway

-- ============================================
-- 104. PAYMENT_GATEWAYS (Konfigurasi Midtrans per Sekolah)
-- ============================================
CREATE TABLE `payment_gateways` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `provider` VARCHAR(30) NOT NULL DEFAULT 'MIDTRANS',
    `environment` VARCHAR(20) NOT NULL DEFAULT 'SANDBOX',
    `merchant_id` VARCHAR(100) NULL,
    `client_key` VARCHAR(255) NULL,
    `server_key` VARCHAR(255) NULL,
    `is_production` TINYINT(1) NOT NULL DEFAULT 0,
    `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
    `default_currency_id` BIGINT UNSIGNED NOT NULL,
    `expiry_minutes` INTEGER NOT NULL DEFAULT 1440,
    `config` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `payment_gateways_school_provider_env_unique` (`school_id`, `provider`, `environment`),
    INDEX `payment_gateways_school_index` (`school_id`),
    INDEX `payment_gateways_provider_index` (`provider`),
    INDEX `payment_gateways_environment_index` (`environment`),
    INDEX `payment_gateways_is_primary_index` (`is_primary`),
    INDEX `payment_gateways_currency_index` (`default_currency_id`),
    INDEX `payment_gateways_deleted_at_index` (`deleted_at`),
    INDEX `payment_gateways_created_by_index` (`created_by`),
    INDEX `payment_gateways_updated_by_index` (`updated_by`),
    CONSTRAINT `payment_gateways_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `payment_gateways_default_currency_id_foreign` FOREIGN KEY (`default_currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 105. PAYMENT_CHANNELS (Channel Aktif: QRIS, VA BCA, BNI, dll.)
-- ============================================
CREATE TABLE `payment_channels` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `payment_gateway_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `channel_type` VARCHAR(20) NOT NULL,
    `bank_code` VARCHAR(20) NULL,
    `logo_url` TEXT NULL,
    `admin_fee` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `admin_fee_type` VARCHAR(20) NOT NULL DEFAULT 'FIXED',
    `min_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `max_amount` DECIMAL(20,2) NULL,
    `expiry_minutes` INTEGER NULL,
    `sort_order` INTEGER NOT NULL DEFAULT 0,
    `config` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `payment_channels_gateway_code_unique` (`payment_gateway_id`, `code`),
    INDEX `payment_channels_gateway_index` (`payment_gateway_id`),
    INDEX `payment_channels_code_index` (`code`),
    INDEX `payment_channels_type_index` (`channel_type`),
    INDEX `payment_channels_bank_index` (`bank_code`),
    INDEX `payment_channels_sort_index` (`sort_order`),
    INDEX `payment_channels_is_active_index` (`is_active`),
    INDEX `payment_channels_deleted_at_index` (`deleted_at`),
    INDEX `payment_channels_created_by_index` (`created_by`),
    INDEX `payment_channels_updated_by_index` (`updated_by`),
    CONSTRAINT `payment_channels_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

💳 8. Transaksi Pembayaran

-- ============================================
-- 106. PAYMENT_TRANSACTIONS (Transaksi ke Midtrans)
-- ============================================
CREATE TABLE `payment_transactions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `payment_gateway_id` BIGINT UNSIGNED NOT NULL,
    `payment_channel_id` BIGINT UNSIGNED NULL,
    `student_invoice_id` BIGINT UNSIGNED NULL,
    `applicant_invoice_id` BIGINT UNSIGNED NULL,
    `student_id` BIGINT UNSIGNED NULL,
    `applicant_id` BIGINT UNSIGNED NULL,
    `order_id` VARCHAR(100) NOT NULL,
    `midtrans_transaction_id` VARCHAR(100) NULL,
    `transaction_type` VARCHAR(30) NOT NULL,
    `payment_type` VARCHAR(30) NULL,
    `currency_id` BIGINT UNSIGNED NOT NULL,
    `gross_amount` DECIMAL(20,2) NOT NULL,
    `admin_fee` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `net_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `exchange_rate` DECIMAL(20,8) NOT NULL DEFAULT 1,
    `functional_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `transaction_status` VARCHAR(30) NOT NULL DEFAULT 'PENDING',
    `fraud_status` VARCHAR(30) NULL,
    `status_code` VARCHAR(10) NULL,
    `status_message` TEXT NULL,
    `transaction_time` TIMESTAMP NULL,
    `settlement_time` TIMESTAMP NULL,
    `expiry_time` TIMESTAMP NULL,
    `va_number` VARCHAR(50) NULL,
    `va_bank` VARCHAR(20) NULL,
    `qris_string` TEXT NULL,
    `qris_url` TEXT NULL,
    `payment_code` VARCHAR(100) NULL,
    `pdf_url` TEXT NULL,
    `raw_request` JSON NULL,
    `raw_response` JSON NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `student_payment_id` BIGINT UNSIGNED NULL,
    `applicant_payment_id` BIGINT UNSIGNED NULL,
    `is_posted` TINYINT(1) NOT NULL DEFAULT 0,
    `posted_at` TIMESTAMP NULL,
    `expired_at` TIMESTAMP NULL,
    `cancelled_at` TIMESTAMP NULL,
    `refunded_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `payment_transactions_order_id_unique` (`order_id`),
    UNIQUE INDEX `payment_transactions_midtrans_id_unique` (`midtrans_transaction_id`),
    INDEX `payment_transactions_school_index` (`school_id`),
    INDEX `payment_transactions_gateway_index` (`payment_gateway_id`),
    INDEX `payment_transactions_channel_index` (`payment_channel_id`),
    INDEX `payment_transactions_student_invoice_index` (`student_invoice_id`),
    INDEX `payment_transactions_applicant_invoice_index` (`applicant_invoice_id`),
    INDEX `payment_transactions_student_index` (`student_id`),
    INDEX `payment_transactions_applicant_index` (`applicant_id`),
    INDEX `payment_transactions_type_index` (`transaction_type`),
    INDEX `payment_transactions_payment_type_index` (`payment_type`),
    INDEX `payment_transactions_status_index` (`transaction_status`),
    INDEX `payment_transactions_fraud_index` (`fraud_status`),
    INDEX `payment_transactions_va_index` (`va_number`),
    INDEX `payment_transactions_transaction_time_index` (`transaction_time`),
    INDEX `payment_transactions_expiry_time_index` (`expiry_time`),
    INDEX `payment_transactions_journal_entry_index` (`journal_entry_id`),
    INDEX `payment_transactions_student_payment_index` (`student_payment_id`),
    INDEX `payment_transactions_applicant_payment_index` (`applicant_payment_id`),
    INDEX `payment_transactions_is_posted_index` (`is_posted`),
    INDEX `payment_transactions_deleted_at_index` (`deleted_at`),
    INDEX `payment_transactions_created_by_index` (`created_by`),
    INDEX `payment_transactions_updated_by_index` (`updated_by`),
    CONSTRAINT `payment_transactions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `payment_transactions_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `payment_transactions_payment_channel_id_foreign` FOREIGN KEY (`payment_channel_id`) REFERENCES `payment_channels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `payment_transactions_student_invoice_id_foreign` FOREIGN KEY (`student_invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `payment_transactions_applicant_invoice_id_foreign` FOREIGN KEY (`applicant_invoice_id`) REFERENCES `applicant_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `payment_transactions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `payment_transactions_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `payment_transactions_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `payment_transactions_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `payment_transactions_student_payment_id_foreign` FOREIGN KEY (`student_payment_id`) REFERENCES `student_payments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `payment_transactions_applicant_payment_id_foreign` FOREIGN KEY (`applicant_payment_id`) REFERENCES `applicant_payments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

🔔 9. Webhook Handler (Idempotent)

-- ============================================
-- 107. PAYMENT_WEBHOOK_LOGS (Log Callback Midtrans)
-- ============================================
CREATE TABLE `payment_webhook_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `payment_gateway_id` BIGINT UNSIGNED NULL,
    `order_id` VARCHAR(100) NULL,
    `transaction_id` VARCHAR(100) NULL,
    `event_type` VARCHAR(50) NULL,
    `signature_key` VARCHAR(255) NULL,
    `signature_valid` TINYINT(1) NOT NULL DEFAULT 0,
    `is_processed` TINYINT(1) NOT NULL DEFAULT 0,
    `processed_at` TIMESTAMP NULL,
    `processing_notes` TEXT NULL,
    `retry_count` INTEGER NOT NULL DEFAULT 0,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `headers` JSON NULL,
    `payload` JSON NOT NULL,
    `response_body` TEXT NULL,
    `response_status` INTEGER NULL,
    `received_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `payment_webhook_logs_gateway_index` (`payment_gateway_id`),
    INDEX `payment_webhook_logs_order_index` (`order_id`),
    INDEX `payment_webhook_logs_transaction_index` (`transaction_id`),
    INDEX `payment_webhook_logs_event_index` (`event_type`),
    INDEX `payment_webhook_logs_is_processed_index` (`is_processed`),
    INDEX `payment_webhook_logs_received_at_index` (`received_at`),
    INDEX `payment_webhook_logs_deleted_at_index` (`deleted_at`),
    INDEX `payment_webhook_logs_created_by_index` (`created_by`),
    INDEX `payment_webhook_logs_updated_by_index` (`updated_by`),
    CONSTRAINT `payment_webhook_logs_payment_gateway_id_foreign` FOREIGN KEY (`payment_gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

🏦 10. Virtual Account & QRIS Detail

-- ============================================
-- 108. VIRTUAL_ACCOUNTS (VA yang Di-generate)
-- ============================================
CREATE TABLE `virtual_accounts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `payment_transaction_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NULL,
    `applicant_id` BIGINT UNSIGNED NULL,
    `bank_code` VARCHAR(20) NOT NULL,
    `va_number` VARCHAR(50) NOT NULL,
    `va_name` VARCHAR(150) NULL,
    `amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `billing_type` VARCHAR(20) NOT NULL DEFAULT 'FIXED',
    `expiry_time` TIMESTAMP NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `paid_at` TIMESTAMP NULL,
    `inquiry_text` TEXT NULL,
    `payment_text` TEXT NULL,
    `raw_response` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `virtual_accounts_bank_va_unique` (`bank_code`, `va_number`),
    INDEX `virtual_accounts_school_index` (`school_id`),
    INDEX `virtual_accounts_transaction_index` (`payment_transaction_id`),
    INDEX `virtual_accounts_student_index` (`student_id`),
    INDEX `virtual_accounts_applicant_index` (`applicant_id`),
    INDEX `virtual_accounts_bank_index` (`bank_code`),
    INDEX `virtual_accounts_status_index` (`status`),
    INDEX `virtual_accounts_expiry_index` (`expiry_time`),
    INDEX `virtual_accounts_deleted_at_index` (`deleted_at`),
    INDEX `virtual_accounts_created_by_index` (`created_by`),
    INDEX `virtual_accounts_updated_by_index` (`updated_by`),
    CONSTRAINT `virtual_accounts_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `virtual_accounts_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `virtual_accounts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `virtual_accounts_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 109. QRIS_TRANSACTIONS (Detail QRIS)
-- ============================================
CREATE TABLE `qris_transactions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `payment_transaction_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NULL,
    `applicant_id` BIGINT UNSIGNED NULL,
    `issuer` VARCHAR(50) NULL,
    `acquirer` VARCHAR(50) NULL,
    `qris_string` TEXT NULL,
    `qris_url` TEXT NULL,
    `amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `expiry_time` TIMESTAMP NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `paid_at` TIMESTAMP NULL,
    `raw_response` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `qris_transactions_school_index` (`school_id`),
    INDEX `qris_transactions_transaction_index` (`payment_transaction_id`),
    INDEX `qris_transactions_student_index` (`student_id`),
    INDEX `qris_transactions_applicant_index` (`applicant_id`),
    INDEX `qris_transactions_status_index` (`status`),
    INDEX `qris_transactions_expiry_index` (`expiry_time`),
    INDEX `qris_transactions_deleted_at_index` (`deleted_at`),
    INDEX `qris_transactions_created_by_index` (`created_by`),
    INDEX `qris_transactions_updated_by_index` (`updated_by`),
    CONSTRAINT `qris_transactions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `qris_transactions_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `qris_transactions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `qris_transactions_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📢 10. Notifikasi ke Orang Tua

-- ============================================
-- 110. PAYMENT_NOTIFICATIONS (Notifikasi WA/Email/SMS)
-- ============================================
CREATE TABLE `payment_notifications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `payment_transaction_id` BIGINT UNSIGNED NULL,
    `student_invoice_id` BIGINT UNSIGNED NULL,
    `applicant_invoice_id` BIGINT UNSIGNED NULL,
    `recipient_user_id` BIGINT UNSIGNED NULL,
    `recipient_name` VARCHAR(150) NULL,
    `recipient_phone` VARCHAR(20) NULL,
    `recipient_email` VARCHAR(100) NULL,
    `channel` VARCHAR(20) NOT NULL DEFAULT 'WHATSAPP',
    `template_code` VARCHAR(50) NULL,
    `subject` VARCHAR(200) NULL,
    `message` TEXT NULL,
    `provider` VARCHAR(30) NULL,
    `provider_message_id` VARCHAR(100) NULL,
    `provider_response` JSON NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `sent_at` TIMESTAMP NULL,
    `delivered_at` TIMESTAMP NULL,
    `read_at` TIMESTAMP NULL,
    `failed_reason` TEXT NULL,
    `retry_count` INTEGER NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `payment_notifications_school_index` (`school_id`),
    INDEX `payment_notifications_transaction_index` (`payment_transaction_id`),
    INDEX `payment_notifications_student_invoice_index` (`student_invoice_id`),
    INDEX `payment_notifications_applicant_invoice_index` (`applicant_invoice_id`),
    INDEX `payment_notifications_recipient_user_index` (`recipient_user_id`),
    INDEX `payment_notifications_phone_index` (`recipient_phone`),
    INDEX `payment_notifications_email_index` (`recipient_email`),
    INDEX `payment_notifications_channel_index` (`channel`),
    INDEX `payment_notifications_status_index` (`status`),
    INDEX `payment_notifications_sent_at_index` (`sent_at`),
    INDEX `payment_notifications_deleted_at_index` (`deleted_at`),
    INDEX `payment_notifications_created_by_index` (`created_by`),
    INDEX `payment_notifications_updated_by_index` (`updated_by`),
    CONSTRAINT `payment_notifications_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `payment_notifications_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `payment_notifications_student_invoice_id_foreign` FOREIGN KEY (`student_invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `payment_notifications_applicant_invoice_id_foreign` FOREIGN KEY (`applicant_invoice_id`) REFERENCES `applicant_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `payment_notifications_recipient_user_id_foreign` FOREIGN KEY (`recipient_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📦 BATCH 11 — RKAS (Rencana Kegiatan dan Anggaran Sekolah)
🗂️ 1. Header & Standar (8 SNP)

-- ============================================
-- 111. RKAS_DOCUMENTS (Header RKAS per Sekolah & Tahun)
-- ============================================
CREATE TABLE `rkas_documents` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_period_id` BIGINT UNSIGNED NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `version` INTEGER NOT NULL DEFAULT 1,
    `revision_type` VARCHAR(20) NOT NULL DEFAULT 'INITIAL',
    `parent_document_id` BIGINT UNSIGNED NULL,
    `total_budget` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_realization` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'DRAFT',
    `approved_at` TIMESTAMP NULL,
    `approved_by` BIGINT UNSIGNED NULL,
    `notes` TEXT NULL,
    `attachment_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `rkas_documents_school_code_unique` (`school_id`, `code`),
    INDEX `rkas_documents_school_index` (`school_id`),
    INDEX `rkas_documents_academic_year_index` (`academic_year_id`),
    INDEX `rkas_documents_fiscal_period_index` (`fiscal_period_id`),
    INDEX `rkas_documents_revision_type_index` (`revision_type`),
    INDEX `rkas_documents_parent_index` (`parent_document_id`),
    INDEX `rkas_documents_status_index` (`status`),
    INDEX `rkas_documents_approved_by_index` (`approved_by`),
    INDEX `rkas_documents_deleted_at_index` (`deleted_at`),
    INDEX `rkas_documents_created_by_index` (`created_by`),
    INDEX `rkas_documents_updated_by_index` (`updated_by`),
    CONSTRAINT `rkas_documents_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_documents_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_documents_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `rkas_documents_parent_document_id_foreign` FOREIGN KEY (`parent_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `rkas_documents_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 112. RKAS_STANDARDS (8 Standar Nasional Pendidikan)
-- ============================================
CREATE TABLE `rkas_standards` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `order_index` INTEGER NOT NULL DEFAULT 0,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `rkas_standards_code_unique` (`code`),
    INDEX `rkas_standards_order_index` (`order_index`),
    INDEX `rkas_standards_deleted_at_index` (`deleted_at`),
    INDEX `rkas_standards_created_by_index` (`created_by`),
    INDEX `rkas_standards_updated_by_index` (`updated_by`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📋 2. Hierarki RKAS: Program → Kegiatan → Sub-Kegiatan

-- ============================================
-- 113. RKAS_PROGRAMS (Program per Standar)
-- ============================================
CREATE TABLE `rkas_programs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `rkas_document_id` BIGINT UNSIGNED NOT NULL,
    `standard_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `order_index` INTEGER NOT NULL DEFAULT 0,
    `total_budget` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_realization` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `rkas_programs_document_code_unique` (`rkas_document_id`, `code`),
    INDEX `rkas_programs_document_index` (`rkas_document_id`),
    INDEX `rkas_programs_standard_index` (`standard_id`),
    INDEX `rkas_programs_order_index` (`order_index`),
    INDEX `rkas_programs_deleted_at_index` (`deleted_at`),
    INDEX `rkas_programs_created_by_index` (`created_by`),
    INDEX `rkas_programs_updated_by_index` (`updated_by`),
    CONSTRAINT `rkas_programs_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_programs_standard_id_foreign` FOREIGN KEY (`standard_id`) REFERENCES `rkas_standards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 114. RKAS_ACTIVITIES (Kegiatan per Program)
-- ============================================
CREATE TABLE `rkas_activities` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `rkas_program_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `order_index` INTEGER NOT NULL DEFAULT 0,
    `total_budget` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_realization` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `rkas_activities_program_code_unique` (`rkas_program_id`, `code`),
    INDEX `rkas_activities_program_index` (`rkas_program_id`),
    INDEX `rkas_activities_order_index` (`order_index`),
    INDEX `rkas_activities_deleted_at_index` (`deleted_at`),
    INDEX `rkas_activities_created_by_index` (`created_by`),
    INDEX `rkas_activities_updated_by_index` (`updated_by`),
    CONSTRAINT `rkas_activities_rkas_program_id_foreign` FOREIGN KEY (`rkas_program_id`) REFERENCES `rkas_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 115. RKAS_SUB_ACTIVITIES (Sub-Kegiatan)
-- ============================================
CREATE TABLE `rkas_sub_activities` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `rkas_activity_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `order_index` INTEGER NOT NULL DEFAULT 0,
    `total_budget` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_realization` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `rkas_sub_activities_activity_code_unique` (`rkas_activity_id`, `code`),
    INDEX `rkas_sub_activities_activity_index` (`rkas_activity_id`),
    INDEX `rkas_sub_activities_order_index` (`order_index`),
    INDEX `rkas_sub_activities_deleted_at_index` (`deleted_at`),
    INDEX `rkas_sub_activities_created_by_index` (`created_by`),
    INDEX `rkas_sub_activities_updated_by_index` (`updated_by`),
    CONSTRAINT `rkas_sub_activities_rkas_activity_id_foreign` FOREIGN KEY (`rkas_activity_id`) REFERENCES `rkas_activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;


💰 3. Komponen Belanja & Sumber Dana

-- ============================================
-- 116. RKAS_COMPONENTS (Komponen Belanja / Rekening)
-- ============================================
CREATE TABLE `rkas_components` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `component_type` VARCHAR(30) NOT NULL,
    `account_code` VARCHAR(20) NULL,
    `account_id` BIGINT UNSIGNED NULL,
    `regulation_code` VARCHAR(50) NULL,
    `regulation_version` VARCHAR(20) NULL,
    `max_percentage` DECIMAL(5,2) NULL,
    `is_mandatory` TINYINT(1) NOT NULL DEFAULT 0,
    `order_index` INTEGER NOT NULL DEFAULT 0,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `rkas_components_school_code_unique` (`school_id`, `code`),
    INDEX `rkas_components_school_index` (`school_id`),
    INDEX `rkas_components_type_index` (`component_type`),
    INDEX `rkas_components_account_index` (`account_code`),
    INDEX `rkas_components_coa_index` (`account_id`),
    INDEX `rkas_components_regulation_index` (`regulation_code`, `regulation_version`),
    INDEX `rkas_components_order_index` (`order_index`),
    INDEX `rkas_components_deleted_at_index` (`deleted_at`),
    INDEX `rkas_components_created_by_index` (`created_by`),
    INDEX `rkas_components_updated_by_index` (`updated_by`),
    CONSTRAINT `rkas_components_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_components_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 117. RKAS_SOURCES (Sumber Dana)
-- ============================================
CREATE TABLE `rkas_sources` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `source_type` VARCHAR(30) NOT NULL,
    `authority` VARCHAR(30) NULL,
    `account_id` BIGINT UNSIGNED NULL,
    `allocation_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `regulation_version` VARCHAR(50) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `rkas_sources_school_code_unique` (`school_id`, `code`),
    INDEX `rkas_sources_school_index` (`school_id`),
    INDEX `rkas_sources_type_index` (`source_type`),
    INDEX `rkas_sources_authority_index` (`authority`),
    INDEX `rkas_sources_coa_index` (`account_id`),
    INDEX `rkas_sources_deleted_at_index` (`deleted_at`),
    INDEX `rkas_sources_created_by_index` (`created_by`),
    INDEX `rkas_sources_updated_by_index` (`updated_by`),
    CONSTRAINT `rkas_sources_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_sources_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📦 4. Item Belanja & Anggaran

-- ============================================
-- 118. RKAS_ITEMS (Item Belanja)
-- ============================================
CREATE TABLE `rkas_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `rkas_sub_activity_id` BIGINT UNSIGNED NOT NULL,
    `rkas_component_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `specification` TEXT NULL,
    `unit` VARCHAR(20) NULL,
    `volume` DECIMAL(20,4) NOT NULL DEFAULT 0,
    `unit_price` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `subtotal` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `tax_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `shipping_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `admin_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `execution_month` TINYINT UNSIGNED NULL,
    `execution_quarter` TINYINT UNSIGNED NULL,
    `order_index` INTEGER NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `rkas_items_sub_activity_code_unique` (`rkas_sub_activity_id`, `code`),
    INDEX `rkas_items_sub_activity_index` (`rkas_sub_activity_id`),
    INDEX `rkas_items_component_index` (`rkas_component_id`),
    INDEX `rkas_items_month_index` (`execution_month`),
    INDEX `rkas_items_quarter_index` (`execution_quarter`),
    INDEX `rkas_items_order_index` (`order_index`),
    INDEX `rkas_items_deleted_at_index` (`deleted_at`),
    INDEX `rkas_items_created_by_index` (`created_by`),
    INDEX `rkas_items_updated_by_index` (`updated_by`),
    CONSTRAINT `rkas_items_rkas_sub_activity_id_foreign` FOREIGN KEY (`rkas_sub_activity_id`) REFERENCES `rkas_sub_activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_items_rkas_component_id_foreign` FOREIGN KEY (`rkas_component_id`) REFERENCES `rkas_components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 119. RKAS_BUDGETS (Anggaran per Item per Sumber Dana)
-- ============================================
CREATE TABLE `rkas_budgets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `rkas_item_id` BIGINT UNSIGNED NOT NULL,
    `rkas_source_id` BIGINT UNSIGNED NOT NULL,
    `amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `percentage` DECIMAL(5,2) NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `rkas_budgets_item_source_unique` (`rkas_item_id`, `rkas_source_id`),
    INDEX `rkas_budgets_item_index` (`rkas_item_id`),
    INDEX `rkas_budgets_source_index` (`rkas_source_id`),
    INDEX `rkas_budgets_deleted_at_index` (`deleted_at`),
    INDEX `rkas_budgets_created_by_index` (`created_by`),
    INDEX `rkas_budgets_updated_by_index` (`updated_by`),
    CONSTRAINT `rkas_budgets_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_budgets_rkas_source_id_foreign` FOREIGN KEY (`rkas_source_id`) REFERENCES `rkas_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📈 5. Realisasi, Revisi & Approval

-- ============================================
-- 120. RKAS_REALIZATIONS (Realisasi Belanja)
-- ============================================
CREATE TABLE `rkas_realizations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `rkas_item_id` BIGINT UNSIGNED NOT NULL,
    `rkas_source_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_period_id` BIGINT UNSIGNED NOT NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `realization_date` DATE NOT NULL,
    `description` TEXT NULL,
    `amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `tax_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `vendor_id` BIGINT UNSIGNED NULL,
    `vendor_invoice_id` BIGINT UNSIGNED NULL,
    `payment_reference` VARCHAR(100) NULL,
    `attachment_url` TEXT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'RECORDED',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `rkas_realizations_item_index` (`rkas_item_id`),
    INDEX `rkas_realizations_source_index` (`rkas_source_id`),
    INDEX `rkas_realizations_fiscal_period_index` (`fiscal_period_id`),
    INDEX `rkas_realizations_journal_entry_index` (`journal_entry_id`),
    INDEX `rkas_realizations_date_index` (`realization_date`),
    INDEX `rkas_realizations_vendor_index` (`vendor_id`),
    INDEX `rkas_realizations_vendor_invoice_index` (`vendor_invoice_id`),
    INDEX `rkas_realizations_status_index` (`status`),
    INDEX `rkas_realizations_deleted_at_index` (`deleted_at`),
    INDEX `rkas_realizations_created_by_index` (`created_by`),
    INDEX `rkas_realizations_updated_by_index` (`updated_by`),
    CONSTRAINT `rkas_realizations_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_realizations_rkas_source_id_foreign` FOREIGN KEY (`rkas_source_id`) REFERENCES `rkas_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_realizations_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_realizations_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `rkas_realizations_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `rkas_realizations_vendor_invoice_id_foreign` FOREIGN KEY (`vendor_invoice_id`) REFERENCES `vendor_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 121. RKAS_REVISIONS (Riwayat Revisi / Pergeseran / Perubahan)
-- ============================================
CREATE TABLE `rkas_revisions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `rkas_document_id` BIGINT UNSIGNED NOT NULL,
    `parent_revision_id` BIGINT UNSIGNED NULL,
    `revision_number` INTEGER NOT NULL DEFAULT 1,
    `revision_type` VARCHAR(20) NOT NULL,
    `revision_date` DATE NOT NULL,
    `reason` TEXT NULL,
    `total_before` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_after` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `difference` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `changes` JSON NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'DRAFT',
    `approved_at` TIMESTAMP NULL,
    `approved_by` BIGINT UNSIGNED NULL,
    `approval_notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `rkas_revisions_document_number_unique` (`rkas_document_id`, `revision_number`),
    INDEX `rkas_revisions_document_index` (`rkas_document_id`),
    INDEX `rkas_revisions_parent_index` (`parent_revision_id`),
    INDEX `rkas_revisions_type_index` (`revision_type`),
    INDEX `rkas_revisions_date_index` (`revision_date`),
    INDEX `rkas_revisions_status_index` (`status`),
    INDEX `rkas_revisions_approved_by_index` (`approved_by`),
    INDEX `rkas_revisions_deleted_at_index` (`deleted_at`),
    INDEX `rkas_revisions_created_by_index` (`created_by`),
    INDEX `rkas_revisions_updated_by_index` (`updated_by`),
    CONSTRAINT `rkas_revisions_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_revisions_parent_revision_id_foreign` FOREIGN KEY (`parent_revision_id`) REFERENCES `rkas_revisions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `rkas_revisions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 122. RKAS_APPROVALS (Approval Workflow)
-- ============================================
CREATE TABLE `rkas_approvals` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `rkas_document_id` BIGINT UNSIGNED NOT NULL,
    `approval_level` INTEGER NOT NULL DEFAULT 1,
    `approval_role` VARCHAR(50) NULL,
    `approver_id` BIGINT UNSIGNED NULL,
    `approver_name` VARCHAR(150) NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `approved_at` TIMESTAMP NULL,
    `notes` TEXT NULL,
    `signature_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `rkas_approvals_document_index` (`rkas_document_id`),
    INDEX `rkas_approvals_level_index` (`approval_level`),
    INDEX `rkas_approvals_role_index` (`approval_role`),
    INDEX `rkas_approvals_approver_index` (`approver_id`),
    INDEX `rkas_approvals_status_index` (`status`),
    INDEX `rkas_approvals_deleted_at_index` (`deleted_at`),
    INDEX `rkas_approvals_created_by_index` (`created_by`),
    INDEX `rkas_approvals_updated_by_index` (`updated_by`),
    CONSTRAINT `rkas_approvals_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_approvals_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📅 6. Laporan Periodik (K1–K7c)

-- ============================================
-- 123. RKAS_REPORTS (Laporan Periodik BOS K1-K7c)
-- ============================================
CREATE TABLE `rkas_reports` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `rkas_document_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_period_id` BIGINT UNSIGNED NOT NULL,
    `report_code` VARCHAR(20) NOT NULL,
    `report_name` VARCHAR(150) NOT NULL,
    `report_type` VARCHAR(20) NOT NULL DEFAULT 'PERIODIC',
    `period_number` INTEGER NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `total_budget` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_realization` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_remaining` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `percentage` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `file_url` TEXT NULL,
    `report_data` JSON NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'DRAFT',
    `submitted_at` TIMESTAMP NULL,
    `submitted_by` BIGINT UNSIGNED NULL,
    `verified_at` TIMESTAMP NULL,
    `verified_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `rkas_reports_school_document_code_period_unique` (`school_id`, `rkas_document_id`, `report_code`, `period_number`),
    INDEX `rkas_reports_school_index` (`school_id`),
    INDEX `rkas_reports_document_index` (`rkas_document_id`),
    INDEX `rkas_reports_fiscal_period_index` (`fiscal_period_id`),
    INDEX `rkas_reports_code_index` (`report_code`),
    INDEX `rkas_reports_type_index` (`report_type`),
    INDEX `rkas_reports_status_index` (`status`),
    INDEX `rkas_reports_submitted_by_index` (`submitted_by`),
    INDEX `rkas_reports_verified_by_index` (`verified_by`),
    INDEX `rkas_reports_deleted_at_index` (`deleted_at`),
    INDEX `rkas_reports_created_by_index` (`created_by`),
    INDEX `rkas_reports_updated_by_index` (`updated_by`),
    CONSTRAINT `rkas_reports_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_reports_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_reports_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_reports_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `rkas_reports_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 124. RKAS_REPORT_ITEMS (Detail Item Laporan)
-- ============================================
CREATE TABLE `rkas_report_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `rkas_report_id` BIGINT UNSIGNED NOT NULL,
    `rkas_component_id` BIGINT UNSIGNED NULL,
    `rkas_source_id` BIGINT UNSIGNED NULL,
    `account_id` BIGINT UNSIGNED NULL,
    `description` TEXT NULL,
    `budget_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `realization_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `remaining_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `percentage` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `item_data` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `rkas_report_items_report_index` (`rkas_report_id`),
    INDEX `rkas_report_items_component_index` (`rkas_component_id`),
    INDEX `rkas_report_items_source_index` (`rkas_source_id`),
    INDEX `rkas_report_items_coa_index` (`account_id`),
    INDEX `rkas_report_items_deleted_at_index` (`deleted_at`),
    INDEX `rkas_report_items_created_by_index` (`created_by`),
    INDEX `rkas_report_items_updated_by_index` (`updated_by`),
    CONSTRAINT `rkas_report_items_rkas_report_id_foreign` FOREIGN KEY (`rkas_report_id`) REFERENCES `rkas_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `rkas_report_items_rkas_component_id_foreign` FOREIGN KEY (`rkas_component_id`) REFERENCES `rkas_components` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `rkas_report_items_rkas_source_id_foreign` FOREIGN KEY (`rkas_source_id`) REFERENCES `rkas_sources` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `rkas_report_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📦 BATCH 12 — BOS & Pengadaan
🏫 1. Master BOS (Mendukung Kemendikbud & Kemenag)

-- ============================================
-- 125. BOS_PERIODS (Periode BOS per Sekolah)
-- ============================================
CREATE TABLE `bos_periods` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_period_id` BIGINT UNSIGNED NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `authority` VARCHAR(30) NOT NULL DEFAULT 'KEMENDIKBUD',
    `regulation_version` VARCHAR(50) NULL,
    `period_number` INTEGER NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `total_allocation` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_received` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_used` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_remaining` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PLANNING',
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `bos_periods_school_code_unique` (`school_id`, `code`),
    INDEX `bos_periods_school_index` (`school_id`),
    INDEX `bos_periods_academic_year_index` (`academic_year_id`),
    INDEX `bos_periods_fiscal_period_index` (`fiscal_period_id`),
    INDEX `bos_periods_authority_index` (`authority`),
    INDEX `bos_periods_status_index` (`status`),
    INDEX `bos_periods_dates_index` (`start_date`, `end_date`),
    INDEX `bos_periods_deleted_at_index` (`deleted_at`),
    INDEX `bos_periods_created_by_index` (`created_by`),
    INDEX `bos_periods_updated_by_index` (`updated_by`),
    CONSTRAINT `bos_periods_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bos_periods_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bos_periods_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 126. BOS_COMPONENTS (Komponen BOS sesuai Juknis)
-- ============================================
CREATE TABLE `bos_components` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `authority` VARCHAR(30) NOT NULL DEFAULT 'KEMENDIKBUD',
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `component_type` VARCHAR(30) NOT NULL,
    `regulation_code` VARCHAR(50) NULL,
    `regulation_version` VARCHAR(50) NULL,
    `max_percentage` DECIMAL(5,2) NULL,
    `min_percentage` DECIMAL(5,2) NULL,
    `calculation_base` VARCHAR(20) NULL,
    `is_mandatory` TINYINT(1) NOT NULL DEFAULT 0,
    `is_prohibited` TINYINT(1) NOT NULL DEFAULT 0,
    `order_index` INTEGER NOT NULL DEFAULT 0,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `bos_components_authority_code_unique` (`authority`, `code`),
    INDEX `bos_components_authority_index` (`authority`),
    INDEX `bos_components_type_index` (`component_type`),
    INDEX `bos_components_regulation_index` (`regulation_code`, `regulation_version`),
    INDEX `bos_components_order_index` (`order_index`),
    INDEX `bos_components_deleted_at_index` (`deleted_at`),
    INDEX `bos_components_created_by_index` (`created_by`),
    INDEX `bos_components_updated_by_index` (`updated_by`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

💰 2. Alokasi & Penerimaan Dana BOS

-- ============================================
-- 127. BOS_ALLOCATIONS (Alokasi Dana per Komponen)
-- ============================================
CREATE TABLE `bos_allocations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `bos_period_id` BIGINT UNSIGNED NOT NULL,
    `bos_component_id` BIGINT UNSIGNED NOT NULL,
    `allocation_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `percentage` DECIMAL(5,2) NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `bos_allocations_period_component_unique` (`bos_period_id`, `bos_component_id`),
    INDEX `bos_allocations_period_index` (`bos_period_id`),
    INDEX `bos_allocations_component_index` (`bos_component_id`),
    INDEX `bos_allocations_deleted_at_index` (`deleted_at`),
    INDEX `bos_allocations_created_by_index` (`created_by`),
    INDEX `bos_allocations_updated_by_index` (`updated_by`),
    CONSTRAINT `bos_allocations_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bos_allocations_bos_component_id_foreign` FOREIGN KEY (`bos_component_id`) REFERENCES `bos_components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 128. BOS_RECEIPTS (Penerimaan Dana BOS)
-- ============================================
CREATE TABLE `bos_receipts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `bos_period_id` BIGINT UNSIGNED NOT NULL,
    `bank_account_id` BIGINT UNSIGNED NULL,
    `receipt_number` VARCHAR(50) NOT NULL,
    `receipt_date` DATE NOT NULL,
    `amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `transfer_reference` VARCHAR(100) NULL,
    `bank_name` VARCHAR(50) NULL,
    `transfer_proof_url` TEXT NULL,
    `received_by` BIGINT UNSIGNED NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `bos_receipts_period_number_unique` (`bos_period_id`, `receipt_number`),
    INDEX `bos_receipts_period_index` (`bos_period_id`),
    INDEX `bos_receipts_bank_account_index` (`bank_account_id`),
    INDEX `bos_receipts_date_index` (`receipt_date`),
    INDEX `bos_receipts_received_by_index` (`received_by`),
    INDEX `bos_receipts_deleted_at_index` (`deleted_at`),
    INDEX `bos_receipts_created_by_index` (`created_by`),
    INDEX `bos_receipts_updated_by_index` (`updated_by`),
    CONSTRAINT `bos_receipts_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bos_receipts_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `bos_receipts_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📋 3. Rencana & Realisasi Penggunaan BOS

-- ============================================
-- 129. BOS_USAGE_PLANS (Rencana Penggunaan BOS)
-- ============================================
CREATE TABLE `bos_usage_plans` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `bos_period_id` BIGINT UNSIGNED NOT NULL,
    `rkas_document_id` BIGINT UNSIGNED NULL,
    `rkas_item_id` BIGINT UNSIGNED NULL,
    `bos_component_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `planned_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `execution_month` TINYINT UNSIGNED NULL,
    `execution_quarter` TINYINT UNSIGNED NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PLANNED',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `bos_usage_plans_period_code_unique` (`bos_period_id`, `code`),
    INDEX `bos_usage_plans_period_index` (`bos_period_id`),
    INDEX `bos_usage_plans_rkas_document_index` (`rkas_document_id`),
    INDEX `bos_usage_plans_rkas_item_index` (`rkas_item_id`),
    INDEX `bos_usage_plans_component_index` (`bos_component_id`),
    INDEX `bos_usage_plans_month_index` (`execution_month`),
    INDEX `bos_usage_plans_status_index` (`status`),
    INDEX `bos_usage_plans_deleted_at_index` (`deleted_at`),
    INDEX `bos_usage_plans_created_by_index` (`created_by`),
    INDEX `bos_usage_plans_updated_by_index` (`updated_by`),
    CONSTRAINT `bos_usage_plans_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bos_usage_plans_rkas_document_id_foreign` FOREIGN KEY (`rkas_document_id`) REFERENCES `rkas_documents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `bos_usage_plans_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `bos_usage_plans_bos_component_id_foreign` FOREIGN KEY (`bos_component_id`) REFERENCES `bos_components` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 130. BOS_REALIZATIONS (Realisasi Penggunaan BOS)
-- ============================================
CREATE TABLE `bos_realizations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `bos_period_id` BIGINT UNSIGNED NOT NULL,
    `bos_usage_plan_id` BIGINT UNSIGNED NULL,
    `rkas_realization_id` BIGINT UNSIGNED NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `vendor_id` BIGINT UNSIGNED NULL,
    `vendor_invoice_id` BIGINT UNSIGNED NULL,
    `receipt_number` VARCHAR(50) NULL,
    `realization_date` DATE NOT NULL,
    `description` TEXT NULL,
    `amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `tax_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `payment_method` VARCHAR(30) NULL,
    `attachment_url` TEXT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'RECORDED',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `bos_realizations_period_index` (`bos_period_id`),
    INDEX `bos_realizations_usage_plan_index` (`bos_usage_plan_id`),
    INDEX `bos_realizations_rkas_realization_index` (`rkas_realization_id`),
    INDEX `bos_realizations_journal_entry_index` (`journal_entry_id`),
    INDEX `bos_realizations_vendor_index` (`vendor_id`),
    INDEX `bos_realizations_vendor_invoice_index` (`vendor_invoice_id`),
    INDEX `bos_realizations_date_index` (`realization_date`),
    INDEX `bos_realizations_status_index` (`status`),
    INDEX `bos_realizations_deleted_at_index` (`deleted_at`),
    INDEX `bos_realizations_created_by_index` (`created_by`),
    INDEX `bos_realizations_updated_by_index` (`updated_by`),
    CONSTRAINT `bos_realizations_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bos_realizations_bos_usage_plan_id_foreign` FOREIGN KEY (`bos_usage_plan_id`) REFERENCES `bos_usage_plans` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `bos_realizations_rkas_realization_id_foreign` FOREIGN KEY (`rkas_realization_id`) REFERENCES `rkas_realizations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `bos_realizations_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `bos_realizations_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `bos_realizations_vendor_invoice_id_foreign` FOREIGN KEY (`vendor_invoice_id`) REFERENCES `vendor_invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📊 4. Laporan BOS (K1–K7c)

-- ============================================
-- 131. BOS_REPORTS (Laporan Periodik BOS)
-- ============================================
CREATE TABLE `bos_reports` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `bos_period_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_period_id` BIGINT UNSIGNED NULL,
    `report_code` VARCHAR(20) NOT NULL,
    `report_name` VARCHAR(150) NOT NULL,
    `report_type` VARCHAR(20) NOT NULL DEFAULT 'PERIODIC',
    `period_number` INTEGER NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `total_allocation` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_received` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_used` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_remaining` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `percentage` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `file_url` TEXT NULL,
    `report_data` JSON NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'DRAFT',
    `submitted_at` TIMESTAMP NULL,
    `submitted_by` BIGINT UNSIGNED NULL,
    `verified_at` TIMESTAMP NULL,
    `verified_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `bos_reports_school_period_code_unique` (`school_id`, `bos_period_id`, `report_code`),
    INDEX `bos_reports_school_index` (`school_id`),
    INDEX `bos_reports_period_index` (`bos_period_id`),
    INDEX `bos_reports_fiscal_period_index` (`fiscal_period_id`),
    INDEX `bos_reports_code_index` (`report_code`),
    INDEX `bos_reports_type_index` (`report_type`),
    INDEX `bos_reports_status_index` (`status`),
    INDEX `bos_reports_submitted_by_index` (`submitted_by`),
    INDEX `bos_reports_verified_by_index` (`verified_by`),
    INDEX `bos_reports_deleted_at_index` (`deleted_at`),
    INDEX `bos_reports_created_by_index` (`created_by`),
    INDEX `bos_reports_updated_by_index` (`updated_by`),
    CONSTRAINT `bos_reports_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bos_reports_bos_period_id_foreign` FOREIGN KEY (`bos_period_id`) REFERENCES `bos_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bos_reports_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `bos_reports_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `bos_reports_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 132. BOS_REPORT_ITEMS (Detail Item Laporan BOS)
-- ============================================
CREATE TABLE `bos_report_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `bos_report_id` BIGINT UNSIGNED NOT NULL,
    `bos_component_id` BIGINT UNSIGNED NULL,
    `rkas_source_id` BIGINT UNSIGNED NULL,
    `account_id` BIGINT UNSIGNED NULL,
    `description` TEXT NULL,
    `allocation_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `received_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `realization_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `remaining_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `percentage` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `item_data` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `bos_report_items_report_index` (`bos_report_id`),
    INDEX `bos_report_items_component_index` (`bos_component_id`),
    INDEX `bos_report_items_source_index` (`rkas_source_id`),
    INDEX `bos_report_items_coa_index` (`account_id`),
    INDEX `bos_report_items_deleted_at_index` (`deleted_at`),
    INDEX `bos_report_items_created_by_index` (`created_by`),
    INDEX `bos_report_items_updated_by_index` (`updated_by`),
    CONSTRAINT `bos_report_items_bos_report_id_foreign` FOREIGN KEY (`bos_report_id`) REFERENCES `bos_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bos_report_items_bos_component_id_foreign` FOREIGN KEY (`bos_component_id`) REFERENCES `bos_components` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `bos_report_items_rkas_source_id_foreign` FOREIGN KEY (`rkas_source_id`) REFERENCES `rkas_sources` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `bos_report_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

📦 5. Pengadaan: Purchase Request → PO → Goods Receipt

-- ============================================
-- 133. PURCHASE_REQUESTS (Pengajuan Pembelian)
-- ============================================
CREATE TABLE `purchase_requests` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `rkas_item_id` BIGINT UNSIGNED NULL,
    `request_number` VARCHAR(50) NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `requested_by` BIGINT UNSIGNED NULL,
    `requested_at` TIMESTAMP NULL,
    `needed_by` DATE NULL,
    `total_estimated_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'DRAFT',
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `rejection_notes` TEXT NULL,
    `notes` TEXT NULL,
    `attachment_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `purchase_requests_school_number_unique` (`school_id`, `request_number`),
    INDEX `purchase_requests_school_index` (`school_id`),
    INDEX `purchase_requests_rkas_item_index` (`rkas_item_id`),
    INDEX `purchase_requests_requested_by_index` (`requested_by`),
    INDEX `purchase_requests_approved_by_index` (`approved_by`),
    INDEX `purchase_requests_status_index` (`status`),
    INDEX `purchase_requests_needed_by_index` (`needed_by`),
    INDEX `purchase_requests_deleted_at_index` (`deleted_at`),
    INDEX `purchase_requests_created_by_index` (`created_by`),
    INDEX `purchase_requests_updated_by_index` (`updated_by`),
    CONSTRAINT `purchase_requests_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `purchase_requests_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `purchase_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `purchase_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 134. PURCHASE_REQUEST_ITEMS (Detail Item PR)
-- ============================================
CREATE TABLE `purchase_request_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `purchase_request_id` BIGINT UNSIGNED NOT NULL,
    `rkas_item_id` BIGINT UNSIGNED NULL,
    `account_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(200) NOT NULL,
    `specification` TEXT NULL,
    `unit` VARCHAR(20) NULL,
    `quantity` DECIMAL(20,4) NOT NULL DEFAULT 1,
    `estimated_unit_price` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `estimated_subtotal` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `estimated_total` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `purchase_request_items_request_index` (`purchase_request_id`),
    INDEX `purchase_request_items_rkas_item_index` (`rkas_item_id`),
    INDEX `purchase_request_items_coa_index` (`account_id`),
    INDEX `purchase_request_items_deleted_at_index` (`deleted_at`),
    INDEX `purchase_request_items_created_by_index` (`created_by`),
    INDEX `purchase_request_items_updated_by_index` (`updated_by`),
    CONSTRAINT `purchase_request_items_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `purchase_request_items_rkas_item_id_foreign` FOREIGN KEY (`rkas_item_id`) REFERENCES `rkas_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `purchase_request_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 135. PURCHASE_ORDERS (PO ke Vendor)
-- ============================================
CREATE TABLE `purchase_orders` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `vendor_id` BIGINT UNSIGNED NOT NULL,
    `purchase_request_id` BIGINT UNSIGNED NULL,
    `fiscal_period_id` BIGINT UNSIGNED NOT NULL,
    `currency_id` BIGINT UNSIGNED NOT NULL,
    `exchange_rate` DECIMAL(20,8) NOT NULL DEFAULT 1,
    `po_number` VARCHAR(50) NOT NULL,
    `po_date` DATE NOT NULL,
    `expected_delivery_date` DATE NULL,
    `delivery_address` TEXT NULL,
    `payment_terms` VARCHAR(50) NULL,
    `subtotal` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `discount_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `tax_base` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `ppn_rate` DECIMAL(5,2) NOT NULL DEFAULT 0,
    `ppn_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `shipping_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `functional_total` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'DRAFT',
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `sent_at` TIMESTAMP NULL,
    `completed_at` TIMESTAMP NULL,
    `cancelled_at` TIMESTAMP NULL,
    `cancellation_reason` TEXT NULL,
    `notes` TEXT NULL,
    `attachment_url` TEXT NULL,
    `sip_lah_order_id` VARCHAR(100) NULL,
    `sip_lah_order_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `purchase_orders_school_number_unique` (`school_id`, `po_number`),
    INDEX `purchase_orders_school_index` (`school_id`),
    INDEX `purchase_orders_vendor_index` (`vendor_id`),
    INDEX `purchase_orders_request_index` (`purchase_request_id`),
    INDEX `purchase_orders_fiscal_period_index` (`fiscal_period_id`),
    INDEX `purchase_orders_currency_index` (`currency_id`),
    INDEX `purchase_orders_date_index` (`po_date`),
    INDEX `purchase_orders_status_index` (`status`),
    INDEX `purchase_orders_approved_by_index` (`approved_by`),
    INDEX `purchase_orders_siplah_order_index` (`sip_lah_order_id`),
    INDEX `purchase_orders_deleted_at_index` (`deleted_at`),
    INDEX `purchase_orders_created_by_index` (`created_by`),
    INDEX `purchase_orders_updated_by_index` (`updated_by`),
    CONSTRAINT `purchase_orders_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `purchase_orders_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `purchase_orders_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `purchase_orders_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `purchase_orders_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `purchase_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 136. PURCHASE_ORDER_ITEMS (Detail Item PO)
-- ============================================
CREATE TABLE `purchase_order_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `purchase_order_id` BIGINT UNSIGNED NOT NULL,
    `purchase_request_item_id` BIGINT UNSIGNED NULL,
    `account_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(200) NOT NULL,
    `specification` TEXT NULL,
    `unit` VARCHAR(20) NULL,
    `quantity` DECIMAL(20,4) NOT NULL DEFAULT 1,
    `unit_price` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `discount_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `subtotal` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `tax_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `total_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `received_quantity` DECIMAL(20,4) NOT NULL DEFAULT 0,
    `line_number` INTEGER NOT NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `purchase_order_items_po_index` (`purchase_order_id`),
    INDEX `purchase_order_items_request_item_index` (`purchase_request_item_id`),
    INDEX `purchase_order_items_coa_index` (`account_id`),
    INDEX `purchase_order_items_line_number_index` (`line_number`),
    INDEX `purchase_order_items_deleted_at_index` (`deleted_at`),
    INDEX `purchase_order_items_created_by_index` (`created_by`),
    INDEX `purchase_order_items_updated_by_index` (`updated_by`),
    CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `purchase_order_items_purchase_request_item_id_foreign` FOREIGN KEY (`purchase_request_item_id`) REFERENCES `purchase_request_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `purchase_order_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 137. GOODS_RECEIPTS (Penerimaan Barang)
-- ============================================
CREATE TABLE `goods_receipts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `purchase_order_id` BIGINT UNSIGNED NOT NULL,
    `vendor_id` BIGINT UNSIGNED NOT NULL,
    `receipt_number` VARCHAR(50) NOT NULL,
    `receipt_date` DATE NOT NULL,
    `received_by` BIGINT UNSIGNED NULL,
    `checked_by` BIGINT UNSIGNED NULL,
    `delivery_note_number` VARCHAR(100) NULL,
    `vehicle_number` VARCHAR(20) NULL,
    `condition` VARCHAR(20) NOT NULL DEFAULT 'GOOD',
    `condition_notes` TEXT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'RECEIVED',
    `notes` TEXT NULL,
    `attachment_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `goods_receipts_school_number_unique` (`school_id`, `receipt_number`),
    INDEX `goods_receipts_school_index` (`school_id`),
    INDEX `goods_receipts_po_index` (`purchase_order_id`),
    INDEX `goods_receipts_vendor_index` (`vendor_id`),
    INDEX `goods_receipts_date_index` (`receipt_date`),
    INDEX `goods_receipts_received_by_index` (`received_by`),
    INDEX `goods_receipts_checked_by_index` (`checked_by`),
    INDEX `goods_receipts_status_index` (`status`),
    INDEX `goods_receipts_condition_index` (`condition`),
    INDEX `goods_receipts_deleted_at_index` (`deleted_at`),
    INDEX `goods_receipts_created_by_index` (`created_by`),
    INDEX `goods_receipts_updated_by_index` (`updated_by`),
    CONSTRAINT `goods_receipts_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `goods_receipts_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `goods_receipts_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `goods_receipts_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `goods_receipts_checked_by_foreign` FOREIGN KEY (`checked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 138. GOODS_RECEIPT_ITEMS (Detail Item Penerimaan)
-- ============================================
CREATE TABLE `goods_receipt_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `goods_receipt_id` BIGINT UNSIGNED NOT NULL,
    `purchase_order_item_id` BIGINT UNSIGNED NOT NULL,
    `account_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(200) NOT NULL,
    `unit` VARCHAR(20) NULL,
    `ordered_quantity` DECIMAL(20,4) NOT NULL DEFAULT 0,
    `received_quantity` DECIMAL(20,4) NOT NULL DEFAULT 0,
    `rejected_quantity` DECIMAL(20,4) NOT NULL DEFAULT 0,
    `unit_price` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `subtotal` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `condition` VARCHAR(20) NOT NULL DEFAULT 'GOOD',
    `notes` TEXT NULL,
    `is_asset` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `goods_receipt_items_receipt_index` (`goods_receipt_id`),
    INDEX `goods_receipt_items_po_item_index` (`purchase_order_item_id`),
    INDEX `goods_receipt_items_coa_index` (`account_id`),
    INDEX `goods_receipt_items_is_asset_index` (`is_asset`),
    INDEX `goods_receipt_items_deleted_at_index` (`deleted_at`),
    INDEX `goods_receipt_items_created_by_index` (`created_by`),
    INDEX `goods_receipt_items_updated_by_index` (`updated_by`),
    CONSTRAINT `goods_receipt_items_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `goods_receipt_items_purchase_order_item_id_foreign` FOREIGN KEY (`purchase_order_item_id`) REFERENCES `purchase_order_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `goods_receipt_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

🏗️ 6. Aset Tetap (Fixed Assets)

-- ============================================
-- 139. FIXED_ASSETS (Aset Tetap dari Pembelian BOS/PO)
-- ============================================
CREATE TABLE `fixed_assets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `goods_receipt_item_id` BIGINT UNSIGNED NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `asset_code` VARCHAR(50) NOT NULL,
    `asset_name` VARCHAR(200) NOT NULL,
    `asset_category` VARCHAR(50) NULL,
    `brand` VARCHAR(100) NULL,
    `model` VARCHAR(100) NULL,
    `serial_number` VARCHAR(100) NULL,
    `specification` TEXT NULL,
    `acquisition_date` DATE NOT NULL,
    `acquisition_cost` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `useful_life_months` INTEGER NULL,
    `useful_life_years` INTEGER NULL,
    `residual_value` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `depreciation_method` VARCHAR(20) NOT NULL DEFAULT 'STRAIGHT_LINE',
    `accumulated_depreciation` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `book_value` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `location` VARCHAR(150) NULL,
    `assigned_to` BIGINT UNSIGNED NULL,
    `condition` VARCHAR(20) NOT NULL DEFAULT 'GOOD',
    `status` VARCHAR(20) NOT NULL DEFAULT 'ACTIVE',
    `disposal_date` DATE NULL,
    `disposal_reason` TEXT NULL,
    `disposal_value` DECIMAL(20,2) NULL,
    `photo_url` TEXT NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fixed_assets_school_code_unique` (`school_id`, `asset_code`),
    INDEX `fixed_assets_school_index` (`school_id`),
    INDEX `fixed_assets_receipt_item_index` (`goods_receipt_item_id`),
    INDEX `fixed_assets_coa_index` (`account_id`),
    INDEX `fixed_assets_category_index` (`asset_category`),
    INDEX `fixed_assets_serial_index` (`serial_number`),
    INDEX `fixed_assets_acquisition_date_index` (`acquisition_date`),
    INDEX `fixed_assets_location_index` (`location`),
    INDEX `fixed_assets_assigned_to_index` (`assigned_to`),
    INDEX `fixed_assets_condition_index` (`condition`),
    INDEX `fixed_assets_status_index` (`status`),
    INDEX `fixed_assets_deleted_at_index` (`deleted_at`),
    INDEX `fixed_assets_created_by_index` (`created_by`),
    INDEX `fixed_assets_updated_by_index` (`updated_by`),
    CONSTRAINT `fixed_assets_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fixed_assets_goods_receipt_item_id_foreign` FOREIGN KEY (`goods_receipt_item_id`) REFERENCES `goods_receipt_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fixed_assets_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fixed_assets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 140. FIXED_ASSET_DEPRECIATIONS (Riwayat Penyusutan)
-- ============================================
CREATE TABLE `fixed_asset_depreciations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `fixed_asset_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_period_id` BIGINT UNSIGNED NOT NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `period_number` INTEGER NOT NULL DEFAULT 1,
    `depreciation_date` DATE NOT NULL,
    `depreciation_amount` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `accumulated_depreciation` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `book_value` DECIMAL(20,2) NOT NULL DEFAULT 0,
    `is_posted` TINYINT(1) NOT NULL DEFAULT 0,
    `posted_at` TIMESTAMP NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fixed_asset_depreciations_asset_period_unique` (`fixed_asset_id`, `period_number`),
    INDEX `fixed_asset_depreciations_asset_index` (`fixed_asset_id`),
    INDEX `fixed_asset_depreciations_fiscal_period_index` (`fiscal_period_id`),
    INDEX `fixed_asset_depreciations_journal_entry_index` (`journal_entry_id`),
    INDEX `fixed_asset_depreciations_date_index` (`depreciation_date`),
    INDEX `fixed_asset_depreciations_is_posted_index` (`is_posted`),
    INDEX `fixed_asset_depreciations_deleted_at_index` (`deleted_at`),
    INDEX `fixed_asset_depreciations_created_by_index` (`created_by`),
    INDEX `fixed_asset_depreciations_updated_by_index` (`updated_by`),
    CONSTRAINT `fixed_asset_depreciations_fixed_asset_id_foreign` FOREIGN KEY (`fixed_asset_id`) REFERENCES `fixed_assets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fixed_asset_depreciations_fiscal_period_id_foreign` FOREIGN KEY (`fiscal_period_id`) REFERENCES `fiscal_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fixed_asset_depreciations_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

