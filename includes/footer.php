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
                        <li><a href="<?php echo APP_URL; ?>/index.php"><?php echo e(t('home')); ?></a></li>
                        <li><a href="<?php echo APP_URL; ?>/contact.php"><?php echo e(t('contact_us')); ?></a></li>
                        <li><a href="<?php echo APP_URL; ?>/privacy.php"><?php echo e(t('privacy_policy')); ?></a></li>
                        <li><a href="<?php echo APP_URL; ?>/report-error.php">Report an Error</a></li>
                    </ul>
                </div>
                
                <div class="footer-section footer-stats">
                    <h4><?php echo e(t('total_countries')); ?></h4>
                    <p class="stat-number"><?php echo getCountryCount(); ?></p>
                    <p class="stat-label"><?php echo e(t('last_updated')); ?>: <?php echo formatDate(date('Y-m-d')); ?></p>
                </div>
            </div>
            
            <div class="footer-disclaimer">
                <p>⚠️ <?php echo e(t('footer_disclaimer')); ?></p>
            </div>
            
            <div class="footer-bottom">
                <p><?php echo e(t('footer_copyright')); ?></p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script src="/assets/js/main.js" defer></script>
    <?php if (isset($additionalJS)): ?>
        <script src="/assets/js/<?php echo $additionalJS; ?>"></script>
    <?php endif; ?>
</body>
</html>
