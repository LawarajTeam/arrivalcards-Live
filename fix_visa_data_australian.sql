-- ================================================================
-- FIX ALL VISA DATA - Australian Passport Holder Perspective
-- ================================================================
-- Generated: 2026-02-23
-- Run this on your production database via phpMyAdmin or SSH.
-- 
-- Fixes:
--   1. Corrects visa_type for all countries (Australian passport holders)
--   2. Regenerates visa text with 94 country-specific overrides
--   3. Replaces fake template URLs with real official_url
-- ================================================================

SET NAMES utf8mb4;
START TRANSACTION;

-- ================================================================
-- PHASE 1: Fix visa_type for all countries
-- ================================================================

-- ── VISA FREE (120 countries) ────────────────────────────────

-- Schengen Area (27)
UPDATE countries SET visa_type = 'visa_free' WHERE country_code IN (
  'AUT','BEL','CZE','DNK','EST','FIN','FRA','DEU','GRC','HUN',
  'ISL','ITA','LVA','LTU','LUX','MLT','NLD','NOR','POL','PRT',
  'SVK','SVN','ESP','SWE','CHE','HRV','LIE'
);

-- Other Europe
UPDATE countries SET visa_type = 'visa_free' WHERE country_code IN (
  'GBR','IRL','ROU','BGR','CYP','SRB','ALB','MNE','MKD','BIH',
  'GEO','MDA','UKR','XKX','MCO','SMR','AND','VAT'
);

-- Asia-Pacific
UPDATE countries SET visa_type = 'visa_free' WHERE country_code IN (
  'JPN','KOR','HKG','MAC','SGP','MYS','THA','IDN','PHL','VNM',
  'BRN','MNG','TWN','KAZ','KGZ'
);

-- Americas
UPDATE countries SET visa_type = 'visa_free' WHERE country_code IN (
  'MEX','BRA','ARG','CHL','COL','PER','ECU','URY','CRI','PAN',
  'GTM','HND','NIC','SLV','BLZ','DOM','JAM','TTO','BHS','BRB',
  'ATG','DMA','GRD','KNA','LCA','VCT','GUY','SUR','PRY'
);

-- Middle East
UPDATE countries SET visa_type = 'visa_free' WHERE country_code IN (
  'ARE','ISR','TUR','QAT'
);

-- Africa
UPDATE countries SET visa_type = 'visa_free' WHERE country_code IN (
  'ZAF','MAR','TUN','MUS','SYC','BWA','NAM','SEN','SWZ','LSO',
  'BEN','GAB','GMB','RWA'
);

-- Oceania
UPDATE countries SET visa_type = 'visa_free' WHERE country_code IN (
  'NZL','FJI','VUT','WSM','TON','SLB','KIR','MHL','FSM','TUV',
  'NRU','COK','NIU'
);

-- ── E-VISA (38 countries) ────────────────────────────────────
UPDATE countries SET visa_type = 'evisa' WHERE country_code IN (
  'USA','CAN','AUS',
  'IND','LKA','MMR','PAK','UZB','TJK','AZE',
  'KEN','ETH','UGA','ZMB','CIV','CMR','GHA','NGA','MOZ','ZWE',
  'TZA','BFA','GNB','AGO','COG','COD','GIN','MLI','NER','TCD',
  'CAF','SSD','SLE','LBR',
  'SAU','OMN','KWT','BHR'
);

-- ── VISA ON ARRIVAL (20 countries) ───────────────────────────
UPDATE countries SET visa_type = 'visa_on_arrival' WHERE country_code IN (
  'KHM','LAO','NPL','TLS','MDV',
  'JOR','LBN','IRN',
  'EGY','MDG','PLW','PNG','BOL','CUB',
  'CPV','COM','DJI','MRT','TGO','SOM'
);

-- ── VISA REQUIRED (14 countries) ─────────────────────────────
UPDATE countries SET visa_type = 'visa_required' WHERE country_code IN (
  'CHN','RUS','PRK',
  'AFG','IRQ','SYR','YEM',
  'TKM','BTN',
  'LBY','SDN','ERI','DZA',
  'BLR'
);


-- ================================================================
-- PHASE 2: Regenerate visa data — Country-Specific Overrides
-- ================================================================
-- 94 countries with researched, specific data for Australian passport holders.
-- Each UPDATE targets country_translations WHERE lang_code = 'en'.

-- ────────────────────────────────────────────────────────────────
-- SCHENGEN AREA (27 countries)
-- All share: 90/180 days, passport 3+ months, free, instant, no arrival card
-- ────────────────────────────────────────────────────────────────

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://france-visas.gouv.fr',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €65 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter France and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €65 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'FRA') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.auswaertiges-amt.de/en/visa-service',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €45 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Germany and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €45 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'DEU') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://vistoperitalia.esteri.it',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €45-50 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Italy and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €45-50 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'ITA') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.exteriores.gob.es/en/ServiciosAlCiudadano/Paginas/Visados.aspx',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (€100 per person per day (minimum €900 total))\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Spain and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (€100 per person per day (minimum €900 total)). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'ESP') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.netherlandsworldwide.nl/visa-the-netherlands',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €55 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter the Netherlands and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €55 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'NLD') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://diplomatie.belgium.be/en/travel-belgium/visa-belgium',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €95 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Belgium and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €95 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'BEL') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.bmeia.gv.at/en/travel-stay/entry-requirements',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €60 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Austria and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €60 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'AUT') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.sem.admin.ch/sem/en/home/themen/einreise.html',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately CHF 100 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Switzerland and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately CHF 100 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'CHE') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.mfa.gr/en/visas/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €50 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Greece and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €50 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'GRC') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.vistos.mne.gov.pt/en/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €40 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Portugal and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €40 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'PRT') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.migrationsverket.se/English/Private-individuals/Visiting-Sweden.html',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately SEK 450 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Sweden and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately SEK 450 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'SWE') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.udi.no/en/want-to-apply/visit-and-holiday/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately NOK 500 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Norway and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately NOK 500 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'NOR') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.nyidanmark.dk/en-GB',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately DKK 350 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Denmark and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately DKK 350 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'DNK') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://um.fi/entering-finland',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €50 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Finland and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €50 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'FIN') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.utl.is/index.php/en/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately ISK 10,000 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Iceland and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately ISK 10,000 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'ISL') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.gov.pl/web/diplomacy/visas',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately PLN 300 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Poland and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately PLN 300 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'POL') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.mzv.cz/jnp/en/information_for_aliens/visa_information/index.html',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately CZK 1,000 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Czech Republic and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately CZK 1,000 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'CZE') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://konzuliszolgalat.kormany.hu/en',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €40 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Hungary and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €40 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'HUN') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.mzv.sk/en/travel_to_slovakia/visa_requirements',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €50 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Slovakia and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €50 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'SVK') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.gov.si/en/topics/entry-and-residence/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €60 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Slovenia and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €60 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'SVN') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.politsei.ee/en/instructions/applying-for-visa',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €45 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Estonia and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €45 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'EST') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.pmlp.gov.lv/en/home.html',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €40 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Latvia and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €40 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'LVA') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.migracija.lt/en',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €40 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Lithuania and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €40 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'LTU') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://identitymalta.com/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €48 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Malta and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €48 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'MLT') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://guichet.public.lu/en/citoyens/immigration.html',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €50 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Luxembourg and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €50 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'LUX') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://mvep.gov.hr/services-for-citizens/consular-information/visas/visa-requirements-overview/861',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately €60 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Croatia and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately €60 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'HRV') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period (Schengen)',
  passport_validity = '3+ months beyond departure from Schengen Area; issued within last 10 years',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.llv.li/en/national-administration/office-of-immigration-and-passports',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond Schengen departure, issued within 10 years)\n• Return/onward ticket\n• Proof of accommodation (hotel booking or invitation letter)\n• Travel insurance with minimum €30,000 medical coverage\n• Proof of sufficient funds (approximately CHF 100 per day)\n• Purpose of visit documentation (if requested)',
  visa_requirements = 'Australian passport holders can enter Liechtenstein and the Schengen Area visa-free for up to 90 days within any 180-day period for tourism, business, or family visits. Your passport must be valid for at least 3 months beyond your planned departure from the Schengen zone and must have been issued within the last 10 years. Carry proof of return travel, accommodation arrangements, travel insurance with minimum €30,000 medical coverage, and evidence of sufficient funds (approximately CHF 100 per day). Important: the 90-day limit applies to the ENTIRE Schengen Area cumulatively — time spent in any Schengen country counts toward the total. No arrival card is required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'LIE') AND lang_code = 'en';


