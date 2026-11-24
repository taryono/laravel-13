SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for student_families
-- ----------------------------
DROP TABLE IF EXISTS `student_families`;
CREATE TABLE `student_families` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `family_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary_parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_student_families_unique_code`(`organization_id` ASC, `family_code` ASC) USING BTREE,
  INDEX `idx_student_families_org`(`organization_id` ASC) USING BTREE,
  INDEX `idx_student_families_name`(`organization_id` ASC, `family_name` ASC) USING BTREE,
  INDEX `idx_student_families_primary_parent`(`primary_parent_id` ASC) USING BTREE,
  INDEX `idx_student_families_deleted_at`(`deleted_at` ASC) USING BTREE,
  INDEX `idx_student_families_created_by`(`created_by` ASC) USING BTREE,
  INDEX `idx_student_families_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `sf_organization_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sf_primary_parent_fk` FOREIGN KEY (`primary_parent_id`) REFERENCES `parents` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sf_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sf_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for student_family_members
-- ----------------------------
DROP TABLE IF EXISTS `student_family_members`;
CREATE TABLE `student_family_members` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `student_family_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `applicant_id` bigint UNSIGNED NULL DEFAULT NULL,
  `sibling_order` smallint UNSIGNED NULL DEFAULT NULL,
  `relation` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `joined_at` date NULL DEFAULT NULL,
  `left_at` date NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_student_family_members_unique_student`(`student_family_id` ASC, `student_id` ASC) USING BTREE,
  UNIQUE INDEX `idx_student_family_members_unique_applicant`(`student_family_id` ASC, `applicant_id` ASC) USING BTREE,
  INDEX `idx_student_family_members_org`(`organization_id` ASC) USING BTREE,
  INDEX `idx_student_family_members_family_active`(`student_family_id` ASC, `is_active` ASC) USING BTREE,
  INDEX `idx_student_family_members_student`(`student_id` ASC) USING BTREE,
  INDEX `idx_student_family_members_applicant`(`applicant_id` ASC) USING BTREE,
  INDEX `idx_student_family_members_deleted_at`(`deleted_at` ASC) USING BTREE,
  INDEX `idx_student_family_members_created_by`(`created_by` ASC) USING BTREE,
  INDEX `idx_student_family_members_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `sfm_organization_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sfm_family_fk` FOREIGN KEY (`student_family_id`) REFERENCES `student_families` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sfm_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sfm_applicant_fk` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sfm_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sfm_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for sibling_discount_programs
-- ----------------------------
DROP TABLE IF EXISTS `sibling_discount_programs`;
CREATE TABLE `sibling_discount_programs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `min_sibling_count` smallint UNSIGNED NOT NULL DEFAULT 2,
  `calculation_basis` enum('percentage','fixed_amount') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
  `allocation_method` enum('all_members','selected_members','tiered') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all_members',
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_sibling_discount_programs_unique_code`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `idx_sibling_discount_programs_org_school`(`organization_id` ASC, `school_id` ASC, `is_active` ASC) USING BTREE,
  INDEX `idx_sibling_discount_programs_school`(`school_id` ASC) USING BTREE,
  INDEX `idx_sibling_discount_programs_deleted_at`(`deleted_at` ASC) USING BTREE,
  INDEX `idx_sibling_discount_programs_created_by`(`created_by` ASC) USING BTREE,
  INDEX `idx_sibling_discount_programs_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `sdp_organization_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sdp_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sdp_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sdp_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for sibling_discount_rules
-- ----------------------------
DROP TABLE IF EXISTS `sibling_discount_rules`;
CREATE TABLE `sibling_discount_rules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sibling_discount_program_id` bigint UNSIGNED NOT NULL,
  `min_sibling_count` smallint UNSIGNED NOT NULL,
  `max_sibling_count` smallint UNSIGNED NULL DEFAULT NULL,
  `discount_type` enum('percentage','fixed_amount') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(18, 2) NOT NULL,
  `priority` int UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_sibling_discount_rules_program`(`sibling_discount_program_id` ASC, `min_sibling_count` ASC, `max_sibling_count` ASC) USING BTREE,
  INDEX `idx_sibling_discount_rules_deleted_at`(`deleted_at` ASC) USING BTREE,
  INDEX `idx_sibling_discount_rules_created_by`(`created_by` ASC) USING BTREE,
  INDEX `idx_sibling_discount_rules_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `sdr_program_fk` FOREIGN KEY (`sibling_discount_program_id`) REFERENCES `sibling_discount_programs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sdr_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sdr_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for sibling_discount_allocations
