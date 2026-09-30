-- ==========================================
-- 1. MASTER & REFERENSI DASAR
-- ==========================================

SET FOREIGN_KEY_CHECKS = 0;

-- ---------- test_types (SATU definisi, InnoDB) ----------
DROP TABLE IF EXISTS `test_types`;
CREATE TABLE `test_types` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `name_en` varchar(150) NULL DEFAULT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text NULL,
  `category` enum('akademik','non_akademik','kesehatan','administrasi','lainnya') NOT NULL DEFAULT 'akademik',
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `icon` varchar(100) NULL DEFAULT NULL,
  `color` varchar(20) NULL DEFAULT NULL,
  `badge_class` varchar(50) NULL DEFAULT NULL,
  `default_duration_minutes` int UNSIGNED NULL DEFAULT NULL,
  `default_passing_score` decimal(5,2) NULL DEFAULT NULL,
  `default_max_score` decimal(5,2) NULL DEFAULT NULL,
  `default_weight` decimal(5,2) NOT NULL DEFAULT 100.00,
  `default_method` enum('offline','online','hybrid') NOT NULL DEFAULT 'offline',
  `requires_room` tinyint(1) NOT NULL DEFAULT 1,
  `requires_supervisor` tinyint(1) NOT NULL DEFAULT 1,
  `requires_cbt` tinyint(1) NOT NULL DEFAULT 0,
  `scoring_components` json NULL,
  `settings` json NULL,
  `order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_test_types_code` (`school_id`, `code`, `deleted_at`) USING BTREE,
  UNIQUE INDEX `uk_test_types_slug` (`school_id`, `slug`, `deleted_at`) USING BTREE,
  INDEX `idx_test_types_category` (`category`, `is_active`) USING BTREE,
  INDEX `idx_test_types_parent` (`parent_id`) USING BTREE,
  INDEX `idx_test_types_order` (`order`) USING BTREE,
  CONSTRAINT `fk_test_types_school`     FOREIGN KEY (`school_id`)  REFERENCES `schools` (`id`) ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_test_types_parent`     FOREIGN KEY (`parent_id`)  REFERENCES `test_types` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_test_types_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)   ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_test_types_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)   ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_statuses (SATU definisi) ----------
DROP TABLE IF EXISTS `applicant_statuses`;
CREATE TABLE `applicant_statuses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'NULL = global, terisi = spesifik sekolah',
  `code` varchar(50) NOT NULL COMMENT 'mis: DRAFT, SUBMITTED, VERIFIED',
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text NULL,
  `status_group` enum('registration','verification','selection','announcement','re_registration','enrollment','final') NOT NULL DEFAULT 'registration',
  `status_type` enum('draft','pending','in_progress','success','failed','cancelled','expired','waitlist') NOT NULL DEFAULT 'pending',
  `stage_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_final` tinyint(1) NOT NULL DEFAULT 0,
  `is_success` tinyint(1) NOT NULL DEFAULT 0,
  `is_failure` tinyint(1) NOT NULL DEFAULT 0,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `allow_edit` tinyint(1) NOT NULL DEFAULT 1,
  `allow_upload_document` tinyint(1) NOT NULL DEFAULT 1,
  `allow_payment` tinyint(1) NOT NULL DEFAULT 0,
  `next_status_ids` json NULL,
  `notify_applicant` tinyint(1) NOT NULL DEFAULT 0,
  `notification_template` varchar(100) NULL DEFAULT NULL,
  `color` varchar(20) NULL DEFAULT NULL,
  `badge_class` varchar(50) NULL DEFAULT NULL,
  `icon` varchar(100) NULL DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_applicant_status_code` (`school_id`, `code`, `deleted_at`) USING BTREE,
  UNIQUE INDEX `uk_applicant_status_slug` (`school_id`, `slug`, `deleted_at`) USING BTREE,
  INDEX `idx_applicant_status_group` (`status_group`) USING BTREE,
  INDEX `idx_applicant_status_type` (`status_type`) USING BTREE,
  INDEX `idx_applicant_status_order` (`stage_order`) USING BTREE,
  INDEX `idx_applicant_status_active` (`is_active`) USING BTREE,
  INDEX `idx_applicant_status_default` (`is_default`) USING BTREE,
  CONSTRAINT `fk_applicant_statuses_school`     FOREIGN KEY (`school_id`)  REFERENCES `schools` (`id`) ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_statuses_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)   ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_statuses_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)   ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_categories (SATU definisi) ----------
DROP TABLE IF EXISTS `applicant_categories`;
CREATE TABLE `applicant_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'NULL = global',
  `admission_id` bigint UNSIGNED NULL DEFAULT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text NULL,
  `category_type` enum('general','afirmasi','prestasi','khusus','pindahan','lainnya') NOT NULL DEFAULT 'general',
  `priority_level` int UNSIGNED NOT NULL DEFAULT 0,
  `weight` decimal(5,2) NOT NULL DEFAULT 0.00,
  `requires_document` tinyint(1) NOT NULL DEFAULT 0,
  `required_document_types` json NULL,
  `required_fields` json NULL,
  `icon` varchar(100) NULL DEFAULT NULL,
  `color` varchar(20) NULL DEFAULT NULL,
  `badge_class` varchar(50) NULL DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_applicant_category_code` (`school_id`, `code`, `deleted_at`) USING BTREE,
  UNIQUE INDEX `uk_applicant_category_slug` (`school_id`, `slug`, `deleted_at`) USING BTREE,
  INDEX `idx_applicant_category_admission` (`admission_id`) USING BTREE,
  INDEX `idx_applicant_category_parent` (`parent_id`) USING BTREE,
  INDEX `idx_applicant_category_type` (`category_type`) USING BTREE,
  INDEX `idx_applicant_category_active` (`is_active`) USING BTREE,
  CONSTRAINT `fk_applicant_categories_school`     FOREIGN KEY (`school_id`)    REFERENCES `schools` (`id`) ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_categories_admission`  FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_categories_parent`     FOREIGN KEY (`parent_id`)    REFERENCES `applicant_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_categories_created_by` FOREIGN KEY (`created_by`)   REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_categories_updated_by` FOREIGN KEY (`updated_by`)   REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ==========================================
-- 2. GELOMBANG & JALUR PENDAFTARAN (ADMISSIONS)
-- ==========================================

