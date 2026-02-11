<?php
require_once __DIR__ . '/includes/config.php';

echo "<h1>North Korea Verification</h1>\n";

try {
    // Check if North Korea exists
    $stmt = $pdo->query("SELECT * FROM countries WHERE country_code = 'PRK'");
    $country = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($country) {
        echo "<p style='color:green; font-weight:bold;'>✓ North Korea found in database!</p>\n";
        echo "<pre>" . print_r($country, true) . "</pre>\n";
        
        echo "<h2>Translations</h2>\n";
        $stmt2 = $pdo->prepare("SELECT lang_code, country_name, entry_summary FROM country_translations WHERE country_id = ?");
        $stmt2->execute([$country['id']]);
        $translations = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<ul>\n";
        foreach ($translations as $trans) {
            echo "<li><strong>{$trans['lang_code']}</strong>: {$trans['country_name']}<br>";
            echo "<small>" . substr($trans['entry_summary'], 0, 100) . "...</small></li>\n";
        }
        echo "</ul>\n";
        
        echo "<p><a href='index.php' style='color:blue;'>View on Homepage</a></p>\n";
        echo "<p><a href='country.php?id={$country['id']}' style='color:blue;'>View Country Details Page</a></p>\n";
    } else {
        echo "<p style='color:red; font-weight:bold;'>✗ North Korea NOT found in database</p>\n";
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>\n";
}
?>
