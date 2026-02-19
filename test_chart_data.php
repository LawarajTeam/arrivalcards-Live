<?php
/**
 * Test Chart Data Output
 */

require_once __DIR__ . '/includes/config.php';

$dateRange = 7;
$dateFrom = date('Y-m-d 00:00:00', strtotime("-{$dateRange} days"));

// Get daily traffic
$stmt = $pdo->prepare("
    SELECT 
        DATE(viewed_at) as view_date,
        COUNT(*) as page_views,
        COUNT(DISTINCT visitor_ip) as unique_visitors
    FROM page_views
    WHERE viewed_at >= ?
    GROUP BY DATE(viewed_at)
    ORDER BY view_date ASC
");
$stmt->execute([$dateFrom]);
$dailyTraffic = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get hourly traffic
$stmt = $pdo->query("
    SELECT 
        HOUR(viewed_at) as hour,
        COUNT(*) as views
    FROM page_views
    WHERE DATE(viewed_at) = CURDATE()
    GROUP BY HOUR(viewed_at)
    ORDER BY hour ASC
");
$hourlyTraffic = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Chart Data Test</h2>";

echo "<h3>Daily Traffic Data (for chart):</h3>";
echo "<pre>";
print_r($dailyTraffic);
echo "</pre>";

echo "<h3>Hourly Traffic Data (for chart):</h3>";
echo "<pre>";
print_r($hourlyTraffic);
echo "</pre>";

// Test JSON encoding
echo "<h3>Daily Labels (JSON for JS):</h3>";
$dailyLabels = array_map(function($d) { 
    return date('M d', strtotime($d['view_date'])); 
}, $dailyTraffic);
echo "<pre>" . json_encode($dailyLabels) . "</pre>";

echo "<h3>Daily Page Views (JSON for JS):</h3>";
$dailyPageViews = array_map(function($d) { 
    return $d['page_views']; 
}, $dailyTraffic);
echo "<pre>" . json_encode($dailyPageViews) . "</pre>";

// Hourly data
echo "<h3>Hourly Data Array (for chart):</h3>";
$hours = array_fill(0, 24, 0);
foreach ($hourlyTraffic as $h) {
    $hours[$h['hour']] = $h['views'];
}
echo "<pre>";
print_r($hours);
echo "</pre>";

echo "<h3>Hourly Labels (JSON for JS):</h3>";
$hourlyLabels = array_map(function($i) { 
    return str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; 
}, range(0, 23));
echo "<pre>" . json_encode($hourlyLabels) . "</pre>";

echo "<h3>Hourly Data (JSON for JS):</h3>";
echo "<pre>" . json_encode(array_values($hours)) . "</pre>";

echo "<hr>";
echo "<p><strong>If you see data above, the PHP is working correctly.</strong></p>";
echo "<p>The issue is likely with Chart.js not loading or a JavaScript error in the browser console.</p>";
?>
