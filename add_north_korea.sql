-- Add North Korea (DPRK) to the database with comprehensive information
-- This is a unique country with extremely strict entry and travel requirements

-- Insert into countries table
INSERT INTO countries (
    country_code, 
    code, 
    name_en, 
    capital, 
    region, 
    visa_type, 
    is_active, 
    display_order,
    view_count
) VALUES (
    'PRK',
    'KP',
    'North Korea',
    'Pyongyang',
    'Asia',
    'visa_required',
    1,
    110,
    0
);

-- Get the inserted country ID
SET @north_korea_id = LAST_INSERT_ID();

-- Insert English translation with extensive details
INSERT INTO country_translations (
    country_id,
    lang_code,
    country_name,
    entry_summary,
    visa_requirements,
    visa_duration,
    passport_validity,
    visa_fee,
    processing_time,
    official_visa_url,
    arrival_card_required,
    additional_docs,
    cultural_notes,
    prohibited_items,
    currency_exchange,
    health_requirements,
    connectivity,
    travel_advisory,
    last_verified
) VALUES (
    @north_korea_id,
    'en',
    'North Korea (DPRK)',
    'North Korea (Democratic People\'s Republic of Korea) has one of the world\'s most restrictive entry policies. ALL foreigners must obtain a visa in advance and travel exclusively through government-approved tour operators with mandatory guides. Independent travel is strictly prohibited. US citizens face additional restrictions and may be denied entry. Visitors have no freedom of movement and must follow strict photography and behavior rules.',
    'MANDATORY VISA REQUIRED - Very Strict Process:

1. TOUR OPERATOR REQUIREMENT (CRITICAL):
   - You CANNOT travel independently to North Korea
   - Must book through approved international tour operator (e.g., Koryo Tours, Young Pioneer Tours)
   - Tour operator handles all visa applications
   - Group tours only - solo travel forbidden
   - Government guides accompany you at ALL times

2. VISA APPLICATION PROCESS:
   - Apply through your tour operator 6-8 weeks before travel
   - Tour operator submits application to DPRK authorities
   - Passport must not contain Israeli stamps or evidence of journalism
   - Approval can take 4-8 weeks
   - Visa is issued as separate paper document (not stamped in passport)

3. NATIONALITY RESTRICTIONS:
   - South Korean citizens: BANNED (no entry)
   - US citizens: Restricted - special authorization required, additional scrutiny
   - Journalists: Generally denied unless on official press tour
   - Some nationalities require clearance from Pyongyang

4. REQUIRED DOCUMENTS:
   - Valid passport (6+ months validity)
   - Completed visa application form (via tour operator)
   - Recent passport photos (2)
   - Travel insurance covering DPRK
   - Detailed itinerary from approved tour operator
   - Letter of invitation from tour company
   - Employment letter or proof of occupation

5. ENTRY POINTS:
   - Air: Pyongyang Sunan International Airport (mostly via Beijing)
   - Train: From Dandong, China (via Sino-Korean Friendship Bridge)
   - Limited entry points - no land border with South Korea

6. COSTS:
   - Visa fee: Approximately $50-80 USD (varies by nationality)
   - Tour costs: $1,000-3,000+ per week (includes visa, accommodation, guides, meals)
   - Additional fees for special permits

IMPORTANT: Visa approval is NOT guaranteed and can be revoked without explanation.',
    'Tourist visa: 7-21 days (based on tour length)
Special permits required for diplomatic, business, or journalistic purposes
Extensions virtually impossible',
    'Passport must be valid for minimum 6 months beyond entry date
Must have at least 2 blank pages
Passport should not contain Israeli visa stamps or evidence of journalistic work',
    'Approximately $50-80 USD
Additional tour operator fees: $1,000-3,000+ for full tour package including visa processing
Payment typically made to tour operator',
    'Extremely long: 6-8 weeks minimum
Tour operators require booking 2-3 months in advance
Emergency or rush processing: Not available
Approval subject to political climate and nationality',
    'No official DPRK government website accessible from outside
Must contact approved tour operators:
- Koryo Tours (www.koryogroup.com)
- Young Pioneer Tours (www.ypt.com) 
Note: Tour operators handle all visa arrangements',
    'YES - Mandatory Arrival Card
Detailed customs declaration form required
Must declare all foreign currency, electronic devices, and reading materials
Forms provided on flight or at border
Keep arrival portion until departure',
    'CRITICAL ADDITIONAL REQUIREMENTS:

1. TOUR OPERATOR DOCUMENTATION:
   - Official invitation letter from DPRK-approved tour company
   - Detailed day-by-day itinerary (must be followed exactly)
   - Group booking confirmation
   - Emergency contact information

2. PERSONAL CONDUCT AGREEMENT:
   - Must sign rules of conduct with tour operator
   - Agreement to follow all DPRK laws and guidelines
   - Acknowledge restrictions on photography, movement, and speech
   - Understand severe penalties for rule violations

3. TECHNOLOGY DECLARATION:
   - Must declare ALL electronic devices at customs
   - Smartphones, laptops, tablets, GPS devices, drones
   - Professional cameras may require special permission
   - Memory cards may be inspected

4. READING MATERIALS:
   - Must declare all books, magazines, religious materials
   - Items deemed inappropriate will be confiscated
   - No religious proselytizing materials allowed
   - No materials critical of DPRK leadership

5. FINANCIAL REQUIREMENTS:
   - Full tour payment in advance (non-refundable)
   - Additional cash in EUR, USD, or CNY for personal expenses
   - Credit cards do NOT work in DPRK
   - No ATMs available

6. TRAVEL INSURANCE:
   - Mandatory comprehensive travel insurance
   - Must cover emergency medical evacuation
   - Standard policies often exclude DPRK - special coverage needed

7. EMPLOYMENT VERIFICATION:
   - Letter from employer stating position
   - NOT available to journalists unless on official press tour
   - Government employees may face additional scrutiny

8. HEALTH SCREENING:
   - May require medical examination results
   - Proof of vaccinations
   - No known infectious diseases

9. PHOTOGRAPHY RESTRICTIONS (STRICTLY ENFORCED):
   - Cannot photograph: military personnel, police, construction sites, poverty
   - Must ask permission before photographing locals
   - Guides will review photos and may delete images
   - Violations can result in detention

10. PROHIBITED BEHAVIOR:
    - No disrespect toward DPRK leaders or ideology
    - No unauthorized communication with locals
    - No deviation from approved itinerary
    - No attempt to leave your group
    - Violations can result in arrest and lengthy detention',
    'CULTURAL PROTOCOL - STRICTLY OBSERVE:

1. RESPECT FOR LEADERSHIP:
   - Supreme Leader and former leaders are deeply sacred
   - NEVER criticize or joke about current or past leaders
   - When photographing leader statues/images, include entire figure (no cropped photos)
   - Bow at designated monuments and statues
   - Do not fold, damage, or discard newspapers with leader images

2. PHOTOGRAPHY RULES:
   - Ask guides before taking ANY photo
   - No photos of military, police, soldiers, or checkpoints
   - No photos showing poverty or underdevelopment
   - No photos of construction sites or infrastructure
   - Guides may review and delete photos at any time
   - Violation can lead to arrest

3. SOCIAL INTERACTION:
   - Limited interaction with locals allowed
   - Do not discuss politics, human rights, or comparative living standards
   - Do not offer gifts or money to locals without guide approval
   - No religious discussions or proselytizing
   - Assume all conversations are monitored

4. ACCOMMODATION:
   - Stay only in government-approved hotels
   - Cannot leave hotel unaccompanied
   - Hotel staff may search rooms
   - No room service calls outside hotel

5. DRESS CODE:
   - Conservative dress required at all times
   - No jeans at monuments or important sites
   - No shorts at formal locations
   - No clothing with Western political messages or slogans
   - Smart casual as minimum standard

6. DINING ETIQUETTE:
   - Accept food offered by guides
   - Toast to friendship if locals offer
   - No wasting food - considered disrespectful
   - Tipping guides acceptable but not required

7. BEHAVIOR AT MONUMENTS:
   - Show utmost respect at leader statues and museums
   - Follow guide instructions exactly
   - Maintain solemn demeanor at revolutionary sites
   - Participate in group activities (bowing, flower laying)

8. GIFT GIVING:
   - Small gifts for guides acceptable (Western snacks, postcards)
   - No religious materials or materials critical of DPRK
   - No banned books or media
   - No items that could be seen as bribes

WARNING: Rules are strictly enforced. Violations have led to arrest, detention for months/years, and forced confessions. Several Western tourists have been detained for "crimes against the state" for seemingly minor infractions.',
    'STRICTLY PROHIBITED - Severe Penalties:

1. ELECTRONICS & MEDIA:
   - Religious texts (Bibles, Qurans, etc.) - CONFISCATED
   - Materials critical of DPRK government - CRIMINAL OFFENSE
   - South Korean media or products - BANNED
   - Pornographic materials - ILLEGAL
   - Political books or materials - CONFISCATED
   - Satellite phones - BANNED
   - Drones - STRICTLY PROHIBITED
   - GPS devices (may be confiscated)
   - Two-way radios - BANNED
   - Professional video equipment without permit

2. COMMUNICATION DEVICES:
   - International SIM cards (may work but monitored)
   - Satellite internet equipment
   - VPN software (though internet access is restricted anyway)

3. PRINTED MATERIALS:
   - Newspapers from South Korea or critical countries
   - Books about North Korean defectors
   - Human rights reports
   - Materials about democracy or capitalism
   - Anything depicting unfavorably the DPRK leadership

4. WEAPONS & MILITARY ITEMS:
   - Any weapons or ammunition
   - Binoculars (may be confiscated)
   - Night vision equipment
   - Military-style clothing

5. DRUGS & MEDICINES:
   - Illegal drugs (death penalty if caught)
   - Large quantities of prescription medications without documentation
   - Some over-the-counter medications banned

6. CURRENCY:
   - South Korean Won - ABSOLUTELY BANNED
   - Large undeclared amounts of foreign currency

7. BEHAVIOR (PROHIBITED):
   - Unauthorized photography of military/police
   - Speaking negatively about the leadership
   - Distributing any materials to locals
   - Attempting to leave your tour group
   - Evangelizing or religious proselytizing
   - Romantic relationships with locals

PENALTIES: Items will be confiscated at entry. Serious violations can result in arrest, lengthy detention (months to years), forced confessions, and show trials. Several Americans and other nationals have been detained for 1-15 years for violations.',
    'NORTH KOREAN WON (KPW) - Heavily Restricted:

1. FOREIGN CURRENCY USE:
   - Tourists CANNOT use North Korean Won
   - Must use: Chinese Yuan (CNY), US Dollars (USD), or Euros (EUR)
   - Most transactions in CNY or EUR
   - USD accepted but may face difficulty due to US sanctions

2. BRINGING CASH:
   - Bring sufficient cash for entire trip
   - Banks do NOT exist for tourists
   - NO ATMs available anywhere
   - NO credit cards work (even in hotels)
   - NO debit cards accepted
   - NO wire transfers possible

3. DECLARATION REQUIREMENTS:
   - Must declare ALL foreign currency at entry
   - Amounts over $10,000 USD equivalent must be declared in detail
   - Keep receipts for major purchases
   - May need to account for currency at exit

4. EXCHANGE RESTRICTIONS:
   - Cannot exchange money at banks
   - Hotels may exchange limited amounts
   - Tour guides can assist with small exchanges
   - Cannot exchange North Korean Won abroad
   - Keep your arrival currency declaration form

5. PRICES:
   - Most expenses covered in tour package
   - Extra costs: souvenirs ($10-100), drinks ($2-10), optional activities
   - Budget $200-500 additional for personal expenses per week
   - Prices in tourist areas significantly inflated

6. TIPPING:
   - Not mandatory but appreciated
   - Guide tips: $5-10 USD per day per person
   - Driver tips: $3-5 USD per day per person
   - Given in envelope at end of tour

7. SHOPPING:
   - Government-run shops accept EUR, USD, CNY
   - Souvenir shops in hotels and tourist sites
   - Market purchases must be guided
   - Keep receipts for customs

8. RECEIPTS:
   - Keep all receipts for proof of legitimate expenses
   - May be asked to show at departure
   - Helps account for currency differences from arrival declaration',
    'HEALTH & MEDICAL - Limited Facilities:

1. VACCINATIONS (Recommended):
   - Routine: MMR, DPT, Polio
   - Hepatitis A and B (recommended)
   - Typhoid (recommended)
   - Japanese Encephalitis (if rural areas)
   - Influenza (seasonal)
   - COVID-19 (check current requirements - DPRK sealed borders 2020-2023)

2. MEDICAL FACILITIES:
   - Extremely limited for foreigners
   - Basic care available in Pyongyang at diplomatic hospitals
   - Outside Pyongyang: very limited facilities
   - No reliable emergency services
   - Medical evacuation required for serious issues

3. MEDICATIONS:
   - Bring all personal medications in original packaging
   - Carry prescription letters from doctor
   - No pharmacies accessible to tourists
   - Stock up on basic medications before arrival

4. HEALTH INSURANCE:
   - Comprehensive travel insurance MANDATORY
   - Must cover medical evacuation to China or South Korea
   - Standard policies often exclude DPRK - verify coverage
   - Evacuation costs can exceed $100,000

5. WATER & FOOD SAFETY:
   - Do NOT drink tap water
   - Only bottled or boiled water
   - Eat only in approved restaurants (part of tour)
   - Limited food variety - mostly Korean and Chinese cuisine
   - Special dietary requirements difficult to accommodate

6. HYGIENE:
   - Hotels have basic soap and toiletries (bring your own recommended)
   - Hand sanitizer recommended
   - Toilet paper not always available - bring your own
   - Western-style toilets in tourist hotels only

7. ALTITUDE & CLIMATE:
   - Pyongyang sea level - no altitude concerns
   - Extreme cold in winter (-20°C/-4°F in January)
   - Hot and humid in summer (30°C/86°F in August)
   - Spring and autumn best for travel

8. HEALTH SCREENING:
   - May require health declaration on arrival
   - Temperature screening possible
   - Quarantine possible if showing symptoms of infectious disease

9. EMERGENCY MEDICAL EVACUATION:
   - Requires diplomatic intervention
   - Evacuation to China (Beijing) standard route
   - Can take 24-48 hours to arrange
   - Costs $50,000-150,000 or more

10. MENTAL HEALTH CONSIDERATIONS:
    - Strict surveillance can be stressful
    - Limited communication with outside world
    - Cultural shock significant
    - No counseling services available',
    'CONNECTIVITY - Extremely Limited:

1. INTERNET ACCESS:
   - NO public internet available to tourists
   - NO WiFi in hotels (except very limited in some high-end hotels)
   - DPRK has internal "intranet" (Kwangmyong) - not accessible to foreigners
   - Cannot access email, social media, or any websites
   - NO internet cafes

2. MOBILE PHONES:
   - Local SIM cards available for tourists at Pyongyang Airport
   - Company: Koryolink (DPRK state mobile operator)
   - Cost: ~$50 USD for tourist SIM + minutes
   - Can make/receive international calls ONLY (around $7-8/minute)
   - NO data services or internet on mobile
   - Cannot call local DPRK numbers
   - Phone calls likely monitored
   - Coverage limited to Pyongyang and major tourist routes

3. LANDLINE PHONES:
   - Hotel rooms have phones for international calls
   - Extremely expensive: $5-10 per minute
   - Must go through operator
   - Likely monitored
   - Keep calls brief due to cost

4. POSTAL SERVICE:
   - Can send postcards from DPRK post offices
   - Delivery can take weeks or months
   - Mail likely read/inspected
   - Cannot receive mail as tourist
   - Stamps available as souvenirs

5. COMMUNICATION WITH OUTSIDE:
   - Inform family of limited/no contact period
   - Tour operators can relay emergency messages
   - Chinese Embassy can assist in true emergencies
   - Plan for communication blackout during trip

6. SOCIAL MEDIA:
   - Cannot access Facebook, Twitter, Instagram, etc.
   - Cannot post updates during trip
   - Photos can be posted AFTER departure
   - Do not attempt to use VPN - illegal

7. NEWS ACCESS:
   - Cannot access international news
   - Only state-run DPRK media available
   - Radio signals jammed
   - Bring downloaded entertainment for downtime

8. BUSINESS COMMUNICATION:
   - Plan all business communications before/after trip
   - No email access
   - Fax services may be available at diplomatic hotels (very limited)

9. EMERGENCY CONTACT:
   - Tour operator emergency number (works from China SIM)
   - Embassy contact numbers (memorize or write down)
   - Satellite phones BANNED - do not attempt to bring

10. AFTER DEPARTURE:
    - Can reconnect at Beijing airport or China border
    - Check with tour operator about group WiFi on return journey
    - Resume normal communications once across Chinese border

IMPORTANT: Plan for complete communication isolation. Inform family and employers you will be unreachable for duration of trip.',
    'CRITICAL TRAVEL ADVISORY - HIGH RISK:

⚠️ SEVERE RISK WARNING ⚠️

Multiple governments issue their highest level travel warnings for North Korea:

🇺🇸 USA: "DO NOT TRAVEL" - Level 4 (Highest Warning)
- US citizens arrested and detained for months to years
- Limited consular access (no US embassy in DPRK - Swedish embassy provides limited services)
- Risk of arbitrary arrest and long-term detention
- US passport use for travel to DPRK restricted by law (special validation required)

🇬🇧 UK: "Advise against all travel"
- Arrest and long-term detention for activities that would not be a concern in other countries
- Severe restrictions on movement and behavior
- Limited consular support

🇨🇦 CANADA: "Avoid all travel" - Highest risk level
- Extreme restrictions on freedom of movement
- Arbitrary enforcement of local laws
- Risk of detention and interrogation

🇦🇺 AUSTRALIA: "Do not travel" - Highest warning
- Serious personal security risks
- Unpredictable application of local laws
- Very limited consular assistance

🇪🇺 EU Countries: Generally advise against or very limited travel

KEY RISKS:

1. ARBITRARY DETENTION:
   - Tourists have been detained for minor perceived offenses
   - Examples: taking wrong photos, not showing proper respect at monuments, owning "wrong" books
   - Detention periods: months to 15+ years
   - Forced confessions common
   - Show trials with predetermined outcomes

2. LACK OF LEGAL PROTECTIONS:
   - No due process as understood in Western countries
   - No right to legal representation
   - Confessions may be coerced
   - Sentences disproportionate to alleged crimes

3. LIMITED CONSULAR ACCESS:
   - Most countries have NO embassy in DPRK
   - Consular assistance very limited or impossible
   - Diplomatic negotiations for release can take months/years
   - Cannot rely on your government to secure release

4. PAST DETENTION CASES:
   - Otto Warmbier (US): Detained 2016, released in coma 2017, died shortly after
   - Kenneth Bae (US): Detained 2012-2014 for 2 years
   - Multiple journalists detained for "espionage"
   - Several detained for religious activities or possession of religious materials

5. POLITICAL TENSIONS:
   - International relations fluctuate dramatically
   - Tourists may be used as political leverage
   - Risk increases during periods of international tension
   - American citizens face highest risk during US-DPRK tensions

6. NATURAL DISASTERS:
   - Limited ability to evacuate if needed
   - Earthquakes possible (seismic zone)
   - Flooding during monsoon season
   - No reliable emergency response

7. HEALTH EMERGENCIES:
   - Inadequate medical facilities
   - Medical evacuation difficult to arrange
   - May be delayed for political reasons
   - COVID-19: Border closures 2020-2023 - check current status

8. INVOLUNTARY RETURN RESTRICTION:
   - DPRK may prevent departure without warning
   - Has happened during diplomatic incidents
   - Could be held until political situation resolves

RECOMMENDATIONS IF YOU CHOOSE TO TRAVEL:

1. Register with your embassy before travel
2. Leave detailed itinerary with family/friends
3. Follow ALL rules meticulously - no exceptions
4. Never joke about or criticize the DPRK leadership
5. Be extremely cautious with photography
6. Purchase comprehensive travel insurance with DPRK coverage
7. Bring emergency contact information
8. Do not engage in any political, religious, or journalistic activities
9. Follow your guides\' instructions precisely
10. Accept that you have no effective legal rights

ALTERNATIVE: Consider other destinations. North Korea tourism is extremely high-risk with limited reward. If seeking cultural experience of Korean culture, consider South Korea instead.

Last Updated: February 2026
Source: Multiple government travel advisories and tour operator guidelines

⚠️ TRAVEL AT YOUR OWN RISK - RISK OF DETENTION, LIMITED CONSULAR SUPPORT, AND SERIOUS HARM ⚠️',
    NOW()
);

