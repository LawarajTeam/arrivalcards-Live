<?php
require 'includes/config.php';

echo "=== DATABASE STRUCTURE - VISA FIELDS ===\n\n";

// Check countries table structure
echo "--- COUNTRIES TABLE ---\n";
$stmt = $pdo->query("DESCRIBE countries");
while ($row = $stmt->fetch()) {
    if (stripos($row['Field'], 'visa') !== false || stripos($row['Field'], 'entry') !== false) {
        echo $row['Field'] . " - " . $row['Type'] . " - " . $row['Null'] . "\n";
    }
}

// Check country_translations table structure
echo "\n--- COUNTRY_TRANSLATIONS TABLE ---\n";
$stmt = $pdo->query("DESCRIBE country_translations");
while ($row = $stmt->fetch()) {
    if (stripos($row['Field'], 'visa') !== false || stripos($row['Field'], 'entry') !== false || stripos($row['Field'], 'requirement') !== false || stripos($row['Field'], 'summary') !== false) {
        echo $row['Field'] . " - " . $row['Type'] . " - " . $row['Null'] . "\n";
    }
}

// Sample 5 countries to see current visa data
echo "\n\n=== SAMPLE VISA DATA (5 countries) ===\n\n";
$stmt = $pdo->query("
    SELECT c.country_code, c.visa_type, 
           ct.country_name, ct.entry_summary, 
           LENGTH(ct.visa_requirements) as req_length,
           SUBSTRING(ct.visa_requirements, 1, 200) as requirements_preview
    FROM countries c
    JOIN country_translations ct ON c.id = ct.country_id
    WHERE ct.lang_code = 'en'
    ORDER BY c.country_code
    LIMIT 5
");

while ($row = $stmt->fetch()) {
    echo "--- " . $row['country_code'] . " - " . $row['country_name'] . " ---\n";
    echo "Visa Type: " . $row['visa_type'] . "\n";
    echo "Entry Summary Length: " . strlen($row['entry_summary']) . " chars\n";
    echo "Visa Requirements Length: " . $row['req_length'] . " chars\n";
    echo "Requirements Preview: " . substr($row['requirements_preview'], 0, 150) . "...\n\n";
}

// Count countries by visa type
echo "\n=== VISA TYPE DISTRIBUTION ===\n\n";
$stmt = $pdo->query("SELECT visa_type, COUNT(*) as count FROM countries GROUP BY visa_type ORDER BY count DESC");
while ($row = $stmt->fetch()) {
    echo $row['visa_type'] . ": " . $row['count'] . " countries\n";
}

// Check for empty or generic visa requirements
echo "\n\n=== DATA QUALITY CHECK ===\n\n";
$emptyRequirements = $pdo->query("
    SELECT COUNT(*) FROM country_translations 
    WHERE lang_code = 'en' AND (visa_requirements IS NULL OR visa_requirements = '' OR LENGTH(visa_requirements) < 50)
")->fetchColumn();
echo "Countries with empty/short visa requirements: $emptyRequirements\n";

$genericText = $pdo->query("
    SELECT COUNT(*) FROM country_translations 
    WHERE lang_code = 'en' AND visa_requirements LIKE '%Check with%'
")->fetchColumn();
echo "Countries with generic 'Check with' text: $genericText\n";
