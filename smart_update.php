<?php
/**
 * Smart Update - Matches country descriptions to actual database country codes
 */

require 'includes/config.php';

echo "=== Smart Country Entry Summary Update ===\n\n";

// Get all countries from database with their actual codes
$stmt = $pdo->query("SELECT c.id, c.country_code, ct.country_name, ct.entry_summary
                     FROM countries c 
                     LEFT JOIN country_translations ct ON c.id = ct.country_id 
                     WHERE ct.lang_code = 'en'
                     ORDER BY ct.country_name");
$dbCountries = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Found " . count($dbCountries) . " countries in database.\n\n";

// Load the descriptions file
require 'update_country_summaries.php';

// Match and update
$updated = 0;
$skipped = 0;
$errors = [];

$updateStmt = $pdo->prepare("UPDATE country_translations 
                              SET entry_summary = ? 
                              WHERE id = ?");

foreach ($dbCountries as $country) {
    $code = $country['country_code'];
    
    // Check if we have a description for this code
    if (isset($countryDescriptions[$code])) {
        try {
            // Get the translation ID
            $transStmt = $pdo->prepare("SELECT id FROM country_translations 
                                        WHERE country_id = ? AND lang_code = 'en'");
            $transStmt->execute([$country['id']]);
            $trans = $transStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($trans) {
                $updateStmt->execute([$countryDescriptions[$code], $trans['id']]);
                $updated++;
                echo "✓ {$country['country_name']} ($code)\n";
            }
        } catch (PDOException $e) {
            $errors[] = "{$country['country_name']} ($code) - " . $e->getMessage();
        }
    } else {
        $skipped++;
        echo "⊘ {$country['country_name']} ($code) - No description provided\n";
    }
}

echo "\n=== Update Complete ===\n";
echo "Successfully updated: $updated countries\n";
echo "Skipped (no description): $skipped countries\n";

if (!empty($errors)) {
    echo "\nErrors:\n";
    foreach ($errors as $error) {
        echo "  ✗ $error\n";
    }
}
