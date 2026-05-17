-- ============================================================
-- Visa Data Update — May 2026
-- Updates countries + English country_translations
-- for all 70 countries with accurate current information
-- Run: mysql -u USER -p DATABASE < update_visa_data_may2026.sql
-- ============================================================

SET NAMES utf8mb4;
SET foreign_key_checks = 0;

-- ============================================================
-- HELPER: update last_updated on all active countries
-- ============================================================
UPDATE countries SET last_updated = '2026-05-17' WHERE is_active = 1;

-- ============================================================
-- AMERICAS
-- ============================================================

-- United States
UPDATE countries SET visa_type='evisa', official_url='https://esta.cbp.dhs.gov' WHERE country_code='US';
UPDATE country_translations SET
  entry_summary='Visitors from Visa Waiver Program countries must obtain an ESTA (Electronic System for Travel Authorization) before boarding. Citizens of non-VWP countries require a B-1/B-2 visitor visa from a US embassy.',
  visa_requirements='42 countries participate in the US Visa Waiver Program (VWP) including Australia, UK, most EU nations, Japan, South Korea and New Zealand. VWP travelers must apply for ESTA online at least 72 hours before departure. Non-VWP travelers must apply for a B-1 (business) or B-2 (tourism) visa at a US consulate, requiring an interview, DS-160 form, and supporting documents.',
  visa_duration='Up to 90 days per visit',
  passport_validity='Valid for the full duration of stay (e-passport required for VWP)',
  visa_fee='ESTA: USD $21 | B-2 Visa: USD $185',
  processing_time='ESTA: Immediate–72 hours | B-2 Visa: 2–8 weeks',
  official_visa_url='https://esta.cbp.dhs.gov',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='US') AND lang_code='en';

-- Canada
UPDATE countries SET visa_type='evisa', official_url='https://www.canada.ca/en/immigration-refugees-citizenship/services/visit-canada/eta.html' WHERE country_code='CA';
UPDATE country_translations SET
  entry_summary='Citizens of visa-exempt countries must obtain an eTA (Electronic Travel Authorisation) before flying to Canada. Citizens of countries requiring a visitor visa must apply at a Canadian visa application centre.',
  visa_requirements='Citizens of 50+ visa-exempt countries (including the UK, EU nations, Australia, New Zealand, and Japan) require an eTA costing CAD $7, which is linked to the passport and valid for 5 years or until it expires. US citizens are exempt from eTA. All other nationalities require a Temporary Resident Visa (visitor visa) obtained from a Canadian visa centre.',
  visa_duration='Up to 6 months per visit',
  passport_validity='Valid for the duration of stay',
  visa_fee='eTA: CAD $7 | Visitor Visa: CAD $100',
  processing_time='eTA: Immediate–72 hours | Visitor Visa: 2–8 weeks',
  official_visa_url='https://www.canada.ca/en/immigration-refugees-citizenship/services/visit-canada/eta.html',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='CA') AND lang_code='en';

-- Mexico
UPDATE countries SET visa_type='visa_free', official_url='https://www.inm.gob.mx' WHERE country_code='MX';
UPDATE country_translations SET
  entry_summary='Mexico allows citizens of most Western countries to enter visa-free for up to 180 days. An FMM (Forma Migratoria Múltiple) tourist permit is issued on arrival or completed digitally.',
  visa_requirements='Citizens of over 65 countries, including all EU nations, USA, Canada, Australia, UK, Japan, South Korea and most of Latin America, can enter Mexico without a visa for up to 180 days for tourism. The FMM tourist card is issued automatically at the border or airport; some airlines embed it digitally. Travelers must show proof of onward travel and sufficient funds.',
  visa_duration='Up to 180 days',
  passport_validity='6 months beyond the entry date (recommended)',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://www.inm.gob.mx',
  arrival_card_required='Yes — FMM (digital or paper)',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='MX') AND lang_code='en';

-- Brazil
UPDATE countries SET visa_type='visa_free', official_url='https://www.gov.br/mre/en/consular-services/visas' WHERE country_code='BR';
UPDATE country_translations SET
  entry_summary='Brazil reinstated visa-free access for US, Canadian, and Australian citizens in 2023, joining the EU and many other nations. Most Western passport holders can stay up to 90 days per visit.',
  visa_requirements='Citizens of 90+ countries including the United States, Canada, Australia, all EU nations, UK and Japan may enter Brazil visa-free for tourism or business for up to 90 days, renewable for a further 90 days within a 180-day period. A valid return ticket and proof of funds are recommended. Citizens of countries without an agreement must apply for a visa at the Brazilian consulate.',
  visa_duration='Up to 90 days (extendable to 180 days per year)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://www.gov.br/mre/en/consular-services/visas',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='BR') AND lang_code='en';

-- Argentina
UPDATE countries SET visa_type='visa_free', official_url='https://www.migraciones.gov.ar' WHERE country_code='AR';
UPDATE country_translations SET
  entry_summary='Argentina grants visa-free entry to citizens of most Western countries for up to 90 days. Entry is straightforward at land, air and sea borders with a valid passport.',
  visa_requirements='Citizens of the USA, Canada, Australia, UK, all EU nations, Japan, South Korea and most of Latin America can enter Argentina visa-free for 90 days. The stay may be extended once at the Migraciones office for a further 90 days. A return ticket and proof of funds are recommended. Reciprocity fees previously applied to some nationalities have been abolished.',
  visa_duration='Up to 90 days (extendable to 180 days)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://www.migraciones.gov.ar',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='AR') AND lang_code='en';

-- Colombia
UPDATE countries SET visa_type='visa_free', official_url='https://www.cancilleria.gov.co/tramites_servicios/visas' WHERE country_code='CO';
UPDATE country_translations SET
  entry_summary='Colombia allows visa-free entry to citizens of most Western countries for up to 90 days per visit, with a maximum of 180 days per year. No pre-arrival visa or authorisation is required.',
  visa_requirements='Citizens of the US, Canada, Australia, UK, EU nations, Japan, and most of Latin America may enter Colombia visa-free for up to 90 days. The total stay must not exceed 180 days in a calendar year. Travelers must hold a valid passport, proof of return or onward travel, and evidence of sufficient funds. An additional 90-day extension can be requested from Migración Colombia.',
  visa_duration='Up to 90 days per visit (max 180 days/year)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://www.cancilleria.gov.co/tramites_servicios/visas',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='CO') AND lang_code='en';

-- Peru
UPDATE countries SET visa_type='visa_free', official_url='https://www.migraciones.gob.pe' WHERE country_code='PE';
UPDATE country_translations SET
  entry_summary='Peru permits visa-free entry for citizens of most Western countries for up to 90 days. Entry at Lima''s Jorge Chávez International Airport is efficient with e-passport lanes available.',
  visa_requirements='Citizens of over 80 countries including the US, Canada, Australia, UK and all EU nations can enter Peru without a visa for up to 90 days. Travelers must show a valid passport, return or onward ticket, and proof of accommodation. Extensions of up to 30 additional days may be granted by Migraciones upon payment of a fee.',
  visa_duration='Up to 90 days',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://www.migraciones.gob.pe',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='PE') AND lang_code='en';

-- Chile
UPDATE countries SET visa_type='visa_free', official_url='https://www.extranjeria.gob.cl' WHERE country_code='CL';
UPDATE country_translations SET
  entry_summary='Chile allows visa-free entry for citizens of most Western countries for up to 90 days. The country is one of Latin America''s most straightforward destinations for international visitors.',
  visa_requirements='Citizens of the US, Canada, Australia, UK, EU nations, Japan, South Korea and most countries in the Americas may enter Chile visa-free for tourism or business for 90 days. The stay can be extended at the Departamento de Extranjería for up to 90 additional days. Valid passport, return ticket and proof of funds are standard requirements.',
  visa_duration='Up to 90 days (extendable)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://www.extranjeria.gob.cl',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='CL') AND lang_code='en';

-- Cuba
UPDATE countries SET visa_type='visa_on_arrival', official_url='https://www.cubagob.cu' WHERE country_code='CU';
UPDATE country_translations SET
  entry_summary='Most visitors to Cuba require a Tourist Card (Tarjeta del Turista) which can be purchased at the airport or from airlines before departure. US citizens face additional restrictions and require a valid travel category.',
  visa_requirements='Citizens of most countries can visit Cuba with a Tourist Card (tarjeta del turista) — a pink slip for charter/US flights or green for all others — purchased from airlines, Cuban consulates, or at some airports. The card is valid for 30 days and extendable once for a further 30 days at an immigration office. US citizens may only visit under 12 authorised travel categories. Travelers must show proof of travel health insurance, proof of accommodation, and sufficient funds.',
  visa_duration='30 days (extendable to 60 days)',
  passport_validity='6 months beyond the intended stay',
  visa_fee='USD $25–75 (varies by purchase location)',
  processing_time='On arrival or pre-purchase',
  official_visa_url='https://www.cubagob.cu',
  arrival_card_required='Yes — Tourist Card (Tarjeta del Turista)',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='CU') AND lang_code='en';

