/*
 * Table Group: cbt
 * Total Tables: 25
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `assignments_quizzes`
-- ----------------------------
DROP TABLE IF EXISTS `assignments_quizzes`;
CREATE TABLE `assignments_quizzes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `lesson_id` bigint unsigned DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `max_score` int NOT NULL DEFAULT '100',
  `time_limit_minutes` int DEFAULT NULL,
  `passing_score` int NOT NULL DEFAULT '70',
  `due_date` timestamp NULL DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `question_data` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `assignments_quizzes_course_index` (`course_id`) USING BTREE,
  KEY `assignments_quizzes_lesson_index` (`lesson_id`) USING BTREE,
  KEY `assignments_quizzes_type_index` (`type`) USING BTREE,
  KEY `assignments_quizzes_due_date_index` (`due_date`) USING BTREE,
  KEY `assignments_quizzes_is_published_index` (`is_published`) USING BTREE,
  KEY `assignments_quizzes_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `assignments_quizzes_created_by_index` (`created_by`) USING BTREE,
  KEY `assignments_quizzes_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `assignments_quizzes_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assignments_quizzes_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `cbt_activity_logs`
-- ----------------------------
DROP TABLE IF EXISTS `cbt_activity_logs`;
CREATE TABLE `cbt_activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` bigint unsigned NOT NULL,
  `activity_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `severity` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info' COMMENT 'info | warning | violation',
  `activity_data` json DEFAULT NULL,
  `recorded_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `cbt_activity_logs_session_index` (`session_id`) USING BTREE,
  KEY `cbt_activity_logs_type_index` (`activity_type`) USING BTREE,
  KEY `cbt_activity_logs_recorded_at_index` (`recorded_at`) USING BTREE,
  KEY `cbt_activity_logs_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `cbt_activity_logs_created_by_index` (`created_by`) USING BTREE,
  KEY `cbt_activity_logs_updated_by_index` (`updated_by`) USING BTREE,
  KEY `cbt_activity_logs_session_severity_index` (`session_id`,`severity`) USING BTREE,
  CONSTRAINT `cbt_activity_logs_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `cbt_features`
-- ----------------------------
DROP TABLE IF EXISTS `cbt_features`;
CREATE TABLE `cbt_features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kunci unik dipakai di backend & frontend, contoh: fullscreen_required',
  `category` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'browser | monitoring | exam | policy',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Label di UI admin',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_enabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Status awal saat admin membuat jadwal baru',
  `default_config` json DEFAULT NULL COMMENT 'Parameter default, contoh: {"interval_seconds":60}',
  `requires_device` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'camera | microphone | screen (izin browser yang dibutuhkan)',
  `order_index` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uniq_cbt_feature_code` (`code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `cbt_proctoring`
-- ----------------------------
DROP TABLE IF EXISTS `cbt_proctoring`;
CREATE TABLE `cbt_proctoring` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` bigint unsigned NOT NULL,
  `proctor_id` bigint unsigned DEFAULT NULL,
  `start_proctoring` timestamp NULL DEFAULT NULL,
  `end_proctoring` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `cbt_proctoring_session_index` (`session_id`) USING BTREE,
  KEY `cbt_proctoring_proctor_index` (`proctor_id`) USING BTREE,
  KEY `cbt_proctoring_status_index` (`status`) USING BTREE,
  KEY `cbt_proctoring_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `cbt_proctoring_created_by_index` (`created_by`) USING BTREE,
  KEY `cbt_proctoring_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `cbt_proctoring_proctor_id_foreign` FOREIGN KEY (`proctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cbt_proctoring_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `cbt_proctoring_evidence`
-- ----------------------------
DROP TABLE IF EXISTS `cbt_proctoring_evidence`;
CREATE TABLE `cbt_proctoring_evidence` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` bigint unsigned NOT NULL,
  `evidence_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'snapshot | screenshot | audio | video',
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size_bytes` bigint unsigned DEFAULT NULL,
  `captured_at` timestamp NULL DEFAULT NULL,
  `ai_result` json DEFAULT NULL COMMENT 'Hasil deteksi: jumlah wajah, objek, skor kemiripan, dll',
  `is_flagged` tinyint(1) NOT NULL DEFAULT '0',
  `expires_at` timestamp NULL DEFAULT NULL COMMENT 'Batas retensi; file dihapus oleh job terjadwal setelah lewat',
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_cpe_session` (`session_id`,`captured_at`) USING BTREE,
  KEY `idx_cpe_flagged` (`is_flagged`) USING BTREE,
  KEY `idx_cpe_expires` (`expires_at`) USING BTREE,
  CONSTRAINT `fk_cpe_session` FOREIGN KEY (`session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `cbt_schedule_features`
-- ----------------------------
DROP TABLE IF EXISTS `cbt_schedule_features`;
CREATE TABLE `cbt_schedule_features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint unsigned NOT NULL,
  `feature_id` bigint unsigned NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `config` json DEFAULT NULL COMMENT 'Override parameter; null = pakai default_config fitur',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uniq_schedule_feature` (`schedule_id`,`feature_id`) USING BTREE,
  KEY `idx_csf_feature` (`feature_id`) USING BTREE,
  CONSTRAINT `fk_csf_feature` FOREIGN KEY (`feature_id`) REFERENCES `cbt_features` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_csf_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `cbt_schedule_proctors`
-- ----------------------------
DROP TABLE IF EXISTS `cbt_schedule_proctors`;
CREATE TABLE `cbt_schedule_proctors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint unsigned NOT NULL,
  `proctor_id` bigint unsigned NOT NULL,
  `classroom_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uniq_schedule_proctor_room` (`schedule_id`,`proctor_id`,`classroom_id`) USING BTREE,
  KEY `idx_csp_proctor` (`proctor_id`) USING BTREE,
  KEY `idx_csp_classroom` (`classroom_id`) USING BTREE,
  CONSTRAINT `fk_csp_classroom` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_csp_proctor` FOREIGN KEY (`proctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_csp_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `cbt_session_answers`
-- ----------------------------
DROP TABLE IF EXISTS `cbt_session_answers`;
CREATE TABLE `cbt_session_answers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cbt_session_id` bigint unsigned NOT NULL,
  `exam_package_question_id` bigint unsigned NOT NULL,
  `student_answer` json DEFAULT NULL COMMENT 'Jawaban siswa, bentuk mengikuti tipe soal (lihat catatan tipe soal)',
  `is_correct` tinyint(1) DEFAULT NULL COMMENT 'Null jika belum dikoreksi (tipe esai)',
  `score` decimal(5,2) DEFAULT NULL COMMENT 'Skor per soal, mendukung partial scoring',
  `answered_at` timestamp NULL DEFAULT NULL,
  `time_spent_seconds` int DEFAULT NULL,
  `graded_by` bigint unsigned DEFAULT NULL COMMENT 'Diisi jika dikoreksi manual (esai)',
  `grading_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uniq_session_question` (`cbt_session_id`,`exam_package_question_id`) USING BTREE,
  KEY `idx_csa_session` (`cbt_session_id`) USING BTREE,
  KEY `idx_csa_epq` (`exam_package_question_id`) USING BTREE,
  CONSTRAINT `fk_csa_epq` FOREIGN KEY (`exam_package_question_id`) REFERENCES `exam_package_questions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_csa_session` FOREIGN KEY (`cbt_session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `cbt_sessions`
-- ----------------------------
DROP TABLE IF EXISTS `cbt_sessions`;
CREATE TABLE `cbt_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `exam_package_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `schedule_id` bigint unsigned DEFAULT NULL,
  `classroom_id` bigint unsigned DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `duration_seconds` int DEFAULT NULL,
  `current_question_index` int NOT NULL DEFAULT '0',
  `answers` json DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `proctoring_config` json DEFAULT NULL COMMENT 'Snapshot fitur aktif + parameter saat sesi dimulai (tidak berubah walau jadwal diedit)',
  `consent_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu peserta menyetujui pemantauan (kamera/mic/log)',
  `violation_count` int NOT NULL DEFAULT '0',
  `last_heartbeat_at` timestamp NULL DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `lock_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proctor_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_submitted` tinyint(1) NOT NULL DEFAULT '0',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `final_score` decimal(5,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `cbt_sessions_exam_package_index` (`exam_package_id`) USING BTREE,
  KEY `cbt_sessions_student_index` (`student_id`) USING BTREE,
  KEY `cbt_sessions_schedule_index` (`schedule_id`) USING BTREE,
  KEY `cbt_sessions_class_index` (`classroom_id`) USING BTREE,
  KEY `cbt_sessions_status_index` (`status`) USING BTREE,
  KEY `cbt_sessions_is_submitted_index` (`is_submitted`) USING BTREE,
  KEY `cbt_sessions_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `cbt_sessions_created_by_index` (`created_by`) USING BTREE,
  KEY `cbt_sessions_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `cbt_sessions_class_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cbt_sessions_exam_package_id_foreign` FOREIGN KEY (`exam_package_id`) REFERENCES `exam_packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cbt_sessions_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cbt_sessions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `cognitive_levels`
-- ----------------------------
DROP TABLE IF EXISTS `cognitive_levels`;
CREATE TABLE `cognitive_levels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'C1 s.d. C6',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mengingat, Memahami, dst',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level_order` int NOT NULL DEFAULT '0' COMMENT 'Urutan dari C1 (rendah) ke C6 (tinggi)',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uniq_cognitive_code` (`code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `difficulty_levels`
-- ----------------------------
DROP TABLE IF EXISTS `difficulty_levels`;
CREATE TABLE `difficulty_levels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'easy | medium | hard',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mudah, Sedang, Sulit',
  `level_order` int NOT NULL DEFAULT '0' COMMENT 'Urutan dari termudah (1) ke tersulit',
  `score_weight` decimal(3,2) NOT NULL DEFAULT '1.00' COMMENT 'Opsional: pengali bobot nilai, contoh sulit = 1.50',
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kode warna badge UI, contoh: success/warning/danger atau hex',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uniq_difficulty_code` (`code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `exam_package_questions`
-- ----------------------------
DROP TABLE IF EXISTS `exam_package_questions`;
CREATE TABLE `exam_package_questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `exam_package_id` bigint unsigned NOT NULL,
  `question_bank_id` bigint unsigned DEFAULT NULL COMMENT 'Referensi asal soal, null-able karena soal sumber bisa dihapus',
  `question_type_id` bigint unsigned NOT NULL,
  `question_text_snapshot` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plain' COMMENT 'plain | latex | html',
  `options_snapshot` json DEFAULT NULL COMMENT 'Salinan beku opsi jawaban saat soal dimasukkan ke paket',
  `correct_answer_snapshot` json DEFAULT NULL COMMENT 'Salinan beku kunci jawaban',
  `stimulus_snapshot` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `points` int NOT NULL DEFAULT '10' COMMENT 'Bisa override poin default dari question_banks',
  `partial_scoring` tinyint(1) NOT NULL DEFAULT '0',
  `question_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_epq_exam_package` (`exam_package_id`) USING BTREE,
  KEY `idx_epq_question_bank` (`question_bank_id`) USING BTREE,
  CONSTRAINT `fk_epq_exam_package` FOREIGN KEY (`exam_package_id`) REFERENCES `exam_packages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_epq_question_bank` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `exam_packages`
-- ----------------------------
DROP TABLE IF EXISTS `exam_packages`;
CREATE TABLE `exam_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `duration_minutes` int NOT NULL DEFAULT '60',
  `total_questions` int NOT NULL DEFAULT '0',
  `passing_score` int NOT NULL DEFAULT '70',
  `is_randomized` tinyint(1) NOT NULL DEFAULT '0',
  `shuffle_options` tinyint(1) NOT NULL DEFAULT '0',
  `show_results` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `exam_packages_school_index` (`school_id`) USING BTREE,
  KEY `exam_packages_subject_index` (`subject_id`) USING BTREE,
  KEY `exam_packages_is_active_index` (`is_active`) USING BTREE,
  KEY `exam_packages_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `exam_packages_created_by_index` (`created_by`) USING BTREE,
  KEY `exam_packages_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `exam_packages_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exam_packages_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `question_bank_tag_pivot`
-- ----------------------------
DROP TABLE IF EXISTS `question_bank_tag_pivot`;
CREATE TABLE `question_bank_tag_pivot` (
  `question_bank_id` bigint unsigned NOT NULL,
  `tag_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`question_bank_id`,`tag_id`) USING BTREE,
  KEY `fk_qbtp_tag` (`tag_id`) USING BTREE,
  CONSTRAINT `fk_qbtp_question_bank` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_qbtp_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `question_bank_versions`
-- ----------------------------
DROP TABLE IF EXISTS `question_bank_versions`;
CREATE TABLE `question_bank_versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question_bank_id` bigint unsigned NOT NULL,
  `version_number` int NOT NULL DEFAULT '1',
  `snapshot_data` json NOT NULL COMMENT 'Salinan penuh question_text, options, correct_answer sebelum diubah',
  `change_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changed_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_qbv_question_bank` (`question_bank_id`) USING BTREE,
  CONSTRAINT `fk_qbv_question_bank` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `question_banks`
-- ----------------------------
DROP TABLE IF EXISTS `question_banks`;
CREATE TABLE `question_banks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `question_type_id` bigint unsigned NOT NULL,
  `difficulty_level_id` bigint unsigned NOT NULL,
  `cognitive_level_id` bigint unsigned NOT NULL,
  `stimulus_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke question_stimuli, untuk soal berkelompok',
  `question_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plain' COMMENT 'plain | latex | html',
  `correct_answer_text` json DEFAULT NULL COMMENT 'Khusus tipe isian singkat: array alias jawaban benar, contoh ["jakarta","dki jakarta"]',
  `explanation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `explanation_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plain' COMMENT 'plain | latex | html',
  `tags` json DEFAULT NULL COMMENT 'Tag bebas untuk pencarian cepat, filter presisi pakai tabel question_bank_tag_pivot',
  `points` int NOT NULL DEFAULT '10' COMMENT 'Poin default, bisa dioverride per paket ujian di exam_package_questions',
  `partial_scoring` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Berlaku utk tipe multi-select/menjodohkan: beri nilai sebagian jika jawaban sebagian benar',
  `scoring_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'auto' COMMENT 'auto | manual (esai wajib manual)',
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_qb_school` (`school_id`) USING BTREE,
  KEY `idx_qb_subject` (`subject_id`) USING BTREE,
  KEY `idx_qb_type` (`question_type_id`) USING BTREE,
  KEY `idx_qb_difficulty` (`difficulty_level_id`) USING BTREE,
  KEY `idx_qb_cognitive` (`cognitive_level_id`) USING BTREE,
  KEY `idx_qb_stimulus` (`stimulus_id`) USING BTREE,
  CONSTRAINT `fk_qb_cognitive_level` FOREIGN KEY (`cognitive_level_id`) REFERENCES `cognitive_levels` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_qb_difficulty_level` FOREIGN KEY (`difficulty_level_id`) REFERENCES `difficulty_levels` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_qb_question_type` FOREIGN KEY (`question_type_id`) REFERENCES `question_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_qb_stimulus` FOREIGN KEY (`stimulus_id`) REFERENCES `question_stimuli` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `question_matching_pairs`
-- ----------------------------
DROP TABLE IF EXISTS `question_matching_pairs`;
CREATE TABLE `question_matching_pairs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question_bank_id` bigint unsigned NOT NULL,
  `left_item` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `right_item` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plain' COMMENT 'plain | latex | html',
  `pair_key` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode unik pasangan dalam 1 soal, contoh "1"',
  `order_index` int NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_qmp_question_bank` (`question_bank_id`) USING BTREE,
  CONSTRAINT `fk_qmp_question_bank` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `question_media`
-- ----------------------------
DROP TABLE IF EXISTS `question_media`;
CREATE TABLE `question_media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question_bank_id` bigint unsigned DEFAULT NULL,
  `question_option_id` bigint unsigned DEFAULT NULL,
  `question_stimulus_id` bigint unsigned DEFAULT NULL,
  `media_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'image | audio | video | document | model_3d',
  `file_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Khusus model_3d: glb | gltf | obj | stl',
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Preview gambar statis sebelum model 3D dimuat',
  `viewer_config` json DEFAULT NULL COMMENT 'Konfigurasi viewer 3D: kamera, auto-rotate, background, dll',
  `file_size_bytes` bigint unsigned DEFAULT NULL COMMENT 'Untuk validasi ukuran maksimal upload',
  `caption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_index` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_qm_question_bank` (`question_bank_id`) USING BTREE,
  KEY `idx_qm_question_option` (`question_option_id`) USING BTREE,
  KEY `idx_qm_question_stimulus` (`question_stimulus_id`) USING BTREE,
  CONSTRAINT `fk_qm_question_bank` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_qm_question_option` FOREIGN KEY (`question_option_id`) REFERENCES `question_options` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_qm_question_stimulus` FOREIGN KEY (`question_stimulus_id`) REFERENCES `question_stimuli` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `chk_qm_owner` CHECK (((((`question_bank_id` is not null) + (`question_option_id` is not null)) + (`question_stimulus_id` is not null)) = 1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `question_media_annotations`
-- ----------------------------
DROP TABLE IF EXISTS `question_media_annotations`;
CREATE TABLE `question_media_annotations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question_media_id` bigint unsigned NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama bagian, contoh: Ventrikel Kiri',
  `position_x` decimal(10,6) NOT NULL COMMENT 'Koordinat 3D relatif terhadap model (normalized)',
  `position_y` decimal(10,6) NOT NULL,
  `position_z` decimal(10,6) NOT NULL,
  `is_correct_target` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True jika titik ini adalah jawaban yang benar untuk soal identifikasi bagian',
  `order_index` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_qma_media` (`question_media_id`) USING BTREE,
  CONSTRAINT `fk_qma_media` FOREIGN KEY (`question_media_id`) REFERENCES `question_media` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `question_options`
-- ----------------------------
DROP TABLE IF EXISTS `question_options`;
CREATE TABLE `question_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question_bank_id` bigint unsigned NOT NULL,
  `option_label` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'A, B, C, D atau null jika tidak berlabel',
  `option_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plain' COMMENT 'plain | latex | html',
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `order_index` int NOT NULL DEFAULT '0' COMMENT 'Urutan tampil, dan urutan benar untuk tipe ordering',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_qo_question_bank` (`question_bank_id`) USING BTREE,
  CONSTRAINT `fk_qo_question_bank` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `question_stimuli`
-- ----------------------------
DROP TABLE IF EXISTS `question_stimuli`;
CREATE TABLE `question_stimuli` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Teks bacaan/wacana (bisa HTML)',
  `content_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plain' COMMENT 'plain | latex | html',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_stimuli_school` (`school_id`) USING BTREE,
  KEY `idx_stimuli_subject` (`subject_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `question_types`
-- ----------------------------
DROP TABLE IF EXISTS `question_types`;
CREATE TABLE `question_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kunci unik dipakai di logic backend & frontend, contoh: single_choice',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama tampilan, contoh: Pilihan Ganda',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_options` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True jika tipe ini pakai tabel question_options (PG, B-S, urutkan)',
  `has_matching_pairs` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True khusus tipe menjodohkan',
  `is_auto_gradable` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'False untuk esai (wajib dikoreksi manual)',
  `allow_multiple_correct` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True untuk pilihan ganda kompleks (multi_choice)',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama icon untuk UI, contoh: list-check',
  `order_index` int NOT NULL DEFAULT '0' COMMENT 'Urutan tampil di dropdown',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uniq_question_type_code` (`code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `quiz_attempts`
-- ----------------------------
DROP TABLE IF EXISTS `quiz_attempts`;
CREATE TABLE `quiz_attempts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `assignment_quiz_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `answers` json NOT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `time_spent_seconds` int DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `quiz_attempts_assignment_index` (`assignment_quiz_id`) USING BTREE,
  KEY `quiz_attempts_student_index` (`student_id`) USING BTREE,
  KEY `quiz_attempts_is_completed_index` (`is_completed`) USING BTREE,
  KEY `quiz_attempts_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `quiz_attempts_created_by_index` (`created_by`) USING BTREE,
  KEY `quiz_attempts_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `quiz_attempts_assignment_quiz_id_foreign` FOREIGN KEY (`assignment_quiz_id`) REFERENCES `assignments_quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `quiz_attempts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `test_schedules`
-- ----------------------------
DROP TABLE IF EXISTS `test_schedules`;
CREATE TABLE `test_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` bigint unsigned NOT NULL,
  `admission_selection_id` bigint unsigned DEFAULT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `test_type` enum('tulis','wawancara','praktek','kesehatan','psikotes','lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'tulis',
  `test_method` enum('offline','online','hybrid') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'offline',
  `test_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_minutes` int unsigned DEFAULT NULL,
  `registration_start` datetime DEFAULT NULL,
  `registration_end` datetime DEFAULT NULL,
  `room_id` bigint unsigned DEFAULT NULL,
  `building_id` bigint unsigned DEFAULT NULL,
  `location_detail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `online_meeting_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `online_meeting_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `online_password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `cbt_session_id` bigint unsigned DEFAULT NULL,
  `exam_package_id` bigint unsigned DEFAULT NULL,
  `quota` int unsigned NOT NULL DEFAULT '0',
  `booked_count` int unsigned NOT NULL DEFAULT '0',
  `min_participants` int unsigned DEFAULT '0',
  `supervisor_id` bigint unsigned DEFAULT NULL,
  `coordinator_id` bigint unsigned DEFAULT NULL,
  `proctor_ids` json DEFAULT NULL,
  `allow_self_register` tinyint(1) NOT NULL DEFAULT '0',
  `allow_reschedule` tinyint(1) NOT NULL DEFAULT '0',
  `late_tolerance_min` int unsigned DEFAULT '15',
  `status` enum('draft','published','ongoing','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'draft',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `passing_score` decimal(5,2) DEFAULT NULL,
  `max_score` decimal(5,2) DEFAULT NULL,
  `score_input_deadline` datetime DEFAULT NULL,
  `result_announcement` datetime DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `attachments` json DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `code` (`code`) USING BTREE,
  KEY `idx_admission` (`admission_id`) USING BTREE,
  KEY `idx_selection` (`admission_selection_id`) USING BTREE,
  KEY `idx_academic_year` (`academic_year_id`) USING BTREE,
  KEY `idx_test_date` (`test_date`,`start_time`) USING BTREE,
  KEY `idx_status` (`status`,`is_active`) USING BTREE,
  KEY `idx_test_type` (`test_type`) USING BTREE,
  KEY `idx_cbt_session` (`cbt_session_id`) USING BTREE,
  KEY `fk_test_schedules_room` (`room_id`) USING BTREE,
  KEY `fk_test_schedules_exam_package` (`exam_package_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `test_types`
-- ----------------------------
DROP TABLE IF EXISTS `test_types`;
CREATE TABLE `test_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name_en` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `slug` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `category` enum('akademik','non_akademik','kesehatan','administrasi','lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'akademik',
  `parent_id` bigint unsigned DEFAULT NULL,
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `badge_class` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `default_duration_minutes` int unsigned DEFAULT NULL,
  `default_passing_score` decimal(5,2) DEFAULT NULL,
  `default_max_score` decimal(5,2) DEFAULT NULL,
  `default_weight` decimal(5,2) DEFAULT '100.00',
  `default_method` enum('offline','online','hybrid') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'offline',
  `requires_room` tinyint(1) NOT NULL DEFAULT '1',
  `requires_supervisor` tinyint(1) NOT NULL DEFAULT '1',
  `requires_cbt` tinyint(1) NOT NULL DEFAULT '0',
  `scoring_components` json DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `order` int unsigned DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `school_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `code` (`code`) USING BTREE,
  UNIQUE KEY `slug` (`slug`) USING BTREE,
  KEY `idx_code` (`code`) USING BTREE,
  KEY `idx_category` (`category`,`is_active`) USING BTREE,
  KEY `idx_parent` (`parent_id`) USING BTREE,
  KEY `idx_order` (`order`) USING BTREE,
  KEY `idx_school` (`school_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
