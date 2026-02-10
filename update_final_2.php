<?php
require 'includes/config.php';

// Final 2 countries
$final = [
    'AM' => ['Yerevan', '3 million', 'Armenian Dram', 'AMD', '֏', 'Armenian', 'UTC+4', '+374', 'Type C, F', 'First Christian nation, Mount Ararat views, ancient monasteries, brandy, and rich history.'],
    'MDG' => ['Antananarivo', '28 million', 'Malagasy Ariary', 'MGA', 'Ar', 'Malagasy, French', 'UTC+3', '+261', 'Type C, D, E, J, K', 'Unique lemurs, baobab trees, biodiversity hotspot, vanilla, and fascinating wildlife.'],
];

$stmt = $pdo->prepare("UPDATE countries SET capital = ?, population = ?, currency_name = ?, currency_code = ?, currency_symbol = ?, languages = ?, time_zone = ?, calling_code = ?, plug_type = ? WHERE country_code = ?");
$detailsStmt = $pdo->prepare("INSERT INTO country_details (country_id, lang_code, known_for) VALUES ((SELECT id FROM countries WHERE country_code = ?), 'en', ?) ON DUPLICATE KEY UPDATE known_for = VALUES(known_for)");

$updated = 0;
foreach ($final as $code => $d) {
    try {
        $stmt->execute([$d[0], $d[1], $d[2], $d[3], $d[4], $d[5], $d[6], $d[7], $d[8], $code]);
        $detailsStmt->execute([$code, $d[9]]);
        $updated++;
        echo "✓ $code - " . $d[0] . "\n";
    } catch (PDOException $e) {
        echo "✗ $code: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Final update complete: $updated countries ===\n";
