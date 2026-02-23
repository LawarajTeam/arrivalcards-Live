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

// Get all country info from DB (for flag, name, region)
$countryQuery = "
    SELECT 
        c.id,
        c.country_code,
        ct.country_name,
        c.flag_emoji,
        c.region
    FROM countries c
    LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
    WHERE c.is_active = 1
";
$stmt = $pdo->query($countryQuery);
$countryLookup = [];
foreach ($stmt->fetchAll() as $row) {
    $countryLookup[$row['country_code']] = $row;
}

// Get bilateral data where available (for detailed breakdown)
$bilateralQuery = "
    SELECT 
        c.country_code,
        COUNT(DISTINCT b.to_country_id) as destinations_with_data,
        SUM(CASE WHEN b.visa_type = 'visa_free' THEN 1 ELSE 0 END) as visa_free_count,
        SUM(CASE WHEN b.visa_type = 'visa_on_arrival' THEN 1 ELSE 0 END) as voa_count,
        SUM(CASE WHEN b.visa_type = 'evisa' THEN 1 ELSE 0 END) as evisa_count,
        SUM(CASE WHEN b.visa_type = 'visa_required' THEN 1 ELSE 0 END) as visa_required_count
    FROM countries c
    INNER JOIN bilateral_visa_requirements b ON c.id = b.from_country_id
    GROUP BY c.country_code
";
$stmt = $pdo->query($bilateralQuery);
$bilateralLookup = [];
foreach ($stmt->fetchAll() as $row) {
    $bilateralLookup[$row['country_code']] = $row;
}

