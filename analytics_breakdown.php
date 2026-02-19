<?php
/**
 * Analytics Numbers Diagnostic
 * Shows exactly what each metric is counting
 */

require_once __DIR__ . '/includes/config.php';

$dateRange = isset($_GET['range']) ? $_GET['range'] : '7';
$dateFrom = date('Y-m-d 00:00:00', strtotime("-{$dateRange} days"));

echo "<h1>📊 Analytics Numbers Breakdown</h1>";
echo "<p><strong>Date Range:</strong> Last {$dateRange} days (from {$dateFrom})</p>";
echo "<hr>";

try {
    // 1. Total Page Views
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM page_views WHERE viewed_at >= ?");
    $stmt->execute([$dateFrom]);
    $totalPageViews = $stmt->fetchColumn();
    
    echo "<h2>1️⃣ Total Page Views: " . number_format($totalPageViews) . "</h2>";
    echo "<p>Counts <strong>ALL</strong> page views in selected date range (homepage, country pages, contact, etc.)</p>";
    
    // Breakdown by page type
    $stmt = $pdo->prepare("
        SELECT 
            CASE 
                WHEN page_url LIKE '%country.php%' THEN 'Country Detail Pages'
                WHEN page_url LIKE '%index.php%' OR page_url = '/' OR page_url LIKE '%arrivalcards.com' THEN 'Homepage'
                WHEN page_url LIKE '%contact%' THEN 'Contact Page'
                WHEN page_url LIKE '%privacy%' THEN 'Privacy Page'
                ELSE 'Other Pages'
            END as page_type,
            COUNT(*) as count
        FROM page_views
        WHERE viewed_at >= ?
        GROUP BY page_type
        ORDER BY count DESC
    ");
    $stmt->execute([$dateFrom]);
    $pageBreakdown = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='8' style='margin: 10px 0; border-collapse: collapse;'>";
    echo "<tr><th>Page Type</th><th>Count</th><th>% of Total</th></tr>";
    foreach ($pageBreakdown as $row) {
        $percentage = $totalPageViews > 0 ? round(($row['count'] / $totalPageViews) * 100, 1) : 0;
        echo "<tr><td>{$row['page_type']}</td><td>" . number_format($row['count']) . "</td><td>{$percentage}%</td></tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    
    // 2. Unique Visitors
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT visitor_ip) FROM page_views WHERE viewed_at >= ?");
    $stmt->execute([$dateFrom]);
    $uniqueVisitors = $stmt->fetchColumn();
    
    echo "<h2>2️⃣ Unique Visitors: " . number_format($uniqueVisitors) . "</h2>";
    echo "<p>Counts <strong>DISTINCT IP addresses</strong> that visited ANY page</p>";
    echo "<p><em>Note: Same visitor viewing multiple pages = 1 unique visitor</em></p>";
    
    echo "<hr>";
    
    // 3. Country Detail Page Views (only pages with country_id)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM page_views 
        WHERE viewed_at >= ? AND country_id IS NOT NULL
    ");
    $stmt->execute([$dateFrom]);
    $countryPageViews = $stmt->fetchColumn();
    
    echo "<h2>3️⃣ Country Detail Page Views: " . number_format($countryPageViews) . "</h2>";
    echo "<p>Counts <strong>ONLY</strong> views where country_id is set (country detail pages)</p>";
    echo "<p><em>This is what 'Top Countries Viewed' section uses</em></p>";
    
    // Top countries from page_views
    $stmt = $pdo->prepare("
        SELECT 
            c.country_code,
            ct.country_name,
            COUNT(*) as view_count
        FROM page_views pv
        INNER JOIN countries c ON pv.country_id = c.id
        LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
        WHERE pv.viewed_at >= ? AND pv.country_id IS NOT NULL
        GROUP BY c.id, c.country_code, ct.country_name
        ORDER BY view_count DESC
        LIMIT 5
    ");
    $stmt->execute([$dateFrom]);
    $topCountries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='8' style='margin: 10px 0; border-collapse: collapse;'>";
    echo "<tr><th>Country</th><th>Views (in date range)</th></tr>";
    foreach ($topCountries as $row) {
        echo "<tr><td>{$row['country_name']} ({$row['country_code']})</td><td>" . number_format($row['view_count']) . "</td></tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    
    // 4. Browser Breakdown
    $stmt = $pdo->prepare("
        SELECT 
            browser,
            COUNT(*) as views
        FROM page_views
        WHERE viewed_at >= ? AND browser IS NOT NULL
        GROUP BY browser
        ORDER BY views DESC
    ");
    $stmt->execute([$dateFrom]);
    $browsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $totalBrowserViews = array_sum(array_column($browsers, 'views'));
    
    echo "<h2>4️⃣ Browser Breakdown: " . number_format($totalBrowserViews) . " total views</h2>";
    echo "<p>Counts ALL page views grouped by browser (should match Total Page Views if all have browser info)</p>";
    
    echo "<table border='1' cellpadding='8' style='margin: 10px 0; border-collapse: collapse;'>";
    echo "<tr><th>Browser</th><th>Views</th><th>% of Total</th></tr>";
    foreach ($browsers as $row) {
        $percentage = $totalBrowserViews > 0 ? round(($row['views'] / $totalBrowserViews) * 100, 1) : 0;
        echo "<tr><td>{$row['browser']}</td><td>" . number_format($row['views']) . "</td><td>{$percentage}%</td></tr>";
    }
    $totalBrowserSum = array_sum(array_column($browsers, 'views'));
    echo "<tr style='font-weight: bold; background: #f0f0f0;'><td>TOTAL</td><td>" . number_format($totalBrowserSum) . "</td><td>100%</td></tr>";
    echo "</table>";
    
    echo "<hr>";
    
    // 5. Countries Table View Count (All-Time)
    $stmt = $pdo->query("
        SELECT 
            SUM(view_count) as total_clicks
        FROM countries
    ");
    $allTimeClicks = $stmt->fetchColumn();
    
    echo "<h2>5️⃣ All-Time Country Card Clicks: " . number_format($allTimeClicks) . "</h2>";
    echo "<p>This is the <strong>cumulative counter</strong> from countries.view_count column</p>";
    echo "<p><em>Incremented when users click 'View Details' on homepage cards</em></p>";
    echo "<p style='color: #f59e0b;'><strong>⚠️ This is ALL-TIME data, not filtered by date range!</strong></p>";
    
    echo "<hr>";
    
    // Summary
    echo "<h2>📋 SUMMARY - Why Numbers Don't Match</h2>";
    echo "<div style='background: #fef3c7; padding: 15px; border-radius: 8px; border: 2px solid #f59e0b;'>";
    echo "<ol style='margin: 0; padding-left: 20px;'>";
    echo "<li><strong>Total Page Views ({$totalPageViews})</strong> = ALL pages (homepage + country pages + other)</li>";
    echo "<li><strong>Unique Visitors ({$uniqueVisitors})</strong> = Distinct IPs (one person viewing multiple pages = 1 visitor)</li>";
    echo "<li><strong>Country Page Views ({$countryPageViews})</strong> = Only country detail pages</li>";
    echo "<li><strong>Browser Total ({$totalBrowserViews})</strong> = Should match Total Page Views (if browser data captured)</li>";
    echo "<li><strong>All-Time Clicks ({$allTimeClicks})</strong> = Cumulative forever, NOT filtered by date range</li>";
    echo "</ol>";
    echo "</div>";
    
    echo "<hr>";
    echo "<h3>✅ These SHOULD Match:</h3>";
    echo "<ul>";
    echo "<li><strong>Total Page Views</strong> ({$totalPageViews}) ≈ <strong>Browser Total</strong> ({$totalBrowserViews})";
    if ($totalPageViews == $totalBrowserViews) {
        echo " <span style='color: green;'>✅ MATCH</span>";
    } else {
        $diff = abs($totalPageViews - $totalBrowserViews);
        echo " <span style='color: orange;'>⚠️ Difference: {$diff} (likely missing browser data)</span>";
    }
    echo "</li>";
    echo "</ul>";
    
    echo "<h3>❌ These WON'T Match (Different Metrics):</h3>";
    echo "<ul>";
    echo "<li>Total Page Views ({$totalPageViews}) vs Country Page Views ({$countryPageViews}) - <em>Country pages are subset of all pages</em></li>";
    echo "<li>Total Page Views ({$totalPageViews}) vs Unique Visitors ({$uniqueVisitors}) - <em>Visitors can view multiple pages</em></li>";
    echo "<li>Date-filtered views vs All-Time Country Clicks - <em>Different time periods</em></li>";
    echo "</ul>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
