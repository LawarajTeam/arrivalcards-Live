<?php
/**
 * Fix Missing Translations (comment-caused skips)
 * Run: https://arrivalcards.com/run_fix_translations.php?key=FixTrans2025!
 */

if (!isset($_GET['key']) || $_GET['key'] !== 'FixTrans2025!') {
    die('Unauthorized');
}

require_once __DIR__ . '/includes/config.php';

echo "<h2>Inserting Missing Translations...</h2><pre>";

$statements = [
    // =============================================
    // COMPARE PASSPORTS - 6 missing keys
    // =============================================

    // cp_page_title
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'cp_page_title', 'Compare Passports - Side-by-Side Visa Requirements | Arrival Cards', 'compare_passports'),
    ('es', 'cp_page_title', 'Comparar Pasaportes - Requisitos de Visa Lado a Lado | Arrival Cards', 'compare_passports'),
    ('zh', 'cp_page_title', '比较护照 - 签证要求并排对比 | Arrival Cards', 'compare_passports'),
    ('fr', 'cp_page_title', 'Comparer les Passeports - Exigences de Visa Côte à Côte | Arrival Cards', 'compare_passports'),
    ('de', 'cp_page_title', 'Reisepässe Vergleichen - Visaanforderungen im Vergleich | Arrival Cards', 'compare_passports'),
    ('it', 'cp_page_title', 'Confronta Passaporti - Requisiti Visto a Confronto | Arrival Cards', 'compare_passports'),
    ('ar', 'cp_page_title', 'مقارنة جوازات السفر - متطلبات التأشيرة جنباً إلى جنب | Arrival Cards', 'compare_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // cp_hero_title
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'cp_hero_title', 'Compare Passports', 'compare_passports'),
    ('es', 'cp_hero_title', 'Comparar Pasaportes', 'compare_passports'),
    ('zh', 'cp_hero_title', '比较护照', 'compare_passports'),
    ('fr', 'cp_hero_title', 'Comparer les Passeports', 'compare_passports'),
    ('de', 'cp_hero_title', 'Reisepässe Vergleichen', 'compare_passports'),
    ('it', 'cp_hero_title', 'Confronta Passaporti', 'compare_passports'),
    ('ar', 'cp_hero_title', 'مقارنة جوازات السفر', 'compare_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // cp_first_passport
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'cp_first_passport', 'First Passport', 'compare_passports'),
    ('es', 'cp_first_passport', 'Primer Pasaporte', 'compare_passports'),
    ('zh', 'cp_first_passport', '第一本护照', 'compare_passports'),
    ('fr', 'cp_first_passport', 'Premier Passeport', 'compare_passports'),
    ('de', 'cp_first_passport', 'Erster Reisepass', 'compare_passports'),
    ('it', 'cp_first_passport', 'Primo Passaporto', 'compare_passports'),
    ('ar', 'cp_first_passport', 'الجواز الأول', 'compare_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // cp_visa_free
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'cp_visa_free', 'Visa-Free', 'compare_passports'),
    ('es', 'cp_visa_free', 'Sin Visa', 'compare_passports'),
    ('zh', 'cp_visa_free', '免签', 'compare_passports'),
    ('fr', 'cp_visa_free', 'Sans Visa', 'compare_passports'),
    ('de', 'cp_visa_free', 'Visafrei', 'compare_passports'),
    ('it', 'cp_visa_free', 'Senza Visto', 'compare_passports'),
    ('ar', 'cp_visa_free', 'بدون تأشيرة', 'compare_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // cp_better_access
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'cp_better_access', 'Better Access', 'compare_passports'),
    ('es', 'cp_better_access', 'Mejor Acceso', 'compare_passports'),
    ('zh', 'cp_better_access', '更好的入境权', 'compare_passports'),
    ('fr', 'cp_better_access', 'Meilleur Accès', 'compare_passports'),
    ('de', 'cp_better_access', 'Besserer Zugang', 'compare_passports'),
    ('it', 'cp_better_access', 'Accesso Migliore', 'compare_passports'),
    ('ar', 'cp_better_access', 'وصول أفضل', 'compare_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // cp_dest_comparison
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'cp_dest_comparison', 'Destination-by-Destination Comparison', 'compare_passports'),
    ('es', 'cp_dest_comparison', 'Comparación Destino por Destino', 'compare_passports'),
    ('zh', 'cp_dest_comparison', '逐个目的地对比', 'compare_passports'),
    ('fr', 'cp_dest_comparison', 'Comparaison Destination par Destination', 'compare_passports'),
    ('de', 'cp_dest_comparison', 'Ziel-für-Ziel-Vergleich', 'compare_passports'),
    ('it', 'cp_dest_comparison', 'Confronto Destinazione per Destinazione', 'compare_passports'),
    ('ar', 'cp_dest_comparison', 'مقارنة وجهة بوجهة', 'compare_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // =============================================
    // BEST PASSPORTS - 9 missing keys (same bug)
    // =============================================

    // bp_page_title
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'bp_page_title', 'Best Passports in the World 2026 - Global Ranking | Arrival Cards', 'best_passports'),
    ('es', 'bp_page_title', 'Mejores Pasaportes del Mundo 2026 - Ranking Global | Arrival Cards', 'best_passports'),
    ('zh', 'bp_page_title', '2026年世界最佳护照 - 全球排名 | Arrival Cards', 'best_passports'),
    ('fr', 'bp_page_title', 'Meilleurs Passeports du Monde 2026 - Classement Mondial | Arrival Cards', 'best_passports'),
    ('de', 'bp_page_title', 'Beste Reisepässe der Welt 2026 - Globales Ranking | Arrival Cards', 'best_passports'),
    ('it', 'bp_page_title', 'Migliori Passaporti del Mondo 2026 - Classifica Globale | Arrival Cards', 'best_passports'),
    ('ar', 'bp_page_title', 'أفضل جوازات السفر في العالم 2026 - التصنيف العالمي | Arrival Cards', 'best_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // bp_hero_title
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'bp_hero_title', 'Best Passports in the World 2026', 'best_passports'),
    ('es', 'bp_hero_title', 'Mejores Pasaportes del Mundo 2026', 'best_passports'),
    ('zh', 'bp_hero_title', '2026年世界最佳护照', 'best_passports'),
    ('fr', 'bp_hero_title', 'Meilleurs Passeports du Monde 2026', 'best_passports'),
    ('de', 'bp_hero_title', 'Beste Reisepässe der Welt 2026', 'best_passports'),
    ('it', 'bp_hero_title', 'Migliori Passaporti del Mondo 2026', 'best_passports'),
    ('ar', 'bp_hero_title', 'أفضل جوازات السفر في العالم 2026', 'best_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // bp_passports_ranked
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'bp_passports_ranked', 'Passports Ranked', 'best_passports'),
    ('es', 'bp_passports_ranked', 'Pasaportes Clasificados', 'best_passports'),
    ('zh', 'bp_passports_ranked', '护照已排名', 'best_passports'),
    ('fr', 'bp_passports_ranked', 'Passeports Classés', 'best_passports'),
    ('de', 'bp_passports_ranked', 'Pässe im Ranking', 'best_passports'),
    ('it', 'bp_passports_ranked', 'Passaporti Classificati', 'best_passports'),
    ('ar', 'bp_passports_ranked', 'جوازات سفر مصنفة', 'best_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // bp_about_title
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'bp_about_title', 'About This Ranking', 'best_passports'),
    ('es', 'bp_about_title', 'Acerca de Este Ranking', 'best_passports'),
    ('zh', 'bp_about_title', '关于此排名', 'best_passports'),
    ('fr', 'bp_about_title', 'À Propos de Ce Classement', 'best_passports'),
    ('de', 'bp_about_title', 'Über Dieses Ranking', 'best_passports'),
    ('it', 'bp_about_title', 'Informazioni su Questa Classifica', 'best_passports'),
    ('ar', 'bp_about_title', 'حول هذا التصنيف', 'best_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // bp_th_rank
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'bp_th_rank', 'Rank', 'best_passports'),
    ('es', 'bp_th_rank', 'Rango', 'best_passports'),
    ('zh', 'bp_th_rank', '排名', 'best_passports'),
    ('fr', 'bp_th_rank', 'Rang', 'best_passports'),
    ('de', 'bp_th_rank', 'Rang', 'best_passports'),
    ('it', 'bp_th_rank', 'Posizione', 'best_passports'),
    ('ar', 'bp_th_rank', 'المرتبة', 'best_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // bp_destinations
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'bp_destinations', 'destinations', 'best_passports'),
    ('es', 'bp_destinations', 'destinos', 'best_passports'),
    ('zh', 'bp_destinations', '个目的地', 'best_passports'),
    ('fr', 'bp_destinations', 'destinations', 'best_passports'),
    ('de', 'bp_destinations', 'Ziele', 'best_passports'),
    ('it', 'bp_destinations', 'destinazioni', 'best_passports'),
    ('ar', 'bp_destinations', 'وجهة', 'best_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // bp_howto_title
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'bp_howto_title', 'How to Read This Table', 'best_passports'),
    ('es', 'bp_howto_title', 'Cómo Leer Esta Tabla', 'best_passports'),
    ('zh', 'bp_howto_title', '如何阅读此表格', 'best_passports'),
    ('fr', 'bp_howto_title', 'Comment Lire Ce Tableau', 'best_passports'),
    ('de', 'bp_howto_title', 'So Lesen Sie Diese Tabelle', 'best_passports'),
    ('it', 'bp_howto_title', 'Come Leggere Questa Tabella', 'best_passports'),
    ('ar', 'bp_howto_title', 'كيفية قراءة هذا الجدول', 'best_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // bp_sources_title
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'bp_sources_title', 'Sources & Methodology', 'best_passports'),
    ('es', 'bp_sources_title', 'Fuentes y Metodología', 'best_passports'),
    ('zh', 'bp_sources_title', '数据来源与方法论', 'best_passports'),
    ('fr', 'bp_sources_title', 'Sources et Méthodologie', 'best_passports'),
    ('de', 'bp_sources_title', 'Quellen und Methodik', 'best_passports'),
    ('it', 'bp_sources_title', 'Fonti e Metodologia', 'best_passports'),
    ('ar', 'bp_sources_title', 'المصادر والمنهجية', 'best_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",

    // bp_disclaimer
    "INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
    ('en', 'bp_disclaimer', '<strong>Disclaimer:</strong> Visa policies change frequently. This ranking is for informational purposes only and should not be considered legal advice. Always verify the latest entry requirements with the destination country''s official immigration authority or your nearest embassy before travelling.', 'best_passports'),
    ('es', 'bp_disclaimer', '<strong>Aviso Legal:</strong> Las políticas de visa cambian con frecuencia. Este ranking es solo con fines informativos y no debe considerarse asesoramiento legal. Siempre verifique los últimos requisitos de entrada con la autoridad de inmigración oficial del país de destino o su embajada más cercana antes de viajar.', 'best_passports'),
    ('zh', 'bp_disclaimer', '<strong>免责声明：</strong>签证政策经常变化。此排名仅供参考，不应视为法律建议。旅行前请务必向目的地国家的官方移民机构或最近的大使馆核实最新入境要求。', 'best_passports'),
    ('fr', 'bp_disclaimer', '<strong>Avertissement :</strong> Les politiques de visa changent fréquemment. Ce classement est uniquement à titre informatif et ne doit pas être considéré comme un avis juridique. Vérifiez toujours les dernières exigences d''entrée auprès de l''autorité d''immigration officielle du pays de destination ou de votre ambassade la plus proche avant de voyager.', 'best_passports'),
    ('de', 'bp_disclaimer', '<strong>Haftungsausschluss:</strong> Visarichtlinien ändern sich häufig. Dieses Ranking dient nur zu Informationszwecken und sollte nicht als Rechtsberatung angesehen werden. Überprüfen Sie immer die aktuellsten Einreisebestimmungen bei der offiziellen Einwanderungsbehörde des Ziellandes oder Ihrer nächsten Botschaft, bevor Sie reisen.', 'best_passports'),
    ('it', 'bp_disclaimer', '<strong>Avvertenza:</strong> Le politiche sui visti cambiano frequentemente. Questa classifica è solo a scopo informativo e non deve essere considerata consulenza legale. Verificare sempre i requisiti di ingresso più recenti presso l''autorità ufficiale per l''immigrazione del paese di destinazione o l''ambasciata più vicina prima di viaggiare.', 'best_passports'),
    ('ar', 'bp_disclaimer', '<strong>إخلاء المسؤولية:</strong> تتغير سياسات التأشيرات بشكل متكرر. هذا التصنيف لأغراض إعلامية فقط ولا ينبغي اعتباره استشارة قانونية. تحقق دائماً من أحدث متطلبات الدخول لدى هيئة الهجرة الرسمية في بلد الوجهة أو أقرب سفارة قبل السفر.', 'best_passports')
    ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)",
];

