<?php
/**
 * Command-line script to add North Korea to database
 * Run from terminal: php add_north_korea_cli.php
 */

require_once __DIR__ . '/includes/config.php';

echo "\n🇰🇵 Adding North Korea to Database\n";
echo str_repeat('=', 60) . "\n\n";

try {
    // Start transaction
    $pdo->beginTransaction();
    
    echo "Step 1: Checking if North Korea already exists...\n";
    $stmt = $pdo->query("SELECT id, country_code FROM countries WHERE country_code = 'PRK'");
    $existing = $stmt->fetch();
    
    if ($existing) {
        echo "❌ ERROR: North Korea already exists (ID: {$existing['id']})\n";
        echo "   To update it, delete the existing entry first.\n\n";
        $pdo->rollBack();
        exit(1);
    }
    
    echo "✓ North Korea not found - proceeding with insertion\n\n";
    
    // Insert country
    echo "Step 2: Inserting North Korea into countries table...\n";
    $stmt = $pdo->prepare("
        INSERT INTO countries (
            country_code, flag_emoji, capital, region, visa_type, 
            is_active, is_popular, display_order, 
            official_url, last_updated, 
            population, currency_name, currency_code, currency_symbol,
            plug_type, time_zone, calling_code, languages,
            helpful_yes, helpful_no,
            created_at, updated_at
        ) VALUES (
            'PRK', '🇰🇵', 'Pyongyang', 'Asia', 'visa_required',
            1, 0, 110,
            'https://koryogroup.com', CURDATE(),
            '26 million', 'North Korean Won', 'KPW', '₩',
            'Type C, F', 'UTC+9', '+850', 'Korean',
            0, 0,
            NOW(), NOW()
        )
    ");
    $stmt->execute();
    $northKoreaId = $pdo->lastInsertId();
    
    echo "✓ Country inserted with ID: {$northKoreaId}\n\n";
    
    // Translations data
    $translations = [
        'en' => [
            'name' => 'North Korea (DPRK)',
            'summary' => 'North Korea (Democratic People\'s Republic of Korea) has one of the world\'s most restrictive entry policies. ALL foreigners must obtain a visa in advance and travel exclusively through government-approved tour operators with mandatory guides. Independent travel is strictly prohibited. US citizens face additional restrictions. Visitors have no freedom of movement and must follow strict photography and behavior rules.',
            'visa_req' => 'MANDATORY VISA REQUIRED - Very Strict Process. You CANNOT travel independently. Must book through approved international tour operator (e.g., Koryo Tours, Young Pioneer Tours). Tour operator handles all visa applications. Processing: 6-8 weeks minimum. South Korean citizens: BANNED. US citizens: Restricted with special authorization. Journalists generally denied. Visa costs ~$50-80 USD + tour package $1,000-3,000+. Group tours only with mandatory government guides at ALL times. Severe penalties for rule violations including arrest and long-term detention.',
            'duration' => 'Tourist visa: 7-21 days (based on tour length). Extensions virtually impossible.',
            'passport' => 'Minimum 6 months validity, 2 blank pages required. Must not contain Israeli stamps or evidence of journalistic work.',
            'fee' => 'Approximately $50-80 USD visa fee + $1,000-3,000+ tour package (includes visa processing)',
            'processing' => '6-8 weeks minimum. Tour operators require 2-3 months advance booking. No emergency processing available.',
            'url' => 'No official DPRK website. Contact approved tour operators: Koryo Tours (koryogroup.com) or Young Pioneer Tours (ypt.com)',
            'arrival_card' => 'YES - Mandatory arrival card and detailed customs declaration. Must declare all foreign currency, electronics, and reading materials.',
            'additional' => 'CRITICAL REQUIREMENTS:\n\n' .
                'TOUR OPERATOR: Must travel with approved tour operator. Sign conduct agreement. Declare all electronics. No independent movement. Photography strictly restricted. No disrespect toward leadership. Violations can result in arrest and detention for months/years.\n\n' .
                'CULTURAL PROTOCOLS: Extreme respect required for leadership and monuments. Never criticize leaders. Ask before photographing. No interaction with locals without permission. Conservative dress required. All actions monitored. Multiple tourists detained for minor perceived offenses.\n\n' .
                'STRICTLY PROHIBITED: Religious texts, materials critical of DPRK, South Korean media, drones, satellite phones, GPS devices, unauthorized cameras, any weapons. Large undeclared currency. Violations result in confiscation or arrest.\n\n' .
                'CURRENCY: Tourists cannot use North Korean Won. Must use Chinese Yuan (CNY), US Dollars (USD), or Euros (EUR). NO ATMs, credit cards, or banks available. Bring all cash needed. Declare all currency at entry.\n\n' .
                'HEALTH: Very limited medical facilities. Comprehensive insurance with medical evacuation coverage MANDATORY. Bring all medications. Only bottled water. Evacuations difficult and expensive ($50,000-150,000).\n\n' .
                'CONNECTIVITY: NO internet access for tourists. Limited international phone calls available ($7-8/minute). Tourist SIM cards available at airport (~$50). No data services. Plan for complete communication blackout.\n\n' .
                '⚠️ SEVERE RISK WARNING: USA "DO NOT TRAVEL" Level 4 warning. UK, Canada, Australia advise against all travel. Multiple cases of arbitrary detention of tourists for months to 15+ years. Forced confessions common. Limited consular access (no US embassy). Examples: Otto Warmbier detained 2016, died 2017. Kenneth Bae detained 2 years. Political tensions make travel extremely risky. Consider alternative destinations. TRAVEL AT YOUR OWN RISK.'
        ],
        'es' => [
            'name' => 'Corea del Norte (RPDC)',
            'summary' => 'Corea del Norte tiene una de las políticas de entrada más restrictivas del mundo. Todos los extranjeros deben obtener visa previa y viajar solo con operadores aprobados.',
            'visa_req' => 'VISA OBLIGATORIA. Proceso muy estricto. Reserva solo a través de operadores aprobados. 6-8 semanas de procesamiento. Ciudadanos surcoreanos prohibidos. Guías obligatorios en todo momento.',
            'duration' => 'Visa turística: 7-21 días',
            'passport' => '6 meses de validez mínima, 2 páginas en blanco',
            'fee' => '$50-80 USD + paquete turístico $1,000-3,000+',
            'processing' => '6-8 semanas mínimo',
            'url' => 'Contactar operadores: Koryo Tours, Young Pioneer Tours',
            'arrival_card' => 'SÍ - Tarjeta de llegada obligatoria',
            'additional' => 'Debe viajar con operador aprobado. Respeto extremo hacia el liderazgo. Restricciones fotográficas estrictas. Prohibido: textos religiosos, críticas, drones. Use CNY, USD o EUR. Sin cajeros automáticos. Seguro con evacuación médica obligatorio. Sin acceso a internet. ALTO RIESGO - Advertencias de viaje de nivel máximo.'
        ],
        'zh' => [
            'name' => '朝鲜（北韩）',
            'summary' => '朝鲜拥有世界上最严格的入境政策之一。所有外国人必须提前获得签证，只能通过政府批准的旅行社旅行。',
            'visa_req' => '强制性签证。非常严格的流程。必须通过批准的旅行社预订。处理时间6-8周。韩国公民禁止入境。必须有导游全程陪同。',
            'duration' => '旅游签证：7-21天',
            'passport' => '至少6个月有效期，2页空白页',
            'fee' => '$50-80美元 + 旅游套餐$1,000-3,000+',
            'processing' => '至少6-8周',
            'url' => '联系批准的旅行社',
            'arrival_card' => '是 - 强制性入境卡',
            'additional' => '必须与批准的旅行社一起旅行。对领导层需要极度尊重。严格的摄影限制。禁止：宗教文本、批评、无人机。使用人民币、美元或欧元。无ATM。需要医疗疏散保险。无互联网接入。高风险 - 最高级别旅行警告。'
        ],
        'fr' => [
            'name' => 'Corée du Nord (RPDC)',
            'summary' => 'La Corée du Nord a l\'une des politiques d\'entrée les plus restrictives. Tous les étrangers doivent obtenir un visa et voyager uniquement avec des agences approuvées.',
            'visa_req' => 'VISA OBLIGATOIRE. Processus très strict. Réservation via voyagistes agréés uniquement. Délai 6-8 semaines. Guides obligatoires en permanence.',
            'duration' => 'Visa touristique: 7-21 jours',
            'passport' => '6 mois de validité minimum, 2 pages vierges',
            'fee' => '$50-80 USD + forfait voyage $1,000-3,000+',
            'processing' => '6-8 semaines minimum',
            'url' => 'Contacter agences: Koryo Tours, Young Pioneer Tours',
            'arrival_card' => 'OUI - Carte d\'arrivée obligatoire',
            'additional' => 'Voyage avec opérateur agréé obligatoire. Respect extrême envers la direction. Restrictions photo strictes. Interdit: textes religieux, critiques, drones. Utiliser CNY, USD ou EUR. Pas de GAB. Assurance avec évacuation médicale obligatoire. Pas d\'accès internet. RISQUE ÉLEVÉ - Avertissements maximaux.'
        ],
        'de' => [
            'name' => 'Nordkorea (DVRK)',
            'summary' => 'Nordkorea hat eine der restriktivsten Einreisebestimmungen. Alle Ausländer müssen ein Visum erhalten und nur über genehmigte Reiseveranstalter reisen.',
            'visa_req' => 'VISUM ERFORDERLICH. Sehr strenger Prozess. Buchung nur über zugelassene Reiseveranstalter. Bearbeitungszeit 6-8 Wochen. Guides jederzeit obligatorisch.',
            'duration' => 'Touristenvisum: 7-21 Tage',
            'passport' => 'Mindestens 6 Monate gültig, 2 leere Seiten',
            'fee' => '$50-80 USD + Reisepaket $1,000-3,000+',
            'processing' => 'Mindestens 6-8 Wochen',
            'url' => 'Reiseveranstalter kontaktieren',
            'arrival_card' => 'JA - Ankunftskarte erforderlich',
            'additional' => 'Reise mit genehmigtem Veranstalter Pflicht. Extremer Respekt vor der Führung erforderlich. Strenge Fotoverbote. Verboten: religiöse Texte, Kritik, Drohnen. CNY, USD oder EUR verwenden. Keine Geldautomaten. Versicherung mit med. Evakuierung erforderlich. Kein Internetzugang. HOHES RISIKO - Höchste Reisewarnungen.'
        ],
        'it' => [
            'name' => 'Corea del Nord (RPDC)',
            'summary' => 'La Corea del Nord ha una delle politiche di ingresso più restrittive. Tutti gli stranieri devono ottenere un visto e viaggiare solo con tour operator approvati.',
            'visa_req' => 'VISTO OBBLIGATORIO. Processo molto rigido. Prenotazione solo tramite tour operator approvati. Elaborazione 6-8 settimane. Guide obbligatorie sempre.',
            'duration' => 'Visto turistico: 7-21 giorni',
            'passport' => 'Validità minima 6 mesi, 2 pagine vuote',
            'fee' => '$50-80 USD + pacchetto turistico $1,000-3,000+',
            'processing' => 'Minimo 6-8 settimane',
            'url' => 'Contattare tour operator approvati',
            'arrival_card' => 'SÌ - Carta di arrivo obbligatoria',
            'additional' => 'Viaggio con operatore approvato obbligatorio. Rispetto estremo verso la leadership. Restrizioni fotografiche rigide. Vietato: testi religiosi, critiche, droni. Usare CNY, USD o EUR. Nessun bancomat. Assicurazione con evacuazione medica obbligatoria. Nessun accesso internet. ALTO RISCHIO - Massimi avvertimenti di viaggio.'
        ],
        'ar' => [
            'name' => 'كوريا الشمالية',
            'summary' => 'كوريا الشمالية لديها واحدة من أكثر سياسات الدخول تقييدًا. يجب على جميع الأجانب الحصول على تأشيرة والسفر فقط مع منظمي رحلات معتمدين.',
            'visa_req' => 'تأشيرة إلزامية. عملية صارمة جدًا. الحجز فقط من خلال منظمي رحلات معتمدين. معالجة 6-8 أسابيع. مرشدون إلزاميون دائمًا.',
            'duration' => 'تأشيرة سياحية: 7-21 يومًا',
            'passport' => 'صلاحية 6 أشهر كحد أدنى، صفحتان فارغتان',
            'fee' => '50-80 دولار أمريكي + حزمة سياحية 1,000-3,000 دولار+',
            'processing' => '6-8 أسابيع كحد أدنى',
            'url' => 'الاتصال بمنظمي الرحلات المعتمدين',
            'arrival_card' => 'نعم - بطاقة وصول إلزامية',
            'additional' => 'السفر مع منظم معتمد إلزامي. احترام شديد للقيادة. قيود تصوير صارمة. محظور: نصوص دينية، انتقادات، طائرات بدون طيار. استخدام يوان أو دولار أو يورو. لا يوجد صراف آلي. تأمين مع إخلاء طبي. لا يوجد إنترنت. مخاطر عالية - أقصى تحذيرات السفر.'
        ]
    ];
    
    echo "Step 3: Inserting translations...\n";
    
    foreach ($translations as $lang => $data) {
        $stmt = $pdo->prepare("
            INSERT INTO country_translations (
                country_id, lang_code, country_name, entry_summary, visa_requirements,
                visa_duration, passport_validity, visa_fee, processing_time, official_visa_url,
                arrival_card_required, additional_docs, last_verified, created_at, updated_at
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), NOW(), NOW()
            )
        ");
        
        $stmt->execute([
            $northKoreaId,
            $lang,
            $data['name'],
            $data['summary'],
            $data['visa_req'],
            $data['duration'],
            $data['passport'],
            $data['fee'],
            $data['processing'],
            $data['url'],
            $data['arrival_card'],
            $data['additional']
        ]);
        
        echo "  ✓ {$lang} - {$data['name']}\n";
    }
    
    // Commit transaction
    $pdo->commit();
    
    echo "\n" . str_repeat('=', 60) . "\n";
    echo "✅ SUCCESS! North Korea added to database\n";
    echo str_repeat('=', 60) . "\n\n";
    
    echo "Summary:\n";
    echo "  Country ID:    {$northKoreaId}\n";
    echo "  Country Code:  PRK (🇰🇵)\n";
    echo "  Capital:       Pyongyang\n";
    echo "  Region:        Asia\n";
    echo "  Visa Type:     Visa Required (Extremely Strict)\n";
    echo "  Translations:  7 languages (EN, ES, ZH, FR, DE, IT, AR)\n\n";
    
    echo "North Korea will now appear on the homepage!\n";
    echo "Visit: http://localhost/ArrivalCards/\n\n";
    
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "\n❌ ERROR: Failed to add North Korea\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n\n";
    exit(1);
}
?>
