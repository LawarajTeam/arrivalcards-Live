<?php
/**
 * TASK 1: Add New Visa Fields to Database
 * Estimated time: 30 minutes
 */

require 'includes/config.php';

echo "=== TASK 1: DATABASE MIGRATION ===\n\n";

// Step 1: Backup reminder
echo "Step 1: Backup Database\n";
echo "Run this command first:\n";
echo "mysqldump -u root -p arrivalcards > backup_arrivalcards_" . date('Y-m-d-His') . ".sql\n\n";
echo "Press Enter when backup is complete...";
// In production, wait for user confirmation

echo "\nStep 2: Adding new fields to country_translations table...\n\n";

$fields = [
    'visa_duration' => "VARCHAR(100) NULL COMMENT 'e.g., 90 days, 6 months'",
    'passport_validity' => "VARCHAR(100) NULL COMMENT 'e.g., 6 months beyond stay'",
    'visa_fee' => "VARCHAR(100) NULL COMMENT 'e.g., Free, $50 USD'",
    'processing_time' => "VARCHAR(100) NULL COMMENT 'e.g., Instant, 3-5 business days'",
    'official_visa_url' => "VARCHAR(500) NULL COMMENT 'Official government visa portal'",
    'arrival_card_required' => "VARCHAR(50) NULL COMMENT 'Yes, No, Online only'",
    'additional_docs' => "TEXT NULL COMMENT 'Required documents list'",
    'last_verified' => "DATE NULL COMMENT 'Last time data was verified'"
];

$success = 0;
$errors = [];

foreach ($fields as $fieldName => $definition) {
    try {
        // Check if field already exists
        $check = $pdo->query("SHOW COLUMNS FROM country_translations LIKE '$fieldName'");
        if ($check->fetch()) {
            echo "⚠ $fieldName already exists - skipping\n";
            $success++;
            continue;
        }
        
        // Add the field
        $sql = "ALTER TABLE country_translations ADD COLUMN $fieldName $definition";
        $pdo->exec($sql);
        echo "✓ Added: $fieldName\n";
        $success++;
    } catch (PDOException $e) {
        echo "✗ Error adding $fieldName: " . $e->getMessage() . "\n";
        $errors[] = $fieldName;
    }
}

echo "\n=== MIGRATION COMPLETE ===\n";
echo "Successfully added: $success/" . count($fields) . " fields\n";

if (count($errors) > 0) {
    echo "Errors with: " . implode(', ', $errors) . "\n";
    echo "⚠ Please fix errors before continuing\n";
} else {
    echo "✓ All fields added successfully!\n";
    echo "\n✓ TASK 1 COMPLETE - Ready for Task 2\n";
}

// Verify the changes
echo "\n--- Verification ---\n";
$stmt = $pdo->query("DESCRIBE country_translations");
$existingFields = [];
while ($row = $stmt->fetch()) {
    if (in_array($row['Field'], array_keys($fields))) {
        $existingFields[] = $row['Field'];
    }
}

echo "Verified " . count($existingFields) . "/" . count($fields) . " new fields exist\n";

if (count($existingFields) == count($fields)) {
    echo "\n🎯 Database is ready for visa data import!\n";
} else {
    echo "\n⚠ Some fields missing - check errors above\n";
}