-- Costa Rica
UPDATE countries SET visa_type='visa_free', official_url='https://migracion.go.cr' WHERE country_code='CR';
UPDATE country_translations SET
  entry_summary='Costa Rica welcomes citizens of most Western countries visa-free for up to 90 days. The country is a major ecotourism destination with straightforward entry procedures.',
  visa_requirements='Citizens of the US, Canada, Australia, UK, all EU nations, Japan, South Korea and most of Latin America may enter Costa Rica visa-free for up to 90 days for tourism. Travelers must hold a valid passport, an onward or return ticket, and proof of sufficient funds (approx. USD $100/day or a credit card). Longer stays require a residency permit or visa extension.',
  visa_duration='Up to 90 days',
  passport_validity='1 day beyond intended stay (minimum 30 days recommended)',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://migracion.go.cr',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='CR') AND lang_code='en';

-- ============================================================
-- EUROPE
-- ============================================================

-- United Kingdom
UPDATE countries SET visa_type='evisa', official_url='https://www.gov.uk/apply-electronic-travel-authorisation' WHERE country_code='GB';
UPDATE country_translations SET
  entry_summary='From 2025, visitors from eligible visa-exempt countries must obtain a UK Electronic Travel Authorisation (ETA) before travelling. Citizens of countries not covered by the ETA scheme require a Standard Visitor Visa.',
  visa_requirements='The UK ETA is mandatory for citizens of eligible non-visa, non-British/Irish nationals, including the US, Canada, Australia, New Zealand, EU nations, and many others. The ETA costs £10, is applied for via the UK ETA app or online, and is typically processed within 3 working days. It is linked to the passport and valid for multiple trips over 2 years or until the passport expires. Citizens not covered by ETA require a Standard Visitor Visa from a UK Visa Application Centre.',
  visa_duration='Up to 6 months per visit',
  passport_validity='Valid for the full duration of the stay',
  visa_fee='ETA: £10 | Standard Visitor Visa: £115',
  processing_time='ETA: Immediate–3 working days | Visitor Visa: 3–8 weeks',
  official_visa_url='https://www.gov.uk/apply-electronic-travel-authorisation',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='GB') AND lang_code='en';

-- Germany (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='DE';
UPDATE country_translations SET
  entry_summary='Germany is part of the Schengen Area. From 2025, travelers from visa-exempt non-EU countries (including the US, UK, Australia, Canada and many others) require an ETIAS (European Travel Information and Authorisation System) authorisation before arrival.',
  visa_requirements='The EU ETIAS is an electronic pre-travel screening for citizens of 60+ visa-exempt countries visiting Schengen states. It costs €7, is valid for 3 years or until the passport expires, and allows multiple entries. Citizens of countries requiring a Schengen visa must apply through the German embassy or consulate. Within the Schengen Area, travellers may stay up to 90 days in any 180-day period.',
  visa_duration='Up to 90 days in any 180-day period (Schengen rule)',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='DE') AND lang_code='en';

