<?php
/**
 * AdSense Helper Functions
 */

/**
 * Get AdSense setting value
 */
function getAdSenseSetting($key, $default = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM adsense_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch (PDOException $e) {
        return $default;
    }
}

/**
 * Check if AdSense is enabled
 */
function isAdSenseEnabled() {
    return getAdSenseSetting('adsense_enabled', '0') === '1';
}

/**
 * Get AdSense client ID
 */
function getAdSenseClientId() {
    return getAdSenseSetting('adsense_client_id', '');
}

/**
 * Display AdSense ad unit
 */
function displayAdSense($slotKey, $adFormat = 'auto', $customClass = '') {
    if (!isAdSenseEnabled()) {
        return '';
    }
    
    $clientId = getAdSenseClientId();
    $adSlot = getAdSenseSetting($slotKey, '');
    
    if (empty($clientId) || empty($adSlot)) {
        return '';
    }
    
    $html = '<div class="adsense-container ' . htmlspecialchars($customClass) . '">';
    $html .= '<ins class="adsbygoogle"';
    $html .= ' style="display:block"';
    $html .= ' data-ad-client="' . htmlspecialchars($clientId) . '"';
    $html .= ' data-ad-slot="' . htmlspecialchars($adSlot) . '"';
    $html .= ' data-ad-format="' . htmlspecialchars($adFormat) . '"';
    $html .= ' data-full-width-responsive="true"></ins>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Display in-feed ad card (fits with country cards design)
 */
function displayAdCard() {
    // Show placeholder even when disabled for preview
    $clientId = getAdSenseClientId();
    $adSlot = getAdSenseSetting('ad_slot_infeed_card', '');
    $enabled = isAdSenseEnabled();
    
    if (!$enabled || empty($clientId) || empty($adSlot)) {
        // Show placeholder
        return '<div class="country-card ad-card ad-placeholder">
            <div class="ad-label">Advertisement Space</div>
            <div style="padding: 2rem; text-align: center; color: #94a3b8;">
                <svg width="48" height="48" viewBox="0 0 20 20" fill="currentColor" style="opacity: 0.3; margin-bottom: 1rem;">
                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                </svg>
                <p style="font-size: 0.875rem; font-weight: 500;">Google AdSense In-Feed Ad</p>
                <p style="font-size: 0.75rem; margin-top: 0.5rem;">Configure in Admin Panel</p>
            </div>
        </div>';
    }
    
    $html = '<div class="country-card ad-card">';
    $html .= '<div class="ad-label">Sponsored</div>';
    $html .= '<ins class="adsbygoogle"';
    $html .= ' style="display:block"';
    $html .= ' data-ad-format="fluid"';
    $html .= ' data-ad-layout-key="-6t+ed+2i-1n-4w"';
    $html .= ' data-ad-client="' . htmlspecialchars($clientId) . '"';
    $html .= ' data-ad-slot="' . htmlspecialchars($adSlot) . '"></ins>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Display horizontal panel ad
 */
function displayAdPanel($slotKey = 'ad_slot_panel_horizontal') {
    // Show placeholder even when disabled for preview
    $clientId = getAdSenseClientId();
    $adSlot = getAdSenseSetting($slotKey, '');
    $enabled = isAdSenseEnabled();
    
    if (!$enabled || empty($clientId) || empty($adSlot)) {
        // Show placeholder
        return '<div class="ad-panel ad-placeholder">
            <div class="ad-label">Advertisement Space</div>
            <div style="padding: 2rem; text-align: center; color: #94a3b8;">
                <svg width="48" height="48" viewBox="0 0 20 20" fill="currentColor" style="opacity: 0.3; margin-bottom: 1rem;">
                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                </svg>
                <p style="font-size: 1rem; font-weight: 500;">Google AdSense Display Ad (Horizontal)</p>
                <p style="font-size: 0.875rem; margin-top: 0.5rem;">Configure in Admin Panel to activate</p>
            </div>
        </div>';
    }
    
    $html = '<div class="ad-panel">';
    $html .= '<div class="ad-label">Advertisement</div>';
    $html .= '<ins class="adsbygoogle"';
    $html .= ' style="display:block"';
    $html .= ' data-ad-client="' . htmlspecialchars($clientId) . '"';
    $html .= ' data-ad-slot="' . htmlspecialchars($adSlot) . '"';
    $html .= ' data-ad-format="horizontal"';
    $html .= ' data-full-width-responsive="true"></ins>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Get AdSense script tag for page head
 */
function getAdSenseScript() {
    if (!isAdSenseEnabled()) {
        return '';
    }
        // Check if custom site code is provided
    $siteCode = getAdSenseSetting('adsense_site_code', '');
    if (!empty($siteCode)) {
        // Use custom site code provided by user
        return trim($siteCode);
    }
    
    // Fall back to auto-generated script    $clientId = getAdSenseClientId();
    if (empty($clientId)) {
        return '';
    }
    
    return '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . 
           htmlspecialchars($clientId) . '" crossorigin="anonymous"></script>';
}

/**
 * Initialize AdSense ads on page
 */
function initAdSenseAds() {
    if (!isAdSenseEnabled()) {
        return '';
    }
    
    return '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
}