-- Add Spanish translation
INSERT INTO country_translations (
    country_id,
    lang_code,
    country_name,
    entry_summary,
    visa_requirements,
    last_verified
) VALUES (
    @north_korea_id,
    'es',
    'Corea del Norte (RPDC)',
    'Corea del Norte tiene una de las políticas de entrada más restrictivas del mundo. TODOS los extranjeros deben obtener una visa previa y viajar exclusivamente a través de operadores turísticos aprobados por el gobierno con guías obligatorios. El viaje independiente está estrictamente prohibido. Los visitantes no tienen libertad de movimiento y deben seguir estrictas reglas de fotografía y comportamiento.',
    'VISA OBLIGATORIA - Proceso Muy Estricto: Debe reservar a través de un operador turístico aprobado (por ejemplo, Koryo Tours, Young Pioneer Tours). El operador maneja todas las solicitudes de visa. Proceso de 6-8 semanas. Ciudadanos surcoreanos: PROHIBIDOS. Ciudadanos estadounidenses: Restricciones adicionales. Los periodistas generalmente son rechazados.',
    NOW()
);

-- Add Chinese translation
INSERT INTO country_translations (
    country_id,
    lang_code,
    country_name,
    entry_summary,
    visa_requirements,
    last_verified
) VALUES (
    @north_korea_id,
    'zh',
    '朝鲜（北韩）',
    '朝鲜（朝鲜民主主义人民共和国）拥有世界上最严格的入境政策之一。所有外国人必须提前获得签证，并且只能通过政府批准的旅行社和强制性导游进行旅行。严格禁止独立旅行。游客没有行动自由，必须遵守严格的摄影和行为规则。',
    '强制性签证要求 - 非常严格的流程：必须通过批准的国际旅行社预订（例如高丽旅行社）。旅行社处理所有签证申请。处理时间6-8周。韩国公民：禁止入境。美国公民：面临额外限制。记者通常被拒绝。',
    NOW()
);

