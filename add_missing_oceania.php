<?php
/**
 * Add Missing Oceania Countries - Nauru and Tuvalu
 */

require_once __DIR__ . '/includes/config.php';

echo "<h1>Adding Missing Oceania Countries</h1>\n";
echo "<pre>\n";

$missingCountries = [
    [
        'country_code' => 'NRU',
        'region' => 'Oceania',
        'capital' => 'Yaren',
        'visa_type' => 'visa_free',
        'flag_emoji' => '🇳🇷',
        'latitude' => -0.5228,
        'longitude' => 166.9315,
        'translations' => [
            'en' => [
                'country_name' => 'Nauru',
                'entry_summary' => 'Visit the world\'s smallest island nation. Nauru allows visa-free entry for most nationalities for up to 30 days. Visitors must have a valid passport with at least 6 months validity, proof of onward travel, and sufficient funds for their stay. The island offers unique phosphate mining history and beautiful coastal areas.',
                'visa_requirements' => 'Most travelers can enter Nauru without a visa for stays up to 30 days. Your passport must be valid for at least 6 months beyond your intended stay. Proof of return or onward ticket and evidence of sufficient funds are required. Extensions can be arranged through local authorities.'
            ],
            'es' => [
                'country_name' => 'Nauru',
                'entry_summary' => 'Visite la nación insular más pequeña del mundo. Nauru permite la entrada sin visa para la mayoría de las nacionalidades hasta 30 días. Los visitantes deben tener un pasaporte válido con al menos 6 meses de validez, prueba de viaje de ida, y fondos suficientes para su estadía.',
                'visa_requirements' => 'La mayoría de los viajeros pueden ingresar a Nauru sin visa para estadías de hasta 30 días. Su pasaporte debe ser válido por al menos 6 meses más allá de su estadía prevista. Se requiere prueba de boleto de regreso y evidencia de fondos suficientes.'
            ],
            'zh' => [
                'country_name' => '瑙鲁',
                'entry_summary' => '参观世界上最小的岛国。瑙鲁允许大多数国籍的游客免签入境最多30天。访客必须持有至少6个月有效期的护照、续程机票证明和足够的旅行资金。该岛提供独特的磷酸盐开采历史和美丽的海岸地区。',
                'visa_requirements' => '大多数旅客可以免签证进入瑙鲁，最多停留30天。您的护照必须在预定停留期之后至少有6个月的有效期。需要提供回程或续程机票证明以及足够资金证明。可以通过当地机构安排延期。'
            ],
            'fr' => [
                'country_name' => 'Nauru',
                'entry_summary' => 'Visitez la plus petite nation insulaire du monde. Nauru permet l\'entrée sans visa pour la plupart des nationalités jusqu\'à 30 jours. Les visiteurs doivent avoir un passeport valide avec au moins 6 mois de validité, une preuve de voyage aller, et des fonds suffisants.',
                'visa_requirements' => 'La plupart des voyageurs peuvent entrer à Nauru sans visa pour des séjours jusqu\'à 30 jours. Votre passeport doit être valide pendant au moins 6 mois au-delà de votre séjour prévu. Une preuve de billet de retour et une preuve de fonds suffisants sont requises.'
            ],
            'de' => [
                'country_name' => 'Nauru',
                'entry_summary' => 'Besuchen Sie die kleinste Inselnation der Welt. Nauru erlaubt visumfreie Einreise für die meisten Nationalitäten bis zu 30 Tage. Besucher müssen einen gültigen Reisepass mit mindestens 6 Monaten Gültigkeit, einen Nachweis über die Weiterreise und ausreichende Mittel haben.',
                'visa_requirements' => 'Die meisten Reisenden können ohne Visum nach Nauru einreisen für Aufenthalte bis zu 30 Tagen. Ihr Reisepass muss mindestens 6 Monate über Ihren geplanten Aufenthalt hinaus gültig sein. Ein Nachweis über ein Rück- oder Weiterreiseticket und ausreichende Mittel sind erforderlich.'
            ],
            'it' => [
                'country_name' => 'Nauru',
                'entry_summary' => 'Visita la più piccola nazione insulare del mondo. Nauru consente l\'ingresso senza visto per la maggior parte delle nazionalità fino a 30 giorni. I visitatori devono avere un passaporto valido con almeno 6 mesi di validità, prova di viaggio di proseguimento e fondi sufficienti.',
                'visa_requirements' => 'La maggior parte dei viaggiatori può entrare a Nauru senza visto per soggiorni fino a 30 giorni. Il passaporto deve essere valido per almeno 6 mesi oltre il soggiorno previsto. È richiesta la prova del biglietto di ritorno e dimostrazione di fondi sufficienti.'
            ],
            'ar' => [
                'country_name' => 'ناورو',
                'entry_summary' => 'قم بزيارة أصغر دولة جزرية في العالم. تسمح ناورو بالدخول بدون تأشيرة لمعظم الجنسيات لمدة تصل إلى 30 يومًا. يجب أن يكون لدى الزوار جواز سفر ساري المفعول لمدة 6 أشهر على الأقل، وإثبات السفر المستمر، وأموال كافية.',
                'visa_requirements' => 'يمكن لمعظم المسافرين الدخول إلى ناورو بدون تأشيرة للإقامة حتى 30 يومًا. يجب أن يكون جواز سفرك صالحًا لمدة 6 أشهر على الأقل بعد إقامتك المقصودة. مطلوب إثبات تذكرة العودة أو المغادرة وإثبات الأموال الكافية.'
            ]
        ]
    ],
    [
        'country_code' => 'TUV',
        'region' => 'Oceania',
        'capital' => 'Funafuti',
        'visa_type' => 'visa_free',
        'flag_emoji' => '🇹🇻',
        'latitude' => -8.5211,
        'longitude' => 179.1962,
        'translations' => [
            'en' => [
                'country_name' => 'Tuvalu',
                'entry_summary' => 'Discover one of the world\'s smallest and most remote nations. Tuvalu allows visa-free entry for most nationalities for up to 30 days. Visitors must have a valid passport with at least 6 months validity, proof of onward travel, and sufficient funds. Experience pristine atolls, friendly local culture, and unique island life.',
                'visa_requirements' => 'Most travelers can enter Tuvalu without a visa for stays up to 30 days. Your passport must be valid for at least 6 months beyond your intended stay. Proof of return or onward ticket, accommodation arrangements, and evidence of sufficient funds are required. Extensions may be possible through immigration authorities.'
            ],
            'es' => [
                'country_name' => 'Tuvalu',
                'entry_summary' => 'Descubra una de las naciones más pequeñas y remotas del mundo. Tuvalu permite la entrada sin visa para la mayoría de las nacionalidades hasta 30 días. Los visitantes deben tener un pasaporte válido con al menos 6 meses de validez, prueba de viaje de continuación y fondos suficientes.',
                'visa_requirements' => 'La mayoría de los viajeros pueden ingresar a Tuvalu sin visa para estadías de hasta 30 días. Su pasaporte debe ser válido por al menos 6 meses más allá de su estadía prevista. Se requiere prueba de boleto de regreso, arreglos de alojamiento y evidencia de fondos suficientes.'
            ],
            'zh' => [
                'country_name' => '图瓦卢',
                'entry_summary' => '探索世界上最小和最偏远的国家之一。图瓦卢允许大多数国籍的游客免签入境最多30天。访客必须持有至少6个月有效期的护照、续程机票证明和足够的资金。体验原始环礁、友好的当地文化和独特的岛屿生活。',
                'visa_requirements' => '大多数旅客可以免签证进入图瓦卢，最多停留30天。您的护照必须在预定停留期之后至少有6个月的有效期。需要提供回程或续程机票证明、住宿安排证明以及足够资金证明。可以通过移民当局安排延期。'
            ],
            'fr' => [
                'country_name' => 'Tuvalu',
                'entry_summary' => 'Découvrez l\'une des nations les plus petites et les plus isolées du monde. Tuvalu permet l\'entrée sans visa pour la plupart des nationalités jusqu\'à 30 jours. Les visiteurs doivent avoir un passeport valide avec au moins 6 mois de validité et des fonds suffisants.',
                'visa_requirements' => 'La plupart des voyageurs peuvent entrer à Tuvalu sans visa pour des séjours jusqu\'à 30 jours. Votre passeport doit être valide pendant au moins 6 mois au-delà de votre séjour prévu. Une preuve de billet de retour, d\'arrangements d\'hébergement et de fonds suffisants est requise.'
            ],
            'de' => [
                'country_name' => 'Tuvalu',
                'entry_summary' => 'Entdecken Sie eine der kleinsten und abgelegensten Nationen der Welt. Tuvalu erlaubt visumfreie Einreise für die meisten Nationalitäten bis zu 30 Tage. Besucher müssen einen gültigen Reisepass mit mindestens 6 Monaten Gültigkeit und ausreichende Mittel haben.',
                'visa_requirements' => 'Die meisten Reisenden können ohne Visum nach Tuvalu einreisen für Aufenthalte bis zu 30 Tagen. Ihr Reisepass muss mindestens 6 Monate über Ihren geplanten Aufenthalt hinaus gültig sein. Nachweis über Rück- oder Weiterreiseticket, Unterkunftsvereinbarungen und ausreichende Mittel sind erforderlich.'
            ],
            'it' => [
                'country_name' => 'Tuvalu',
                'entry_summary' => 'Scopri una delle nazioni più piccole e remote del mondo. Tuvalu consente l\'ingresso senza visto per la maggior parte delle nazionalità fino a 30 giorni. I visitatori devono avere un passaporto valido con almeno 6 mesi di validità e fondi sufficienti.',
                'visa_requirements' => 'La maggior parte dei viaggiatori può entrare a Tuvalu senza visto per soggiorni fino a 30 giorni. Il passaporto deve essere valido per almeno 6 mesi oltre il soggiorno previsto. È richiesta la prova del biglietto di ritorno, sistemazioni di alloggio e dimostrazione di fondi sufficienti.'
            ],
            'ar' => [
                'country_name' => 'توفالو',
                'entry_summary' => 'اكتشف واحدة من أصغر وأبعد الدول في العالم. تسمح توفالو بالدخول بدون تأشيرة لمعظم الجنسيات لمدة تصل إلى 30 يومًا. يجب أن يكون لدى الزوار جواز سفر ساري المفعول لمدة 6 أشهر على الأقل وأموال كافية.',
                'visa_requirements' => 'يمكن لمعظم المسافرين الدخول إلى توفالو بدون تأشيرة للإقامة حتى 30 يومًا. يجب أن يكون جواز سفرك صالحًا لمدة 6 أشهر على الأقل بعد إقامتك المقصودة. مطلوب إثبات تذكرة العودة أو المغادرة وترتيبات الإقامة وإثبات الأموال الكافية.'
            ]
        ]
    ]
];

