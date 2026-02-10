<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$pageTitle = 'AdSense Management';
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $settings = [
            'adsense_enabled' => $_POST['adsense_enabled'] ?? '0',
            'adsense_client_id' => trim($_POST['adsense_client_id'] ?? ''),
            'adsense_site_code' => trim($_POST['adsense_site_code'] ?? ''),
            'ad_slot_infeed_card' => trim($_POST['ad_slot_infeed_card'] ?? ''),
            'ad_slot_panel_horizontal' => trim($_POST['ad_slot_panel_horizontal'] ?? ''),
            'ad_slot_sidebar' => trim($_POST['ad_slot_sidebar'] ?? ''),
            'ad_slot_landing_top' => trim($_POST['ad_slot_landing_top'] ?? ''),
            'ad_slot_landing_middle' => trim($_POST['ad_slot_landing_middle'] ?? ''),
            'ad_slot_landing_bottom' => trim($_POST['ad_slot_landing_bottom'] ?? ''),
            'ad_frequency_cards' => intval($_POST['ad_frequency_cards'] ?? 50),
            'ad_frequency_panels' => intval($_POST['ad_frequency_panels'] ?? 100),
        ];

        $stmt = $pdo->prepare("UPDATE adsense_settings SET setting_value = ? WHERE setting_key = ?");
        
        foreach ($settings as $key => $value) {
            $stmt->execute([$value, $key]);
        }

        $success = 'AdSense settings updated successfully!';
    } catch (PDOException $e) {
        $error = 'Error updating settings: ' . $e->getMessage();
    }
}

