<?php
require 'includes/config.php';

$stmt = $pdo->query("SELECT c.id, c.country_code, c.flag_emoji, c.region, c.visa_type, 
                     ct.country_name, ct.entry_summary, ct.visa_requirements
                     FROM countries c 
                     LEFT JOIN country_translations ct ON c.id = ct.country_id 
                     WHERE ct.lang_code = 'en' 
                     ORDER BY ct.country_name");

$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total countries: " . count($countries) . "\n\n";
echo "=== SAMPLE ENTRIES ===\n\n";

foreach (array_slice($countries, 0, 10) as $country) {
    echo $country['country_code'] . " - " . $country['country_name'] . " (" . $country['visa_type'] . ")\n";
    echo "Summary: " . substr($country['entry_summary'], 0, 150) . "...\n";
    echo "---\n\n";
}

// Check for duplicate/similar summaries
echo "\n=== CHECKING FOR SIMILAR SUMMARIES ===\n\n";
$summaries = [];
foreach ($countries as $country) {
    $summary = $country['entry_summary'];
    if (!isset($summaries[$summary])) {
        $summaries[$summary] = [];
    }
    $summaries[$summary][] = $country['country_name'];
}

foreach ($summaries as $summary => $countryList) {
    if (count($countryList) > 1) {
        echo count($countryList) . " countries share this summary:\n";
        echo "\"" . substr($summary, 0, 100) . "...\"\n";
        echo "Countries: " . implode(", ", array_slice($countryList, 0, 5));
        if (count($countryList) > 5) echo " (+" . (count($countryList) - 5) . " more)";
        echo "\n\n";
    }
}
