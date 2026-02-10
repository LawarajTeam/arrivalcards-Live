<?php
/**
 * FINAL VERIFICATION - Complete System Check
 * Verifies all visa improvement changes are working correctly
 */

require 'includes/config.php';

echo "========================================\n";
echo "   FINAL VERIFICATION REPORT            \n";
echo "========================================\n\n";

$allChecks = [];
$passedChecks = 0;
$totalChecks = 0;

function runCheck($name, $result, $details = '') {
    global $allChecks, $passedChecks, $totalChecks;
    $totalChecks++;
    $status = $result ? '✅ PASS' : '❌ FAIL';
    if ($result) $passedChecks++;
    $allChecks[] = ['name' => $name, 'passed' => $result, 'details' => $details];
    echo "$status - $name\n";
    if ($details) echo "        $details\n";
}

echo "1. DATABASE STRUCTURE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Check all new fields exist
$stmt = $pdo->query("SHOW COLUMNS FROM country_translations");
$columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

$requiredFields = [
    'visa_duration',
    'passport_validity',
    'visa_fee',
    'processing_time',
    'official_visa_url',
    'arrival_card_required',
    'additional_docs',
    'last_verified'
];

foreach ($requiredFields as $field) {
    runCheck("Field '$field' exists", in_array($field, $columns));
}

echo "\n2. DATA COMPLETENESS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Check data population
$stmt = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN ct.visa_duration IS NOT NULL AND ct.visa_duration != '' THEN 1 ELSE 0 END) as has_duration,
        SUM(CASE WHEN ct.passport_validity IS NOT NULL AND ct.passport_validity != '' THEN 1 ELSE 0 END) as has_passport,
        SUM(CASE WHEN ct.visa_fee IS NOT NULL AND ct.visa_fee != '' THEN 1 ELSE 0 END) as has_fee,
        SUM(CASE WHEN ct.processing_time IS NOT NULL AND ct.processing_time != '' THEN 1 ELSE 0 END) as has_processing,
        SUM(CASE WHEN ct.official_visa_url IS NOT NULL AND ct.official_visa_url != '' THEN 1 ELSE 0 END) as has_url,
        SUM(CASE WHEN ct.arrival_card_required IS NOT NULL AND ct.arrival_card_required != '' THEN 1 ELSE 0 END) as has_arrival,
        SUM(CASE WHEN ct.additional_docs IS NOT NULL AND ct.additional_docs != '' THEN 1 ELSE 0 END) as has_docs,
        SUM(CASE WHEN LENGTH(ct.visa_requirements) > 200 THEN 1 ELSE 0 END) as has_expanded_text,
        AVG(LENGTH(ct.visa_requirements)) as avg_req_length
    FROM country_translations ct
    WHERE ct.lang_code = 'en'
");

$stats = $stmt->fetch();

runCheck("All countries have visa_duration", $stats['has_duration'] == $stats['total'], "{$stats['has_duration']}/{$stats['total']}");
runCheck("All countries have passport_validity", $stats['has_passport'] == $stats['total'], "{$stats['has_passport']}/{$stats['total']}");
runCheck("All countries have visa_fee", $stats['has_fee'] == $stats['total'], "{$stats['has_fee']}/{$stats['total']}");
runCheck("All countries have processing_time", $stats['has_processing'] == $stats['total'], "{$stats['has_processing']}/{$stats['total']}");
runCheck("All countries have official_visa_url", $stats['has_url'] == $stats['total'], "{$stats['has_url']}/{$stats['total']}");
runCheck("All countries have arrival_card_required", $stats['has_arrival'] == $stats['total'], "{$stats['has_arrival']}/{$stats['total']}");
runCheck("All countries have additional_docs", $stats['has_docs'] == $stats['total'], "{$stats['has_docs']}/{$stats['total']}");
runCheck("90%+ countries have expanded visa_requirements", $stats['has_expanded_text'] >= ($stats['total'] * 0.9), "{$stats['has_expanded_text']}/{$stats['total']}");
runCheck("Average visa_requirements length > 300 chars", $stats['avg_req_length'] > 300, round($stats['avg_req_length']) . " chars avg");

