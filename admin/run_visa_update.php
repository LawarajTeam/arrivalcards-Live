<?php
/**
 * One-time admin script: Run visa data update
 * DELETE THIS FILE after use.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$sqlFile = __DIR__ . '/../sql/update_visa_data_may2026.sql';
$results = [];
$errors  = [];

/**
 * Parse SQL into individual statements, correctly handling:
 * - String literals (single/double/backtick quoted, with '' escaping)
 * - -- line comments and # line comments (ignored outside strings)
 * - /* block comments (ignored outside strings)
 * - ; statement terminators (only outside strings/comments)
 */
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
                // '' is an escaped quote inside a string — keep going
                if ($i + 1 < $len && $sql[$i + 1] === $stringChar) {
                    $current .= $sql[++$i];
                } else {
                    $inString = false;
                }
            }
            $i++;
            continue;
        }

        // Outside a string
        if ($c === "'" || $c === '"' || $c === '`') {
            $inString   = true;
            $stringChar = $c;
            $current   .= $c;
            $i++;
            continue;
        }

        // -- line comment
        if ($c === '-' && $i + 1 < $len && $sql[$i + 1] === '-') {
            while ($i < $len && $sql[$i] !== "\n") $i++;
            continue;
        }

        // # line comment
        if ($c === '#') {
            while ($i < $len && $sql[$i] !== "\n") $i++;
            continue;
        }

        // /* block comment */
        if ($c === '/' && $i + 1 < $len && $sql[$i + 1] === '*') {
            $i += 2;
            while ($i + 1 < $len && !($sql[$i] === '*' && $sql[$i + 1] === '/')) $i++;
            $i += 2;
            continue;
        }

        // Statement terminator
        if ($c === ';') {
            $stmt = trim($current);
            if ($stmt !== '') {
                $statements[] = $stmt;
            }
            $current = '';
            $i++;
            continue;
        }

        $current .= $c;
        $i++;
    }

    $stmt = trim($current);
    if ($stmt !== '') {
        $statements[] = $stmt;
    }

    return $statements;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    if (!file_exists($sqlFile)) {
        die('SQL file not found: ' . htmlspecialchars($sqlFile));
    }

    $sql        = file_get_contents($sqlFile);
    $statements = parseSqlStatements($sql);

    $pdo->beginTransaction();
    try {
        foreach ($statements as $stmt) {
            $pdo->exec($stmt);
            $results[] = htmlspecialchars(substr($stmt, 0, 120)) . '…';
        }
        $pdo->commit();
        $success = true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $errors[] = 'DB Error: ' . htmlspecialchars($e->getMessage());
        $success  = false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visa Data Update — Admin</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
        h1 { color: #1e40af; }
        .ok  { background: #d1fae5; padding: .5rem 1rem; border-radius: 6px; margin: .25rem 0; font-size: .8rem; }
        .err { background: #fee2e2; padding: .5rem 1rem; border-radius: 6px; margin: .25rem 0; }
        .btn { background: #1e40af; color: #fff; border: none; padding: .75rem 1.5rem;
               border-radius: 8px; font-size: 1rem; cursor: pointer; }
        .btn:hover { background: #1e3a8a; }
        .warn { background: #fef3c7; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
    </style>
</head>
<body>
<h1>Visa Data Update — May 2026</h1>

<?php if (!empty($errors)): ?>
    <div class="err"><strong>Errors:</strong>
        <?php foreach ($errors as $e): ?><p><?= $e ?></p><?php endforeach; ?>
    </div>
<?php elseif (isset($success) && $success): ?>
    <div class="ok"><strong>✅ Update complete.</strong>
        <?= count($results) ?> statements executed successfully.
    </div>
    <p><strong>⚠️ Please delete this file:</strong> <code>admin/run_visa_update.php</code></p>
<?php else: ?>
    <div class="warn">
        <strong>⚠️ Warning:</strong> This script will overwrite all English visa data for 70 countries
        with May 2026 data. This cannot be undone. Make a database backup first.
    </div>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
        <input type="hidden" name="confirm" value="1">
        <button type="submit" class="btn">Run Visa Data Update</button>
        <a href="<?= APP_URL ?>/admin/" style="margin-left:1rem;">Cancel</a>
    </form>
<?php endif; ?>

</body>
</html>
