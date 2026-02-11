<?php
/**
 * Fix all language check errors
 * - Remove placeholder text from Marshall Islands (English)
 * - Add missing entry summaries for Chinese translations
 * - Extend short entry summaries to meet minimum 100 chars
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

echo "<h1>Fixing Language Check Errors</h1>\n";
echo "<pre>\n";

// Get country IDs for the problem countries
$countryCodes = ['MHL', 'BEN', 'BOL', 'BIH', 'BRN', 'BDI', 'CPV'];

foreach ($countryCodes as $code) {
    $stmt = $pdo->prepare("SELECT id, country_code, visa_type FROM countries WHERE country_code = ?");
    $stmt->execute([$code]);
    $country = $stmt->fetch();
    
    if (!$country) {
        echo "❌ Country $code not found\n";
        continue;
    }
    
    echo "\n" . str_repeat("=", 70) . "\n";
    echo "Processing: {$country['country_code']} (ID: {$country['id']})\n";
    echo str_repeat("=", 70) . "\n";
    
    // Check English translation for Marshall Islands
    if ($code === 'MHL') {
        $stmt = $pdo->prepare("SELECT * FROM country_translations WHERE country_id = ? AND lang_code = 'en'");
        $stmt->execute([$country['id']]);
        $trans = $stmt->fetch();
        
        if ($trans) {
            echo "Current English entry_summary:\n";
            echo substr($trans['entry_summary'], 0, 200) . "...\n\n";
            
            // Check for placeholder terms
            $placeholderTerms = ['lorem ipsum', 'test', 'placeholder', 'coming soon', 'tbd'];
            $textToCheck = strtolower($trans['entry_summary']);
            $hasPlaceholder = false;
            
            foreach ($placeholderTerms as $term) {
                if (stripos($textToCheck, $term) !== false) {
                    echo "⚠️ Found placeholder term: '$term'\n";
                    $hasPlaceholder = true;
                }
            }
            
            if ($hasPlaceholder || strlen($trans['entry_summary']) < 100) {
                // Generate proper summary based on visa type
                $newSummary = generateProperSummary($country, 'en');
                
                echo "\n📝 Updating with proper content...\n";
                $updateStmt = $pdo->prepare("
                    UPDATE country_translations 
                    SET entry_summary = ?, updated_at = NOW()
                    WHERE country_id = ? AND lang_code = 'en'
                ");
                $updateStmt->execute([$newSummary, $country['id']]);
                echo "✅ Fixed English translation (length: " . strlen($newSummary) . " chars)\n";
            } else {
                echo "✅ English translation is OK\n";
            }
        }
    }
    
    // Check Chinese translation
    $stmt = $pdo->prepare("SELECT * FROM country_translations WHERE country_id = ? AND lang_code = 'zh'");
    $stmt->execute([$country['id']]);
    $transCn = $stmt->fetch();
    
    if (!$transCn || empty($transCn['entry_summary']) || strlen($transCn['entry_summary']) < 100) {
        $currentLength = $transCn ? strlen($transCn['entry_summary']) : 0;
        echo "\n🇨🇳 Chinese translation issue - Length: $currentLength chars\n";
        
        // Generate proper Chinese summary
        $newSummary = generateProperSummary($country, 'zh');
        
        if ($transCn) {
            // Update existing
            echo "📝 Updating Chinese translation...\n";
            $updateStmt = $pdo->prepare("
                UPDATE country_translations 
                SET entry_summary = ?, updated_at = NOW()
                WHERE country_id = ? AND lang_code = 'zh'
            ");
            $updateStmt->execute([$newSummary, $country['id']]);
        } else {
            // Insert new
            echo "📝 Creating Chinese translation...\n";
            
            // Get country name in Chinese from another source or use default
            $cnName = getChineseCountryName($country['country_code']);
            
            $insertStmt = $pdo->prepare("
                INSERT INTO country_translations (country_id, lang_code, country_name, entry_summary, updated_at)
                VALUES (?, 'zh', ?, ?, NOW())
            ");
            $insertStmt->execute([$country['id'], $cnName, $newSummary]);
        }
        echo "✅ Fixed Chinese translation (length: " . strlen($newSummary) . " chars)\n";
    } else {
        echo "✅ Chinese translation is OK (length: " . strlen($transCn['entry_summary']) . " chars)\n";
    }
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "✅ All fixes completed!\n";
echo str_repeat("=", 70) . "\n";
echo "</pre>\n";

function generateProperSummary($country, $langCode) {
    $visaType = $country['visa_type'];
    
    $templates = [
        'en' => [
            'visa_free' => 'Most travelers can enter without a visa for short stays of up to 90 days. Your passport must be valid for at least 6 months beyond your planned departure date. Check specific requirements for your nationality as some countries may have different allowances. Ensure you have proof of onward travel and sufficient funds for your stay.',
            'visa_on_arrival' => 'Travelers can obtain a visa upon arrival at the airport or designated border crossing points. The visa fee must be paid in local currency or major international currencies such as USD or EUR. Standard tourist visas typically allow stays of 30 days. Have your passport with at least 6 months validity, passport photos, and proof of accommodation ready.',
            'evisa' => 'An electronic visa (eVisa) must be obtained online before travel through the official government portal. The application process typically takes 1-7 business days for processing. Once approved, print your eVisa confirmation and present it upon arrival along with your passport. Ensure all information matches your passport exactly to avoid entry issues.',
            'visa_required' => 'A visa must be obtained in advance through an embassy or consulate before traveling. The application process requires comprehensive documentation including passport photos, proof of accommodation, detailed travel itinerary, financial statements, and sometimes an invitation letter. Processing times vary from several days to weeks depending on your nationality, so apply well in advance of your planned travel dates.',
            'restricted' => 'Travel to this destination is heavily restricted or prohibited for most tourists. Special government permits or diplomatic clearance may be required. Contact the relevant embassy or consulate for detailed information about entry requirements and any available exemptions. Most tourist activities are not permitted without prior authorization.'
        ],
        'zh' => [
            'visa_free' => '大多数旅客可以免签入境，短期停留最多90天。您的护照必须在计划离境日期后至少有6个月的有效期。请查看您的国籍的具体要求，因为某些国家可能有不同的免签政策。请确保您有续程机票证明和足够的旅行资金。建议出发前仔细核对入境要求。',
            'visa_on_arrival' => '旅客可在机场或指定边境口岸获得落地签证。签证费必须使用当地货币或美元、欧元等主要国际货币支付。标准旅游签证通常允许停留30天。请准备好至少有6个月有效期的护照、护照照片和住宿证明。建议提前准备好所有必需文件以加快入境流程。',
            'evisa' => '必须在旅行前通过官方政府门户网站在线申请电子签证（eVisa）。申请处理通常需要1-7个工作日。获得批准后，请打印电子签证确认函，并在抵达时与护照一起出示给入境官员。确保所有信息与您的护照完全匹配以避免入境问题。建议至少提前两周申请。',
            'visa_required' => '必须在旅行前通过大使馆或领事馆提前获得签证。申请流程需要提供全面的文件材料，包括护照照片、住宿证明、详细旅行行程、财务证明，有时还需要邀请函。处理时间根据您的国籍从几天到几周不等，因此请在计划旅行日期之前尽早申请。建议提前咨询相关使领馆了解具体要求。',
            'restricted' => '前往此目的地受到严格限制，大多数游客禁止入境。可能需要特殊的政府许可证或外交许可。请联系相关大使馆或领事馆了解入境要求和任何可用豁免的详细信息。未经事先授权，大多数旅游活动不被允许。'
        ]
    ];
    
    $template = $templates[$langCode][$visaType] ?? $templates[$langCode]['visa_required'];
    return $template;
}

function getChineseCountryName($countryCode) {
    $chineseNames = [
        'MHL' => '马绍尔群岛',
        'BEN' => '贝宁',
        'BOL' => '玻利维亚',
        'BIH' => '波斯尼亚和黑塞哥维那',
        'BRN' => '文莱',
        'BDI' => '布隆迪',
        'CPV' => '佛得角'
    ];
    
    return $chineseNames[$countryCode] ?? $countryCode;
}
