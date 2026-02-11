<?php
/**
 * Check Oceania Countries - Verify completeness
 */

require_once __DIR__ . '/includes/config.php';

echo "<pre>";
echo "OCEANIA COUNTRIES CHECK\n";
echo str_repeat("=", 70) . "\n\n";

// Get all Oceania countries currently in database
$stmt = $pdo->query("
    SELECT c.country_code, ct.country_name, c.region, c.visa_type
    FROM countries c
    LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
    WHERE c.region = 'Oceania'
    ORDER BY c.country_code
");
$oceaniaCountries = $stmt->fetchAll();

echo "Current Oceania Countries in Database: " . count($oceaniaCountries) . "\n";
echo str_repeat("-", 70) . "\n";
foreach ($oceaniaCountries as $country) {
    printf("%-5s - %-30s (Visa: %s)\n", 
        $country['country_code'], 
        $country['country_name'] ?? 'No translation', 
        $country['visa_type']
    );
}

echo "\n\nExpected Oceania Countries (14):\n";
echo str_repeat("-", 70) . "\n";

$expectedOceania = [
    'AUS' => 'Australia',
    'NZL' => 'New Zealand',
    'PNG' => 'Papua New Guinea',
    'FJI' => 'Fiji',
    'SLB' => 'Solomon Islands',
    'VUT' => 'Vanuatu',
    'WSM' => 'Samoa',
    'TON' => 'Tonga',
    'KIR' => 'Kiribati',
    'FSM' => 'Micronesia (Federated States of)',
    'MHL' => 'Marshall Islands',
    'PLW' => 'Palau',
    'NRU' => 'Nauru',
    'TUV' => 'Tuvalu'
];

$foundCodes = array_column($oceaniaCountries, 'country_code');

foreach ($expectedOceania as $code => $name) {
    // Try different code variations
    $found = false;
    $actualCode = null;
    
    foreach ($foundCodes as $foundCode) {
        if (strtoupper($foundCode) === strtoupper($code) || 
            strtoupper($foundCode) === strtoupper(substr($code, 0, 2)) ||
            strtoupper(substr($foundCode, 0, 3)) === strtoupper(substr($code, 0, 3))) {
            $found = true;
            $actualCode = $foundCode;
            break;
        }
    }
    
    if ($found) {
        echo "✅ $code ($actualCode) - $name\n";
    } else {
        echo "❌ $code - $name (MISSING or WRONG REGION)\n";
    }
}

// Check if Nauru and Tuvalu exist in database but in wrong region
echo "\n\nSearching for Missing Countries in Other Regions:\n";
echo str_repeat("-", 70) . "\n";

$missingCodes = ['NRU', 'TUV', 'NR', 'TV', 'NAU', 'TUV'];

foreach ($missingCodes as $code) {
    $stmt = $pdo->prepare("
        SELECT c.country_code, ct.country_name, c.region
        FROM countries c
        LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
        WHERE c.country_code LIKE ?
    ");
    $stmt->execute([$code . '%']);
    $result = $stmt->fetch();
    
    if ($result) {
        echo "Found: {$result['country_code']} - {$result['country_name']} in region: {$result['region']}\n";
    }
}

// Search by name
echo "\n\nSearching by Name:\n";
echo str_repeat("-", 70) . "\n";

$searchNames = ['Nauru', 'Tuvalu'];
foreach ($searchNames as $name) {
    $stmt = $pdo->prepare("
        SELECT c.country_code, ct.country_name, c.region
        FROM countries c
        LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
        WHERE ct.country_name LIKE ?
    ");
    $stmt->execute(['%' . $name . '%']);
    $results = $stmt->fetchAll();
    
    if (!empty($results)) {
        foreach ($results as $result) {
            echo "Found: {$result['country_code']} - {$result['country_name']} in region: {$result['region']}\n";
        }
    } else {
        echo "❌ '$name' NOT FOUND in database\n";
    }
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "\nSUMMARY:\n";
echo "Current count: " . count($oceaniaCountries) . "\n";
echo "Expected count: 14\n";
echo "Missing: " . (14 - count($oceaniaCountries)) . "\n";

echo "</pre>";
