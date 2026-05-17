<?php
/**
 * Request a Callback Page
 * Allows users to request a call from a visa agent
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Talk to a Visa Agent – Get Expert Help | Arrival Cards';
$pageDescription = 'Request a free callback from one of our visa experts. Tell us about your trip and we\'ll guide you through the visa process step by step.';
$pageKeywords = 'visa agent, visa help, visa consultation, talk to visa expert, visa callback, visa advice';
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<section class="countries-section">
    <div class="container">
        <div class="callback-form-wrapper">

            <div class="callback-hero">
                <div class="callback-hero-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.37 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 5.53 5.53l1.62-1.85a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                </div>
                <h1>Talk to a Visa Agent</h1>
                <p>Fill in your trip details below and one of our visa specialists will call you back to help navigate your visa requirements.</p>
            </div>

            <?php
            $flash = getFlashMessage();
            if ($flash):
            ?>
            <div class="alert alert-<?php echo e($flash['type']); ?>" role="alert">
                <?php echo e($flash['message']); ?>
            </div>
            <?php endif; ?>

            <form id="callback-form" action="<?php echo APP_URL; ?>/process_callback.php" method="POST" novalidate>
                <!-- CSRF Protection -->
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                <!-- Honeypot for spam protection -->
                <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Full Name <span class="required-star">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-input"
                            required
                            minlength="2"
                            maxlength="100"
                            placeholder="Your full name"
                            aria-required="true"
                        >
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">
                            Phone Number <span class="required-star">*</span>
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="form-input"
                            required
                            maxlength="30"
                            placeholder="+1 555 000 0000"
                            aria-required="true"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        Email Address <span class="required-star">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        required
                        maxlength="150"
                        placeholder="you@example.com"
                        aria-required="true"
                    >
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="passport_country" class="form-label">
                            Your Passport / Home Country <span class="required-star">*</span>
                        </label>
                        <input
                            type="text"
                            id="passport_country"
                            name="passport_country"
                            class="form-input"
                            required
                            maxlength="100"
                            placeholder="e.g. Australia"
                            aria-required="true"
                        >
                    </div>

                    <div class="form-group">
                        <label for="destination_countries" class="form-label">
                            Destination Country / Countries <span class="required-star">*</span>
                        </label>
                        <input
                            type="text"
                            id="destination_countries"
                            name="destination_countries"
                            class="form-input"
                            required
                            maxlength="200"
                            placeholder="e.g. Japan, Thailand, Vietnam"
                            aria-required="true"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="trip_details" class="form-label">
                        Trip Details <span class="required-star">*</span>
                    </label>
                    <textarea
                        id="trip_details"
                        name="trip_details"
                        class="form-textarea"
                        required
                        minlength="10"
                        maxlength="1000"
                        rows="4"
                        placeholder="Briefly describe your trip – purpose of travel, how long you plan to stay, number of travellers, etc."
                        aria-required="true"
                    ></textarea>
                </div>

                <div class="form-group">
                    <label for="timeline" class="form-label">
                        When are you planning to travel? <span class="required-star">*</span>
                    </label>
                    <select id="timeline" name="timeline" class="form-input form-select" required aria-required="true">
                        <option value="" disabled selected>Select your travel timeline</option>
                        <option value="within_1_week">Within 1 week – urgent!</option>
                        <option value="2_4_weeks">2 – 4 weeks</option>
                        <option value="1_3_months">1 – 3 months</option>
                        <option value="3_6_months">3 – 6 months</option>
                        <option value="6_plus_months">6+ months away</option>
                        <option value="not_sure">Not sure yet</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="comments" class="form-label">
                        Additional Comments
                    </label>
                    <textarea
                        id="comments"
                        name="comments"
                        class="form-textarea"
                        maxlength="1000"
                        rows="3"
                        placeholder="Anything else we should know? Special circumstances, previous visa rejections, multiple nationalities, etc."
                    ></textarea>
                </div>

                <button type="submit" class="btn btn-callback-submit">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.37 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 5.53 5.53l1.62-1.85a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    Request My Callback
                </button>
            </form>

        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