-- France (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='FR';
UPDATE country_translations SET
  entry_summary='France is part of the Schengen Area. Citizens of visa-exempt non-EU countries must hold a valid ETIAS authorisation before travelling to France and other Schengen states.',
  visa_requirements='The ETIAS authorisation (€7) is required for 60+ nationalities including the US, UK, Australia, Canada, Japan and South Korea. It covers the entire Schengen zone for 3 years. Citizens requiring a Schengen visa apply at the French consulate. The 90/180-day Schengen rule applies to all non-EU visitors.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='FR') AND lang_code='en';

-- Italy (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='IT';
UPDATE country_translations SET
  entry_summary='Italy is a member of the Schengen Area. Visitors from visa-exempt non-EU countries must obtain an ETIAS authorisation before travel. The famous 90/180-day rule governs stays across all Schengen nations.',
  visa_requirements='ETIAS (€7) is required for 60+ nationalities visiting Schengen member states including Italy. It is valid for 3 years or until the passport expires. Citizens of countries not covered by ETIAS must apply for a Schengen visa at the Italian consulate. Italy is also home to the Vatican (Holy See), a separate sovereign entity within Rome.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='IT') AND lang_code='en';

-- Spain (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='ES';
UPDATE country_translations SET
  entry_summary='Spain is part of the Schengen Area. Non-EU nationals from visa-exempt countries require a valid ETIAS authorisation before visiting Spain and other Schengen member states.',
  visa_requirements='ETIAS (€7) covers 60+ nationalities for multiple visits across Schengen countries for 3 years. Citizens from countries requiring a Schengen visa must apply at the Spanish consulate. The Schengen 90/180-day rule applies. Spain''s territories include the Canary Islands and Balearic Islands, which are also within the Schengen Area.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='ES') AND lang_code='en';

-- Netherlands (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='NL';
UPDATE country_translations SET
  entry_summary='The Netherlands is a Schengen Area member. Visitors from visa-exempt non-EU countries must obtain ETIAS authorisation before travel. Amsterdam''s Schiphol Airport is one of Europe''s busiest international hubs.',
  visa_requirements='ETIAS authorisation (€7) is mandatory for 60+ nationalities visiting Schengen member states. It is valid for 3 years with multiple entries. Citizens of countries requiring a Schengen visa apply at the Dutch embassy.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='NL') AND lang_code='en';

-- Switzerland (Schengen but not EU)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='CH';
UPDATE country_translations SET
  entry_summary='Switzerland participates in the Schengen Area but is not an EU member state. Visa-exempt non-EU citizens must hold a valid ETIAS before visiting Switzerland and other Schengen countries.',
  visa_requirements='The ETIAS (€7) authorisation covers Switzerland as part of the Schengen zone. Citizens of 60+ visa-exempt nationalities including the US, UK, Australia, Canada and others must apply online. Citizens from countries not in the ETIAS scheme apply for a Swiss/Schengen visa through the Swiss embassy.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='CH') AND lang_code='en';

-- Austria (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='AT';
UPDATE country_translations SET
  entry_summary='Austria is a Schengen member state. Non-EU citizens from visa-exempt countries need an ETIAS authorisation to enter Austria and travel throughout the Schengen zone.',
  visa_requirements='ETIAS (€7) applies to 60+ nationalities and covers all Schengen countries including Austria. It is valid for 3 years with unlimited entries within the 90/180-day limit. Other nationalities apply for a Schengen visa at the Austrian consulate.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='AT') AND lang_code='en';

-- Sweden (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='SE';
UPDATE country_translations SET
  entry_summary='Sweden is a Schengen Area member. Citizens of visa-exempt non-EU countries must have an ETIAS authorisation before entering Sweden and any other Schengen state.',
  visa_requirements='ETIAS (€7) authorisation is required for 60+ nationalities. It covers all Schengen countries for 3 years. Citizens of non-exempt countries apply for a Schengen visa at a Swedish embassy.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='SE') AND lang_code='en';

-- Norway (Schengen, not EU)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='NO';
UPDATE country_translations SET
  entry_summary='Norway is part of the Schengen Area but is not an EU member. Non-EU travelers from visa-exempt countries require an ETIAS authorisation before visiting Norway and the wider Schengen zone.',
  visa_requirements='ETIAS (€7) applies to Norway as a Schengen member. It covers 60+ nationalities for 3 years. Non-exempt citizens apply for a Norwegian/Schengen visa. Svalbard (Spitsbergen) has separate entry rules and does not require a visa for any nationality.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='NO') AND lang_code='en';

-- Denmark (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='DK';
UPDATE country_translations SET
  entry_summary='Denmark is a Schengen Area member. Non-EU visitors from visa-exempt countries must hold a valid ETIAS authorisation before travelling to Denmark and other Schengen countries.',
  visa_requirements='ETIAS (€7) is required for 60+ nationalities and covers the full Schengen zone including Denmark for 3 years. Note: Greenland and the Faroe Islands are not in the Schengen Area and have separate entry requirements.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='DK') AND lang_code='en';

-- Finland (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='FI';
UPDATE country_translations SET
  entry_summary='Finland is a Schengen member state. Non-EU nationals from visa-exempt countries are required to have an ETIAS authorisation before visiting Finland and travelling throughout the Schengen Area.',
  visa_requirements='ETIAS (€7) covers 60+ nationalities across all Schengen states including Finland for 3 years. Other nationalities apply for a Schengen visa at the Finnish embassy.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='FI') AND lang_code='en';

-- Belgium (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='BE';
UPDATE country_translations SET
  entry_summary='Belgium is a founding Schengen member state and home to EU institutions in Brussels. Non-EU travelers from visa-exempt countries must obtain an ETIAS authorisation before visiting.',
  visa_requirements='ETIAS (€7) is required for 60+ nationalities and is valid for all Schengen countries including Belgium for 3 years. Other nationalities apply for a Belgian/Schengen visa.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='BE') AND lang_code='en';

-- Portugal (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='PT';
UPDATE country_translations SET
  entry_summary='Portugal is a Schengen member state. Non-EU visitors from visa-exempt countries must carry a valid ETIAS authorisation. Portugal remains one of Europe''s most popular travel destinations.',
  visa_requirements='ETIAS (€7) applies to 60+ nationalities visiting Schengen countries including Portugal for 3 years. The Azores and Madeira archipelagos are also in the Schengen zone. Citizens not covered by ETIAS apply for a Portuguese/Schengen visa.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='PT') AND lang_code='en';

-- Ireland (not Schengen, visa-free for many)
UPDATE countries SET visa_type='visa_free', official_url='https://www.irishimmigration.ie' WHERE country_code='IE';
UPDATE country_translations SET
  entry_summary='Ireland is not a member of the Schengen Area and maintains its own visa policy. Citizens of most Western countries can visit visa-free for up to 90 days. Ireland does not participate in ETIAS.',
  visa_requirements='Citizens of the US, Canada, Australia, New Zealand, Japan, South Korea, most EU nations and many others can enter Ireland without a visa for up to 90 days per visit. Irish immigration officers may ask for proof of accommodation, return travel and sufficient funds. Citizens of countries not in the visa-waiver arrangement must apply for an Irish short-stay visa (C-visa) from an Irish consulate.',
  visa_duration='Up to 90 days per visit',
  passport_validity='Valid for the full duration of stay',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://www.irishimmigration.ie',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='IE') AND lang_code='en';

-- Greece (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='GR';
UPDATE country_translations SET
  entry_summary='Greece is a Schengen member state and one of Europe''s top tourist destinations. Non-EU visitors from visa-exempt countries must hold a valid ETIAS authorisation.',
  visa_requirements='ETIAS (€7) covers all Schengen nations including Greece for 3 years. The Greek islands including Crete, Santorini and Mykonos are within the Schengen zone. Citizens not covered by ETIAS apply for a Schengen visa at the Greek embassy.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='GR') AND lang_code='en';

-- Poland (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='PL';
UPDATE country_translations SET
  entry_summary='Poland is a full Schengen member state. Non-EU visitors from visa-exempt countries need an ETIAS authorisation before travelling to Poland and the wider Schengen Area.',
  visa_requirements='ETIAS (€7) is required for 60+ nationalities including the US, UK, Australia and Canada for all Schengen states including Poland. Other nationalities apply for a Polish/Schengen visa.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='PL') AND lang_code='en';

-- Czech Republic (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='CZ';
UPDATE country_translations SET
  entry_summary='The Czech Republic (Czechia) is a Schengen member state. Non-EU nationals from visa-exempt countries must obtain an ETIAS authorisation before visiting Prague and other Czech destinations.',
  visa_requirements='ETIAS (€7) covers all Schengen states including Czechia for 3 years for 60+ nationalities. Other nationalities apply for a Czech/Schengen visa at the Czech embassy.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='CZ') AND lang_code='en';

-- Hungary (Schengen + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='HU';
UPDATE country_translations SET
  entry_summary='Hungary is a Schengen member state. Non-EU visitors from visa-exempt countries must hold a valid ETIAS authorisation to enter Hungary and travel throughout the Schengen Area.',
  visa_requirements='ETIAS (€7) covers Hungary as part of the Schengen zone. Valid for 60+ nationalities for 3 years with multiple entries. Other nationalities apply for a Hungarian/Schengen visa.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='HU') AND lang_code='en';

-- Romania (Schengen since 2025 + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='RO';
UPDATE country_translations SET
  entry_summary='Romania joined the full Schengen Area in January 2025. Non-EU visitors from visa-exempt countries now require an ETIAS authorisation to enter Romania and travel across the Schengen zone.',
  visa_requirements='ETIAS (€7) now covers Romania as a full Schengen member, applicable to 60+ nationalities for 3 years. Prior to 2025, Romania was not in Schengen; travelers should use ETIAS rather than seeking a separate Romanian visa.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='RO') AND lang_code='en';

-- Croatia (Schengen since 2023 + ETIAS)
UPDATE countries SET visa_type='evisa', official_url='https://travel.ec.europa.eu/etias_en' WHERE country_code='HR';
UPDATE country_translations SET
  entry_summary='Croatia joined the Schengen Area in January 2023. Non-EU visitors from visa-exempt countries must hold a valid ETIAS authorisation to enter Croatia and travel throughout the Schengen zone.',
  visa_requirements='ETIAS (€7) covers Croatia as part of the Schengen zone, applicable to 60+ nationalities. Croatia also adopted the Euro in January 2023. Other nationalities apply for a Croatian/Schengen visa.',
  visa_duration='Up to 90 days in any 180-day period',
  passport_validity='3 months beyond the intended departure date',
  visa_fee='ETIAS: €7 | Schengen Visa: €90',
  processing_time='ETIAS: Immediate–4 working days | Schengen Visa: 2–6 weeks',
  official_visa_url='https://travel.ec.europa.eu/etias_en',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='HR') AND lang_code='en';

-- Russia
UPDATE countries SET visa_type='visa_required', official_url='https://www.kdmid.ru/en/services/visa.aspx' WHERE country_code='RU';
UPDATE country_translations SET
  entry_summary='Russia requires a visa for citizens of most Western countries. Due to the ongoing conflict in Ukraine, many governments advise against all travel to Russia. Consular services may be limited in several countries.',
  visa_requirements='Most Western nationals require a tourist or business visa issued by a Russian embassy or consulate. Applications require an invitation letter, visa form, passport photos and a fee. Tourist e-visas are available for some nationalities entering via certain ports. Travel advisories from the US, UK, EU, Australia and Canada all currently warn against travel to Russia.',
  visa_duration='Up to 30 days for tourist visas (single or double entry)',
  passport_validity='6 months beyond the date of visa expiry',
  visa_fee='Approximately USD $50–200 depending on nationality and visa type',
  processing_time='4 business days to 4 weeks',
  official_visa_url='https://www.kdmid.ru/en/services/visa.aspx',
  arrival_card_required='Yes — migration card issued on arrival',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='RU') AND lang_code='en';

-- Turkey
UPDATE countries SET visa_type='evisa', official_url='https://www.evisa.gov.tr' WHERE country_code='TR';
UPDATE country_translations SET
  entry_summary='Turkey offers an e-Visa for citizens of over 100 countries. The e-Visa is applied for online and must be obtained before travel. Some nationalities can also get a visa on arrival or enter visa-free.',
  visa_requirements='The Turkish e-Visa costs approximately USD $50–80 (varies by nationality) and is valid for 180 days with single or multiple entries for up to 90 days. Citizens of some countries (including Japan, Germany and several others) may enter visa-free. Applications are made at the official e-visa portal; processing is usually instant to a few hours.',
  visa_duration='Up to 90 days per stay (single or multiple entry)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Approx. USD $50–80 (varies by nationality)',
  processing_time='Immediate to a few hours',
  official_visa_url='https://www.evisa.gov.tr',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='TR') AND lang_code='en';

-- Ukraine
UPDATE countries SET visa_type='visa_free', official_url='https://visitukraine.today' WHERE country_code='UA';
UPDATE country_translations SET
  entry_summary='Ukraine allows visa-free entry for citizens of most Western countries for up to 90 days. Due to the ongoing war, most governments strongly advise against all travel to Ukraine at this time.',
  visa_requirements='Citizens of the US, EU, UK, Canada, Australia, Japan and many others can enter Ukraine visa-free for 90 days. However, the ongoing conflict means that safe entry is extremely limited and most commercial flights into Ukraine are suspended. Only essential travel is possible via land borders with EU neighbours such as Poland, Slovakia, Hungary and Romania.',
  visa_duration='Up to 90 days',
  passport_validity='3 months beyond the intended stay',
  visa_fee='Free',
  processing_time='On arrival (where accessible)',
  official_visa_url='https://visitukraine.today',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='UA') AND lang_code='en';

-- ============================================================
-- ASIA-PACIFIC
-- ============================================================

-- Australia
UPDATE countries SET visa_type='evisa', official_url='https://immi.homeaffairs.gov.au/visas/getting-a-visa/visa-listing/electronic-travel-authority-601' WHERE country_code='AU';
UPDATE country_translations SET
  entry_summary='Australia requires most international visitors to obtain an Electronic Travel Authority (ETA) or eVisitor visa before arrival. Both are applied for online and are free or low-cost.',
  visa_requirements='Citizens of 8 countries (US, Canada, Japan, Singapore, South Korea, Malaysia, Hong Kong and Brunei) qualify for an ETA (Subclass 601) via the AUS ETA app for AUD $20. Citizens of 56 European, Scandinavian and select other countries use the eVisitor (Subclass 651) which is free and applied for on the IMMI website. Other nationalities require a Tourist Visa (Subclass 600). All visas allow stays of up to 3 months per visit within a 12-month validity window.',
  visa_duration='Up to 3 months per visit (within 12-month validity)',
  passport_validity='6 months beyond the date of travel',
  visa_fee='ETA: AUD $20 (app fee) | eVisitor: Free | Tourist Visa: AUD $190',
  processing_time='ETA/eVisitor: Immediate to 24 hours | Tourist Visa: 2–8 weeks',
  official_visa_url='https://immi.homeaffairs.gov.au',
  arrival_card_required='No (Incoming Passenger Card now digital)',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='AU') AND lang_code='en';

-- New Zealand
UPDATE countries SET visa_type='evisa', official_url='https://www.immigration.govt.nz/new-zealand-visas/apply-for-a-visa/about-visa/nzeta' WHERE country_code='NZ';
UPDATE country_translations SET
  entry_summary='New Zealand requires visitors from visa-waiver countries to apply for an NZeTA (New Zealand Electronic Travel Authority) before departure. The NZeTA is a simple online application.',
  visa_requirements='Citizens of 60+ countries including the US, UK, EU nations, Australia, Japan, Canada and South Korea must obtain an NZeTA before flying to New Zealand. The fee is NZD $17 via the NZeTA app or NZD $23 online. An International Visitor Conservation and Tourism Levy (IVL) of NZD $35 also applies for most visitors. NZeTA is typically approved within 72 hours and linked to the passport for multiple entries. Other nationalities require a Visitor Visa.',
  visa_duration='Up to 3 months per visit',
  passport_validity='3 months beyond the date of intended departure',
  visa_fee='NZeTA: NZD $17 (app) or $23 (online) + IVL NZD $35',
  processing_time='NZeTA: Immediate–72 hours | Visitor Visa: 3–8 weeks',
  official_visa_url='https://www.immigration.govt.nz/new-zealand-visas/apply-for-a-visa/about-visa/nzeta',
  arrival_card_required='No (digital arrival process)',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='NZ') AND lang_code='en';

-- Japan
UPDATE countries SET visa_type='visa_free', official_url='https://www.mofa.go.jp/j_info/visit/visa/index.html' WHERE country_code='JP';
UPDATE country_translations SET
  entry_summary='Japan allows visa-free entry for citizens of over 60 countries for up to 90 days for tourism. Japan remains one of the world''s most visited destinations with streamlined immigration procedures.',
  visa_requirements='Citizens of 68+ countries including the US, UK, Canada, Australia, New Zealand, all EU nations, and many others can visit Japan for up to 90 days without a visa. Travelers must complete a Japan Web registration before arrival for faster processing. A valid return or onward ticket and sufficient funds are recommended. Citizens not on the visa-free list must apply for a tourist visa from a Japanese embassy.',
  visa_duration='Up to 90 days (non-extendable for visa-free entry)',
  passport_validity='Valid for the full duration of stay',
  visa_fee='Free (visa-free) | Visa: Varies (~USD $35–60)',
  processing_time='On arrival (visa-free) | 5–10 working days (visa)',
  official_visa_url='https://www.mofa.go.jp/j_info/visit/visa/index.html',
  arrival_card_required='No (digital via Japan Web)',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='JP') AND lang_code='en';

-- China
UPDATE countries SET visa_type='visa_required', official_url='https://www.visaforchina.cn' WHERE country_code='CN';
UPDATE country_translations SET
  entry_summary='China requires a visa for most nationalities, though it has expanded its visa-free list to 30+ countries since 2023. Most Western travelers must apply for a tourist (L) visa from a Chinese embassy before departure.',
  visa_requirements='Citizens of over 30 countries, including France, Germany, Italy, Netherlands, Spain, Switzerland, Belgium, Ireland, Hungary, Austria, Portugal, Luxembourg, Australia, South Korea, Japan, Singapore, Malaysia, UAE, Saudi Arabia and others, can now enter China visa-free for 15–30 days. Citizens of the US, UK and Canada must apply for a Chinese tourist visa (L visa) at a Chinese consulate. Applications require a completed form, passport photos, hotel bookings and return flight. The 144-hour transit visa-free policy applies at major airports.',
  visa_duration='Up to 30 days (visa-free eligible); L visa: 30–90 days',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Varies by nationality: approx. USD $140–180 for US citizens',
  processing_time='4 business days to 2 weeks',
  official_visa_url='https://www.visaforchina.cn',
  arrival_card_required='Yes — Arrival Card',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='CN') AND lang_code='en';

-- South Korea
UPDATE countries SET visa_type='visa_free', official_url='https://www.visa.go.kr' WHERE country_code='KR';
UPDATE country_translations SET
  entry_summary='South Korea grants visa-free entry to citizens of over 100 countries for up to 90 days. The K-ETA pre-travel screening requirement has been waived for most eligible nationalities.',
  visa_requirements='Citizens of the US, UK, Canada, Australia, New Zealand, EU nations, Japan and many others can enter South Korea without a visa for 90 days. The K-ETA (Korea Electronic Travel Authorisation) has been waived for eligible nationalities through at least 2026. Travelers complete arrival declaration digitally. Citizens not in the visa-free arrangement apply for a C-3 short-term visit visa from a Korean consulate.',
  visa_duration='Up to 90 days',
  passport_validity='Valid for the full duration of stay',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://www.visa.go.kr',
  arrival_card_required='No (digital)',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='KR') AND lang_code='en';

-- Singapore
UPDATE countries SET visa_type='visa_free', official_url='https://www.ica.gov.sg/enter-depart/entry_requirement' WHERE country_code='SG';
UPDATE country_translations SET
  entry_summary='Singapore grants visa-free entry to citizens of over 160 countries. It is one of the most accessible destinations in the world with an efficient modern airport and immigration system.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, New Zealand, Japan, South Korea and most other countries can enter Singapore visa-free for 30–90 days depending on nationality. An SG Arrival Card (SGAC) must be completed online within 3 days before arrival. A valid return or onward ticket and evidence of sufficient funds are required by immigration.',
  visa_duration='30–90 days (varies by passport)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free',
  processing_time='On arrival (after SGAC submission)',
  official_visa_url='https://www.ica.gov.sg/enter-depart/entry_requirement',
  arrival_card_required='Yes — SG Arrival Card (online, free)',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='SG') AND lang_code='en';

-- Thailand
UPDATE countries SET visa_type='visa_free', official_url='https://www.thaievisa.go.th' WHERE country_code='TH';
UPDATE country_translations SET
  entry_summary='Thailand extended visa-free stays for most Western countries to 60 days in 2024, up from 30 days. This applies to tourism and is extendable by a further 30 days at an immigration office.',
  visa_requirements='Citizens of 57+ countries including the US, UK, EU nations, Canada, Australia, New Zealand, Japan and South Korea can enter Thailand visa-free for 60 days for tourism. A 30-day extension can be obtained at any immigration office (THB 1,900). Travelers staying longer may apply for a tourist visa (TR visa, 60 days) or a Thailand Long-Term Resident (LTR) visa. The TM30 registration is required within 24 hours of accommodation check-in.',
  visa_duration='60 days (extendable by 30 days)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free (visa-free) | Tourist Visa: Approx. USD $40',
  processing_time='On arrival (visa-free)',
  official_visa_url='https://www.thaievisa.go.th',
  arrival_card_required='No (TM6 Arrival Card discontinued 2022)',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='TH') AND lang_code='en';

-- Indonesia
UPDATE countries SET visa_type='visa_free', official_url='https://molina.imigrasi.go.id' WHERE country_code='ID';
UPDATE country_translations SET
  entry_summary='Indonesia offers visa-free entry to citizens of 15 ASEAN-partnered and select other countries. Most other Western travelers can obtain a Visa on Arrival (VoA) or an e-VOA upon arrival at major airports.',
  visa_requirements='Citizens of Australia, France, Germany, Italy, Netherlands, UK, US, Canada and many others can obtain a Visa on Arrival (IDR 500,000 / ~USD $30) at major international airports and seaports. The e-VOA can also be applied for in advance via the Molina app. Both options allow a 30-day stay extendable once to 60 days. Citizens of 15 countries including ASEAN nations, Peru and Chile are exempt from the VoA fee.',
  visa_duration='30 days (extendable once to 60 days)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='VoA/e-VOA: IDR 500,000 (~USD $30) | Exempt: Free',
  processing_time='On arrival or pre-approval via app',
  official_visa_url='https://molina.imigrasi.go.id',
  arrival_card_required='Yes — customs/health declaration (electronic)',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='ID') AND lang_code='en';

-- Malaysia
UPDATE countries SET visa_type='visa_free', official_url='https://www.imi.gov.my' WHERE country_code='MY';
UPDATE country_translations SET
  entry_summary='Malaysia offers visa-free entry to citizens of over 160 countries. Most Western passport holders can stay for 30–90 days without a visa for tourism or business.',
  visa_requirements='Citizens of the US, UK, Australia, New Zealand, EU nations, Canada, Japan and South Korea can enter Malaysia visa-free for 90 days. Citizens of most other countries receive 30 days visa-free. An MDAC (Malaysia Digital Arrival Card) must be submitted online within 3 days before arrival. Citizens of India and China (among others) require a visa but may be eligible for the eNTRI or eVISA system.',
  visa_duration='30–90 days (varies by passport)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free (visa-free)',
  processing_time='On arrival (after MDAC submission)',
  official_visa_url='https://www.imi.gov.my',
  arrival_card_required='Yes — Malaysia Digital Arrival Card (free, online)',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='MY') AND lang_code='en';

-- Philippines
UPDATE countries SET visa_type='visa_free', official_url='https://eservices.immigration.gov.ph' WHERE country_code='PH';
UPDATE country_translations SET
  entry_summary='The Philippines grants visa-free entry to citizens of over 150 countries for an initial 30 days, extendable at the Bureau of Immigration for up to a total of 59 days and beyond.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, Japan and most countries can enter the Philippines without a visa for 30 days. The stay can be extended at any Bureau of Immigration office for fees: 29-day extensions are available up to a total of 3 years for tourism. A valid return or onward ticket is a firm entry requirement. Pre-registration is not required.',
  visa_duration='30 days (extendable up to 59 days and beyond)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free (initial 30 days) | Extensions: PHP 1,000+',
  processing_time='On arrival',
  official_visa_url='https://eservices.immigration.gov.ph',
  arrival_card_required='Yes — Bureau of Immigration Arrival Card',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='PH') AND lang_code='en';

-- Vietnam
UPDATE countries SET visa_type='evisa', official_url='https://evisa.xuatnhapcanh.gov.vn' WHERE country_code='VN';
UPDATE country_translations SET
  entry_summary='Vietnam offers a 90-day multiple-entry e-Visa for citizens of most countries. Citizens of 13 countries also qualify for a unilateral visa exemption for 45 days without any pre-registration.',
  visa_requirements='The Vietnam e-Visa (USD $25) allows a 90-day stay for single or multiple entries and is available to most nationalities. Applications are made online at the official portal. Citizens of the UK, France, Germany, Italy, Spain, Japan, South Korea, Russia, and a few others enjoy a unilateral visa exemption for 45 days without any visa or pre-registration. Entry is available at all international airports, seaports and most land border crossings.',
  visa_duration='90 days (e-Visa) or 45 days (visa-free eligible)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='e-Visa: USD $25 | Visa-free eligible: Free',
  processing_time='e-Visa: 3 working days',
  official_visa_url='https://evisa.xuatnhapcanh.gov.vn',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='VN') AND lang_code='en';

