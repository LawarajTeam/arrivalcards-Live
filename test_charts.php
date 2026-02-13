<?php
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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chart Test</title>
    <script src="<?php echo APP_URL; ?>/assets/js/chart.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; }
        .chart-container { width: 100%; height: 400px; margin: 30px 0; background: #f9f9f9; padding: 20px; border-radius: 8px; }
        h2 { color: #333; }
    </style>
</head>
<body>
    <h1>🧪 Chart.js Test Page</h1>
    <p>If you see charts below, Chart.js is working. If not, check browser console for errors.</p>

    <div class="chart-container">
        <h2>Daily Traffic (Last 7 Days)</h2>
        <canvas id="dailyChart"></canvas>
    </div>

    <div class="chart-container">
        <h2>Hourly Traffic (Today)</h2>
        <canvas id="hourlyChart"></canvas>
    </div>

    <script>
        console.log('Chart.js loaded:', typeof Chart !== 'undefined');
        
        // Daily Traffic Chart
        const dailyLabels = <?php echo json_encode(array_map(function($d) { 
            return date('M d', strtotime($d['view_date'])); 
        }, $dailyTraffic)); ?>;
        
        const dailyData = <?php echo json_encode(array_map(function($d) { 
            return $d['page_views']; 
        }, $dailyTraffic)); ?>;
        
        console.log('Daily labels:', dailyLabels);
        console.log('Daily data:', dailyData);
        
        if (dailyLabels.length > 0) {
            new Chart(document.getElementById('dailyChart'), {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: 'Page Views',
                        data: dailyData,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
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
        } else {
            document.getElementById('dailyChart').parentElement.innerHTML += '<p style="color: red;">No daily data available</p>';
        }
        
        // Hourly Traffic Chart
        const hourlyRaw = <?php echo json_encode($hourlyTraffic); ?>;
        console.log('Hourly raw data:', hourlyRaw);
        
        const hours = Array(24).fill(0);
        hourlyRaw.forEach(h => {
            hours[h.hour] = h.views;
        });
        
        const hourlyLabels = Array.from({length: 24}, (_, i) => String(i).padStart(2, '0') + ':00');
        
        console.log('Hourly labels:', hourlyLabels);
        console.log('Hourly data:', hours);
        
        new Chart(document.getElementById('hourlyChart'), {
            type: 'bar',
            data: {
                labels: hourlyLabels,
                datasets: [{
                    label: 'Views',
                    data: hours,
                    backgroundColor: '#10b981'
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
        
        console.log('Charts initialized!');
    </script>

    <hr>
    <h3>Debug Info:</h3>
    <p>Chart.js CDN: https://cdn.jsdelivr.net/npm/chart.js</p>
    <p>Daily data points: <?php echo count($dailyTraffic); ?></p>
    <p>Hourly data points: <?php echo count($hourlyTraffic); ?></p>
    <p><strong>Check browser console (F12) for any JavaScript errors</strong></p>
</body>
</html>
