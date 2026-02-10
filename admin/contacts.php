<?php
/**
 * Admin - View Contact Submissions
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$pageTitle = 'Contact Submissions';

// Handle mark as read
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $id = (int)$_GET['mark_read'];
    $stmt = $pdo->prepare("UPDATE contact_submissions SET is_read = 1 WHERE id = ?");
    $stmt->execute([$id]);
    logAdminAction('Marked contact as read', 'contact_submissions', $id);
    setFlashMessage('Contact marked as read.', 'success');
    redirect(APP_URL . '/admin/contacts.php');
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM contact_submissions WHERE id = ?");
    $stmt->execute([$id]);
    logAdminAction('Deleted contact submission', 'contact_submissions', $id);
    setFlashMessage('Contact submission deleted.', 'success');
    redirect(APP_URL . '/admin/contacts.php');
}

// Get all contact submissions
$stmt = $pdo->query("SELECT * FROM contact_submissions ORDER BY submitted_at DESC");
$contacts = $stmt->fetchAll();
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
        <h1>Contact Submissions</h1>
        
        <?php if ($flash = getFlashMessage()): ?>
            <div class="alert alert-<?php echo e($flash['type']); ?>">
                <?php echo e($flash['message']); ?>
            </div>
        <?php endif; ?>
        
        <div class="admin-section">
            <?php if (empty($contacts)): ?>
                <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">
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
                                <th>Submitted</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contacts as $contact): ?>
                                <tr style="<?php echo !$contact['is_read'] ? 'background-color: #fef3c7;' : ''; ?>">
                                    <td><strong><?php echo e($contact['name']); ?></strong></td>
                                    <td>
                                        <a href="mailto:<?php echo e($contact['email']); ?>?subject=Re: Your message to Arrival Cards">
                                            <?php echo e($contact['email']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <details>
                                            <summary style="cursor: pointer; color: var(--primary-color);">
                                                <?php echo e(truncate($contact['message'], 50)); ?>
                                            </summary>
                                            <p style="margin-top: var(--spacing-sm); padding: var(--spacing-sm); background-color: var(--bg-secondary); border-radius: var(--radius-sm);">
                                                <?php echo nl2br(e($contact['message'])); ?>
                                            </p>
                                        </details>
                                    </td>
                                    <td>
                                        <?php echo formatDate($contact['submitted_at'], 'M d, Y'); ?><br>
                                        <small style="color: var(--text-light);"><?php echo formatDate($contact['submitted_at'], 'g:i A'); ?></small>
                                    </td>
                                    <td>
                                        <?php if ($contact['is_read']): ?>
                                            <span class="badge badge-success">Read</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Unread</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!$contact['is_read']): ?>
                                            <a href="?mark_read=<?php echo $contact['id']; ?>" class="btn btn-secondary btn-sm">Mark Read</a>
                                        <?php endif; ?>
                                        <a href="?delete=<?php echo $contact['id']; ?>" 
                                           class="btn btn-secondary btn-sm" 
                                           onclick="return confirm('Are you sure you want to delete this contact submission?');"
                                           style="color: var(--danger-color);">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <p style="margin-top: var(--spacing-lg); color: var(--text-secondary); font-size: var(--font-size-sm);">
                    Total: <?php echo count($contacts); ?> submissions | 
                    Unread: <?php echo count(array_filter($contacts, function($c) { return !$c['is_read']; })); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="<?php echo APP_URL; ?>/assets/js/admin.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/js/main.js"></script>
</body>
</html>
