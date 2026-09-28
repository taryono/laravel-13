admission_announcements
admission_document_verifications
admission_interviews
admission_paths
admission_quota_settings
admission_registration_fees,
admission_selections,
admission_test_results,
admission_test_schedules,
admission_timelines,
admissions,
admissions_categories,
admissions_statuses,
admissions_types,
admissions_phases 
admissions_path_study_programs 
applicants,
application_documents,
applicant_invoices,
applicant_payments, 
test_types,
enrollment_conversions



-- ----------------------------
-- Table structure for admission_announcements
-- ----------------------------
DROP TABLE IF EXISTS `admission_announcements`;
CREATE TABLE `admission_announcements`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke admissions',
  `admission_path_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke admission_paths',
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke organizations',
  `education_level_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke education_levels',
  `study_program_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke study_programs',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Judul pengumuman',
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug untuk URL',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Isi pengumuman (HTML/Markdown)',
  `announcement_type` enum('selection_result','document_verification','test_schedule','interview_schedule','general','re_registration','waitlist') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'selection_result' COMMENT 'Jenis pengumuman',
  `announcement_date` datetime NOT NULL COMMENT 'Tanggal & waktu pengumuman',
  `publish_date` datetime NULL DEFAULT NULL COMMENT 'Tanggal publish',
  `expired_date` datetime NULL DEFAULT NULL COMMENT 'Tanggal kadaluarsa',
  `is_published` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika sudah dipublish',
  `is_public` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 jika bisa diakses publik',
  `requires_login` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika harus login',
  `attachment_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'File lampiran (PDF)',
  `external_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'URL eksternal (jika ada)',
  `show_ranking` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika tampilkan ranking',
  `show_score` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika tampilkan skor',
  `total_participants` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Total peserta',
  `total_passed` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Total lulus',
  `total_failed` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Total tidak lulus',
  `total_waitlist` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Total waiting list',
  `notification_sent` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika notifikasi sudah dikirim',
  `notification_sent_at` timestamp NULL DEFAULT NULL,
  `notification_channels` json NULL COMMENT 'Channel notifikasi (email, wa, sms)',
  `meta_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'SEO title',
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'SEO description',
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'SEO keywords',
  `views_count` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Jumlah views',
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_admission_announcement_slug`(`slug` ASC) USING BTREE,
  INDEX `idx_admission_ann_admission`(`admission_id` ASC) USING BTREE,
  INDEX `idx_admission_ann_path`(`admission_path_id` ASC) USING BTREE,
  INDEX `idx_admission_ann_org`(`organization_id` ASC) USING BTREE,
  INDEX `idx_admission_ann_level`(`education_level_id` ASC) USING BTREE,
  INDEX `idx_admission_ann_program`(`study_program_id` ASC) USING BTREE,
  INDEX `idx_admission_ann_type`(`announcement_type` ASC) USING BTREE,
  INDEX `idx_admission_ann_date`(`announcement_date` ASC) USING BTREE,
  INDEX `idx_admission_ann_published`(`is_published` ASC) USING BTREE,
  INDEX `idx_admission_ann_public`(`is_public` ASC) USING BTREE,
  INDEX `fk_admission_ann_created_by`(`created_by` ASC) USING BTREE,
  INDEX `fk_admission_ann_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `admission_announcements_ibfk_1` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `admission_announcements_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_announcements_ibfk_3` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_announcements_ibfk_4` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_announcements_ibfk_5` FOREIGN KEY (`admission_path_id`) REFERENCES `admission_paths` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_announcements_ibfk_6` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_announcements_ibfk_7` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admission_announcements
-- ----------------------------

