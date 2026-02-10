<?php
require 'includes/config.php';

// Check for countries still with placeholder data
$stmt = $pdo->query("SELECT country_code, capital, population, languages, time_zone, calling_code, plug_type FROM countries WHERE capital = 'Capital City' OR time_zone = 'UTC+0' OR calling_code = '+000' OR plug_type = 'Various' ORDER BY country_code");

echo "=== Countries with placeholder data remaining ===\n\n";
$count = 0;
while ($row = $stmt->fetch()) {
    echo $row['country_code'] . ": " . $row['capital'] . " | " . $row['population'] . " | " . $row['languages'] . " | " . $row['time_zone'] . " | " . $row['calling_code'] . " | " . $row['plug_type'] . "\n";
    $count++;
}

echo "\n=== Total remaining: $count ===\n";

// Also check known_for field
$detailsStmt = $pdo->query("SELECT c.country_code FROM countries c LEFT JOIN country_details cd ON c.id = cd.country_id WHERE cd.known_for IS NULL OR cd.known_for = '' OR cd.known_for LIKE '%Capital city%' ORDER BY c.country_code");
echo "\n=== Countries missing 'known_for' description ===\n\n";
$missing = 0;
while ($row = $detailsStmt->fetch()) {
    echo $row['country_code'] . "\n";
    $missing++;
}
echo "\n=== Total missing known_for: $missing ===\n";