-- India
UPDATE countries SET visa_type='evisa', official_url='https://indianvisaonline.gov.in/evisa/tvoa.html' WHERE country_code='IN';
UPDATE country_translations SET
  entry_summary='India offers an e-Visa (eTV) for citizens of 166 countries for tourism, business, medical and conference purposes. The e-Visa is applied for online and must be obtained before travel.',
  visa_requirements='India''s e-Tourist Visa allows stays of 30 days (double entry) or 1 year (multiple entry). The 1-year e-Visa allows a continuous stay of up to 90 days from the date of first entry. Applications require a digital passport photo, scanned passport bio page, arrival/return flight details and payment. The e-Visa must be used within 120 days of issue and is linked to the passport. Citizens of Pakistan, Afghanistan and certain other countries are not eligible for e-Visa.',
  visa_duration='30 days (double entry) or up to 1 year (multiple entry, max 90 days/stay)',
  passport_validity='6 months beyond the intended stay with at least 2 blank pages',
  visa_fee='USD $25–80 depending on nationality and visa duration',
  processing_time='72 hours (apply at least 4 days before travel)',
  official_visa_url='https://indianvisaonline.gov.in/evisa/tvoa.html',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='IN') AND lang_code='en';

-- Pakistan
UPDATE countries SET visa_type='evisa', official_url='https://visa.nadra.gov.pk' WHERE country_code='PK';
UPDATE country_translations SET
  entry_summary='Pakistan offers an e-Visa for citizens of over 170 countries for tourism, business and visiting family. The online system has made visiting Pakistan more accessible than in previous years.',
  visa_requirements='Pakistan''s e-Visa portal allows citizens of most countries to apply for a tourist visa (single entry, 30 days), business visa or family visit visa online. Processing takes 7–10 business days. Citizens of some countries may require additional approval which can take up to 4 weeks. Visitors to Pakistan''s tourism regions including Gilgit-Baltistan and KPK may need additional permits for certain areas.',
  visa_duration='30 days (tourist) to 90 days (business)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Approx. USD $50–80',
  processing_time='7–10 business days (standard); up to 4 weeks (some nationalities)',
  official_visa_url='https://visa.nadra.gov.pk',
  arrival_card_required='Yes',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='PK') AND lang_code='en';

