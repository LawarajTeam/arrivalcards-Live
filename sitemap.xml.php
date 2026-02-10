<?php
/**
 * Sitemap Generator
 * Generates XML sitemap for search engines
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/xml; charset=utf-8');

// Get all countries
$countries = getCountries();
$languages = ['en', 'es', 'zh', 'fr', 'de', 'it', 'ar'];

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
    
    <!-- Homepage -->
    <?php foreach ($languages as $lang): ?>
    <url>
        <loc><?php echo APP_URL; ?>/index.php?lang=<?php echo $lang; ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
        <?php foreach ($languages as $altLang): ?>
        <xhtml:link rel="alternate" hreflang="<?php echo $altLang; ?>" href="<?php echo APP_URL; ?>/index.php?lang=<?php echo $altLang; ?>"/>
        <?php endforeach; ?>
    </url>
    <?php endforeach; ?>
    
    <!-- Country Detail Pages (if they exist) -->
    <?php foreach ($countries as $country): ?>
        <?php if (isset($country['slug'])): ?>
        <?php foreach ($languages as $lang): ?>
        <url>
            <loc><?php echo APP_URL; ?>/country.php?id=<?php echo $country['id']; ?>&amp;lang=<?php echo $lang; ?></loc>
            <lastmod><?php echo date('Y-m-d', strtotime($country['updated_at'] ?? 'now')); ?></lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
            <?php foreach ($languages as $altLang): ?>
            <xhtml:link rel="alternate" hreflang="<?php echo $altLang; ?>" href="<?php echo APP_URL; ?>/country.php?id=<?php echo $country['id']; ?>&amp;lang=<?php echo $altLang; ?>"/>
            <?php endforeach; ?>
        </url>
        <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>
    
    <!-- Static Pages -->
    <?php 
    $staticPages = [
        ['url' => 'about.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ['url' => 'contact.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => 'privacy-policy.php', 'priority' => '0.5', 'changefreq' => 'yearly'],
        ['url' => 'report-error.php', 'priority' => '0.6', 'changefreq' => 'monthly']
    ];
    ?>
    <?php foreach ($staticPages as $page): ?>
        <?php foreach ($languages as $lang): ?>
        <url>
            <loc><?php echo APP_URL; ?>/<?php echo $page['url']; ?>?lang=<?php echo $lang; ?></loc>
            <lastmod><?php echo date('Y-m-d'); ?></lastmod>
            <changefreq><?php echo $page['changefreq']; ?></changefreq>
            <priority><?php echo $page['priority']; ?></priority>
            <?php foreach ($languages as $altLang): ?>
            <xhtml:link rel="alternate" hreflang="<?php echo $altLang; ?>" href="<?php echo APP_URL; ?>/<?php echo $page['url']; ?>?lang=<?php echo $altLang; ?>"/>
            <?php endforeach; ?>
        </url>
        <?php endforeach; ?>
    <?php endforeach; ?>
    
</urlset>
