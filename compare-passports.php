<?php
/**
 * Passport Comparison Tool
 * Compare visa requirements between two passports side-by-side
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = t('cp_page_title');
$pageDescription = t('cp_page_description');
$pageKeywords = t('cp_page_keywords');

// Get all passports that have data
$query = "
    SELECT DISTINCT
        c.id,
        c.country_code,
        ct.country_name,
        c.flag_emoji,
        COUNT(DISTINCT b.to_country_id) as destinations_count
    FROM countries c
    LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = ?
    INNER JOIN bilateral_visa_requirements b ON c.id = b.from_country_id
    GROUP BY c.id, c.country_code, ct.country_name, c.flag_emoji
    HAVING destinations_count > 0
    ORDER BY ct.country_name
";

$stmt = $pdo->prepare($query);
$stmt->execute([CURRENT_LANG]);
$availablePassports = $stmt->fetchAll();

// Get comparison data if passports are selected
$passport1 = $_GET['passport1'] ?? null;
$passport2 = $_GET['passport2'] ?? null;
$comparisonData = null;

if ($passport1 && $passport2 && $passport1 !== $passport2) {
    // Get passport 1 details
    $stmt = $pdo->prepare("SELECT c.id, c.country_code, ct.country_name, c.flag_emoji FROM countries c LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = ? WHERE c.country_code = ?");
    $stmt->execute([CURRENT_LANG, $passport1]);
    $p1Details = $stmt->fetch();
    
    // Get passport 2 details
    $stmt = $pdo->prepare("SELECT c.id, c.country_code, ct.country_name, c.flag_emoji FROM countries c LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = ? WHERE c.country_code = ?");
    $stmt->execute([CURRENT_LANG, $passport2]);
    $p2Details = $stmt->fetch();
    
    if ($p1Details && $p2Details) {
        // Get all destinations with visa requirements for both passports
        $query = "
            SELECT 
                dest.id as dest_id,
                dest.country_code as dest_code,
                dest_ct.country_name as dest_name,
                dest.flag_emoji as dest_flag,
                dest.region as dest_region,
                p1.visa_type as p1_visa_type,
                p1.duration_days as p1_duration,
                p1.cost_usd as p1_cost,
                p1.processing_time_days as p1_processing,
                p1.approval_rate_percent as p1_approval,
                p2.visa_type as p2_visa_type,
                p2.duration_days as p2_duration,
                p2.cost_usd as p2_cost,
                p2.processing_time_days as p2_processing,
                p2.approval_rate_percent as p2_approval
            FROM countries dest
            LEFT JOIN country_translations dest_ct ON dest.id = dest_ct.country_id AND dest_ct.lang_code = ?
            LEFT JOIN bilateral_visa_requirements p1 ON (dest.id = p1.to_country_id AND p1.from_country_id = ?)
            LEFT JOIN bilateral_visa_requirements p2 ON (dest.id = p2.to_country_id AND p2.from_country_id = ?)
            WHERE (p1.id IS NOT NULL OR p2.id IS NOT NULL)
                AND dest.id != ? AND dest.id != ?
            ORDER BY dest_ct.country_name
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([CURRENT_LANG, $p1Details['id'], $p2Details['id'], $p1Details['id'], $p2Details['id']]);
        $destinations = $stmt->fetchAll();
        
        // Calculate statistics
        $p1Stats = ['visa_free' => 0, 'visa_on_arrival' => 0, 'evisa' => 0, 'visa_required' => 0, 'no_data' => 0];
        $p2Stats = ['visa_free' => 0, 'visa_on_arrival' => 0, 'evisa' => 0, 'visa_required' => 0, 'no_data' => 0];
        $p1TotalCost = 0;
        $p2TotalCost = 0;
        $p1TotalProcessing = 0;
        $p2TotalProcessing = 0;
        $p1ProcessingCount = 0;
        $p2ProcessingCount = 0;
        
        foreach ($destinations as $dest) {
            if ($dest['p1_visa_type']) {
                $p1Stats[$dest['p1_visa_type']]++;
                $p1TotalCost += $dest['p1_cost'] ?? 0;
                if ($dest['p1_processing'] > 0) {
                    $p1TotalProcessing += $dest['p1_processing'];
                    $p1ProcessingCount++;
                }
            } else {
                $p1Stats['no_data']++;
            }
            
            if ($dest['p2_visa_type']) {
                $p2Stats[$dest['p2_visa_type']]++;
                $p2TotalCost += $dest['p2_cost'] ?? 0;
                if ($dest['p2_processing'] > 0) {
                    $p2TotalProcessing += $dest['p2_processing'];
                    $p2ProcessingCount++;
                }
            } else {
                $p2Stats['no_data']++;
            }
        }
        
        $p1Stats['easy_access'] = $p1Stats['visa_free'] + $p1Stats['visa_on_arrival'];
        $p2Stats['easy_access'] = $p2Stats['visa_free'] + $p2Stats['visa_on_arrival'];
        $p1Stats['avg_cost'] = $p1TotalCost > 0 ? round($p1TotalCost / count($destinations), 2) : 0;
        $p2Stats['avg_cost'] = $p2TotalCost > 0 ? round($p2TotalCost / count($destinations), 2) : 0;
        $p1Stats['avg_processing'] = $p1ProcessingCount > 0 ? round($p1TotalProcessing / $p1ProcessingCount, 1) : 0;
        $p2Stats['avg_processing'] = $p2ProcessingCount > 0 ? round($p2TotalProcessing / $p2ProcessingCount, 1) : 0;
        
        $comparisonData = [
            'passport1' => $p1Details,
            'passport2' => $p2Details,
            'p1_stats' => $p1Stats,
            'p2_stats' => $p2Stats,
            'destinations' => $destinations
        ];
    }
}

// Structured data for compare tool
$structuredData = [
    '@context' => 'https://schema.org',
    '@type' => 'WebApplication',
    'name' => 'Passport Comparison Tool',
    'description' => 'Compare passports side by side to see visa-free access, visa requirements, and travel freedom differences between countries.',
    'url' => APP_URL . '/compare-passports.php',
    'applicationCategory' => 'TravelApplication',
    'operatingSystem' => 'Web',
    'offers' => [
        '@type' => 'Offer',
        'price' => '0',
        'priceCurrency' => 'USD'
    ]
];

$breadcrumbSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => APP_URL
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Compare Passports',
            'item' => APP_URL . '/compare-passports.php'
        ]
    ]
];

include __DIR__ . '/includes/header.php'; ?>

<style>
.compare-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
    position: relative;
    overflow: hidden;
}

.compare-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)" /></svg>');
    opacity: 0.3;
}

.compare-hero-content {
    position: relative;
    z-index: 1;
    text-align: center;
}

.compare-hero h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
}

.compare-hero-subtitle {
    font-size: 1.3rem;
    opacity: 0.95;
}

.selector-section {
    padding: 40px 0;
    background: white;
}

.selector-grid {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    gap: 2rem;
    align-items: center;
    max-width: 900px;
    margin: 0 auto;
}

.passport-selector {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 2rem;
    border: 2px solid #e9ecef;
}

.passport-selector h3 {
    font-size: 1.1rem;
    color: #6c757d;
    margin-bottom: 1rem;
    text-align: center;
}

.passport-selector select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #dee2e6;
    border-radius: 10px;
    font-size: 1rem;
    background: white;
    cursor: pointer;
    transition: all 0.2s;
}

.passport-selector select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.vs-divider {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.compare-btn {
    display: block;
    width: 100%;
    max-width: 300px;
    margin: 2rem auto 0;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.compare-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.results-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.stats-comparison {
    display: grid;
    grid-template-columns: 2fr 1fr 2fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

.passport-stats-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.passport-header {
    text-align: center;
    margin-bottom: 2rem;
}

.passport-flag-large {
    font-size: 4rem;
    margin-bottom: 0.5rem;
}

.passport-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
}

.stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.stat-row:last-child {
    border-bottom: none;
}

.stat-label {
    font-size: 0.95rem;
    color: #6c757d;
}

.stat-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}

.winner-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.winner-badge {
    background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
    color: #000;
    padding: 1rem 1.5rem;
    border-radius: 50px;
    font-size: 3rem;
    box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
}

.winner-text {
    font-weight: 600;
    color: #495057;
}

.destinations-table {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

.destinations-table table {
    width: 100%;
    border-collapse: collapse;
}

.destinations-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.destinations-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
}

.destinations-table tbody tr {
    border-bottom: 1px solid #e9ecef;
}

.destinations-table tbody tr:hover {
    background: #f8f9fa;
}

.destinations-table td {
    padding: 1rem;
}

.visa-badge {
    display: inline-block;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
}

.visa-free { background: #d4edda; color: #155724; }
.visa-on-arrival { background: #d1ecf1; color: #0c5460; }
.evisa { background: #fff3cd; color: #856404; }
.visa-required { background: #f8d7da; color: #721c24; }
.no-data { background: #e9ecef; color: #6c757d; }

.advantage {
    color: #28a745;
    font-weight: 600;
}

.disadvantage {
    color: #dc3545;
    font-weight: 600;
}

@media (max-width: 768px) {
    .selector-grid {
        grid-template-columns: 1fr;
    }
    
    .vs-divider {
        transform: rotate(90deg);
    }
    
    .stats-comparison {
        grid-template-columns: 1fr;
    }
    
    .compare-hero h1 {
        font-size: 2rem;
    }
}
</style>

<!-- Hero Section -->
<section class="compare-hero">
    <div class="container">
        <div class="compare-hero-content">
            <h1>⚖️ <?php echo e(t('cp_hero_title')); ?></h1>
            <p class="compare-hero-subtitle">
                <?php echo e(t('cp_hero_subtitle')); ?>
            </p>
        </div>
    </div>
</section>

<!-- Passport Selector -->
<section class="selector-section">
    <div class="container">
        <form method="GET" action="">
            <div class="selector-grid">
                <div class="passport-selector">
                    <h3><?php echo e(t('cp_first_passport')); ?></h3>
                    <select name="passport1" id="passport1" required>
                        <option value=""><?php echo e(t('cp_select_passport')); ?></option>
                        <?php foreach ($availablePassports as $passport): ?>
                            <option value="<?php echo e($passport['country_code']); ?>" 
                                    <?php echo ($passport1 == $passport['country_code']) ? 'selected' : ''; ?>>
                                <?php echo e($passport['flag_emoji']); ?> <?php echo e($passport['country_name']); ?> 
                                (<?php echo $passport['destinations_count']; ?> <?php echo e(t('cp_destinations')); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="vs-divider">VS</div>
                
                <div class="passport-selector">
                    <h3><?php echo e(t('cp_second_passport')); ?></h3>
                    <select name="passport2" id="passport2" required>
                        <option value=""><?php echo e(t('cp_select_passport')); ?></option>
                        <?php foreach ($availablePassports as $passport): ?>
                            <option value="<?php echo e($passport['country_code']); ?>" 
                                    <?php echo ($passport2 == $passport['country_code']) ? 'selected' : ''; ?>>
                                <?php echo e($passport['flag_emoji']); ?> <?php echo e($passport['country_name']); ?> 
                                (<?php echo $passport['destinations_count']; ?> <?php echo e(t('cp_destinations')); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="compare-btn">
                🔍 <?php echo e(t('cp_compare_btn')); ?>
            </button>
        </form>
    </div>
</section>

<!-- Results Section -->
<?php if ($comparisonData): ?>
<section class="results-section">
    <div class="container">
        
        <!-- Statistics Comparison -->
        <div class="stats-comparison">
            <!-- Passport 1 Stats -->
            <div class="passport-stats-card">
                <div class="passport-header">
                    <div class="passport-flag-large"><?php echo e($comparisonData['passport1']['flag_emoji']); ?></div>
                    <div class="passport-name"><?php echo e($comparisonData['passport1']['country_name']); ?></div>
                </div>
                
                <div class="stat-row">
                    <span class="stat-label">🟢 <?php echo e(t('cp_visa_free')); ?></span>
                    <span class="stat-value"><?php echo $comparisonData['p1_stats']['visa_free']; ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">🔵 <?php echo e(t('cp_visa_on_arrival')); ?></span>
                    <span class="stat-value"><?php echo $comparisonData['p1_stats']['visa_on_arrival']; ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">✨ <?php echo e(t('cp_easy_access')); ?></span>
                    <span class="stat-value"><?php echo $comparisonData['p1_stats']['easy_access']; ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">🟡 <?php echo e(t('cp_evisa_required')); ?></span>
                    <span class="stat-value"><?php echo $comparisonData['p1_stats']['evisa']; ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">🔴 <?php echo e(t('cp_visa_required')); ?></span>
                    <span class="stat-value"><?php echo $comparisonData['p1_stats']['visa_required']; ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">💰 <?php echo e(t('cp_avg_cost')); ?></span>
                    <span class="stat-value">$<?php echo $comparisonData['p1_stats']['avg_cost']; ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">⏱️ <?php echo e(t('cp_avg_processing')); ?></span>
                    <span class="stat-value"><?php echo $comparisonData['p1_stats']['avg_processing']; ?> <?php echo e(t('cp_days')); ?></span>
                </div>
            </div>
            
            <!-- Winner Indicator -->
            <div class="winner-indicator">
                <?php 
                $p1Better = $comparisonData['p1_stats']['easy_access'];
                $p2Better = $comparisonData['p2_stats']['easy_access'];
                if ($p1Better > $p2Better): ?>
                    <div class="winner-badge">←</div>
                    <div class="winner-text"><?php echo e(t('cp_better_access')); ?></div>
                <?php elseif ($p2Better > $p1Better): ?>
                    <div class="winner-badge">→</div>
                    <div class="winner-text"><?php echo e(t('cp_better_access')); ?></div>
                <?php else: ?>
                    <div class="winner-badge">🤝</div>
                    <div class="winner-text"><?php echo e(t('cp_equal_access')); ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Passport 2 Stats -->
            <div class="passport-stats-card">
                <div class="passport-header">
                    <div class="passport-flag-large"><?php echo e($comparisonData['passport2']['flag_emoji']); ?></div>
                    <div class="passport-name"><?php echo e($comparisonData['passport2']['country_name']); ?></div>
                </div>
                
                <div class="stat-row">
                    <span class="stat-label">🟢 <?php echo e(t('cp_visa_free')); ?></span>
                    <span class="stat-value"><?php echo $comparisonData['p2_stats']['visa_free']; ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">🔵 <?php echo e(t('cp_visa_on_arrival')); ?></span>
                    <span class="stat-value"><?php echo $comparisonData['p2_stats']['visa_on_arrival']; ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">✨ <?php echo e(t('cp_easy_access')); ?></span>
                    <span class="stat-value"><?php echo $comparisonData['p2_stats']['easy_access']; ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">🟡 <?php echo e(t('cp_evisa_required')); ?></span>
                    <span class="stat-value"><?php echo $comparisonData['p2_stats']['evisa']; ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">🔴 <?php echo e(t('cp_visa_required')); ?></span>
                    <span class="stat-value"><?php echo $comparisonData['p2_stats']['visa_required']; ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">💰 <?php echo e(t('cp_avg_cost')); ?></span>
                    <span class="stat-value">$<?php echo $comparisonData['p2_stats']['avg_cost']; ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">⏱️ <?php echo e(t('cp_avg_processing')); ?></span>
                    <span class="stat-value"><?php echo $comparisonData['p2_stats']['avg_processing']; ?> <?php echo e(t('cp_days')); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Destinations Table -->
        <h2 style="text-align: center; margin-bottom: 2rem; color: #2c3e50;"><?php echo e(t('cp_dest_comparison')); ?></h2>
        <div class="destinations-table">
            <table>
                <thead>
                    <tr>
                        <th><?php echo e(t('cp_th_destination')); ?></th>
                        <th><?php echo e($comparisonData['passport1']['flag_emoji']); ?> <?php echo e($comparisonData['passport1']['country_name']); ?></th>
                        <th><?php echo e($comparisonData['passport2']['flag_emoji']); ?> <?php echo e($comparisonData['passport2']['country_name']); ?></th>
                        <th><?php echo e(t('cp_th_advantage')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comparisonData['destinations'] as $dest): 
                        $p1Type = $dest['p1_visa_type'] ?? 'no-data';
                        $p2Type = $dest['p2_visa_type'] ?? 'no-data';
                        
                        // Determine advantage
                        $advantage = '—';
                        $visaRank = ['visa_free' => 4, 'visa_on_arrival' => 3, 'evisa' => 2, 'visa_required' => 1, 'no-data' => 0];
                        $p1Rank = $visaRank[$p1Type] ?? 0;
                        $p2Rank = $visaRank[$p2Type] ?? 0;
                        
                        if ($p1Rank > $p2Rank) {
                            $advantage = $comparisonData['passport1']['flag_emoji'] . ' ' . t('cp_better');
                            $advantageClass = 'advantage';
                        } elseif ($p2Rank > $p1Rank) {
                            $advantage = $comparisonData['passport2']['flag_emoji'] . ' ' . t('cp_better');
                            $advantageClass = 'advantage';
                        } else {
                            $advantageClass = '';
                        }
                    ?>
                    <tr>
                        <td>
                            <strong><?php echo e($dest['dest_flag']); ?> <?php echo e($dest['dest_name']); ?></strong>
                            <br><small style="color: #6c757d;"><?php echo e($dest['dest_region']); ?></small>
                        </td>
                        <td>
                            <span class="visa-badge <?php echo str_replace('_', '-', $p1Type); ?>">
                                <?php echo ucwords(str_replace('_', ' ', $p1Type)); ?>
                            </span>
                            <?php if ($dest['p1_cost'] > 0): ?>
                                <br><small>$<?php echo $dest['p1_cost']; ?> • <?php echo $dest['p1_duration']; ?> <?php echo e(t('cp_days')); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="visa-badge <?php echo str_replace('_', '-', $p2Type); ?>">
                                <?php echo ucwords(str_replace('_', ' ', $p2Type)); ?>
                            </span>
                            <?php if ($dest['p2_cost'] > 0): ?>
                                <br><small>$<?php echo $dest['p2_cost']; ?> • <?php echo $dest['p2_duration']; ?> <?php echo e(t('cp_days')); ?></small>
                            <?php endif; ?>
                        </td>
                        <td class="<?php echo $advantageClass; ?>">
                            <?php echo $advantage; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
