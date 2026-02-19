<?php
/**
 * Personalized Visa Requirements API
 * Returns visa requirements personalized for a specific passport
 * 
 * Usage:
 *   GET /api/get_personalized_visa_requirements.php?passport=USA
 *   GET /api/get_personalized_visa_requirements.php?passport=IND&destination=GBR
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../includes/config.php';

// Helper function to send JSON response
function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Helper function to send error
function sendError($message, $statusCode = 400) {
    sendResponse(['error' => $message, 'success' => false], $statusCode);
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Only GET requests are allowed', 405);
}

// Get passport parameter (required)
$passportCode = isset($_GET['passport']) ? strtoupper(trim($_GET['passport'])) : null;

if (!$passportCode) {
    sendError('Missing required parameter: passport (e.g., ?passport=USA)');
}

// Get optional destination parameter
$destinationCode = isset($_GET['destination']) ? strtoupper(trim($_GET['destination'])) : null;

try {
    // Get passport country ID
    $stmt = $pdo->prepare("
        SELECT c.id, c.country_code, c.flag_emoji, ct.country_name
        FROM countries c
        JOIN country_translations ct ON c.id = ct.country_id
        WHERE c.country_code = ? AND ct.lang_code = ?
        LIMIT 1
    ");
    $stmt->execute([$passportCode, CURRENT_LANG]);
    $passportCountry = $stmt->fetch();
    
    if (!$passportCountry) {
        sendError("Passport country not found: {$passportCode}", 404);
    }
    
    // If specific destination requested
    if ($destinationCode) {
        $stmt = $pdo->prepare("
            SELECT 
                c.id,
                c.country_code,
                c.flag_emoji,
                ct.country_name,
                TRIM(COALESCE(b.visa_type, c.visa_type)) as visa_type,
                b.duration_days,
                b.cost_usd,
                b.cost_local_currency,
                b.processing_time_days,
                b.requirements_summary,
                b.application_process,
                b.special_notes,
                b.approval_rate_percent,
                b.is_verified,
                b.data_source,
                b.last_updated
            FROM countries c
            JOIN country_translations ct ON c.id = ct.country_id
            LEFT JOIN bilateral_visa_requirements b 
                ON b.from_country_id = ? AND b.to_country_id = c.id
            WHERE c.country_code = ? AND ct.lang_code = ?
            LIMIT 1
        ");
        $stmt->execute([$passportCountry['id'], $destinationCode, CURRENT_LANG]);
        $destination = $stmt->fetch();
        
        if (!$destination) {
            sendError("Destination country not found: {$destinationCode}", 404);
        }
        
        // If no bilateral data exists, fall back to generic visa_type
        if (!$destination['visa_type']) {
            $stmt2 = $pdo->prepare("SELECT visa_type FROM countries WHERE id = ?");
            $stmt2->execute([$destination['id']]);
            $genericData = $stmt2->fetch();
            $destination['visa_type'] = $genericData['visa_type'];
            $destination['is_personalized'] = false;
        } else {
            $destination['is_personalized'] = true;
        }
        
        sendResponse([
            'success' => true,
            'passport' =>  [
                'id' => $passportCountry['id'],
                'code' => $passportCountry['country_code'],
                'name' => $passportCountry['country_name'],
                'flag' => $passportCountry['flag_emoji']
            ],
            'destination' => $destination
        ]);
    }
    
    // Get all personalized visa requirements for this passport
    $stmt = $pdo->prepare("
        SELECT 
            c.id,
            c.country_code,
            c.flag_emoji,
            c.region,
            ct.country_name,
            TRIM(COALESCE(b.visa_type, c.visa_type)) as visa_type,
            b.duration_days,
            b.cost_usd,
            b.cost_local_currency,
            b.processing_time_days,
            b.requirements_summary,
            b.application_process,
            b.special_notes,
            b.approval_rate_percent,
            b.is_verified,
            CASE 
                WHEN b.id IS NOT NULL THEN TRUE 
                ELSE FALSE 
            END as is_personalized
        FROM countries c
        JOIN country_translations ct ON c.id = ct.country_id
        LEFT JOIN bilateral_visa_requirements b 
            ON b.from_country_id = ? AND b.to_country_id = c.id
        WHERE c.id != ? AND ct.lang_code = ?
        ORDER BY ct.country_name
    ");
    $stmt->execute([$passportCountry['id'], $passportCountry['id'], CURRENT_LANG]);
    $destinations = $stmt->fetchAll();
    
    // Calculate statistics
    $stats = [
        'visa_free' => 0,
        'visa_on_arrival' => 0,
        'evisa' => 0,
        'visa_required' => 0,
        'no_entry' => 0,
        'total' => count($destinations),
        'personalized_count' => 0,
        'generic_count' => 0
    ];
    
    foreach ($destinations as $dest) {
        $visaType = $dest['visa_type'];
        if (isset($stats[$visaType])) {
            $stats[$visaType]++;
        }
        
        if ($dest['is_personalized']) {
            $stats['personalized_count']++;
        } else {
            $stats['generic_count']++;
        }
    }
    
    $stats['easy_access'] = $stats['visa_free'] + $stats['visa_on_arrival'];
    
    // Group by region
    $byRegion = [];
    foreach ($destinations as $dest) {
        $region = $dest['region'];
        if (!isset($byRegion[$region])) {
            $byRegion[$region] = [
                'region' => $region,
                'countries' => [],
                'stats' => [
                    'visa_free' => 0,
                    'visa_on_arrival' => 0,
                    'evisa' => 0,
                    'visa_required' => 0,
                    'no_entry' => 0
                ]
            ];
        }
        $byRegion[$region]['countries'][] = $dest;
        $byRegion[$region]['stats'][$dest['visa_type']]++;
    }
    
    sendResponse([
        'success' => true,
        'passport' => [
            'id' => $passportCountry['id'],
            'code' => $passportCountry['country_code'],
            'name' => $passportCountry['country_name'],
            'flag' => $passportCountry['flag_emoji']
        ],
        'statistics' => $stats,
        'destinations' => $destinations,
        'by_region' => array_values($byRegion)
    ]);
    
} catch (PDOException $e) {
    sendError('Database error: ' . $e->getMessage(), 500);
} catch (Exception $e) {
    sendError('Server error: ' . $e->getMessage(), 500);
}
?>
