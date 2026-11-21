/*
 * Table Group: library
 * Total Tables: 13
 * Generated At: 2026-11-21 06:06:00
 */

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `book_authors`
-- ----------------------------
DROP TABLE IF EXISTS `book_authors`;
CREATE TABLE `book_authors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `biography` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `author_org_index` (`organization_id`) USING BTREE,
  CONSTRAINT `author_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `book_categories`
-- ----------------------------
DROP TABLE IF EXISTS `book_categories`;
CREATE TABLE `book_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ddc_classification` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `book_cat_unique` (`organization_id`,`code`) USING BTREE,
  CONSTRAINT `book_cat_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `book_copies`
-- ----------------------------
DROP TABLE IF EXISTS `book_copies`;
CREATE TABLE `book_copies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `library_book_id` bigint unsigned NOT NULL,
  `barcode` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfid_tag` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shelf_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `copy_number` smallint unsigned NOT NULL DEFAULT '1',
  `condition` enum('good','fair','damaged','lost') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'good',
  `status` enum('available','borrowed','reserved','in_repair','written_off') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `acquisition_date` date DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `book_copies_barcode_unique` (`barcode`) USING BTREE,
  KEY `book_copies_rfid_index` (`rfid_tag`) USING BTREE,
  KEY `book_copies_book_index` (`library_book_id`) USING BTREE,
  KEY `book_copies_status_index` (`status`) USING BTREE,
  CONSTRAINT `book_copies_book_fk` FOREIGN KEY (`library_book_id`) REFERENCES `library_books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `book_publishers`
-- ----------------------------
DROP TABLE IF EXISTS `book_publishers`;
CREATE TABLE `book_publishers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `pub_org_index` (`organization_id`) USING BTREE,
  CONSTRAINT `pub_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `book_reservations`
-- ----------------------------
DROP TABLE IF EXISTS `book_reservations`;
CREATE TABLE `book_reservations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `library_book_id` bigint unsigned NOT NULL,
  `library_membership_id` bigint unsigned NOT NULL,
  `reservation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiry_date` date NOT NULL,
  `status` enum('active','fulfilled','cancelled','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `book_res_book_index` (`library_book_id`) USING BTREE,
  KEY `book_res_mem_index` (`library_membership_id`) USING BTREE,
  CONSTRAINT `book_res_book_fk` FOREIGN KEY (`library_book_id`) REFERENCES `library_books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `book_res_mem_fk` FOREIGN KEY (`library_membership_id`) REFERENCES `library_memberships` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `digital_library_access_logs`
