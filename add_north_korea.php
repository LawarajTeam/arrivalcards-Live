<?php
/**
 * Add North Korea to Database
 * Comprehensive entry with detailed visa and travel information
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Check if admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    requireAdmin();
}

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Add North Korea</title>";
echo "<style>body{font-family:sans-serif;max-width:800px;margin:2rem auto;padding:0 1rem;}";
echo "pre{background:#f5f5f5;padding:1rem;border-radius:8px;overflow-x:auto;}";
echo ".success{color:#10b981;font-weight:bold;}.error{color:#ef4444;font-weight:bold;}</style>";
echo "</head><body>";

echo "<h1>🇰🇵 Adding North Korea to Database</h1>";
echo "<p>This will add North Korea (DPRK) with comprehensive visa and travel information in 7 languages.</p>";
echo "<hr>";

try {
    // Start transaction
    $pdo->beginTransaction();
    
    echo "<h2>Step 1: Checking if North Korea already exists...</h2>";
    $stmt = $pdo->query("SELECT id, name_en FROM countries WHERE code = 'KP' OR country_code = 'PRK'");
    $existing = $stmt->fetch();
    
    if ($existing) {
        echo "<p class='error'>❌ North Korea already exists in database (ID: {$existing['id']}, Name: {$existing['name_en']})</p>";
        echo "<p>If you want to update it, please delete the existing entry first.</p>";
        $pdo->rollBack();
        echo "</body></html>";
        exit;
    }
    
    echo "<p class='success'>✓ North Korea not found - proceeding with insertion</p>";
    
    // Insert country
    echo "<h2>Step 2: Inserting North Korea into countries table...</h2>";
    $stmt = $pdo->prepare("
        INSERT INTO countries (
            country_code, code, name_en, capital, region, visa_type, 
            is_active, display_order, view_count, created_at, updated_at
        ) VALUES (
            'PRK', 'KP', 'North Korea', 'Pyongyang', 'Asia', 'visa_required',
            1, 110, 0, NOW(), NOW()
        )
    ");
    $stmt->execute();
    $northKoreaId = $pdo->lastInsertId();
    
    echo "<p class='success'>✓ Country inserted with ID: {$northKoreaId}</p>";
    
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
            'additional' => 'CRITICAL: Must travel with approved tour operator. Sign conduct agreement. Declare all electronics. No independent movement. Photography strictly restricted. No disrespect toward leadership. Violations can result in arrest and detention for months/years. Multiple foreign nationals have been detained. Extreme travel advisory warnings from all Western governments.',
            'cultural' => 'Extreme respect required for leadership and monuments. Never criticize leaders. Ask before photographing. No interaction with locals without permission. Conservative dress required. All actions monitored. Multiple tourists detained for minor perceived offenses.',
            'prohibited' => 'STRICTLY BANNED: Religious texts, materials critical of DPRK, South Korean media, drones, satellite phones, GPS devices, unauthorized cameras, any weapons. Large undeclared currency. Violations result in confiscation or arrest.',
            'currency' => 'Tourists cannot use North Korean Won. Must use Chinese Yuan (CNY), US Dollars (USD), or Euros (EUR). NO ATMs, credit cards, or banks available. Bring all cash needed. Declare all currency at entry.',
            'health' => 'Very limited medical facilities. Comprehensive insurance with medical evacuation coverage MANDATORY. Bring all medications. Only bottled water. Evacuations difficult and expensive ($50,000-150,000).',
            'connectivity' => 'NO internet access for tourists. Limited international phone calls available ($7-8/minute). Tourist SIM cards available at airport (~$50). No data services. Plan for complete communication blackout.',
            'advisory' => '⚠️ SEVERE RISK: USA "DO NOT TRAVEL" Level 4 warning. UK, Canada, Australia advise against all travel. Multiple cases of arbitrary detention of tourists for months to 15+ years. Forced confessions common. Limited consular access (no US embassy). Examples: Otto Warmbier detained 2016, died 2017. Political tensions make travel extremely risky. Consider alternative destinations. TRAVEL AT YOUR OWN RISK.'
        ],
        'es' => [
            'name' => 'Corea del Norte (RPDC)',
            'summary' => 'Corea del Norte tiene una de las políticas de entrada más restrictivas del mundo. Todos los extranjeros deben obtener visa previa y viajar solo con operadores aprobados.',
            'visa_req' => 'VISA OBLIGATORIA. Proceso muy estricto. Reserva solo a través de operadores aprobados. 6-8 semanas de procesamiento. Ciudadanos surcoreanos prohibidos.',
            'duration' => 'Visa turística: 7-21 días',
            'passport' => '6 meses de validez mínima',
            'fee' => '$50-80 USD + paquete turístico $1,000-3,000+',
            'processing' => '6-8 semanas mínimo',
            'url' => 'Contactar operadores: Koryo Tours, Young Pioneer Tours',
            'arrival_card' => 'SÍ - Tarjeta de llegada obligatoria',
            'additional' => 'Debe viajar con operador aprobado. Restricciones fotográficas estrictas.',
            'cultural' => 'Respeto extremo requerido hacia el liderazgo.',
            'prohibited' => 'Prohibido: textos religiosos, críticas al gobierno, drones',
            'currency' => 'Use CNY, USD o EUR. Sin cajeros automáticos.',
            'health' => 'Seguro con evacuación médica obligatorio',
            'connectivity' => 'Sin acceso a internet',
            'advisory' => 'ALTO RIESGO. Advertencias de viaje de nivel máximo.'
        ],
        'zh' => [
            'name' => '朝鲜（北韩）',
            'summary' => '朝鲜拥有世界上最严格的入境政策之一。所有外国人必须提前获得签证，只能通过政府批准的旅行社旅行。',
            'visa_req' => '强制性签证。非常严格的流程。必须通过批准的旅行社预订。处理时间6-8周。韩国公民禁止入境。',
            'duration' => '旅游签证：7-21天',
            'passport' => '至少6个月有效期',
            'fee' => '$50-80美元 + 旅游套餐$1,000-3,000+',
            'processing' => '至少6-8周',
            'url' => '联系批准的旅行社',
            'arrival_card' => '是 - 强制性入境卡',
            'additional' => '必须与批准的旅行社一起旅行。严格的摄影限制。',
            'cultural' => '对领导层需要极度尊重',
            'prohibited' => '禁止：宗教文本、批评政府的材料、无人机',
            'currency' => '使用人民币、美元或欧元。无ATM。',
            'health' => '需要医疗疏散保险',
            'connectivity' => '无互联网接入',
            'advisory' => '高风险。最高级别旅行警告。'
        ],
        'fr' => [
            'name' => 'Corée du Nord (RPDC)',
            'summary' => 'La Corée du Nord a l\'une des politiques d\'entrée les plus restrictives. Tous les étrangers doivent obtenir un visa et voyager uniquement avec des agences approuvées.',
            'visa_req' => 'VISA OBLIGATOIRE. Processus très strict. Réservation via voyagistes agréés uniquement. Délai 6-8 semaines.',
            'duration' => 'Visa touristique: 7-21 jours',
            'passport' => '6 mois de validité minimum',
            'fee' => '$50-80 USD + forfait voyage $1,000-3,000+',
            'processing' => '6-8 semaines minimum',
            'url' => 'Contacter agences: Koryo Tours, Young Pioneer Tours',
            'arrival_card' => 'OUI - Carte d\'arrivée obligatoire',
            'additional' => 'Voyage avec opérateur agréé obligatoire. Restrictions photo strictes.',
            'cultural' => 'Respect extrême requis envers la direction',
            'prohibited' => 'Interdit: textes religieux, critiques, drones',
            'currency' => 'Utiliser CNY, USD ou EUR. Pas de GAB.',
            'health' => 'Assurance avec évacuation médicale obligatoire',
            'connectivity' => 'Pas d\'accès internet',
            'advisory' => 'RISQUE ÉLEVÉ. Avertissements maximaux.'
        ],
        'de' => [
            'name' => 'Nordkorea (DVRK)',
            'summary' => 'Nordkorea hat eine der restriktivsten Einreisebestimmungen. Alle Ausländer müssen ein Visum erhalten und nur über genehmigte Reiseveranstalter reisen.',
            'visa_req' => 'VISUM ERFORDERLICH. Sehr strenger Prozess. Buchung nur über zugelassene Reiseveranstalter. Bearbeitungszeit 6-8 Wochen.',
            'duration' => 'Touristenvisum: 7-21 Tage',
            'passport' => 'Mindestens 6 Monate gültig',
            'fee' => '$50-80 USD + Reisepaket $1,000-3,000+',
            'processing' => 'Mindestens 6-8 Wochen',
            'url' => 'Reiseveranstalter kontaktieren',
            'arrival_card' => 'JA - Ankunftskarte erforderlich',
            'additional' => 'Reise mit genehmigtem Veranstalter Pflicht. Strenge Fotoverbote.',
            'cultural' => 'Extremer Respekt vor der Führung erforderlich',
            'prohibited' => 'Verboten: religiöse Texte, Kritik, Drohnen',
            'currency' => 'CNY, USD oder EUR verwenden. Keine Geldautomaten.',
            'health' => 'Versicherung mit med. Evakuierung erforderlich',
            'connectivity' => 'Kein Internetzugang',
            'advisory' => 'HOHES RISIKO. Höchste Reisewarnungen.'
        ],
        'it' => [
            'name' => 'Corea del Nord (RPDC)',
            'summary' => 'La Corea del Nord ha una delle politiche di ingresso più restrittive. Tutti gli stranieri devono ottenere un visto e viaggiare solo con tour operator approvati.',
            'visa_req' => 'VISTO OBBLIGATORIO. Processo molto rigido. Prenotazione solo tramite tour operator approvati. Elaborazione 6-8 settimane.',
            'duration' => 'Visto turistico: 7-21 giorni',
            'passport' => 'Validità minima 6 mesi',
            'fee' => '$50-80 USD + pacchetto turistico $1,000-3,000+',
            'processing' => 'Minimo 6-8 settimane',
            'url' => 'Contattare tour operator approvati',
            'arrival_card' => 'SÌ - Carta di arrivo obbligatoria',
            'additional' => 'Viaggio con operatore approvato obbligatorio. Restrizioni fotografiche rigide.',
            'cultural' => 'Rispetto estremo richiesto verso la leadership',
            'prohibited' => 'Vietato: testi religiosi, critiche, droni',
            'currency' => 'Usare CNY, USD o EUR. Nessun bancomat.',
            'health' => 'Assicurazione con evacuazione medica obbligatoria',
            'connectivity' => 'Nessun accesso internet',
            'advisory' => 'ALTO RISCHIO. Massimi avvertimenti di viaggio.'
        ],
        'ar' => [
            'name' => 'كوريا الشمالية',
            'summary' => 'كوريا الشمالية لديها واحدة من أكثر سياسات الدخول تقييدًا. يجب على جميع الأجانب الحصول على تأشيرة والسفر فقط مع منظمي رحلات معتمدين.',
            'visa_req' => 'تأشيرة إلزامية. عملية صارمة جدًا. الحجز فقط من خلال منظمي رحلات معتمدين. معالجة 6-8 أسابيع.',
            'duration' => 'تأشيرة سياحية: 7-21 يومًا',
            'passport' => 'صلاحية 6 أشهر كحد أدنى',
            'fee' => '50-80 دولار أمريكي + حزمة سياحية 1,000-3,000 دولار+',
            'processing' => '6-8 أسابيع كحد أدنى',
            'url' => 'الاتصال بمنظمي الرحلات المعتمدين',
            'arrival_card' => 'نعم - بطاقة وصول إلزامية',
            'additional' => 'السفر مع منظم معتمد إلزامي. قيود تصوير صارمة.',
            'cultural' => 'احترام شديد مطلوب للقيادة',
            'prohibited' => 'محظور: نصوص دينية، انتقادات، طائرات بدون طيار',
            'currency' => 'استخدام يوان صيني أو دولار أو يورو. لا يوجد صراف آلي.',
            'health' => 'تأمين مع إخلاء طبي إلزامي',
            'connectivity' => 'لا يوجد وصول للإنترنت',
            'advisory' => 'مخاطر عالية. أقصى تحذيرات السفر.'
        ]
    ];
    
    echo "<h2>Step 3: Inserting translations...</h2>";
    echo "<ul>";
    
    foreach ($translations as $lang => $data) {
        $stmt = $pdo->prepare("
            INSERT INTO country_translations (
                country_id, lang_code, country_name, entry_summary, visa_requirements,
                visa_duration, passport_validity, visa_fee, processing_time, official_visa_url,
                arrival_card_required, additional_docs, cultural_notes, prohibited_items,
                currency_exchange, health_requirements, connectivity, travel_advisory, last_verified
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
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
            $data['additional'],
            $data['cultural'],
            $data['prohibited'],
            $data['currency'],
            $data['health'],
            $data['connectivity'],
            $data['advisory']
        ]);
        
        echo "<li class='success'>✓ {$lang} - {$data['name']}</li>";
    }
    
    echo "</ul>";
    
    // Commit transaction
    $pdo->commit();
    
    echo "<h2>✅ Success!</h2>";
    echo "<p class='success'>North Korea has been added to the database with comprehensive information in 7 languages.</p>";
    
    echo "<h3>Summary:</h3>";
    echo "<ul>";
    echo "<li>Country ID: <strong>{$northKoreaId}</strong></li>";
    echo "<li>Country Code: <strong>KP</strong></li>";
    echo "<li>ISO Code: <strong>PRK</strong></li>";
    echo "<li>Capital: <strong>Pyongyang</strong></li>";
    echo "<li>Region: <strong>Asia</strong></li>";
    echo "<li>Visa Type: <strong>Visa Required (Extremely Strict)</strong></li>";
    echo "<li>Translations: <strong>7 languages (EN, ES, ZH, FR, DE, IT, AR)</strong></li>";
    echo "</ul>";
    
    echo "<h3>View on Site:</h3>";
    echo "<p>North Korea will now appear on the homepage. Visit:</p>";
    echo "<p><a href='" . APP_URL . "' target='_blank' style='color:#2563eb;font-weight:bold;'>" . APP_URL . "</a></p>";
    echo "<p><a href='" . APP_URL . "/country.php?id={$northKoreaId}' target='_blank' style='color:#2563eb;font-weight:bold;'>View North Korea details page</a></p>";
    
    echo "<hr>";
    echo "<p><a href='admin/countries.php' style='color:#2563eb;'>← Back to Countries Management</a></p>";
    
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "<h2 class='error'>❌ Error</h2>";
    echo "<p class='error'>Failed to add North Korea: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
?>