-- ────────────────────────────────────────────────────────────────
-- OTHER EUROPE
-- ────────────────────────────────────────────────────────────────

UPDATE country_translations SET
  visa_duration = '6 months',
  passport_validity = 'Valid for duration of stay',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.gov.uk/check-uk-visa',
  arrival_card_required = 'No',
  additional_docs = '• Valid Australian passport\n• Return/onward ticket\n• Proof of accommodation\n• Evidence of sufficient funds\n• Details of your visit (business contacts, tour bookings, etc.)',
  visa_requirements = 'Australian passport holders can visit the United Kingdom visa-free for up to 6 months for tourism, business meetings, or family visits. Your passport must be valid for the duration of your stay. No advance application required — your visa status is determined at the UK border. Bring proof of your return or onward journey, accommodation details, and evidence of sufficient funds. No arrival card is required. If you plan to work, study, or stay longer than 6 months, you will need to apply for the appropriate visa.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'GBR') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = 'Valid for duration of stay',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.irishimmigration.ie/',
  arrival_card_required = 'No',
  additional_docs = '• Valid Australian passport\n• Return/onward ticket\n• Proof of accommodation\n• Evidence of sufficient funds',
  visa_requirements = 'Australian passport holders can visit Ireland visa-free for up to 90 days for tourism or business. Your passport must be valid for the duration of your stay. No advance application required. Present your return ticket, accommodation details, and evidence of sufficient funds at immigration. Note: Ireland is NOT part of the Schengen Area — time spent in Ireland does not count toward Schengen 90-day limits, and a Schengen visa does not cover Ireland.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'IRL') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.evisa.gov.tr/en/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months validity)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Turkey visa-free for up to 90 days within a 180-day period for tourism or business. Your passport must be valid for at least 6 months from your date of entry. No eVisa or advance application is required. Present your return ticket, accommodation proof, and evidence of sufficient funds at immigration.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'TUR') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period',
  passport_validity = 'valid for 3+ months beyond departure',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.mae.ro/en/node/2040',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for 3+ months beyond departure)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Romania visa-free for up to 90 days within 180-day period for tourism or business. Your passport must be valid for valid for 3+ months beyond departure. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Romania is an EU member but not yet in the Schengen Area. Time spent in Romania does NOT count toward your Schengen 90-day limit.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'ROU') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period',
  passport_validity = 'valid for 3+ months beyond departure',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.mfa.bg/en/services/travel-bulgaria/visa-bulgaria',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for 3+ months beyond departure)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Bulgaria visa-free for up to 90 days within 180-day period for tourism or business. Your passport must be valid for valid for 3+ months beyond departure. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Bulgaria is an EU member but not yet fully in the Schengen Area. Time spent in Bulgaria does NOT count toward your Schengen 90-day limit.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'BGR') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period',
  passport_validity = 'valid for 3+ months beyond departure',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.mfa.gov.cy/visa/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for 3+ months beyond departure)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Cyprus visa-free for up to 90 days within 180-day period for tourism or business. Your passport must be valid for valid for 3+ months beyond departure. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Cyprus is an EU member but not in the Schengen Area. Time spent in Cyprus does NOT count toward your Schengen 90-day limit.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'CYP') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.mfa.gov.rs/en/consular-affairs/entry-serbia/visa-regime',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Serbia visa-free for up to 90 days within 180-day period for tourism or business. Your passport must be valid for 6 months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'SRB') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within calendar year',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://punetejashtme.gov.al/en/services/consular-services/visas/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Albania visa-free for up to 90 days within calendar year for tourism or business. Your passport must be valid for 6 months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'ALB') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.gov.me/en/mup/services',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Montenegro visa-free for up to 90 days within 180-day period for tourism or business. Your passport must be valid for 6 months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'MNE') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.mfa.gov.mk/?q=category/37/visa-regime',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter North Macedonia visa-free for up to 90 days within 180-day period for tourism or business. Your passport must be valid for 6 months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'MKD') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.mvp.gov.ba/konzularne_informacije/vize/?lang=en',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Bosnia and Herzegovina visa-free for up to 90 days within 180-day period for tourism or business. Your passport must be valid for 6 months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'BIH') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '1 year',
  passport_validity = 'valid for duration of stay',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.geoconsul.gov.ge/en/nonvisa',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for duration of stay)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Georgia visa-free for up to 1 year for tourism or business. Your passport must be valid for valid for duration of stay. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Georgia offers one of the most generous visa-free stays, allowing Australians to stay for up to 1 year without a visa.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'GEO') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.mfa.gov.md/en/content/visa-information',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Moldova visa-free for up to 90 days within 180-day period for tourism or business. Your passport must be valid for 6 months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'MDA') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period',
  passport_validity = 'valid for duration of stay',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://mfa.gov.ua/en/consular-affairs/entering-ukraine/visa-requirements-for-foreigners',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for duration of stay)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Ukraine visa-free for up to 90 days within 180-day period for tourism or business. Your passport must be valid for valid for duration of stay. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Check current travel advisories before planning travel to Ukraine.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'UKR') AND lang_code = 'en';


-- ────────────────────────────────────────────────────────────────
-- AMERICAS
-- ────────────────────────────────────────────────────────────────

