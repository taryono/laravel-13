SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `provider_message_events`;
DROP TABLE IF EXISTS `sms_logs`;
DROP TABLE IF EXISTS `senders`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `senders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `name` varchar(50) NOT NULL COMMENT 'sender ID, mis. "SEKOLAHA"',
  `phone_number` varchar(20) NULL DEFAULT NULL,
  `provider` varchar(50) NULL DEFAULT NULL COMMENT 'zenziva, twilio, dll.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `senders_organization_id_name_unique` (`organization_id`, `name`),
  CONSTRAINT `senders_organization_id_foreign`
    FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `sms_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint unsigned NOT NULL,
  `notification_template_id` bigint unsigned NULL DEFAULT NULL,
  `sender_id` bigint unsigned NULL DEFAULT NULL,

  `recipient_type` varchar(50) NULL DEFAULT NULL COMMENT 'user | student | employee',
  `recipient_id` bigint unsigned NULL DEFAULT NULL,
  `recipient_name` varchar(150) NULL DEFAULT NULL,
  `recipient_phone` varchar(20) NOT NULL,

  `message` text NOT NULL,

  `provider` varchar(50) NULL DEFAULT NULL COMMENT 'zenziva, twilio, dll.',
  `external_id` varchar(100) NULL DEFAULT NULL COMMENT 'ID pesan dari gateway',
  `status` varchar(20) NOT NULL DEFAULT 'queued' COMMENT 'queued, sent, delivered, failed',
  `provider_response` json NULL,
  `error_message` text NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,

  `currency_id` bigint unsigned NULL DEFAULT NULL,
  `cost` decimal(12,4) NOT NULL DEFAULT 0.0000,

  `reference_type` varchar(50) NULL DEFAULT NULL COMMENT 'invoice | announcement | ...',
  `reference_id` bigint unsigned NULL DEFAULT NULL,

  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_sms_logs_org_status` (`organization_id`, `status`),
  INDEX `idx_sms_logs_recipient` (`recipient_type`, `recipient_id`),
  INDEX `idx_sms_logs_reference` (`reference_type`, `reference_id`),
  INDEX `idx_sms_logs_created` (`created_at`),
  UNIQUE KEY `uq_sms_logs_provider_external` (`provider`, `external_id`),
  CONSTRAINT `sms_logs_organization_id_foreign`
    FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`),
  CONSTRAINT `sms_logs_sender_id_foreign`
    FOREIGN KEY (`sender_id`) REFERENCES `senders` (`id`)
  -- FK berikut dipasang SETELAH tabelnya ada (lihat ALTER di bawah)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `provider_message_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sms_log_id` bigint unsigned NOT NULL,
  `status` varchar(20) NOT NULL COMMENT 'sent, delivered, failed',
  `payload` json NULL,
  `received_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_pme_log` (`sms_log_id`),
  CONSTRAINT `provider_message_events_sms_log_id_foreign`
    FOREIGN KEY (`sms_log_id`) REFERENCES `sms_logs` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;