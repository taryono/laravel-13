/*
 * Table Group: absence
 * Total Tables: 19
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `attendance_corrections`
-- ----------------------------
DROP TABLE IF EXISTS `attendance_corrections`;
CREATE TABLE `attendance_corrections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_attendance_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke employee_attendances yang dikoreksi',
  `employee_id` bigint unsigned NOT NULL COMMENT 'FK ke employees yang mengajukan',
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `correction_type` enum('check_in','check_out','both','status','overtime','break','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Jenis koreksi',
  `original_check_in` datetime DEFAULT NULL COMMENT 'Check-in sebelum koreksi',
  `original_check_out` datetime DEFAULT NULL COMMENT 'Check-out sebelum koreksi',
  `original_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Status sebelum koreksi',
  `requested_check_in` datetime DEFAULT NULL COMMENT 'Check-in yang diminta',
  `requested_check_out` datetime DEFAULT NULL COMMENT 'Check-out yang diminta',
  `requested_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Status yang diminta',
  `requested_overtime_minutes` int unsigned DEFAULT NULL COMMENT 'Overtime yang diminta (menit)',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Alasan koreksi',
  `attachment_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bukti pendukung (foto, surat)',
  `status` enum('pending','approved','rejected','cancelled','revised') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'Status permintaan',
  `approved_by` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang approve',
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang reject',
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `applied_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu koreksi diterapkan ke employee_attendances',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_att_corr_attendance` (`employee_attendance_id`) USING BTREE,
  KEY `idx_att_corr_employee` (`employee_id`) USING BTREE,
  KEY `idx_att_corr_org` (`organization_id`) USING BTREE,
  KEY `idx_att_corr_status` (`status`) USING BTREE,
  KEY `idx_att_corr_type` (`correction_type`) USING BTREE,
  KEY `fk_att_corr_approved_by` (`approved_by`) USING BTREE,
  KEY `fk_att_corr_rejected_by` (`rejected_by`) USING BTREE,
  KEY `fk_att_corr_created_by` (`created_by`) USING BTREE,
  KEY `fk_att_corr_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `fk_att_corr_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_att_corr_attendance` FOREIGN KEY (`employee_attendance_id`) REFERENCES `employee_attendances` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_att_corr_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_att_corr_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_att_corr_org` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_att_corr_rejected_by` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_att_corr_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `attendance_dailies`
-- ----------------------------
DROP TABLE IF EXISTS `attendance_dailies`;
CREATE TABLE `attendance_dailies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('present','absent','late','leave','holiday','off','half_day','sick','permit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_hours` decimal(8,2) NOT NULL DEFAULT '0.00',
  `overtime_hours` decimal(8,2) NOT NULL DEFAULT '0.00',
  `late_minutes` int NOT NULL DEFAULT '0',
  `early_leave_minutes` int NOT NULL DEFAULT '0',
  `source_log_id` bigint unsigned DEFAULT NULL,
  `check_in_time` datetime DEFAULT NULL,
  `check_out_time` datetime DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `attendance_dailies_employee_date_unique` (`employee_id`,`attendance_date`) USING BTREE,
  KEY `attendance_dailies_org_fk` (`organization_id`) USING BTREE,
  KEY `attendance_dailies_log_fk` (`source_log_id`) USING BTREE,
  KEY `attendance_dailies_status_index` (`status`) USING BTREE,
  KEY `attendance_dailies_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `attendance_dailies_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `attendance_dailies_log_fk` FOREIGN KEY (`source_log_id`) REFERENCES `attendance_logs` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `attendance_dailies_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `attendance_import_batches`
-- ----------------------------
DROP TABLE IF EXISTS `attendance_import_batches`;
CREATE TABLE `attendance_import_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `batch_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int DEFAULT NULL,
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `machine_source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_records` int DEFAULT '0',
  `processed_records` int DEFAULT '0',
  `failed_records` int DEFAULT '0',
  `status` enum('uploaded','processing','completed','failed','partial') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'uploaded',
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `errors` json DEFAULT NULL,
  `imported_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `attendance_import_batches_batch_code_unique` (`batch_code`) USING BTREE,
  KEY `attendance_import_batches_org_fk` (`organization_id`) USING BTREE,
  KEY `attendance_import_batches_status_index` (`status`) USING BTREE,
  KEY `attendance_import_batches_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `attendance_import_batches_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `attendance_logs`
-- ----------------------------
DROP TABLE IF EXISTS `attendance_logs`;
CREATE TABLE `attendance_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `attendance_date` date NOT NULL,
  `check_in` datetime DEFAULT NULL,
  `check_out` datetime DEFAULT NULL,
  `source` enum('manual','machine','import','api','mobile') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `source_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` json DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `attendance_logs_employee_date_index` (`employee_id`,`attendance_date`) USING BTREE,
  KEY `attendance_logs_org_fk` (`organization_id`) USING BTREE,
  KEY `attendance_logs_source_index` (`source`) USING BTREE,
  KEY `attendance_logs_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `attendance_logs_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `attendance_logs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `attendances`
-- ----------------------------
DROP TABLE IF EXISTS `attendances`;
CREATE TABLE `attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `attendance_daily_id` bigint unsigned DEFAULT NULL,
  `payroll_period_id` bigint unsigned DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('hadir','sakit','izin','alpha','cuti','dinas_luar','training','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hadir',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `attendance_employee_date_unique` (`employee_id`,`attendance_date`) USING BTREE,
  KEY `attendances_employee_id_index` (`employee_id`) USING BTREE,
  KEY `attendances_daily_fk_index` (`attendance_daily_id`) USING BTREE,
  KEY `attendances_period_index` (`payroll_period_id`) USING BTREE,
  KEY `attendances_status_index` (`status`) USING BTREE,
  KEY `attendances_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `attendances_daily_fk` FOREIGN KEY (`attendance_daily_id`) REFERENCES `attendance_dailies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `attendances_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `attendances_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `employee_attendance_logs`
-- ----------------------------
DROP TABLE IF EXISTS `employee_attendance_logs`;
CREATE TABLE `employee_attendance_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_attendance_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke employee_attendances (bisa NULL jika log mentah)',
  `employee_id` bigint unsigned NOT NULL COMMENT 'FK ke employees',
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `device_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke devices (jika ada master device)',
  `log_type` enum('check_in','check_out','break_start','break_end','overtime_start','overtime_end','manual_entry','correction','import') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tipe log',
  `log_time` datetime NOT NULL COMMENT 'Waktu kejadian',
  `method` enum('manual','rfid','fingerprint','face_recognition','gps','qr_code','import','mobile_app','api') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `location_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama lokasi, misal: Gerbang Utama',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `device_identifier` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID unik perangkat',
  `photo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Path foto selfie (jika ada)',
  `raw_data` json DEFAULT NULL COMMENT 'Data mentah dari perangkat/import',
  `is_processed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika sudah diproses ke employee_attendances',
  `processed_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_emp_att_log_att` (`employee_attendance_id`) USING BTREE,
  KEY `idx_emp_att_log_employee` (`employee_id`) USING BTREE,
  KEY `idx_emp_att_log_org` (`organization_id`) USING BTREE,
  KEY `idx_emp_att_log_time` (`log_time`) USING BTREE,
  KEY `idx_emp_att_log_type` (`log_type`) USING BTREE,
  KEY `idx_emp_att_log_processed` (`is_processed`) USING BTREE,
  KEY `fk_emp_att_log_created_by` (`created_by`) USING BTREE,
  CONSTRAINT `employee_attendance_logs_ibfk_1` FOREIGN KEY (`employee_attendance_id`) REFERENCES `employee_attendances` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_attendance_logs_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_attendance_logs_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_attendance_logs_ibfk_4` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `employee_attendances`
-- ----------------------------
DROP TABLE IF EXISTS `employee_attendances`;
CREATE TABLE `employee_attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL COMMENT 'FK ke employees',
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `work_schedule_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke work_schedules',
  `shift_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke shifts (jika menggunakan shift)',
  `attendance_date` date NOT NULL COMMENT 'Tanggal kehadiran',
  `check_in_time` datetime DEFAULT NULL COMMENT 'Waktu check-in aktual',
  `check_out_time` datetime DEFAULT NULL COMMENT 'Waktu check-out aktual',
  `scheduled_check_in` datetime DEFAULT NULL COMMENT 'Jadwal check-in seharusnya',
  `scheduled_check_out` datetime DEFAULT NULL COMMENT 'Jadwal check-out seharusnya',
  `work_duration_minutes` int unsigned NOT NULL DEFAULT '0' COMMENT 'Total durasi kerja (menit)',
  `late_minutes` int unsigned NOT NULL DEFAULT '0' COMMENT 'Keterlambatan (menit)',
  `early_leave_minutes` int unsigned NOT NULL DEFAULT '0' COMMENT 'Pulang cepat (menit)',
  `overtime_minutes` int unsigned NOT NULL DEFAULT '0' COMMENT 'Lembur (menit)',
  `break_duration_minutes` int unsigned NOT NULL DEFAULT '0' COMMENT 'Durasi istirahat (menit)',
  `status` enum('present','absent','late','early_leave','on_leave','sick','permission','holiday','weekend','remote','business_trip','half_day') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present' COMMENT 'Status kehadiran',
  `attendance_method` enum('manual','rfid','fingerprint','face_recognition','gps','qr_code','import','mobile_app') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual' COMMENT 'Metode absensi',
  `check_in_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lokasi check-in (jika GPS)',
  `check_out_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lokasi check-out (jika GPS)',
  `check_in_latitude` decimal(10,7) DEFAULT NULL,
  `check_in_longitude` decimal(10,7) DEFAULT NULL,
  `check_out_latitude` decimal(10,7) DEFAULT NULL,
  `check_out_longitude` decimal(10,7) DEFAULT NULL,
  `check_in_device_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID perangkat check-in',
  `check_out_device_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID perangkat check-out',
  `check_in_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leave_request_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke employee_leaves jika status on_leave',
  `permission_request_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke permission_requests',
  `is_corrected` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika sudah dikoreksi',
  `correction_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke attendance_corrections',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang approve',
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_employee_attendance_date` (`employee_id`,`attendance_date`) USING BTREE,
  KEY `idx_emp_att_org` (`organization_id`) USING BTREE,
  KEY `idx_emp_att_date` (`attendance_date`) USING BTREE,
  KEY `idx_emp_att_status` (`status`) USING BTREE,
  KEY `idx_emp_att_schedule` (`work_schedule_id`) USING BTREE,
  KEY `idx_emp_att_shift` (`shift_id`) USING BTREE,
  KEY `idx_emp_att_leave` (`leave_request_id`) USING BTREE,
  KEY `idx_emp_att_permission` (`permission_request_id`) USING BTREE,
  KEY `fk_emp_att_approved_by` (`approved_by`) USING BTREE,
  KEY `fk_emp_att_created_by` (`created_by`) USING BTREE,
  KEY `fk_emp_att_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `employee_attendances_ibfk_1` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_attendances_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_attendances_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_attendances_ibfk_4` FOREIGN KEY (`leave_request_id`) REFERENCES `employee_leaves` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_attendances_ibfk_5` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_attendances_ibfk_6` FOREIGN KEY (`permission_request_id`) REFERENCES `permission_requests` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_attendances_ibfk_7` FOREIGN KEY (`work_schedule_id`) REFERENCES `work_schedules` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_attendances_ibfk_8` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employee_attendances_ibfk_9` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `employee_leaves`
-- ----------------------------
DROP TABLE IF EXISTS `employee_leaves`;
CREATE TABLE `employee_leaves` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `leave_type_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` decimal(8,2) NOT NULL DEFAULT '0.00',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  KEY `employee_leaves_employee_fk` (`employee_id`) USING BTREE,
  KEY `employee_leaves_type_fk` (`leave_type_id`) USING BTREE,
  KEY `employee_leaves_status_index` (`status`) USING BTREE,
  KEY `employee_leaves_date_range_index` (`start_date`,`end_date`) USING BTREE,
  KEY `employee_leaves_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `employee_leaves_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_leaves_type_fk` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `employee_shifts`
-- ----------------------------
DROP TABLE IF EXISTS `employee_shifts`;
CREATE TABLE `employee_shifts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `shift_id` bigint unsigned NOT NULL,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `day_of_week` tinyint DEFAULT NULL,
  `is_rotational` tinyint(1) DEFAULT '0',
  `rotation_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `employee_shift_effective_unique` (`employee_id`,`effective_date`,`shift_id`) USING BTREE,
  KEY `employee_shifts_employee_id_index` (`employee_id`) USING BTREE,
  KEY `employee_shifts_shift_id_index` (`shift_id`) USING BTREE,
  KEY `employee_shifts_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `employee_shifts_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_shifts_shift_fk` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `holidays`
-- ----------------------------
DROP TABLE IF EXISTS `holidays`;
CREATE TABLE `holidays` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('national','special','company','religious') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'national',
  `is_recurring` tinyint(1) DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `holidays_org_date_type_unique` (`organization_id`,`date`,`type`) USING BTREE,
  KEY `holidays_date_index` (`date`) USING BTREE,
  KEY `holidays_type_index` (`type`) USING BTREE,
  KEY `holidays_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `holidays_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `leave_balances`
-- ----------------------------
DROP TABLE IF EXISTS `leave_balances`;
CREATE TABLE `leave_balances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `leave_type_id` bigint unsigned NOT NULL,
  `year` int NOT NULL,
  `allocated_days` decimal(8,2) NOT NULL DEFAULT '0.00',
  `used_days` decimal(8,2) NOT NULL DEFAULT '0.00',
  `remaining_days` decimal(8,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `leave_balances_unique` (`employee_id`,`leave_type_id`,`year`) USING BTREE,
  KEY `leave_balances_type_fk` (`leave_type_id`) USING BTREE,
  KEY `leave_balances_year_index` (`year`) USING BTREE,
  KEY `leave_balances_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `leave_balances_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `leave_balances_type_fk` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `leave_types`
-- ----------------------------
DROP TABLE IF EXISTS `leave_types`;
CREATE TABLE `leave_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_days` decimal(8,2) NOT NULL DEFAULT '0.00',
  `max_consecutive_days` int DEFAULT NULL,
  `min_working_days_before` int DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT '1',
  `requires_attachment` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `leave_types_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `leave_types_is_active_index` (`is_active`) USING BTREE,
  KEY `leave_types_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `leave_types_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `overtimes`
-- ----------------------------
DROP TABLE IF EXISTS `overtimes`;
CREATE TABLE `overtimes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `attendance_daily_id` bigint unsigned DEFAULT NULL,
  `overtime_date` date NOT NULL,
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `hours` decimal(8,2) NOT NULL DEFAULT '0.00',
  `hourly_rate` decimal(15,2) NOT NULL DEFAULT '0.00',
  `rate_multiplier` decimal(8,4) NOT NULL DEFAULT '1.0000',
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','submitted','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint unsigned DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `overtimes_org_idx` (`organization_id`) USING BTREE,
  KEY `overtimes_employee_idx` (`employee_id`) USING BTREE,
  KEY `overtimes_daily_idx` (`attendance_daily_id`) USING BTREE,
  KEY `overtimes_date_idx` (`overtime_date`) USING BTREE,
  KEY `overtimes_status_idx` (`status`) USING BTREE,
  KEY `overtimes_employee_date_idx` (`employee_id`,`overtime_date`) USING BTREE,
  KEY `overtimes_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `overtimes_daily_fk` FOREIGN KEY (`attendance_daily_id`) REFERENCES `attendance_dailies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `overtimes_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `overtimes_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `shift_swaps`
-- ----------------------------
DROP TABLE IF EXISTS `shift_swaps`;
CREATE TABLE `shift_swaps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `employee_id_a` bigint unsigned NOT NULL,
  `original_shift_a` bigint unsigned NOT NULL,
  `target_shift_a` bigint unsigned NOT NULL,
  `employee_id_b` bigint unsigned NOT NULL,
  `original_shift_b` bigint unsigned NOT NULL,
  `target_shift_b` bigint unsigned NOT NULL,
  `status` enum('pending','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint unsigned DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `swap_per_date_unique` (`date`,`employee_id_a`) USING BTREE,
  UNIQUE KEY `swap_per_date_b_unique` (`date`,`employee_id_b`) USING BTREE,
  KEY `shift_swaps_employee_id_a_index` (`employee_id_a`) USING BTREE,
  KEY `shift_swaps_employee_id_b_index` (`employee_id_b`) USING BTREE,
  KEY `shift_swaps_status_index` (`status`) USING BTREE,
  KEY `shift_swaps_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `shift_swaps_org_fk` (`organization_id`) USING BTREE,
  KEY `shift_swaps_orig_shift_a_fk` (`original_shift_a`) USING BTREE,
  KEY `shift_swaps_target_shift_a_fk` (`target_shift_a`) USING BTREE,
  KEY `shift_swaps_orig_shift_b_fk` (`original_shift_b`) USING BTREE,
  KEY `shift_swaps_target_shift_b_fk` (`target_shift_b`) USING BTREE,
  CONSTRAINT `shift_swaps_employee_a_fk` FOREIGN KEY (`employee_id_a`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `shift_swaps_employee_b_fk` FOREIGN KEY (`employee_id_b`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `shift_swaps_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `shift_swaps_orig_shift_a_fk` FOREIGN KEY (`original_shift_a`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `shift_swaps_orig_shift_b_fk` FOREIGN KEY (`original_shift_b`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `shift_swaps_target_shift_a_fk` FOREIGN KEY (`target_shift_a`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `shift_swaps_target_shift_b_fk` FOREIGN KEY (`target_shift_b`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `shifts`
-- ----------------------------
DROP TABLE IF EXISTS `shifts`;
CREATE TABLE `shifts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `crosses_midnight` tinyint(1) DEFAULT NULL,
  `grace_period_start` int DEFAULT '15',
  `grace_period_end` int DEFAULT '15',
  `break_start` time DEFAULT NULL,
  `break_end` time DEFAULT NULL,
  `break_duration` int DEFAULT '0',
  `night_shift_start` time DEFAULT NULL,
  `overtime_min_threshold` int DEFAULT '60',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `shifts_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `shifts_is_active_index` (`is_active`) USING BTREE,
  KEY `shifts_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `shifts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_attendances`
-- ----------------------------
DROP TABLE IF EXISTS `student_attendances`;
CREATE TABLE `student_attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `schedule_id` bigint unsigned DEFAULT NULL,
  `classroom_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `late_minutes` int NOT NULL DEFAULT '0',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `verified_by` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `attendances_student_schedule_date_unique` (`student_id`,`schedule_id`,`date`) USING BTREE,
  KEY `attendances_student_index` (`student_id`) USING BTREE,
  KEY `attendances_schedule_index` (`schedule_id`) USING BTREE,
  KEY `attendances_classroom_index` (`classroom_id`) USING BTREE,
  KEY `attendances_date_index` (`date`) USING BTREE,
  KEY `attendances_status_index` (`status`) USING BTREE,
  KEY `attendances_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `attendances_created_by_index` (`created_by`) USING BTREE,
  KEY `attendances_updated_by_index` (`updated_by`) USING BTREE,
  KEY `attendances_verified_by_foreign` (`verified_by`) USING BTREE,
  CONSTRAINT `attendances_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `attendances_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attendances_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `work_calendar_holidays`
-- ----------------------------
DROP TABLE IF EXISTS `work_calendar_holidays`;
CREATE TABLE `work_calendar_holidays` (
  `work_calendar_id` bigint unsigned NOT NULL,
  `holiday_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`work_calendar_id`,`holiday_id`) USING BTREE,
  KEY `fk_wch_holiday` (`holiday_id`) USING BTREE,
  CONSTRAINT `work_calendar_holidays_ibfk_1` FOREIGN KEY (`work_calendar_id`) REFERENCES `work_calendars` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `work_calendar_holidays_ibfk_2` FOREIGN KEY (`holiday_id`) REFERENCES `holidays` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `work_calendars`
-- ----------------------------
DROP TABLE IF EXISTS `work_calendars`;
CREATE TABLE `work_calendars` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama kalender, misal: Kalender Guru, Kalender Staff',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `work_days` json DEFAULT NULL COMMENT 'Hari kerja (misal: [1,2,3,4,5])',
  `work_start_time` time DEFAULT NULL,
  `work_end_time` time DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_work_calendars_code` (`organization_id`,`code`) USING BTREE,
  KEY `idx_work_calendars_org` (`organization_id`) USING BTREE,
  KEY `idx_work_calendars_active` (`is_active`) USING BTREE,
  KEY `fk_work_calendars_created_by` (`created_by`) USING BTREE,
  KEY `fk_work_calendars_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `work_calendars_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `work_calendars_ibfk_2` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `work_calendars_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `work_schedules`
-- ----------------------------
DROP TABLE IF EXISTS `work_schedules`;
CREATE TABLE `work_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations, NULL untuk global',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama jadwal, misal: Shift Pagi, Normal Office',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode unik jadwal, misal: SHIFT_PAGI',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `day_type` enum('weekday','weekend','holiday','all') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'weekday' COMMENT 'Tipe hari berlaku',
  `check_in_time` time NOT NULL COMMENT 'Jam masuk yang diharapkan',
  `check_out_time` time NOT NULL COMMENT 'Jam pulang yang diharapkan',
  `break_start_time` time DEFAULT NULL COMMENT 'Jam mulai istirahat',
  `break_end_time` time DEFAULT NULL COMMENT 'Jam selesai istirahat',
  `late_tolerance_minutes` int unsigned NOT NULL DEFAULT '0' COMMENT 'Toleransi keterlambatan (menit)',
  `early_leave_tolerance_minutes` int unsigned NOT NULL DEFAULT '0' COMMENT 'Toleransi pulang cepat (menit)',
  `overtime_threshold_minutes` int unsigned NOT NULL DEFAULT '30' COMMENT 'Ambang batas overtime (menit)',
  `is_cross_day` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika shift melewati tengah malam',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `effective_from` date DEFAULT NULL COMMENT 'Berlaku mulai tanggal',
  `effective_until` date DEFAULT NULL COMMENT 'Berlaku sampai tanggal',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_work_schedules_code` (`code`,`organization_id`) USING BTREE,
  KEY `idx_work_schedules_org` (`organization_id`) USING BTREE,
  KEY `idx_work_schedules_active` (`is_active`) USING BTREE,
  KEY `fk_work_schedules_created_by` (`created_by`) USING BTREE,
  KEY `fk_work_schedules_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `work_schedules_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `work_schedules_ibfk_2` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `work_schedules_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
