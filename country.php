<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/analytics_functions.php';

// Get country ID from URL
$countryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($countryId <= 0) {
    header('Location: index.php');
    exit;
}

// Get country data with translations
$stmt = $pdo->prepare("
    SELECT c.*, 
           ct.country_name, 
           ct.entry_summary, 
           ct.visa_requirements,
           ct.visa_duration,
           ct.passport_validity,
           ct.visa_fee,
           ct.processing_time,
           ct.official_visa_url,
           ct.arrival_card_required,
           ct.additional_docs,
           ct.last_verified
    FROM countries c
    LEFT JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = ?
    WHERE c.id = ? AND c.is_active = 1
");
$stmt->execute([CURRENT_LANG, $countryId]);
$country = $stmt->fetch();

if (!$country) {
    header('Location: index.php');
    exit;
}

// Track page view with country ID
$countryName = $country['country_name'] ?? $country['name_en'];
trackPageView($countryId, $countryName . ' - Visa Requirements');

// Increment view counter
try {
    $stmt = $pdo->prepare("INSERT INTO country_views (country_id, views, last_viewed) 
                           VALUES (?, 1, NOW()) 
                           ON DUPLICATE KEY UPDATE views = views + 1, last_viewed = NOW()");
    $stmt->execute([$countryId]);
} catch (PDOException $e) {
    // Silently fail if view tracking doesn't work
}

// Get country details
$stmt = $pdo->prepare("SELECT * FROM country_details WHERE country_id = ? AND lang_code = ?");
$stmt->execute([$countryId, CURRENT_LANG]);
$details = $stmt->fetch();

// Get airports
$stmt = $pdo->prepare("SELECT * FROM airports WHERE country_id = ? ORDER BY is_main DESC, city ASC");
$stmt->execute([$countryId]);
$airports = $stmt->fetchAll();

// SEO - Enhanced with comprehensive keywords
$pageTitle = $country['country_name'] . ' Visa Requirements & Entry Information | Arrival Cards';
$pageDescription = truncate($country['entry_summary'], 155) . ' Get visa info, eVisa, visa on arrival details.';

// Dynamic SEO keywords based on visa type
$visaTypeKeywords = [
    'visa_free' => 'visa free, no visa required, visa exemption, visa waiver',
    'visa_on_arrival' => 'visa on arrival, airport visa, border visa, instant visa',
    'evisa' => 'eVisa, electronic visa, online visa, visa application online',
    'visa_required' => 'embassy visa, consulate visa, visa application, visa process'
];
$specificKeywords = $visaTypeKeywords[$country['visa_type']] ?? 'visa information';

$pageKeywords = $country['country_name'] . ' visa requirements, ' . $country['country_name'] . ' visa, ' . 
                $country['country_name'] . ' entry requirements, ' . $country['country_name'] . ' travel visa, ' .
                $country['country_name'] . ' arrival card, ' . $specificKeywords . ', ' .
                'passport requirements, travel documents, immigration, border crossing, ' .
                $country['country_name'] . ' tourism, international travel';

// Breadcrumb Schema
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
            'name' => $country['region'],
            'item' => APP_URL . '/?region=' . urlencode($country['region'])
        ],
        [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $country['country_name'],
            'item' => APP_URL . '/country.php?id=' . $countryId
        ]
    ]
];

// Enhanced Travel Action Schema
$structuredData = [
    '@context' => 'https://schema.org',
    '@type' => 'TravelAction',
    'name' => $country['country_name'] . ' Visa & Entry Requirements',
    'description' => $pageDescription,
    'target' => [
        '@type' => 'EntryPoint',
        'urlTemplate' => APP_URL . '/country.php?id=' . $countryId,
        'actionPlatform' => [
            'http://schema.org/DesktopWebPlatform',
            'http://schema.org/MobileWebPlatform',
            'http://schema.org/IOSPlatform',
            'http://schema.org/AndroidPlatform'
        ]
    ],
    'result' => [
        '@type' => 'Thing',
        'name' => 'Visa Information for ' . $country['country_name'],
        'description' => 'Complete visa requirements and entry documentation'
    ]
];

