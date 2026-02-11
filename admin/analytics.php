<?php
/**
 * Analytics Dashboard - Comprehensive Traffic Reports
 * Shows detailed site traffic, locations, dwell times, and popular countries
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$pageTitle = 'Analytics Dashboard';

// Check if analytics tables exist
$tablesExist = false;
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'page_views'");
    $tablesExist = $stmt->rowCount() > 0;
} catch (PDOException $e) {
    $tablesExist = false;
}

// If tables don't exist, show setup message
if (!$tablesExist) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo e($pageTitle); ?> - Arrival Cards</title>
        <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
        <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/admin.css">
    </head>
    <body>
        <?php include __DIR__ . '/includes/admin_header.php'; ?>
        
        <div class="admin-container">
            <div style="max-width: 600px; margin: 4rem auto; text-align: center;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📊</div>
                <h1>Analytics Setup Required</h1>
                <p style="color: var(--text-secondary); margin: 1.5rem 0;">
                    The analytics database tables haven't been created yet. 
                    Click the button below to run the one-time setup.
                </p>
                <a href="setup_analytics.php" class="btn btn-primary" style="margin-top: 1rem;">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" style="margin-right: 0.5rem;">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                    </svg>
                    Run Analytics Setup
                </a>
                <p style="color: var(--text-light); font-size: 0.875rem; margin-top: 2rem;">
                    This will create the necessary database tables to track:<br>
                    Page views, Visitor sessions, Geographic data, Device info, and more
                </p>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Date range filter
$dateRange = isset($_GET['range']) ? $_GET['range'] : '7';
$dateFrom = date('Y-m-d 00:00:00', strtotime("-{$dateRange} days"));
$dateTo = date('Y-m-d 23:59:59');

try {
    // Total page views
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM page_views WHERE viewed_at >= ?");
    $stmt->execute([$dateFrom]);
    $totalPageViews = $stmt->fetchColumn();

    // Unique visitors (by IP)
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT visitor_ip) FROM page_views WHERE viewed_at >= ?");
    $stmt->execute([$dateFrom]);
    $uniqueVisitors = $stmt->fetchColumn();

    // Average session duration (in seconds)
    $stmt = $pdo->prepare("
        SELECT AVG(session_duration) 
        FROM page_views 
        WHERE viewed_at >= ? AND session_duration > 0
    ");
    $stmt->execute([$dateFrom]);
    $avgSessionDuration = round($stmt->fetchColumn() ?? 0);

    // Bounce rate (single page sessions)
    $stmt = $pdo->prepare("
        SELECT 
            (SELECT COUNT(DISTINCT session_id) FROM page_views WHERE viewed_at >= ? 
             GROUP BY session_id HAVING COUNT(*) = 1) * 100.0 / 
            COUNT(DISTINCT session_id) as bounce_rate
        FROM page_views 
        WHERE viewed_at >= ?
    ");
    $stmt->execute([$dateFrom, $dateFrom]);
    $bounceRate = round($stmt->fetchColumn() ?? 0, 1);
} catch (PDOException $e) {
    // If there's an error, redirect to setup
    header('Location: setup_analytics.php?error=' . urlencode($e->getMessage()));
    exit;
}

// Top 10 countries viewed (from page_views tracking)
$stmt = $pdo->prepare("
    SELECT 
        c.country_code,
        ct.country_name,
        COUNT(*) as view_count,
        COUNT(DISTINCT pv.visitor_ip) as unique_viewers,
        AVG(pv.session_duration) as avg_time_on_page
    FROM page_views pv
    INNER JOIN countries c ON pv.country_id = c.id
    LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
    WHERE pv.viewed_at >= ? AND pv.country_id IS NOT NULL
    GROUP BY c.id, c.country_code, ct.country_name
    ORDER BY view_count DESC
    LIMIT 10
");
$stmt->execute([$dateFrom]);
$topCountriesDetailed = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Top 10 countries by total view count (all time from countries table)
$stmt = $pdo->query("
    SELECT 
        c.country_code,
        ct.country_name,
        c.view_count,
        c.id as country_id
    FROM countries c
    LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
    WHERE c.view_count > 0
    ORDER BY c.view_count DESC
    LIMIT 10
");
$topCountries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traffic by location (visitor countries)
$stmt = $pdo->prepare("
    SELECT 
        visitor_country,
        COUNT(*) as visits,
        COUNT(DISTINCT visitor_ip) as unique_visitors
    FROM page_views
    WHERE viewed_at >= ? AND visitor_country IS NOT NULL AND visitor_country != ''
    GROUP BY visitor_country
    ORDER BY visits DESC
    LIMIT 15
");
$stmt->execute([$dateFrom]);
$trafficByLocation = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traffic by day (last 7/30 days)
$stmt = $pdo->prepare("
    SELECT 
        DATE(viewed_at) as view_date,
        COUNT(*) as page_views,
        COUNT(DISTINCT visitor_ip) as unique_visitors,
        AVG(session_duration) as avg_duration
    FROM page_views
    WHERE viewed_at >= ?
    GROUP BY DATE(viewed_at)
    ORDER BY view_date ASC
");
$stmt->execute([$dateFrom]);
$dailyTraffic = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traffic by hour (today)
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

// Top pages
$stmt = $pdo->prepare("
    SELECT 
        page_url,
        page_title,
        COUNT(*) as views,
        AVG(session_duration) as avg_time
    FROM page_views
    WHERE viewed_at >= ?
    GROUP BY page_url, page_title
    ORDER BY views DESC
    LIMIT 10
");
$stmt->execute([$dateFrom]);
$topPages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Device breakdown
$stmt = $pdo->prepare("
    SELECT 
        device_type,
        COUNT(*) as views,
        COUNT(DISTINCT visitor_ip) as unique_visitors
    FROM page_views
    WHERE viewed_at >= ?
    GROUP BY device_type
    ORDER BY views DESC
");
$stmt->execute([$dateFrom]);
$deviceBreakdown = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Browser breakdown
$stmt = $pdo->prepare("
    SELECT 
        browser,
        COUNT(*) as views
    FROM page_views
    WHERE viewed_at >= ? AND browser IS NOT NULL
    GROUP BY browser
    ORDER BY views DESC
    LIMIT 8
");
$stmt->execute([$dateFrom]);
$browserBreakdown = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format duration helper
function formatDuration($seconds) {
    if ($seconds < 60) return round($seconds) . 's';
    if ($seconds < 3600) return round($seconds / 60) . 'm ' . ($seconds % 60) . 's';
    return round($seconds / 3600, 1) . 'h';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> - Arrival Cards</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .analytics-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .date-filter {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        .date-filter select {
            padding: 0.5rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            background: white;
            cursor: pointer;
        }
        
        .chart-container {
            position: relative;
            height: 350px;
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            border: 2px solid var(--border-color);
            margin-bottom: 1.5rem;
        }
        
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .analytics-table {
            background: white;
            border-radius: var(--radius-lg);
            border: 2px solid var(--border-color);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .analytics-table h3 {
            padding: 1rem 1.5rem;
            margin: 0;
            background: var(--bg-secondary);
            border-bottom: 2px solid var(--border-color);
            font-size: 1rem;
        }
        
        .analytics-table table {
            width: 100%;
        }
        
        .analytics-table th {
            background: var(--bg-tertiary);
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.813rem;
            font-weight: 600;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
        }
        
        .analytics-table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.875rem;
        }
        
        .analytics-table tr:last-child td {
            border-bottom: none;
        }
        
        .analytics-table tr:hover {
            background: var(--bg-secondary);
        }
        
        .country-flag-small {
            width: 24px;
            height: 16px;
            object-fit: cover;
            border-radius: 2px;
            margin-right: 0.5rem;
            vertical-align: middle;
        }
        
        .metric-badge {
            display: inline-block;
            padding: 0.25rem 0.625rem;
            background: var(--bg-tertiary);
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
        }
        
        .metric-badge.success {
            background: #d1fae5;
            color: #065f46;
        }
        
        .metric-badge.warning {
            background: #fef3c7;
            color: #92400e;
        }
        
        .progress-bar {
            height: 6px;
            background: var(--bg-tertiary);
            border-radius: 3px;
            overflow: hidden;
            margin-top: 0.5rem;
        }
        
        .progress-fill {
            height: 100%;
            background: var(--primary-color);
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .chart-grid {
                grid-template-columns: 1fr;
            }
            
            .chart-container {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <div class="analytics-header">
            <div>
                <h1>📊 Analytics Dashboard</h1>
                <p style="color: var(--text-secondary); margin: 0;">
                    Detailed traffic reports and visitor insights
                </p>
            </div>
            
            <div class="date-filter">
                <label for="dateRange" style="font-size: 0.875rem; font-weight: 600;">Time Range:</label>
                <select id="dateRange" onchange="window.location.href='?range='+this.value">
                    <option value="1" <?php echo $dateRange == '1' ? 'selected' : ''; ?>>Last 24 Hours</option>
                    <option value="7" <?php echo $dateRange == '7' ? 'selected' : ''; ?>>Last 7 Days</option>
                    <option value="30" <?php echo $dateRange == '30' ? 'selected' : ''; ?>>Last 30 Days</option>
                    <option value="90" <?php echo $dateRange == '90' ? 'selected' : ''; ?>>Last 90 Days</option>
                </select>
            </div>
        </div>
        
        <!-- Key Metrics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #dbeafe;">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="#2563eb">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Page Views</div>
                    <div class="stat-value"><?php echo number_format($totalPageViews); ?></div>
                    <div class="stat-sublabel">Last <?php echo $dateRange; ?> days</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #d1fae5;">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="#10b981">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Unique Visitors</div>
                    <div class="stat-value"><?php echo number_format($uniqueVisitors); ?></div>
                    <div class="stat-sublabel">Distinct IP addresses</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #fef3c7;">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="#f59e0b">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Avg. Session Duration</div>
                    <div class="stat-value"><?php echo formatDuration($avgSessionDuration); ?></div>
                    <div class="stat-sublabel">Time on site</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #fee2e2;">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="#ef4444">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Bounce Rate</div>
                    <div class="stat-value"><?php echo $bounceRate; ?>%</div>
                    <div class="stat-sublabel">Single page sessions</div>
                </div>
            </div>
        </div>
        
        <!-- Charts -->
        <div class="chart-grid">
            <div class="chart-container">
                <h3 style="margin: 0 0 1rem 0;">Daily Traffic Trend</h3>
                <canvas id="dailyTrafficChart"></canvas>
            </div>
            
            <div class="chart-container">
                <h3 style="margin: 0 0 1rem 0;">Traffic by Hour (Today)</h3>
                <canvas id="hourlyTrafficChart"></canvas>
            </div>
        </div>
        
        <!-- Top Countries Viewed -->
        <div class="analytics-table">
            <h3>🌍 Most Viewed Countries (All Time)</h3>
            <p style="color: #6b7280; font-size: 0.875rem; margin: -0.5rem 0 1rem 0;">Total cumulative views from country cards</p>
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Country</th>
                        <th>Total Views</th>
                        <th>Unique Viewers</th>
                        <th>Avg. Time on Page</th>
                        <th>Engagement</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($topCountries)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                                No country view data available yet
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($topCountries as $index => $country): ?>
                            <tr>
                                <td><strong>#<?php echo $index + 1; ?></strong></td>
                                <td>
                                    <img src="<?php echo APP_URL; ?>/assets/images/flags/<?php echo strtolower($country['country_code']); ?>.svg" 
                                         class="country-flag-small" 
                                         onerror="this.style.display='none'">
                                    <strong><?php echo e($country['country_name']); ?></strong>
                                </td>
                                <td><?php echo number_format($country['view_count']); ?></td>
                                <td>
                                    <?php 
                                    if (isset($country['unique_viewers'])) {
                                        echo number_format($country['unique_viewers']);
                                    } else {
                                        echo '<span style="color: #9ca3af;">—</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    if (isset($country['avg_time_on_page'])) {
                                        echo formatDuration($country['avg_time_on_page'] ?? 0);
                                    } else {
                                        echo '<span style="color: #9ca3af;">—</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    $avgTime = $country['avg_time_on_page'] ?? null;
                                    if ($avgTime === null) {
                                        echo '<span style="color: #9ca3af;">—</span>';
                                    } elseif ($avgTime > 60) {
                                        echo '<span class="metric-badge success">High</span>';
                                    } elseif ($avgTime > 30) {
                                        echo '<span class="metric-badge">Medium</span>';
                                    } else {
                                        echo '<span class="metric-badge warning">Low</span>';
                                    }
                                    ?>
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Traffic by Visitor Location -->
        <div class="analytics-table">
            <h3>📍 Traffic by Visitor Location</h3>
            <table>
                <thead>
                    <tr>
                        <th>Visitor Country</th>
                        <th>Total Visits</th>
                        <th>Unique Visitors</th>
                        <th>% of Total Traffic</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($trafficByLocation)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                                No location data available yet
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($trafficByLocation as $location): ?>
                            <?php $percentage = $totalPageViews > 0 ? ($location['visits'] / $totalPageViews * 100) : 0; ?>
                            <tr>
                                <td><strong><?php echo e($location['visitor_country']); ?></strong></td>
                                <td><?php echo number_format($location['visits']); ?></td>
                                <td><?php echo number_format($location['unique_visitors']); ?></td>
                                <td>
                                    <?php echo number_format($percentage, 1); ?>%
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo min($percentage, 100); ?>%;"></div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Device & Browser Breakdown -->
        <div class="chart-grid">
            <div class="analytics-table">
                <h3>📱 Traffic by Device</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Device Type</th>
                            <th>Views</th>
                            <th>Unique Visitors</th>
                            <th>% Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($deviceBreakdown)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 1.5rem; color: var(--text-secondary);">
                                    No device data available
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($deviceBreakdown as $device): ?>
                                <?php $percentage = $totalPageViews > 0 ? ($device['views'] / $totalPageViews * 100) : 0; ?>
                                <tr>
                                    <td><strong><?php echo e(ucfirst($device['device_type'] ?? 'Unknown')); ?></strong></td>
                                    <td><?php echo number_format($device['views']); ?></td>
                                    <td><?php echo number_format($device['unique_visitors']); ?></td>
                                    <td><?php echo number_format($percentage, 1); ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="analytics-table">
                <h3>🌐 Traffic by Browser</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Browser</th>
                            <th>Views</th>
                            <th>% Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($browserBreakdown)): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 1.5rem; color: var(--text-secondary);">
                                    No browser data available
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($browserBreakdown as $browser): ?>
                                <?php $percentage = $totalPageViews > 0 ? ($browser['views'] / $totalPageViews * 100) : 0; ?>
                                <tr>
                                    <td><strong><?php echo e($browser['browser'] ?? 'Unknown'); ?></strong></td>
                                    <td><?php echo number_format($browser['views']); ?></td>
                                    <td><?php echo number_format($percentage, 1); ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Top Pages -->
        <div class="analytics-table">
            <h3>📄 Most Visited Pages</h3>
            <table>
                <thead>
                    <tr>
                        <th>Page Title</th>
                        <th>URL</th>
                        <th>Views</th>
                        <th>Avg. Time on Page</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($topPages)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                                No page view data available yet
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($topPages as $page): ?>
                            <tr>
                                <td><strong><?php echo e($page['page_title'] ?? 'Untitled'); ?></strong></td>
                                <td style="font-size: 0.813rem; color: var(--text-secondary);">
                                    <?php echo e(substr($page['page_url'], 0, 60)); ?><?php echo strlen($page['page_url']) > 60 ? '...' : ''; ?>
                                </td>
                                <td><?php echo number_format($page['views']); ?></td>
                                <td><?php echo formatDuration($page['avg_time'] ?? 0); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // Daily Traffic Chart
        const dailyLabels = <?php echo json_encode(array_map(function($d) { 
            return date('M d', strtotime($d['view_date'])); 
        }, $dailyTraffic)); ?>;
        
        const dailyPageViews = <?php echo json_encode(array_map(function($d) { 
            return $d['page_views']; 
        }, $dailyTraffic)); ?>;
        
        const dailyUniqueVisitors = <?php echo json_encode(array_map(function($d) { 
            return $d['unique_visitors']; 
        }, $dailyTraffic)); ?>;
        
        new Chart(document.getElementById('dailyTrafficChart'), {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Page Views',
                    data: dailyPageViews,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Unique Visitors',
                    data: dailyUniqueVisitors,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Hourly Traffic Chart
        const hourlyLabels = <?php 
            $hours = array_fill(0, 24, 0);
            foreach ($hourlyTraffic as $h) {
                $hours[$h['hour']] = $h['views'];
            }
            echo json_encode(array_map(function($i) { 
                return str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; 
            }, range(0, 23)));
        ?>;
        
        const hourlyData = <?php echo json_encode(array_values($hours)); ?>;
        
        new Chart(document.getElementById('hourlyTrafficChart'), {
            type: 'bar',
            data: {
                labels: hourlyLabels,
                datasets: [{
                    label: 'Views',
                    data: hourlyData,
                    backgroundColor: 'rgba(249, 115, 22, 0.8)',
                    borderColor: '#f97316',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
    
    <script src="<?php echo APP_URL; ?>/assets/js/admin.js"></script>
</body>
</html>
