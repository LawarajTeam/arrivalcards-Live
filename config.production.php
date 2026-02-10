<?php
// ============================================
// PRODUCTION Configuration File
// Copy this to includes/config.php on your server
// DO NOT commit this file to GitHub!
// ============================================

// ============================================
// DATABASE CONFIGURATION
// Update these with your production server details
// ============================================
define('DB_HOST', 'localhost');        // Usually 'localhost' for shared hosting
define('DB_NAME', 'your_db_name');     // Your database name
define('DB_USER', 'your_db_user');     // Your database username  
define('DB_PASS', 'your_db_password'); // Your database password

// ============================================
// PDO DATABASE CONNECTION
// ============================================
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ]
    );
} catch (PDOException $e) {
    // DO NOT display error details in production
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection error. Please contact support.");
}

// ============================================
// SESSION CONFIGURATION
// ============================================
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);   // Set to 0 if not using HTTPS
ini_set('session.cookie_samesite', 'Lax');

session_start();

// ============================================
// ERROR REPORTING (Production)
// ============================================
error_reporting(E_ALL);
ini_set('display_errors', 0);  // Never display errors in production
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error.log');

// ============================================
// TIMEZONE
// ============================================
date_default_timezone_set('UTC');  // Change to your timezone

// ============================================
// SITE CONFIGURATION
// ============================================
define('SITE_URL', 'https://www.arrivalcards.com');
define('SITE_NAME', 'Arrival Cards');
define('CONTACT_EMAIL', 'info@arrivalcards.com'); // Change to your email

// ============================================
// SECURITY SETTINGS
// ============================================
// Maximum login attempts
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes in seconds

// CSRF Token expiry
define('CSRF_TOKEN_EXPIRY', 3600); // 1 hour

// ============================================
// FILE UPLOAD SETTINGS (if you add upload features)
// ============================================
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf']);

// ============================================
// MAINTENANCE MODE
// ============================================
define('MAINTENANCE_MODE', false); // Set to true to enable maintenance mode

if (MAINTENANCE_MODE && !isset($_SESSION['admin_id'])) {
    // Show maintenance page to non-admin users
    http_response_code(503);
    header('Retry-After: 3600'); // 1 hour
    die('
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Site Maintenance</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f5f5f5; }
            h1 { color: #333; }
            p { color: #666; }
        </style>
    </head>
    <body>
        <h1>⚙️ Scheduled Maintenance</h1>
        <p>We are currently performing scheduled maintenance.</p>
        <p>We will be back shortly. Thank you for your patience!</p>
    </body>
    </html>
    ');
}

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Sanitize output for display
 */
function esc($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in as admin
 */
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

/**
 * Redirect to login if not authenticated
 */
function requireAdmin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
        return false;
    }
    
    // Check if token expired
    if (time() - $_SESSION['csrf_token_time'] > CSRF_TOKEN_EXPIRY) {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

// ============================================
// PRODUCTION READY
// ============================================
// After updating the settings above:
// 1. Save this file as includes/config.php on your server
// 2. Set file permissions to 640 (read-only)
// 3. Test the connection by visiting admin/system-test.php
// 4. Delete any test files (test_*.php, check_*.php, setup_*.php)
// ============================================