UPDATE country_translations SET
  visa_duration = '90 days (Visa Waiver Program)',
  passport_validity = 'Valid for duration of stay (e-Passport required)',
  visa_fee = 'ESTA: $21 USD',
  processing_time = 'ESTA: Usually instant, up to 72 hours',
  official_visa_url = 'https://esta.cbp.dhs.gov',
  arrival_card_required = 'No (ESTA replaces arrival card)',
  additional_docs = '• Valid e-Passport (electronic passport with chip)\n• Approved ESTA ($21 USD)\n• Return/onward ticket\n• Proof of accommodation\n• Evidence of sufficient funds',
  visa_requirements = 'Australian passport holders can travel to the United States visa-free under the Visa Waiver Program (VWP) for stays up to 90 days for tourism or business. You must obtain an Electronic System for Travel Authorization (ESTA) online at https://esta.cbp.dhs.gov before departure — ideally at least 72 hours in advance. The ESTA costs $21 USD and is valid for 2 years or until your passport expires. Your passport must be an e-Passport (with electronic chip). Present your ESTA approval, return ticket, and proof of accommodation upon arrival. The 90-day stay cannot be extended.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'USA') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '6 months',
  passport_validity = 'Valid for duration of stay',
  visa_fee = 'eTA: $7 CAD',
  processing_time = 'eTA: Usually instant, up to 72 hours',
  official_visa_url = 'https://www.canada.ca/en/immigration-refugees-citizenship/services/visit-canada/eta.html',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport\n• Approved eTA ($7 CAD)\n• Return/onward ticket\n• Proof of sufficient funds\n• Letter of invitation (if visiting family/friends)',
  visa_requirements = 'Australian passport holders need an Electronic Travel Authorization (eTA) to fly to Canada. The eTA costs $7 CAD and is applied for online. It is usually approved within minutes but can take up to 72 hours. Once approved, your eTA is valid for 5 years or until your passport expires. You can stay in Canada for up to 6 months per visit. Present your eTA confirmation with your passport at check-in and at the Canadian border. If arriving by land from the US, no eTA is needed.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'CAN') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = 'N/A — Home country',
  passport_validity = 'N/A',
  visa_fee = 'N/A',
  processing_time = 'N/A',
  official_visa_url = 'https://www.homeaffairs.gov.au',
  arrival_card_required = 'Yes (Incoming Passenger Card)',
  additional_docs = '• Valid Australian passport',
  visa_requirements = 'As an Australian passport holder, you have the automatic right of entry into Australia. No visa is required. Your passport must be valid. You will need to complete an Incoming Passenger Card upon arrival, which collects customs and immigration information. SmartGate (automated passport control) is available at major airports.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'AUS') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '180 days',
  passport_validity = 'valid for duration of stay',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.inm.gob.mx/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for duration of stay)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Completed FMM (Forma Migratoria Múltiple) — available at airport',
  visa_requirements = 'Australian passport holders can enter Mexico visa-free for up to 180 days for tourism or business. Your passport must be valid for valid for duration of stay. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Keep your FMM form safe — you must surrender it when departing Mexico. Do not lose it, as replacement involves fees and delays. For stays over 180 days, apply for a Temporary Resident visa.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'MEX') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 180-day period',
  passport_validity = 'valid for 6+ months',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.gov.br/mre/en/subjects/visas',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for 6+ months)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Brazil visa-free for up to 90 days within 180-day period for tourism or business. Your passport must be valid for valid for 6+ months. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Australian passport holders have had visa-free access to Brazil since 2024. You may extend your stay by an additional 90 days at a Federal Police office.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'BRA') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = 'valid for duration of stay',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.argentina.gob.ar/interior/migraciones',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for duration of stay)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Argentina visa-free for up to 90 days for tourism or business. Your passport must be valid for valid for duration of stay. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Extensions of 90 days are possible at the immigration office (Dirección Nacional de Migraciones). You may also do a border run and re-enter for another 90 days.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'ARG') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = 'valid for duration of stay',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.chile.gob.cl/chile/en/travel-chile',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for duration of stay)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Completed PDI immigration form',
  visa_requirements = 'Australian passport holders can enter Chile visa-free for up to 90 days for tourism or business. Your passport must be valid for valid for duration of stay. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. A Tarjeta de Turismo (tourist card) will be issued electronically at entry — it''s linked to your passport number. Extensions are available at the PDI (Policía de Investigaciones).',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'CHL') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days (extendable to 180)',
  passport_validity = 'valid for duration of stay',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.cancilleria.gov.co/en/procedures_services/visas',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for duration of stay)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Colombia visa-free for up to 90 days (extendable to 180) for tourism or business. Your passport must be valid for valid for duration of stay. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Your initial 90-day stay can be extended once for an additional 90 days at a Migración Colombia office. Complete the Check-Mig online form within 72 hours before arrival.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'COL') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '183 days',
  passport_validity = 'valid for 6+ months',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.migraciones.gob.pe/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for 6+ months)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Peru visa-free for up to 183 days for tourism or business. Your passport must be valid for valid for 6+ months. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. The immigration officer determines your permitted stay (usually 90-183 days). Extensions are available at Migraciones offices.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'PER') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within calendar year',
  passport_validity = 'valid for 6+ months',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.cancilleria.gob.ec/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for 6+ months)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Ecuador visa-free for up to 90 days within calendar year for tourism or business. Your passport must be valid for valid for 6+ months. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. You may stay up to 90 days within any calendar year without a visa. For longer stays, apply for a special visa.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'ECU') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = 'valid for duration of stay (6 months recommended)',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.migracion.go.cr/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for duration of stay (6 months recommended))\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Costa Rica visa-free for up to 90 days for tourism or business. Your passport must be valid for valid for duration of stay (6 months recommended). Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. You may need to show a return ticket and proof of funds ($100 per month of intended stay). Extensions are not commonly granted — most travelers do a border run.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'CRI') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '180 days',
  passport_validity = 'valid for 3+ months beyond entry',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.migracion.gob.pa/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for 3+ months beyond entry)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Panama visa-free for up to 180 days for tourism or business. Your passport must be valid for valid for 3+ months beyond entry. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Present your return ticket (within 180 days) and proof of funds ($500 minimum or credit card). An automatic 180-day tourist stamp is given at entry.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'PAN') AND lang_code = 'en';