-- ---------- admissions ----------
DROP TABLE IF EXISTS `admissions`;
CREATE TABLE `admissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `class_level_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `type` varchar(20) NULL DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `quota` int UNSIGNED NOT NULL DEFAULT 0,
  `registration_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `min_age` int NULL DEFAULT NULL,
  `max_age` int NULL DEFAULT NULL,
  `requirements` text NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_admissions_code` (`school_id`, `code`, `deleted_at`) USING BTREE,
  INDEX `idx_admissions_academic_year` (`academic_year_id`) USING BTREE,
  INDEX `idx_admissions_class_level` (`class_level_id`) USING BTREE,
  INDEX `idx_admissions_dates` (`start_date`, `end_date`) USING BTREE,
  INDEX `idx_admissions_active` (`is_active`) USING BTREE,
  CONSTRAINT `fk_admissions_school`        FOREIGN KEY (`school_id`)        REFERENCES `schools` (`id`)        ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admissions_academic_year` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admissions_class_level`   FOREIGN KEY (`class_level_id`)   REFERENCES `class_levels` (`id`)   ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admissions_created_by`    FOREIGN KEY (`created_by`)       REFERENCES `users` (`id`)          ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admissions_updated_by`    FOREIGN KEY (`updated_by`)       REFERENCES `users` (`id`)          ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- admission_paths ----------
DROP TABLE IF EXISTS `admission_paths`;
CREATE TABLE `admission_paths` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NULL,
  `quota` int UNSIGNED NOT NULL DEFAULT 0,
  `min_score` decimal(5,2) NULL DEFAULT NULL,
  `max_score` decimal(5,2) NULL DEFAULT NULL,
  `requirements` json NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `open_date` date NULL DEFAULT NULL,
  `close_date` date NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_admission_paths_code` (`admission_id`, `code`, `deleted_at`) USING BTREE,
  INDEX `idx_admission_paths_org` (`organization_id`) USING BTREE,
  INDEX `idx_admission_paths_active` (`is_active`) USING BTREE,
  CONSTRAINT `fk_admission_paths_admission`    FOREIGN KEY (`admission_id`)    REFERENCES `admissions` (`id`)    ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_paths_organization` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_paths_created_by`   FOREIGN KEY (`created_by`)      REFERENCES `users` (`id`)         ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_paths_updated_by`   FOREIGN KEY (`updated_by`)      REFERENCES `users` (`id`)         ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- admission_quota_settings ----------
DROP TABLE IF EXISTS `admission_quota_settings`;
CREATE TABLE `admission_quota_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `admission_path_id` bigint UNSIGNED NULL DEFAULT NULL,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `study_program_id` bigint UNSIGNED NULL DEFAULT NULL,
  `faculty_id` bigint UNSIGNED NULL DEFAULT NULL,
  `education_level_id` bigint UNSIGNED NULL DEFAULT NULL,
  `class_level_id` bigint UNSIGNED NULL DEFAULT NULL,
  `quota` int UNSIGNED NOT NULL DEFAULT 0,
  `filled` int UNSIGNED NOT NULL DEFAULT 0,
  `reserved` int UNSIGNED NOT NULL DEFAULT 0,
  `waitlist_quota` int UNSIGNED NOT NULL DEFAULT 0,
  `min_quota` int UNSIGNED NOT NULL DEFAULT 0,
  `notes` text NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_admission_quota_unique` (`admission_id`, `admission_path_id`, `study_program_id`, `education_level_id`) USING BTREE,
  INDEX `idx_admission_quota_org` (`organization_id`) USING BTREE,
  INDEX `idx_admission_quota_faculty` (`faculty_id`) USING BTREE,
  INDEX `idx_admission_quota_level` (`education_level_id`) USING BTREE,
  INDEX `idx_admission_quota_class_level` (`class_level_id`) USING BTREE,
  CONSTRAINT `fk_admission_quota_admission`      FOREIGN KEY (`admission_id`)        REFERENCES `admissions` (`id`)        ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_quota_path`           FOREIGN KEY (`admission_path_id`)   REFERENCES `admission_paths` (`id`)   ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_quota_organization`   FOREIGN KEY (`organization_id`)     REFERENCES `organizations` (`id`)     ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_quota_study_program`  FOREIGN KEY (`study_program_id`)    REFERENCES `study_programs` (`id`)    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_quota_faculty`        FOREIGN KEY (`faculty_id`)          REFERENCES `faculties` (`id`)         ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_quota_education_level` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`)  ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_quota_class_level`    FOREIGN KEY (`class_level_id`)      REFERENCES `class_levels` (`id`)      ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_quota_created_by`     FOREIGN KEY (`created_by`)          REFERENCES `users` (`id`)             ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_quota_updated_by`     FOREIGN KEY (`updated_by`)          REFERENCES `users` (`id`)             ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ==========================================
-- 3. PENDAFTAR & DATA TERPISAH (APPLICANTS)
-- ==========================================

-- ---------- applicants ----------
DROP TABLE IF EXISTS `applicants`;
CREATE TABLE `applicants` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `nik` varchar(50) NULL DEFAULT NULL,
  `birth_place` varchar(100) NULL DEFAULT NULL,
  `birth_date` date NULL DEFAULT NULL,
  `gender` varchar(10) NULL DEFAULT NULL,
  `address` text NULL,
  `phone` varchar(20) NULL DEFAULT NULL,
  `email` varchar(100) NULL DEFAULT NULL,
  `parent_name` varchar(150) NULL DEFAULT NULL,
  `parent_phone` varchar(20) NULL DEFAULT NULL,
  `parent_email` varchar(100) NULL DEFAULT NULL,
  `previous_school` varchar(150) NULL DEFAULT NULL,
  `registration_number` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'REGISTERED',
  `test_score` decimal(5,2) NULL DEFAULT NULL,
  `interview_score` decimal(5,2) NULL DEFAULT NULL,
  `total_score` decimal(5,2) NULL DEFAULT NULL,
  `ranking` int NULL DEFAULT NULL,
  `notes` text NULL,
  `registered_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_applicants_school_reg` (`school_id`, `registration_number`, `deleted_at`) USING BTREE,
  UNIQUE INDEX `uk_applicants_school_nik` (`school_id`, `nik`, `deleted_at`) USING BTREE,
  INDEX `idx_applicants_admission` (`admission_id`) USING BTREE,
  INDEX `idx_applicants_status` (`status`) USING BTREE,
  INDEX `idx_applicants_reg_number` (`registration_number`) USING BTREE,
  INDEX `idx_applicants_created_by` (`created_by`) USING BTREE,
  INDEX `idx_applicants_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `fk_applicants_school`     FOREIGN KEY (`school_id`)    REFERENCES `schools` (`id`)    ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_applicants_admission`  FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_applicants_created_by` FOREIGN KEY (`created_by`)   REFERENCES `users` (`id`)      ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_applicants_updated_by` FOREIGN KEY (`updated_by`)   REFERENCES `users` (`id`)      ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_domiciles ----------
DROP TABLE IF EXISTS `applicant_domiciles`;
CREATE TABLE `applicant_domiciles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `address` text NOT NULL,
  `rt` varchar(5) NULL DEFAULT NULL,
  `rw` varchar(5) NULL DEFAULT NULL,
  `postal_code` varchar(10) NULL DEFAULT NULL,
  `province_id` bigint UNSIGNED NULL DEFAULT NULL,
  `regency_id` bigint UNSIGNED NULL DEFAULT NULL,
  `district_id` bigint UNSIGNED NULL DEFAULT NULL,
  `village_id` bigint UNSIGNED NULL DEFAULT NULL,
  `latitude` decimal(10,8) NULL DEFAULT NULL,
  `longitude` decimal(11,8) NULL DEFAULT NULL,
  `distance_to_school_m` decimal(10,2) NULL DEFAULT NULL,
  `distance_calculated_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verification_notes` text NULL,
  `document_kk_url` varchar(255) NULL DEFAULT NULL,
  `document_house_photo_url` varchar(255) NULL DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_applicant_domicile_applicant` (`applicant_id`) USING BTREE,
  INDEX `idx_applicant_domicile_village` (`village_id`) USING BTREE,
  INDEX `idx_applicant_domicile_distance` (`distance_to_school_m`) USING BTREE,
  INDEX `idx_applicant_domicile_verified` (`is_verified`) USING BTREE,
  CONSTRAINT `fk_applicant_domiciles_applicant`   FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_domiciles_verified_by` FOREIGN KEY (`verified_by`)  REFERENCES `users` (`id`)      ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_parents ----------
