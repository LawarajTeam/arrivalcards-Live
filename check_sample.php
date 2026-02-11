<?php
require_once __DIR__ . '/includes/config.php';

echo "Sample country data:\n\n";

try {
    // Get a sample country
    $stmt = $pdo->query("SELECT * FROM countries LIMIT 1");
    $country = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Countries table sample:\n";
    print_r($country);
    
    echo "\n\nCountry translations sample:\n";
    $stmt2 = $pdo->prepare("SELECT * FROM country_translations WHERE country_id = ? LIMIT 1");
    $stmt2->execute([$country['id']]);
    $translation = $stmt2->fetch(PDO::FETCH_ASSOC);
    print_r($translation);
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
