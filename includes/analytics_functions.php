<?php
/**
 * Analytics Tracking Functions
 * Automatically track page views, sessions, and visitor data
 */

/**
 * Track page view with comprehensive details
 */
function trackPageView($countryId = null, $pageTitle = null) {
    global $pdo;
    
    // Don't track admin pages or bot traffic
    if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) {
        return;
    }
    
    // Check if it's a bot
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $botPatterns = ['bot', 'crawler', 'spider', 'scraper', 'curl', 'wget'];
    foreach ($botPatterns as $pattern) {
        if (stripos($userAgent, $pattern) !== false) {
            return;
        }
    }
    
    try {
        // Get or create session ID
        if (!isset($_SESSION['visitor_session_id'])) {
            $_SESSION['visitor_session_id'] = bin2hex(random_bytes(16));
            $_SESSION['session_start_time'] = time();
        }
        
        $sessionId = $_SESSION['visitor_session_id'];
        $sessionDuration = time() - ($_SESSION['session_start_time'] ?? time());
        
        // Get visitor details
        $visitorIp = getVisitorIP();
        $visitorCountry = getVisitorCountry($visitorIp);
        $deviceInfo = detectDevice();
        $browserInfo = detectBrowser();
        
        // Current page details
        $pageUrl = $_SERVER['REQUEST_URI'] ?? '/';
        $referrer = $_SERVER['HTTP_REFERER'] ?? null;
        
        // Insert page view
        $stmt = $pdo->prepare("
            INSERT INTO page_views (
                session_id, visitor_ip, visitor_country, country_id, page_url, 
                page_title, referrer, user_agent, device_type, browser, 
                operating_system, language, session_duration, viewed_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $sessionId,
            $visitorIp,
            $visitorCountry,
            $countryId,
            substr($pageUrl, 0, 500),
            $pageTitle,
            $referrer ? substr($referrer, 0, 500) : null,
            substr($userAgent, 0, 1000),
            $deviceInfo['type'],
            $browserInfo['name'],
            $browserInfo['os'],
            CURRENT_LANG ?? 'en',
            $sessionDuration
        ]);
        
        // Update session tracking
        updateSessionTracking($sessionId, $visitorIp, $visitorCountry, $pageUrl);
        
        // Update country view count if viewing a country page
        if ($countryId) {
            updateCountryViews($countryId);
        }
        
    } catch (PDOException $e) {
        // Silently fail - don't break the page if analytics fails
        error_log("Analytics tracking error: " . $e->getMessage());
    }
}

/**
 * Update or create visitor session
 */
function updateSessionTracking($sessionId, $visitorIp, $visitorCountry, $currentPage) {
    global $pdo;
    
    try {
        // Check if session exists
        $stmt = $pdo->prepare("SELECT id, pages_viewed FROM visitor_sessions WHERE session_id = ?");
        $stmt->execute([$sessionId]);
        $session = $stmt->fetch();
        
        if ($session) {
            // Update existing session
            $stmt = $pdo->prepare("
                UPDATE visitor_sessions 
                SET last_page = ?, 
                    pages_viewed = pages_viewed + 1,
                    last_activity = NOW(),
                    total_duration = TIMESTAMPDIFF(SECOND, started_at, NOW())
                WHERE session_id = ?
            ");
            $stmt->execute([substr($currentPage, 0, 500), $sessionId]);
        } else {
            // Create new session
            $stmt = $pdo->prepare("
                INSERT INTO visitor_sessions (
                    session_id, visitor_ip, visitor_country, first_page, 
                    last_page, pages_viewed, started_at, last_activity
                ) VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())
            ");
            $stmt->execute([
                $sessionId,
                $visitorIp,
                $visitorCountry,
                substr($currentPage, 0, 500),
                substr($currentPage, 0, 500)
            ]);
        }
    } catch (PDOException $e) {
        error_log("Session tracking error: " . $e->getMessage());
    }
}

/**
 * Get visitor IP address (handles proxies)
 */
function getVisitorIP() {
    $ipKeys = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];
    
    foreach ($ipKeys as $key) {
        if (!empty($_SERVER[$key])) {
            $ips = explode(',', $_SERVER[$key]);
            $ip = trim($ips[0]);
            
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

/**
 * Get visitor country from IP (basic implementation)
 */
function getVisitorCountry($ip) {
    // For production, integrate with IP geolocation service like:
    // - MaxMind GeoIP2
    // - IPStack API
    // - IP-API.com
    
    // Basic implementation using CloudFlare header if available
    if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
        return $_SERVER['HTTP_CF_IPCOUNTRY'];
    }
    
    // Try ip-api.com (free, 45 requests/minute limit)
    try {
        $data = @file_get_contents("http://ip-api.com/json/{$ip}?fields=country");
        if ($data) {
            $json = json_decode($data, true);
            if (isset($json['country'])) {
                return $json['country'];
            }
        }
    } catch (Exception $e) {
        // Silent fail
    }
    
    return null;
}

/**
 * Detect device type from user agent
 */
function detectDevice() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    $isMobile = preg_match('/(android|iphone|ipod|blackberry|windows phone)/i', $userAgent);
    $isTablet = preg_match('/(ipad|tablet|kindle|playbook)/i', $userAgent);
    
    if ($isTablet) {
        return ['type' => 'tablet'];
    } elseif ($isMobile) {
        return ['type' => 'mobile'];
    } else {
        return ['type' => 'desktop'];
    }
}

/**
 * Detect browser and OS from user agent
 */
function detectBrowser() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Detect browser
    $browsers = [
        'Chrome' => '/Chrome\/[\d\.]+/i',
        'Firefox' => '/Firefox\/[\d\.]+/i',
        'Safari' => '/Safari\/[\d\.]+/i',
        'Edge' => '/Edg\/[\d\.]+/i',
        'Opera' => '/OPR\/[\d\.]+/i',
        'IE' => '/MSIE|Trident/i'
    ];
    
    $browserName = 'Unknown';
    foreach ($browsers as $name => $pattern) {
        if (preg_match($pattern, $userAgent)) {
            $browserName = $name;
            break;
        }
    }
    
    // Detect OS
    $os = 'Unknown';
    if (preg_match('/Windows NT/i', $userAgent)) {
        $os = 'Windows';
    } elseif (preg_match('/Mac OS X/i', $userAgent)) {
        $os = 'macOS';
    } elseif (preg_match('/Linux/i', $userAgent)) {
        $os = 'Linux';
    } elseif (preg_match('/Android/i', $userAgent)) {
        $os = 'Android';
    } elseif (preg_match('/iOS|iPhone|iPad/i', $userAgent)) {
        $os = 'iOS';
    }
    
    return [
        'name' => $browserName,
        'os' => $os
    ];
}

/**
 * Update country view count in countries table
 */
function updateCountryViews($countryId) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            UPDATE countries 
            SET view_count = view_count + 1 
            WHERE id = ?
        ");
        $stmt->execute([$countryId]);
    } catch (PDOException $e) {
        error_log("Country view count error: " . $e->getMessage());
    }
}

/**
 * Get analytics summary for date range
 */
function getAnalyticsSummary($days = 7) {
    global $pdo;
    
    $dateFrom = date('Y-m-d 00:00:00', strtotime("-{$days} days"));
    
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_views,
            COUNT(DISTINCT visitor_ip) as unique_visitors,
            AVG(session_duration) as avg_duration,
            COUNT(DISTINCT session_id) as total_sessions
        FROM page_views
        WHERE viewed_at >= ?
    ");
    $stmt->execute([$dateFrom]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