// FAQ Schema for common visa questions
$faqSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => [
        [
            '@type' => 'Question',
            'name' => 'Do I need a visa for ' . $country['country_name'] . '?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $country['entry_summary']
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'What type of visa does ' . $country['country_name'] . ' require?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Visa Type: ' . ucwords(str_replace('_', ' ', $country['visa_type'])) . '. ' . substr($country['visa_requirements'], 0, 200)
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'How long can I stay in ' . $country['country_name'] . '?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Stay duration varies by nationality and visa type. Check specific requirements for your passport.'
            ]
        ]
    ]
];

// Setup visual breadcrumbs for user navigation
$breadcrumbs = [
    ['name' => t('home'), 'url' => APP_URL . '/index.php'],
    ['name' => $country['region'], 'url' => APP_URL . '/index.php?region=' . urlencode($country['region'])],
    ['name' => $country['country_name'], 'url' => null]
];

include __DIR__ . '/includes/header.php';
?>

<!-- Breadcrumb Navigation -->
<div class="container">
    <?php include __DIR__ . '/includes/breadcrumbs.php'; ?>
</div>

<style>
.country-hero {
    position: relative;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: white;
    padding: 0;
    margin-bottom: 2rem;
    overflow: hidden;
    border-radius: 0 0 16px 16px;
}

.country-hero-image {
    position: relative;
    height: 400px;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
}

.country-hero-image::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(30, 58, 138, 0.7) 0%, rgba(30, 58, 138, 0.9) 100%);
}

