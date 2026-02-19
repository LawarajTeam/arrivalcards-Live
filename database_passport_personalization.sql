-- ==================================================================
-- PASSPORT PERSONALIZATION DATABASE SCHEMA
-- ==================================================================
-- Purpose: Add bilateral visa requirements for passport-specific data
-- Date: February 19, 2026
-- Impact: Enables personalized visa information per passport nationality
-- ==================================================================

-- Create bilateral visa requirements table
CREATE TABLE IF NOT EXISTS `bilateral_visa_requirements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `from_country_id` INT UNSIGNED NOT NULL COMMENT 'Passport holder country (references countries.id)',
  `to_country_id` INT UNSIGNED NOT NULL COMMENT 'Destination country (references countries.id)',
  `visa_type` ENUM('visa_free', 'visa_on_arrival', 'evisa', 'visa_required', 'no_entry') NOT NULL DEFAULT 'visa_required',
  `duration_days` INT NULL COMMENT 'Maximum stay duration in days (NULL = varies)',
  `cost_usd` DECIMAL(10,2) NULL COMMENT 'Visa cost in USD (NULL = free or varies)',
  `cost_local_currency` VARCHAR(20) NULL COMMENT 'Cost in local currency (e.g., €80, £95)',
  `processing_time_days` INT NULL COMMENT 'Processing time in days (NULL = immediate or varies)',
  `requirements_summary` TEXT NULL COMMENT 'Brief summary of key requirements',
  `application_process` TEXT NULL COMMENT 'How to apply (e.g., online, embassy, airport)',
  `special_notes` TEXT NULL COMMENT 'Special conditions, exemptions, or important info',
  `approval_rate_percent` TINYINT UNSIGNED NULL COMMENT 'Approval rate percentage (0-100)',
  `is_verified` BOOLEAN DEFAULT FALSE COMMENT 'Whether data has been verified',
  `data_source` VARCHAR(255) NULL COMMENT 'Source of information (e.g., IATA, gov website)',
  `last_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  -- Indexes for performance
  INDEX `idx_from_country` (`from_country_id`),
  INDEX `idx_to_country` (`to_country_id`),
  INDEX `idx_visa_type` (`visa_type`),
  INDEX `idx_from_to` (`from_country_id`, `to_country_id`),
  UNIQUE KEY `unique_bilateral` (`from_country_id`, `to_country_id`),
  
  -- Foreign key constraints
  CONSTRAINT `fk_from_country` FOREIGN KEY (`from_country_id`) 
    REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_to_country` FOREIGN KEY (`to_country_id`) 
    REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    
  -- Prevent self-references (no country to itself)
  CONSTRAINT `chk_different_countries` CHECK (`from_country_id` != `to_country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Bilateral visa requirements between countries for passport personalization';

-- ==================================================================
-- Add user preferences table for storing passport selections
-- ==================================================================

CREATE TABLE IF NOT EXISTS `user_preferences` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `session_id` VARCHAR(255) NOT NULL COMMENT 'User session identifier',
  `selected_passport_country_id` INT UNSIGNED NULL COMMENT 'User selected passport country',
  `last_accessed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  INDEX `idx_session` (`session_id`),
  INDEX `idx_passport` (`selected_passport_country_id`),
  
  CONSTRAINT `fk_passport_country` FOREIGN KEY (`selected_passport_country_id`) 
    REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='User preferences and passport selections';

-- ==================================================================
-- Add statistics table for tracking personalization usage
-- ==================================================================

CREATE TABLE IF NOT EXISTS `personalization_stats` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `passport_country_id` INT UNSIGNED NOT NULL,
  `destination_country_id` INT UNSIGNED NULL COMMENT 'NULL = just selected passport',
  `action_type` ENUM('passport_selected', 'country_viewed', 'filter_used') NOT NULL,
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  INDEX `idx_passport_stats` (`passport_country_id`),
  INDEX `idx_destination_stats` (`destination_country_id`),
  INDEX `idx_timestamp` (`timestamp`),
  
  CONSTRAINT `fk_passport_stats` FOREIGN KEY (`passport_country_id`) 
    REFERENCES `countries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_destination_stats` FOREIGN KEY (`destination_country_id`) 
    REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Track which passports and destinations are most viewed';

-- ==================================================================
-- Sample Data: USA Passport (for testing)
-- ==================================================================
-- Format: (from_country_id, to_country_id, visa_type, duration, cost, processing_time, requirements, process, notes, approval_rate, verified, source)
-- USA country_id = 236 (you'll need to verify this from your database)

-- Note: Replace country IDs with actual IDs from your countries table
-- This is just sample structure - actual IDs need to be queried

-- Example template (commented out - will populate programmatically):
-- INSERT INTO bilateral_visa_requirements 
-- (from_country_id, to_country_id, visa_type, duration_days, cost_usd, cost_local_currency, 
--  processing_time_days, requirements_summary, application_process, special_notes, 
--  approval_rate_percent, is_verified, data_source)
-- VALUES
-- -- USA to Thailand
-- (236, 221, 'visa_free', 30, 0.00, NULL, 0, 
--  'Valid passport (6 months), return ticket, proof of funds', 
--  'Visa-free entry at all ports', 
--  'Can extend 30 days at immigration office',
--  99, TRUE, 'Thai Immigration Bureau'),
-- 
-- -- USA to UK  
-- (236, 77, 'visa_free', 180, 0.00, NULL, 0,
--  'Valid passport, return ticket, sufficient funds',
--  'Visa-free entry for tourism/business',
--  'Part of visa waiver program',
--  99, TRUE, 'UK Home Office');

-- ==================================================================
-- VERIFICATION QUERIES
-- ==================================================================

-- Count total bilateral records
-- SELECT COUNT(*) as total_records FROM bilateral_visa_requirements;

-- Check records for specific passport
-- SELECT 
--   c1.country_name as passport,
--   c2.country_name as destination,
--   visa_type,
--   duration_days,
--   cost_usd
-- FROM bilateral_visa_requirements b
-- JOIN countries c1 ON b.from_country_id = c1.id
-- JOIN countries c2 ON b.to_country_id = c2.id
-- WHERE c1.country_code = 'USA'
-- ORDER BY c2.country_name;

-- Statistics by visa type for a passport
-- SELECT 
--   visa_type,
--   COUNT(*) as count
-- FROM bilateral_visa_requirements
-- WHERE from_country_id = (SELECT id FROM countries WHERE country_code = 'USA')
-- GROUP BY visa_type;

-- ==================================================================
-- ROLLBACK SCRIPT (if needed)
-- ==================================================================

-- DROP TABLE IF EXISTS personalization_stats;
-- DROP TABLE IF EXISTS user_preferences;
-- DROP TABLE IF EXISTS bilateral_visa_requirements;

-- ==================================================================
-- END OF MIGRATION
-- ==================================================================
