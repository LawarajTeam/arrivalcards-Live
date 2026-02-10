<?php
/**
 * 404 Error Page
 * Displayed when a page is not found
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Page Not Found';
http_response_code(404);
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<section class="countries-section">
    <div class="container">
        <div style="max-width: 600px; margin: 0 auto; text-align: center;">
            <div style="font-size: 120px; margin-bottom: 1rem;">🧭</div>
            <h1 style="font-size: 3rem; margin-bottom: 1rem;">404</h1>
            <h2 style="color: var(--text-secondary); margin-bottom: 2rem;">Page Not Found</h2>
            <p style="font-size: 1.125rem; color: var(--text-secondary); margin-bottom: 2rem;">
                Sorry, the page you're looking for doesn't exist or has been moved.
            </p>
            
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo APP_URL; ?>/index.php" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Go to Homepage
                </a>
                <a href="<?php echo APP_URL; ?>/contact.php" class="btn btn-secondary">
                    Contact Us
                </a>
            </div>
            
            <div style="margin-top: 3rem; padding: 2rem; background-color: var(--bg-secondary); border-radius: var(--radius-lg);">
                <h3 style="margin-bottom: 1rem;">Quick Links</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 0.5rem;">
                        <a href="<?php echo APP_URL; ?>/index.php" style="color: var(--primary-color);">Browse Countries</a>
                    </li>
                    <li style="margin-bottom: 0.5rem;">
                        <a href="<?php echo APP_URL; ?>/contact.php" style="color: var(--primary-color);">Contact Us</a>
                    </li>
                    <li style="margin-bottom: 0.5rem;">
                        <a href="<?php echo APP_URL; ?>/privacy.php" style="color: var(--primary-color);">Privacy Policy</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
