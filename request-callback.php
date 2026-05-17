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

// Pre-fill destination from card link (sanitised)
$prefillDestination = '';
if (!empty($_GET['destination'])) {
    $prefillDestination = htmlspecialchars(strip_tags(substr($_GET['destination'], 0, 200)));
}

// Generate math CAPTCHA and record form load time
$captchaA = random_int(2, 9);
$captchaB = random_int(1, 9);
$_SESSION['callback_captcha']     = $captchaA + $captchaB;
$_SESSION['callback_form_loaded'] = time();

// Dial codes list
$dialCodes = [
    ['+1','🇺🇸 +1 (US / Canada)'], ['+7','🇷🇺 +7 (Russia / KZ)'],
    ['+20','🇪🇬 +20 (Egypt)'], ['+27','🇿🇦 +27 (South Africa)'],
    ['+30','🇬🇷 +30 (Greece)'], ['+31','🇳🇱 +31 (Netherlands)'],
    ['+32','🇧🇪 +32 (Belgium)'], ['+33','🇫🇷 +33 (France)'],
    ['+34','🇪🇸 +34 (Spain)'], ['+36','🇭🇺 +36 (Hungary)'],
    ['+39','🇮🇹 +39 (Italy)'], ['+40','🇷🇴 +40 (Romania)'],
    ['+41','🇨🇭 +41 (Switzerland)'], ['+43','🇦🇹 +43 (Austria)'],
    ['+44','🇬🇧 +44 (UK)'], ['+45','🇩🇰 +45 (Denmark)'],
    ['+46','🇸🇪 +46 (Sweden)'], ['+47','🇳🇴 +47 (Norway)'],
    ['+48','🇵🇱 +48 (Poland)'], ['+49','🇩🇪 +49 (Germany)'],
    ['+51','🇵🇪 +51 (Peru)'], ['+52','🇲🇽 +52 (Mexico)'],
    ['+54','🇦🇷 +54 (Argentina)'], ['+55','🇧🇷 +55 (Brazil)'],
    ['+56','🇨🇱 +56 (Chile)'], ['+57','🇨🇴 +57 (Colombia)'],
    ['+60','🇲🇾 +60 (Malaysia)'], ['+61','🇦🇺 +61 (Australia)'],
    ['+62','🇮🇩 +62 (Indonesia)'], ['+63','🇵🇭 +63 (Philippines)'],
    ['+64','🇳🇿 +64 (New Zealand)'], ['+65','🇸🇬 +65 (Singapore)'],
    ['+66','🇹🇭 +66 (Thailand)'], ['+81','🇯🇵 +81 (Japan)'],
    ['+82','🇰🇷 +82 (South Korea)'], ['+84','🇻🇳 +84 (Vietnam)'],
    ['+86','🇨🇳 +86 (China)'], ['+90','🇹🇷 +90 (Turkey)'],
    ['+91','🇮🇳 +91 (India)'], ['+92','🇵🇰 +92 (Pakistan)'],
    ['+94','🇱🇰 +94 (Sri Lanka)'], ['+95','🇲🇲 +95 (Myanmar)'],
    ['+98','🇮🇷 +98 (Iran)'], ['+212','🇲🇦 +212 (Morocco)'],
    ['+213','🇩🇿 +213 (Algeria)'], ['+216','🇹🇳 +216 (Tunisia)'],
    ['+234','🇳🇬 +234 (Nigeria)'], ['+254','🇰🇪 +254 (Kenya)'],
    ['+351','🇵🇹 +351 (Portugal)'], ['+353','🇮🇪 +353 (Ireland)'],
    ['+358','🇫🇮 +358 (Finland)'], ['+380','🇺🇦 +380 (Ukraine)'],
    ['+420','🇨🇿 +420 (Czech Republic)'], ['+421','🇸🇰 +421 (Slovakia)'],
    ['+852','🇭🇰 +852 (Hong Kong)'], ['+880','🇧🇩 +880 (Bangladesh)'],
    ['+960','🇲🇻 +960 (Maldives)'], ['+961','🇱🇧 +961 (Lebanon)'],
    ['+966','🇸🇦 +966 (Saudi Arabia)'], ['+971','🇦🇪 +971 (UAE)'],
    ['+972','🇮🇱 +972 (Israel)'], ['+974','🇶🇦 +974 (Qatar)'],
    ['+977','🇳🇵 +977 (Nepal)'], ['+998','🇺🇿 +998 (Uzbekistan)'],
];

