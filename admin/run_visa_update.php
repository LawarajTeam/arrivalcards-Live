<?php
/**
 * One-time admin script: Run visa data update (corrected alpha-3 codes)
 * DELETE THIS FILE after use.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$sqlFile = __DIR__ . '/../sql/update_visa_data_may2026.sql';
$results = [];
$errors  = [];

function parseSqlStatements(string $sql): array {
    $statements = [];
    $current    = '';
    $inString   = false;
    $stringChar = '';
    $len        = strlen($sql);
    $i          = 0;

    while ($i < $len) {
        $c = $sql[$i];
        if ($inString) {
            $current .= $c;
            if ($c === $stringChar) {
                if ($i + 1 < $len && $sql[$i + 1] === $stringChar) { $current .= $sql[++$i]; }
                else { $inString = false; }
            }
            $i++; continue;
        }
        if ($c === "'" || $c === '"' || $c === '`') { $inString = true; $stringChar = $c; $current .= $c; $i++; continue; }
        if ($c === '-' && $i + 1 < $len && $sql[$i + 1] === '-') { while ($i < $len && $sql[$i] !== "\n") $i++; continue; }
        if ($c === '#') { while ($i < $len && $sql[$i] !== "\n") $i++; continue; }
        if ($c === '/' && $i + 1 < $len && $sql[$i + 1] === '*') { $i += 2; while ($i + 1 < $len && !($sql[$i] === '*' && $sql[$i + 1] === '/')) $i++; $i += 2; continue; }
        if ($c === ';') { $stmt = trim($current); if ($stmt !== '') $statements[] = $stmt; $current = ''; $i++; continue; }
        $current .= $c; $i++;
    }
    $stmt = trim($current); if ($stmt !== '') $statements[] = $stmt;
    return $statements;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Invalid CSRF token');
    if (!file_exists($sqlFile)) die('SQL file not found');

    $raw = file_get_contents($sqlFile);
    $raw = ltrim($raw, "\xEF\xBB\xBF");  // strip UTF-8 BOM if present
    $statements = parseSqlStatements($raw);
    $pdo->beginTransaction();
    try {
        foreach ($statements as $stmt) { $pdo->exec($stmt); $results[] = htmlspecialchars(substr($stmt, 0, 120)) . '…'; }
        $pdo->commit(); $success = true;
    } catch (PDOException $e) {
        $pdo->rollBack(); $errors[] = htmlspecialchars($e->getMessage()); $success = false;
    }
}
?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Visa Update</title>
<style>body{font-family:system-ui,sans-serif;max-width:900px;margin:2rem auto;padding:0 1rem}.ok{background:#d1fae5;padding:.75rem 1rem;border-radius:6px;margin:.25rem 0;font-size:.8rem}.err{background:#fee2e2;padding:.5rem 1rem;border-radius:6px;margin:.25rem 0}.btn{background:#1e40af;color:#fff;border:none;padding:.75rem 1.5rem;border-radius:8px;font-size:1rem;cursor:pointer}.warn{background:#fef3c7;padding:1rem;border-radius:8px;margin-bottom:1rem}</style>
</head><body>
<h1>Visa Data Update — May 2026 (Fixed)</h1>
<?php if (!empty($errors)): ?><div class="err"><strong>Error:</strong> <?= implode('<br>', $errors) ?></div>
<?php elseif (isset($success) && $success): ?>
    <div class="ok"><strong>✅ <?= count($results) ?> statements executed successfully.</strong></div>
    <p><strong>⚠️ Delete this file:</strong> <code>admin/run_visa_update.php</code></p>
<?php else: ?>
    <div class="warn">⚠️ This will overwrite English visa data for all 70 countries using correct ISO alpha-3 codes.</div>
    <form method="POST"><input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>"><input type="hidden" name="confirm" value="1">
    <button type="submit" class="btn">Run Visa Data Update</button> <a href="/admin/">Cancel</a></form>
<?php endif; ?>
</body></html>
