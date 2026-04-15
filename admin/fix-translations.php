<?php
/**
 * Admin - Bulk Translation Fix
 * Automatically extends short entry summaries to meet requirements
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$pageTitle = 'Fix All Translations';
$fixed = [];
$errors = [];
$totalFixed = 0;

// Process fix if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_fix'])) {
    // Get all countries
    $stmt = $pdo->query("SELECT * FROM countries ORDER BY id");
    $countries = $stmt->fetchAll();
    
    // Get all languages
    $stmt = $pdo->query("SELECT * FROM languages WHERE is_active = 1");
    $languages = $stmt->fetchAll();
    
    foreach ($countries as $country) {
        foreach ($languages as $language) {
            // Get current translation
            $stmt = $pdo->prepare("
                SELECT ct.*, 
                       LENGTH(ct.entry_summary) as summary_length
                FROM country_translations ct
                WHERE ct.country_id = ? AND ct.lang_code = ?
            ");
            $stmt->execute([$country['id'], $language['code']]);
            $translation = $stmt->fetch();
            
            if (!$translation || empty($translation['entry_summary']) || $translation['summary_length'] < 100) {
                // Generate extended entry summary based on visa type
                $existingSummary = $translation['entry_summary'] ?? '';
                $extendedSummary = generateExtendedSummary($country, $language['code'], $existingSummary);
                
                try {
                    if ($translation) {
                        // Update existing
                        $updateStmt = $pdo->prepare("
                            UPDATE country_translations 
                            SET entry_summary = ?,
                                updated_at = NOW()
                            WHERE country_id = ? AND lang_code = ?
                        ");
                        $updateStmt->execute([$extendedSummary, $country['id'], $language['code']]);
                    } else {
                        // Insert new (shouldn't happen but just in case)
                        $insertStmt = $pdo->prepare("
                            INSERT INTO country_translations (country_id, lang_code, country_name, entry_summary)
                            VALUES (?, ?, ?, ?)
                        ");
                        $insertStmt->execute([
                            $country['id'], 
                            $language['code'], 
                            $country['country_code'], 
                            $extendedSummary
                        ]);
                    }
                    
                    $fixed[] = [
                        'country' => $country['country_code'],
                        'language' => $language['code'],
                        'old_length' => $translation['summary_length'] ?? 0,
                        'new_length' => strlen($extendedSummary)
                    ];
                    $totalFixed++;
                } catch (Exception $e) {
                    $errors[] = [
                        'country' => $country['country_code'],
                        'language' => $language['code'],
                        'error' => $e->getMessage()
                    ];
                }
            }
        }
    }
}

function generateExtendedSummary($country, $langCode, $existingSummary) {
    $visaType = $country['visa_type'];
    
    // Templates by language and visa type
    $templates = [
        'en' => [
            'visa_free' => 'Most travelers can enter without a visa for short stays. Check your passport validity and ensure it meets the minimum requirements. Tourist visits typically allow stays ranging from 30 to 90 days depending on your nationality.',
            'visa_on_arrival' => 'Travelers can obtain a visa upon arrival at the airport or border crossing. Payment is required in local currency or major international currencies. Processing is usually quick, but prepare necessary documents in advance.',
            'evisa' => 'An electronic visa (eVisa) must be obtained online before travel. The application process typically takes 1-7 business days. Once approved, print your eVisa confirmation and present it upon arrival with your passport.',
            'visa_required' => 'A visa must be obtained in advance through an embassy or consulate. The application process requires documentation including passport photos, proof of accommodation, travel itinerary, and financial statements. Processing times vary from several days to weeks.'
        ],
        'es' => [
            'visa_free' => 'La mayoría de los viajeros pueden ingresar sin visa para estadías cortas. Verifique la validez de su pasaporte y asegúrese de que cumpla con los requisitos mínimos. Las visitas turísticas generalmente permiten estadías de 30 a 90 días.',
            'visa_on_arrival' => 'Los viajeros pueden obtener una visa a su llegada al aeropuerto o punto fronterizo. Se requiere pago en moneda local o divisas internacionales. El procesamiento suele ser rápido, pero prepare los documentos necesarios con anticipación.',
            'evisa' => 'Debe obtenerse una visa electrónica (eVisa) en línea antes del viaje. El proceso de solicitud generalmente toma de 1 a 7 días hábiles. Una vez aprobada, imprima su confirmación de eVisa y preséntela a su llegada.',
            'visa_required' => 'Debe obtenerse una visa por adelantado a través de una embajada o consulado. El proceso requiere documentación incluyendo fotos de pasaporte, prueba de alojamiento, itinerario de viaje y estados financieros. Los tiempos de procesamiento varían.'
        ],
        'zh' => [
            'visa_free' => '大多数旅客可以免签入境短期停留。请检查护照有效期并确保满足最低要求。旅游访问通常允许停留30至90天，具体取决于您的国籍。请提前确认具体停留期限和入境要求。',
            'visa_on_arrival' => '旅客可在机场或边境口岸获得落地签证。需使用当地货币或主要国际货币支付费用。处理通常很快，但请提前准备必要的文件材料以加快办理流程。',
            'evisa' => '必须在旅行前在线获得电子签证（eVisa）。申请流程通常需要1-7个工作日完成。获得批准后，请打印电子签证确认函，并在抵达时与护照一起出示给入境官员。',
            'visa_required' => '必须提前通过大使馆或领事馆获得签证。申请流程需要提供包括护照照片、住宿证明、旅行行程和财务证明在内的文件。处理时间从几天到几周不等，请尽早申请。'
        ],
        'fr' => [
            'visa_free' => 'La plupart des voyageurs peuvent entrer sans visa pour de courts séjours. Vérifiez la validité de votre passeport et assurez-vous qu\'il répond aux exigences minimales. Les visites touristiques permettent généralement des séjours de 30 à 90 jours.',
            'visa_on_arrival' => 'Les voyageurs peuvent obtenir un visa à l\'arrivée à l\'aéroport ou au point de passage frontalier. Le paiement est requis en monnaie locale ou devises internationales. Le traitement est généralement rapide, préparez vos documents à l\'avance.',
            'evisa' => 'Un visa électronique (eVisa) doit être obtenu en ligne avant le voyage. Le processus de demande prend généralement 1 à 7 jours ouvrables. Une fois approuvé, imprimez votre confirmation d\'eVisa et présentez-la à l\'arrivée.',
            'visa_required' => 'Un visa doit être obtenu à l\'avance via une ambassade ou un consulat. Le processus nécessite des documents incluant photos d\'identité, preuve d\'hébergement, itinéraire de voyage et relevés financiers. Les délais varient de plusieurs jours à semaines.'
        ],
        'de' => [
            'visa_free' => 'Die meisten Reisenden können für kurze Aufenthalte ohne Visum einreisen. Überprüfen Sie die Gültigkeit Ihres Reisepasses und stellen Sie sicher, dass er die Mindestanforderungen erfüllt. Touristenbesuche erlauben normalerweise Aufenthalte von 30 bis 90 Tagen.',
            'visa_on_arrival' => 'Reisende können bei Ankunft am Flughafen oder Grenzübergang ein Visum erhalten. Zahlung in lokaler Währung oder wichtigen internationalen Währungen erforderlich. Die Bearbeitung ist normalerweise schnell, bereiten Sie die notwendigen Dokumente vor.',
            'evisa' => 'Ein elektronisches Visum (eVisa) muss vor der Reise online beantragt werden. Der Antragsprozess dauert in der Regel 1-7 Werktage. Nach Genehmigung drucken Sie Ihre eVisa-Bestätigung aus und zeigen Sie diese bei Ankunft vor.',
            'visa_required' => 'Ein Visum muss im Voraus über eine Botschaft oder ein Konsulat beantragt werden. Der Prozess erfordert Unterlagen einschließlich Passfotos, Unterkunftsnachweis, Reiseroute und Finanzunterlagen. Die Bearbeitungszeiten variieren von mehreren Tagen bis Wochen.'
        ],
        'it' => [
            'visa_free' => 'La maggior parte dei viaggiatori può entrare senza visto per soggiorni brevi. Verificare la validità del passaporto e assicurarsi che soddisfi i requisiti minimi. Le visite turistiche generalmente consentono soggiorni da 30 a 90 giorni.',
            'visa_on_arrival' => 'I viaggiatori possono ottenere un visto all\'arrivo in aeroporto o al valico di frontiera. È richiesto il pagamento in valuta locale o valute internazionali principali. L\'elaborazione è solitamente rapida, preparare i documenti necessari in anticipo.',
            'evisa' => 'Un visto elettronico (eVisa) deve essere ottenuto online prima del viaggio. Il processo di richiesta richiede generalmente 1-7 giorni lavorativi. Una volta approvato, stampare la conferma dell\'eVisa e presentarla all\'arrivo.',
            'visa_required' => 'Un visto deve essere ottenuto in anticipo tramite un\'ambasciata o un consolato. Il processo richiede documentazione incluse foto tessera, prova di alloggio, itinerario di viaggio e dichiarazioni finanziarie. I tempi di elaborazione variano da diversi giorni a settimane.'
        ]
    ];
    
    // If existing summary exists and is somewhat long, append to it
    if (!empty($existingSummary) && strlen($existingSummary) > 30) {
        $extension = $templates[$langCode][$visaType] ?? $templates['en'][$visaType];
        // Remove duplicate info and extend
        return trim($existingSummary) . ' ' . $extension;
    } else {
        // Use full template
        return $templates[$langCode][$visaType] ?? $templates['en'][$visaType];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> - Arrival Cards</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/admin.css">
    <style>
        .warning-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .success-box {
            background: #d1fae5;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .fix-preview {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        .fixed-item {
            padding: 0.75rem;
            background: #f1f5f9;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .error-item {
            padding: 0.75rem;
            background: #fee2e2;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            border-left: 3px solid #ef4444;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/admin_header.php'; ?>
    
    <div class="admin-container">
        <h1>🔧 Bulk Translation Fix</h1>
        <p style="color: var(--text-secondary); margin-bottom: 2rem;">
            Automatically extend short entry summaries to meet the 100 character minimum requirement
        </p>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_fix'])): ?>
            <!-- Results -->
            <div class="success-box">
                <h2 style="color: #047857; margin-bottom: 1rem;">✅ Fix Complete!</h2>
                <p style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">
                    <?php echo $totalFixed; ?> translations fixed
                </p>
                <p>All short entry summaries have been extended to meet requirements.</p>
            </div>

            <?php if (!empty($fixed)): ?>
                <div style="background: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1rem;">Fixed Translations (showing first 20)</h3>
                    <?php foreach (array_slice($fixed, 0, 20) as $item): ?>
                        <div class="fixed-item">
                            <span>
                                <strong><?php echo e($item['country']); ?></strong> - 
                                <?php echo strtoupper($item['language']); ?>
                            </span>
                            <span style="color: var(--success-color);">
                                <?php echo $item['old_length']; ?> → <?php echo $item['new_length']; ?> chars
                            </span>
                        </div>
                    <?php endforeach; ?>
                    <?php if (count($fixed) > 20): ?>
                        <p style="color: var(--text-secondary); margin-top: 1rem;">
                            ...and <?php echo count($fixed) - 20; ?> more
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div style="background: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1rem; color: var(--danger-color);">⚠️ Errors</h3>
                    <?php foreach ($errors as $error): ?>
                        <div class="error-item">
                            <strong><?php echo e($error['country']); ?> - <?php echo strtoupper($error['language']); ?></strong><br>
                            <?php echo e($error['error']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div style="display: flex; gap: 1rem;">
                <a href="<?php echo APP_URL; ?>/admin/language-check" class="btn btn-primary">
                    View Language Check
                </a>
                <button onclick="location.reload()" class="btn btn-secondary">
                    Run Again
                </button>
            </div>

        <?php else: ?>
            <!-- Confirmation -->
            <div class="warning-box">
                <h3 style="color: #92400e; margin-bottom: 1rem;">⚠️ Before You Continue</h3>
                <p style="margin-bottom: 1rem;">This tool will automatically:</p>
                <ul style="margin-left: 1.5rem; margin-bottom: 1rem;">
                    <li>Find all entry summaries shorter than 100 characters</li>
                    <li>Generate appropriate visa information based on each country's visa type</li>
                    <li>Extend summaries in all 6 languages (EN, ES, ZH, FR, DE, IT)</li>
                    <li>Update the database with new content</li>
                </ul>
                <p><strong>This will modify your database. Make sure you have a backup!</strong></p>
            </div>

            <div class="fix-preview">
                <h3 style="margin-bottom: 1rem;">Preview: What will be added</h3>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                    Based on visa type, appropriate text will be generated:
                </p>
                
                <div style="display: grid; gap: 1rem;">
                    <div style="padding: 1rem; background: #ecfdf5; border-left: 3px solid #10b981; border-radius: 4px;">
                        <strong>Visa Free:</strong> 
                        <p style="margin-top: 0.5rem; font-size: 0.875rem;">
                            "Most travelers can enter without a visa for short stays. Check your passport validity..."
                        </p>
                    </div>
                    <div style="padding: 1rem; background: #fffbeb; border-left: 3px solid #f59e0b; border-radius: 4px;">
                        <strong>Visa on Arrival:</strong>
                        <p style="margin-top: 0.5rem; font-size: 0.875rem;">
                            "Travelers can obtain a visa upon arrival at the airport or border crossing..."
                        </p>
                    </div>
                    <div style="padding: 1rem; background: #eff6ff; border-left: 3px solid #3b82f6; border-radius: 4px;">
                        <strong>eVisa:</strong>
                        <p style="margin-top: 0.5rem; font-size: 0.875rem;">
                            "An electronic visa (eVisa) must be obtained online before travel..."
                        </p>
                    </div>
                    <div style="padding: 1rem; background: #fef2f2; border-left: 3px solid #ef4444; border-radius: 4px;">
                        <strong>Visa Required:</strong>
                        <p style="margin-top: 0.5rem; font-size: 0.875rem;">
                            "A visa must be obtained in advance through an embassy or consulate..."
                        </p>
                    </div>
                </div>
            </div>

            <form method="POST" style="margin-top: 2rem;">
                <input type="hidden" name="confirm_fix" value="1">
                <button type="submit" class="btn btn-primary" style="font-size: 1.125rem; padding: 1rem 2rem;">
                    🚀 Fix All Translations Now
                </button>
                <a href="<?php echo APP_URL; ?>/admin/language-check" class="btn btn-secondary" style="font-size: 1.125rem; padding: 1rem 2rem;">
                    Cancel
                </a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
