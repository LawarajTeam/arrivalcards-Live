<?php
/**
 * Helper Functions
 * Utility functions used throughout the application
 */

/**
 * Convert 3-letter country code to 2-letter for flags
 */
function getCountryCode2Letter($code3) {
    $mapping = [
        'FRA' => 'fr', 'USA' => 'us', 'JPN' => 'jp', 'AUS' => 'au', 'GBR' => 'gb',
        'CAN' => 'ca', 'MEX' => 'mx', 'DEU' => 'de', 'ITA' => 'it', 'ESP' => 'es',
        'CHN' => 'cn', 'IND' => 'in', 'THA' => 'th', 'SGP' => 'sg', 'KOR' => 'kr',
        'BRA' => 'br', 'ARG' => 'ar', 'ZAF' => 'za', 'EGY' => 'eg', 'MAR' => 'ma',
        'TUR' => 'tr', 'GRC' => 'gr', 'NLD' => 'nl', 'CHE' => 'ch', 'AUT' => 'at',
        'PRT' => 'pt', 'BEL' => 'be', 'SWE' => 'se', 'NOR' => 'no', 'DNK' => 'dk',
        'POL' => 'pl', 'CZE' => 'cz', 'HUN' => 'hu', 'IRL' => 'ie', 'RUS' => 'ru',
        'ARE' => 'ae', 'IDN' => 'id', 'MYS' => 'my', 'VNM' => 'vn', 'PHL' => 'ph',
        'NZL' => 'nz', 'ISL' => 'is', 'FIN' => 'fi', 'HKG' => 'hk', 'CHL' => 'cl',
        'PER' => 'pe', 'COL' => 'co', 'ECU' => 'ec', 'CRI' => 'cr', 'PAN' => 'pa',
        'ISR' => 'il', 'JOR' => 'jo', 'KEN' => 'ke', 'TZA' => 'tz', 'LKA' => 'lk',
        'NPL' => 'np', 'CUB' => 'cu', 'DOM' => 'do', 'JAM' => 'jm', 'TTO' => 'tt',
        'BHS' => 'bs', 'BRB' => 'bb', 'URY' => 'uy', 'PRY' => 'py', 'BOL' => 'bo',
        'VEN' => 've', 'GTM' => 'gt', 'HND' => 'hn', 'NIC' => 'ni', 'SLV' => 'sv',
        'BLZ' => 'bz', 'LUX' => 'lu', 'MLT' => 'mt', 'CYP' => 'cy', 'EST' => 'ee',
        'LVA' => 'lv', 'LTU' => 'lt', 'SVN' => 'si', 'SVK' => 'sk', 'HRV' => 'hr',
        'BGR' => 'bg', 'ROU' => 'ro', 'SRB' => 'rs', 'ALB' => 'al', 'MKD' => 'mk',
        'BIH' => 'ba', 'MNE' => 'me', 'UKR' => 'ua', 'BLR' => 'by', 'MDA' => 'md',
        'GEO' => 'ge', 'ARM' => 'am', 'AZE' => 'az', 'KAZ' => 'kz', 'UZB' => 'uz',
        'KGZ' => 'kg', 'TJK' => 'tj', 'TKM' => 'tm', 'MNG' => 'mn', 'BGD' => 'bd',
        'PAK' => 'pk', 'MMR' => 'mm', 'KHM' => 'kh', 'LAO' => 'la', 'BRN' => 'bn',
        'QAT' => 'qa', 'KWT' => 'kw', 'BHR' => 'bh', 'OMN' => 'om', 'SAU' => 'sa',
        'LBN' => 'lb', 'IRQ' => 'iq', 'SYR' => 'sy', 'YEM' => 'ye', 'AFG' => 'af',
        'IRN' => 'ir', 'ETH' => 'et', 'GHA' => 'gh', 'NGA' => 'ng', 'SEN' => 'sn',
        'CIV' => 'ci', 'CMR' => 'cm', 'UGA' => 'ug', 'RWA' => 'rw', 'ZMB' => 'zm',
        'ZWE' => 'zw', 'MOZ' => 'mz', 'BWA' => 'bw', 'NAM' => 'na', 'AGO' => 'ao',
        'TUN' => 'tn', 'DZA' => 'dz', 'LBY' => 'ly', 'SDN' => 'sd', 'MUS' => 'mu',
        'SYC' => 'sc', 'MDG' => 'mg', 'MLI' => 'ml', 'BFA' => 'bf', 'NER' => 'ne',
        'TCD' => 'td', 'MWI' => 'mw', 'FJI' => 'fj', 'PNG' => 'pg', 'VUT' => 'vu',
        'WSM' => 'ws', 'TON' => 'to', 'PLW' => 'pw', 'MHL' => 'mh', 'FSM' => 'fm',
        'KIR' => 'ki', 'SLB' => 'sb', 'TLS' => 'tl', 'BTN' => 'bt', 'MDV' => 'mv'
    ];
    return $mapping[strtoupper($code3)] ?? strtolower(substr($code3, 0, 2));
}

/**
 * Sanitize output to prevent XSS
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Get translation for current language
 */
function t($key) {
    global $translations;
    if (!isset($translations)) {
        loadTranslations();
    }
    return $translations[$key] ?? $key;
}

/**
 * Load translations for current language
 */
function loadTranslations() {
    global $pdo, $translations;
    
    $lang = CURRENT_LANG;
    $stmt = $pdo->prepare("SELECT translation_key, translation_value FROM translations WHERE lang_code = ?");
    $stmt->execute([$lang]);
    
    $translations = [];
    while ($row = $stmt->fetch()) {
        $translations[$row['translation_key']] = $row['translation_value'];
    }
}

