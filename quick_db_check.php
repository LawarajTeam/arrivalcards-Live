<?php
/**
 * Quick Database Check - Show actual issues
 */

require_once __DIR__ . '/includes/config.php';

echo "<pre>";
echo "QUICK DATABASE INTEGRITY CHECK\n";
echo str_repeat("=", 70) . "\n\n";

// Check for invalid regions
echo "1. INVALID REGIONS CHECK:\n";
echo str_repeat("-", 70) . "\n";
$validRegions = ['Africa', 'Asia', 'Europe', 'North America', 'South America', 'Oceania', 'Antarctica'];
$stmt = $pdo->prepare("SELECT country_code, region FROM countries WHERE region NOT IN ('" . implode("','", $validRegions) . "') OR region IS NULL");
$stmt->execute();
$invalidRegions = $stmt->fetchAll();

if (empty($invalidRegions)) {
    echo "✅ All regions are valid!\n";
} else {
    echo "❌ Found " . count($invalidRegions) . " countries with invalid regions:\n";
    foreach ($invalidRegions as $country) {
        $region = $country['region'] ?? 'NULL';
        echo "  - {$country['country_code']}: $region\n";
    }
}

// Check for invalid visa types
echo "\n2. INVALID VISA TYPES CHECK:\n";
echo str_repeat("-", 70) . "\n";
$validVisaTypes = ['visa_free', 'visa_on_arrival', 'evisa', 'visa_required', 'restricted'];
$stmt = $pdo->prepare("SELECT country_code, visa_type FROM countries WHERE visa_type NOT IN ('" . implode("','", $validVisaTypes) . "') OR visa_type IS NULL");
$stmt->execute();
$invalidVisaTypes = $stmt->fetchAll();

if (empty($invalidVisaTypes)) {
    echo "✅ All visa types are valid!\n";
} else {
    echo "❌ Found " . count($invalidVisaTypes) . " countries with invalid visa types:\n";
    foreach ($invalidVisaTypes as $country) {
        $visaType = $country['visa_type'] ?? 'NULL';
        echo "  - {$country['country_code']}: $visaType\n";
    }
}

// Check region distribution
echo "\n3. REGION DISTRIBUTION:\n";
echo str_repeat("-", 70) . "\n";
$stmt = $pdo->query("SELECT region, COUNT(*) as count FROM countries GROUP BY region ORDER BY region");
$regions = $stmt->fetchAll();
foreach ($regions as $row) {
    printf("%-20s: %3d countries\n", $row['region'], $row['count']);
}

// Check visa type distribution
echo "\n4. VISA TYPE DISTRIBUTION:\n";
echo str_repeat("-", 70) . "\n";
$stmt = $pdo->query("SELECT visa_type, COUNT(*) as count FROM countries GROUP BY visa_type ORDER BY count DESC");
$visaTypes = $stmt->fetchAll();
foreach ($visaTypes as $row) {
    $type = $row['visa_type'] ?? 'NULL';
    printf("%-20s: %3d countries\n", $type, $row['count']);
}

// Check total countries
echo "\n5. SUMMARY:\n";
echo str_repeat("-", 70) . "\n";
$stmt = $pdo->query("SELECT COUNT(*) as total FROM countries");
$total = $stmt->fetchColumn();
echo "Total Countries: $total\n";

$stmt = $pdo->query("SELECT COUNT(DISTINCT region) as regions FROM countries");
$regionCount = $stmt->fetchColumn();
echo "Unique Regions: $regionCount\n";

$stmt = $pdo->query("SELECT COUNT(DISTINCT visa_type) as types FROM countries");
$typeCount = $stmt->fetchColumn();
echo "Unique Visa Types: $typeCount\n";

echo "\n" . str_repeat("=", 70) . "\n";

if (empty($invalidRegions) && empty($invalidVisaTypes)) {
    echo "✅ DATABASE INTEGRITY: EXCELLENT\n";
} else {
    echo "❌ DATABASE INTEGRITY: NEEDS FIXING\n";
    echo "\nRun fix_database_integrity.php to automatically correct these issues.\n";
}

echo "</pre>";
