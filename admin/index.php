<?php
/**
 * Admin Dashboard
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$pageTitle = 'Admin Dashboard';

// Get statistics
$totalCountries = getCountryCount();

$stmt = $pdo->query("SELECT COUNT(*) FROM contact_submissions");
$totalContacts = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM contact_submissions WHERE is_read = 0");
$unreadContacts = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM languages WHERE is_active = 1");
$activeLanguages = $stmt->fetchColumn();

// Recent contact submissions
$stmt = $pdo->query("SELECT * FROM contact_submissions ORDER BY submitted_at DESC LIMIT 5");
$recentContacts = $stmt->fetchAll();

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
        <h1>Dashboard</h1>
        <p style="color: var(--text-secondary); margin-bottom: 2rem;">
            Welcome back, <?php echo e($_SESSION['admin_username']); ?>!
        </p>
        
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #dbeafe;">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="#2563eb">
                        <path fill-rule="evenodd" d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Countries</div>
                    <div class="stat-value"><?php echo $totalCountries; ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #d1fae5;">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="#10b981">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Contact Submissions</div>
                    <div class="stat-value"><?php echo $totalContacts; ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #fef3c7;">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="#f59e0b">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Unread Messages</div>
                    <div class="stat-value"><?php echo $unreadContacts; ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #e0e7ff;">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="#6366f1">
                        <path fill-rule="evenodd" d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a19.098 19.098 0 01-3.107 3.567 1 1 0 01-1.334-1.49 17.087 17.087 0 003.13-3.733 18.992 18.992 0 01-1.487-2.494 1 1 0 111.79-.89c.234.47.489.928.764 1.372.417-.934.752-1.913.997-2.927H3a1 1 0 110-2h3V3a1 1 0 011-1zm6 6a1 1 0 01.894.553l2.991 5.982a.869.869 0 01.02.037l.99 1.98a1 1 0 11-1.79.895L15.383 16h-4.764l-.724 1.447a1 1 0 11-1.788-.894l.99-1.98.019-.038 2.99-5.982A1 1 0 0113 8zm-1.382 6h2.764L13 11.236 11.618 14z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Active Languages</div>
                    <div class="stat-value"><?php echo $activeLanguages; ?></div>
                </div>
            </div>
        </div>
        
        <!-- Recent Contact Submissions -->
        <div class="admin-section">
            <div class="section-header">
                <h2>Recent Contact Submissions</h2>
                <a href="<?php echo APP_URL; ?>/admin/contacts.php" class="btn btn-secondary">View All</a>
            </div>
            
            <?php if (empty($recentContacts)): ?>
                <p style="color: var(--text-secondary); text-align: center; padding: 2rem;">
                    No contact submissions yet.
                </p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentContacts as $contact): ?>
                                <tr>
                                    <td><?php echo e($contact['name']); ?></td>
                                    <td><a href="mailto:<?php echo e($contact['email']); ?>"><?php echo e($contact['email']); ?></a></td>
                                    <td><?php echo e(truncate($contact['message'], 60)); ?></td>
                                    <td><?php echo formatDate($contact['submitted_at'], 'M d, Y g:i A'); ?></td>
                                    <td>
                                        <?php if ($contact['is_read']): ?>
                                            <span class="badge badge-success">Read</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Unread</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Quick Actions -->
        <div class="admin-section">
            <h2>Quick Actions</h2>
            <div class="action-grid">
                <a href="<?php echo APP_URL; ?>/admin/countries.php" class="action-card">
                    <svg width="32" height="32" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z" clip-rule="evenodd"/>
                    </svg>
                    <h3>Manage Countries</h3>
                    <p>Add, edit, or remove country information</p>
                </a>
                
                <a href="<?php echo APP_URL; ?>/admin/contacts.php" class="action-card">
                    <svg width="32" height="32" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    <h3>Contact Messages</h3>
                    <p>View and manage contact submissions</p>
                </a>
                
                <a href="<?php echo APP_URL; ?>/index.php" class="action-card" target="_blank">
                    <svg width="32" height="32" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    <h3>View Website</h3>
                    <p>Preview the public-facing site</p>
                </a>
                
                <a href="<?php echo APP_URL; ?>/test.php" class="action-card" target="_blank">
                    <svg width="32" height="32" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <h3>System Test</h3>
                    <p>Run diagnostics and verify setup</p>
                </a>
            </div>
        </div>
    </div>
    
    <script src="<?php echo APP_URL; ?>/assets/js/admin.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/js/main.js"></script>
</body>
</html>
