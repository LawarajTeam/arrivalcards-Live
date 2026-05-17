    </main>

    <footer class="site-footer">
        <div class="container">
            <!-- Main row: brand · links · stats -->
            <div class="footer-main">
                <span class="footer-brand"><?php echo e(t('site_title')); ?></span>
                <nav class="footer-nav">
                    <a href="<?php echo APP_URL; ?>/"><?php echo e(t('home')); ?></a>
                    <a href="<?php echo APP_URL; ?>/about">About</a>
                    <a href="<?php echo APP_URL; ?>/faq">FAQ</a>
                    <a href="<?php echo APP_URL; ?>/contact">Contact</a>
                    <a href="<?php echo APP_URL; ?>/privacy">Privacy</a>
                    <a href="<?php echo APP_URL; ?>/terms">Terms</a>
                    <a href="<?php echo APP_URL; ?>/report-error">Report Error</a>
                </nav>
                <?php
                $footerViewStats = ['today' => 0, 'total' => 0];
                try {
                    $row = $pdo->query("
                        SELECT COUNT(*) AS total_views,
                               SUM(CASE WHEN DATE(viewed_at) = CURDATE() THEN 1 ELSE 0 END) AS today_views
                        FROM page_views
                    ")->fetch();
                    $footerViewStats = [
                        'today' => (int)($row['today_views'] ?? 0),
                        'total' => (int)($row['total_views'] ?? 0),
                    ];
                } catch (Exception $e) {}
                ?>
                <div class="footer-stats-inline">
                    <span><?php echo getCountryCount(); ?> countries</span>
                    <span class="fsep">·</span>
                    <span><?php echo number_format($footerViewStats['today']); ?> views today</span>
                    <span class="fsep">·</span>
                    <span><?php echo number_format($footerViewStats['total']); ?> all time</span>
                </div>
            </div>

            <!-- Bottom row: disclaimer · copyright -->
            <div class="footer-bottom">
                <span>⚠️ <?php echo e(t('footer_disclaimer')); ?></span>
                <span class="footer-copy"><?php echo e(t('footer_copyright')); ?> &nbsp;·&nbsp; An <a href="https://www.shmarlo.com" target="_blank" rel="noopener noreferrer">SDA Project</a></span>
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
