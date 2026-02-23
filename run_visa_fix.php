<?php
/**
 * ============================================================
 * VISA DATA FIX — RUN ON LIVE SERVER
 * ============================================================
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to your server root (public_html/) via FTP/cPanel
 * 2. Visit: https://www.arrivalcards.com/run_visa_fix.php
 * 3. Enter the security code when prompted
 * 4. Review the preview, then click "Apply Fixes"
 * 5. DELETE this file from your server immediately after running
 *
 * This script fixes:
 *   - visa_type for all countries (Australian passport perspective)
 *   - Visa text data with 94 country-specific overrides
 *   - Fake template URLs replaced with real government URLs
 * ============================================================
 */

// ── SECURITY ──────────────────────────────────────────────────
// Change this code to something only you know before uploading
define('SECURITY_CODE', 'FixVisaData2026!');

session_start();

// Rate-limit: max 5 attempts per 10 minutes
if (!isset($_SESSION['fix_attempts'])) $_SESSION['fix_attempts'] = 0;
if (!isset($_SESSION['fix_first_attempt'])) $_SESSION['fix_first_attempt'] = time();
if (time() - $_SESSION['fix_first_attempt'] > 600) {
    $_SESSION['fix_attempts'] = 0;
    $_SESSION['fix_first_attempt'] = time();
}

$authenticated = isset($_SESSION['fix_authenticated']) && $_SESSION['fix_authenticated'] === true;
$error = '';
$step = 'login'; // login | preview | apply | done

// Handle login
if (isset($_POST['security_code'])) {
    $_SESSION['fix_attempts']++;
    if ($_SESSION['fix_attempts'] > 5) {
        $error = 'Too many attempts. Wait 10 minutes.';
    } elseif ($_POST['security_code'] === SECURITY_CODE) {
        $_SESSION['fix_authenticated'] = true;
        $authenticated = true;
    } else {
        $error = 'Invalid security code.';
    }
}

if ($authenticated && isset($_POST['action'])) {
    $step = $_POST['action']; // 'preview' or 'apply'
} elseif ($authenticated) {
    $step = 'preview';
}

