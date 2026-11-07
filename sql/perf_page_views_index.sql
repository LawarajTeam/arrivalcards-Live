-- Speeds up the homepage "views today" queries (range scan on viewed_at, covering country_id).
-- Run once on the production database. Safe to re-run on MariaDB (IF NOT EXISTS).
CREATE INDEX IF NOT EXISTS idx_viewed_country ON page_views (viewed_at, country_id);
