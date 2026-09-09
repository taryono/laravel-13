-- ============================================
-- REFACTORED DATABASE SCHEMA - ENTERPRISE READY
-- ============================================
-- Perubahan:
-- 1. Semua tabel menggunakan ENGINE=InnoDB
-- 2. Penambahan deleted_at, created_by, updated_by
-- 3. Penambahan audit trail fields
-- 4. Optimasi tipe data dan index
-- 5. Penambahan check constraints
-- ============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- DROP TABLES (URUTAN BERDASARKAN DEPENDENSI)
-- ============================================
DROP TABLE IF EXISTS `audit_trails`;
DROP TABLE IF EXISTS `system_logs`;
DROP TABLE IF EXISTS `scheduled_jobs`;
DROP TABLE IF EXISTS `api_rate_limits`;
DROP TABLE IF EXISTS `payroll_audit_logs`;
DROP TABLE IF EXISTS `finance_audit_logs`;
DROP TABLE IF EXISTS `asset_depreciations`;
DROP TABLE IF EXISTS `reconciliation_details`;
DROP TABLE IF EXISTS `reconciliations`;
DROP TABLE IF EXISTS `bank_transactions`;
DROP TABLE IF EXISTS `bank_accounts`;
DROP TABLE IF EXISTS `opening_balances`;
DROP TABLE IF EXISTS `budget_details`;
DROP TABLE IF EXISTS `budgets`;
DROP TABLE IF EXISTS `tax_payments`;
DROP TABLE IF EXISTS `tax_liabilities`;
DROP TABLE IF EXISTS `tax_settings`;
DROP TABLE IF EXISTS `expense_payments`;
DROP TABLE IF EXISTS `expense_items`;
DROP TABLE IF EXISTS `expenses`;
DROP TABLE IF EXISTS `invoice_payments`;
DROP TABLE IF EXISTS `invoice_items`;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `customers`;
DROP TABLE IF EXISTS `vendors`;
DROP TABLE IF EXISTS `fixed_assets`;
DROP TABLE IF EXISTS `account_mappings`;
DROP TABLE IF EXISTS `accounts`;
DROP TABLE IF EXISTS `account_categories`;
DROP TABLE IF EXISTS `accounting_periods`;
DROP TABLE IF EXISTS `fiscal_years`;
DROP TABLE IF EXISTS `payroll_payments`;
DROP TABLE IF EXISTS `payroll_payment_batches`;
DROP TABLE IF EXISTS `payslips`;
DROP TABLE IF EXISTS `payroll_integrations`;
DROP TABLE IF EXISTS `payroll_detail_components`;
DROP TABLE IF EXISTS `payroll_details`;
DROP TABLE IF EXISTS `payroll_runs`;
DROP TABLE IF EXISTS `payroll_periods`;
DROP TABLE IF EXISTS `payroll_settings`;
DROP TABLE IF EXISTS `payroll_permissions`;
DROP TABLE IF EXISTS `employee_bonuses`;
DROP TABLE IF EXISTS `employee_deductions`;
DROP TABLE IF EXISTS `employee_contracts`;
DROP TABLE IF EXISTS `employee_salary_components`;
DROP TABLE IF EXISTS `salary_structure_components`;
DROP TABLE IF EXISTS `salary_structures`;
DROP TABLE IF EXISTS `salary_components`;
DROP TABLE IF EXISTS `deductions`;
DROP TABLE IF EXISTS `deduction_categories`;
DROP TABLE IF EXISTS `bonuses`;
DROP TABLE IF EXISTS `loans`;
DROP TABLE IF EXISTS `loan_installments`;
DROP TABLE IF EXISTS `employee_leaves`;
DROP TABLE IF EXISTS `leave_balances`;
DROP TABLE IF EXISTS `leave_types`;
DROP TABLE IF EXISTS `overtimes`;
DROP TABLE IF EXISTS `attendance_dailies`;
DROP TABLE IF EXISTS `attendance_logs`;
DROP TABLE IF EXISTS `attendance_import_batches`;
DROP TABLE IF EXISTS `attendances`;
DROP TABLE IF EXISTS `employee_shifts`;
DROP TABLE IF EXISTS `shift_swaps`;
DROP TABLE IF EXISTS `shift_rates`;
DROP TABLE IF EXISTS `shifts`;
DROP TABLE IF EXISTS `holidays`;
DROP TABLE IF EXISTS `employee_mutations`;
DROP TABLE IF EXISTS `employees`;
DROP TABLE IF EXISTS `positions`;
DROP TABLE IF EXISTS `departments`;
DROP TABLE IF EXISTS `cost_centers`;
DROP TABLE IF EXISTS `organization_user`;
DROP TABLE IF EXISTS `organization_users`;
DROP TABLE IF EXISTS `organization_types`;
DROP TABLE IF EXISTS `organizations`;
DROP TABLE IF EXISTS `position_user`;
DROP TABLE IF EXISTS `role_user`;
DROP TABLE IF EXISTS `action_role`;
DROP TABLE IF EXISTS `action_user`;
DROP TABLE IF EXISTS `actions`;
DROP TABLE IF EXISTS `menu_role`;
DROP TABLE IF EXISTS `menus`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `schools`;
DROP TABLE IF EXISTS `education_levels`;
DROP TABLE IF EXISTS `subjects`;
DROP TABLE IF EXISTS `class_levels`;
DROP TABLE IF EXISTS `classrooms`;
DROP TABLE IF EXISTS `student_classrooms`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `parents`;
DROP TABLE IF EXISTS `student_parents`;
DROP TABLE IF EXISTS `teachers`;
DROP TABLE IF EXISTS `teaching_loads`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `lessons`;
DROP TABLE IF EXISTS `curriculum_structures`;
DROP TABLE IF EXISTS `schedules`;
DROP TABLE IF EXISTS `academic_years`;
DROP TABLE IF EXISTS `buildings`;
DROP TABLE IF EXISTS `floors`;
DROP TABLE IF EXISTS `rooms`;
DROP TABLE IF EXISTS `facilities`;
DROP TABLE IF EXISTS `facility_categories`;
DROP TABLE IF EXISTS `dictionaries`;
DROP TABLE IF EXISTS `ptkp_statuses`;
DROP TABLE IF EXISTS `blood_types`;
DROP TABLE IF EXISTS `genders`;
DROP TABLE IF EXISTS `identity_types`;
DROP TABLE IF EXISTS `marital_statuses`;
DROP TABLE IF EXISTS `religions`;
DROP TABLE IF EXISTS `student_ranges`;
DROP TABLE IF EXISTS `trial_registration_education_level`;
DROP TABLE IF EXISTS `trial_registration_module`;
DROP TABLE IF EXISTS `trial_registration_role`;
DROP TABLE IF EXISTS `trial_registrations`;
DROP TABLE IF EXISTS `packages`;
DROP TABLE IF EXISTS `modules`;
DROP TABLE IF EXISTS `navigation`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `sessions`;

-- ============================================
-- MASTER DATA TABLES
-- ============================================

-- 1. organizations
CREATE TABLE `organizations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `legal_name` VARCHAR(255) NULL,
    `organization_type` ENUM('foundation','company','school','unit','other') NOT NULL DEFAULT 'foundation',
    `parent_id` BIGINT UNSIGNED NULL,
    `currency_code` VARCHAR(10) NOT NULL DEFAULT 'IDR',
    `timezone` VARCHAR(100) NOT NULL DEFAULT 'Asia/Jakarta',
    `schema_name` VARCHAR(50) NULL,
    `config` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `organizations_code_unique` (`code`),
    INDEX `organizations_parent_id_index` (`parent_id`),
    INDEX `organizations_organization_type_index` (`organization_type`),
    INDEX `organizations_is_active_index` (`is_active`),
    INDEX `organizations_deleted_at_index` (`deleted_at`),
    CONSTRAINT `organizations_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. organization_types
CREATE TABLE `organization_types` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `organization_types_code_unique` (`code`),
    INDEX `organization_types_is_active_index` (`is_active`),
    INDEX `organization_types_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. organization_user
CREATE TABLE `organization_user` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `is_default` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `organization_users_unique` (`organization_id`, `user_id`),
    INDEX `organization_users_user_id_index` (`user_id`),
    INDEX `organization_users_is_active_index` (`is_active`),
    INDEX `organization_users_deleted_at_index` (`deleted_at`),
    CONSTRAINT `organization_user_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. organization_users (backward compatibility)
CREATE TABLE `organization_users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `is_default` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `organization_users_unique` (`organization_id`, `user_id`),
    INDEX `organization_users_user_id_index` (`user_id`),
    INDEX `organization_users_is_active_index` (`is_active`),
    INDEX `organization_users_deleted_at_index` (`deleted_at`),
    CONSTRAINT `organization_users_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. departments
CREATE TABLE `departments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `parent_id` BIGINT UNSIGNED NULL,
    `manager_id` BIGINT UNSIGNED NULL,
    `cost_center_code` VARCHAR(50) NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `departments_org_code_unique` (`organization_id`, `code`),
    INDEX `departments_parent_id_index` (`parent_id`),
    INDEX `departments_manager_id_index` (`manager_id`),
    INDEX `departments_is_active_index` (`is_active`),
    INDEX `departments_deleted_at_index` (`deleted_at`),
    CONSTRAINT `departments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `departments_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. positions
CREATE TABLE `positions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `level` INT NULL,
    `salary_range_min` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `salary_range_max` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `positions_org_code_unique` (`organization_id`, `code`),
    INDEX `positions_is_active_index` (`is_active`),
    INDEX `positions_deleted_at_index` (`deleted_at`),
    CONSTRAINT `positions_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. cost_centers
CREATE TABLE `cost_centers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `parent_id` BIGINT UNSIGNED NULL,
    `department_id` BIGINT UNSIGNED NULL,
    `manager_id` BIGINT UNSIGNED NULL,
    `budget_holder_id` BIGINT UNSIGNED NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `cost_centers_org_code_unique` (`organization_id`, `code`),
    INDEX `cost_centers_parent_fk` (`parent_id`),
    INDEX `cost_centers_department_fk` (`department_id`),
    INDEX `cost_centers_is_active_index` (`is_active`),
    INDEX `cost_centers_deleted_at_index` (`deleted_at`),
    CONSTRAINT `cost_centers_department_fk` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
    CONSTRAINT `cost_centers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `cost_centers_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. employees
CREATE TABLE `employees` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `nik` VARCHAR(20) NOT NULL,
    `employee_number` VARCHAR(50) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `department_id` BIGINT UNSIGNED NULL,
    `position_id` BIGINT UNSIGNED NULL,
    `supervisor_id` BIGINT UNSIGNED NULL,
    `salary_structure_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(255) NOT NULL,
    `nick_name` VARCHAR(255) NULL,
    `gender` ENUM('L','P') NULL,
    `birth_place` VARCHAR(255) NULL,
    `birth_date` DATE NULL,
    `address` TEXT NULL,
    `phone` VARCHAR(20) NULL,
    `email` VARCHAR(255) NULL,
    `join_date` DATE NOT NULL,
    `resign_date` DATE NULL,
    `employment_status` ENUM('tetap','kontrak','probation','freelance') NOT NULL DEFAULT 'probation',
    `bank_name` VARCHAR(100) NULL,
    `bank_account_number` VARCHAR(50) NULL,
    `bank_account_name` VARCHAR(255) NULL,
    `npwp` VARCHAR(50) NULL,
    `bpjs_ketenagakerjaan` VARCHAR(50) NULL,
    `bpjs_kesehatan` VARCHAR(50) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `employees_org_number_unique` (`organization_id`, `employee_number`),
    UNIQUE INDEX `employees_org_nik_unique` (`organization_id`, `nik`),
    UNIQUE INDEX `employees_email_unique` (`email`),
    INDEX `employees_user_id_index` (`user_id`),
    INDEX `employees_department_id_index` (`department_id`),
    INDEX `employees_position_id_index` (`position_id`),
    INDEX `employees_supervisor_id_index` (`supervisor_id`),
    INDEX `employees_salary_structure_id_index` (`salary_structure_id`),
    INDEX `employees_join_date_index` (`join_date`),
    INDEX `employees_is_active_index` (`is_active`),
    INDEX `employees_deleted_at_index` (`deleted_at`),
    CONSTRAINT `employees_department_fk` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
    CONSTRAINT `employees_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `employees_position_fk` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL,
    CONSTRAINT `employees_salary_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE SET NULL,
    CONSTRAINT `employees_supervisor_fk` FOREIGN KEY (`supervisor_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. employee_mutations
