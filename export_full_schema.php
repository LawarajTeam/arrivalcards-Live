<?php
require_once 'includes/config.php';

// Export complete database schema
$output = "-- ============================================\n";
$output .= "-- Arrival Cards Complete Database Schema\n";
$output .= "-- Date: " . date('F d, Y') . "\n";
$output .= "-- Includes: AdSense integration and view counter\n";
$output .= "-- ============================================\n\n";

$output .= "DROP DATABASE IF EXISTS arrivalcards;\n";
$output .= "CREATE DATABASE arrivalcards CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
$output .= "USE arrivalcards;\n\n";

// Get all tables
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

foreach ($tables as $table) {
    // Get CREATE TABLE statement
    $createStmt = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
    $output .= "-- ============================================\n";
    $output .= "-- Table: $table\n";
    $output .= "-- ============================================\n";
    $output .= $createStmt['Create Table'] . ";\n\n";
}

// Write to file
file_put_contents('database_complete.sql', $output);

echo "✓ Database schema exported to database_complete.sql\n";
echo "✓ Total tables: " . count($tables) . "\n";
echo "Tables included:\n";
foreach ($tables as $table) {
    echo "  - $table\n";
}