-- Bangladesh
UPDATE countries SET visa_type='visa_on_arrival', official_url='https://visa.gov.bd' WHERE country_code='BD';
UPDATE country_translations SET
  entry_summary='Bangladesh offers visa on arrival for citizens of a number of countries at Dhaka''s Hazrat Shahjalal International Airport. An online e-Visa is also available for citizens of most countries.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, Japan and South Korea can obtain a visa on arrival at Dhaka and Chittagong airports (USD $51 for a 30-day single entry visa). The e-Visa can also be applied for online. A return or onward ticket, proof of accommodation and sufficient funds are required. Citizens of India and some other nationalities have specific bilateral arrangements.',
  visa_duration='Up to 30 days (extendable at DOEIH)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Approx. USD $51 (visa on arrival)',
  processing_time='Visa on arrival: On arrival | e-Visa: 3–7 working days',
  official_visa_url='https://visa.gov.bd',
  arrival_card_required='Yes',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='BD') AND lang_code='en';

-- Sri Lanka
UPDATE countries SET visa_type='evisa', official_url='https://www.srilankaevisit.gov.lk' WHERE country_code='LK';
UPDATE country_translations SET
  entry_summary='Sri Lanka requires all visitors (except Indian nationals with certain permits) to obtain an Electronic Travel Authorisation (ETA) online before arrival. The ETA is quick and straightforward to apply for.',
  visa_requirements='Sri Lanka''s ETA (USD $20 for tourists) is mandatory for citizens of all countries except Singapore, which is visa-free. The ETA allows a 30-day stay on initial entry, extendable up to 90 days at the Department of Immigration. Applications are made at the official portal and are usually approved within 24 hours. Multiple entries are possible within the 6-month validity period.',
  visa_duration='30 days (extendable up to 90 days)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='USD $20 (tourism) | USD $35 (business)',
  processing_time='Immediate to 24 hours',
  official_visa_url='https://www.srilankaevisit.gov.lk',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='LK') AND lang_code='en';