-- ----------------------------
-- Table structure for admission_document_verifications
-- ----------------------------
DROP TABLE IF EXISTS `admission_document_verifications`;
CREATE TABLE `admission_document_verifications`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke applicants',
  `application_document_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke application_documents',
  `admission_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke admissions',
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke organizations',
  `document_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Jenis dokumen, misal: Ijazah, Akta, KK',
  `document_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Nomor dokumen',
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Path file dokumen',
  `verification_status` enum('pending','verified','rejected','revision_required','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `verification_method` enum('manual','online','physical','third_party') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke users yang verifikasi',
  `verified_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke users yang reject',
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `revision_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Catatan jika perlu revisi',
  `revision_deadline` date NULL DEFAULT NULL COMMENT 'Batas waktu revisi',
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 jika dokumen wajib',
  `is_valid` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika dokumen valid',
  `valid_until` date NULL DEFAULT NULL COMMENT 'Berlaku sampai (jika ada masa berlaku)',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_admission_verif_applicant`(`applicant_id` ASC) USING BTREE,
  INDEX `idx_admission_verif_document`(`application_document_id` ASC) USING BTREE,
  INDEX `idx_admission_verif_admission`(`admission_id` ASC) USING BTREE,
  INDEX `idx_admission_verif_org`(`organization_id` ASC) USING BTREE,
  INDEX `idx_admission_verif_status`(`verification_status` ASC) USING BTREE,
  INDEX `idx_admission_verif_type`(`document_type` ASC) USING BTREE,
  INDEX `idx_admission_verif_verified_by`(`verified_by` ASC) USING BTREE,
  INDEX `fk_admission_verif_rejected_by`(`rejected_by` ASC) USING BTREE,
  INDEX `fk_admission_verif_created_by`(`created_by` ASC) USING BTREE,
  INDEX `fk_admission_verif_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `admission_document_verifications_ibfk_1` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_document_verifications_ibfk_2` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `admission_document_verifications_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_document_verifications_ibfk_4` FOREIGN KEY (`application_document_id`) REFERENCES `application_documents` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_document_verifications_ibfk_5` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_document_verifications_ibfk_6` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_document_verifications_ibfk_7` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_document_verifications_ibfk_8` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admission_document_verifications
-- ----------------------------

-- ----------------------------
-- Table structure for admission_interviews
-- ----------------------------
DROP TABLE IF EXISTS `admission_interviews`;
CREATE TABLE `admission_interviews`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke admissions',
  `admission_path_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke admission_paths',
  `applicant_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke applicants',
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke organizations',
  `interviewer_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke employees (pewawancara)',
  `secondary_interviewer_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke employees (pewawancara kedua)',
  `room_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke rooms',
  `schedule_date` date NOT NULL COMMENT 'Tanggal wawancara',
  `start_time` time NOT NULL,
  `end_time` time NULL DEFAULT NULL,
  `duration_minutes` int UNSIGNED NULL DEFAULT NULL,
  `interview_type` enum('in_person','online','phone','video_call') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_person',
  `meeting_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'URL meeting jika online',
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` enum('scheduled','ongoing','completed','no_show','cancelled','rescheduled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `attendance_status` enum('present','absent','late','sick','permission') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `score` decimal(5, 2) NULL DEFAULT NULL COMMENT 'Skor wawancara',
  `max_score` decimal(5, 2) NULL DEFAULT NULL,
  `recommendation` enum('strongly_recommended','recommended','neutral','not_recommended','strongly_not_recommended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `result` enum('pending','passed','failed','waitlist') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Catatan pewawancara',
  `internal_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Catatan internal (tidak terlihat applicant)',
  `attachment_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Bukti/dokumen wawancara',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_admission_interview_admission`(`admission_id` ASC) USING BTREE,
  INDEX `idx_admission_interview_path`(`admission_path_id` ASC) USING BTREE,
  INDEX `idx_admission_interview_applicant`(`applicant_id` ASC) USING BTREE,
  INDEX `idx_admission_interview_org`(`organization_id` ASC) USING BTREE,
  INDEX `idx_admission_interview_date`(`schedule_date` ASC) USING BTREE,
  INDEX `idx_admission_interview_status`(`status` ASC) USING BTREE,
  INDEX `idx_admission_interview_interviewer`(`interviewer_id` ASC) USING BTREE,
  INDEX `fk_admission_interview_secondary`(`secondary_interviewer_id` ASC) USING BTREE,
  INDEX `fk_admission_interview_room`(`room_id` ASC) USING BTREE,
  INDEX `fk_admission_interview_created_by`(`created_by` ASC) USING BTREE,
  INDEX `fk_admission_interview_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `admission_interviews_ibfk_1` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `admission_interviews_ibfk_2` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `admission_interviews_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_interviews_ibfk_4` FOREIGN KEY (`interviewer_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_interviews_ibfk_5` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_interviews_ibfk_6` FOREIGN KEY (`admission_path_id`) REFERENCES `admission_paths` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_interviews_ibfk_7` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_interviews_ibfk_8` FOREIGN KEY (`secondary_interviewer_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_interviews_ibfk_9` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admission_interviews
-- ----------------------------

-- ----------------------------
-- Table structure for admission_paths
-- ----------------------------
DROP TABLE IF EXISTS `admission_paths`;
CREATE TABLE `admission_paths`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke admissions',
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke organizations',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode jalur, misal: ZONASI, PRESTASI',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama jalur',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `quota` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Kuota jalur ini',
  `min_score` decimal(5, 2) NULL DEFAULT NULL COMMENT 'Nilai minimum (jika ada)',
  `max_score` decimal(5, 2) NULL DEFAULT NULL COMMENT 'Nilai maksimum (jika ada)',
  `requirements` json NULL COMMENT 'Persyaratan khusus dalam JSON',
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `open_date` date NULL DEFAULT NULL COMMENT 'Tanggal buka pendaftaran',
  `close_date` date NULL DEFAULT NULL COMMENT 'Tanggal tutup pendaftaran',
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_admission_paths_code`(`admission_id` ASC, `code` ASC) USING BTREE,
  INDEX `idx_admission_paths_admission`(`admission_id` ASC) USING BTREE,
  INDEX `idx_admission_paths_org`(`organization_id` ASC) USING BTREE,
  INDEX `idx_admission_paths_active`(`is_active` ASC) USING BTREE,
  INDEX `fk_admission_paths_created_by`(`created_by` ASC) USING BTREE,
  INDEX `fk_admission_paths_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `admission_paths_ibfk_1` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `admission_paths_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_paths_ibfk_3` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_paths_ibfk_4` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admission_paths
-- ----------------------------

-- ----------------------------
-- Table structure for admission_quota_settings
-- ----------------------------
DROP TABLE IF EXISTS `admission_quota_settings`;
CREATE TABLE `admission_quota_settings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke admissions',
  `admission_path_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke admission_paths',
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke organizations',
  `study_program_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke study_programs (jika per prodi)',
  `faculty_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke faculties (jika per fakultas)',
  `education_level_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke education_levels (jika per jenjang)',
  `class_level_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke class_levels',
  `quota` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Jumlah kuota',
  `filled` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Jumlah yang sudah terisi',
  `reserved` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Jumlah yang direservasi',
  `waitlist_quota` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Kuota waiting list',
  `min_quota` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Kuota minimum agar jalur dibuka',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_admission_quota_unique`(`admission_id` ASC, `admission_path_id` ASC, `study_program_id` ASC, `education_level_id` ASC) USING BTREE,
  INDEX `idx_admission_quota_admission`(`admission_id` ASC) USING BTREE,
  INDEX `idx_admission_quota_path`(`admission_path_id` ASC) USING BTREE,
  INDEX `idx_admission_quota_org`(`organization_id` ASC) USING BTREE,
  INDEX `idx_admission_quota_program`(`study_program_id` ASC) USING BTREE,
  INDEX `idx_admission_quota_faculty`(`faculty_id` ASC) USING BTREE,
  INDEX `idx_admission_quota_level`(`education_level_id` ASC) USING BTREE,
  INDEX `fk_admission_quota_class_level`(`class_level_id` ASC) USING BTREE,
  INDEX `fk_admission_quota_created_by`(`created_by` ASC) USING BTREE,
  INDEX `fk_admission_quota_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `admission_quota_settings_ibfk_1` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `admission_quota_settings_ibfk_2` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_quota_settings_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_quota_settings_ibfk_4` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_quota_settings_ibfk_5` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_quota_settings_ibfk_6` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_quota_settings_ibfk_7` FOREIGN KEY (`admission_path_id`) REFERENCES `admission_paths` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `admission_quota_settings_ibfk_8` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_quota_settings_ibfk_9` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admission_quota_settings
-- ----------------------------

-- ----------------------------
-- Table structure for admission_registration_fees
-- ----------------------------
DROP TABLE IF EXISTS `admission_registration_fees`;
CREATE TABLE `admission_registration_fees`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke admissions',
  `admission_path_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke admission_paths',
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke organizations',
  `education_level_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke education_levels',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama biaya, misal: Biaya Pendaftaran Gelombang 1',
  `fee_type` enum('registration','test','interview','document','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'registration' COMMENT 'Jenis biaya',
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00 COMMENT 'Nominal biaya',
  `currency_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke currencies',
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 jika wajib',
  `is_refundable` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika bisa dikembalikan',
  `early_bird_amount` decimal(15, 2) NULL DEFAULT NULL COMMENT 'Harga early bird',
  `early_bird_until` date NULL DEFAULT NULL COMMENT 'Early bird berlaku sampai',
  `effective_from` date NULL DEFAULT NULL,
  `effective_until` date NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_admission_fee_admission`(`admission_id` ASC) USING BTREE,
  INDEX `idx_admission_fee_path`(`admission_path_id` ASC) USING BTREE,
  INDEX `idx_admission_fee_org`(`organization_id` ASC) USING BTREE,
  INDEX `idx_admission_fee_level`(`education_level_id` ASC) USING BTREE,
  INDEX `idx_admission_fee_type`(`fee_type` ASC) USING BTREE,
  INDEX `idx_admission_fee_active`(`is_active` ASC) USING BTREE,
  INDEX `fk_admission_fee_currency`(`currency_id` ASC) USING BTREE,
  INDEX `fk_admission_fee_created_by`(`created_by` ASC) USING BTREE,
  INDEX `fk_admission_fee_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `admission_registration_fees_ibfk_1` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `admission_registration_fees_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_registration_fees_ibfk_3` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_registration_fees_ibfk_4` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_registration_fees_ibfk_5` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_registration_fees_ibfk_6` FOREIGN KEY (`admission_path_id`) REFERENCES `admission_paths` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_registration_fees_ibfk_7` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admission_registration_fees
-- ----------------------------

-- ----------------------------
-- Table structure for admission_selections
-- ----------------------------
DROP TABLE IF EXISTS `admission_selections`;
CREATE TABLE `admission_selections`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `selection_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `score` decimal(5, 2) NULL DEFAULT NULL,
  `result` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `evaluator_id` bigint UNSIGNED NULL DEFAULT NULL,
  `evaluation_date` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admission_selections_admission_index`(`admission_id` ASC) USING BTREE,
  INDEX `admission_selections_applicant_index`(`applicant_id` ASC) USING BTREE,
  INDEX `admission_selections_type_index`(`selection_type` ASC) USING BTREE,
  INDEX `admission_selections_result_index`(`result` ASC) USING BTREE,
  INDEX `admission_selections_evaluator_index`(`evaluator_id` ASC) USING BTREE,
  INDEX `admission_selections_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `admission_selections_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `admission_selections_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `admission_selections_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `admission_selections_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `admission_selections_evaluator_id_foreign` FOREIGN KEY (`evaluator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admission_selections
-- ----------------------------

-- ----------------------------
-- Table structure for admission_test_results
-- ----------------------------
DROP TABLE IF EXISTS `admission_test_results`;
CREATE TABLE `admission_test_results`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_test_schedule_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke admission_test_schedules',
  `applicant_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke applicants',
  `admission_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke admissions',
  `admission_path_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke admission_paths',
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke organizations',
  `test_type_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke test_types',
  `registration_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Nomor registrasi peserta',
  `attendance_status` enum('present','absent','late','sick','permission') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present' COMMENT 'Status kehadiran tes',
  `check_in_time` datetime NULL DEFAULT NULL COMMENT 'Waktu check-in',
  `check_out_time` datetime NULL DEFAULT NULL COMMENT 'Waktu check-out',
  `raw_score` decimal(8, 2) NULL DEFAULT NULL COMMENT 'Skor mentah',
  `final_score` decimal(8, 2) NULL DEFAULT NULL COMMENT 'Skor akhir setelah konversi',
  `max_score` decimal(8, 2) NULL DEFAULT NULL COMMENT 'Skor maksimum',
  `percentage` decimal(5, 2) NULL DEFAULT NULL COMMENT 'Persentase',
  `grade` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Grade (A, B, C, dll)',
  `rank` int UNSIGNED NULL DEFAULT NULL COMMENT 'Peringkat',
  `passing_status` enum('pending','passed','failed','waitlist') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'Status kelulusan tes',
  `cbt_session_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke cbt_sessions (jika CBT)',
  `cbt_session_answer_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke cbt_session_answers',
  `detail_scores` json NULL COMMENT 'Detail skor per sub-tes',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `graded_by` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke users yang menilai',
  `graded_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_admission_test_result`(`admission_test_schedule_id` ASC, `applicant_id` ASC) USING BTREE,
  INDEX `idx_admission_result_schedule`(`admission_test_schedule_id` ASC) USING BTREE,
  INDEX `idx_admission_result_applicant`(`applicant_id` ASC) USING BTREE,
  INDEX `idx_admission_result_admission`(`admission_id` ASC) USING BTREE,
  INDEX `idx_admission_result_path`(`admission_path_id` ASC) USING BTREE,
  INDEX `idx_admission_result_org`(`organization_id` ASC) USING BTREE,
  INDEX `idx_admission_result_status`(`passing_status` ASC) USING BTREE,
  INDEX `idx_admission_result_rank`(`rank` ASC) USING BTREE,
  INDEX `fk_admission_result_test_type`(`test_type_id` ASC) USING BTREE,
  INDEX `fk_admission_result_cbt_session`(`cbt_session_id` ASC) USING BTREE,
  INDEX `fk_admission_result_cbt_answer`(`cbt_session_answer_id` ASC) USING BTREE,
  INDEX `fk_admission_result_graded_by`(`graded_by` ASC) USING BTREE,
  INDEX `fk_admission_result_created_by`(`created_by` ASC) USING BTREE,
  INDEX `fk_admission_result_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `admission_test_results_ibfk_1` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `admission_test_results_ibfk_10` FOREIGN KEY (`test_type_id`) REFERENCES `test_types` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_test_results_ibfk_11` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_test_results_ibfk_2` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `admission_test_results_ibfk_3` FOREIGN KEY (`cbt_session_answer_id`) REFERENCES `cbt_session_answers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_test_results_ibfk_4` FOREIGN KEY (`cbt_session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_test_results_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_test_results_ibfk_6` FOREIGN KEY (`graded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_test_results_ibfk_7` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_test_results_ibfk_8` FOREIGN KEY (`admission_path_id`) REFERENCES `admission_paths` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `admission_test_results_ibfk_9` FOREIGN KEY (`admission_test_schedule_id`) REFERENCES `admission_test_schedules` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admission_test_results
-- ----------------------------
 
-- ----------------------------
-- Table structure for admission_timelines
-- ----------------------------
DROP TABLE IF EXISTS `admission_timelines`;
CREATE TABLE `admission_timelines`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `phase_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `order_number` int NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admission_timelines_admission_index`(`admission_id` ASC) USING BTREE,
  INDEX `admission_timelines_order_index`(`order_number` ASC) USING BTREE,
  INDEX `admission_timelines_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `admission_timelines_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `admission_timelines_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `admission_timelines_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admission_timelines
-- ----------------------------

-- ----------------------------
-- Table structure for admissions
-- ----------------------------
DROP TABLE IF EXISTS `admissions`;
CREATE TABLE `admissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `class_level_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `quota` int NOT NULL DEFAULT 0,
  `registration_fee` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `min_age` int NULL DEFAULT NULL,
  `max_age` int NULL DEFAULT NULL,
  `requirements` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `admissions_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `admissions_school_index`(`school_id` ASC) USING BTREE,
  INDEX `admissions_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `admissions_class_level_index`(`class_level_id` ASC) USING BTREE,
  INDEX `admissions_dates_index`(`start_date` ASC, `end_date` ASC) USING BTREE,
  INDEX `admissions_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `admissions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `admissions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `admissions_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `admissions_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `admissions_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `admissions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admissions
-- ----------------------------

-- ----------------------------
-- Table structure for applicant_invoices
-- ----------------------------
DROP TABLE IF EXISTS `applicant_invoices`;
CREATE TABLE `applicant_invoices`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `due_date` date NULL DEFAULT NULL,
  `total_amount` decimal(15, 2) NOT NULL,
  `discount_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `penalty_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(15, 2) NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `applicant_invoices_school_number_unique`(`school_id` ASC, `invoice_number` ASC) USING BTREE,
  INDEX `applicant_invoices_applicant_index`(`applicant_id` ASC) USING BTREE,
  INDEX `applicant_invoices_school_index`(`school_id` ASC) USING BTREE,
  INDEX `applicant_invoices_admission_index`(`admission_id` ASC) USING BTREE,
  INDEX `applicant_invoices_status_index`(`status` ASC) USING BTREE,
  INDEX `applicant_invoices_due_date_index`(`due_date` ASC) USING BTREE,
  INDEX `applicant_invoices_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `applicant_invoices_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `applicant_invoices_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `applicant_invoices_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applicant_invoices_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applicant_invoices_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of applicant_invoices
-- ----------------------------

-- ----------------------------
-- Table structure for applicant_payments
-- ----------------------------
DROP TABLE IF EXISTS `applicant_payments`;
CREATE TABLE `applicant_payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `payment_method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payment_date` date NULL DEFAULT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `reference_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payment_proof_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verification_date` timestamp NULL DEFAULT NULL,
  `refund_date` timestamp NULL DEFAULT NULL,
  `refund_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `applicant_payments_invoice_index`(`invoice_id` ASC) USING BTREE,
  INDEX `applicant_payments_applicant_index`(`applicant_id` ASC) USING BTREE,
  INDEX `applicant_payments_status_index`(`status` ASC) USING BTREE,
  INDEX `applicant_payments_verified_by_index`(`verified_by` ASC) USING BTREE,
  INDEX `applicant_payments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `applicant_payments_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `applicant_payments_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `applicant_payments_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applicant_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `applicant_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applicant_payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of applicant_payments
-- ---------------------------

-- ----------------------------
-- Table structure for applicants
-- ----------------------------
DROP TABLE IF EXISTS `applicants`;
CREATE TABLE `applicants`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birth_place` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birth_date` date NULL DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `parent_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `parent_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `parent_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `previous_school` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `registration_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'REGISTERED',
  `test_score` decimal(5, 2) NULL DEFAULT NULL,
  `interview_score` decimal(5, 2) NULL DEFAULT NULL,
  `total_score` decimal(5, 2) NULL DEFAULT NULL,
  `ranking` int NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `registered_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `applicants_school_registration_number_unique`(`school_id` ASC, `registration_number` ASC) USING BTREE,
  UNIQUE INDEX `applicants_school_nik_unique`(`school_id` ASC, `nik` ASC) USING BTREE,
  INDEX `applicants_school_index`(`school_id` ASC) USING BTREE,
  INDEX `applicants_admission_index`(`admission_id` ASC) USING BTREE,
  INDEX `applicants_status_index`(`status` ASC) USING BTREE,
  INDEX `applicants_registration_number_index`(`registration_number` ASC) USING BTREE,
  INDEX `applicants_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `applicants_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `applicants_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `applicants_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `applicants_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of applicants
-- ----------------------------

-- ----------------------------
-- Table structure for application_documents
-- ----------------------------
DROP TABLE IF EXISTS `application_documents`;
CREATE TABLE `application_documents`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `document_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `document_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `file_size` bigint NULL DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verification_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `application_documents_applicant_index`(`applicant_id` ASC) USING BTREE,
  INDEX `application_documents_type_index`(`document_type` ASC) USING BTREE,
  INDEX `application_documents_verified_index`(`verified` ASC) USING BTREE,
  INDEX `application_documents_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `application_documents_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `application_documents_updated_by_index`(`updated_by` ASC) USING BTREE,
  INDEX `application_documents_verified_by_foreign`(`verified_by` ASC) USING BTREE,
  CONSTRAINT `application_documents_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `application_documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of application_documents
-- ----------------------------


-- ----------------------------
-- Table structure for admission_test_schedules
-- ----------------------------
DROP TABLE IF EXISTS `admission_test_schedules`;
CREATE TABLE `admission_test_schedules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `admission_id` bigint UNSIGNED NOT NULL,
  `admission_path_id` bigint UNSIGNED NULL DEFAULT NULL,      -- ✅ BARU
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,        -- ✅ BARU
  `admission_selection_id` bigint UNSIGNED NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text NULL,
  `test_type` enum('tulis','wawancara','praktek','kesehatan','psikotes','lainnya') NOT NULL DEFAULT 'tulis',
  `test_type_id` bigint UNSIGNED NULL DEFAULT NULL,           -- ✅ BARU
  `test_method` enum('offline','online','hybrid') NOT NULL DEFAULT 'offline',
  `test_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_minutes` int UNSIGNED NULL DEFAULT NULL,
  `registration_start` datetime NULL DEFAULT NULL,
  `registration_end` datetime NULL DEFAULT NULL,
  `room_id` bigint UNSIGNED NULL DEFAULT NULL,
  `building_id` bigint UNSIGNED NULL DEFAULT NULL,
  `location` varchar(255) NULL DEFAULT NULL,                  -- ✅ RENAME dari location_detail
  `online_meeting_url` varchar(500) NULL DEFAULT NULL,
  `online_meeting_id` varchar(100) NULL DEFAULT NULL,
  `online_password` varchar(100) NULL DEFAULT NULL,
  `cbt_session_id` bigint UNSIGNED NULL DEFAULT NULL,
  `exam_package_id` bigint UNSIGNED NULL DEFAULT NULL,
  `quota` int UNSIGNED NOT NULL DEFAULT 0,
  `registered` int UNSIGNED NOT NULL DEFAULT 0,               -- ✅ RENAME dari booked_count
  `attended` int UNSIGNED NOT NULL DEFAULT 0,                 -- ✅ BARU
  `min_participants` int UNSIGNED NULL DEFAULT 0,
  `supervisor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `coordinator_id` bigint UNSIGNED NULL DEFAULT NULL,
  `proctor_id` bigint UNSIGNED NULL DEFAULT NULL,             -- ✅ BARU
  `proctor_ids` json NULL,
  `allow_self_register` tinyint(1) NOT NULL DEFAULT 0,
  `allow_reschedule` tinyint(1) NOT NULL DEFAULT 0,
  `late_tolerance_min` int UNSIGNED NULL DEFAULT 15,
  `status` enum('draft','published','ongoing','completed','cancelled') NOT NULL DEFAULT 'draft',
  `is_online` tinyint(1) NOT NULL DEFAULT 0,                  -- ✅ BARU
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `passing_score` decimal(5,2) NULL DEFAULT NULL,
  `max_score` decimal(5,2) NULL DEFAULT NULL,
  `score_input_deadline` datetime NULL DEFAULT NULL,
  `result_announcement` datetime NULL DEFAULT NULL,
  `notes` text NULL,
  `instructions` text NULL,                                   -- ✅ BARU
  `attachments` json NULL,
  `settings` json NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  `deleted_at` timestamp NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code`) USING BTREE,
  INDEX `idx_admission`(`admission_id`) USING BTREE,
  INDEX `idx_admission_path`(`admission_path_id`) USING BTREE,      -- ✅ BARU
  INDEX `idx_organization`(`organization_id`) USING BTREE,          -- ✅ BARU
  INDEX `idx_selection`(`admission_selection_id`) USING BTREE,
  INDEX `idx_academic_year`(`academic_year_id`) USING BTREE,
  INDEX `idx_test_date`(`test_date`, `start_time`) USING BTREE,
  INDEX `idx_status`(`status`, `is_active`) USING BTREE,
  INDEX `idx_test_type`(`test_type`) USING BTREE,
  INDEX `idx_test_type_id`(`test_type_id`) USING BTREE,             -- ✅ BARU
  INDEX `idx_cbt_session`(`cbt_session_id`) USING BTREE,
  INDEX `idx_attended`(`attended`) USING BTREE,                     -- ✅ BARU
  INDEX `idx_proctor`(`proctor_id`) USING BTREE,                    -- ✅ BARU
  INDEX `idx_is_online`(`is_online`) USING BTREE,                   -- ✅ BARU
  INDEX `fk_test_schedules_room`(`room_id`) USING BTREE,
  INDEX `fk_test_schedules_exam_package`(`exam_package_id`) USING BTREE,
  
  -- FOREIGN KEYS
  CONSTRAINT `test_schedules_path_id_foreign` 
      FOREIGN KEY (`admission_path_id`) REFERENCES `admission_paths` (`id`) 
      ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `test_schedules_organization_id_foreign` 
      FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) 
      ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `test_schedules_test_type_id_foreign` 
      FOREIGN KEY (`test_type_id`) REFERENCES `test_types` (`id`) 
      ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `test_schedules_proctor_id_foreign` 
      FOREIGN KEY (`proctor_id`) REFERENCES `employees` (`id`) 
      ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 
  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of test_schedules
-- ----------------------------

-- ----------------------------
-- Table structure for test_types
-- ----------------------------
DROP TABLE IF EXISTS `test_types`;
CREATE TABLE `test_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name_en` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `slug` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `category` enum('akademik','non_akademik','kesehatan','administrasi','lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'akademik',
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `badge_class` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `default_duration_minutes` int UNSIGNED NULL DEFAULT NULL,
  `default_passing_score` decimal(5, 2) NULL DEFAULT NULL,
  `default_max_score` decimal(5, 2) NULL DEFAULT NULL,
  `default_weight` decimal(5, 2) NULL DEFAULT 100.00,
  `default_method` enum('offline','online','hybrid') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'offline',
  `requires_room` tinyint(1) NOT NULL DEFAULT 1,
  `requires_supervisor` tinyint(1) NOT NULL DEFAULT 1,
  `requires_cbt` tinyint(1) NOT NULL DEFAULT 0,
  `scoring_components` json NULL,
  `settings` json NULL,
  `order` int UNSIGNED NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code`) USING BTREE,
  UNIQUE INDEX `slug`(`slug`) USING BTREE,
  INDEX `idx_code`(`code`) USING BTREE,
  INDEX `idx_category`(`category`, `is_active`) USING BTREE,
  INDEX `idx_parent`(`parent_id`) USING BTREE,
  INDEX `idx_order`(`order`) USING BTREE,
  INDEX `idx_school`(`school_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of test_types
-- ----------------------------

-- ----------------------------
-- Table structure for enrollment_conversions
-- ----------------------------
DROP TABLE IF EXISTS `enrollment_conversions`;
CREATE TABLE `enrollment_conversions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `admission_id` bigint UNSIGNED NOT NULL,
  `converted_at` timestamp NULL DEFAULT NULL,
  `converted_by` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NOT NULL,
  `updated_at` timestamp NULL DEFAULT NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `enrollment_conversions_applicant_unique`(`applicant_id` ASC) USING BTREE,
  UNIQUE INDEX `enrollment_conversions_student_unique`(`student_id` ASC) USING BTREE,
  INDEX `enrollment_conversions_school_index`(`school_id` ASC) USING BTREE,
  INDEX `enrollment_conversions_admission_index`(`admission_id` ASC) USING BTREE,
  INDEX `enrollment_conversions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `enrollment_conversions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `enrollment_conversions_updated_by_index`(`updated_by` ASC) USING BTREE,
  INDEX `enrollment_conversions_converted_by_foreign`(`converted_by` ASC) USING BTREE,
  CONSTRAINT `enrollment_conversions_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `enrollment_conversions_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `enrollment_conversions_converted_by_foreign` FOREIGN KEY (`converted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `enrollment_conversions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `enrollment_conversions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of enrollment_conversions
-- ----------------------------

DROP TABLE IF EXISTS `applicant_categories`;
CREATE TABLE `applicant_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'NULL = global, terisi = spesifik sekolah',
  `admission_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke admissions (jika kategori per gelombang)',
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'Self-reference untuk hierarki kategori',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode unik kategori, mis: REGULER, KIP, ABK',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama kategori',
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug untuk URL',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Deskripsi kategori',
  `category_type` enum('general','afirmasi','prestasi','khusus','pindahan','lainnya')
      CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general' COMMENT 'Tipe kategori',
  `priority_level` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Prioritas seleksi (kecil = tinggi)',
  `weight` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'Bobot tambahan untuk skor (jika ada)',
  `requires_document` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika wajib upload dokumen',
  `required_document_types` json NULL COMMENT 'Daftar jenis dokumen wajib (array)',
  `required_fields` json NULL COMMENT 'Field wajib yang harus diisi pendaftar',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `badge_class` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika kategori default untuk pendaftar baru',
  `is_system` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika kategori bawaan sistem (tidak bisa dihapus)',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_applicant_category_code`(`school_id` ASC, `code` ASC) USING BTREE,
  UNIQUE INDEX `uk_applicant_category_slug`(`school_id` ASC, `slug` ASC) USING BTREE,
  INDEX `idx_applicant_category_school`(`school_id` ASC) USING BTREE,
  INDEX `idx_applicant_category_admission`(`admission_id` ASC) USING BTREE,
  INDEX `idx_applicant_category_parent`(`parent_id` ASC) USING BTREE,
  INDEX `idx_applicant_category_type`(`category_type` ASC) USING BTREE,
  INDEX `idx_applicant_category_active`(`is_active` ASC) USING BTREE,
  INDEX `fk_applicant_category_created_by`(`created_by` ASC) USING BTREE,
  INDEX `fk_applicant_category_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `applicant_categories_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `applicant_categories_ibfk_2` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `applicant_categories_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `applicant_categories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `applicant_categories_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `applicant_categories_ibfk_5` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `applicant_statuses`;
CREATE TABLE `applicant_statuses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'NULL = global, terisi = spesifik sekolah',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode unik status, mis: DRAFT, SUBMITTED, VERIFIED',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama status',
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug untuk URL',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Deskripsi status',
  `status_group` enum('registration','verification','selection','announcement','re_registration','enrollment','final')
      CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'registration' COMMENT 'Kelompok fase status',
  `status_type` enum('draft','pending','in_progress','success','failed','cancelled','expired','waitlist')
      CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'Tipe status generik',
  `stage_order` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Urutan tahap (0 = paling awal)',
  `is_final` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika status final (tidak bisa diubah)',
  `is_success` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika status menunjukkan keberhasilan',
  `is_failure` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika status menunjukkan kegagalan',
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika status default saat pendaftar dibuat',
  `is_system` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika status bawaan sistem',
  `allow_edit` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 jika pendaftar masih bisa edit data',
  `allow_upload_document` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 jika pendaftar masih bisa upload dokumen',
  `allow_payment` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika pendaftar bisa bayar',
  `next_status_ids` json NULL COMMENT 'Daftar status_id yang mungkin berikutnya',
  `notify_applicant` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika perubahan ke status ini trigger notifikasi',
  `notification_template` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Template notifikasi',
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Warna badge',
  `badge_class` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Class CSS badge',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_applicant_status_code`(`school_id` ASC, `code` ASC) USING BTREE,
  UNIQUE INDEX `uk_applicant_status_slug`(`school_id` ASC, `slug` ASC) USING BTREE,
  INDEX `idx_applicant_status_school`(`school_id` ASC) USING BTREE,
  INDEX `idx_applicant_status_group`(`status_group` ASC) USING BTREE,
  INDEX `idx_applicant_status_type`(`status_type` ASC) USING BTREE,
  INDEX `idx_applicant_status_order`(`stage_order` ASC) USING BTREE,
  INDEX `idx_applicant_status_active`(`is_active` ASC) USING BTREE,
  INDEX `idx_applicant_status_default`(`is_default` ASC) USING BTREE,
  INDEX `fk_applicant_status_created_by`(`created_by` ASC) USING BTREE,
  INDEX `fk_applicant_status_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `applicant_statuses_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `applicant_statuses_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `applicant_statuses_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `applicant_status_histories`;
CREATE TABLE `applicant_status_histories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke applicants',
  `from_status_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'Status sebelumnya',
  `to_status_id` bigint UNSIGNED NOT NULL COMMENT 'Status baru',
  `changed_by` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'User yang ubah',
  `changed_by_system` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika diubah sistem (cron/auto)',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Alasan perubahan',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `metadata` json NULL COMMENT 'Data tambahan (IP, user agent, dll)',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_status_history_applicant`(`applicant_id` ASC) USING BTREE,
  INDEX `idx_status_history_from`(`from_status_id` ASC) USING BTREE,
  INDEX `idx_status_history_to`(`to_status_id` ASC) USING BTREE,
  INDEX `idx_status_history_created`(`created_at` ASC) USING BTREE,
  INDEX `fk_status_history_changed_by`(`changed_by` ASC) USING BTREE,
  CONSTRAINT `applicant_status_histories_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `applicant_status_histories_ibfk_2` FOREIGN KEY (`from_status_id`) REFERENCES `applicant_statuses` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `applicant_status_histories_ibfk_3` FOREIGN KEY (`to_status_id`) REFERENCES `applicant_statuses` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `applicant_status_histories_ibfk_4` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `applicant_domiciles`;
CREATE TABLE `applicant_domiciles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke applicants',
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rt` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `rw` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `postal_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `province_id` bigint UNSIGNED NULL DEFAULT NULL,
  `regency_id` bigint UNSIGNED NULL DEFAULT NULL,
  `district_id` bigint UNSIGNED NULL DEFAULT NULL,
  `village_id` bigint UNSIGNED NULL DEFAULT NULL,
  `latitude` decimal(10,8) NULL DEFAULT NULL COMMENT 'Koordinat latitude',
  `longitude` decimal(11,8) NULL DEFAULT NULL COMMENT 'Koordinat longitude',
  `distance_to_school_m` decimal(10,2) NULL DEFAULT NULL COMMENT 'Jarak ke sekolah (meter, hasil hitung geodesic)',
  `distance_calculated_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika sudah diverifikasi tim survei',
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verification_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `document_kk_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Upload KK',
  `document_house_photo_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Foto rumah',
  `is_primary` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 jika alamat utama',
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_applicant_domicile_applicant`(`applicant_id` ASC) USING BTREE,
  INDEX `idx_applicant_domicile_village`(`village_id` ASC) USING BTREE,
  INDEX `idx_applicant_domicile_distance`(`distance_to_school_m` ASC) USING BTREE,
  INDEX `idx_applicant_domicile_verified`(`is_verified` ASC) USING BTREE,
  INDEX `fk_applicant_domicile_verified_by`(`verified_by` ASC) USING BTREE,
  CONSTRAINT `applicant_domiciles_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `applicant_domiciles_ibfk_2` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `applicant_parents`;
CREATE TABLE `applicant_parents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke applicants',
  `relation_type` enum('father','mother','guardian') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'father',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birth_place` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birth_date` date NULL DEFAULT NULL,
  `education_level` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `job` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `income_range` enum('below_1m','1m_2m','2m_5m','5m_10m','above_10m','unknown') 
      CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_alive` tinyint(1) NOT NULL DEFAULT 1,
  `is_primary_contact` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 jika kontak utama',
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_applicant_parent_applicant`(`applicant_id` ASC) USING BTREE,
  INDEX `idx_applicant_parent_relation`(`relation_type` ASC) USING BTREE,
  CONSTRAINT `applicant_parents_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `applicant_achievements`;
CREATE TABLE `applicant_achievements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke applicants',
  `achievement_type` enum('academic','non_academic','sport','art','science','other') 
      CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'academic',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama prestasi',
  `level` enum('school','district','regency','province','national','international') 
      CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'school',
  `rank` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Juara 1/2/3, dsb',
  `organizer` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `year` year NULL DEFAULT NULL,
  `certificate_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `certificate_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `score_point` decimal(5,2) NULL DEFAULT NULL COMMENT 'Poin prestasi',
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_by` bigint UNSIGNED NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_applicant_achievement_applicant`(`applicant_id` ASC) USING BTREE,
  INDEX `idx_applicant_achievement_type`(`achievement_type` ASC) USING BTREE,
  INDEX `idx_applicant_achievement_level`(`level` ASC) USING BTREE,
  CONSTRAINT `applicant_achievements_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `applicant_support_programs`;
CREATE TABLE `applicant_support_programs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke applicants',
  `program_type` enum('kip','pip','ketm','abk','yatim','lainnya') 
      CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kip',
  `card_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `program_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `issuer` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Lembaga penerbit',
  `valid_from` date NULL DEFAULT NULL,
  `valid_until` date NULL DEFAULT NULL,
  `card_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `supporting_document_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `validation_status` enum('pending','valid','invalid','need_verification') 
      CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `validated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `validation_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_applicant_support_applicant`(`applicant_id` ASC) USING BTREE,
  INDEX `idx_applicant_support_type`(`program_type` ASC) USING BTREE,
  INDEX `idx_applicant_support_status`(`validation_status` ASC) USING BTREE,
  CONSTRAINT `applicant_support_programs_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `applicant_support_programs_ibfk_2` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `user_role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'create/update/delete/login/export/dll',
  `module` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Nama modul/tabel',
  `record_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'ID record yang diubah',
  `record_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Model/class',
  `old_values` json NULL COMMENT 'Nilai sebelum',
  `new_values` json NULL COMMENT 'Nilai sesudah',
  `changed_fields` json NULL COMMENT 'Field yang berubah',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` enum('success','failed','warning') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'success',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_activity_log_user`(`user_id` ASC) USING BTREE,
  INDEX `idx_activity_log_action`(`action` ASC) USING BTREE,
  INDEX `idx_activity_log_module`(`module` ASC) USING BTREE,
  INDEX `idx_activity_log_record`(`record_type` ASC, `record_id` ASC) USING BTREE,
  INDEX `idx_activity_log_created`(`created_at` ASC) USING BTREE,
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `applicant_registrations`;
CREATE TABLE `applicant_registrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint UNSIGNED NOT NULL COMMENT 'FK ke applicants',
  `admission_id` bigint UNSIGNED NOT NULL,
  `registration_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `registration_date` datetime NULL DEFAULT NULL,
  `registration_method` enum('online','offline','on_site') 
      CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'online',
  `registration_status` enum('pending','confirmed','cancelled','expired') 
      CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `officer_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'Petugas yang memproses',
  `location` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `proof_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Bukti daftar ulang',
  `uniform_size` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_applicant_registration_applicant`(`applicant_id` ASC) USING BTREE,
  INDEX `idx_applicant_registration_admission`(`admission_id` ASC) USING BTREE,
  INDEX `idx_applicant_registration_status`(`registration_status` ASC) USING BTREE,
  CONSTRAINT `applicant_registrations_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `applicant_registrations_ibfk_2` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;