CREATE TABLE `employee_mutations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `mutation_code` VARCHAR(255) NOT NULL,
    `mutation_type` ENUM('promotion','demotion','transfer','department_change','position_change','salary_change') NOT NULL,
    `old_department_id` BIGINT UNSIGNED NULL,
    `new_department_id` BIGINT UNSIGNED NULL,
    `old_position_id` BIGINT UNSIGNED NULL,
    `new_position_id` BIGINT UNSIGNED NULL,
    `old_salary` DECIMAL(15,2) NULL,
    `new_salary` DECIMAL(15,2) NULL,
    `effective_date` DATE NOT NULL,
    `notes` TEXT NULL,
    `status` ENUM('draft','submitted','approved','rejected','cancelled') NOT NULL DEFAULT 'draft',
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `rejected_by` BIGINT UNSIGNED NULL,
    `rejected_at` TIMESTAMP NULL,
    `rejection_reason` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `employee_mutations_mutation_code_unique` (`mutation_code`),
    INDEX `employee_mutations_employee_id_index` (`employee_id`),
    INDEX `employee_mutations_organization_id_index` (`organization_id`),
    INDEX `employee_mutations_status_index` (`status`),
    INDEX `employee_mutations_effective_date_index` (`effective_date`),
    INDEX `employee_mutations_deleted_at_index` (`deleted_at`),
    CONSTRAINT `employee_mutations_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `employee_mutations_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `employee_mutations_old_dept_fk` FOREIGN KEY (`old_department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
    CONSTRAINT `employee_mutations_new_dept_fk` FOREIGN KEY (`new_department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
    CONSTRAINT `employee_mutations_old_pos_fk` FOREIGN KEY (`old_position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL,
    CONSTRAINT `employee_mutations_new_pos_fk` FOREIGN KEY (`new_position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. shifts
CREATE TABLE `shifts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `crosses_midnight` TINYINT(1) NULL,
    `grace_period_start` INT NULL DEFAULT 15,
    `grace_period_end` INT NULL DEFAULT 15,
    `break_start` TIME NULL,
    `break_end` TIME NULL,
    `break_duration` INT NULL DEFAULT 0,
    `night_shift_start` TIME NULL,
    `overtime_min_threshold` INT NULL DEFAULT 60,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `shifts_org_code_unique` (`organization_id`, `code`),
    INDEX `shifts_is_active_index` (`is_active`),
    INDEX `shifts_deleted_at_index` (`deleted_at`),
    CONSTRAINT `shifts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. shift_rates
CREATE TABLE `shift_rates` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `shift_id` BIGINT UNSIGNED NOT NULL,
    `day_type` ENUM('weekday','saturday','sunday','national_holiday','special_holiday') NOT NULL,
    `base_rate_per_shift` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `overtime_multiplier` DECIMAL(5,2) NOT NULL DEFAULT 1.50,
    `overtime_rate_per_hour` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `night_allowance_per_shift` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `meal_allowance_per_shift` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `transport_allowance_per_shift` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `shift_rates_shift_day_unique` (`shift_id`, `day_type`),
    INDEX `shift_rates_is_active_index` (`is_active`),
    INDEX `shift_rates_deleted_at_index` (`deleted_at`),
    CONSTRAINT `shift_rates_shift_fk` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. employee_shifts
CREATE TABLE `employee_shifts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `shift_id` BIGINT UNSIGNED NOT NULL,
    `effective_date` DATE NOT NULL,
    `end_date` DATE NULL,
    `day_of_week` TINYINT NULL,
    `is_rotational` TINYINT(1) NULL DEFAULT 0,
    `rotation_group` VARCHAR(50) NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `employee_shift_effective_unique` (`employee_id`, `effective_date`, `shift_id`),
    INDEX `employee_shifts_employee_id_index` (`employee_id`),
    INDEX `employee_shifts_shift_id_index` (`shift_id`),
    INDEX `employee_shifts_deleted_at_index` (`deleted_at`),
    CONSTRAINT `employee_shifts_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `employee_shifts_shift_fk` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. shift_swaps
CREATE TABLE `shift_swaps` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `employee_id_a` BIGINT UNSIGNED NOT NULL,
    `original_shift_a` BIGINT UNSIGNED NOT NULL,
    `target_shift_a` BIGINT UNSIGNED NOT NULL,
    `employee_id_b` BIGINT UNSIGNED NOT NULL,
    `original_shift_b` BIGINT UNSIGNED NOT NULL,
    `target_shift_b` BIGINT UNSIGNED NOT NULL,
    `status` ENUM('pending','approved','rejected','cancelled') NULL DEFAULT 'pending',
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `rejected_by` BIGINT UNSIGNED NULL,
    `rejected_at` TIMESTAMP NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `swap_per_date_unique` (`date`, `employee_id_a`),
    UNIQUE INDEX `swap_per_date_b_unique` (`date`, `employee_id_b`),
    INDEX `shift_swaps_employee_id_a_index` (`employee_id_a`),
    INDEX `shift_swaps_employee_id_b_index` (`employee_id_b`),
    INDEX `shift_swaps_status_index` (`status`),
    INDEX `shift_swaps_deleted_at_index` (`deleted_at`),
    CONSTRAINT `shift_swaps_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `shift_swaps_employee_a_fk` FOREIGN KEY (`employee_id_a`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `shift_swaps_employee_b_fk` FOREIGN KEY (`employee_id_b`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `shift_swaps_orig_shift_a_fk` FOREIGN KEY (`original_shift_a`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `shift_swaps_target_shift_a_fk` FOREIGN KEY (`target_shift_a`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `shift_swaps_orig_shift_b_fk` FOREIGN KEY (`original_shift_b`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `shift_swaps_target_shift_b_fk` FOREIGN KEY (`target_shift_b`) REFERENCES `shifts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. holidays
CREATE TABLE `holidays` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `type` ENUM('national','special','company','religious') NOT NULL DEFAULT 'national',
    `is_recurring` TINYINT(1) NULL DEFAULT 0,
    `description` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `holidays_org_date_type_unique` (`organization_id`, `date`, `type`),
    INDEX `holidays_date_index` (`date`),
    INDEX `holidays_type_index` (`type`),
    INDEX `holidays_deleted_at_index` (`deleted_at`),
    CONSTRAINT `holidays_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. attendance_logs
CREATE TABLE `attendance_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `attendance_date` DATE NOT NULL,
    `check_in` DATETIME NULL,
    `check_out` DATETIME NULL,
    `source` ENUM('manual','machine','import','api','mobile') NOT NULL DEFAULT 'manual',
    `source_reference` VARCHAR(255) NULL,
    `device_id` VARCHAR(100) NULL,
    `location` JSON NULL,
    `metadata` JSON NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `attendance_logs_employee_date_index` (`employee_id`, `attendance_date`),
    INDEX `attendance_logs_org_fk` (`organization_id`),
    INDEX `attendance_logs_source_index` (`source`),
    INDEX `attendance_logs_deleted_at_index` (`deleted_at`),
    CONSTRAINT `attendance_logs_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `attendance_logs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. attendance_dailies
CREATE TABLE `attendance_dailies` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `attendance_date` DATE NOT NULL,
    `status` ENUM('present','absent','late','leave','holiday','off','half_day','sick','permit') NOT NULL,
    `work_hours` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `overtime_hours` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `late_minutes` INT NOT NULL DEFAULT 0,
    `early_leave_minutes` INT NOT NULL DEFAULT 0,
    `source_log_id` BIGINT UNSIGNED NULL,
    `check_in_time` DATETIME NULL,
    `check_out_time` DATETIME NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `attendance_dailies_employee_date_unique` (`employee_id`, `attendance_date`),
    INDEX `attendance_dailies_org_fk` (`organization_id`),
    INDEX `attendance_dailies_log_fk` (`source_log_id`),
    INDEX `attendance_dailies_status_index` (`status`),
    INDEX `attendance_dailies_deleted_at_index` (`deleted_at`),
    CONSTRAINT `attendance_dailies_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `attendance_dailies_log_fk` FOREIGN KEY (`source_log_id`) REFERENCES `attendance_logs` (`id`) ON DELETE SET NULL,
    CONSTRAINT `attendance_dailies_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. attendance_import_batches
CREATE TABLE `attendance_import_batches` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `batch_code` VARCHAR(100) NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_size` INT NULL,
    `file_path` VARCHAR(500) NULL,
    `machine_source` VARCHAR(100) NULL,
    `total_records` INT NULL DEFAULT 0,
    `processed_records` INT NULL DEFAULT 0,
    `failed_records` INT NULL DEFAULT 0,
    `status` ENUM('uploaded','processing','completed','failed','partial') NULL DEFAULT 'uploaded',
    `started_at` TIMESTAMP NULL,
    `completed_at` TIMESTAMP NULL,
    `errors` JSON NULL,
    `imported_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `attendance_import_batches_batch_code_unique` (`batch_code`),
    INDEX `attendance_import_batches_org_fk` (`organization_id`),
    INDEX `attendance_import_batches_status_index` (`status`),
    INDEX `attendance_import_batches_deleted_at_index` (`deleted_at`),
    CONSTRAINT `attendance_import_batches_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. attendances
CREATE TABLE `attendances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `attendance_daily_id` BIGINT UNSIGNED NULL,
    `payroll_period_id` BIGINT UNSIGNED NULL,
    `attendance_date` DATE NOT NULL,
    `status` ENUM('hadir','sakit','izin','alpha','cuti','dinas_luar','training','other') NOT NULL DEFAULT 'hadir',
    `notes` TEXT NULL,
    `attachment_path` VARCHAR(500) NULL,
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `attendance_employee_date_unique` (`employee_id`, `attendance_date`),
    INDEX `attendances_employee_id_index` (`employee_id`),
    INDEX `attendances_daily_fk_index` (`attendance_daily_id`),
    INDEX `attendances_period_index` (`payroll_period_id`),
    INDEX `attendances_status_index` (`status`),
    INDEX `attendances_deleted_at_index` (`deleted_at`),
    CONSTRAINT `attendances_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `attendances_daily_fk` FOREIGN KEY (`attendance_daily_id`) REFERENCES `attendance_dailies` (`id`) ON DELETE SET NULL,
    CONSTRAINT `attendances_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. overtimes
CREATE TABLE `overtimes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `attendance_daily_id` BIGINT UNSIGNED NULL,
    `overtime_date` DATE NOT NULL,
    `start_at` DATETIME NULL,
    `end_at` DATETIME NULL,
    `hours` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `hourly_rate` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `rate_multiplier` DECIMAL(8,4) NOT NULL DEFAULT 1.0000,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `status` ENUM('draft','submitted','approved','rejected','cancelled') NOT NULL DEFAULT 'draft',
    `submitted_at` TIMESTAMP NULL,
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `rejected_by` BIGINT UNSIGNED NULL,
    `rejected_at` TIMESTAMP NULL,
    `rejection_reason` TEXT NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `overtimes_org_idx` (`organization_id`),
    INDEX `overtimes_employee_idx` (`employee_id`),
    INDEX `overtimes_daily_idx` (`attendance_daily_id`),
    INDEX `overtimes_date_idx` (`overtime_date`),
    INDEX `overtimes_status_idx` (`status`),
    INDEX `overtimes_employee_date_idx` (`employee_id`, `overtime_date`),
    INDEX `overtimes_deleted_at_index` (`deleted_at`),
    CONSTRAINT `overtimes_daily_fk` FOREIGN KEY (`attendance_daily_id`) REFERENCES `attendance_dailies` (`id`) ON DELETE SET NULL,
    CONSTRAINT `overtimes_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `overtimes_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. leave_types
CREATE TABLE `leave_types` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `default_days` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `max_consecutive_days` INT NULL,
    `min_working_days_before` INT NULL,
    `is_paid` TINYINT(1) NOT NULL DEFAULT 1,
    `requires_attachment` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `leave_types_org_code_unique` (`organization_id`, `code`),
    INDEX `leave_types_is_active_index` (`is_active`),
    INDEX `leave_types_deleted_at_index` (`deleted_at`),
    CONSTRAINT `leave_types_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. leave_balances
CREATE TABLE `leave_balances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `leave_type_id` BIGINT UNSIGNED NOT NULL,
    `year` INT NOT NULL,
    `allocated_days` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `used_days` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `remaining_days` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `leave_balances_unique` (`employee_id`, `leave_type_id`, `year`),
    INDEX `leave_balances_type_fk` (`leave_type_id`),
    INDEX `leave_balances_year_index` (`year`),
    INDEX `leave_balances_deleted_at_index` (`deleted_at`),
    CONSTRAINT `leave_balances_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `leave_balances_type_fk` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22. employee_leaves
CREATE TABLE `employee_leaves` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `leave_type_id` BIGINT UNSIGNED NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `total_days` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `reason` TEXT NULL,
    `attachment_path` VARCHAR(500) NULL,
    `status` ENUM('draft','submitted','approved','rejected','cancelled') NOT NULL DEFAULT 'draft',
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `rejected_by` BIGINT UNSIGNED NULL,
    `rejected_at` TIMESTAMP NULL,
    `rejection_reason` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `employee_leaves_employee_fk` (`employee_id`),
    INDEX `employee_leaves_type_fk` (`leave_type_id`),
    INDEX `employee_leaves_status_index` (`status`),
    INDEX `employee_leaves_date_range_index` (`start_date`, `end_date`),
    INDEX `employee_leaves_deleted_at_index` (`deleted_at`),
    CONSTRAINT `employee_leaves_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `employee_leaves_type_fk` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 23. loans
CREATE TABLE `loans` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `loan_number` VARCHAR(100) NOT NULL,
    `loan_date` DATE NOT NULL,
    `principal_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `installment_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_installments` INT NOT NULL DEFAULT 0,
    `remaining_installments` INT NOT NULL DEFAULT 0,
    `status` ENUM('draft','active','completed','cancelled','defaulted') NOT NULL DEFAULT 'draft',
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `loans_org_number_unique` (`organization_id`, `loan_number`),
    INDEX `loans_employee_fk` (`employee_id`),
    INDEX `loans_status_index` (`status`),
    INDEX `loans_deleted_at_index` (`deleted_at`),
    CONSTRAINT `loans_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `loans_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 24. loan_installments
CREATE TABLE `loan_installments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `loan_id` BIGINT UNSIGNED NOT NULL,
    `installment_number` INT NOT NULL,
    `due_date` DATE NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `paid_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `status` ENUM('pending','paid','partial','cancelled','overdue') NOT NULL DEFAULT 'pending',
    `payroll_detail_id` BIGINT UNSIGNED NULL,
    `paid_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `loan_installments_number_unique` (`loan_id`, `installment_number`),
    INDEX `loan_installments_payroll_fk` (`payroll_detail_id`),
    INDEX `loan_installments_status_index` (`status`),
    INDEX `loan_installments_due_date_index` (`due_date`),
    INDEX `loan_installments_deleted_at_index` (`deleted_at`),
    CONSTRAINT `loan_installments_loan_fk` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE,
    CONSTRAINT `loan_installments_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 25. bonuses
CREATE TABLE `bonuses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `bonus_code` VARCHAR(255) NOT NULL,
    `bonus_type` ENUM('performance','thr','bonus_year_end','project_bonus','referral','attendance','other') NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `payment_date` DATE NOT NULL,
    `description` TEXT NULL,
    `payroll_period_id` BIGINT UNSIGNED NULL,
    `is_paid` TINYINT(1) NOT NULL DEFAULT 0,
    `paid_at` TIMESTAMP NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `bonuses_org_code_unique` (`organization_id`, `bonus_code`),
    INDEX `bonuses_employee_id_index` (`employee_id`),
    INDEX `bonuses_payroll_period_id_index` (`payroll_period_id`),
    INDEX `bonuses_bonus_type_index` (`bonus_type`),
    INDEX `bonuses_is_paid_index` (`is_paid`),
    INDEX `bonuses_deleted_at_index` (`deleted_at`),
    CONSTRAINT `bonuses_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `bonuses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `bonuses_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 26. deduction_categories
CREATE TABLE `deduction_categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `category_type` ENUM('mandatory','voluntary','other') NOT NULL DEFAULT 'other',
    `deduction_group` ENUM('insurance','tax','saving','loan','penalty','other') NOT NULL DEFAULT 'other',
    `calculation_type` ENUM('fixed','percentage','sliding','custom') NOT NULL DEFAULT 'fixed',
    `default_amount` DECIMAL(15,2) NULL,
    `default_percentage` DECIMAL(5,2) NULL,
    `min_amount` DECIMAL(15,2) NULL,
    `max_amount` DECIMAL(15,2) NULL,
    `is_based_on_salary` TINYINT(1) NOT NULL DEFAULT 0,
    `is_taxable` TINYINT(1) NOT NULL DEFAULT 0,
    `tax_code` VARCHAR(50) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `is_system` TINYINT(1) NOT NULL DEFAULT 0,
    `description` TEXT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `deduction_categories_org_code_unique` (`organization_id`, `code`),
    INDEX `deduction_categories_is_active_index` (`is_active`),
    INDEX `deduction_categories_deleted_at_index` (`deleted_at`),
    CONSTRAINT `deduction_categories_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 27. deductions
CREATE TABLE `deductions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `payroll_period_id` BIGINT UNSIGNED NULL,
    `deduction_category_id` BIGINT UNSIGNED NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `description` TEXT NULL,
    `is_recurring` TINYINT(1) NOT NULL DEFAULT 0,
    `recurring_until` DATE NULL,
    `is_paid` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `deductions_employee_id_index` (`employee_id`),
    INDEX `deductions_payroll_period_id_index` (`payroll_period_id`),
    INDEX `deductions_deduction_category_id_index` (`deduction_category_id`),
    INDEX `deductions_organization_id_index` (`organization_id`),
    INDEX `deductions_deleted_at_index` (`deleted_at`),
    CONSTRAINT `deductions_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `deductions_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `deductions_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE SET NULL,
    CONSTRAINT `deductions_category_fk` FOREIGN KEY (`deduction_category_id`) REFERENCES `deduction_categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 28. salary_components
CREATE TABLE `salary_components` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `type` ENUM('earning','deduction','company_contribution','tax') NOT NULL,
    `calculation_type` ENUM('fixed','percentage','formula','attendance','loan','tax','manual') NOT NULL DEFAULT 'fixed',
    `calculation_base` VARCHAR(100) NULL,
    `default_value` DECIMAL(15,4) NOT NULL DEFAULT 0.0000,
    `is_taxable` TINYINT(1) NOT NULL DEFAULT 0,
    `is_bpjs_base` TINYINT(1) NOT NULL DEFAULT 0,
    `affects_net_salary` TINYINT(1) NOT NULL DEFAULT 1,
    `is_recurring` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `salary_components_org_code_unique` (`organization_id`, `code`),
    INDEX `salary_components_type_index` (`type`),
    INDEX `salary_components_is_active_index` (`is_active`),
    INDEX `salary_components_deleted_at_index` (`deleted_at`),
    CONSTRAINT `salary_components_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 29. salary_structures
CREATE TABLE `salary_structures` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `salary_structures_org_code_unique` (`organization_id`, `code`),
    INDEX `salary_structures_is_active_index` (`is_active`),
    INDEX `salary_structures_deleted_at_index` (`deleted_at`),
    CONSTRAINT `salary_structures_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 30. salary_structure_components
CREATE TABLE `salary_structure_components` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `salary_structure_id` BIGINT UNSIGNED NOT NULL,
    `salary_component_id` BIGINT UNSIGNED NOT NULL,
    `calculation_type` ENUM('fixed','percentage','formula','inherit') NOT NULL DEFAULT 'inherit',
    `value` DECIMAL(15,4) NOT NULL DEFAULT 0.0000,
    `calculation_base` VARCHAR(100) NULL,
    `calculation_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `salary_structure_components_unique` (`salary_structure_id`, `salary_component_id`),
    INDEX `ssc_component_fk` (`salary_component_id`),
    INDEX `ssc_is_active_index` (`is_active`),
    INDEX `ssc_deleted_at_index` (`deleted_at`),
    CONSTRAINT `ssc_component_fk` FOREIGN KEY (`salary_component_id`) REFERENCES `salary_components` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `ssc_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 31. employee_salary_components
CREATE TABLE `employee_salary_components` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `salary_component_id` BIGINT UNSIGNED NOT NULL,
    `value` DECIMAL(15,4) NOT NULL DEFAULT 0.0000,
    `effective_from` DATE NOT NULL,
    `effective_to` DATE NULL,
    `source` ENUM('structure','override','manual') NOT NULL DEFAULT 'override',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `esc_employee_component_index` (`employee_id`, `salary_component_id`),
    INDEX `esc_component_fk` (`salary_component_id`),
    INDEX `esc_is_active_index` (`is_active`),
    INDEX `esc_deleted_at_index` (`deleted_at`),
    CONSTRAINT `esc_component_fk` FOREIGN KEY (`salary_component_id`) REFERENCES `salary_components` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `esc_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 32. employee_contracts
CREATE TABLE `employee_contracts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `contract_number` VARCHAR(100) NOT NULL,
    `contract_type` ENUM('tetap','kontrak','probation','freelance','project') NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NULL,
    `salary` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `salary_structure_id` BIGINT UNSIGNED NULL,
    `status` ENUM('draft','active','expired','terminated','extended') NOT NULL DEFAULT 'draft',
    `document_path` VARCHAR(500) NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `employee_contracts_org_number_unique` (`organization_id`, `contract_number`),
    INDEX `employee_contracts_employee_id_index` (`employee_id`),
    INDEX `employee_contracts_salary_structure_id_index` (`salary_structure_id`),
    INDEX `employee_contracts_organization_id_index` (`organization_id`),
    INDEX `employee_contracts_status_index` (`status`),
    INDEX `employee_contracts_deleted_at_index` (`deleted_at`),
    CONSTRAINT `employee_contracts_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `employee_contracts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `employee_contracts_salary_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 33. employee_bonuses
CREATE TABLE `employee_bonuses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `bonus_number` VARCHAR(100) NOT NULL,
    `bonus_date` DATE NOT NULL,
    `bonus_type` VARCHAR(100) NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `payroll_detail_id` BIGINT UNSIGNED NULL,
    `status` ENUM('draft','approved','paid','cancelled') NOT NULL DEFAULT 'draft',
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `employee_bonuses_org_number_unique` (`organization_id`, `bonus_number`),
    INDEX `employee_bonuses_employee_fk` (`employee_id`),
    INDEX `employee_bonuses_payroll_fk` (`payroll_detail_id`),
    INDEX `employee_bonuses_organization_id_index` (`organization_id`),
    INDEX `employee_bonuses_status_index` (`status`),
    INDEX `employee_bonuses_deleted_at_index` (`deleted_at`),
    CONSTRAINT `employee_bonuses_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `employee_bonuses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `employee_bonuses_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 34. employee_deductions
CREATE TABLE `employee_deductions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `deduction_number` VARCHAR(100) NOT NULL,
    `deduction_date` DATE NOT NULL,
    `deduction_type` VARCHAR(100) NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `recurring` TINYINT(1) NOT NULL DEFAULT 0,
    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `payroll_detail_id` BIGINT UNSIGNED NULL,
    `status` ENUM('draft','approved','active','paid','cancelled') NOT NULL DEFAULT 'draft',
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `employee_deductions_org_number_unique` (`organization_id`, `deduction_number`),
    INDEX `employee_deductions_employee_fk` (`employee_id`),
    INDEX `employee_deductions_payroll_fk` (`payroll_detail_id`),
    INDEX `employee_deductions_organization_id_index` (`organization_id`),
    INDEX `employee_deductions_status_index` (`status`),
    INDEX `employee_deductions_deleted_at_index` (`deleted_at`),
    CONSTRAINT `employee_deductions_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `employee_deductions_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `employee_deductions_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 35. payroll_settings
CREATE TABLE `payroll_settings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `key` VARCHAR(255) NOT NULL,
    `value` TEXT NULL,
    `group` VARCHAR(100) NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `payroll_settings_org_key_unique` (`organization_id`, `key`),
    INDEX `payroll_settings_group_index` (`group`),
    INDEX `payroll_settings_is_active_index` (`is_active`),
    INDEX `payroll_settings_deleted_at_index` (`deleted_at`),
    CONSTRAINT `payroll_settings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 36. payroll_periods
CREATE TABLE `payroll_periods` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `period` VARCHAR(7) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `payment_date` DATE NULL,
    `status` ENUM('open','processing','approved','paid','closed','cancelled') NOT NULL DEFAULT 'open',
    `is_closed` TINYINT(1) NOT NULL DEFAULT 0,
    `closed_at` TIMESTAMP NULL,
    `closed_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `payroll_periods_org_period_unique` (`organization_id`, `period`),
    INDEX `payroll_periods_status_index` (`status`),
    INDEX `payroll_periods_deleted_at_index` (`deleted_at`),
    CONSTRAINT `payroll_periods_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 37. payroll_runs
CREATE TABLE `payroll_runs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payroll_period_id` BIGINT UNSIGNED NOT NULL,
    `run_number` VARCHAR(100) NOT NULL,
    `run_type` ENUM('regular','off_cycle','correction','bonus') NOT NULL DEFAULT 'regular',
    `status` ENUM('draft','calculating','calculated','reviewed','approved','posted','paid','cancelled','failed') NOT NULL DEFAULT 'draft',
    `total_gross` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_deduction` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_company_contribution` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_tax` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_net` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `calculated_at` TIMESTAMP NULL,
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `posted_by` BIGINT UNSIGNED NULL,
    `posted_at` TIMESTAMP NULL,
    `locked_by` BIGINT UNSIGNED NULL,
    `locked_at` TIMESTAMP NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `payroll_runs_org_number_unique` (`organization_id`, `run_number`),
    INDEX `payroll_runs_period_index` (`payroll_period_id`),
    INDEX `payroll_runs_status_index` (`status`),
    INDEX `payroll_runs_deleted_at_index` (`deleted_at`),
    CONSTRAINT `payroll_runs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `payroll_runs_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 38. payroll_details
CREATE TABLE `payroll_details` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `payroll_run_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `employee_number_snapshot` VARCHAR(50) NOT NULL,
    `employee_name_snapshot` VARCHAR(255) NOT NULL,
    `department_name_snapshot` VARCHAR(255) NULL,
    `position_name_snapshot` VARCHAR(255) NULL,
    `gross_salary` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_deduction` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_company_contribution` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_tax` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `net_salary` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `component_details` JSON NULL,
    `calculation_metadata` JSON NULL,
    `status` ENUM('calculated','approved','paid','cancelled') NOT NULL DEFAULT 'calculated',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `payroll_details_run_employee_unique` (`payroll_run_id`, `employee_id`),
    INDEX `payroll_details_employee_index` (`employee_id`),
    INDEX `payroll_details_status_index` (`status`),
    INDEX `payroll_details_deleted_at_index` (`deleted_at`),
    CONSTRAINT `payroll_details_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `payroll_details_run_fk` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 39. payroll_detail_components
CREATE TABLE `payroll_detail_components` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `payroll_detail_id` BIGINT UNSIGNED NOT NULL,
    `salary_component_id` BIGINT UNSIGNED NULL,
    `component_code` VARCHAR(50) NOT NULL,
    `component_name` VARCHAR(255) NOT NULL,
    `component_type` ENUM('earning','deduction','company_contribution','tax') NOT NULL,
    `quantity` DECIMAL(15,4) NOT NULL DEFAULT 1.0000,
    `rate` DECIMAL(15,4) NOT NULL DEFAULT 0.0000,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `calculation_source` VARCHAR(100) NULL,
    `metadata` JSON NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `pdc_detail_index` (`payroll_detail_id`),
    INDEX `pdc_component_index` (`salary_component_id`),
    INDEX `pdc_component_type_index` (`component_type`),
    INDEX `pdc_deleted_at_index` (`deleted_at`),
    CONSTRAINT `pdc_component_fk` FOREIGN KEY (`salary_component_id`) REFERENCES `salary_components` (`id`) ON DELETE SET NULL,
    CONSTRAINT `pdc_detail_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 40. payroll_integrations
CREATE TABLE `payroll_integrations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payroll_run_id` BIGINT UNSIGNED NOT NULL,
    `payroll_period_id` BIGINT UNSIGNED NOT NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `integration_code` VARCHAR(255) NOT NULL,
    `total_gross_salary` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_net_salary` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_tax` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_bpjs_company` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_bpjs_employee` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_loans` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_other_deductions` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `mapping_config` JSON NULL,
    `status` ENUM('pending','mapped','synced','failed') NOT NULL DEFAULT 'pending',
    `synced_at` TIMESTAMP NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `payroll_integrations_run_unique` (`payroll_run_id`),
    UNIQUE INDEX `payroll_integrations_org_code_unique` (`organization_id`, `integration_code`),
    INDEX `payroll_integrations_period_fk` (`payroll_period_id`),
    INDEX `payroll_integrations_journal_fk` (`journal_entry_id`),
    INDEX `payroll_integrations_status_index` (`status`),
    INDEX `payroll_integrations_deleted_at_index` (`deleted_at`),
    CONSTRAINT `payroll_integrations_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `payroll_integrations_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `payroll_integrations_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `payroll_integrations_run_fk` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 41. payslips
CREATE TABLE `payslips` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `payroll_detail_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payroll_period_id` BIGINT UNSIGNED NOT NULL,
    `payslip_number` VARCHAR(255) NOT NULL,
    `generated_at` TIMESTAMP NULL,
    `pdf_path` VARCHAR(500) NULL,
    `html_content` TEXT NULL,
    `is_sent` TINYINT(1) NOT NULL DEFAULT 0,
    `sent_at` TIMESTAMP NULL,
    `is_downloaded` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `payslips_detail_unique` (`payroll_detail_id`),
    UNIQUE INDEX `payslips_org_number_unique` (`organization_id`, `payslip_number`),
    INDEX `payslips_employee_fk` (`employee_id`),
    INDEX `payslips_period_fk` (`payroll_period_id`),
    INDEX `payslips_organization_id_index` (`organization_id`),
    INDEX `payslips_is_sent_index` (`is_sent`),
    INDEX `payslips_deleted_at_index` (`deleted_at`),
    CONSTRAINT `payslips_detail_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE CASCADE,
    CONSTRAINT `payslips_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `payslips_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `payslips_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 42. payroll_payment_batches
CREATE TABLE `payroll_payment_batches` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payroll_run_id` BIGINT UNSIGNED NOT NULL,
    `bank_account_id` BIGINT UNSIGNED NOT NULL,
    `batch_number` VARCHAR(100) NOT NULL,
    `payment_date` DATE NOT NULL,
    `total_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `employee_count` INT NOT NULL DEFAULT 0,
    `status` ENUM('draft','approved','processing','completed','partial','failed','cancelled') NOT NULL DEFAULT 'draft',
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `processed_at` TIMESTAMP NULL,
    `completed_at` TIMESTAMP NULL,
    `failed_reason` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `payroll_payment_batches_org_number_unique` (`organization_id`, `batch_number`),
    INDEX `ppb_run_fk` (`payroll_run_id`),
    INDEX `ppb_bank_fk` (`bank_account_id`),
    INDEX `ppb_status_index` (`status`),
    INDEX `ppb_deleted_at_index` (`deleted_at`),
    CONSTRAINT `ppb_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `ppb_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `ppb_run_fk` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 43. payroll_payments
CREATE TABLE `payroll_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `payroll_payment_batch_id` BIGINT UNSIGNED NOT NULL,
    `payroll_detail_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `status` ENUM('pending','processing','success','failed','cancelled') NOT NULL DEFAULT 'pending',
    `bank_reference` VARCHAR(255) NULL,
    `paid_at` TIMESTAMP NULL,
    `failure_reason` TEXT NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `payroll_payments_batch_detail_unique` (`payroll_payment_batch_id`, `payroll_detail_id`),
    INDEX `payroll_payments_detail_fk` (`payroll_detail_id`),
    INDEX `payroll_payments_employee_fk` (`employee_id`),
    INDEX `payroll_payments_journal_fk` (`journal_entry_id`),
    INDEX `payroll_payments_status_index` (`status`),
    INDEX `payroll_payments_deleted_at_index` (`deleted_at`),
    CONSTRAINT `payroll_payments_batch_fk` FOREIGN KEY (`payroll_payment_batch_id`) REFERENCES `payroll_payment_batches` (`id`) ON DELETE CASCADE,
    CONSTRAINT `payroll_payments_detail_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `payroll_payments_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `payroll_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 44. payroll_permissions
CREATE TABLE `payroll_permissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `module` ENUM('payroll','finance','hr','admin') NOT NULL,
    `permission` ENUM('view','create','edit','delete','process','approve','export') NOT NULL,
    `department_id` BIGINT UNSIGNED NULL,
    `employee_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `payroll_permissions_user_module_permission_unique` (`user_id`, `module`, `permission`, `department_id`, `employee_id`),
    INDEX `payroll_permissions_user_id_index` (`user_id`),
    INDEX `payroll_permissions_module_index` (`module`),
    INDEX `payroll_permissions_deleted_at_index` (`deleted_at`),
    CONSTRAINT `payroll_permissions_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 45. payroll_audit_logs
CREATE TABLE `payroll_audit_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `action` VARCHAR(100) NOT NULL,
    `table_name` VARCHAR(100) NOT NULL,
    `record_id` BIGINT UNSIGNED NULL,
    `old_data` JSON NULL,
    `new_data` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `payroll_audit_logs_org_fk` (`organization_id`),
    INDEX `payroll_audit_logs_user_id_index` (`user_id`),
    INDEX `payroll_audit_logs_table_name_index` (`table_name`),
    INDEX `payroll_audit_logs_created_at_index` (`created_at`),
    CONSTRAINT `payroll_audit_logs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- FINANCE & ACCOUNTING TABLES
-- ============================================

-- 46. fiscal_years
CREATE TABLE `fiscal_years` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `year` INT NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `status` ENUM('open','closed') NOT NULL DEFAULT 'open',
    `is_closed` TINYINT(1) NOT NULL DEFAULT 0,
    `closed_at` TIMESTAMP NULL,
    `closed_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fiscal_years_org_year_unique` (`organization_id`, `year`),
    INDEX `fiscal_years_status_index` (`status`),
    INDEX `fiscal_years_deleted_at_index` (`deleted_at`),
    CONSTRAINT `fiscal_years_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 47. accounting_periods
CREATE TABLE `accounting_periods` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_year_id` BIGINT UNSIGNED NOT NULL,
    `period` VARCHAR(7) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `status` ENUM('open','soft_closed','closed') NOT NULL DEFAULT 'open',
    `closed_at` TIMESTAMP NULL,
    `closed_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `accounting_periods_org_period_unique` (`organization_id`, `period`),
    INDEX `accounting_periods_year_fk` (`fiscal_year_id`),
    INDEX `accounting_periods_status_index` (`status`),
    INDEX `accounting_periods_deleted_at_index` (`deleted_at`),
    CONSTRAINT `accounting_periods_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `accounting_periods_year_fk` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 48. account_categories
CREATE TABLE `account_categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `type` ENUM('aset','kewajiban','ekuitas','pendapatan','beban') NOT NULL,
    `normal_balance` ENUM('debit','kredit') NOT NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `account_categories_org_code_unique` (`organization_id`, `code`),
    INDEX `account_categories_type_index` (`type`),
    INDEX `account_categories_is_active_index` (`is_active`),
    INDEX `account_categories_deleted_at_index` (`deleted_at`),
    CONSTRAINT `account_categories_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 49. accounts
CREATE TABLE `accounts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `account_category_id` BIGINT UNSIGNED NOT NULL,
    `parent_id` BIGINT UNSIGNED NULL,
    `account_type` ENUM('header','detail') NOT NULL DEFAULT 'detail',
    `normal_balance` ENUM('debit','kredit') NOT NULL,
    `currency_code` VARCHAR(10) NOT NULL DEFAULT 'IDR',
    `description` TEXT NULL,
    `opening_balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `current_balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `is_system` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `accounts_org_code_unique` (`organization_id`, `code`),
    INDEX `accounts_category_index` (`account_category_id`),
    INDEX `accounts_parent_index` (`parent_id`),
    INDEX `accounts_is_active_index` (`is_active`),
    INDEX `accounts_deleted_at_index` (`deleted_at`),
    CONSTRAINT `accounts_category_fk` FOREIGN KEY (`account_category_id`) REFERENCES `account_categories` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `accounts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `accounts_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 50. account_mappings
CREATE TABLE `account_mappings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `source_type` ENUM('payroll_component','sales','purchase','expense','tax','loan','bank','other') NOT NULL,
    `source_code` VARCHAR(255) NOT NULL,
    `debit_account_id` BIGINT UNSIGNED NULL,
    `credit_account_id` BIGINT UNSIGNED NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `account_mappings_org_source_unique` (`organization_id`, `source_type`, `source_code`),
    INDEX `account_mappings_debit_fk` (`debit_account_id`),
    INDEX `account_mappings_credit_fk` (`credit_account_id`),
    INDEX `account_mappings_is_active_index` (`is_active`),
    INDEX `account_mappings_deleted_at_index` (`deleted_at`),
    CONSTRAINT `account_mappings_credit_fk` FOREIGN KEY (`credit_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
    CONSTRAINT `account_mappings_debit_fk` FOREIGN KEY (`debit_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
    CONSTRAINT `account_mappings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 51. journal_entries
CREATE TABLE `journal_entries` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `accounting_period_id` BIGINT UNSIGNED NOT NULL,
    `journal_number` VARCHAR(255) NOT NULL,
    `journal_date` DATE NOT NULL,
    `description` TEXT NOT NULL,
    `source_type` ENUM('manual','payroll','sales','purchase','expense','transfer','tax','opening','adjustment','closing') NOT NULL DEFAULT 'manual',
    `source_id` BIGINT UNSIGNED NULL,
    `reference_number` VARCHAR(255) NULL,
    `total_debit` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_credit` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `status` ENUM('draft','approved','posted','void') NOT NULL DEFAULT 'draft',
    `reversal_of_id` BIGINT UNSIGNED NULL,
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `posted_by` BIGINT UNSIGNED NULL,
    `posted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `journal_entries_number_unique` (`organization_id`, `journal_number`),
    INDEX `journal_entries_date_index` (`organization_id`, `journal_date`),
    INDEX `journal_entries_source_index` (`source_type`, `source_id`),
    INDEX `journal_entries_period_index` (`accounting_period_id`),
    INDEX `journal_entries_reversal_fk` (`reversal_of_id`),
    INDEX `journal_entries_status_index` (`status`),
    INDEX `journal_entries_deleted_at_index` (`deleted_at`),
    CONSTRAINT `journal_entries_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `journal_entries_period_fk` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `journal_entries_reversal_fk` FOREIGN KEY (`reversal_of_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 52. journal_details
CREATE TABLE `journal_details` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `journal_entry_id` BIGINT UNSIGNED NOT NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `cost_center_id` BIGINT UNSIGNED NULL,
    `description` TEXT NULL,
    `debit` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `credit` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `reference_type` VARCHAR(100) NULL,
    `reference_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `journal_details_entry_index` (`journal_entry_id`),
    INDEX `journal_details_account_index` (`account_id`),
    INDEX `journal_details_cost_center_index` (`cost_center_id`),
    CONSTRAINT `journal_details_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `journal_details_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL,
    CONSTRAINT `journal_details_entry_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE,
    CONSTRAINT `journal_details_debit_credit_chk` CHECK ((`debit` >= 0) and (`credit` >= 0) and ((`debit` <= 0) or (`credit` <= 0)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 53. opening_balances
CREATE TABLE `opening_balances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_year_id` BIGINT UNSIGNED NOT NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `cost_center_id` BIGINT UNSIGNED NULL,
    `debit` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `credit` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `opening_balances_unique` (`organization_id`, `fiscal_year_id`, `account_id`, `cost_center_id`),
    INDEX `opening_balances_year_fk` (`fiscal_year_id`),
    INDEX `opening_balances_account_fk` (`account_id`),
    INDEX `opening_balances_cost_center_fk` (`cost_center_id`),
    INDEX `opening_balances_journal_fk` (`journal_entry_id`),
    INDEX `opening_balances_deleted_at_index` (`deleted_at`),
    CONSTRAINT `opening_balances_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `opening_balances_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL,
    CONSTRAINT `opening_balances_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `opening_balances_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `opening_balances_year_fk` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 54. budgets
CREATE TABLE `budgets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_year_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(100) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `status` ENUM('draft','approved','locked','cancelled') NOT NULL DEFAULT 'draft',
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `budgets_org_code_unique` (`organization_id`, `code`),
    INDEX `budgets_year_fk` (`fiscal_year_id`),
    INDEX `budgets_status_index` (`status`),
    INDEX `budgets_deleted_at_index` (`deleted_at`),
    CONSTRAINT `budgets_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `budgets_year_fk` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 55. budget_details
CREATE TABLE `budget_details` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `budget_id` BIGINT UNSIGNED NOT NULL,
    `accounting_period_id` BIGINT UNSIGNED NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `cost_center_id` BIGINT UNSIGNED NULL,
    `budget_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `actual_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `variance_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `variance_percentage` DECIMAL(7,2) NOT NULL DEFAULT 0.00,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `budget_details_budget_fk` (`budget_id`),
    INDEX `budget_details_period_fk` (`accounting_period_id`),
    INDEX `budget_details_account_fk` (`account_id`),
    INDEX `budget_details_cost_center_fk` (`cost_center_id`),
    INDEX `budget_details_deleted_at_index` (`deleted_at`),
    CONSTRAINT `budget_details_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `budget_details_budget_fk` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE CASCADE,
    CONSTRAINT `budget_details_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL,
    CONSTRAINT `budget_details_period_fk` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 56. bank_accounts
CREATE TABLE `bank_accounts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `bank_name` VARCHAR(255) NOT NULL,
    `account_number` VARCHAR(50) NOT NULL,
    `account_name` VARCHAR(255) NOT NULL,
    `currency_code` VARCHAR(10) NOT NULL DEFAULT 'IDR',
    `current_balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `is_default` TINYINT(1) NOT NULL DEFAULT 0,
    `bank_code` VARCHAR(50) NULL,
    `branch_name` VARCHAR(255) NULL,
    `swift_code` VARCHAR(50) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `bank_accounts_org_number_unique` (`organization_id`, `account_number`),
    INDEX `bank_accounts_account_fk` (`account_id`),
    INDEX `bank_accounts_is_active_index` (`is_active`),
    INDEX `bank_accounts_deleted_at_index` (`deleted_at`),
    CONSTRAINT `bank_accounts_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `bank_accounts_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 57. bank_transactions
CREATE TABLE `bank_transactions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `bank_account_id` BIGINT UNSIGNED NOT NULL,
    `transaction_date` DATE NOT NULL,
    `transaction_type` ENUM('debit','credit','transfer') NOT NULL,
    `description` TEXT NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `balance_after` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `reference_number` VARCHAR(255) NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `is_reconciled` TINYINT(1) NOT NULL DEFAULT 0,
    `reconciled_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `bank_transactions_account_date_index` (`bank_account_id`, `transaction_date`),
    INDEX `bank_transactions_journal_fk` (`journal_entry_id`),
    INDEX `bank_transactions_is_reconciled_index` (`is_reconciled`),
    INDEX `bank_transactions_deleted_at_index` (`deleted_at`),
    CONSTRAINT `bank_transactions_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `bank_transactions_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 58. reconciliations
CREATE TABLE `reconciliations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `bank_account_id` BIGINT UNSIGNED NOT NULL,
    `reconciliation_date` DATE NOT NULL,
    `statement_balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `book_balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `difference` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `status` ENUM('pending','completed','failed') NOT NULL DEFAULT 'pending',
    `notes` TEXT NULL,
    `completed_by` BIGINT UNSIGNED NULL,
    `completed_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `reconciliations_bank_fk` (`bank_account_id`),
    INDEX `reconciliations_status_index` (`status`),
    INDEX `reconciliations_deleted_at_index` (`deleted_at`),
    CONSTRAINT `reconciliations_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 59. reconciliation_details
CREATE TABLE `reconciliation_details` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `reconciliation_id` BIGINT UNSIGNED NOT NULL,
    `bank_transaction_id` BIGINT UNSIGNED NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `transaction_date` DATE NOT NULL,
    `description` TEXT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `type` ENUM('match','unmatch','adjustment') NOT NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `reconciliation_details_recon_fk` (`reconciliation_id`),
    INDEX `reconciliation_details_bank_fk` (`bank_transaction_id`),
    INDEX `reconciliation_details_journal_fk` (`journal_entry_id`),
    CONSTRAINT `reconciliation_details_bank_fk` FOREIGN KEY (`bank_transaction_id`) REFERENCES `bank_transactions` (`id`) ON DELETE SET NULL,
    CONSTRAINT `reconciliation_details_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `reconciliation_details_recon_fk` FOREIGN KEY (`reconciliation_id`) REFERENCES `reconciliations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 60. fixed_assets
CREATE TABLE `fixed_assets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `asset_code` VARCHAR(100) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `acquisition_date` DATE NOT NULL,
    `acquisition_cost` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `residual_value` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `useful_life_months` INT NOT NULL DEFAULT 0,
    `depreciation_method` ENUM('straight_line','declining_balance') NOT NULL DEFAULT 'straight_line',
    `accumulated_depreciation` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `book_value` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `asset_account_id` BIGINT UNSIGNED NOT NULL,
    `accumulated_depreciation_account_id` BIGINT UNSIGNED NULL,
    `depreciation_expense_account_id` BIGINT UNSIGNED NULL,
    `status` ENUM('draft','active','disposed','fully_depreciated') NOT NULL DEFAULT 'draft',
    `disposed_at` DATE NULL,
    `disposal_value` DECIMAL(15,2) NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fixed_assets_org_code_unique` (`organization_id`, `asset_code`),
    INDEX `fixed_assets_asset_account_fk` (`asset_account_id`),
    INDEX `fixed_assets_accum_account_fk` (`accumulated_depreciation_account_id`),
    INDEX `fixed_assets_depr_expense_account_fk` (`depreciation_expense_account_id`),
    INDEX `fixed_assets_status_index` (`status`),
    INDEX `fixed_assets_deleted_at_index` (`deleted_at`),
    CONSTRAINT `fixed_assets_accum_account_fk` FOREIGN KEY (`accumulated_depreciation_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fixed_assets_asset_account_fk` FOREIGN KEY (`asset_account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fixed_assets_depr_expense_account_fk` FOREIGN KEY (`depreciation_expense_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fixed_assets_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 61. asset_depreciations
CREATE TABLE `asset_depreciations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `fixed_asset_id` BIGINT UNSIGNED NOT NULL,
    `accounting_period_id` BIGINT UNSIGNED NOT NULL,
    `depreciation_date` DATE NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `asset_depreciations_asset_period_unique` (`fixed_asset_id`, `accounting_period_id`),
    INDEX `asset_depreciations_period_fk` (`accounting_period_id`),
    INDEX `asset_depreciations_journal_fk` (`journal_entry_id`),
    INDEX `asset_depreciations_deleted_at_index` (`deleted_at`),
    CONSTRAINT `asset_depreciations_asset_fk` FOREIGN KEY (`fixed_asset_id`) REFERENCES `fixed_assets` (`id`) ON DELETE CASCADE,
    CONSTRAINT `asset_depreciations_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `asset_depreciations_period_fk` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 62. vendors
CREATE TABLE `vendors` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NULL,
    `email` VARCHAR(255) NULL,
    `tax_number` VARCHAR(50) NULL,
    `address` TEXT NULL,
    `payable_account_id` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `vendors_org_code_unique` (`organization_id`, `code`),
    INDEX `vendors_payable_account_fk` (`payable_account_id`),
    INDEX `vendors_is_active_index` (`is_active`),
    INDEX `vendors_deleted_at_index` (`deleted_at`),
    CONSTRAINT `vendors_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `vendors_payable_account_fk` FOREIGN KEY (`payable_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 63. customers
CREATE TABLE `customers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NULL,
    `email` VARCHAR(255) NULL,
    `tax_number` VARCHAR(50) NULL,
    `address` TEXT NULL,
    `receivable_account_id` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `customers_org_code_unique` (`organization_id`, `code`),
    INDEX `customers_receivable_account_fk` (`receivable_account_id`),
    INDEX `customers_is_active_index` (`is_active`),
    INDEX `customers_deleted_at_index` (`deleted_at`),
    CONSTRAINT `customers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `customers_receivable_account_fk` FOREIGN KEY (`receivable_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 64. invoices
CREATE TABLE `invoices` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `customer_id` BIGINT UNSIGNED NULL,
    `invoice_number` VARCHAR(255) NOT NULL,
    `customer_name_snapshot` VARCHAR(255) NOT NULL,
    `customer_phone_snapshot` VARCHAR(50) NULL,
    `customer_email_snapshot` VARCHAR(255) NULL,
    `invoice_date` DATE NOT NULL,
    `due_date` DATE NOT NULL,
    `subtotal` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `tax` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `discount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `paid_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `remaining_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `status` ENUM('draft','sent','partial','paid','overdue','cancelled') NOT NULL DEFAULT 'draft',
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `notes` TEXT NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `invoices_org_number_unique` (`organization_id`, `invoice_number`),
    INDEX `invoices_customer_fk` (`customer_id`),
    INDEX `invoices_journal_fk` (`journal_entry_id`),
    INDEX `invoices_status_index` (`status`),
    INDEX `invoices_invoice_date_index` (`invoice_date`),
    INDEX `invoices_due_date_index` (`due_date`),
    INDEX `invoices_deleted_at_index` (`deleted_at`),
    CONSTRAINT `invoices_customer_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
    CONSTRAINT `invoices_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `invoices_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 65. invoice_items
CREATE TABLE `invoice_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` BIGINT UNSIGNED NOT NULL,
    `product_code` VARCHAR(100) NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `quantity` DECIMAL(15,4) NOT NULL DEFAULT 1.0000,
    `unit_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `discount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `tax_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `revenue_account_id` BIGINT UNSIGNED NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `invoice_items_invoice_fk` (`invoice_id`),
    INDEX `invoice_items_revenue_account_fk` (`revenue_account_id`),
    INDEX `invoice_items_deleted_at_index` (`deleted_at`),
    CONSTRAINT `invoice_items_invoice_fk` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
    CONSTRAINT `invoice_items_revenue_account_fk` FOREIGN KEY (`revenue_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 66. invoice_payments
CREATE TABLE `invoice_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` BIGINT UNSIGNED NOT NULL,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payment_number` VARCHAR(100) NOT NULL,
    `payment_date` DATE NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `payment_method` ENUM('cash','bank_transfer','check','other') NOT NULL,
    `bank_account_id` BIGINT UNSIGNED NULL,
    `reference_number` VARCHAR(255) NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `notes` TEXT NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `invoice_payments_org_number_unique` (`organization_id`, `payment_number`),
    INDEX `invoice_payments_invoice_fk` (`invoice_id`),
    INDEX `invoice_payments_bank_fk` (`bank_account_id`),
    INDEX `invoice_payments_journal_fk` (`journal_entry_id`),
    INDEX `invoice_payments_organization_id_index` (`organization_id`),
    INDEX `invoice_payments_deleted_at_index` (`deleted_at`),
    CONSTRAINT `invoice_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
    CONSTRAINT `invoice_payments_invoice_fk` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `invoice_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `invoice_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 67. expenses
CREATE TABLE `expenses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `vendor_id` BIGINT UNSIGNED NULL,
    `expense_number` VARCHAR(255) NOT NULL,
    `expense_date` DATE NOT NULL,
    `vendor_name_snapshot` VARCHAR(255) NULL,
    `description` TEXT NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `tax` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `payment_status` ENUM('unpaid','partial','paid') NOT NULL DEFAULT 'unpaid',
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `attachment_path` VARCHAR(500) NULL,
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `expenses_org_number_unique` (`organization_id`, `expense_number`),
    INDEX `expenses_vendor_fk` (`vendor_id`),
    INDEX `expenses_journal_fk` (`journal_entry_id`),
    INDEX `expenses_payment_status_index` (`payment_status`),
    INDEX `expenses_expense_date_index` (`expense_date`),
    INDEX `expenses_deleted_at_index` (`deleted_at`),
    CONSTRAINT `expenses_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `expenses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `expenses_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 68. expense_items
CREATE TABLE `expense_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `expense_id` BIGINT UNSIGNED NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `quantity` DECIMAL(15,4) NOT NULL DEFAULT 1.0000,
    `unit_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `cost_center_id` BIGINT UNSIGNED NULL,
    `tax_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `expense_items_expense_fk` (`expense_id`),
    INDEX `expense_items_account_fk` (`account_id`),
    INDEX `expense_items_cost_center_fk` (`cost_center_id`),
    INDEX `expense_items_deleted_at_index` (`deleted_at`),
    CONSTRAINT `expense_items_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `expense_items_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL,
    CONSTRAINT `expense_items_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 69. expense_payments
CREATE TABLE `expense_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `expense_id` BIGINT UNSIGNED NOT NULL,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payment_number` VARCHAR(100) NOT NULL,
    `payment_date` DATE NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `payment_method` ENUM('cash','bank_transfer','check','other') NOT NULL,
    `bank_account_id` BIGINT UNSIGNED NULL,
    `reference_number` VARCHAR(255) NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `expense_payments_org_number_unique` (`organization_id`, `payment_number`),
    INDEX `expense_payments_expense_fk` (`expense_id`),
    INDEX `expense_payments_bank_fk` (`bank_account_id`),
    INDEX `expense_payments_journal_fk` (`journal_entry_id`),
    INDEX `expense_payments_organization_id_index` (`organization_id`),
    INDEX `expense_payments_deleted_at_index` (`deleted_at`),
    CONSTRAINT `expense_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
    CONSTRAINT `expense_payments_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `expense_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `expense_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 70. tax_settings
CREATE TABLE `tax_settings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `tax_code` VARCHAR(50) NOT NULL,
    `tax_name` VARCHAR(255) NOT NULL,
    `tax_type` ENUM('pph21','pph23','pph25','ppn','ppnbm','other') NOT NULL,
    `tax_rate` DECIMAL(7,4) NOT NULL DEFAULT 0.0000,
    `min_amount` DECIMAL(15,2) NULL,
    `max_amount` DECIMAL(15,2) NULL,
    `effective_date` DATE NOT NULL,
    `expiry_date` DATE NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `description` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `tax_settings_org_code_unique` (`organization_id`, `tax_code`),
    INDEX `tax_settings_is_active_index` (`is_active`),
    INDEX `tax_settings_deleted_at_index` (`deleted_at`),
    CONSTRAINT `tax_settings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 71. tax_liabilities
CREATE TABLE `tax_liabilities` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `tax_type` ENUM('pph21','pph23','pph25','ppn','ppnbm','other') NOT NULL,
    `tax_period` VARCHAR(7) NOT NULL,
    `tax_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `paid_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `due_date` DATE NOT NULL,
    `status` ENUM('pending','partial','paid','overdue','cancelled') NOT NULL DEFAULT 'pending',
    `source_type` VARCHAR(100) NULL,
    `source_id` BIGINT UNSIGNED NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `tax_liabilities_org_period_index` (`organization_id`, `tax_type`, `tax_period`),
    INDEX `tax_liabilities_journal_fk` (`journal_entry_id`),
    INDEX `tax_liabilities_status_index` (`status`),
    INDEX `tax_liabilities_due_date_index` (`due_date`),
    INDEX `tax_liabilities_deleted_at_index` (`deleted_at`),
    CONSTRAINT `tax_liabilities_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `tax_liabilities_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 72. tax_payments
CREATE TABLE `tax_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tax_liability_id` BIGINT UNSIGNED NOT NULL,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payment_number` VARCHAR(100) NOT NULL,
    `payment_date` DATE NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `payment_reference` VARCHAR(255) NULL,
    `bank_account_id` BIGINT UNSIGNED NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `status` ENUM('draft','paid','cancelled') NOT NULL DEFAULT 'draft',
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `tax_payments_org_number_unique` (`organization_id`, `payment_number`),
    INDEX `tax_payments_liability_fk` (`tax_liability_id`),
    INDEX `tax_payments_bank_fk` (`bank_account_id`),
    INDEX `tax_payments_journal_fk` (`journal_entry_id`),
    INDEX `tax_payments_status_index` (`status`),
    INDEX `tax_payments_deleted_at_index` (`deleted_at`),
    CONSTRAINT `tax_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
    CONSTRAINT `tax_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `tax_payments_liability_fk` FOREIGN KEY (`tax_liability_id`) REFERENCES `tax_liabilities` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `tax_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 73. finance_audit_logs
CREATE TABLE `finance_audit_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `action` VARCHAR(100) NOT NULL,
    `table_name` VARCHAR(100) NOT NULL,
    `record_id` BIGINT UNSIGNED NULL,
    `old_data` JSON NULL,
    `new_data` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `finance_audit_logs_org_index` (`organization_id`),
    INDEX `finance_audit_logs_table_record_index` (`table_name`, `record_id`),
    INDEX `finance_audit_logs_created_index` (`created_at`),
    CONSTRAINT `finance_audit_logs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- USER & AUTHENTICATION TABLES
-- ============================================

-- 74. users
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` TIMESTAMP NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) NULL,
    `two_factor_secret` TEXT NULL,
    `two_factor_recovery_codes` TEXT NULL,
    `two_factor_confirmed_at` TIMESTAMP NULL,
    `last_login_at` TIMESTAMP NULL,
    `last_login_ip` VARCHAR(45) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `users_email_unique` (`email`),
    INDEX `users_email_verified_at_index` (`email_verified_at`),
    INDEX `users_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 75. roles
CREATE TABLE `roles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(191) NOT NULL,
    `display_name` VARCHAR(191) NOT NULL,
    `description` TEXT NULL,
    `is_system` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `roles_org_name_unique` (`organization_id`, `name`),
    INDEX `roles_is_system_index` (`is_system`),
    INDEX `roles_deleted_at_index` (`deleted_at`),
    CONSTRAINT `roles_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 76. menus
CREATE TABLE `menus` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NULL,
    `parent_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(255) NULL,
    `title` VARCHAR(191) NOT NULL,
    `flyout_title` VARCHAR(255) NULL,
    `slug` VARCHAR(191) NOT NULL,
    `icon` VARCHAR(191) NULL,
    `icon_type` ENUM('material','fa') NULL,
    `route` VARCHAR(191) NULL,
    `url` VARCHAR(255) NULL,
    `type` ENUM('topbar','sidebar','module') NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `sequence` INT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `en_text` VARCHAR(50) NULL,
    `id_text` VARCHAR(50) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `menus_slug_unique` (`slug`),
    INDEX `menus_parent_id_foreign` (`parent_id`),
    INDEX `menus_is_active_index` (`is_active`),
    INDEX `menus_deleted_at_index` (`deleted_at`),
    CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 77. menu_role
CREATE TABLE `menu_role` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `menu_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,
    `is_view` TINYINT(1) NOT NULL DEFAULT 0,
    `is_create` TINYINT(1) NOT NULL DEFAULT 0,
    `is_edit` TINYINT(1) NOT NULL DEFAULT 0,
    `is_delete` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `menu_role_menu_id_role_id_unique` (`menu_id`, `role_id`),
    INDEX `menu_role_menu_id_foreign` (`menu_id`),
    INDEX `menu_role_role_id_foreign` (`role_id`),
    INDEX `menu_role_deleted_at_index` (`deleted_at`),
    CONSTRAINT `menu_role_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
    CONSTRAINT `menu_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 78. actions
CREATE TABLE `actions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `menu_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(191) NOT NULL,
    `display_name` VARCHAR(191) NOT NULL,
    `description` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `actions_org_name_unique` (`organization_id`, `name`),
    INDEX `actions_menu_id_foreign` (`menu_id`),
    INDEX `actions_is_active_index` (`is_active`),
    INDEX `actions_deleted_at_index` (`deleted_at`),
    CONSTRAINT `actions_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
    CONSTRAINT `actions_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 79. action_role
CREATE TABLE `action_role` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `action_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `action_role_action_id_role_id_unique` (`action_id`, `role_id`),
    INDEX `action_role_action_id_foreign` (`action_id`),
    INDEX `action_role_role_id_foreign` (`role_id`),
    INDEX `action_role_deleted_at_index` (`deleted_at`),
    CONSTRAINT `action_role_action_id_foreign` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`) ON DELETE CASCADE,
    CONSTRAINT `action_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 80. action_user
CREATE TABLE `action_user` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `action_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `action_user_action_id_user_id_unique` (`action_id`, `user_id`),
    INDEX `action_user_action_id_foreign` (`action_id`),
    INDEX `action_user_user_id_foreign` (`user_id`),
    INDEX `action_user_deleted_at_index` (`deleted_at`),
    CONSTRAINT `action_user_action_id_foreign` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`) ON DELETE CASCADE,
    CONSTRAINT `action_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 81. role_user
CREATE TABLE `role_user` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `is_default` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `role_user_user_id_role_id_org_unique` (`user_id`, `role_id`, `organization_id`),
    UNIQUE INDEX `role_user_single_default_per_user_org` (`user_id`, `organization_id`, `is_default`),
    INDEX `role_user_role_id_foreign` (`role_id`),
    INDEX `role_user_organization_id_index` (`organization_id`),
    INDEX `role_user_deleted_at_index` (`deleted_at`),
    CONSTRAINT `role_user_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
    CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 82. position_user
CREATE TABLE `position_user` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `position_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `user_position_unique` (`user_id`, `position_id`),
    INDEX `position_user_position_id_index` (`position_id`),
    INDEX `position_user_deleted_at_index` (`deleted_at`),
    CONSTRAINT `position_user_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `position_user_position_fk` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SCHOOL / EDUCATION TABLES
-- ============================================

-- 83. schools
CREATE TABLE `schools` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `npsn` VARCHAR(255) NULL,
    `total_student` VARCHAR(191) NULL,
    `address` TEXT NULL,
    `status` ENUM('trial','active','suspended') NOT NULL DEFAULT 'trial',
    `trial_ends_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `schools_organization_id_index` (`organization_id`),
    INDEX `schools_status_index` (`status`),
    INDEX `schools_deleted_at_index` (`deleted_at`),
    CONSTRAINT `schools_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 84. education_levels
CREATE TABLE `education_levels` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `education_levels_org_code_unique` (`organization_id`, `code`),
    INDEX `education_levels_is_active_index` (`is_active`),
    INDEX `education_levels_deleted_at_index` (`deleted_at`),
    CONSTRAINT `education_levels_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 85. academic_years
CREATE TABLE `academic_years` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `semester_start_date` DATE NULL,
    `semester_end_date` DATE NULL,
    `is_registration_open` TINYINT(1) NULL DEFAULT 0,
    `registration_start_date` DATE NULL,
    `registration_end_date` DATE NULL,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `is_current` TINYINT(1) NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `academic_years_org_code_unique` (`organization_id`, `code`),
    INDEX `idx_academic_years_date_range` (`start_date`, `end_date`),
    INDEX `idx_academic_years_is_current` (`is_current`),
    INDEX `idx_academic_years_is_active` (`is_active`),
    INDEX `idx_academic_years_deleted_at` (`deleted_at`),
    CONSTRAINT `academic_years_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 86. subjects
CREATE TABLE `subjects` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `abbreviation` VARCHAR(20) NULL,
    `description` TEXT NULL,
    `category` ENUM('CORE','ELECTIVE','EXTRACURRICULAR','RELIGION','LOCAL_CONTENT') NULL,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `subjects_org_code_unique` (`organization_id`, `code`),
    INDEX `idx_subjects_category` (`category`),
    INDEX `idx_subjects_is_active` (`is_active`),
    INDEX `idx_subjects_deleted_at` (`deleted_at`),
    CONSTRAINT `subjects_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 87. class_levels
CREATE TABLE `class_levels` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `education_level_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `level_order` INT NOT NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `class_levels_org_code_unique` (`organization_id`, `code`),
    INDEX `idx_class_levels_education` (`education_level_id`),
    INDEX `idx_class_levels_order` (`level_order`),
    INDEX `idx_class_levels_is_active` (`is_active`),
    INDEX `idx_class_levels_deleted_at` (`deleted_at`),
    CONSTRAINT `class_levels_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `class_levels_education_fk` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 88. classrooms
CREATE TABLE `classrooms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `capacity` INT NULL DEFAULT 30,
    `homeroom_teacher_id` BIGINT UNSIGNED NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `classrooms_org_code_unique` (`organization_id`, `code`),
    INDEX `idx_classrooms_level` (`class_level_id`),
    INDEX `idx_classrooms_academic_year` (`academic_year_id`),
    INDEX `idx_classrooms_teacher` (`homeroom_teacher_id`),
    INDEX `idx_classrooms_is_active` (`is_active`),
    INDEX `idx_classrooms_deleted_at` (`deleted_at`),
    CONSTRAINT `classrooms_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `classrooms_level_fk` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `classrooms_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 89. students
CREATE TABLE `students` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `nis` VARCHAR(50) NOT NULL,
    `nisn` VARCHAR(50) NULL,
    `registration_number` VARCHAR(50) NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `birth_place` VARCHAR(100) NULL,
    `birth_date` DATE NULL,
    `gender` ENUM('MALE','FEMALE') NULL,
    `address` TEXT NULL,
    `religion` VARCHAR(20) NULL,
    `parents_phone` VARCHAR(20) NULL,
    `emergency_contact` VARCHAR(20) NULL,
    `medical_notes` TEXT NULL,
    `join_date` DATE NULL,
    `status` ENUM('ACTIVE','GRADUATED','TRANSFERRED','DROPPED','SUSPENDED') NULL DEFAULT 'ACTIVE',
    `admission_status` ENUM('NEW','TRANSFER','RETURNING') NULL,
    `enrollment_year` INT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `students_org_nis_unique` (`organization_id`, `nis`),
    UNIQUE INDEX `students_org_nisn_unique` (`organization_id`, `nisn`),
    INDEX `idx_students_organization_id` (`organization_id`),
    INDEX `idx_students_status` (`status`),
    INDEX `idx_students_birth_date` (`birth_date`),
    INDEX `idx_students_deleted_at` (`deleted_at`),
    CONSTRAINT `students_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 90. parents
CREATE TABLE `parents` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `email` VARCHAR(100) NULL,
    `address` TEXT NULL,
    `relationship` ENUM('FATHER','MOTHER','GUARDIAN') NULL,
    `work` VARCHAR(100) NULL,
    `is_primary` TINYINT(1) NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_parents_organization_id` (`organization_id`),
    INDEX `idx_parents_user_id` (`user_id`),
    INDEX `idx_parents_phone` (`phone`),
    INDEX `idx_parents_deleted_at` (`deleted_at`),
    CONSTRAINT `parents_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 91. student_parents
CREATE TABLE `student_parents` (
    `student_id` BIGINT UNSIGNED NOT NULL,
    `parent_id` BIGINT UNSIGNED NOT NULL,
    `is_primary` TINYINT(1) NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`student_id`, `parent_id`),
    INDEX `idx_student_parents_student` (`student_id`),
    INDEX `idx_student_parents_parent` (`parent_id`),
    INDEX `idx_student_parents_deleted_at` (`deleted_at`),
    CONSTRAINT `student_parents_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    CONSTRAINT `student_parents_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 92. student_classrooms
CREATE TABLE `student_classrooms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `enrollment_date` DATE NULL,
    `status` ENUM('ACTIVE','TRANSFERRED','DROPPED','GRADUATED') NULL DEFAULT 'ACTIVE',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_classrooms_unique` (`student_id`, `classroom_id`, `academic_year_id`),
    INDEX `idx_student_classrooms_student` (`student_id`),
    INDEX `idx_student_classrooms_classroom` (`classroom_id`),
    INDEX `idx_student_classrooms_academic_year` (`academic_year_id`),
    INDEX `idx_student_classrooms_deleted_at` (`deleted_at`),
    CONSTRAINT `student_classrooms_student_fk` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    CONSTRAINT `student_classrooms_classroom_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE,
    CONSTRAINT `student_classrooms_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 93. teachers
CREATE TABLE `teachers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `nip` VARCHAR(50) NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `specialization` VARCHAR(100) NULL,
    `education_level` VARCHAR(50) NULL,
    `join_date` DATE NULL,
    `status` ENUM('ACTIVE','RESIGNED','RETIRED') NULL DEFAULT 'ACTIVE',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `teachers_org_nip_unique` (`organization_id`, `nip`),
    INDEX `idx_teachers_organization_id` (`organization_id`),
    INDEX `idx_teachers_status` (`status`),
    INDEX `idx_teachers_deleted_at` (`deleted_at`),
    CONSTRAINT `teachers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 94. teaching_loads
CREATE TABLE `teaching_loads` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `teacher_id` BIGINT UNSIGNED NOT NULL,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `semester` ENUM('SEMESTER_1','SEMESTER_2') NULL,
    `total_hours` INT NULL DEFAULT 0,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `teaching_loads_unique` (`teacher_id`, `classroom_id`, `subject_id`, `academic_year_id`, `semester`),
    INDEX `idx_teaching_loads_teacher` (`teacher_id`),
    INDEX `idx_teaching_loads_classroom` (`classroom_id`),
    INDEX `idx_teaching_loads_academic_year` (`academic_year_id`),
    INDEX `idx_teaching_loads_deleted_at` (`deleted_at`),
    CONSTRAINT `teaching_loads_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
    CONSTRAINT `teaching_loads_classroom_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE,
    CONSTRAINT `teaching_loads_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `teaching_loads_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 95. courses
CREATE TABLE `courses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `subject_id` BIGINT UNSIGNED NULL,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `thumbnail_url` TEXT NULL,
    `level` ENUM('BEGINNER','INTERMEDIATE','ADVANCED') NULL,
    `duration_hours` INT NULL DEFAULT 0,
    `is_published` TINYINT(1) NULL DEFAULT 0,
    `published_at` TIMESTAMP NULL,
    `enrollment_count` INT NULL DEFAULT 0,
    `rating` DECIMAL(3,2) NULL DEFAULT 0.00,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `courses_org_slug_unique` (`organization_id`, `slug`),
    INDEX `idx_courses_teacher` (`teacher_id`),
    INDEX `idx_courses_subject` (`subject_id`),
    INDEX `idx_courses_is_published` (`is_published`),
    INDEX `idx_courses_deleted_at` (`deleted_at`),
    CONSTRAINT `courses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `courses_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL,
    CONSTRAINT `courses_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 96. lessons
CREATE TABLE `lessons` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `content` TEXT NULL,
    `video_url` TEXT NULL,
    `duration_minutes` INT NULL DEFAULT 0,
    `order_index` INT NOT NULL,
    `is_free_preview` TINYINT(1) NULL DEFAULT 0,
    `is_published` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_lessons_course` (`course_id`),
    INDEX `idx_lessons_order` (`order_index`),
    INDEX `idx_lessons_deleted_at` (`deleted_at`),
    CONSTRAINT `lessons_course_fk` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 97. curriculum_structures
CREATE TABLE `curriculum_structures` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `semester` ENUM('SEMESTER_1','SEMESTER_2') NULL,
    `hours_per_week` INT NOT NULL,
    `hour_duration` INT NULL DEFAULT 45,
    `is_core` TINYINT(1) NULL DEFAULT 1,
    `min_student` INT NULL DEFAULT 1,
    `max_student` INT NULL DEFAULT 40,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `curriculum_structures_unique` (`organization_id`, `class_level_id`, `subject_id`, `academic_year_id`, `semester`),
    INDEX `idx_curriculum_class` (`class_level_id`),
    INDEX `idx_curriculum_subject` (`subject_id`),
    INDEX `idx_curriculum_deleted_at` (`deleted_at`),
    CONSTRAINT `curriculum_structures_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `curriculum_structures_class_fk` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `curriculum_structures_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `curriculum_structures_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 98. schedules
CREATE TABLE `schedules` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `day_of_week` ENUM('MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY') NOT NULL,
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `room_id` BIGINT UNSIGNED NULL,
    `is_locked` TINYINT(1) NULL DEFAULT 0,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_schedules_classroom` (`classroom_id`),
    INDEX `idx_schedules_teacher` (`teacher_id`),
    INDEX `idx_schedules_day_time` (`day_of_week`, `start_time`),
    INDEX `idx_schedules_academic_year` (`academic_year_id`),
    INDEX `idx_schedules_deleted_at` (`deleted_at`),
    CONSTRAINT `schedules_classroom_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE,
    CONSTRAINT `schedules_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `schedules_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL,
    CONSTRAINT `schedules_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- LOOKUP TABLES
-- ============================================

-- 99. blood_types
CREATE TABLE `blood_types` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(5) NOT NULL,
    `rhesus` ENUM('+','-','') NOT NULL DEFAULT '',
    `name` VARCHAR(10) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `blood_types_name_unique` (`name`),
    INDEX `blood_types_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 100. genders
CREATE TABLE `genders` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` CHAR(1) NOT NULL,
    `name` VARCHAR(20) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `genders_code_unique` (`code`),
    INDEX `genders_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 101. marital_statuses
CREATE TABLE `marital_statuses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(10) NULL,
    `name` VARCHAR(50) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `marital_statuses_name_unique` (`name`),
    INDEX `marital_statuses_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 102. religions
CREATE TABLE `religions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(10) NULL,
    `name` VARCHAR(50) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `religions_name_unique` (`name`),
    INDEX `religions_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 103. identity_types
CREATE TABLE `identity_types` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `max_length` TINYINT UNSIGNED NULL,
    `is_required` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `identity_types_code_unique` (`code`),
    INDEX `identity_types_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 104. ptkp_statuses
CREATE TABLE `ptkp_statuses` (
    `id` VARCHAR(10) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `ptkp_statuses_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SYSTEM TABLES
-- ============================================

-- 105. buildings
CREATE TABLE `buildings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `address` VARCHAR(255) NULL,
    `total_floors` INT NOT NULL DEFAULT 0,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `buildings_org_code_unique` (`organization_id`, `code`),
    INDEX `buildings_is_active_index` (`is_active`),
    INDEX `buildings_deleted_at_index` (`deleted_at`),
    CONSTRAINT `buildings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 106. floors
CREATE TABLE `floors` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `building_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `floor_number` INT NOT NULL,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `floors_building_code_unique` (`building_id`, `code`),
    INDEX `floors_building_id_foreign` (`building_id`),
    INDEX `floors_is_active_index` (`is_active`),
    INDEX `floors_deleted_at_index` (`deleted_at`),
    CONSTRAINT `floors_building_fk` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 107. rooms
CREATE TABLE `rooms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `floor_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(100) NOT NULL,
    `name` VARCHAR(191) NOT NULL,
    `room_number` VARCHAR(255) NULL,
    `capacity` INT NULL DEFAULT 0,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `rooms_floor_code_unique` (`floor_id`, `code`),
    INDEX `rooms_floor_id_foreign` (`floor_id`),
    INDEX `rooms_is_active_index` (`is_active`),
    INDEX `rooms_deleted_at_index` (`deleted_at`),
    CONSTRAINT `rooms_floor_fk` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 108. facility_categories
CREATE TABLE `facility_categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `facility_categories_org_code_unique` (`organization_id`, `code`),
    INDEX `facility_categories_is_active_index` (`is_active`),
    INDEX `facility_categories_deleted_at_index` (`deleted_at`),
    CONSTRAINT `facility_categories_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 109. facilities
CREATE TABLE `facilities` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `facility_category_id` BIGINT UNSIGNED NOT NULL,
    `room_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(100) NOT NULL,
    `name` VARCHAR(191) NOT NULL,
    `description` TEXT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `is_available` TINYINT(1) NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `facilities_org_code_unique` (`organization_id`, `code`),
    INDEX `facilities_facility_category_id_foreign` (`facility_category_id`),
    INDEX `facilities_room_id_foreign` (`room_id`),
    INDEX `facilities_is_active_index` (`is_active`),
    INDEX `facilities_deleted_at_index` (`deleted_at`),
    CONSTRAINT `facilities_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `facilities_category_fk` FOREIGN KEY (`facility_category_id`) REFERENCES `facility_categories` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `facilities_room_fk` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 110. dictionaries
CREATE TABLE `dictionaries` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `key` VARCHAR(255) NOT NULL,
    `group` VARCHAR(255) NULL,
    `en` TEXT NULL,
    `id` TEXT NULL,
    `metadata` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `dictionaries_org_key_group_unique` (`organization_id`, `key`, `group`),
    INDEX `dictionaries_is_active_index` (`is_active`),
    INDEX `dictionaries_deleted_at_index` (`deleted_at`),
    CONSTRAINT `dictionaries_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- MODULES & PACKAGES TABLES
-- ============================================

-- 111. modules
CREATE TABLE `modules` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NULL,
    `code` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `icon` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `price` DECIMAL(10,2) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `modules_code_unique` (`code`),
    INDEX `modules_is_active_index` (`is_active`),
    INDEX `modules_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 112. packages
CREATE TABLE `packages` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NULL,
    `code` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `price` INT NOT NULL DEFAULT 0,
    `description` TEXT NOT NULL,
    `features` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `packages_code_unique` (`code`),
    INDEX `packages_is_active_index` (`is_active`),
    INDEX `packages_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 113. trial_registrations
CREATE TABLE `trial_registrations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NULL,
    `token` CHAR(36) NOT NULL,
    `current_step` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `status` ENUM('in_progress','completed','abandoned') NOT NULL DEFAULT 'in_progress',
    `nama_sekolah` VARCHAR(255) NULL,
    `npsn` VARCHAR(255) NULL,
    `total_student` VARCHAR(255) NULL,
    `address` VARCHAR(255) NULL,
    `nama_user` VARCHAR(255) NULL,
    `position_id` INT NULL,
    `notif_whatsapp` TINYINT(1) NOT NULL DEFAULT 0,
    `notif_email` TINYINT(1) NOT NULL DEFAULT 0,
    `notif_sms` TINYINT(1) NOT NULL DEFAULT 0,
    `email` VARCHAR(255) NULL,
    `no_hp` VARCHAR(255) NULL,
    `password` VARCHAR(255) NULL,
    `school_id` BIGINT UNSIGNED NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `completed_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `trial_registrations_token_unique` (`token`),
    INDEX `trial_registrations_status_index` (`status`),
    INDEX `trial_registrations_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 114. trial_registration_education_level
CREATE TABLE `trial_registration_education_level` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `trial_registration_id` BIGINT UNSIGNED NOT NULL,
    `education_level_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `tr_education_level_unique` (`trial_registration_id`, `education_level_id`),
    INDEX `trial_registration_education_level_education_fk` (`education_level_id`),
    CONSTRAINT `tr_education_level_reg_fk` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `tr_education_level_edu_fk` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 115. trial_registration_module
CREATE TABLE `trial_registration_module` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `trial_registration_id` BIGINT UNSIGNED NOT NULL,
    `module_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `tr_module_unique` (`trial_registration_id`, `module_id`),
    INDEX `trial_registration_module_module_fk` (`module_id`),
    CONSTRAINT `tr_module_reg_fk` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `tr_module_module_fk` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 116. trial_registration_role
CREATE TABLE `trial_registration_role` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `trial_registration_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `tr_role_unique` (`trial_registration_id`, `role_id`),
    INDEX `trial_registration_role_role_fk` (`role_id`),
    CONSTRAINT `tr_role_reg_fk` FOREIGN KEY (`trial_registration_id`) REFERENCES `trial_registrations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `tr_role_role_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- AUDIT & SYSTEM LOGS TABLES
-- ============================================

-- 117. audit_trails
CREATE TABLE `audit_trails` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `table_name` VARCHAR(100) NOT NULL,
    `record_id` BIGINT UNSIGNED NOT NULL,
    `action` ENUM('INSERT','UPDATE','DELETE','RESTORE') NOT NULL,
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_audit_trails_org` (`organization_id`),
    INDEX `idx_audit_trails_table_record` (`table_name`, `record_id`),
    INDEX `idx_audit_trails_created` (`created_at`),
    CONSTRAINT `audit_trails_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 118. system_logs
CREATE TABLE `system_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NULL,
    `level` ENUM('debug','info','warning','error','critical') NOT NULL,
    `message` TEXT NOT NULL,
    `context` JSON NULL,
    `channel` VARCHAR(50) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_system_logs_level` (`level`),
    INDEX `idx_system_logs_created` (`created_at`),
    INDEX `idx_system_logs_org` (`organization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 119. scheduled_jobs
CREATE TABLE `scheduled_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `command` VARCHAR(255) NOT NULL,
    `schedule` VARCHAR(100) NOT NULL,
    `last_run` TIMESTAMP NULL,
    `next_run` TIMESTAMP NULL,
    `status` ENUM('active','inactive','failed') DEFAULT 'active',
    `last_error` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `scheduled_jobs_org_name_unique` (`organization_id`, `name`),
    INDEX `scheduled_jobs_status_index` (`status`),
    INDEX `scheduled_jobs_deleted_at_index` (`deleted_at`),
    CONSTRAINT `scheduled_jobs_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 120. api_rate_limits
CREATE TABLE `api_rate_limits` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `api_key` VARCHAR(100) NOT NULL,
    `endpoint` VARCHAR(255) NOT NULL,
    `requests` INT NOT NULL DEFAULT 0,
    `limit` INT NOT NULL DEFAULT 1000,
    `reset_at` TIMESTAMP NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_api_rate_limits_key_endpoint` (`api_key`, `endpoint`),
    INDEX `idx_api_rate_limits_org` (`organization_id`),
    CONSTRAINT `api_rate_limits_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SESSION & CACHE TABLES
-- ============================================

-- 121. sessions
CREATE TABLE `sessions` (
    `id` VARCHAR(191) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `sessions_user_id_index` (`user_id`),
    INDEX `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 122. cache
CREATE TABLE `cache` (
    `key` VARCHAR(191) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 123. cache_locks
CREATE TABLE `cache_locks` (
    `key` VARCHAR(191) NOT NULL,
    `owner` VARCHAR(191) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 124. jobs
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(191) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 125. job_batches
CREATE TABLE `job_batches` (
    `id` VARCHAR(191) NOT NULL,
    `name` VARCHAR(191) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL,
    `cancelled_at` INT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 126. failed_jobs
CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(191) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 127. migrations
CREATE TABLE `migrations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 128. password_reset_tokens
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(191) NOT NULL,
    `token` VARCHAR(191) NOT NULL,
    `created_at` TIMESTAMP NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 129. navigation
CREATE TABLE `navigation` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NULL,
    `title` VARCHAR(255) NULL,
    `subtitle` VARCHAR(255) NULL,
    `intro` VARCHAR(255) NULL,
    `description` VARCHAR(255) NULL,
    `parent_id` INT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `navigation_parent_id_index` (`parent_id`),
    INDEX `navigation_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 130. student_ranges
CREATE TABLE `student_ranges` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `min_value` INT NULL,
    `max_value` INT NULL,
    `sort_order` INT NOT NULL,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `student_ranges_org_fk` (`organization_id`),
    INDEX `student_ranges_is_active_index` (`is_active`),
    INDEX `student_ranges_deleted_at_index` (`deleted_at`),
    CONSTRAINT `student_ranges_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- EXPENSE CATEGORIES (untuk backward compatibility)
-- ============================================

-- 131. expense_categories
CREATE TABLE `expense_categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `parent_id` BIGINT UNSIGNED NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `expense_categories_org_code_unique` (`organization_id`, `code`),
    UNIQUE INDEX `expense_categories_org_name_unique` (`organization_id`, `name`),
    INDEX `expense_categories_parent_id_index` (`parent_id`),
    INDEX `expense_categories_is_active_index` (`is_active`),
    INDEX `expense_categories_deleted_at_index` (`deleted_at`),
    CONSTRAINT `expense_categories_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `expense_categories_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;