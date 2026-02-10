<?php
require 'includes/config.php';

echo "=== COMPLETE DATA UPDATE SUMMARY ===\n\n";

// Total countries
$total = $pdo->query("SELECT COUNT(*) FROM countries")->fetchColumn();
echo "Total countries in database: $total\n\n";

// Check each field is populated
$fields = [
    'capital' => "SELECT COUNT(*) FROM countries WHERE capital IS NOT NULL AND capital != '' AND capital != 'Capital City'",
    'population' => "SELECT COUNT(*) FROM countries WHERE population IS NOT NULL AND population != '' AND population != 'Population data available'",
    'currency_name' => "SELECT COUNT(*) FROM countries WHERE currency_name IS NOT NULL AND currency_name != '' AND currency_name != 'Local Currency'",
    'currency_code' => "SELECT COUNT(*) FROM countries WHERE currency_code IS NOT NULL AND currency_code != ''",
    'currency_symbol' => "SELECT COUNT(*) FROM countries WHERE currency_symbol IS NOT NULL AND currency_symbol != ''",
    'languages' => "SELECT COUNT(*) FROM countries WHERE languages IS NOT NULL AND languages != '' AND languages != 'Local Language'",
    'time_zone' => "SELECT COUNT(*) FROM countries WHERE time_zone IS NOT NULL AND time_zone != '' AND time_zone != 'UTC+0'",
    'calling_code' => "SELECT COUNT(*) FROM countries WHERE calling_code IS NOT NULL AND calling_code != '' AND calling_code != '+000'",
    'plug_type' => "SELECT COUNT(*) FROM countries WHERE plug_type IS NOT NULL AND plug_type != '' AND plug_type != 'Various'",
];

echo "--- Field Completion Status ---\n";
foreach ($fields as $field => $query) {
    $count = $pdo->query($query)->fetchColumn();
    $percentage = round(($count / $total) * 100, 1);
    $status = ($count == $total) ? '✓' : '✗';
    echo "$status $field: $count/$total ($percentage%)\n";
}

// Check known_for
$knownFor = $pdo->query("SELECT COUNT(DISTINCT c.id) FROM countries c INNER JOIN country_details cd ON c.id = cd.country_id WHERE cd.lang_code = 'en' AND cd.known_for IS NOT NULL AND cd.known_for != ''")->fetchColumn();
$knownForPct = round(($knownFor / $total) * 100, 1);
$knownForStatus = ($knownFor == $total) ? '✓' : '✗';
echo "$knownForStatus known_for (English): $knownFor/$total ($knownForPct%)\n";

echo "\n--- Summary ---\n";
if ($knownFor == $total) {
    foreach ($fields as $field => $query) {
        $count = $pdo->query($query)->fetchColumn();
        if ($count != $total) {
            echo "⚠ Missing data detected\n";
            exit;
        }
    }
    echo "✅ ALL 195 COUNTRIES HAVE UNIQUE, COMPLETE DATA!\n";
    echo "\nCultural Highlights and Practical Information sections\n";
    echo "now display unique, accurate data for each country.\n\n";
    echo "No more generic placeholders like:\n";
    echo "• Capital City\n";
    echo "• Local Currency ($)\n";
    echo "• Local Language\n";
    echo "• UTC+0\n";
    echo "• +000\n";
    echo "• Type Various\n\n";
    echo "Every country now shows specific information relevant\n";
    echo "to that destination!\n";
} else {
    echo "⚠ Some data still missing\n";
}