-- Nepal
UPDATE countries SET visa_type='visa_on_arrival', official_url='https://www.immigration.gov.np' WHERE country_code='NP';
UPDATE country_translations SET
  entry_summary='Nepal offers visa on arrival for citizens of most countries at Tribhuvan International Airport in Kathmandu and several land border crossings. Indian and Chinese citizens have separate bilateral arrangements.',
  visa_requirements='Citizens of all countries except India (visa-free) and some others can obtain a visa on arrival in Nepal. Available durations are 15 days (USD $30), 30 days (USD $50) and 90 days (USD $125). Multiple-entry visas are also available. An online visa application (Nepal e-Visa) can be submitted in advance to speed up arrival processing. Trekking permits (TIMS card and national park permits) are required separately for trekking in Nepal.',
  visa_duration='15, 30 or 90 days (multiple entry available)',
  passport_validity='6 months beyond the date of entry with at least 2 blank pages',
  visa_fee='USD $30 (15 days) | USD $50 (30 days) | USD $125 (90 days)',
  processing_time='On arrival',
  official_visa_url='https://www.immigration.gov.np',
  arrival_card_required='Yes',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='NP') AND lang_code='en';

-- Maldives
UPDATE countries SET visa_type='visa_on_arrival', official_url='https://imuga.immigration.gov.mv' WHERE country_code='MV';
UPDATE country_translations SET
  entry_summary='The Maldives grants a free 30-day visa on arrival to all nationalities. No pre-arrival visa application is required, making it one of the most straightforward destinations in the world to enter.',
  visa_requirements='All nationalities receive a free 30-day visa on arrival in the Maldives. To qualify, travelers must hold a confirmed hotel booking or resort reservation, a valid return or onward ticket, and have sufficient funds (USD $100 per day recommended). The stay can be extended by up to 60 days at the Department of Immigration for a fee. An IMUGA digital arrival card must be submitted online before departure.',
  visa_duration='30 days on arrival (extendable up to 90 days)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://imuga.immigration.gov.mv',
  arrival_card_required='Yes — IMUGA Arrival Card (online, free)',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='MV') AND lang_code='en';

-- Hong Kong
UPDATE countries SET visa_type='visa_free', official_url='https://www.immd.gov.hk/eng/services/visas/visit-transit/visit-visa-entry-permit.html' WHERE country_code='HK';
UPDATE country_translations SET
  entry_summary='Hong Kong operates a separate immigration policy from mainland China. Citizens of over 170 countries can enter without a visa for stays ranging from 7 to 180 days, depending on nationality.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, New Zealand and Japan can enter Hong Kong without a visa for 90 days. Citizens of most Commonwealth countries and many others receive between 14 and 180 days visa-free. Those wishing to stay longer may apply for an extension from the Immigration Department. Note: Entry to mainland China requires a separate Chinese visa.',
  visa_duration='14–90 days (varies by passport)',
  passport_validity='1 month beyond the intended stay',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://www.immd.gov.hk/eng/services/visas/visit-transit/visit-visa-entry-permit.html',
  arrival_card_required='Yes — Arrival Card (at airport)',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='HK') AND lang_code='en';

-- Taiwan
UPDATE countries SET visa_type='visa_free', official_url='https://www.boca.gov.tw/cp-222-4388-7767e-1.html' WHERE country_code='TW';
UPDATE country_translations SET
  entry_summary='Taiwan grants visa-free entry to citizens of 65+ countries for 30–90 days. Taiwan has an efficient and modern immigration system with e-Gate lanes available for eligible passport holders.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, New Zealand and Japan can enter Taiwan visa-free for 90 days. Some nationalities receive 30 days. An ROC eGate pre-registration is recommended for eligible travelers for faster processing. Citizens not on the visa-free list must apply for a visitor visa at a Taiwanese representative office. Note: Taiwan is not a UN member; diplomatic arrangements vary.',
  visa_duration='30–90 days (varies by passport)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://www.boca.gov.tw',
  arrival_card_required='Yes — Arrival Card',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='TW') AND lang_code='en';

-- Cambodia
UPDATE countries SET visa_type='evisa', official_url='https://www.evisa.gov.kh' WHERE country_code='KH';
UPDATE country_translations SET
  entry_summary='Cambodia offers an e-Visa for citizens of most countries, which must be applied for online before travel. Visa on arrival is also available at major international airports and border crossings.',
  visa_requirements='The Cambodian e-Visa (USD $30 + USD $6 processing fee) is valid for 30 days single entry and must be used within 3 months of issue. Visa on arrival (USD $35 for tourist) is available at Phnom Penh and Siem Reap international airports and major land borders. Applications require a passport photo, passport scan and card payment. Citizens of ASEAN nations receive a 14–30 day visa-free stay.',
  visa_duration='30 days (extendable to 60 days at Department of Immigration)',
  passport_validity='6 months beyond the date of entry with at least 1 blank page',
  visa_fee='e-Visa: USD $36 | Visa on arrival: USD $35',
  processing_time='e-Visa: 3 business days | Visa on arrival: On arrival',
  official_visa_url='https://www.evisa.gov.kh',
  arrival_card_required='Yes — Arrival/Departure Card',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='KH') AND lang_code='en';

-- Myanmar
UPDATE countries SET visa_type='visa_required', official_url='https://evisa.moip.gov.mm' WHERE country_code='MM';
UPDATE country_translations SET
  entry_summary='Myanmar (Burma) requires a visa for most nationalities. Due to the ongoing political crisis following the 2021 military coup, most Western governments advise against all travel to Myanmar.',
  visa_requirements='Myanmar requires tourists to apply for a tourist visa in advance at a Myanmar embassy or via the e-Visa portal. The e-Visa has been intermittently suspended. Tourism is severely restricted and most international flights have been disrupted. The security situation is volatile and travel is not recommended by the governments of the US, UK, Australia, EU and others.',
  visa_duration='28 days (tourist visa)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Approx. USD $50',
  processing_time='3–5 business days',
  official_visa_url='https://evisa.moip.gov.mm',
  arrival_card_required='Yes',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='MM') AND lang_code='en';

-- ============================================================
-- MIDDLE EAST
-- ============================================================

-- UAE
UPDATE countries SET visa_type='visa_on_arrival', official_url='https://u.ae/en/information-and-services/visa-and-emirates-id/do-you-need-an-entry-permit-to-visit-dubai/entering-dubai' WHERE country_code='AE';
UPDATE country_translations SET
  entry_summary='The UAE grants visa-on-arrival or visa-free access to citizens of over 50 countries for 30–90 days. Travelers from other countries can obtain a pre-approved e-Visa through UAE immigration or airlines.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, New Zealand, Japan and South Korea receive visa-on-arrival or visa-free entry in the UAE for 30 days (extendable to 90 days free of charge). Citizens of GCC states (Saudi Arabia, Kuwait, Qatar, Bahrain, Oman) are visa-free for unlimited stays. Other nationalities apply for a pre-entry visa through UAE immigration or via Dubai-based airlines (Emirates, flydubai). A valid passport, return ticket, and hotel booking are standard requirements.',
  visa_duration='30–90 days on arrival (visa-free eligible)',
  passport_validity='6 months beyond the date of entry with at least 1 blank page',
  visa_fee='Free (eligible countries) | Other nationalities: AED 200–400',
  processing_time='On arrival or 3–5 working days (pre-entry visa)',
  official_visa_url='https://u.ae/en/information-and-services/visa-and-emirates-id',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='AE') AND lang_code='en';

-- Saudi Arabia
UPDATE countries SET visa_type='evisa', official_url='https://visa.visitsaudi.com' WHERE country_code='SA';
UPDATE country_translations SET
  entry_summary='Saudi Arabia issues tourist e-Visas for citizens of 60+ countries, allowing multiple-entry stays. The country has invested heavily in tourism infrastructure and relaxed many social regulations since 2019.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, New Zealand, Japan and many others can apply for a Saudi tourist e-Visa at visa.visitsaudi.com. The cost is SAR 440 (~USD $117), inclusive of mandatory travel insurance. The visa allows multiple entries for a 1-year period with a maximum stay of 90 days per visit and 180 days total per year. Citizens of select Islamic nations may qualify for free-of-charge entry arrangements. Women may travel independently without a male guardian.',
  visa_duration='Up to 90 days per visit (max 180 days/year) within 1-year validity',
  passport_validity='6 months beyond the date of intended exit',
  visa_fee='SAR 440 (~USD $117, inclusive of travel insurance)',
  processing_time='Immediate to 24 hours',
  official_visa_url='https://visa.visitsaudi.com',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='SA') AND lang_code='en';