$pdo->beginTransaction();

try {
    foreach ($missingCountries as $countryData) {
        echo "Adding: {$countryData['country_code']} - {$countryData['translations']['en']['country_name']}\n";
        echo str_repeat("-", 70) . "\n";
        
        // Insert country
        $stmt = $pdo->prepare("
            INSERT INTO countries (country_code, region, capital, visa_type, flag_emoji, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $countryData['country_code'],
            $countryData['region'],
            $countryData['capital'],
            $countryData['visa_type'],
            $countryData['flag_emoji']
        ]);
        
        $countryId = $pdo->lastInsertId();
        echo "✅ Country record created (ID: $countryId)\n";
        
        // Insert translations
        $stmt = $pdo->prepare("
            INSERT INTO country_translations (country_id, lang_code, country_name, entry_summary, visa_requirements, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        
        foreach ($countryData['translations'] as $langCode => $translation) {
            $stmt->execute([
                $countryId,
                $langCode,
                $translation['country_name'],
                $translation['entry_summary'],
                $translation['visa_requirements']
            ]);
            echo "  ✅ Added $langCode translation\n";
        }
        
        echo "\n";
    }
    
    $pdo->commit();
    
    echo str_repeat("=", 70) . "\n";
    echo "✅ SUCCESS! Added 2 countries to Oceania\n";
    echo str_repeat("=", 70) . "\n\n";
    
    // Verify
    $stmt = $pdo->query("SELECT COUNT(*) FROM countries WHERE region = 'Oceania'");
    $count = $stmt->fetchColumn();
    echo "Oceania now has: $count countries\n";
    
    echo "\nNew countries added:\n";
    $stmt = $pdo->query("
        SELECT c.country_code, ct.country_name, c.visa_type
        FROM countries c
        JOIN country_translations ct ON c.id = ct.country_id AND ct.lang_code = 'en'
        WHERE c.country_code IN ('NRU', 'TUV')
    ");
    $newCountries = $stmt->fetchAll();
    foreach ($newCountries as $country) {
        echo "  {$country['flag_emoji']} {$country['country_code']} - {$country['country_name']} ({$country['visa_type']})\n";
    }
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "</pre>\n";
