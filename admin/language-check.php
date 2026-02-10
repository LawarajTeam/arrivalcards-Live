<?php
/**
 * Admin - Language Translation Verification
 * Checks all countries for complete translations in all languages
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$pageTitle = 'Language Check';

// Get all languages
$stmt = $pdo->query("SELECT * FROM languages WHERE is_active = 1 ORDER BY display_order");
$languages = $stmt->fetchAll();

// Get all countries
$stmt = $pdo->query("SELECT * FROM countries ORDER BY id");
$countries = $stmt->fetchAll();

// Check translations for each country in each language
$translationStatus = [];
$missingTranslations = [];
$totalChecks = count($countries) * count($languages);
$completedChecks = 0;

foreach ($countries as $country) {
    foreach ($languages as $language) {
        $stmt = $pdo->prepare("
            SELECT ct.*, 
                   LENGTH(ct.entry_summary) as summary_length,
                   LENGTH(ct.visa_requirements) as requirements_length
            FROM country_translations ct
            WHERE ct.country_id = ? AND ct.lang_code = ?
        ");
        $stmt->execute([$country['id'], $language['code']]);
        $translation = $stmt->fetch();
        
        $status = 'missing';
        $issues = [];
        
        if ($translation) {
            // Start as complete, downgrade if issues found
            $status = 'complete';
            
            // Check country name
            if (empty($translation['country_name'])) {
                $issues[] = 'Missing country name';
                $status = 'incomplete';
            } elseif (strlen($translation['country_name']) < 3) {
                $issues[] = 'Country name too short';
                $status = 'incomplete';
            }
            
            // Check entry summary
            if (empty($translation['entry_summary'])) {
                $issues[] = 'Missing entry summary';
                $status = 'incomplete';
            } elseif ($translation['summary_length'] < 100) {
                $issues[] = 'Entry summary too short (min 100 chars)';
                $status = 'incomplete';
            }
            
            // Check visa requirements (optional but recommended)
            if (empty($translation['visa_requirements'])) {
                $issues[] = 'Missing detailed visa requirements';
                if ($status === 'complete') {
                    $status = 'incomplete';
                }
            } elseif ($translation['requirements_length'] < 50) {
                $issues[] = 'Visa requirements too brief';
                if ($status === 'complete') {
                    $status = 'incomplete';
                }
            }
            
            // Check for placeholder or generic text
            $placeholderTerms = ['lorem ipsum', 'test', 'placeholder', 'coming soon', 'tbd'];
            $textToCheck = strtolower($translation['entry_summary'] . ' ' . ($translation['visa_requirements'] ?? ''));
            foreach ($placeholderTerms as $term) {
                if (stripos($textToCheck, $term) !== false) {
                    $issues[] = 'Contains placeholder text';
                    $status = 'incomplete';
                    break;
                }
            }
        } else {
            $issues[] = 'No translation record exists';
        }
        
        $translationStatus[$country['id']][$language['code']] = [
            'status' => $status,
            'issues' => $issues,
            'data' => $translation
        ];
        
        if ($status !== 'complete') {
            $missingTranslations[] = [
                'country_id' => $country['id'],
                'country_code' => $country['country_code'],
                'language' => $language['code'],
                'language_name' => $language['name'],
                'status' => $status,
                'issues' => $issues
            ];
        }
        
        $completedChecks++;
    }
}

// Calculate statistics
$totalTranslations = $totalChecks;
$completeTranslations = 0;
$incompleteTranslations = 0;
$missingCount = 0;

foreach ($translationStatus as $countryId => $langs) {
    foreach ($langs as $langCode => $status) {
        if ($status['status'] === 'complete') {
            $completeTranslations++;
        } elseif ($status['status'] === 'incomplete') {
            $incompleteTranslations++;
        } else {
            $missingCount++;
        }
    }
}

$completionRate = ($completeTranslations / $totalTranslations) * 100;

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
        .progress-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .progress-bar {
            width: 100%;
            height: 40px;
            background: var(--bg-secondary);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            margin-bottom: 1rem;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--success-color) 0%, #059669 100%);
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-box {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary-color);
        }
        .stat-box.success {
            border-left-color: var(--success-color);
        }
        .stat-box.warning {
            border-left-color: var(--warning-color);
        }
        .stat-box.danger {
            border-left-color: var(--danger-color);
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        .language-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .language-tab {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: 2px solid var(--border-color);
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }
        .language-tab:hover {
            border-color: var(--primary-color);
            background: var(--bg-secondary);
        }
        .language-tab.active {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }
        .country-check-grid {
            display: grid;
            gap: 0.75rem;
        }
        .country-check-item {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid transparent;
        }
        .country-check-item.complete {
            border-left-color: var(--success-color);
        }
        .country-check-item.incomplete {
            border-left-color: var(--warning-color);
        }
        .country-check-item.missing {
            border-left-color: var(--danger-color);
        }
        .status-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .country-info {
            flex: 1;
        }
        .country-name-check {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .country-issues {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
        .issue-tag {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            background: var(--danger-color);
            color: white;
            font-size: 0.75rem;
            margin-right: 4px;
            margin-bottom: 4px;
        }
        .filter-controls {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .filter-btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: 2px solid var(--border-color);
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.875rem;
        }
        .filter-btn.active {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }
        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1>🌐 Language Translation Check</h1>
                <p style="color: var(--text-secondary);">Verify translations for all <?php echo count($countries); ?> countries in <?php echo count($languages); ?> languages</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <?php if ($incompleteTranslations > 0 || $missingCount > 0): ?>
                    <a href="<?php echo APP_URL; ?>/admin/fix-translations.php" class="btn btn-success">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="margin-right: 6px;">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Fix All Issues
                    </a>
                <?php endif; ?>
                <button onclick="location.reload()" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="margin-right: 6px;">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    Recheck All
                </button>
            </div>
        </div>

        <!-- Progress Overview -->
        <div class="progress-container">
            <h3 style="margin-bottom: 1rem;">Overall Progress</h3>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo number_format($completionRate, 1); ?>%;">
                    <?php echo number_format($completionRate, 1); ?>%
                </div>
            </div>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">
                <?php echo $completeTranslations; ?> of <?php echo $totalTranslations; ?> translations complete
            </p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-box success">
                <div class="stat-value"><?php echo $completeTranslations; ?></div>
                <div class="stat-label">Complete Translations</div>
            </div>
            <div class="stat-box warning">
                <div class="stat-value"><?php echo $incompleteTranslations; ?></div>
                <div class="stat-label">Incomplete Translations</div>
            </div>
            <div class="stat-box danger">
                <div class="stat-value"><?php echo $missingCount; ?></div>
                <div class="stat-label">Missing Translations</div>
            </div>
            <div class="stat-box">
                <div class="stat-value"><?php echo count($languages); ?></div>
                <div class="stat-label">Active Languages</div>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="filter-controls">
            <strong>Show:</strong>
            <button class="filter-btn" onclick="filterStatus('all')">All</button>
            <button class="filter-btn active" onclick="filterStatus('issues')">Issues Only</button>
            <button class="filter-btn" onclick="filterStatus('missing')">Missing</button>
            <button class="filter-btn" onclick="filterStatus('incomplete')">Incomplete</button>
            <button class="filter-btn" onclick="filterStatus('complete')">Complete</button>
        </div>

        <!-- Language Tabs -->
        <div class="language-tabs">
            <button class="language-tab active" onclick="showLanguage('all')">
                All Languages
            </button>
            <?php foreach ($languages as $lang): ?>
                <button class="language-tab" onclick="showLanguage('<?php echo e($lang['code']); ?>')">
                    <?php echo e($lang['flag_emoji']); ?> <?php echo e($lang['name']); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Results Container -->
        <div id="results-container">
            <div class="country-check-grid" id="check-grid">
                <?php 
                $displayedCount = 0;
                foreach ($countries as $country): 
                    foreach ($languages as $language):
                        $status = $translationStatus[$country['id']][$language['code']];
                        $displayedCount++;
                        
                        // Determine if this should be shown by default (issues only)
                        $showByDefault = ($status['status'] !== 'complete');
                ?>
                    <div class="country-check-item <?php echo $status['status']; ?>" 
                         data-status="<?php echo $status['status']; ?>" 
                         data-language="<?php echo $language['code']; ?>"
                         style="<?php echo !$showByDefault ? 'display: none;' : ''; ?> animation: fadeIn 0.3s ease <?php echo ($displayedCount * 0.02); ?>s both;">
                        <div class="status-icon">
                            <?php if ($status['status'] === 'complete'): ?>
                                ✅
                            <?php elseif ($status['status'] === 'incomplete'): ?>
                                ⚠️
                            <?php else: ?>
                                ❌
                            <?php endif; ?>
                        </div>
                        <div class="country-info">
                            <div class="country-name-check">
                                <?php echo e($country['country_code']); ?> - 
                                <?php echo e($status['data']['country_name'] ?? 'Unknown'); ?> 
                                (<?php echo e($language['flag_emoji']); ?> <?php echo e($language['name']); ?>)
                            </div>
                            <?php if (!empty($status['issues'])): ?>
                                <div class="country-issues">
                                    <?php foreach ($status['issues'] as $issue): ?>
                                        <span class="issue-tag"><?php echo e($issue); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="country-issues" style="color: var(--success-color);">
                                    ✓ All checks passed
                                </div>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo APP_URL; ?>/admin/edit_country.php?id=<?php echo $country['id']; ?>&lang=<?php echo $language['code']; ?>" 
                           class="btn btn-sm btn-primary">
                            <?php echo $status['status'] === 'complete' ? 'View' : 'Fix'; ?>
                        </a>
                    </div>
                <?php 
                    endforeach;
                endforeach; 
                ?>
            </div>
        </div>

        <p style="text-align: center; color: var(--text-light); font-size: 0.875rem; margin-top: 2rem;">
            Last checked: <?php echo date('F j, Y g:i A'); ?>
        </p>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        let currentLanguage = 'all';
        let currentFilter = 'issues'; // Default to showing only issues

        function showLanguage(lang) {
            currentLanguage = lang;
            applyFilters();
            
            // Update active tab
            document.querySelectorAll('.language-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        function filterStatus(status) {
            currentFilter = status;
            applyFilters();
            
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        function applyFilters() {
            const items = document.querySelectorAll('.country-check-item');
            let visibleCount = 0;
            
            items.forEach((item, index) => {
                const itemStatus = item.getAttribute('data-status');
                const itemLang = item.getAttribute('data-language');
                
                let showItem = true;
                
                // Apply language filter
                if (currentLanguage !== 'all' && itemLang !== currentLanguage) {
                    showItem = false;
                }
                
                // Apply status filter
                if (currentFilter === 'issues') {
                    // Show incomplete and missing, hide complete
                    if (itemStatus === 'complete') {
                        showItem = false;
                    }
                } else if (currentFilter !== 'all' && itemStatus !== currentFilter) {
                    showItem = false;
                }
                
                if (showItem) {
                    item.style.display = 'flex';
                    item.style.animation = `fadeIn 0.3s ease ${visibleCount * 0.02}s both`;
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Update count display in header
            const totalItems = items.length;
            console.log(`Showing ${visibleCount} of ${totalItems} translations`);

            // Show empty state if no items visible
            const grid = document.getElementById('check-grid');
            const existingEmpty = document.querySelector('.empty-state-filter');
            
            if (visibleCount === 0) {
                if (!existingEmpty) {
                    const emptyState = document.createElement('div');
                    emptyState.className = 'empty-state empty-state-filter';
                    if (currentFilter === 'issues') {
                        emptyState.innerHTML = '<svg viewBox="0 0 20 20" fill="currentColor" style="width: 64px; height: 64px; margin-bottom: 1rem; opacity: 0.5;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg><h3>All Translations Complete! 🎉</h3><p>No issues found for the selected language(s).</p>';
                    } else {
                        emptyState.innerHTML = '<h3>No items match your filters</h3><p>Try adjusting your language or status filters.</p>';
                    }
                    grid.parentElement.appendChild(emptyState);
                }
            } else {
                if (existingEmpty) {
                    existingEmpty.remove();
                }
            }
        }

        // Initial load
        document.addEventListener('DOMContentLoaded', function() {
            applyFilters(); // Apply default filter on load
            console.log('Language Check loaded - checking all translations');
        });
    </script>
</body>
</html>