.country-hero-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.country-flag-large {
    font-size: 80px;
    line-height: 1;
    text-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.country-title-section h1 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    color: white;
    text-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.country-subtitle {
    font-size: 1.1rem;
    opacity: 0.95;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.country-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

@media (max-width: 968px) {
    .country-content {
        grid-template-columns: 1fr;
    }
}

.country-main {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.country-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.info-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.info-card h3 {
    font-size: 1.2rem;
    margin-bottom: 1rem;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: var(--text-secondary);
}

.info-value {
    color: var(--text-primary);
    text-align: right;
}

.visa-cta {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    text-align: center;
    margin: 2rem 0;
    box-shadow: 0 4px 16px rgba(16, 185, 129, 0.2);
}

.visa-cta h2 {
    color: white;
    margin-bottom: 1rem;
    font-size: 1.8rem;
}

.visa-cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    color: #059669;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.visa-cta-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}

.visa-cta-top {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    box-shadow: 0 4px 16px rgba(16, 185, 129, 0.2);
    flex-wrap: wrap;
    gap: 1rem;
}

.visa-cta-top h3 {
    color: white;
    margin: 0;
    font-size: 1.3rem;
}

.visa-cta-top p {
    margin: 0.25rem 0 0;
    opacity: 0.95;
    font-size: 0.95rem;
}

.highlight-box {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-left: 4px solid var(--primary-color);
    padding: 1.5rem;
    border-radius: 8px;
    margin: 1.5rem 0;
}

.highlight-box h4 {
    color: var(--primary-color);
    margin: 0 0 0.75rem;
    font-size: 1.1rem;
}

.highlight-box ul {
    margin: 0;
    padding-left: 1.5rem;
}

.highlight-box li {
    margin: 0.5rem 0;
    color: var(--text-secondary);
    line-height: 1.6;
}

.airports-grid {
    display: grid;
    gap: 1rem;
    margin-top: 1rem;
}

.airport-card {
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1rem;
    transition: border-color 0.2s;
}

.airport-card:hover {
    border-color: var(--primary-color);
}

.airport-card.main {
    border-color: var(--primary-color);
    background: rgba(37, 99, 235, 0.05);
}

.airport-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 0.5rem;
}

.airport-name {
    font-weight: 600;
    color: var(--text-primary);
}

.airport-code {
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    font-weight: 600;
}

.airport-city {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.breadcrumb {
    padding: 1rem 0;
    font-size: 0.9rem;
}

.breadcrumb a {
    color: var(--primary-color);
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.section-title {
    font-size: 1.5rem;
    margin: 2rem 0 1rem;
    color: var(--text-primary);
}

.description-text {
    line-height: 1.8;
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
}

.visa-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1rem;
}
</style>

<!-- Breadcrumb -->
<div class="container">
    <div class="breadcrumb">
        <a href="index.php">🏠 <?php echo t('home'); ?></a>
        <span> / </span>
        <span><?php echo e($country['country_name']); ?></span>
    </div>
</div>

<!-- Country Hero -->
<section class="country-hero">
    <div class="country-hero-image" style="background-image: linear-gradient(to bottom, rgba(30, 58, 138, 0.7) 0%, rgba(30, 58, 138, 0.9) 100%), url('https://source.unsplash.com/1600x900/?<?php echo urlencode($country['country_name']); ?>,landmark,travel');">
        <div class="container">
            <div class="country-hero-content">
                <div class="country-flag-large">
                    <?php echo $country['flag_emoji']; ?>
                </div>
                <div class="country-title-section">
                    <h1><?php echo e($country['country_name']); ?></h1>
                    <p class="country-subtitle"><?php echo e($country['region']); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="container">
    <!-- Top Visa CTA -->
    <div class="visa-cta-top">
        <div>
            <h3>🎫 <?php echo t('ready_to_apply'); ?></h3>
            <p><?php echo t('get_official_visa_info'); ?></p>
        </div>
        <a href="<?php echo e($country['official_url']); ?>" target="_blank" rel="noopener noreferrer" class="visa-cta-btn">
            <?php echo t('visit_official_site'); ?>
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
            </svg>
        </a>
    </div>

    <div class="country-content">
        <!-- Main Column -->
        <div class="country-main">
            <!-- Visa Status Badge -->
            <span class="visa-badge <?php echo getVisaTypeBadgeClass($country['visa_type']); ?>">
                <?php echo getVisaTypeLabel($country['visa_type']); ?>
            </span>

            <!-- Description -->
            <?php if ($details && !empty($details['description'])): ?>
                <h2 class="section-title"><?php echo t('about'); ?> <?php echo e($country['country_name']); ?></h2>
                <div class="description-text">
                    <?php echo nl2br(e($details['description'])); ?>
                </div>
            <?php endif; ?>

            <!-- Known For -->
            <?php if ($details && !empty($details['known_for'])): ?>
                <h2 class="section-title">🌟 <?php echo e($country['country_name']); ?> <?php echo t('is_known_for'); ?></h2>
                <div class="description-text">
                    <?php echo nl2br(e($details['known_for'])); ?>
                </div>
            <?php endif; ?>

            <!-- Cultural Highlights -->
            <div class="highlight-box">
                <h4>🎭 Cultural Highlights</h4>
                <ul>
                    <?php if (!empty($country['languages'])): ?>
                        <li><strong>Languages:</strong> <?php echo e($country['languages']); ?></li>
                    <?php endif; ?>
                    <?php if (!empty($country['currency_name'])): ?>
                        <li><strong>Currency:</strong> <?php echo e($country['currency_name']); ?> (<?php echo $country['currency_symbol'] ?? $country['currency_code']; ?>)</li>
                    <?php endif; ?>
                    <li><strong>Region:</strong> <?php echo e($country['region']); ?></li>
                </ul>
            </div>

            <!-- Practical Information -->
            <div class="highlight-box">
                <h4>💡 Practical Information</h4>
                <ul>
                    <?php if (!empty($country['time_zone'])): ?>
                        <li><strong>Time Zone:</strong> <?php echo e($country['time_zone']); ?></li>
                    <?php endif; ?>
                    <?php if (!empty($country['calling_code'])): ?>
                        <li><strong>International Dialing Code:</strong> <?php echo e($country['calling_code']); ?></li>
                    <?php endif; ?>
                    <?php if (!empty($country['plug_type'])): ?>
                        <li><strong>Electrical Plugs:</strong> Type <?php echo e($country['plug_type']); ?></li>
                    <?php endif; ?>
                    <li><strong>Capital City:</strong> <?php echo e($country['capital'] ?? 'N/A'); ?></li>
                </ul>
            </div>

            <!-- Visa Requirements -->
            <h2 class="section-title"><?php echo t('visa_requirements'); ?></h2>
            <div class="description-text">
                <?php echo nl2br(e($country['entry_summary'])); ?>
            </div>

            <!-- Visa Information Cards -->
            <div class="visa-info-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin: 1.5rem 0;">
                
                <?php if (!empty($country['visa_duration'])): ?>
                <div class="visa-info-card" style="background: #f0f9ff; border-left: 4px solid #3b82f6; padding: 1rem; border-radius: 0.5rem;">
                    <div  style="font-size: 1.5rem; margin-bottom: 0.5rem;">⏱️</div>
                    <div style="font-weight: bold; color: #1e40af; margin-bottom: 0.25rem;">Duration</div>
                    <div style="color: #374151;"><?php echo e($country['visa_duration']); ?></div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($country['visa_fee'])): ?>
                <div class="visa-info-card" style="background: #ecfdf5; border-left: 4px solid #10b981; padding: 1rem; border-radius: 0.5rem;">
                    <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">💰</div>
                    <div style="font-weight: bold; color: #047857; margin-bottom: 0.25rem;">Visa Fee</div>
                    <div style="color: #374151;"><?php echo e($country['visa_fee']); ?></div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($country['processing_time'])): ?>
                <div class="visa-info-card" style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 1rem; border-radius: 0.5rem;">
                    <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">⚡</div>
                    <div style="font-weight: bold; color: #b45309; margin-bottom: 0.25rem;">Processing Time</div>
                    <div style="color: #374151;"><?php echo e($country['processing_time']); ?></div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($country['passport_validity'])): ?>
                <div class="visa-info-card" style="background: #ede9fe; border-left: 4px solid #8b5cf6; padding: 1rem; border-radius: 0.5rem;">
                    <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🛂</div>
                    <div style="font-weight: bold; color: #6d28d9; margin-bottom: 0.25rem;">Passport Validity</div>
                    <div style="color: #374151;"><?php echo e($country['passport_validity']); ?></div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($country['arrival_card_required'])): ?>
                <div class="visa-info-card" style="background: #fce7f3; border-left: 4px solid #ec4899; padding: 1rem; border-radius: 0.5rem;">
                    <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">📝</div>
                    <div style="font-weight: bold; color: #be185d; margin-bottom: 0.25rem;">Arrival Card</div>
                    <div style="color: #374151;"><?php echo e($country['arrival_card_required']); ?></div>
                </div>
                <?php endif; ?>
                
            </div>

            <!-- Official Visa Application -->
            <?php if (!empty($country['official_visa_url'])): ?>
            <div class="highlight-box" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-left-color: #3b82f6; margin: 1.5rem 0;">
                <h4 style="display: flex; align-items: center; gap: 0.5rem;">
                    <span>🌐</span> Official Visa Information
                </h4>
                <p style="margin: 0.5rem 0 1rem 0; color: #1e40af;">
                    Apply for your visa or get official information from the government website:
                </p>
                <a href="<?php echo e($country['official_visa_url']); ?>" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="btn-primary"
                   style="display: inline-flex; align-items: center; gap: 0.5rem; background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; transition: background 0.3s;">
                    <span>Apply Now / Learn More</span>
                    <span>→</span>
                </a>
            </div>
            <?php endif; ?>

            <h3 style="margin-top: 1.5rem; margin-bottom: 0.75rem;"><?php echo t('requirements_details'); ?></h3>
            <div class="description-text">
                <?php echo nl2br(e($country['visa_requirements'])); ?>
            </div>

            <!-- Required Documents / Additional Information -->
            <?php if (!empty($country['additional_docs'])): ?>
            <h3 style="margin-top: 1.5rem; margin-bottom: 0.75rem;">📄 Additional Requirements & Important Information</h3>
            <div class="additional-docs-formatted">
                <?php
                // Format the additional_docs content
                $additionalDocs = $country['additional_docs'];
                
                // First, convert literal \n to actual newlines if they exist
                $additionalDocs = str_replace(['\\n\\n', '\\n'], ["\n\n", "\n"], $additionalDocs);
                
                // Split by double newlines to get sections
                $sections = preg_split('/\n\n+/', trim($additionalDocs));
                
                foreach ($sections as $section) {
                    $section = trim($section);
                    if (empty($section)) continue;
                    
                    // Check if section starts with a heading (ALL CAPS followed by colon)
                    if (preg_match('/^([A-Z][A-Z\s&\(\)]+?):\s*(.+)/s', $section, $matches)) {
                        $heading = trim($matches[1]);
                        $content = trim($matches[2]);
                        
                        // Determine styling based on heading
                        $isWarning = stripos($heading, 'CRITICAL') !== false || 
                                    stripos($heading, 'WARNING') !== false || 
                                    stripos($heading, 'RISK') !== false ||
                                    stripos($heading, 'PROHIBITED') !== false ||
                                    stripos($heading, 'SEVERE') !== false;
                        
                        $boxStyle = $isWarning 
                            ? 'background: #fee2e2; border-left: 4px solid #dc2626; padding: 1.25rem; margin: 1rem 0; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);'
                            : 'background: #f9fafb; border-left: 4px solid #3b82f6; padding: 1.25rem; margin: 1rem 0; border-radius: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);';
                        
                        $iconStyle = $isWarning ? 'color: #dc2626; font-size: 1.25rem;' : 'color: #3b82f6; font-size: 1.1rem;';
                        $icon = $isWarning ? '⚠️' : '📌';
                        
                        echo '<div style="' . $boxStyle . '">';
                        echo '<h4 style="margin: 0 0 1rem 0; ' . $iconStyle . ' display: flex; align-items: center; gap: 0.5rem; font-size: 1.05rem; font-weight: 600;">';
                        echo '<span>' . $icon . '</span><span>' . e($heading) . '</span>';
                        echo '</h4>';
                        
                        // Format content with better readability
                        $contentLines = explode("\n", $content);
                        echo '<div style="color: #1f2937; line-height: 1.8;">';
                        
                        $currentList = false;
                        foreach ($contentLines as $line) {
                            $line = trim($line);
                            if (empty($line)) continue;
                            
                            // Check if line is a bullet point
                            if (preg_match('/^[-•]\s*(.+)/', $line, $listMatch)) {
                                if (!$currentList) {
                                    echo '<ul style="margin: 0.5rem 0; padding-left: 1.5rem;">';
                                    $currentList = true;
                                }
                                echo '<li style="margin: 0.4rem 0;">' . e($listMatch[1]) . '</li>';
                            } else {
                                if ($currentList) {
                                    echo '</ul>';
                                    $currentList = false;
                                }
                                echo '<p style="margin: 0.5rem 0;">' . e($line) . '</p>';
                            }
                        }
                        
                        if ($currentList) {
                            echo '</ul>';
                        }
                        
                        echo '</div>';
                        echo '</div>';
                    } else {
                        // Regular paragraph without heading
                        echo '<div style="background: #f9fafb; padding: 1rem; margin: 0.5rem 0; border-radius: 0.5rem; line-height: 1.7;">';
                        echo '<p style="margin: 0; color: #374151;">' . nl2br(e($section)) . '</p>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <?php endif; ?>

            <!-- Last Verified -->
            <?php if (!empty($country['last_verified'])): ?>
            <div style="margin-top: 1rem; padding: 0.5rem; background: #f3f4f6; border-radius: 0.25rem; font-size: 0.875rem; color: #6b7280;">
                ℹ️ Last verified: <?php echo date('F j, Y', strtotime($country['last_verified'])); ?>
            </div>
            <?php endif; ?>

            <!-- Travel Tips -->
            <?php if ($details && !empty($details['travel_tips'])): ?>
                <h2 class="section-title">✈️ <?php echo t('travel_tips'); ?></h2>
                <div class="description-text">
                    <?php echo nl2br(e($details['travel_tips'])); ?>
                </div>
            <?php endif; ?>

            <!-- Entry Requirements Summary -->
            <div class="highlight-box" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left-color: #f59e0b;">
                <h4>📋 Entry Requirements Summary</h4>
                <ul>
                    <li><strong>Visa Type:</strong> <?php echo getVisaTypeLabel($country['visa_type']); ?></li>
                    <li><strong>Processing:</strong> <?php echo e($country['entry_summary']); ?></li>
                    <?php if (!empty($country['population'])): ?>
                        <li><strong>Population:</strong> <?php echo e($country['population']); ?></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Airports -->
            <?php if (!empty($airports)): ?>
                <h2 class="section-title"><?php echo t('major_airports'); ?></h2>
                <div class="airports-grid">
                    <?php foreach ($airports as $airport): ?>
                        <div class="airport-card <?php echo $airport['is_main'] ? 'main' : ''; ?>">
                            <div class="airport-header">
                                <div>
                                    <div class="airport-name"><?php echo e($airport['airport_name']); ?></div>
                                    <div class="airport-city">📍 <?php echo e($airport['city']); ?></div>
                                </div>
                                <div class="airport-code"><?php echo e($airport['airport_code']); ?></div>
                            </div>
                            <?php if (!empty($airport['website_url'])): ?>
                                <a href="<?php echo e($airport['website_url']); ?>" target="_blank" rel="noopener" style="font-size: 0.9rem; color: var(--primary-color);">
                                    <?php echo t('visit_website'); ?> →
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="country-sidebar">
            <!-- Quick Facts -->
            <div class="info-card">
                <h3>📊 <?php echo t('quick_facts'); ?></h3>
                
                <?php if (!empty($country['capital'])): ?>
                <div class="info-row">
                    <span class="info-label"><?php echo t('capital'); ?></span>
                    <span class="info-value"><?php echo e($country['capital']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($country['population'])): ?>
                <div class="info-row">
                    <span class="info-label"><?php echo t('population'); ?></span>
                    <span class="info-value"><?php echo e($country['population']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($country['languages'])): ?>
                <div class="info-row">
                    <span class="info-label"><?php echo t('languages'); ?></span>
                    <span class="info-value"><?php echo e($country['languages']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($country['currency_name'])): ?>
                <div class="info-row">
                    <span class="info-label"><?php echo t('currency'); ?></span>
                    <span class="info-value">
                        <?php echo e($country['currency_name']); ?>
                        <?php if (!empty($country['currency_symbol'])): ?>
                            (<?php echo $country['currency_symbol']; ?>)
                        <?php endif; ?>
                    </span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($country['time_zone'])): ?>
                <div class="info-row">
                    <span class="info-label"><?php echo t('time_zone'); ?></span>
                    <span class="info-value"><?php echo e($country['time_zone']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($country['calling_code'])): ?>
                <div class="info-row">
                    <span class="info-label"><?php echo t('calling_code'); ?></span>
                    <span class="info-value"><?php echo e($country['calling_code']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($country['plug_type'])): ?>
                <div class="info-row">
                    <span class="info-label"><?php echo t('plug_type'); ?></span>
                    <span class="info-value"><?php echo e($country['plug_type']); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bottom Visa CTA -->
    <div class="visa-cta">
        <h2>🎫 <?php echo t('ready_to_apply'); ?></h2>
        <p style="margin-bottom: 1.5rem; font-size: 1.1rem; opacity: 0.95;">
            Get your visa application started today. Visit the official government website for the most up-to-date information.
        </p>
        <a href="<?php echo e($country['official_url']); ?>" target="_blank" rel="noopener noreferrer" class="visa-cta-btn">
            <?php echo t('visit_official_site'); ?>
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
            </svg>
        </a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