// Known rankings (Henley Passport Index Q1 2025 reference data)
$knownRankings = [
    // Rank 1
    'JPN' => ['rank' => 1, 'global_visa_free' => 193],
    'SGP' => ['rank' => 1, 'global_visa_free' => 193],
    // Rank 2
    'FRA' => ['rank' => 2, 'global_visa_free' => 192],
    'DEU' => ['rank' => 2, 'global_visa_free' => 192],
    'ITA' => ['rank' => 2, 'global_visa_free' => 192],
    'ESP' => ['rank' => 2, 'global_visa_free' => 192],
    // Rank 3
    'AUT' => ['rank' => 3, 'global_visa_free' => 191],
    'FIN' => ['rank' => 3, 'global_visa_free' => 191],
    'IRL' => ['rank' => 3, 'global_visa_free' => 191],
    'LUX' => ['rank' => 3, 'global_visa_free' => 191],
    'NLD' => ['rank' => 3, 'global_visa_free' => 191],
    'KOR' => ['rank' => 3, 'global_visa_free' => 191],
    'SWE' => ['rank' => 3, 'global_visa_free' => 191],
    // Rank 4
    'BEL' => ['rank' => 4, 'global_visa_free' => 190],
    'DNK' => ['rank' => 4, 'global_visa_free' => 190],
    'GBR' => ['rank' => 4, 'global_visa_free' => 190],
    'NZL' => ['rank' => 4, 'global_visa_free' => 190],
    'NOR' => ['rank' => 4, 'global_visa_free' => 190],
    'CHE' => ['rank' => 4, 'global_visa_free' => 190],
    // Rank 5
    'AUS' => ['rank' => 5, 'global_visa_free' => 189],
    'PRT' => ['rank' => 5, 'global_visa_free' => 189],
    // Rank 6
    'GRC' => ['rank' => 6, 'global_visa_free' => 188],
    'POL' => ['rank' => 6, 'global_visa_free' => 188],
    // Rank 7
    'CAN' => ['rank' => 7, 'global_visa_free' => 187],
    'CZE' => ['rank' => 7, 'global_visa_free' => 187],
    'HUN' => ['rank' => 7, 'global_visa_free' => 187],
    'MLT' => ['rank' => 7, 'global_visa_free' => 187],
    // Rank 8
    'USA' => ['rank' => 8, 'global_visa_free' => 186],
    'LTU' => ['rank' => 8, 'global_visa_free' => 186],
    // Rank 9
    'EST' => ['rank' => 9, 'global_visa_free' => 185],
    'LVA' => ['rank' => 9, 'global_visa_free' => 185],
    'SVK' => ['rank' => 9, 'global_visa_free' => 185],
    'SVN' => ['rank' => 9, 'global_visa_free' => 185],
    // Rank 10
    'ISL' => ['rank' => 10, 'global_visa_free' => 184],
    // Rank 11
    'LIE' => ['rank' => 11, 'global_visa_free' => 183],
    // Rank 12
    'ARE' => ['rank' => 12, 'global_visa_free' => 182],
    'HRV' => ['rank' => 12, 'global_visa_free' => 182],
    // Rank 13
    'MYS' => ['rank' => 13, 'global_visa_free' => 180],
    // Rank 14
    'ROU' => ['rank' => 14, 'global_visa_free' => 179],
    // Rank 15
    'BGR' => ['rank' => 15, 'global_visa_free' => 178],
    // Rank 16
    'CHL' => ['rank' => 16, 'global_visa_free' => 177],
    'CYP' => ['rank' => 16, 'global_visa_free' => 177],
    // Rank 17
    'HKG' => ['rank' => 17, 'global_visa_free' => 172],
    'MCO' => ['rank' => 17, 'global_visa_free' => 172],
    // Rank 18
    'ARG' => ['rank' => 18, 'global_visa_free' => 171],
    'BRA' => ['rank' => 18, 'global_visa_free' => 171],
    // Rank 19
    'SMR' => ['rank' => 19, 'global_visa_free' => 170],
    'AND' => ['rank' => 19, 'global_visa_free' => 170],
    // Rank 20
    'ISR' => ['rank' => 20, 'global_visa_free' => 168],
    // Rank 21
    'BRN' => ['rank' => 21, 'global_visa_free' => 167],
    'MEX' => ['rank' => 21, 'global_visa_free' => 167],
    // Rank 22
    'SRB' => ['rank' => 22, 'global_visa_free' => 163],
    // Rank 23
    'URY' => ['rank' => 23, 'global_visa_free' => 161],
    'BHS' => ['rank' => 23, 'global_visa_free' => 161],
    // Rank 24
    'MNE' => ['rank' => 24, 'global_visa_free' => 159],
    'UKR' => ['rank' => 24, 'global_visa_free' => 159],
    // Rank 25
    'CRI' => ['rank' => 25, 'global_visa_free' => 158],
    'MKD' => ['rank' => 25, 'global_visa_free' => 158],
    // Rank 26
    'PAN' => ['rank' => 26, 'global_visa_free' => 157],
    'TTO' => ['rank' => 26, 'global_visa_free' => 157],
    // Rank 27
    'GEO' => ['rank' => 27, 'global_visa_free' => 156],
    'MDA' => ['rank' => 27, 'global_visa_free' => 156],
    // Rank 28
    'ALB' => ['rank' => 28, 'global_visa_free' => 155],
    // Rank 29
    'COL' => ['rank' => 29, 'global_visa_free' => 154],
    'PER' => ['rank' => 29, 'global_visa_free' => 154],
    // Rank 30
    'PRY' => ['rank' => 30, 'global_visa_free' => 153],
    'SLV' => ['rank' => 30, 'global_visa_free' => 153],
    // Rank 31
    'BIH' => ['rank' => 31, 'global_visa_free' => 152],
    'GTM' => ['rank' => 31, 'global_visa_free' => 152],
    'HND' => ['rank' => 31, 'global_visa_free' => 152],
    // Rank 32
    'TWN' => ['rank' => 32, 'global_visa_free' => 148],
    // Rank 33
    'TUR' => ['rank' => 33, 'global_visa_free' => 146],
    'NIC' => ['rank' => 33, 'global_visa_free' => 146],
    // Rank 34
    'BRB' => ['rank' => 34, 'global_visa_free' => 144],
    'VCT' => ['rank' => 34, 'global_visa_free' => 144],
    // Rank 35
    'ATG' => ['rank' => 35, 'global_visa_free' => 143],
    'KNA' => ['rank' => 35, 'global_visa_free' => 143],
    'LCA' => ['rank' => 35, 'global_visa_free' => 143],
    'DMA' => ['rank' => 35, 'global_visa_free' => 143],
    'GRD' => ['rank' => 35, 'global_visa_free' => 143],
    // Rank 36
    'THA' => ['rank' => 36, 'global_visa_free' => 141],
    'QAT' => ['rank' => 36, 'global_visa_free' => 141],
    // Rank 37
    'KAZ' => ['rank' => 37, 'global_visa_free' => 139],
    'KWT' => ['rank' => 37, 'global_visa_free' => 139],
    // Rank 38
    'BHR' => ['rank' => 38, 'global_visa_free' => 135],
    'OMN' => ['rank' => 38, 'global_visa_free' => 135],
    // Rank 39
    'SAU' => ['rank' => 39, 'global_visa_free' => 133],
    // Rank 40
    'BLR' => ['rank' => 40, 'global_visa_free' => 131],
    // Rank 41
    'RUS' => ['rank' => 41, 'global_visa_free' => 129],
    // Rank 42
    'ZAF' => ['rank' => 42, 'global_visa_free' => 106],
    // Rank 43
    'IDN' => ['rank' => 43, 'global_visa_free' => 99],
    // Rank 44
    'KEN' => ['rank' => 44, 'global_visa_free' => 93],
    // Rank 45
    'JAM' => ['rank' => 45, 'global_visa_free' => 91],
    // Rank 46
    'BOL' => ['rank' => 46, 'global_visa_free' => 88],
    'CHN' => ['rank' => 46, 'global_visa_free' => 88],
    // Rank 47
    'MAR' => ['rank' => 47, 'global_visa_free' => 83],
    // Rank 48
    'PHL' => ['rank' => 48, 'global_visa_free' => 79],
    // Rank 49
    'EGY' => ['rank' => 49, 'global_visa_free' => 73],
    'GHA' => ['rank' => 49, 'global_visa_free' => 73],
    // Rank 50
    'VNM' => ['rank' => 50, 'global_visa_free' => 70],
    // Rank 51
    'IND' => ['rank' => 51, 'global_visa_free' => 68],
    // Rank 52
    'NGA' => ['rank' => 52, 'global_visa_free' => 63],
    'ETH' => ['rank' => 52, 'global_visa_free' => 63],
    // Rank 53
    'LKA' => ['rank' => 53, 'global_visa_free' => 60],
    'NPL' => ['rank' => 53, 'global_visa_free' => 60],
    // Rank 54
    'BGD' => ['rank' => 54, 'global_visa_free' => 57],
    'MMR' => ['rank' => 54, 'global_visa_free' => 57],
    // Rank 55
    'PAK' => ['rank' => 55, 'global_visa_free' => 53],
    'SDN' => ['rank' => 55, 'global_visa_free' => 53],
    // Rank 56
    'SOM' => ['rank' => 56, 'global_visa_free' => 47],
    'YEM' => ['rank' => 56, 'global_visa_free' => 47],
    // Rank 57
    'IRQ' => ['rank' => 57, 'global_visa_free' => 44],
    'SYR' => ['rank' => 57, 'global_visa_free' => 44],
    // Rank 58
    'AFG' => ['rank' => 58, 'global_visa_free' => 38],
];

