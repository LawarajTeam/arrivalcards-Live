<?php
/**
 * Check current AdSense slot configuration
 */

require_once __DIR__ . '/includes/config.php';

echo "<h1>Current AdSense Configuration</h1>\n";
echo "<pre>\n";

// Get all AdSense settings
$stmt = $pdo->query("SELECT * FROM adsense_settings ORDER BY id");
$settings = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Current Settings:\n";
echo str_repeat("=", 70) . "\n";

foreach ($settings as $setting) {
    $key = $setting['setting_key'];
    $value = $setting['setting_value'];
    $desc = $setting['description'];
    
    echo "\n$key:\n";
    echo "  Value: " . ($value ?: '(empty)') . "\n";
    echo "  Description: $desc\n";
}

echo "\n" . str_repeat("=", 70) . "\n";

// Check slot keys specifically
$slotKeys = [
    'ad_slot_infeed_card',
    'ad_slot_panel_horizontal',
    'ad_slot_landing_top',
    'ad_slot_landing_middle',
    'ad_slot_landing_bottom',
    'ad_slot_sidebar'
];

echo "\nAd Slot Status:\n";
echo str_repeat("=", 70) . "\n";

$configuredCount = 0;
foreach ($slotKeys as $key) {
    $stmt = $pdo->prepare("SELECT setting_value FROM adsense_settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $value = $stmt->fetchColumn();
    
    if (!empty($value)) {
        echo "✅ $key: $value\n";
        $configuredCount++;
    } else {
        echo "❌ $key: Not configured\n";
    }
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "Total: $configuredCount of " . count($slotKeys) . " slots configured\n";

if ($configuredCount === 0) {
    echo "\n⚠️  WARNING: No ad slots configured!\n";
    echo "To fix: Add ad slot IDs from your Google AdSense account.\n";
}

echo "</pre>\n";
