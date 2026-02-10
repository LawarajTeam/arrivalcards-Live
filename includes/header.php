<?php
// Force UTF-8 output
header('Content-Type: text/html; charset=utf-8');

if (!defined('CURRENT_LANG')) {
    require_once __DIR__ . '/config.php';
}
require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? t('site_title');
$languages = getLanguages();
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="<?php echo CURRENT_LANG; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo e(isset($pageDescription) ? $pageDescription : t('site_tagline')); ?>">
    <meta name="keywords" content="<?php echo e(isset($pageKeywords) ? $pageKeywords : 'visa, travel, arrival cards, visa requirements, international travel'); ?>">
    <meta name="author" content="Arrival Cards">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <link rel="canonical" href="<?php echo APP_URL . $_SERVER['PHP_SELF']; ?>">
    <title><?php echo e(isset($pageTitle) ? $pageTitle : t('site_title')); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo APP_URL; ?>/assets/images/logo.svg">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
    
    <!-- Open Graph for social sharing -->
    <meta property="og:title" content="<?php echo e(isset($pageTitle) ? $pageTitle : t('site_title')); ?>">
    <meta property="og:description" content="<?php echo e(isset($pageDescription) ? $pageDescription : t('site_tagline')); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo APP_URL . $_SERVER['PHP_SELF']; ?>">
    <meta property="og:site_name" content="Arrival Cards">
    <meta property="og:locale" content="<?php echo CURRENT_LANG; ?>_<?php echo strtoupper(CURRENT_LANG); ?>">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo e(isset($pageTitle) ? $pageTitle : t('site_title')); ?>">
    <meta name="twitter:description" content="<?php echo e(isset($pageDescription) ? $pageDescription : t('site_tagline')); ?>">
    
    <?php if (isset($structuredData)): ?>
    <!-- Structured Data -->
    <script type="application/ld+json">
    <?php echo json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
    </script>
    <?php endif; ?>
    
    <?php if (isset($organizationData)): ?>
    <script type="application/ld+json">
    <?php echo json_encode($organizationData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
    </script>
    <?php endif; ?>
    
    <!-- Language Alternate Links for SEO -->
    <?php 
    $langCodes = ['en', 'es', 'zh', 'fr', 'de', 'it', 'ar'];
    $currentUrl = $_SERVER['PHP_SELF'];
    foreach ($langCodes as $langCode): 
    ?>
    <link rel="alternate" hreflang="<?php echo $langCode; ?>" href="<?php echo APP_URL . $currentUrl . '?lang=' . $langCode; ?>">
    <?php endforeach; ?>
    <link rel="alternate" hreflang="x-default" href="<?php echo APP_URL . $currentUrl; ?>">
    
    <?php if (isset($additionalCSS)): ?>
        <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/<?php echo $additionalCSS; ?>">
    <?php endif; ?>
    
    <!-- Google Analytics - Add your tracking ID -->
    <?php if (getenv('GA_TRACKING_ID')): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo getenv('GA_TRACKING_ID'); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo getenv('GA_TRACKING_ID'); ?>');
    </script>
    <?php endif; ?>
    
    <!-- Google AdSense -->
    <?php 
    if (file_exists(__DIR__ . '/adsense_functions.php')) {
        require_once __DIR__ . '/adsense_functions.php';
        echo getAdSenseScript();
    }
    ?>
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <a href="<?php echo APP_URL; ?>/index.php" aria-label="Home">
                        <img src="<?php echo APP_URL; ?>/assets/images/logo.svg" alt="<?php echo e(t('site_title')); ?>" class="logo-image">
                    </a>
                </div>
                
                <!-- Language Switcher -->
                <div class="language-switcher">
                    <button class="lang-dropdown-btn" aria-haspopup="true" aria-expanded="false" aria-label="<?php echo e(t('language')); ?>">
                        <span class="lang-label"><?php echo e(t('language')); ?>:</span>
                        <span class="current-lang-flag">
                            <?php 
                            foreach ($languages as $lang) {
                                if ($lang['code'] === CURRENT_LANG) {
                                    echo $lang['flag_emoji'] . ' ' . $lang['code'];
                                    break;
                                }
                            }
                            ?>
                        </span>
                        <span class="lang-dropdown-arrow">▼</span>
                    </button>
                    <ul class="lang-dropdown-menu" role="menu">
                        <?php foreach ($languages as $lang): ?>
                            <li role="none">
                                <a href="<?php echo APP_URL; ?>/set_language.php?lang=<?php echo $lang['code']; ?>&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                                   role="menuitem"
                                   class="<?php echo $lang['code'] === CURRENT_LANG ? 'active' : ''; ?>">
                                    <span class="lang-flag"><?php echo $lang['flag_emoji']; ?></span>
                                    <span class="lang-name"><?php echo e($lang['native_name']); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" aria-label="Toggle menu" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>
    
    <!-- Flash Message -->
    <?php if ($flash = getFlashMessage()): ?>
        <div class="flash-message flash-<?php echo e($flash['type']); ?>" role="alert">
            <div class="container">
                <p><?php echo e($flash['message']); ?></p>
                <button class="flash-close" aria-label="Close message">&times;</button>
            </div>
        </div>
    <?php endif; ?>
    
    <main class="site-content">
