<?php
/**
 * Setup Analytics Tables
 * Run this script once to create the analytics database tables
 */

require_once __DIR__ . '/../includes/config.php';

// Check if admin or run from CLI
if (php_sapi_name() !== 'cli' && !isset($_SESSION['admin_logged_in'])) {
    die('Access denied');
}

echo "<h1>Analytics Database Setup</h1>";
echo "<pre>";

try {
    // Read SQL files
    $sqlFiles = [
        'analytics_tables.sql',
        'add_view_count.sql'
    ];
    
    foreach ($sqlFiles as $file) {
        echo "\n========================================\n";
        echo "Executing: $file\n";
        echo "========================================\n";
        
        $sqlFile = __DIR__ . '/../' . $file;
        if (!file_exists($sqlFile)) {
            echo "❌ File not found: $file\n";
            continue;
        }
        
        $sql = file_get_contents($sqlFile);
        
        // Split by semicolon and execute each statement
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (empty($statement) || strpos($statement, '--') === 0) {
                continue;
            }
            
            try {
                $pdo->exec($statement);
                echo "✓ Executed successfully\n";
            } catch (PDOException $e) {
                // Check if error is about table already exists
                if (strpos($e->getMessage(), 'already exists') !== false) {
                    echo "ℹ️ Already exists (skipped)\n";
                } else {
                    echo "❌ Error: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "\n========================================\n";
    echo "✅ Analytics Setup Complete!\n";
    echo "========================================\n\n";
    
    echo "Created tables:\n";
    echo "  - page_views (track all page visits)\n";
    echo "  - visitor_sessions (track user sessions)\n";
    echo "  - view_count column in countries table\n\n";
    
    echo "Next steps:\n";
    echo "1. Visit the Admin Analytics Dashboard:\n";
    echo "   " . APP_URL . "/admin/analytics.php\n\n";
    echo "2. Analytics will start tracking automatically\n";
    echo "3. Check back in 24 hours for initial data\n\n";
    
} catch (Exception $e) {
    echo "❌ Setup failed: " . $e->getMessage() . "\n";
}

echo "</pre>";

// Display verification queries
echo "<h2>Verification Queries</h2>";
echo "<pre>";

try {
    // Check if tables exist
    $tables = ['page_views', 'visitor_sessions'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '{$table}'");
        if ($stmt->rowCount() > 0) {
            echo "✓ Table '{$table}' exists\n";
            
            // Get row count
            $count = $pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
            echo "  Current records: {$count}\n\n";
        } else {
            echo "❌ Table '{$table}' does not exist\n";
        }
    }
    
    // Check view_count column
    $stmt = $pdo->query("SHOW COLUMNS FROM countries LIKE 'view_count'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Column 'view_count' exists in countries table\n";
    } else {
        echo "❌ Column 'view_count' does not exist in countries table\n";
    }
    
} catch (PDOException $e) {
    echo "Error checking tables: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
