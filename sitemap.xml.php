<?php
/**
 * Comprehensive Sitemap Generator for ArrivalCards.com
 * Generates XML sitemap with all pages for optimal SEO and Google indexing
 * Includes: Homepage, All Country Pages (195+ countries), Static Pages
 * Multi-language support: EN, ES, ZH, FR, DE, IT, AR
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Set XML header
header('Content-Type: application/xml; charset=utf-8');

// Cache sitemap for 1 hour to improve performance
header('Cache-Control: max-age=3600, public');

// Get all active countries from database
try {
    $stmt = $pdo->prepare("SELECT id, code, updated_at FROM countries WHERE is_active = 1 ORDER BY code ASC");
    $stmt->execute();
    $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Sitemap Error: " . $e->getMessage());
    $countries = [];
}

// Supported languages
$languages = ['en', 'es', 'zh', 'fr', 'de', 'it', 'ar'];
$languageNames = [
    'en' => 'English',
    'es' => 'Español',
    'zh' => '中文',
    'fr' => 'Français',
    'de' => 'Deutsch',
    'it' => 'Italiano',
    'ar' => 'العربية'
];

// Base URL (remove trailing slash if present)
$baseUrl = rtrim(APP_URL, '/');

// Current date for lastmod
$today = date('Y-m-d');

// Calculate total URLs
$totalUrls = count($languages) + (count($countries) * count($languages)) + (3 * count($languages));

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    <!-- 
    ArrivalCards.com Comprehensive Sitemap
    Total URLs: <?php echo $totalUrls; ?>
    
    Generated: <?php echo date('Y-m-d H:i:s'); ?>
    
    Priority Scale:
    1.0 = Homepage (highest priority)
    0.9 = Popular country pages (high traffic)
    0.8 = Standard country pages
    0.6 = Contact and utility pages
    0.5 = Privacy and legal pages
    -->
    
    <!-- ========================================
         HOMEPAGE - Maximum Priority
         ======================================== -->
    <?php foreach ($languages as $lang): ?>
    <url>
        <loc><?php echo $baseUrl; ?>/?lang=<?php echo $lang; ?></loc>
        <lastmod><?php echo $today; ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
        <?php foreach ($languages as $altLang): ?>
        <xhtml:link rel="alternate" hreflang="<?php echo $altLang; ?>" href="<?php echo $baseUrl; ?>/?lang=<?php echo $altLang; ?>"/>
        <?php endforeach; ?>
        <xhtml:link rel="alternate" hreflang="x-default" href="<?php echo $baseUrl; ?>/?lang=en"/>
    </url>
    <?php endforeach; ?>
    
    <!-- ========================================
         COUNTRY DETAIL PAGES - All <?php echo count($countries); ?> Countries
         High Priority for SEO
         ======================================== -->
    <?php 
    // Popular countries get higher priority
    $popularCountries = ['US', 'GB', 'FR', 'DE', 'ES', 'IT', 'JP', 'CN', 'AU', 'CA', 
                        'MX', 'BR', 'IN', 'TH', 'SG', 'AE', 'NZ', 'KR', 'MY', 'ID'];
    
    foreach ($countries as $country): 
        $countryId = $country['id'];
        $countryCode = $country['code'];
        $isPopular = in_array($countryCode, $popularCountries);
        $priority = $isPopular ? '0.9' : '0.8';
        $lastMod = isset($country['updated_at']) && $country['updated_at'] 
                   ? date('Y-m-d', strtotime($country['updated_at'])) 
                   : $today;
    ?>
        <?php foreach ($languages as $lang): ?>
    <url>
        <loc><?php echo $baseUrl; ?>/country.php?id=<?php echo $countryId; ?>&amp;lang=<?php echo $lang; ?></loc>
        <lastmod><?php echo $lastMod; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority><?php echo $priority; ?></priority>
        <?php foreach ($languages as $altLang): ?>
        <xhtml:link rel="alternate" hreflang="<?php echo $altLang; ?>" href="<?php echo $baseUrl; ?>/country.php?id=<?php echo $countryId; ?>&amp;lang=<?php echo $altLang; ?>"/>
        <?php endforeach; ?>
        <xhtml:link rel="alternate" hreflang="x-default" href="<?php echo $baseUrl; ?>/country.php?id=<?php echo $countryId; ?>&amp;lang=en"/>
        <image:image>
            <image:loc><?php echo $baseUrl; ?>/assets/images/flags/<?php echo strtolower($countryCode); ?>.svg</image:loc>
            <image:title><?php echo htmlspecialchars($countryCode, ENT_XML1, 'UTF-8'); ?> Flag</image:title>
        </image:image>
    </url>
        <?php endforeach; ?>
    <?php endforeach; ?>
    
    <!-- ========================================
         STATIC PAGES - Contact, Privacy, etc.
         ======================================== -->
    <?php 
    $staticPages = [
        [
            'file' => 'contact.php', 
            'priority' => '0.6', 
            'changefreq' => 'monthly',
            'description' => 'Contact form for visa inquiries'
        ],
        [
            'file' => 'privacy.php', 
            'priority' => '0.5', 
            'changefreq' => 'yearly',
            'description' => 'Privacy policy and data protection'
        ],
        [
            'file' => 'report-error.php', 
            'priority' => '0.6', 
            'changefreq' => 'monthly',
            'description' => 'Report incorrect visa information'
        ]
    ];
    
    foreach ($staticPages as $page): 
        foreach ($languages as $lang): 
    ?>
    <url>
        <loc><?php echo $baseUrl; ?>/<?php echo $page['file']; ?>?lang=<?php echo $lang; ?></loc>
        <lastmod><?php echo $today; ?></lastmod>
        <changefreq><?php echo $page['changefreq']; ?></changefreq>
        <priority><?php echo $page['priority']; ?></priority>
        <?php foreach ($languages as $altLang): ?>
        <xhtml:link rel="alternate" hreflang="<?php echo $altLang; ?>" href="<?php echo $baseUrl; ?>/<?php echo $page['file']; ?>?lang=<?php echo $altLang; ?>"/>
        <?php endforeach; ?>
        <xhtml:link rel="alternate" hreflang="x-default" href="<?php echo $baseUrl; ?>/<?php echo $page['file']; ?>?lang=en"/>
    </url>
        <?php endforeach; ?>
    <?php endforeach; ?>
    
</urlset>
