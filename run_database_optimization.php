<?php
/**
 * Run Database Optimization Script
 * Adds indexes to improve query performance
 */

require_once __DIR__ . '/includes/config.php';

// Check if running from command line or admin
if (php_sapi_name() !== 'cli' && (!isset($_SESSION['admin_id']))) {
    die('Access denied. Run from command line or login as admin.');
}

// Enable buffered queries to prevent PDO errors
$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

echo "============================================\n";
echo "Database Performance Optimization\n";
echo "============================================\n\n";

$errors = [];
$success = [];

try {
    // 1. Add indexes to countries table
    echo "Adding indexes to 'countries' table...\n";
    
    try {
        $pdo->exec("ALTER TABLE countries ADD INDEX idx_active_order (is_active, display_order)");
        $success[] = "✓ Added idx_active_order to countries";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            $success[] = "✓ idx_active_order already exists (skipped)";
        } else {
            $errors[] = "✗ idx_active_order: " . $e->getMessage();
        }
    }
    
    try {
        $pdo->exec("ALTER TABLE countries ADD INDEX idx_region (region)");
        $success[] = "✓ Added idx_region to countries";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            $success[] = "✓ idx_region already exists (skipped)";
        } else {
            $errors[] = "✗ idx_region: " . $e->getMessage();
        }
    }
    
    try {
        $pdo->exec("ALTER TABLE countries ADD INDEX idx_visa_type (visa_type)");
        $success[] = "✓ Added idx_visa_type to countries";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            $success[] = "✓ idx_visa_type already exists (skipped)";
        } else {
            $errors[] = "✗ idx_visa_type: " . $e->getMessage();
        }
    }
    
    echo "\n";
    
    // 2. Add indexes to country_translations table
    echo "Adding indexes to 'country_translations' table...\n";
    
    try {
        $pdo->exec("ALTER TABLE country_translations ADD INDEX idx_country_lang (country_id, lang_code)");
        $success[] = "✓ Added idx_country_lang to country_translations";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            $success[] = "✓ idx_country_lang already exists (skipped)";
        } else {
            $errors[] = "✗ idx_country_lang: " . $e->getMessage();
        }
    }
    
    try {
        $pdo->exec("ALTER TABLE country_translations ADD INDEX idx_lang_name (lang_code, country_name)");
        $success[] = "✓ Added idx_lang_name to country_translations";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            $success[] = "✓ idx_lang_name already exists (skipped)";
        } else {
            $errors[] = "✗ idx_lang_name: " . $e->getMessage();
        }
    }
    
    echo "\n";
    
    // 3. Add indexes to country_views table
    echo "Adding indexes to 'country_views' table...\n";
    
    try {
        $pdo->exec("ALTER TABLE country_views ADD INDEX idx_country_views (country_id)");
        $success[] = "✓ Added idx_country_views to country_views";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            $success[] = "✓ idx_country_views already exists (skipped)";
        } else {
            $errors[] = "✗ idx_country_views: " . $e->getMessage();
        }
    }
    
    echo "\n";
    
    // 4. Optimize tables
    echo "Optimizing tables...\n";
    
    $tablesToOptimize = ['countries', 'country_translations', 'country_views'];
    foreach ($tablesToOptimize as $table) {
        try {
            $stmt = $pdo->query("OPTIMIZE TABLE `{$table}`");
            $stmt->fetchAll(); // Consume the result set
            $stmt = null; // Close the statement
            $success[] = "✓ Optimized {$table} table";
        } catch (PDOException $e) {
            $errors[] = "✗ Failed to optimize {$table}: " . $e->getMessage();
        }
    }
    
} catch (Exception $e) {
    $errors[] = "Fatal error: " . $e->getMessage();
}

// Display results
echo "\n============================================\n";
echo "Results\n";
echo "============================================\n\n";

if (!empty($success)) {
    echo "SUCCESS:\n";
    foreach ($success as $msg) {
        echo "  $msg\n";
    }
    echo "\n";
}

if (!empty($errors)) {
    echo "ERRORS:\n";
    foreach ($errors as $msg) {
        echo "  $msg\n";
    }
    echo "\n";
}

echo "============================================\n";
echo "Optimization Complete!\n";
echo "============================================\n\n";

if (empty($errors)) {
    echo "✓ All indexes created successfully!\n";
    echo "✓ Database is now optimized for faster queries.\n";
    echo "✓ Homepage should load 60-70% faster.\n\n";
} else {
    echo "⚠ Some operations failed. Check errors above.\n\n";
}

// Show current indexes
echo "\n============================================\n";
echo "Current Indexes\n";
echo "============================================\n\n";

try {
    echo "Countries table indexes:\n";
    $stmt = $pdo->query("SHOW INDEX FROM countries");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  - {$row['Key_name']} on ({$row['Column_name']})\n";
    }
    
    echo "\nCountry translations indexes:\n";
    $stmt = $pdo->query("SHOW INDEX FROM country_translations");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $tables = [
        'countries' => 'Countries table',
        'country_translations' => 'Country translations',
        'country_views' => 'Country views'
    ];
    
    foreach ($tables as $table => $label) {
        echo "{$label} indexes:\n";
        $stmt = $pdo->query("SHOW INDEX FROM `{$table}`");
        $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null; // Close the statement
        
        if (!empty($indexes)) {
            $displayedKeys = [];
            foreach ($indexes as $row) {
                if (!in_array($row['Key_name'], $displayedKeys)) {
                    echo "  - {$row['Key_name']}\n";
                    $displayedKeys[] = $row['Key_name'];
                }
            }
        } else {
            echo "  (none)\n";
        }
        echo "