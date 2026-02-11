-- Add view_count column to countries table if not exists
ALTER TABLE countries 
ADD COLUMN IF NOT EXISTS view_count INT(11) DEFAULT 0 AFTER is_active;

-- Add index for better performance
CREATE INDEX IF NOT EXISTS idx_view_count ON countries(view_count);