-- Qatar
UPDATE countries SET visa_type='visa_free', official_url='https://portal.moi.gov.qa/wps/portal/MOIInternet/departmentactivities/passportsandimmigration/travelingthroughqatar' WHERE country_code='QA';
UPDATE country_translations SET
  entry_summary='Qatar grants visa-free entry to citizens of over 95 countries for up to 30 days, with free visa on arrival available for most others. Doha is a major international transit hub via Qatar Airways.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, New Zealand, Japan and South Korea (and many others) enter Qatar visa-free for 30 days. Free-of-charge visa on arrival is available for an additional 90+ nationalities. The stay is extendable for a further 30 days at the Immigration Department. Citizens of GCC states have unrestricted access. A valid return or onward ticket and hotel booking are recommended.',
  visa_duration='Up to 30 days (extendable by 30 days)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://portal.moi.gov.qa',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='QA') AND lang_code='en';

-- Oman
UPDATE countries SET visa_type='evisa', official_url='https://evisa.rop.gov.om' WHERE country_code='OM';
UPDATE country_translations SET
  entry_summary='Oman offers an e-Visa for citizens of over 100 countries through the Royal Oman Police portal. Visa on arrival is available for some GCC countries and British nationals at certain entry points.',
  visa_requirements='Oman''s e-Visa (OMR 20 / approx. USD $52 for a 10-day single entry; OMR 50 for a 30-day or annual multiple-entry visa) is processed online. Processing is usually 3 business days. British nationals holding BOTC, BN(O) or other specific British passports may qualify for visa on arrival. GCC state citizens enter visa-free. Travelers must hold travel insurance, a hotel booking and return ticket.',
  visa_duration='10 days (single) or 30 days per visit (multiple entry)',
  passport_validity='6 months beyond the intended departure date',
  visa_fee='OMR 20 (~USD $52) single | OMR 50 (~USD $130) annual multiple entry',
  processing_time='3 business days',
  official_visa_url='https://evisa.rop.gov.om',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='OM') AND lang_code='en';

-- Kuwait
UPDATE countries SET visa_type='visa_on_arrival', official_url='https://evisa.moi.gov.kw' WHERE country_code='KW';
UPDATE country_translations SET
  entry_summary='Kuwait offers visa on arrival for citizens of select Western countries, including those holding US, UK, EU, Australian and Canadian passports, for up to 90 days. An e-Visa is also available.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, New Zealand, Japan, South Korea and GCC states can obtain a visa on arrival or e-Visa for Kuwait for 90 days (single entry, extendable). The e-Visa can be applied for online through the Kuwait Ministry of Interior portal. Citizens of other countries require a visa from a Kuwaiti embassy. A return ticket and hotel reservation are required.',
  visa_duration='Up to 90 days (extendable)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='KWD 3 (~USD $10)',
  processing_time='On arrival or 3–5 days (e-Visa)',
  official_visa_url='https://evisa.moi.gov.kw',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='KW') AND lang_code='en';

-- Bahrain
UPDATE countries SET visa_type='evisa', official_url='https://evisa.gov.bh' WHERE country_code='BH';
UPDATE country_translations SET
  entry_summary='Bahrain offers a convenient e-Visa for citizens of most countries through its official portal. Visitors from the GCC region can enter without a visa, and a visa on arrival is available at Bahrain International Airport.',
  visa_requirements='Bahrain''s e-Visa (BHD 9 / ~USD $24 for a 2-week single entry; BHD 5 for a 1-month visa or BHD 5 for a 1-year multiple-entry visa) is applied for online and usually approved within minutes. Citizens of the US, UK, EU nations, Canada, Australia and Japan are eligible for e-Visa or visa on arrival. GCC nationals enter visa-free. A valid return ticket and hotel booking are recommended.',
  visa_duration='14 days to 1 year depending on visa type',
  passport_validity='6 months beyond the date of entry',
  visa_fee='BHD 5–9 (~USD $13–24)',
  processing_time='Immediate to a few hours',
  official_visa_url='https://evisa.gov.bh',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='BH') AND lang_code='en';

-- Jordan
UPDATE countries SET visa_type='visa_on_arrival', official_url='https://www.visitjordan.com/travelinformation/entryrequirements.aspx' WHERE country_code='JO';
UPDATE country_translations SET
  entry_summary='Jordan grants visa on arrival to citizens of most countries at Queen Alia International Airport (Amman) and the Aqaba port. The Jordan Pass, which includes entry to Petra, also covers the visa fee.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, Japan and most others can obtain a single-entry visa on arrival in Jordan (JOD 40 / ~USD $56). The Jordan Pass (available from visitjordan.com) includes the visa fee and entry to 40+ attractions including Petra — a popular option for most visitors. GCC nationals enter visa-free. An e-Visa is also available online prior to travel. Proof of return ticket and funds may be requested.',
  visa_duration='30 days (extendable at the Passport Department)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='JOD 40 (~USD $56) | Included with Jordan Pass',
  processing_time='On arrival',
  official_visa_url='https://www.visitjordan.com',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='JO') AND lang_code='en';

-- Israel
UPDATE countries SET visa_type='visa_free', official_url='https://www.gov.il/en/service/visa-for-foreign-nationals' WHERE country_code='IL';
UPDATE country_translations SET
  entry_summary='Israel allows visa-free entry for citizens of most Western countries for up to 90 days. Travelers may be subject to enhanced security screening. Those with stamps from certain Arab countries may face additional questioning.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, New Zealand, Japan and South Korea can enter Israel visa-free for 90 days for tourism. A B/2 tourist visa is required for non-exempt nationalities. Israeli immigration officers may deny entry or conduct extensive interviews at their discretion. Travelers with evidence of visits to Iran, Iraq, Syria, Lebanon, Sudan, Yemen, Libya or Somalia may face additional screening. An International Visitor Card (Teudat Cnis) is issued on arrival.',
  visa_duration='Up to 90 days',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free (visa-exempt) | B/2 Visa: varies',
  processing_time='On arrival',
  official_visa_url='https://www.gov.il/en/service/visa-for-foreign-nationals',
  arrival_card_required='Yes — International Visitor Card issued on arrival',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='IL') AND lang_code='en';

-- ============================================================
-- AFRICA
-- ============================================================

-- South Africa
UPDATE countries SET visa_type='visa_free', official_url='https://www.dha.gov.za/index.php/immigration-services/types-of-visas' WHERE country_code='ZA';
UPDATE country_translations SET
  entry_summary='South Africa grants visa-free entry to citizens of over 75 countries for 30–90 days. A valid return or onward ticket is a strict entry requirement enforced at airports.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, New Zealand and Japan can enter South Africa visa-free for 30 days (extendable to 90 days at Home Affairs). A confirmed return or onward ticket and proof of sufficient funds are mandatory requirements. Children require an unabridged birth certificate in addition to a passport. Citizens of other countries apply for a South African visitors visa from a SA embassy.',
  visa_duration='30 days on arrival (extendable to 90 days)',
  passport_validity='30 days beyond the intended departure date, with at least 2 blank pages',
  visa_fee='Free (visa-free) | Visitor''s Visa: approx. ZAR 425',
  processing_time='On arrival',
  official_visa_url='https://www.dha.gov.za',
  arrival_card_required='Yes — Traveler''s Card (VAF 1)',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='ZA') AND lang_code='en';

-- Egypt
UPDATE countries SET visa_type='visa_on_arrival', official_url='https://visa2egypt.gov.eg' WHERE country_code='EG';
UPDATE country_translations SET
  entry_summary='Egypt provides visa on arrival for citizens of over 45 countries at Cairo, Hurghada, Sharm el-Sheikh and other international airports. An e-Visa can also be applied for online before travel.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, Japan and South Korea can obtain a single-entry visa on arrival in Egypt (USD $25) for a 30-day stay. The Egypt e-Visa (USD $25 online) is also available and can be more convenient. Citizens of Jordan and some other Arab countries enter visa-free. The Sinai-only entry permit allows access to the Sharm el-Sheikh area without a full visa.',
  visa_duration='30 days (extendable at Passport and Immigration offices)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='USD $25 (visa on arrival or e-Visa)',
  processing_time='On arrival or online',
  official_visa_url='https://visa2egypt.gov.eg',
  arrival_card_required='Yes — Arrival Card',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='EG') AND lang_code='en';