-- Add French translation
INSERT INTO country_translations (
    country_id,
    lang_code,
    country_name,
    entry_summary,
    visa_requirements,
    last_verified
) VALUES (
    @north_korea_id,
    'fr',
    'Corée du Nord (RPDC)',
    'La Corée du Nord a l\'une des politiques d\'entrée les plus restrictives au monde. TOUS les étrangers doivent obtenir un visa à l\'avance et voyager exclusivement par l\'intermédiaire d\'agences de voyage approuvées par le gouvernement avec des guides obligatoires. Les voyages indépendants sont strictement interdits. Les visiteurs n\'ont aucune liberté de mouvement.',
    'VISA OBLIGATOIRE - Processus Très Strict : Vous devez réserver via un voyagiste agréé (par exemple Koryo Tours). L\'opérateur gère toutes les demandes de visa. Délai de 6 à 8 semaines. Citoyens sud-coréens : INTERDITS. Citoyens américains : Restrictions supplémentaires.',
    NOW()
);

-- Add German translation
INSERT INTO country_translations (
    country_id,
    lang_code,
    country_name,
    entry_summary,
    visa_requirements,
    last_verified
) VALUES (
    @north_korea_id,
    'de',
    'Nordkorea (DVRK)',
    'Nordkorea hat eine der restriktivsten Einreisebestimmungen der Welt. ALLE Ausländer müssen im Voraus ein Visum erhalten und ausschließlich über staatlich genehmigte Reiseveranstalter mit obligatorischen Führern reisen. Unabhängiges Reisen ist streng verboten.',
    'VISUM ERFORDERLICH - Sehr Strenger Prozess: Buchung nur über zugelassene Reiseveranstalter (z.B. Koryo Tours). Bearbeitungszeit 6-8 Wochen. Südkoreanische Staatsbürger: VERBOTEN. US-Bürger: Zusätzliche Einschränkungen.',
    NOW()
);

