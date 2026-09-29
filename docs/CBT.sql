/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80031 (8.0.31)
 Source Host           : localhost:3306
 Source Schema         : laravel

 Target Server Type    : MySQL
 Target Server Version : 80031 (8.0.31)
 File Encoding         : 65001

 Date: 29/09/2026 18:44:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;


-- ----------------------------
-- Table structure for cbt_activity_logs
-- ----------------------------
DROP TABLE IF EXISTS `cbt_activity_logs`;
CREATE TABLE `cbt_activity_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` bigint UNSIGNED NOT NULL,
  `activity_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `severity` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info' COMMENT 'info | warning | violation',
  `activity_data` json NULL,
  `recorded_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cbt_activity_logs_session_index`(`session_id` ASC) USING BTREE,
  INDEX `cbt_activity_logs_type_index`(`activity_type` ASC) USING BTREE,
  INDEX `cbt_activity_logs_recorded_at_index`(`recorded_at` ASC) USING BTREE,
  INDEX `cbt_activity_logs_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `cbt_activity_logs_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `cbt_activity_logs_updated_by_index`(`updated_by` ASC) USING BTREE,
  INDEX `cbt_activity_logs_session_severity_index`(`session_id` ASC, `severity` ASC) USING BTREE,
  CONSTRAINT `cbt_activity_logs_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cbt_activity_logs
-- ----------------------------

