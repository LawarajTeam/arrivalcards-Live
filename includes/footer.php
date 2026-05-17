    </main>
    
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section footer-about">
                    <h3><?php echo e(t('site_title')); ?></h3>
                    <p><?php echo e(t('site_tagline')); ?></p>
                </div>
                
                <div class="footer-section footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo APP_URL; ?>/"><?php echo e(t('home')); ?></a></li>
                        <li><a href="<?php echo APP_URL; ?>/about">About Us</a></li>
                        <li><a href="<?php echo APP_URL; ?>/faq">FAQ</a></li>
                        <li><a href="<?php echo APP_URL; ?>/contact"><?php echo e(t('contact_us')); ?></a></li>
                        <li><a href="<?php echo APP_URL; ?>/privacy"><?php echo e(t('privacy_policy')); ?></a></li>
                        <li><a href="<?php echo APP_URL; ?>/terms">Terms of Service</a></li>
                        <li><a href="<?php echo APP_URL; ?>/report-error">Report an Error</a></li>
                    </ul>
                </div>
                
                <div class="footer-section footer-stats">
                    <h4><?php echo e(t('total_countries')); ?></h4>
                    <p class="stat-number"><?php echo getCountryCount(); ?></p>
                    <p class="stat-label"><?php echo e(t('last_updated')); ?>: <?php echo formatDate(date('Y-m-d')); ?></p>
                    <?php
                    // Site-wide view stats
                    $footerViewStats = ['today' => 0, 'total' => 0];
                    try {
                        $row = $pdo->query("
                            SELECT
                                COUNT(*) AS total_views,
                                SUM(CASE WHEN DATE(viewed_at) = CURDATE() THEN 1 ELSE 0 END) AS today_views
                            FROM page_views
                        ")->fetch();
                        $footerViewStats = [
                            'today' => (int)($row['today_views'] ?? 0),
                            'total' => (int)($row['total_views'] ?? 0),
                        ];
                    } catch (Exception $e) {}
                    ?>
                    <p class="stat-label" style="margin-top:1rem;">Page Views</p>
                    <p class="stat-number"><?php echo number_format($footerViewStats['today']); ?></p>
                    <p class="stat-label">Today</p>
                    <p class="stat-number" style="margin-top:0.4rem;"><?php echo number_format($footerViewStats['total']); ?></p>
                    <p class="stat-label">All Time</p>
                </div>
            </div>
            
            <div class="footer-disclaimer">
                <p>⚠️ <?php echo e(t('footer_disclaimer')); ?></p>
            </div>
            
            <div class="footer-bottom">
                <p><?php echo e(t('footer_copyright')); ?></p>
                <p class="footer-sda">An <a href="https://www.shmarlo.com" target="_blank" rel="noopener noreferrer">SDA Project</a></p>
            </div>
        </div>
    </footer>
    
    <!-- Floating Callback Button (hidden on the callback page itself) -->
    <?php if (basename($_SERVER['PHP_SELF']) !== 'request-callback.php'): ?>
    <a href="<?php echo APP_URL; ?>/request-callback.php" class="callback-fab" aria-label="Talk to a visa agent">
        <span class="callback-fab-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.37 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 5.53 5.53l1.62-1.85a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
        </span>
        <span class="callback-fab-label">Talk to an Agent</span>
    </a>
    <?php endif; ?>

    <!-- JavaScript -->
    <script src="/assets/js/main.js" defer></script>
    <script src="/assets/js/passport-personalization.js" defer></script>
    <?php if (isset($additionalJS)): ?>
        <script src="/assets/js/<?php echo $additionalJS; ?>"></script>
    <?php endif; ?>
</body>
</html>