-- ----------------------------
DROP TABLE IF EXISTS `digital_library_access_logs`;
CREATE TABLE `digital_library_access_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `digital_library_item_id` bigint unsigned NOT NULL COMMENT 'FK ke digital_library_items',
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke users',
  `student_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke students (jika siswa)',
  `employee_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke employees (jika karyawan)',
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `access_type` enum('view','read','download','stream','print','share','bookmark','rate','review') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tipe akses',
  `access_duration_seconds` int unsigned DEFAULT NULL COMMENT 'Durasi akses (detik)',
  `pages_read` int unsigned DEFAULT NULL COMMENT 'Halaman yang dibaca',
  `last_page_read` int unsigned DEFAULT NULL COMMENT 'Halaman terakhir dibaca',
  `progress_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Persentase progress baca',
  `device_type` enum('desktop','mobile','tablet','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tipe device',
  `device_identifier` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Halaman asal',
  `session_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Session ID',
  `rating` decimal(3,2) DEFAULT NULL COMMENT 'Rating (jika access_type = rate)',
  `review` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Review (jika access_type = review)',
  `is_offline` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 jika akses offline',
  `accessed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu akses',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_digital_log_item` (`digital_library_item_id`) USING BTREE,
  KEY `idx_digital_log_user` (`user_id`) USING BTREE,
  KEY `idx_digital_log_student` (`student_id`) USING BTREE,
  KEY `idx_digital_log_employee` (`employee_id`) USING BTREE,
  KEY `idx_digital_log_org` (`organization_id`) USING BTREE,
  KEY `idx_digital_log_type` (`access_type`) USING BTREE,
  KEY `idx_digital_log_accessed` (`accessed_at`) USING BTREE,
  CONSTRAINT `digital_library_access_logs_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `digital_library_access_logs_ibfk_2` FOREIGN KEY (`digital_library_item_id`) REFERENCES `digital_library_items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `digital_library_access_logs_ibfk_3` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `digital_library_access_logs_ibfk_4` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `digital_library_access_logs_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `digital_library_items`
-- ----------------------------
DROP TABLE IF EXISTS `digital_library_items`;
CREATE TABLE `digital_library_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `library_book_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke library_books',
  `book_category_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke book_categories',
  `book_publisher_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke book_publishers',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Judul',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug untuk URL',
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_type` enum('ebook','ejournal','article','video','audio','course','thesis','report','magazine','newspaper','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ebook',
  `format` enum('pdf','epub','mobi','docx','pptx','mp4','mp3','html','zip','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pdf',
  `isbn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Digital Object Identifier',
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Penulis',
  `editor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `translator` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'id',
  `edition` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `volume` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publication_year` year DEFAULT NULL,
  `publication_date` date DEFAULT NULL,
  `page_count` int unsigned DEFAULT NULL,
  `duration_seconds` int unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `abstract` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `keywords` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size_bytes` bigint unsigned DEFAULT NULL,
  `cover_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `external_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_type` enum('open','restricted','subscription','purchase','internal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `access_level` enum('public','member','student','teacher','staff','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `license_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_expires_at` date DEFAULT NULL,
  `max_concurrent_access` int unsigned DEFAULT NULL,
  `download_allowed` tinyint(1) NOT NULL DEFAULT '0',
  `print_allowed` tinyint(1) NOT NULL DEFAULT '0',
  `copy_allowed` tinyint(1) NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `published_at` timestamp NULL DEFAULT NULL,
  `total_views` int unsigned NOT NULL DEFAULT '0',
  `total_downloads` int unsigned NOT NULL DEFAULT '0',
  `rating_average` decimal(3,2) DEFAULT NULL,
  `rating_count` int unsigned NOT NULL DEFAULT '0',
  `meta_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_digital_library_slug` (`slug`) USING BTREE,
  KEY `idx_digital_library_org` (`organization_id`) USING BTREE,
  KEY `idx_digital_library_book` (`library_book_id`) USING BTREE,
  KEY `idx_digital_library_category` (`book_category_id`) USING BTREE,
  KEY `idx_digital_library_publisher` (`book_publisher_id`) USING BTREE,
  KEY `idx_digital_library_type` (`item_type`) USING BTREE,
  KEY `idx_digital_library_format` (`format`) USING BTREE,
  KEY `idx_digital_library_access` (`access_type`) USING BTREE,
  KEY `idx_digital_library_level` (`access_level`) USING BTREE,
  KEY `idx_digital_library_active` (`is_active`) USING BTREE,
  KEY `idx_digital_library_featured` (`is_featured`) USING BTREE,
  KEY `idx_digital_library_year` (`publication_year`) USING BTREE,
  KEY `fk_digital_library_created_by` (`created_by`) USING BTREE,
  KEY `fk_digital_library_updated_by` (`updated_by`) USING BTREE,
  FULLTEXT KEY `ft_digital_library_search` (`title`,`author`,`description`,`abstract`,`keywords`),
  CONSTRAINT `digital_library_items_ibfk_1` FOREIGN KEY (`library_book_id`) REFERENCES `library_books` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `digital_library_items_ibfk_2` FOREIGN KEY (`book_category_id`) REFERENCES `book_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `digital_library_items_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `digital_library_items_ibfk_4` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `digital_library_items_ibfk_5` FOREIGN KEY (`book_publisher_id`) REFERENCES `book_publishers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `digital_library_items_ibfk_6` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `library_books`
-- ----------------------------
DROP TABLE IF EXISTS `library_books`;
CREATE TABLE `library_books` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_id` bigint unsigned NOT NULL,
  `isbn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publisher` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year_published` int DEFAULT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cover_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_copies` int NOT NULL DEFAULT '1',
  `available_copies` int NOT NULL DEFAULT '1',
  `shelf_location` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `library_books_school_code_unique` (`school_id`,`code`) USING BTREE,
  KEY `library_books_school_index` (`school_id`) USING BTREE,
  KEY `library_books_isbn_index` (`isbn`) USING BTREE,
  KEY `library_books_title_index` (`title`) USING BTREE,
  KEY `library_books_category_index` (`category`) USING BTREE,
  KEY `library_books_author_index` (`author`) USING BTREE,
  KEY `library_books_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `library_books_created_by_index` (`created_by`) USING BTREE,
  KEY `library_books_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `library_books_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `library_fines`
-- ----------------------------
DROP TABLE IF EXISTS `library_fines`;
CREATE TABLE `library_fines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `library_loan_id` bigint unsigned NOT NULL,
  `library_membership_id` bigint unsigned NOT NULL,
  `days_overdue` smallint unsigned NOT NULL DEFAULT '0',
  `fine_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('unpaid','partial','paid','waived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `paid_at` timestamp NULL DEFAULT NULL,
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `lib_fines_loan_index` (`library_loan_id`) USING BTREE,
  KEY `lib_fines_mem_index` (`library_membership_id`) USING BTREE,
  KEY `lib_fines_journal_index` (`journal_entry_id`) USING BTREE,
  CONSTRAINT `lib_fines_journal_fk` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `lib_fines_loan_fk` FOREIGN KEY (`library_loan_id`) REFERENCES `library_loans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lib_fines_mem_fk` FOREIGN KEY (`library_membership_id`) REFERENCES `library_memberships` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `library_loans`
-- ----------------------------
DROP TABLE IF EXISTS `library_loans`;
CREATE TABLE `library_loans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `book_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned DEFAULT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `school_id` bigint unsigned NOT NULL,
  `loan_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BORROWED',
  `fine_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `fine_paid` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `handled_by` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `library_loans_book_index` (`book_id`) USING BTREE,
  KEY `library_loans_student_index` (`student_id`) USING BTREE,
  KEY `library_loans_teacher_index` (`teacher_id`) USING BTREE,
  KEY `library_loans_school_index` (`school_id`) USING BTREE,
  KEY `library_loans_status_index` (`status`) USING BTREE,
  KEY `library_loans_loan_date_index` (`loan_date`) USING BTREE,
  KEY `library_loans_due_date_index` (`due_date`) USING BTREE,
  KEY `library_loans_handled_by_index` (`handled_by`) USING BTREE,
  KEY `library_loans_deleted_at_index` (`deleted_at`) USING BTREE,
  KEY `library_loans_created_by_index` (`created_by`) USING BTREE,
  KEY `library_loans_updated_by_index` (`updated_by`) USING BTREE,
  CONSTRAINT `library_loans_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `library_books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `library_loans_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `library_loans_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `library_loans_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `library_loans_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `library_memberships`
-- ----------------------------
DROP TABLE IF EXISTS `library_memberships`;
CREATE TABLE `library_memberships` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `school_id` bigint unsigned NOT NULL,
  `member_type` enum('student','employee','external') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `member_id` bigint unsigned NOT NULL,
  `member_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_borrow_limit` tinyint unsigned NOT NULL DEFAULT '3',
  `max_borrow_days` tinyint unsigned NOT NULL DEFAULT '7',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `lib_mem_code_unique` (`member_code`) USING BTREE,
  KEY `lib_mem_type_id_index` (`member_type`,`member_id`) USING BTREE,
  KEY `lib_mem_school_index` (`school_id`) USING BTREE,
  KEY `lib_mem_org_fk` (`organization_id`) USING BTREE,
  CONSTRAINT `lib_mem_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `lib_mem_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `library_stock_opname`
-- ----------------------------
DROP TABLE IF EXISTS `library_stock_opname`;
CREATE TABLE `library_stock_opname` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `library_book_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke library_books',
  `book_copy_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke book_copies',
  `opname_batch` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Batch opname, misal: OPNAME-2025-01',
  `opname_date` date NOT NULL COMMENT 'Tanggal opname',
  `expected_quantity` int unsigned NOT NULL DEFAULT '0' COMMENT 'Jumlah seharusnya',
  `actual_quantity` int unsigned NOT NULL DEFAULT '0' COMMENT 'Jumlah aktual',
  `difference` int NOT NULL DEFAULT '0' COMMENT 'Selisih (actual - expected)',
  `condition` enum('good','fair','poor','damaged','lost','written_off') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'good' COMMENT 'Kondisi buku',
  `status` enum('draft','in_progress','completed','verified','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft' COMMENT 'Status opname',
  `discrepancy_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Alasan selisih',
  `action_taken` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Tindakan yang diambil',
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lokasi penyimpanan',
  `shelf_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kode rak',
  `checked_by` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang memeriksa',
  `verified_by` bigint unsigned DEFAULT NULL COMMENT 'FK ke users yang verifikasi',
  `verified_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bukti foto/dokumen',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uk_library_opname_batch_copy` (`opname_batch`,`book_copy_id`) USING BTREE,
  KEY `idx_library_opname_org` (`organization_id`) USING BTREE,
  KEY `idx_library_opname_book` (`library_book_id`) USING BTREE,
  KEY `idx_library_opname_copy` (`book_copy_id`) USING BTREE,
  KEY `idx_library_opname_batch` (`opname_batch`) USING BTREE,
  KEY `idx_library_opname_date` (`opname_date`) USING BTREE,
  KEY `idx_library_opname_status` (`status`) USING BTREE,
  KEY `idx_library_opname_condition` (`condition`) USING BTREE,
  KEY `fk_library_opname_checked_by` (`checked_by`) USING BTREE,
  KEY `fk_library_opname_verified_by` (`verified_by`) USING BTREE,
  KEY `fk_library_opname_created_by` (`created_by`) USING BTREE,
  KEY `fk_library_opname_updated_by` (`updated_by`) USING BTREE,
  CONSTRAINT `library_stock_opname_ibfk_1` FOREIGN KEY (`library_book_id`) REFERENCES `library_books` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `library_stock_opname_ibfk_2` FOREIGN KEY (`checked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `library_stock_opname_ibfk_3` FOREIGN KEY (`book_copy_id`) REFERENCES `book_copies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `library_stock_opname_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `library_stock_opname_ibfk_5` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `library_stock_opname_ibfk_6` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `library_stock_opname_ibfk_7` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `library_visits`
-- ----------------------------
DROP TABLE IF EXISTS `library_visits`;
CREATE TABLE `library_visits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `library_membership_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke library_memberships',
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke users',
  `student_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke students',
  `employee_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke employees',
  `organization_id` bigint unsigned DEFAULT NULL COMMENT 'FK ke organizations',
  `visit_type` enum('student','teacher','staff','guest','alumni','parent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student' COMMENT 'Tipe pengunjung',
  `purpose` enum('read','borrow','return','study','research','meeting','computer_use','print','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'read' COMMENT 'Tujuan kunjungan',
  `check_in_time` datetime NOT NULL COMMENT 'Waktu masuk',
  `check_out_time` datetime DEFAULT NULL COMMENT 'Waktu keluar',
  `duration_minutes` int unsigned DEFAULT NULL COMMENT 'Durasi kunjungan (menit)',
  `check_in_method` enum('manual','rfid','qr_code','fingerprint','face_recognition','mobile_app') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual' COMMENT 'Metode check-in',
  `check_out_method` enum('manual','rfid','qr_code','fingerprint','face_recognition','mobile_app') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Metode check-out',
  `visitor_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama pengunjung (jika guest)',
  `visitor_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitor_institution` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Institusi asal (jika guest)',
  `visitor_id_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor identitas (jika guest)',
  `companion_count` int unsigned NOT NULL DEFAULT '0' COMMENT 'Jumlah pendamping',
  `books_read` int unsigned NOT NULL DEFAULT '0' COMMENT 'Jumlah buku dibaca',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_library_visit_membership` (`library_membership_id`) USING BTREE,
  KEY `idx_library_visit_user` (`user_id`) USING BTREE,
  KEY `idx_library_visit_student` (`student_id`) USING BTREE,
  KEY `idx_library_visit_employee` (`employee_id`) USING BTREE,
  KEY `idx_library_visit_org` (`organization_id`) USING BTREE,
  KEY `idx_library_visit_type` (`visit_type`) USING BTREE,
  KEY `idx_library_visit_purpose` (`purpose`) USING BTREE,
  KEY `idx_library_visit_checkin` (`check_in_time`) USING BTREE,
  KEY `fk_library_visit_created_by` (`created_by`) USING BTREE,
  CONSTRAINT `library_visits_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `library_visits_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `library_visits_ibfk_3` FOREIGN KEY (`library_membership_id`) REFERENCES `library_memberships` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `library_visits_ibfk_4` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `library_visits_ibfk_5` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `library_visits_ibfk_6` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
