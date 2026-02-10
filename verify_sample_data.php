<?php
require 'includes/config.php';

// Sample various continents
$samples = ['FRA', 'JPN', 'BRA', 'KEN', 'AUS', 'AM', 'MDG', 'ISL', 'SGP', 'EGY'];

echo "=== Sample Country Data Verification ===\n\n";

foreach ($samples as $code) {
    $stmt = $pdo->prepare("SELECT country_code, capital, population, currency_name, currency_code, currency_symbol, languages, time_zone, calling_code, plug_type FROM countries WHERE country_code = ?");
    $stmt->execute([$code]);
    $country = $stmt->fetch();
    
    $detailsStmt = $pdo->prepare("SELECT known_for FROM country_details WHERE country_id = (SELECT id FROM countries WHERE country_code = ?) AND lang_code = 'en'");
    $detailsStmt->execute([$code]);
    $details = $detailsStmt->fetch();
    
    echo "--- " . $code . " ---\n";
    echo "Capital: " . $country['capital'] . "\n";
    echo "Population: " . $country['population'] . "\n";
    echo "Currency: " . $country['currency_name'] . " (" . $country['currency_code'] . ") " . $country['currency_symbol'] . "\n";
    echo "Languages: " . $country['languages'] . "\n";
    echo "Time Zone: " . $country['time_zone'] . "\n";
    echo "Calling Code: " . $country['calling_code'] . "\n";
    echo "Plug Type: " . $country['plug_type'] . "\n";
    echo "Known For: " . $details['known_for'] . "\n\n";
}
