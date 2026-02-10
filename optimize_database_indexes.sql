-- ============================================
-- Database Performance Optimization
-- Add indexes for faster queries
-- ============================================

-- Index for countries table
ALTER TABLE countries 
ADD INDEX idx_active_order (is_active, display_order),
ADD INDEX idx_region (region),
ADD INDEX idx_visa_type (visa_type);

-- Index for country_translations table
ALTER TABLE country_translations 
ADD INDEX idx_country_lang (country_id, lang_code),
ADD INDEX idx_lang_name (lang_code, country_name);

-- Index for country_views table
ALTER TABLE country_views 
ADD INDEX idx_country_views (country_id);

-- Optimize tables after adding indexes
OPTIMIZE TABLE countries;
OPTIMIZE TABLE country_translations;
OPTIMIZE TABLE country_views;

-- Verify indexes were created
SHOW INDEX FROM countries;
SHOW INDEX FROM country_translations;
SHOW INDEX FROM country_views;
