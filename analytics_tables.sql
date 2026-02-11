-- Analytics Database Tables
-- Create comprehensive tracking tables for traffic analytics

-- Page Views Tracking Table
CREATE TABLE IF NOT EXISTS `page_views` (
  `id` bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `session_id` varchar(100) NOT NULL,
  `visitor_ip` varchar(45) NOT NULL,
  `visitor_country` varchar(100) NULL,
  `visitor_city` varchar(100) NULL,
  `country_id` int(11) NULL,
  `page_url` varchar(500) NOT NULL,
  `page_title` varchar(255) NULL,
  `referrer` varchar(500) NULL,
  `user_agent` text NULL,
  `device_type` varchar(20) DEFAULT 'desktop',
  `browser` varchar(50) NULL,
  `operating_system` varchar(50) NULL,
  `screen_resolution` varchar(20) NULL,
  `language` varchar(10) NULL,
  `session_duration` int(11) DEFAULT 0,
  `viewed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_session` (`session_id`),
  KEY `idx_visitor_ip` (`visitor_ip`),
  KEY `idx_country` (`country_id`),
  KEY `idx_viewed_at` (`viewed_at`),
  KEY `idx_visitor_country` (`visitor_country`),
  KEY `idx_device` (`device_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Session Tracking Table
CREATE TABLE IF NOT EXISTS `visitor_sessions` (
  `id` bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `session_id` varchar(100) NOT NULL,
  `visitor_ip` varchar(45) NOT NULL,
  `visitor_country` varchar(100) NULL,
  `first_page` varchar(500) NULL,
  `last_page` varchar(500) NULL,
  `pages_viewed` int(11) DEFAULT 1,
  `total_duration` int(11) DEFAULT 0,
  `started_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_activity` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `idx_session_id` (`session_id`),
  KEY `idx_visitor_ip` (`visitor_ip`),
  KEY `idx_started_at` (`started_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