// World country list (alphabetical)
$worldCountries = [
    'Afghanistan','Albania','Algeria','Andorra','Angola','Antigua and Barbuda',
    'Argentina','Armenia','Australia','Austria','Azerbaijan',
    'Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize',
    'Benin','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil',
    'Brunei','Bulgaria','Burkina Faso','Burundi',
    'Cabo Verde','Cambodia','Cameroon','Canada','Central African Republic','Chad',
    'Chile','China','Colombia','Comoros','Congo (Republic)','Congo (DRC)',
    'Costa Rica','Croatia','Cuba','Cyprus','Czech Republic',
    'Denmark','Djibouti','Dominica','Dominican Republic',
    'Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia',
    'Eswatini','Ethiopia',
    'Fiji','Finland','France',
    'Gabon','Gambia','Georgia','Germany','Ghana','Greece','Grenada',
    'Guatemala','Guinea','Guinea-Bissau','Guyana',
    'Haiti','Honduras','Hungary',
    'Iceland','India','Indonesia','Iran','Iraq','Ireland','Israel','Italy',
    'Jamaica','Japan','Jordan',
    'Kazakhstan','Kenya','Kiribati','Kosovo','Kuwait','Kyrgyzstan',
    'Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein',
    'Lithuania','Luxembourg',
    'Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands',
    'Mauritania','Mauritius','Mexico','Micronesia','Moldova','Monaco','Mongolia',
    'Montenegro','Morocco','Mozambique','Myanmar',
    'Namibia','Nauru','Nepal','Netherlands','New Zealand','Nicaragua','Niger',
    'Nigeria','North Korea','North Macedonia','Norway',
    'Oman',
    'Pakistan','Palau','Palestine','Panama','Papua New Guinea','Paraguay','Peru',
    'Philippines','Poland','Portugal',
    'Qatar',
    'Romania','Russia','Rwanda',
    'Saint Kitts and Nevis','Saint Lucia','Saint Vincent and the Grenadines',
    'Samoa','San Marino','São Tomé and Príncipe','Saudi Arabia','Senegal','Serbia',
    'Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands',
    'Somalia','South Africa','South Korea','South Sudan','Spain','Sri Lanka',
    'Sudan','Suriname','Sweden','Switzerland','Syria',
    'Taiwan','Tajikistan','Tanzania','Thailand','Timor-Leste','Togo','Tonga',
    'Trinidad and Tobago','Tunisia','Turkey','Turkmenistan','Tuvalu',
    'Uganda','Ukraine','United Arab Emirates','United Kingdom','United States',
    'Uruguay','Uzbekistan',
    'Vanuatu','Vatican City','Venezuela','Vietnam',
    'Yemen',
    'Zambia','Zimbabwe',
];
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

                <!-- Honeypot fields (hidden from humans, bots fill them) -->
                <input type="text" name="website"          class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">
                <input type="text" name="email_confirm"    class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">

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
                        <label for="phone_number" class="form-label">
                            Phone Number <span class="required-star">*</span>
                        </label>
                        <div class="phone-input-group">
                            <select name="phone_code" id="phone_code" class="form-input phone-code-select" aria-label="Country dial code">
                                <?php foreach ($dialCodes as [$code, $label]): ?>
                                <option value="<?php echo e($code); ?>"<?php echo $code === '+61' ? ' selected' : ''; ?>>
                                    <?php echo e($label); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <input
                                type="tel"
                                id="phone_number"
                                name="phone_number"
                                class="form-input phone-number-input"
                                required
                                maxlength="20"
                                placeholder="555 000 0000"
                                aria-required="true"
                            >
                        </div>
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
                        <select
                            id="passport_country"
                            name="passport_country"
                            class="form-input form-select"
                            required
                            aria-required="true"
                        >
                            <option value="" disabled selected>Select your country</option>
                            <?php foreach ($worldCountries as $c): ?>
                            <option value="<?php echo e($c); ?>"><?php echo e($c); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="destination_countries" class="form-label">
                            Destination Countries <span class="required-star">*</span>
                        </label>
                        <select
                            id="destination_countries"
                            name="destination_countries[]"
                            class="form-input form-select country-multiselect"
                            multiple
                            required
                            size="5"
                            aria-required="true"
                        >
                            <?php foreach ($worldCountries as $c): ?>
                            <option value="<?php echo e($c); ?>"<?php echo ($prefillDestination && strcasecmp($c, $prefillDestination) === 0) ? ' selected' : ''; ?>>
                                <?php echo e($c); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="form-hint">Hold Ctrl (Windows) or ⌘ (Mac) to select multiple countries</p>
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

                <!-- Math CAPTCHA -->
                <div class="form-group captcha-group">
                    <label for="captcha" class="form-label">
                        Security check: What is <strong><?php echo $captchaA; ?> + <?php echo $captchaB; ?></strong>? <span class="required-star">*</span>
                    </label>
                    <input
                        type="number"
                        id="captcha"
                        name="captcha"
                        class="form-input captcha-input"
                        required
                        min="0"
                        max="18"
                        placeholder="Your answer"
                        autocomplete="off"
                        aria-required="true"
                    >
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
