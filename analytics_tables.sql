-- Analytics Database Tables
-- Create comprehensive tracking tables for traffic analytics

-- Page Views Tracking Table
CREATE TABLE IF NOT EXISTS `page_views` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) NOT NULL,
  `visitor_ip` varchar(45) NOT NULL,
  `visitor_country` varchar(100) DEFAULT NULL,
  `visitor_city` varchar(100) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `page_url` varchar(500) NOT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `referrer` varchar(500) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `device_type` varchar(20) DEFAULT 'desktop',
  `browser` varchar(50) DEFAULT NULL,
  `operating_system` varchar(50) DEFAULT NULL,
  `screen_resolution` varchar(20) DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  `session_duration` int(11) DEFAULT 0,
  `viewed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_session` (`session_id`),
  KEY `idx_visitor_ip` (`visitor_ip`),
  KEY `idx_country` (`country_id`),
  KEY `idx_viewed_at` (`viewed_at`),
  KEY `idx_visitor_country` (`visitor_country`),
  KEY `idx_device` (`device_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Session Tracking Table
CREATE TABLE IF NOT EXISTS `visitor_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) NOT NULL,
  `visitor_ip` varchar(45) NOT NULL,
  `visitor_country` varchar(100) DEFAULT NULL,
  `first_page` varchar(500) DEFAULT NULL,
  `last_page` varchar(500) DEFAULT NULL,
  `pages_viewed` int(11) DEFAULT 1,
  `total_duration` int(11) DEFAULT 0,
  `started_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_activity` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_session_id` (`session_id`),
  KEY `idx_visitor_ip` (`visitor_ip`),
  KEY `idx_started_at` (`started_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
