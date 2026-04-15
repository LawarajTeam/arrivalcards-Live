<?php
/**
 * Sitemap Generator Cron Script
 * Called daily by cron job to generate static sitemap.xml with all countries and pages
 * Can also be called manually from admin panel
 * 
 * Cron Command:
 * 0 2 * * * curl -s https://arrivalcards.com/admin/generate_sitemap_cron.php >> /path/to/sitemap.log 2>&1
 * 
 * Or with wget:
 * 0 2 * * * wget -q -O - https://arrivalcards.com/admin/generate_sitemap_cron.php >> /path/to/sitemap.log 2>&1
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Only allow CLI execution or admin IP validation for security
$isCliRequest = (php_sapi_name() === 'cli');
$isAdminRequest = isset($_SESSION['admin_id']) && $_SESSION['admin_id'];
$isLocalhost = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1', 'localhost']);

if (!$isCliRequest && !$isAdminRequest && !$isLocalhost) {
    http_response_code(403);
    die('Access Denied');
}

$sitemapPath = __DIR__ . '/../sitemap.xml';
$logPath = __DIR__ . '/../sitemap_generation.log';

try {
    // Start output buffering to capture sitemap XML
    ob_start();
    
    // Get all active countries from database
    try {
        $stmt = $pdo->prepare("SELECT id, country_code, updated_at FROM countries WHERE is_active = 1 ORDER BY country_code ASC");
        $stmt->execute();
        $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Sitemap Error: " . $e->getMessage());
        $countries = [];
    }

    // Supported languages
    $languages = ['en', 'es', 'zh', 'fr', 'de', 'it', 'ar'];

    // Base URL
    $baseUrl = rtrim(APP_URL, '/');

    // Current date for lastmod
    $today = date('Y-m-d');

    // Calculate total URLs
    $staticPageCount = 8;
    $totalUrls = count($languages) + (count($countries) * count($languages)) + ($staticPageCount * count($languages));

    // Start building XML
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo PHP_EOL;
    ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    <!-- 
    ArrivalCards.com Dynamic Sitemap
    Total URLs: <?php echo $totalUrls; ?>
    
    Generated: <?php echo date('Y-m-d H:i:s'); ?>
    
    Priority Scale:
    1.0 = Homepage
    0.9 = Popular country pages
    0.8 = Standard country pages
    0.7 = Content pages (best-passports, compare, faq)
    0.6 = Utility pages (about, contact, report-error)
    0.5 = Legal pages (privacy, terms)
    -->
    
    <!-- HOMEPAGE -->
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
    
    <!-- ALL COUNTRIES (<?php echo count($countries); ?> countries) -->
    <?php 
    $popularCountries = ['US', 'GB', 'FR', 'DE', 'ES', 'IT', 'JP', 'CN', 'AU', 'CA', 
                        'MX', 'BR', 'IN', 'TH', 'SG', 'AE', 'NZ', 'KR', 'MY', 'ID'];
    
    foreach ($countries as $country): 
        $countryId = $country['id'];
        $countryCode = $country['country_code'];
        $isPopular = in_array($countryCode, $popularCountries);
        $priority = $isPopular ? '0.9' : '0.8';
        $lastMod = isset($country['updated_at']) && $country['updated_at'] 
                   ? date('Y-m-d', strtotime($country['updated_at'])) 
                   : $today;
    ?>
        <?php foreach ($languages as $lang): ?>
    <url>
        <loc><?php echo $baseUrl; ?>/country?id=<?php echo $countryId; ?>&amp;lang=<?php echo $lang; ?></loc>
        <lastmod><?php echo $lastMod; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority><?php echo $priority; ?></priority>
        <?php foreach ($languages as $altLang): ?>
        <xhtml:link rel="alternate" hreflang="<?php echo $altLang; ?>" href="<?php echo $baseUrl; ?>/country?id=<?php echo $countryId; ?>&amp;lang=<?php echo $altLang; ?>"/>
        <?php endforeach; ?>
        <xhtml:link rel="alternate" hreflang="x-default" href="<?php echo $baseUrl; ?>/country?id=<?php echo $countryId; ?>&amp;lang=en"/>
        <image:image>
            <image:loc><?php echo $baseUrl; ?>/assets/images/flags/<?php echo strtolower($countryCode); ?>.svg</image:loc>
            <image:title><?php echo htmlspecialchars($countryCode, ENT_XML1, 'UTF-8'); ?> Flag</image:title>
        </image:image>
    </url>
        <?php endforeach; ?>
    <?php endforeach; ?>
    
    <!-- STATIC PAGES -->
    <?php 
    $staticPages = [
        'best-passports' => ['priority' => '0.8', 'changefreq' => 'weekly'],
        'compare-passports' => ['priority' => '0.7', 'changefreq' => 'weekly'],
        'faq' => ['priority' => '0.7', 'changefreq' => 'monthly'],
        'about' => ['priority' => '0.6', 'changefreq' => 'monthly'],
        'contact' => ['priority' => '0.6', 'changefreq' => 'monthly'],
        'privacy' => ['priority' => '0.5', 'changefreq' => 'yearly'],
        'terms' => ['priority' => '0.5', 'changefreq' => 'yearly'],
        'report-error' => ['priority' => '0.6', 'changefreq' => 'monthly'],
    ];
    
    foreach ($staticPages as $page => $meta):
    ?>
    <url>
        <loc><?php echo $baseUrl; ?>/<?php echo $page; ?></loc>
        <lastmod><?php echo $today; ?></lastmod>
        <changefreq><?php echo $meta['changefreq']; ?></changefreq>
        <priority><?php echo $meta['priority']; ?></priority>
    </url>
    <?php endforeach; ?>

</urlset>
<?php
    
    // Get the generated XML
    $xmlContent = ob_get_clean();
    
    // Write to file
    if (file_put_contents($sitemapPath, $xmlContent) === false) {
        throw new Exception("Failed to write sitemap to: $sitemapPath");
    }
    
    // Log the generation
    $logMessage = date('Y-m-d H:i:s') . " - Sitemap generated successfully\n";
    file_put_contents($logPath, $logMessage, FILE_APPEND);
    
    // Output result
    if ($isCliRequest) {
        echo "✓ Sitemap generated successfully at $sitemapPath\n";
        echo "  Total URLs: $totalUrls\n";
        echo "  Countries: " . count($countries) . "\n";
        echo "  File size: " . filesize($sitemapPath) . " bytes\n";
    } else {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Sitemap generated successfully',
            'path' => $sitemapPath,
            'total_urls' => $totalUrls,
            'countries' => count($countries),
            'file_size' => filesize($sitemapPath),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
} catch (Exception $e) {
    $errorMessage = date('Y-m-d H:i:s') . " - ERROR: " . $e->getMessage() . "\n";
    file_put_contents($logPath, $errorMessage, FILE_APPEND);
    
    if ($isCliRequest) {
        echo "✗ Error: " . $e->getMessage() . "\n";
        exit(1);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
?>
