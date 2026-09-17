-- ==============================================================================
-- ENTERPRISE MODULE 02: UNIVERSAL APPROVAL WORKFLOW ENGINE
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 1. Master Definisi Alur Kerja (Workflow Definitions)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `workflow_definitions`;
CREATE TABLE `workflow_definitions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `wf_def_org_code_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  INDEX `wf_def_school_index`(`school_id` ASC) USING BTREE,
  INDEX `wf_def_module_index`(`module` ASC) USING BTREE,
  INDEX `wf_def_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `wf_def_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `wf_def_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Langkah-Langkah Persetujuan (Workflow Steps)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `workflow_steps`;
CREATE TABLE `workflow_steps` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `workflow_definition_id` bigint UNSIGNED NOT NULL,
  `step_order` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `approver_type` enum('role','user','supervisor','dept_head','amount_threshold') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'role',
  `min_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `max_amount` decimal(15, 2) NULL DEFAULT NULL,
  `action_type` enum('approve_reject','review','acknowledge') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approve_reject',
  `auto_notify` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `wf_step_order_unique`(`workflow_definition_id` ASC, `step_order` ASC) USING BTREE,
  CONSTRAINT `wf_step_def_fk` FOREIGN KEY (`workflow_definition_id`) REFERENCES `workflow_definitions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 3. Penanggung Jawab Approver (Workflow Approvers)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `workflow_approvers`;
CREATE TABLE `workflow_approvers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `workflow_step_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `wf_appr_step_index`(`workflow_step_id` ASC) USING BTREE,
  INDEX `wf_appr_role_index`(`role_id` ASC) USING BTREE,
  INDEX `wf_appr_user_index`(`user_id` ASC) USING BTREE,
  CONSTRAINT `wf_appr_step_fk` FOREIGN KEY (`workflow_step_id`) REFERENCES `workflow_steps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wf_appr_role_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wf_appr_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 4. Permohonan Persetujuan Aktif (Approval Requests)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `approval_requests`;
CREATE TABLE `approval_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `workflow_definition_id` bigint UNSIGNED NOT NULL,
  `current_step_id` bigint UNSIGNED NULL DEFAULT NULL,
  `requestable_type` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requestable_id` bigint UNSIGNED NOT NULL,
  `document_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `requester_id` bigint UNSIGNED NOT NULL,
  `status` enum('draft','pending','approved','rejected','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `appr_req_org_index`(`organization_id` ASC) USING BTREE,
  INDEX `appr_req_wf_index`(`workflow_definition_id` ASC) USING BTREE,
  INDEX `appr_req_step_index`(`current_step_id` ASC) USING BTREE,
  INDEX `appr_req_morph_index`(`requestable_type` ASC, `requestable_id` ASC) USING BTREE,
  INDEX `appr_req_requester_index`(`requester_id` ASC) USING BTREE,
  INDEX `appr_req_status_index`(`status` ASC) USING BTREE,
  CONSTRAINT `appr_req_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `appr_req_wf_fk` FOREIGN KEY (`workflow_definition_id`) REFERENCES `workflow_definitions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `appr_req_step_fk` FOREIGN KEY (`current_step_id`) REFERENCES `workflow_steps` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `appr_req_requester_fk` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 5. Riwayat Jejak Persetujuan (Approval Histories)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `approval_histories`;
CREATE TABLE `approval_histories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `approval_request_id` bigint UNSIGNED NOT NULL,
  `workflow_step_id` bigint UNSIGNED NOT NULL,
  `approver_id` bigint UNSIGNED NOT NULL,
  `action` enum('approved','rejected','revised','delegated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `signature_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `appr_hist_req_index`(`approval_request_id` ASC) USING BTREE,
  INDEX `appr_hist_step_index`(`workflow_step_id` ASC) USING BTREE,
  INDEX `appr_hist_user_index`(`approver_id` ASC) USING BTREE,
  CONSTRAINT `appr_hist_req_fk` FOREIGN KEY (`approval_request_id`) REFERENCES `approval_requests` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `appr_hist_step_fk` FOREIGN KEY (`workflow_step_id`) REFERENCES `workflow_steps` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `appr_hist_approver_fk` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