DROP TABLE IF EXISTS `applicant_parents`;
CREATE TABLE `applicant_parents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `relation_type` enum('father','mother','guardian') NOT NULL DEFAULT 'father',
  `name` varchar(150) NOT NULL,
  `nik` varchar(20) NULL DEFAULT NULL,
  `birth_place` varchar(100) NULL DEFAULT NULL,
  `birth_date` date NULL DEFAULT NULL,
  `education_level` varchar(50) NULL DEFAULT NULL,
  `job` varchar(100) NULL DEFAULT NULL,
  `income_range` enum('below_1m','1m_2m','2m_5m','5m_10m','above_10m','unknown') NULL DEFAULT NULL,
  `phone` varchar(20) NULL DEFAULT NULL,
  `email` varchar(100) NULL DEFAULT NULL,
  `address` text NULL,
  `is_alive` tinyint(1) NOT NULL DEFAULT 1,
  `is_primary_contact` tinyint(1) NOT NULL DEFAULT 0,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_applicant_parent_applicant` (`applicant_id`) USING BTREE,
  INDEX `idx_applicant_parent_relation` (`relation_type`) USING BTREE,
  CONSTRAINT `fk_applicant_parents_applicant` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_achievements ----------
DROP TABLE IF EXISTS `applicant_achievements`;
CREATE TABLE `applicant_achievements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `achievement_type` enum('academic','non_academic','sport','art','science','other') NOT NULL DEFAULT 'academic',
  `title` varchar(200) NOT NULL,
  `level` enum('school','district','regency','province','national','international') NOT NULL DEFAULT 'school',
  `rank` varchar(50) NULL DEFAULT NULL,
  `organizer` varchar(150) NULL DEFAULT NULL,
  `year` year NULL DEFAULT NULL,
  `certificate_number` varchar(100) NULL DEFAULT NULL,
  `certificate_url` varchar(255) NULL DEFAULT NULL,
  `score_point` decimal(5,2) NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `notes` text NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_applicant_achievement_applicant` (`applicant_id`) USING BTREE,
  INDEX `idx_applicant_achievement_type` (`achievement_type`) USING BTREE,
  INDEX `idx_applicant_achievement_level` (`level`) USING BTREE,
  CONSTRAINT `fk_applicant_achievements_applicant`   FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_achievements_verified_by` FOREIGN KEY (`verified_by`)  REFERENCES `users` (`id`)      ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_support_programs ----------
DROP TABLE IF EXISTS `applicant_support_programs`;
CREATE TABLE `applicant_support_programs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `program_type` enum('kip','pip','ketm','abk','yatim','lainnya') NOT NULL DEFAULT 'kip',
  `card_number` varchar(100) NULL DEFAULT NULL,
  `program_name` varchar(150) NULL DEFAULT NULL,
  `issuer` varchar(150) NULL DEFAULT NULL,
  `valid_from` date NULL DEFAULT NULL,
  `valid_until` date NULL DEFAULT NULL,
  `card_url` varchar(255) NULL DEFAULT NULL,
  `supporting_document_url` varchar(255) NULL DEFAULT NULL,
  `validation_status` enum('pending','valid','invalid','need_verification') NOT NULL DEFAULT 'pending',
  `validated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `validation_notes` text NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_applicant_support_applicant` (`applicant_id`) USING BTREE,
  INDEX `idx_applicant_support_type` (`program_type`) USING BTREE,
  INDEX `idx_applicant_support_status` (`validation_status`) USING BTREE,
  CONSTRAINT `fk_applicant_support_programs_applicant`    FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_support_programs_validated_by` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`)      ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_registrations ----------
DROP TABLE IF EXISTS `applicant_registrations`;
CREATE TABLE `applicant_registrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `registration_number` varchar(50) NULL DEFAULT NULL,
  `registration_date` datetime NULL DEFAULT NULL,
  `registration_method` enum('online','offline','on_site') NOT NULL DEFAULT 'online',
  `registration_status` enum('pending','confirmed','cancelled','expired') NOT NULL DEFAULT 'pending',
  `officer_id` bigint UNSIGNED NULL DEFAULT NULL,
  `location` varchar(150) NULL DEFAULT NULL,
  `proof_url` varchar(255) NULL DEFAULT NULL,
  `uniform_size` varchar(10) NULL DEFAULT NULL,
  `notes` text NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_applicant_registration_applicant` (`applicant_id`) USING BTREE,
  INDEX `idx_applicant_registration_admission` (`admission_id`) USING BTREE,
  INDEX `idx_applicant_registration_status` (`registration_status`) USING BTREE,
  CONSTRAINT `fk_applicant_registrations_applicant` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_registrations_admission` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_choices ----------
DROP TABLE IF EXISTS `applicant_choices`;
CREATE TABLE `applicant_choices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `study_program_id` bigint UNSIGNED NOT NULL,
  `choice_order` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 = Pilihan Pertama, 2 = Pilihan Kedua',
  `is_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_applicant_choice_order` (`applicant_id`, `choice_order`) USING BTREE,
  CONSTRAINT `fk_applicant_choices_applicant`     FOREIGN KEY (`applicant_id`)     REFERENCES `applicants` (`id`)     ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_choices_study_program` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- PIVOT: applicant_applicant_category ----------
DROP TABLE IF EXISTS `applicant_applicant_category`;
CREATE TABLE `applicant_applicant_category` (
  `applicant_id` bigint UNSIGNED NOT NULL,
  `applicant_category_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`applicant_id`, `applicant_category_id`) USING BTREE,
  CONSTRAINT `fk_pivot_app_cat_applicant` FOREIGN KEY (`applicant_id`)          REFERENCES `applicants` (`id`)           ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pivot_app_cat_category`  FOREIGN KEY (`applicant_category_id`) REFERENCES `applicant_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ==========================================
