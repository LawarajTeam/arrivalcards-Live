<?php
/**
 * One-time cleanup: remove duplicate alpha-2 country records superseded by alpha-3 records
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$csrfToken = generateCSRFToken();

$results = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }

    // IDs of old alpha-2 records that have been superseded by alpha-3 records
    // Format: old_alpha2_id => [country_name, alpha2_code, alpha3_code, alpha3_id]
    $duplicates = [
        65  => ['Bolivia',                 'BO',  'BOL', 159],
        87  => ['Bosnia and Herzegovina',  'BA',  'BIH', 160],
        106 => ['Brunei',                  'BN',  'BRN', 162],
        72  => ['Costa Rica',              'CR',  'CRI', 49 ],
        81  => ['Croatia',                 'HR',  'HRV', 172],
        57  => ['Cuba',                    'CU',  'CUB', 173],
        75  => ['Cyprus',                  'CY',  'CYP', 174],
        58  => ['Dominican Republic',      'DO',  'DOM', 177],
        76  => ['Estonia',                 'EE',  'EST', 182],
        92  => ['Georgia',                 'GE',  'GEO', 187],
        67  => ['Guatemala',               'GT',  'GTM', 190],
        68  => ['Honduras',                'HN',  'HND', 195],
        59  => ['Jamaica',                 'JM',  'JAM', 197],
        95  => ['Kazakhstan',              'KZ',  'KAZ', 198],
        97  => ['Kyrgyzstan',              'KG',  'KGZ', 201],
        105 => ['Laos',                    'LA',  'LAO', 202],
        77  => ['Latvia',                  'LV',  'LVA', 203],
    ];

    $oldIds = array_keys($duplicates);

    try {
        $pdo->beginTransaction();

        // Delete translations for old records
        $inPlaceholders = implode(',', array_fill(0, count($oldIds), '?'));
        $stmt = $pdo->prepare("DELETE FROM country_translations WHERE country_id IN ($inPlaceholders)");
        $stmt->execute($oldIds);
        $transDeleted = $stmt->rowCount();

        // Delete old country records
        $stmt = $pdo->prepare("DELETE FROM countries WHERE id IN ($inPlaceholders)");
        $stmt->execute($oldIds);
        $countryDeleted = $stmt->rowCount();

        $pdo->commit();
        $results = "✅ Deleted $countryDeleted duplicate country records and $transDeleted translation rows.";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $results = "❌ Error: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Dedup Countries</title>
<style>body{font-family:sans-serif;max-width:700px;margin:40px auto;padding:20px}
.result{padding:15px;background:#e8f5e9;border-radius:6px;margin:20px 0}
.error{background:#ffebee}
</style>
</head>
<body>
<h1>Remove Duplicate Alpha-2 Country Records</h1>
<p>This will permanently delete 17 old alpha-2 coded country records that have been superseded by alpha-3 records, removing the duplicate entries in the passport selector.</p>
<table border="1" cellpadding="6" style="width:100%;border-collapse:collapse;margin:20px 0">
<tr><th>Country</th><th>Delete (alpha-2)</th><th>Keep (alpha-3)</th></tr>
<tr><td>Bolivia</td><td>BO (id=65)</td><td>BOL (id=159)</td></tr>
<tr><td>Bosnia and Herzegovina</td><td>BA (id=87)</td><td>BIH (id=160)</td></tr>
<tr><td>Brunei</td><td>BN (id=106)</td><td>BRN (id=162)</td></tr>
<tr><td>Costa Rica</td><td>CR (id=72)</td><td>CRI (id=49)</td></tr>
<tr><td>Croatia</td><td>HR (id=81)</td><td>HRV (id=172)</td></tr>
<tr><td>Cuba</td><td>CU (id=57)</td><td>CUB (id=173)</td></tr>
<tr><td>Cyprus</td><td>CY (id=75)</td><td>CYP (id=174)</td></tr>
<tr><td>Dominican Republic</td><td>DO (id=58)</td><td>DOM (id=177)</td></tr>
<tr><td>Estonia</td><td>EE (id=76)</td><td>EST (id=182)</td></tr>
<tr><td>Georgia</td><td>GE (id=92)</td><td>GEO (id=187)</td></tr>
<tr><td>Guatemala</td><td>GT (id=67)</td><td>GTM (id=190)</td></tr>
<tr><td>Honduras</td><td>HN (id=68)</td><td>HND (id=195)</td></tr>
<tr><td>Jamaica</td><td>JM (id=59)</td><td>JAM (id=197)</td></tr>
<tr><td>Kazakhstan</td><td>KZ (id=95)</td><td>KAZ (id=198)</td></tr>
<tr><td>Kyrgyzstan</td><td>KG (id=97)</td><td>KGZ (id=201)</td></tr>
<tr><td>Laos</td><td>LA (id=105)</td><td>LAO (id=202)</td></tr>
<tr><td>Latvia</td><td>LV (id=77)</td><td>LVA (id=203)</td></tr>
</table>

<?php if ($results): ?>
<div class="result <?php echo strpos($results,'❌') !== false ? 'error' : ''; ?>"><?php echo $results; ?></div>
<?php if (strpos($results,'✅') !== false): ?>
<p><strong>⚠️ Delete this file:</strong> <code>admin/run_dedup_countries.php</code></p>
<?php endif; ?>
<?php else: ?>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
    <button type="submit" style="background:#d32f2f;color:#fff;padding:12px 24px;border:none;border-radius:6px;cursor:pointer;font-size:16px">
        Delete 17 Duplicate Records
    </button>
    <a href="/admin/" style="margin-left:20px">Cancel</a>
</form>
<?php endif; ?>
</body>
</html>
