<?php
/**
 * Fix Database Integrity Issues
 * Corrects invalid regions and standardizes to the 7 main continents
 */

require_once __DIR__ . '/includes/config.php';

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Database Fix</title>";
echo "<style>
    body { font-family: 'Segoe UI', Arial, sans-serif; padding: 20px; background: #f5f5f5; max-width: 1200px; margin: 0 auto; }
    h1, h2 { color: #333; }
    .success { color: #10b981; font-weight: bold; }
    .error { color: #ef4444; font-weight: bold; }
    .info { color: #0284c7; font-weight: bold; }
    pre { background: white; padding: 15px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow-x: auto; }
    .section { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; margin: 15px 0; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb; }
    th { background: #f3f4f6; font-weight: 600; }
    .changed { background: #fef3c7; }
</style></head><body>";

echo "<h1>🔧 Database Integrity Fix</h1>";
echo "<p style='color: #64748b;'>Standardizing regions to the 7 main continents...</p>";

$fixes = [];
$errors = [];

// Define region mappings
$regionMappings = [
    // Middle East → Asia
    'Middle East' => [
        'new_region' => 'Asia',
        'countries' => ['QAT', 'KWT', 'BHR', 'OMN', 'SAU', 'LBN', 'IRQ', 'SYR', 'YEM', 'IRN']
    ],
    
    // Caribbean → North America
    'Caribbean' => [
        'new_region' => 'North America',
        'countries' => ['CUB', 'DMA', 'DOM', 'GRD', 'HTI', 'JAM', 'BS', 'BB', 'JM', 'TT']
    ],
    
    // Central America → North America
    'Central America' => [
        'new_region' => 'North America',
        'countries' => ['CRI', 'PAN', 'GTM', 'HND', 'NI', 'SV', 'BZ', 'CR', 'GT', 'HN']
    ],
    
    // Americas → Need to determine North vs South
    'Americas' => [
        'north_america' => ['CU', 'DO', 'JM', 'TT', 'BS', 'BB', 'CR'],
        'south_america' => ['UY', 'PY', 'BO', 'VE']
    ]
];

echo "<div class='section'>";
echo "<h2>Fixing Invalid Regions</h2>";

// Fix Middle East → Asia
echo "<h3>1. Middle East → Asia</h3>";
echo "<table><tr><th>Country Code</th><th>Old Region</th><th>New Region</th><th>Status</th></tr>";
foreach ($regionMappings['Middle East']['countries'] as $countryCode) {
    try {
        $stmt = $pdo->prepare("UPDATE countries SET region = ? WHERE country_code = ?");
        $stmt->execute(['Asia', $countryCode]);
        
        if ($stmt->rowCount() > 0) {
            echo "<tr class='changed'><td>$countryCode</td><td>Middle East</td><td>Asia</td><td class='success'>✓ Updated</td></tr>";
            $fixes[] = "$countryCode: Middle East → Asia";
        } else {
            echo "<tr><td>$countryCode</td><td>Middle East</td><td>Asia</td><td class='info'>ℹ No change needed</td></tr>";
        }
    } catch (Exception $e) {
        echo "<tr><td>$countryCode</td><td>Middle East</td><td>Asia</td><td class='error'>✗ Error</td></tr>";
        $errors[] = "$countryCode: " . $e->getMessage();
    }
}
echo "</table>";

// Fix Caribbean → North America
echo "<h3>2. Caribbean → North America</h3>";
echo "<table><tr><th>Country Code</th><th>Old Region</th><th>New Region</th><th>Status</th></tr>";
foreach ($regionMappings['Caribbean']['countries'] as $countryCode) {
    try {
        $stmt = $pdo->prepare("UPDATE countries SET region = ? WHERE country_code = ?");
        $stmt->execute(['North America', $countryCode]);
        
        if ($stmt->rowCount() > 0) {
            echo "<tr class='changed'><td>$countryCode</td><td>Caribbean</td><td>North America</td><td class='success'>✓ Updated</td></tr>";
            $fixes[] = "$countryCode: Caribbean → North America";
        } else {
            echo "<tr><td>$countryCode</td><td>Caribbean</td><td>North America</td><td class='info'>ℹ No change needed</td></tr>";
        }
    } catch (Exception $e) {
        echo "<tr><td>$countryCode</td><td>Caribbean</td><td>North America</td><td class='error'>✗ Error</td></tr>";
        $errors[] = "$countryCode: " . $e->getMessage();
    }
}
echo "</table>";

// Fix Central America → North America
echo "<h3>3. Central America → North America</h3>";
echo "<table><tr><th>Country Code</th><th>Old Region</th><th>New Region</th><th>Status</th></tr>";
foreach ($regionMappings['Central America']['countries'] as $countryCode) {
    try {
        $stmt = $pdo->prepare("UPDATE countries SET region = ? WHERE country_code = ?");
        $stmt->execute(['North America', $countryCode]);
        
        if ($stmt->rowCount() > 0) {
            echo "<tr class='changed'><td>$countryCode</td><td>Central America</td><td>North America</td><td class='success'>✓ Updated</td></tr>";
            $fixes[] = "$countryCode: Central America → North America";
        } else {
            echo "<tr><td>$countryCode</td><td>Central America</td><td>North America</td><td class='info'>ℹ No change needed</td></tr>";
        }
    } catch (Exception $e) {
        echo "<tr><td>$countryCode</td><td>Central America</td><td>North America</td><td class='error'>✗ Error</td></tr>";
        $errors[] = "$countryCode: " . $e->getMessage();
    }
}
echo "</table>";

// Fix Americas (split between North and South)
echo "<h3>4. Americas → North America / South America</h3>";
echo "<table><tr><th>Country Code</th><th>Old Region</th><th>New Region</th><th>Status</th></tr>";

foreach ($regionMappings['Americas']['north_america'] as $countryCode) {
    try {
        $stmt = $pdo->prepare("UPDATE countries SET region = ? WHERE country_code = ?");
        $stmt->execute(['North America', $countryCode]);
        
        if ($stmt->rowCount() > 0) {
            echo "<tr class='changed'><td>$countryCode</td><td>Americas</td><td>North America</td><td class='success'>✓ Updated</td></tr>";
            $fixes[] = "$countryCode: Americas → North America";
        }
    } catch (Exception $e) {
        echo "<tr><td>$countryCode</td><td>Americas</td><td>North America</td><td class='error'>✗ Error</td></tr>";
        $errors[] = "$countryCode: " . $e->getMessage();
    }
}

foreach ($regionMappings['Americas']['south_america'] as $countryCode) {
    try {
        $stmt = $pdo->prepare("UPDATE countries SET region = ? WHERE country_code = ?");
        $stmt->execute(['South America', $countryCode]);
        
        if ($stmt->rowCount() > 0) {
            echo "<tr class='changed'><td>$countryCode</td><td>Americas</td><td>South America</td><td class='success'>✓ Updated</td></tr>";
            $fixes[] = "$countryCode: Americas → South America";
        }
    } catch (Exception $e) {
        echo "<tr><td>$countryCode</td><td>Americas</td><td>South America</td><td class='error'>✗ Error</td></tr>";
        $errors[] = "$countryCode: " . $e->getMessage();
    }
}
echo "</table>";

echo "</div>";

// Verify the fixes
echo "<div class='section'>";
echo "<h2>Verification</h2>";

$stmt = $pdo->query("SELECT region, COUNT(*) as count FROM countries GROUP BY region ORDER BY region");
$regions = $stmt->fetchAll();

echo "<h3>Updated Region Distribution:</h3>";
echo "<table><tr><th>Region</th><th>Country Count</th><th>Status</th></tr>";

$validRegions = ['Africa', 'Asia', 'Europe', 'North America', 'South America', 'Oceania', 'Antarctica'];

foreach ($regions as $row) {
    $region = $row['region'];
    $count = $row['count'];
    $isValid = in_array($region, $validRegions);
    $status = $isValid ? "<span class='success'>✓ Valid</span>" : "<span class='error'>✗ Invalid</span>";
    
    echo "<tr><td><strong>$region</strong></td><td>$count</td><td>$status</td></tr>";
}
echo "</table>";

// Check for remaining invalid regions
$stmt = $pdo->prepare("SELECT COUNT(*) FROM countries WHERE region NOT IN ('" . implode("','", $validRegions) . "')");
$stmt->execute();
$invalidCount = $stmt->fetchColumn();

echo "</div>";

// Summary
echo "<div class='section'>";
echo "<h2>📊 Summary</h2>";

if ($invalidCount == 0 && empty($errors)) {
    echo "<div style='padding: 20px; background: #d1fae5; border-radius: 8px; border-left: 4px solid #10b981;'>";
    echo "<h3 style='color: #065f46; margin-top: 0;'>✅ All Fixes Applied Successfully!</h3>";
    echo "<p style='color: #047857;'>Total fixes applied: <strong>" . count($fixes) . "</strong></p>";
    echo "<p style='color: #047857;'>All regions are now standardized to the 7 main continents.</p>";
    echo "</div>";
} else {
    if ($invalidCount > 0) {
        echo "<div style='padding: 20px; background: #fee2e2; border-radius: 8px; border-left: 4px solid #ef4444; margin: 20px 0;'>";
        echo "<h3 style='color: #991b1b;'>⚠️ Warning: $invalidCount countries still have invalid regions</h3>";
        echo "</div>";
    }
    
    if (!empty($errors)) {
        echo "<div style='padding: 20px; background: #fee2e2; border-radius: 8px; border-left: 4px solid #ef4444; margin: 20px 0;'>";
        echo "<h3 style='color: #991b1b;'>❌ Errors Encountered:</h3>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li style='color: #991b1b;'>$error</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}

echo "<h3>Changes Made:</h3>";
echo "<ul>";
foreach ($fixes as $fix) {
    echo "<li>$fix</li>";
}
echo "</ul>";

echo "<p style='margin-top: 30px;'><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Run <code>quick_db_check.php</code> to verify all regions are now valid</li>";
echo "<li>Test homepage region filters</li>";
echo "<li>Test visa type filters</li>";
echo "<li>Verify country pages load correctly</li>";
echo "</ol>";

echo "</div>";

echo "<p style='text-align: center; color: #64748b; margin-top: 40px;'>";
echo "Fix completed: " . date('F j, Y g:i A');
echo "</p>";

echo "</body></html>";