-- 4. VERIFIKASI DOKUMEN & PRESTASI
-- ==========================================

-- ---------- application_documents ----------
DROP TABLE IF EXISTS `application_documents`;
CREATE TABLE `application_documents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `document_type` varchar(30) NULL DEFAULT NULL,
  `document_url` text NOT NULL,
  `file_name` varchar(200) NULL DEFAULT NULL,
  `file_size` bigint NULL DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verification_notes` text NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_application_documents_applicant` (`applicant_id`) USING BTREE,
  INDEX `idx_application_documents_type` (`document_type`) USING BTREE,
  INDEX `idx_application_documents_verified` (`verified`) USING BTREE,
  CONSTRAINT `fk_application_documents_applicant`   FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_application_documents_verified_by` FOREIGN KEY (`verified_by`)  REFERENCES `users` (`id`)      ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- admission_document_verifications ----------
DROP TABLE IF EXISTS `admission_document_verifications`;
CREATE TABLE `admission_document_verifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `application_document_id` bigint UNSIGNED NULL DEFAULT NULL,
  `admission_id` bigint UNSIGNED NULL DEFAULT NULL,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `document_type` varchar(100) NOT NULL,
  `document_number` varchar(100) NULL DEFAULT NULL,
  `file_path` varchar(255) NULL DEFAULT NULL,
  `verification_status` enum('pending','verified','rejected','revision_required','expired') NOT NULL DEFAULT 'pending',
  `verification_method` enum('manual','online','physical','third_party') NOT NULL DEFAULT 'manual',
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint UNSIGNED NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text NULL,
  `revision_notes` text NULL,
  `revision_deadline` date NULL DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 1,
  `is_valid` tinyint(1) NOT NULL DEFAULT 0,
  `valid_until` date NULL DEFAULT NULL,
  `notes` text NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_admission_verif_applicant` (`applicant_id`) USING BTREE,
  INDEX `idx_admission_verif_document` (`application_document_id`) USING BTREE,
  INDEX `idx_admission_verif_admission` (`admission_id`) USING BTREE,
  INDEX `idx_admission_verif_org` (`organization_id`) USING BTREE,
  INDEX `idx_admission_verif_status` (`verification_status`) USING BTREE,
  INDEX `idx_admission_verif_type` (`document_type`) USING BTREE,
  CONSTRAINT `fk_admission_verif_admission`   FOREIGN KEY (`admission_id`)            REFERENCES `admissions` (`id`)            ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_verif_applicant`   FOREIGN KEY (`applicant_id`)            REFERENCES `applicants` (`id`)            ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_verif_document`    FOREIGN KEY (`application_document_id`) REFERENCES `application_documents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_verif_organization` FOREIGN KEY (`organization_id`)         REFERENCES `organizations` (`id`)         ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_verif_verified_by` FOREIGN KEY (`verified_by`)              REFERENCES `users` (`id`)                 ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_verif_rejected_by` FOREIGN KEY (`rejected_by`)              REFERENCES `users` (`id`)                 ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_verif_created_by`  FOREIGN KEY (`created_by`)               REFERENCES `users` (`id`)                 ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_verif_updated_by`  FOREIGN KEY (`updated_by`)               REFERENCES `users` (`id`)                 ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ==========================================
-- 5. SELEKSI, JADWAL TES & WAWANCARA
-- ==========================================

