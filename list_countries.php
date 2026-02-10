<?php
require 'includes/config.php';

$stmt = $pdo->query("SELECT country_code, ct.country_name 
                     FROM countries c 
                     LEFT JOIN country_translations ct ON c.id = ct.country_id 
                     WHERE ct.lang_code = 'en'
                     ORDER BY country_code");
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total countries in database: " . count($countries) . "\n\n";
echo "Country codes:\n";
foreach ($countries as $country) {
    echo $country['country_code'] . " - " . $country['country_name'] . "\n";
}