/**
 * Get all active languages
 */
function getLanguages() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM languages WHERE is_active = 1 ORDER BY display_order");
    return $stmt->fetchAll();
}

/**
 * Get all regions
 */
function getRegions() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT DISTINCT region FROM countries WHERE is_active = 1 ORDER BY region");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * Get all visa types
 */
function getVisaTypes() {
    return [
        'visa_free' => t('visa_free'),
        'visa_on_arrival' => t('visa_on_arrival'),
        'visa_required' => t('visa_required'),
        'evisa' => t('evisa')
    ];
}

/**
 * Get countries with translations (with caching)
 */
function getCountries($region = null, $visaType = null, $search = null) {
    global $pdo;
    
    // Create cache key based on parameters
    $cacheKey = 'countries_' . CURRENT_LANG . '_' . ($region ?? 'all') . '_' . ($visaType ?? 'all') . '_' . ($search ?? 'none');
    
    // Check if we have cached data (valid for 5 minutes)
    if (isset($_SESSION[$cacheKey]) && isset($_SESSION[$cacheKey . '_time'])) {
        if (time() - $_SESSION[$cacheKey . '_time'] < 300) { // 5 minutes
            return $_SESSION[$cacheKey];
        }
    }
    
    $sql = "SELECT c.*, ct.country_name, ct.entry_summary, ct.visa_requirements, ct.last_verified,
            c.view_count
            FROM countries c
            INNER JOIN country_translations ct ON c.id = ct.country_id
            WHERE c.is_active = 1 AND ct.lang_code = ?";
    
    $params = [CURRENT_LANG];
    
    if ($region) {
        $sql .= " AND c.region = ?";
        $params[] = $region;
    }
    
    if ($visaType) {
        $sql .= " AND c.visa_type = ?";
        $params[] = $visaType;
    }
    
    if ($search) {
        $sql .= " AND ct.country_name LIKE ?";
        $params[] = "%$search%";
    }
    
    $sql .= " ORDER BY c.display_order, ct.country_name";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll();
    
    // Cache the results
    $_SESSION[$cacheKey] = $results;
    $_SESSION[$cacheKey . '_time'] = time();
    
    return $results;
}

/**
 * Get single country by ID
 */
function getCountryById($id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT c.*, ct.country_name, ct.entry_summary, ct.visa_requirements, ct.last_verified,
        c.view_count
        FROM countries c
        INNER JOIN country_translations ct ON c.id = ct.country_id
        WHERE c.id = ? AND ct.lang_code = ?
    ");
    $stmt->execute([$id, CURRENT_LANG]);
    return $stmt->fetch();
}

/**
 * Get total country count
 */
function getCountryCount() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM countries WHERE is_active = 1");
    return $stmt->fetchColumn();
}

/**
 * Format date for display
 */
function formatDate($date, $format = 'M d, Y') {
    if (empty($date)) {
        return 'N/A';
    }
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return 'N/A';
    }
    return date($format, $timestamp);
}

/**
 * Validate email address
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Send email (basic PHP mail for now, can be upgraded to PHPMailer)
 */
function sendEmail($to, $subject, $message, $from = null) {
    $from = $from ?: SMTP_FROM;
    $headers = [
        'From: ' . SMTP_FROM_NAME . ' <' . $from . '>',
        'Reply-To: ' . $from,
        'X-Mailer: PHP/' . phpversion(),
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8'
    ];
    
    return mail($to, $subject, $message, implode("\r\n", $headers));
}

/**
 * Log admin action
 */
function logAdminAction($action, $tableName = null, $recordId = null, $oldValue = null, $newValue = null) {
    global $pdo;
    
    if (!isset($_SESSION['admin_id'])) {
        return false;
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO audit_log (admin_user_id, action, table_name, record_id, old_value, new_value, ip_address)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    return $stmt->execute([
        $_SESSION['admin_id'],
        $action,
        $tableName,
        $recordId,
        $oldValue ? json_encode($oldValue) : null,
        $newValue ? json_encode($newValue) : null,
        $_SERVER['REMOTE_ADDR']
    ]);
}

/**
 * Check if user is logged in as admin
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

/**
 * Require admin login
 */
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header('Location: ' . APP_URL . '/admin/login.php');
        exit;
    }
}

/**
 * Get client IP address
 */
function getClientIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

/**
 * Redirect helper
 */
function redirect($url, $statusCode = 302) {
    header('Location: ' . $url, true, $statusCode);
    exit;
}

/**
 * Set flash message
 */
function setFlashMessage($message, $type = 'success') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

/**
 * Get and clear flash message
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = [
            'message' => $_SESSION['flash_message'],
            'type' => $_SESSION['flash_type'] ?? 'success'
        ];
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return $message;
    }
    return null;
}

/**
 * Truncate text to specified length
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . $suffix;
}

/**
 * Get visa type badge class
 */
function getVisaTypeBadgeClass($visaType) {
    $classes = [
        'visa_free' => 'success',
        'visa_on_arrival' => 'info',
        'evisa' => 'warning',
        'visa_required' => 'danger'
    ];
    return $classes[$visaType] ?? 'secondary';
}

/**
 * Get visa type label
 */
function getVisaTypeLabel($visaType) {
    $labels = [
        'visa_free' => t('visa_free'),
        'visa_on_arrival' => t('visa_on_arrival'),
        'evisa' => t('evisa'),
        'visa_required' => t('visa_required')
    ];
    return $labels[$visaType] ?? t('visa_required');
}
