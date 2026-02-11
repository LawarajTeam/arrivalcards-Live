<?php
/**
 * Comprehensive Database Integrity Check
 * Verifies regions, categories, visa types, and country assignments
 */

require_once __DIR__ . '/includes/config.php';

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Database Integrity Check</title>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
    h1, h2, h3 { color: #333; }
    .section { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .success { color: #10b981; font-weight: bold; }
    .error { color: #ef4444; font-weight: bold; }
    .warning { color: #f59e0b; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f3f4f6; font-weight: bold; }
    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
    .stat-box { background: #e0f2fe; padding: 15px; border-radius: 6px; border-left: 4px solid #0284c7; }
    .stat-value { font-size: 24px; font-weight: bold; color: #0284c7; }
    .stat-label { color: #64748b; font-size: 14px; }
    pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
</style></head><body>";

echo "<h1>🔍 Database Integrity Check - Arrival Cards</h1>";
echo "<p style='color: #64748b;'>Comprehensive verification of regions, categories, visa types, and country data</p>";

$issues = [];
$warnings = [];
$stats = [];

// =====================================================
// 1. CHECK REGIONS
// =====================================================
echo "<div class='section'>";
echo "<h2>1️⃣ Regions Check</h2>";

$validRegions = ['Africa', 'Asia', 'Europe', 'North America', 'South America', 'Oceania', 'Antarctica'];

echo "<h3>Expected Regions (7):</h3>";
echo "<ul>";
foreach ($validRegions as $region) {
    echo "<li>$region</li>";
}
echo "</ul>";

echo "<h3>Countries by Region:</h3>";
$stmt = $pdo->query("SELECT region, COUNT(*) as count FROM countries GROUP BY region ORDER BY count DESC");
$regionCounts = $stmt->fetchAll();

echo "<table><tr><th>Region</th><th>Country Count</th><th>Status</th></tr>";
$totalCountries = 0;
$foundRegions = [];

foreach ($regionCounts as $row) {
    $region = $row['region'];
    $count = $row['count'];
    $totalCountries += $count;
    $foundRegions[] = $region;
    
    $isValid = in_array($region, $validRegions);
    $status = $isValid ? "<span class='success'>✓ Valid</span>" : "<span class='error'>✗ Invalid</span>";
    
    if (!$isValid) {
        $issues[] = "Invalid region found: '$region' ($count countries)";
    }
    
    echo "<tr><td><strong>$region</strong></td><td>$count</td><td>$status</td></tr>";
}
echo "</table>";

// Check for missing regions
$missingRegions = array_diff($validRegions, $foundRegions);
if (!empty($missingRegions)) {
    foreach ($missingRegions as $missing) {
        $warnings[] = "No countries assigned to region: '$missing'";
    }
}

$stats['Total Countries'] = $totalCountries;
$stats['Active Regions'] = count($foundRegions);

// Find countries with invalid regions
$stmt = $pdo->prepare("SELECT country_code, region FROM countries WHERE region NOT IN ('" . implode("','", $validRegions) . "')");
$stmt->execute();
$invalidRegionCountries = $stmt->fetchAll();

if (!empty($invalidRegionCountries)) {
    echo "<h3 class='error'>❌ Countries with Invalid Regions:</h3>";
    echo "<table><tr><th>Country Code</th><th>Invalid Region</th></tr>";
    foreach ($invalidRegionCountries as $country) {
        echo "<tr><td>{$country['country_code']}</td><td>{$country['region']}</td></tr>";
    }
    echo "</table>";
}

echo "</div>";

// =====================================================
// 2. CHECK VISA TYPES
// =====================================================
echo "<div class='section'>";
echo "<h2>2️⃣ Visa Types Check</h2>";

$validVisaTypes = ['visa_free', 'visa_on_arrival', 'evisa', 'visa_required', 'restricted'];

echo "<h3>Expected Visa Types (5):</h3>";
echo "<ul>";
foreach ($validVisaTypes as $type) {
    $label = ucwords(str_replace('_', ' ', $type));
    echo "<li><code>$type</code> - $label</li>";
}
echo "</ul>";

echo "<h3>Countries by Visa Type:</h3>";
$stmt = $pdo->query("SELECT visa_type, COUNT(*) as count FROM countries GROUP BY visa_type ORDER BY count DESC");
$visaTypeCounts = $stmt->fetchAll();

echo "<table><tr><th>Visa Type</th><th>Country Count</th><th>Status</th></tr>";
$foundVisaTypes = [];

foreach ($visaTypeCounts as $row) {
    $visaType = $row['visa_type'];
    $count = $row['count'];
    $foundVisaTypes[] = $visaType;
    
    $isValid = in_array($visaType, $validVisaTypes);
    $status = $isValid ? "<span class='success'>✓ Valid</span>" : "<span class='error'>✗ Invalid</span>";
    
    if (!$isValid) {
        $issues[] = "Invalid visa type found: '$visaType' ($count countries)";
    }
    
    echo "<tr><td><strong>$visaType</strong></td><td>$count</td><td>$status</td></tr>";
}
echo "</table>";

// Find countries with invalid visa types
$stmt = $pdo->prepare("SELECT country_code, visa_type FROM countries WHERE visa_type NOT IN ('" . implode("','", $validVisaTypes) . "') OR visa_type IS NULL");
$stmt->execute();
$invalidVisaTypeCountries = $stmt->fetchAll();

if (!empty($invalidVisaTypeCountries)) {
    echo "<h3 class='error'>❌ Countries with Invalid Visa Types:</h3>";
    echo "<table><tr><th>Country Code</th><th>Invalid Visa Type</th></tr>";
    foreach ($invalidVisaTypeCountries as $country) {
        $visaType = $country['visa_type'] ?? 'NULL';
        echo "<tr><td>{$country['country_code']}</td><td>$visaType</td></tr>";
    }
    echo "</table>";
}

echo "</div>";

// =====================================================
// 3. CHECK COUNTRY DATA COMPLETENESS
// =====================================================
echo "<div class='section'>";
echo "<h2>3️⃣ Country Data Completeness</h2>";

// Check for countries missing essential data
$stmt = $pdo->query("
    SELECT 
        country_code,
        CASE WHEN country_code IS NULL OR country_code = '' THEN 0 ELSE 1 END as has_code,
        CASE WHEN region IS NULL OR region = '' THEN 0 ELSE 1 END as has_region,
        CASE WHEN visa_type IS NULL OR visa_type = '' THEN 0 ELSE 1 END as has_visa_type,
        CASE WHEN capital IS NULL OR capital = '' THEN 0 ELSE 1 END as has_capital,
        CASE WHEN flag_emoji IS NULL OR flag_emoji = '' THEN 0 ELSE 1 END as has_flag
    FROM countries
    ORDER BY country_code
");
$countries = $stmt->fetchAll();

$incomplete = [];
$complete = 0;

foreach ($countries as $country) {
    $missing = [];
    if (!$country['has_code']) $missing[] = 'country_code';
    if (!$country['has_region']) $missing[] = 'region';
    if (!$country['has_visa_type']) $missing[] = 'visa_type';
    if (!$country['has_capital']) $missing[] = 'capital';
    if (!$country['has_flag']) $missing[] = 'flag';
    
    if (!empty($missing)) {
        $incomplete[] = [
            'code' => $country['country_code'],
            'missing' => $missing
        ];
    } else {
        $complete++;
    }
}

echo "<div class='stats'>";
echo "<div class='stat-box'><div class='stat-value'>$complete</div><div class='stat-label'>Complete Countries</div></div>";
echo "<div class='stat-box'><div class='stat-value'>" . count($incomplete) . "</div><div class='stat-label'>Incomplete Countries</div></div>";
echo "</div>";

if (!empty($incomplete)) {
    echo "<h3 class='warning'>⚠️ Countries Missing Essential Data:</h3>";
    echo "<table><tr><th>Country Code</th><th>Missing Fields</th></tr>";
    foreach ($incomplete as $item) {
        echo "<tr><td>{$item['code']}</td><td>" . implode(', ', $item['missing']) . "</td></tr>";
        $warnings[] = "Country {$item['code']} missing: " . implode(', ', $item['missing']);
    }
    echo "</table>";
}

echo "</div>";

// =====================================================
// 4. CHECK TRANSLATIONS
// =====================================================
echo "<div class='section'>";
echo "<h2>4️⃣ Translation Coverage</h2>";

// Get active languages
$stmt = $pdo->query("SELECT code, name FROM languages WHERE is_active = 1 ORDER BY display_order");
$languages = $stmt->fetchAll();

echo "<h3>Active Languages (" . count($languages) . "):</h3>";
echo "<ul>";
foreach ($languages as $lang) {
    echo "<li>{$lang['name']} ({$lang['code']})</li>";
}
echo "</ul>";

// Check translation coverage
$stmt = $pdo->query("
    SELECT 
        l.code as lang_code,
        l.name as lang_name,
        COUNT(DISTINCT ct.country_id) as translated_count,
        (SELECT COUNT(*) FROM countries) as total_countries,
        ROUND(COUNT(DISTINCT ct.country_id) * 100.0 / (SELECT COUNT(*) FROM countries), 1) as coverage_percent
    FROM languages l
    LEFT JOIN country_translations ct ON l.code = ct.lang_code
    WHERE l.is_active = 1
    GROUP BY l.code, l.name
    ORDER BY coverage_percent DESC
");
$translationStats = $stmt->fetchAll();

echo "<h3>Translation Coverage by Language:</h3>";
echo "<table><tr><th>Language</th><th>Translated</th><th>Total</th><th>Coverage</th><th>Status</th></tr>";

foreach ($translationStats as $row) {
    $percent = floatval($row['coverage_percent']);
    $status = $percent >= 95 ? "<span class='success'>✓ Excellent</span>" : 
              ($percent >= 80 ? "<span class='warning'>⚠ Good</span>" : "<span class='error'>✗ Poor</span>");
    
    echo "<tr>";
    echo "<td><strong>{$row['lang_name']}</strong></td>";
    echo "<td>{$row['translated_count']}</td>";
    echo "<td>{$row['total_countries']}</td>";
    echo "<td>{$percent}%</td>";
    echo "<td>$status</td>";
    echo "</tr>";
    
    if ($percent < 95) {
        $warnings[] = "{$row['lang_name']} translation coverage is {$percent}%";
    }
}
echo "</table>";

echo "</div>";

// =====================================================
// 5. WORKFLOW VERIFICATION
// =====================================================
echo "<div class='section'>";
echo "<h2>5️⃣ Workflow Verification</h2>";

echo "<h3>Testing Critical Workflows:</h3>";

$workflowTests = [];

// Test 1: Homepage query (region filter)
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM countries WHERE region = 'Asia'");
    $asiaCount = $stmt->fetchColumn();
    $workflowTests[] = [
        'name' => 'Homepage Region Filter (Asia)',
        'status' => 'success',
        'message' => "✓ Found $asiaCount countries in Asia"
    ];
} catch (Exception $e) {
    $workflowTests[] = [
        'name' => 'Homepage Region Filter',
        'status' => 'error',
        'message' => "✗ Error: " . $e->getMessage()
    ];
    $issues[] = "Homepage region filter failed: " . $e->getMessage();
}

// Test 2: Visa type filter
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM countries WHERE visa_type = 'visa_free'");
    $visaFreeCount = $stmt->fetchColumn();
    $workflowTests[] = [
        'name' => 'Visa Type Filter (Visa Free)',
        'status' => 'success',
        'message' => "✓ Found $visaFreeCount visa-free countries"
    ];
} catch (Exception $e) {
    $workflowTests[] = [
        'name' => 'Visa Type Filter',
        'status' => 'error',
        'message' => "✗ Error: " . $e->getMessage()
    ];
    $issues[] = "Visa type filter failed: " . $e->getMessage();
}

// Test 3: Country detail page query
try {
    $stmt = $pdo->query("
        SELECT c.*, ct.country_name, ct.entry_summary 
        FROM countries c
        LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
        LIMIT 1
    ");
    $testCountry = $stmt->fetch();
    $workflowTests[] = [
        'name' => 'Country Detail Page Query',
        'status' => 'success',
        'message' => "✓ Country page query successful: {$testCountry['country_code']}"
    ];
} catch (Exception $e) {
    $workflowTests[] = [
        'name' => 'Country Detail Page Query',
        'status' => 'error',
        'message' => "✗ Error: " . $e->getMessage()
    ];
    $issues[] = "Country detail page query failed: " . $e->getMessage();
}

// Test 4: Search functionality
try {
    $stmt = $pdo->query("
        SELECT c.country_code, ct.country_name 
        FROM countries c
        JOIN country_translations ct ON c.id = ct.country_id
        WHERE ct.lang_code = 'en' AND ct.country_name LIKE '%United%'
        LIMIT 5
    ");
    $searchResults = $stmt->fetchAll();
    $workflowTests[] = [
        'name' => 'Search Functionality',
        'status' => 'success',
        'message' => "✓ Search working: Found " . count($searchResults) . " results for 'United'"
    ];
} catch (Exception $e) {
    $workflowTests[] = [
        'name' => 'Search Functionality',
        'status' => 'error',
        'message' => "✗ Error: " . $e->getMessage()
    ];
    $issues[] = "Search functionality failed: " . $e->getMessage();
}

echo "<table><tr><th>Workflow Test</th><th>Result</th></tr>";
foreach ($workflowTests as $test) {
    $statusClass = $test['status'];
    echo "<tr><td><strong>{$test['name']}</strong></td><td class='$statusClass'>{$test['message']}</td></tr>";
}
echo "</table>";

echo "</div>";

// =====================================================
// 6. SUMMARY
// =====================================================
echo "<div class='section'>";
echo "<h2>📊 Summary</h2>";

echo "<div class='stats'>";
foreach ($stats as $label => $value) {
    echo "<div class='stat-box'><div class='stat-value'>$value</div><div class='stat-label'>$label</div></div>";
}
echo "</div>";

if (empty($issues) && empty($warnings)) {
    echo "<div style='padding: 20px; background: #d1fae5; border-radius: 8px; border-left: 4px solid #10b981; margin: 20px 0;'>";
    echo "<h3 style='color: #065f46; margin-top: 0;'>✅ All Checks Passed!</h3>";
    echo "<p style='color: #047857;'>Database integrity is excellent. All regions, visa types, and workflows are functioning correctly.</p>";
    echo "</div>";
} else {
    if (!empty($issues)) {
        echo "<div style='padding: 20px; background: #fee2e2; border-radius: 8px; border-left: 4px solid #ef4444; margin: 20px 0;'>";
        echo "<h3 style='color: #991b1b; margin-top: 0;'>❌ Critical Issues Found (" . count($issues) . ")</h3>";
        echo "<ul>";
        foreach ($issues as $issue) {
            echo "<li style='color: #991b1b;'>$issue</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
    
    if (!empty($warnings)) {
        echo "<div style='padding: 20px; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b; margin: 20px 0;'>";
        echo "<h3 style='color: #92400e; margin-top: 0;'>⚠️ Warnings (" . count($warnings) . ")</h3>";
        echo "<ul>";
        foreach ($warnings as $warning) {
            echo "<li style='color: #92400e;'>$warning</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}

echo "<h3>🛠️ Recommended Actions:</h3>";
echo "<ol>";
echo "<li>Run <code>fix_database_integrity.php</code> to automatically fix any issues found</li>";
echo "<li>Review and update countries with invalid regions or visa types</li>";
echo "<li>Complete missing translations for better international coverage</li>";
echo "<li>Verify country data completeness in admin panel</li>";
echo "</ol>";

echo "</div>";

echo "<p style='text-align: center; color: #64748b; margin-top: 40px;'>";
echo "Report generated: " . date('F j, Y g:i A') . " | Total checks performed: " . (count($workflowTests) + 4);
echo "</p>";

echo "</body></html>";
