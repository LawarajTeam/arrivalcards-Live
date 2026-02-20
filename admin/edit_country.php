<?php
/**
 * Admin - Edit Country
 * Full edit with all 5 language translations
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$pageTitle = 'Edit Country';
$errors = [];

$countryId = intval($_GET['id'] ?? 0);
if ($countryId <= 0) {
    setFlashMessage('Invalid country ID.', 'danger');
    redirect(APP_URL . '/admin/countries.php');
}

// Get available languages
$languages = getLanguages();
$regions = ['Africa', 'Asia', 'Europe', 'North America', 'South America', 'Oceania', 'Central America', 'Caribbean', 'Middle East'];

// Fetch existing country data
$stmt = $pdo->prepare("SELECT * FROM countries WHERE id = ?");
$stmt->execute([$countryId]);
$country = $stmt->fetch();

if (!$country) {
    setFlashMessage('Country not found.', 'danger');
    redirect(APP_URL . '/admin/countries.php');
}

// Fetch all translations
$stmt = $pdo->prepare("SELECT * FROM country_translations WHERE country_id = ?");
$stmt->execute([$countryId]);
$translationsRaw = $stmt->fetchAll();
$translations = [];
foreach ($translationsRaw as $row) {
    $translations[$row['lang_code']] = $row;
}

// Fetch country details
$stmt = $pdo->prepare("SELECT * FROM country_details WHERE country_id = ?");
$stmt->execute([$countryId]);
$detailsRaw = $stmt->fetchAll();
$details = [];
foreach ($detailsRaw as $row) {
    $details[$row['lang_code']] = $row;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid CSRF token. Please try again.';
    } else {
        $country_code = strtoupper(trim($_POST['country_code'] ?? ''));
        $flag_emoji = trim($_POST['flag_emoji'] ?? '');
        $region = trim($_POST['region'] ?? '');
        $visa_type = trim($_POST['visa_type'] ?? '');
        $official_url = trim($_POST['official_url'] ?? '');
        $display_order = intval($_POST['display_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $is_popular = isset($_POST['is_popular']) ? 1 : 0;

        $capital = trim($_POST['capital'] ?? '');
        $population = trim($_POST['population'] ?? '');
        $currency_name = trim($_POST['currency_name'] ?? '');
        $currency_code = trim($_POST['currency_code'] ?? '');
        $currency_symbol = trim($_POST['currency_symbol'] ?? '');
        $plug_type = trim($_POST['plug_type'] ?? '');
        $leader_name = trim($_POST['leader_name'] ?? '');
        $leader_title = trim($_POST['leader_title'] ?? '');
        $time_zone = trim($_POST['time_zone'] ?? '');
        $calling_code = trim($_POST['calling_code'] ?? '');
        $languages_field = trim($_POST['languages'] ?? '');

        if (empty($country_code) || strlen($country_code) !== 3) {
            $errors[] = 'Country code must be exactly 3 characters.';
        }
        if (empty($flag_emoji)) $errors[] = 'Flag emoji is required.';
        if (empty($region)) $errors[] = 'Region is required.';
        if (!in_array($visa_type, ['visa_free', 'visa_on_arrival', 'visa_required', 'evisa'])) {
            $errors[] = 'Invalid visa type.';
        }

        $en_country_name = trim($_POST['translations']['en']['country_name'] ?? '');
        if (empty($en_country_name)) $errors[] = 'English country name is required.';

        // Check duplicate country code (excluding current)
        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT id FROM countries WHERE country_code = ? AND id != ?");
            $stmt->execute([$country_code, $countryId]);
            if ($stmt->fetch()) {
                $errors[] = "Country code '$country_code' is already used by another country.";
            }
        }

        if (empty($errors)) {
            try {
                $pdo->beginTransaction();

                $oldName = $translations['en']['country_name'] ?? 'Unknown';

                $stmt = $pdo->prepare("
                    UPDATE countries SET 
                        country_code = ?, flag_emoji = ?, region = ?, official_url = ?, visa_type = ?,
                        last_updated = CURDATE(), display_order = ?, is_active = ?, is_popular = ?,
                        capital = ?, population = ?, currency_name = ?, currency_code = ?, currency_symbol = ?,
                        plug_type = ?, leader_name = ?, leader_title = ?, time_zone = ?, calling_code = ?, languages = ?
                    WHERE id = ?
                ");
                $stmt->execute([
                    $country_code, $flag_emoji, $region, $official_url, $visa_type,
                    $display_order, $is_active, $is_popular,
                    $capital, $population, $currency_name, $currency_code, $currency_symbol,
                    $plug_type, $leader_name, $leader_title, $time_zone, $calling_code, $languages_field,
                    $countryId
                ]);

                // Upsert translations
                foreach ($languages as $lang) {
                    $code = $lang['code'];
                    $t = $_POST['translations'][$code] ?? [];
                    $countryName = trim($t['country_name'] ?? '');
                    $entrySummary = trim($t['entry_summary'] ?? '');
                    $visaRequirements = trim($t['visa_requirements'] ?? '');
                    $visaDuration = trim($t['visa_duration'] ?? '');
                    $passportValidity = trim($t['passport_validity'] ?? '');
                    $visaFee = trim($t['visa_fee'] ?? '');
                    $processingTime = trim($t['processing_time'] ?? '');
                    $officialVisaUrl = trim($t['official_visa_url'] ?? '');
                    $arrivalCardRequired = trim($t['arrival_card_required'] ?? '');
                    $additionalDocs = trim($t['additional_docs'] ?? '');

                    if ($code !== 'en' && empty($countryName)) {
                        // Delete translation if name is cleared
                        $stmt = $pdo->prepare("DELETE FROM country_translations WHERE country_id = ? AND lang_code = ?");
                        $stmt->execute([$countryId, $code]);
                        continue;
                    }

                    if (isset($translations[$code])) {
                        $stmt = $pdo->prepare("
                            UPDATE country_translations SET 
                                country_name = ?, entry_summary = ?, visa_requirements = ?,
                                visa_duration = ?, passport_validity = ?, visa_fee = ?, processing_time = ?,
                                official_visa_url = ?, arrival_card_required = ?, additional_docs = ?, last_verified = CURDATE()
                            WHERE country_id = ? AND lang_code = ?
                        ");
                        $stmt->execute([
                            $countryName, $entrySummary, $visaRequirements,
                            $visaDuration, $passportValidity, $visaFee, $processingTime,
                            $officialVisaUrl, $arrivalCardRequired, $additionalDocs,
                            $countryId, $code
                        ]);
                    } else {
                        $stmt = $pdo->prepare("
                            INSERT INTO country_translations 
                                (country_id, lang_code, country_name, entry_summary, visa_requirements,
                                 visa_duration, passport_validity, visa_fee, processing_time,
                                 official_visa_url, arrival_card_required, additional_docs, last_verified)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE())
                        ");
                        $stmt->execute([
                            $countryId, $code, $countryName, $entrySummary, $visaRequirements,
                            $visaDuration, $passportValidity, $visaFee, $processingTime,
                            $officialVisaUrl, $arrivalCardRequired, $additionalDocs
                        ]);
                    }
                }

                // Upsert country details
                foreach ($languages as $lang) {
                    $code = $lang['code'];
                    $d = $_POST['details'][$code] ?? [];
                    $description = trim($d['description'] ?? '');
                    $knownFor = trim($d['known_for'] ?? '');
                    $travelTips = trim($d['travel_tips'] ?? '');

                    if (empty($description) && empty($knownFor) && empty($travelTips)) {
                        $stmt = $pdo->prepare("DELETE FROM country_details WHERE country_id = ? AND lang_code = ?");
                        $stmt->execute([$countryId, $code]);
                        continue;
                    }

                    if (isset($details[$code])) {
                        $stmt = $pdo->prepare("
                            UPDATE country_details SET description = ?, known_for = ?, travel_tips = ?
                            WHERE country_id = ? AND lang_code = ?
                        ");
                        $stmt->execute([$description, $knownFor, $travelTips, $countryId, $code]);
                    } else {
                        $stmt = $pdo->prepare("
                            INSERT INTO country_details (country_id, lang_code, description, known_for, travel_tips)
                            VALUES (?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([$countryId, $code, $description, $knownFor, $travelTips]);
                    }
                }

                logAdminAction('update', 'countries', $countryId, $oldName, "Updated country: $en_country_name ($country_code)");
                $pdo->commit();
                setFlashMessage("Country '$en_country_name' updated successfully!", 'success');
                redirect(APP_URL . '/admin/countries.php');
            } catch (Exception $e) {
                $pdo->rollBack();
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

$csrfToken = generateCSRFToken();

// Helper to get field value (POST on error, DB on load)
function fval($field, $dbValue = '') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return $_POST[$field] ?? $dbValue;
    }
    return $dbValue;
}
function tval($langCode, $field, $translations) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return $_POST['translations'][$langCode][$field] ?? '';
    }
    return $translations[$langCode][$field] ?? '';
}
function dval($langCode, $field, $details) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return $_POST['details'][$langCode][$field] ?? '';
    }
    return $details[$langCode][$field] ?? '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> - Arrival Cards Admin</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/admin.css">
    <style>
        .tab-nav { display: flex; gap: 0; border-bottom: 2px solid var(--border-color, #e2e8f0); margin-bottom: 1.5rem; flex-wrap: wrap; }
        .tab-btn { padding: 0.75rem 1.25rem; border: none; background: none; cursor: pointer; font-size: 0.95rem; font-weight: 500; color: var(--text-secondary, #64748b); border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all 0.2s; }
        .tab-btn:hover { color: var(--primary-color, #3b82f6); }
        .tab-btn.active { color: var(--primary-color, #3b82f6); border-bottom-color: var(--primary-color, #3b82f6); }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
        @media (max-width: 768px) { .form-grid, .form-grid-3 { grid-template-columns: 1fr; } }
        .lang-flag { font-size: 1.2rem; margin-right: 0.25rem; }
        .section-title { font-size: 1.1rem; font-weight: 600; margin: 1.5rem 0 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-color, #e2e8f0); }
        .checkbox-group { display: flex; align-items: center; gap: 0.5rem; margin: 0.5rem 0; }
        .checkbox-group input[type="checkbox"] { width: 18px; height: 18px; }
        .country-header { display: flex; align-items: center; gap: 0.75rem; }
        .country-header .flag { font-size: 2rem; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <div class="section-header">
            <h1>
                <span class="country-header">
                    <span class="flag"><?php echo $country['flag_emoji']; ?></span>
                    Edit: <?php echo e($translations['en']['country_name'] ?? $country['country_code']); ?>
                </span>
            </h1>
            <a href="<?php echo APP_URL; ?>/admin/countries.php" class="btn btn-secondary">← Back to Countries</a>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <strong>Please fix the following errors:</strong>
                <ul style="margin: 0.5rem 0 0; padding-left: 1.25rem;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if ($flash = getFlashMessage()): ?>
            <div class="alert alert-<?php echo e($flash['type']); ?>">
                <?php echo e($flash['message']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
            
            <div class="admin-section">
                <h3 class="section-title">Core Country Information</h3>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label">Country Code (ISO alpha-3) *</label>
                        <input type="text" name="country_code" class="form-input" maxlength="3" 
                               value="<?php echo e(fval('country_code', $country['country_code'])); ?>" required style="text-transform: uppercase;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Flag Emoji *</label>
                        <input type="text" name="flag_emoji" class="form-input" 
                               value="<?php echo e(fval('flag_emoji', $country['flag_emoji'])); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Display Order</label>
                        <input type="number" name="display_order" class="form-input" 
                               value="<?php echo e(fval('display_order', $country['display_order'])); ?>">
                    </div>
                </div>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label">Region *</label>
                        <select name="region" class="form-input" required>
                            <option value="">-- Select Region --</option>
                            <?php $currentRegion = fval('region', $country['region']); ?>
                            <?php foreach ($regions as $r): ?>
                                <option value="<?php echo e($r); ?>" <?php echo $currentRegion === $r ? 'selected' : ''; ?>><?php echo e($r); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Visa Type *</label>
                        <?php $currentVisa = fval('visa_type', $country['visa_type']); ?>
                        <select name="visa_type" class="form-input" required>
                            <option value="visa_free" <?php echo $currentVisa === 'visa_free' ? 'selected' : ''; ?>>Visa Free</option>
                            <option value="visa_on_arrival" <?php echo $currentVisa === 'visa_on_arrival' ? 'selected' : ''; ?>>Visa on Arrival</option>
                            <option value="evisa" <?php echo $currentVisa === 'evisa' ? 'selected' : ''; ?>>E-Visa</option>
                            <option value="visa_required" <?php echo $currentVisa === 'visa_required' ? 'selected' : ''; ?>>Visa Required</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Official URL</label>
                        <input type="url" name="official_url" class="form-input" 
                               value="<?php echo e(fval('official_url', $country['official_url'])); ?>" placeholder="https://...">
                    </div>
                </div>
                <div class="form-grid">
                    <?php $activeVal = ($_SERVER['REQUEST_METHOD'] === 'POST') ? isset($_POST['is_active']) : $country['is_active']; ?>
                    <?php $popularVal = ($_SERVER['REQUEST_METHOD'] === 'POST') ? isset($_POST['is_popular']) : $country['is_popular']; ?>
                    <div class="checkbox-group">
                        <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo $activeVal ? 'checked' : ''; ?>>
                        <label for="is_active">Active (visible on site)</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" name="is_popular" id="is_popular" value="1" <?php echo $popularVal ? 'checked' : ''; ?>>
                        <label for="is_popular">Popular destination</label>
                    </div>
                </div>
            </div>

            <div class="admin-section">
                <h3 class="section-title">Country Details</h3>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label">Capital</label>
                        <input type="text" name="capital" class="form-input" value="<?php echo e(fval('capital', $country['capital'] ?? '')); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Population</label>
                        <input type="text" name="population" class="form-input" value="<?php echo e(fval('population', $country['population'] ?? '')); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Currency Name</label>
                        <input type="text" name="currency_name" class="form-input" value="<?php echo e(fval('currency_name', $country['currency_name'] ?? '')); ?>">
                    </div>
                </div>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label">Currency Code</label>
                        <input type="text" name="currency_code" class="form-input" maxlength="10" value="<?php echo e(fval('currency_code', $country['currency_code'] ?? '')); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Currency Symbol</label>
                        <input type="text" name="currency_symbol" class="form-input" maxlength="10" value="<?php echo e(fval('currency_symbol', $country['currency_symbol'] ?? '')); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Plug Type</label>
                        <input type="text" name="plug_type" class="form-input" value="<?php echo e(fval('plug_type', $country['plug_type'] ?? '')); ?>">
                    </div>
                </div>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label">Leader Name</label>
                        <input type="text" name="leader_name" class="form-input" value="<?php echo e(fval('leader_name', $country['leader_name'] ?? '')); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Leader Title</label>
                        <input type="text" name="leader_title" class="form-input" value="<?php echo e(fval('leader_title', $country['leader_title'] ?? '')); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time Zone</label>
                        <input type="text" name="time_zone" class="form-input" value="<?php echo e(fval('time_zone', $country['time_zone'] ?? '')); ?>">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Calling Code</label>
                        <input type="text" name="calling_code" class="form-input" value="<?php echo e(fval('calling_code', $country['calling_code'] ?? '')); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Languages</label>
                        <input type="text" name="languages" class="form-input" value="<?php echo e(fval('languages', $country['languages'] ?? '')); ?>">
                    </div>
                </div>
            </div>
            
            <div class="admin-section">
                <h3 class="section-title">Translations &amp; Visa Information</h3>
                <div class="tab-nav">
                    <?php foreach ($languages as $i => $lang): ?>
                        <button type="button" class="tab-btn <?php echo $i === 0 ? 'active' : ''; ?>" data-tab="lang-<?php echo e($lang['code']); ?>">
                            <span class="lang-flag"><?php echo $lang['flag_emoji']; ?></span>
                            <?php echo e($lang['name']); ?><?php if ($lang['code'] === 'en') echo ' *'; ?>
                            <?php if (isset($translations[$lang['code']])): ?>
                                <span style="color: var(--success-color, #22c55e);" title="Translation exists">●</span>
                            <?php endif; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                
                <?php foreach ($languages as $i => $lang): ?>
                    <?php $code = $lang['code']; ?>
                    <div class="tab-content <?php echo $i === 0 ? 'active' : ''; ?>" id="lang-<?php echo e($code); ?>">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Country Name <?php if ($code === 'en') echo '*'; ?></label>
                                <input type="text" name="translations[<?php echo e($code); ?>][country_name]" class="form-input" 
                                       value="<?php echo e(tval($code, 'country_name', $translations)); ?>"
                                       <?php if ($code === 'en') echo 'required'; ?>>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Official Visa URL</label>
                                <input type="url" name="translations[<?php echo e($code); ?>][official_visa_url]" class="form-input" 
                                       value="<?php echo e(tval($code, 'official_visa_url', $translations)); ?>" placeholder="https://...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Entry Summary</label>
                            <textarea name="translations[<?php echo e($code); ?>][entry_summary]" class="form-textarea" rows="3" 
                                      placeholder="Brief overview of entry requirements..."><?php echo e(tval($code, 'entry_summary', $translations)); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Visa Requirements (detailed)</label>
                            <textarea name="translations[<?php echo e($code); ?>][visa_requirements]" class="form-textarea" rows="5" 
                                      placeholder="Detailed visa information..."><?php echo e(tval($code, 'visa_requirements', $translations)); ?></textarea>
                        </div>
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label class="form-label">Visa Duration</label>
                                <input type="text" name="translations[<?php echo e($code); ?>][visa_duration]" class="form-input" 
                                       value="<?php echo e(tval($code, 'visa_duration', $translations)); ?>" placeholder="e.g. 90 days">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Visa Fee</label>
                                <input type="text" name="translations[<?php echo e($code); ?>][visa_fee]" class="form-input" 
                                       value="<?php echo e(tval($code, 'visa_fee', $translations)); ?>" placeholder="e.g. Free, $50 USD">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Processing Time</label>
                                <input type="text" name="translations[<?php echo e($code); ?>][processing_time]" class="form-input" 
                                       value="<?php echo e(tval($code, 'processing_time', $translations)); ?>" placeholder="e.g. 3-5 business days">
                            </div>
                        </div>
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label class="form-label">Passport Validity</label>
                                <input type="text" name="translations[<?php echo e($code); ?>][passport_validity]" class="form-input" 
                                       value="<?php echo e(tval($code, 'passport_validity', $translations)); ?>" placeholder="e.g. 6 months beyond stay">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Arrival Card Required</label>
                                <?php $arrCard = tval($code, 'arrival_card_required', $translations); ?>
                                <select name="translations[<?php echo e($code); ?>][arrival_card_required]" class="form-input">
                                    <option value="">-- Select --</option>
                                    <option value="Yes" <?php echo $arrCard === 'Yes' ? 'selected' : ''; ?>>Yes</option>
                                    <option value="No" <?php echo $arrCard === 'No' ? 'selected' : ''; ?>>No</option>
                                    <option value="Online only" <?php echo $arrCard === 'Online only' ? 'selected' : ''; ?>>Online only</option>
                                </select>
                            </div>
                            <div></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Additional Documents</label>
                            <textarea name="translations[<?php echo e($code); ?>][additional_docs]" class="form-textarea" rows="3" 
                                      placeholder="List required documents, one per line..."><?php echo e(tval($code, 'additional_docs', $translations)); ?></textarea>
                        </div>
                        <h4 style="margin-top: 1.5rem; font-size: 0.95rem; color: var(--text-secondary, #64748b);">Country Details (<?php echo e($lang['name']); ?>)</h4>
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea name="details[<?php echo e($code); ?>][description]" class="form-textarea" rows="3" 
                                      placeholder="Country description..."><?php echo e(dval($code, 'description', $details)); ?></textarea>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Known For</label>
                                <textarea name="details[<?php echo e($code); ?>][known_for]" class="form-textarea" rows="2" 
                                          placeholder="What is this country known for..."><?php echo e(dval($code, 'known_for', $details)); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Travel Tips</label>
                                <textarea name="details[<?php echo e($code); ?>][travel_tips]" class="form-textarea" rows="2" 
                                          placeholder="Travel advice and tips..."><?php echo e(dval($code, 'travel_tips', $details)); ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="form-actions" style="padding: 1.5rem 0; display: flex; gap: 1rem; justify-content: space-between; align-items: center;">
                <div style="font-size: 0.85rem; color: var(--text-secondary, #64748b);">
                    Last updated: <?php echo formatDate($country['last_updated']); ?>
                    <?php if (!empty($translations['en']['last_verified'])): ?>
                        | Visa verified: <?php echo formatDate($translations['en']['last_verified']); ?>
                    <?php endif; ?>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <a href="<?php echo APP_URL; ?>/admin/countries.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
    
    <script src="<?php echo APP_URL; ?>/assets/js/admin.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/js/main.js"></script>
    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById(this.dataset.tab).classList.add('active');
            });
        });
    </script>
</body>
</html>
