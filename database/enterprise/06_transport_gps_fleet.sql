-- ==============================================================================
-- ENTERPRISE MODULE 06: TRANSPORTATION, BUS FLEET & GPS TELEMETRY
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 1. Master Kendaraan Armada (Vehicles)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE `vehicles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `plate_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_type` enum('bus','minibus','van','car','motorcycle') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bus',
  `brand` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `model` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `seat_capacity` tinyint UNSIGNED NOT NULL DEFAULT 20,
  `gps_device_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `stnk_expiry_date` date NULL DEFAULT NULL,
  `kir_expiry_date` date NULL DEFAULT NULL,
  `insurance_expiry_date` date NULL DEFAULT NULL,
  `status` enum('active','maintenance','inactive','disposed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `vehicles_plate_unique`(`plate_number` ASC) USING BTREE,
  INDEX `vehicles_org_index`(`organization_id` ASC) USING BTREE,
  INDEX `vehicles_school_index`(`school_id` ASC) USING BTREE,
  INDEX `vehicles_gps_device_index`(`gps_device_id` ASC) USING BTREE,
  INDEX `vehicles_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `vehicles_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `vehicles_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Master Supir / Pengemudi (Drivers)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `drivers`;
CREATE TABLE `drivers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `sim_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'B1',
  `sim_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sim_expiry_date` date NOT NULL,
  `emergency_contact_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `emergency_contact_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `drivers_emp_unique`(`employee_id` ASC) USING BTREE,
  UNIQUE INDEX `drivers_sim_unique`(`sim_number` ASC) USING BTREE,
  CONSTRAINT `drivers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `drivers_emp_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 3. Rute Antar Jemput (Transport Routes)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `transport_routes`;
CREATE TABLE `transport_routes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `vehicle_id` bigint UNSIGNED NULL DEFAULT NULL,
  `driver_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_type` enum('pickup','dropoff','both') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'both',
  `morning_departure_time` time NULL DEFAULT NULL,
  `afternoon_departure_time` time NULL DEFAULT NULL,
  `monthly_fare` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `routes_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `routes_vehicle_index`(`vehicle_id` ASC) USING BTREE,
  INDEX `routes_driver_index`(`driver_id` ASC) USING BTREE,
  CONSTRAINT `routes_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `routes_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `routes_vehicle_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `routes_driver_fk` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 4. Titik Halte / Pemberhentian (Transport Stops)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `transport_stops`;
CREATE TABLE `transport_stops` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `transport_route_id` bigint UNSIGNED NOT NULL,
  `stop_order` tinyint UNSIGNED NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `latitude` decimal(10, 8) NULL DEFAULT NULL,
  `longitude` decimal(11, 8) NULL DEFAULT NULL,
  `estimated_pickup_time` time NULL DEFAULT NULL,
  `estimated_dropoff_time` time NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `stops_route_order_unique`(`transport_route_id` ASC, `stop_order` ASC) USING BTREE,
  CONSTRAINT `stops_route_fk` FOREIGN KEY (`transport_route_id`) REFERENCES `transport_routes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 5. Langganan Antar-Jemput Siswa (Student Transport Subscriptions)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `student_transport_subscriptions`;
CREATE TABLE `student_transport_subscriptions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `transport_route_id` bigint UNSIGNED NOT NULL,
  `transport_stop_id` bigint UNSIGNED NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `service_type` enum('round_trip','pickup_only','dropoff_only') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'round_trip',
  `start_date` date NOT NULL,
  `end_date` date NULL DEFAULT NULL,
  `monthly_fee` decimal(15, 2) NOT NULL,
  `status` enum('active','suspended','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sub_student_index`(`student_id` ASC) USING BTREE,
  INDEX `sub_route_index`(`transport_route_id` ASC) USING BTREE,
  INDEX `sub_stop_index`(`transport_stop_id` ASC) USING BTREE,
  INDEX `sub_year_index`(`academic_year_id` ASC) USING BTREE,
  CONSTRAINT `sub_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sub_route_fk` FOREIGN KEY (`transport_route_id`) REFERENCES `transport_routes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `sub_stop_fk` FOREIGN KEY (`transport_stop_id`) REFERENCES `transport_stops` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sub_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 6. Log GPS Real-Time Armada (GPS Telemetry Logs)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `gps_telemetry_logs`;
CREATE TABLE `gps_telemetry_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `latitude` decimal(10, 8) NOT NULL,
  `longitude` decimal(11, 8) NOT NULL,
  `speed_kmh` decimal(5, 2) NOT NULL DEFAULT 0.00,
  `heading_degrees` smallint UNSIGNED NULL DEFAULT NULL,
  `is_engine_on` tinyint(1) NOT NULL DEFAULT 1,
  `odometer_km` decimal(10, 2) NULL DEFAULT NULL,
  `recorded_at` timestamp NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `gps_vehicle_time_index`(`vehicle_id` ASC, `recorded_at` DESC) USING BTREE,
  CONSTRAINT `gps_vehicle_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 7. Riwayat Servis & Perawatan Armada (Vehicle Maintenance Logs)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `vehicle_maintenance_logs`;
CREATE TABLE `vehicle_maintenance_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `service_date` date NOT NULL,
  `odometer_reading` int UNSIGNED NULL DEFAULT NULL,
  `maintenance_type` enum('routine_service','oil_change','tire_replacement','repair','kir_inspection','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `next_service_due_date` date NULL DEFAULT NULL,
  `next_service_due_odometer` int UNSIGNED NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `veh_maint_vehicle_index`(`vehicle_id` ASC) USING BTREE,
  INDEX `veh_maint_vendor_index`(`vendor_id` ASC) USING BTREE,
  INDEX `veh_maint_journal_index`(`journal_entry_id` ASC) USING BTREE,
  CONSTRAINT `veh_maint_vehicle_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `veh_maint_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `veh_maint_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
