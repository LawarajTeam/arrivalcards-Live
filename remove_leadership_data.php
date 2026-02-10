<?php
/**
 * Remove Leadership Data from All Country Cards
 * This script clears all leadership information (leader_name, leader_title, leader_term)
 * from all 195 countries in the database.
 */

require 'includes/config.php';

echo "=== Removing Leadership Data from All Countries ===\n\n";

try {
    // Count countries before update
    $countStmt = $pdo->query("SELECT COUNT(*) as total FROM countries");
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "Found $total countries in the database.\n\n";
    
    // Clear all leadership data
    $updateQuery = "UPDATE countries 
                    SET leader_name = NULL, 
                        leader_title = NULL, 
                        leader_term = NULL";
    
    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute();
    
    $affected = $stmt->rowCount();
    
    echo "✓ Successfully cleared leadership data from $affected countries.\n\n";
    
    // Verify the cleanup
    $verifyStmt = $pdo->query("SELECT COUNT(*) as remaining 
                               FROM countries 
                               WHERE leader_name IS NOT NULL 
                                  OR leader_title IS NOT NULL 
                                  OR leader_term IS NOT NULL");
    $remaining = $verifyStmt->fetch(PDO::FETCH_ASSOC)['remaining'];
    
    if ($remaining == 0) {
        echo "✓ Verification complete: No leadership data remains in the database.\n";
    } else {
        echo "⚠ Warning: $remaining countries still have leadership data.\n";
    }
    
    echo "\n=== Leadership Data Removal Complete ===\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
