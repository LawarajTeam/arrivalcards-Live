<?php
require 'includes/config.php';

echo "=== PHASE 1: Database Schema Updates ===\n\n";

// Add new columns to countries table
$alterQueries = [
    "ALTER TABLE countries ADD COLUMN IF NOT EXISTS capital VARCHAR(100) DEFAULT NULL",
    "ALTER TABLE countries ADD COLUMN IF NOT EXISTS population VARCHAR(50) DEFAULT NULL",
    "ALTER TABLE countries ADD COLUMN IF NOT EXISTS currency_name VARCHAR(100) DEFAULT NULL",
    "ALTER TABLE countries ADD COLUMN IF NOT EXISTS currency_code VARCHAR(10) DEFAULT NULL",
    "ALTER TABLE countries ADD COLUMN IF NOT EXISTS currency_symbol VARCHAR(10) DEFAULT NULL",
    "ALTER TABLE countries ADD COLUMN IF NOT EXISTS plug_type VARCHAR(50) DEFAULT NULL",
    "ALTER TABLE countries ADD COLUMN IF NOT EXISTS leader_name VARCHAR(200) DEFAULT NULL",
    "ALTER TABLE countries ADD COLUMN IF NOT EXISTS leader_title VARCHAR(100) DEFAULT NULL",
    "ALTER TABLE countries ADD COLUMN IF NOT EXISTS time_zone VARCHAR(100) DEFAULT NULL",
    "ALTER TABLE countries ADD COLUMN IF NOT EXISTS calling_code VARCHAR(20) DEFAULT NULL",
    "ALTER TABLE countries ADD COLUMN IF NOT EXISTS languages VARCHAR(200) DEFAULT NULL"
];

foreach ($alterQueries as $query) {
    try {
        $pdo->exec($query);
        echo "✓ " . substr($query, 0, 60) . "...\n";
    } catch (PDOException $e) {
        // Column might already exist
        if (strpos($e->getMessage(), 'Duplicate column') === false) {
            echo "✗ Error: " . $e->getMessage() . "\n";
        }
    }
}

// Create country_details table for translatable descriptions
$createCountryDetails = "CREATE TABLE IF NOT EXISTS country_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    country_id INT NOT NULL,
    lang_code VARCHAR(10) NOT NULL,
    description TEXT,
    known_for TEXT,
    travel_tips TEXT,
    UNIQUE KEY unique_country_lang (country_id, lang_code),
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

try {
    $pdo->exec($createCountryDetails);
    echo "\n✓ Created country_details table\n";
} catch (PDOException $e) {
    echo "\n✓ country_details table already exists\n";
}

// Create airports table
$createAirports = "CREATE TABLE IF NOT EXISTS airports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    country_id INT NOT NULL,
    airport_name VARCHAR(200) NOT NULL,
    airport_code VARCHAR(10) NOT NULL,
    city VARCHAR(100) NOT NULL,
    is_main TINYINT(1) DEFAULT 0,
    website_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

try {
    $pdo->exec($createAirports);
    echo "✓ Created airports table\n";
} catch (PDOException $e) {
    echo "✓ airports table already exists\n";
}

echo "\n✅ Database schema updated successfully!\n";