-- Morocco
UPDATE countries SET visa_type='visa_free', official_url='https://www.diplomatie.ma' WHERE country_code='MA';
UPDATE country_translations SET
  entry_summary='Morocco allows visa-free entry for citizens of over 65 countries for up to 90 days. Major entry points include Casablanca''s Mohammed V Airport, Marrakech Airport and the ferry ports from Spain.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, Japan and most other Western countries can enter Morocco visa-free for 90 days for tourism or business. A valid return or onward ticket and evidence of accommodation are recommended. Citizens of other countries require a visa from a Moroccan embassy. Note that Algeria''s land border with Morocco has been closed since 1994.',
  visa_duration='Up to 90 days',
  passport_validity='3 months beyond the date of intended departure',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://www.diplomatie.ma',
  arrival_card_required='Yes — Arrival/Departure Card',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='MA') AND lang_code='en';

-- Tunisia
UPDATE countries SET visa_type='visa_free', official_url='https://www.diplomatie.gov.tn' WHERE country_code='TN';
UPDATE country_translations SET
  entry_summary='Tunisia grants visa-free entry to citizens of over 100 countries for up to 90 days. Entry is available at Tunis-Carthage International Airport and several other airports and land crossings.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia, Japan and most Western countries can enter Tunisia without a visa for 90 days. A valid return or onward ticket is required. Citizens of some countries need to register with the local police authority within 24 hours of arrival if not staying in a hotel.',
  visa_duration='Up to 90 days',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Free',
  processing_time='On arrival',
  official_visa_url='https://www.diplomatie.gov.tn',
  arrival_card_required='Yes — Arrival Card',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='TN') AND lang_code='en';

-- Kenya
UPDATE countries SET visa_type='evisa', official_url='https://www.etakenya.go.ke' WHERE country_code='KE';
UPDATE country_translations SET
  entry_summary='Kenya replaced visa on arrival with a mandatory Electronic Travel Authorisation (eTA) in January 2024. All visitors (except East African Community citizens) must apply for an eTA before travel. The process is fully online.',
  visa_requirements='Kenya''s eTA (USD $30 for most nationalities) is applied for at etakenya.go.ke at least 72 hours before departure. Citizens of East African Community countries (Uganda, Tanzania, Rwanda, Burundi, South Sudan, DR Congo and Somalia) are exempt. The eTA is valid for 90 days with a single or multiple-entry option. Travelers must provide a hotel booking, return ticket and travel itinerary. An annual multiple-entry eTA is available for USD $60.',
  visa_duration='Up to 90 days (single or multiple entry)',
  passport_validity='6 months beyond the date of entry with at least 2 blank pages',
  visa_fee='USD $30 (single entry) | USD $60 (annual multiple entry)',
  processing_time='72 hours (apply at least 3 days before travel)',
  official_visa_url='https://www.etakenya.go.ke',
  arrival_card_required='No',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='KE') AND lang_code='en';

-- Nigeria
UPDATE countries SET visa_type='visa_required', official_url='https://portal.immigration.gov.ng' WHERE country_code='NG';
UPDATE country_translations SET
  entry_summary='Nigeria requires a visa for citizens of most countries. An e-Visa is available through the Nigeria Immigration Service portal. ECOWAS citizens enjoy visa-free access.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada, Australia and most non-African countries require a visa for Nigeria. The Nigeria e-Visa (tourist or business) can be applied for online (USD $160+ depending on visa type and nationality). Applications require an invitation letter or hotel booking, return ticket, yellow fever vaccination certificate, and financial proof. ECOWAS member state citizens (West African nations) enter visa-free. Processing takes 2–5 business days.',
  visa_duration='30 days (extendable at the NIS)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='USD $100–200+ depending on visa type and nationality',
  processing_time='2–5 business days',
  official_visa_url='https://portal.immigration.gov.ng',
  arrival_card_required='Yes',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='NG') AND lang_code='en';

-- Ghana
UPDATE countries SET visa_type='visa_required', official_url='https://www.ghanaimmigration.org' WHERE country_code='GH';
UPDATE country_translations SET
  entry_summary='Ghana requires a visa for citizens of most non-African countries. An online e-Visa system is available, though most applications still go through Ghanaian embassies. ECOWAS citizens enter visa-free.',
  visa_requirements='Citizens of the US, UK, EU nations, Canada and Australia must obtain a Ghanaian tourist or business visa in advance at a Ghanaian embassy or via the online application system. Applications require passport photos, return ticket, hotel booking, yellow fever vaccination certificate and financial evidence. ECOWAS member state citizens enter Ghana visa-free. A Ghana Cards (biometric registration) is available on arrival for returning diaspora members.',
  visa_duration='30–60 days (extendable at the Ghana Immigration Service)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='Approx. USD $60–150 (varies by nationality and embassy)',
  processing_time='3–10 business days at embassy',
  official_visa_url='https://www.ghanaimmigration.org',
  arrival_card_required='Yes — Ghana Incoming Passenger Card',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='GH') AND lang_code='en';

-- Tanzania
UPDATE countries SET visa_type='evisa', official_url='https://immigration.go.tz' WHERE country_code='TZ';
UPDATE country_translations SET
  entry_summary='Tanzania moved to a mandatory e-Visa system in 2023, eliminating visa on arrival. All visitors must apply online before travel. Tanzania is home to the Serengeti, Mount Kilimanjaro and the Zanzibar archipelago.',
  visa_requirements='Tanzania''s e-Visa (USD $50 for most nationalities) must be applied for at immigration.go.tz before departure. Citizens of East African Community countries (Kenya, Uganda, Rwanda, Burundi) are exempt. The e-Visa is valid for a single 90-day entry. Travelers must have a valid yellow fever vaccination certificate if arriving from or transiting through a yellow fever endemic country. A hotel booking and return ticket are required.',
  visa_duration='Up to 90 days (single entry)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='USD $50 (single entry)',
  processing_time='3–5 business days',
  official_visa_url='https://immigration.go.tz',
  arrival_card_required='Yes',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='TZ') AND lang_code='en';

-- Uganda
UPDATE countries SET visa_type='evisa', official_url='https://visas.immigration.go.ug' WHERE country_code='UG';
UPDATE country_translations SET
  entry_summary='Uganda requires most visitors to obtain an e-Visa before travel. The visa is applied for online and approved before departure. East African Community citizens enter visa-free.',
  visa_requirements='Uganda''s e-Visa (USD $50) is applied for at visas.immigration.go.ug and processed within 2–3 business days. Citizens of EAC member states (Kenya, Tanzania, Rwanda, Burundi, South Sudan, DRC, Somalia) are visa-free. The visa allows a 30-day stay extendable at the Directorate of Citizenship and Immigration Control. Yellow fever vaccination certificate is required. Travelers must have valid travel insurance, hotel booking and return ticket.',
  visa_duration='30 days (extendable)',
  passport_validity='6 months beyond the date of entry',
  visa_fee='USD $50',
  processing_time='2–3 business days',
  official_visa_url='https://visas.immigration.go.ug',
  arrival_card_required='Yes',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='UG') AND lang_code='en';

-- Ethiopia
UPDATE countries SET visa_type='evisa', official_url='https://www.evisa.gov.et' WHERE country_code='ET';
UPDATE country_translations SET
  entry_summary='Ethiopia offers an e-Visa for citizens of most countries through its official portal. Addis Ababa''s Bole International Airport is a major African aviation hub via Ethiopian Airlines.',
  visa_requirements='Ethiopia''s e-Visa (USD $52 for a 30-day single entry; USD $72 for a 90-day visa) is available at evisa.gov.et. Visa on arrival is also available for some nationalities at Addis Ababa Bole Airport. African Union member state citizens may be eligible for visa exemption. A valid return ticket, hotel booking and financial evidence are required.',
  visa_duration='30 days (single entry) or 90 days',
  passport_validity='6 months beyond the date of entry',
  visa_fee='USD $52 (30 days) | USD $72 (90 days)',
  processing_time='3–5 business days',
  official_visa_url='https://www.evisa.gov.et',
  arrival_card_required='Yes',
  last_verified='2026-05-17'
WHERE country_id=(SELECT id FROM countries WHERE country_code='ET') AND lang_code='en';

SET foreign_key_checks = 1;

-- ============================================================
-- VERIFY: Check updated records
-- ============================================================
-- SELECT c.country_code, ct.country_name, c.visa_type, ct.last_verified
-- FROM countries c
-- JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
-- WHERE c.is_active = 1
-- ORDER BY c.region, ct.country_name;