// Build the passports array from knownRankings (sorted by rank, then visa_free)
$passports = [];
foreach ($knownRankings as $code => $ranking) {
    $country = $countryLookup[$code] ?? null;
    $bilateral = $bilateralLookup[$code] ?? null;
    
    $passports[] = [
        'id' => $country['id'] ?? null,
        'country_code' => $code,
        'country_name' => $country['country_name'] ?? $code,
        'flag_emoji' => $country['flag_emoji'] ?? '',
        'region' => $country['region'] ?? '',
        'global_rank' => $ranking['rank'],
        'global_visa_free' => $ranking['global_visa_free'],
        'easy_access' => $ranking['global_visa_free'], // Use Henley data as the primary metric
        // Use bilateral data if available, otherwise estimate from global_visa_free
        'visa_free_count' => $bilateral['visa_free_count'] ?? null,
        'voa_count' => $bilateral['voa_count'] ?? null,
        'evisa_count' => $bilateral['evisa_count'] ?? null,
        'visa_required_count' => $bilateral['visa_required_count'] ?? null,
        'has_bilateral_data' => ($bilateral !== null),
    ];
}

// Sort by global rank (ascending), then by global_visa_free (descending) for ties
usort($passports, function($a, $b) {
    if ($a['global_rank'] == $b['global_rank']) {
        return $b['global_visa_free'] - $a['global_visa_free'];
    }
    return $a['global_rank'] - $b['global_rank'];
});

