<?php
/**
 * TASK 5: Spot-Check Visa Data for Popular Countries
 */

require 'includes/config.php';

echo "========================================\n";
echo "   SPOT-CHECK: POPULAR COUNTRIES        \n";
echo "========================================\n\n";

// Popular countries to check
$checkCountries = ['USA', 'GBR', 'FRA', 'DEU', 'ESP', 'ITA', 'CAN', 'AUS', 'JPN', 'THA'];

echo "Checking visa data for " . count($checkCountries) . " popular destinations...\n\n";

foreach ($checkCountries as $code) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $stmt = $pdo->prepare("
        SELECT 
            ct.country_name,
            c.visa_type,
            ct.visa_duration,
            ct.passport_validity,
            ct.visa_fee,
            ct.processing_time,
            ct.official_visa_url,
            ct.arrival_card_required,
            LENGTH(ct.visa_requirements) as req_length,
            LENGTH(ct.additional_docs) as docs_length
        FROM countries c
        JOIN country_translations ct ON c.id = ct.country_id
        WHERE c.country_code = ? AND ct.lang_code = 'en'
    ");
    $stmt->execute([$code]);
    $data = $stmt->fetch();
    
    if ($data) {
        echo "🌍 {$data['country_name']} ($code)\n";
        echo "   Visa Type: {$data['visa_type']}\n";
        echo "   Duration: {$data['visa_duration']}\n";
        echo "   Fee: {$data['visa_fee']}\n";
        echo "   Processing: {$data['processing_time']}\n";
        echo "   Passport: {$data['passport_validity']}\n";
        echo "   Arrival Card: {$data['arrival_card_required']}\n";
        echo "   Official URL: " . substr($data['official_visa_url'], 0, 50) . "...\n";
        echo "   Requirements: {$data['req_length']} chars\n";
        echo "   Documents: {$data['docs_length']} chars\n";
        
        // Check if data looks reasonable
        $issues = [];
        if ($data['req_length'] < 100) $issues[] = "Requirements text too short";
        if ($data['docs_length'] < 20) $issues[] = "Documents missing";
        if (empty($data['visa_duration'])) $issues[] = "Duration missing";
        if (empty($data['visa_fee'])) $issues[] = "Fee missing";
        
        if (count($issues) > 0) {
            echo "   ⚠️  Issues: " . implode(", ", $issues) . "\n";
        } else {
            echo "   ✅ All fields populated\n";
        }
    } else {
        echo "❌ $code - NOT FOUND\n";
    }
    
    echo "\n";
}

echo "========================================\n";
echo "SPOT-CHECK COMPLETE\n";
echo "========================================\n";