-- ---------- admission_selections ----------
DROP TABLE IF EXISTS `admission_selections`;
CREATE TABLE `admission_selections` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `selection_type` varchar(20) NULL DEFAULT NULL,
  `score` decimal(5,2) NULL DEFAULT NULL,
  `result` varchar(20) NULL DEFAULT NULL,
  `evaluator_id` bigint UNSIGNED NULL DEFAULT NULL,
  `evaluation_date` timestamp NULL DEFAULT NULL,
  `notes` text NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_admission_selections_admission` (`admission_id`) USING BTREE,
  INDEX `idx_admission_selections_applicant` (`applicant_id`) USING BTREE,
  INDEX `idx_admission_selections_type` (`selection_type`) USING BTREE,
  INDEX `idx_admission_selections_result` (`result`) USING BTREE,
  INDEX `idx_admission_selections_evaluator` (`evaluator_id`) USING BTREE,
  CONSTRAINT `fk_admission_selections_admission`   FOREIGN KEY (`admission_id`)  REFERENCES `admissions` (`id`)  ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_selections_applicant`   FOREIGN KEY (`applicant_id`)  REFERENCES `applicants` (`id`)  ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_selections_evaluator`   FOREIGN KEY (`evaluator_id`)  REFERENCES `users` (`id`)       ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_selections_created_by`  FOREIGN KEY (`created_by`)    REFERENCES `users` (`id`)       ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_selections_updated_by`  FOREIGN KEY (`updated_by`)    REFERENCES `users` (`id`)       ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- admission_test_schedules ----------
DROP TABLE IF EXISTS `admission_test_schedules`;
CREATE TABLE `admission_test_schedules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `admission_path_id` bigint UNSIGNED NULL DEFAULT NULL,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `admission_selection_id` bigint UNSIGNED NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text NULL,
  `test_type` enum('tulis','wawancara','praktek','kesehatan','psikotes','lainnya') NOT NULL DEFAULT 'tulis',
  `test_type_id` bigint UNSIGNED NULL DEFAULT NULL,
  `test_method` enum('offline','online','hybrid') NOT NULL DEFAULT 'offline',
  `test_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_minutes` int UNSIGNED NULL DEFAULT NULL,
  `registration_start` datetime NULL DEFAULT NULL,
  `registration_end` datetime NULL DEFAULT NULL,
  `room_id` bigint UNSIGNED NULL DEFAULT NULL,
  `building_id` bigint UNSIGNED NULL DEFAULT NULL,
  `location` varchar(255) NULL DEFAULT NULL,
  `online_meeting_url` varchar(500) NULL DEFAULT NULL,
  `online_meeting_id` varchar(100) NULL DEFAULT NULL,
  `online_password` varchar(100) NULL DEFAULT NULL,
  `cbt_session_id` bigint UNSIGNED NULL DEFAULT NULL,
  `exam_package_id` bigint UNSIGNED NULL DEFAULT NULL,
  `quota` int UNSIGNED NOT NULL DEFAULT 0,
  `registered` int UNSIGNED NOT NULL DEFAULT 0,
  `attended` int UNSIGNED NOT NULL DEFAULT 0,
  `min_participants` int UNSIGNED NOT NULL DEFAULT 0,
  `supervisor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `coordinator_id` bigint UNSIGNED NULL DEFAULT NULL,
  `proctor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `proctor_ids` json NULL,
  `allow_self_register` tinyint(1) NOT NULL DEFAULT 0,
  `allow_reschedule` tinyint(1) NOT NULL DEFAULT 0,
  `late_tolerance_min` int UNSIGNED NOT NULL DEFAULT 15,
  `status` enum('draft','published','ongoing','completed','cancelled') NOT NULL DEFAULT 'draft',
  `is_online` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `passing_score` decimal(5,2) NULL DEFAULT NULL,
  `max_score` decimal(5,2) NULL DEFAULT NULL,
  `score_input_deadline` datetime NULL DEFAULT NULL,
  `result_announcement` datetime NULL DEFAULT NULL,
  `notes` text NULL,
  `instructions` text NULL,
  `attachments` json NULL,
  `settings` json NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_admission_test_schedules_code` (`code`) USING BTREE,
  INDEX `idx_test_schedules_admission` (`admission_id`) USING BTREE,
  INDEX `idx_test_schedules_path` (`admission_path_id`) USING BTREE,
  INDEX `idx_test_schedules_organization` (`organization_id`) USING BTREE,
  INDEX `idx_test_schedules_selection` (`admission_selection_id`) USING BTREE,
  INDEX `idx_test_schedules_academic_year` (`academic_year_id`) USING BTREE,
  INDEX `idx_test_schedules_date` (`test_date`, `start_time`) USING BTREE,
  INDEX `idx_test_schedules_status` (`status`, `is_active`) USING BTREE,
  INDEX `idx_test_schedules_test_type` (`test_type`) USING BTREE,
  INDEX `idx_test_schedules_test_type_id` (`test_type_id`) USING BTREE,
  INDEX `idx_test_schedules_cbt_session` (`cbt_session_id`) USING BTREE,
  INDEX `idx_test_schedules_proctor` (`proctor_id`) USING BTREE,
  INDEX `idx_test_schedules_is_online` (`is_online`) USING BTREE,
  CONSTRAINT `fk_test_schedules_admission`     FOREIGN KEY (`admission_id`)     REFERENCES `admissions` (`id`)     ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_test_schedules_path`          FOREIGN KEY (`admission_path_id`) REFERENCES `admission_paths` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_test_schedules_organization`  FOREIGN KEY (`organization_id`)  REFERENCES `organizations` (`id`)  ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_test_schedules_test_type`     FOREIGN KEY (`test_type_id`)     REFERENCES `test_types` (`id`)     ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_test_schedules_proctor`       FOREIGN KEY (`proctor_id`)       REFERENCES `employees` (`id`)      ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_test_schedules_room`          FOREIGN KEY (`room_id`)          REFERENCES `rooms` (`id`)          ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_test_schedules_created_by`    FOREIGN KEY (`created_by`)       REFERENCES `users` (`id`)          ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_test_schedules_updated_by`    FOREIGN KEY (`updated_by`)       REFERENCES `users` (`id`)          ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- admission_test_results ----------
DROP TABLE IF EXISTS `admission_test_results`;
CREATE TABLE `admission_test_results` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_test_schedule_id` bigint UNSIGNED NOT NULL,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `admission_path_id` bigint UNSIGNED NULL DEFAULT NULL,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `test_type_id` bigint UNSIGNED NULL DEFAULT NULL,
  `registration_number` varchar(50) NULL DEFAULT NULL,
  `attendance_status` enum('present','absent','late','sick','permission') NOT NULL DEFAULT 'present',
  `check_in_time` datetime NULL DEFAULT NULL,
  `check_out_time` datetime NULL DEFAULT NULL,
  `raw_score` decimal(8,2) NULL DEFAULT NULL,
  `final_score` decimal(8,2) NULL DEFAULT NULL,
  `max_score` decimal(8,2) NULL DEFAULT NULL,
  `percentage` decimal(5,2) NULL DEFAULT NULL,
  `grade` varchar(10) NULL DEFAULT NULL,
  `rank` int UNSIGNED NULL DEFAULT NULL,
  `passing_status` enum('pending','passed','failed','waitlist') NOT NULL DEFAULT 'pending',
  `cbt_session_id` bigint UNSIGNED NULL DEFAULT NULL,
  `cbt_session_answer_id` bigint UNSIGNED NULL DEFAULT NULL,
  `detail_scores` json NULL,
  `notes` text NULL,
  `graded_by` bigint UNSIGNED NULL DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_admission_test_result` (`admission_test_schedule_id`, `applicant_id`) USING BTREE,
  INDEX `idx_admission_result_applicant` (`applicant_id`) USING BTREE,
  INDEX `idx_admission_result_admission` (`admission_id`) USING BTREE,
  INDEX `idx_admission_result_path` (`admission_path_id`) USING BTREE,
  INDEX `idx_admission_result_org` (`organization_id`) USING BTREE,
  INDEX `idx_admission_result_status` (`passing_status`) USING BTREE,
  INDEX `idx_admission_result_rank` (`rank`) USING BTREE,
  CONSTRAINT `fk_admission_test_results_schedule`   FOREIGN KEY (`admission_test_schedule_id`) REFERENCES `admission_test_schedules` (`id`) ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_test_results_applicant`  FOREIGN KEY (`applicant_id`)              REFERENCES `applicants` (`id`)              ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_test_results_admission`  FOREIGN KEY (`admission_id`)              REFERENCES `admissions` (`id`)              ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_test_results_path`       FOREIGN KEY (`admission_path_id`)         REFERENCES `admission_paths` (`id`)         ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_test_results_org`        FOREIGN KEY (`organization_id`)           REFERENCES `organizations` (`id`)           ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_test_results_test_type`  FOREIGN KEY (`test_type_id`)              REFERENCES `test_types` (`id`)              ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_test_results_cbt_session` FOREIGN KEY (`cbt_session_id`)           REFERENCES `cbt_sessions` (`id`)            ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_test_results_cbt_answer`  FOREIGN KEY (`cbt_session_answer_id`)     REFERENCES `cbt_session_answers` (`id`)     ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_test_results_graded_by`   FOREIGN KEY (`graded_by`)                 REFERENCES `users` (`id`)                   ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_test_results_created_by`  FOREIGN KEY (`created_by`)                REFERENCES `users` (`id`)                   ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_test_results_updated_by`  FOREIGN KEY (`updated_by`)                REFERENCES `users` (`id`)                   ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- admission_interviews ----------