// Get current settings
$stmt = $pdo->query("SELECT setting_key, setting_value, description FROM adsense_settings ORDER BY id");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = [
        'value' => $row['setting_value'],
        'description' => $row['description']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> - Arrival Cards Admin</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <div class="content-header">
            <h1>🎯 Google AdSense Management</h1>
            <p>Configure Google AdSense integration and ad placements</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">
        <form method="POST" action="">
            <h2>Account Settings</h2>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="adsense_enabled" value="1" 
                           <?php echo ($settings['adsense_enabled']['value'] ?? '0') === '1' ? 'checked' : ''; ?>>
                    <strong>Enable Google AdSense</strong>
                </label>
                <p class="help-text">Turn on AdSense ads across the website</p>
            </div>

            <div class="form-group">
                <label for="adsense_client_id">AdSense Client ID *</label>
                <input type="text" 
                       id="adsense_client_id" 
                       name="adsense_client_id" 
                       class="form-control"
                       placeholder="ca-pub-XXXXXXXXXXXXXXXX"
                       value="<?php echo htmlspecialchars($settings['adsense_client_id']['value'] ?? ''); ?>">
                <p class="help-text">Your Google AdSense publisher ID (found in your AdSense account)</p>
            </div>

            <div class="form-group">
                <label for="adsense_site_code">AdSense Site Code (Optional)</label>
                <textarea 
                    id="adsense_site_code" 
                    name="adsense_site_code" 
                    class="form-control"
                    rows="6"
                    placeholder="Paste your complete AdSense script code here (e.g., <script async src=...)"><?php echo htmlspecialchars($settings['adsense_site_code']['value'] ?? ''); ?></textarea>
                <p class="help-text">Paste the complete AdSense site verification script from Google. Leave blank to auto-generate based on Client ID above.</p>
            </div>

            <hr style="margin: 2rem 0;">

            <h2>Ad Slot Configuration</h2>
            <p class="help-text" style="margin-bottom: 1.5rem;">
                Create ad units in your AdSense account and paste the slot IDs here. Each position requires a separate ad unit.
            </p>

            <div class="form-group">
                <label for="ad_slot_infeed_card">In-Feed Card Ad Slot</label>
                <input type="text" 
                       id="ad_slot_infeed_card" 
                       name="ad_slot_infeed_card" 
                       class="form-control"
                       placeholder="1234567890"
                       value="<?php echo htmlspecialchars($settings['ad_slot_infeed_card']['value'] ?? ''); ?>">
                <p class="help-text">Ad unit displayed as cards within country listings (every 50 cards)</p>
            </div>

            <div class="form-group">
                <label for="ad_slot_panel_horizontal">Horizontal Panel Ad Slot</label>
                <input type="text" 
                       id="ad_slot_panel_horizontal" 
                       name="ad_slot_panel_horizontal" 
                       class="form-control"
                       placeholder="1234567890"
                       value="<?php echo htmlspecialchars($settings['ad_slot_panel_horizontal']['value'] ?? ''); ?>">
                <p class="help-text">Large horizontal banner ads (every 100 cards)</p>
            </div>

            <div class="form-group">
                <label for="ad_slot_landing_top">Landing Page - Top Banner</label>
                <input type="text" 
                       id="ad_slot_landing_top" 
                       name="ad_slot_landing_top" 
                       class="form-control"
                       placeholder="1234567890"
                       value="<?php echo htmlspecialchars($settings['ad_slot_landing_top']['value'] ?? ''); ?>">
                <p class="help-text">Banner ad at the top of the homepage</p>
            </div>

            <div class="form-group">
                <label for="ad_slot_landing_middle">Landing Page - Middle Section</label>
                <input type="text" 
                       id="ad_slot_landing_middle" 
                       name="ad_slot_landing_middle" 
                       class="form-control"
                       placeholder="1234567890"
                       value="<?php echo htmlspecialchars($settings['ad_slot_landing_middle']['value'] ?? ''); ?>">
                <p class="help-text">Ad unit in the middle of the homepage content</p>
            </div>

            <div class="form-group">
                <label for="ad_slot_landing_bottom">Landing Page - Bottom Section</label>
                <input type="text" 
                       id="ad_slot_landing_bottom" 
                       name="ad_slot_landing_bottom" 
                       class="form-control"
                       placeholder="1234567890"
                       value="<?php echo htmlspecialchars($settings['ad_slot_landing_bottom']['value'] ?? ''); ?>">
                <p class="help-text">Ad unit at the bottom of the homepage</p>
            </div>

            <div class="form-group">
                <label for="ad_slot_sidebar">Sidebar Ad Slot</label>
                <input type="text" 
                       id="ad_slot_sidebar" 
                       name="ad_slot_sidebar" 
                       class="form-control"
                       placeholder="1234567890"
                       value="<?php echo htmlspecialchars($settings['ad_slot_sidebar']['value'] ?? ''); ?>">
                <p class="help-text">Vertical sidebar ad units (for future use)</p>
            </div>

            <hr style="margin: 2rem 0;">

            <h2>Ad Frequency Settings</h2>

            <div class="form-group">
                <label for="ad_frequency_cards">Show Ad Card Every X Countries</label>
                <input type="number" 
                       id="ad_frequency_cards" 
                       name="ad_frequency_cards" 
                       class="form-control"
                       min="3"
                       max="100"
                       value="<?php echo htmlspecialchars($settings['ad_frequency_cards']['value'] ?? '50'); ?>">
                <p class="help-text">How often to show in-feed ad cards (minimum: 3, default: 50)</p>
            </div>

            <div class="form-group">
                <label for="ad_frequency_panels">Show Ad Panel Every X Countries</label>
                <input type="number" 
                       id="ad_frequency_panels" 
                       name="ad_frequency_panels" 
                       class="form-control"
                       min="3"
                       max="200"
                       value="<?php echo htmlspecialchars($settings['ad_frequency_panels']['value'] ?? '100'); ?>">
                <p class="help-text">How often to show panel ads (minimum: 3, default: 100)</p>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Save AdSense Settings</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top: 2rem; background: #f9fafb;">
        <h3>📘 Setup Instructions</h3>
        <ol style="line-height: 1.8;">
            <li>Sign up for <a href="https://www.google.com/adsense" target="_blank">Google AdSense</a> if you haven't already</li>
            <li>Get your site approved by Google AdSense</li>
            <li>Create ad units in your AdSense dashboard for each position:
                <ul style="margin-top: 0.5rem;">
                    <li><strong>In-feed ads</strong> - For country card listings</li>
                    <li><strong>Display ads</strong> - For horizontal panels</li>
                    <li><strong>Responsive ads</strong> - For landing page sections</li>
                </ul>
            </li>
            <li>Copy your Publisher ID (ca-pub-XXXXXXXXXXXXXXXX) and paste above</li>
            <li>Copy each ad unit's Slot ID and paste in the corresponding fields</li>
            <li>Enable AdSense using the checkbox</li>
            <li>Save settings and check your website to see ads appear</li>
        </ol>

        <div style="margin-top: 1.5rem; padding: 1rem; background: #fff3cd; border-radius: 4px; border-left: 4px solid #ffc107;">
            <strong>⚠️ Important:</strong> It may take a few hours for ads to start showing after enabling AdSense. 
            Google needs to crawl your site and approve the ad placements.
        </div>
    </div>
    </div> <!-- end admin-container -->

<style>
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #374151;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 1rem;
    transition: border-color 0.15s ease;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.help-text {
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
}

.alert {
    padding: 1rem;
    border-radius: 0.375rem;
    margin-bottom: 1.5rem;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

input[type="checkbox"] {
    width: 1.25rem;
    height: 1.25rem;
    margin-right: 0.5rem;
    cursor: pointer;
}

hr {
    border: none;
    border-top: 1px solid #e5e7eb;
}
</style>

    <script src="<?php echo APP_URL; ?>/assets/js/admin.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/js/main.js"></script>
</body>
</html>
