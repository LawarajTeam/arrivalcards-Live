-- ============================================
-- Arrival Cards Complete Database Schema
-- Date: February 10, 2026
-- Includes: AdSense integration and view counter
-- ============================================

DROP DATABASE IF EXISTS arrivalcards;
CREATE DATABASE arrivalcards CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE arrivalcards;

-- ============================================
-- Table: admin_users
-- ============================================
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: adsense_settings
-- ============================================
CREATE TABLE `adsense_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: airports
-- ============================================
CREATE TABLE `airports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `airport_name` varchar(200) NOT NULL,
  `airport_code` varchar(10) NOT NULL,
  `city` varchar(100) NOT NULL,
  `is_main` tinyint(1) DEFAULT 0,
  `website_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `airports_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: audit_log
-- ============================================
CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_admin` (`admin_user_id`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`admin_user_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: contact_submissions
-- ============================================
CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_submitted` (`submitted_at`),
  KEY `idx_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: countries
-- ============================================
CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(3) NOT NULL COMMENT 'ISO 3166-1 alpha-3 code',
  `flag_emoji` varchar(10) NOT NULL,
  `region` varchar(50) NOT NULL,
  `official_url` varchar(500) NOT NULL,
  `visa_type` enum('visa_free','visa_on_arrival','visa_required','evisa') NOT NULL,
  `last_updated` date NOT NULL,
  `helpful_yes` int(11) DEFAULT 0 COMMENT 'Number of helpful votes',
  `helpful_no` int(11) DEFAULT 0 COMMENT 'Number of not helpful votes',
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `is_popular` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `capital` varchar(100) DEFAULT NULL,
  `population` varchar(50) DEFAULT NULL,
  `currency_name` varchar(100) DEFAULT NULL,
  `currency_code` varchar(10) DEFAULT NULL,
  `currency_symbol` varchar(10) DEFAULT NULL,
  `plug_type` varchar(50) DEFAULT NULL,
  `leader_name` varchar(200) DEFAULT NULL,
  `leader_title` varchar(100) DEFAULT NULL,
  `leader_term` varchar(100) DEFAULT NULL,
  `time_zone` varchar(100) DEFAULT NULL,
  `calling_code` varchar(20) DEFAULT NULL,
  `languages` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `country_code` (`country_code`),
  KEY `idx_region` (`region`),
  KEY `idx_visa_type` (`visa_type`),
  KEY `idx_active` (`is_active`),
  KEY `idx_country_active` (`is_active`,`display_order`),
  KEY `idx_country_region_visa` (`region`,`visa_type`)
) ENGINE=InnoDB AUTO_INCREMENT=207 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: country_details
-- ============================================
CREATE TABLE `country_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `lang_code` varchar(10) NOT NULL,
  `description` text DEFAULT NULL,
  `known_for` text DEFAULT NULL,
  `travel_tips` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_country_lang` (`country_id`,`lang_code`),
  CONSTRAINT `country_details_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1576 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: country_feedback
-- ============================================
CREATE TABLE `country_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `feedback_type` enum('helpful','not_helpful') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_vote` (`country_id`,`ip_address`),
  KEY `idx_country` (`country_id`),
  CONSTRAINT `country_feedback_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: country_translations