DROP TABLE IF EXISTS `admission_interviews`;
CREATE TABLE `admission_interviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `admission_path_id` bigint UNSIGNED NULL DEFAULT NULL,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `interviewer_id` bigint UNSIGNED NULL DEFAULT NULL,
  `secondary_interviewer_id` bigint UNSIGNED NULL DEFAULT NULL,
  `room_id` bigint UNSIGNED NULL DEFAULT NULL,
  `schedule_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NULL DEFAULT NULL,
  `duration_minutes` int UNSIGNED NULL DEFAULT NULL,
  `interview_type` enum('in_person','online','phone','video_call') NOT NULL DEFAULT 'in_person',
  `meeting_url` varchar(255) NULL DEFAULT NULL,
  `location` varchar(255) NULL DEFAULT NULL,
  `status` enum('scheduled','ongoing','completed','no_show','cancelled','rescheduled') NOT NULL DEFAULT 'scheduled',
  `attendance_status` enum('present','absent','late','sick','permission') NOT NULL DEFAULT 'present',
  `score` decimal(5,2) NULL DEFAULT NULL,
  `max_score` decimal(5,2) NULL DEFAULT NULL,
  `recommendation` enum('strongly_recommended','recommended','neutral','not_recommended','strongly_not_recommended') NULL DEFAULT NULL,
  `result` enum('pending','passed','failed','waitlist') NOT NULL DEFAULT 'pending',
  `notes` text NULL,
  `internal_notes` text NULL,
  `attachment_path` varchar(255) NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_admission_interview_admission` (`admission_id`) USING BTREE,
  INDEX `idx_admission_interview_path` (`admission_path_id`) USING BTREE,
  INDEX `idx_admission_interview_applicant` (`applicant_id`) USING BTREE,
  INDEX `idx_admission_interview_org` (`organization_id`) USING BTREE,
  INDEX `idx_admission_interview_date` (`schedule_date`) USING BTREE,
  INDEX `idx_admission_interview_status` (`status`) USING BTREE,
  INDEX `idx_admission_interview_interviewer` (`interviewer_id`) USING BTREE,
  CONSTRAINT `fk_admission_interviews_admission`     FOREIGN KEY (`admission_id`)              REFERENCES `admissions` (`id`)     ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_interviews_path`          FOREIGN KEY (`admission_path_id`)         REFERENCES `admission_paths` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_interviews_applicant`     FOREIGN KEY (`applicant_id`)              REFERENCES `applicants` (`id`)      ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_interviews_organization`  FOREIGN KEY (`organization_id`)           REFERENCES `organizations` (`id`)   ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_interviews_interviewer`   FOREIGN KEY (`interviewer_id`)            REFERENCES `employees` (`id`)       ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_interviews_secondary`     FOREIGN KEY (`secondary_interviewer_id`)  REFERENCES `employees` (`id`)       ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_interviews_room`          FOREIGN KEY (`room_id`)                   REFERENCES `rooms` (`id`)           ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_interviews_created_by`    FOREIGN KEY (`created_by`)                REFERENCES `users` (`id`)           ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_interviews_updated_by`    FOREIGN KEY (`updated_by`)                REFERENCES `users` (`id`)           ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ==========================================
-- 6. PENGUMUMAN & TIMELINE
-- ==========================================

-- ---------- admission_announcements ----------
DROP TABLE IF EXISTS `admission_announcements`;
CREATE TABLE `admission_announcements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `admission_path_id` bigint UNSIGNED NULL DEFAULT NULL,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `education_level_id` bigint UNSIGNED NULL DEFAULT NULL,
  `study_program_id` bigint UNSIGNED NULL DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `content` longtext NULL,
  `announcement_type` enum('selection_result','document_verification','test_schedule','interview_schedule','general','re_registration','waitlist') NOT NULL DEFAULT 'selection_result',
  `announcement_date` datetime NOT NULL,
  `publish_date` datetime NULL DEFAULT NULL,
  `expired_date` datetime NULL DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `requires_login` tinyint(1) NOT NULL DEFAULT 0,
  `attachment_path` varchar(255) NULL DEFAULT NULL,
  `external_url` varchar(255) NULL DEFAULT NULL,
  `show_ranking` tinyint(1) NOT NULL DEFAULT 0,
  `show_score` tinyint(1) NOT NULL DEFAULT 0,
  `total_participants` int UNSIGNED NOT NULL DEFAULT 0,
  `total_passed` int UNSIGNED NOT NULL DEFAULT 0,
  `total_failed` int UNSIGNED NOT NULL DEFAULT 0,
  `total_waitlist` int UNSIGNED NOT NULL DEFAULT 0,
  `notification_sent` tinyint(1) NOT NULL DEFAULT 0,
  `notification_sent_at` timestamp NULL DEFAULT NULL,
  `notification_channels` json NULL,
  `meta_title` varchar(200) NULL DEFAULT NULL,
  `meta_description` text NULL,
  `meta_keywords` varchar(255) NULL DEFAULT NULL,
  `views_count` int UNSIGNED NOT NULL DEFAULT 0,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_admission_announcement_slug` (`slug`, `deleted_at`) USING BTREE,
  INDEX `idx_admission_ann_admission` (`admission_id`) USING BTREE,
  INDEX `idx_admission_ann_path` (`admission_path_id`) USING BTREE,
  INDEX `idx_admission_ann_org` (`organization_id`) USING BTREE,
  INDEX `idx_admission_ann_level` (`education_level_id`) USING BTREE,
  INDEX `idx_admission_ann_program` (`study_program_id`) USING BTREE,
  INDEX `idx_admission_ann_type` (`announcement_type`) USING BTREE,
  INDEX `idx_admission_ann_date` (`announcement_date`) USING BTREE,
  INDEX `idx_admission_ann_published` (`is_published`) USING BTREE,
  INDEX `idx_admission_ann_public` (`is_public`) USING BTREE,
  CONSTRAINT `fk_admission_ann_admission`   FOREIGN KEY (`admission_id`)        REFERENCES `admissions` (`id`)        ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_ann_path`        FOREIGN KEY (`admission_path_id`)   REFERENCES `admission_paths` (`id`)   ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_ann_org`         FOREIGN KEY (`organization_id`)     REFERENCES `organizations` (`id`)     ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_ann_level`       FOREIGN KEY (`education_level_id`)  REFERENCES `education_levels` (`id`)  ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_ann_program`     FOREIGN KEY (`study_program_id`)    REFERENCES `study_programs` (`id`)    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_ann_created_by`  FOREIGN KEY (`created_by`)          REFERENCES `users` (`id`)             ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_ann_updated_by`  FOREIGN KEY (`updated_by`)          REFERENCES `users` (`id`)             ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- admission_timelines ----------
DROP TABLE IF EXISTS `admission_timelines`;
CREATE TABLE `admission_timelines` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `phase_name` varchar(50) NOT NULL,
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `description` text NULL,
  `order_number` int NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_admission_timelines_admission` (`admission_id`) USING BTREE,
  INDEX `idx_admission_timelines_order` (`order_number`) USING BTREE,
  CONSTRAINT `fk_admission_timelines_admission` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- admission_registration_fees ----------
