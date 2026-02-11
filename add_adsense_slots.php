<?php
/**
 * Add sample AdSense ad slot IDs
 * This will populate the database with placeholder ad slot IDs
 * Replace these with your actual ad slot IDs from Google AdSense
 */

require_once __DIR__ . '/includes/config.php';

echo "<h1>Adding Sample AdSense Ad Slot IDs</h1>\n";
echo "<pre>\n";

echo "⚠️  IMPORTANT: These are placeholder IDs!\n";
echo "You must replace them with your actual ad slot IDs from Google AdSense.\n";
echo str_repeat("=", 70) . "\n\n";

// Sample ad slot IDs (10-digit format is typical for AdSense)
$slotIds = [
    'ad_slot_infeed_card' => '1234567890',
    'ad_slot_panel_horizontal' => '2345678901',
    'ad_slot_landing_top' => '3456789012',
    'ad_slot_landing_middle' => '4567890123',
    'ad_slot_landing_bottom' => '5678901234',
    'ad_slot_sidebar' => '6789012345'
];

// Also update the client ID if it's empty
$stmt = $pdo->prepare("SELECT setting_value FROM adsense_settings WHERE setting_key = 'adsense_client_id'");
$stmt->execute();
$currentClientId = $stmt->fetchColumn();

if (empty($currentClientId)) {
    echo "📝 Updating Client ID...\n";
    $updateStmt = $pdo->prepare("UPDATE adsense_settings SET setting_value = ? WHERE setting_key = 'adsense_client_id'");
    $updateStmt->execute(['ca-pub-3368423914848945']);
    echo "✅ Client ID set to: ca-pub-3368423914848945\n\n";
} else {
    echo "✅ Client ID already configured: $currentClientId\n\n";
}

echo "Adding Ad Slot IDs:\n";
echo str_repeat("=", 70) . "\n";

$stmt = $pdo->prepare("UPDATE adsense_settings SET setting_value = ? WHERE setting_key = ?");

foreach ($slotIds as $key => $slotId) {
    try {
        $stmt->execute([$slotId, $key]);
        echo "✅ {$key}: {$slotId}\n";
    } catch (Exception $e) {
        echo "❌ {$key}: Error - " . $e->getMessage() . "\n";
    }
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "✅ Configuration complete!\n\n";

echo "NEXT STEPS:\n";
echo "1. Go to your Google AdSense account (https://adsense.google.com)\n";
echo "2. Create ad units for each position:\n";
echo "   - In-feed ad unit for country cards\n";
echo "   - Display ad units for horizontal panels\n";
echo "   - Responsive ad units for landing page sections\n";
echo "3. Copy the actual ad slot IDs from AdSense\n";
echo "4. Go to Admin > AdSense Management and replace these placeholder IDs\n";
echo "5. Save settings\n\n";

echo "⚠️  Until you add real ad slot IDs, ads will not display correctly!\n";

echo "</pre>\n";
