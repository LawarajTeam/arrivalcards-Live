<?php
/**
 * Quick diagnostic to check analytics data
 */

require_once __DIR__ . '/includes/config.php';

echo "<h2>Analytics Data Diagnostic</h2>";

try {
    // Check if tables exist
    $stmt = $pdo->query("SHOW TABLES LIKE 'page_views'");
    if ($stmt->rowCount() == 0) {
        echo "<p style='color: red;'>❌ page_views table does NOT exist!</p>";
        exit;
    }
    echo "<p style='color: green;'>✅ page_views table exists</p>";

    // Total records
    $stmt = $pdo->query("SELECT COUNT(*) FROM page_views");
    $total = $stmt->fetchColumn();
    echo "<p><strong>Total page_views records:</strong> " . number_format($total) . "</p>";

    if ($total == 0) {
        echo "<p style='color: orange;'>⚠️ No data recorded yet. Visit some pages on the site to generate data.</p>";
        exit;
    }

    // Daily data (last 7 days)
    echo "<h3>Daily Views (Last 7 Days)</h3>";
    $stmt = $pdo->query("
        SELECT 
            DATE(viewed_at) as view_date,
            COUNT(*) as views
        FROM page_views
        WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY DATE(viewed_at)
        ORDER BY view_date DESC
    ");
    $dailyData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($dailyData)) {
        echo "<p style='color: orange;'>No data in last 7 days</p>";
    } else {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Date</th><th>Views</th></tr>";
        foreach ($dailyData as $row) {
            echo "<tr><td>{$row['view_date']}</td><td>{$row['views']}</td></tr>";
        }
        echo "</table>";
    }

    // Hourly data (today)
    echo "<h3>Hourly Views (Today)</h3>";
    $stmt = $pdo->query("
        SELECT 
            HOUR(viewed_at) as hour,
            COUNT(*) as views
        FROM page_views
        WHERE DATE(viewed_at) = CURDATE()
        GROUP BY HOUR(viewed_at)
        ORDER BY hour
    ");
    $hourlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($hourlyData)) {
        echo "<p style='color: orange;'>⚠️ No views recorded today yet</p>";
        echo "<p>Current server time: " . date('Y-m-d H:i:s') . "</p>";
    } else {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Hour</th><th>Views</th></tr>";
        foreach ($hourlyData as $row) {
            echo "<tr><td>Hour {$row['hour']}</td><td>{$row['views']}</td></tr>";
        }
        echo "</table>";
    }

    // Sample records
    echo "<h3>Latest 5 Records</h3>";
    $stmt = $pdo->query("
        SELECT 
            page_url,
            page_title,
            viewed_at,
            visitor_ip
        FROM page_views
        ORDER BY viewed_at DESC
        LIMIT 5
    ");
    $latest = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Page</th><th>Time</th><th>IP</th></tr>";
    foreach ($latest as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['page_title']) . "</td>";
        echo "<td>{$row['viewed_at']}</td>";
        echo "<td>{$row['visitor_ip']}</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