-- ============================================
CREATE TABLE `country_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `lang_code` varchar(5) NOT NULL,
  `country_name` varchar(100) NOT NULL,
  `entry_summary` text NOT NULL COMMENT 'Brief overview of entry requirements',
  `visa_requirements` text DEFAULT NULL COMMENT 'Detailed visa information',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `visa_duration` varchar(100) DEFAULT NULL COMMENT 'e.g., 90 days, 6 months',
  `passport_validity` varchar(100) DEFAULT NULL COMMENT 'e.g., 6 months beyond stay',
  `visa_fee` varchar(100) DEFAULT NULL COMMENT 'e.g., Free, $50 USD',
  `processing_time` varchar(100) DEFAULT NULL COMMENT 'e.g., Instant, 3-5 business days',
  `official_visa_url` varchar(500) DEFAULT NULL COMMENT 'Official government visa portal',
  `arrival_card_required` varchar(50) DEFAULT NULL COMMENT 'Yes, No, Online only',
  `additional_docs` text DEFAULT NULL COMMENT 'Required documents list',
  `last_verified` date DEFAULT NULL COMMENT 'Last time data was verified',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_country_lang` (`country_id`,`lang_code`),
  KEY `lang_code` (`lang_code`),
  KEY `idx_country_name` (`country_name`),
  CONSTRAINT `country_translations_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `country_translations_ibfk_2` FOREIGN KEY (`lang_code`) REFERENCES `languages` (`code`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1366 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: country_views
-- ============================================
CREATE TABLE `country_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `views` int(11) DEFAULT 0,
  `last_viewed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_country` (`country_id`),
  CONSTRAINT `country_views_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: languages
-- ============================================
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `native_name` varchar(50) NOT NULL,
  `flag_emoji` varchar(10) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: translations
-- ============================================
CREATE TABLE `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_code` varchar(5) NOT NULL,
  `translation_key` varchar(100) NOT NULL,
  `translation_value` text NOT NULL,
  `category` varchar(50) DEFAULT 'general',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_translation` (`lang_code`,`translation_key`),
  CONSTRAINT `translations_ibfk_1` FOREIGN KEY (`lang_code`) REFERENCES `languages` (`code`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=477 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: visa_research_progress
-- ============================================
CREATE TABLE `visa_research_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(3) NOT NULL,
  `research_status` enum('not_started','in_progress','researched','verified','completed') DEFAULT 'not_started',
  `researcher_notes` text DEFAULT NULL,
  `sources_used` text DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `verified_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `country_code` (`country_code`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------
-- Table: bilateral_visa_requirements (passport personalization)
-- ------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `bilateral_visa_requirements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `from_country_id` INT UNSIGNED NOT NULL COMMENT 'Passport holder country',
  `to_country_id` INT UNSIGNED NOT NULL COMMENT 'Destination country',
  `visa_type` ENUM('visa_free', 'visa_on_arrival', 'evisa', 'visa_required', 'no_entry') NOT NULL DEFAULT 'visa_required',
  `duration_days` INT NULL,
  `cost_usd` DECIMAL(10,2) NULL,
  `cost_local_currency` VARCHAR(20) NULL,
  `processing_time_days` INT NULL,
  `requirements_summary` TEXT NULL,
  `application_process` TEXT NULL,
  `special_notes` TEXT NULL,
  `approval_rate_percent` TINYINT UNSIGNED NULL,
  `is_verified` BOOLEAN DEFAULT FALSE,
  `data_source` VARCHAR(255) NULL,
  `last_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_from_country` (`from_country_id`),
  INDEX `idx_to_country` (`to_country_id`),
  INDEX `idx_visa_type` (`visa_type`),
  INDEX `idx_from_to` (`from_country_id`, `to_country_id`),
  UNIQUE KEY `unique_bilateral` (`from_country_id`, `to_country_id`),
  CONSTRAINT `fk_from_country` FOREIGN KEY (`from_country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_to_country` FOREIGN KEY (`to_country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chk_different_countries` CHECK (`from_country_id` != `to_country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------
-- Table: user_preferences (passport selections)
-- ------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_preferences` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `session_id` VARCHAR(255) NOT NULL,
  `selected_passport_country_id` INT UNSIGNED NULL,
  `last_accessed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_session` (`session_id`),
  INDEX `idx_passport` (`selected_passport_country_id`),
  CONSTRAINT `fk_passport_country` FOREIGN KEY (`selected_passport_country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------
-- Table: personalization_stats
-- ------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `personalization_stats` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `passport_country_id` INT UNSIGNED NOT NULL,
  `destination_country_id` INT UNSIGNED NULL,
  `action_type` ENUM('passport_selected', 'country_viewed', 'filter_used') NOT NULL,
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_passport_stats` (`passport_country_id`),
  INDEX `idx_destination_stats` (`destination_country_id`),
  INDEX `idx_timestamp` (`timestamp`),
  CONSTRAINT `fk_passport_stats` FOREIGN KEY (`passport_country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_destination_stats` FOREIGN KEY (`destination_country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

