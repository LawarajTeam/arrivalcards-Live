<?php
/**
 * Best Passports Ranking Page
 * Shows global passport power rankings with visa-free statistics
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Best Passports in the World 2026 - Global Ranking | Arrival Cards';
$pageDescription = 'Comprehensive ranking of the world\'s most powerful passports by visa-free access. Compare passport strength, find which countries offer the best travel freedom, and see detailed visa statistics for 196 passports.';
$pageKeywords = 'best passports, passport ranking, strongest passport, most powerful passport, visa free countries, passport index, global mobility, travel freedom, passport power, citizenship by investment';

// Get passports with their personalized data counts
$query = "
    SELECT 
        c.id,
        c.country_code,
        c.country_name,
        c.flag_emoji,
        c.region,
        COUNT(DISTINCT b.to_country_id) as destinations_with_data,
        SUM(CASE WHEN b.visa_type = 'visa_free' THEN 1 ELSE 0 END) as visa_free_count,
        SUM(CASE WHEN b.visa_type = 'visa_on_arrival' THEN 1 ELSE 0 END) as voa_count,
        SUM(CASE WHEN b.visa_type = 'evisa' THEN 1 ELSE 0 END) as evisa_count,
        SUM(CASE WHEN b.visa_type = 'visa_required' THEN 1 ELSE 0 END) as visa_required_count
    FROM countries c
    LEFT JOIN bilateral_visa_requirements b ON c.id = b.from_country_id
    GROUP BY c.id, c.country_code, c.country_name, c.flag_emoji, c.region
    HAVING destinations_with_data > 0
    ORDER BY visa_free_count DESC, voa_count DESC
";

$stmt = $pdo->query($query);
$passports = $stmt->fetchAll();

// Calculate easy access (visa-free + VoA)
foreach ($passports as &$passport) {
    $passport['easy_access'] = $passport['visa_free_count'] + $passport['voa_count'];
}
unset($passport);

// Sort by easy access
usort($passports, function($a, $b) {
    if ($a['easy_access'] == $b['easy_access']) {
        return $b['visa_free_count'] - $a['visa_free_count'];
    }
    return $b['easy_access'] - $a['easy_access'];
});

// Known rankings (Henley Passport Index 2026 reference)
$knownRankings = [
    'JPN' => ['rank' => 1, 'global_visa_free' => 193],
    'DEU' => ['rank' => 2, 'global_visa_free' => 192],
    'FRA' => ['rank' => 3, 'global_visa_free' => 192],
    'ESP' => ['rank' => 4, 'global_visa_free' => 192],
    'ITA' => ['rank' => 5, 'global_visa_free' => 192],
    'CAN' => ['rank' => 6, 'global_visa_free' => 187],
    'AUS' => ['rank' => 7, 'global_visa_free' => 186],
    'USA' => ['rank' => 8, 'global_visa_free' => 186],
    'GBR' => ['rank' => 4, 'global_visa_free' => 192],
    'ARE' => ['rank' => 11, 'global_visa_free' => 181],
    'BRA' => ['rank' => 16, 'global_visa_free' => 170],
    'MEX' => ['rank' => 26, 'global_visa_free' => 158],
    'CHN' => ['rank' => 60, 'global_visa_free' => 85],
    'SAU' => ['rank' => 64, 'global_visa_free' => 88],
    'IND' => ['rank' => 85, 'global_visa_free' => 62],
];

// Add ranking info to passports
foreach ($passports as &$passport) {
    if (isset($knownRankings[$passport['country_code']])) {
        $passport['global_rank'] = $knownRankings[$passport['country_code']]['rank'];
        $passport['global_visa_free'] = $knownRankings[$passport['country_code']]['global_visa_free'];
    }
}
unset($passport);

// Get statistics
$totalPassportsWithData = count($passports);
$avgVisaFree = round(array_sum(array_column($passports, 'visa_free_count')) / max($totalPassportsWithData, 1), 1);
$avgEasyAccess = round(array_sum(array_column($passports, 'easy_access')) / max($totalPassportsWithData, 1), 1);

// ItemList structured data for rich results (top 10 passports)
$topPassports = array_slice($passports, 0, 10);
$itemListElements = [];
foreach ($topPassports as $rank => $p) {
    $itemListElements[] = [
        '@type' => 'ListItem',
        'position' => $rank + 1,
        'name' => ($p['flag_emoji'] ?? '') . ' ' . $p['country_name'] . ' Passport',
        'url' => APP_URL . '/country.php?id=' . $p['id']
    ];
}
$structuredData = [
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => 'Best Passports in the World 2026',
    'description' => 'Ranking of the world\'s most powerful passports by visa-free access and travel freedom.',
    'numberOfItems' => count($topPassports),
    'itemListElement' => $itemListElements
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
            'name' => 'Best Passports',
            'item' => APP_URL . '/best-passports.php'
        ]
    ]
];

include __DIR__ . '/includes/header.php'; ?>

<style>
.ranking-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0 80px;
    position: relative;
    overflow: hidden;
}

.ranking-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)" /></svg>');
    opacity: 0.3;
}

.ranking-hero-content {
    position: relative;
    z-index: 1;
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.ranking-hero h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.ranking-hero-subtitle {
    font-size: 1.3rem;
    opacity: 0.95;
    margin-bottom: 2rem;
}

.ranking-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-top: 3rem;
}

.ranking-stat-card {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid rgba(255,255,255,0.2);
}

.ranking-stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.ranking-stat-label {
    font-size: 0.95rem;
    opacity: 0.9;
}

.ranking-table-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.ranking-table-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
}

.ranking-table {
    width: 100%;
    border-collapse: collapse;
}

.ranking-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.ranking-table th {
    padding: 1.2rem 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ranking-table tbody tr {
    border-bottom: 1px solid #e9ecef;
    transition: all 0.2s;
}

.ranking-table tbody tr:hover {
    background: #f8f9fa;
    transform: scale(1.01);
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.ranking-table td {
    padding: 1rem;
    vertical-align: middle;
}

.rank-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-weight: 700;
    font-size: 1rem;
}

.rank-1 { background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: #000; }
.rank-2 { background: linear-gradient(135deg, #C0C0C0 0%, #808080 100%); color: #000; }
.rank-3 { background: linear-gradient(135deg, #CD7F32 0%, #8B4513 100%); color: white; }
.rank-other { background: #e9ecef; color: #495057; }

.country-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.country-flag {
    font-size: 2rem;
}

.country-name-col {
    display: flex;
    flex-direction: column;
}

.country-name-main {
    font-weight: 600;
    font-size: 1rem;
    color: #2c3e50;
}

.country-code {
    font-size: 0.85rem;
    color: #6c757d;
}

.access-badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

.access-excellent { background: #d4edda; color: #155724; }
.access-good { background: #d1ecf1; color: #0c5460; }
.access-moderate { background: #fff3cd; color: #856404; }
.access-limited { background: #f8d7da; color: #721c24; }

.visa-breakdown {
    display: flex;
    gap: 1rem;
    font-size: 0.85rem;
}

.visa-type-count {
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.visa-icon {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.visa-icon-free { background: #28a745; }
.visa-icon-voa { background: #17a2b8; }
.visa-icon-evisa { background: #ffc107; }
.visa-icon-required { background: #dc3545; }

.global-rank-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.3rem 0.7rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
}

.note-section {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 1.5rem;
    margin: 2rem 0;
    border-radius: 8px;
}

.note-section h3 {
    color: #856404;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.note-section p {
    color: #856404;
    margin: 0;
}

@media (max-width: 768px) {
    .ranking-hero h1 {
        font-size: 2rem;
    }
    
    .ranking-table {
        font-size: 0.85rem;
    }
    
    .visa-breakdown {
        flex-direction: column;
        gap: 0.3rem;
    }
}
</style>

<!-- Hero Section -->
<section class="ranking-hero">
    <div class="container">
        <div class="ranking-hero-content">
            <h1>🏆 Best Passports in the World 2026</h1>
            <p class="ranking-hero-subtitle">
                Discover which passports offer the greatest travel freedom
            </p>
            
            <div class="ranking-stats-grid">
                <div class="ranking-stat-card">
                    <div class="ranking-stat-value"><?php echo $totalPassportsWithData; ?></div>
                    <div class="ranking-stat-label">Passports Ranked</div>
                </div>
                <div class="ranking-stat-card">
                    <div class="ranking-stat-value"><?php echo $avgVisaFree; ?></div>
                    <div class="ranking-stat-label">Avg Visa-Free Access</div>
                </div>
                <div class="ranking-stat-card">
                    <div class="ranking-stat-value"><?php echo $avgEasyAccess; ?></div>
                    <div class="ranking-stat-label">Avg Easy Access</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ranking Table Section -->
<section class="ranking-table-section">
    <div class="container">
        
        <div class="note-section">
            <h3>📊 About This Ranking</h3>
            <p>
                This ranking shows passports based on <strong>visa-free access + visa on arrival</strong> destinations, 
                using data available on Arrival Cards. Global rankings are from the Henley Passport Index 2026. 
                The more destinations you can visit without advance visa application, the more powerful your passport.
            </p>
        </div>
        
        <div class="ranking-table-container">
            <table class="ranking-table">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;">Rank</th>
                        <th>Country</th>
                        <th style="text-align: center;">Global Rank</th>
                        <th style="text-align: center;">Easy Access</th>
                        <th>Visa Breakdown</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $currentRank = 0;
                    $lastEasyAccess = -1;
                    
                    foreach ($passports as $index => $passport): 
                        // Assign rank (handle ties)
                        if ($passport['easy_access'] != $lastEasyAccess) {
                            $currentRank = $index + 1;
                            $lastEasyAccess = $passport['easy_access'];
                        }
                        
                        // Determine rank badge class
                        $rankClass = 'rank-other';
                        if ($currentRank == 1) $rankClass = 'rank-1';
                        elseif ($currentRank == 2) $rankClass = 'rank-2';
                        elseif ($currentRank == 3) $rankClass = 'rank-3';
                        
                        // Determine access badge
                        $accessClass = 'access-limited';
                        if ($passport['easy_access'] >= 15) $accessClass = 'access-excellent';
                        elseif ($passport['easy_access'] >= 10) $accessClass = 'access-good';
                        elseif ($passport['easy_access'] >= 5) $accessClass = 'access-moderate';
                    ?>
                    <tr>
                        <td style="text-align: center;">
                            <div class="rank-badge <?php echo $rankClass; ?>">
                                <?php echo $currentRank; ?>
                            </div>
                        </td>
                        <td>
                            <div class="country-info">
                                <span class="country-flag"><?php echo e($passport['flag_emoji']); ?></span>
                                <div class="country-name-col">
                                    <span class="country-name-main"><?php echo e($passport['country_name']); ?></span>
                                    <span class="country-code"><?php echo e($passport['country_code']); ?> • <?php echo e($passport['region']); ?></span>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <?php if (isset($passport['global_rank'])): ?>
                                <span class="global-rank-badge">
                                    #<?php echo $passport['global_rank']; ?> (<?php echo $passport['global_visa_free']; ?> visa-free)
                                </span>
                            <?php else: ?>
                                <span style="color: #6c757d;">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <span class="access-badge <?php echo $accessClass; ?>">
                                <?php echo $passport['easy_access']; ?> countries
                            </span>
                        </td>
                        <td>
                            <div class="visa-breakdown">
                                <div class="visa-type-count">
                                    <span class="visa-icon visa-icon-free"></span>
                                    <span><?php echo $passport['visa_free_count']; ?> visa-free</span>
                                </div>
                                <div class="visa-type-count">
                                    <span class="visa-icon visa-icon-voa"></span>
                                    <span><?php echo $passport['voa_count']; ?> VoA</span>
                                </div>
                                <div class="visa-type-count">
                                    <span class="visa-icon visa-icon-evisa"></span>
                                    <span><?php echo $passport['evisa_count']; ?> eVisa</span>
                                </div>
                                <div class="visa-type-count">
                                    <span class="visa-icon visa-icon-required"></span>
                                    <span><?php echo $passport['visa_required_count']; ?> visa req.</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="note-section" style="margin-top: 3rem;">
            <h3>🔍 How to Read This Table</h3>
            <p>
                <strong>Rank:</strong> Based on easy access (visa-free + visa on arrival).<br>
                <strong>Global Rank:</strong> Official Henley Passport Index 2026 ranking.<br>
                <strong>Easy Access:</strong> Countries you can visit without applying for visa in advance.<br>
                <strong>Visa Breakdown:</strong>
                <span style="color: #28a745;">● Visa-free</span> (no visa needed) |
                <span style="color: #17a2b8;">● VoA</span> (visa at airport) |
                <span style="color: #ffc107;">● eVisa</span> (apply online) |
                <span style="color: #dc3545;">● Visa required</span> (embassy application)
            </p>
        </div>
        
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
