/*
 * Table Group: shuttle-service
 * Total Tables: 7
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `drivers`
-- ----------------------------
DROP TABLE IF EXISTS `drivers`;
CREATE TABLE `drivers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `sim_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'B1',
  `sim_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sim_expiry_date` date NOT NULL,
  `emergency_contact_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `drivers_emp_unique` (`employee_id`) USING BTREE,
  UNIQUE KEY `drivers_sim_unique` (`sim_number`) USING BTREE,
  KEY `drivers_org_fk` (`organization_id`) USING BTREE,
  CONSTRAINT `drivers_emp_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `drivers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `gps_telemetry_logs`
-- ----------------------------
DROP TABLE IF EXISTS `gps_telemetry_logs`;
CREATE TABLE `gps_telemetry_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint unsigned NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `speed_kmh` decimal(5,2) NOT NULL DEFAULT '0.00',
  `heading_degrees` smallint unsigned DEFAULT NULL,
  `is_engine_on` tinyint(1) NOT NULL DEFAULT '1',
  `odometer_km` decimal(10,2) DEFAULT NULL,
  `recorded_at` timestamp NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `gps_vehicle_time_index` (`vehicle_id`,`recorded_at` DESC) USING BTREE,
  CONSTRAINT `gps_vehicle_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `student_transport_subscriptions`
-- ----------------------------
DROP TABLE IF EXISTS `student_transport_subscriptions`;
CREATE TABLE `student_transport_subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `transport_route_id` bigint unsigned NOT NULL,
  `transport_stop_id` bigint unsigned DEFAULT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `service_type` enum('round_trip','pickup_only','dropoff_only') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'round_trip',
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `monthly_fee` decimal(15,2) NOT NULL,
  `status` enum('active','suspended','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `sub_student_index` (`student_id`) USING BTREE,
  KEY `sub_route_index` (`transport_route_id`) USING BTREE,
  KEY `sub_stop_index` (`transport_stop_id`) USING BTREE,
  KEY `sub_year_index` (`academic_year_id`) USING BTREE,
  CONSTRAINT `sub_route_fk` FOREIGN KEY (`transport_route_id`) REFERENCES `transport_routes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `sub_stop_fk` FOREIGN KEY (`transport_stop_id`) REFERENCES `transport_stops` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sub_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sub_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `transport_routes`
-- ----------------------------
DROP TABLE IF EXISTS `transport_routes`;
CREATE TABLE `transport_routes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `vehicle_id` bigint unsigned DEFAULT NULL,
  `driver_id` bigint unsigned DEFAULT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_type` enum('pickup','dropoff','both') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'both',
  `morning_departure_time` time DEFAULT NULL,
  `afternoon_departure_time` time DEFAULT NULL,
  `monthly_fare` decimal(15,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `routes_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `routes_vehicle_index` (`vehicle_id`) USING BTREE,
  KEY `routes_driver_index` (`driver_id`) USING BTREE,
  KEY `routes_org_fk` (`organization_id`) USING BTREE,
  CONSTRAINT `routes_driver_fk` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `routes_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `routes_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `routes_vehicle_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `transport_stops`
-- ----------------------------
DROP TABLE IF EXISTS `transport_stops`;
CREATE TABLE `transport_stops` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transport_route_id` bigint unsigned NOT NULL,
  `stop_order` tinyint unsigned NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `estimated_pickup_time` time DEFAULT NULL,
  `estimated_dropoff_time` time DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `stops_route_order_unique` (`transport_route_id`,`stop_order`) USING BTREE,
  CONSTRAINT `stops_route_fk` FOREIGN KEY (`transport_route_id`) REFERENCES `transport_routes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `vehicle_maintenance_logs`
-- ----------------------------
DROP TABLE IF EXISTS `vehicle_maintenance_logs`;
CREATE TABLE `vehicle_maintenance_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `service_date` date NOT NULL,
  `odometer_reading` int unsigned DEFAULT NULL,
  `maintenance_type` enum('routine_service','oil_change','tire_replacement','repair','kir_inspection','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `next_service_due_date` date DEFAULT NULL,
  `next_service_due_odometer` int unsigned DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `veh_maint_vehicle_index` (`vehicle_id`) USING BTREE,
  KEY `veh_maint_vendor_index` (`vendor_id`) USING BTREE,
  KEY `veh_maint_journal_index` (`journal_entry_id`) USING BTREE,
  CONSTRAINT `veh_maint_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `veh_maint_vehicle_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `veh_maint_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `vehicles`
-- ----------------------------
DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE `vehicles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned DEFAULT NULL,
  `plate_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_type` enum('bus','minibus','van','car','motorcycle') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bus',
  `brand` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seat_capacity` tinyint unsigned NOT NULL DEFAULT '20',
  `gps_device_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stnk_expiry_date` date DEFAULT NULL,
  `kir_expiry_date` date DEFAULT NULL,
  `insurance_expiry_date` date DEFAULT NULL,
  `status` enum('active','maintenance','inactive','disposed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `vehicles_plate_unique` (`plate_number`) USING BTREE,
  KEY `vehicles_org_index` (`organization_id`) USING BTREE,
  KEY `vehicles_school_index` (`school_id`) USING BTREE,
  KEY `vehicles_gps_device_index` (`gps_device_id`) USING BTREE,
  KEY `vehicles_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `vehicles_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `vehicles_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
