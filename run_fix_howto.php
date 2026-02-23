<?php
/**
 * Fix bp_howto_text translations (contains CSS semicolons that break SQL parsing)
 * Run: https://arrivalcards.com/run_fix_howto.php?key=FixHowto2025!
 */

if (!isset($_GET['key']) || $_GET['key'] !== 'FixHowto2025!') {
    die('Unauthorized');
}

require_once __DIR__ . '/includes/config.php';

echo "<h2>Inserting bp_howto_text translations (prepared statements)...</h2><pre>";

$stmt = $pdo->prepare("INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES (?, 'bp_howto_text', ?, 'best_passports') ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)");

$translations = [
    'en' => '<strong>Rank:</strong> Henley Passport Index position (shared ranks indicate equal visa-free access).<br><strong>Visa-Free Score:</strong> Total destinations accessible without an advance visa (visa-free + visa on arrival).<br><strong>Details:</strong> Where available, shows our detailed breakdown — <span style="color: #28a745;">● Visa-free</span> | <span style="color: #17a2b8;">● VoA</span> (visa at airport) | <span style="color: #ffc107;">● eVisa</span> (apply online) | <span style="color: #dc3545;">● Visa required</span> (embassy application).<br>Click a country name to view its full visa requirements for Australian passport holders.',

    'es' => '<strong>Rango:</strong> Posición en el Índice de Pasaportes Henley (rangos compartidos indican acceso sin visa igual).<br><strong>Puntuación Sin Visa:</strong> Total de destinos accesibles sin visa previa (sin visa + visa a la llegada).<br><strong>Detalles:</strong> Donde esté disponible, muestra nuestro desglose detallado — <span style="color: #28a745;">● Sin visa</span> | <span style="color: #17a2b8;">● VLL</span> (visa en el aeropuerto) | <span style="color: #ffc107;">● eVisa</span> (solicitar en línea) | <span style="color: #dc3545;">● Visa requerida</span> (solicitud en embajada).<br>Haga clic en el nombre de un país para ver sus requisitos completos de visa para titulares de pasaporte australiano.',

    'zh' => '<strong>排名：</strong>亨利护照指数排位（相同排名表示免签入境数量相同）。<br><strong>免签分数：</strong>无需提前办理签证即可进入的目的地总数（免签+落地签）。<br><strong>详情：</strong>如有数据，显示详细分类 — <span style="color: #28a745;">● 免签</span> | <span style="color: #17a2b8;">● 落地签</span>（机场办理） | <span style="color: #ffc107;">● 电子签证</span>（在线申请） | <span style="color: #dc3545;">● 需要签证</span>（使馆申请）。<br>点击国家名称查看澳大利亚护照持有人的完整签证要求。',

    'fr' => '<strong>Rang :</strong> Position dans l\'Indice Henley des Passeports (les rangs partagés indiquent un accès sans visa égal).<br><strong>Score Sans Visa :</strong> Total des destinations accessibles sans visa préalable (sans visa + visa à l\'arrivée).<br><strong>Détails :</strong> Lorsque disponible, affiche notre ventilation détaillée — <span style="color: #28a745;">● Sans visa</span> | <span style="color: #17a2b8;">● VàA</span> (visa à l\'aéroport) | <span style="color: #ffc107;">● eVisa</span> (demande en ligne) | <span style="color: #dc3545;">● Visa requis</span> (demande à l\'ambassade).<br>Cliquez sur le nom d\'un pays pour voir les exigences complètes de visa pour les titulaires de passeport australien.',

    'de' => '<strong>Rang:</strong> Position im Henley Passport Index (geteilte Ränge bedeuten gleichen visafreien Zugang).<br><strong>Visafrei-Score:</strong> Gesamtzahl der Reiseziele ohne Vorausvisum (visafrei + Visum bei Ankunft).<br><strong>Details:</strong> Wo verfügbar, zeigt unsere detaillierte Aufschlüsselung — <span style="color: #28a745;">● Visafrei</span> | <span style="color: #17a2b8;">● VbA</span> (Visum am Flughafen) | <span style="color: #ffc107;">● eVisum</span> (Online-Antrag) | <span style="color: #dc3545;">● Visum erforderlich</span> (Botschaftsantrag).<br>Klicken Sie auf einen Ländernamen, um die vollständigen Visaanforderungen für australische Passinhaber anzuzeigen.',

    'it' => '<strong>Posizione:</strong> Posizione nell\'Indice Henley dei Passaporti (posizioni condivise indicano pari accesso senza visto).<br><strong>Punteggio Senza Visto:</strong> Destinazioni totali accessibili senza visto anticipato (senza visto + visto all\'arrivo).<br><strong>Dettagli:</strong> Dove disponibile, mostra la nostra ripartizione dettagliata — <span style="color: #28a745;">● Senza visto</span> | <span style="color: #17a2b8;">● VaA</span> (visto in aeroporto) | <span style="color: #ffc107;">● eVisto</span> (richiesta online) | <span style="color: #dc3545;">● Visto richiesto</span> (domanda all\'ambasciata).<br>Clicca sul nome di un paese per vedere i requisiti completi per i titolari di passaporto australiano.',

    'ar' => '<strong>المرتبة:</strong> الموقع في مؤشر هنلي لجوازات السفر (المراتب المشتركة تشير إلى وصول متساوٍ بدون تأشيرة).<br><strong>درجة بدون تأشيرة:</strong> إجمالي الوجهات التي يمكن الوصول إليها بدون تأشيرة مسبقة (بدون تأشيرة + تأشيرة عند الوصول).<br><strong>التفاصيل:</strong> حيث تتوفر البيانات، يظهر التفصيل الكامل — <span style="color: #28a745;">● بدون تأشيرة</span> | <span style="color: #17a2b8;">● عند الوصول</span> (تأشيرة في المطار) | <span style="color: #ffc107;">● تأشيرة إلكترونية</span> (تقديم عبر الإنترنت) | <span style="color: #dc3545;">● تأشيرة مطلوبة</span> (طلب في السفارة).<br>انقر على اسم الدولة لعرض متطلبات التأشيرة الكاملة لحاملي جواز السفر الأسترالي.',
];

$success = 0;
$errors = 0;

foreach ($translations as $lang => $value) {
    try {
        $stmt->execute([$lang, $value]);
        echo "✅ $lang: inserted\n";
        $success++;
    } catch (PDOException $e) {
        echo "❌ $lang: " . htmlspecialchars($e->getMessage()) . "\n";
        $errors++;
    }
}

echo "\n✅ Executed: $success\n";
echo "❌ Errors: $errors\n\n";

// Verify total
$check = $pdo->query("SELECT lang_code, COUNT(*) as cnt FROM translations WHERE category = 'best_passports' GROUP BY lang_code ORDER BY lang_code");
echo "Best Passports translations per language:\n";
foreach ($check->fetchAll() as $row) {
    echo "  {$row['lang_code']}: {$row['cnt']} keys\n";
}
$total = $pdo->query("SELECT COUNT(*) FROM translations WHERE category = 'best_passports'")->fetchColumn();
echo "\nTotal: $total (expected: 38 keys x 7 langs = 266)\n";

// Verify bp_howto_text specifically
$verify = $pdo->query("SELECT lang_code FROM translations WHERE translation_key = 'bp_howto_text' ORDER BY lang_code");
$langs = array_column($verify->fetchAll(), 'lang_code');
echo "\nbp_howto_text exists for: " . implode(', ', $langs) . "\n";

echo "</pre>";
echo "<p>✅ <strong>Done!</strong> Delete this file after use.</p>";
?>