-- Add Italian translation
INSERT INTO country_translations (
    country_id,
    lang_code,
    country_name,
    entry_summary,
    visa_requirements,
    last_verified
) VALUES (
    @north_korea_id,
    'it',
    'Corea del Nord (RPDC)',
    'La Corea del Nord ha una delle politiche di ingresso più restrittive al mondo. TUTTI gli stranieri devono ottenere un visto in anticipo e viaggiare esclusivamente tramite tour operator approvati dal governo con guide obbligatorie. Il viaggio indipendente è severamente proibito.',
    'VISTO OBBLIGATORIO - Processo Molto Rigido: Prenotazione solo tramite tour operator approvati (es. Koryo Tours). Tempo di elaborazione 6-8 settimane. Cittadini sudcoreani: VIETATI. Cittadini statunitensi: Restrizioni aggiuntive.',
    NOW()
);

-- Add Arabic translation
INSERT INTO country_translations (
    country_id,
    lang_code,
    country_name,
    entry_summary,
    visa_requirements,
    last_verified
) VALUES (
    @north_korea_id,
    'ar',
    'كوريا الشمالية',
    'كوريا الشمالية لديها واحدة من أكثر سياسات الدخول تقييدًا في العالم. يجب على جميع الأجانب الحصول على تأشيرة مسبقًا والسفر حصريًا من خلال منظمي الرحلات المعتمدين من الحكومة مع أدلة إلزامية. يُحظر السفر المستقل بشكل صارم.',
    'تأشيرة إلزامية - عملية صارمة جدًا: يجب الحجز من خلال منظم رحلات معتمد. معالجة لمدة 6-8 أسابيع. المواطنون الكوريون الجنوبيون: محظورون. المواطنون الأمريكيون: قيود إضافية.',
    NOW()
);

-- Commit changes
COMMIT;

-- Verification query
SELECT 
    c.id,
    c.country_code,
    c.code,
    c.name_en,
    c.capital,
    c.region,
    c.visa_type,
    ct.country_name as translated_name,
    ct.lang_code
FROM countries c
LEFT JOIN country_translations ct ON c.id = ct.country_id
WHERE c.code = 'KP'
ORDER BY ct.lang_code;
