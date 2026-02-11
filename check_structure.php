<?php
require_once __DIR__ . '/includes/config.php';

echo "Checking countries table structure...\n\n";

try {
    $stmt = $pdo->query("DESCRIBE countries");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $col) {
        echo $col['Field'] . " - " . $col['Type'] . "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
