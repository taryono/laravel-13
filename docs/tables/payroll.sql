/*
 * Table Group: payroll
 * Total Tables: 23
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `bonuses`
-- ----------------------------
DROP TABLE IF EXISTS `bonuses`;
CREATE TABLE `bonuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `bonus_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bonus_type` enum('performance','thr','bonus_year_end','project_bonus','referral','attendance','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payroll_period_id` bigint unsigned DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `bonuses_org_code_unique` (`organization_id`,`bonus_code`) USING BTREE,
  KEY `bonuses_employee_id_index` (`employee_id`) USING BTREE,
  KEY `bonuses_payroll_period_id_index` (`payroll_period_id`) USING BTREE,
  KEY `bonuses_bonus_type_index` (`bonus_type`) USING BTREE,
  KEY `bonuses_is_paid_index` (`is_paid`) USING BTREE,
  KEY `bonuses_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `bonuses_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `bonuses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `bonuses_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `deduction_categories`
-- ----------------------------
DROP TABLE IF EXISTS `deduction_categories`;
CREATE TABLE `deduction_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_type` enum('mandatory','voluntary','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `deduction_group` enum('insurance','tax','saving','loan','penalty','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `calculation_type` enum('fixed','percentage','sliding','custom') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `default_amount` decimal(15,2) DEFAULT NULL,
  `default_percentage` decimal(5,2) DEFAULT NULL,
  `min_amount` decimal(15,2) DEFAULT NULL,
  `max_amount` decimal(15,2) DEFAULT NULL,
  `is_based_on_salary` tinyint(1) NOT NULL DEFAULT '0',
  `is_taxable` tinyint(1) NOT NULL DEFAULT '0',
  `tax_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `deduction_categories_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `deduction_categories_is_active_index` (`is_active`) USING BTREE,
  KEY `deduction_categories_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `deduction_categories_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `deductions`
-- ----------------------------
DROP TABLE IF EXISTS `deductions`;
CREATE TABLE `deductions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `payroll_period_id` bigint unsigned DEFAULT NULL,
  `deduction_category_id` bigint unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_recurring` tinyint(1) NOT NULL DEFAULT '0',
  `recurring_until` date DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `deductions_employee_id_index` (`employee_id`) USING BTREE,
  KEY `deductions_payroll_period_id_index` (`payroll_period_id`) USING BTREE,
  KEY `deductions_deduction_category_id_index` (`deduction_category_id`) USING BTREE,
  KEY `deductions_organization_id_index` (`organization_id`) USING BTREE,
  KEY `deductions_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `deductions_category_fk` FOREIGN KEY (`deduction_category_id`) REFERENCES `deduction_categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `deductions_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `deductions_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `deductions_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `employee_bonuses`
-- ----------------------------
DROP TABLE IF EXISTS `employee_bonuses`;
CREATE TABLE `employee_bonuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `bonus_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bonus_date` date NOT NULL,
  `bonus_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payroll_detail_id` bigint unsigned DEFAULT NULL,
  `status` enum('draft','approved','paid','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `employee_bonuses_org_number_unique` (`organization_id`,`bonus_number`) USING BTREE,
  KEY `employee_bonuses_employee_fk` (`employee_id`) USING BTREE,
  KEY `employee_bonuses_payroll_fk` (`payroll_detail_id`) USING BTREE,
  KEY `employee_bonuses_organization_id_index` (`organization_id`) USING BTREE,
  KEY `employee_bonuses_status_index` (`status`) USING BTREE,
  KEY `employee_bonuses_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `employee_bonuses_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_bonuses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_bonuses_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `employee_deductions`
-- ----------------------------
DROP TABLE IF EXISTS `employee_deductions`;
CREATE TABLE `employee_deductions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `deduction_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deduction_date` date NOT NULL,
  `deduction_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `recurring` tinyint(1) NOT NULL DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `payroll_detail_id` bigint unsigned DEFAULT NULL,
  `status` enum('draft','approved','active','paid','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `employee_deductions_org_number_unique` (`organization_id`,`deduction_number`) USING BTREE,
  KEY `employee_deductions_employee_fk` (`employee_id`) USING BTREE,
  KEY `employee_deductions_payroll_fk` (`payroll_detail_id`) USING BTREE,
  KEY `employee_deductions_organization_id_index` (`organization_id`) USING BTREE,
  KEY `employee_deductions_status_index` (`status`) USING BTREE,
  KEY `employee_deductions_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `employee_deductions_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_deductions_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `employee_deductions_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `employee_salary_components`
-- ----------------------------
DROP TABLE IF EXISTS `employee_salary_components`;
CREATE TABLE `employee_salary_components` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `salary_component_id` bigint unsigned NOT NULL,
  `value` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `effective_from` date NOT NULL,
  `effective_to` date DEFAULT NULL,
  `source` enum('structure','override','manual') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'override',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `esc_employee_component_index` (`employee_id`,`salary_component_id`) USING BTREE,
  KEY `esc_component_fk` (`salary_component_id`) USING BTREE,
  KEY `esc_is_active_index` (`is_active`) USING BTREE,
  KEY `esc_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `esc_component_fk` FOREIGN KEY (`salary_component_id`) REFERENCES `salary_components` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `esc_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `employee_salary_histories`
-- ----------------------------
DROP TABLE IF EXISTS `employee_salary_histories`;
CREATE TABLE `employee_salary_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `salary_structure_id` bigint unsigned NOT NULL,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `esh_employee_date_index` (`employee_id`,`effective_date`),
  KEY `esh_org_index` (`organization_id`),
  KEY `esh_salary_fk` (`salary_structure_id`),
  CONSTRAINT `esh_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `esh_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `esh_salary_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `chk_esh_date` CHECK (((`end_date` is null) or (`end_date` >= `effective_date`)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for `payroll_audit_logs`
-- ----------------------------
DROP TABLE IF EXISTS `payroll_audit_logs`;
CREATE TABLE `payroll_audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` bigint unsigned DEFAULT NULL,
  `old_data` json DEFAULT NULL,
  `new_data` json DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `payroll_audit_logs_org_fk` (`organization_id`) USING BTREE,
  KEY `payroll_audit_logs_user_id_index` (`user_id`) USING BTREE,
  KEY `payroll_audit_logs_table_name_index` (`table_name`) USING BTREE,
  KEY `payroll_audit_logs_created_at_index` (`created_at`) USING BTREE,
  CONSTRAINT `payroll_audit_logs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `payroll_detail_components`
-- ----------------------------
DROP TABLE IF EXISTS `payroll_detail_components`;
CREATE TABLE `payroll_detail_components` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payroll_detail_id` bigint unsigned NOT NULL,
  `salary_component_id` bigint unsigned DEFAULT NULL,
  `component_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_type` enum('earning','deduction','company_contribution','tax') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(15,4) NOT NULL DEFAULT '1.0000',
  `rate` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `calculation_source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `pdc_detail_index` (`payroll_detail_id`) USING BTREE,
  KEY `pdc_component_index` (`salary_component_id`) USING BTREE,
  KEY `pdc_component_type_index` (`component_type`) USING BTREE,
  KEY `pdc_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `pdc_component_fk` FOREIGN KEY (`salary_component_id`) REFERENCES `salary_components` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `pdc_detail_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `payroll_details`
-- ----------------------------
DROP TABLE IF EXISTS `payroll_details`;
CREATE TABLE `payroll_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payroll_run_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `employee_number_snapshot` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_name_snapshot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_name_snapshot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position_name_snapshot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gross_salary` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_deduction` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_company_contribution` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_salary` decimal(15,2) NOT NULL DEFAULT '0.00',
  `component_details` json DEFAULT NULL,
  `calculation_metadata` json DEFAULT NULL,
  `status` enum('calculated','approved','paid','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'calculated',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `payroll_details_run_employee_unique` (`payroll_run_id`,`employee_id`) USING BTREE,
  KEY `payroll_details_employee_index` (`employee_id`) USING BTREE,
  KEY `payroll_details_status_index` (`status`) USING BTREE,
  KEY `payroll_details_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `payroll_details_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payroll_details_run_fk` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `payroll_integrations`
-- ----------------------------
DROP TABLE IF EXISTS `payroll_integrations`;
CREATE TABLE `payroll_integrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `payroll_run_id` bigint unsigned NOT NULL,
  `payroll_period_id` bigint unsigned NOT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `integration_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_gross_salary` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_net_salary` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_bpjs_company` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_bpjs_employee` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_loans` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_other_deductions` decimal(15,2) NOT NULL DEFAULT '0.00',
  `mapping_config` json DEFAULT NULL,
  `status` enum('pending','mapped','synced','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `synced_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `payroll_integrations_run_unique` (`payroll_run_id`) USING BTREE,
  UNIQUE KEY `payroll_integrations_org_code_unique` (`organization_id`,`integration_code`) USING BTREE,
  KEY `payroll_integrations_period_fk` (`payroll_period_id`) USING BTREE,
  KEY `payroll_integrations_journal_fk` (`journal_entry_id`) USING BTREE,
  KEY `payroll_integrations_status_index` (`status`) USING BTREE,
  KEY `payroll_integrations_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `payroll_integrations_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `payroll_integrations_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `payroll_integrations_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payroll_integrations_run_fk` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `payroll_payment_batches`
-- ----------------------------
DROP TABLE IF EXISTS `payroll_payment_batches`;
CREATE TABLE `payroll_payment_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `payroll_run_id` bigint unsigned NOT NULL,
  `bank_account_id` bigint unsigned NOT NULL,
  `batch_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `employee_count` int NOT NULL DEFAULT '0',
  `status` enum('draft','approved','processing','completed','partial','failed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `failed_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `payroll_payment_batches_org_number_unique` (`organization_id`,`batch_number`) USING BTREE,
  KEY `ppb_run_fk` (`payroll_run_id`) USING BTREE,
  KEY `ppb_bank_fk` (`bank_account_id`) USING BTREE,
  KEY `ppb_status_index` (`status`) USING BTREE,
  KEY `ppb_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `ppb_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `ppb_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ppb_run_fk` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `payroll_payments`
-- ----------------------------
DROP TABLE IF EXISTS `payroll_payments`;
CREATE TABLE `payroll_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payroll_payment_batch_id` bigint unsigned NOT NULL,
  `payroll_detail_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','processing','success','failed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `bank_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `failure_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `payroll_payments_batch_detail_unique` (`payroll_payment_batch_id`,`payroll_detail_id`) USING BTREE,
  KEY `payroll_payments_detail_fk` (`payroll_detail_id`) USING BTREE,
  KEY `payroll_payments_employee_fk` (`employee_id`) USING BTREE,
  KEY `payroll_payments_journal_fk` (`journal_entry_id`) USING BTREE,
  KEY `payroll_payments_status_index` (`status`) USING BTREE,
  KEY `payroll_payments_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `payroll_payments_batch_fk` FOREIGN KEY (`payroll_payment_batch_id`) REFERENCES `payroll_payment_batches` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `payroll_payments_detail_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payroll_payments_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payroll_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for payroll_periods
-- ----------------------------
DROP TABLE IF EXISTS `payroll_periods`;
CREATE TABLE `payroll_periods`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `organization_id` bigint UNSIGNED NOT NULL,
  `period` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `payment_date` date NULL DEFAULT NULL,
  `status` enum('open','processing','approved','paid','closed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `payroll_periods_org_period_unique`(`organization_id` ASC, `period` ASC) USING BTREE,
  INDEX `payroll_periods_status_index`(`status` ASC) USING BTREE,
  INDEX `payroll_periods_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `payroll_periods_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for `payroll_permissions`
-- ----------------------------
DROP TABLE IF EXISTS `payroll_permissions`;
CREATE TABLE `payroll_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `module` enum('payroll','finance','hr','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission` enum('view','create','edit','delete','process','approve','export') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `employee_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `payroll_permissions_user_module_permission_unique` (`user_id`,`module`,`permission`,`department_id`,`employee_id`) USING BTREE,
  KEY `payroll_permissions_user_id_index` (`user_id`) USING BTREE,
  KEY `payroll_permissions_module_index` (`module`) USING BTREE,
  KEY `payroll_permissions_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `payroll_permissions_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `payroll_runs`
-- ----------------------------
DROP TABLE IF EXISTS `payroll_runs`;
CREATE TABLE `payroll_runs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `payroll_period_id` bigint unsigned NOT NULL,
  `run_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `run_type` enum('regular','off_cycle','correction','bonus') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
  `status` enum('draft','calculating','calculated','reviewed','approved','posted','paid','cancelled','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `total_gross` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_deduction` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_company_contribution` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_net` decimal(15,2) NOT NULL DEFAULT '0.00',
  `calculated_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `posted_by` bigint unsigned DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `locked_by` bigint unsigned DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `version` int unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `payroll_runs_org_number_unique` (`organization_id`,`run_number`) USING BTREE,
  KEY `payroll_runs_period_index` (`payroll_period_id`) USING BTREE,
  KEY `payroll_runs_status_index` (`status`) USING BTREE,
  KEY `payroll_runs_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `payroll_runs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payroll_runs_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `payroll_settings`
-- ----------------------------
DROP TABLE IF EXISTS `payroll_settings`;
CREATE TABLE `payroll_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `group` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `payroll_settings_org_key_unique` (`organization_id`,`key`) USING BTREE,
  KEY `payroll_settings_group_index` (`group`) USING BTREE,
  KEY `payroll_settings_is_active_index` (`is_active`) USING BTREE,
  KEY `payroll_settings_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `payroll_settings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `payslips`
-- ----------------------------
DROP TABLE IF EXISTS `payslips`;
CREATE TABLE `payslips` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payroll_detail_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `organization_id` bigint unsigned NOT NULL,
  `payroll_period_id` bigint unsigned NOT NULL,
  `payslip_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_allowance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_deduction` decimal(15,2) NOT NULL DEFAULT '0.00',
  `gross_salary` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_salary` decimal(15,2) NOT NULL DEFAULT '0.00',
  `currency` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `status` enum('draft','generated','sent','downloaded','void') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `generated_at` timestamp NULL DEFAULT NULL,
  `pdf_path` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `html_content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_sent` tinyint(1) NOT NULL DEFAULT '0',
  `sent_at` timestamp NULL DEFAULT NULL,
  `is_downloaded` tinyint(1) NOT NULL DEFAULT '0',
  `downloaded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `payslips_detail_unique` (`payroll_detail_id`) USING BTREE,
  UNIQUE KEY `payslips_org_number_unique` (`organization_id`,`payslip_number`) USING BTREE,
  KEY `payslips_employee_fk` (`employee_id`) USING BTREE,
  KEY `payslips_period_fk` (`payroll_period_id`) USING BTREE,
  KEY `payslips_organization_id_index` (`organization_id`) USING BTREE,
  KEY `payslips_org_status_index` (`organization_id`,`status`) USING BTREE,
  KEY `payslips_period_status_index` (`payroll_period_id`,`status`) USING BTREE,
  KEY `payslips_is_sent_index` (`is_sent`) USING BTREE,
  KEY `payslips_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `payslips_detail_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `payslips_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `payslips_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `payslips_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `ptkp_statuses`
-- ----------------------------
DROP TABLE IF EXISTS `ptkp_statuses`;
CREATE TABLE `ptkp_statuses` (
  `id` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `ptkp_statuses_deleted_at_index` (`deleted_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `salary_components`
-- ----------------------------
DROP TABLE IF EXISTS `salary_components`;
CREATE TABLE `salary_components` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('earning','deduction','company_contribution','tax') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `calculation_type` enum('fixed','percentage','formula','attendance','loan','tax','manual') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `calculation_base` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_value` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `is_taxable` tinyint(1) NOT NULL DEFAULT '0',
  `is_bpjs_base` tinyint(1) NOT NULL DEFAULT '0',
  `affects_net_salary` tinyint(1) NOT NULL DEFAULT '1',
  `is_recurring` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `salary_components_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `salary_components_type_index` (`type`) USING BTREE,
  KEY `salary_components_is_active_index` (`is_active`) USING BTREE,
  KEY `salary_components_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `salary_components_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `salary_structure_components`
-- ----------------------------
DROP TABLE IF EXISTS `salary_structure_components`;
CREATE TABLE `salary_structure_components` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `salary_structure_id` bigint unsigned NOT NULL,
  `salary_component_id` bigint unsigned NOT NULL,
  `calculation_type` enum('fixed','percentage','formula','inherit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inherit',
  `value` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `calculation_base` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calculation_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `salary_structure_components_unique` (`salary_structure_id`,`salary_component_id`) USING BTREE,
  KEY `ssc_component_fk` (`salary_component_id`) USING BTREE,
  KEY `ssc_is_active_index` (`is_active`) USING BTREE,
  KEY `ssc_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `ssc_component_fk` FOREIGN KEY (`salary_component_id`) REFERENCES `salary_components` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `ssc_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `salary_structures`
-- ----------------------------
DROP TABLE IF EXISTS `salary_structures`;
CREATE TABLE `salary_structures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `salary_structures_org_code_unique` (`organization_id`,`code`) USING BTREE,
  KEY `salary_structures_is_active_index` (`is_active`) USING BTREE,
  KEY `salary_structures_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `salary_structures_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `shift_rates`
-- ----------------------------
DROP TABLE IF EXISTS `shift_rates`;
CREATE TABLE `shift_rates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shift_id` bigint unsigned NOT NULL,
  `day_type` enum('weekday','saturday','sunday','national_holiday','special_holiday') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_rate_per_shift` decimal(12,2) NOT NULL DEFAULT '0.00',
  `overtime_multiplier` decimal(5,2) NOT NULL DEFAULT '1.50',
  `overtime_rate_per_hour` decimal(12,2) NOT NULL DEFAULT '0.00',
  `night_allowance_per_shift` decimal(12,2) NOT NULL DEFAULT '0.00',
  `meal_allowance_per_shift` decimal(12,2) NOT NULL DEFAULT '0.00',
  `transport_allowance_per_shift` decimal(12,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `shift_rates_shift_day_unique` (`shift_id`,`day_type`) USING BTREE,
  KEY `shift_rates_is_active_index` (`is_active`) USING BTREE,
  KEY `shift_rates_deleted_at_index` (`deleted_at`) USING BTREE,
  CONSTRAINT `shift_rates_shift_fk` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
