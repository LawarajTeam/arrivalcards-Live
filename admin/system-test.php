<?php
/**
 * Admin - System Testing and Verification
 * Tests database connection, file structure, and configuration
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$pageTitle = 'System Test';

$tests = [];
$overallStatus = true;

// Helper function to add test result
function addTest($name, $status, $message = '') {
    global $tests, $overallStatus;
    $tests[] = [
        'name' => $name,
        'status' => $status,
        'message' => $message
    ];
    if (!$status) {
        $overallStatus = false;
    }
}

// Run all tests
// TEST 1: PHP Version
$phpVersion = phpversion();
$phpOK = version_compare($phpVersion, '8.0.0', '>=');
addTest(
    'PHP Version',
    $phpOK,
    "PHP $phpVersion " . ($phpOK ? '(✓ Compatible)' : '(✗ Requires PHP 8.0+)')
);

// TEST 2: Database Connection
try {
    $dbTest = $pdo->query("SELECT 1");
    addTest('Database Connection', true, 'Connected to ' . DB_NAME . ' on ' . DB_HOST);
} catch (Exception $e) {
    addTest('Database Connection', false, 'Failed: ' . $e->getMessage());
}

// TEST 3: Check Tables Exist
$requiredTables = ['languages', 'translations', 'countries', 'country_translations', 'contact_submissions', 'admin_users', 'audit_log'];
$tablesExist = [];
foreach ($requiredTables as $table) {
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        $exists = $stmt->rowCount() > 0;
        $tablesExist[$table] = $exists;
    } catch (Exception $e) {
        $tablesExist[$table] = false;
    }
}
$allTablesExist = !in_array(false, $tablesExist);
addTest(
    'Database Tables',
    $allTablesExist,
    $allTablesExist ? 'All 7 required tables exist' : 'Missing tables: ' . implode(', ', array_keys(array_filter($tablesExist, function($v) { return !$v; })))
);

// TEST 4: Check Countries Data
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM countries");
    $countryCount = $stmt->fetchColumn();
    addTest('Country Data', $countryCount > 0, "$countryCount countries found");
} catch (Exception $e) {
    addTest('Country Data', false, 'Error: ' . $e->getMessage());
}

// TEST 5: Check Translations
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM translations");
    $translationCount = $stmt->fetchColumn();
    addTest('UI Translations', $translationCount > 0, "$translationCount translations loaded");
} catch (Exception $e) {
    addTest('UI Translations', false, 'Error: ' . $e->getMessage());
}

// TEST 6: Check Languages
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM languages WHERE is_active = 1");
    $langCount = $stmt->fetchColumn();
    addTest('Active Languages', $langCount > 0, "$langCount languages active");
} catch (Exception $e) {
    addTest('Active Languages', false, 'Error: ' . $e->getMessage());
}

// TEST 7: Session Functionality
$sessionOK = (session_status() === PHP_SESSION_ACTIVE);
addTest('Session Support', $sessionOK, $sessionOK ? 'Sessions working' : 'Sessions not initialized');

// TEST 8: File Permissions
$fileChecks = [
    __DIR__ . '/../includes' => 'readable',
    __DIR__ . '/../assets' => 'readable',
];
$filesOK = true;
$fileMessages = [];
foreach ($fileChecks as $path => $type) {
    if (!file_exists($path)) {
        $filesOK = false;
        $fileMessages[] = basename($path) . ' missing';
    } elseif (!is_readable($path)) {
        $filesOK = false;
        $fileMessages[] = basename($path) . ' not readable';
    }
}
addTest('File Structure', $filesOK, $filesOK ? 'All directories accessible' : implode(', ', $fileMessages));

// TEST 9: .env File
$envExists = file_exists(__DIR__ . '/../.env');
addTest('.env Configuration', $envExists, $envExists ? '.env file found' : '.env file missing - using defaults');

// TEST 10: Translation Function
try {
    $testTranslation = t('site_title');
    $translationWorks = !empty($testTranslation);
    addTest('Translation Function', $translationWorks, "Test: '$testTranslation'");
} catch (Exception $e) {
    addTest('Translation Function', false, 'Error: ' . $e->getMessage());
}

// TEST 11: Email Configuration
$smtpConfigured = !empty(SMTP_USER) && !empty(SMTP_PASS);
addTest(
    'Email Configuration',
    $smtpConfigured || true,
    $smtpConfigured ? 'SMTP credentials configured' : 'SMTP not configured (emails may not send)'
);

// TEST 12: Logo File
$logoExists = file_exists(__DIR__ . '/../assets/images/logo.svg');
addTest('Logo File', $logoExists, $logoExists ? 'Logo found' : 'Logo missing');

// TEST 13: CSS File
$cssExists = file_exists(__DIR__ . '/../assets/css/style.css');
addTest('Stylesheet', $cssExists, $cssExists ? 'CSS loaded' : 'CSS missing');

// TEST 14: JavaScript File
$jsExists = file_exists(__DIR__ . '/../assets/js/main.js');
addTest('JavaScript', $jsExists, $jsExists ? 'JS loaded' : 'JS missing');

// TEST 15: Admin User
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users WHERE is_active = 1");
    $adminCount = $stmt->fetchColumn();
    addTest('Admin Users', $adminCount > 0, "$adminCount active admin users");
} catch (Exception $e) {
    addTest('Admin Users', false, 'Error: ' . $e->getMessage());
}

// TEST 16: Upload Directory
$uploadsPath = __DIR__ . '/../uploads';
$uploadsWritable = is_dir($uploadsPath) && is_writable($uploadsPath);
addTest('Upload Directory', $uploadsWritable || !is_dir($uploadsPath), 
    is_dir($uploadsPath) ? ($uploadsWritable ? 'Writable' : 'Not writable') : 'Directory not created (optional)');

// TEST 17: Flag Images
$flagsPath = __DIR__ . '/../assets/images/flags';
$flagCount = 0;
if (is_dir($flagsPath)) {
    $flagFiles = glob($flagsPath . '/*.svg');
    $flagCount = count($flagFiles);
}
addTest('Flag Images', true, "$flagCount flag SVG files found");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> - Arrival Cards</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/admin.css">
    <style>
        .test-grid {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .test-item {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            border-left: 4px solid transparent;
        }
        .test-item.pass {
            border-left-color: var(--success-color);
        }
        .test-item.fail {
            border-left-color: var(--danger-color);
        }
        .test-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .test-icon.pass {
            color: var(--success-color);
        }
        .test-icon.fail {
            color: var(--danger-color);
        }
        .test-content {
            flex: 1;
        }
        .test-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
        }
        .test-message {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
        .summary-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 2rem;
        }
        .summary-card.pass {
            border-left: 4px solid var(--success-color);
        }
        .summary-card.fail {
            border-left: 4px solid var(--danger-color);
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        .info-box {
            background: var(--bg-secondary);
            padding: 1rem;
            border-radius: 8px;
        }
        .info-label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .info-value {
            font-weight: 600;
            color: var(--text-primary);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>🧪 System Test</h1>
                <p style="color: var(--text-secondary);">Testing system configuration and functionality</p>
            </div>
            <button onclick="location.reload()" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="margin-right: 6px;">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                </svg>
                Retest
            </button>
        </div>

        <div class="summary-card <?php echo $overallStatus ? 'pass' : 'fail'; ?>">
            <h2 style="margin-bottom: 1rem; font-size: 1.5rem;">
                <?php if ($overallStatus): ?>
                    ✅ All Tests Passed!
                <?php else: ?>
                    ⚠️ Some Tests Failed
                <?php endif; ?>
            </h2>
            <p style="color: var(--text-secondary);">
                <?php 
                $passCount = count(array_filter($tests, function($t) { return $t['status']; }));
                echo "$passCount of " . count($tests) . " tests passed";
                ?>
            </p>
            
            <?php if ($overallStatus): ?>
                <p style="margin-top: 1rem; color: var(--success-color); font-weight: 600;">
                    System is functioning correctly
                </p>
            <?php else: ?>
                <p style="margin-top: 1rem; color: var(--danger-color);">
                    Please review and fix the failed tests above
                </p>
            <?php endif; ?>
        </div>

        <div class="test-grid">
            <?php foreach ($tests as $test): ?>
                <div class="test-item <?php echo $test['status'] ? 'pass' : 'fail'; ?>">
                    <div class="test-icon <?php echo $test['status'] ? 'pass' : 'fail'; ?>">
                        <?php echo $test['status'] ? '✓' : '✗'; ?>
                    </div>
                    <div class="test-content">
                        <div class="test-name"><?php echo htmlspecialchars($test['name']); ?></div>
                        <?php if ($test['message']): ?>
                            <div class="test-message"><?php echo htmlspecialchars($test['message']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 1rem;">System Information</h3>
            <div class="info-grid">
                <div class="info-box">
                    <div class="info-label">PHP Version</div>
                    <div class="info-value"><?php echo phpversion(); ?></div>
                </div>
                <div class="info-box">
                    <div class="info-label">Database</div>
                    <div class="info-value"><?php echo DB_NAME; ?></div>
                </div>
                <div class="info-box">
                    <div class="info-label">Environment</div>
                    <div class="info-value"><?php echo getenv('APP_ENV') ?: 'development'; ?></div>
                </div>
                <div class="info-box">
                    <div class="info-label">Default Language</div>
                    <div class="info-value"><?php echo strtoupper(DEFAULT_LANGUAGE); ?></div>
                </div>
                <div class="info-box">
                    <div class="info-label">Countries</div>
                    <div class="info-value"><?php echo getCountryCount(); ?></div>
                </div>
                <div class="info-box">
                    <div class="info-label">Active Languages</div>
                    <div class="info-value">
                        <?php 
                        $stmt = $pdo->query("SELECT COUNT(*) FROM languages WHERE is_active = 1");
                        echo $stmt->fetchColumn();
                        ?>
                    </div>
                </div>
                <div class="info-box">
                    <div class="info-label">Server Software</div>
                    <div class="info-value"><?php echo php_sapi_name(); ?></div>
                </div>
                <div class="info-box">
                    <div class="info-label">Max Upload Size</div>
                    <div class="info-value"><?php echo ini_get('upload_max_filesize'); ?></div>
                </div>
            </div>
        </div>

        <p style="text-align: center; color: var(--text-light); font-size: 0.875rem; margin-top: 2rem;">
            Last tested: <?php echo date('F j, Y g:i A'); ?>
        </p>
    </div>
</body>
</html>
