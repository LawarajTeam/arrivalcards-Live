<?php
require 'includes/config.php';

$stmt = $pdo->query("SELECT c.country_code, ct.country_name, ct.entry_summary
                     FROM countries c 
                     JOIN country_translations ct ON c.id = ct.country_id 
                     WHERE ct.lang_code = 'en' 
                     AND (ct.entry_summary LIKE 'Visa information and entry%'  
                          OR ct.entry_summary = '' 
                          OR ct.entry_summary IS NULL)
                     ORDER BY ct.country_name");

$remaining = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Countries still with generic descriptions: " . count($remaining) . "\n\n";

foreach ($remaining as $country) {
    echo $country['country_code'] . " - " . $country['country_name'] . "\n";
}
