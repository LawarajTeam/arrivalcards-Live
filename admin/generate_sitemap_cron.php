<?php
/**
 * Sitemap Index Generator
 * Produces sitemap.xml (index) + sitemap-{lang}.xml per language
 * 0 2 * * * curl -s https://arrivalcards.com/admin/generate_sitemap_cron.php
 */

set_time_limit(120);
ini_set('memory_limit', '256M');

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

$isCliRequest   = (php_sapi_name() === 'cli');
$isAdminRequest = isset($_SESSION['admin_id']) && $_SESSION['admin_id'];
$isLocalhost    = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1', 'localhost']);

if (!$isCliRequest && !$isAdminRequest && !$isLocalhost) {
    http_response_code(403);
    die('Access Denied');
}

$rootPath = __DIR__ . '/../';
$logPath  = $rootPath . 'sitemap_generation.log';

try {
    $stmt = $pdo->prepare("SELECT id, country_code, updated_at FROM countries WHERE is_active = 1 ORDER BY country_code ASC");
    $stmt->execute();
    $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $languages = ['en', 'es', 'zh', 'fr', 'de', 'it', 'ar'];
    $baseUrl   = rtrim(APP_URL, '/');
    $today     = date('Y-m-d');

    $popularCountries = ['US', 'GB', 'FR', 'DE', 'ES', 'IT', 'JP', 'CN', 'AU', 'CA',
                         'MX', 'BR', 'IN', 'TH', 'SG', 'AE', 'NZ', 'KR', 'MY', 'ID'];

    $staticPages = [
        'best-passports'    => ['priority' => '0.8', 'changefreq' => 'weekly'],
        'compare-passports' => ['priority' => '0.7', 'changefreq' => 'weekly'],
        'faq'               => ['priority' => '0.7', 'changefreq' => 'monthly'],
        'about'             => ['priority' => '0.6', 'changefreq' => 'monthly'],
        'contact'           => ['priority' => '0.6', 'changefreq' => 'monthly'],
        'privacy'           => ['priority' => '0.5', 'changefreq' => 'yearly'],
        'terms'             => ['priority' => '0.5', 'changefreq' => 'yearly'],
        'report-error'      => ['priority' => '0.6', 'changefreq' => 'monthly'],
    ];

    $filesWritten  = [];
    $totalUrlCount = 0;

    // --- Generate one sitemap per language ---
    foreach ($languages as $lang) {
        $xml  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
        $xml .= "        xmlns:xhtml=\"http://www.w3.org/1999/xhtml\"\n";
        $xml .= "        xmlns:image=\"http://www.google.com/schemas/sitemap-image/1.1\">\n";

        // Homepage for this language
        $xml .= "  <url>\n";
        $xml .= "    <loc>{$baseUrl}/{$lang}/</loc>\n";
        $xml .= "    <lastmod>{$today}</lastmod>\n";
        $xml .= "    <changefreq>daily</changefreq>\n";
        $xml .= "    <priority>1.0</priority>\n";
        foreach ($languages as $alt) {
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"{$alt}\" href=\"{$baseUrl}/{$alt}/\"/>\n";
        }
        $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"{$baseUrl}/en/\"/>\n";
        $xml .= "  </url>\n";

        // Country pages
        foreach ($countries as $country) {
            $code     = strtolower($country['country_code']);
            $priority = in_array(strtoupper($code), $popularCountries) ? '0.9' : '0.8';
            $lastMod  = !empty($country['updated_at']) ? date('Y-m-d', strtotime($country['updated_at'])) : $today;

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$baseUrl}/{$lang}/country/{$code}</loc>\n";
            $xml .= "    <lastmod>{$lastMod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>{$priority}</priority>\n";
            foreach ($languages as $alt) {
                $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"{$alt}\" href=\"{$baseUrl}/{$alt}/country/{$code}\"/>\n";
            }
            $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"{$baseUrl}/en/country/{$code}\"/>\n";
            $xml .= "    <image:image>\n";
            $xml .= "      <image:loc>{$baseUrl}/assets/images/flags/{$code}.svg</image:loc>\n";
            $xml .= "      <image:title>" . htmlspecialchars(strtoupper($code), ENT_XML1, 'UTF-8') . " Flag</image:title>\n";
            $xml .= "    </image:image>\n";
            $xml .= "  </url>\n";
        }

        // Static pages (only add to English to avoid duplicate indexing of non-localised pages)
        if ($lang === 'en') {
            foreach ($staticPages as $page => $meta) {
                $xml .= "  <url>\n";
                $xml .= "    <loc>{$baseUrl}/{$page}</loc>\n";
                $xml .= "    <lastmod>{$today}</lastmod>\n";
                $xml .= "    <changefreq>{$meta['changefreq']}</changefreq>\n";
                $xml .= "    <priority>{$meta['priority']}</priority>\n";
                $xml .= "  </url>\n";
            }
        }

        $xml .= "</urlset>\n";

        $filePath = $rootPath . "sitemap-{$lang}.xml";
        if (file_put_contents($filePath, $xml) === false) {
            throw new Exception("Failed to write {$filePath}");
        }

        $urlsInFile = 1 + count($countries) + ($lang === 'en' ? count($staticPages) : 0);
        $totalUrlCount += $urlsInFile;
        $filesWritten[$lang] = [
            'file'  => "sitemap-{$lang}.xml",
            'urls'  => $urlsInFile,
            'size'  => filesize($filePath),
        ];
    }

    // --- Generate sitemap index ---
    $index  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $index .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach ($languages as $lang) {
        $index .= "  <sitemap>\n";
        $index .= "    <loc>{$baseUrl}/sitemap-{$lang}.xml</loc>\n";
        $index .= "    <lastmod>{$today}</lastmod>\n";
        $index .= "  </sitemap>\n";
    }
    $index .= "</sitemapindex>\n";

    $indexPath = $rootPath . 'sitemap.xml';
    if (file_put_contents($indexPath, $index) === false) {
        throw new Exception("Failed to write sitemap index");
    }

    file_put_contents($logPath,
        date('Y-m-d H:i:s') . " - Sitemap index generated: " . count($countries) . " countries, {$totalUrlCount} total URLs across " . count($languages) . " language sitemaps\n",
        FILE_APPEND
    );

    if ($isCliRequest) {
        echo "Sitemap index generated successfully\n";
        echo "Countries  : " . count($countries) . "\n";
        echo "Languages  : " . count($languages) . "\n";
        echo "Total URLs : {$totalUrlCount}\n";
        foreach ($filesWritten as $lang => $info) {
            echo "  sitemap-{$lang}.xml : {$info['urls']} URLs, " . round($info['size']/1024, 1) . " KB\n";
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success'    => true,
            'message'    => 'Sitemap index generated successfully',
            'countries'  => count($countries),
            'languages'  => count($languages),
            'total_urls' => $totalUrlCount,
            'files'      => $filesWritten,
            'timestamp'  => date('Y-m-d H:i:s'),
        ]);
    }

} catch (Exception $e) {
    file_put_contents($logPath,
        date('Y-m-d H:i:s') . " - ERROR: " . $e->getMessage() . "\n",
        FILE_APPEND
    );
    if ($isCliRequest) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}