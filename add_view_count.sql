-- Add view_count column to countries table
ALTER TABLE countries ADD COLUMN view_count INT(11) DEFAULT 0;
