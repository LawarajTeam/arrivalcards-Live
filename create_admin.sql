-- ============================================
-- Production Admin User Setup
-- Create your first admin account
-- ============================================

-- IMPORTANT: Change 'YourSecurePassword123!' to your actual password
-- This script will hash the password automatically

-- Step 1: Run this to get your hashed password
-- In PHP, run: echo password_hash('YourSecurePassword123!', PASSWORD_DEFAULT);

-- Step 2: Replace the hash below with your generated hash
INSERT INTO admin_users (username, password_hash, email, full_name, is_active) 
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Change this!
    'your_email@domain.com',  -- Change this!
    'Administrator',
    1
);

-- Verify admin was created
SELECT id, username, email, full_name, is_active, created_at 
FROM admin_users 
WHERE username = 'admin';

-- ============================================
-- Default password in this example is: password
-- CHANGE THIS IMMEDIATELY AFTER FIRST LOGIN!
-- ============================================
