-- ==============================================================================
-- ENTERPRISE MODULE 07: ENHANCED LIBRARY MANAGEMENT SUBSYSTEM
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 1. Penerbit & Pengarang Buku (Book Publishers & Authors)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `book_publishers`;
CREATE TABLE `book_publishers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pub_org_index`(`organization_id` ASC) USING BTREE,
  CONSTRAINT `pub_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `book_authors`;
CREATE TABLE `book_authors` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `biography` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `author_org_index`(`organization_id` ASC) USING BTREE,
  CONSTRAINT `author_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

DROP TABLE IF EXISTS `book_categories`;
CREATE TABLE `book_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ddc_classification` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `book_cat_unique`(`organization_id` ASC, `code` ASC) USING BTREE,
  CONSTRAINT `book_cat_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 2. Eksemplar Fisik Buku (Book Copies)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `book_copies`;
CREATE TABLE `book_copies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `library_book_id` bigint UNSIGNED NOT NULL,
  `barcode` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfid_tag` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `call_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shelf_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `copy_number` smallint UNSIGNED NOT NULL DEFAULT 1,
  `condition` enum('good','fair','damaged','lost') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'good',
  `status` enum('available','borrowed','reserved','in_repair','written_off') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `acquisition_date` date NULL DEFAULT NULL,
  `price` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `book_copies_barcode_unique`(`barcode` ASC) USING BTREE,
  INDEX `book_copies_rfid_index`(`rfid_tag` ASC) USING BTREE,
  INDEX `book_copies_book_index`(`library_book_id` ASC) USING BTREE,
  INDEX `book_copies_status_index`(`status` ASC) USING BTREE,
  CONSTRAINT `book_copies_book_fk` FOREIGN KEY (`library_book_id`) REFERENCES `library_books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 3. Keanggotaan Perpustakaan (Library Memberships)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `library_memberships`;
CREATE TABLE `library_memberships` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NOT NULL,
  `member_type` enum('student','employee','external') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `member_id` bigint UNSIGNED NOT NULL,
  `member_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_borrow_limit` tinyint UNSIGNED NOT NULL DEFAULT 3,
  `max_borrow_days` tinyint UNSIGNED NOT NULL DEFAULT 7,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `expiry_date` date NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `lib_mem_code_unique`(`member_code` ASC) USING BTREE,
  INDEX `lib_mem_type_id_index`(`member_type` ASC, `member_id` ASC) USING BTREE,
  INDEX `lib_mem_school_index`(`school_id` ASC) USING BTREE,
  CONSTRAINT `lib_mem_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `lib_mem_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 4. Denda Perpustakaan (Library Fines)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `library_fines`;
CREATE TABLE `library_fines` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `library_loan_id` bigint UNSIGNED NOT NULL,
  `library_membership_id` bigint UNSIGNED NOT NULL,
  `days_overdue` smallint UNSIGNED NOT NULL DEFAULT 0,
  `fine_amount` decimal(15, 2) NOT NULL,
  `paid_amount` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('unpaid','partial','paid','waived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `paid_at` timestamp NULL DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `lib_fines_loan_index`(`library_loan_id` ASC) USING BTREE,
  INDEX `lib_fines_mem_index`(`library_membership_id` ASC) USING BTREE,
  INDEX `lib_fines_journal_index`(`journal_entry_id` ASC) USING BTREE,
  CONSTRAINT `lib_fines_loan_fk` FOREIGN KEY (`library_loan_id`) REFERENCES `library_loans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lib_fines_mem_fk` FOREIGN KEY (`library_membership_id`) REFERENCES `library_memberships` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `lib_fines_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ------------------------------------------------------------------------------
-- 5. Reservasi / Booking Buku (Book Reservations)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `book_reservations`;
CREATE TABLE `book_reservations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `library_book_id` bigint UNSIGNED NOT NULL,
  `library_membership_id` bigint UNSIGNED NOT NULL,
  `reservation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiry_date` date NOT NULL,
  `status` enum('active','fulfilled','cancelled','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `book_res_book_index`(`library_book_id` ASC) USING BTREE,
  INDEX `book_res_mem_index`(`library_membership_id` ASC) USING BTREE,
  CONSTRAINT `book_res_book_fk` FOREIGN KEY (`library_book_id`) REFERENCES `library_books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `book_res_mem_fk` FOREIGN KEY (`library_membership_id`) REFERENCES `library_memberships` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