-- ----------------------------
DROP TABLE IF EXISTS `sibling_discount_allocations`;
CREATE TABLE `sibling_discount_allocations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sibling_discount_rule_id` bigint UNSIGNED NOT NULL,
  `target_type` enum('all_members','second_onward','specific_order','youngest','oldest') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all_members',
  `sibling_order_from` smallint UNSIGNED NULL DEFAULT NULL,
  `sibling_order_to` smallint UNSIGNED NULL DEFAULT NULL,
  `discount_type` enum('percentage','fixed_amount') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(18, 2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_sibling_discount_allocations_rule`(`sibling_discount_rule_id` ASC) USING BTREE,
  INDEX `idx_sibling_discount_allocations_deleted_at`(`deleted_at` ASC) USING BTREE,
  INDEX `idx_sibling_discount_allocations_created_by`(`created_by` ASC) USING BTREE,
  INDEX `idx_sibling_discount_allocations_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `sda_rule_fk` FOREIGN KEY (`sibling_discount_rule_id`) REFERENCES `sibling_discount_rules` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sda_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sda_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for sibling_discount_eligibilities
-- ----------------------------
DROP TABLE IF EXISTS `sibling_discount_eligibilities`;
CREATE TABLE `sibling_discount_eligibilities` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `student_family_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NULL DEFAULT NULL,
  `applicant_id` bigint UNSIGNED NULL DEFAULT NULL,
  `sibling_count` smallint UNSIGNED NOT NULL,
  `sibling_order` smallint UNSIGNED NOT NULL,
  `is_eligible` tinyint(1) NOT NULL DEFAULT 0,
  `discount_program_id` bigint UNSIGNED NULL DEFAULT NULL,
  `snapshot_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_sibling_discount_eligibilities_unique_student`(`academic_year_id` ASC, `student_family_id` ASC, `student_id` ASC) USING BTREE,
  INDEX `idx_sibling_discount_eligibilities_org`(`organization_id` ASC) USING BTREE,
  INDEX `idx_sibling_discount_eligibilities_family_year`(`student_family_id` ASC, `academic_year_id` ASC) USING BTREE,
  INDEX `idx_sibling_discount_eligibilities_status`(`academic_year_id` ASC, `is_eligible` ASC) USING BTREE,
  INDEX `idx_sibling_discount_eligibilities_student`(`student_id` ASC) USING BTREE,
  INDEX `idx_sibling_discount_eligibilities_applicant`(`applicant_id` ASC) USING BTREE,
  INDEX `idx_sibling_discount_eligibilities_program`(`discount_program_id` ASC) USING BTREE,
  INDEX `idx_sibling_discount_eligibilities_deleted_at`(`deleted_at` ASC) USING BTREE,
  INDEX `idx_sibling_discount_eligibilities_created_by`(`created_by` ASC) USING BTREE,
  INDEX `idx_sibling_discount_eligibilities_updated_by`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `sde_organization_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sde_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sde_family_fk` FOREIGN KEY (`student_family_id`) REFERENCES `student_families` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sde_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sde_applicant_fk` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sde_program_fk` FOREIGN KEY (`discount_program_id`) REFERENCES `sibling_discount_programs` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sde_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `sde_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;