DROP TABLE IF EXISTS `admission_registration_fees`;
CREATE TABLE `admission_registration_fees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `admission_path_id` bigint UNSIGNED NULL DEFAULT NULL,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `education_level_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `fee_type` enum('registration','test','interview','document','other') NOT NULL DEFAULT 'registration',
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `currency_id` bigint UNSIGNED NULL DEFAULT NULL,
  `chart_of_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 1,
  `is_refundable` tinyint(1) NOT NULL DEFAULT 0,
  `early_bird_amount` decimal(15,2) NULL DEFAULT NULL,
  `early_bird_until` date NULL DEFAULT NULL,
  `effective_from` date NULL DEFAULT NULL,
  `effective_until` date NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_admission_fee_admission` (`admission_id`) USING BTREE,
  INDEX `idx_admission_fee_path` (`admission_path_id`) USING BTREE,
  INDEX `idx_admission_fee_org` (`organization_id`) USING BTREE,
  INDEX `idx_admission_fee_level` (`education_level_id`) USING BTREE,
  INDEX `idx_admission_fee_type` (`fee_type`) USING BTREE,
  INDEX `idx_admission_fee_active` (`is_active`) USING BTREE,
  CONSTRAINT `fk_admission_fee_admission`   FOREIGN KEY (`admission_id`)        REFERENCES `admissions` (`id`)        ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_fee_path`        FOREIGN KEY (`admission_path_id`)   REFERENCES `admission_paths` (`id`)   ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_fee_org`         FOREIGN KEY (`organization_id`)     REFERENCES `organizations` (`id`)     ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_fee_level`       FOREIGN KEY (`education_level_id`)  REFERENCES `education_levels` (`id`)  ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_fee_currency`    FOREIGN KEY (`currency_id`)         REFERENCES `currencies` (`id`)        ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_fee_coa`         FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_fee_created_by`  FOREIGN KEY (`created_by`)          REFERENCES `users` (`id`)             ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_admission_fee_updated_by`  FOREIGN KEY (`updated_by`)          REFERENCES `users` (`id`)             ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ==========================================
-- 7. KEUANGAN & PEMBAYARAN
-- ==========================================

-- ---------- installment_plans ----------
DROP TABLE IF EXISTS `installment_plans`;
CREATE TABLE `installment_plans` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `admission_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text NULL,
  `total_terms` tinyint UNSIGNED NOT NULL DEFAULT 1,
  `admin_fee_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_installment_plans_code` (`school_id`, `code`, `deleted_at`) USING BTREE,
  CONSTRAINT `fk_installment_plans_school`    FOREIGN KEY (`school_id`)    REFERENCES `schools` (`id`)    ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_installment_plans_admission` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_invoices (SATU definisi) ----------
DROP TABLE IF EXISTS `applicant_invoices`;
CREATE TABLE `applicant_invoices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `installment_plan_id` bigint UNSIGNED NULL DEFAULT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `invoice_type` varchar(20) NULL DEFAULT NULL,
  `due_date` date NULL DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `penalty_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('unpaid','partially_paid','paid','overdue') NOT NULL DEFAULT 'unpaid',
  `notes` text NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_applicant_invoices_school_number` (`school_id`, `invoice_number`, `deleted_at`) USING BTREE,
  INDEX `idx_applicant_invoices_applicant` (`applicant_id`) USING BTREE,
  INDEX `idx_applicant_invoices_admission` (`admission_id`) USING BTREE,
  INDEX `idx_applicant_invoices_installment_plan` (`installment_plan_id`) USING BTREE,
  INDEX `idx_applicant_invoices_status` (`status`) USING BTREE,
  INDEX `idx_applicant_invoices_due_date` (`due_date`) USING BTREE,
  CONSTRAINT `fk_applicant_invoices_applicant`        FOREIGN KEY (`applicant_id`)        REFERENCES `applicants` (`id`)         ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_invoices_school`           FOREIGN KEY (`school_id`)           REFERENCES `schools` (`id`)            ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_invoices_admission`        FOREIGN KEY (`admission_id`)        REFERENCES `admissions` (`id`)         ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_invoices_installment_plan` FOREIGN KEY (`installment_plan_id`) REFERENCES `installment_plans` (`id`)  ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_invoices_created_by`       FOREIGN KEY (`created_by`)          REFERENCES `users` (`id`)              ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_invoices_updated_by`       FOREIGN KEY (`updated_by`)          REFERENCES `users` (`id`)              ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_invoice_items ----------
DROP TABLE IF EXISTS `applicant_invoice_items`;
CREATE TABLE `applicant_invoice_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_invoice_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `notes` text NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_inv_items_invoice` (`applicant_invoice_id`) USING BTREE,
  CONSTRAINT `fk_inv_items_invoice` FOREIGN KEY (`applicant_invoice_id`) REFERENCES `applicant_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_invoice_plan_terms (master aturan termin per invoice) ----------
DROP TABLE IF EXISTS `applicant_invoice_plan_terms`;
CREATE TABLE `applicant_invoice_plan_terms` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_invoice_id` bigint UNSIGNED NOT NULL,
  `term_number` tinyint UNSIGNED NOT NULL,
  `percentage` decimal(5,2) NULL DEFAULT NULL,
  `fixed_amount` decimal(15,2) NULL DEFAULT NULL,
  `due_offset_days` int NOT NULL DEFAULT 0,
  `notes` varchar(255) NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_invoice_plan_terms` (`applicant_invoice_id`, `term_number`) USING BTREE,
  CONSTRAINT `fk_invoice_plan_terms_invoice` FOREIGN KEY (`applicant_invoice_id`) REFERENCES `applicant_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_payment_terms (termin aktual per invoice) ----------
DROP TABLE IF EXISTS `applicant_payment_terms`;
CREATE TABLE `applicant_payment_terms` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_invoice_id` bigint UNSIGNED NOT NULL,
  `installment_plan_term_id` bigint UNSIGNED NULL DEFAULT NULL,
  `term_number` tinyint UNSIGNED NOT NULL DEFAULT 1,
  `due_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('unpaid','partially_paid','paid','overdue') NOT NULL DEFAULT 'unpaid',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_payment_terms_invoice` (`applicant_invoice_id`) USING BTREE,
  CONSTRAINT `fk_payment_terms_invoice`   FOREIGN KEY (`applicant_invoice_id`)       REFERENCES `applicant_invoices` (`id`)            ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_payment_terms_plan_term` FOREIGN KEY (`installment_plan_term_id`) REFERENCES `applicant_invoice_plan_terms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_payments (SATU definisi, lengkap) ----------
