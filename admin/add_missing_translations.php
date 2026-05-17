<?php
/**
 * One-time: add missing UI translation keys
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$missing = [
    'visit_official_site' => [
        'en' => 'Visit Official Site',
        'es' => 'Visitar Sitio Oficial',
        'zh' => '访问官方网站',
        'fr' => 'Visiter le site officiel',
        'de' => 'Offizielle Website besuchen',
        'it' => 'Visita il sito ufficiale',
        'ar' => 'زيارة الموقع الرسمي',
    ],
    'visit_website' => [
        'en' => 'Visit Website',
        'es' => 'Visitar Sitio Web',
        'zh' => '访问网站',
        'fr' => 'Visiter le site Web',
        'de' => 'Website besuchen',
        'it' => 'Visita il sito Web',
        'ar' => 'زيارة الموقع الإلكتروني',
    ],
    'ready_to_apply' => [
        'en' => 'Ready to Apply for Your Visa?',
        'es' => '¿Listo para solicitar tu visa?',
        'zh' => '准备好申请签证了吗？',
        'fr' => 'Prêt à demander votre visa ?',
        'de' => 'Bereit, Ihr Visum zu beantragen?',
        'it' => 'Pronto per richiedere il visto?',
        'ar' => 'هل أنت مستعد للتقدم بطلب تأشيرتك؟',
    ],
    'get_official_visa_info' => [
        'en' => 'Get the latest official visa information and application details',
        'es' => 'Obtenga la información oficial más reciente sobre visas y detalles de solicitud',
        'zh' => '获取最新官方签证信息和申请详情',
        'fr' => "Obtenez les dernières informations officielles sur les visas et les détails de demande",
        'de' => 'Erhalten Sie die neuesten offiziellen Visum-Informationen und Antragsdetails',
        'it' => 'Ottieni le ultime informazioni ufficiali sui visti e i dettagli della domanda',
        'ar' => 'احصل على أحدث المعلومات الرسمية للتأشيرة وتفاصيل التقديم',
    ],
    'requirements_details' => [
        'en' => 'Requirements Details',
        'es' => 'Detalles de Requisitos',
        'zh' => '要求详情',
        'fr' => 'Détails des exigences',
        'de' => 'Anforderungsdetails',
        'it' => 'Dettagli dei requisiti',
        'ar' => 'تفاصيل المتطلبات',
    ],
    'is_known_for' => [
        'en' => 'is known for',
        'es' => 'es conocido por',
        'zh' => '以...闻名',
        'fr' => 'est connu pour',
        'de' => 'ist bekannt für',
        'it' => 'è noto per',
        'ar' => 'معروف بـ',
    ],
    'major_airports' => [
        'en' => 'Major Airports',
        'es' => 'Principales Aeropuertos',
        'zh' => '主要机场',
        'fr' => 'Principaux aéroports',
        'de' => 'Wichtige Flughäfen',
        'it' => 'Aeroporti principali',
        'ar' => 'المطارات الرئيسية',
    ],
    'quick_facts' => [
        'en' => 'Quick Facts',
        'es' => 'Datos Rápidos',
        'zh' => '快速事实',
        'fr' => 'Faits rapides',
        'de' => 'Schnelle Fakten',
        'it' => 'Fatti rapidi',
        'ar' => 'حقائق سريعة',
    ],
    'travel_tips' => [
        'en' => 'Travel Tips',
        'es' => 'Consejos de Viaje',
        'zh' => '旅行提示',
        'fr' => 'Conseils de voyage',
        'de' => 'Reisetipps',
        'it' => 'Consigli di viaggio',
        'ar' => 'نصائح السفر',
    ],
];

$inserted = 0;
$skipped = 0;
$errors = [];

foreach ($missing as $key => $langs) {
    foreach ($langs as $lang => $value) {
        // Check if exists
        $stmt = $pdo->prepare("SELECT id FROM translations WHERE translation_key = ? AND lang_code = ?");
        $stmt->execute([$key, $lang]);
        if ($stmt->fetch()) {
            $skipped++;
            continue;
        }
        try {
            $stmt = $pdo->prepare("INSERT INTO translations (translation_key, lang_code, translation_value) VALUES (?, ?, ?)");
            $stmt->execute([$key, $lang, $value]);
            $inserted++;
        } catch (PDOException $e) {
            $errors[] = "$key/$lang: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Translations</title>
<style>body{font-family:sans-serif;max-width:600px;margin:40px auto;padding:20px}</style>
</head>
<body>
<h1>Missing Translation Keys</h1>
<p>✅ <strong><?php echo $inserted; ?></strong> translations inserted</p>
<p>⏭️ <strong><?php echo $skipped; ?></strong> already existed (skipped)</p>
<?php if ($errors): ?>
<p>❌ Errors:</p><ul><?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
<?php endif; ?>
<p><strong>⚠️ Delete this file:</strong> <code>admin/add_missing_translations.php</code></p>
<p><a href="/admin/">← Back to Admin</a></p>
</body>
</html>