-- ────────────────────────────────────────────────────────────────
-- ASIA-PACIFIC
-- ────────────────────────────────────────────────────────────────

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = 'Valid for duration of stay',
  visa_fee = 'Free',
  processing_time = 'Instant at immigration',
  official_visa_url = 'https://www.mofa.go.jp/j_info/visit/visa/',
  arrival_card_required = 'Yes (ED card — may be completed digitally via Visit Japan Web)',
  additional_docs = '• Valid passport\n• Completed disembarkation card (or Visit Japan Web registration)\n• Return/onward ticket\n• Proof of accommodation\n• Sufficient funds',
  visa_requirements = 'Australian passport holders can enter Japan visa-free for up to 90 days for tourism or business. Your passport must be valid for the duration of your stay. Register at Visit Japan Web (https://vjw-lp.digital.go.jp/en/) before arrival to expedite immigration and customs procedures. Alternatively, complete the paper disembarkation card on your flight. Present your return ticket, accommodation details, and proof of sufficient funds at immigration. Fingerprints and photo are taken at entry.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'JPN') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = 'Valid for duration of stay',
  visa_fee = 'Free',
  processing_time = 'Instant at immigration',
  official_visa_url = 'https://www.immigration.go.kr/immigration_eng/index.do',
  arrival_card_required = 'Yes (arrival card at immigration)',
  additional_docs = '• Valid passport\n• Completed arrival card\n• Return/onward ticket\n• Proof of accommodation',
  visa_requirements = 'Australian passport holders can enter South Korea visa-free for up to 90 days for tourism or business. K-ETA (Korea Electronic Travel Authorization) has been suspended for Australian nationals — you do not need to apply. Simply present your valid passport, completed arrival card, return ticket, and accommodation proof at immigration. Your passport must be valid for the duration of your stay.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'KOR') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = 'valid for 1+ months beyond intended stay',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.immd.gov.hk/eng/services/visas/visit-transit/visit-visa-entry-permit.html',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for 1+ months beyond intended stay)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Completed arrival card (or pre-register online)',
  visa_requirements = 'Australian passport holders can enter Hong Kong visa-free for up to 90 days for tourism or business. Your passport must be valid for valid for 1+ months beyond intended stay. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Hong Kong is a Special Administrative Region of China — visa rules are separate from mainland China. Your 90-day Hong Kong visa-free stay does not affect your ability to travel to mainland China (which requires a separate visa).',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'HKG') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at immigration',
  official_visa_url = 'https://www.ica.gov.sg/enter-transit-depart/entering-singapore',
  arrival_card_required = 'Yes (SG Arrival Card — submit online before arrival)',
  additional_docs = '• Valid passport (6 months validity)\n• Completed SG Arrival Card (submitted online within 3 days before arrival)\n• Return/onward ticket\n• Proof of accommodation\n• Sufficient funds',
  visa_requirements = 'Australian passport holders can visit Singapore visa-free for up to 90 days for tourism or business. Your passport must be valid for at least 6 months. Submit an SG Arrival Card online at https://eservices.ica.gov.sg/sgarrivalcard within 3 days before arrival. Save your acknowledgement receipt. Present this with your passport, return/onward ticket, accommodation proof, and evidence of sufficient funds at immigration.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'SGP') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at immigration',
  official_visa_url = 'https://www.imi.gov.my',
  arrival_card_required = 'Yes (Malaysia Digital Arrival Card — submit online)',
  additional_docs = '• Valid passport (6 months validity)\n• Malaysia Digital Arrival Card (MDAC) submitted online within 3 days before arrival\n• Return/onward ticket\n• Proof of accommodation\n• Sufficient funds',
  visa_requirements = 'Australian passport holders can visit Malaysia visa-free for up to 90 days for tourism or business. Your passport must be valid for at least 6 months. Complete the Malaysia Digital Arrival Card (MDAC) at https://imigresen-online.imi.gov.my/mdac within 3 days before arrival and save your QR code. Present your MDAC QR code, passport, return/onward ticket, and accommodation proof at immigration.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'MYS') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '60 days (air) / 30 days (land)',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at immigration',
  official_visa_url = 'https://www.immigration.go.th',
  arrival_card_required = 'Yes (TM.6 departure card)',
  additional_docs = '• Valid passport (6 months validity)\n• TM.6 arrival/departure card\n• Return/onward ticket within permitted stay\n• Proof of accommodation\n• Proof of funds (10,000 THB/person or 20,000 THB/family)',
  visa_requirements = 'Australian passport holders can enter Thailand visa-free for up to 60 days when arriving by air (or 30 days by land border). Your passport must be valid for at least 6 months. Complete a TM.6 departure card at immigration. You must have proof of onward travel, accommodation confirmation, and evidence of funds (10,000 THB per person or 20,000 THB per family). Extensions of 30 days are available at immigration offices for 1,900 THB.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'THA') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 days (visa-free) or 30 days extendable (VOA)',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free (visa-free) or $35 USD (Visa on Arrival, extendable)',
  processing_time = 'Instant at immigration',
  official_visa_url = 'https://www.imigrasi.go.id',
  arrival_card_required = 'Yes (customs declaration form)',
  additional_docs = '• Valid passport (6 months validity, 2 blank pages)\n• Return/onward ticket within 30 days\n• Proof of accommodation\n• Customs declaration form',
  visa_requirements = 'Australian passport holders have two options for Indonesia: (1) Visa-free entry for 30 days at designated airports and seaports — free but NOT extendable and cannot be converted; (2) Visa on Arrival (VOA) for 30 days at $35 USD — extendable once for 30 more days at an immigration office. If you think you may want to stay longer than 30 days, choose the VOA option. Your passport must be valid for 6 months with 2 blank pages. Present your return ticket and accommodation proof.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'IDN') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at immigration',
  official_visa_url = 'https://www.immigration.gov.ph',
  arrival_card_required = 'Yes (eTravel — submit online before departure)',
  additional_docs = '• Valid passport (6 months validity)\n• eTravel registration (submitted within 72 hours of departure)\n• Return/onward ticket within 30 days\n• Proof of accommodation',
  visa_requirements = 'Australian passport holders can enter the Philippines visa-free for up to 30 days. Register via the eTravel system at https://etravel.gov.ph within 72 hours before departure and save your QR code. Your passport must be valid for at least 6 months. Present your eTravel QR code, passport, return ticket, and accommodation proof at immigration. Extensions up to 36 months total are available at Bureau of Immigration offices.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'PHL') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '45 days (visa-free, single entry)',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free (visa-free) or $25 USD (eVisa for 90 days)',
  processing_time = 'Instant at border (eVisa: 3 business days)',
  official_visa_url = 'https://evisa.xuatnhapcanh.gov.vn',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months validity)\n• Return/onward ticket\n• Proof of accommodation\n• eVisa printout (if staying beyond 45 days or needing multiple entries)',
  visa_requirements = 'Australian passport holders can enter Vietnam visa-free for up to 45 days (single entry only). For stays beyond 45 days or if you need multiple entries, apply for an eVisa online ($25 USD, valid 90 days, single or multiple entry) at least 3 business days before departure. Your passport must be valid for 6 months. The 45-day visa-free period restarts each time you enter, but there is no mandatory gap between visits.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'VNM') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30-90 days (eVisa)',
  passport_validity = '6 months beyond entry date with 2 blank pages',
  visa_fee = '$25-100 USD depending on duration',
  processing_time = '1-4 business days',
  official_visa_url = 'https://indianvisaonline.gov.in',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months validity, 2 blank pages)\n• Digital passport photo (white background)\n• Scanned passport bio page\n• Return flight ticket\n• Proof of accommodation\n• Payment by credit/debit card',
  visa_requirements = 'Australian passport holders must apply for an eVisa before traveling to India. Tourist eVisas are available for: 30 days (double entry, $25 USD), 1 year (multiple entry, $40 USD), or 5 years (multiple entry, $80 USD). Apply at https://indianvisaonline.gov.in at least 4 business days before departure. Upload a digital passport photo and scanned passport page. Print your approved eVisa and present it upon arrival at designated Indian airports. eVisas cannot be extended — for longer stays, apply for a regular visa at the Indian embassy.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'IND') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 days (extendable to 90)',
  passport_validity = '6 months beyond entry date',
  visa_fee = '$50 USD (ETA)',
  processing_time = '1-3 business days',
  official_visa_url = 'https://www.eta.gov.lk',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months validity)\n• Approved ETA\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds ($50/day)',
  visa_requirements = 'Australian passport holders must obtain an Electronic Travel Authorization (ETA) before traveling to Sri Lanka. Apply online at https://www.eta.gov.lk. The tourist ETA costs $50 USD, is valid for 30 days, and allows double entry. Processing takes 1-3 business days. Extensions up to 90 days are available at the Department of Immigration in Colombo. Print your ETA approval and present it with your passport on arrival.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'LKA') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = '$30 USD (eVisa) or $30 USD (Visa on Arrival)',
  processing_time = 'eVisa: 3 business days; VOA: 15-30 minutes',
  official_visa_url = 'https://www.evisa.gov.kh/',
  arrival_card_required = 'Yes',
  additional_docs = '• Valid passport (6 months validity, 1 blank page)\n• Passport-sized photo (4x6cm)\n• Visa fee: $30 USD cash (VOA) or online payment (eVisa)\n• Return/onward ticket\n• Proof of accommodation',
  visa_requirements = 'Australian passport holders can obtain a Cambodia visa either as an eVisa (apply at https://www.evisa.gov.kh, $30 USD + $6 processing fee, 3 business days) or as a Visa on Arrival at international airports and main land borders ($30 USD cash, bring a passport photo). Both allow a 30-day stay, extendable once for 30 days at an immigration office. Your passport must be valid for 6 months.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'KHM') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '15, 30, or 90 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = '$30 USD (15 days), $50 USD (30 days), $125 USD (90 days)',
  processing_time = '15-30 minutes at airport',
  official_visa_url = 'https://www.immigration.gov.np/',
  arrival_card_required = 'Yes',
  additional_docs = '• Valid passport (6 months validity)\n• Passport photo\n• Visa fee in cash (USD)\n• Completed visa application form\n• Return/onward ticket',
  visa_requirements = 'Australian passport holders can obtain a Nepal visa on arrival at Tribhuvan International Airport (Kathmandu) and major land borders. Choose between 15-day ($30 USD), 30-day ($50 USD), or 90-day ($125 USD) tourist visas. Pay in cash (USD). Bring a passport photo. You can extend your visa at the Department of Immigration in Kathmandu for $3 USD/day. Maximum total stay is 150 days per calendar year.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'NPL') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.consul.mn/eng/index.php?moduls=23',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Mongolia visa-free for up to 30 days for tourism or business. Your passport must be valid for 6 months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. For stays beyond 30 days, register with the Immigration Agency of Mongolia within 7 days of arrival.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'MNG') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = 'Indefinite (Australian citizens can live and work)',
  passport_validity = 'Valid for travel',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.immigration.govt.nz/new-zealand-visas',
  arrival_card_required = 'Yes (NZTD — New Zealand Traveller Declaration, submitted online)',
  additional_docs = '• Valid Australian passport\n• New Zealand Traveller Declaration (submitted online before arrival)',
  visa_requirements = 'Australian citizens receive a Resident Visa on arrival in New Zealand under the Trans-Tasman Travel Arrangement. You can live, work, and study indefinitely — no advance application is needed. Complete the New Zealand Traveller Declaration (NZTD) online at https://www.travellerdeclaration.govt.nz before your flight. Your passport must be valid for travel. You''ll have full rights to remain in New Zealand for as long as you wish.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'NZL') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '4 months',
  passport_validity = 'valid for 6+ months beyond intended stay',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.immigration.gov.fj/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for 6+ months beyond intended stay)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Completed arrival card\n• Proof of yellow fever vaccination (if arriving from infected area)',
  visa_requirements = 'Australian passport holders can enter Fiji visa-free for up to 4 months for tourism or business. Your passport must be valid for valid for 6+ months beyond intended stay. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Extensions up to 6 months total are available at a Fiji immigration office. Fiji is a popular transit point for Pacific island destinations.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'FJI') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '60 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free (Visa on Arrival)',
  processing_time = '15-30 minutes at airport',
  official_visa_url = 'https://www.ica.gov.pg/',
  arrival_card_required = 'Yes',
  additional_docs = '• Valid passport (6 months validity)\n• Return/onward ticket\n• Proof of accommodation\n• Sufficient funds',
  visa_requirements = 'Australian passport holders can obtain a free Visa on Arrival at Port Moresby Jacksons International Airport (and other designated entry points) for up to 60 days. Your passport must be valid for at least 6 months. Present your return ticket, accommodation details, and proof of sufficient funds. Extensions are available at the PNG Immigration office.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'PNG') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.immigration.gov.bn/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Brunei visa-free for up to 90 days for tourism or business. Your passport must be valid for 6 months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'BRN') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.gov.kz/memleket/entities/mfa/press/article/details/211611?lang=en',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Kazakhstan visa-free for up to 30 days for tourism or business. Your passport must be valid for 6 months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. For stays beyond 30 days, you must register with the migration police within 3 business days any time you change location.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'KAZ') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '60 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://evisa.e-gov.kg/get_information.php',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Kyrgyzstan visa-free for up to 60 days for tourism or business. Your passport must be valid for 6 months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. For stays beyond 60 days, apply for a visa extension at the State Registration Service in Bishkek.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'KGZ') AND lang_code = 'en';