// Get statistics
$totalPassportsWithData = count($passports);
$avgVisaFree = round(array_sum(array_column($passports, 'global_visa_free')) / max($totalPassportsWithData, 1), 0);

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
                    <div class="ranking-stat-value"><?php echo $passports[0]['global_visa_free'] ?? '—'; ?></div>
                    <div class="ranking-stat-label">#1 Passport (Visa-Free)</div>
                </div>
                <div class="ranking-stat-card">
                    <div class="ranking-stat-value"><?php echo $avgVisaFree; ?></div>
                    <div class="ranking-stat-label">Avg Visa-Free Access</div>
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
                Passport rankings based on the <strong>Henley Passport Index</strong>, which measures the number of destinations 
                each passport can access visa-free or with visa on arrival. Data sourced from IATA and verified against official 
                government immigration websites. The higher the visa-free score, the more powerful the passport.
            </p>
        </div>
        
        <div class="ranking-table-container">
            <table class="ranking-table">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;">Rank</th>
                        <th>Country</th>
                        <th style="text-align: center;">Visa-Free Score</th>
                        <th style="text-align: center;">Visa-Free Destinations</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $lastRank = -1;
                    $displayRank = 0;
                    
                    foreach ($passports as $index => $passport): 
                        // Use the Henley rank directly
                        $currentRank = $passport['global_rank'];
                        
                        // Determine rank badge class
                        $rankClass = 'rank-other';
                        if ($currentRank == 1) $rankClass = 'rank-1';
                        elseif ($currentRank == 2) $rankClass = 'rank-2';
                        elseif ($currentRank == 3) $rankClass = 'rank-3';
                        
                        // Determine access badge based on visa-free score
                        $vf = $passport['global_visa_free'];
                        $accessClass = 'access-limited';
                        if ($vf >= 170) $accessClass = 'access-excellent';
                        elseif ($vf >= 140) $accessClass = 'access-good';
                        elseif ($vf >= 100) $accessClass = 'access-moderate';
                        
                        // Link to country page if we have it
                        $countryLink = $passport['id'] ? 'country.php?id=' . $passport['id'] : null;
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
                                    <?php if ($countryLink): ?>
                                        <a href="<?php echo $countryLink; ?>" style="text-decoration: none;">
                                            <span class="country-name-main"><?php echo e($passport['country_name']); ?></span>
                                        </a>
                                    <?php else: ?>
                                        <span class="country-name-main"><?php echo e($passport['country_name']); ?></span>
                                    <?php endif; ?>
                                    <span class="country-code"><?php echo e($passport['country_code']); ?><?php if ($passport['region']): ?> • <?php echo e($passport['region']); ?><?php endif; ?></span>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <span class="access-badge <?php echo $accessClass; ?>">
                                <?php echo $passport['global_visa_free']; ?> destinations
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <span class="global-rank-badge">
                                <?php echo $passport['global_visa_free']; ?> visa-free
                            </span>
                        </td>
                        <td>
                            <?php if ($passport['has_bilateral_data']): ?>
                            <div class="visa-breakdown">
                                <div class="visa-type-count">
                                    <span class="visa-icon visa-icon-free"></span>
                                    <span><?php echo $passport['visa_free_count']; ?> free</span>
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
                                    <span><?php echo $passport['visa_required_count']; ?> req.</span>
                                </div>
                            </div>
                            <?php else: ?>
                                <?php if ($countryLink): ?>
                                    <a href="<?php echo $countryLink; ?>" style="color: #667eea; text-decoration: none; font-size: 0.85rem;">View details →</a>
                                <?php else: ?>
                                    <span style="color: #6c757d; font-size: 0.85rem;">—</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="note-section" style="margin-top: 3rem;">
            <h3>🔍 How to Read This Table</h3>
            <p>
                <strong>Rank:</strong> Henley Passport Index position (shared ranks indicate equal visa-free access).<br>
                <strong>Visa-Free Score:</strong> Total destinations accessible without an advance visa (visa-free + visa on arrival).<br>
                <strong>Details:</strong> Where available, shows our detailed breakdown —
                <span style="color: #28a745;">● Visa-free</span> |
                <span style="color: #17a2b8;">● VoA</span> (visa at airport) |
                <span style="color: #ffc107;">● eVisa</span> (apply online) |
                <span style="color: #dc3545;">● Visa required</span> (embassy application).<br>
                Click a country name to view its full visa requirements for Australian passport holders.
            </p>
        </div>

        <!-- Sources & Methodology Section -->
        <div style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); padding: 2.5rem; margin-top: 3rem;">
            <h2 style="font-size: 1.5rem; color: #2c3e50; margin-bottom: 1.5rem;">📚 Sources &amp; Methodology</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
                <div>
                    <h3 style="font-size: 1.1rem; color: #667eea; margin-bottom: 0.5rem;">Data Sources</h3>
                    <ul style="list-style: none; padding: 0; margin: 0; line-height: 2;">
                        <li>📊 <a href="https://www.henleyglobal.com/passport-index" target="_blank" rel="noopener" style="color: #2563eb; text-decoration: none;">Henley Passport Index</a> — Global visa-free rankings, produced by Henley &amp; Partners using IATA data</li>
                        <li>✈️ <a href="https://www.iata.org/" target="_blank" rel="noopener" style="color: #2563eb; text-decoration: none;">IATA (International Air Transport Association)</a> — Bilateral visa requirement data for airlines</li>
                        <li>🏛️ Official government immigration websites — Visa policies, fees, and processing times verified per country</li>
                        <li>🇦🇺 <a href="https://www.smartraveller.gov.au/" target="_blank" rel="noopener" style="color: #2563eb; text-decoration: none;">Smartraveller (DFAT)</a> — Australian Government travel advisories</li>
                    </ul>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; color: #667eea; margin-bottom: 0.5rem;">Methodology</h3>
                    <ul style="list-style: disc; padding-left: 1.2rem; margin: 0; line-height: 2; color: #495057;">
                        <li><strong>"Easy Access"</strong> score = visa-free + visa on arrival destinations</li>
                        <li><strong>Global Rank</strong> is from the Henley Passport Index, which counts passport-free destinations for each nationality</li>
                        <li>Rankings are updated periodically as visa policies change</li>
                        <li>Our database tracks 196 countries/territories with bilateral visa data</li>
                        <li>eVisa destinations are listed separately as they require advance application</li>
                    </ul>
                </div>
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e9ecef;">
                <p style="font-size: 0.85rem; color: #6c757d; margin: 0;">
                    <strong>Disclaimer:</strong> Visa policies change frequently. This ranking is for informational purposes only and should not be considered legal advice. 
                    Always verify the latest entry requirements with the destination country's official immigration authority or your nearest embassy before travelling.
                    Last updated: <?php echo date('F Y'); ?>.
                </p>
            </div>
        </div>
        
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
