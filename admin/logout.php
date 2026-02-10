<?php
/**
 * Admin Logout
 */

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
header('Location: ' . (getenv('APP_URL') ?: 'http://localhost/ArrivalCards') . '/admin/login.php');
exit;