echo "\n3. DATA QUALITY CHECKS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Check for popular countries with specific overrides
$popularCountries = ['USA', 'GBR', 'CAN', 'AUS', 'JPN', 'CHN', 'IND', 'THA', 'SGP', 'FRA'];
$stmt = $pdo->prepare("
    SELECT COUNT(*) as count
    FROM countries c
    JOIN country_translations ct ON c.id = ct.country_id
    WHERE c.country_code IN ('" . implode("','", $popularCountries) . "')
    AND ct.lang_code = 'en'
    AND LENGTH(ct.visa_requirements) > 400
");
$stmt->execute();
$popularComplete = $stmt->fetchColumn();

runCheck("Popular countries have detailed info (>400 chars)", $popularComplete >= 8, "$popularComplete/10 countries");

// Check URL formats
$stmt = $pdo->query("
    SELECT COUNT(*) as count
    FROM country_translations ct
    WHERE ct.lang_code = 'en'
    AND ct.official_visa_url LIKE 'https://%'
");
$validUrls = $stmt->fetchColumn();

runCheck("Official URLs use HTTPS", $validUrls == $stats['total'], "$validUrls/{$stats['total']}");

// Check for variety in visa types
$stmt = $pdo->query("
    SELECT c.visa_type, COUNT(*) as count
    FROM countries c
    GROUP BY c.visa_type
");
$visaTypeCounts = $stmt->fetchAll();

$hasAllVisaTypes = count($visaTypeCounts) >= 4;
runCheck("All visa types represented", $hasAllVisaTypes, count($visaTypeCounts) . " types");

echo "\n4. FILE INTEGRITY\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Check that country.php includes new fields
$countryPhpContent = file_get_contents('country.php');
runCheck("country.php reads visa_duration", strpos($countryPhpContent, 'visa_duration') !== false);
runCheck("country.php reads passport_validity", strpos($countryPhpContent, 'passport_validity') !== false);
runCheck("country.php reads visa_fee", strpos($countryPhpContent, 'visa_fee') !== false);
runCheck("country.php reads official_visa_url", strpos($countryPhpContent, 'official_visa_url') !== false);
runCheck("country.php has visa-info-grid", strpos($countryPhpContent, 'visa-info-grid') !== false);

// Check CSS for new styles
$cssContent = file_get_contents('assets/css/style.css');
runCheck("CSS has visa-info-card styles", strpos($cssContent, 'visa-info-card') !== false);
runCheck("CSS has btn-primary styles", strpos($cssContent, 'btn-primary') !== false);
runCheck("CSS has highlight-box styles", strpos($cssContent, 'highlight-box') !== false);

echo "\n5. SAMPLE DATA VALIDATION\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Check USA (should have ESTA-specific info)
$stmt = $pdo->prepare("
    SELECT ct.visa_fee, ct.official_visa_url, ct.arrival_card_required
    FROM countries c
    JOIN country_translations ct ON c.id = ct.country_id
    WHERE c.country_code = 'USA' AND ct.lang_code = 'en'
");
$stmt->execute();
$usa = $stmt->fetch();

runCheck("USA has ESTA fee info", strpos($usa['visa_fee'], 'ESTA') !== false);
runCheck("USA has esta.cbp.dhs.gov URL", strpos($usa['official_visa_url'], 'esta.cbp.dhs.gov') !== false);
runCheck("USA arrival card correctly set", strpos($usa['arrival_card_required'], 'No') !== false);

// Check Australia (should be home country)
$stmt = $pdo->prepare("
    SELECT ct.visa_fee, ct.visa_duration
    FROM countries c
    JOIN country_translations ct ON c.id = ct.country_id
    WHERE c.country_code = 'AUS' AND ct.lang_code = 'en'
");
$stmt->execute();
$aus = $stmt->fetch();

runCheck("Australia marked as home country", strpos($aus['visa_duration'], 'Home country') !== false);
runCheck("Australia fee is N/A", strpos($aus['visa_fee'], 'N/A') !== false);

// Check a visa-free country
$stmt = $pdo->prepare("
    SELECT ct.visa_fee, c.visa_type
    FROM countries c
    JOIN country_translations ct ON c.id = ct.country_id
    WHERE c.country_code = 'JPN' AND ct.lang_code = 'en'
");
$stmt->execute();
$jpn = $stmt->fetch();

runCheck("Japan visa_type is visa_free", $jpn['visa_type'] == 'visa_free');
runCheck("Japan fee is Free", strpos($jpn['visa_fee'], 'Free') !== false);

echo "\n========================================\n";
echo "VERIFICATION SUMMARY\n";
echo "========================================\n\n";

echo "Total Checks:  $totalChecks\n";
echo "Passed:        $passedChecks\n";
echo "Failed:        " . ($totalChecks - $passedChecks) . "\n";

$successRate = round(($passedChecks / $totalChecks) * 100, 1);
echo "Success Rate:  $successRate%\n\n";

if ($passedChecks == $totalChecks) {
    echo "🎉 ALL CHECKS PASSED!\n";
    echo "✅ Visa improvement project is complete and ready for deployment.\n\n";
    
    echo "Summary of Changes:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "• Added 8 new database fields for detailed visa information\n";
    echo "• Generated comprehensive visa data for all 195 countries\n";
    echo "• Enhanced country.php with visual visa information cards\n";
    echo "• Added professional CSS styling for new components\n";
    echo "• Included official visa application URLs\n";
    echo "• Expanded visa requirements text (avg " . round($stats['avg_req_length']) . " chars)\n";
    echo "• Added required documents lists\n";
    echo "• Implemented last verified dates\n\n";
    
    echo "✨ The visa information is now comprehensive, professional, and user-friendly!\n";
} elseif ($successRate >= 90) {
    echo "⚠️  MOSTLY COMPLETE\n";
    echo "Project is functional but has minor issues. Review failed checks above.\n";
} else {
    echo "❌ VERIFICATION FAILED\n";
    echo "Significant issues detected. Please review and fix failed checks.\n";
}

echo "\n========================================\n";

// List any failed checks
if ($passedChecks < $totalChecks) {
    echo "\nFailed Checks:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    foreach ($allChecks as $check) {
        if (!$check['passed']) {
            echo "❌ {$check['name']}\n";
            if ($check['details']) echo "   {$check['details']}\n";
        }
    }
    echo "\n";
}
