<?php
/**
 * Language Switcher Handler
 * Sets the user's language preference and redirects back
 */

session_start();

// Get requested language
$requestedLang = $_GET['lang'] ?? 'en';

// Validate language
$validLangs = ['en', 'es', 'zh', 'fr', 'de', 'it', 'ar'];
if (in_array($requestedLang, $validLangs)) {
    $_SESSION['lang'] = $requestedLang;
    
    // Set cookie for 1 year
    setcookie('lang', $requestedLang, [
        'expires' => time() + (365 * 24 * 60 * 60),
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

// Redirect back to previous page or home
$redirect = $_GET['redirect'] ?? '/ArrivalCards/index.php';

// Parse the redirect URL to get just the path
$parsedUrl = parse_url($redirect);
$path = $parsedUrl['path'] ?? '/ArrivalCards/index.php';

// Remove the base path (/ArrivalCards/) if it exists to avoid duplication
$path = preg_replace('#^/ArrivalCards/#', '', $path);
$path = ltrim($path, '/');

// Ensure redirect is safe (no external URLs)
if (empty($path) || strpos($path, 'http') === 0 || strpos($path, '//') === 0) {
    $path = 'index.php';
}

// Get existing query parameters
$queryString = $parsedUrl['query'] ?? '';
parse_str($queryString, $queryParams);

// Update the lang parameter
$queryParams['lang'] = $requestedLang;

// Rebuild the URL
$newQueryString = http_build_query($queryParams);
$finalRedirect = $path . ($newQueryString ? '?' . $newQueryString : '');

header('Location: ' . $finalRedirect);
exit;
