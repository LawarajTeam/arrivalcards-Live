<?php
/**
 * Get All Countries API
 * Returns list of all countries with codes for passport selector
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../includes/config.php';

try {
    $stmt = $pdo->prepare("
        SELECT 
            c.id,
            c.country_code,
            c.flag_emoji,
            ct.country_name
        FROM countries c
        JOIN country_translations ct ON c.id = ct.country_id
        WHERE ct.lang_code = ? AND c.is_active = 1
        ORDER BY ct.country_name
    ");
    $stmt->execute([CURRENT_LANG]);
    $countries = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'countries' => $countries,
        'total' => count($countries)
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error'
    ]);
}
?>
