<?php
/**
 * Admin Logout
 */

require_once __DIR__ . '/../includes/config.php';

session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Delete session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to login page
header('Location: ' . APP_URL . '/admin/login.php');
exit;
