<?php
/**
 * VISA DATA IMPROVEMENT - PHASE 1 STARTER
 * Database Schema Enhancement Script
 */

require 'includes/config.php';

echo "=== VISA DATA IMPROVEMENT INITIATIVE ===\n\n";
echo "This script will prepare the database for comprehensive visa information.\n\n";

// Check current schema
echo "--- Current Schema Check ---\n";
$stmt = $pdo->query("SHOW COLUMNS FROM country_translations WHERE Field LIKE '%visa%' OR Field LIKE '%entry%'");
$existing = [];
while ($row = $stmt->fetch()) {
    $existing[] = $row['Field'];
    echo "✓ " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\n--- Proposed New Fields ---\n";
$newFields = [
    'visa_duration' => 'VARCHAR(100) - e.g., "90 days", "30 days", "6 months"',
    'passport_validity' => 'VARCHAR(100) - e.g., "6 months beyond stay"',
    'visa_fee' => 'VARCHAR(100) - e.g., "Free", "$50 USD", "€60"',
    'processing_time' => 'VARCHAR(100) - e.g., "Instant", "3-5 business days"',
    'official_visa_url' => 'VARCHAR(500) - Link to government visa portal',
    'arrival_card_required' => 'VARCHAR(50) - "Yes", "No", "Online only"',
    'additional_docs' => 'TEXT - List of required documents',
    'last_verified' => 'DATE - Last time data was verified'
];

foreach ($newFields as $field => $description) {
    echo "• $field: $description\n";
}

echo "\n--- Implementation Plan Summary ---\n\n";
echo "PHASE 1: Database Setup (This script + manual additions)\n";
echo "  ✓ Add new fields to country_translations table\n";
echo "  ✓ Create research tracking table\n";
echo "  ✓ Set up data entry template\n\n";

echo "PHASE 2: Research (195 countries)\n";
echo "  • Visa-Free: 90 countries - Simple research\n";
echo "  • E-Visa: 32 countries - Medium complexity\n";
echo "  • Visa on Arrival: 26 countries - Medium complexity\n";
echo "  • Visa Required: 47 countries - Most complex\n\n";

echo "PHASE 3: Data Entry & Verification\n";
echo "  • Enter all researched data\n";
echo "  • Standardize formatting\n";
echo "  • Quality check\n\n";

echo "PHASE 4: UI Enhancement\n";
echo "  • Update country.php display\n";
echo "  • Add visual elements\n";
echo "  • Mobile optimization\n\n";

echo "--- Priority Countries (Start Research Here) ---\n";
$priority = [
    'USA', 'GBR', 'FRA', 'DEU', 'ESP', 'ITA', 'CAN', 'AUS', 'JPN', 'CHN',
    'THA', 'MEX', 'BRA', 'ARE', 'SGP', 'IND', 'TUR', 'GRC', 'NLD', 'CHE'
];

echo "Top 20 most visited: " . implode(', ', $priority) . "\n\n";

// Show sample of what current data looks like
echo "--- Current Visa Data Sample (Before Improvement) ---\n\n";
$stmt = $pdo->prepare("
    SELECT c.country_code, ct.country_name, c.visa_type, ct.visa_requirements
    FROM countries c
    JOIN country_translations ct ON c.id = ct.country_id
    WHERE ct.lang_code = 'en' AND c.country_code IN ('USA', 'FRA', 'THA', 'JPN', 'ARE')
    ORDER BY c.country_code
");
$stmt->execute();

while ($row = $stmt->fetch()) {
    echo $row['country_code'] . " - " . $row['country_name'] . " [" . $row['visa_type'] . "]\n";
    echo "Current: " . $row['visa_requirements'] . "\n\n";
}

// Create research tracking table
echo "\n--- Creating Research Progress Tracker ---\n";
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS visa_research_progress (
            id INT AUTO_INCREMENT PRIMARY KEY,
            country_code VARCHAR(3) NOT NULL,
            research_status ENUM('not_started', 'in_progress', 'researched', 'verified', 'completed') DEFAULT 'not_started',
            researcher_notes TEXT,
            sources_used TEXT,
            last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            verified_by VARCHAR(100),
            UNIQUE KEY(country_code)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Created visa_research_progress table\n";
    
    // Initialize all countries
    $countries = $pdo->query("SELECT country_code FROM countries")->fetchAll(PDO::FETCH_COLUMN);
    $stmt = $pdo->prepare("INSERT IGNORE INTO visa_research_progress (country_code) VALUES (?)");
    $inserted = 0;
    foreach ($countries as $code) {
        $stmt->execute([$code]);
        $inserted += $stmt->rowCount();
    }
    echo "✓ Initialized tracking for $inserted countries\n";
    
} catch (PDOException $e) {
    echo "Note: " . $e->getMessage() . "\n";
}

echo "\n=== NEXT STEPS ===\n\n";
echo "1. Review VISA_IMPROVEMENT_PLAN.md for full details\n";
echo "2. If approved, run database migration to add new fields:\n";
echo "   php add_visa_fields_migration.php\n\n";
echo "3. Begin research using template at: visa_research_template.txt\n\n";
echo "4. Track progress in: visa_research_progress table\n\n";
echo "5. Start with priority countries for maximum impact\n\n";

echo "Estimated total time: 80-95 hours over 10 weeks\n";
echo "Expected result: Professional-grade visa information for all 195 countries\n\n";
