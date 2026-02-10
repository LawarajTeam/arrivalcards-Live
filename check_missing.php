<?php
require 'includes/config.php';

// Get all unique country codes from database
$stmt = $pdo->query("SELECT DISTINCT country_code FROM countries ORDER BY country_code");
$dbCodes = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $dbCodes[] = $row['country_code'];
}

// Get codes that were successfully updated (from previous run)
$successCodes = ['KH', 'MM', 'BRN', 'BN', 'TLS', 'IND', 'PK', 'BD', 'LKA', 'NPL', 'BTN', 'MDV', 'AFG', 'KAZ', 'KZ', 'UZ', 'KGZ', 'KG', 'TJ', 'TM', 'ARE', 'SAU', 'QAT', 'OMN', 'KWT', 'BHR', 'JOR', 'LBN', 'ISR', 'SYR', 'IRQ', 'IRN', 'YEM', 'TUR', 'GEO', 'GE', 'AZ', 'EGY', 'MAR', 'TUN', 'LBY', 'DZA', 'SDN', 'NGA', 'GHA', 'SEN', 'CIV', 'MLI', 'BFA', 'NER', 'TCD', 'BEN', 'LBR', 'GMB', 'GNB', 'GIN', 'CPV', 'KEN', 'TZA', 'UGA', 'RWA', 'BDI', 'ETH', 'DJI', 'ERI', 'ZAF', 'NAM', 'BWA', 'ZWE', 'MOZ', 'MWI', 'ZMB', 'AGO', 'LSO', 'SWZ', 'COM', 'SYC', 'MUS', 'MDG', 'CMR', 'CAF', 'COG', 'COD', 'GAB', 'GNQ', 'AUS', 'NZL', 'FJI', 'PNG', 'SLB', 'VUT', 'WSM', 'TON', 'KIR', 'PLW', 'FSM', 'MHL'];

echo "=== Missing Descriptions Analysis ===\n\n";
echo "Total DB codes: " . count($dbCodes) . "\n";
echo "Successfully updated: " . count($successCodes) . "\n\n";

$missing = array_diff($dbCodes, $successCodes);

echo "Country codes needing descriptions (" . count($missing) . "):\n";
foreach ($missing as $code) {
    // Get country name
    $stmt = $pdo->prepare("SELECT ct.country_name 
                           FROM countries c
                           JOIN country_translations ct ON c.id = ct.country_id
                           WHERE c.country_code = ? AND ct.lang_code = 'en' LIMIT 1");
    $stmt->execute([$code]);
    $name = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "$code - " . ($name ? $name['country_name'] : 'Unknown') . "\n";
}
