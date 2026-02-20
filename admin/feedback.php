<?php
/**
 * Admin - Feedback Dashboard
 * View and manage user feedback on country pages
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (verifyCSRFToken($csrfToken)) {
        $deleteId = (int)$_POST['delete_id'];
        $stmt = $pdo->prepare("DELETE FROM country_feedback WHERE id = ?");
        $stmt->execute([$deleteId]);
        logAdminAction('Deleted feedback', 'country_feedback', $deleteId);
        setFlashMessage('Feedback entry deleted.', 'success');
        header('Location: feedback.php');
        exit;
    }
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 25;
$offset = ($page - 1) * $perPage;

// Filters
$filterType = $_GET['type'] ?? '';
$filterCountry = $_GET['country'] ?? '';

// Build query
$where = [];
$params = [];

if ($filterType && in_array($filterType, ['helpful', 'not_helpful'])) {
    $where[] = "cf.feedback_type = ?";
    $params[] = $filterType;
}
if ($filterCountry) {
    $where[] = "c.country_code = ?";
    $params[] = $filterCountry;
}

$whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Get total count
$countSql = "SELECT COUNT(*) FROM country_feedback cf 
             LEFT JOIN countries c ON cf.country_id = c.id 
             $whereClause";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$totalItems = $stmt->fetchColumn();
$totalPages = max(1, ceil($totalItems / $perPage));

// Get feedback entries
$sql = "SELECT cf.*, c.country_code, c.flag_emoji, 
               ct.country_name
        FROM country_feedback cf
        LEFT JOIN countries c ON cf.country_id = c.id
        LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
        $whereClause
        ORDER BY cf.created_at DESC
        LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$feedback = $stmt->fetchAll();

// Get summary stats
$statsStmt = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN feedback_type = 'helpful' THEN 1 ELSE 0 END) as helpful_count,
        SUM(CASE WHEN feedback_type = 'not_helpful' THEN 1 ELSE 0 END) as not_helpful_count,
        COUNT(DISTINCT country_id) as countries_with_feedback
    FROM country_feedback
");
$stats = $statsStmt->fetch();

// Get top-rated and worst-rated countries
$topRatedStmt = $pdo->query("
    SELECT c.country_code, c.flag_emoji, ct.country_name,
           c.helpful_yes, c.helpful_no,
           (c.helpful_yes - c.helpful_no) as net_score
    FROM countries c
    LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
    WHERE c.helpful_yes > 0 OR c.helpful_no > 0
    ORDER BY net_score DESC
    LIMIT 5
");
$topRated = $topRatedStmt->fetchAll();

$worstRatedStmt = $pdo->query("
    SELECT c.country_code, c.flag_emoji, ct.country_name,
           c.helpful_yes, c.helpful_no,
           (c.helpful_yes - c.helpful_no) as net_score
    FROM countries c
    LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
    WHERE c.helpful_yes > 0 OR c.helpful_no > 0
    ORDER BY net_score ASC
    LIMIT 5
");
$worstRated = $worstRatedStmt->fetchAll();

$pageTitle = 'User Feedback - Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?></title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/admin.css">
    <style>
        .feedback-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .fb-stat { background: white; border-radius: 10px; padding: 1.25rem; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
        .fb-stat .number { font-size: 2rem; font-weight: 700; }
        .fb-stat .label { font-size: 0.85rem; color: #6b7280; margin-top: 0.25rem; }
        .fb-stat.positive .number { color: #059669; }
        .fb-stat.negative .number { color: #dc2626; }
        .fb-stat.neutral .number { color: #2563eb; }
        .ranking-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        .ranking-table td, .ranking-table th { padding: 0.5rem 0.75rem; text-align: left; border-bottom: 1px solid #f3f4f6; }
        .ranking-table th { font-weight: 600; color: #6b7280; font-size: 0.8rem; text-transform: uppercase; }
        .score-badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px; font-weight: 700; font-size: 0.8rem; }
        .score-positive { background: #d1fae5; color: #065f46; }
        .score-negative { background: #fee2e2; color: #991b1b; }
        .score-neutral { background: #f3f4f6; color: #6b7280; }
        .filter-bar { display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: center; flex-wrap: wrap; }
        .filter-bar select { padding: 0.5rem 1rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; }
        .side-by-side { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }
        @media (max-width: 768px) { .side-by-side { grid-template-columns: 1fr; } }
        .panel { background: white; border-radius: 10px; padding: 1.25rem; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
        .panel h3 { margin: 0 0 1rem; font-size: 1rem; color: #374151; }
        .pagination { display: flex; gap: 0.5rem; justify-content: center; margin-top: 1.5rem; }
        .pagination a, .pagination span { padding: 0.4rem 0.75rem; border-radius: 6px; border: 1px solid #d1d5db; font-size: 0.85rem; text-decoration: none; color: #374151; }
        .pagination .current { background: #2563eb; color: white; border-color: #2563eb; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <h1>📊 User Feedback</h1>
        <p style="color: var(--text-secondary); margin-bottom: 2rem;">
            Monitor how users rate country page information quality.
        </p>

        <!-- Stats Overview -->
        <div class="feedback-stats">
            <div class="fb-stat neutral">
                <div class="number"><?php echo number_format($stats['total']); ?></div>
                <div class="label">Total Votes</div>
            </div>
            <div class="fb-stat positive">
                <div class="number"><?php echo number_format($stats['helpful_count']); ?></div>
                <div class="label">👍 Helpful</div>
            </div>
            <div class="fb-stat negative">
                <div class="number"><?php echo number_format($stats['not_helpful_count']); ?></div>
                <div class="label">👎 Not Helpful</div>
            </div>
            <div class="fb-stat neutral">
                <div class="number"><?php echo number_format($stats['countries_with_feedback']); ?></div>
                <div class="label">Countries Rated</div>
            </div>
            <div class="fb-stat <?php echo ($stats['total'] > 0 && ($stats['helpful_count'] / max($stats['total'], 1) * 100) >= 70) ? 'positive' : 'negative'; ?>">
                <div class="number"><?php echo $stats['total'] > 0 ? round($stats['helpful_count'] / $stats['total'] * 100) : 0; ?>%</div>
                <div class="label">Satisfaction Rate</div>
            </div>
        </div>

        <!-- Top/Worst Rated Side by Side -->
        <div class="side-by-side">
            <div class="panel">
                <h3>🏆 Highest Rated Pages</h3>
                <?php if (empty($topRated)): ?>
                    <p style="color: #9ca3af; font-size: 0.9rem;">No feedback yet.</p>
                <?php else: ?>
                    <table class="ranking-table">
                        <tr><th>Country</th><th>👍</th><th>👎</th><th>Score</th></tr>
                        <?php foreach ($topRated as $row): ?>
                        <tr>
                            <td><?php echo $row['flag_emoji']; ?> <?php echo e($row['country_name']); ?></td>
                            <td><?php echo (int)$row['helpful_yes']; ?></td>
                            <td><?php echo (int)$row['helpful_no']; ?></td>
                            <td><span class="score-badge <?php echo $row['net_score'] >= 0 ? 'score-positive' : 'score-negative'; ?>">
                                <?php echo $row['net_score'] >= 0 ? '+' : ''; ?><?php echo (int)$row['net_score']; ?>
                            </span></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
            <div class="panel">
                <h3>⚠️ Needs Improvement</h3>
                <?php if (empty($worstRated)): ?>
                    <p style="color: #9ca3af; font-size: 0.9rem;">No feedback yet.</p>
                <?php else: ?>
                    <table class="ranking-table">
                        <tr><th>Country</th><th>👍</th><th>👎</th><th>Score</th></tr>
                        <?php foreach ($worstRated as $row): ?>
                        <tr>
                            <td><?php echo $row['flag_emoji']; ?> <?php echo e($row['country_name']); ?></td>
                            <td><?php echo (int)$row['helpful_yes']; ?></td>
                            <td><?php echo (int)$row['helpful_no']; ?></td>
                            <td><span class="score-badge <?php echo $row['net_score'] >= 0 ? 'score-positive' : 'score-negative'; ?>">
                                <?php echo $row['net_score'] >= 0 ? '+' : ''; ?><?php echo (int)$row['net_score']; ?>
                            </span></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Filters -->
        <div class="panel" style="margin-bottom: 1.5rem;">
            <form method="GET" class="filter-bar">
                <label style="font-weight: 600; color: #374151;">Filters:</label>
                <select name="type">
                    <option value="">All Types</option>
                    <option value="helpful" <?php echo $filterType === 'helpful' ? 'selected' : ''; ?>>👍 Helpful</option>
                    <option value="not_helpful" <?php echo $filterType === 'not_helpful' ? 'selected' : ''; ?>>👎 Not Helpful</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                <?php if ($filterType || $filterCountry): ?>
                    <a href="feedback.php" class="btn btn-secondary btn-sm">Clear</a>
                <?php endif; ?>
                <span style="margin-left: auto; color: #9ca3af; font-size: 0.85rem;">
                    Showing <?php echo number_format($totalItems); ?> entries
                </span>
            </form>
        </div>

        <!-- Feedback Log Table -->
        <div class="panel">
            <h3>Recent Feedback Log</h3>
            <?php if (empty($feedback)): ?>
                <p style="color: #9ca3af;">No feedback entries found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Country</th>
                                <th>Vote</th>
                                <th>IP</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($feedback as $fb): ?>
                            <tr>
                                <td style="white-space: nowrap; font-size: 0.85rem; color: #6b7280;">
                                    <?php echo date('M j, Y H:i', strtotime($fb['created_at'])); ?>
                                </td>
                                <td>
                                    <?php echo $fb['flag_emoji'] ?? ''; ?> 
                                    <?php echo e($fb['country_name'] ?? 'Unknown'); ?>
                                    <span style="color: #9ca3af; font-size: 0.8rem;">(<?php echo e($fb['country_code'] ?? ''); ?>)</span>
                                </td>
                                <td>
                                    <?php if ($fb['feedback_type'] === 'helpful'): ?>
                                        <span class="score-badge score-positive">👍 Helpful</span>
                                    <?php else: ?>
                                        <span class="score-badge score-negative">👎 Not Helpful</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-size: 0.8rem; color: #9ca3af; font-family: monospace;">
                                    <?php echo e($fb['ip_address'] ?? ''); ?>
                                </td>
                                <td>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this feedback entry?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <input type="hidden" name="delete_id" value="<?php echo (int)$fb['id']; ?>">
                                        <button type="submit" class="btn btn-secondary btn-sm" style="color: #dc2626; font-size: 0.75rem;">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php
                    $queryParams = $_GET;
                    for ($i = 1; $i <= $totalPages; $i++):
                        $queryParams['page'] = $i;
                        $url = 'feedback.php?' . http_build_query($queryParams);
                    ?>
                        <?php if ($i === $page): ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="<?php echo e($url); ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
