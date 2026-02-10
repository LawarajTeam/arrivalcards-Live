<?php
/**
 * AdSense Verification Script
 * Checks if AdSense is properly configured and working
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

header('Content-Type: application/json');

$checks = [];
$allPassed = true;

try {
    // Check 1: AdSense Enabled
    $stmt = $pdo->prepare("SELECT setting_value FROM adsense_settings WHERE setting_key = 'adsense_enabled'");
    $stmt->execute();
    $enabled = $stmt->fetchColumn();
    
    if ($enabled === '1') {
        $checks[] = [
            'name' => 'AdSense Status',
            'passed' => true,
            'message' => 'AdSense is enabled in the system.',
            'details' => null
        ];
    } else {
        $checks[] = [
            'name' => 'AdSense Status',
            'passed' => false,
            'message' => 'AdSense is currently disabled.',
            'details' => 'Enable AdSense using the checkbox above and save settings.'
        ];
        $allPassed = false;
    }
    
    // Check 2: Client ID Format
    $stmt = $pdo->prepare("SELECT setting_value FROM adsense_settings WHERE setting_key = 'adsense_client_id'");
    $stmt->execute();
    $clientId = $stmt->fetchColumn();
    
    if (empty($clientId)) {
        $checks[] = [
            'name' => 'Client ID Configuration',
            'passed' => false,
            'message' => 'No AdSense Client ID configured.',
            'details' => 'Add your AdSense Publisher ID (format: ca-pub-XXXXXXXXXXXXXXXX) in the field above.'
        ];
        $allPassed = false;
    } elseif (!preg_match('/^ca-pub-\d{16}$/', $clientId)) {
        $checks[] = [
            'name' => 'Client ID Format',
            'passed' => false,
            'message' => 'Client ID format appears incorrect.',
            'details' => 'Current value: ' . htmlspecialchars($clientId) . '<br>Expected format: ca-pub-XXXXXXXXXXXXXXXX (16 digits)'
        ];
        $allPassed = false;
    } else {
        $checks[] = [
            'name' => 'Client ID Configuration',
            'passed' => true,
            'message' => 'Valid AdSense Client ID configured.',
            'details' => htmlspecialchars($clientId)
        ];
    }
    
    // Check 3: Ad Slot Configuration
    $slotKeys = [
        'ad_slot_infeed_card' => 'In-Feed Card',
        'ad_slot_panel_horizontal' => 'Horizontal Panel',
        'ad_slot_landing_top' => 'Landing Top',
        'ad_slot_landing_middle' => 'Landing Middle',
        'ad_slot_landing_bottom' => 'Landing Bottom'
    ];
    
    $configuredSlots = 0;
    $slotDetails = [];
    
    foreach ($slotKeys as $key => $label) {
        $stmt = $pdo->prepare("SELECT setting_value FROM adsense_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $value = $stmt->fetchColumn();
        
        if (!empty($value)) {
            $configuredSlots++;
            $slotDetails[] = "✓ {$label}: {$value}";
        } else {
            $slotDetails[] = "✗ {$label}: Not configured";
        }
    }
    
    if ($configuredSlots >= 1) {
        $checks[] = [
            'name' => 'Ad Slot Configuration',
            'passed' => true,
            'message' => "{$configuredSlots} of " . count($slotKeys) . " ad slots configured.",
            'details' => implode('<br>', $slotDetails)
        ];
    } else {
        $checks[] = [
            'name' => 'Ad Slot Configuration',
            'passed' => false,
            'message' => 'No ad slots configured.',
            'details' => 'Add at least one ad slot ID from your AdSense account.'
        ];
        $allPassed = false;
    }
    
    // Check 4: Header File Integration
    $headerFile = __DIR__ . '/../includes/header.php';
    if (file_exists($headerFile)) {
        $headerContent = file_get_contents($headerFile);
        
        if (strpos($headerContent, 'adsense_functions.php') !== false && 
            strpos($headerContent, 'getAdSenseScript') !== false) {
            $checks[] = [
                'name' => 'Header Integration',
                'passed' => true,
                'message' => 'AdSense script is properly integrated in header.php.',
                'details' => 'The AdSense functions are being called in the site header.'
            ];
        } else {
            $checks[] = [
                'name' => 'Header Integration',
                'passed' => false,
                'message' => 'AdSense script integration not found in header.',
                'details' => 'The header.php file may need to include AdSense integration code.'
            ];
            $allPassed = false;
        }
    } else {
        $checks[] = [
            'name' => 'Header Integration',
            'passed' => false,
            'message' => 'Cannot verify - header.php file not found.',
            'details' => null
        ];
        $allPassed = false;
    }
    
    // Check 5: Functions File Exists
    $functionsFile = __DIR__ . '/../includes/adsense_functions.php';
    if (file_exists($functionsFile)) {
        $checks[] = [
            'name' => 'AdSense Functions File',
            'passed' => true,
            'message' => 'AdSense functions file exists and is accessible.',
            'details' => 'File: includes/adsense_functions.php'
        ];
    } else {
        $checks[] = [
            'name' => 'AdSense Functions File',
            'passed' => false,
            'message' => 'AdSense functions file not found.',
            'details' => 'The adsense_functions.php file is missing from includes directory.'
        ];
        $allPassed = false;
    }
    
    // Check 6: Test Homepage for AdSense Code
    if (!empty($clientId) && $enabled === '1') {
        $homeUrl = APP_URL . '/index.php';
        
        // Use cURL with proper options
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $homeUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'AdSense-Verification-Bot/1.0');
        
        $homeContent = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode === 200 && $homeContent !== false) {
            // Check if AdSense script is present
            $hasAdSenseScript = strpos($homeContent, 'pagead2.googlesyndication.com') !== false;
            $hasClientId = strpos($homeContent, $clientId) !== false;
            $hasAdSbyGoogle = strpos($homeContent, 'adsbygoogle') !== false;
            
            if ($hasAdSenseScript && $hasClientId && $hasAdSbyGoogle) {
                $checks[] = [
                    'name' => 'Live Site Verification',
                    'passed' => true,
                    'message' => 'AdSense code detected on homepage.',
                    'details' => '✓ AdSense script found<br>✓ Client ID found in page<br>✓ Ad units present'
                ];
            } elseif ($hasAdSenseScript || $hasClientId) {
                $details = '';
                $details .= ($hasAdSenseScript ? '✓' : '✗') . ' AdSense script<br>';
                $details .= ($hasClientId ? '✓' : '✗') . ' Client ID in page<br>';
                $details .= ($hasAdSbyGoogle ? '✓' : '✗') . ' Ad units present';
                
                $checks[] = [
                    'name' => 'Live Site Verification',
                    'passed' => false,
                    'message' => 'AdSense code partially detected on homepage.',
                    'details' => $details . '<br><br>Some elements are missing. Clear cache and reload.'
                ];
                $allPassed = false;
            } else {
                $checks[] = [
                    'name' => 'Live Site Verification',
                    'passed' => false,
                    'message' => 'AdSense code not detected on homepage.',
                    'details' => 'The homepage loaded but no AdSense code was found. Check if caching is enabled or if there are PHP errors.'
                ];
                $allPassed = false;
            }
        } else {
            $checks[] = [
                'name' => 'Live Site Verification',
                'passed' => false,
                'message' => 'Unable to fetch homepage for verification.',
                'details' => 'HTTP Code: ' . $httpCode . '<br>Error: ' . ($curlError ?: 'Unknown error') . '<br>This check requires the site to be accessible.'
            ];
            // Don't fail overall if we can't fetch (might be localhost)
        }
    } else {
        $checks[] = [
            'name' => 'Live Site Verification',
            'passed' => false,
            'message' => 'Skipped - Enable AdSense and configure Client ID first.',
            'details' => null
        ];
    }
    
    // Check 7: Database Table Exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'adsense_settings'");
    if ($stmt->rowCount() > 0) {
        $checks[] = [
            'name' => 'Database Table',
            'passed' => true,
            'message' => 'AdSense settings table exists in database.',
            'details' => null
        ];
    } else {
        $checks[] = [
            'name' => 'Database Table',
            'passed' => false,
            'message' => 'AdSense settings table not found.',
            'details' => 'The database table needs to be created. Run the database migration script.'
        ];
        $allPassed = false;
    }
    
} catch (Exception $e) {
    echo json_encode([
        'allPassed' => false,
        'checks' => [[
            'name' => 'Verification Error',
            'passed' => false,
            'message' => 'Error during verification: ' . $e->getMessage(),
            'details' => null
        ]]
    ]);
    exit;
}

echo json_encode([
    'allPassed' => $allPassed,
    'checks' => $checks
]);