-- ----------------------------
-- Table structure for cbt_features
-- ----------------------------
DROP TABLE IF EXISTS `cbt_features`;
CREATE TABLE `cbt_features`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kunci unik dipakai di backend & frontend, contoh: fullscreen_required',
  `category` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'browser | monitoring | exam | policy',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Label di UI admin',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `default_enabled` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Status awal saat admin membuat jadwal baru',
  `default_config` json NULL COMMENT 'Parameter default, contoh: {\"interval_seconds\":60}',
  `requires_device` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'camera | microphone | screen (izin browser yang dibutuhkan)',
  `order_index` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_cbt_feature_code`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cbt_features
-- ----------------------------
INSERT INTO `cbt_features` VALUES (1, 'fullscreen_required', 'browser', 'Wajib Fullscreen', 'Keluar dari fullscreen dicatat sebagai pelanggaran', 1, NULL, NULL, 1, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (2, 'tab_switch_detection', 'browser', 'Deteksi Pindah Tab/Window', 'Mencatat saat peserta meninggalkan halaman ujian', 1, NULL, NULL, 2, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (3, 'block_copy_paste', 'browser', 'Blokir Copy/Paste/Cut', NULL, 1, NULL, NULL, 3, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (4, 'block_context_menu', 'browser', 'Blokir Klik Kanan & Seleksi Teks', NULL, 1, NULL, NULL, 4, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (5, 'block_shortcuts', 'browser', 'Blokir Shortcut (F12, Ctrl+U, Ctrl+P)', 'Hanya mempersulit, tidak bisa dijamin di web murni', 1, NULL, NULL, 5, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (6, 'devtools_detection', 'browser', 'Deteksi DevTools Terbuka', NULL, 0, NULL, NULL, 6, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (7, 'multi_monitor_detection', 'browser', 'Deteksi Multi-Monitor', 'Butuh dukungan Window Management API', 0, NULL, NULL, 7, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (8, 'leave_page_confirm', 'browser', 'Konfirmasi Sebelum Refresh/Tutup Halaman', NULL, 1, NULL, NULL, 8, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (9, 'single_session_login', 'browser', 'Satu Sesi Login per Akun', 'Menolak login ganda dari perangkat lain', 1, NULL, NULL, 9, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (10, 'require_seb', 'browser', 'Wajib Safe Exam Browser', 'Peserta harus memakai SEB', 0, NULL, NULL, 10, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (11, 'watermark', 'browser', 'Watermark Identitas Peserta', 'Nama/NIS tampil di layar untuk mencegah foto layar', 0, NULL, NULL, 11, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (12, 'webcam_monitoring', 'monitoring', 'Pemantauan Webcam (Snapshot Berkala)', NULL, 0, '{\"quality\": 0.6, \"interval_seconds\": 60}', 'camera', 20, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (13, 'face_detection', 'monitoring', 'Deteksi Wajah', 'Wajah hilang atau lebih dari satu', 0, '{\"max_faces\": 1, \"absence_seconds\": 10}', 'camera', 21, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (14, 'face_verification', 'monitoring', 'Verifikasi Wajah di Awal Ujian', 'Dibandingkan dengan foto profil peserta', 0, '{\"threshold\": 0.6}', 'camera', 22, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (15, 'mic_monitoring', 'monitoring', 'Deteksi Suara', NULL, 0, '{\"duration_seconds\": 5}', 'microphone', 23, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (16, 'live_monitoring', 'monitoring', 'Live Monitoring oleh Pengawas', 'Pengawas dapat memantau dan mengirim peringatan', 0, NULL, NULL, 24, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (17, 'randomize_questions', 'exam', 'Acak Urutan Soal', 'Default diambil dari exam_packages.is_randomized', 0, NULL, NULL, 30, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (18, 'shuffle_options', 'exam', 'Acak Urutan Opsi Jawaban', 'Default diambil dari exam_packages.shuffle_options', 0, NULL, NULL, 31, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (19, 'disable_backtrack', 'exam', 'Larang Kembali ke Soal Sebelumnya', NULL, 0, NULL, NULL, 32, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);
INSERT INTO `cbt_features` VALUES (20, 'violation_policy', 'policy', 'Kebijakan Pelanggaran', 'Tindakan saat jumlah pelanggaran mencapai batas', 1, '{\"max_violations\": 3, \"action_on_limit\": \"lock\", \"warn_before_limit\": true}', NULL, 40, 1, '2026-09-29 18:40:30', '2026-09-29 18:40:30', NULL);

-- ----------------------------
-- Table structure for cbt_proctoring
-- ----------------------------
DROP TABLE IF EXISTS `cbt_proctoring`;
CREATE TABLE `cbt_proctoring`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` bigint UNSIGNED NOT NULL,
  `proctor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `start_proctoring` timestamp NULL DEFAULT NULL,
  `end_proctoring` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `flag_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cbt_proctoring_session_index`(`session_id` ASC) USING BTREE,
  INDEX `cbt_proctoring_proctor_index`(`proctor_id` ASC) USING BTREE,
  INDEX `cbt_proctoring_status_index`(`status` ASC) USING BTREE,
  INDEX `cbt_proctoring_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `cbt_proctoring_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `cbt_proctoring_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `cbt_proctoring_proctor_id_foreign` FOREIGN KEY (`proctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cbt_proctoring_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cbt_proctoring
-- ----------------------------

-- ----------------------------
-- Table structure for cbt_proctoring_evidence
-- ----------------------------
DROP TABLE IF EXISTS `cbt_proctoring_evidence`;
CREATE TABLE `cbt_proctoring_evidence`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` bigint UNSIGNED NOT NULL,
  `evidence_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'snapshot | screenshot | audio | video',
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size_bytes` bigint UNSIGNED NULL DEFAULT NULL,
  `captured_at` timestamp NULL DEFAULT NULL,
  `ai_result` json NULL COMMENT 'Hasil deteksi: jumlah wajah, objek, skor kemiripan, dll',
  `is_flagged` tinyint(1) NOT NULL DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL COMMENT 'Batas retensi; file dihapus oleh job terjadwal setelah lewat',
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_cpe_session`(`session_id` ASC, `captured_at` ASC) USING BTREE,
  INDEX `idx_cpe_flagged`(`is_flagged` ASC) USING BTREE,
  INDEX `idx_cpe_expires`(`expires_at` ASC) USING BTREE,
  CONSTRAINT `fk_cpe_session` FOREIGN KEY (`session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cbt_proctoring_evidence
-- ----------------------------

-- ----------------------------
-- Table structure for cbt_schedule_features
-- ----------------------------
DROP TABLE IF EXISTS `cbt_schedule_features`;
CREATE TABLE `cbt_schedule_features`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint UNSIGNED NOT NULL,
  `feature_id` bigint UNSIGNED NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `config` json NULL COMMENT 'Override parameter; null = pakai default_config fitur',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_schedule_feature`(`schedule_id` ASC, `feature_id` ASC) USING BTREE,
  INDEX `idx_csf_feature`(`feature_id` ASC) USING BTREE,
  CONSTRAINT `fk_csf_feature` FOREIGN KEY (`feature_id`) REFERENCES `cbt_features` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_csf_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cbt_schedule_features
-- ----------------------------

-- ----------------------------
-- Table structure for cbt_schedule_proctors
-- ----------------------------
DROP TABLE IF EXISTS `cbt_schedule_proctors`;
CREATE TABLE `cbt_schedule_proctors`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint UNSIGNED NOT NULL,
  `proctor_id` bigint UNSIGNED NOT NULL,
  `classroom_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_schedule_proctor_room`(`schedule_id` ASC, `proctor_id` ASC, `classroom_id` ASC) USING BTREE,
  INDEX `idx_csp_proctor`(`proctor_id` ASC) USING BTREE,
  INDEX `idx_csp_classroom`(`classroom_id` ASC) USING BTREE,
  CONSTRAINT `fk_csp_classroom` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_csp_proctor` FOREIGN KEY (`proctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_csp_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cbt_schedule_proctors
-- ----------------------------

-- ----------------------------
-- Table structure for cbt_session_answers
-- ----------------------------
DROP TABLE IF EXISTS `cbt_session_answers`;
CREATE TABLE `cbt_session_answers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cbt_session_id` bigint UNSIGNED NOT NULL,
  `exam_package_question_id` bigint UNSIGNED NOT NULL,
  `student_answer` json NULL COMMENT 'Jawaban siswa, bentuk mengikuti tipe soal (lihat catatan tipe soal)',
  `is_correct` tinyint(1) NULL DEFAULT NULL COMMENT 'Null jika belum dikoreksi (tipe esai)',
  `score` decimal(5, 2) NULL DEFAULT NULL COMMENT 'Skor per soal, mendukung partial scoring',
  `answered_at` timestamp NULL DEFAULT NULL,
  `time_spent_seconds` int NULL DEFAULT NULL,
  `graded_by` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'Diisi jika dikoreksi manual (esai)',
  `grading_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_session_question`(`cbt_session_id` ASC, `exam_package_question_id` ASC) USING BTREE,
  INDEX `idx_csa_session`(`cbt_session_id` ASC) USING BTREE,
  INDEX `idx_csa_epq`(`exam_package_question_id` ASC) USING BTREE,
  CONSTRAINT `fk_csa_epq` FOREIGN KEY (`exam_package_question_id`) REFERENCES `exam_package_questions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_csa_session` FOREIGN KEY (`cbt_session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cbt_session_answers
-- ----------------------------

-- ----------------------------
-- Table structure for cbt_sessions
-- ----------------------------
DROP TABLE IF EXISTS `cbt_sessions`;
CREATE TABLE `cbt_sessions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `exam_package_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `schedule_id` bigint UNSIGNED NULL DEFAULT NULL,
  `classroom_id` bigint UNSIGNED NULL DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `duration_seconds` int NULL DEFAULT NULL,
  `current_question_index` int NOT NULL DEFAULT 0,
  `answers` json NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `proctoring_config` json NULL COMMENT 'Snapshot fitur aktif + parameter saat sesi dimulai (tidak berubah walau jadwal diedit)',
  `consent_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu peserta menyetujui pemantauan (kamera/mic/log)',
  `violation_count` int NOT NULL DEFAULT 0,
  `last_heartbeat_at` timestamp NULL DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `lock_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `proctor_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_submitted` tinyint(1) NOT NULL DEFAULT 0,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `final_score` decimal(5, 2) NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cbt_sessions_exam_package_index`(`exam_package_id` ASC) USING BTREE,
  INDEX `cbt_sessions_student_index`(`student_id` ASC) USING BTREE,
  INDEX `cbt_sessions_schedule_index`(`schedule_id` ASC) USING BTREE,
  INDEX `cbt_sessions_class_index`(`classroom_id` ASC) USING BTREE,
  INDEX `cbt_sessions_status_index`(`status` ASC) USING BTREE,
  INDEX `cbt_sessions_is_submitted_index`(`is_submitted` ASC) USING BTREE,
  INDEX `cbt_sessions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `cbt_sessions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `cbt_sessions_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `cbt_sessions_class_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cbt_sessions_exam_package_id_foreign` FOREIGN KEY (`exam_package_id`) REFERENCES `exam_packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cbt_sessions_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cbt_sessions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cbt_sessions
-- ----------------------------

-- ----------------------------
-- Table structure for exam_package_questions
-- ----------------------------
DROP TABLE IF EXISTS `exam_package_questions`;
CREATE TABLE `exam_package_questions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `exam_package_id` bigint UNSIGNED NOT NULL,
  `question_bank_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'Referensi asal soal, null-able karena soal sumber bisa dihapus',
  `question_type_id` bigint UNSIGNED NOT NULL,
  `question_text_snapshot` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plain' COMMENT 'plain | latex | html',
  `options_snapshot` json NULL COMMENT 'Salinan beku opsi jawaban saat soal dimasukkan ke paket',
  `correct_answer_snapshot` json NULL COMMENT 'Salinan beku kunci jawaban',
  `stimulus_snapshot` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `points` int NOT NULL DEFAULT 10 COMMENT 'Bisa override poin default dari question_banks',
  `partial_scoring` tinyint(1) NOT NULL DEFAULT 0,
  `question_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_epq_exam_package`(`exam_package_id` ASC) USING BTREE,
  INDEX `idx_epq_question_bank`(`question_bank_id` ASC) USING BTREE,
  CONSTRAINT `fk_epq_exam_package` FOREIGN KEY (`exam_package_id`) REFERENCES `exam_packages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_epq_question_bank` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of exam_package_questions
-- ----------------------------

-- ----------------------------
-- Table structure for question_bank_tag_pivot
-- ----------------------------
DROP TABLE IF EXISTS `question_bank_tag_pivot`;
CREATE TABLE `question_bank_tag_pivot`  (
  `question_bank_id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`question_bank_id`, `tag_id`) USING BTREE,
  INDEX `fk_qbtp_tag`(`tag_id` ASC) USING BTREE,
  CONSTRAINT `fk_qbtp_question_bank` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_qbtp_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of question_bank_tag_pivot
-- ----------------------------

-- ----------------------------
-- Table structure for question_bank_versions
-- ----------------------------
DROP TABLE IF EXISTS `question_bank_versions`;
CREATE TABLE `question_bank_versions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_bank_id` bigint UNSIGNED NOT NULL,
  `version_number` int NOT NULL DEFAULT 1,
  `snapshot_data` json NOT NULL COMMENT 'Salinan penuh question_text, options, correct_answer sebelum diubah',
  `change_note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `changed_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_qbv_question_bank`(`question_bank_id` ASC) USING BTREE,
  CONSTRAINT `fk_qbv_question_bank` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of question_bank_versions
-- ----------------------------

-- ----------------------------
-- Table structure for question_banks
-- ----------------------------
DROP TABLE IF EXISTS `question_banks`;
CREATE TABLE `question_banks`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `question_type_id` bigint UNSIGNED NOT NULL,
  `difficulty_level_id` bigint UNSIGNED NOT NULL,
  `cognitive_level_id` bigint UNSIGNED NOT NULL,
  `stimulus_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK ke question_stimuli, untuk soal berkelompok',
  `question_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plain' COMMENT 'plain | latex | html',
  `correct_answer_text` json NULL COMMENT 'Khusus tipe isian singkat: array alias jawaban benar, contoh [\"jakarta\",\"dki jakarta\"]',
  `explanation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `explanation_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plain' COMMENT 'plain | latex | html',
  `tags` json NULL COMMENT 'Tag bebas untuk pencarian cepat, filter presisi pakai tabel question_bank_tag_pivot',
  `points` int NOT NULL DEFAULT 10 COMMENT 'Poin default, bisa dioverride per paket ujian di exam_package_questions',
  `partial_scoring` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Berlaku utk tipe multi-select/menjodohkan: beri nilai sebagian jika jawaban sebagian benar',
  `scoring_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'auto' COMMENT 'auto | manual (esai wajib manual)',
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_qb_school`(`school_id` ASC) USING BTREE,
  INDEX `idx_qb_subject`(`subject_id` ASC) USING BTREE,
  INDEX `idx_qb_type`(`question_type_id` ASC) USING BTREE,
  INDEX `idx_qb_difficulty`(`difficulty_level_id` ASC) USING BTREE,
  INDEX `idx_qb_cognitive`(`cognitive_level_id` ASC) USING BTREE,
  INDEX `idx_qb_stimulus`(`stimulus_id` ASC) USING BTREE,
  CONSTRAINT `fk_qb_cognitive_level` FOREIGN KEY (`cognitive_level_id`) REFERENCES `cognitive_levels` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_qb_difficulty_level` FOREIGN KEY (`difficulty_level_id`) REFERENCES `difficulty_levels` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_qb_question_type` FOREIGN KEY (`question_type_id`) REFERENCES `question_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_qb_stimulus` FOREIGN KEY (`stimulus_id`) REFERENCES `question_stimuli` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of question_banks
-- ----------------------------

-- ----------------------------
-- Table structure for question_matching_pairs
-- ----------------------------
DROP TABLE IF EXISTS `question_matching_pairs`;
CREATE TABLE `question_matching_pairs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_bank_id` bigint UNSIGNED NOT NULL,
  `left_item` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `right_item` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plain' COMMENT 'plain | latex | html',
  `pair_key` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode unik pasangan dalam 1 soal, contoh \"1\"',
  `order_index` int NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_qmp_question_bank`(`question_bank_id` ASC) USING BTREE,
  CONSTRAINT `fk_qmp_question_bank` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of question_matching_pairs
-- ----------------------------

-- ----------------------------
-- Table structure for question_media
-- ----------------------------
DROP TABLE IF EXISTS `question_media`;
CREATE TABLE `question_media`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_bank_id` bigint UNSIGNED NULL DEFAULT NULL,
  `question_option_id` bigint UNSIGNED NULL DEFAULT NULL,
  `question_stimulus_id` bigint UNSIGNED NULL DEFAULT NULL,
  `media_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'image | audio | video | document | model_3d',
  `file_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Khusus model_3d: glb | gltf | obj | stl',
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Preview gambar statis sebelum model 3D dimuat',
  `viewer_config` json NULL COMMENT 'Konfigurasi viewer 3D: kamera, auto-rotate, background, dll',
  `file_size_bytes` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'Untuk validasi ukuran maksimal upload',
  `caption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `order_index` int NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_qm_question_bank`(`question_bank_id` ASC) USING BTREE,
  INDEX `idx_qm_question_option`(`question_option_id` ASC) USING BTREE,
  INDEX `idx_qm_question_stimulus`(`question_stimulus_id` ASC) USING BTREE,
  CONSTRAINT `fk_qm_question_bank` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_qm_question_option` FOREIGN KEY (`question_option_id`) REFERENCES `question_options` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_qm_question_stimulus` FOREIGN KEY (`question_stimulus_id`) REFERENCES `question_stimuli` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `chk_qm_owner` CHECK ((((`question_bank_id` is not null) + (`question_option_id` is not null)) + (`question_stimulus_id` is not null)) = 1)
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of question_media
-- ----------------------------

-- ----------------------------
-- Table structure for question_media_annotations
-- ----------------------------
DROP TABLE IF EXISTS `question_media_annotations`;
CREATE TABLE `question_media_annotations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_media_id` bigint UNSIGNED NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama bagian, contoh: Ventrikel Kiri',
  `position_x` decimal(10, 6) NOT NULL COMMENT 'Koordinat 3D relatif terhadap model (normalized)',
  `position_y` decimal(10, 6) NOT NULL,
  `position_z` decimal(10, 6) NOT NULL,
  `is_correct_target` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'True jika titik ini adalah jawaban yang benar untuk soal identifikasi bagian',
  `order_index` int NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_qma_media`(`question_media_id` ASC) USING BTREE,
  CONSTRAINT `fk_qma_media` FOREIGN KEY (`question_media_id`) REFERENCES `question_media` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of question_media_annotations
-- ----------------------------

-- ----------------------------
-- Table structure for question_options
-- ----------------------------
DROP TABLE IF EXISTS `question_options`;
CREATE TABLE `question_options`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_bank_id` bigint UNSIGNED NOT NULL,
  `option_label` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'A, B, C, D atau null jika tidak berlabel',
  `option_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plain' COMMENT 'plain | latex | html',
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `order_index` int NOT NULL DEFAULT 0 COMMENT 'Urutan tampil, dan urutan benar untuk tipe ordering',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_qo_question_bank`(`question_bank_id` ASC) USING BTREE,
  CONSTRAINT `fk_qo_question_bank` FOREIGN KEY (`question_bank_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of question_options
-- ----------------------------

-- ----------------------------
-- Table structure for question_stimuli
-- ----------------------------
DROP TABLE IF EXISTS `question_stimuli`;
CREATE TABLE `question_stimuli`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Teks bacaan/wacana (bisa HTML)',
  `content_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'plain' COMMENT 'plain | latex | html',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_stimuli_school`(`school_id` ASC) USING BTREE,
  INDEX `idx_stimuli_subject`(`subject_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of question_stimuli
-- ----------------------------

-- ----------------------------
-- Table structure for question_types
-- ----------------------------
DROP TABLE IF EXISTS `question_types`;
CREATE TABLE `question_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kunci unik dipakai di logic backend & frontend, contoh: single_choice',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama tampilan, contoh: Pilihan Ganda',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `has_options` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'True jika tipe ini pakai tabel question_options (PG, B-S, urutkan)',
  `has_matching_pairs` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'True khusus tipe menjodohkan',
  `is_auto_gradable` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'False untuk esai (wajib dikoreksi manual)',
  `allow_multiple_correct` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'True untuk pilihan ganda kompleks (multi_choice)',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Nama icon untuk UI, contoh: list-check',
  `order_index` int NOT NULL DEFAULT 0 COMMENT 'Urutan tampil di dropdown',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_question_type_code`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of question_types
-- ----------------------------
INSERT INTO `question_types` VALUES (1, 'single_choice', 'Pilihan Ganda', 'Satu jawaban benar dari beberapa opsi', 1, 0, 1, 0, 'list-radio', 1, 1, '2026-09-21 00:17:14', '2026-09-21 00:17:14', NULL);
INSERT INTO `question_types` VALUES (2, 'multi_choice', 'Pilihan Ganda Kompleks', 'Lebih dari satu jawaban benar', 1, 0, 1, 1, 'list-check', 2, 1, '2026-09-21 00:17:14', '2026-09-21 00:17:14', NULL);
INSERT INTO `question_types` VALUES (3, 'true_false', 'Benar / Salah', 'Pernyataan benar atau salah', 1, 0, 1, 0, 'toggle-left', 3, 1, '2026-09-21 00:17:14', '2026-09-21 00:17:14', NULL);
INSERT INTO `question_types` VALUES (4, 'matching', 'Menjodohkan', 'Mencocokkan item kiri dengan item kanan', 0, 1, 1, 0, 'arrows-left-right', 4, 1, '2026-09-21 00:17:14', '2026-09-21 00:17:14', NULL);
INSERT INTO `question_types` VALUES (5, 'ordering', 'Urutkan', 'Menyusun item sesuai urutan yang benar', 1, 0, 1, 0, 'sort-ascending', 5, 1, '2026-09-21 00:17:14', '2026-09-21 00:17:14', NULL);
INSERT INTO `question_types` VALUES (6, 'short_answer', 'Isian Singkat', 'Jawaban singkat berupa kata/frasa', 0, 0, 1, 0, 'text-size', 6, 1, '2026-09-21 00:17:14', '2026-09-21 00:17:14', NULL);
INSERT INTO `question_types` VALUES (7, 'essay', 'Esai', 'Jawaban panjang, dikoreksi manual oleh guru', 0, 0, 0, 0, 'file-text', 7, 1, '2026-09-21 00:17:14', '2026-09-21 00:17:14', NULL);

-- ----------------------------
-- Table structure for tracer_study_questions
-- ----------------------------
DROP TABLE IF EXISTS `tracer_study_questions`;
CREATE TABLE `tracer_study_questions`  (
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
  CONSTRAINT `tracer_study_questions_ibfk_1` FOREIGN KEY (`tracer_study_id`) REFERENCES `tracer_studies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tracer_study_questions
-- ----------------------------

-- ----------------------------
-- Table structure for exam_packages
-- ----------------------------
DROP TABLE IF EXISTS `exam_packages`;
CREATE TABLE `exam_packages`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `duration_minutes` int NOT NULL DEFAULT 60,
  `total_questions` int NOT NULL DEFAULT 0,
  `passing_score` int NOT NULL DEFAULT 70,
  `is_randomized` tinyint(1) NOT NULL DEFAULT 0,
  `shuffle_options` tinyint(1) NOT NULL DEFAULT 0,
  `show_results` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `exam_packages_school_index`(`school_id` ASC) USING BTREE,
  INDEX `exam_packages_subject_index`(`subject_id` ASC) USING BTREE,
  INDEX `exam_packages_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `exam_packages_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `exam_packages_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `exam_packages_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `exam_packages_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exam_packages_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of exam_packages
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
