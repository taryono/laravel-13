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
SET
    NAMES utf8mb4;

SET
    FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- DROP TABLES (URUTAN BERDASARKAN DEPENDENSI)
-- ============================================
DROP TABLE IF EXISTS `audit_trails`;

DROP TABLE IF EXISTS `system_logs`;

DROP TABLE IF EXISTS `scheduled_jobs`;

DROP TABLE IF EXISTS `api_rate_limits`;

DROP TABLE IF EXISTS `payroll_audit_logs`;

DROP TABLE IF EXISTS `finance_audit_logs`;
DROP TABLE IF EXISTS `journal_details`;

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
DROP TABLE IF EXISTS `journal_entries`;

-- ============================================
-- MASTER DATA TABLES
-- ============================================
-- 1. organizations
CREATE TABLE `organizations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `legal_name` VARCHAR(255) NULL,
    `organization_type` ENUM(
        'foundation',
        'company',
        'school',
        'unit',
        'other'
    ) NOT NULL DEFAULT 'foundation',
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
    CONSTRAINT `organizations_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `organizations` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    CONSTRAINT `departments_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 6. positions
CREATE TABLE `positions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `level` INT NULL,
    `salary_range_min` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `salary_range_max` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    CONSTRAINT `cost_centers_department_fk` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `cost_centers_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
        CONSTRAINT `cost_centers_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `cost_centers` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    `gender` ENUM('L', 'P') NULL,
    `birth_place` VARCHAR(255) NULL,
    `birth_date` DATE NULL,
    `address` TEXT NULL,
    `phone` VARCHAR(20) NULL,
    `email` VARCHAR(255) NULL,
    `join_date` DATE NOT NULL,
    `resign_date` DATE NULL,
    `employment_status` ENUM('tetap', 'kontrak', 'probation', 'freelance') NOT NULL DEFAULT 'probation',
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
    CONSTRAINT `employees_department_fk` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `employees_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
        CONSTRAINT `employees_position_fk` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `employees_salary_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `employees_supervisor_fk` FOREIGN KEY (`supervisor_id`) REFERENCES `employees` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 9. employee_mutations
CREATE TABLE `employee_mutations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `mutation_code` VARCHAR(255) NOT NULL,
    `mutation_type` ENUM(
        'promotion',
        'demotion',
        'transfer',
        'department_change',
        'position_change',
        'salary_change'
    ) NOT NULL,
    `old_department_id` BIGINT UNSIGNED NULL,
    `new_department_id` BIGINT UNSIGNED NULL,
    `old_position_id` BIGINT UNSIGNED NULL,
    `new_position_id` BIGINT UNSIGNED NULL,
    `old_salary` DECIMAL(15, 2) NULL,
    `new_salary` DECIMAL(15, 2) NULL,
    `effective_date` DATE NOT NULL,
    `notes` TEXT NULL,
    `status` ENUM(
        'draft',
        'submitted',
        'approved',
        'rejected',
        'cancelled'
    ) NOT NULL DEFAULT 'draft',
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
    CONSTRAINT `employee_mutations_old_dept_fk` FOREIGN KEY (`old_department_id`) REFERENCES `departments` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `employee_mutations_new_dept_fk` FOREIGN KEY (`new_department_id`) REFERENCES `departments` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `employee_mutations_old_pos_fk` FOREIGN KEY (`old_position_id`) REFERENCES `positions` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `employee_mutations_new_pos_fk` FOREIGN KEY (`new_position_id`) REFERENCES `positions` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 11. shift_rates
CREATE TABLE `shift_rates` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `shift_id` BIGINT UNSIGNED NOT NULL,
    `day_type` ENUM(
        'weekday',
        'saturday',
        'sunday',
        'national_holiday',
        'special_holiday'
    ) NOT NULL,
    `base_rate_per_shift` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `overtime_multiplier` DECIMAL(5, 2) NOT NULL DEFAULT 1.50,
    `overtime_rate_per_hour` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `night_allowance_per_shift` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `meal_allowance_per_shift` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `transport_allowance_per_shift` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 12. employee_shifts
CREATE TABLE `employee_shifts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    `status` ENUM('pending', 'approved', 'rejected', 'cancelled') NULL DEFAULT 'pending',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 14. holidays
CREATE TABLE `holidays` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `type` ENUM('national', 'special', 'company', 'religious') NOT NULL DEFAULT 'national',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 15. attendance_logs
CREATE TABLE `attendance_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `attendance_date` DATE NOT NULL,
    `check_in` DATETIME NULL,
    `check_out` DATETIME NULL,
    `source` ENUM('manual', 'machine', 'import', 'api', 'mobile') NOT NULL DEFAULT 'manual',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 16. attendance_dailies
CREATE TABLE `attendance_dailies` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `attendance_date` DATE NOT NULL,
    `status` ENUM(
        'present',
        'absent',
        'late',
        'leave',
        'holiday',
        'off',
        'half_day',
        'sick',
        'permit'
    ) NOT NULL,
    `work_hours` DECIMAL(8, 2) NOT NULL DEFAULT 0.00,
    `overtime_hours` DECIMAL(8, 2) NOT NULL DEFAULT 0.00,
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
    CONSTRAINT `attendance_dailies_log_fk` FOREIGN KEY (`source_log_id`) REFERENCES `attendance_logs` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `attendance_dailies_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    `status` ENUM(
        'uploaded',
        'processing',
        'completed',
        'failed',
        'partial'
    ) NULL DEFAULT 'uploaded',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 18. attendances
CREATE TABLE `attendances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `attendance_daily_id` BIGINT UNSIGNED NULL,
    `payroll_period_id` BIGINT UNSIGNED NULL,
    `attendance_date` DATE NOT NULL,
    `status` ENUM(
        'hadir',
        'sakit',
        'izin',
        'alpha',
        'cuti',
        'dinas_luar',
        'training',
        'other'
    ) NOT NULL DEFAULT 'hadir',
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
    CONSTRAINT `attendances_daily_fk` FOREIGN KEY (`attendance_daily_id`) REFERENCES `attendance_dailies` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `attendances_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 19. overtimes
CREATE TABLE `overtimes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `attendance_daily_id` BIGINT UNSIGNED NULL,
    `overtime_date` DATE NOT NULL,
    `start_at` DATETIME NULL,
    `end_at` DATETIME NULL,
    `hours` DECIMAL(8, 2) NOT NULL DEFAULT 0.00,
    `hourly_rate` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `rate_multiplier` DECIMAL(8, 4) NOT NULL DEFAULT 1.0000,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `status` ENUM(
        'draft',
        'submitted',
        'approved',
        'rejected',
        'cancelled'
    ) NOT NULL DEFAULT 'draft',
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
    CONSTRAINT `overtimes_daily_fk` FOREIGN KEY (`attendance_daily_id`) REFERENCES `attendance_dailies` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `overtimes_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
        CONSTRAINT `overtimes_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 20. leave_types
CREATE TABLE `leave_types` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `default_days` DECIMAL(8, 2) NOT NULL DEFAULT 0.00,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 21. leave_balances
CREATE TABLE `leave_balances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `leave_type_id` BIGINT UNSIGNED NOT NULL,
    `year` INT NOT NULL,
    `allocated_days` DECIMAL(8, 2) NOT NULL DEFAULT 0.00,
    `used_days` DECIMAL(8, 2) NOT NULL DEFAULT 0.00,
    `remaining_days` DECIMAL(8, 2) NOT NULL DEFAULT 0.00,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 22. employee_leaves
CREATE TABLE `employee_leaves` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `leave_type_id` BIGINT UNSIGNED NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `total_days` DECIMAL(8, 2) NOT NULL DEFAULT 0.00,
    `reason` TEXT NULL,
    `attachment_path` VARCHAR(500) NULL,
    `status` ENUM(
        'draft',
        'submitted',
        'approved',
        'rejected',
        'cancelled'
    ) NOT NULL DEFAULT 'draft',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 23. loans
CREATE TABLE `loans` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `loan_number` VARCHAR(100) NOT NULL,
    `loan_date` DATE NOT NULL,
    `principal_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `installment_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_installments` INT NOT NULL DEFAULT 0,
    `remaining_installments` INT NOT NULL DEFAULT 0,
    `status` ENUM(
        'draft',
        'active',
        'completed',
        'cancelled',
        'defaulted'
    ) NOT NULL DEFAULT 'draft',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 24. loan_installments
CREATE TABLE `loan_installments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `loan_id` BIGINT UNSIGNED NOT NULL,
    `installment_number` INT NOT NULL,
    `due_date` DATE NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `paid_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `status` ENUM(
        'pending',
        'paid',
        'partial',
        'cancelled',
        'overdue'
    ) NOT NULL DEFAULT 'pending',
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
    CONSTRAINT `loan_installments_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 25. bonuses
CREATE TABLE `bonuses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `bonus_code` VARCHAR(255) NOT NULL,
    `bonus_type` ENUM(
        'performance',
        'thr',
        'bonus_year_end',
        'project_bonus',
        'referral',
        'attendance',
        'other'
    ) NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL,
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
    CONSTRAINT `bonuses_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 26. deduction_categories