-- ────────────────────────────────────────────────────────────────
-- MIDDLE EAST
-- ────────────────────────────────────────────────────────────────

UPDATE country_translations SET
  visa_duration = '60 days (free visa on arrival)',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = '5-10 minutes at airport',
  official_visa_url = 'https://www.icp.gov.ae',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months validity)\n• Return/onward ticket\n• Hotel reservation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders receive a free 60-day visa stamp on arrival at UAE airports. Your passport must be valid for at least 6 months. The visa is free of charge and allows a 60-day stay. Present your return ticket, hotel reservation, and proof of sufficient funds at immigration. This visa can be extended for an additional 30 days by visiting a GDRFA service center (fee applies). No advance application required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'ARE') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.gov.il/en/departments/topics/visa-information',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Proof of travel insurance',
  visa_requirements = 'Australian passport holders can enter Israel visa-free for up to 90 days for tourism or business. Your passport must be valid for 6 months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. An electronic entry card is issued at the airport. Keep it safe — you''ll need it for hotel check-ins. Israeli entry/exit stamps are issued electronically and don''t appear in your passport.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'ISR') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 days (single entry) or stay duration with Jordan Pass',
  passport_validity = '6 months beyond entry date',
  visa_fee = '$56-113 USD (visa) or included in Jordan Pass ($70-80 USD)',
  processing_time = '10-15 minutes at airport',
  official_visa_url = 'https://www.jordanpass.jo/',
  arrival_card_required = 'Yes',
  additional_docs = '• Valid passport (6 months validity)\n• Jordan Pass (recommended — includes visa fee + Petra entry) OR visa fee in cash\n• Return/onward ticket\n• Proof of accommodation',
  visa_requirements = 'Australian passport holders can obtain a Jordan visa on arrival at Queen Alia International Airport. The single-entry visa costs 40 JOD (~$56 USD). Strongly recommended: purchase a Jordan Pass at https://www.jordanpass.jo before arrival ($70-80 USD) — it includes the visa fee AND entry to Petra and 40+ attractions, making it excellent value. You must stay at least 3 consecutive nights in Jordan for the Jordan Pass visa waiver to apply.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'JOR') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://portal.moi.gov.qa/wps/portal/MOIInternet/departmentcommissioner/visas/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Qatar visa-free for up to 90 days for tourism or business. Your passport must be valid for 6 months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Qatar grants visa-free entry for Australian passport holders for up to 90 days. No advance application required.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'QAT') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days within 1-year period (multiple entry)',
  passport_validity = '6 months beyond entry date',
  visa_fee = '$120 USD (eVisa)',
  processing_time = '1-5 business days',
  official_visa_url = 'https://visa.visitsaudi.com/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months validity)\n• Digital passport photo\n• Completed eVisa application\n• Return flight ticket\n• Proof of accommodation\n• Travel insurance\n• Payment by credit card',
  visa_requirements = 'Australian passport holders can apply for a Saudi tourist eVisa online at https://visa.visitsaudi.com. The eVisa costs approximately $120 USD (including insurance), is valid for 1 year with multiple entries, and allows stays of up to 90 days per visit. Processing usually takes 1-5 business days. Your passport must be valid for 6 months. Women can travel independently. Dress modestly. Alcohol is prohibited.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'SAU') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '14 days (free) or 30 days (paid eVisa)',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free (14-day) or 20 OMR / ~$52 USD (30-day eVisa)',
  processing_time = '1-3 business days',
  official_visa_url = 'https://evisa.rop.gov.om/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months validity)\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Travel insurance',
  visa_requirements = 'Australian passport holders have two options: (1) Free 14-day tourist visa on arrival, or (2) 30-day eVisa for 20 OMR (~$52 USD) applied at https://evisa.rop.gov.om. For the eVisa, apply at least 3 business days before travel. Your passport must be valid for 6 months. Both options require a return ticket, accommodation proof, and travel insurance.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'OMN') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 days (single entry)',
  passport_validity = '6 months beyond entry date',
  visa_fee = '$25 USD',
  processing_time = 'Visa on Arrival: 10-15 min; eVisa: 5-7 business days',
  official_visa_url = 'https://visa2egypt.gov.eg/',
  arrival_card_required = 'Yes',
  additional_docs = '• Valid passport (6 months validity)\n• Passport photo\n• Visa fee: $25 USD (cash for VOA; card for eVisa)\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can obtain an Egypt visa either on arrival at Egyptian airports ($25 USD, paid at bank kiosk before immigration) or as an eVisa at https://visa2egypt.gov.eg ($25 USD, 5-7 business days processing). Both give a 30-day single-entry visa. Your passport must be valid for 6 months. The eVisa option avoids queues at the airport. Extensions are possible at the Mogamma building in Cairo.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'EGY') AND lang_code = 'en';


