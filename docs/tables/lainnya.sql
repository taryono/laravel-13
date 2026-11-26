/*
 * Table Group: lainnya
 * Total Tables: 63
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `activity_logs`
-- ----------------------------
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `user_role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'create/update/delete/login/export/dll',
  `module` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama modul/tabel',
  `record_id` bigint unsigned DEFAULT NULL COMMENT 'ID record yang diubah',
  `record_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Model/class',
  `old_values` json DEFAULT NULL COMMENT 'Nilai sebelum',
  `new_values` json DEFAULT NULL COMMENT 'Nilai sesudah',
  `changed_fields` json DEFAULT NULL COMMENT 'Field yang berubah',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('success','failed','warning') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'success',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_activity_log_user` (`user_id`) USING BTREE,
  KEY `idx_activity_log_action` (`action`) USING BTREE,
  KEY `idx_activity_log_module` (`module`) USING BTREE,
  KEY `idx_activity_log_record` (`record_type`,`record_id`) USING BTREE,
  KEY `idx_activity_log_created` (`created_at`) USING BTREE,
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `api_rate_limits`
-- ----------------------------
DROP TABLE IF EXISTS `api_rate_limits`;
CREATE TABLE `api_rate_limits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `api_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `endpoint` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requests` int NOT NULL DEFAULT '0',
  `limit` int NOT NULL DEFAULT '1000',
  `reset_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_api_rate_limits_key_endpoint` (`api_key`,`endpoint`) USING BTREE,
  KEY `idx_api_rate_limits_org` (`organization_id`) USING BTREE,
  CONSTRAINT `api_rate_limits_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `approval_histories`
-- ----------------------------
DROP TABLE IF EXISTS `approval_histories`;
CREATE TABLE `approval_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `approval_request_id` bigint unsigned NOT NULL,
  `workflow_step_id` bigint unsigned NOT NULL,
  `approver_id` bigint unsigned NOT NULL,
  `action` enum('approved','rejected','revised','delegated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `signature_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `appr_hist_req_index` (`approval_request_id`) USING BTREE,
  KEY `appr_hist_step_index` (`workflow_step_id`) USING BTREE,
  KEY `appr_hist_user_index` (`approver_id`) USING BTREE,
  CONSTRAINT `appr_hist_approver_fk` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `appr_hist_req_fk` FOREIGN KEY (`approval_request_id`) REFERENCES `approval_requests` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `appr_hist_step_fk` FOREIGN KEY (`workflow_step_id`) REFERENCES `workflow_steps` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `approval_requests`
-- ----------------------------
DROP TABLE IF EXISTS `approval_requests`;
CREATE TABLE `approval_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `workflow_definition_id` bigint unsigned NOT NULL,
  `current_step_id` bigint unsigned DEFAULT NULL,
  `requestable_type` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requestable_id` bigint unsigned NOT NULL,
  `document_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `requester_id` bigint unsigned NOT NULL,
  `status` enum('draft','pending','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `appr_req_org_index` (`organization_id`) USING BTREE,
  KEY `appr_req_wf_index` (`workflow_definition_id`) USING BTREE,
  KEY `appr_req_step_index` (`current_step_id`) USING BTREE,
  KEY `appr_req_morph_index` (`requestable_type`,`requestable_id`) USING BTREE,
  KEY `appr_req_requester_index` (`requester_id`) USING BTREE,
  KEY `appr_req_status_index` (`status`) USING BTREE,
  CONSTRAINT `appr_req_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `appr_req_requester_fk` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `appr_req_step_fk` FOREIGN KEY (`current_step_id`) REFERENCES `workflow_steps` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `appr_req_wf_fk` FOREIGN KEY (`workflow_definition_id`) REFERENCES `workflow_definitions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `audit_trails`
-- ----------------------------
DROP TABLE IF EXISTS `audit_trails`;
CREATE TABLE `audit_trails` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `table_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` bigint unsigned NOT NULL,
  `action` enum('INSERT','UPDATE','DELETE','RESTORE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_audit_trails_org` (`organization_id`) USING BTREE,
  KEY `idx_audit_trails_table_record` (`table_name`,`record_id`) USING BTREE,
  KEY `idx_audit_trails_created` (`created_at`) USING BTREE,
  CONSTRAINT `audit_trails_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `blood_types`
-- ----------------------------
DROP TABLE IF EXISTS `blood_types`;
CREATE TABLE `blood_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rhesus` enum('+','-','') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `blood_types_name_unique` (`name`) USING BTREE,
  KEY `blood_types_deleted_at_index` (`deleted_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `cache`
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `cache_locks`
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `dapodik_mappings`
-- ----------------------------
DROP TABLE IF EXISTS `dapodik_mappings`;
CREATE TABLE `dapodik_mappings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `local_table` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `local_id` bigint unsigned NOT NULL,
  `dapodik_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dapodik_table` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hash_value` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_sync_at` timestamp NULL DEFAULT NULL,
  `sync_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `retry_count` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `dapodik_mappings_local_unique` (`local_table`,`local_id`) USING BTREE,
  UNIQUE KEY `dapodik_mappings_dapodik_unique` (`dapodik_table`,`dapodik_id`) USING BTREE,
  KEY `dapodik_mappings_school_index` (`school_id`) USING BTREE,
  KEY `dapodik_mappings_local_index` (`local_table`,`local_id`) USING BTREE,
  KEY `dapodik_mappings_dapodik_index` (`dapodik_table`,`dapodik_id`) USING BTREE,
  KEY `dapodik_mappings_status_index` (`sync_status`) USING BTREE,
  KEY `dapodik_mappings_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `dapodik_mappings_created_by_index` (`created_by`) USING BTREE,
  KEY `dapodik_mappings_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `dapodik_mappings_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `dapodik_sync_logs`
-- ----------------------------
DROP TABLE IF EXISTS `dapodik_sync_logs`;
CREATE TABLE `dapodik_sync_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `sync_date` timestamp NULL DEFAULT NULL,
  `sync_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_records` int NOT NULL DEFAULT '0',
  `total_synced` int NOT NULL DEFAULT '0',
  `total_failed` int NOT NULL DEFAULT '0',
  `total_conflicts` int NOT NULL DEFAULT '0',
  `sync_duration_seconds` int DEFAULT NULL,
  `error_log` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `details` json DEFAULT NULL,
  `triggered_by` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `dapodik_sync_logs_school_index` (`school_id`) USING BTREE,
  KEY `dapodik_sync_logs_status_index` (`status`) USING BTREE,
  KEY `dapodik_sync_logs_sync_type_index` (`sync_type`) USING BTREE,
  KEY `dapodik_sync_logs_sync_date_index` (`sync_date`) USING BTREE,
  KEY `dapodik_sync_logs_triggered_by_index` (`triggered_by`) USING BTREE,
  KEY `dapodik_sync_logs_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `dapodik_sync_logs_created_by_index` (`created_by`) USING BTREE,
  KEY `dapodik_sync_logs_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `dapodik_sync_logs_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dapodik_sync_logs_triggered_by_foreign` FOREIGN KEY (`triggered_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `dapodik_sync_schedules`
-- ----------------------------
DROP TABLE IF EXISTS `dapodik_sync_schedules`;
CREATE TABLE `dapodik_sync_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `schedule_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sync_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frequency` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_run` timestamp NULL DEFAULT NULL,
  `next_run` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `dapodik_sync_schedules_school_index` (`school_id`) USING BTREE,
  KEY `dapodik_sync_schedules_next_run_index` (`next_run`) USING BTREE,
  KEY `dapodik_sync_schedules_is_active_index` (`is_active`) USING BTREE,
  KEY `dapodik_sync_schedules_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `dapodik_sync_schedules_created_by_index` (`created_by`) USING BTREE,
  KEY `dapodik_sync_schedules_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `dapodik_sync_schedules_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `demo_requests`
-- ----------------------------
DROP TABLE IF EXISTS `demo_requests`;
CREATE TABLE `demo_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `person_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `package` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `demo_datetime` datetime NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `demo_requests_status_index` (`status`),
  KEY `demo_requests_email_index` (`email`),
  KEY `demo_requests_phone_index` (`phone`),
  KEY `demo_requests_demo_datetime_index` (`demo_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `dictionaries`
-- ----------------------------
DROP TABLE IF EXISTS `dictionaries`;
CREATE TABLE `dictionaries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `en_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `id_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metadata` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `dictionaries_org_key_group_unique` (`organization_id`,`key`,`group`) USING BTREE,
  KEY `dictionaries_is_active_index` (`is_active`) USING BTREE,
  KEY `dictionaries_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `dictionaries_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `digital_signatures`
-- ----------------------------
DROP TABLE IF EXISTS `digital_signatures`;
CREATE TABLE `digital_signatures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint unsigned NOT NULL COMMENT 'FK ke documents',
  `document_version_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke document_versions',
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `signer_user_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke users penandatangan',
  `signer_employee_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke employees',
  `signer_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama penandatangan',
  `signer_email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signer_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signer_position` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Jabatan',
  `signer_nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'NIP/NIK',
  `signature_type` enum('electronic','digital','stamp','biometric','wet','initials','parap') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'electronic' COMMENT 'Tipe tanda tangan',
  `signature_method` enum('draw','type','upload','certificate','qr_code','otp','biometric') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draw' COMMENT 'Metode tanda tangan',
  `signature_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Data tanda tangan (base64 untuk draw)',
  `signature_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path file tanda tangan',
  `certificate_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path sertifikat digital',
  `certificate_serial` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Serial sertifikat',
  `certificate_issuer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Penerbit sertifikat',
  `certificate_valid_from` timestamp NULL DEFAULT NULL,
  `certificate_valid_until` timestamp NULL DEFAULT NULL,
  `signature_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SHA-256 hash tanda tangan',
  `document_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SHA-256 hash dokumen saat ditandatangani',
  `page_number` int unsigned DEFAULT NULL COMMENT 'Halaman tanda tangan',
  `position_x` decimal(10,4) DEFAULT NULL COMMENT 'Posisi X',
  `position_y` decimal(10,4) DEFAULT NULL COMMENT 'Posisi Y',
  `position_width` decimal(10,4) DEFAULT NULL COMMENT 'Lebar',
  `position_height` decimal(10,4) DEFAULT NULL COMMENT 'Tinggi',
  `signed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu tanda tangan',
  `status` enum('pending','signed','rejected','expired','revoked','invalid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'Status tanda tangan',
  `verification_status` enum('unverified','verified','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unverified' COMMENT 'Status verifikasi',
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang verifikasi',
  `verification_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revocation_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked_at` timestamp NULL DEFAULT NULL,
  `revoked_by` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang revoke',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `device_identifier` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `otp_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kode OTP (jika metode OTP)',
  `otp_verified_at` timestamp NULL DEFAULT NULL,
  `reference_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tipe referensi',
  `reference_id` bigint unsigned DEFAULT NULL COMMENT 'ID referensi',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL COMMENT 'Metadata tambahan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_digital_signatures_document` (`document_id`) USING BTREE,
  KEY `idx_digital_signatures_version` (`document_version_id`) USING BTREE,
  KEY `idx_digital_signatures_org` (`organization_id`) USING BTREE,
  KEY `idx_digital_signatures_signer_user` (`signer_user_id`) USING BTREE,
  KEY `idx_digital_signatures_signer_employee` (`signer_employee_id`) USING BTREE,
  KEY `idx_digital_signatures_type` (`signature_type`) USING BTREE,
  KEY `idx_digital_signatures_method` (`signature_method`) USING BTREE,
  KEY `idx_digital_signatures_status` (`status`) USING BTREE,
  KEY `idx_digital_signatures_verification` (`verification_status`) USING BTREE,
  KEY `idx_digital_signatures_signed` (`signed_at`) USING BTREE,
  KEY `idx_digital_signatures_reference` (`reference_type`,`reference_id`) USING BTREE,
  KEY `fk_digital_signatures_verified_by` (`verified_by`) USING BTREE,
  KEY `fk_digital_signatures_revoked_by` (`revoked_by`) USING BTREE,
  CONSTRAINT `digital_signatures_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `digital_signatures_ibfk_2` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `digital_signatures_ibfk_3` FOREIGN KEY (`revoked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `digital_signatures_ibfk_4` FOREIGN KEY (`signer_employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `digital_signatures_ibfk_5` FOREIGN KEY (`signer_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `digital_signatures_ibfk_6` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `digital_signatures_ibfk_7` FOREIGN KEY (`document_version_id`) REFERENCES `document_versions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `discussion_replies`
-- ----------------------------
DROP TABLE IF EXISTS `discussion_replies`;
CREATE TABLE `discussion_replies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `discussion_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_solution` tinyint(1) NOT NULL DEFAULT '0',
  `likes_count` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `discussion_replies_discussion_index` (`discussion_id`) USING BTREE,
  KEY `discussion_replies_user_index` (`user_id`) USING BTREE,
  KEY `discussion_replies_is_solution_index` (`is_solution`) USING BTREE,
  KEY `discussion_replies_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `discussion_replies_created_by_index` (`created_by`) USING BTREE,
  KEY `discussion_replies_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `discussion_replies_discussion_id_foreign` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `discussion_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `discussions`
-- ----------------------------
DROP TABLE IF EXISTS `discussions`;
CREATE TABLE `discussions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `views_count` int NOT NULL DEFAULT '0',
  `replies_count` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `discussions_course_index` (`course_id`) USING BTREE,
  KEY `discussions_user_index` (`user_id`) USING BTREE,
  KEY `discussions_created_at_index` (`created_at`) USING BTREE,
  KEY `discussions_is_pinned_index` (`is_pinned`) USING BTREE,
  KEY `discussions_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `discussions_created_by_index` (`created_by`) USING BTREE,
  KEY `discussions_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `discussions_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `discussions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `document_access_logs`
-- ----------------------------
DROP TABLE IF EXISTS `document_access_logs`;
CREATE TABLE `document_access_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint unsigned NOT NULL COMMENT 'FK ke documents',
  `document_version_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke document_versions (jika akses versi tertentu)',
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke users',
  `student_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke students',
  `employee_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke employees',
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `access_type` enum('view','preview','download','print','share','email','edit','delete','restore','verify','reject','sign','copy','move','rename') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tipe akses',
  `access_duration_seconds` int unsigned DEFAULT NULL COMMENT 'Durasi akses (detik)',
  `pages_viewed` int unsigned DEFAULT NULL COMMENT 'Halaman yang dilihat',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `device_type` enum('desktop','mobile','tablet','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_identifier` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lokasi (jika GPS)',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `referrer` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Halaman asal',
  `share_method` enum('link','email','whatsapp','download','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Metode share',
  `share_recipient` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Penerima share',
  `status` enum('success','failed','denied','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'success' COMMENT 'Status akses',
  `failure_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Alasan gagal',
  `session_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accessed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu akses',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_document_access_document` (`document_id`) USING BTREE,
  KEY `idx_document_access_version` (`document_version_id`) USING BTREE,
  KEY `idx_document_access_user` (`user_id`) USING BTREE,
  KEY `idx_document_access_student` (`student_id`) USING BTREE,
  KEY `idx_document_access_employee` (`employee_id`) USING BTREE,
  KEY `idx_document_access_org` (`organization_id`) USING BTREE,
  KEY `idx_document_access_type` (`access_type`) USING BTREE,
  KEY `idx_document_access_status` (`status`) USING BTREE,
  KEY `idx_document_access_accessed` (`accessed_at`) USING BTREE,
  CONSTRAINT `document_access_logs_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `document_access_logs_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `document_access_logs_ibfk_3` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `document_access_logs_ibfk_4` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `document_access_logs_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `document_access_logs_ibfk_6` FOREIGN KEY (`document_version_id`) REFERENCES `document_versions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `document_number_sequences`
-- ----------------------------
DROP TABLE IF EXISTS `document_number_sequences`;
CREATE TABLE `document_number_sequences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned DEFAULT NULL,
  `document_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_key` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ALL',
  `prefix` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pattern` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '{PREFIX}/{YEAR}/{MONTH}/{SEQ:5}',
  `last_number` bigint unsigned NOT NULL DEFAULT '0',
  `padding` tinyint unsigned NOT NULL DEFAULT '5',
  `reset_frequency` enum('none','yearly','monthly','daily') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yearly',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `doc_number_sequences_unique` (`organization_id`,`school_id`,`document_type`,`period_key`) USING BTREE,
  KEY `doc_seq_type_index` (`document_type`) USING BTREE,
  KEY `doc_seq_school_index` (`school_id`) USING BTREE,
  CONSTRAINT `doc_seq_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `doc_seq_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `document_types`
-- ----------------------------
DROP TABLE IF EXISTS `document_types`;
CREATE TABLE `document_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations, NULL untuk global',
  `parent_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke document_types (hierarki)',
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode unik jenis dokumen, misal: IJAZAH, AKTA',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama jenis dokumen',
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug untuk URL',
  `category` enum('identity','academic','financial','legal','medical','employment','admission','graduation','certificate','report','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other' COMMENT 'Kategori dokumen',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `applicable_to` enum('all','student','employee','teacher','staff','applicant','alumni','organization') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all' COMMENT 'Berlaku untuk',
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_expirable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika punya masa berlaku',
  `default_validity_days` int unsigned DEFAULT NULL COMMENT 'Masa berlaku default (hari)',
  `allowed_extensions` json DEFAULT NULL COMMENT 'Ekstensi file yang diizinkan, misal: ["pdf","jpg"]',
  `max_file_size_mb` int unsigned DEFAULT NULL COMMENT 'Ukuran maks file (MB)',
  `requires_verification` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika perlu verifikasi',
  `requires_signature` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika perlu tanda tangan',
  `template_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Template dokumen (jika generate otomatis)',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_system` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika tipe sistem',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_document_types_slug` (`slug`) USING BTREE,
  UNIQUE KEY `uk_document_types_code` (`code`,`organization_id`) USING BTREE,
  KEY `idx_document_types_org` (`organization_id`) USING BTREE,
  KEY `idx_document_types_parent` (`parent_id`) USING BTREE,
  KEY `idx_document_types_category` (`category`) USING BTREE,
  KEY `idx_document_types_applicable` (`applicable_to`) USING BTREE,
  KEY `idx_document_types_active` (`is_active`) USING BTREE,
  KEY `fk_document_types_created_by` (`created_by`) USING BTREE,
  KEY `fk_document_types_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `document_types_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `document_types_ibfk_2` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `document_types_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `document_types` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `document_types_ibfk_4` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `document_versions`
-- ----------------------------
DROP TABLE IF EXISTS `document_versions`;
CREATE TABLE `document_versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint unsigned NOT NULL COMMENT 'FK ke documents',
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `version_number` int unsigned NOT NULL DEFAULT '1' COMMENT 'Nomor versi',
  `version_label` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Label versi, misal: v1.0, revisi-1',
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama file asli',
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Path file',
  `file_extension` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size_bytes` bigint unsigned DEFAULT NULL,
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SHA-256 hash',
  `change_summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Ringkasan perubahan',
  `change_type` enum('initial','revision','correction','update','replacement','restore') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'revision' COMMENT 'Tipe perubahan',
  `is_current` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika versi saat ini',
  `status` enum('draft','active','superseded','archived','deleted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active' COMMENT 'Status versi',
  `uploaded_by` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang upload',
  `approved_by` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang approve',
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_document_versions_number` (`document_id`,`version_number`) USING BTREE,
  KEY `idx_document_versions_document` (`document_id`) USING BTREE,
  KEY `idx_document_versions_org` (`organization_id`) USING BTREE,
  KEY `idx_document_versions_current` (`is_current`) USING BTREE,
  KEY `idx_document_versions_status` (`status`) USING BTREE,
  KEY `idx_document_versions_hash` (`file_hash`) USING BTREE,
  KEY `fk_document_versions_uploaded_by` (`uploaded_by`) USING BTREE,
  KEY `fk_document_versions_approved_by` (`approved_by`) USING BTREE,
  CONSTRAINT `document_versions_ibfk_1` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `document_versions_ibfk_2` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `document_versions_ibfk_3` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `document_versions_ibfk_4` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `documents`
-- ----------------------------
DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organisasi tempat dokumen berada',
  `document_type_id` bigint unsigned NOT NULL COMMENT 'FK ke document_types — jenis dokumen ini',
  `owner_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Model pemilik: User,Student,Employee dll (untuk polimorfik)',
  `owner_id` bigint unsigned NOT NULL COMMENT 'ID pemilik sesuai model di atas',
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama berkas asli',
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Lokasi di server/storage',
  `file_disk` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local' COMMENT 'Disk penyimpanan: local,s3,dll',
  `file_extension` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'pdf,jpg,png...',
  `file_size_kb` int unsigned DEFAULT NULL COMMENT 'Ukuran dalam KB',
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hash untuk cek integritas',
  `status` enum('draft','pending_verification','verified','rejected','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_verification' COMMENT 'Status proses',
  `verification_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Catatan admin jika ditolak/diverifikasi',
  `verified_by` bigint unsigned DEFAULT NULL COMMENT 'Siapa yang memverifikasi',
  `verified_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT NULL COMMENT 'Tanggal diterbitkan',
  `valid_until` timestamp NULL DEFAULT NULL COMMENT 'Tanggal kadaluwarsa — isi jika is_expirable = 1',
  `signed_by` bigint unsigned DEFAULT NULL,
  `signed_at` timestamp NULL DEFAULT NULL,
  `signature_data` json DEFAULT NULL COMMENT 'Data tanda tangan digital',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Catatan bebas',
  `custom_data` json DEFAULT NULL COMMENT 'Data tambahan spesifik dokumen',
  `is_archived` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Diarsipkan atau tidak',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_documents_org` (`organization_id`),
  KEY `idx_documents_type` (`document_type_id`),
  KEY `idx_documents_owner` (`owner_type`,`owner_id`),
  KEY `idx_documents_status` (`status`),
  KEY `idx_documents_validity` (`valid_until`),
  KEY `idx_documents_verified_by` (`verified_by`),
  KEY `idx_documents_signed_by` (`signed_by`),
  KEY `idx_documents_active` (`is_archived`,`deleted_at`),
  KEY `fk_documents_created_by` (`created_by`),
  KEY `fk_documents_updated_by` (`updated_by`),
  CONSTRAINT `fk_documents_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_documents_organization` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_documents_signed_by` FOREIGN KEY (`signed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_documents_type` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_documents_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_documents_verified_by` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `email_logs`
-- ----------------------------
DROP TABLE IF EXISTS `email_logs`;
CREATE TABLE `email_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `notification_template_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke notification_templates',
  `recipient_user_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke users',
  `recipient_student_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke students',
  `recipient_employee_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke employees',
  `to_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email tujuan',
  `to_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama penerima',
  `cc` json DEFAULT NULL COMMENT 'Daftar CC',
  `bcc` json DEFAULT NULL COMMENT 'Daftar BCC',
  `from_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email pengirim',
  `from_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama pengirim',
  `reply_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Subjek email',
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Isi email (text)',
  `body_html` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Isi email (HTML)',
  `attachments` json DEFAULT NULL COMMENT 'Daftar lampiran',
  `provider` enum('smtp','mailgun','ses','postmark','sendgrid','resend','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'smtp' COMMENT 'Provider email',
  `provider_message_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_response` json DEFAULT NULL,
  `status` enum('queued','sending','sent','delivered','bounced','failed','rejected','spam') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'queued',
  `error_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `retry_count` int unsigned NOT NULL DEFAULT '0',
  `max_retries` int unsigned NOT NULL DEFAULT '3',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `opened_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu dibuka',
  `clicked_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu link diklik',
  `bounced_at` timestamp NULL DEFAULT NULL,
  `failed_at` timestamp NULL DEFAULT NULL,
  `open_count` int unsigned NOT NULL DEFAULT '0' COMMENT 'Jumlah dibuka',
  `click_count` int unsigned NOT NULL DEFAULT '0' COMMENT 'Jumlah klik',
  `reference_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_email_logs_org` (`organization_id`) USING BTREE,
  KEY `idx_email_logs_template` (`notification_template_id`) USING BTREE,
  KEY `idx_email_logs_recipient_user` (`recipient_user_id`) USING BTREE,
  KEY `idx_email_logs_recipient_student` (`recipient_student_id`) USING BTREE,
  KEY `idx_email_logs_recipient_employee` (`recipient_employee_id`) USING BTREE,
  KEY `idx_email_logs_to` (`to_email`) USING BTREE,
  KEY `idx_email_logs_status` (`status`) USING BTREE,
  KEY `idx_email_logs_sent` (`sent_at`) USING BTREE,
  KEY `idx_email_logs_reference` (`reference_type`,`reference_id`) USING BTREE,
  KEY `fk_email_logs_created_by` (`created_by`) USING BTREE,
  CONSTRAINT `email_logs_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `email_logs_ibfk_2` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `email_logs_ibfk_3` FOREIGN KEY (`recipient_employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `email_logs_ibfk_4` FOREIGN KEY (`recipient_student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `email_logs_ibfk_5` FOREIGN KEY (`recipient_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `email_logs_ibfk_6` FOREIGN KEY (`notification_template_id`) REFERENCES `notification_templates` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `failed_jobs`
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `file_uploads`
-- ----------------------------
DROP TABLE IF EXISTS `file_uploads`;
CREATE TABLE `file_uploads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `uploaded_by` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang upload',
  `uploadable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tipe model pemilik (polymorphic)',
  `uploadable_id` bigint unsigned DEFAULT NULL COMMENT 'ID model pemilik (polymorphic)',
  `collection` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Koleksi/grup, misal: attachments, photos, documents',
  `original_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama file asli',
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama file tersimpan',
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Path file',
  `file_extension` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size_bytes` bigint unsigned DEFAULT NULL,
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SHA-256 hash',
  `disk` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local' COMMENT 'Storage disk (local, s3, gcs)',
  `is_public` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika bisa diakses publik',
  `url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL publik (jika public)',
  `thumbnail_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thumbnail',
  `metadata` json DEFAULT NULL COMMENT 'Metadata tambahan (dimensi, durasi, dll)',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `status` enum('uploading','active','archived','deleted','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active' COMMENT 'Status file',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_file_uploads_org` (`organization_id`) USING BTREE,
  KEY `idx_file_uploads_uploader` (`uploaded_by`) USING BTREE,
  KEY `idx_file_uploads_uploadable` (`uploadable_type`,`uploadable_id`) USING BTREE,
  KEY `idx_file_uploads_collection` (`collection`) USING BTREE,
  KEY `idx_file_uploads_hash` (`file_hash`) USING BTREE,
  KEY `idx_file_uploads_disk` (`disk`) USING BTREE,
  KEY `idx_file_uploads_status` (`status`) USING BTREE,
  CONSTRAINT `file_uploads_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `file_uploads_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `genders`
-- ----------------------------
DROP TABLE IF EXISTS `genders`;
CREATE TABLE `genders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `genders_code_unique` (`code`) USING BTREE,
  KEY `genders_deleted_at_index` (`deleted_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `identity_types`
-- ----------------------------
DROP TABLE IF EXISTS `identity_types`;
CREATE TABLE `identity_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_length` tinyint unsigned DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `identity_types_code_unique` (`code`) USING BTREE,
  KEY `identity_types_deleted_at_index` (`deleted_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `job_batches`
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `jobs`
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `jobs_queue_index` (`queue`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `kitab_references`
-- ----------------------------
DROP TABLE IF EXISTS `kitab_references`;
CREATE TABLE `kitab_references` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `subject_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke subjects',
  `course_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke courses',
  `curriculum_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke curriculums',
  `education_level_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke education_levels',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode kitab',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Judul kitab',
  `arabic_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Judul dalam bahasa Arab',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug untuk URL',
  `author` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Pengarang',
  `author_arabic` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Pengarang (Arab)',
  `publisher` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publication_year` year DEFAULT NULL,
  `edition` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `volume` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ar' COMMENT 'Bahasa',
  `category` enum('fiqih','tauhid','tafsir','hadits','akhlak','nahwu','sharaf','balaghah','manthiq','ushul_fiqih','tajwid','tarikh','tasawuf','doa','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other' COMMENT 'Kategori kitab',
  `level` enum('ibtidaiyah','tsanawiyah','aliyah','kuliah','umum') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'umum' COMMENT 'Jenjang',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Ringkasan isi',
  `total_pages` int unsigned DEFAULT NULL,
  `total_chapters` int unsigned DEFAULT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'File digital (PDF)',
  `cover_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cover kitab',
  `is_digital` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika tersedia digital',
  `is_printed` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 jika tersedia cetak',
  `stock` int unsigned NOT NULL DEFAULT '0' COMMENT 'Stok cetak',
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika wajib',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_kitab_references_slug` (`slug`) USING BTREE,
  UNIQUE KEY `uk_kitab_references_code` (`code`,`organization_id`) USING BTREE,
  KEY `idx_kitab_references_org` (`organization_id`) USING BTREE,
  KEY `idx_kitab_references_subject` (`subject_id`) USING BTREE,
  KEY `idx_kitab_references_course` (`course_id`) USING BTREE,
  KEY `idx_kitab_references_curriculum` (`curriculum_id`) USING BTREE,
  KEY `idx_kitab_references_level` (`education_level_id`) USING BTREE,
  KEY `idx_kitab_references_category` (`category`) USING BTREE,
  KEY `idx_kitab_references_level_kitab` (`level`) USING BTREE,
  KEY `idx_kitab_references_active` (`is_active`) USING BTREE,
  KEY `fk_kitab_references_created_by` (`created_by`) USING BTREE,
  KEY `fk_kitab_references_updated_by` (`updated_by`) USING BTREE,
  FULLTEXT KEY `ft_kitab_references_search` (`title`,`arabic_title`,`author`,`description`),
  CONSTRAINT `kitab_references_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `kitab_references_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `kitab_references_ibfk_3` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `kitab_references_ibfk_4` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `kitab_references_ibfk_5` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `kitab_references_ibfk_6` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `kitab_references_ibfk_7` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `marital_statuses`
-- ----------------------------
DROP TABLE IF EXISTS `marital_statuses`;
CREATE TABLE `marital_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `marital_statuses_name_unique` (`name`) USING BTREE,
  KEY `marital_statuses_deleted_at_index` (`deleted_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `message_participants`
-- ----------------------------
DROP TABLE IF EXISTS `message_participants`;
CREATE TABLE `message_participants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message_thread_id` bigint unsigned NOT NULL COMMENT 'FK ke message_threads',
  `user_id` bigint unsigned NOT NULL COMMENT 'FK ke users',
  `role` enum('owner','admin','moderator','member','guest','readonly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member' COMMENT 'Peran di thread',
  `nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama panggilan di thread',
  `is_muted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika di-mute',
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika di-pin',
  `last_read_at` timestamp NULL DEFAULT NULL COMMENT 'Terakhir baca',
  `last_read_message_id` bigint unsigned DEFAULT NULL COMMENT 'ID pesan terakhir yang dibaca',
  `unread_count` int unsigned NOT NULL DEFAULT '0' COMMENT 'Jumlah belum dibaca',
  `joined_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu join',
  `left_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu keluar',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `mute_until` timestamp NULL DEFAULT NULL COMMENT 'Mute sampai',
  `notification_preference` enum('all','mentions','none') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all' COMMENT 'Preferensi notifikasi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_message_participants` (`message_thread_id`,`user_id`) USING BTREE,
  KEY `idx_message_participants_thread` (`message_thread_id`) USING BTREE,
  KEY `idx_message_participants_user` (`user_id`) USING BTREE,
  KEY `idx_message_participants_role` (`role`) USING BTREE,
  KEY `idx_message_participants_active` (`is_active`) USING BTREE,
  KEY `idx_message_participants_last_read` (`last_read_at`) USING BTREE,
  CONSTRAINT `message_participants_ibfk_1` FOREIGN KEY (`message_thread_id`) REFERENCES `message_threads` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `message_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `message_threads`
-- ----------------------------
DROP TABLE IF EXISTS `message_threads`;
CREATE TABLE `message_threads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `created_by` bigint unsigned DEFAULT NULL COMMENT 'FK ke users pembuat thread',
  `thread_type` enum('direct','group','class','announcement','support','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'direct' COMMENT 'Tipe thread',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Judul thread (untuk grup)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Deskripsi thread',
  `avatar_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Avatar thread',
  `reference_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tipe referensi (classroom, extracurricular, dll)',
  `reference_id` bigint unsigned DEFAULT NULL COMMENT 'ID referensi',
  `is_locked` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika thread dikunci',
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika di-pin',
  `allow_attachments` tinyint(1) NOT NULL DEFAULT '1',
  `allow_reactions` tinyint(1) NOT NULL DEFAULT '1',
  `max_participants` int unsigned DEFAULT NULL COMMENT 'Maks peserta',
  `last_message_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu pesan terakhir',
  `last_message_id` bigint unsigned DEFAULT NULL COMMENT 'ID pesan terakhir',
  `message_count` int unsigned NOT NULL DEFAULT '0' COMMENT 'Jumlah pesan',
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `archived_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_message_threads_org` (`organization_id`) USING BTREE,
  KEY `idx_message_threads_creator` (`created_by`) USING BTREE,
  KEY `idx_message_threads_type` (`thread_type`) USING BTREE,
  KEY `idx_message_threads_reference` (`reference_type`,`reference_id`) USING BTREE,
  KEY `idx_message_threads_last_message` (`last_message_at`) USING BTREE,
  KEY `idx_message_threads_archived` (`is_archived`) USING BTREE,
  CONSTRAINT `message_threads_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `message_threads_ibfk_2` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `messages`
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint unsigned NOT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `subject` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_message_id` bigint unsigned DEFAULT NULL,
  `attachment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `messages_sender_index` (`sender_id`) USING BTREE,
  KEY `messages_receiver_index` (`receiver_id`) USING BTREE,
  KEY `messages_parent_index` (`parent_message_id`) USING BTREE,
  KEY `messages_is_read_index` (`is_read`) USING BTREE,
  KEY `messages_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `messages_created_by_index` (`created_by`) USING BTREE,
  KEY `messages_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `messages_parent_message_id_foreign` FOREIGN KEY (`parent_message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `migrations`
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `modules`
-- ----------------------------
DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `modules_code_unique` (`code`) USING BTREE,
  KEY `modules_is_active_index` (`is_active`) USING BTREE,
  KEY `modules_deleted_at_index` (`deleted_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=441 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `notification_preferences`
-- ----------------------------
DROP TABLE IF EXISTS `notification_preferences`;
CREATE TABLE `notification_preferences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL COMMENT 'FK ke users',
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `category` enum('academic','finance','attendance','admission','announcement','reminder','alert','system','marketing','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kategori notifikasi',
  `email_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 jika email aktif',
  `whatsapp_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 jika WA aktif',
  `sms_enabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika SMS aktif',
  `push_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 jika push aktif',
  `in_app_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 jika in-app aktif',
  `quiet_hours_start` time DEFAULT NULL COMMENT 'Jam mulai mode senyap',
  `quiet_hours_end` time DEFAULT NULL COMMENT 'Jam selesai mode senyap',
  `digest_frequency` enum('instant','hourly','daily','weekly','never') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'instant' COMMENT 'Frekuensi digest',
  `language` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'id' COMMENT 'Preferensi bahasa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_notification_preferences_user_category` (`user_id`,`category`) USING BTREE,
  KEY `idx_notification_preferences_user` (`user_id`) USING BTREE,
  KEY `idx_notification_preferences_org` (`organization_id`) USING BTREE,
  KEY `idx_notification_preferences_category` (`category`) USING BTREE,
  CONSTRAINT `notification_preferences_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `notification_preferences_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `notification_templates`
-- ----------------------------
DROP TABLE IF EXISTS `notification_templates`;
CREATE TABLE `notification_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations, NULL untuk global',
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode unik template, misal: INVOICE_PAID',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama template',
  `category` enum('academic','finance','attendance','admission','announcement','reminder','alert','system','marketing','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'system' COMMENT 'Kategori notifikasi',
  `channel` enum('email','whatsapp','sms','push','in_app','webhook','all') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'email' COMMENT 'Channel pengiriman',
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Subjek (untuk email)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Judul (untuk push/in-app)',
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Isi template (bisa dengan placeholder {{name}})',
  `body_html` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Isi HTML (untuk email)',
  `variables` json DEFAULT NULL COMMENT 'Daftar variabel yang tersedia',
  `language` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'id' COMMENT 'Kode bahasa',
  `sender_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama pengirim',
  `sender_email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lampiran default',
  `priority` enum('low','normal','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_system` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika template sistem (tidak bisa dihapus)',
  `version` int unsigned NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_notification_templates_code` (`code`,`organization_id`,`channel`,`language`) USING BTREE,
  KEY `idx_notification_templates_org` (`organization_id`) USING BTREE,
  KEY `idx_notification_templates_category` (`category`) USING BTREE,
  KEY `idx_notification_templates_channel` (`channel`) USING BTREE,
  KEY `idx_notification_templates_active` (`is_active`) USING BTREE,
  KEY `idx_notification_templates_system` (`is_system`) USING BTREE,
  KEY `fk_notification_templates_created_by` (`created_by`) USING BTREE,
  KEY `fk_notification_templates_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `notification_templates_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `notification_templates_ibfk_2` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `notification_templates_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `notifications`
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference_table` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `channel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IN_APP',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `notifications_user_index` (`user_id`) USING BTREE,
  KEY `notifications_type_index` (`type`) USING BTREE,
  KEY `notifications_channel_index` (`channel`) USING BTREE,
  KEY `notifications_is_read_index` (`is_read`) USING BTREE,
  KEY `notifications_reference_index` (`reference_table`,`reference_id`) USING BTREE,
  KEY `notifications_sent_at_index` (`sent_at`) USING BTREE,
  KEY `notifications_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `notifications_created_by_index` (`created_by`) USING BTREE,
  KEY `notifications_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `password_reset_tokens`
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `pesantren_activities`
-- ----------------------------
DROP TABLE IF EXISTS `pesantren_activities`;
CREATE TABLE `pesantren_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `dormitory_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke dormitories',
  `room_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke rooms',
  `kitab_reference_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke kitab_references',
  `academic_year_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke academic_years',
  `academic_semester_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke academic_semesters',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode kegiatan',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama kegiatan',
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug untuk URL',
  `category` enum('kajian','halaqah','musyawarah','lomba','haul','wisuda','rihlah','olahraga','seni','sosial','lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kajian',
  `activity_type` enum('routine','special','annual','temporary') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'routine',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `objectives` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `speaker_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `speaker_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quota` int unsigned NOT NULL DEFAULT '0',
  `registered` int unsigned NOT NULL DEFAULT '0',
  `attended` int unsigned NOT NULL DEFAULT '0',
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `requires_registration` tinyint(1) NOT NULL DEFAULT '0',
  `registration_open` date DEFAULT NULL,
  `registration_close` date DEFAULT NULL,
  `budget` decimal(15,2) DEFAULT NULL,
  `actual_cost` decimal(15,2) DEFAULT NULL,
  `currency_id` bigint unsigned DEFAULT NULL,
  `poster_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documentation_paths` json DEFAULT NULL,
  `status` enum('draft','published','ongoing','completed','cancelled','postponed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_pesantren_activities_slug` (`slug`) USING BTREE,
  UNIQUE KEY `uk_pesantren_activities_code` (`code`,`organization_id`) USING BTREE,
  KEY `idx_pesantren_activities_org` (`organization_id`) USING BTREE,
  KEY `idx_pesantren_activities_dormitory` (`dormitory_id`) USING BTREE,
  KEY `idx_pesantren_activities_room` (`room_id`) USING BTREE,
  KEY `idx_pesantren_activities_kitab` (`kitab_reference_id`) USING BTREE,
  KEY `idx_pesantren_activities_year` (`academic_year_id`) USING BTREE,
  KEY `idx_pesantren_activities_semester` (`academic_semester_id`) USING BTREE,
  KEY `idx_pesantren_activities_category` (`category`) USING BTREE,
  KEY `idx_pesantren_activities_type` (`activity_type`) USING BTREE,
  KEY `idx_pesantren_activities_start` (`start_date`) USING BTREE,
  KEY `idx_pesantren_activities_status` (`status`) USING BTREE,
  KEY `fk_pesantren_activities_currency` (`currency_id`) USING BTREE,
  KEY `fk_pesantren_activities_created_by` (`created_by`) USING BTREE,
  KEY `fk_pesantren_activities_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `pesantren_activities_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_activities_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_activities_ibfk_3` FOREIGN KEY (`dormitory_id`) REFERENCES `dormitories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_activities_ibfk_4` FOREIGN KEY (`kitab_reference_id`) REFERENCES `kitab_references` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_activities_ibfk_5` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_activities_ibfk_6` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_activities_ibfk_7` FOREIGN KEY (`academic_semester_id`) REFERENCES `academic_semesters` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_activities_ibfk_8` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_activities_ibfk_9` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `pesantren_rule_violations`
-- ----------------------------
DROP TABLE IF EXISTS `pesantren_rule_violations`;
CREATE TABLE `pesantren_rule_violations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pesantren_rule_id` bigint unsigned NOT NULL COMMENT 'FK ke pesantren_rules',
  `student_id` bigint unsigned NOT NULL COMMENT 'FK ke students',
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `dormitory_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke dormitories',
  `reporter_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang melaporkan',
  `handler_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang menangani',
  `violation_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `violation_date` date NOT NULL,
  `violation_time` time DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `evidence_paths` json DEFAULT NULL,
  `witnesses` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `severity` enum('light','medium','heavy','severe') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light',
  `sanction_type` enum('warning','point','detention','suspension','expulsion','community_service','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sanction_points` int unsigned NOT NULL DEFAULT '0',
  `sanction_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sanction_start_date` date DEFAULT NULL,
  `sanction_end_date` date DEFAULT NULL,
  `status` enum('reported','investigating','resolved','appealed','closed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'reported',
  `student_statement` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `parent_notified` tinyint(1) NOT NULL DEFAULT '0',
  `parent_notified_at` timestamp NULL DEFAULT NULL,
  `parent_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `resolution_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `appeal_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `appeal_status` enum('none','pending','accepted','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `appeal_decided_by` bigint unsigned DEFAULT NULL,
  `appeal_decided_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_pesantren_violation_number` (`violation_number`) USING BTREE,
  KEY `idx_pesantren_violation_rule` (`pesantren_rule_id`) USING BTREE,
  KEY `idx_pesantren_violation_student` (`student_id`) USING BTREE,
  KEY `idx_pesantren_violation_org` (`organization_id`) USING BTREE,
  KEY `idx_pesantren_violation_dormitory` (`dormitory_id`) USING BTREE,
  KEY `idx_pesantren_violation_reporter` (`reporter_id`) USING BTREE,
  KEY `idx_pesantren_violation_handler` (`handler_id`) USING BTREE,
  KEY `idx_pesantren_violation_date` (`violation_date`) USING BTREE,
  KEY `idx_pesantren_violation_severity` (`severity`) USING BTREE,
  KEY `idx_pesantren_violation_status` (`status`) USING BTREE,
  KEY `fk_pesantren_violation_appeal_decided_by` (`appeal_decided_by`) USING BTREE,
  KEY `fk_pesantren_violation_created_by` (`created_by`) USING BTREE,
  KEY `fk_pesantren_violation_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `pesantren_rule_violations_ibfk_1` FOREIGN KEY (`appeal_decided_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_rule_violations_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_rule_violations_ibfk_3` FOREIGN KEY (`dormitory_id`) REFERENCES `dormitories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_rule_violations_ibfk_4` FOREIGN KEY (`handler_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_rule_violations_ibfk_5` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_rule_violations_ibfk_6` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_rule_violations_ibfk_7` FOREIGN KEY (`pesantren_rule_id`) REFERENCES `pesantren_rules` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_rule_violations_ibfk_8` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_rule_violations_ibfk_9` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `pesantren_rules`
-- ----------------------------
DROP TABLE IF EXISTS `pesantren_rules`;
CREATE TABLE `pesantren_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `dormitory_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke dormitories',
  `parent_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke pesantren_rules (hierarki)',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode peraturan',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Judul peraturan',
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug untuk URL',
  `category` enum('discipline','worship','study','dormitory','health','security','ethics','uniform','technology','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'discipline',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `legal_basis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `severity` enum('light','medium','heavy','severe') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light',
  `sanction_type` enum('warning','point','detention','suspension','expulsion','community_service','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sanction_points` int unsigned NOT NULL DEFAULT '0',
  `effective_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `document_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_pesantren_rules_slug` (`slug`) USING BTREE,
  UNIQUE KEY `uk_pesantren_rules_code` (`code`,`organization_id`) USING BTREE,
  KEY `idx_pesantren_rules_org` (`organization_id`) USING BTREE,
  KEY `idx_pesantren_rules_dormitory` (`dormitory_id`) USING BTREE,
  KEY `idx_pesantren_rules_parent` (`parent_id`) USING BTREE,
  KEY `idx_pesantren_rules_category` (`category`) USING BTREE,
  KEY `idx_pesantren_rules_severity` (`severity`) USING BTREE,
  KEY `idx_pesantren_rules_active` (`is_active`) USING BTREE,
  KEY `fk_pesantren_rules_created_by` (`created_by`) USING BTREE,
  KEY `fk_pesantren_rules_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `pesantren_rules_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_rules_ibfk_2` FOREIGN KEY (`dormitory_id`) REFERENCES `dormitories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_rules_ibfk_3` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_rules_ibfk_4` FOREIGN KEY (`parent_id`) REFERENCES `pesantren_rules` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pesantren_rules_ibfk_5` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `push_notification_tokens`
-- ----------------------------
DROP TABLE IF EXISTS `push_notification_tokens`;
CREATE TABLE `push_notification_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL COMMENT 'FK ke users',
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Token FCM/APNs',
  `token_hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hash token untuk unique index',
  `platform` enum('android','ios','web','huawei','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'android' COMMENT 'Platform',
  `device_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID unik device',
  `device_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama device',
  `device_model` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Model device',
  `os_version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Versi OS',
  `app_version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Versi aplikasi',
  `provider` enum('fcm','apns','huawei','web_push','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fcm' COMMENT 'Provider push',
  `locale` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Locale device',
  `timezone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Timezone device',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_used_at` timestamp NULL DEFAULT NULL COMMENT 'Terakhir digunakan',
  `expires_at` timestamp NULL DEFAULT NULL COMMENT 'Kadaluarsa token',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_push_tokens_hash` (`token_hash`) USING BTREE,
  KEY `idx_push_tokens_user` (`user_id`) USING BTREE,
  KEY `idx_push_tokens_org` (`organization_id`) USING BTREE,
  KEY `idx_push_tokens_platform` (`platform`) USING BTREE,
  KEY `idx_push_tokens_active` (`is_active`) USING BTREE,
  KEY `idx_push_tokens_last_used` (`last_used_at`) USING BTREE,
  CONSTRAINT `push_notification_tokens_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `push_notification_tokens_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `reference_types`
-- ----------------------------
DROP TABLE IF EXISTS `reference_types`;
CREATE TABLE `reference_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `reference_types_code_unique` (`code`) USING BTREE,
  KEY `reference_types_is_active_index` (`is_active`) USING BTREE,
  KEY `reference_types_deleted_at_index` (`deleted_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `reference_values`
-- ----------------------------
DROP TABLE IF EXISTS `reference_values`;
CREATE TABLE `reference_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference_type_id` bigint unsigned NOT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `badge_color` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'secondary',
  `badge_class` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `reference_values_type_code_unique` (`reference_type_id`,`code`) USING BTREE,
  KEY `reference_values_type_index` (`reference_type_id`) USING BTREE,
  KEY `reference_values_active_index` (`is_active`) USING BTREE,
  KEY `reference_values_sort_order_index` (`sort_order`) USING BTREE,
  KEY `reference_values_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `reference_values_type_fk` FOREIGN KEY (`reference_type_id`) REFERENCES `reference_types` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `religions`
-- ----------------------------
DROP TABLE IF EXISTS `religions`;
CREATE TABLE `religions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `religions_name_unique` (`name`) USING BTREE,
  KEY `religions_deleted_at_index` (`deleted_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `scheduled_jobs`
-- ----------------------------
DROP TABLE IF EXISTS `scheduled_jobs`;
CREATE TABLE `scheduled_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `command` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_run` timestamp NULL DEFAULT NULL,
  `next_run` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `last_error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `scheduled_jobs_org_name_unique` (`organization_id`,`name`) USING BTREE,
  KEY `scheduled_jobs_status_index` (`status`) USING BTREE,
  KEY `scheduled_jobs_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `scheduled_jobs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `sessions`
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `sessions_user_id_index` (`user_id`) USING BTREE,
  KEY `sessions_last_activity_index` (`last_activity`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `sms_logs`
-- ----------------------------
DROP TABLE IF EXISTS `sms_logs`;
CREATE TABLE `sms_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `notification_template_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke notification_templates',
  `recipient_user_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke users',
  `recipient_student_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke students',
  `recipient_employee_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke employees',
  `phone_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nomor SMS tujuan',
  `recipient_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Isi SMS',
  `message_length` int unsigned DEFAULT NULL COMMENT 'Panjang pesan',
  `message_parts` int unsigned NOT NULL DEFAULT '1' COMMENT 'Jumlah parts (jika > 160 char)',
  `provider` enum('twilio','nexmo','vonage','local','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'twilio' COMMENT 'Provider SMS',
  `provider_message_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_response` json DEFAULT NULL,
  `sender_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sender ID / nomor pengirim',
  `status` enum('queued','sending','sent','delivered','failed','undelivered','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'queued',
  `error_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `retry_count` int unsigned NOT NULL DEFAULT '0',
  `max_retries` int unsigned NOT NULL DEFAULT '3',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `failed_at` timestamp NULL DEFAULT NULL,
  `cost` decimal(15,4) DEFAULT NULL,
  `currency_id` bigint unsigned DEFAULT NULL,
  `reference_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_sms_logs_org` (`organization_id`) USING BTREE,
  KEY `idx_sms_logs_template` (`notification_template_id`) USING BTREE,
  KEY `idx_sms_logs_recipient_user` (`recipient_user_id`) USING BTREE,
  KEY `idx_sms_logs_recipient_student` (`recipient_student_id`) USING BTREE,
  KEY `idx_sms_logs_recipient_employee` (`recipient_employee_id`) USING BTREE,
  KEY `idx_sms_logs_phone` (`phone_number`) USING BTREE,
  KEY `idx_sms_logs_status` (`status`) USING BTREE,
  KEY `idx_sms_logs_sent` (`sent_at`) USING BTREE,
  KEY `idx_sms_logs_reference` (`reference_type`,`reference_id`) USING BTREE,
  KEY `fk_sms_logs_currency` (`currency_id`) USING BTREE,
  KEY `fk_sms_logs_created_by` (`created_by`) USING BTREE,
  CONSTRAINT `sms_logs_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sms_logs_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sms_logs_ibfk_3` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sms_logs_ibfk_4` FOREIGN KEY (`recipient_employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sms_logs_ibfk_5` FOREIGN KEY (`recipient_student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sms_logs_ibfk_6` FOREIGN KEY (`recipient_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sms_logs_ibfk_7` FOREIGN KEY (`notification_template_id`) REFERENCES `notification_templates` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `sorogan_evaluations`
-- ----------------------------
DROP TABLE IF EXISTS `sorogan_evaluations`;
CREATE TABLE `sorogan_evaluations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sorogan_session_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke sorogan_sessions',
  `sorogan_schedule_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke sorogan_schedules',
  `student_id` bigint unsigned NOT NULL COMMENT 'FK ke students',
  `teacher_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke teachers',
  `employee_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke employees',
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `kitab_reference_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke kitab_references',
  `evaluation_date` date NOT NULL,
  `chapter` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_from` int unsigned DEFAULT NULL,
  `page_to` int unsigned DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fluency_score` decimal(5,2) DEFAULT NULL,
  `tajwid_score` decimal(5,2) DEFAULT NULL,
  `makhraj_score` decimal(5,2) DEFAULT NULL,
  `understanding_score` decimal(5,2) DEFAULT NULL,
  `memorization_score` decimal(5,2) DEFAULT NULL,
  `final_score` decimal(5,2) DEFAULT NULL,
  `predicate` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mastery_level` enum('beginner','intermediate','advanced','expert') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attendance_status` enum('present','absent','late','sick','permission') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `teacher_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `recommendations` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `next_target` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','submitted','reviewed','finalized') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_sorogan_eval_session` (`sorogan_session_id`) USING BTREE,
  KEY `idx_sorogan_eval_schedule` (`sorogan_schedule_id`) USING BTREE,
  KEY `idx_sorogan_eval_student` (`student_id`) USING BTREE,
  KEY `idx_sorogan_eval_teacher` (`teacher_id`) USING BTREE,
  KEY `idx_sorogan_eval_employee` (`employee_id`) USING BTREE,
  KEY `idx_sorogan_eval_org` (`organization_id`) USING BTREE,
  KEY `idx_sorogan_eval_kitab` (`kitab_reference_id`) USING BTREE,
  KEY `idx_sorogan_eval_date` (`evaluation_date`) USING BTREE,
  KEY `idx_sorogan_eval_status` (`status`) USING BTREE,
  KEY `fk_sorogan_eval_reviewed_by` (`reviewed_by`) USING BTREE,
  KEY `fk_sorogan_eval_created_by` (`created_by`) USING BTREE,
  KEY `fk_sorogan_eval_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `sorogan_evaluations_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_evaluations_ibfk_10` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_evaluations_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_evaluations_ibfk_3` FOREIGN KEY (`kitab_reference_id`) REFERENCES `kitab_references` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_evaluations_ibfk_4` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_evaluations_ibfk_5` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_evaluations_ibfk_6` FOREIGN KEY (`sorogan_schedule_id`) REFERENCES `sorogan_schedules` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_evaluations_ibfk_7` FOREIGN KEY (`sorogan_session_id`) REFERENCES `sorogan_sessions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_evaluations_ibfk_8` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_evaluations_ibfk_9` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `sorogan_schedules`
-- ----------------------------
DROP TABLE IF EXISTS `sorogan_schedules`;
CREATE TABLE `sorogan_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `kitab_reference_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke kitab_references',
  `teacher_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke teachers',
  `employee_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke employees',
  `dormitory_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke dormitories',
  `room_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke rooms',
  `academic_year_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke academic_years',
  `academic_semester_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke academic_semesters',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Judul sesi sorogan',
  `sorogan_type` enum('individual','group','classical','halaqah','talaqqi','musyawarah') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'individual' COMMENT 'Tipe sorogan',
  `day_of_week` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hari',
  `specific_date` date DEFAULT NULL COMMENT 'Tanggal spesifik',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_minutes` int unsigned DEFAULT NULL,
  `recurrence_type` enum('none','weekly','biweekly','monthly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'weekly',
  `recurrence_until` date DEFAULT NULL,
  `quota` int unsigned NOT NULL DEFAULT '0',
  `registered` int unsigned NOT NULL DEFAULT '0',
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `effective_from` date DEFAULT NULL,
  `effective_until` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_sorogan_sched_org` (`organization_id`) USING BTREE,
  KEY `idx_sorogan_sched_kitab` (`kitab_reference_id`) USING BTREE,
  KEY `idx_sorogan_sched_teacher` (`teacher_id`) USING BTREE,
  KEY `idx_sorogan_sched_employee` (`employee_id`) USING BTREE,
  KEY `idx_sorogan_sched_dormitory` (`dormitory_id`) USING BTREE,
  KEY `idx_sorogan_sched_room` (`room_id`) USING BTREE,
  KEY `idx_sorogan_sched_year` (`academic_year_id`) USING BTREE,
  KEY `idx_sorogan_sched_semester` (`academic_semester_id`) USING BTREE,
  KEY `idx_sorogan_sched_type` (`sorogan_type`) USING BTREE,
  KEY `idx_sorogan_sched_day` (`day_of_week`) USING BTREE,
  KEY `idx_sorogan_sched_active` (`is_active`) USING BTREE,
  KEY `fk_sorogan_sched_created_by` (`created_by`) USING BTREE,
  KEY `fk_sorogan_sched_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `sorogan_schedules_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_schedules_ibfk_10` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_schedules_ibfk_2` FOREIGN KEY (`dormitory_id`) REFERENCES `dormitories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_schedules_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_schedules_ibfk_4` FOREIGN KEY (`kitab_reference_id`) REFERENCES `kitab_references` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_schedules_ibfk_5` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_schedules_ibfk_6` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_schedules_ibfk_7` FOREIGN KEY (`academic_semester_id`) REFERENCES `academic_semesters` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_schedules_ibfk_8` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sorogan_schedules_ibfk_9` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `system_logs`
-- ----------------------------
DROP TABLE IF EXISTS `system_logs`;
CREATE TABLE `system_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `record_id` bigint unsigned DEFAULT NULL,
  `old_data` json DEFAULT NULL,
  `new_data` json DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `organization_id` bigint unsigned DEFAULT NULL,
  `level` enum('debug','info','warning','error','critical') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `context` json DEFAULT NULL,
  `channel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_system_logs_level` (`level`) USING BTREE,
  KEY `idx_system_logs_created` (`created_at`) USING BTREE,
  KEY `idx_system_logs_org` (`organization_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `tags`
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uniq_tag_per_school` (`school_id`,`name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `template_assets`
-- ----------------------------
DROP TABLE IF EXISTS `template_assets`;
CREATE TABLE `template_assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `template_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `trial_registration_education_level`
-- ----------------------------
DROP TABLE IF EXISTS `trial_registration_education_level`;
CREATE TABLE `trial_registration_education_level` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trial_registration_id` bigint unsigned NOT NULL,
  `education_level_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `tr_education_level_unique` (`trial_registration_id`,`education_level_id`) USING BTREE,
  KEY `trial_registration_education_level_education_fk` (`education_level_id`) USING BTREE,
  CONSTRAINT `tr_education_level_edu_fk` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tr_education_level_reg_fk` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `trial_registration_module`
-- ----------------------------
DROP TABLE IF EXISTS `trial_registration_module`;
CREATE TABLE `trial_registration_module` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trial_registration_id` bigint unsigned NOT NULL,
  `module_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `tr_module_unique` (`trial_registration_id`,`module_id`) USING BTREE,
  KEY `trial_registration_module_module_fk` (`module_id`) USING BTREE,
  CONSTRAINT `tr_module_module_fk` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tr_module_reg_fk` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `trial_registration_role`
-- ----------------------------
DROP TABLE IF EXISTS `trial_registration_role`;
CREATE TABLE `trial_registration_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trial_registration_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `tr_role_unique` (`trial_registration_id`,`role_id`) USING BTREE,
  KEY `trial_registration_role_role_fk` (`role_id`) USING BTREE,
  CONSTRAINT `tr_role_reg_fk` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tr_role_role_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `trial_registration_role_modules`
-- ----------------------------
DROP TABLE IF EXISTS `trial_registration_role_modules`;
CREATE TABLE `trial_registration_role_modules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trial_registration_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `module_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trm_registration_role_module_unique` (`trial_registration_id`,`role_id`,`module_id`),
  KEY `trial_registration_role_modules_role_id_foreign` (`role_id`),
  KEY `trial_registration_role_modules_module_id_foreign` (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `trial_registrations`
-- ----------------------------
DROP TABLE IF EXISTS `trial_registrations`;
CREATE TABLE `trial_registrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL,
  `token` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_step` tinyint unsigned NOT NULL DEFAULT '1',
  `status` enum('in_progress','completed','abandoned') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `nama_sekolah` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `npsn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_student` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position_id` int DEFAULT NULL,
  `notif_whatsapp` tinyint(1) NOT NULL DEFAULT '0',
  `notif_email` tinyint(1) NOT NULL DEFAULT '0',
  `notif_sms` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `trial_registrations_token_unique` (`token`) USING BTREE,
  KEY `trial_registrations_status_index` (`status`) USING BTREE,
  KEY `trial_registrations_deleted_at_index` (`deleted_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `whatsapp_logs`
-- ----------------------------
DROP TABLE IF EXISTS `whatsapp_logs`;
CREATE TABLE `whatsapp_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `notification_template_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke notification_templates',
  `recipient_user_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke users (jika penerima user)',
  `recipient_student_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke students',
  `recipient_employee_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke employees',
  `phone_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nomor WA tujuan',
  `recipient_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama penerima',
  `message_type` enum('text','image','document','video','audio','template','interactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text' COMMENT 'Tipe pesan',
  `message_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Isi pesan',
  `template_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama template WA (jika pakai template)',
  `template_variables` json DEFAULT NULL COMMENT 'Variabel template',
  `media_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL media',
  `media_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path media lokal',
  `provider` enum('twilio','fonnte','wablas','wati','meta','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'meta' COMMENT 'Provider WA',
  `provider_message_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID pesan dari provider',
  `provider_response` json DEFAULT NULL COMMENT 'Response dari provider',
  `status` enum('queued','sending','sent','delivered','read','failed','undelivered','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'queued' COMMENT 'Status pengiriman',
  `error_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `retry_count` int unsigned NOT NULL DEFAULT '0',
  `max_retries` int unsigned NOT NULL DEFAULT '3',
  `scheduled_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu dijadwalkan',
  `sent_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu terkirim',
  `delivered_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu diterima',
  `read_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu dibaca',
  `failed_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu gagal',
  `cost` decimal(15,4) DEFAULT NULL COMMENT 'Biaya pengiriman',
  `currency_id` bigint unsigned DEFAULT NULL,
  `reference_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tipe referensi (invoice, announcement, dll)',
  `reference_id` bigint unsigned DEFAULT NULL COMMENT 'ID referensi',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_whatsapp_logs_org` (`organization_id`) USING BTREE,
  KEY `idx_whatsapp_logs_template` (`notification_template_id`) USING BTREE,
  KEY `idx_whatsapp_logs_recipient_user` (`recipient_user_id`) USING BTREE,
  KEY `idx_whatsapp_logs_recipient_student` (`recipient_student_id`) USING BTREE,
  KEY `idx_whatsapp_logs_recipient_employee` (`recipient_employee_id`) USING BTREE,
  KEY `idx_whatsapp_logs_phone` (`phone_number`) USING BTREE,
  KEY `idx_whatsapp_logs_status` (`status`) USING BTREE,
  KEY `idx_whatsapp_logs_sent` (`sent_at`) USING BTREE,
  KEY `idx_whatsapp_logs_reference` (`reference_type`,`reference_id`) USING BTREE,
  KEY `fk_whatsapp_logs_currency` (`currency_id`) USING BTREE,
  KEY `fk_whatsapp_logs_created_by` (`created_by`) USING BTREE,
  CONSTRAINT `whatsapp_logs_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `whatsapp_logs_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `whatsapp_logs_ibfk_3` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `whatsapp_logs_ibfk_4` FOREIGN KEY (`recipient_employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `whatsapp_logs_ibfk_5` FOREIGN KEY (`recipient_student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `whatsapp_logs_ibfk_6` FOREIGN KEY (`recipient_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `whatsapp_logs_ibfk_7` FOREIGN KEY (`notification_template_id`) REFERENCES `notification_templates` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `workflow_approvers`
-- ----------------------------
DROP TABLE IF EXISTS `workflow_approvers`;
CREATE TABLE `workflow_approvers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `workflow_step_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `wf_appr_step_index` (`workflow_step_id`) USING BTREE,
  KEY `wf_appr_role_index` (`role_id`) USING BTREE,
  KEY `wf_appr_user_index` (`user_id`) USING BTREE,
  CONSTRAINT `wf_appr_role_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wf_appr_step_fk` FOREIGN KEY (`workflow_step_id`) REFERENCES `workflow_steps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wf_appr_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `workflow_definitions`
-- ----------------------------
DROP TABLE IF EXISTS `workflow_definitions`;
CREATE TABLE `workflow_definitions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `wf_def_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `wf_def_school_index` (`school_id`) USING BTREE,
  KEY `wf_def_module_index` (`module`) USING BTREE,
  KEY `wf_def_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `wf_def_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `wf_def_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `workflow_steps`
-- ----------------------------
DROP TABLE IF EXISTS `workflow_steps`;
CREATE TABLE `workflow_steps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `workflow_definition_id` bigint unsigned NOT NULL,
  `step_order` int unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `approver_type` enum('role','user','supervisor','dept_head','amount_threshold') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'role',
  `min_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `max_amount` decimal(15,2) DEFAULT NULL,
  `action_type` enum('approve_reject','review','acknowledge') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approve_reject',
  `auto_notify` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `wf_step_order_unique` (`workflow_definition_id`,`step_order`) USING BTREE,
  CONSTRAINT `wf_step_def_fk` FOREIGN KEY (`workflow_definition_id`) REFERENCES `workflow_definitions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
