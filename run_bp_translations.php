<?php
/**
 * Run Best Passports Translations
 * Execute via browser: https://arrivalcards.com/run_bp_translations.php?key=BPtrans2025!
 */

if (!isset($_GET['key']) || $_GET['key'] !== 'BPtrans2025!') {
    die('Unauthorized');
}

require_once __DIR__ . '/includes/config.php';

echo "<h2>Running Best Passports Translations...</h2><pre>";

$sqlFile = __DIR__ . '/add_best_passports_translations.sql';
if (!file_exists($sqlFile)) {
    die("SQL file not found: $sqlFile");
}

$sql = file_get_contents($sqlFile);

// Split into individual statements
$statements = array_filter(array_map('trim', explode(';', $sql)));

$success = 0;
$errors = 0;

foreach ($statements as $stmt) {
    // Skip comments and empty
    if (empty($stmt) || strpos($stmt, '--') === 0 || strpos($stmt, 'SELECT') === 0) {
        continue;
    }
    
    try {
        $pdo->exec($stmt);
        $success++;
    } catch (PDOException $e) {
        echo "ERROR: " . htmlspecialchars($e->getMessage()) . "\n";
        echo "Statement: " . htmlspecialchars(substr($stmt, 0, 100)) . "...\n\n";
        $errors++;
    }
}

echo "\n✅ Executed: $success statements\n";
echo "❌ Errors: $errors\n\n";

// Verify
$check = $pdo->query("SELECT lang_code, COUNT(*) as cnt FROM translations WHERE category = 'best_passports' GROUP BY lang_code ORDER BY lang_code");
echo "Verification - translations per language:\n";
foreach ($check->fetchAll() as $row) {
    echo "  {$row['lang_code']}: {$row['cnt']} keys\n";
}

$total = $pdo->query("SELECT COUNT(*) FROM translations WHERE category = 'best_passports'")->fetchColumn();
echo "\nTotal best_passports translations: $total\n";
echo "</pre>";
echo "<p>✅ <strong>Done!</strong> Delete this file from production after use.</p>";
?>
