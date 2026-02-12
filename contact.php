<?php
/**
 * Contact Form Page
 * Allows users to send messages with spam protection
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contact Us - Visa Information Support | Arrival Cards';
$pageDescription = 'Contact Arrival Cards for visa information support, report errors, or provide feedback. We help travelers with visa requirements for 196 countries worldwide.';
$pageKeywords = 'contact visa support, visa help, travel visa questions, visa inquiry, report visa error, visa information support, customer service';
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<section class="countries-section">
    <div class="container">
        <div style="max-width: 600px; margin: 0 auto;">
            <h1 class="text-center"><?php echo e(t('contact_form_title')); ?></h1>
            <p class="text-center" style="color: var(--text-secondary); margin-bottom: 2rem;">
                Have questions or feedback? We'd love to hear from you!
            </p>
            
            <form id="contact-form" action="<?php echo APP_URL; ?>/process_contact.php" method="POST">
                <!-- CSRF Protection -->
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <!-- Honeypot for spam protection (hidden field) -->
                <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">
                
                <div class="form-group">
                    <label for="name" class="form-label">
                        <?php echo e(t('contact_name')); ?> <span style="color: var(--danger-color);">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-input"
                        required
                        minlength="2"
                        maxlength="100"
                        aria-required="true"
                    >
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">
                        <?php echo e(t('contact_email')); ?> <span style="color: var(--danger-color);">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input"
                        required
                        maxlength="150"
                        aria-required="true"
                    >
                </div>
                
                <div class="form-group">
                    <label for="message" class="form-label">
                        <?php echo e(t('contact_message')); ?> <span style="color: var(--danger-color);">*</span>
                    </label>
                    <textarea 
                        id="message" 
                        name="message" 
                        class="form-textarea"
                        required
                        minlength="10"
                        maxlength="2000"
                        aria-required="true"
                    ></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <?php echo e(t('contact_submit')); ?>
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