-- ────────────────────────────────────────────────────────────────
-- AFRICA
-- ────────────────────────────────────────────────────────────────

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = '30 days beyond intended departure with 2 blank pages',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'http://www.dha.gov.za/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (30 days beyond intended departure with 2 blank pages)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Proof of yellow fever vaccination (if arriving from endemic area)\n• Return/onward ticket (mandatory)',
  visa_requirements = 'Australian passport holders can enter South Africa visa-free for up to 90 days for tourism or business. Your passport must be valid for 30 days beyond intended departure with 2 blank pages. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Your passport MUST have at least 2 blank pages for entry/exit stamps — South Africa is strict about this and will deny boarding if you don''t have blank pages. A return ticket is mandatory.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'ZAF') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.consulat.ma/en',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Completed arrival form (provided on flight or at border)',
  visa_requirements = 'Australian passport holders can enter Morocco visa-free for up to 90 days for tourism or business. Your passport must be valid for 6 months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Extensions beyond 90 days require applying at the local police station (Sûreté Nationale) before your 90 days expire.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'MAR') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = '3+ months beyond entry date',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://www.diplomatie.gov.tn/en/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond entry date)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders can enter Tunisia visa-free for up to 90 days for tourism or business. Your passport must be valid for 3+ months beyond entry date. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'TUN') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '60 days (extendable to 90)',
  passport_validity = 'valid for duration of stay',
  visa_fee = 'Free',
  processing_time = 'Instant at border',
  official_visa_url = 'https://passport.govmu.org/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (valid for duration of stay)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Completed disembarkation card\n• Proof of yellow fever vaccination (if arriving from endemic area)\n• Return ticket',
  visa_requirements = 'Australian passport holders can enter Mauritius visa-free for up to 60 days (extendable to 90) for tourism or business. Your passport must be valid for valid for duration of stay. Present your return or onward ticket, proof of accommodation, and evidence of sufficient funds at immigration. Mauritius is extremely popular with Australian tourists. Extensions to 90 days are available free of charge at the Passport and Immigration Office in Port Louis.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'MUS') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = '$50 USD (eTA)',
  processing_time = '3-5 business days',
  official_visa_url = 'https://www.etakenya.go.ke/',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (6 months validity, 2 blank pages)\n• Approved eTA\n• Yellow fever vaccination certificate\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds',
  visa_requirements = 'Australian passport holders must obtain an electronic Travel Authorization (eTA) before traveling to Kenya. Apply at https://www.etakenya.go.ke at least 5 business days before departure. The eTA costs $50 USD and allows a 90-day stay. A yellow fever vaccination certificate is MANDATORY. Your passport must be valid for 6 months with 2 blank pages. Print your eTA confirmation.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'KEN') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '90 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = '$50 USD (eVisa)',
  processing_time = '5-10 business days',
  official_visa_url = 'https://visa.immigration.go.tz/',
  arrival_card_required = 'Yes',
  additional_docs = '• Valid passport (6 months validity, 2 blank pages)\n• Approved eVisa\n• Yellow fever vaccination certificate\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds ($500 minimum)',
  visa_requirements = 'Australian passport holders must obtain an eVisa before traveling to Tanzania. Apply at https://visa.immigration.go.tz at least 10 business days before departure. The tourist eVisa costs $50 USD. A yellow fever vaccination certificate is MANDATORY. Your passport must be valid for 6 months with 2 blank pages. Visas on arrival are no longer available for most nationalities.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'TZA') AND lang_code = 'en';


-- ────────────────────────────────────────────────────────────────
-- VISA REQUIRED — Key Countries
-- ────────────────────────────────────────────────────────────────

UPDATE country_translations SET
  visa_duration = '30-90 days (depending on visa type)',
  passport_validity = '6 months beyond entry date with 2 blank pages',
  visa_fee = '$109.50 AUD (tourist visa)',
  processing_time = '4-7 business days',
  official_visa_url = 'https://www.visaforchina.cn',
  arrival_card_required = 'Yes',
  additional_docs = '• Valid passport (6 months validity, 2 blank pages)\n• Completed visa application form (Form V.2013)\n• Recent passport photo (48x33mm, white background)\n• Round-trip flight booking\n• Hotel reservations for entire stay\n• Bank statement (last 3 months)\n• Travel insurance\n• Employment letter or proof of income\n• Invitation letter (if visiting friends/family)',
  visa_requirements = 'Australian passport holders must obtain a visa before traveling to China. Apply through a Chinese Visa Application Service Center (CVASC) in Sydney, Melbourne, Brisbane, or Perth at least 7 business days before departure. Tourist (L) visas are typically valid for 30 days (single entry) or up to 90 days (double/multiple entry). The fee is approximately $109.50 AUD. Submit your completed application, photo, flight and hotel bookings, bank statements, and employment letter. Note: Some Chinese cities offer 72-hour or 144-hour visa-free transit — check eligibility at your transit city.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'CHN') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 days (tourist visa) or 16 days (eVisa where available)',
  passport_validity = '6 months beyond visa expiry',
  visa_fee = '$50+ USD (tourist visa); eVisa $40 USD where available',
  processing_time = '10-20 business days (tourist visa); eVisa 4 days',
  official_visa_url = 'https://electronic-visa.kdmid.ru/',
  arrival_card_required = 'Yes (migration card at border)',
  additional_docs = '• Valid passport (6 months beyond visa expiry, 2 blank pages)\n• Completed visa application form\n• Passport photo\n• Letter of invitation from Russian tour operator/hotel\n• Travel insurance valid in Russia\n• Proof of accommodation\n• Visa fee payment',
  visa_requirements = 'Australian passport holders require a visa to visit Russia. Tourist visas (up to 30 days) require an invitation letter from a Russian hotel or tour operator. Apply at the Russian embassy/consulate at least 20 business days before travel. An eVisa (up to 16 days) may be available for certain entry points — check https://electronic-visa.kdmid.ru. Keep your migration card (issued at entry) safe — you must surrender it when leaving Russia. Check current travel advisories before planning travel.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'RUS') AND lang_code = 'en';