$success = 0;
$errors = 0;

foreach ($statements as $stmt) {
    try {
        $pdo->exec($stmt);
        $success++;
    } catch (PDOException $e) {
        echo "ERROR: " . htmlspecialchars($e->getMessage()) . "\n";
        echo "Key: " . htmlspecialchars(substr($stmt, 0, 120)) . "...\n\n";
        $errors++;
    }
}

echo "✅ Executed: $success statements\n";
echo "❌ Errors: $errors\n\n";

// Verify both pages
echo "=== COMPARE PASSPORTS ===\n";
$check = $pdo->query("SELECT lang_code, COUNT(*) as cnt FROM translations WHERE category = 'compare_passports' GROUP BY lang_code ORDER BY lang_code");
foreach ($check->fetchAll() as $row) {
    echo "  {$row['lang_code']}: {$row['cnt']} keys\n";
}
$total = $pdo->query("SELECT COUNT(*) FROM translations WHERE category = 'compare_passports'")->fetchColumn();
echo "Total: $total\n\n";

echo "=== BEST PASSPORTS ===\n";
$check = $pdo->query("SELECT lang_code, COUNT(*) as cnt FROM translations WHERE category = 'best_passports' GROUP BY lang_code ORDER BY lang_code");
foreach ($check->fetchAll() as $row) {
    echo "  {$row['lang_code']}: {$row['cnt']} keys\n";
}
$total = $pdo->query("SELECT COUNT(*) FROM translations WHERE category = 'best_passports'")->fetchColumn();
echo "Total: $total\n";

echo "</pre>";
echo "<p>✅ <strong>Done!</strong> Expected: 24 keys/lang for compare, 26 keys/lang for best passports. Delete this file after use.</p>";
?>
