<?php
/**
 * Admin Panel - Bilateral Visa Data Entry
 * Add personalized visa requirements for passport holders
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Use proper admin authentication
requireAdmin();
$is_authenticated = true;

// Get all countries
$stmt = $pdo->query("SELECT id, country_code, flag_emoji FROM countries WHERE is_active = 1 ORDER BY country_code");
$allCountries = $stmt->fetchAll(PDO::FETCH_ASSOC);
$countryMap = [];
foreach ($allCountries as $c) {
    $countryMap[$c['country_code']] = $c;
}

// Priority passports to add
$priorityPassports = [
    'JPN' => 'Japan',
    'DEU' => 'Germany', 
    'CAN' => 'Canada',
    'AUS' => 'Australia',
    'FRA' => 'France',
    'ESP' => 'Spain',
    'ITA' => 'Italy',
    'BRA' => 'Brazil',
    'MEX' => 'Mexico',
    'SAU' => 'Saudi Arabia'
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_visa_data'])) {
    $fromCountryCode = $_POST['from_country'];
    $toCountryCode = $_POST['to_country'];
    $visaType = $_POST['visa_type'];
    $durationDays = !empty($_POST['duration_days']) ? (int)$_POST['duration_days'] : null;
    $costUsd = !empty($_POST['cost_usd']) ? (float)$_POST['cost_usd'] : null;
    $processingDays = !empty($_POST['processing_days']) ? (int)$_POST['processing_days'] : null;
    $requirementsSummary = !empty($_POST['requirements_summary']) ? $_POST['requirements_summary'] : null;
    $specialNotes = !empty($_POST['special_notes']) ? $_POST['special_notes'] : null;
    $approvalRate = !empty($_POST['approval_rate']) ? (int)$_POST['approval_rate'] : null;
    
    $fromCountryId = $countryMap[$fromCountryCode]['id'];
    $toCountryId = $countryMap[$toCountryCode]['id'];
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO bilateral_visa_requirements 
            (from_country_id, to_country_id, visa_type, duration_days, cost_usd, processing_time_days, 
             requirements_summary, special_notes, approval_rate_percent, is_verified, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())
            ON DUPLICATE KEY UPDATE
                visa_type = VALUES(visa_type),
                duration_days = VALUES(duration_days),
                cost_usd = VALUES(cost_usd),
                processing_time_days = VALUES(processing_time_days),
                requirements_summary = VALUES(requirements_summary),
                special_notes = VALUES(special_notes),
                approval_rate_percent = VALUES(approval_rate_percent)
        ");
        
        $stmt->execute([
            $fromCountryId, $toCountryId, $visaType, $durationDays, $costUsd, 
            $processingDays, $requirementsSummary, $specialNotes, $approvalRate
        ]);
        
        $successMessage = "✓ Added visa data: {$countryMap[$fromCountryCode]['flag_emoji']} $fromCountryCode → {$countryMap[$toCountryCode]['flag_emoji']} $toCountryCode";
    } catch (PDOException $e) {
        $errorMessage = "Error: " . $e->getMessage();
    }
}

// Get existing data count
$stmt = $pdo->query("
    SELECT 
        c1.country_code as from_code,
        c1.flag_emoji as from_flag,
        COUNT(*) as destinations
    FROM bilateral_visa_requirements b
    JOIN countries c1 ON b.from_country_id = c1.id
    GROUP BY c1.country_code, c1.flag_emoji
    ORDER BY destinations DESC
");
$existingData = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Visa Data Entry - Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; background: #f9fafb; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header h1 { font-size: 1.75rem; margin-bottom: 0.5rem; }
        .header p { opacity: 0.9; }
        .logout { float: right; background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 0.375rem; color: white; text-decoration: none; font-size: 0.875rem; }
        .logout:hover { background: rgba(255,255,255,0.3); }
        .container { max-width: 1400px; margin: 0 auto; padding: 2rem; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        .card { background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; }
        .card h2 { color: #1f2937; margin-bottom: 1.5rem; font-size: 1.25rem; padding-bottom: 0.75rem; border-bottom: 2px solid #e5e7eb; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; color: #374151; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.875rem; }
        select, input, textarea { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; }
        select:focus, input:focus, textarea:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        textarea { resize: vertical; min-height: 80px; font-family: inherit; }
        .btn { background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 600; font-size: 0.875rem; width: 100%; transition: background 0.2s; }
        .btn:hover { background: #2563eb; }
        .message { padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-size: 0.875rem; }
        .success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.25rem; border-radius: 0.5rem; }
        .stat-number { font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem; }
        .stat-label { font-size: 0.875rem; opacity: 0.9; }
        .data-list { max-height: 400px; overflow-y: auto; }
        .data-item { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; border-bottom: 1px solid #e5e7eb; }
        .data-item:last-child { border-bottom: none; }
        .priority-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.75rem; margin-top: 1rem; }
        .priority-item { background: #f3f4f6; padding: 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; }
        .quick-fill { display: flex; gap: 0.5rem; margin-top: 0.5rem; }
        .quick-fill button { padding: 0.375rem 0.75rem; background: #e5e7eb; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 0.75rem; }
        .quick-fill button:hover { background: #d1d5db; }
    </style>
</head>
<body>
    <div class="header">
        <a href="?logout=1" class="logout">Logout</a>
        <h1>🛂 Bilateral Visa Data Entry</h1>
        <p>Add personalized visa requirements for passport holders</p>
    </div>
    
    <div class="container">
        <?php if (isset($successMessage)): ?>
            <div class="message success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        
        <?php if (isset($errorMessage)): ?>
            <div class="message error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($existingData); ?></div>
                <div class="stat-label">Passports with Data</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php 
                    $totalRecords = $pdo->query("SELECT COUNT(*) FROM bilateral_visa_requirements")->fetchColumn();
                    echo $totalRecords;
                ?></div>
                <div class="stat-label">Total Bilateral Records</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($priorityPassports); ?></div>
                <div class="stat-label">Priority Passports</div>
            </div>
        </div>
        
        <div class="grid">
            <div class="card">
                <h2>Add Visa Requirement</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>From Country (Passport Holder)</label>
                        <select name="from_country" required id="from_country">
                            <option value="">Select passport nationality...</option>
                            <?php foreach ($priorityPassports as $code => $name): ?>
                                <?php if (isset($countryMap[$code])): ?>
                                    <option value="<?php echo $code; ?>">
                                        <?php echo $countryMap[$code]['flag_emoji']; ?> <?php echo $name; ?> (<?php echo $code; ?>)
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>To Country (Destination)</label>
                        <select name="to_country" required id="to_country">
                            <option value="">Select destination...</option>
                            <?php foreach ($allCountries as $country): ?>
                                <option value="<?php echo $country['country_code']; ?>">
                                    <?php echo $country['flag_emoji']; ?> <?php echo $country['country_code']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Visa Type</label>
                        <select name="visa_type" required id="visa_type">
                            <option value="">Select visa type...</option>
                            <option value="visa_free">Visa Free</option>
                            <option value="visa_on_arrival">Visa on Arrival</option>
                            <option value="evisa">eVisa</option>
                            <option value="visa_required">Visa Required</option>
                            <option value="no_entry">No Entry</option>
                        </select>
                        <div class="quick-fill">
                            <button type="button" onclick="quickFill('visa_free', 90, 0, 0)">Visa Free (90d)</button>
                            <button type="button" onclick="quickFill('visa_on_arrival', 30, 50, 1)">VoA ($50)</button>
                            <button type="button" onclick="quickFill('evisa', 30, 80, 3)">eVisa ($80)</button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Duration (days)</label>
                        <input type="number" name="duration_days" id="duration_days" placeholder="e.g., 90">
                    </div>
                    
                    <div class="form-group">
                        <label>Cost (USD)</label>
                        <input type="number" step="0.01" name="cost_usd" id="cost_usd" placeholder="e.g., 160.00">
                    </div>
                    
                    <div class="form-group">
                        <label>Processing Time (days)</label>
                        <input type="number" name="processing_days" id="processing_days" placeholder="e.g., 5">
                    </div>
                    
                    <div class="form-group">
                        <label>Requirements Summary (optional)</label>
                        <textarea name="requirements_summary" placeholder="e.g., Valid passport, return ticket, hotel booking"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Special Notes (optional)</label>
                        <textarea name="special_notes" placeholder="e.g., Interview required, 400+ day wait times"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Approval Rate %</label>
                        <input type="number" name="approval_rate" min="0" max="100" placeholder="e.g., 95">
                    </div>
                    
                    <button type="submit" name="add_visa_data" class="btn">Add Visa Data</button>
                </form>
            </div>
            
            <div>
                <div class="card">
                    <h2>Priority Passports to Add</h2>
                    <div class="priority-list">
                        <?php foreach ($priorityPassports as $code => $name): ?>
                            <?php 
                            $hasData = false;
                            foreach ($existingData as $data) {
                                if ($data['from_code'] === $code) {
                                    $hasData = true;
                                    break;
                                }
                            }
                            ?>
                            <div class="priority-item" style="<?php echo $hasData ? 'background: #d1fae5; border-left: 3px solid #10b981;' : ''; ?>">
                                <?php echo isset($countryMap[$code]) ? $countryMap[$code]['flag_emoji'] : '🏳️'; ?> 
                                <strong><?php echo $name; ?></strong>
                                <?php echo $hasData ? '<br><span style="color: #065f46; font-size: 0.75rem;">✓ Has data</span>' : '<br><span style="color: #6b7280; font-size: 0.75rem;">⚠ No data yet</span>'; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="card" style="margin-top: 2rem;">
                    <h2>Existing Data</h2>
                    <div class="data-list">
                        <?php if (empty($existingData)): ?>
                            <p style="color: #6b7280; text-align: center; padding: 2rem;">No data yet. Start adding visa requirements above.</p>
                        <?php else: ?>
                            <?php foreach ($existingData as $data): ?>
                                <div class="data-item">
                                    <span><?php echo $data['from_flag']; ?> <strong><?php echo $data['from_code']; ?></strong></span>
                                    <span style="color: #6b7280;"><?php echo $data['destinations']; ?> destinations</span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    function quickFill(visaType, duration, cost, processing) {
        document.getElementById('visa_type').value = visaType;
        document.getElementById('duration_days').value = duration;
        document.getElementById('cost_usd').value = cost;
        document.getElementById('processing_days').value = processing;
    }
    </script>
</body>
</html>
