<?php
/**
 * Admin - Manage Countries
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$pageTitle = 'Manage Countries';

// Get all countries with English names
$stmt = $pdo->query("
    SELECT c.*, ct.country_name
    FROM countries c
    LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
    ORDER BY c.display_order, ct.country_name
");
$countries = $stmt->fetchAll();
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
        <div class="section-header">
            <h1>Manage Countries</h1>
            <a href="<?php echo APP_URL; ?>/admin/add_country.php" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Add New Country
            </a>
        </div>
        
        <?php if ($flash = getFlashMessage()): ?>
            <div class="alert alert-<?php echo e($flash['type']); ?>">
                <?php echo e($flash['message']); ?>
            </div>
        <?php endif; ?>
        
        <div class="admin-section">
            <?php if (empty($countries)): ?>
                <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">
                    No countries found. <a href="<?php echo APP_URL; ?>/admin/add_country.php">Add your first country</a>
                </p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Flag</th>
                                <th>Country</th>
                                <th>Region</th>
                                <th>Visa Type</th>
                                <th>Last Updated</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($countries as $country): ?>
                                <tr>
                                    <td style="font-size: 1.5rem;"><?php echo $country['flag_emoji']; ?></td>
                                    <td><strong><?php echo e($country['country_name']); ?></strong></td>
                                    <td><?php echo e($country['region']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo getVisaTypeBadgeClass($country['visa_type']); ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $country['visa_type'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatDate($country['last_updated']); ?></td>
                                    <td>
                                        <?php if ($country['is_active']): ?>
                                            <span class="badge badge-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo APP_URL; ?>/admin/edit_country.php?id=<?php echo $country['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                                        <a href="<?php echo APP_URL; ?>/admin/delete_country.php?id=<?php echo $country['id']; ?>" 
                                           class="btn btn-secondary btn-sm" 
                                           onclick="return confirm('Are you sure you want to delete this country? This cannot be undone.');"
                                           style="color: var(--danger-color);">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <p style="margin-top: var(--spacing-lg); color: var(--text-secondary); font-size: var(--font-size-sm);">
                    Total: <?php echo count($countries); ?> countries
                </p>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="<?php echo APP_URL; ?>/assets/js/admin.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/js/main.js"></script>
</body>
</html>
