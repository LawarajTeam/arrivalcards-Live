-- ============================================
-- QUICK FIX: Add missing country_views table
-- Run this in phpMyAdmin SQL tab
-- ============================================

CREATE TABLE IF NOT EXISTS `country_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `views` int(11) DEFAULT 0,
  `last_viewed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_country` (`country_id`),
  CONSTRAINT `country_views_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Initialize view counts for all existing countries
INSERT IGNORE INTO country_views (country_id, views, last_viewed)
SELECT id, 0, NOW()
FROM countries;

-- ============================================
-- Verification query - Should show all countries with 0 views
-- ============================================
SELECT c.country_code, c.id, COALESCE(cv.views, 0) as views
FROM countries c
LEFT JOIN country_views cv ON c.id = cv.country_id
ORDER BY c.country_code
LIMIT 10;