-- ────────────────────────────────────────────────────────────────
-- ADDITIONAL eVISA / VOA COUNTRIES
-- ────────────────────────────────────────────────────────────────

UPDATE country_translations SET
  visa_duration = '28 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = '$50 USD (eVisa)',
  processing_time = '3-5 business days',
  official_visa_url = 'https://evisa.moip.gov.mm/',
  arrival_card_required = 'Yes',
  additional_docs = '• Valid passport (6 months validity)\n• Digital passport photo\n• Return flight ticket\n• Proof of accommodation\n• Payment by credit/debit card',
  visa_requirements = 'Australian passport holders must obtain an eVisa before traveling to Myanmar. Apply at https://evisa.moip.gov.mm at least 5 business days before departure. The tourist eVisa costs $50 USD and allows a 28-day stay. Entry is only through designated airports (Yangon, Mandalay, Nay Pyi Taw) and border crossings. Check current travel advisories before planning travel.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'MMR') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 or 90 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = '$52-72 USD (eVisa)',
  processing_time = '1-3 business days',
  official_visa_url = 'https://www.evisa.gov.et/',
  arrival_card_required = 'Yes',
  additional_docs = '• Valid passport (6 months validity)\n• Digital passport photo\n• Return flight ticket\n• Proof of accommodation\n• Yellow fever vaccination certificate\n• Payment by credit card',
  visa_requirements = 'Australian passport holders must obtain an eVisa before traveling to Ethiopia. Apply at https://www.evisa.gov.et. Tourist eVisas cost $52 USD (30 days) or $72 USD (90 days). Processing usually takes 1-3 business days. A yellow fever vaccination certificate is required if arriving from an endemic country. Entry is via Bole International Airport (Addis Ababa) only for eVisa holders.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'ETH') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 days',
  passport_validity = '3 months beyond visa expiry',
  visa_fee = '$20 USD (eVisa)',
  processing_time = '2-3 business days',
  official_visa_url = 'https://e-visa.gov.uz/main',
  arrival_card_required = 'No',
  additional_docs = '• Valid passport (3+ months beyond visa expiry)\n• Digital passport photo\n• Proof of accommodation\n• Return ticket\n• Payment by credit card',
  visa_requirements = 'Australian passport holders can apply for an eVisa for Uzbekistan at https://e-visa.gov.uz. The eVisa costs $20 USD, allows a single-entry stay of 30 days, and processing takes 2-3 business days. Your passport must be valid for at least 3 months beyond the visa expiry date. Register with local authorities if staying more than 3 days in one location (hotels do this automatically).',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'UZB') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = '$35 USD',
  processing_time = '10-20 minutes at immigration',
  official_visa_url = 'https://laoevisa.gov.la/',
  arrival_card_required = 'Yes',
  additional_docs = '• Valid passport (6 months validity, 2 blank pages)\n• Passport photo (4x6cm)\n• Visa fee: $35 USD cash\n• Return/onward ticket\n• Proof of accommodation',
  visa_requirements = 'Australian passport holders can obtain a Laos visa on arrival at international airports (Vientiane, Luang Prabang, Pakse, Savannakhet) and most international land borders. The visa costs $35 USD (bring exact cash — ATMs may not be available at borders), plus you need a passport photo. Alternatively, apply for an eVisa at https://laoevisa.gov.la ($35 + $5 processing). Both options allow a 30-day stay, which can be extended at immigration offices for $2/day.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'LAO') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 days',
  passport_validity = '6 months beyond entry date',
  visa_fee = 'Free (visa on arrival)',
  processing_time = 'Instant at immigration',
  official_visa_url = 'https://immigration.gov.mv/',
  arrival_card_required = 'Yes (IMUGA Traveller Declaration — submit online before arrival)',
  additional_docs = '• Valid passport (6 months validity)\n• Return/onward ticket\n• Proof of accommodation (hotel/resort booking)\n• Proof of sufficient funds ($100 + $50/day)\n• IMUGA Traveller Declaration',
  visa_requirements = 'Australian passport holders receive a free 30-day visa on arrival in the Maldives. Submit the IMUGA Traveller Declaration at https://imuga.immigration.gov.mv within 96 hours before arrival. Present your passport, return ticket, and hotel/resort booking at immigration. Proof of sufficient funds ($100 + $50 per day) may be requested. The Maldives is an Islamic country — alcohol is only available at resort islands.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'MDV') AND lang_code = 'en';

UPDATE country_translations SET
  visa_duration = '30 days (extendable to 90)',
  passport_validity = '6 months beyond entry date',
  visa_fee = '$52 USD (Visa on Arrival) or $160 USD (eVisa/embassy)',
  processing_time = '30-60 minutes at border',
  official_visa_url = 'https://www.rree.gob.bo/webmre/',
  arrival_card_required = 'Yes',
  additional_docs = '• Valid passport (6 months validity, 2 blank pages)\n• Passport photo\n• Return/onward ticket\n• Proof of accommodation\n• Yellow fever vaccination certificate\n• Visa fee in USD cash',
  visa_requirements = 'Australian passport holders can obtain a Bolivia visa on arrival at major airports and some land borders. The tourist visa costs $52 USD (cash). A yellow fever vaccination certificate is required. Your passport must be valid for 6 months with 2 blank pages. The visa allows a 30-day stay, extendable up to 90 days at immigration offices. Alternatively, apply at a Bolivian embassy before travel.',
  last_verified = CURDATE()
WHERE country_id = (SELECT id FROM countries WHERE country_code = 'BOL') AND lang_code = 'en';


-- ════════════════════════════════════════════════════════════════
-- PHASE 2B: Update template countries (no specific override)
-- ════════════════════════════════════════════════════════════════
-- These are improved generic templates — much better than the old fake-URL templates.
-- They update countries WHERE they're NOT in the override list above.

-- Visa-free template countries (those not specifically overridden)
UPDATE country_translations ct
JOIN countries c ON ct.country_id = c.id
SET 
  ct.visa_duration = '90 days (check specific country allowance)',
  ct.passport_validity = '6 months beyond entry date (recommended)',
  ct.visa_fee = 'Free',
  ct.processing_time = 'Instant at border',
  ct.arrival_card_required = 'Check on arrival',
  ct.additional_docs = CONCAT('• Valid passport (6 months validity recommended)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds'),
  ct.visa_requirements = CONCAT('Australian passport holders can enter ', ct.country_name, ' visa-free for short-term tourism or business visits. Duration of stay varies — check with the embassy or consulate for the exact permitted period. Your passport should have at least 6 months validity from your date of entry. Carry proof of return travel, accommodation, and sufficient funds. Check the official immigration website for the most current entry requirements before travel.'),
  ct.last_verified = CURDATE()