DROP TABLE IF EXISTS `applicant_payments`;
CREATE TABLE `applicant_payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_invoice_id` bigint UNSIGNED NOT NULL,
  `applicant_payment_term_id` bigint UNSIGNED NULL DEFAULT NULL,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `invoice_no` varchar(100) NOT NULL,
  `payment_method` enum('cash','bank_transfer','credit_card','debit_card','virtual_account','ewallet','check','other') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `transaction_id` varchar(255) NULL DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `payment_time` time NULL DEFAULT NULL,
  `bank_name` varchar(100) NULL DEFAULT NULL,
  `account_name` varchar(150) NULL DEFAULT NULL,
  `reference_code` varchar(100) NULL DEFAULT NULL,
  `attachment` varchar(255) NULL DEFAULT NULL,
  `status` enum('pending','paid','failed','cancelled') NOT NULL DEFAULT 'pending',
  `chart_of_account_id` bigint UNSIGNED NULL DEFAULT NULL,
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_applicant_payments_invoice` (`applicant_invoice_id`) USING BTREE,
  INDEX `idx_applicant_payments_term` (`applicant_payment_term_id`) USING BTREE,
  INDEX `idx_applicant_payments_applicant` (`applicant_id`) USING BTREE,
  INDEX `idx_applicant_payments_status` (`status`) USING BTREE,
  INDEX `idx_applicant_payments_coa` (`chart_of_account_id`) USING BTREE,
  INDEX `idx_applicant_payments_verified_by` (`verified_by`) USING BTREE,
  CONSTRAINT `fk_applicant_payments_invoice`   FOREIGN KEY (`applicant_invoice_id`)        REFERENCES `applicant_invoices` (`id`)            ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_payments_term`      FOREIGN KEY (`applicant_payment_term_id`)   REFERENCES `applicant_payment_terms` (`id`)       ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_payments_applicant` FOREIGN KEY (`applicant_id`)               REFERENCES `applicants` (`id`)                   ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_payments_coa`       FOREIGN KEY (`chart_of_account_id`)        REFERENCES `chart_of_accounts` (`id`)             ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_payments_verified`  FOREIGN KEY (`verified_by`)                REFERENCES `users` (`id`)                         ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_payments_created_by` FOREIGN KEY (`created_by`)                 REFERENCES `users` (`id`)                         ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_applicant_payments_updated_by` FOREIGN KEY (`updated_by`)                 REFERENCES `users` (`id`)                         ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ==========================================
-- 8. KONVERSI & AUDIT LOG
-- ==========================================

-- ---------- enrollment_conversions ----------
DROP TABLE IF EXISTS `enrollment_conversions`;
CREATE TABLE `enrollment_conversions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `converted_at` timestamp NULL DEFAULT NULL,
  `converted_by` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_enrollment_conversions_applicant` (`applicant_id`) USING BTREE,
  UNIQUE INDEX `uk_enrollment_conversions_student` (`student_id`) USING BTREE,
  INDEX `idx_enrollment_conversions_school` (`school_id`) USING BTREE,
  INDEX `idx_enrollment_conversions_admission` (`admission_id`) USING BTREE,
  CONSTRAINT `fk_enrollment_conversions_applicant`   FOREIGN KEY (`applicant_id`)  REFERENCES `applicants` (`id`)  ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_enrollment_conversions_student`     FOREIGN KEY (`student_id`)    REFERENCES `students` (`id`)    ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_enrollment_conversions_school`      FOREIGN KEY (`school_id`)     REFERENCES `schools` (`id`)     ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_enrollment_conversions_admission`   FOREIGN KEY (`admission_id`)  REFERENCES `admissions` (`id`)  ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_enrollment_conversions_converted_by` FOREIGN KEY (`converted_by`) REFERENCES `users` (`id`)       ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_enrollment_conversions_created_by`  FOREIGN KEY (`created_by`)    REFERENCES `users` (`id`)       ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_enrollment_conversions_updated_by`  FOREIGN KEY (`updated_by`)    REFERENCES `users` (`id`)       ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- applicant_status_histories ----------
DROP TABLE IF EXISTS `applicant_status_histories`;
CREATE TABLE `applicant_status_histories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `from_status_id` bigint UNSIGNED NULL DEFAULT NULL,
  `to_status_id` bigint UNSIGNED NOT NULL,
  `changed_by` bigint UNSIGNED NULL DEFAULT NULL,
  `changed_by_system` tinyint(1) NOT NULL DEFAULT 0,
  `reason` text NULL,
  `notes` text NULL,
  `metadata` json NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_status_history_applicant` (`applicant_id`) USING BTREE,
  INDEX `idx_status_history_from` (`from_status_id`) USING BTREE,
  INDEX `idx_status_history_to` (`to_status_id`) USING BTREE,
  INDEX `idx_status_history_created` (`created_at`) USING BTREE,
  CONSTRAINT `fk_status_history_applicant` FOREIGN KEY (`applicant_id`)   REFERENCES `applicants` (`id`)          ON DELETE CASCADE  ON UPDATE CASCADE,
  CONSTRAINT `fk_status_history_from`      FOREIGN KEY (`from_status_id`) REFERENCES `applicant_statuses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_status_history_to`        FOREIGN KEY (`to_status_id`)   REFERENCES `applicant_statuses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_status_history_changed_by` FOREIGN KEY (`changed_by`)    REFERENCES `users` (`id`)               ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


-- ---------- activity_logs ----------
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `user_role` varchar(50) NULL DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `module` varchar(100) NULL DEFAULT NULL,
  `record_id` bigint UNSIGNED NULL DEFAULT NULL,
  `record_type` varchar(100) NULL DEFAULT NULL,
  `old_values` json NULL,
  `new_values` json NULL,
  `changed_fields` json NULL,
  `ip_address` varchar(45) NULL DEFAULT NULL,
  `user_agent` text NULL,
  `url` varchar(500) NULL DEFAULT NULL,
  `method` varchar(10) NULL DEFAULT NULL,
  `status` enum('success','failed','warning') NOT NULL DEFAULT 'success',
  `description` text NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_activity_log_user` (`user_id`) USING BTREE,
  INDEX `idx_activity_log_action` (`action`) USING BTREE,
  INDEX `idx_activity_log_module` (`module`) USING BTREE,
  INDEX `idx_activity_log_record` (`record_type`, `record_id`) USING BTREE,
  INDEX `idx_activity_log_created` (`created_at`) USING BTREE,
  CONSTRAINT `fk_activity_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;