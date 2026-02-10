<?php
/**
 * Add Africa and Oceania Countries
 */

require 'includes/config.php';

// [capital, population, currency, code, symbol, languages, timezone, calling, plug, known_for]
$countries = [
    // AFRICA
    'EGY' => ['Cairo', '104 million', 'Egyptian Pound', 'EGP', '£', 'Arabic', 'UTC+2', '+20', 'Type C, F', 'Pyramids, Sphinx, Nile River, ancient pharaohs, and archaeological wonders.'],
    'ZAF' => ['Pretoria', '60 million', 'South African Rand', 'ZAR', 'R', 'Afrikaans, English, Zulu, Xhosa', 'UTC+2', '+27', 'Type C, D, M, N', 'Table Mountain, safaris, Kruger Park, diverse wildlife, and rainbow nation.'],
    'KEN' => ['Nairobi', '54 million', 'Kenyan Shilling', 'KES', 'Sh', 'Swahili, English', 'UTC+3', '+254', 'Type G', 'Safari adventures, Maasai culture, Mount Kenya, wildebeest migration, and tech hub.'],
    'NGA' => ['Abuja', '211 million', 'Nigerian Naira', 'NGN', '₦', 'English', 'UTC+1', '+234', 'Type D, G', 'Nollywood, vibrant culture, oil wealth, diverse ethnic groups, and Afrobeat music.'],
    'ETH' => ['Addis Ababa', '117 million', 'Ethiopian Birr', 'ETB', 'Br', 'Amharic', 'UTC+3', '+251', 'Type C, E, F, L', 'Coffee birthplace, ancient churches, unique cuisine, and never colonized.'],
    'GHA' => ['Accra', '31 million', 'Ghanaian Cedi', 'GHS', '₵', 'English', 'UTC0', '+233', 'Type D, G', 'Gold Coast, slave castles, cocoa, friendly people, and democratic stability.'],
    'TZA' => ['Dodoma', '60 million', 'Tanzanian Shilling', 'TZS', 'Sh', 'Swahili, English', 'UTC+3', '+255', 'Type D, G', 'Serengeti, Kilimanjaro, Zanzibar spices, safaris, and pristine beaches.'],
    'UGA' => ['Kampala', '47 million', 'Ugandan Shilling', 'UGX', 'Sh', 'English, Swahili', 'UTC+3', '+256', 'Type G', 'Mountain gorillas, Nile source, Pearl of Africa, and diverse wildlife.'],
    'DZA' => ['Algiers', '44 million', 'Algerian Dinar', 'DZD', 'د.ج', 'Arabic, Berber', 'UTC+1', '+213', 'Type C, F', 'Sahara Desert, Roman ruins, oil and gas, couscous, and Mediterranean coast.'],
    'MAR' => ['Rabat', '37 million', 'Moroccan Dirham', 'MAD', 'د.م.', 'Arabic, Berber', 'UTC+1', '+212', 'Type C, E', 'Marrakech souks, Sahara, mint tea, tagine, colorful riads, and hospitality.'],
    'TUN' => ['Tunis', '12 million', 'Tunisian Dinar', 'TND', 'د.ت', 'Arabic', 'UTC+1', '+216', 'Type C, E', 'Carthage ruins, Sidi Bou Said, beaches, moderate culture, and Star Wars filming.'],
    'LBY' => ['Tripoli', '7 million', 'Libyan Dinar', 'LYD', 'ل.د', 'Arabic', 'UTC+2', '+218', 'Type C, D, F, L', 'Ancient Leptis Magna, desert, oil wealth, and complex political situation.'],
    'CMR' => ['Yaoundé', '27 million', 'Central African CFA Franc', 'XAF', 'Fr', 'French, English', 'UTC+1', '+237', 'Type C, E', 'Diverse geography, wildlife, Mount Cameroon, bilingual culture, and football.'],
    'CIV' => ['Yamoussoukro', '27 million', 'West African CFA Franc', 'XOF', 'Fr', 'French', 'UTC0', '+225', 'Type C, E', 'Cocoa leader, diverse culture, Basilica, Abidjan, and economic hub.'],
    'SEN' => ['Dakar', '17 million', 'West African CFA Franc', 'XOF', 'Fr', 'French', 'UTC0', '+221', 'Type C, D, E, K', 'Dakar Rally, Gorée Island, vibrant culture, seafood, and stable democracy.'],
    'ZWE' => ['Harare', '15 million', 'Zimbabwean Dollar', 'ZWL', 'Z$', 'English, Shona, Ndebele', 'UTC+2', '+263', 'Type D, G', 'Victoria Falls, Great Zimbabwe ruins, wildlife, and resilient people.'],
    'ZMB' => ['Lusaka', '19 million', 'Zambian Kwacha', 'ZMK', 'ZK', 'English', 'UTC+2', '+260', 'Type C, D, G', 'Victoria Falls (Zambia side), safaris, copper mining, and national parks.'],
    'MOZ' => ['Maputo', '32 million', 'Mozambican Metical', 'MZN', 'MT', 'Portuguese', 'UTC+2', '+258', 'Type C, F, M', 'Beach paradise, Portuguese heritage, seafood, archipelagos, and emerging tourism.'],
    'BWA' => ['Gaborone', '2.4 million', 'Botswana Pula', 'BWP', 'P', 'English, Setswana', 'UTC+2', '+267', 'Type D, G, M', 'Okavango Delta, diamond success, wildlife, stable democracy, and sustainable tourism.'],
    'NAM' => ['Windhoek', '2.5 million', 'Namibian Dollar', 'NAD', 'N$', 'English', 'UTC+2', '+264', 'Type D, M', 'Namib Desert, Sossusvlei dunes, Etosha Park, German heritage, and stark beauty.'],
    'AGO' => ['Luanda', '33 million', 'Angolan Kwanza', 'AOA', 'Kz', 'Portuguese', 'UTC+1', '+244', 'Type C', 'Oil wealth, Portuguese influence, diverse landscapes, and rebuilding nation.'],
    'RWA' => ['Kigali', '13 million', 'Rwandan Franc', 'RWF', 'Fr', 'Kinyarwanda, French, English', 'UTC+2', '+250', 'Type C, J', 'Mountain gorillas, clean cities, recovery story, innovation, and progress.'],
    'BDI' => ['Gitega', '12 million', 'Burundian Franc', 'BIF', 'Fr', 'Kirundi, French', 'UTC+2', '+257', 'Type C, E', 'Lake Tanganyika, drummers, coffee, and landlocked beauty.'],
    'MLI' => ['Bamako', '21 million', 'West African CFA Franc', 'XOF', 'Fr', 'French', 'UTC0', '+223', 'Type C, E', 'Timbuktu, Niger River, ancient manuscripts, gold, and Sahel.'],
    'NER' => ['Niamey', '25 million', 'West African CFA Franc', 'XOF', 'Fr', 'French', 'UTC+1', '+227', 'Type A, B, C, D, E, F', 'Sahara gateway, uranium, nomadic Tuareg, and challenging environment.'],
    'TCD' => ['N\'Djamena', '17 million', 'Central African CFA Franc', 'XAF', 'Fr', 'Arabic, French', 'UTC+1', '+235', 'Type C, D, E, F', 'Lake Chad, Sahara, diverse wildlife, and landlocked challenges.'],
    'SOM' => ['Mogadishu', '16 million', 'Somali Shilling', 'SOS', 'Sh', 'Somali, Arabic', 'UTC+3', '+252', 'Type C', 'Long coastline, ancient ports, nomadic heritage, and rebuilding efforts.'],
    'SDN' => ['Khartoum', '44 million', 'Sudanese Pound', 'SDG', '£', 'Arabic, English', 'UTC+2', '+249', 'Type C, D', 'Nubian pyramids, Nile confluence, ancient civilizations, and transition period.'],
'MWI' => ['Lilongwe', '19 million', 'Malawian Kwacha', 'MWK', 'MK', 'English, Chichewa', 'UTC+2', '+265', 'Type G', 'Lake Malawi, warm heart of Africa, freshwater diving, and friendly people.'],
    'BEN' => ['Porto-Novo', '12 million', 'West African CFA Franc', 'XOF', 'Fr', 'French', 'UTC+1', '+229', 'Type C, E', 'Birthplace of Vodou, stilt villages, wildlife, and historic kingdom.'],
    'TGO' => ['Lomé', '8.4 million', 'West African CFA Franc', 'XOF', 'Fr', 'French', 'UTC0', '+228', 'Type C', 'Vibrant markets, beaches, fetish market, and West African culture.'],
    'BFA' => ['Ouagadougou', '21 million', 'West African CFA Franc', 'XOF', 'Fr', 'French', 'UTC0', '+226', 'Type C, E', 'FESPACO film festival, crafts, Sahel culture, and landlocked.'],
    'GAB' => ['Libreville', '2.2 million', 'Central African CFA Franc', 'XAF', 'Fr', 'French', 'UTC+1', '+241', 'Type C', 'Rainforest, oil wealth, wildlife, gorillas, and biodiversity.'],
    'GNQ' => ['Malabo', '1.4 million', 'Central African CFA Franc', 'XAF', 'Fr', 'Spanish, French, Portuguese', 'UTC+1', '+240', 'Type C, E', 'Oil-rich, unique Spanish-speaking African nation, and Bioko Island.'],
    'COD' => ['Kinshasa', '92 million', 'Congolese Franc', 'CDF', 'Fr', 'French', 'UTC+1 to UTC+2', '+243', 'Type C, D, E', 'Congo River, rainforest, minerals, Virunga gorillas, and huge potential.'],
    'COG' => ['Brazzaville', '5.5 million', 'Central African CFA Franc', 'XAF', 'Fr', 'French', 'UTC+1', '+242', 'Type C, E', 'Congo River opposite Kinshasa, oil, forests, and Odzala Park.'],
    'CAF' => ['Bangui', '5 million', 'Central African CFA Franc', 'XAF', 'Fr', 'French, Sango', 'UTC+1', '+236', 'Type C, E', 'Rainforest, wildlife, diamonds, and complex challenges.'],
    'MRT' => ['Nouakchott', '4.7 million', 'Mauritanian Ouguiya', 'MRU', 'UM', 'Arabic', 'UTC0', '+222', 'Type C', 'Sahara Desert, ancient trading routes, iron ore, and nomadic traditions.'],
    'LBR' => ['Monrovia', '5.2 million', 'Liberian Dollar', 'LRD', 'L$', 'English', 'UTC0', '+231', 'Type A, B, C, D, E, F', 'Founded by freed American slaves, rubber, and recovery story.'],
    'SLE' => ['Freetown', '8.1 million', 'Sierra Leonean Leone', 'SLL', 'Le', 'English', 'UTC0', '+232', 'Type D, G', 'Diamonds, beautiful beaches, Freetown Peninsula, and post-war rebuilding.'],
    'GIN' => ['Conakry', '13 million', 'Guinean Franc', 'GNF', 'Fr', 'French', 'UTC0', '+224', 'Type C, F, K', 'Bauxite wealth, Mount Nimba, source of Niger River, and diverse terrain.'],
    'GNB' => ['Bissau', '2 million', 'West African CFA Franc', 'XOF', 'Fr', 'Portuguese', 'UTC0', '+245', 'Type C', 'Bijagós archipelago, cashews, Portuguese creole, and tropical islands.'],
    'GMB' => ['Banjul', '2.5 million', 'Gambian Dalasi', 'GMD', 'D', 'English', 'UTC0', '+220', 'Type G', 'Smallest African mainland nation, Gambia River, bird watching, and beaches.'],
    'MUS' => ['Port Louis', '1.3 million', 'Mauritian Rupee', 'MUR', '₨', 'English, French, Creole', 'UTC+4', '+230', 'Type C, G', 'Paradise island, multicultural, beaches, extinct dodo, and economic success.'],
    'SYC' => ['Victoria', '98,000', 'Seychellois Rupee', 'SCR', '₨', 'Seychellois Creole, English, French', 'UTC+4', '+248', 'Type G', 'Luxury island paradise, granite boulders, pristine beaches, and exclusive resorts.'],
    'COM' => ['Moroni', '870,000', 'Comorian Franc', 'KMF', 'Fr', 'Comorian, Arabic, French', 'UTC+3', '+269', 'Type C, E', 'Volcanic islands, ylang-ylang perfume, vanilla, and Indian Ocean gem.'],
    'CPV' => ['Praia', '560,000', 'Cape Verdean Escudo', 'CVE', '$', 'Portuguese', 'UTC-1', '+238', 'Type C, F', 'Mid-Atlantic islands, music, Cesária Évora, morna, and creole culture.'],
    'STP' => ['São Tomé', '220,000', 'São Tomé and Príncipe Dobra', 'STN', 'Db', 'Portuguese', 'UTC0', '+239', 'Type C, F', 'Chocolate islands, cocoa, pristine nature, and equatorial paradise.'],
    'SSD' => ['Juba', '11 million', 'South Sudanese Pound', 'SSP', '£', 'English', 'UTC+3', '+211', 'Type C, D', 'Worlds newest nation, oil, Nile tributaries, and ongoing challenges.'],
    'ERI' => ['Asmara', '3.6 million', 'Eritrean Nakfa', 'ERN', 'Nfk', 'Tigrinya, Arabic, English', 'UTC+3', '+291', 'Type C, L', 'Art Deco capital, Red Sea coast, diverse culture, and Italian influence.'],
    'DJI' => ['Djibouti City', '1 million', 'Djiboutian Franc', 'DJF', 'Fr', 'French, Arabic', 'UTC+3', '+253', 'Type C, E', 'Strategic port, Lake Assal, military bases, and hot climate.'],
    'SWZ' => ['Mbabane', '1.2 million', 'Swazi Lilangeni', 'SZL', 'L', 'Swati, English', 'UTC+2', '+268', 'Type M', 'Last African monarchy, Umhlanga Reed Dance, wildlife reserves, and crafts.'],
    'LSO' => ['Maseru', '2.1 million', 'Lesotho Loti', 'LSL', 'L', 'Sesotho, English', 'UTC+2', '+266', 'Type M', 'Kingdom in the sky, surrounded by South Africa, mountains, and blankets.'],
    
    // OCEANIA
    'AUS' => ['Canberra', '26 million', 'Australian Dollar', 'AUD', '$', 'English', 'UTC+8 to UTC+11', '+61', 'Type I', 'Great Barrier Reef, Sydney Opera House, kangaroos, Outback, beaches, and laid-back culture.'],
    'NZ' => ['Wellington', '5.1 million', 'New Zealand Dollar', 'NZD', '$', 'English, Māori', 'UTC+12', '+64', 'Type I', 'Lord of the Rings landscapes, Māori culture, All Blacks rugby, and stunning nature.'],
    'NZL' => ['Wellington', '5.1 million', 'New Zealand Dollar', 'NZD', '$', 'English, Māori', 'UTC+12', '+64', 'Type I', 'Lord of the Rings landscapes, Māori culture, All Blacks rugby, and stunning nature.'],
    'FJI' => ['Suva', '900,000', 'Fijian Dollar', 'FJD', '$', 'English, Fijian, Hindi', 'UTC+12', '+679', 'Type I', 'Tropical paradise, coral reefs, friendly locals saying "Bula!", and pristine beaches.'],
    'PNG' => ['Port Moresby', '9.1 million', 'Papua New Guinean Kina', 'PGK', 'K', 'English, Tok Pisin, Hiri Motu', 'UTC+10', '+675', 'Type I', 'Indigenous cultures, coral Triangle, tribal diversity, and rugged terrain.'],
    'SLB' => ['Honiara', '700,000', 'Solomon Islands Dollar', 'SBD', '$', 'English', 'UTC+11', '+677', 'Type G, I', 'WWII history, diving, traditional culture, and remote islands.'],
    'VUT' => ['Port Vila', '310,000', 'Vanuatu Vatu', 'VUV', 'Vt', 'Bislama, English, French', 'UTC+11', '+678', 'Type C, G, I', 'Active volcanoes, bungee jumping origins, blue holes, and Melanesian culture.'],
    'SAM' => ['Apia', '200,000', 'Samoan Tālā', 'WST', 'T', 'Samoan, English', 'UTC+13', '+685', 'Type I', 'Fa\'a Samoa culture, traditional tattoos, To Sua Ocean Trench, and welcoming spirit.'],
    'WSM' => ['Apia', '200,000', 'Samoan Tālā', 'WST', 'T', 'Samoan, English', 'UTC+13', '+685', 'Type I', 'Fa\'a Samoa culture, traditional tattoos, To Sua Ocean Trench, and welcoming spirit.'],
    'TON' => ['Nuku\'alofa', '106,000', 'Tongan Pa\'anga', 'TOP', 'T$', 'Tongan, English', 'UTC+13', '+676', 'Type I', 'Polynesian kingdom, whale watching, friendly islands, and royal traditions.'],
    'FSM' => ['Palikir', '115,000', 'US Dollar', 'USD', '$', 'English', 'UTC+10 to UTC+11', '+691', 'Type A, B', 'Ancient Nan Madol ruins, diving, atolls, and traditional navigation.'],
    'KIR' => ['Tarawa', '120,000', 'Australian Dollar', 'AUD', '$', 'English, Gilbertese', 'UTC+12 to UTC+14', '+686', 'Type I', 'First nation to see sunrise, atolls, climate change frontline, and ocean.'],
    'MHL' => ['Majuro', '59,000', 'US Dollar', 'USD', '$', 'Marshallese, English', 'UTC+12', '+692', 'Type A, B', 'Nuclear testing history, coral atolls, navigation traditions, and scattered islands.'],
    'PLW' => ['Ngerulmud', '18,000', 'US Dollar', 'USD', '$', 'Palauan, English', 'UTC+9', '+680', 'Type A, B', 'Rock Islands, Jellyfish Lake, pristine diving, conservation, and unique biodiversity.'],
    'NRU' => ['Yaren', '11,000', 'Australian Dollar', 'AUD', '$', 'Nauruan, English', 'UTC+12', '+674', 'Type I', 'Smallest island nation, phosphate mining, and environmental challenges.'],
    'TUV' => ['Funafuti', '12,000', 'Australian Dollar', 'AUD', '$', 'Tuvaluan, English', 'UTC+12', '+688', 'Type I', 'Low-lying atolls, climate change vulnerability, limited tourism, and Pacific isolation.'],
];

$stmt = $pdo->prepare("UPDATE countries SET capital = ?, population = ?, currency_name = ?, currency_code = ?, currency_symbol = ?, languages = ?, time_zone = ?, calling_code = ?, plug_type = ? WHERE country_code = ?");
$detailsStmt = $pdo->prepare("INSERT INTO country_details (country_id, lang_code, known_for) VALUES ((SELECT id FROM countries WHERE country_code = ?), 'en', ?) ON DUPLICATE KEY UPDATE known_for = VALUES(known_for)");

$updated = 0;
foreach ($countries as $code => $d) {
    try {
        $stmt->execute([$d[0], $d[1], $d[2], $d[3], $d[4], $d[5], $d[6], $d[7], $d[8], $code]);
        $detailsStmt->execute([$code, $d[9]]);
        $updated++;
        echo "✓ $code\n";
    } catch (PDOException $e) {
        echo "✗ $code: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Updated: $updated countries ===\n";