CREATE TABLE `deduction_categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `category_type` ENUM('mandatory', 'voluntary', 'other') NOT NULL DEFAULT 'other',
    `deduction_group` ENUM(
        'insurance',
        'tax',
        'saving',
        'loan',
        'penalty',
        'other'
    ) NOT NULL DEFAULT 'other',
    `calculation_type` ENUM('fixed', 'percentage', 'sliding', 'custom') NOT NULL DEFAULT 'fixed',
    `default_amount` DECIMAL(15, 2) NULL,
    `default_percentage` DECIMAL(5, 2) NULL,
    `min_amount` DECIMAL(15, 2) NULL,
    `max_amount` DECIMAL(15, 2) NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 27. deductions
CREATE TABLE `deductions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `payroll_period_id` BIGINT UNSIGNED NULL,
    `deduction_category_id` BIGINT UNSIGNED NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL,
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
    CONSTRAINT `deductions_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `deductions_category_fk` FOREIGN KEY (`deduction_category_id`) REFERENCES `deduction_categories` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 28. salary_components
CREATE TABLE `salary_components` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `type` ENUM(
        'earning',
        'deduction',
        'company_contribution',
        'tax'
    ) NOT NULL,
    `calculation_type` ENUM(
        'fixed',
        'percentage',
        'formula',
        'attendance',
        'loan',
        'tax',
        'manual'
    ) NOT NULL DEFAULT 'fixed',
    `calculation_base` VARCHAR(100) NULL,
    `default_value` DECIMAL(15, 4) NOT NULL DEFAULT 0.0000,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 30. salary_structure_components
CREATE TABLE `salary_structure_components` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `salary_structure_id` BIGINT UNSIGNED NOT NULL,
    `salary_component_id` BIGINT UNSIGNED NOT NULL,
    `calculation_type` ENUM('fixed', 'percentage', 'formula', 'inherit') NOT NULL DEFAULT 'inherit',
    `value` DECIMAL(15, 4) NOT NULL DEFAULT 0.0000,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 31. employee_salary_components
CREATE TABLE `employee_salary_components` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `salary_component_id` BIGINT UNSIGNED NOT NULL,
    `value` DECIMAL(15, 4) NOT NULL DEFAULT 0.0000,
    `effective_from` DATE NOT NULL,
    `effective_to` DATE NULL,
    `source` ENUM('structure', 'override', 'manual') NOT NULL DEFAULT 'override',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 32. employee_contracts
CREATE TABLE `employee_contracts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `contract_number` VARCHAR(100) NOT NULL,
    `contract_type` ENUM(
        'tetap',
        'kontrak',
        'probation',
        'freelance',
        'project'
    ) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NULL,
    `salary` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `salary_structure_id` BIGINT UNSIGNED NULL,
    `status` ENUM(
        'draft',
        'active',
        'expired',
        'terminated',
        'extended'
    ) NOT NULL DEFAULT 'draft',
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
    CONSTRAINT `employee_contracts_salary_structure_fk` FOREIGN KEY (`salary_structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 33. employee_bonuses
CREATE TABLE `employee_bonuses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `bonus_number` VARCHAR(100) NOT NULL,
    `bonus_date` DATE NOT NULL,
    `bonus_type` VARCHAR(100) NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `payroll_detail_id` BIGINT UNSIGNED NULL,
    `status` ENUM('draft', 'approved', 'paid', 'cancelled') NOT NULL DEFAULT 'draft',
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
    CONSTRAINT `employee_bonuses_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 34. employee_deductions
CREATE TABLE `employee_deductions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `deduction_number` VARCHAR(100) NOT NULL,
    `deduction_date` DATE NOT NULL,
    `deduction_type` VARCHAR(100) NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `recurring` TINYINT(1) NOT NULL DEFAULT 0,
    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `payroll_detail_id` BIGINT UNSIGNED NULL,
    `status` ENUM(
        'draft',
        'approved',
        'active',
        'paid',
        'cancelled'
    ) NOT NULL DEFAULT 'draft',
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
    CONSTRAINT `employee_deductions_payroll_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 36. payroll_periods
CREATE TABLE `payroll_periods` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `period` VARCHAR(7) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `payment_date` DATE NULL,
    `status` ENUM(
        'open',
        'processing',
        'approved',
        'paid',
        'closed',
        'cancelled'
    ) NOT NULL DEFAULT 'open',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 37. payroll_runs
CREATE TABLE `payroll_runs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payroll_period_id` BIGINT UNSIGNED NOT NULL,
    `run_number` VARCHAR(100) NOT NULL,
    `run_type` ENUM('regular', 'off_cycle', 'correction', 'bonus') NOT NULL DEFAULT 'regular',
    `status` ENUM(
        'draft',
        'calculating',
        'calculated',
        'reviewed',
        'approved',
        'posted',
        'paid',
        'cancelled',
        'failed'
    ) NOT NULL DEFAULT 'draft',
    `total_gross` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_deduction` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_company_contribution` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_tax` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_net` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 38. payroll_details
CREATE TABLE `payroll_details` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payroll_run_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `employee_number_snapshot` VARCHAR(50) NOT NULL,
    `employee_name_snapshot` VARCHAR(255) NOT NULL,
    `department_name_snapshot` VARCHAR(255) NULL,
    `position_name_snapshot` VARCHAR(255) NULL,
    `gross_salary` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_deduction` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_company_contribution` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_tax` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `net_salary` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `component_details` JSON NULL,
    `calculation_metadata` JSON NULL,
    `status` ENUM('calculated', 'approved', 'paid', 'cancelled') NOT NULL DEFAULT 'calculated',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 39. payroll_detail_components
CREATE TABLE `payroll_detail_components` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payroll_detail_id` BIGINT UNSIGNED NOT NULL,
    `salary_component_id` BIGINT UNSIGNED NULL,
    `component_code` VARCHAR(50) NOT NULL,
    `component_name` VARCHAR(255) NOT NULL,
    `component_type` ENUM(
        'earning',
        'deduction',
        'company_contribution',
        'tax'
    ) NOT NULL,
    `quantity` DECIMAL(15, 4) NOT NULL DEFAULT 1.0000,
    `rate` DECIMAL(15, 4) NOT NULL DEFAULT 0.0000,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
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
    CONSTRAINT `pdc_component_fk` FOREIGN KEY (`salary_component_id`) REFERENCES `salary_components` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `pdc_detail_fk` FOREIGN KEY (`payroll_detail_id`) REFERENCES `payroll_details` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 40. payroll_integrations
CREATE TABLE `payroll_integrations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payroll_run_id` BIGINT UNSIGNED NOT NULL,
    `payroll_period_id` BIGINT UNSIGNED NOT NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `integration_code` VARCHAR(255) NOT NULL,
    `total_gross_salary` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_net_salary` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_tax` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_bpjs_company` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_bpjs_employee` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_loans` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_other_deductions` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `mapping_config` JSON NULL,
    `status` ENUM('pending', 'mapped', 'synced', 'failed') NOT NULL DEFAULT 'pending',
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
    CONSTRAINT `payroll_integrations_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `payroll_integrations_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
        CONSTRAINT `payroll_integrations_period_fk` FOREIGN KEY (`payroll_period_id`) REFERENCES `payroll_periods` (`id`) ON DELETE RESTRICT,
        CONSTRAINT `payroll_integrations_run_fk` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 41. payslips
CREATE TABLE `payslips` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payroll_detail_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 42. payroll_payment_batches
CREATE TABLE `payroll_payment_batches` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payroll_run_id` BIGINT UNSIGNED NOT NULL,
    `bank_account_id` BIGINT UNSIGNED NOT NULL,
    `batch_number` VARCHAR(100) NOT NULL,
    `payment_date` DATE NOT NULL,
    `total_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `employee_count` INT NOT NULL DEFAULT 0,
    `status` ENUM(
        'draft',
        'approved',
        'processing',
        'completed',
        'partial',
        'failed',
        'cancelled'
    ) NOT NULL DEFAULT 'draft',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 43. payroll_payments
CREATE TABLE `payroll_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `payroll_payment_batch_id` BIGINT UNSIGNED NOT NULL,
    `payroll_detail_id` BIGINT UNSIGNED NOT NULL,
    `employee_id` BIGINT UNSIGNED NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `status` ENUM(
        'pending',
        'processing',
        'success',
        'failed',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',
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
    CONSTRAINT `payroll_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 44. payroll_permissions
CREATE TABLE `payroll_permissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `module` ENUM('payroll', 'finance', 'hr', 'admin') NOT NULL,
    `permission` ENUM(
        'view',
        'create',
        'edit',
        'delete',
        'process',
        'approve',
        'export'
    ) NOT NULL,
    `department_id` BIGINT UNSIGNED NULL,
    `employee_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `payroll_permissions_user_module_permission_unique` (
        `user_id`,
        `module`,
        `permission`,
        `department_id`,
        `employee_id`
    ),
    INDEX `payroll_permissions_user_id_index` (`user_id`),
    INDEX `payroll_permissions_module_index` (`module`),
    INDEX `payroll_permissions_deleted_at_index` (`deleted_at`),
    CONSTRAINT `payroll_permissions_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    `status` ENUM('open', 'closed') NOT NULL DEFAULT 'open',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 47. accounting_periods
CREATE TABLE `accounting_periods` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_year_id` BIGINT UNSIGNED NOT NULL,
    `period` VARCHAR(7) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `status` ENUM('open', 'soft_closed', 'closed') NOT NULL DEFAULT 'open',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 48. account_categories
CREATE TABLE `account_categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `type` ENUM(
        'aset',
        'kewajiban',
        'ekuitas',
        'pendapatan',
        'beban'
    ) NOT NULL,
    `normal_balance` ENUM('debit', 'kredit') NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 49. accounts
CREATE TABLE `accounts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `account_category_id` BIGINT UNSIGNED NOT NULL,
    `parent_id` BIGINT UNSIGNED NULL,
    `account_type` ENUM('header', 'detail') NOT NULL DEFAULT 'detail',
    `normal_balance` ENUM('debit', 'kredit') NOT NULL,
    `currency_code` VARCHAR(10) NOT NULL DEFAULT 'IDR',
    `description` TEXT NULL,
    `opening_balance` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `current_balance` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
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
    CONSTRAINT `accounts_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `accounts` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 50. account_mappings
CREATE TABLE `account_mappings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `source_type` ENUM(
        'payroll_component',
        'sales',
        'purchase',
        'expense',
        'tax',
        'loan',
        'bank',
        'other'
    ) NOT NULL,
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
    CONSTRAINT `account_mappings_credit_fk` FOREIGN KEY (`credit_account_id`) REFERENCES `accounts` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `account_mappings_debit_fk` FOREIGN KEY (`debit_account_id`) REFERENCES `accounts` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `account_mappings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 51. journal_entries
CREATE TABLE `journal_entries` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `accounting_period_id` BIGINT UNSIGNED NOT NULL,
    `journal_number` VARCHAR(255) NOT NULL,
    `journal_date` DATE NOT NULL,
    `description` TEXT NOT NULL,
    `source_type` ENUM(
        'manual',
        'payroll',
        'sales',
        'purchase',
        'expense',
        'transfer',
        'tax',
        'opening',
        'adjustment',
        'closing'
    ) NOT NULL DEFAULT 'manual',
    `source_id` BIGINT UNSIGNED NULL,
    `reference_number` VARCHAR(255) NULL,
    `total_debit` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_credit` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `status` ENUM('draft', 'approved', 'posted', 'void') NOT NULL DEFAULT 'draft',
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
    CONSTRAINT `journal_entries_reversal_fk` FOREIGN KEY (`reversal_of_id`) REFERENCES `journal_entries` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 52. journal_details
CREATE TABLE `journal_details` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `journal_entry_id` BIGINT UNSIGNED NOT NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `cost_center_id` BIGINT UNSIGNED NULL,
    `description` TEXT NULL,
    `debit` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `credit` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `reference_type` VARCHAR(100) NULL,
    `reference_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `journal_details_entry_index` (`journal_entry_id`),
    INDEX `journal_details_account_index` (`account_id`),
    INDEX `journal_details_cost_center_index` (`cost_center_id`),
    CONSTRAINT `journal_details_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `journal_details_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `journal_details_entry_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE,
        CONSTRAINT `journal_details_debit_credit_chk` CHECK (
            (`debit` >= 0)
            and (`credit` >= 0)
            and (
                (`debit` <= 0)
                or (`credit` <= 0)
            )
        )
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 53. opening_balances
CREATE TABLE `opening_balances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_year_id` BIGINT UNSIGNED NOT NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `cost_center_id` BIGINT UNSIGNED NULL,
    `debit` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `credit` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `opening_balances_unique` (
        `organization_id`,
        `fiscal_year_id`,
        `account_id`,
        `cost_center_id`
    ),
    INDEX `opening_balances_year_fk` (`fiscal_year_id`),
    INDEX `opening_balances_account_fk` (`account_id`),
    INDEX `opening_balances_cost_center_fk` (`cost_center_id`),
    INDEX `opening_balances_journal_fk` (`journal_entry_id`),
    INDEX `opening_balances_deleted_at_index` (`deleted_at`),
    CONSTRAINT `opening_balances_account_fk` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `opening_balances_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `opening_balances_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `opening_balances_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
        CONSTRAINT `opening_balances_year_fk` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 54. budgets
CREATE TABLE `budgets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `fiscal_year_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(100) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `status` ENUM('draft', 'approved', 'locked', 'cancelled') NOT NULL DEFAULT 'draft',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 55. budget_details
CREATE TABLE `budget_details` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `budget_id` BIGINT UNSIGNED NOT NULL,
    `accounting_period_id` BIGINT UNSIGNED NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `cost_center_id` BIGINT UNSIGNED NULL,
    `budget_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `actual_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `variance_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `variance_percentage` DECIMAL(7, 2) NOT NULL DEFAULT 0.00,
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
    CONSTRAINT `budget_details_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `budget_details_period_fk` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 56. bank_accounts
CREATE TABLE `bank_accounts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `bank_name` VARCHAR(255) NOT NULL,
    `account_number` VARCHAR(50) NOT NULL,
    `account_name` VARCHAR(255) NOT NULL,
    `currency_code` VARCHAR(10) NOT NULL DEFAULT 'IDR',
    `current_balance` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 57. bank_transactions
CREATE TABLE `bank_transactions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `bank_account_id` BIGINT UNSIGNED NOT NULL,
    `transaction_date` DATE NOT NULL,
    `transaction_type` ENUM('debit', 'credit', 'transfer') NOT NULL,
    `description` TEXT NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `balance_after` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
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
    CONSTRAINT `bank_transactions_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 58. reconciliations
CREATE TABLE `reconciliations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `bank_account_id` BIGINT UNSIGNED NOT NULL,
    `reconciliation_date` DATE NOT NULL,
    `statement_balance` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `book_balance` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `difference` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `status` ENUM('pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 59. reconciliation_details
CREATE TABLE `reconciliation_details` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `reconciliation_id` BIGINT UNSIGNED NOT NULL,
    `bank_transaction_id` BIGINT UNSIGNED NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `transaction_date` DATE NOT NULL,
    `description` TEXT NULL,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `type` ENUM('match', 'unmatch', 'adjustment') NOT NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `reconciliation_details_recon_fk` (`reconciliation_id`),
    INDEX `reconciliation_details_bank_fk` (`bank_transaction_id`),
    INDEX `reconciliation_details_journal_fk` (`journal_entry_id`),
    CONSTRAINT `reconciliation_details_bank_fk` FOREIGN KEY (`bank_transaction_id`) REFERENCES `bank_transactions` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `reconciliation_details_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `reconciliation_details_recon_fk` FOREIGN KEY (`reconciliation_id`) REFERENCES `reconciliations` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 60. fixed_assets
CREATE TABLE `fixed_assets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `asset_code` VARCHAR(100) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `acquisition_date` DATE NOT NULL,
    `acquisition_cost` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `residual_value` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `useful_life_months` INT NOT NULL DEFAULT 0,
    `depreciation_method` ENUM('straight_line', 'declining_balance') NOT NULL DEFAULT 'straight_line',
    `accumulated_depreciation` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `book_value` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `asset_account_id` BIGINT UNSIGNED NOT NULL,
    `accumulated_depreciation_account_id` BIGINT UNSIGNED NULL,
    `depreciation_expense_account_id` BIGINT UNSIGNED NULL,
    `status` ENUM(
        'draft',
        'active',
        'disposed',
        'fully_depreciated'
    ) NOT NULL DEFAULT 'draft',
    `disposed_at` DATE NULL,
    `disposal_value` DECIMAL(15, 2) NULL,
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
    CONSTRAINT `fixed_assets_accum_account_fk` FOREIGN KEY (`accumulated_depreciation_account_id`) REFERENCES `accounts` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `fixed_assets_asset_account_fk` FOREIGN KEY (`asset_account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT,
        CONSTRAINT `fixed_assets_depr_expense_account_fk` FOREIGN KEY (`depreciation_expense_account_id`) REFERENCES `accounts` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `fixed_assets_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 61. asset_depreciations
CREATE TABLE `asset_depreciations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `fixed_asset_id` BIGINT UNSIGNED NOT NULL,
    `accounting_period_id` BIGINT UNSIGNED NOT NULL,
    `depreciation_date` DATE NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
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
    CONSTRAINT `asset_depreciations_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `asset_depreciations_period_fk` FOREIGN KEY (`accounting_period_id`) REFERENCES `accounting_periods` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    CONSTRAINT `vendors_payable_account_fk` FOREIGN KEY (`payable_account_id`) REFERENCES `accounts` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    CONSTRAINT `customers_receivable_account_fk` FOREIGN KEY (`receivable_account_id`) REFERENCES `accounts` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    `subtotal` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `tax` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `discount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `paid_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `remaining_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `status` ENUM(
        'draft',
        'sent',
        'partial',
        'paid',
        'overdue',
        'cancelled'
    ) NOT NULL DEFAULT 'draft',
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
    CONSTRAINT `invoices_customer_fk` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `invoices_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `invoices_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 65. invoice_items
CREATE TABLE `invoice_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `invoice_id` BIGINT UNSIGNED NOT NULL,
    `product_code` VARCHAR(100) NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `quantity` DECIMAL(15, 4) NOT NULL DEFAULT 1.0000,
    `unit_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `discount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `tax_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
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
    CONSTRAINT `invoice_items_revenue_account_fk` FOREIGN KEY (`revenue_account_id`) REFERENCES `accounts` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 66. invoice_payments
CREATE TABLE `invoice_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `invoice_id` BIGINT UNSIGNED NOT NULL,
    `payment_number` VARCHAR(100) NOT NULL,
    `payment_date` DATE NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `payment_method` ENUM('cash', 'bank_transfer', 'check', 'other') NOT NULL,
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
    CONSTRAINT `invoice_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `invoice_payments_invoice_fk` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE RESTRICT,
        CONSTRAINT `invoice_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `invoice_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 67. expenses
CREATE TABLE `expenses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `vendor_id` BIGINT UNSIGNED NULL,
    `expense_number` VARCHAR(255) NOT NULL,
    `expense_date` DATE NOT NULL,
    `vendor_name_snapshot` VARCHAR(255) NULL,
    `description` TEXT NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `tax` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `total_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `payment_status` ENUM('unpaid', 'partial', 'paid') NOT NULL DEFAULT 'unpaid',
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
    CONSTRAINT `expenses_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `expenses_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
        CONSTRAINT `expenses_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 68. expense_items
CREATE TABLE `expense_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `expense_id` BIGINT UNSIGNED NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `quantity` DECIMAL(15, 4) NOT NULL DEFAULT 1.0000,
    `unit_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `cost_center_id` BIGINT UNSIGNED NULL,
    `tax_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
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
    CONSTRAINT `expense_items_cost_center_fk` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `expense_items_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 69. expense_payments
CREATE TABLE `expense_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `expense_id` BIGINT UNSIGNED NOT NULL,
    `payment_number` VARCHAR(100) NOT NULL,
    `payment_date` DATE NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `payment_method` ENUM('cash', 'bank_transfer', 'check', 'other') NOT NULL,
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
    CONSTRAINT `expense_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `expense_payments_expense_fk` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE RESTRICT,
        CONSTRAINT `expense_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `expense_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `tax_types`;
CREATE TABLE `tax_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint NULL DEFAULT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tax_types_code_unique`(`code`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- 70. tax_settings
CREATE TABLE `tax_settings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `tax_code` VARCHAR(50) NOT NULL,
    `tax_name` VARCHAR(255) NOT NULL,
    `tax_type_id` BIGINT UNSIGNED NOT NULL,
    `tax_rate` DECIMAL(7, 4) NOT NULL DEFAULT 0.0000,
    `min_amount` DECIMAL(15, 2) NULL,
    `max_amount` DECIMAL(15, 2) NULL,
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
    CONSTRAINT `tax_settings_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE ,
    CONSTRAINT `tax_settings_org_tax_type_fk` FOREIGN KEY (`tax_type_id`) REFERENCES `tax_types` (`id`) ON DELETE ,
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 71. tax_liabilities
CREATE TABLE `tax_liabilities` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `tax_type` ENUM(
        'pph21',
        'pph23',
        'pph25',
        'ppn',
        'ppnbm',
        'other'
    ) NOT NULL,
    `tax_period` VARCHAR(7) NOT NULL,
    `tax_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `paid_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `due_date` DATE NOT NULL,
    `status` ENUM(
        'pending',
        'partial',
        'paid',
        'overdue',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',
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
    CONSTRAINT `tax_liabilities_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `tax_liabilities_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 72. tax_payments
CREATE TABLE `tax_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `tax_liability_id` BIGINT UNSIGNED NOT NULL,
    `payment_number` VARCHAR(100) NOT NULL,
    `payment_date` DATE NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `payment_reference` VARCHAR(255) NULL,
    `bank_account_id` BIGINT UNSIGNED NULL,
    `journal_entry_id` BIGINT UNSIGNED NULL,
    `status` ENUM('draft', 'paid', 'cancelled') NOT NULL DEFAULT 'draft',
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
    CONSTRAINT `tax_payments_bank_fk` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `tax_payments_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `tax_payments_liability_fk` FOREIGN KEY (`tax_liability_id`) REFERENCES `tax_liabilities` (`id`) ON DELETE RESTRICT,
        CONSTRAINT `tax_payments_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE test_schedules (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Relasi
    admission_id        BIGINT UNSIGNED NOT NULL,          -- FK ke admissions (gelombang PPDB)
    admission_selection_id BIGINT UNSIGNED NULL,           -- FK opsional ke admission_selections
    academic_year_id    BIGINT UNSIGNED NOT NULL,          -- FK ke academic_years
    school_id           BIGINT UNSIGNED NULL,              -- kalau multi-tenant
    
    -- Info Dasar
    code                VARCHAR(50) NOT NULL UNIQUE,       -- "TES-TULIS-2025-01"
    name                VARCHAR(150) NOT NULL,             -- "Tes Tulis Gelombang 1 - Sesi A"
    description         TEXT NULL,
    
    -- Jenis & Metode Tes
    test_type           ENUM('tulis','wawancara','praktek','kesehatan','psikotes','lainnya') 
                        NOT NULL DEFAULT 'tulis',
    test_method         ENUM('offline','online','hybrid') 
                        NOT NULL DEFAULT 'offline',
    
    -- Jadwal & Waktu
    test_date           DATE NOT NULL,                     -- tanggal pelaksanaan
    start_time          TIME NOT NULL,                     -- jam mulai
    end_time            TIME NOT NULL,                     -- jam selesai
    duration_minutes    INT UNSIGNED NULL,                 -- durasi (auto dari start-end)
    registration_start  DATETIME NULL,                     -- kapan pendaftaran slot dibuka
    registration_end    DATETIME NULL,                     -- kapan pendaftaran slot ditutup
    
    -- Lokasi
    room_id             BIGINT UNSIGNED NULL,              -- FK ke rooms (kalau offline)
    building_id         BIGINT UNSIGNED NULL,              -- FK ke buildings
    location_detail     VARCHAR(255) NULL,                 -- "Gedung A Lt. 2 Ruang 201"
    online_meeting_url  VARCHAR(500) NULL,                 -- Zoom/Meet (kalau online)
    online_meeting_id   VARCHAR(100) NULL,
    online_password     VARCHAR(100) NULL,
    
    -- CBT Integration (kalau tes online via CBT)
    cbt_session_id      BIGINT UNSIGNED NULL,              -- FK ke cbt_sessions
    exam_package_id     BIGINT UNSIGNED NULL,              -- FK ke exam_packages
    
    -- Kapasitas & Kuota
    quota               INT UNSIGNED NOT NULL DEFAULT 0,   -- 0 = unlimited
    booked_count        INT UNSIGNED NOT NULL DEFAULT 0,   -- cached count
    min_participants    INT UNSIGNED DEFAULT 0,            -- minimal peserta agar tes jalan
    
    -- Pengawas & Panitia
    supervisor_id       BIGINT UNSIGNED NULL,              -- FK ke employees/users
    coordinator_id      BIGINT UNSIGNED NULL,              -- penanggung jawab
    proctor_ids         JSON NULL,                         -- array ID pengawas tambahan
    
    -- Pengaturan Peserta
    allow_self_register BOOLEAN NOT NULL DEFAULT FALSE,    -- peserta pilih sendiri?
    allow_reschedule    BOOLEAN NOT NULL DEFAULT FALSE,    -- boleh reschedule?
    late_tolerance_min  INT UNSIGNED DEFAULT 15,           -- toleransi keterlambatan (menit)
    
    -- Status
    status              ENUM('draft','published','ongoing','completed','cancelled') 
                        NOT NULL DEFAULT 'draft',
    is_active           BOOLEAN NOT NULL DEFAULT TRUE,
    
    -- Hasil & Nilai
    passing_score       DECIMAL(5,2) NULL,                 -- nilai minimum lulus
    max_score           DECIMAL(5,2) NULL,                 -- nilai maksimum
    score_input_deadline DATETIME NULL,                    -- batas input nilai
    result_announcement DATETIME NULL,                     -- kapan hasil diumumkan
    
    -- Metadata
    notes               TEXT NULL,                         -- catatan internal
    attachments         JSON NULL,                         -- file pendukung (prosedur, dll)
    settings            JSON NULL,                         -- konfigurasi fleksibel
    
    -- Audit
    created_by          BIGINT UNSIGNED NULL,
    updated_by          BIGINT UNSIGNED NULL,
    created_at          TIMESTAMP NULL,
    updated_at          TIMESTAMP NULL,
    deleted_at          TIMESTAMP NULL,                    -- soft delete
    
    -- Indexes
    INDEX idx_admission (admission_id),
    INDEX idx_selection (admission_selection_id),
    INDEX idx_academic_year (academic_year_id),
    INDEX idx_test_date (test_date, start_time),
    INDEX idx_status (status, is_active),
    INDEX idx_test_type (test_type),
    INDEX idx_cbt_session (cbt_session_id),
    
    -- Foreign Keys
    CONSTRAINT fk_test_schedules_admission 
        FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE CASCADE,
    CONSTRAINT fk_test_schedules_selection 
        FOREIGN KEY (admission_selection_id) REFERENCES admission_selections(id) ON DELETE SET NULL,
    CONSTRAINT fk_test_schedules_academic_year 
        FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE RESTRICT,
    CONSTRAINT fk_test_schedules_room 
        FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL,
    CONSTRAINT fk_test_schedules_cbt_session 
        FOREIGN KEY (cbt_session_id) REFERENCES cbt_sessions(id) ON DELETE SET NULL,
    CONSTRAINT fk_test_schedules_exam_package 
        FOREIGN KEY (exam_package_id) REFERENCES exam_packages(id) ON DELETE SET NULL
);

CREATE TABLE test_types (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Identitas
    code                VARCHAR(50) NOT NULL UNIQUE,       -- "tulis", "wawancara", "psikotes"
    name                VARCHAR(150) NOT NULL,             -- "Tes Tulis"
    name_en             VARCHAR(150) NULL,                 -- "Written Test" (opsional)
    slug                VARCHAR(150) NOT NULL UNIQUE,      -- "tes-tulis"
    description         TEXT NULL,
    
    -- Kategorisasi
    category            ENUM('akademik','non_akademik','kesehatan','administrasi','lainnya')
                        NOT NULL DEFAULT 'akademik',
    parent_id           BIGINT UNSIGNED NULL,              -- untuk sub-jenis (hierarchical)
    
    -- Visual
    icon                VARCHAR(100) NULL,                 -- "bi-pencil-square"
    color               VARCHAR(20) NULL,                  -- "#3b82f6" (hex)
    badge_class         VARCHAR(50) NULL,                  -- "badge-primary" (Bootstrap)
    
    -- Pengaturan Default
    default_duration_minutes INT UNSIGNED NULL,            -- 90 menit
    default_passing_score    DECIMAL(5,2) NULL,            -- 70.00
    default_max_score        DECIMAL(5,2) NULL,            -- 100.00
    default_weight           DECIMAL(5,2) DEFAULT 100.00,  -- bobot dalam seleksi
    
    -- Mode Pelaksanaan
    default_method      ENUM('offline','online','hybrid') 
                        NOT NULL DEFAULT 'offline',
    requires_room       BOOLEAN NOT NULL DEFAULT TRUE,     -- butuh ruangan?
    requires_supervisor BOOLEAN NOT NULL DEFAULT TRUE,     -- butuh pengawas?
    requires_cbt        BOOLEAN NOT NULL DEFAULT FALSE,    -- pakai CBT?
    
    -- Komponen Penilaian (JSON fleksibel)
    -- Contoh: {"komponen": ["pengetahuan", "logika", "bahasa"], "skala": "0-100"}
    scoring_components  JSON NULL,
    
    -- Konfigurasi tambahan
    settings            JSON NULL,
    
    -- Status & Urutan
    order               INT UNSIGNED DEFAULT 0,
    is_active           BOOLEAN NOT NULL DEFAULT TRUE,
    is_system           BOOLEAN NOT NULL DEFAULT FALSE,    -- TRUE = tidak bisa dihapus user
    
    -- Multi-tenant (kalau ada)
    school_id           BIGINT UNSIGNED NULL,
    
    -- Audit
    created_by          BIGINT UNSIGNED NULL,
    updated_by          BIGINT UNSIGNED NULL,
    created_at          TIMESTAMP NULL,
    updated_at          TIMESTAMP NULL,
    deleted_at          TIMESTAMP NULL,                    -- soft delete
    
    -- Indexes
    INDEX idx_code (code),
    INDEX idx_category (category, is_active),
    INDEX idx_parent (parent_id),
    INDEX idx_order (order),
    INDEX idx_school (school_id),
    
    -- Foreign Keys
    CONSTRAINT fk_test_types_parent 
        FOREIGN KEY (parent_id) REFERENCES test_types(id) ON DELETE SET NULL,
    CONSTRAINT fk_test_types_school 
        FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE
);

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- USER & AUTHENTICATION TABLES
-- ============================================
-- 74. users
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 76. menus
CREATE TABLE `menus` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `parent_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(255) NULL,
    `title` VARCHAR(191) NOT NULL,
    `flyout_title` VARCHAR(255) NULL,
    `slug` VARCHAR(191) NOT NULL,
    `icon` VARCHAR(191) NULL,
    `icon_type` ENUM('material', 'fa') NULL,
    `route` VARCHAR(191) NULL,
    `url` VARCHAR(255) NULL,
    `type` ENUM('topbar', 'sidebar', 'module') NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `sequence` INT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `is_publish` tinyint(1) NOT NULL DEFAULT 1,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 77. menu_role
CREATE TABLE `menu_role` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 79. action_role
CREATE TABLE `action_role` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 80. action_user
CREATE TABLE `action_user` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 81. role_user
CREATE TABLE `role_user` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 82. position_user
CREATE TABLE `position_user` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    `status` ENUM('trial', 'active', 'suspended') NOT NULL DEFAULT 'trial',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 85. academic_years
CREATE TABLE `academic_years` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 86. subjects
CREATE TABLE `subjects` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `abbreviation` VARCHAR(20) NULL,
    `description` TEXT NULL,
    `category` ENUM(
        'CORE',
        'ELECTIVE',
        'EXTRACURRICULAR',
        'RELIGION',
        'LOCAL_CONTENT'
    ) NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 88. classrooms
CREATE TABLE `classrooms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `capacity` INT NULL DEFAULT 30, 
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `homeroom_teachers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NOT NULL COMMENT 'Merujuk ke tabel teachers',
    `classroom_id` BIGINT UNSIGNED NOT NULL COMMENT 'Merujuk ke tabel classrooms',
    `academic_year_id` BIGINT UNSIGNED NOT NULL COMMENT 'Tahun ajaran penugasan',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `assignment_date` DATE NULL COMMENT 'Tanggal ditunjuk sebagai wali kelas',
    `end_date` DATE NULL COMMENT 'Tanggal berakhirnya tugas (jika diganti)',
    `notes` VARCHAR(255) NULL COMMENT 'Catatan tambahan',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    -- Satu kelas dalam satu tahun ajaran hanya punya 1 wali kelas
    UNIQUE INDEX `homeroom_class_year_unique` (`classroom_id`, `academic_year_id`),
    -- Satu guru dalam satu tahun ajaran tidak duplikat di kelas yang sama
    UNIQUE INDEX `homeroom_teacher_class_unique` (`teacher_id`, `classroom_id`, `academic_year_id`),
    INDEX `idx_teacher_id` (`teacher_id`),
    INDEX `idx_classroom_id` (`classroom_id`),
    INDEX `idx_academic_year` (`academic_year_id`),
    INDEX `idx_organization` (`organization_id`),
    INDEX `idx_is_active` (`is_active`),
    CONSTRAINT `hr_org_fk` 
        FOREIGN KEY (`organization_id`) 
        REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `hr_teacher_fk` 
        FOREIGN KEY (`teacher_id`) 
        REFERENCES `teachers` (`id`) ON DELETE CASCADE,
    CONSTRAINT `hr_classroom_fk` 
        FOREIGN KEY (`classroom_id`) 
        REFERENCES `classrooms` (`id`) ON DELETE CASCADE,
    CONSTRAINT `hr_academic_year_fk` 
        FOREIGN KEY (`academic_year_id`) 
        REFERENCES `academic_years` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT='Penugasan wali kelas per tahun ajaran';


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
    `gender` ENUM('MALE', 'FEMALE') NULL,
    `address` TEXT NULL,
    `religion` VARCHAR(20) NULL,
    `parents_phone` VARCHAR(20) NULL,
    `emergency_contact` VARCHAR(20) NULL,
    `medical_notes` TEXT NULL,
    `join_date` DATE NULL,
    `status` ENUM(
        'ACTIVE',
        'GRADUATED',
        'TRANSFERRED',
        'DROPPED',
        'SUSPENDED'
    ) NULL DEFAULT 'ACTIVE',
    `admission_status` ENUM('NEW', 'TRANSFER', 'RETURNING') NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 90. parents
CREATE TABLE `parents` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `email` VARCHAR(100) NULL,
    `address` TEXT NULL,
    `relationship` ENUM('FATHER', 'MOTHER', 'GUARDIAN') NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 92. student_classrooms
CREATE TABLE `student_classrooms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `enrollment_date` DATE NULL,
    `status` ENUM('ACTIVE', 'TRANSFERRED', 'DROPPED', 'GRADUATED') NULL DEFAULT 'ACTIVE',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    `status` ENUM('ACTIVE', 'RESIGNED', 'RETIRED') NULL DEFAULT 'ACTIVE',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 94. teaching_loads
CREATE TABLE `teaching_loads` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NOT NULL,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `semester` ENUM('SEMESTER_1', 'SEMESTER_2') NULL,
    `total_hours` INT NULL DEFAULT 0,
    `is_active` TINYINT(1) NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `teaching_loads_unique` (
        `teacher_id`,
        `classroom_id`,
        `subject_id`,
        `academic_year_id`,
        `semester`
    ),
    INDEX `idx_teaching_loads_teacher` (`teacher_id`),
    INDEX `idx_teaching_loads_classroom` (`classroom_id`),
    INDEX `idx_teaching_loads_academic_year` (`academic_year_id`),
    INDEX `idx_teaching_loads_deleted_at` (`deleted_at`),
    CONSTRAINT `teaching_loads_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
    CONSTRAINT `teaching_loads_classroom_fk` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE,
    CONSTRAINT `teaching_loads_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `teaching_loads_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    `level` ENUM('BEGINNER', 'INTERMEDIATE', 'ADVANCED') NULL,
    `duration_hours` INT NULL DEFAULT 0,
    `is_published` TINYINT(1) NULL DEFAULT 0,
    `published_at` TIMESTAMP NULL,
    `enrollment_count` INT NULL DEFAULT 0,
    `rating` DECIMAL(3, 2) NULL DEFAULT 0.00,
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
    CONSTRAINT `courses_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `courses_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 96. lessons
CREATE TABLE `lessons` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 97. curriculum_structures
CREATE TABLE `curriculum_structures` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `semester` ENUM('SEMESTER_1', 'SEMESTER_2') NULL,
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
    UNIQUE INDEX `curriculum_structures_unique` (
        `organization_id`,
        `class_level_id`,
        `subject_id`,
        `academic_year_id`,
        `semester`
    ),
    INDEX `idx_curriculum_class` (`class_level_id`),
    INDEX `idx_curriculum_subject` (`subject_id`),
    INDEX `idx_curriculum_deleted_at` (`deleted_at`),
    CONSTRAINT `curriculum_structures_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `curriculum_structures_class_fk` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `curriculum_structures_subject_fk` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `curriculum_structures_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 98. schedules
CREATE TABLE `schedules` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `day_of_week` ENUM(
        'MONDAY',
        'TUESDAY',
        'WEDNESDAY',
        'THURSDAY',
        'FRIDAY',
        'SATURDAY',
        'SUNDAY'
    ) NOT NULL,
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
    CONSTRAINT `schedules_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE
    SET
        NULL,
        CONSTRAINT `schedules_academic_year_fk` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE RESTRICT
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- LOOKUP TABLES
-- ============================================
-- 99. blood_types
CREATE TABLE `blood_types` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(5) NOT NULL,
    `rhesus` ENUM('+', '-', '') NOT NULL DEFAULT '',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 100. genders
CREATE TABLE `genders` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 101. marital_statuses
CREATE TABLE `marital_statuses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 102. religions
CREATE TABLE `religions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 103. identity_types
CREATE TABLE `identity_types` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 104. ptkp_statuses
CREATE TABLE `ptkp_statuses` (
    `id` VARCHAR(10) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `ptkp_statuses_deleted_at_index` (`deleted_at`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 106. floors
CREATE TABLE `floors` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 107. rooms
CREATE TABLE `rooms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 110. dictionaries
CREATE TABLE `dictionaries` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `key` VARCHAR(255) NOT NULL,
    `group` VARCHAR(255) NULL,
    `en` TEXT NULL,
    `indo` TEXT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- MODULES & PACKAGES TABLES
-- ============================================
-- 111. modules
CREATE TABLE `modules` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL, 
    `code` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `icon` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `price` DECIMAL(10, 2) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `modules_code_unique` (`code`),
    INDEX `modules_is_active_index` (`is_active`),
    INDEX `modules_deleted_at_index` (`deleted_at`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 112. packages
CREATE TABLE `packages` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 113. trial_registrations
CREATE TABLE `trial_registrations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `token` CHAR(36) NOT NULL,
    `current_step` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `status` ENUM('in_progress', 'completed', 'abandoned') NOT NULL DEFAULT 'in_progress',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 114. trial_registration_education_level
CREATE TABLE `trial_registration_education_level` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 115. trial_registration_module
CREATE TABLE `trial_registration_module` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 116. trial_registration_role
CREATE TABLE `trial_registration_role` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    `action` ENUM('INSERT', 'UPDATE', 'DELETE', 'RESTORE') NOT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 118. system_logs
CREATE TABLE `system_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `level` ENUM('debug', 'info', 'warning', 'error', 'critical') NOT NULL,
    `message` TEXT NOT NULL,
    `context` JSON NULL,
    `channel` VARCHAR(50) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_system_logs_level` (`level`),
    INDEX `idx_system_logs_created` (`created_at`),
    INDEX `idx_system_logs_org` (`organization_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 119. scheduled_jobs
CREATE TABLE `scheduled_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `command` VARCHAR(255) NOT NULL,
    `schedule` VARCHAR(100) NOT NULL,
    `last_run` TIMESTAMP NULL,
    `next_run` TIMESTAMP NULL,
    `status` ENUM('active', 'inactive', 'failed') DEFAULT 'active',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 122. cache
CREATE TABLE `cache` (
    `key` VARCHAR(191) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 123. cache_locks
CREATE TABLE `cache_locks` (
    `key` VARCHAR(191) NOT NULL,
    `owner` VARCHAR(191) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 124. jobs
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `queue` VARCHAR(191) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `jobs_queue_index` (`queue`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 126. failed_jobs
CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_id` BIGINT UNSIGNED NOT NULL,
    `uuid` VARCHAR(191) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `failed_jobs_uuid_unique` (`uuid`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 127. migrations
CREATE TABLE `migrations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 128. password_reset_tokens
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(191) NOT NULL,
    `token` VARCHAR(191) NOT NULL,
    `created_at` TIMESTAMP NULL,
    PRIMARY KEY (`email`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

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
    CONSTRAINT `expense_categories_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `expense_categories` (`id`) ON DELETE
    SET
        NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

SET
    FOREIGN_KEY_CHECKS = 1;

    -- ============================================
-- 1. USERS
-- ============================================
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password_hash` TEXT NOT NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `birth_date` DATE NULL,
    `gender` VARCHAR(10) NULL,
    `avatar_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `last_login_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `users_username_unique` (`username`),
    UNIQUE INDEX `users_email_unique` (`email`),
    INDEX `users_is_active_index` (`is_active`),
    INDEX `users_deleted_at_index` (`deleted_at`),
    INDEX `users_created_by_index` (`created_by`),
    INDEX `users_updated_by_index` (`updated_by`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 2. SCHOOLS
-- ============================================
CREATE TABLE `schools` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `type` VARCHAR(20) NULL,
    `level_education` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `phone` VARCHAR(20) NULL,
    `email` VARCHAR(100) NULL,
    `website` VARCHAR(100) NULL,
    `logo_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `schools_code_unique` (`code`),
    INDEX `schools_type_index` (`type`),
    INDEX `schools_is_active_index` (`is_active`),
    INDEX `schools_deleted_at_index` (`deleted_at`),
    INDEX `schools_created_by_index` (`created_by`),
    INDEX `schools_updated_by_index` (`updated_by`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 3. STUDENTS
-- ============================================
CREATE TABLE `students` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `nis` VARCHAR(50) NOT NULL,
    `nisn` VARCHAR(50) NULL,
    `registration_number` VARCHAR(50) NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `birth_place` VARCHAR(100) NULL,
    `birth_date` DATE NULL,
    `gender` VARCHAR(10) NULL,
    `address` TEXT NULL,
    `religion` VARCHAR(20) NULL,
    `parents_phone` VARCHAR(20) NULL,
    `emergency_contact` VARCHAR(20) NULL,
    `medical_notes` TEXT NULL,
    `join_date` DATE NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'ACTIVE',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `students_nis_unique` (`nis`),
    UNIQUE INDEX `students_nisn_unique` (`nisn`),
    INDEX `students_school_id_index` (`school_id`),
    INDEX `students_user_id_index` (`user_id`),
    INDEX `students_status_index` (`status`),
    INDEX `students_birth_date_index` (`birth_date`),
    INDEX `students_is_deleted_index` (`deleted_at`),
    INDEX `students_created_by_index` (`created_by`),
    INDEX `students_updated_by_index` (`updated_by`),
    CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `students_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 4. TEACHERS
-- ============================================
CREATE TABLE `teachers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `nip` VARCHAR(50) NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `specialization` VARCHAR(100) NULL,
    `education_level` VARCHAR(50) NULL,
    `join_date` DATE NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'ACTIVE',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `teachers_nip_unique` (`nip`),
    INDEX `teachers_school_id_index` (`school_id`),
    INDEX `teachers_user_id_index` (`user_id`),
    INDEX `teachers_status_index` (`status`),
    INDEX `teachers_deleted_at_index` (`deleted_at`),
    INDEX `teachers_created_by_index` (`created_by`),
    INDEX `teachers_updated_by_index` (`updated_by`),
    CONSTRAINT `teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `teachers_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 5. PARENTS
-- ============================================
CREATE TABLE `parents` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `email` VARCHAR(100) NULL,
    `address` TEXT NULL,
    `relationship` VARCHAR(20) NULL,
    `work` VARCHAR(100) NULL,
    `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `parents_user_id_index` (`user_id`),
    INDEX `parents_phone_index` (`phone`),
    INDEX `parents_deleted_at_index` (`deleted_at`),
    INDEX `parents_created_by_index` (`created_by`),
    INDEX `parents_updated_by_index` (`updated_by`),
    CONSTRAINT `parents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 6. STUDENT_PARENTS (Pivot)
-- ============================================
CREATE TABLE `student_parents` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `parent_id` BIGINT UNSIGNED NOT NULL,
    `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_parents_student_parent_unique` (`student_id`, `parent_id`),
    INDEX `student_parents_student_index` (`student_id`),
    INDEX `student_parents_parent_index` (`parent_id`),
    INDEX `student_parents_deleted_at_index` (`deleted_at`),
    CONSTRAINT `student_parents_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_parents_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 7. ACADEMIC_YEARS
-- ============================================
CREATE TABLE `academic_years` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `semester_start_date` DATE NULL,
    `semester_end_date` DATE NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `is_current` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `academic_years_school_code_unique` (`school_id`, `code`),
    INDEX `academic_years_school_index` (`school_id`),
    INDEX `academic_years_date_range_index` (`start_date`, `end_date`),
    INDEX `academic_years_is_current_index` (`is_current`),
    INDEX `academic_years_deleted_at_index` (`deleted_at`),
    INDEX `academic_years_created_by_index` (`created_by`),
    INDEX `academic_years_updated_by_index` (`updated_by`),
    CONSTRAINT `academic_years_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 8. CLASS_LEVELS
-- ============================================
CREATE TABLE `class_levels` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `level_order` INTEGER NOT NULL,
    `education_level` VARCHAR(20) NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `class_levels_school_code_unique` (`school_id`, `code`),
    INDEX `class_levels_school_index` (`school_id`),
    INDEX `class_levels_order_index` (`level_order`),
    INDEX `class_levels_deleted_at_index` (`deleted_at`),
    INDEX `class_levels_created_by_index` (`created_by`),
    INDEX `class_levels_updated_by_index` (`updated_by`),
    CONSTRAINT `class_levels_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 9. CLASSROOMS
-- ============================================
CREATE TABLE `classrooms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `capacity` INTEGER NOT NULL DEFAULT 30,
    `homeroom_teacher_id` BIGINT UNSIGNED NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `classrooms_school_code_unique` (`school_id`, `code`),
    INDEX `classrooms_school_index` (`school_id`),
    INDEX `classrooms_level_index` (`class_level_id`),
    INDEX `classrooms_academic_year_index` (`academic_year_id`),
    INDEX `classrooms_teacher_index` (`homeroom_teacher_id`),
    INDEX `classrooms_deleted_at_index` (`deleted_at`),
    INDEX `classrooms_created_by_index` (`created_by`),
    INDEX `classrooms_updated_by_index` (`updated_by`),
    CONSTRAINT `classrooms_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `classrooms_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `classrooms_homeroom_teacher_id_foreign` FOREIGN KEY (`homeroom_teacher_id`) REFERENCES `teachers` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `classrooms_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 10. SUBJECTS
-- ============================================
CREATE TABLE `subjects` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `abbreviation` VARCHAR(20) NULL,
    `description` TEXT NULL,
    `category` VARCHAR(50) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `subjects_school_code_unique` (`school_id`, `code`),
    INDEX `subjects_school_index` (`school_id`),
    INDEX `subjects_category_index` (`category`),
    INDEX `subjects_deleted_at_index` (`deleted_at`),
    INDEX `subjects_created_by_index` (`created_by`),
    INDEX `subjects_updated_by_index` (`updated_by`),
    CONSTRAINT `subjects_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 11. SCHEDULES
-- ============================================
CREATE TABLE `schedules` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `day_of_week` VARCHAR(10) NULL,
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `room` VARCHAR(50) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `schedules_classroom_index` (`classroom_id`),
    INDEX `schedules_teacher_index` (`teacher_id`),
    INDEX `schedules_day_time_index` (`day_of_week`, `start_time`),
    INDEX `schedules_academic_year_index` (`academic_year_id`),
    INDEX `schedules_subject_index` (`subject_id`),
    INDEX `schedules_deleted_at_index` (`deleted_at`),
    INDEX `schedules_created_by_index` (`created_by`),
    INDEX `schedules_updated_by_index` (`updated_by`),
    CONSTRAINT `schedules_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `schedules_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `schedules_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `schedules_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 12. CURRICULUM_PHASES (Kurikulum Merdeka)
-- ============================================
CREATE TABLE `curriculum_phases` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `description` TEXT NULL,
    `age_range` VARCHAR(20) NULL,
    `class_levels` JSON NULL,
    `competencies` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `curriculum_phases_school_code_unique` (`school_id`, `code`),
    INDEX `curriculum_phases_school_index` (`school_id`),
    INDEX `curriculum_phases_is_active_index` (`is_active`),
    INDEX `curriculum_phases_deleted_at_index` (`deleted_at`),
    INDEX `curriculum_phases_created_by_index` (`created_by`),
    INDEX `curriculum_phases_updated_by_index` (`updated_by`),
    CONSTRAINT `curriculum_phases_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 13. CURRICULUM_STRUCTURES
-- ============================================
CREATE TABLE `curriculum_structures` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `curriculum_phase_id` BIGINT UNSIGNED NULL,
    `semester` VARCHAR(10) NULL,
    `hours_per_week` INTEGER NOT NULL,
    `hour_duration` INTEGER NOT NULL DEFAULT 45,
    `is_core` TINYINT(1) NOT NULL DEFAULT 1,
    `min_student` INTEGER NOT NULL DEFAULT 1,
    `max_student` INTEGER NOT NULL DEFAULT 40,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `curriculum_structures_unique` (
        `school_id`,
        `class_level_id`,
        `subject_id`,
        `academic_year_id`,
        `semester`
    ),
    INDEX `curriculum_structures_school_index` (`school_id`),
    INDEX `curriculum_structures_class_index` (`class_level_id`),
    INDEX `curriculum_structures_subject_index` (`subject_id`),
    INDEX `curriculum_structures_academic_year_index` (`academic_year_id`),
    INDEX `curriculum_structures_phase_index` (`curriculum_phase_id`),
    INDEX `curriculum_structures_deleted_at_index` (`deleted_at`),
    INDEX `curriculum_structures_created_by_index` (`created_by`),
    INDEX `curriculum_structures_updated_by_index` (`updated_by`),
    CONSTRAINT `curriculum_structures_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `curriculum_structures_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `curriculum_structures_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `curriculum_structures_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `curriculum_structures_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 14. TEACHING_LOADS (Beban Mengajar Guru)
-- ============================================
CREATE TABLE `teaching_loads` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `teacher_id` BIGINT UNSIGNED NOT NULL,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `semester` VARCHAR(10) NULL,
    `total_hours` INTEGER NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `teaching_loads_unique` (
        `teacher_id`,
        `classroom_id`,
        `subject_id`,
        `academic_year_id`,
        `semester`
    ),
    INDEX `teaching_loads_teacher_index` (`teacher_id`),
    INDEX `teaching_loads_classroom_index` (`classroom_id`),
    INDEX `teaching_loads_subject_index` (`subject_id`),
    INDEX `teaching_loads_academic_year_index` (`academic_year_id`),
    INDEX `teaching_loads_deleted_at_index` (`deleted_at`),
    INDEX `teaching_loads_created_by_index` (`created_by`),
    INDEX `teaching_loads_updated_by_index` (`updated_by`),
    CONSTRAINT `teaching_loads_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `teaching_loads_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `teaching_loads_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `teaching_loads_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 15. STUDENT_CLASSROOMS (Siswa per Kelas)
-- ============================================
CREATE TABLE `student_classrooms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `enrollment_date` DATE NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'ACTIVE',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_classrooms_unique` (`student_id`, `classroom_id`, `academic_year_id`),
    INDEX `student_classrooms_student_index` (`student_id`),
    INDEX `student_classrooms_classroom_index` (`classroom_id`),
    INDEX `student_classrooms_academic_year_index` (`academic_year_id`),
    INDEX `student_classrooms_status_index` (`status`),
    INDEX `student_classrooms_deleted_at_index` (`deleted_at`),
    INDEX `student_classrooms_created_by_index` (`created_by`),
    INDEX `student_classrooms_updated_by_index` (`updated_by`),
    CONSTRAINT `student_classrooms_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_classrooms_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_classrooms_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 16. PERIODS / SEMESTERS
-- ============================================
CREATE TABLE `periods` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `type` VARCHAR(20) NULL,
    `semester` INTEGER NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `grading_start` DATE NULL,
    `grading_end` DATE NULL,
    `assessment_type` VARCHAR(30) NULL,
    `weight` INTEGER NOT NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `periods_academic_year_code_unique` (`academic_year_id`, `code`),
    INDEX `periods_academic_year_index` (`academic_year_id`),
    INDEX `periods_date_range_index` (`start_date`, `end_date`),
    INDEX `periods_is_active_index` (`is_active`),
    INDEX `periods_deleted_at_index` (`deleted_at`),
    INDEX `periods_created_by_index` (`created_by`),
    INDEX `periods_updated_by_index` (`updated_by`),
    CONSTRAINT `periods_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 17. GRADES (Nilai Siswa)
-- ============================================
CREATE TABLE `grades` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_classroom_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `score` DECIMAL(5, 2) NULL,
    `grade_letter` VARCHAR(2) NULL,
    `predicate` VARCHAR(50) NULL,
    `description` TEXT NULL,
    `assessment_type` VARCHAR(20) NULL,
    `weight` INTEGER NOT NULL DEFAULT 1,
    `is_final` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `grades_unique` (
        `student_classroom_id`,
        `subject_id`,
        `period_id`,
        `assessment_type`
    ),
    INDEX `grades_student_classroom_index` (`student_classroom_id`),
    INDEX `grades_subject_index` (`subject_id`),
    INDEX `grades_period_index` (`period_id`),
    INDEX `grades_teacher_index` (`teacher_id`),
    INDEX `grades_is_final_index` (`is_final`),
    INDEX `grades_deleted_at_index` (`deleted_at`),
    INDEX `grades_created_by_index` (`created_by`),
    INDEX `grades_updated_by_index` (`updated_by`),
    CONSTRAINT `grades_student_classroom_id_foreign` FOREIGN KEY (`student_classroom_id`) REFERENCES `student_classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `grades_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `grades_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `grades_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 18. GRADE_SCALES (Konversi Nilai)
-- ============================================
CREATE TABLE `grade_scales` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `min_score` DECIMAL(5, 2) NOT NULL,
    `max_score` DECIMAL(5, 2) NOT NULL,
    `grade_letter` VARCHAR(2) NOT NULL,
    `predicate` VARCHAR(50) NOT NULL,
    `description` TEXT NULL,
    `is_default` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `grade_scales_school_letter_unique` (`school_id`, `grade_letter`),
    INDEX `grade_scales_school_index` (`school_id`),
    INDEX `grade_scales_is_default_index` (`is_default`),
    INDEX `grade_scales_deleted_at_index` (`deleted_at`),
    INDEX `grade_scales_created_by_index` (`created_by`),
    INDEX `grade_scales_updated_by_index` (`updated_by`),
    CONSTRAINT `grade_scales_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 19. COURSES (e-Learning)
-- ============================================
CREATE TABLE `courses` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `thumbnail_url` TEXT NULL,
    `level` VARCHAR(20) NULL,
    `duration_hours` INTEGER NOT NULL DEFAULT 0,
    `is_published` TINYINT(1) NOT NULL DEFAULT 0,
    `published_at` TIMESTAMP NULL,
    `enrollment_count` INTEGER NOT NULL DEFAULT 0,
    `rating` DECIMAL(3, 2) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `courses_school_slug_unique` (`school_id`, `slug`),
    INDEX `courses_school_index` (`school_id`),
    INDEX `courses_teacher_index` (`teacher_id`),
    INDEX `courses_subject_index` (`subject_id`),
    INDEX `courses_is_published_index` (`is_published`),
    INDEX `courses_deleted_at_index` (`deleted_at`),
    INDEX `courses_created_by_index` (`created_by`),
    INDEX `courses_updated_by_index` (`updated_by`),
    CONSTRAINT `courses_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `courses_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `courses_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 20. LESSONS (Pertemuan/Bab)
-- ============================================
CREATE TABLE `lessons` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `content` TEXT NULL,
    `video_url` TEXT NULL,
    `duration_minutes` INTEGER NOT NULL DEFAULT 0,
    `order_index` INTEGER NOT NULL,
    `is_free_preview` TINYINT(1) NOT NULL DEFAULT 0,
    `is_published` TINYINT(1) NOT NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `lessons_course_index` (`course_id`),
    INDEX `lessons_order_index` (`order_index`),
    INDEX `lessons_is_published_index` (`is_published`),
    INDEX `lessons_deleted_at_index` (`deleted_at`),
    INDEX `lessons_created_by_index` (`created_by`),
    INDEX `lessons_updated_by_index` (`updated_by`),
    CONSTRAINT `lessons_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 21. ASSIGNMENTS_QUIZZES (STI: ASSIGNMENT / QUIZ / EXAM)
-- ============================================
CREATE TABLE `assignments_quizzes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id` BIGINT UNSIGNED NOT NULL,
    `lesson_id` BIGINT UNSIGNED NULL,
    `type` VARCHAR(20) NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `max_score` INTEGER NOT NULL DEFAULT 100,
    `time_limit_minutes` INTEGER NULL,
    `passing_score` INTEGER NOT NULL DEFAULT 70,
    `due_date` TIMESTAMP NULL,
    `is_published` TINYINT(1) NOT NULL DEFAULT 0,
    `question_data` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `assignments_quizzes_course_index` (`course_id`),
    INDEX `assignments_quizzes_lesson_index` (`lesson_id`),
    INDEX `assignments_quizzes_type_index` (`type`),
    INDEX `assignments_quizzes_due_date_index` (`due_date`),
    INDEX `assignments_quizzes_is_published_index` (`is_published`),
    INDEX `assignments_quizzes_deleted_at_index` (`deleted_at`),
    INDEX `assignments_quizzes_created_by_index` (`created_by`),
    INDEX `assignments_quizzes_updated_by_index` (`updated_by`),
    CONSTRAINT `assignments_quizzes_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `assignments_quizzes_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 22. SUBMISSIONS (Pengumpulan Tugas)
-- ============================================
CREATE TABLE `submissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `assignment_quiz_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `submission_text` TEXT NULL,
    `file_urls` JSON NULL,
    `score` DECIMAL(5, 2) NULL,
    `feedback` TEXT NULL,
    `graded_by` BIGINT UNSIGNED NULL,
    `submitted_at` TIMESTAMP NULL,
    `graded_at` TIMESTAMP NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'SUBMITTED',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `submissions_assignment_student_unique` (`assignment_quiz_id`, `student_id`),
    INDEX `submissions_assignment_index` (`assignment_quiz_id`),
    INDEX `submissions_student_index` (`student_id`),
    INDEX `submissions_status_index` (`status`),
    INDEX `submissions_graded_by_index` (`graded_by`),
    INDEX `submissions_deleted_at_index` (`deleted_at`),
    INDEX `submissions_created_by_index` (`created_by`),
    INDEX `submissions_updated_by_index` (`updated_by`),
    CONSTRAINT `submissions_assignment_quiz_id_foreign` FOREIGN KEY (`assignment_quiz_id`) REFERENCES `assignments_quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `submissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `submissions_graded_by_foreign` FOREIGN KEY (`graded_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 23. QUIZ_ATTEMPTS (Jawaban Kuis)
-- ============================================
CREATE TABLE `quiz_attempts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `assignment_quiz_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `answers` JSON NOT NULL,
    `score` DECIMAL(5, 2) NULL,
    `started_at` TIMESTAMP NULL,
    `submitted_at` TIMESTAMP NULL,
    `time_spent_seconds` INTEGER NULL,
    `is_completed` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `quiz_attempts_assignment_index` (`assignment_quiz_id`),
    INDEX `quiz_attempts_student_index` (`student_id`),
    INDEX `quiz_attempts_is_completed_index` (`is_completed`),
    INDEX `quiz_attempts_deleted_at_index` (`deleted_at`),
    INDEX `quiz_attempts_created_by_index` (`created_by`),
    INDEX `quiz_attempts_updated_by_index` (`updated_by`),
    CONSTRAINT `quiz_attempts_assignment_quiz_id_foreign` FOREIGN KEY (`assignment_quiz_id`) REFERENCES `assignments_quizzes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `quiz_attempts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 24. LESSON_PROGRESS (Progres Belajar)
-- ============================================
CREATE TABLE `lesson_progress` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `lesson_id` BIGINT UNSIGNED NOT NULL,
    `is_completed` TINYINT(1) NOT NULL DEFAULT 0,
    `progress_percent` INTEGER NOT NULL DEFAULT 0,
    `last_position_seconds` INTEGER NOT NULL DEFAULT 0,
    `started_at` TIMESTAMP NULL,
    `completed_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `lesson_progress_student_lesson_unique` (`student_id`, `lesson_id`),
    INDEX `lesson_progress_student_index` (`student_id`),
    INDEX `lesson_progress_lesson_index` (`lesson_id`),
    INDEX `lesson_progress_completed_index` (`is_completed`),
    INDEX `lesson_progress_deleted_at_index` (`deleted_at`),
    INDEX `lesson_progress_created_by_index` (`created_by`),
    INDEX `lesson_progress_updated_by_index` (`updated_by`),
    CONSTRAINT `lesson_progress_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lesson_progress_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 25. DISCUSSIONS (Forum Diskusi)
-- ============================================
CREATE TABLE `discussions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `content` TEXT NOT NULL,
    `is_pinned` TINYINT(1) NOT NULL DEFAULT 0,
    `is_locked` TINYINT(1) NOT NULL DEFAULT 0,
    `views_count` INTEGER NOT NULL DEFAULT 0,
    `replies_count` INTEGER NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `discussions_course_index` (`course_id`),
    INDEX `discussions_user_index` (`user_id`),
    INDEX `discussions_created_at_index` (`created_at`),
    INDEX `discussions_is_pinned_index` (`is_pinned`),
    INDEX `discussions_deleted_at_index` (`deleted_at`),
    INDEX `discussions_created_by_index` (`created_by`),
    INDEX `discussions_updated_by_index` (`updated_by`),
    CONSTRAINT `discussions_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `discussions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 26. DISCUSSION_REPLIES
-- ============================================
CREATE TABLE `discussion_replies` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `discussion_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `content` TEXT NOT NULL,
    `is_solution` TINYINT(1) NOT NULL DEFAULT 0,
    `likes_count` INTEGER NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `discussion_replies_discussion_index` (`discussion_id`),
    INDEX `discussion_replies_user_index` (`user_id`),
    INDEX `discussion_replies_is_solution_index` (`is_solution`),
    INDEX `discussion_replies_deleted_at_index` (`deleted_at`),
    INDEX `discussion_replies_created_by_index` (`created_by`),
    INDEX `discussion_replies_updated_by_index` (`updated_by`),
    CONSTRAINT `discussion_replies_discussion_id_foreign` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `discussion_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 27. REPORT_CARD_TEMPLATES
-- ============================================
CREATE TABLE `report_card_templates` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `template_data` JSON NOT NULL,
    `is_default` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `version` INTEGER NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `report_card_templates_school_code_unique` (`school_id`, `code`),
    INDEX `report_card_templates_school_index` (`school_id`),
    INDEX `report_card_templates_is_default_index` (`is_default`),
    INDEX `report_card_templates_is_active_index` (`is_active`),
    INDEX `report_card_templates_deleted_at_index` (`deleted_at`),
    INDEX `report_card_templates_created_by_index` (`created_by`),
    INDEX `report_card_templates_updated_by_index` (`updated_by`),
    CONSTRAINT `report_card_templates_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 28. GENERATED_REPORTS (Rapor yang Dihasilkan)
-- ============================================
CREATE TABLE `generated_reports` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NOT NULL,
    `template_id` BIGINT UNSIGNED NULL,
    `pdf_path` TEXT NOT NULL,
    `report_data` JSON NULL,
    `generated_at` TIMESTAMP NULL,
    `downloaded_count` INTEGER NOT NULL DEFAULT 0,
    `last_download_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `generated_reports_student_index` (`student_id`),
    INDEX `generated_reports_period_index` (`period_id`),
    INDEX `generated_reports_template_index` (`template_id`),
    INDEX `generated_reports_generated_at_index` (`generated_at`),
    INDEX `generated_reports_deleted_at_index` (`deleted_at`),
    INDEX `generated_reports_created_by_index` (`created_by`),
    INDEX `generated_reports_updated_by_index` (`updated_by`),
    CONSTRAINT `generated_reports_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `generated_reports_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `generated_reports_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `report_card_templates` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 29. TEACHER_COMMENTS (Catatan Wali Kelas)
-- ============================================
CREATE TABLE `teacher_comments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NOT NULL,
    `classroom_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `comment` TEXT NOT NULL,
    `comment_type` VARCHAR(20) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `teacher_comments_student_index` (`student_id`),
    INDEX `teacher_comments_period_index` (`period_id`),
    INDEX `teacher_comments_classroom_index` (`classroom_id`),
    INDEX `teacher_comments_teacher_index` (`teacher_id`),
    INDEX `teacher_comments_deleted_at_index` (`deleted_at`),
    INDEX `teacher_comments_created_by_index` (`created_by`),
    INDEX `teacher_comments_updated_by_index` (`updated_by`),
    CONSTRAINT `teacher_comments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `teacher_comments_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `teacher_comments_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `teacher_comments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 30. ACHIEVEMENTS (Prestasi Siswa)
-- ============================================
CREATE TABLE `achievements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `achievement_type` VARCHAR(50) NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `level` VARCHAR(20) NULL,
    `date_achieved` DATE NULL,
    `certificate_url` TEXT NULL,
    `points` INTEGER NOT NULL DEFAULT 0,
    `verified_by` BIGINT UNSIGNED NULL,
    `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `achievements_student_index` (`student_id`),
    INDEX `achievements_type_index` (`achievement_type`),
    INDEX `achievements_level_index` (`level`),
    INDEX `achievements_date_index` (`date_achieved`),
    INDEX `achievements_is_verified_index` (`is_verified`),
    INDEX `achievements_deleted_at_index` (`deleted_at`),
    INDEX `achievements_created_by_index` (`created_by`),
    INDEX `achievements_updated_by_index` (`updated_by`),
    CONSTRAINT `achievements_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `achievements_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 31. ATTENDANCES (Kehadiran Siswa)
-- ============================================
CREATE TABLE `student_attendances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `schedule_id` BIGINT UNSIGNED NULL,
    `classroom_id` BIGINT UNSIGNED NULL,
    `date` DATE NOT NULL,
    `status` VARCHAR(20) NULL,
    `check_in_time` TIME NULL,
    `check_out_time` TIME NULL,
    `late_minutes` INTEGER NOT NULL DEFAULT 0,
    `reason` TEXT NULL,
    `verified_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `attendances_student_schedule_date_unique` (`student_id`, `schedule_id`, `date`),
    INDEX `attendances_student_index` (`student_id`),
    INDEX `attendances_schedule_index` (`schedule_id`),
    INDEX `attendances_classroom_index` (`classroom_id`),
    INDEX `attendances_date_index` (`date`),
    INDEX `attendances_status_index` (`status`),
    INDEX `attendances_deleted_at_index` (`deleted_at`),
    INDEX `attendances_created_by_index` (`created_by`),
    INDEX `attendances_updated_by_index` (`updated_by`),
    CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `attendances_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `attendances_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `attendances_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 32. EXTRACURRICULARS (Ekstrakurikuler)
-- ============================================
CREATE TABLE `extracurriculars` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `description` TEXT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `day_of_week` VARCHAR(10) NULL,
    `start_time` TIME NULL,
    `end_time` TIME NULL,
    `max_students` INTEGER NOT NULL DEFAULT 30,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `extracurriculars_school_code_unique` (`school_id`, `code`),
    INDEX `extracurriculars_school_index` (`school_id`),
    INDEX `extracurriculars_teacher_index` (`teacher_id`),
    INDEX `extracurriculars_day_index` (`day_of_week`),
    INDEX `extracurriculars_deleted_at_index` (`deleted_at`),
    INDEX `extracurriculars_created_by_index` (`created_by`),
    INDEX `extracurriculars_updated_by_index` (`updated_by`),
    CONSTRAINT `extracurriculars_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `extracurriculars_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 33. STUDENT_EXTRACURRICULARS (Pivot)
-- ============================================
CREATE TABLE `student_extracurriculars` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `extracurricular_id` BIGINT UNSIGNED NOT NULL,
    `enrollment_date` DATE NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'ACTIVE',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_extracurriculars_unique` (`student_id`, `extracurricular_id`),
    INDEX `student_extracurriculars_student_index` (`student_id`),
    INDEX `student_extracurriculars_extracurricular_index` (`extracurricular_id`),
    INDEX `student_extracurriculars_status_index` (`status`),
    INDEX `student_extracurriculars_deleted_at_index` (`deleted_at`),
    INDEX `student_extracurriculars_created_by_index` (`created_by`),
    INDEX `student_extracurriculars_updated_by_index` (`updated_by`),
    CONSTRAINT `student_extracurriculars_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_extracurriculars_extracurricular_id_foreign` FOREIGN KEY (`extracurricular_id`) REFERENCES `extracurriculars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 34. SYSTEM_LOGS (Audit Trail)
-- ============================================
CREATE TABLE `system_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `action` VARCHAR(50) NOT NULL,
    `table_name` VARCHAR(50) NULL,
    `record_id` BIGINT UNSIGNED NULL,
    `old_data` JSON NULL,
    `new_data` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `system_logs_user_index` (`user_id`),
    INDEX `system_logs_action_index` (`action`),
    INDEX `system_logs_table_name_index` (`table_name`),
    INDEX `system_logs_record_id_index` (`record_id`),
    INDEX `system_logs_created_at_index` (`created_at`),
    INDEX `system_logs_deleted_at_index` (`deleted_at`),
    INDEX `system_logs_created_by_index` (`created_by`),
    INDEX `system_logs_updated_by_index` (`updated_by`),
    CONSTRAINT `system_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 35. TIMETABLE_CONSTRAINTS (Constraint Guru)
-- ============================================
CREATE TABLE `timetable_constraints` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NOT NULL,
    `day_of_week` VARCHAR(10) NULL,
    `time_slot_start` TIME NULL,
    `time_slot_end` TIME NULL,
    `constraint_type` VARCHAR(20) NULL,
    `reason` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `timetable_constraints_school_index` (`school_id`),
    INDEX `timetable_constraints_teacher_index` (`teacher_id`),
    INDEX `timetable_constraints_day_index` (`day_of_week`),
    INDEX `timetable_constraints_type_index` (`constraint_type`),
    INDEX `timetable_constraints_deleted_at_index` (`deleted_at`),
    INDEX `timetable_constraints_created_by_index` (`created_by`),
    INDEX `timetable_constraints_updated_by_index` (`updated_by`),
    CONSTRAINT `timetable_constraints_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `timetable_constraints_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 36. TIMETABLE_ROOMS (Ruangan)
-- ============================================
CREATE TABLE `timetable_rooms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `room_code` VARCHAR(20) NOT NULL,
    `room_name` VARCHAR(100) NOT NULL,
    `capacity` INTEGER NOT NULL DEFAULT 30,
    `room_type` VARCHAR(20) NULL,
    `floor_number` INTEGER NULL,
    `building` VARCHAR(50) NULL,
    `facilities` JSON NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `timetable_rooms_school_code_unique` (`school_id`, `room_code`),
    INDEX `timetable_rooms_school_index` (`school_id`),
    INDEX `timetable_rooms_type_index` (`room_type`),
    INDEX `timetable_rooms_deleted_at_index` (`deleted_at`),
    INDEX `timetable_rooms_created_by_index` (`created_by`),
    INDEX `timetable_rooms_updated_by_index` (`updated_by`),
    CONSTRAINT `timetable_rooms_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 37. ROOM_CONSTRAINTS (Constraint Ruangan)
-- ============================================
CREATE TABLE `room_constraints` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `room_id` BIGINT UNSIGNED NOT NULL,
    `day_of_week` VARCHAR(10) NULL,
    `time_slot_start` TIME NULL,
    `time_slot_end` TIME NULL,
    `constraint_type` VARCHAR(20) NULL,
    `reason` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `room_constraints_room_index` (`room_id`),
    INDEX `room_constraints_day_index` (`day_of_week`),
    INDEX `room_constraints_type_index` (`constraint_type`),
    INDEX `room_constraints_deleted_at_index` (`deleted_at`),
    INDEX `room_constraints_created_by_index` (`created_by`),
    INDEX `room_constraints_updated_by_index` (`updated_by`),
    CONSTRAINT `room_constraints_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `timetable_rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 38. TIMETABLE_GENERATIONS (Hasil Generate Timetable)
-- ============================================
CREATE TABLE `timetable_generations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `semester` VARCHAR(10) NULL,
    `generation_date` TIMESTAMP NULL,
    `algorithm_version` VARCHAR(50) NULL,
    `total_conflicts` INTEGER NOT NULL DEFAULT 0,
    `total_assignments` INTEGER NOT NULL DEFAULT 0,
    `total_teachers` INTEGER NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'GENERATED',
    `generation_data` JSON NULL,
    `generated_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `timetable_generations_school_index` (`school_id`),
    INDEX `timetable_generations_academic_year_index` (`academic_year_id`),
    INDEX `timetable_generations_status_index` (`status`),
    INDEX `timetable_generations_generated_by_index` (`generated_by`),
    INDEX `timetable_generations_deleted_at_index` (`deleted_at`),
    INDEX `timetable_generations_created_by_index` (`created_by`),
    INDEX `timetable_generations_updated_by_index` (`updated_by`),
    CONSTRAINT `timetable_generations_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `timetable_generations_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `timetable_generations_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 39. ALTER SCHEDULES — Tambah room_id, generation_id, dll
-- ============================================
ALTER TABLE
    `schedules`
ADD
    COLUMN `room_id` BIGINT UNSIGNED NULL
AFTER
    `room`,
ADD
    COLUMN `generation_id` BIGINT UNSIGNED NULL
AFTER
    `room_id`,
ADD
    COLUMN `is_locked` TINYINT(1) NOT NULL DEFAULT 0
AFTER
    `is_active`,
ADD
    COLUMN `notes` TEXT NULL
AFTER
    `is_locked`,
ADD
    INDEX `schedules_room_index` (`room_id`),
ADD
    INDEX `schedules_generation_index` (`generation_id`),
ADD
    CONSTRAINT `schedules_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `timetable_rooms` (`id`) ON DELETE
SET
    NULL ON UPDATE CASCADE,
ADD
    CONSTRAINT `schedules_generation_id_foreign` FOREIGN KEY (`generation_id`) REFERENCES `timetable_generations` (`id`) ON DELETE
SET
    NULL ON UPDATE CASCADE;

-- ============================================
-- 40. QUESTION_BANKS (Bank Soal)
-- ============================================
CREATE TABLE `question_banks` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `question_type` VARCHAR(30) NULL,
    `difficulty_level` VARCHAR(20) NULL,
    `cognitive_level` VARCHAR(20) NULL,
    `question_text` TEXT NOT NULL,
    `options` JSON NULL,
    `correct_answer` JSON NULL,
    `explanation` TEXT NULL,
    `tags` JSON NULL,
    `points` INTEGER NOT NULL DEFAULT 10,
    `is_public` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `question_banks_school_index` (`school_id`),
    INDEX `question_banks_subject_index` (`subject_id`),
    INDEX `question_banks_type_index` (`question_type`),
    INDEX `question_banks_difficulty_index` (`difficulty_level`),
    INDEX `question_banks_cognitive_index` (`cognitive_level`),
    INDEX `question_banks_is_public_index` (`is_public`),
    INDEX `question_banks_is_active_index` (`is_active`),
    INDEX `question_banks_deleted_at_index` (`deleted_at`),
    INDEX `question_banks_created_by_index` (`created_by`),
    INDEX `question_banks_updated_by_index` (`updated_by`),
    CONSTRAINT `question_banks_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `question_banks_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 41. EXAM_PACKAGES (Paket Soal)
-- ============================================
CREATE TABLE `exam_packages` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `duration_minutes` INTEGER NOT NULL DEFAULT 60,
    `total_questions` INTEGER NOT NULL DEFAULT 0,
    `passing_score` INTEGER NOT NULL DEFAULT 70,
    `is_randomized` TINYINT(1) NOT NULL DEFAULT 0,
    `shuffle_options` TINYINT(1) NOT NULL DEFAULT 0,
    `show_results` TINYINT(1) NOT NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `exam_packages_school_index` (`school_id`),
    INDEX `exam_packages_subject_index` (`subject_id`),
    INDEX `exam_packages_is_active_index` (`is_active`),
    INDEX `exam_packages_deleted_at_index` (`deleted_at`),
    INDEX `exam_packages_created_by_index` (`created_by`),
    INDEX `exam_packages_updated_by_index` (`updated_by`),
    CONSTRAINT `exam_packages_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `exam_packages_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 42. EXAM_PACKAGE_QUESTIONS (Pivot)
-- ============================================
CREATE TABLE `exam_package_questions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `package_id` BIGINT UNSIGNED NOT NULL,
    `question_id` BIGINT UNSIGNED NOT NULL,
    `question_order` INTEGER NOT NULL DEFAULT 0,
    `points` INTEGER NOT NULL DEFAULT 10,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `exam_package_questions_unique` (`package_id`, `question_id`),
    INDEX `exam_package_questions_package_index` (`package_id`),
    INDEX `exam_package_questions_question_index` (`question_id`),
    INDEX `exam_package_questions_deleted_at_index` (`deleted_at`),
    CONSTRAINT `exam_package_questions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `exam_packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `exam_package_questions_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `question_banks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 43. CBT_SESSIONS (Sesi Ujian)
-- ============================================
CREATE TABLE `cbt_sessions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `exam_package_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `schedule_id` BIGINT UNSIGNED NULL,
    `class_id` BIGINT UNSIGNED NULL,
    `start_time` TIMESTAMP NULL,
    `end_time` TIMESTAMP NULL,
    `duration_seconds` INTEGER NULL,
    `current_question_index` INTEGER NOT NULL DEFAULT 0,
    `answers` JSON NULL,
    `status` VARCHAR(20) NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `proctor_notes` TEXT NULL,
    `is_submitted` TINYINT(1) NOT NULL DEFAULT 0,
    `submitted_at` TIMESTAMP NULL,
    `final_score` DECIMAL(5, 2) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `cbt_sessions_exam_package_index` (`exam_package_id`),
    INDEX `cbt_sessions_student_index` (`student_id`),
    INDEX `cbt_sessions_schedule_index` (`schedule_id`),
    INDEX `cbt_sessions_class_index` (`class_id`),
    INDEX `cbt_sessions_status_index` (`status`),
    INDEX `cbt_sessions_is_submitted_index` (`is_submitted`),
    INDEX `cbt_sessions_deleted_at_index` (`deleted_at`),
    INDEX `cbt_sessions_created_by_index` (`created_by`),
    INDEX `cbt_sessions_updated_by_index` (`updated_by`),
    CONSTRAINT `cbt_sessions_exam_package_id_foreign` FOREIGN KEY (`exam_package_id`) REFERENCES `exam_packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `cbt_sessions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `cbt_sessions_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `cbt_sessions_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classrooms` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 44. CBT_ACTIVITY_LOGS (Anti-Cheating Log)
-- ============================================
CREATE TABLE `cbt_activity_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `session_id` BIGINT UNSIGNED NOT NULL,
    `activity_type` VARCHAR(30) NULL,
    `activity_data` JSON NULL,
    `recorded_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `cbt_activity_logs_session_index` (`session_id`),
    INDEX `cbt_activity_logs_type_index` (`activity_type`),
    INDEX `cbt_activity_logs_recorded_at_index` (`recorded_at`),
    INDEX `cbt_activity_logs_deleted_at_index` (`deleted_at`),
    INDEX `cbt_activity_logs_created_by_index` (`created_by`),
    INDEX `cbt_activity_logs_updated_by_index` (`updated_by`),
    CONSTRAINT `cbt_activity_logs_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 45. CBT_PROCTORING (Dashboard Pengawas)
-- ============================================
CREATE TABLE `cbt_proctoring` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `session_id` BIGINT UNSIGNED NOT NULL,
    `proctor_id` BIGINT UNSIGNED NULL,
    `start_proctoring` TIMESTAMP NULL,
    `end_proctoring` TIMESTAMP NULL,
    `notes` TEXT NULL,
    `status` VARCHAR(20) NULL,
    `flag_reason` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `cbt_proctoring_session_index` (`session_id`),
    INDEX `cbt_proctoring_proctor_index` (`proctor_id`),
    INDEX `cbt_proctoring_status_index` (`status`),
    INDEX `cbt_proctoring_deleted_at_index` (`deleted_at`),
    INDEX `cbt_proctoring_created_by_index` (`created_by`),
    INDEX `cbt_proctoring_updated_by_index` (`updated_by`),
    CONSTRAINT `cbt_proctoring_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `cbt_sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `cbt_proctoring_proctor_id_foreign` FOREIGN KEY (`proctor_id`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 46. ADMISSIONS (Master PPDB)
-- ============================================
CREATE TABLE `admissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `type` VARCHAR(20) NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `quota` INTEGER NOT NULL DEFAULT 0,
    `registration_fee` DECIMAL(15, 2) NOT NULL DEFAULT 0,
    `min_age` INTEGER NULL,
    `max_age` INTEGER NULL,
    `requirements` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `admissions_school_code_unique` (`school_id`, `code`),
    INDEX `admissions_school_index` (`school_id`),
    INDEX `admissions_academic_year_index` (`academic_year_id`),
    INDEX `admissions_class_level_index` (`class_level_id`),
    INDEX `admissions_dates_index` (`start_date`, `end_date`),
    INDEX `admissions_is_active_index` (`is_active`),
    INDEX `admissions_deleted_at_index` (`deleted_at`),
    INDEX `admissions_created_by_index` (`created_by`),
    INDEX `admissions_updated_by_index` (`updated_by`),
    CONSTRAINT `admissions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `admissions_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `admissions_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 47. APPLICANTS (Pendaftar)
-- ============================================
CREATE TABLE `applicants` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `admission_id` BIGINT UNSIGNED NOT NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `nik` VARCHAR(50) NULL,
    `birth_place` VARCHAR(100) NULL,
    `birth_date` DATE NULL,
    `gender` VARCHAR(10) NULL,
    `address` TEXT NULL,
    `phone` VARCHAR(20) NULL,
    `email` VARCHAR(100) NULL,
    `parent_name` VARCHAR(150) NULL,
    `parent_phone` VARCHAR(20) NULL,
    `parent_email` VARCHAR(100) NULL,
    `previous_school` VARCHAR(150) NULL,
    `registration_number` VARCHAR(50) NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'REGISTERED',
    `test_score` DECIMAL(5, 2) NULL,
    `interview_score` DECIMAL(5, 2) NULL,
    `total_score` DECIMAL(5, 2) NULL,
    `ranking` INTEGER NULL,
    `notes` TEXT NULL,
    `registered_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `applicants_school_registration_number_unique` (`school_id`, `registration_number`),
    UNIQUE INDEX `applicants_school_nik_unique` (`school_id`, `nik`),
    INDEX `applicants_school_index` (`school_id`),
    INDEX `applicants_admission_index` (`admission_id`),
    INDEX `applicants_status_index` (`status`),
    INDEX `applicants_registration_number_index` (`registration_number`),
    INDEX `applicants_deleted_at_index` (`deleted_at`),
    INDEX `applicants_created_by_index` (`created_by`),
    INDEX `applicants_updated_by_index` (`updated_by`),
    CONSTRAINT `applicants_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `applicants_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 48. APPLICATION_DOCUMENTS (Dokumen Pendaftaran)
-- ============================================
CREATE TABLE `application_documents` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `applicant_id` BIGINT UNSIGNED NOT NULL,
    `document_type` VARCHAR(30) NULL,
    `document_url` TEXT NOT NULL,
    `file_name` VARCHAR(200) NULL,
    `file_size` BIGINT NULL,
    `uploaded_at` TIMESTAMP NULL,
    `verified` TINYINT(1) NOT NULL DEFAULT 0,
    `verified_by` BIGINT UNSIGNED NULL,
    `verification_notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `application_documents_applicant_index` (`applicant_id`),
    INDEX `application_documents_type_index` (`document_type`),
    INDEX `application_documents_verified_index` (`verified`),
    INDEX `application_documents_deleted_at_index` (`deleted_at`),
    INDEX `application_documents_created_by_index` (`created_by`),
    INDEX `application_documents_updated_by_index` (`updated_by`),
    CONSTRAINT `application_documents_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `application_documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 49. ADMISSION_SELECTIONS (Proses Seleksi)
-- ============================================
CREATE TABLE `admission_selections` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `admission_id` BIGINT UNSIGNED NOT NULL,
    `applicant_id` BIGINT UNSIGNED NOT NULL,
    `selection_type` VARCHAR(20) NULL,
    `score` DECIMAL(5, 2) NULL,
    `result` VARCHAR(20) NULL,
    `evaluator_id` BIGINT UNSIGNED NULL,
    `evaluation_date` TIMESTAMP NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `admission_selections_admission_index` (`admission_id`),
    INDEX `admission_selections_applicant_index` (`applicant_id`),
    INDEX `admission_selections_type_index` (`selection_type`),
    INDEX `admission_selections_result_index` (`result`),
    INDEX `admission_selections_evaluator_index` (`evaluator_id`),
    INDEX `admission_selections_deleted_at_index` (`deleted_at`),
    INDEX `admission_selections_created_by_index` (`created_by`),
    INDEX `admission_selections_updated_by_index` (`updated_by`),
    CONSTRAINT `admission_selections_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `admission_selections_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `admission_selections_evaluator_id_foreign` FOREIGN KEY (`evaluator_id`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 50. ADMISSION_TIMELINES (Timeline Pendaftaran)
-- ============================================
CREATE TABLE `admission_timelines` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `admission_id` BIGINT UNSIGNED NOT NULL,
    `phase_name` VARCHAR(50) NOT NULL,
    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `description` TEXT NULL,
    `order_number` INTEGER NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `admission_timelines_admission_index` (`admission_id`),
    INDEX `admission_timelines_order_index` (`order_number`),
    INDEX `admission_timelines_deleted_at_index` (`deleted_at`),
    INDEX `admission_timelines_created_by_index` (`created_by`),
    INDEX `admission_timelines_updated_by_index` (`updated_by`),
    CONSTRAINT `admission_timelines_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 51. APPLICANT_INVOICES (Invoice Pendaftar)
-- ============================================
CREATE TABLE `applicant_invoices` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `applicant_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `admission_id` BIGINT UNSIGNED NOT NULL,
    `invoice_number` VARCHAR(50) NOT NULL,
    `invoice_type` VARCHAR(20) NULL,
    `due_date` DATE NULL,
    `total_amount` DECIMAL(15, 2) NOT NULL,
    `discount_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0,
    `penalty_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0,
    `final_amount` DECIMAL(15, 2) NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `applicant_invoices_school_number_unique` (`school_id`, `invoice_number`),
    INDEX `applicant_invoices_applicant_index` (`applicant_id`),
    INDEX `applicant_invoices_school_index` (`school_id`),
    INDEX `applicant_invoices_admission_index` (`admission_id`),
    INDEX `applicant_invoices_status_index` (`status`),
    INDEX `applicant_invoices_due_date_index` (`due_date`),
    INDEX `applicant_invoices_deleted_at_index` (`deleted_at`),
    INDEX `applicant_invoices_created_by_index` (`created_by`),
    INDEX `applicant_invoices_updated_by_index` (`updated_by`),
    CONSTRAINT `applicant_invoices_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `applicant_invoices_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `applicant_invoices_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 52. APPLICANT_PAYMENTS (Pembayaran Pendaftar)
-- ============================================
CREATE TABLE `applicant_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` BIGINT UNSIGNED NOT NULL,
    `applicant_id` BIGINT UNSIGNED NOT NULL,
    `payment_method` VARCHAR(30) NULL,
    `payment_date` DATE NULL,
    `amount` DECIMAL(15, 2) NOT NULL,
    `reference_number` VARCHAR(100) NULL,
    `bank_name` VARCHAR(50) NULL,
    `payment_proof_url` TEXT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `verified_by` BIGINT UNSIGNED NULL,
    `verification_date` TIMESTAMP NULL,
    `refund_date` TIMESTAMP NULL,
    `refund_reason` TEXT NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `applicant_payments_invoice_index` (`invoice_id`),
    INDEX `applicant_payments_applicant_index` (`applicant_id`),
    INDEX `applicant_payments_status_index` (`status`),
    INDEX `applicant_payments_verified_by_index` (`verified_by`),
    INDEX `applicant_payments_deleted_at_index` (`deleted_at`),
    INDEX `applicant_payments_created_by_index` (`created_by`),
    INDEX `applicant_payments_updated_by_index` (`updated_by`),
    CONSTRAINT `applicant_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `applicant_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `applicant_payments_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `applicant_payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 53. FEE_MASTERS (Master Biaya)
-- ============================================
CREATE TABLE `fee_masters` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NULL,
    `fee_category` VARCHAR(30) NULL,
    `fee_name` VARCHAR(100) NOT NULL,
    `fee_code` VARCHAR(50) NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL,
    `frequency` VARCHAR(20) NULL,
    `is_mandatory` TINYINT(1) NOT NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `description` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fee_masters_school_code_unique` (`school_id`, `fee_code`),
    INDEX `fee_masters_school_index` (`school_id`),
    INDEX `fee_masters_academic_year_index` (`academic_year_id`),
    INDEX `fee_masters_class_level_index` (`class_level_id`),
    INDEX `fee_masters_category_index` (`fee_category`),
    INDEX `fee_masters_deleted_at_index` (`deleted_at`),
    INDEX `fee_masters_created_by_index` (`created_by`),
    INDEX `fee_masters_updated_by_index` (`updated_by`),
    CONSTRAINT `fee_masters_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fee_masters_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fee_masters_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 54. FEE_STRUCTURES (Detail Biaya per Periode)
-- ============================================
CREATE TABLE `fee_structures` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `fee_master_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NOT NULL,
    `due_date` DATE NULL,
    `amount` DECIMAL(15, 2) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fee_structures_master_period_unique` (`fee_master_id`, `period_id`),
    INDEX `fee_structures_master_index` (`fee_master_id`),
    INDEX `fee_structures_period_index` (`period_id`),
    INDEX `fee_structures_due_date_index` (`due_date`),
    INDEX `fee_structures_deleted_at_index` (`deleted_at`),
    INDEX `fee_structures_created_by_index` (`created_by`),
    INDEX `fee_structures_updated_by_index` (`updated_by`),
    CONSTRAINT `fee_structures_fee_master_id_foreign` FOREIGN KEY (`fee_master_id`) REFERENCES `fee_masters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fee_structures_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 55. STUDENT_INVOICES (Invoice Siswa)
-- ============================================
CREATE TABLE `student_invoices` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `fee_master_id` BIGINT UNSIGNED NOT NULL,
    `student_classroom_id` BIGINT UNSIGNED NULL,
    `invoice_number` VARCHAR(50) NOT NULL,
    `period_id` BIGINT UNSIGNED NULL,
    `invoice_date` DATE NULL,
    `due_date` DATE NULL,
    `total_amount` DECIMAL(15, 2) NOT NULL,
    `discount_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0,
    `penalty_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0,
    `final_amount` DECIMAL(15, 2) NOT NULL,
    `paid_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_invoices_school_number_unique` (`school_id`, `invoice_number`),
    INDEX `student_invoices_student_index` (`student_id`),
    INDEX `student_invoices_school_index` (`school_id`),
    INDEX `student_invoices_fee_master_index` (`fee_master_id`),
    INDEX `student_invoices_student_classroom_index` (`student_classroom_id`),
    INDEX `student_invoices_status_index` (`status`),
    INDEX `student_invoices_due_date_index` (`due_date`),
    INDEX `student_invoices_period_index` (`period_id`),
    INDEX `student_invoices_deleted_at_index` (`deleted_at`),
    INDEX `student_invoices_created_by_index` (`created_by`),
    INDEX `student_invoices_updated_by_index` (`updated_by`),
    CONSTRAINT `student_invoices_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_invoices_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_invoices_fee_master_id_foreign` FOREIGN KEY (`fee_master_id`) REFERENCES `fee_masters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_invoices_student_classroom_id_foreign` FOREIGN KEY (`student_classroom_id`) REFERENCES `student_classrooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_invoices_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 56. STUDENT_INSTALLMENTS (Cicilan Siswa)
-- ============================================
CREATE TABLE `student_installments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` BIGINT UNSIGNED NOT NULL,
    `installment_number` INTEGER NOT NULL,
    `due_date` DATE NULL,
    `amount` DECIMAL(15, 2) NOT NULL,
    `is_paid` TINYINT(1) NOT NULL DEFAULT 0,
    `paid_date` DATE NULL,
    `paid_amount` DECIMAL(15, 2) NULL,
    `payment_id` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_installments_invoice_number_unique` (`invoice_id`, `installment_number`),
    INDEX `student_installments_invoice_index` (`invoice_id`),
    INDEX `student_installments_due_date_index` (`due_date`),
    INDEX `student_installments_is_paid_index` (`is_paid`),
    INDEX `student_installments_payment_index` (`payment_id`),
    INDEX `student_installments_deleted_at_index` (`deleted_at`),
    INDEX `student_installments_created_by_index` (`created_by`),
    INDEX `student_installments_updated_by_index` (`updated_by`),
    CONSTRAINT `student_installments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 57. STUDENT_PAYMENTS (Pembayaran Siswa)
-- ============================================
CREATE TABLE `student_payments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `payment_method` VARCHAR(30) NULL,
    `payment_date` DATE NULL,
    `amount` DECIMAL(15, 2) NOT NULL,
    `reference_number` VARCHAR(100) NULL,
    `bank_name` VARCHAR(50) NULL,
    `payment_proof_url` TEXT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `verified_by` BIGINT UNSIGNED NULL,
    `verification_date` TIMESTAMP NULL,
    `refund_date` TIMESTAMP NULL,
    `refund_reason` TEXT NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `student_payments_invoice_index` (`invoice_id`),
    INDEX `student_payments_student_index` (`student_id`),
    INDEX `student_payments_status_index` (`status`),
    INDEX `student_payments_verified_by_index` (`verified_by`),
    INDEX `student_payments_deleted_at_index` (`deleted_at`),
    INDEX `student_payments_created_by_index` (`created_by`),
    INDEX `student_payments_updated_by_index` (`updated_by`),
    CONSTRAINT `student_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 58. STUDENT_PAYMENT_BATCHES (Batch Pembayaran Massal)
-- ============================================
CREATE TABLE `student_payment_batches` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `batch_number` VARCHAR(50) NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NULL,
    `description` TEXT NULL,
    `total_invoices` INTEGER NOT NULL DEFAULT 0,
    `total_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0,
    `paid_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `processed_by` BIGINT UNSIGNED NULL,
    `processed_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_payment_batches_school_number_unique` (`school_id`, `batch_number`),
    INDEX `student_payment_batches_school_index` (`school_id`),
    INDEX `student_payment_batches_academic_year_index` (`academic_year_id`),
    INDEX `student_payment_batches_period_index` (`period_id`),
    INDEX `student_payment_batches_status_index` (`status`),
    INDEX `student_payment_batches_processed_by_index` (`processed_by`),
    INDEX `student_payment_batches_deleted_at_index` (`deleted_at`),
    INDEX `student_payment_batches_created_by_index` (`created_by`),
    INDEX `student_payment_batches_updated_by_index` (`updated_by`),
    CONSTRAINT `student_payment_batches_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_payment_batches_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_payment_batches_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `student_payment_batches_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 59. STUDENT_INVOICE_BATCHES (Pivot Invoice-Batch)
-- ============================================
CREATE TABLE `student_invoice_batches` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` BIGINT UNSIGNED NOT NULL,
    `batch_id` BIGINT UNSIGNED NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_invoice_batches_unique` (`invoice_id`, `batch_id`),
    INDEX `student_invoice_batches_invoice_index` (`invoice_id`),
    INDEX `student_invoice_batches_batch_index` (`batch_id`),
    INDEX `student_invoice_batches_deleted_at_index` (`deleted_at`),
    CONSTRAINT `student_invoice_batches_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `student_invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_invoice_batches_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `student_payment_batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 60. DAPODIK_MAPPINGS (Mapping ke Dapodik)
-- ============================================
CREATE TABLE `dapodik_mappings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `local_table` VARCHAR(50) NOT NULL,
    `local_id` BIGINT UNSIGNED NOT NULL,
    `dapodik_id` VARCHAR(50) NOT NULL,
    `dapodik_table` VARCHAR(50) NOT NULL,
    `hash_value` VARCHAR(64) NULL,
    `last_sync_at` TIMESTAMP NULL,
    `sync_status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `error_message` TEXT NULL,
    `retry_count` INTEGER NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `dapodik_mappings_local_unique` (`local_table`, `local_id`),
    UNIQUE INDEX `dapodik_mappings_dapodik_unique` (`dapodik_table`, `dapodik_id`),
    INDEX `dapodik_mappings_school_index` (`school_id`),
    INDEX `dapodik_mappings_local_index` (`local_table`, `local_id`),
    INDEX `dapodik_mappings_dapodik_index` (`dapodik_table`, `dapodik_id`),
    INDEX `dapodik_mappings_status_index` (`sync_status`),
    INDEX `dapodik_mappings_deleted_at_index` (`deleted_at`),
    INDEX `dapodik_mappings_created_by_index` (`created_by`),
    INDEX `dapodik_mappings_updated_by_index` (`updated_by`),
    CONSTRAINT `dapodik_mappings_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 61. DAPODIK_SYNC_LOGS
-- ============================================
CREATE TABLE `dapodik_sync_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `sync_date` TIMESTAMP NULL,
    `sync_type` VARCHAR(20) NULL,
    `status` VARCHAR(20) NULL,
    `total_records` INTEGER NOT NULL DEFAULT 0,
    `total_synced` INTEGER NOT NULL DEFAULT 0,
    `total_failed` INTEGER NOT NULL DEFAULT 0,
    `total_conflicts` INTEGER NOT NULL DEFAULT 0,
    `sync_duration_seconds` INTEGER NULL,
    `error_log` TEXT NULL,
    `details` JSON NULL,
    `triggered_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `dapodik_sync_logs_school_index` (`school_id`),
    INDEX `dapodik_sync_logs_status_index` (`status`),
    INDEX `dapodik_sync_logs_sync_type_index` (`sync_type`),
    INDEX `dapodik_sync_logs_sync_date_index` (`sync_date`),
    INDEX `dapodik_sync_logs_triggered_by_index` (`triggered_by`),
    INDEX `dapodik_sync_logs_deleted_at_index` (`deleted_at`),
    INDEX `dapodik_sync_logs_created_by_index` (`created_by`),
    INDEX `dapodik_sync_logs_updated_by_index` (`updated_by`),
    CONSTRAINT `dapodik_sync_logs_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `dapodik_sync_logs_triggered_by_foreign` FOREIGN KEY (`triggered_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 62. DAPODIK_SYNC_SCHEDULES
-- ============================================
CREATE TABLE `dapodik_sync_schedules` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `schedule_name` VARCHAR(100) NOT NULL,
    `sync_type` VARCHAR(20) NULL,
    `frequency` VARCHAR(20) NULL,
    `last_run` TIMESTAMP NULL,
    `next_run` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `dapodik_sync_schedules_school_index` (`school_id`),
    INDEX `dapodik_sync_schedules_next_run_index` (`next_run`),
    INDEX `dapodik_sync_schedules_is_active_index` (`is_active`),
    INDEX `dapodik_sync_schedules_deleted_at_index` (`deleted_at`),
    INDEX `dapodik_sync_schedules_created_by_index` (`created_by`),
    INDEX `dapodik_sync_schedules_updated_by_index` (`updated_by`),
    CONSTRAINT `dapodik_sync_schedules_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

ALTER TABLE
    `students`
ADD
    COLUMN `applicant_id` BIGINT UNSIGNED NULL
AFTER
    `user_id`,
ADD
    INDEX `students_applicant_index` (`applicant_id`),
ADD
    CONSTRAINT `students_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE
SET
    NULL ON UPDATE CASCADE;

CREATE TABLE `lesson_plans` (
    -- RPP / Modul Ajar
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `curriculum_phase_id` BIGINT UNSIGNED NULL,
    `title` VARCHAR(200) NOT NULL,
    `learning_objectives` JSON NULL,
    -- TP
    `learning_outcomes` JSON NULL,
    -- CP
    `activities` JSON NULL,
    -- Langkah pembelajaran
    `assessment_plan` JSON NULL,
    `media` JSON NULL,
    `duration_minutes` INTEGER NULL,
    `meeting_number` INTEGER NULL,
    `status` VARCHAR(20) DEFAULT 'DRAFT',
    -- DRAFT, REVIEW, APPROVED
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL
);

-- Materi ajar
CREATE TABLE `learning_materials` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `lesson_plan_id` BIGINT UNSIGNED NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `type` VARCHAR(30),
    -- PDF, VIDEO, LINK, SLIDE
    `url` TEXT,
    `content` TEXT,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL
);

ALTER TABLE
    `grades`
ADD
    COLUMN `assessment_category` VARCHAR(20) NULL
AFTER
    `assessment_type`,
    -- FORMATIF, SUMATIF, DIAGNOSTIK, AKM, SURVEI_KARAKTER
ADD
    COLUMN `is_counted_in_report` TINYINT(1) NOT NULL DEFAULT 1
AFTER
    `is_final`;

-- CP per fase/mapel
CREATE TABLE `learning_outcomes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `curriculum_phase_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `element` VARCHAR(100),
    -- Bilangan, Aljabar, Geometri, dll
    `code` VARCHAR(50),
    `description` TEXT,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL
);

-- Capaian per siswa
CREATE TABLE `student_outcome_achievements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `learning_outcome_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NOT NULL,
    `achievement_level` VARCHAR(20),
    -- BELUM_TERCAPAI, CUKUP, BAIK, SANGAT_BAIK
    `narrative` TEXT,
    -- Deskripsi kualitatif
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL
);

CREATE TABLE `student_promotions` (
    -- Kenaikan kelas
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `from_classroom_id` BIGINT UNSIGNED,
    `to_classroom_id` BIGINT UNSIGNED,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `decision` VARCHAR(20),
    -- NAIK, TINGGAL, LULUS
    `decision_date` DATE,
    `meeting_notes` TEXT,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL
);

CREATE TABLE `graduations` (
    -- Kelulusan
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `graduation_date` DATE,
    `certificate_number` VARCHAR(50),
    -- No Ijazah
    `skhu_number` VARCHAR(50),
    -- No SKHU
    `final_score_average` DECIMAL(5, 2),
    `predicate` VARCHAR(50),
    `status` VARCHAR(20),
    -- LULUS, TIDAK_LULUS
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL
);

ALTER TABLE
    `student_extracurriculars`
ADD
    COLUMN `grade_letter` VARCHAR(2) NULL,
ADD
    COLUMN `predicate` VARCHAR(50) NULL,
ADD
    COLUMN `description` TEXT NULL,
ADD
    COLUMN `certificate_url` TEXT NULL,
ADD
    COLUMN `achievement_notes` TEXT NULL;

CREATE TABLE `announcements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `content` TEXT,
    `audience` VARCHAR(30),
    -- ALL, TEACHERS, STUDENTS, PARENTS
    `published_at` TIMESTAMP,
    `expired_at` TIMESTAMP,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL
);

CREATE TABLE `notifications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `type` VARCHAR(30),
    `title` VARCHAR(200),
    `message` TEXT,
    `is_read` TINYINT(1) DEFAULT 0,
    `read_at` TIMESTAMP,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL
);

CREATE TABLE `student_disciplines` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `category` VARCHAR(50),
    -- TERLAMBAT, BOLOS, ATRIBUT, dll
    `description` TEXT,
    `points` INTEGER,
    `action_taken` TEXT,
    -- Tindakan
    `incident_date` DATE,
    `reported_by` BIGINT UNSIGNED,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL
);

CREATE TABLE `student_health_records` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `record_date` DATE,
    `complaint` TEXT,
    `diagnosis` TEXT,
    `treatment` TEXT,
    `height_cm` DECIMAL(5, 2),
    `weight_kg` DECIMAL(5, 2),
    `blood_type` VARCHAR(3),
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL
);

CREATE TABLE `counseling_sessions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `counselor_id` BIGINT UNSIGNED,
    `session_date` TIMESTAMP,
    `category` VARCHAR(30),
    -- AKADEMIK, SOSIAL, KARIR, PRIBADI
    `problem` TEXT,
    `action` TEXT,
    `follow_up` TEXT,
    `is_confidential` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL
);

CREATE TABLE `academic_calendar_events` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200),
    `event_type` VARCHAR(30),
    -- HOLIDAY, EXAM, ACTIVITY, MEETING
    `start_date` DATE,
    `end_date` DATE,
    `is_holiday` TINYINT(1) DEFAULT 0,
    `description` TEXT,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL
);

-- ============================================
-- 63. ALTER STUDENTS — Jembatan ke Applicants (GAP #1)
-- ============================================
ALTER TABLE
    `students`
ADD
    COLUMN `applicant_id` BIGINT UNSIGNED NULL
AFTER
    `user_id`,
ADD
    INDEX `students_applicant_index` (`applicant_id`),
ADD
    CONSTRAINT `students_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE
SET
    NULL ON UPDATE CASCADE;

-- Tabel audit konversi pendaftar → siswa
CREATE TABLE `enrollment_conversions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `applicant_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `admission_id` BIGINT UNSIGNED NOT NULL,
    `converted_at` TIMESTAMP NULL,
    `converted_by` BIGINT UNSIGNED NULL,
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `enrollment_conversions_applicant_unique` (`applicant_id`),
    UNIQUE INDEX `enrollment_conversions_student_unique` (`student_id`),
    INDEX `enrollment_conversions_school_index` (`school_id`),
    INDEX `enrollment_conversions_admission_index` (`admission_id`),
    INDEX `enrollment_conversions_deleted_at_index` (`deleted_at`),
    INDEX `enrollment_conversions_created_by_index` (`created_by`),
    INDEX `enrollment_conversions_updated_by_index` (`updated_by`),
    CONSTRAINT `enrollment_conversions_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `enrollment_conversions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `enrollment_conversions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `enrollment_conversions_admission_id_foreign` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `enrollment_conversions_converted_by_foreign` FOREIGN KEY (`converted_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 64. LESSON_PLANS (RPP / Modul Ajar) — GAP #2
-- ============================================
CREATE TABLE `lesson_plans` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `teacher_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `curriculum_phase_id` BIGINT UNSIGNED NULL,
    `period_id` BIGINT UNSIGNED NULL,
    `title` VARCHAR(200) NOT NULL,
    `code` VARCHAR(50) NULL,
    `learning_objectives` JSON NULL,
    `learning_outcomes` JSON NULL,
    `activities` JSON NULL,
    `assessment_plan` JSON NULL,
    `media` JSON NULL,
    `resources` JSON NULL,
    `duration_minutes` INTEGER NULL,
    `meeting_number` INTEGER NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'DRAFT',
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `rejection_notes` TEXT NULL,
    `version` INTEGER NOT NULL DEFAULT 1,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `lesson_plans_school_index` (`school_id`),
    INDEX `lesson_plans_teacher_index` (`teacher_id`),
    INDEX `lesson_plans_subject_index` (`subject_id`),
    INDEX `lesson_plans_class_level_index` (`class_level_id`),
    INDEX `lesson_plans_academic_year_index` (`academic_year_id`),
    INDEX `lesson_plans_phase_index` (`curriculum_phase_id`),
    INDEX `lesson_plans_period_index` (`period_id`),
    INDEX `lesson_plans_status_index` (`status`),
    INDEX `lesson_plans_approved_by_index` (`approved_by`),
    INDEX `lesson_plans_deleted_at_index` (`deleted_at`),
    INDEX `lesson_plans_created_by_index` (`created_by`),
    INDEX `lesson_plans_updated_by_index` (`updated_by`),
    CONSTRAINT `lesson_plans_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lesson_plans_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lesson_plans_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lesson_plans_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lesson_plans_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lesson_plans_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `lesson_plans_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `lesson_plans_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 65. LEARNING_MATERIALS (Materi Ajar) — GAP #2
-- ============================================
CREATE TABLE `learning_materials` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `lesson_plan_id` BIGINT UNSIGNED NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `class_level_id` BIGINT UNSIGNED NULL,
    `curriculum_phase_id` BIGINT UNSIGNED NULL,
    `title` VARCHAR(200) NOT NULL,
    `type` VARCHAR(30) NULL,
    `url` TEXT NULL,
    `content` LONGTEXT NULL,
    `file_name` VARCHAR(200) NULL,
    `file_size` BIGINT NULL,
    `mime_type` VARCHAR(100) NULL,
    `order_index` INTEGER NOT NULL DEFAULT 0,
    `is_public` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `learning_materials_school_index` (`school_id`),
    INDEX `learning_materials_lesson_plan_index` (`lesson_plan_id`),
    INDEX `learning_materials_subject_index` (`subject_id`),
    INDEX `learning_materials_class_level_index` (`class_level_id`),
    INDEX `learning_materials_phase_index` (`curriculum_phase_id`),
    INDEX `learning_materials_type_index` (`type`),
    INDEX `learning_materials_order_index` (`order_index`),
    INDEX `learning_materials_deleted_at_index` (`deleted_at`),
    INDEX `learning_materials_created_by_index` (`created_by`),
    INDEX `learning_materials_updated_by_index` (`updated_by`),
    CONSTRAINT `learning_materials_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `learning_materials_lesson_plan_id_foreign` FOREIGN KEY (`lesson_plan_id`) REFERENCES `lesson_plans` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `learning_materials_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `learning_materials_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `learning_materials_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 66. ALTER GRADES — Formatif vs Sumatif (GAP #3)
-- ============================================
ALTER TABLE
    `grades`
ADD
    COLUMN `assessment_category` VARCHAR(20) NULL
AFTER
    `assessment_type`,
ADD
    COLUMN `is_counted_in_report` TINYINT(1) NOT NULL DEFAULT 1
AFTER
    `is_final`,
ADD
    INDEX `grades_assessment_category_index` (`assessment_category`),
ADD
    INDEX `grades_is_counted_in_report_index` (`is_counted_in_report`);

-- ============================================
-- 67. LEARNING_OUTCOMES (CP per Fase & Mapel) — GAP #4
-- ============================================
CREATE TABLE `learning_outcomes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NULL,
    `curriculum_phase_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `element` VARCHAR(100) NULL,
    `code` VARCHAR(50) NULL,
    `description` TEXT NOT NULL,
    `competency` TEXT NULL,
    `order_index` INTEGER NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `learning_outcomes_school_index` (`school_id`),
    INDEX `learning_outcomes_phase_index` (`curriculum_phase_id`),
    INDEX `learning_outcomes_subject_index` (`subject_id`),
    INDEX `learning_outcomes_element_index` (`element`),
    INDEX `learning_outcomes_code_index` (`code`),
    INDEX `learning_outcomes_order_index` (`order_index`),
    INDEX `learning_outcomes_deleted_at_index` (`deleted_at`),
    INDEX `learning_outcomes_created_by_index` (`created_by`),
    INDEX `learning_outcomes_updated_by_index` (`updated_by`),
    CONSTRAINT `learning_outcomes_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `learning_outcomes_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `learning_outcomes_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 68. STUDENT_OUTCOME_ACHIEVEMENTS (Capaian Siswa) — GAP #4
-- ============================================
CREATE TABLE `student_outcome_achievements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `learning_outcome_id` BIGINT UNSIGNED NOT NULL,
    `period_id` BIGINT UNSIGNED NOT NULL,
    `subject_id` BIGINT UNSIGNED NOT NULL,
    `achievement_level` VARCHAR(20) NULL,
    `score` DECIMAL(5, 2) NULL,
    `narrative` TEXT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_outcome_achievements_unique` (`student_id`, `learning_outcome_id`, `period_id`),
    INDEX `student_outcome_achievements_student_index` (`student_id`),
    INDEX `student_outcome_achievements_outcome_index` (`learning_outcome_id`),
    INDEX `student_outcome_achievements_period_index` (`period_id`),
    INDEX `student_outcome_achievements_subject_index` (`subject_id`),
    INDEX `student_outcome_achievements_level_index` (`achievement_level`),
    INDEX `student_outcome_achievements_teacher_index` (`teacher_id`),
    INDEX `student_outcome_achievements_deleted_at_index` (`deleted_at`),
    INDEX `student_outcome_achievements_created_by_index` (`created_by`),
    INDEX `student_outcome_achievements_updated_by_index` (`updated_by`),
    CONSTRAINT `student_outcome_achievements_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_outcome_achievements_outcome_id_foreign` FOREIGN KEY (`learning_outcome_id`) REFERENCES `learning_outcomes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_outcome_achievements_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_outcome_achievements_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_outcome_achievements_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 69. STUDENT_PROMOTIONS (Kenaikan Kelas) — GAP #5
-- ============================================
CREATE TABLE `student_promotions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `from_classroom_id` BIGINT UNSIGNED NULL,
    `to_classroom_id` BIGINT UNSIGNED NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `decision` VARCHAR(20) NULL,
    `decision_date` DATE NULL,
    `meeting_notes` TEXT NULL,
    `final_score_average` DECIMAL(5, 2) NULL,
    `attendance_percentage` DECIMAL(5, 2) NULL,
    `decided_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_promotions_student_year_unique` (`student_id`, `academic_year_id`),
    INDEX `student_promotions_student_index` (`student_id`),
    INDEX `student_promotions_school_index` (`school_id`),
    INDEX `student_promotions_from_classroom_index` (`from_classroom_id`),
    INDEX `student_promotions_to_classroom_index` (`to_classroom_id`),
    INDEX `student_promotions_academic_year_index` (`academic_year_id`),
    INDEX `student_promotions_decision_index` (`decision`),
    INDEX `student_promotions_decided_by_index` (`decided_by`),
    INDEX `student_promotions_deleted_at_index` (`deleted_at`),
    INDEX `student_promotions_created_by_index` (`created_by`),
    INDEX `student_promotions_updated_by_index` (`updated_by`),
    CONSTRAINT `student_promotions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_promotions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_promotions_from_classroom_id_foreign` FOREIGN KEY (`from_classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `student_promotions_to_classroom_id_foreign` FOREIGN KEY (`to_classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `student_promotions_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `student_promotions_decided_by_foreign` FOREIGN KEY (`decided_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 70. GRADUATIONS (Kelulusan) — GAP #5
-- ============================================
CREATE TABLE `graduations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `classroom_id` BIGINT UNSIGNED NULL,
    `graduation_date` DATE NULL,
    `certificate_number` VARCHAR(50) NULL,
    `certificate_issued_at` DATE NULL,
    `skhu_number` VARCHAR(50) NULL,
    `skhu_issued_at` DATE NULL,
    `transcript_number` VARCHAR(50) NULL,
    `final_score_average` DECIMAL(5, 2) NULL,
    `predicate` VARCHAR(50) NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'LULUS',
    `notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `graduations_student_year_unique` (`student_id`, `academic_year_id`),
    UNIQUE INDEX `graduations_certificate_number_unique` (`certificate_number`),
    UNIQUE INDEX `graduations_skhu_number_unique` (`skhu_number`),
    INDEX `graduations_student_index` (`student_id`),
    INDEX `graduations_school_index` (`school_id`),
    INDEX `graduations_academic_year_index` (`academic_year_id`),
    INDEX `graduations_classroom_index` (`classroom_id`),
    INDEX `graduations_status_index` (`status`),
    INDEX `graduations_graduation_date_index` (`graduation_date`),
    INDEX `graduations_deleted_at_index` (`deleted_at`),
    INDEX `graduations_created_by_index` (`created_by`),
    INDEX `graduations_updated_by_index` (`updated_by`),
    CONSTRAINT `graduations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `graduations_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `graduations_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `graduations_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 71. ALTER STUDENT_EXTRACURRICULARS — Penilaian Ekskul (GAP #6)
-- ============================================
ALTER TABLE
    `student_extracurriculars`
ADD
    COLUMN `grade_letter` VARCHAR(2) NULL
AFTER
    `status`,
ADD
    COLUMN `predicate` VARCHAR(50) NULL
AFTER
    `grade_letter`,
ADD
    COLUMN `description` TEXT NULL
AFTER
    `predicate`,
ADD
    COLUMN `certificate_url` TEXT NULL
AFTER
    `description`,
ADD
    COLUMN `achievement_notes` TEXT NULL
AFTER
    `certificate_url`,
ADD
    COLUMN `evaluated_by` BIGINT UNSIGNED NULL
AFTER
    `achievement_notes`,
ADD
    COLUMN `evaluated_at` TIMESTAMP NULL
AFTER
    `evaluated_by`,
ADD
    INDEX `student_extracurriculars_evaluated_by_index` (`evaluated_by`),
ADD
    CONSTRAINT `student_extracurriculars_evaluated_by_foreign` FOREIGN KEY (`evaluated_by`) REFERENCES `teachers` (`id`) ON DELETE
SET
    NULL ON UPDATE CASCADE;

-- Tabel kehadiran ekskul (opsional tapi berguna)
CREATE TABLE `extracurricular_attendances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_extracurricular_id` BIGINT UNSIGNED NOT NULL,
    `meeting_date` DATE NOT NULL,
    `status` VARCHAR(20) NULL,
    `notes` TEXT NULL,
    `recorded_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `extracurricular_attendances_unique` (`student_extracurricular_id`, `meeting_date`),
    INDEX `extracurricular_attendances_student_extracurricular_index` (`student_extracurricular_id`),
    INDEX `extracurricular_attendances_date_index` (`meeting_date`),
    INDEX `extracurricular_attendances_status_index` (`status`),
    INDEX `extracurricular_attendances_recorded_by_index` (`recorded_by`),
    INDEX `extracurricular_attendances_deleted_at_index` (`deleted_at`),
    INDEX `extracurricular_attendances_created_by_index` (`created_by`),
    INDEX `extracurricular_attendances_updated_by_index` (`updated_by`),
    CONSTRAINT `extracurricular_attendances_student_extracurricular_id_foreign` FOREIGN KEY (`student_extracurricular_id`) REFERENCES `student_extracurriculars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `extracurricular_attendances_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `teachers` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 72. ANNOUNCEMENTS (Pengumuman) — GAP #7
-- ============================================
CREATE TABLE `announcements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `content` LONGTEXT NULL,
    `audience` VARCHAR(30) NULL,
    `attachment_url` TEXT NULL,
    `priority` VARCHAR(20) NOT NULL DEFAULT 'NORMAL',
    `published_at` TIMESTAMP NULL,
    `expired_at` TIMESTAMP NULL,
    `is_pinned` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `announcements_school_index` (`school_id`),
    INDEX `announcements_audience_index` (`audience`),
    INDEX `announcements_priority_index` (`priority`),
    INDEX `announcements_published_at_index` (`published_at`),
    INDEX `announcements_expired_at_index` (`expired_at`),
    INDEX `announcements_is_pinned_index` (`is_pinned`),
    INDEX `announcements_deleted_at_index` (`deleted_at`),
    INDEX `announcements_created_by_index` (`created_by`),
    INDEX `announcements_updated_by_index` (`updated_by`),
    CONSTRAINT `announcements_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 73. NOTIFICATIONS — GAP #7
-- ============================================
CREATE TABLE `notifications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `type` VARCHAR(30) NULL,
    `title` VARCHAR(200) NULL,
    `message` TEXT NULL,
    `reference_table` VARCHAR(50) NULL,
    `reference_id` BIGINT UNSIGNED NULL,
    `channel` VARCHAR(20) NOT NULL DEFAULT 'IN_APP',
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `read_at` TIMESTAMP NULL,
    `sent_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `notifications_user_index` (`user_id`),
    INDEX `notifications_type_index` (`type`),
    INDEX `notifications_channel_index` (`channel`),
    INDEX `notifications_is_read_index` (`is_read`),
    INDEX `notifications_reference_index` (`reference_table`, `reference_id`),
    INDEX `notifications_sent_at_index` (`sent_at`),
    INDEX `notifications_deleted_at_index` (`deleted_at`),
    INDEX `notifications_created_by_index` (`created_by`),
    INDEX `notifications_updated_by_index` (`updated_by`),
    CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 74. MESSAGES (Pesan Langsung) — GAP #7
-- ============================================
CREATE TABLE `messages` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `sender_id` BIGINT UNSIGNED NOT NULL,
    `receiver_id` BIGINT UNSIGNED NOT NULL,
    `subject` VARCHAR(200) NULL,
    `content` TEXT NOT NULL,
    `parent_message_id` BIGINT UNSIGNED NULL,
    `attachment_url` TEXT NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `read_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `messages_sender_index` (`sender_id`),
    INDEX `messages_receiver_index` (`receiver_id`),
    INDEX `messages_parent_index` (`parent_message_id`),
    INDEX `messages_is_read_index` (`is_read`),
    INDEX `messages_deleted_at_index` (`deleted_at`),
    INDEX `messages_created_by_index` (`created_by`),
    INDEX `messages_updated_by_index` (`updated_by`),
    CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `messages_parent_message_id_foreign` FOREIGN KEY (`parent_message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 75. PERMISSION_REQUESTS (Surat Izin / Dispensasi) — GAP #7
-- ============================================
CREATE TABLE `permission_requests` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `request_type` VARCHAR(30) NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `reason` TEXT NOT NULL,
    `attachment_url` TEXT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` TIMESTAMP NULL,
    `rejection_notes` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `permission_requests_student_index` (`student_id`),
    INDEX `permission_requests_school_index` (`school_id`),
    INDEX `permission_requests_type_index` (`request_type`),
    INDEX `permission_requests_status_index` (`status`),
    INDEX `permission_requests_date_index` (`start_date`, `end_date`),
    INDEX `permission_requests_approved_by_index` (`approved_by`),
    INDEX `permission_requests_deleted_at_index` (`deleted_at`),
    INDEX `permission_requests_created_by_index` (`created_by`),
    INDEX `permission_requests_updated_by_index` (`updated_by`),
    CONSTRAINT `permission_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `permission_requests_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `permission_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 76. STUDENT_DISCIPLINES (Pelanggaran & Poin Disiplin) — GAP #8
-- ============================================
CREATE TABLE `student_disciplines` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `category` VARCHAR(50) NULL,
    `description` TEXT NULL,
    `points` INTEGER NOT NULL DEFAULT 0,
    `action_taken` TEXT NULL,
    `incident_date` DATE NULL,
    `reported_by` BIGINT UNSIGNED NULL,
    `witness` VARCHAR(200) NULL,
    `attachment_url` TEXT NULL,
    `parent_notified` TINYINT(1) NOT NULL DEFAULT 0,
    `parent_notified_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `student_disciplines_student_index` (`student_id`),
    INDEX `student_disciplines_school_index` (`school_id`),
    INDEX `student_disciplines_category_index` (`category`),
    INDEX `student_disciplines_incident_date_index` (`incident_date`),
    INDEX `student_disciplines_reported_by_index` (`reported_by`),
    INDEX `student_disciplines_deleted_at_index` (`deleted_at`),
    INDEX `student_disciplines_created_by_index` (`created_by`),
    INDEX `student_disciplines_updated_by_index` (`updated_by`),
    CONSTRAINT `student_disciplines_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_disciplines_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_disciplines_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 77. STUDENT_HEALTH_RECORDS (Riwayat Kesehatan / UKS) — GAP #9
-- ============================================
CREATE TABLE `student_health_records` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `record_date` DATE NOT NULL,
    `complaint` TEXT NULL,
    `diagnosis` TEXT NULL,
    `treatment` TEXT NULL,
    `height_cm` DECIMAL(5, 2) NULL,
    `weight_kg` DECIMAL(5, 2) NULL,
    `blood_type` VARCHAR(3) NULL,
    `blood_pressure` VARCHAR(20) NULL,
    `allergies` TEXT NULL,
    `vaccination_notes` TEXT NULL,
    `handled_by` BIGINT UNSIGNED NULL,
    `referred_to` VARCHAR(200) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `student_health_records_student_index` (`student_id`),
    INDEX `student_health_records_school_index` (`school_id`),
    INDEX `student_health_records_record_date_index` (`record_date`),
    INDEX `student_health_records_handled_by_index` (`handled_by`),
    INDEX `student_health_records_deleted_at_index` (`deleted_at`),
    INDEX `student_health_records_created_by_index` (`created_by`),
    INDEX `student_health_records_updated_by_index` (`updated_by`),
    CONSTRAINT `student_health_records_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_health_records_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_health_records_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 78. COUNSELING_SESSIONS (Bimbingan Konseling) — GAP #9
-- ============================================
CREATE TABLE `counseling_sessions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `counselor_id` BIGINT UNSIGNED NULL,
    `session_date` TIMESTAMP NULL,
    `category` VARCHAR(30) NULL,
    `problem` TEXT NULL,
    `action` TEXT NULL,
    `follow_up` TEXT NULL,
    `follow_up_date` DATE NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'OPEN',
    `is_confidential` TINYINT(1) NOT NULL DEFAULT 1,
    `parent_involved` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `counseling_sessions_student_index` (`student_id`),
    INDEX `counseling_sessions_school_index` (`school_id`),
    INDEX `counseling_sessions_counselor_index` (`counselor_id`),
    INDEX `counseling_sessions_category_index` (`category`),
    INDEX `counseling_sessions_status_index` (`status`),
    INDEX `counseling_sessions_session_date_index` (`session_date`),
    INDEX `counseling_sessions_deleted_at_index` (`deleted_at`),
    INDEX `counseling_sessions_created_by_index` (`created_by`),
    INDEX `counseling_sessions_updated_by_index` (`updated_by`),
    CONSTRAINT `counseling_sessions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `counseling_sessions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `counseling_sessions_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 79. LIBRARY_BOOKS (Perpustakaan) — GAP #10
-- ============================================
CREATE TABLE `library_books` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `isbn` VARCHAR(20) NULL,
    `code` VARCHAR(50) NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `author` VARCHAR(150) NULL,
    `publisher` VARCHAR(150) NULL,
    `year_published` INTEGER NULL,
    `category` VARCHAR(50) NULL,
    `description` TEXT NULL,
    `cover_url` TEXT NULL,
    `total_copies` INTEGER NOT NULL DEFAULT 1,
    `available_copies` INTEGER NOT NULL DEFAULT 1,
    `shelf_location` VARCHAR(50) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `library_books_school_code_unique` (`school_id`, `code`),
    INDEX `library_books_school_index` (`school_id`),
    INDEX `library_books_isbn_index` (`isbn`),
    INDEX `library_books_title_index` (`title`),
    INDEX `library_books_category_index` (`category`),
    INDEX `library_books_author_index` (`author`),
    INDEX `library_books_deleted_at_index` (`deleted_at`),
    INDEX `library_books_created_by_index` (`created_by`),
    INDEX `library_books_updated_by_index` (`updated_by`),
    CONSTRAINT `library_books_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 80. LIBRARY_LOANS (Peminjaman Buku) — GAP #10
-- ============================================
CREATE TABLE `library_loans` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `book_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `loan_date` DATE NOT NULL,
    `due_date` DATE NOT NULL,
    `return_date` DATE NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'BORROWED',
    `fine_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0,
    `fine_paid` TINYINT(1) NOT NULL DEFAULT 0,
    `notes` TEXT NULL,
    `handled_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `library_loans_book_index` (`book_id`),
    INDEX `library_loans_student_index` (`student_id`),
    INDEX `library_loans_teacher_index` (`teacher_id`),
    INDEX `library_loans_school_index` (`school_id`),
    INDEX `library_loans_status_index` (`status`),
    INDEX `library_loans_loan_date_index` (`loan_date`),
    INDEX `library_loans_due_date_index` (`due_date`),
    INDEX `library_loans_handled_by_index` (`handled_by`),
    INDEX `library_loans_deleted_at_index` (`deleted_at`),
    INDEX `library_loans_created_by_index` (`created_by`),
    INDEX `library_loans_updated_by_index` (`updated_by`),
    CONSTRAINT `library_loans_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `library_books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `library_loans_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `library_loans_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE,
        CONSTRAINT `library_loans_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `library_loans_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 81. ASSETS (Inventaris Aset) — GAP #10
-- ============================================
CREATE TABLE `assets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `category` VARCHAR(50) NULL,
    `brand` VARCHAR(100) NULL,
    `model` VARCHAR(100) NULL,
    `serial_number` VARCHAR(100) NULL,
    `purchase_date` DATE NULL,
    `purchase_price` DECIMAL(15, 2) NULL,
    `supplier` VARCHAR(150) NULL,
    `location` VARCHAR(150) NULL,
    `condition` VARCHAR(20) NOT NULL DEFAULT 'GOOD',
    `assigned_to` BIGINT UNSIGNED NULL,
    `warranty_end` DATE NULL,
    `depreciation_value` DECIMAL(15, 2) NULL,
    `notes` TEXT NULL,
    `photo_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `assets_school_code_unique` (`school_id`, `code`),
    INDEX `assets_school_index` (`school_id`),
    INDEX `assets_category_index` (`category`),
    INDEX `assets_condition_index` (`condition`),
    INDEX `assets_location_index` (`location`),
    INDEX `assets_assigned_to_index` (`assigned_to`),
    INDEX `assets_purchase_date_index` (`purchase_date`),
    INDEX `assets_deleted_at_index` (`deleted_at`),
    INDEX `assets_created_by_index` (`created_by`),
    INDEX `assets_updated_by_index` (`updated_by`),
    CONSTRAINT `assets_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `assets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 82. ACADEMIC_CALENDAR_EVENTS (Kalender Akademik) — GAP #11
-- ============================================
CREATE TABLE `academic_calendar_events` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `academic_year_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `event_type` VARCHAR(30) NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NULL,
    `start_time` TIME NULL,
    `end_time` TIME NULL,
    `location` VARCHAR(200) NULL,
    `is_holiday` TINYINT(1) NOT NULL DEFAULT 0,
    `is_all_day` TINYINT(1) NOT NULL DEFAULT 0,
    `color` VARCHAR(20) NULL,
    `attachment_url` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    INDEX `academic_calendar_events_school_index` (`school_id`),
    INDEX `academic_calendar_events_academic_year_index` (`academic_year_id`),
    INDEX `academic_calendar_events_type_index` (`event_type`),
    INDEX `academic_calendar_events_dates_index` (`start_date`, `end_date`),
    INDEX `academic_calendar_events_is_holiday_index` (`is_holiday`),
    INDEX `academic_calendar_events_deleted_at_index` (`deleted_at`),
    INDEX `academic_calendar_events_created_by_index` (`created_by`),
    INDEX `academic_calendar_events_updated_by_index` (`updated_by`),
    CONSTRAINT `academic_calendar_events_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `academic_calendar_events_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 83. ROLES (Peran) — GAP #12
-- ============================================
CREATE TABLE `roles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NULL,
    `code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `level` INTEGER NOT NULL DEFAULT 0,
    `is_system` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `roles_school_code_unique` (`school_id`, `code`),
    INDEX `roles_school_index` (`school_id`),
    INDEX `roles_code_index` (`code`),
    INDEX `roles_is_system_index` (`is_system`),
    INDEX `roles_deleted_at_index` (`deleted_at`),
    INDEX `roles_created_by_index` (`created_by`),
    INDEX `roles_updated_by_index` (`updated_by`),
    CONSTRAINT `roles_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 84. PERMISSIONS (Hak Akses) — GAP #12
-- ============================================
CREATE TABLE `permissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(100) NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `module` VARCHAR(50) NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `permissions_code_unique` (`code`),
    INDEX `permissions_module_index` (`module`),
    INDEX `permissions_deleted_at_index` (`deleted_at`),
    INDEX `permissions_created_by_index` (`created_by`),
    INDEX `permissions_updated_by_index` (`updated_by`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 85. ROLE_PERMISSIONS (Pivot) — GAP #12
-- ============================================
CREATE TABLE `role_permissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `role_id` BIGINT UNSIGNED NOT NULL,
    `permission_id` BIGINT UNSIGNED NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `role_permissions_unique` (`role_id`, `permission_id`),
    INDEX `role_permissions_role_index` (`role_id`),
    INDEX `role_permissions_permission_index` (`permission_id`),
    INDEX `role_permissions_deleted_at_index` (`deleted_at`),
    CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- 86. USER_ROLES (Pivot) — GAP #12
-- ============================================
CREATE TABLE `user_roles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,
    `school_id` BIGINT UNSIGNED NULL,
    `assigned_at` TIMESTAMP NULL,
    `assigned_by` BIGINT UNSIGNED NULL,
    `expired_at` TIMESTAMP NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `user_roles_user_role_school_unique` (`user_id`, `role_id`, `school_id`),
    INDEX `user_roles_user_index` (`user_id`),
    INDEX `user_roles_role_index` (`role_id`),
    INDEX `user_roles_school_index` (`school_id`),
    INDEX `user_roles_assigned_by_index` (`assigned_by`),
    INDEX `user_roles_deleted_at_index` (`deleted_at`),
    INDEX `user_roles_created_by_index` (`created_by`),
    INDEX `user_roles_updated_by_index` (`updated_by`),
    CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `user_roles_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `user_roles_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE
    SET
        NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- STUDENT_ATTENDANCES
-- ============================================
CREATE TABLE `student_attendances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `schedule_id` BIGINT UNSIGNED NULL,
    `classroom_id` BIGINT UNSIGNED NULL,
    `date` DATE NOT NULL,
    `status` VARCHAR(20) NULL,
    `check_in_time` TIME NULL,
    `check_out_time` TIME NULL,
    `late_minutes` INTEGER NOT NULL DEFAULT 0,
    `reason` TEXT NULL,
    `verified_by` BIGINT UNSIGNED NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `student_attendances_student_schedule_date_unique` (`student_id`, `schedule_id`, `date`),
    INDEX `student_attendances_student_index` (`student_id`),
    INDEX `student_attendances_schedule_index` (`schedule_id`),
    INDEX `student_attendances_classroom_index` (`classroom_id`),
    INDEX `student_attendances_date_index` (`date`),
    INDEX `student_attendances_status_index` (`status`),
    INDEX `student_attendances_deleted_at_index` (`deleted_at`),
    INDEX `student_attendances_created_by_index` (`created_by`),
    INDEX `student_attendances_updated_by_index` (`updated_by`),
    CONSTRAINT `student_attendances_student_id_foreign`
        FOREIGN KEY (`student_id`) REFERENCES `students` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_attendances_schedule_id_foreign`
        FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `student_attendances_classroom_id_foreign`
        FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `student_attendances_verified_by_foreign`
        FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ============================================
-- EXTRACURRICULARS
-- ============================================
CREATE TABLE `extracurriculars` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `description` TEXT NULL,
    `teacher_id` BIGINT UNSIGNED NULL,
    `day_of_week` VARCHAR(10) NULL,
    `start_time` TIME NULL,
    `end_time` TIME NULL,
    `max_students` INTEGER NOT NULL DEFAULT 30,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `extracurriculars_school_code_unique` (`school_id`, `code`),
    INDEX `extracurriculars_school_index` (`school_id`),
    INDEX `extracurriculars_teacher_index` (`teacher_id`),
    INDEX `extracurriculars_day_index` (`day_of_week`),
    INDEX `extracurriculars_deleted_at_index` (`deleted_at`),
    INDEX `extracurriculars_created_by_index` (`created_by`),
    INDEX `extracurriculars_updated_by_index` (`updated_by`),
    CONSTRAINT `extracurriculars_school_id_foreign`
        FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `extracurriculars_teacher_id_foreign`
        FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;


SET FOREIGN_KEY_CHECKS = 1;