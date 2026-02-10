<?php
require 'includes/config.php';

echo "=== VISA TYPE DISTRIBUTION ===\n\n";

$stmt = $pdo->query('SELECT visa_type, COUNT(*) as cnt FROM countries GROUP BY visa_type');
while($row = $stmt->fetch()) {
    echo $row['visa_type'] . ': ' . $row['cnt'] . "\n";
}

echo "\n=== SAMPLE COUNTRIES BY VISA TYPE ===\n\n";

$types = ['visa_free', 'visa_on_arrival', 'evisa', 'visa_required'];
foreach ($types as $type) {
    $stmt = $pdo->prepare('SELECT country_code FROM countries WHERE visa_type = ? ORDER BY country_code LIMIT 20');
    $stmt->execute([$type]);
    $codes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "$type: " . implode(', ', $codes) . "\n\n";
}
