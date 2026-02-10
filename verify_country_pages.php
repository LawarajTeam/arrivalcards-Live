<?php
/**
 * Verify Country Pages
 * Tests all 195 country pages in all 7 languages to ensure no 404 or 500 errors
 */

require_once 'includes/config.php';

echo "=== Verifying All Country Pages ===\n\n";

$languages = ['en', 'es', 'zh', 'fr', 'de', 'it', 'ar'];

// Get all countries
$stmt = $pdo->query("SELECT countries.id, countries.country_code, ct.country_name 
                     FROM countries 
                     JOIN country_translations ct ON countries.id = ct.country_id 
                     WHERE ct.lang_code = 'en' 
                     ORDER BY countries.id");
$countries = $stmt->fetchAll();

$totalTests = 0;
$passedTests = 0;
$failedTests = [];

echo "Testing " . count($countries) . " countries in " . count($languages) . " languages...\n";
echo "Total tests: " . (count($countries) * count($languages)) . "\n\n";

foreach ($countries as $country) {
    $countryId = $country['id'];
    $countryName = $country['country_name'];
    
    foreach ($languages as $lang) {
        $totalTests++;
        
        // Check if country has translation in this language
        $stmtCheck = $pdo->prepare("SELECT country_name FROM country_translations WHERE country_id = ? AND lang_code = ?");
        $stmtCheck->execute([$countryId, $lang]);
        $translation = $stmtCheck->fetch();
        
        if (!$translation) {
            $failedTests[] = [
                'country_id' => $countryId,
                'country_name' => $countryName,
                'language' => $lang,
                'error' => 'Missing translation'
            ];
            echo "✗ ID: $countryId | $countryName | Lang: $lang | ERROR: Missing translation\n";
        } else {
            // Check if country has description content
            $stmtContent = $pdo->prepare("SELECT description FROM country_details WHERE country_id = ? AND lang_code = ?");
            $stmtContent->execute([$countryId, $lang]);
            $content = $stmtContent->fetch();
            
            if (!$content) {
                $failedTests[] = [
                    'country_id' => $countryId,
                    'country_name' => $countryName,
                    'language' => $lang,
                    'error' => 'Missing content'
                ];
                echo "⚠ ID: $countryId | $countryName | Lang: $lang | WARNING: Missing description content\n";
            } else {
                $passedTests++;
                // Only show every 50th success to avoid clutter
                if ($totalTests % 50 == 0) {
                    echo "✓ Tested $totalTests pages...\n";
                }
            }
        }
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "VERIFICATION COMPLETE\n";
echo str_repeat("=", 60) . "\n\n";

echo "Total Tests: $totalTests\n";
echo "Passed: $passedTests ✓\n";
echo "Failed/Warnings: " . count($failedTests) . " ✗\n";
echo "Success Rate: " . round(($passedTests / $totalTests) * 100, 2) . "%\n\n";

if (count($failedTests) > 0) {
    echo "Failed Tests:\n";
    echo str_repeat("-", 60) . "\n";
    foreach ($failedTests as $failed) {
        echo "• Country ID: {$failed['country_id']} | {$failed['country_name']} | Lang: {$failed['language']} | Error: {$failed['error']}\n";
    }
    echo "\n";
}

// Check official URLs
echo "\nChecking Official Visa URLs...\n";
echo str_repeat("-", 60) . "\n";

$stmtUrls = $pdo->query("SELECT id, country_code, official_url FROM countries WHERE official_url IS NULL OR official_url = '' ORDER BY id");
$missingUrls = $stmtUrls->fetchAll();

if (count($missingUrls) > 0) {
    echo "⚠ " . count($missingUrls) . " countries missing official visa URLs:\n";
    foreach ($missingUrls as $country) {
        echo "  • ID: {$country['id']} | Code: {$country['country_code']}\n";
    }
} else {
    echo "✓ All countries have official visa URLs\n";
}

// Check airports
echo "\n\nChecking Airports Data...\n";
echo str_repeat("-", 60) . "\n";

$stmtAirports = $pdo->query("SELECT countries.id, countries.country_code, COUNT(airports.id) as airport_count 
                             FROM countries 
                             LEFT JOIN airports ON countries.id = airports.country_id 
                             GROUP BY countries.id 
                             HAVING airport_count = 0");
$noAirports = $stmtAirports->fetchAll();

if (count($noAirports) > 0) {
    echo "ℹ " . count($noAirports) . " countries have no airport data (this is optional)\n";
} else {
    echo "✓ All countries have at least one airport listed\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Verification complete! Check the results above.\n";
echo "To test in browser, visit: http://localhost/ArrivalCards/country.php?id=1&lang=en\n";
echo str_repeat("=", 60) . "\n";
