<?php
/**
 * FIX ALL VISA DATA - Australian Passport Holder Perspective
 * ==========================================================
 * 
 * Fixes three critical issues:
 * 1. Corrects visa_type for all countries (Australian passport holders)
 * 2. Regenerates visa text with 80+ country-specific overrides
 * 3. Replaces fake template URLs with real official_url from countries table
 *
 * Run: php fix_visa_data_australian.php
 */

require 'includes/config.php';

echo "================================================================\n";
echo "  FIX VISA DATA - Australian Passport Holder Perspective\n";
echo "================================================================\n\n";

// ============================================================
// PHASE 1: Fix visa_type for all countries
// ============================================================
echo "PHASE 1: Correcting visa_type for Australian passport holders...\n";
echo "----------------------------------------------------------------\n";

// Correct visa_type mapping for Australian passport holders (ISO alpha-3)
$visaTypeCorrections = [
    // ── VISA FREE ─────────────────────────────────────────────
    'visa_free' => [
        // Schengen Area (27 members)
        'AUT','BEL','CZE','DNK','EST','FIN','FRA','DEU','GRC','HUN',
        'ISL','ITA','LVA','LTU','LUX','MLT','NLD','NOR','POL','PRT',
        'SVK','SVN','ESP','SWE','CHE','HRV','LIE',
        // Other Europe
        'GBR','IRL','ROU','BGR','CYP','SRB','ALB','MNE','MKD','BIH',
        'GEO','MDA','UKR','XKX','MCO','SMR','AND','VAT',
        // Asia-Pacific
        'JPN','KOR','HKG','MAC','SGP','MYS','THA','IDN','PHL','VNM',
        'BRN','MNG','TWN','KAZ','KGZ',
        // Americas
        'MEX','BRA','ARG','CHL','COL','PER','ECU','URY','CRI','PAN',
        'GTM','HND','NIC','SLV','BLZ','DOM','JAM','TTO','BHS','BRB',
        'ATG','DMA','GRD','KNA','LCA','VCT','GUY','SUR','PRY',
        // Middle East
        'ARE','ISR','TUR','QAT',
        // Africa
        'ZAF','MAR','TUN','MUS','SYC','BWA','NAM','SEN','SWZ','LSO',
        'BEN','GAB','GMB','RWA',
        // Oceania
        'NZL','FJI','VUT','WSM','TON','SLB','KIR','MHL','FSM','TUV',
        'NRU','COK','NIU',
    ],
    // ── E-VISA ────────────────────────────────────────────────
    'evisa' => [
        'USA','CAN','AUS',
        'IND','LKA','MMR','PAK','UZB','TJK','AZE',
        'KEN','ETH','UGA','ZMB','CIV','CMR','GHA','NGA','MOZ','ZWE',
        'TZA','BFA','GNB','AGO','COG','COD','GIN','MLI','NER','TCD',
        'CAF','SSD','SLE','LBR',
        'SAU','OMN','KWT','BHR',
    ],
    // ── VISA ON ARRIVAL ───────────────────────────────────────
    'visa_on_arrival' => [
        'KHM','LAO','NPL','TLS','MDV',
        'JOR','LBN','IRN',
        'EGY','MDG','PLW','PNG','BOL','CUB',
        'CPV','COM','DJI','MRT','TGO','SOM',
    ],
    // ── VISA REQUIRED ─────────────────────────────────────────
    'visa_required' => [
        'CHN','RUS','PRK',
        'AFG','IRQ','SYR','YEM',
        'TKM','BTN',
        'LBY','SDN','ERI','DZA',
        'BLR',
    ],
];

$updateVisaType = $pdo->prepare("UPDATE countries SET visa_type = ? WHERE country_code = ?");
$phase1Count = 0;
$phase1Errors = 0;

foreach ($visaTypeCorrections as $correctType => $codes) {
    foreach ($codes as $code) {
        $updateVisaType->execute([$correctType, $code]);
        if ($updateVisaType->rowCount() > 0) {
            $phase1Count++;
        }
    }
}

echo "  ✓ Updated $phase1Count countries' visa_type\n\n";

// ============================================================
// PHASE 2: Regenerate visa data with comprehensive overrides
// ============================================================
echo "PHASE 2: Regenerating visa data with 80+ country overrides...\n";
echo "----------------------------------------------------------------\n";