WHERE ct.lang_code = 'en'
  AND c.visa_type = 'visa_free'
  AND c.country_code NOT IN (
    'AUT','BEL','CZE','DNK','EST','FIN','FRA','DEU','GRC','HUN',
    'ISL','ITA','LVA','LTU','LUX','MLT','NLD','NOR','POL','PRT',
    'SVK','SVN','ESP','SWE','CHE','HRV','LIE',
    'GBR','IRL','TUR','ROU','BGR','CYP','SRB','ALB','MNE','MKD','BIH','GEO','MDA','UKR',
    'JPN','KOR','HKG','SGP','MYS','THA','IDN','PHL','VNM','BRN','MNG','KAZ','KGZ',
    'USA','CAN','AUS','MEX','BRA','ARG','CHL','COL','PER','ECU','CRI','PAN',
    'ARE','ISR','QAT',
    'ZAF','MAR','TUN','MUS',
    'NZL','FJI','PNG'
  );

-- eVisa template countries
UPDATE country_translations ct
JOIN countries c ON ct.country_id = c.id
SET 
  ct.visa_duration = '30-90 days (varies by visa type)',
  ct.passport_validity = '6 months beyond entry date',
  ct.visa_fee = 'Varies — check official website',
  ct.processing_time = '3-7 business days',
  ct.arrival_card_required = 'Check requirements',
  ct.additional_docs = CONCAT('• Valid passport (6 months validity)\n• Digital passport photo\n• Completed online application\n• Return flight ticket\n• Proof of accommodation\n• Proof of sufficient funds\n• Travel insurance (recommended)\n• Payment by credit/debit card'),
  ct.visa_requirements = CONCAT('Australian passport holders must apply for an electronic visa (eVisa) before traveling to ', ct.country_name, '. Apply online through the official government portal at least 7 business days before your departure. Fees, duration, and processing times vary — check the official website for current rates. Your passport must be valid for at least 6 months from your date of entry. Print your approved eVisa and present it with your passport upon arrival.'),
  ct.last_verified = CURDATE()
WHERE ct.lang_code = 'en'
  AND c.visa_type = 'evisa'
  AND c.country_code NOT IN (
    'USA','CAN','AUS','IND','LKA','MMR','ETH','UZB','SAU','OMN','KEN','TZA'
  );

-- Visa on Arrival template countries
UPDATE country_translations ct
JOIN countries c ON ct.country_id = c.id
SET 
  ct.visa_duration = '15-30 days (varies)',
  ct.passport_validity = '6 months beyond entry date',
  ct.visa_fee = 'Varies — bring USD cash',
  ct.processing_time = '15-30 minutes at immigration',
  ct.arrival_card_required = 'Yes',
  ct.additional_docs = CONCAT('• Valid passport (6 months validity, 2 blank pages)\n• Passport photo\n• Visa fee in cash (USD recommended)\n• Return/onward ticket\n• Proof of accommodation\n• Proof of sufficient funds'),
  ct.visa_requirements = CONCAT('Australian passport holders can obtain a visa on arrival at ', ct.country_name, ' international airports and designated border crossings. Bring a passport photo, visa fee in cash (USD is widely accepted), return ticket, and accommodation proof. Your passport must be valid for at least 6 months with blank pages for the visa stamp. Processing takes 15-30 minutes at the immigration counter. Check the official website for current fees before travel.'),
  ct.last_verified = CURDATE()
WHERE ct.lang_code = 'en'
  AND c.visa_type = 'visa_on_arrival'
  AND c.country_code NOT IN (
    'KHM','NPL','LAO','MDV','JOR','EGY','BOL','PNG'
  );

-- Visa Required template countries
UPDATE country_translations ct
JOIN countries c ON ct.country_id = c.id
SET 
  ct.visa_duration = '30-90 days (varies by visa type)',
  ct.passport_validity = '6 months beyond intended stay',
  ct.visa_fee = 'Varies — check embassy website',
  ct.processing_time = '5-20 business days',
  ct.arrival_card_required = 'Yes',
  ct.additional_docs = CONCAT('• Valid passport (6 months validity, 2 blank pages)\n• Completed visa application form\n• Recent passport photos (2)\n• Full travel itinerary with flight bookings\n• Hotel/accommodation proof for entire stay\n• Bank statements (last 3 months)\n• Travel insurance\n• Employment letter or proof of income\n• Visa fee payment'),
  ct.visa_requirements = CONCAT('Australian passport holders must obtain a visa before traveling to ', ct.country_name, '. Contact the nearest embassy or consulate and apply at least 20 business days before your intended departure. Fees, requirements, and processing times vary depending on the visa type. Your passport must be valid for at least 6 months beyond your intended stay with 2 blank pages. Submit a completed application form, passport photos, travel itinerary, accommodation proof, financial statements, and travel insurance. Some countries may require an interview.'),
  ct.last_verified = CURDATE()
WHERE ct.lang_code = 'en'
  AND c.visa_type = 'visa_required'
  AND c.country_code NOT IN ('CHN','RUS');


-- ================================================================
-- PHASE 3: Fix URLs
-- ================================================================
-- Copy countries.official_url to country_translations.official_visa_url
-- ONLY for countries where the override didn't set a specific URL
-- and the current URL is NULL, empty, or a fake template URL

UPDATE country_translations ct
JOIN countries c ON ct.country_id = c.id
SET ct.official_visa_url = c.official_url
WHERE ct.lang_code = 'en'
  AND c.official_url IS NOT NULL
  AND c.official_url != ''
  AND (
    ct.official_visa_url IS NULL 
    OR ct.official_visa_url = ''
    OR ct.official_visa_url LIKE '%www.gov.___/%'
    OR ct.official_visa_url LIKE '%www.immigration.___.%'
    OR ct.official_visa_url LIKE 'https://evisa.___.gov%'
    OR ct.official_visa_url LIKE '%embassy___.%'
  );


-- ================================================================
-- COMMIT
-- ================================================================
COMMIT;

-- ================================================================
-- VERIFICATION QUERIES (run after import to check results)
-- ================================================================

-- Check visa_type distribution
SELECT visa_type, COUNT(*) as country_count 
FROM countries WHERE is_active = 1 
GROUP BY visa_type ORDER BY country_count DESC;

-- Spot-check key corrections
SELECT c.country_code, c.visa_type, ct.visa_duration, ct.visa_fee, 
       LEFT(ct.official_visa_url, 60) as url_preview
FROM countries c
JOIN country_translations ct ON c.id = ct.country_id
WHERE ct.lang_code = 'en' 
AND c.country_code IN ('FRA','DEU','GBR','USA','JPN','THA','IND','CHN','NZL','ZAF','BRA','ARE')
ORDER BY c.country_code;

-- Check for remaining NULL/empty/fake URLs
SELECT COUNT(*) as missing_urls
FROM country_translations ct
JOIN countries c ON ct.country_id = c.id
WHERE ct.lang_code = 'en'
  AND c.is_active = 1
  AND (ct.official_visa_url IS NULL OR ct.official_visa_url = '');

-- Check for remaining fake template URLs
SELECT c.country_code, ct.official_visa_url 
FROM country_translations ct
JOIN countries c ON ct.country_id = c.id
WHERE ct.lang_code = 'en'
  AND (ct.official_visa_url LIKE '%www.gov.___/%'
       OR ct.official_visa_url LIKE '%www.immigration.___.%');
