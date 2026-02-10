<?php
/**
 * Update All Countries with Unique, Accurate Data
 * Populates capital, population, currency, languages, timezone, calling code, plug types, and "known for"
 */

require 'includes/config.php';

echo "=== Updating All Country Data ===\n\n";

$countryData = [
    // Americas
    'USA' => ['capital' => 'Washington, D.C.', 'population' => '331 million', 'currency' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'languages' => 'English', 'timezone' => 'UTC-5 to UTC-10', 'calling' => '+1', 'plug' => 'Type A, B', 'known_for' => 'Statue of Liberty, Hollywood, Grand Canyon, Silicon Valley, diverse landscapes from beaches to mountains, and being a global center of innovation and entertainment.'],
    'CAN' => ['capital' => 'Ottawa', 'population' => '38 million', 'currency' => 'Canadian Dollar', 'code' => 'CAD', 'symbol' => '$', 'languages' => 'English, French', 'timezone' => 'UTC-3.5 to UTC-8', 'calling' => '+1', 'plug' => 'Type A, B', 'known_for' => 'Niagara Falls, Rocky Mountains, maple syrup, hockey, stunning national parks, multicultural cities, and breathtaking natural wilderness.'],
    'MEX' => ['capital' => 'Mexico City', 'population' => '128 million', 'currency' => 'Mexican Peso', 'code' => 'MXN', 'symbol' => '$', 'languages' => 'Spanish', 'timezone' => 'UTC-5 to UTC-8', 'calling' => '+52', 'plug' => 'Type A, B', 'known_for' => 'Ancient Mayan and Aztec ruins, beautiful beaches, tequila, Day of the Dead celebrations, vibrant street food, and rich colonial architecture.'],
    'BRA' => ['capital' => 'Brasília', 'population' => '215 million', 'currency' => 'Brazilian Real', 'code' => 'BRL', 'symbol' => 'R$', 'languages' => 'Portuguese', 'timezone' => 'UTC-2 to UTC-5', 'calling' => '+55', 'plug' => 'Type C, N', 'known_for' => 'Amazon rainforest, Rio Carnival, Christ the Redeemer, football (soccer), samba music, stunning beaches like Copacabana, and diverse ecosystems.'],
    'ARG' => ['capital' => 'Buenos Aires', 'population' => '45 million', 'currency' => 'Argentine Peso', 'code' => 'ARS', 'symbol' => '$', 'languages' => 'Spanish', 'timezone' => 'UTC-3', 'calling' => '+54', 'plug' => 'Type C, I', 'known_for' => 'Tango dancing, Patagonian glaciers, world-class beef and wine, Iguazu Falls, stunning landscapes, and passionate football culture.'],
    'CHL' => ['capital' => 'Santiago', 'population' => '19 million', 'currency' => 'Chilean Peso', 'code' => 'CLP', 'symbol' => '$', 'languages' => 'Spanish', 'timezone' => 'UTC-3 to UTC-4', 'calling' => '+56', 'plug' => 'Type C, L', 'known_for' => 'Atacama Desert, Easter Island moai statues, Torres del Paine, world-renowned wines, stunning Pacific coastline, and dramatic Andes mountains.'],
    'COL' => ['capital' => 'Bogotá', 'population' => '51 million', 'currency' => 'Colombian Peso', 'code' => 'COP', 'symbol' => '$', 'languages' => 'Spanish', 'timezone' => 'UTC-5', 'calling' => '+57', 'plug' => 'Type A, B', 'known_for' => 'Coffee production, Cartagena colonial architecture, emeralds, Caribbean beaches, salsa dancing, and stunning biodiversity from Amazon to Andes.'],
    'PER' => ['capital' => 'Lima', 'population' => '33 million', 'currency' => 'Peruvian Sol', 'code' => 'PEN', 'symbol' => 'S/', 'languages' => 'Spanish, Quechua', 'timezone' => 'UTC-5', 'calling' => '+51', 'plug' => 'Type A, B, C', 'known_for' => 'Machu Picchu, Nazca Lines, Amazon rainforest, world-class gastronomy, Inca heritage, Lake Titicaca, and Andean mountain culture.'],
    'VE' => ['capital' => 'Caracas', 'population' => '28 million', 'currency' => 'Bolívar', 'code' => 'VES', 'symbol' => 'Bs.', 'languages' => 'Spanish', 'timezone' => 'UTC-4', 'calling' => '+58', 'plug' => 'Type A, B', 'known_for' => 'Angel Falls (world\'s highest waterfall), Caribbean beaches, Los Roques archipelago, oil reserves, and diverse ecosystems from Andes to Amazon.'],
    'ECU' => ['capital' => 'Quito', 'population' => '18 million', 'currency' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'languages' => 'Spanish', 'timezone' => 'UTC-5 to UTC-6', 'calling' => '+593', 'plug' => 'Type A, B', 'known_for' => 'Galápagos Islands, straddling the equator, Amazon rainforest, colonial Quito, biodiversity hotspot, and indigenous markets.'],
    'BO' => ['capital' => 'La Paz (administrative), Sucre (constitutional)', 'population' => '12 million', 'currency' => 'Boliviano', 'code' => 'BOB', 'symbol' => 'Bs.', 'languages' => 'Spanish, Quechua, Aymara', 'timezone' => 'UTC-4', 'calling' => '+591', 'plug' => 'Type A, C', 'known_for' => 'Uyuni Salt Flats, Lake Titicaca, world\'s highest capital city, indigenous culture, Andes mountains, and traditional markets.'],
    'BOL' => ['capital' => 'La Paz (administrative), Sucre (constitutional)', 'population' => '12 million', 'currency' => 'Boliviano', 'code' => 'BOB', 'symbol' => 'Bs.', 'languages' => 'Spanish, Quechua, Aymara', 'timezone' => 'UTC-4', 'calling' => '+591', 'plug' => 'Type A, C', 'known_for' => 'Uyuni Salt Flats, Lake Titicaca, world\'s highest capital city, indigenous culture, Andes mountains, and traditional markets.'],
    'PY' => ['capital' => 'Asunción', 'population' => '7 million', 'currency' => 'Guaraní', 'code' => 'PYG', 'symbol' => '₲', 'languages' => 'Spanish, Guaraní', 'timezone' => 'UTC-3 to UTC-4', 'calling' => '+595', 'plug' => 'Type C', 'known_for' => 'Jesuit Missions, Gran Chaco wilderness, Itaipu Dam, traditional harp music, and being one of only two landlocked countries in Americas.'],
    'UY' => ['capital' => 'Montevideo', 'population' => '3.5 million', 'currency' => 'Uruguayan Peso', 'code' => 'UYU', 'symbol' => '$', 'languages' => 'Spanish', 'timezone' => 'UTC-3', 'calling' => '+598', 'plug' => 'Type C, F, L', 'known_for' => 'Punta del Este beaches, mate tea culture, progressive social policies, colonial Colonia, gaucho traditions, and stable democracy.'],
    'GUY' => ['capital' => 'Georgetown', 'population' => '790,000', 'currency' => 'Guyanese Dollar', 'code' => 'GYD', 'symbol' => '$', 'languages' => 'English', 'timezone' => 'UTC-4', 'calling' => '+592', 'plug' => 'Type A, B, D, G', 'known_for' => 'Kaieteur Falls, pristine rainforests, Caribbean English-speaking culture, diverse wildlife, and being the only English-speaking country in South America.'],
];

$stmt = $pdo->prepare("UPDATE countries SET 
    capital = ?, population = ?, currency_name = ?, currency_code = ?, currency_symbol = ?,
    languages = ?, time_zone = ?, calling_code = ?, plug_type = ?
    WHERE country_code = ?");

$detailsStmt = $pdo->prepare("INSERT INTO country_details (country_id, lang_code, known_for) 
    VALUES ((SELECT id FROM countries WHERE country_code = ?), 'en', ?)
    ON DUPLICATE KEY UPDATE known_for = VALUES(known_for)");

$updated = 0;
foreach ($countryData as $code => $data) {
    try {
        $stmt->execute([
            $data['capital'],
            $data['population'],
            $data['currency'],
            $data['code'],
            $data['symbol'],
            $data['languages'],
            $data['timezone'],
            $data['calling'],
            $data['plug'],
            $code
        ]);
        
        $detailsStmt->execute([$code, $data['known_for']]);
        
        $updated++;
        echo "✓ Updated $code\n";
    } catch (PDOException $e) {
        echo "✗ Error updating $code: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Phase 1 Complete ===\n";
echo "Updated: $updated countries\n";
echo "Continuing with remaining countries...\n";