// Connect to database
$pdo = null;
if ($authenticated) {
    require __DIR__ . '/includes/config.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Visa Data Fix — ArrivalCards</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; color: #e2e8f0; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        h1 { color: #38bdf8; margin-bottom: 8px; font-size: 24px; }
        h2 { color: #f59e0b; margin: 24px 0 12px; font-size: 18px; }
        .subtitle { color: #94a3b8; margin-bottom: 24px; }
        .card { background: #1e293b; border-radius: 12px; padding: 24px; margin-bottom: 20px; border: 1px solid #334155; }
        .warning { background: #451a03; border-color: #92400e; }
        .success { background: #052e16; border-color: #166534; }
        .error { background: #450a0a; border-color: #991b1b; color: #fca5a5; }
        label { display: block; color: #94a3b8; margin-bottom: 6px; font-size: 14px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px 14px; border: 1px solid #475569; border-radius: 8px; background: #0f172a; color: #e2e8f0; font-size: 16px; margin-bottom: 16px; }
        button, .btn { padding: 12px 28px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; display: inline-block; text-decoration: none; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-secondary { background: #475569; color: white; }
        table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        th, td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #334155; font-size: 13px; }
        th { color: #94a3b8; font-weight: 600; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .badge-green { background: #166534; color: #86efac; }
        .badge-blue { background: #1e3a5f; color: #93c5fd; }
        .badge-yellow { background: #713f12; color: #fde047; }
        .badge-red { background: #7f1d1d; color: #fca5a5; }
        .log { background: #0f172a; border: 1px solid #334155; border-radius: 8px; padding: 16px; font-family: 'Cascadia Code', 'Fira Code', monospace; font-size: 13px; max-height: 500px; overflow-y: auto; white-space: pre-wrap; line-height: 1.6; }
        .log .ok { color: #4ade80; }
        .log .warn { color: #fbbf24; }
        .log .err { color: #f87171; }
        .log .info { color: #60a5fa; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin: 16px 0; }
        .stat { background: #0f172a; border-radius: 8px; padding: 16px; text-align: center; }
        .stat .number { font-size: 32px; font-weight: 700; color: #38bdf8; }
        .stat .label { font-size: 12px; color: #94a3b8; margin-top: 4px; }
        .actions { display: flex; gap: 12px; margin-top: 20px; }
        .delete-reminder { margin-top: 20px; padding: 16px; background: #451a03; border: 2px solid #f59e0b; border-radius: 8px; text-align: center; }
        .delete-reminder strong { color: #fbbf24; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔧 Visa Data Fix</h1>
    <p class="subtitle">Australian Passport Holder Perspective — ArrivalCards.com</p>

<?php if ($step === 'login'): ?>
    <!-- ════ LOGIN ════ -->
    <div class="card">
        <h2>🔐 Authentication Required</h2>
        <p style="margin-bottom:16px;">Enter the security code to proceed.</p>
        <?php if ($error): ?>
            <div class="card error" style="margin-bottom:16px; padding:12px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <label for="security_code">Security Code</label>
            <input type="password" name="security_code" id="security_code" placeholder="Enter security code" autofocus>
            <button type="submit" class="btn btn-primary">Authenticate</button>
        </form>
    </div>

<?php elseif ($step === 'preview'): ?>
    <!-- ════ PREVIEW ════ -->
    <?php
    // Show current state before changes
    $currentTypes = $pdo->query("SELECT visa_type, COUNT(*) as cnt FROM countries WHERE is_active = 1 GROUP BY visa_type ORDER BY cnt DESC")->fetchAll();
    
    // Count fake URLs
    $fakeUrls = $pdo->query("
        SELECT COUNT(*) as cnt FROM country_translations ct
        JOIN countries c ON ct.country_id = c.id
        WHERE ct.lang_code = 'en' AND c.is_active = 1
        AND (ct.official_visa_url LIKE '%www.gov.___/%'
             OR ct.official_visa_url LIKE '%www.immigration.___.%'
             OR ct.official_visa_url LIKE 'https://evisa.___.gov%')
    ")->fetch()['cnt'];
    
    // Sample misclassified countries
    $misclassified = $pdo->query("
        SELECT c.country_code, ct.country_name, c.visa_type
        FROM countries c
        JOIN country_translations ct ON c.id = ct.country_id
        WHERE ct.lang_code = 'en' 
        AND c.country_code IN ('FRA','DEU','ITA','ESP','GBR')
        ORDER BY c.country_code
    ")->fetchAll();
    
    $totalCountries = $pdo->query("SELECT COUNT(*) as cnt FROM countries WHERE is_active = 1")->fetch()['cnt'];
    ?>

    <div class="card warning">
        <h2>⚠️ Pre-Fix Assessment</h2>
        <p>Review the current state of your database before applying fixes.</p>
    </div>

    <div class="card">
        <h2>Current visa_type Distribution</h2>
        <table>
            <tr><th>Visa Type</th><th>Count</th></tr>
            <?php foreach ($currentTypes as $row): ?>
            <tr>
                <td><span class="badge badge-blue"><?= htmlspecialchars($row['visa_type']) ?></span></td>
                <td><?= $row['cnt'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="card">
        <h2>Key Countries — Current Classification</h2>
        <table>
            <tr><th>Code</th><th>Country</th><th>Current Type</th><th>Should Be</th></tr>
            <?php foreach ($misclassified as $row): 
                $shouldBe = 'visa_free';
                $wrong = ($row['visa_type'] !== $shouldBe);
            ?>
            <tr>
                <td><?= htmlspecialchars($row['country_code']) ?></td>
                <td><?= htmlspecialchars($row['country_name']) ?></td>
                <td><span class="badge <?= $wrong ? 'badge-red' : 'badge-green' ?>"><?= htmlspecialchars($row['visa_type']) ?></span></td>
                <td><span class="badge badge-green"><?= $shouldBe ?></span></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="number"><?= $totalCountries ?></div>
            <div class="label">Total Countries</div>
        </div>
        <div class="stat">
            <div class="number"><?= $fakeUrls ?></div>
            <div class="label">Fake URLs Found</div>
        </div>
        <div class="stat">
            <div class="number">94</div>
            <div class="label">Override Countries</div>
        </div>
        <div class="stat">
            <div class="number">3</div>
            <div class="label">Phases to Run</div>
        </div>
    </div>

    <div class="card">
        <h2>What Will Be Fixed</h2>
        <table>
            <tr><th>Phase</th><th>Action</th><th>Scope</th></tr>
            <tr><td>1</td><td>Correct visa_type</td><td>192 countries — reclassified for Australian passport holders</td></tr>
            <tr><td>2</td><td>Regenerate visa data</td><td>94 country-specific overrides + improved templates for rest</td></tr>
            <tr><td>3</td><td>Fix fake URLs</td><td>Replace template URLs with real government URLs</td></tr>
        </table>
    </div>

    <div class="actions">
        <form method="POST" onsubmit="return confirm('This will update ALL visa data in your database. Are you sure?');">
            <input type="hidden" name="action" value="apply">
            <button type="submit" class="btn btn-danger">🚀 Apply All Fixes Now</button>
        </form>
    </div>

<?php elseif ($step === 'apply'): ?>
    <!-- ════ APPLY FIXES ════ -->
    <?php
    ob_start();
    $log = [];
    $errors = 0;
    
    function logMsg($msg, $type = 'info') {
        global $log;
        $log[] = ['msg' => $msg, 'type' => $type];
    }

    try {
        $pdo->beginTransaction();
        
        // ════════════════════════════════════════════════
        // PHASE 1: Fix visa_type
        // ════════════════════════════════════════════════
        logMsg("═══ PHASE 1: Correcting visa_type ═══", 'info');
        
        $visaTypeCorrections = [
            'visa_free' => [
                'AUT','BEL','CZE','DNK','EST','FIN','FRA','DEU','GRC','HUN',
                'ISL','ITA','LVA','LTU','LUX','MLT','NLD','NOR','POL','PRT',
                'SVK','SVN','ESP','SWE','CHE','HRV','LIE',
                'GBR','IRL','ROU','BGR','CYP','SRB','ALB','MNE','MKD','BIH',
                'GEO','MDA','UKR','XKX','MCO','SMR','AND','VAT',
                'JPN','KOR','HKG','MAC','SGP','MYS','THA','IDN','PHL','VNM',
                'BRN','MNG','TWN','KAZ','KGZ',
                'MEX','BRA','ARG','CHL','COL','PER','ECU','URY','CRI','PAN',
                'GTM','HND','NIC','SLV','BLZ','DOM','JAM','TTO','BHS','BRB',
                'ATG','DMA','GRD','KNA','LCA','VCT','GUY','SUR','PRY',
                'ARE','ISR','TUR','QAT',
                'ZAF','MAR','TUN','MUS','SYC','BWA','NAM','SEN','SWZ','LSO',
                'BEN','GAB','GMB','RWA',
                'NZL','FJI','VUT','WSM','TON','SLB','KIR','MHL','FSM','TUV',
                'NRU','COK','NIU',
            ],
            'evisa' => [
                'USA','CAN','AUS',
                'IND','LKA','MMR','PAK','UZB','TJK','AZE',
                'KEN','ETH','UGA','ZMB','CIV','CMR','GHA','NGA','MOZ','ZWE',
                'TZA','BFA','GNB','AGO','COG','COD','GIN','MLI','NER','TCD',
                'CAF','SSD','SLE','LBR',
                'SAU','OMN','KWT','BHR',
            ],
            'visa_on_arrival' => [
                'KHM','LAO','NPL','TLS','MDV',
                'JOR','LBN','IRN',
                'EGY','MDG','PLW','PNG','BOL','CUB',
                'CPV','COM','DJI','MRT','TGO','SOM',
            ],
            'visa_required' => [
                'CHN','RUS','PRK',
                'AFG','IRQ','SYR','YEM',
                'TKM','BTN',
                'LBY','SDN','ERI','DZA',
                'BLR',
            ],
        ];
        
        $updateType = $pdo->prepare("UPDATE countries SET visa_type = ? WHERE country_code = ?");
        $phase1Count = 0;
        
        foreach ($visaTypeCorrections as $type => $codes) {
            foreach ($codes as $code) {
                $updateType->execute([$type, $code]);
                if ($updateType->rowCount() > 0) $phase1Count++;
            }
            logMsg("  Set " . count($codes) . " countries to {$type}", 'ok');
        }
        logMsg("Phase 1 complete: {$phase1Count} countries updated", 'ok');

        // ════════════════════════════════════════════════
        // PHASE 2: Regenerate visa data
        // ════════════════════════════════════════════════
        logMsg("", 'info');
        logMsg("═══ PHASE 2: Regenerating visa data ═══", 'info');
        
        // Fetch all countries
        $allCountries = $pdo->query("
            SELECT c.id, c.country_code, c.visa_type, c.official_url, ct.country_name
            FROM countries c
            JOIN country_translations ct ON c.id = ct.country_id
            WHERE ct.lang_code = 'en' AND c.is_active = 1
            ORDER BY c.country_code
        ")->fetchAll();
        
        logMsg("  Found " . count($allCountries) . " countries to process", 'info');

        // Helper: Schengen data
        function schengenData($countryName, $url, $fundsNote = 'approximately €65 per day') {
            return [
                'duration' => '90 days within 180-day period (Schengen)',
                'passport_validity' => '3+ months beyond departure from Schengen Area; issued within last 10 years',
                'fee' => 'Free',
                'processing_time' => 'Instant at border',
                'official_url' => $url,
                'arrival_card' => 'No',
                'docs' => "• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds ({$fundsNote})\n• Purpose of visit documentation (if requested)",
                'requirements' => "Australian passport holders can enter {$countryName} and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds ({$fundsNote}). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required."
            ];
        }

        function visaFreeData($countryName, $duration, $url, $passportValidity = '6 months beyond entry date', $extraDocs = '', $extraNotes = '') {
            $docs = "• Valid passport ({$passportValidity})\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds";
            if ($extraDocs) $docs .= "\n" . $extraDocs;
            $req = "Australian passport holders can enter {$countryName} visa-free for up to {$duration} for tourism or business. Your passport must be valid for {$passportValidity}. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration.";
            if ($extraNotes) $req .= " " . $extraNotes;
            return [
                'duration' => $duration,
                'passport_validity' => $passportValidity,
                'fee' => 'Free',
                'processing_time' => 'Instant at border',
                'official_url' => $url,
                'arrival_card' => 'No',
                'docs' => $docs,
                'requirements' => $req
            ];
        }

        // ── Country overrides (94 countries) ──
        $countryOverrides = [
            // SCHENGEN (27)
            'FRA' => schengenData('France', 'https://france-visas.gouv.fr', 'approximately €65 per day'),
            'DEU' => schengenData('Germany', 'https://www.auswaertiges-amt.de/en/visa-service', 'approximately €45 per day'),
            'ITA' => schengenData('Italy', 'https://vistoperitalia.esteri.it', 'approximately €45-50 per day'),
            'ESP' => schengenData('Spain', 'https://www.exteriores.gob.es/en/ServiciosAlCiudadano/Paginas/Visados.aspx', '€100 per person per day (minimum €900 total)'),
            'NLD' => schengenData('the Netherlands', 'https://www.netherlandsworldwide.nl/visa-the-netherlands', 'approximately €55 per day'),
            'BEL' => schengenData('Belgium', 'https://diplomatie.belgium.be/en/travel-belgium/visa-belgium', 'approximately €95 per day'),
            'AUT' => schengenData('Austria', 'https://www.bmeia.gv.at/en/travel-stay/entry-requirements', 'approximately €60 per day'),
            'CHE' => schengenData('Switzerland', 'https://www.sem.admin.ch/sem/en/home/themen/einreise.html', 'approximately CHF 100 per day'),
            'GRC' => schengenData('Greece', 'https://www.mfa.gr/en/visas/', 'approximately €50 per day'),
            'PRT' => schengenData('Portugal', 'https://www.vistos.mne.gov.pt/en/', 'approximately €40 per day'),
            'SWE' => schengenData('Sweden', 'https://www.migrationsverket.se/English/Private-individuals/Visiting-Sweden.html', 'approximately SEK 450 per day'),
            'NOR' => schengenData('Norway', 'https://www.udi.no/en/want-to-apply/visit-and-holiday/', 'approximately NOK 500 per day'),
            'DNK' => schengenData('Denmark', 'https://www.nyidanmark.dk/en-GB', 'approximately DKK 350 per day'),
            'FIN' => schengenData('Finland', 'https://um.fi/entering-finland', 'approximately €50 per day'),
            'ISL' => schengenData('Iceland', 'https://www.utl.is/index.php/en/', 'approximately ISK 10,000 per day'),
            'POL' => schengenData('Poland', 'https://www.gov.pl/web/diplomacy/visas', 'approximately PLN 300 per day'),
            'CZE' => schengenData('Czech Republic', 'https://www.mzv.cz/jnp/en/information_for_aliens/visa_information/index.html', 'approximately CZK 1,000 per day'),
            'HUN' => schengenData('Hungary', 'https://konzuliszolgalat.kormany.hu/en', 'approximately €40 per day'),
            'SVK' => schengenData('Slovakia', 'https://www.mzv.sk/en/travel_to_slovakia/visa_requirements', 'approximately €50 per day'),
            'SVN' => schengenData('Slovenia', 'https://www.gov.si/en/topics/entry-and-residence/', 'approximately €60 per day'),
            'EST' => schengenData('Estonia', 'https://www.politsei.ee/en/instructions/applying-for-visa', 'approximately €45 per day'),
            'LVA' => schengenData('Latvia', 'https://www.pmlp.gov.lv/en/home.html', 'approximately €40 per day'),
            'LTU' => schengenData('Lithuania', 'https://www.migracija.lt/en', 'approximately €40 per day'),
            'MLT' => schengenData('Malta', 'https://identitymalta.com/', 'approximately €48 per day'),
            'LUX' => schengenData('Luxembourg', 'https://guichet.public.lu/en/citoyens/immigration.html', 'approximately €50 per day'),
            'HRV' => schengenData('Croatia', 'https://mvep.gov.hr/services-for-citizens/consular-information/visas/visa-requirements-overview/861', 'approximately €60 per day'),
            'LIE' => schengenData('Liechtenstein', 'https://www.llv.li/en/national-administration/office-of-immigration-and-passports', 'approximately CHF 100 per day'),

            // OTHER EUROPE
            'GBR' => ['duration'=>'6 months','passport_validity'=>'Valid for duration of stay','fee'=>'Free','processing_time'=>'Instant at border','official_url'=>'https://www.gov.uk/check-uk-visa','arrival_card'=>'No','docs'=>"• Valid Australian passport\n• Return/onward ticket\n• Proof of accommodation\n• Evidence of sufficient funds\n• Details of your visit (business contacts, tour bookings, etc.)",'requirements'=>"Australian passport holders can visit the United Kingdom visa-free for up to 6 months for tourism, business meetings, or family visits. Your passport must be valid for the duration of your stay. No advance application required — your visa status is determined at the UK border. Bring proof of your return or onward journey, accommodation details, and evidence of sufficient funds. No arrival card is required. If you plan to work, study, or stay longer than 6 months, you will need to apply for the appropriate visa."],
            'IRL' => ['duration'=>'90 days','passport_validity'=>'Valid for duration of stay','fee'=>'Free','processing_time'=>'Instant at border','official_url'=>'https://www.irishimmigration.ie/','arrival_card'=>'No','docs'=>"• Valid Australian passport\n• Return/onward ticket\n• Proof of accommodation\n• Evidence of sufficient funds",'requirements'=>"Australian passport holders can visit Ireland visa-free for up to 90 days for tourism or business. Your passport must be valid for the duration of your stay. No advance application required. Present your return ticket, accommodation details, and evidence of sufficient funds at immigration. Note: Ireland is NOT part of the Schengen Area — time spent in Ireland does not count toward Schengen 90-day limits, and a Schengen visa does not cover Ireland."],
            'TUR' => ['duration'=>'90 days within 180-day period','passport_validity'=>'6 months beyond entry date','fee'=>'Free','processing_time'=>'Instant at border','official_url'=>'https://www.evisa.gov.tr/en/','arrival_card'=>'No','docs'=>"• Valid passport (6 months validity)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds",'requirements'=>"Australian passport holders can enter Turkey visa-free for up to 90 days within a 180-day period for tourism or business. Your passport must be valid for at least 6 months from your date of entry. No eVisa or advance application is required. Present your return ticket, accommodation proof, and evidence of sufficient funds at immigration."],
            'ROU' => visaFreeData('Romania', '90 days within 180-day period', 'https://www.mae.ro/en/node/2040', 'valid for 3+ months beyond departure', '', 'Romania is an EU member but not yet in the Schengen Area. Time spent in Romania does NOT count toward your Schengen 90-day limit.'),
            'BGR' => visaFreeData('Bulgaria', '90 days within 180-day period', 'https://www.mfa.bg/en/services/travel-bulgaria/visa-bulgaria', 'valid for 3+ months beyond departure', '', 'Bulgaria is an EU member but not yet fully in the Schengen Area. Time spent in Bulgaria does NOT count toward your Schengen 90-day limit.'),
            'CYP' => visaFreeData('Cyprus', '90 days within 180-day period', 'https://www.mfa.gov.cy/visa/', 'valid for 3+ months beyond departure', '', 'Cyprus is an EU member but not in the Schengen Area. Time spent in Cyprus does NOT count toward your Schengen 90-day limit.'),
            'SRB' => visaFreeData('Serbia', '90 days within 180-day period', 'https://www.mfa.gov.rs/en/consular-affairs/entry-serbia/visa-regime'),
            'ALB' => visaFreeData('Albania', '90 days within calendar year', 'https://punetejashtme.gov.al/en/services/consular-services/visas/'),
            'MNE' => visaFreeData('Montenegro', '90 days within 180-day period', 'https://www.gov.me/en/mup/services'),
            'MKD' => visaFreeData('North Macedonia', '90 days within 180-day period', 'https://www.mfa.gov.mk/?q=category/37/visa-regime'),
            'BIH' => visaFreeData('Bosnia and Herzegovina', '90 days within 180-day period', 'https://www.mvp.gov.ba/konzularne_informacije/vize/?lang=en'),
            'GEO' => visaFreeData('Georgia', '1 year', 'https://www.geoconsul.gov.ge/en/nonvisa', 'valid for duration of stay', '', 'Georgia offers one of the most generous visa-free stays, allowing Australians to stay for up to 1 year without a visa.'),
            'MDA' => visaFreeData('Moldova', '90 days within 180-day period', 'https://www.mfa.gov.md/en/content/visa-information'),
            'UKR' => visaFreeData('Ukraine', '90 days within 180-day period', 'https://mfa.gov.ua/en/consular-affairs/entering-ukraine/visa-requirements-for-foreigners', 'valid for duration of stay', '', 'Check current travel advisories before planning travel to Ukraine.'),

            // AMERICAS
            'USA' => ['duration'=>'90 days (Visa Waiver Program)','passport_validity'=>'Valid for duration of stay (e-Passport required)','fee'=>'ESTA: $21 USD','processing_time'=>'ESTA: Usually instant, up to 72 hours','official_url'=>'https://esta.cbp.dhs.gov','arrival_card'=>'No (ESTA replaces arrival card)','docs'=>"• Valid e-Passport (electronic passport with chip)\n• Approved ESTA (\$21 USD)\n• Return/onward ticket\n• Proof of accommodation\n• Evidence of sufficient funds",'requirements'=>"Australian passport holders can travel to the United States visa-free under the Visa Waiver Program (VWP) for stays up to 90 days for tourism or business. You must obtain an Electronic System for Travel Authorization (ESTA) online at https://esta.cbp.dhs.gov before departure — ideally at least 72 hours in advance. The ESTA costs \$21 USD and is valid for 2 years or until your passport expires. Your passport must be an e-Passport (with electronic chip). Present your ESTA approval, return ticket, and proof of accommodation upon arrival. The 90-day stay cannot be extended."],
            'CAN' => ['duration'=>'6 months','passport_validity'=>'Valid for duration of stay','fee'=>'eTA: $7 CAD','processing_time'=>'eTA: Usually instant, up to 72 hours','official_url'=>'https://www.canada.ca/en/immigration-refugees-citizenship/services/visit-canada/eta.html','arrival_card'=>'No','docs'=>"• Valid passport\n• Approved eTA (\$7 CAD)\n• Return/onward ticket\n• Proof of sufficient funds\n• Letter of invitation (if visiting family/friends)",'requirements'=>"Australian passport holders need an Electronic Travel Authorization (eTA) to fly to Canada. The eTA costs \$7 CAD and is applied for online. It is usually approved within minutes but can take up to 72 hours. Once approved, your eTA is valid for 5 years or until your passport expires. You can stay in Canada for up to 6 months per visit. Present your eTA confirmation with your passport at check-in and at the Canadian border. If arriving by land from the US, no eTA is needed."],
            'AUS' => ['duration'=>'N/A — Home country','passport_validity'=>'N/A','fee'=>'N/A','processing_time'=>'N/A','official_url'=>'https://www.homeaffairs.gov.au','arrival_card'=>'Yes (Incoming Passenger Card)','docs'=>"• Valid Australian passport",'requirements'=>"As an Australian passport holder, you have the automatic right of entry into Australia. No visa is required. Your passport must be valid. You will need to complete an Incoming Passenger Card upon arrival, which collects customs and immigration information. SmartGate (automated passport control) is available at major airports."],
            'MEX' => visaFreeData('Mexico', '180 days', 'https://www.inm.gob.mx/', 'valid for duration of stay', "• Completed FMM (Forma Migratoria Múltiple) — available at airport", "Keep your FMM form safe — you must surrender it when departing Mexico."),
            'BRA' => visaFreeData('Brazil', '90 days within 180-day period', 'https://www.gov.br/mre/en/subjects/visas', 'valid for 6+ months', '', 'Australian passport holders have had visa-free access to Brazil since 2024.'),
            'ARG' => visaFreeData('Argentina', '90 days', 'https://www.argentina.gob.ar/interior/migraciones', 'valid for duration of stay', '', 'Extensions of 90 days are possible at the immigration office.'),
            'CHL' => visaFreeData('Chile', '90 days', 'https://www.chile.gob.cl/chile/en/travel-chile', 'valid for duration of stay'),
            'COL' => visaFreeData('Colombia', '90 days (extendable to 180)', 'https://www.cancilleria.gov.co/en/procedures_services/visas', 'valid for duration of stay', '', 'Complete the Check-Mig online form within 72 hours before arrival.'),
            'PER' => visaFreeData('Peru', '183 days', 'https://www.migraciones.gob.pe/', 'valid for 6+ months'),
            'ECU' => visaFreeData('Ecuador', '90 days within calendar year', 'https://www.cancilleria.gob.ec/', 'valid for 6+ months'),
            'CRI' => visaFreeData('Costa Rica', '90 days', 'https://www.migracion.go.cr/', 'valid for duration of stay (6 months recommended)'),
            'PAN' => visaFreeData('Panama', '180 days', 'https://www.migracion.gob.pa/', 'valid for 3+ months beyond entry', '', 'Present your return ticket (within 180 days) and proof of funds ($500 minimum or credit card).'),

            // ASIA-PACIFIC
            'JPN' => ['duration'=>'90 days','passport_validity'=>'Valid for duration of stay','fee'=>'Free','processing_time'=>'Instant at immigration','official_url'=>'https://www.mofa.go.jp/j_info/visit/visa/','arrival_card'=>'Yes (ED card or Visit Japan Web)','docs'=>"• Valid passport\n• Completed disembarkation card (or Visit Japan Web registration)\n• Return/onward ticket\n• Proof of accommodation\n• Sufficient funds",'requirements'=>"Australian passport holders can enter Japan visa-free for up to 90 days for tourism or business. Your passport must be valid for the duration of your stay. Register at Visit Japan Web (https://vjw-lp.digital.go.jp/en/) before arrival to expedite immigration and customs procedures. Fingerprints and photo are taken at entry."],
            'KOR' => ['duration'=>'90 days','passport_validity'=>'Valid for duration of stay','fee'=>'Free','processing_time'=>'Instant at immigration','official_url'=>'https://www.immigration.go.kr/immigration_eng/index.do','arrival_card'=>'Yes (arrival card at immigration)','docs'=>"• Valid passport\n• Completed arrival card\n• Return/onward ticket\n• Proof of accommodation",'requirements'=>"Australian passport holders can enter South Korea visa-free for up to 90 days for tourism or business. K-ETA (Korea Electronic Travel Authorization) has been suspended for Australian nationals — you do not need to apply. Simply present your valid passport, completed arrival card, return ticket, and accommodation proof at immigration."],
            'HKG' => visaFreeData('Hong Kong', '90 days', 'https://www.immd.gov.hk/eng/services/visas/visit-transit/visit-visa-entry-permit.html', 'valid for 1+ months beyond intended stay', "• Completed arrival card (or pre-register online)", "Hong Kong is a Special Administrative Region of China — visa rules are separate from mainland China."),
            'SGP' => ['duration'=>'90 days','passport_validity'=>'6 months beyond entry date','fee'=>'Free','processing_time'=>'Instant at immigration','official_url'=>'https://www.ica.gov.sg/enter-transit-depart/entering-singapore','arrival_card'=>'Yes (SG Arrival Card — online)','docs'=>"• Valid passport (6 months validity)\n• Completed SG Arrival Card (submitted online within 3 days before arrival)\n• Return/onward ticket\n• Proof of accommodation\n• Sufficient funds",'requirements'=>"Australian passport holders can visit Singapore visa-free for up to 90 days for tourism or business. Your passport must be valid for at least 6 months. Submit an SG Arrival Card online at https://eservices.ica.gov.sg/sgarrivalcard within 3 days before arrival."],
            'MYS' => ['duration'=>'90 days','passport_validity'=>'6 months beyond entry date','fee'=>'Free','processing_time'=>'Instant at immigration','official_url'=>'https://www.imi.gov.my','arrival_card'=>'Yes (MDAC — submit online)','docs'=>"• Valid passport (6 months validity)\n• Malaysia Digital Arrival Card (MDAC) submitted online within 3 days before arrival\n• Return/onward ticket\n• Proof of accommodation\n• Sufficient funds",'requirements'=>"Australian passport holders can visit Malaysia visa-free for up to 90 days for tourism or business. Your passport must be valid for at least 6 months. Complete the Malaysia Digital Arrival Card (MDAC) at https://imigresen-online.imi.gov.my/mdac within 3 days before arrival."],
            'THA' => ['duration'=>'60 days (air) / 30 days (land)','passport_validity'=>'6 months beyond entry date','fee'=>'Free','processing_time'=>'Instant at immigration','official_url'=>'https://www.immigration.go.th','arrival_card'=>'Yes (TM.6 departure card)','docs'=>"• Valid passport (6 months validity)\n• TM.6 arrival/departure card\n• Return/onward ticket within permitted stay\n• Proof of accommodation\n• Proof of funds (10,000 THB/person or 20,000 THB/family)",'requirements'=>"Australian passport holders can enter Thailand visa-free for up to 60 days when arriving by air (or 30 days by land border). Your passport must be valid for at least 6 months. Extensions of 30 days are available at immigration offices for 1,900 THB."],
            'IDN' => ['duration'=>'30 days (visa-free) or 30 days extendable (VOA)','passport_validity'=>'6 months beyond entry date','fee'=>'Free (visa-free) or $35 USD (Visa on Arrival, extendable)','processing_time'=>'Instant at immigration','official_url'=>'https://www.imigrasi.go.id','arrival_card'=>'Yes (customs declaration form)','docs'=>"• Valid passport (6 months validity, 2 blank pages)\n• Return/onward ticket within 30 days\n• Proof of accommodation\n• Customs declaration form",'requirements'=>"Australian passport holders have two options for Indonesia: (1) Visa-free entry for 30 days — free but NOT extendable; (2) Visa on Arrival (VOA) for 30 days at \$35 USD — extendable once for 30 more days. If you may want to stay longer than 30 days, choose the VOA option."],
            'PHL' => ['duration'=>'30 days','passport_validity'=>'6 months beyond entry date','fee'=>'Free','processing_time'=>'Instant at immigration','official_url'=>'https://www.immigration.gov.ph','arrival_card'=>'Yes (eTravel — submit online before departure)','docs'=>"• Valid passport (6 months validity)\n• eTravel registration (submitted within 72 hours of departure)\n• Return/onward ticket within 30 days\n• Proof of accommodation",'requirements'=>"Australian passport holders can enter the Philippines visa-free for up to 30 days. Register via the eTravel system at https://etravel.gov.ph within 72 hours before departure and save your QR code. Extensions up to 36 months total are available at Bureau of Immigration offices."],
            'VNM' => ['duration'=>'45 days (visa-free, single entry)','passport_validity'=>'6 months beyond entry date','fee'=>'Free (visa-free) or $25 USD (eVisa for 90 days)','processing_time'=>'Instant at border (eVisa: 3 business days)','official_url'=>'https://evisa.xuatnhapcanh.gov.vn','arrival_card'=>'No','docs'=>"• Valid passport (6 months validity)\n• Return/onward ticket\n• Proof of accommodation\n• eVisa printout (if staying beyond 45 days)",'requirements'=>"Australian passport holders can enter Vietnam visa-free for up to 45 days (single entry only). For stays beyond 45 days or multiple entries, apply for an eVisa online (\$25 USD, valid 90 days) at least 3 business days before departure."],
            'IND' => ['duration'=>'30-90 days (eVisa)','passport_validity'=>'6 months beyond entry date with 2 blank pages','fee'=>'$25-100 USD depending on duration','processing_time'=>'1-4 business days','official_url'=>'https://indianvisaonline.gov.in','arrival_card'=>'No','docs'=>"• Valid passport (6 months validity, 2 blank pages)\n• Digital passport photo (white background)\n• Scanned passport bio page\n• Return flight ticket\n• Proof of accommodation\n• Payment by credit/debit card",'requirements'=>"Australian passport holders must apply for an eVisa before traveling to India. Tourist eVisas are available for: 30 days (double entry, \$25 USD), 1 year (multiple entry, \$40 USD), or 5 years (multiple entry, \$80 USD). Apply at https://indianvisaonline.gov.in at least 4 business days before departure."],
            'LKA' => ['duration'=>'30 days (extendable to 90)','passport_validity'=>'6 months beyond entry date','fee'=>'$50 USD (ETA)','processing_time'=>'1-3 business days','official_url'=>'https://www.eta.gov.lk','arrival_card'=>'No','docs'=>"• Valid passport (6 months validity)\n• Approved ETA\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds (\$50/day)",'requirements'=>"Australian passport holders must obtain an Electronic Travel Authorization (ETA) before traveling to Sri Lanka. Apply online at https://www.eta.gov.lk. The tourist ETA costs \$50 USD, is valid for 30 days, and allows double entry. Extensions up to 90 days are available."],
            'KHM' => ['duration'=>'30 days','passport_validity'=>'6 months beyond entry date','fee'=>'$30 USD (eVisa) or $30 USD (Visa on Arrival)','processing_time'=>'eVisa: 3 business days; VOA: 15-30 minutes','official_url'=>'https://www.evisa.gov.kh/','arrival_card'=>'Yes','docs'=>"• Valid passport (6 months validity, 1 blank page)\n• Passport-sized photo (4x6cm)\n• Visa fee: \$30 USD cash (VOA) or online payment (eVisa)\n• Return/onward ticket\n• Proof of accommodation",'requirements'=>"Australian passport holders can obtain a Cambodia visa either as an eVisa (apply at https://www.evisa.gov.kh, \$30 USD + \$6 processing fee) or as a Visa on Arrival (\$30 USD cash, bring a passport photo). Both allow a 30-day stay, extendable once for 30 days."],
            'NPL' => ['duration'=>'15, 30, or 90 days','passport_validity'=>'6 months beyond entry date','fee'=>'$30 USD (15 days), $50 USD (30 days), $125 USD (90 days)','processing_time'=>'15-30 minutes at airport','official_url'=>'https://www.immigration.gov.np/','arrival_card'=>'Yes','docs'=>"• Valid passport (6 months validity)\n• Passport photo\n• Visa fee in cash (USD)\n• Completed visa application form\n• Return/onward ticket",'requirements'=>"Australian passport holders can obtain a Nepal visa on arrival at Tribhuvan International Airport (Kathmandu) and major land borders. Choose between 15-day (\$30 USD), 30-day (\$50 USD), or 90-day (\$125 USD) tourist visas. Maximum total stay is 150 days per calendar year."],
            'MNG' => visaFreeData('Mongolia', '30 days', 'https://www.consul.mn/eng/index.php?moduls=23', '6 months beyond entry date', '', 'For stays beyond 30 days, register with the Immigration Agency of Mongolia within 7 days of arrival.'),
            'NZL' => ['duration'=>'Indefinite (Australian citizens can live and work)','passport_validity'=>'Valid for travel','fee'=>'Free','processing_time'=>'Instant at border','official_url'=>'https://www.immigration.govt.nz/new-zealand-visas','arrival_card'=>'Yes (NZTD — submit online)','docs'=>"• Valid Australian passport\n• New Zealand Traveller Declaration (submitted online before arrival)",'requirements'=>"Australian citizens receive a Resident Visa on arrival in New Zealand under the Trans-Tasman Travel Arrangement. You can live, work, and study indefinitely — no advance application is needed. Complete the New Zealand Traveller Declaration (NZTD) online before your flight."],
            'FJI' => visaFreeData('Fiji', '4 months', 'https://www.immigration.gov.fj/', 'valid for 6+ months beyond intended stay', "• Completed arrival card", "Extensions up to 6 months total are available at a Fiji immigration office."),
            'PNG' => ['duration'=>'60 days','passport_validity'=>'6 months beyond entry date','fee'=>'Free (Visa on Arrival)','processing_time'=>'15-30 minutes at airport','official_url'=>'https://www.ica.gov.pg/','arrival_card'=>'Yes','docs'=>"• Valid passport (6 months validity)\n• Return/onward ticket\n• Proof of accommodation\n• Sufficient funds",'requirements'=>"Australian passport holders can obtain a free Visa on Arrival at Port Moresby Jacksons International Airport for up to 60 days. Your passport must be valid for at least 6 months."],
            'BRN' => visaFreeData('Brunei', '90 days', 'https://www.immigration.gov.bn/', '6 months beyond entry date'),
            'KAZ' => visaFreeData('Kazakhstan', '30 days', 'https://www.gov.kz/memleket/entities/mfa/press/article/details/211611?lang=en', '6 months beyond entry date'),
            'KGZ' => visaFreeData('Kyrgyzstan', '60 days', 'https://evisa.e-gov.kg/get_information.php', '6 months beyond entry date'),

            // MIDDLE EAST
            'ARE' => ['duration'=>'60 days (free visa on arrival)','passport_validity'=>'6 months beyond entry date','fee'=>'Free','processing_time'=>'5-10 minutes at airport','official_url'=>'https://www.icp.gov.ae','arrival_card'=>'No','docs'=>"• Valid passport (6 months validity)\n• Return/onward ticket\n• Hotel reservation\n• Proof of sufficient funds",'requirements'=>"Australian passport holders receive a free 60-day visa stamp on arrival at UAE airports. Your passport must be valid for at least 6 months. This visa can be extended for an additional 30 days at a GDRFA service center (fee applies). No advance application required."],
            'ISR' => visaFreeData('Israel', '90 days', 'https://www.gov.il/en/departments/topics/visa-information', '6 months beyond entry date', "• Proof of travel insurance", "An electronic entry card is issued at the airport — Israeli entry/exit stamps are issued electronically and don't appear in your passport."),
            'JOR' => ['duration'=>'30 days (single entry) or stay duration with Jordan Pass','passport_validity'=>'6 months beyond entry date','fee'=>'$56-113 USD (visa) or included in Jordan Pass ($70-80 USD)','processing_time'=>'10-15 minutes at airport','official_url'=>'https://www.jordanpass.jo/','arrival_card'=>'Yes','docs'=>"• Valid passport (6 months validity)\n• Jordan Pass (recommended) OR visa fee in cash\n• Return/onward ticket\n• Proof of accommodation",'requirements'=>"Australian passport holders can obtain a Jordan visa on arrival. Strongly recommended: purchase a Jordan Pass at https://www.jordanpass.jo before arrival (\$70-80 USD) — it includes the visa fee AND entry to Petra and 40+ attractions. You must stay at least 3 consecutive nights for the Jordan Pass visa waiver."],
            'QAT' => visaFreeData('Qatar', '90 days', 'https://portal.moi.gov.qa/wps/portal/MOIInternet/departmentcommissioner/visas/', '6 months beyond entry date', '', 'Qatar grants visa-free entry for Australian passport holders for up to 90 days. No advance application required.'),
            'SAU' => ['duration'=>'90 days within 1-year period (multiple entry)','passport_validity'=>'6 months beyond entry date','fee'=>'$120 USD (eVisa)','processing_time'=>'1-5 business days','official_url'=>'https://visa.visitsaudi.com/','arrival_card'=>'No','docs'=>"• Valid passport (6 months validity)\n• Digital passport photo\n• Completed eVisa application\n• Return flight ticket\n• Proof of accommodation\n• Travel insurance\n• Payment by credit card",'requirements'=>"Australian passport holders can apply for a Saudi tourist eVisa online at https://visa.visitsaudi.com. The eVisa costs approximately \$120 USD (including insurance), is valid for 1 year with multiple entries, and allows stays of up to 90 days per visit. Women can travel independently. Dress modestly. Alcohol is prohibited."],
            'OMN' => ['duration'=>'14 days (free) or 30 days (paid eVisa)','passport_validity'=>'6 months beyond entry date','fee'=>'Free (14-day) or 20 OMR / ~$52 USD (30-day eVisa)','processing_time'=>'1-3 business days','official_url'=>'https://evisa.rop.gov.om/','arrival_card'=>'No','docs'=>"• Valid passport (6 months validity)\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Travel insurance",'requirements'=>"Australian passport holders have two options: (1) Free 14-day tourist visa on arrival, or (2) 30-day eVisa for 20 OMR (~\$52 USD) applied at https://evisa.rop.gov.om. Both require a return ticket, accommodation proof, and travel insurance."],
            'EGY' => ['duration'=>'30 days (single entry)','passport_validity'=>'6 months beyond entry date','fee'=>'$25 USD','processing_time'=>'Visa on Arrival: 10-15 min; eVisa: 5-7 business days','official_url'=>'https://visa2egypt.gov.eg/','arrival_card'=>'Yes','docs'=>"• Valid passport (6 months validity)\n• Passport photo\n• Visa fee: \$25 USD (cash for VOA; card for eVisa)\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds",'requirements'=>"Australian passport holders can obtain an Egypt visa either on arrival at Egyptian airports (\$25 USD, paid at bank kiosk before immigration) or as an eVisa at https://visa2egypt.gov.eg (\$25 USD). The eVisa option avoids queues at the airport. Extensions are possible at the Mogamma building in Cairo."],

            // AFRICA
            'ZAF' => visaFreeData('South Africa', '90 days', 'http://www.dha.gov.za/', '30 days beyond intended departure with 2 blank pages', "• Proof of yellow fever vaccination (if arriving from endemic area)\n• Return/onward ticket (mandatory)", "Your passport MUST have at least 2 blank pages for entry/exit stamps — South Africa is strict about this and will deny boarding if you don't have blank pages."),
            'MAR' => visaFreeData('Morocco', '90 days', 'https://www.consulat.ma/en', '6 months beyond entry date', "• Completed arrival form (provided on flight or at border)"),
            'TUN' => visaFreeData('Tunisia', '90 days', 'https://www.diplomatie.gov.tn/en/', '3+ months beyond entry date'),
            'MUS' => visaFreeData('Mauritius', '60 days (extendable to 90)', 'https://passport.govmu.org/', 'valid for duration of stay', "• Completed disembarkation card\n• Return ticket", "Extensions to 90 days are available free of charge at the Passport and Immigration Office in Port Louis."),
            'KEN' => ['duration'=>'90 days','passport_validity'=>'6 months beyond entry date','fee'=>'$50 USD (eTA)','processing_time'=>'3-5 business days','official_url'=>'https://www.etakenya.go.ke/','arrival_card'=>'No','docs'=>"• Valid passport (6 months validity, 2 blank pages)\n• Approved eTA\n• Yellow fever vaccination certificate\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds",'requirements'=>"Australian passport holders must obtain an electronic Travel Authorization (eTA) before traveling to Kenya. Apply at https://www.etakenya.go.ke at least 5 business days before departure. The eTA costs \$50 USD. A yellow fever vaccination certificate is MANDATORY."],
            'TZA' => ['duration'=>'90 days','passport_validity'=>'6 months beyond entry date','fee'=>'$50 USD (eVisa)','processing_time'=>'5-10 business days','official_url'=>'https://visa.immigration.go.tz/','arrival_card'=>'Yes','docs'=>"• Valid passport (6 months validity, 2 blank pages)\n• Approved eVisa\n• Yellow fever vaccination certificate\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds (\$500 minimum)",'requirements'=>"Australian passport holders must obtain an eVisa before traveling to Tanzania. Apply at https://visa.immigration.go.tz at least 10 business days before departure. A yellow fever vaccination certificate is MANDATORY."],

            // VISA REQUIRED — Key
            'CHN' => ['duration'=>'30-90 days (depending on visa type)','passport_validity'=>'6 months beyond entry date with 2 blank pages','fee'=>'$109.50 AUD (tourist visa)','processing_time'=>'4-7 business days','official_url'=>'https://www.visaforchina.cn','arrival_card'=>'Yes','docs'=>"• Valid passport (6 months validity, 2 blank pages)\n• Completed visa application form (Form V.2013)\n• Recent passport photo (48x33mm, white background)\n• Round-trip flight booking\n• Hotel reservations for entire stay\n• Bank statement (last 3 months)\n• Travel insurance\n• Employment letter or proof of income\n• Invitation letter (if visiting friends/family)",'requirements'=>"Australian passport holders must obtain a visa before traveling to China. Apply through a Chinese Visa Application Service Center (CVASC) in Sydney, Melbourne, Brisbane, or Perth at least 7 business days before departure. Tourist (L) visas are typically valid for 30 days (single entry) or up to 90 days (double/multiple entry). Fee is approximately \$109.50 AUD. Note: Some Chinese cities offer 72-hour or 144-hour visa-free transit — check eligibility at your transit city."],
            'RUS' => ['duration'=>'30 days (tourist visa) or 16 days (eVisa where available)','passport_validity'=>'6 months beyond visa expiry','fee'=>'$50+ USD (tourist visa); eVisa $40 USD where available','processing_time'=>'10-20 business days (tourist visa); eVisa 4 days','official_url'=>'https://electronic-visa.kdmid.ru/','arrival_card'=>'Yes (migration card at border)','docs'=>"• Valid passport (6 months beyond visa expiry, 2 blank pages)\n• Completed visa application form\n• Passport photo\n• Letter of invitation from Russian tour operator/hotel\n• Travel insurance valid in Russia\n• Proof of accommodation\n• Visa fee payment",'requirements'=>"Australian passport holders require a visa to visit Russia. Tourist visas (up to 30 days) require an invitation letter from a Russian hotel or tour operator. Apply at the Russian embassy/consulate at least 20 business days before travel. Check current travel advisories before planning travel."],

            // ADDITIONAL eVISA/VOA
            'MMR' => ['duration'=>'28 days','passport_validity'=>'6 months beyond entry date','fee'=>'$50 USD (eVisa)','processing_time'=>'3-5 business days','official_url'=>'https://evisa.moip.gov.mm/','arrival_card'=>'Yes','docs'=>"• Valid passport (6 months validity)\n• Digital passport photo\n• Return flight ticket\n• Proof of accommodation\n• Payment by credit/debit card",'requirements'=>"Australian passport holders must obtain an eVisa before traveling to Myanmar. Apply at https://evisa.moip.gov.mm at least 5 business days before departure. The tourist eVisa costs \$50 USD. Check current travel advisories before planning travel."],
            'ETH' => ['duration'=>'30 or 90 days','passport_validity'=>'6 months beyond entry date','fee'=>'$52-72 USD (eVisa)','processing_time'=>'1-3 business days','official_url'=>'https://www.evisa.gov.et/','arrival_card'=>'Yes','docs'=>"• Valid passport (6 months validity)\n• Digital passport photo\n• Return flight ticket\n• Proof of accommodation\n• Yellow fever vaccination certificate\n• Payment by credit card",'requirements'=>"Australian passport holders must obtain an eVisa before traveling to Ethiopia. Apply at https://www.evisa.gov.et. Tourist eVisas cost \$52 USD (30 days) or \$72 USD (90 days). Entry is via Bole International Airport (Addis Ababa) only for eVisa holders."],
            'UZB' => ['duration'=>'30 days','passport_validity'=>'3 months beyond visa expiry','fee'=>'$20 USD (eVisa)','processing_time'=>'2-3 business days','official_url'=>'https://e-visa.gov.uz/main','arrival_card'=>'No','docs'=>"• Valid passport (3+ months beyond visa expiry)\n• Digital passport photo\n• Proof of accommodation\n• Return ticket\n• Payment by credit card",'requirements'=>"Australian passport holders can apply for an eVisa for Uzbekistan at https://e-visa.gov.uz. The eVisa costs \$20 USD, allows a single-entry stay of 30 days. Register with local authorities if staying more than 3 days in one location (hotels do this automatically)."],
            'LAO' => ['duration'=>'30 days','passport_validity'=>'6 months beyond entry date','fee'=>'$35 USD','processing_time'=>'10-20 minutes at immigration','official_url'=>'https://laoevisa.gov.la/','arrival_card'=>'Yes','docs'=>"• Valid passport (6 months validity, 2 blank pages)\n• Passport photo (4x6cm)\n• Visa fee: \$35 USD cash\n• Return/onward ticket\n• Proof of accommodation",'requirements'=>"Australian passport holders can obtain a Laos visa on arrival at international airports and most land borders for \$35 USD cash. Alternatively, apply for an eVisa at https://laoevisa.gov.la (\$35 + \$5 processing). Both allow a 30-day stay, extendable at immigration offices for \$2/day."],
            'MDV' => ['duration'=>'30 days','passport_validity'=>'6 months beyond entry date','fee'=>'Free (visa on arrival)','processing_time'=>'Instant at immigration','official_url'=>'https://immigration.gov.mv/','arrival_card'=>'Yes (IMUGA online declaration)','docs'=>"• Valid passport (6 months validity)\n• Return/onward ticket\n• Proof of accommodation (hotel/resort booking)\n• Proof of sufficient funds (\$100 + \$50/day)\n• IMUGA Traveller Declaration",'requirements'=>"Australian passport holders receive a free 30-day visa on arrival in the Maldives. Submit the IMUGA Traveller Declaration at https://imuga.immigration.gov.mv within 96 hours before arrival. The Maldives is an Islamic country — alcohol is only available at resort islands."],
            'BOL' => ['duration'=>'30 days (extendable to 90)','passport_validity'=>'6 months beyond entry date','fee'=>'$52 USD (Visa on Arrival)','processing_time'=>'30-60 minutes at border','official_url'=>'https://www.rree.gob.bo/webmre/','arrival_card'=>'Yes','docs'=>"• Valid passport (6 months validity, 2 blank pages)\n• Passport photo\n• Return/onward ticket\n• Proof of accommodation\n• Yellow fever vaccination certificate\n• Visa fee in USD cash",'requirements'=>"Australian passport holders can obtain a Bolivia visa on arrival at major airports for \$52 USD (cash). A yellow fever vaccination certificate is required. Extendable up to 90 days at immigration offices."],
        ];

        // Update statement
        $updateStmt = $pdo->prepare("
            UPDATE country_translations SET 
                visa_duration = ?, passport_validity = ?, visa_fee = ?,
                processing_time = ?, official_visa_url = ?, arrival_card_required = ?,
                additional_docs = ?, visa_requirements = ?, last_verified = CURDATE()
            WHERE country_id = (SELECT id FROM countries WHERE country_code = ?)
            AND lang_code = 'en'
        ");

        $overrideCount = 0;
        $templateCount = 0;

        foreach ($allCountries as $country) {
            $code = $country['country_code'];
            $name = $country['country_name'];
            $visaType = $country['visa_type'];

            if (isset($countryOverrides[$code])) {
                $d = $countryOverrides[$code];
                $overrideCount++;
            } else {
                // Improved template
                switch ($visaType) {
                    case 'visa_free':
                        $d = ['duration'=>'90 days (check specific country allowance)','passport_validity'=>'6 months beyond entry date (recommended)','fee'=>'Free','processing_time'=>'Instant at border','official_url'=>null,'arrival_card'=>'Check on arrival','docs'=>"• Valid passport (6 months validity recommended)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds",'requirements'=>"Australian passport holders can enter {$name} visa-free for short-term tourism or business visits. Duration of stay varies — check with the embassy or consulate for the exact permitted period. Your passport should have at least 6 months validity. Carry proof of return travel, accommodation, and sufficient funds."];
                        break;
                    case 'evisa':
                        $d = ['duration'=>'30-90 days (varies by visa type)','passport_validity'=>'6 months beyond entry date','fee'=>'Varies — check official website','processing_time'=>'3-7 business days','official_url'=>null,'arrival_card'=>'Check requirements','docs'=>"• Valid passport (6 months validity)\n• Digital passport photo\n• Completed online application\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Travel insurance (recommended)\n• Payment by credit/debit card",'requirements'=>"Australian passport holders must apply for an electronic visa (eVisa) before traveling to {$name}. Apply online through the official government portal at least 7 business days before your departure. Your passport must be valid for at least 6 months. Print your approved eVisa and present it with your passport upon arrival."];
                        break;
                    case 'visa_on_arrival':
                        $d = ['duration'=>'15-30 days (varies)','passport_validity'=>'6 months beyond entry date','fee'=>'Varies — bring USD cash','processing_time'=>'15-30 minutes at immigration','official_url'=>null,'arrival_card'=>'Yes','docs'=>"• Valid passport (6 months validity, 2 blank pages)\n• Passport photo\n• Visa fee in cash (USD recommended)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds",'requirements'=>"Australian passport holders can obtain a visa on arrival at {$name} international airports and designated border crossings. Bring a passport photo, visa fee in cash (USD is widely accepted), return ticket, and accommodation proof. Your passport must be valid for at least 6 months."];
                        break;
                    default: // visa_required
                        $d = ['duration'=>'30-90 days (varies by visa type)','passport_validity'=>'6 months beyond intended stay','fee'=>'Varies — check embassy website','processing_time'=>'5-20 business days','official_url'=>null,'arrival_card'=>'Yes','docs'=>"• Valid passport (6 months validity, 2 blank pages)\n• Completed visa application form\n• Recent passport photos (2)\n• Full travel itinerary with flight bookings\n• Hotel/accommodation proof for entire stay\n• Bank statements (last 3 months)\n• Travel insurance\n• Employment letter or proof of income\n• Visa fee payment",'requirements'=>"Australian passport holders must obtain a visa before traveling to {$name}. Contact the nearest embassy or consulate and apply at least 20 business days before your intended departure. Your passport must be valid for at least 6 months beyond your intended stay with 2 blank pages."];
                        break;
                }
                $templateCount++;
            }

            $updateStmt->execute([
                $d['duration'], $d['passport_validity'], $d['fee'],
                $d['processing_time'], $d['official_url'], $d['arrival_card'],
                $d['docs'], $d['requirements'], $code
            ]);
        }

        logMsg("  {$overrideCount} countries with specific data", 'ok');
        logMsg("  {$templateCount} countries with improved templates", 'ok');
        logMsg("Phase 2 complete: " . ($overrideCount + $templateCount) . " countries processed", 'ok');

        // ════════════════════════════════════════════════
        // PHASE 3: Fix URLs
        // ════════════════════════════════════════════════
        logMsg("", 'info');
        logMsg("═══ PHASE 3: Fixing URLs ═══", 'info');

        $urlFix = $pdo->prepare("
            UPDATE country_translations ct
            JOIN countries c ON ct.country_id = c.id
            SET ct.official_visa_url = c.official_url
            WHERE ct.lang_code = 'en'
            AND (ct.official_visa_url IS NULL OR ct.official_visa_url = ''
                 OR ct.official_visa_url LIKE '%www.gov.___/%'
                 OR ct.official_visa_url LIKE '%www.immigration.___.%'
                 OR ct.official_visa_url LIKE 'https://evisa.___.gov%')
            AND c.official_url IS NOT NULL AND c.official_url != ''
        ");
        $urlFix->execute();
        $urlsFixed = $urlFix->rowCount();
        logMsg("  Copied real URLs for {$urlsFixed} countries", 'ok');
        logMsg("Phase 3 complete", 'ok');

        // Commit
        $pdo->commit();
        logMsg("", 'info');
        logMsg("════════════════════════════════════════", 'ok');
        logMsg("✅ ALL CHANGES COMMITTED SUCCESSFULLY", 'ok');
        logMsg("════════════════════════════════════════", 'ok');

        // Verification
        logMsg("", 'info');
        logMsg("═══ POST-FIX VERIFICATION ═══", 'info');
        
        $postTypes = $pdo->query("SELECT visa_type, COUNT(*) as cnt FROM countries WHERE is_active = 1 GROUP BY visa_type ORDER BY cnt DESC")->fetchAll();
        foreach ($postTypes as $row) {
            logMsg("  {$row['visa_type']}: {$row['cnt']} countries", 'info');
        }
        
        $nullUrls = $pdo->query("SELECT COUNT(*) as cnt FROM country_translations ct JOIN countries c ON ct.country_id = c.id WHERE ct.lang_code = 'en' AND c.is_active = 1 AND (ct.official_visa_url IS NULL OR ct.official_visa_url = '')")->fetch()['cnt'];
        logMsg("  Missing URLs: {$nullUrls}", $nullUrls > 0 ? 'warn' : 'ok');
        
        $spotCheck = $pdo->query("
            SELECT c.country_code, c.visa_type, ct.visa_duration, LEFT(ct.official_visa_url, 50) as url
            FROM countries c JOIN country_translations ct ON c.id = ct.country_id
            WHERE ct.lang_code = 'en' AND c.country_code IN ('FRA','DEU','GBR','USA','JPN','THA','NZL','CHN')
            ORDER BY c.country_code
        ")->fetchAll();
        
        logMsg("", 'info');
        logMsg("═══ SPOT CHECK ═══", 'info');

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $errors++;
        logMsg("", 'err');
        logMsg("❌ ERROR — ALL CHANGES ROLLED BACK", 'err');
        logMsg($e->getMessage(), 'err');
    }
    
    ob_end_clean();
    ?>

    <div class="card <?= $errors > 0 ? 'error' : 'success' ?>">
        <h2><?= $errors > 0 ? '❌ Fix Failed — Changes Rolled Back' : '✅ All Fixes Applied Successfully' ?></h2>
    </div>

    <?php if ($errors === 0): ?>
    <div class="stats">
        <div class="stat">
            <div class="number"><?= $phase1Count ?></div>
            <div class="label">visa_type Fixed</div>
        </div>
        <div class="stat">
            <div class="number"><?= $overrideCount ?></div>
            <div class="label">Specific Overrides</div>
        </div>
        <div class="stat">
            <div class="number"><?= $templateCount ?></div>
            <div class="label">Template Updates</div>
        </div>
        <div class="stat">
            <div class="number"><?= $urlsFixed ?></div>
            <div class="label">URLs Fixed</div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <h2>Execution Log</h2>
        <div class="log"><?php
            foreach ($log as $entry) {
                $cls = $entry['type'];
                echo '<span class="' . $cls . '">' . htmlspecialchars($entry['msg']) . "</span>\n";
            }
        ?></div>
    </div>

    <?php if ($errors === 0 && !empty($spotCheck)): ?>
    <div class="card">
        <h2>Spot Check — Key Countries</h2>
        <table>
            <tr><th>Code</th><th>Type</th><th>Duration</th><th>URL</th></tr>
            <?php foreach ($spotCheck as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['country_code']) ?></td>
                <td><span class="badge badge-green"><?= htmlspecialchars($row['visa_type']) ?></span></td>
                <td><?= htmlspecialchars($row['visa_duration']) ?></td>
                <td style="font-size:11px;"><?= htmlspecialchars($row['url']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>

    <div class="delete-reminder">
        <strong>⚠️ IMPORTANT: Delete this file from your server NOW!</strong><br>
        Remove <code>run_visa_fix.php</code> from public_html/ to prevent unauthorized access.
    </div>

<?php endif; ?>

</div>
</body>
</html>