// Fetch all countries fresh (with corrected visa_type)
$stmt = $pdo->query("
    SELECT c.id, c.country_code, c.visa_type, c.official_url,
           ct.country_name
    FROM countries c
    JOIN country_translations ct ON c.id = ct.country_id
    WHERE ct.lang_code = 'en' AND c.is_active = 1
    ORDER BY c.country_code
");
$allCountries = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "  Found " . count($allCountries) . " countries to process\n\n";

// ── HELPER FUNCTIONS ──────────────────────────────────────────

/**
 * Generate Schengen Area visa data for Australian passport holders
 */
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

/**
 * Generate visa-free country data (non-Schengen)
 */
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

// ── COMPREHENSIVE COUNTRY OVERRIDES ──────────────────────────
// 80+ countries with researched, specific data

$countryOverrides = [

    // ════════════════════════════════════════════════════════════
    // SCHENGEN AREA (27 countries)
    // ════════════════════════════════════════════════════════════
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

    // ════════════════════════════════════════════════════════════
    // OTHER EUROPE
    // ════════════════════════════════════════════════════════════
    'GBR' => [
        'duration' => '6 months',
        'passport_validity' => 'Valid for duration of stay',
        'fee' => 'Free',
        'processing_time' => 'Instant at border',
        'official_url' => 'https://www.gov.uk/check-uk-visa',
        'arrival_card' => 'No',
        'docs' => "• Valid Australian passport\n• Return/onward ticket\n• Proof of accommodation\n• Evidence of sufficient funds\n• Details of your visit (business contacts, tour bookings, etc.)",
        'requirements' => "Australian passport holders can visit the United Kingdom visa-free for up to 6 months for tourism, business meetings, or family visits. Your passport must be valid for the duration of your stay. No advance application required — your visa status is determined at the UK border. Bring proof of your return or onward journey, accommodation details, and evidence of sufficient funds. No arrival card is required. If you plan to work, study, or stay longer than 6 months, you will need to apply for the appropriate visa."
    ],
    'IRL' => [
        'duration' => '90 days',
        'passport_validity' => 'Valid for duration of stay',
        'fee' => 'Free',
        'processing_time' => 'Instant at border',
        'official_url' => 'https://www.irishimmigration.ie/',
        'arrival_card' => 'No',
        'docs' => "• Valid Australian passport\n• Return/onward ticket\n• Proof of accommodation\n• Evidence of sufficient funds",
        'requirements' => "Australian passport holders can visit Ireland visa-free for up to 90 days for tourism or business. Your passport must be valid for the duration of your stay. No advance application required. Present your return ticket, accommodation details, and evidence of sufficient funds at immigration. Note: Ireland is NOT part of the Schengen Area — time spent in Ireland does not count toward Schengen 90-day limits, and a Schengen visa does not cover Ireland."
    ],
    'TUR' => [
        'duration' => '90 days within 180-day period',
        'passport_validity' => '6 months beyond entry date',
        'fee' => 'Free',
        'processing_time' => 'Instant at border',
        'official_url' => 'https://www.evisa.gov.tr/en/',
        'arrival_card' => 'No',
        'docs' => "• Valid passport (6 months validity)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds",
        'requirements' => "Australian passport holders can enter Turkey visa-free for up to 90 days within a 180-day period for tourism or business. Your passport must be valid for at least 6 months from your date of entry. No eVisa or advance application is required. Present your return ticket, accommodation proof, and evidence of sufficient funds at immigration."
    ],
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

    // ════════════════════════════════════════════════════════════
    // AMERICAS
    // ════════════════════════════════════════════════════════════
    'USA' => [
        'duration' => '90 days (Visa Waiver Program)',
        'passport_validity' => 'Valid for duration of stay (e-Passport required)',
        'fee' => 'ESTA: $21 USD',
        'processing_time' => 'ESTA: Usually instant, up to 72 hours',
        'official_url' => 'https://esta.cbp.dhs.gov',
        'arrival_card' => 'No (ESTA replaces arrival card)',
        'docs' => "• Valid e-Passport (electronic passport with chip)\n• Approved ESTA ($21 USD)\n• Return/onward ticket\n• Proof of accommodation\n• Evidence of sufficient funds",
        'requirements' => "Australian passport holders can travel to the United States visa-free under the Visa Waiver Program (VWP) for stays up to 90 days for tourism or business. You must obtain an Electronic System for Travel Authorization (ESTA) online at https://esta.cbp.dhs.gov before departure — ideally at least 72 hours in advance. The ESTA costs $21 USD and is valid for 2 years or until your passport expires. Your passport must be an e-Passport (with electronic chip). Present your ESTA approval, return ticket, and proof of accommodation upon arrival. The 90-day stay cannot be extended."
    ],
    'CAN' => [
        'duration' => '6 months',
        'passport_validity' => 'Valid for duration of stay',
        'fee' => 'eTA: $7 CAD',
        'processing_time' => 'eTA: Usually instant, up to 72 hours',
        'official_url' => 'https://www.canada.ca/en/immigration-refugees-citizenship/services/visit-canada/eta.html',
        'arrival_card' => 'No',
        'docs' => "• Valid passport\n• Approved eTA ($7 CAD)\n• Return/onward ticket\n• Proof of sufficient funds\n• Letter of invitation (if visiting family/friends)",
        'requirements' => "Australian passport holders need an Electronic Travel Authorization (eTA) to fly to Canada. The eTA costs $7 CAD and is applied for online. It is usually approved within minutes but can take up to 72 hours. Once approved, your eTA is valid for 5 years or until your passport expires. You can stay in Canada for up to 6 months per visit. Present your eTA confirmation with your passport at check-in and at the Canadian border. If arriving by land from the US, no eTA is needed."
    ],
    'AUS' => [
        'duration' => 'N/A — Home country',
        'passport_validity' => 'N/A',
        'fee' => 'N/A',
        'processing_time' => 'N/A',
        'official_url' => 'https://www.homeaffairs.gov.au',
        'arrival_card' => 'Yes (Incoming Passenger Card)',
        'docs' => "• Valid Australian passport",
        'requirements' => "As an Australian passport holder, you have the automatic right of entry into Australia. No visa is required. Your passport must be valid. You will need to complete an Incoming Passenger Card upon arrival, which collects customs and immigration information. SmartGate (automated passport control) is available at major airports."
    ],
    'MEX' => visaFreeData('Mexico', '180 days', 'https://www.inm.gob.mx/', 'valid for duration of stay', "• Completed FMM (Forma Migratoria Múltiple) — available at airport", "Keep your FMM form safe — you must surrender it when departing Mexico. Do not lose it, as replacement involves fees and delays. For stays over 180 days, apply for a Temporary Resident visa."),
    'BRA' => visaFreeData('Brazil', '90 days within 180-day period', 'https://www.gov.br/mre/en/subjects/visas', 'valid for 6+ months', '', 'Australian passport holders have had visa-free access to Brazil since 2024. You may extend your stay by an additional 90 days at a Federal Police office.'),
    'ARG' => visaFreeData('Argentina', '90 days', 'https://www.argentina.gob.ar/interior/migraciones', 'valid for duration of stay', '', 'Extensions of 90 days are possible at the immigration office (Dirección Nacional de Migraciones). You may also do a border run and re-enter for another 90 days.'),
    'CHL' => visaFreeData('Chile', '90 days', 'https://www.chile.gob.cl/chile/en/travel-chile', 'valid for duration of stay', "• Completed PDI immigration form", "A Tarjeta de Turismo (tourist card) will be issued electronically at entry — it's linked to your passport number. Extensions are available at the PDI (Policía de Investigaciones)."),
    'COL' => visaFreeData('Colombia', '90 days (extendable to 180)', 'https://www.cancilleria.gov.co/en/procedures_services/visas', 'valid for duration of stay', '', 'Your initial 90-day stay can be extended once for an additional 90 days at a Migración Colombia office. Complete the Check-Mig online form within 72 hours before arrival.'),
    'PER' => visaFreeData('Peru', '183 days', 'https://www.migraciones.gob.pe/', 'valid for 6+ months', '', 'The immigration officer determines your permitted stay (usually 90-183 days). Extensions are available at Migraciones offices.'),
    'ECU' => visaFreeData('Ecuador', '90 days within calendar year', 'https://www.cancilleria.gob.ec/', 'valid for 6+ months', '', 'You may stay up to 90 days within any calendar year without a visa. For longer stays, apply for a special visa.'),
    'CRI' => visaFreeData('Costa Rica', '90 days', 'https://www.migracion.go.cr/', 'valid for duration of stay (6 months recommended)', '', 'You may need to show a return ticket and proof of funds ($100 per month of intended stay). Extensions are not commonly granted — most travelers do a border run.'),
    'PAN' => visaFreeData('Panama', '180 days', 'https://www.migracion.gob.pa/', 'valid for 3+ months beyond entry', '', 'Present your return ticket (within 180 days) and proof of funds ($500 minimum or credit card). An automatic 180-day tourist stamp is given at entry.'),

    // ════════════════════════════════════════════════════════════
    // ASIA-PACIFIC
    // ════════════════════════════════════════════════════════════
    'JPN' => [
        'duration' => '90 days',
        'passport_validity' => 'Valid for duration of stay',
        'fee' => 'Free',
        'processing_time' => 'Instant at immigration',
        'official_url' => 'https://www.mofa.go.jp/j_info/visit/visa/',
        'arrival_card' => 'Yes (ED card — may be completed digitally via Visit Japan Web)',
        'docs' => "• Valid passport\n• Completed disembarkation card (or Visit Japan Web registration)\n• Return/onward ticket\n• Proof of accommodation\n• Sufficient funds",
        'requirements' => "Australian passport holders can enter Japan visa-free for up to 90 days for tourism or business. Your passport must be valid for the duration of your stay. Register at Visit Japan Web (https://vjw-lp.digital.go.jp/en/) before arrival to expedite immigration and customs procedures. Alternatively, complete the paper disembarkation card on your flight. Present your return ticket, accommodation details, and proof of sufficient funds at immigration. Fingerprints and photo are taken at entry."
    ],
    'KOR' => [
        'duration' => '90 days',
        'passport_validity' => 'Valid for duration of stay',
        'fee' => 'Free',
        'processing_time' => 'Instant at immigration',
        'official_url' => 'https://www.immigration.go.kr/immigration_eng/index.do',
        'arrival_card' => 'Yes (arrival card at immigration)',
        'docs' => "• Valid passport\n• Completed arrival card\n• Return/onward ticket\n• Proof of accommodation",
        'requirements' => "Australian passport holders can enter South Korea visa-free for up to 90 days for tourism or business. K-ETA (Korea Electronic Travel Authorization) has been suspended for Australian nationals — you do not need to apply. Simply present your valid passport, completed arrival card, return ticket, and accommodation proof at immigration. Your passport must be valid for the duration of your stay."
    ],
    'HKG' => visaFreeData('Hong Kong', '90 days', 'https://www.immd.gov.hk/eng/services/visas/visit-transit/visit-visa-entry-permit.html', 'valid for 1+ months beyond intended stay', "• Completed arrival card (or pre-register online)", "Hong Kong is a Special Administrative Region of China — visa rules are separate from mainland China. Your 90-day Hong Kong visa-free stay does not affect your ability to travel to mainland China (which requires a separate visa)."),
    'SGP' => [
        'duration' => '90 days',
        'passport_validity' => '6 months beyond entry date',
        'fee' => 'Free',
        'processing_time' => 'Instant at immigration',
        'official_url' => 'https://www.ica.gov.sg/enter-transit-depart/entering-singapore',
        'arrival_card' => 'Yes (SG Arrival Card — submit online before arrival)',
        'docs' => "• Valid passport (6 months validity)\n• Completed SG Arrival Card (submitted online within 3 days before arrival)\n• Return/onward ticket\n• Proof of accommodation\n• Sufficient funds",
        'requirements' => "Australian passport holders can visit Singapore visa-free for up to 90 days for tourism or business. Your passport must be valid for at least 6 months. Submit an SG Arrival Card online at https://eservices.ica.gov.sg/sgarrivalcard within 3 days before arrival. Save your acknowledgement receipt. Present this with your passport, return/onward ticket, accommodation proof, and evidence of sufficient funds at immigration."
    ],
    'MYS' => [
        'duration' => '90 days',
        'passport_validity' => '6 months beyond entry date',
        'fee' => 'Free',
        'processing_time' => 'Instant at immigration',
        'official_url' => 'https://www.imi.gov.my',
        'arrival_card' => 'Yes (Malaysia Digital Arrival Card — submit online)',
        'docs' => "• Valid passport (6 months validity)\n• Malaysia Digital Arrival Card (MDAC) submitted online within 3 days before arrival\n• Return/onward ticket\n• Proof of accommodation\n• Sufficient funds",
        'requirements' => "Australian passport holders can visit Malaysia visa-free for up to 90 days for tourism or business. Your passport must be valid for at least 6 months. Complete the Malaysia Digital Arrival Card (MDAC) at https://imigresen-online.imi.gov.my/mdac within 3 days before arrival and save your QR code. Present your MDAC QR code, passport, return/onward ticket, and accommodation proof at immigration."
    ],
    'THA' => [
        'duration' => '60 days (air) / 30 days (land)',
        'passport_validity' => '6 months beyond entry date',
        'fee' => 'Free',
        'processing_time' => 'Instant at immigration',
        'official_url' => 'https://www.immigration.go.th',
        'arrival_card' => 'Yes (TM.6 departure card)',
        'docs' => "• Valid passport (6 months validity)\n• TM.6 arrival/departure card\n• Return/onward ticket within permitted stay\n• Proof of accommodation\n• Proof of funds (10,000 THB/person or 20,000 THB/family)",
        'requirements' => "Australian passport holders can enter Thailand visa-free for up to 60 days when arriving by air (or 30 days by land border). Your passport must be valid for at least 6 months. Complete a TM.6 departure card at immigration. You must have proof of onward travel, accommodation confirmation, and evidence of funds (10,000 THB per person or 20,000 THB per family). Extensions of 30 days are available at immigration offices for 1,900 THB."
    ],
    'IDN' => [
        'duration' => '30 days (visa-free) or 30 days extendable (VOA)',
        'passport_validity' => '6 months beyond entry date',
        'fee' => 'Free (visa-free) or $35 USD (Visa on Arrival, extendable)',
        'processing_time' => 'Instant at immigration',
        'official_url' => 'https://www.imigrasi.go.id',
        'arrival_card' => 'Yes (customs declaration form)',
        'docs' => "• Valid passport (6 months validity, 2 blank pages)\n• Return/onward ticket within 30 days\n• Proof of accommodation\n• Customs declaration form",
        'requirements' => "Australian passport holders have two options for Indonesia: (1) Visa-free entry for 30 days at designated airports and seaports — free but NOT extendable and cannot be converted; (2) Visa on Arrival (VOA) for 30 days at $35 USD — extendable once for 30 more days at an immigration office. If you think you may want to stay longer than 30 days, choose the VOA option. Your passport must be valid for 6 months with 2 blank pages. Present your return ticket and accommodation proof."
    ],
    'PHL' => [
        'duration' => '30 days',
        'passport_validity' => '6 months beyond entry date',
        'fee' => 'Free',
        'processing_time' => 'Instant at immigration',
        'official_url' => 'https://www.immigration.gov.ph',
        'arrival_card' => 'Yes (eTravel — submit online before departure)',
        'docs' => "• Valid passport (6 months validity)\n• eTravel registration (submitted within 72 hours of departure)\n• Return/onward ticket within 30 days\n• Proof of accommodation",
        'requirements' => "Australian passport holders can enter the Philippines visa-free for up to 30 days. Register via the eTravel system at https://etravel.gov.ph within 72 hours before departure and save your QR code. Your passport must be valid for at least 6 months. Present your eTravel QR code, passport, return ticket, and accommodation proof at immigration. Extensions up to 36 months total are available at Bureau of Immigration offices."
    ],
    'VNM' => [
        'duration' => '45 days (visa-free, single entry)',
        'passport_validity' => '6 months beyond entry date',
        'fee' => 'Free (visa-free) or $25 USD (eVisa for 90 days)',
        'processing_time' => 'Instant at border (eVisa: 3 business days)',
        'official_url' => 'https://evisa.xuatnhapcanh.gov.vn',
        'arrival_card' => 'No',
        'docs' => "• Valid passport (6 months validity)\n• Return/onward ticket\n• Proof of accommodation\n• eVisa printout (if staying beyond 45 days or needing multiple entries)",
        'requirements' => "Australian passport holders can enter Vietnam visa-free for up to 45 days (single entry only). For stays beyond 45 days or if you need multiple entries, apply for an eVisa online ($25 USD, valid 90 days, single or multiple entry) at least 3 business days before departure. Your passport must be valid for 6 months. The 45-day visa-free period restarts each time you enter, but there is no mandatory gap between visits."
    ],
    'IND' => [
        'duration' => '30-90 days (eVisa)',
        'passport_validity' => '6 months beyond entry date with 2 blank pages',
        'fee' => '$25-100 USD depending on duration',
        'processing_time' => '1-4 business days',
        'official_url' => 'https://indianvisaonline.gov.in',
        'arrival_card' => 'No',
        'docs' => "• Valid passport (6 months validity, 2 blank pages)\n• Digital passport photo (white background)\n• Scanned passport bio page\n• Return flight ticket\n• Proof of accommodation\n• Payment by credit/debit card",
        'requirements' => "Australian passport holders must apply for an eVisa before traveling to India. Tourist eVisas are available for: 30 days (double entry, $25 USD), 1 year (multiple entry, $40 USD), or 5 years (multiple entry, $80 USD). Apply at https://indianvisaonline.gov.in at least 4 business days before departure. Upload a digital passport photo and scanned passport page. Print your approved eVisa and present it upon arrival at designated Indian airports. eVisas cannot be extended — for longer stays, apply for a regular visa at the Indian embassy."
    ],
    'LKA' => [
        'duration' => '30 days (extendable to 90)',
        'passport_validity' => '6 months beyond entry date',
        'fee' => '$50 USD (ETA)',
        'processing_time' => '1-3 business days',
        'official_url' => 'https://www.eta.gov.lk',
        'arrival_card' => 'No',
        'docs' => "• Valid passport (6 months validity)\n• Approved ETA\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds ($50/day)",
        'requirements' => "Australian passport holders must obtain an Electronic Travel Authorization (ETA) before traveling to Sri Lanka. Apply online at https://www.eta.gov.lk. The tourist ETA costs $50 USD, is valid for 30 days, and allows double entry. Processing takes 1-3 business days. Extensions up to 90 days are available at the Department of Immigration in Colombo. Print your ETA approval and present it with your passport on arrival."
    ],
    'KHM' => [
        'duration' => '30 days',
        'passport_validity' => '6 months beyond entry date',
        'fee' => '$30 USD (eVisa) or $30 USD (Visa on Arrival)',
        'processing_time' => 'eVisa: 3 business days; VOA: 15-30 minutes',
        'official_url' => 'https://www.evisa.gov.kh/',
        'arrival_card' => 'Yes',
        'docs' => "• Valid passport (6 months validity, 1 blank page)\n• Passport-sized photo (4x6cm)\n• Visa fee: $30 USD cash (VOA) or online payment (eVisa)\n• Return/onward ticket\n• Proof of accommodation",
        'requirements' => "Australian passport holders can obtain a Cambodia visa either as an eVisa (apply at https://www.evisa.gov.kh, $30 USD + $6 processing fee, 3 business days) or as a Visa on Arrival at international airports and main land borders ($30 USD cash, bring a passport photo). Both allow a 30-day stay, extendable once for 30 days at an immigration office. Your passport must be valid for 6 months."
    ],
    'NPL' => [
        'duration' => '15, 30, or 90 days',
        'passport_validity' => '6 months beyond entry date',
        'fee' => '$30 USD (15 days), $50 USD (30 days), $125 USD (90 days)',
        'processing_time' => '15-30 minutes at airport',
        'official_url' => 'https://www.immigration.gov.np/',
        'arrival_card' => 'Yes',
        'docs' => "• Valid passport (6 months validity)\n• Passport photo\n• Visa fee in cash (USD)\n• Completed visa application form\n• Return/onward ticket",
        'requirements' => "Australian passport holders can obtain a Nepal visa on arrival at Tribhuvan International Airport (Kathmandu) and major land borders. Choose between 15-day ($30 USD), 30-day ($50 USD), or 90-day ($125 USD) tourist visas. Pay in cash (USD). Bring a passport photo. You can extend your visa at the Department of Immigration in Kathmandu for $3 USD/day. Maximum total stay is 150 days per calendar year."
    ],
    'MNG' => visaFreeData('Mongolia', '30 days', 'https://www.consul.mn/eng/index.php?moduls=23', '6 months beyond entry date', '', 'For stays beyond 30 days, register with the Immigration Agency of Mongolia within 7 days of arrival.'),
    'NZL' => [
        'duration' => 'Indefinite (Australian citizens can live and work)',
        'passport_validity' => 'Valid for travel',
        'fee' => 'Free',
        'processing_time' => 'Instant at border',
        'official_url' => 'https://www.immigration.govt.nz/new-zealand-visas',
        'arrival_card' => 'Yes (NZTD — New Zealand Traveller Declaration, submitted online)',
        'docs' => "• Valid Australian passport\n• New Zealand Traveller Declaration (submitted online before arrival)",
        'requirements' => "Australian citizens receive a Resident Visa on arrival in New Zealand under the Trans-Tasman Travel Arrangement. You can live, work, and study indefinitely — no advance application is needed. Complete the New Zealand Traveller Declaration (NZTD) online at https://www.travellerdeclaration.govt.nz before your flight. Your passport must be valid for travel. You'll have full rights to remain in New Zealand for as long as you wish."
    ],
    'FJI' => visaFreeData('Fiji', '4 months', 'https://www.immigration.gov.fj/', 'valid for 6+ months beyond intended stay', "• Completed arrival card\n• Proof of yellow fever vaccination (if arriving from infected area)", "Extensions up to 6 months total are available at a Fiji immigration office. Fiji is a popular transit point for Pacific island destinations."),
    'PNG' => [
        'duration' => '60 days',
        'passport_validity' => '6 months beyond entry date',
        'fee' => 'Free (Visa on Arrival)',
        'processing_time' => '15-30 minutes at airport',
        'official_url' => 'https://www.ica.gov.pg/',
        'arrival_card' => 'Yes',
        'docs' => "• Valid passport (6 months validity)\n• Return/onward ticket\n• Proof of accommodation\n• Sufficient funds",
        'requirements' => "Australian passport holders can obtain a free Visa on Arrival at Port Moresby Jacksons International Airport (and other designated entry points) for up to 60 days. Your passport must be valid for at least 6 months. Present your return ticket, accommodation details, and proof of sufficient funds. Extensions are available at the PNG Immigration office."
    ],
    'BRN' => visaFreeData('Brunei', '90 days', 'https://www.immigration.gov.bn/', '6 months beyond entry date'),
    'KAZ' => visaFreeData('Kazakhstan', '30 days', 'https://www.gov.kz/memleket/entities/mfa/press/article/details/211611?lang=en', '6 months beyond entry date', '', 'For stays beyond 30 days, you must register with the migration police within 3 business days any time you change location.'),
    'KGZ' => visaFreeData('Kyrgyzstan', '60 days', 'https://evisa.e-gov.kg/get_information.php', '6 months beyond entry date', '', 'For stays beyond 60 days, apply for a visa extension at the State Registration Service in Bishkek.'),

    // ════════════════════════════════════════════════════════════
    // MIDDLE EAST
    // ════════════════════════════════════════════════════════════
    'ARE' => [
        'duration' => '60 days (free visa on arrival)',
        'passport_validity' => '6 months beyond entry date',
        'fee' => 'Free',
        'processing_time' => '5-10 minutes at airport',
        'official_url' => 'https://www.icp.gov.ae',
        'arrival_card' => 'No',
        'docs' => "• Valid passport (6 months validity)\n• Return/onward ticket\n• Hotel reservation\n• Proof of sufficient funds",
        'requirements' => "Australian passport holders receive a free 60-day visa stamp on arrival at UAE airports. Your passport must be valid for at least 6 months. The visa is free of charge and allows a 60-day stay. Present your return ticket, hotel reservation, and proof of sufficient funds at immigration. This visa can be extended for an additional 30 days by visiting a GDRFA service center (fee applies). No advance application required."
    ],
    'ISR' => visaFreeData('Israel', '90 days', 'https://www.gov.il/en/departments/topics/visa-information', '6 months beyond entry date', "• Proof of travel insurance", "An electronic entry card is issued at the airport. Keep it safe — you'll need it for hotel check-ins. Israeli entry/exit stamps are issued electronically and don't appear in your passport."),
    'JOR' => [
        'duration' => '30 days (single entry) or stay duration with Jordan Pass',
        'passport_validity' => '6 months beyond entry date',
        'fee' => '$56-113 USD (visa) or included in Jordan Pass ($70-80 USD)',
        'processing_time' => '10-15 minutes at airport',
        'official_url' => 'https://www.jordanpass.jo/',
        'arrival_card' => 'Yes',
        'docs' => "• Valid passport (6 months validity)\n• Jordan Pass (recommended — includes visa fee + Petra entry) OR visa fee in cash\n• Return/onward ticket\n• Proof of accommodation",
        'requirements' => "Australian passport holders can obtain a Jordan visa on arrival at Queen Alia International Airport. The single-entry visa costs 40 JOD (~$56 USD). Strongly recommended: purchase a Jordan Pass at https://www.jordanpass.jo before arrival ($70-80 USD) — it includes the visa fee AND entry to Petra and 40+ attractions, making it excellent value. You must stay at least 3 consecutive nights in Jordan for the Jordan Pass visa waiver to apply."
    ],
    'QAT' => visaFreeData('Qatar', '90 days', 'https://portal.moi.gov.qa/wps/portal/MOIInternet/departmentcommissioner/visas/', '6 months beyond entry date', '', 'Qatar grants visa-free entry for Australian passport holders for up to 90 days. No advance application required.'),
    'SAU' => [
        'duration' => '90 days within 1-year period (multiple entry)',
        'passport_validity' => '6 months beyond entry date',
        'fee' => '$120 USD (eVisa)',
        'processing_time' => '1-5 business days',
        'official_url' => 'https://visa.visitsaudi.com/',
        'arrival_card' => 'No',
        'docs' => "• Valid passport (6 months validity)\n• Digital passport photo\n• Completed eVisa application\n• Return flight ticket\n• Proof of accommodation\n• Travel insurance\n• Payment by credit card",
        'requirements' => "Australian passport holders can apply for a Saudi tourist eVisa online at https://visa.visitsaudi.com. The eVisa costs approximately $120 USD (including insurance), is valid for 1 year with multiple entries, and allows stays of up to 90 days per visit. Processing usually takes 1-5 business days. Your passport must be valid for 6 months. Women can travel independently. Dress modestly. Alcohol is prohibited."
    ],
    'OMN' => [
        'duration' => '14 days (free) or 30 days (paid eVisa)',
        'passport_validity' => '6 months beyond entry date',
        'fee' => 'Free (14-day) or 20 OMR / ~$52 USD (30-day eVisa)',
        'processing_time' => '1-3 business days',
        'official_url' => 'https://evisa.rop.gov.om/',
        'arrival_card' => 'No',
        'docs' => "• Valid passport (6 months validity)\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Travel insurance",
        'requirements' => "Australian passport holders have two options: (1) Free 14-day tourist visa on arrival, or (2) 30-day eVisa for 20 OMR (~$52 USD) applied at https://evisa.rop.gov.om. For the eVisa, apply at least 3 business days before travel. Your passport must be valid for 6 months. Both options require a return ticket, accommodation proof, and travel insurance."
    ],
    'EGY' => [
        'duration' => '30 days (single entry)',
        'passport_validity' => '6 months beyond entry date',
        'fee' => '$25 USD',
        'processing_time' => 'Visa on Arrival: 10-15 min; eVisa: 5-7 business days',
        'official_url' => 'https://visa2egypt.gov.eg/',
        'arrival_card' => 'Yes',
        'docs' => "• Valid passport (6 months validity)\n• Passport photo\n• Visa fee: $25 USD (cash for VOA; card for eVisa)\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds",
        'requirements' => "Australian passport holders can obtain an Egypt visa either on arrival at Egyptian airports ($25 USD, paid at bank kiosk before immigration) or as an eVisa at https://visa2egypt.gov.eg ($25 USD, 5-7 business days processing). Both give a 30-day single-entry visa. Your passport must be valid for 6 months. The eVisa option avoids queues at the airport. Extensions are possible at the Mogamma building in Cairo."
    ],

    // ════════════════════════════════════════════════════════════
    // AFRICA
    // ════════════════════════════════════════════════════════════
    'ZAF' => visaFreeData('South Africa', '90 days', 'http://www.dha.gov.za/', '30 days beyond intended departure with 2 blank pages', "• Proof of yellow fever vaccination (if arriving from endemic area)\n• Return/onward ticket (mandatory)", "Your passport MUST have at least 2 blank pages for entry/exit stamps — South Africa is strict about this and will deny boarding if you don't have blank pages. A return ticket is mandatory."),
    'MAR' => visaFreeData('Morocco', '90 days', 'https://www.consulat.ma/en', '6 months beyond entry date', "• Completed arrival form (provided on flight or at border)", "Extensions beyond 90 days require applying at the local police station (Sûreté Nationale) before your 90 days expire."),
    'TUN' => visaFreeData('Tunisia', '90 days', 'https://www.diplomatie.gov.tn/en/', '3+ months beyond entry date'),
    'MUS' => visaFreeData('Mauritius', '60 days (extendable to 90)', 'https://passport.govmu.org/', 'valid for duration of stay', "• Completed disembarkation card\n• Proof of yellow fever vaccination (if arriving from endemic area)\n• Return ticket", "Mauritius is extremely popular with Australian tourists. Extensions to 90 days are available free of charge at the Passport and Immigration Office in Port Louis."),
    'KEN' => [
        'duration' => '90 days',
        'passport_validity' => '6 months beyond entry date',
        'fee' => '$50 USD (eTA)',
        'processing_time' => '3-5 business days',
        'official_url' => 'https://www.etakenya.go.ke/',
        'arrival_card' => 'No',
        'docs' => "• Valid passport (6 months validity, 2 blank pages)\n• Approved eTA\n• Yellow fever vaccination certificate\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds",
        'requirements' => "Australian passport holders must obtain an electronic Travel Authorization (eTA) before traveling to Kenya. Apply at https://www.etakenya.go.ke at least 5 business days before departure. The eTA costs $50 USD and allows a 90-day stay. A yellow fever vaccination certificate is MANDATORY. Your passport must be valid for 6 months with 2 blank pages. Print your eTA confirmation."
    ],
    'TZA' => [
        'duration' => '90 days',
        'passport_validity' => '6 months beyond entry date',
        'fee' => '$50 USD (eVisa)',
        'processing_time' => '5-10 business days',
        'official_url' => 'https://visa.immigration.go.tz/',
        'arrival_card' => 'Yes',
        'docs' => "• Valid passport (6 months validity, 2 blank pages)\n• Approved eVisa\n• Yellow fever vaccination certificate\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds ($500 minimum)",
        'requirements' => "Australian passport holders must obtain an eVisa before traveling to Tanzania. Apply at https://visa.immigration.go.tz at least 10 business days before departure. The tourist eVisa costs $50 USD. A yellow fever vaccination certificate is MANDATORY. Your passport must be valid for 6 months with 2 blank pages. Visas on arrival are no longer available for most nationalities."
    ],

    // ════════════════════════════════════════════════════════════
    // VISA REQUIRED - Key Countries
    // ════════════════════════════════════════════════════════════
    'CHN' => [
        'duration' => '30-90 days (depending on visa type)',
        'passport_validity' => '6 months beyond entry date with 2 blank pages',
        'fee' => '$109.50 AUD (tourist visa)',
        'processing_time' => '4-7 business days',
        'official_url' => 'https://www.visaforchina.cn',
        'arrival_card' => 'Yes',
        'docs' => "• Valid passport (6 months validity, 2 blank pages)\n• Completed visa application form (Form V.2013)\n• Recent passport photo (48x33mm, white background)\n• Round-trip flight booking\n• Hotel reservations for entire stay\n• Bank statement (last 3 months)\n• Travel insurance\n• Employment letter or proof of income\n• Invitation letter (if visiting friends/family)",
        'requirements' => "Australian passport holders must obtain a visa before traveling to China. Apply through a Chinese Visa Application Service Center (CVASC) in Sydney, Melbourne, Brisbane, or Perth at least 7 business days before departure. Tourist (L) visas are typically valid for 30 days (single entry) or up to 90 days (double/multiple entry). The fee is approximately $109.50 AUD. Submit your completed application, photo, flight and hotel bookings, bank statements, and employment letter. Note: Some Chinese cities offer 72-hour or 144-hour visa-free transit — check eligibility at your transit city."
    ],
    'RUS' => [
        'duration' => '30 days (tourist visa) or 16 days (eVisa where available)',
        'passport_validity' => '6 months beyond visa expiry',
        'fee' => '$50+ USD (tourist visa); eVisa $40 USD where available',
        'processing_time' => '10-20 business days (tourist visa); eVisa 4 days',
        'official_url' => 'https://electronic-visa.kdmid.ru/',
        'arrival_card' => 'Yes (migration card at border)',
        'docs' => "• Valid passport (6 months beyond visa expiry, 2 blank pages)\n• Completed visa application form\n• Passport photo\n• Letter of invitation from Russian tour operator/hotel\n• Travel insurance valid in Russia\n• Proof of accommodation\n• Visa fee payment",
        'requirements' => "Australian passport holders require a visa to visit Russia. Tourist visas (up to 30 days) require an invitation letter from a Russian hotel or tour operator. Apply at the Russian embassy/consulate at least 20 business days before travel. An eVisa (up to 16 days) may be available for certain entry points — check https://electronic-visa.kdmid.ru. Keep your migration card (issued at entry) safe — you must surrender it when leaving Russia. Check current travel advisories before planning travel."
    ],

    // ── Additional e-Visa countries ───────
    'MMR' => [
        'duration' => '28 days',
        'passport_validity' => '6 months beyond entry date',
        'fee' => '$50 USD (eVisa)',
        'processing_time' => '3-5 business days',
        'official_url' => 'https://evisa.moip.gov.mm/',
        'arrival_card' => 'Yes',
        'docs' => "• Valid passport (6 months validity)\n• Digital passport photo\n• Return flight ticket\n• Proof of accommodation\n• Payment by credit/debit card",
        'requirements' => "Australian passport holders must obtain an eVisa before traveling to Myanmar. Apply at https://evisa.moip.gov.mm at least 5 business days before departure. The tourist eVisa costs $50 USD and allows a 28-day stay. Entry is only through designated airports (Yangon, Mandalay, Nay Pyi Taw) and border crossings. Check current travel advisories before planning travel."
    ],
    'ETH' => [
        'duration' => '30 or 90 days',
        'passport_validity' => '6 months beyond entry date',
        'fee' => '$52-72 USD (eVisa)',
        'processing_time' => '1-3 business days',
        'official_url' => 'https://www.evisa.gov.et/',
        'arrival_card' => 'Yes',
        'docs' => "• Valid passport (6 months validity)\n• Digital passport photo\n• Return flight ticket\n• Proof of accommodation\n• Yellow fever vaccination certificate\n• Payment by credit card",
        'requirements' => "Australian passport holders must obtain an eVisa before traveling to Ethiopia. Apply at https://www.evisa.gov.et. Tourist eVisas cost $52 USD (30 days) or $72 USD (90 days). Processing usually takes 1-3 business days. A yellow fever vaccination certificate is required if arriving from an endemic country. Entry is via Bole International Airport (Addis Ababa) only for eVisa holders."
    ],
    'UZB' => [
        'duration' => '30 days',
        'passport_validity' => '3 months beyond visa expiry',
        'fee' => '$20 USD (eVisa)',
        'processing_time' => '2-3 business days',
        'official_url' => 'https://e-visa.gov.uz/main',
        'arrival_card' => 'No',
        'docs' => "• Valid passport (3+ months beyond visa expiry)\n• Digital passport photo\n• Proof of accommodation\n• Return ticket\n• Payment by credit card",
        'requirements' => "Australian passport holders can apply for an eVisa for Uzbekistan at https://e-visa.gov.uz. The eVisa costs $20 USD, allows a single-entry stay of 30 days, and processing takes 2-3 business days. Your passport must be valid for at least 3 months beyond the visa expiry date. Register with local authorities if staying more than 3 days in one location (hotels do this automatically)."
    ],

    // ── Visa on Arrival countries ─────────
    'LAO' => [
        'duration' => '30 days',
        'passport_validity' => '6 months beyond entry date',
        'fee' => '$35 USD',
        'processing_time' => '10-20 minutes at immigration',
        'official_url' => 'https://laoevisa.gov.la/',
        'arrival_card' => 'Yes',
        'docs' => "• Valid passport (6 months validity, 2 blank pages)\n• Passport photo (4x6cm)\n• Visa fee: $35 USD cash\n• Return/onward ticket\n• Proof of accommodation",
        'requirements' => "Australian passport holders can obtain a Laos visa on arrival at international airports (Vientiane, Luang Prabang, Pakse, Savannakhet) and most international land borders. The visa costs $35 USD (bring exact cash — ATMs may not be available at borders), plus you need a passport photo. Alternatively, apply for an eVisa at https://laoevisa.gov.la ($35 + $5 processing). Both options allow a 30-day stay, which can be extended at immigration offices for $2/day."
    ],
    'MDV' => [
        'duration' => '30 days',
        'passport_validity' => '6 months beyond entry date',
        'fee' => 'Free (visa on arrival)',
        'processing_time' => 'Instant at immigration',
        'official_url' => 'https://immigration.gov.mv/',
        'arrival_card' => 'Yes (IMUGA Traveller Declaration — submit online before arrival)',
        'docs' => "• Valid passport (6 months validity)\n• Return/onward ticket\n• Proof of accommodation (hotel/resort booking)\n• Proof of sufficient funds ($100 + $50/day)\n• IMUGA Traveller Declaration",
        'requirements' => "Australian passport holders receive a free 30-day visa on arrival in the Maldives. Submit the IMUGA Traveller Declaration at https://imuga.immigration.gov.mv within 96 hours before arrival. Present your passport, return ticket, and hotel/resort booking at immigration. Proof of sufficient funds ($100 + $50 per day) may be requested. The Maldives is an Islamic country — alcohol is only available at resort islands."
    ],
    'BOL' => [
        'duration' => '30 days (extendable to 90)',
        'passport_validity' => '6 months beyond entry date',
        'fee' => '$52 USD (Visa on Arrival) or $160 USD (eVisa/embassy)',
        'processing_time' => '30-60 minutes at border',
        'official_url' => 'https://www.rree.gob.bo/webmre/',
        'arrival_card' => 'Yes',
        'docs' => "• Valid passport (6 months validity, 2 blank pages)\n• Passport photo\n• Return/onward ticket\n• Proof of accommodation\n• Yellow fever vaccination certificate\n• Visa fee in USD cash",
        'requirements' => "Australian passport holders can obtain a Bolivia visa on arrival at major airports and some land borders. The tourist visa costs $52 USD (cash). A yellow fever vaccination certificate is required. Your passport must be valid for 6 months with 2 blank pages. The visa allows a 30-day stay, extendable up to 90 days at immigration offices. Alternatively, apply at a Bolivian embassy before travel."
    ],
];

// ── UPDATE VISA DATA ──────────────────────────────────────────

$updateStmt = $pdo->prepare("
    UPDATE country_translations SET 
        visa_duration = ?,
        passport_validity = ?,
        visa_fee = ?,
        processing_time = ?,
        official_visa_url = ?,
        arrival_card_required = ?,
        additional_docs = ?,
        visa_requirements = ?,
        last_verified = CURDATE()
    WHERE country_id = (SELECT id FROM countries WHERE country_code = ?)
    AND lang_code = 'en'
");

/**
 * Generate improved template data for countries without specific overrides
 * (much better than the old templates — no fake URLs, accurate text)
 */
function generateTemplate($visaType, $countryName) {
    switch ($visaType) {
        case 'visa_free':
            return [
                'duration' => '90 days (check specific country allowance)',
                'passport_validity' => '6 months beyond entry date (recommended)',
                'fee' => 'Free',
                'processing_time' => 'Instant at border',
                'official_url' => null, // Will be filled from countries.official_url in Phase 3
                'arrival_card' => 'Check on arrival',
                'docs' => "• Valid passport (6 months validity recommended)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds",
                'requirements' => "Australian passport holders can enter {$countryName} visa-free for short-term tourism or business visits. Duration of stay varies — check with the embassy or consulate for the exact permitted period. Your passport should have at least 6 months validity from your date of entry. Carry proof of return travel, accommodation, and sufficient funds. Check the official immigration website for the most current entry requirements before travel."
            ];
        case 'evisa':
            return [
                'duration' => '30-90 days (varies by visa type)',
                'passport_validity' => '6 months beyond entry date',
                'fee' => 'Varies — check official website',
                'processing_time' => '3-7 business days',
                'official_url' => null,
                'arrival_card' => 'Check requirements',
                'docs' => "• Valid passport (6 months validity)\n• Digital passport photo\n• Completed online application\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Travel insurance (recommended)\n• Payment by credit/debit card",
                'requirements' => "Australian passport holders must apply for an electronic visa (eVisa) before traveling to {$countryName}. Apply online through the official government portal at least 7 business days before your departure. Fees, duration, and processing times vary — check the official website for current rates. Your passport must be valid for at least 6 months from your date of entry. Print your approved eVisa and present it with your passport upon arrival."
            ];
        case 'visa_on_arrival':
            return [
                'duration' => '15-30 days (varies)',
                'passport_validity' => '6 months beyond entry date',
                'fee' => 'Varies — bring USD cash',
                'processing_time' => '15-30 minutes at immigration',
                'official_url' => null,
                'arrival_card' => 'Yes',
                'docs' => "• Valid passport (6 months validity, 2 blank pages)\n• Passport photo\n• Visa fee in cash (USD recommended)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds",
                'requirements' => "Australian passport holders can obtain a visa on arrival at {$countryName} international airports and designated border crossings. Bring a passport photo, visa fee in cash (USD is widely accepted), return ticket, and accommodation proof. Your passport must be valid for at least 6 months with blank pages for the visa stamp. Processing takes 15-30 minutes at the immigration counter. Check the official website for current fees before travel."
            ];
        case 'visa_required':
            return [
                'duration' => '30-90 days (varies by visa type)',
                'passport_validity' => '6 months beyond intended stay',
                'fee' => 'Varies — check embassy website',
                'processing_time' => '5-20 business days',
                'official_url' => null,
                'arrival_card' => 'Yes',
                'docs' => "• Valid passport (6 months validity, 2 blank pages)\n• Completed visa application form\n• Recent passport photos (2)\n• Full travel itinerary with flight bookings\n• Hotel/accommodation proof for entire stay\n• Bank statements (last 3 months)\n• Travel insurance\n• Employment letter or proof of income\n• Visa fee payment",
                'requirements' => "Australian passport holders must obtain a visa before traveling to {$countryName}. Contact the nearest embassy or consulate and apply at least 20 business days before your intended departure. Fees, requirements, and processing times vary depending on the visa type. Your passport must be valid for at least 6 months beyond your intended stay with 2 blank pages. Submit a completed application form, passport photos, travel itinerary, accommodation proof, financial statements, and travel insurance. Some countries may require an interview."
            ];
        default:
            return [
                'duration' => 'Check with embassy',
                'passport_validity' => '6 months minimum',
                'fee' => 'Varies',
                'processing_time' => 'Varies — contact embassy',
                'official_url' => null,
                'arrival_card' => 'Yes',
                'docs' => "• Valid passport\n• Visa application form\n• Supporting documents as required by embassy",
                'requirements' => "Visa requirements for {$countryName} vary. Please contact the nearest embassy or consulate for current requirements, fees, and processing times. Check your government's travel advisory website for up-to-date information."
            ];
    }
}

$processed = 0;
$overrideCount = 0;
$templateCount = 0;
$errors = 0;

foreach ($allCountries as $country) {
    $code = $country['country_code'];
    $name = $country['country_name'];
    $visaType = $country['visa_type'];
    
    try {
        // Use override if available, otherwise use template
        if (isset($countryOverrides[$code])) {
            $data = $countryOverrides[$code];
            $overrideCount++;
        } else {
            $data = generateTemplate($visaType, $name);
            $templateCount++;
        }
        
        $updateStmt->execute([
            $data['duration'],
            $data['passport_validity'],
            $data['fee'],
            $data['processing_time'],
            $data['official_url'],   // null for templates, real URL for overrides
            $data['arrival_card'],
            $data['docs'],
            $data['requirements'],
            $code
        ]);
        
        $processed++;
        $marker = isset($countryOverrides[$code]) ? '★' : '·';
        if ($processed % 25 === 0 || isset($countryOverrides[$code])) {
            echo "  {$marker} {$code} - {$name} ({$visaType})\n";
        }
        
    } catch (PDOException $e) {
        $errors++;
        echo "  ✗ {$code} - ERROR: " . $e->getMessage() . "\n";
    }
}

echo "\n  ✓ Processed $processed countries ($overrideCount with specific data, $templateCount with templates)\n";
if ($errors > 0) echo "  ✗ $errors errors\n";
echo "\n";

// ============================================================
// PHASE 3: Fix URLs — copy countries.official_url where needed
// ============================================================
echo "PHASE 3: Fixing URLs from countries.official_url...\n";
echo "----------------------------------------------------------------\n";

// For any country where official_visa_url is NULL (templates), copy from countries.official_url
$urlFixStmt = $pdo->prepare("
    UPDATE country_translations ct
    JOIN countries c ON ct.country_id = c.id
    SET ct.official_visa_url = c.official_url
    WHERE ct.lang_code = 'en'
    AND (ct.official_visa_url IS NULL OR ct.official_visa_url = '')
    AND c.official_url IS NOT NULL
    AND c.official_url != ''
");
$urlFixStmt->execute();
$urlsFixed = $urlFixStmt->rowCount();
echo "  ✓ Copied real official_url for $urlsFixed countries (where no override URL was set)\n\n";

// ============================================================
// VERIFICATION
// ============================================================
echo "VERIFICATION\n";
echo "================================================================\n\n";

// 1. Check visa_type distribution
$stmt = $pdo->query("
    SELECT visa_type, COUNT(*) as cnt 
    FROM countries WHERE is_active = 1 
    GROUP BY visa_type ORDER BY cnt DESC
");
echo "Visa Type Distribution:\n";
while ($row = $stmt->fetch()) {
    echo "  {$row['visa_type']}: {$row['cnt']} countries\n";
}

// 2. Check for remaining NULL/empty URLs
$stmt = $pdo->query("
    SELECT COUNT(*) as null_urls 
    FROM country_translations ct
    JOIN countries c ON ct.country_id = c.id
    WHERE ct.lang_code = 'en'
    AND c.is_active = 1
    AND (ct.official_visa_url IS NULL OR ct.official_visa_url = '')
");
$nullUrls = $stmt->fetch()['null_urls'];
echo "\n  Countries still missing official_visa_url: $nullUrls\n";

// 3. Spot-check key corrections
echo "\nSpot Check — Key Countries:\n";
$spotCheck = ['FRA','DEU','GBR','USA','JPN','THA','IND','CHN','NZL','ZAF','BRA','ARE'];
$stmt = $pdo->prepare("
    SELECT c.country_code, c.visa_type, ct.visa_duration, ct.visa_fee, 
           LEFT(ct.official_visa_url, 50) as url_preview
    FROM countries c
    JOIN country_translations ct ON c.id = ct.country_id
    WHERE ct.lang_code = 'en' AND c.country_code = ?
");
foreach ($spotCheck as $sc) {
    $stmt->execute([$sc]);
    $row = $stmt->fetch();
    if ($row) {
        echo "  {$row['country_code']}: type={$row['visa_type']}, duration={$row['visa_duration']}, fee={$row['visa_fee']}, url={$row['url_preview']}\n";
    }
}

// 4. Check for fake URLs still remaining
$stmt = $pdo->query("
    SELECT COUNT(*) as fake_count 
    FROM country_translations 
    WHERE lang_code = 'en' 
    AND official_visa_url LIKE '%www.gov.___/%'
    OR official_visa_url LIKE '%www.immigration.___.%'
    OR official_visa_url LIKE '%evisa.___.gov%'
    OR official_visa_url LIKE '%embassy___.%'
");
$fakeCount = $stmt->fetch()['fake_count'];
echo "\n  Remaining fake/template URLs: $fakeCount\n";

echo "\n================================================================\n";
if ($errors === 0 && $fakeCount === 0 && $nullUrls === 0) {
    echo "  ✅ ALL FIXES APPLIED SUCCESSFULLY!\n";
} else {
    echo "  ⚠️  FIXES APPLIED WITH WARNINGS — review items above\n";
}
echo "================================================================\n\n";

echo "Summary:\n";
echo "  Phase 1: Fixed visa_type for $phase1Count countries\n";
echo "  Phase 2: Regenerated visa data ($overrideCount overrides + $templateCount templates)\n";
echo "  Phase 3: Copied real URLs for $urlsFixed countries\n";
echo "\nDone! Run task5_spot_check.php to verify data quality.\n";
