<?php
require 'includes/config.php';

$testCodes = ['USA', 'DEU', 'ITA', 'JPN', 'BRA', 'KEN', 'AUS', 'FRA', 'THA', 'EGY'];

$stmt = $pdo->prepare("SELECT ct.country_name, ct.entry_summary 
                       FROM countries c 
                       JOIN country_translations ct ON c.id = ct.country_id 
                       WHERE ct.lang_code = 'en' AND c.country_code = ?");

echo "=== Sample Country Descriptions ===\n\n";

foreach ($testCodes as $code) {
    $stmt->execute([$code]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "=== {$row['country_name']} ===\n";
        echo $row['entry_summary'] . "\n\n";
    }
}

// Overall stats
$statsStmt = $pdo->query("SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN entry_summary NOT LIKE 'Visa information and entry%' THEN 1 END) as unique_count
    FROM country_translations WHERE lang_code = 'en'");
$stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

echo "=== Statistics ===\n";
echo "Total countries: {$stats['total']}\n";
echo "With unique descriptions: {$stats['unique_count']}\n";
echo "Success rate: " . round(($stats['unique_count'] / $stats['total']) * 100, 1) . "%\n";
