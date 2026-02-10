<?php
require 'includes/config.php';

// Sample check - what fields exist and what's populated
$stmt = $pdo->query("SELECT country_code, capital, population, currency_name, currency_code, currency_symbol, 
                     plug_type, time_zone, calling_code, languages 
                     FROM countries 
                     WHERE country_code IN ('USA', 'FRA', 'JPN', 'BRA', 'AUS')
                     ORDER BY country_code");

echo "=== Current Data Sample ===\n\n";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Country: {$row['country_code']}\n";
    echo "Capital: " . ($row['capital'] ?: 'NOT SET') . "\n";
    echo "Population: " . ($row['population'] ?: 'NOT SET') . "\n";
    echo "Currency: " . ($row['currency_name'] ?: 'NOT SET') . "\n";
    echo "Languages: " . ($row['languages'] ?: 'NOT SET') . "\n";
    echo "Time Zone: " . ($row['time_zone'] ?: 'NOT SET') . "\n";
    echo "Calling Code: " . ($row['calling_code'] ?: 'NOT SET') . "\n";
    echo "Plug Type: " . ($row['plug_type'] ?: 'NOT SET') . "\n";
    echo "---\n\n";
}

// Check country_details table
$detailsStmt = $pdo->query("SELECT COUNT(*) as count FROM country_details WHERE lang_code = 'en'");
$detailsCount = $detailsStmt->fetch(PDO::FETCH_ASSOC);
echo "Country details records (known_for, etc): {$detailsCount['count']}\n";
