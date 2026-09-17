-- ==============================================================================
-- ENTERPRISE MODULE 03: DOCUMENT NUMBER AUTO-SEQUENCING ENGINE
-- Compatible with MySQL 8.0+ / MariaDB 10.3+
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ==============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 1. Generator Nomor Urut Dokumen (Document Number Sequences)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `document_number_sequences`;
CREATE TABLE `document_number_sequences` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_id` bigint UNSIGNED NOT NULL,
  `school_id` bigint UNSIGNED NULL DEFAULT NULL,
  `document_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_key` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ALL',
  `prefix` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `pattern` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '{PREFIX}/{YEAR}/{MONTH}/{SEQ:5}',
  `last_number` bigint UNSIGNED NOT NULL DEFAULT 0,
  `padding` tinyint UNSIGNED NOT NULL DEFAULT 5,
  `reset_frequency` enum('none','yearly','monthly','daily') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yearly',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `doc_number_sequences_unique`(`organization_id` ASC, `school_id` ASC, `document_type` ASC, `period_key` ASC) USING BTREE,
  INDEX `doc_seq_type_index`(`document_type` ASC) USING BTREE,
  INDEX `doc_seq_school_index`(`school_id` ASC) USING BTREE,
  CONSTRAINT `doc_seq_org_fk` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `doc_seq_school_fk` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
