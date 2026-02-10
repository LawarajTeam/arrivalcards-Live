<?php
require 'includes/config.php';

echo "=== COUNTRIES TABLE STRUCTURE ===\n\n";
$stmt = $pdo->query('SHOW COLUMNS FROM countries');
while($row = $stmt->fetch()) {
    echo $row['Field'] . " | " . $row['Type'] . "\n";
}
