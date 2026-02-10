<?php
/**
 * Add Remaining Countries - Europe, Asia, Africa, Oceania
 * This adds data for the remaining~158 countries
 */

require 'includes/config.php';

// Compact format: [capital, population, currency, code, symbol, languages, timezone, calling, plug, known_for]
$remaining = [
    // EUROPE
    'FRA' => ['Paris', '67 million', 'Euro', 'EUR', '€', 'French', 'UTC+1', '+33', 'Type C, E', 'Eiffel Tower, Louvre, French cuisine, fashion, wine regions, and romantic ambiance.'],
    'DEU' => ['Berlin', '83 million', 'Euro', 'EUR', '€', 'German', 'UTC+1', '+49', 'Type C, F', 'Berlin Wall history, Oktoberfest, engineering excellence, and fairy-tale castles.'],
    'ESP' => ['Madrid', '47 million', 'Euro', 'EUR', '€', 'Spanish', 'UTC+1', '+34', 'Type C, F', 'Flamenco, paella, Sagrada Família, bullfighting, and Mediterranean lifestyle.'],
    'ITA' => ['Rome', '60 million', 'Euro', 'EUR', '€', 'Italian', 'UTC+1', '+39', 'Type C, F, L', 'Colosseum, Vatican, Renaissance art, pasta, pizza, and fashion capital Milan.'],
    'NLD' => ['Amsterdam', '17.4 million', 'Euro', 'EUR', '€', 'Dutch', 'UTC+1', '+31', 'Type C, F', 'Windmills, tulips, canals, cycling culture, liberal policies, and cheese markets.'],
    'BEL' => ['Brussels', '11.5 million', 'Euro', 'EUR', '€', 'Dutch, French, German', 'UTC+1', '+32', 'Type C, E', 'Chocolate, waffles, medieval Bruges, EU headquarters, and Art Nouveau architecture.'],
    'AUT' => ['Vienna', '9 million', 'Euro', 'EUR', '€', 'German', 'UTC+1', '+43', 'Type C, F', 'Classical music, Mozart, Alps skiing, Sachertorte, and imperial palaces.'],
    'CHE' => ['Bern', '8.6 million', 'Swiss Franc', 'CHF', 'CHF', 'German, French, Italian, Romansh', 'UTC+1', '+41', 'Type C, J', 'Swiss Alps, chocolate, watches, neutrality, banking, and precision engineering.'],
    'GRC' => ['Athens', '10.7 million', 'Euro', 'EUR', '€', 'Greek', 'UTC+2', '+30', 'Type C, F', 'Acropolis, Greek islands, ancient philosophy, Mediterranean cuisine, and mythology.'],
    'PRT' => ['Lisbon', '10.3 million', 'Euro', 'EUR', '€', 'Portuguese', 'UTC0', '+351', 'Type C, F', 'Port wine, Fado music, Age of Discovery, pastel de nata, and beautiful tiles.'],
    'SWE' => ['Stockholm', '10.4 million', 'Swedish Krona', 'SEK', 'kr', 'Swedish', 'UTC+1', '+46', 'Type C, F', 'IKEA, ABBA, Northern Lights, midsummer celebrations, and Nordic design.'],
    'NOR' => ['Oslo', '5.4 million', 'Norwegian Krone', 'NOK', 'kr', 'Norwegian', 'UTC+1', '+47', 'Type C, F', 'Fjords, midnight sun, Vikings, Northern Lights, and oil wealth.'],
    'DNK' => ['Copenhagen', '5.8 million', 'Danish Krone', 'DKK', 'kr', 'Danish', 'UTC+1', '+45', 'Type C, E, F, K', 'Lego, hygge lifestyle, Hans Christian Andersen, cycling culture, and design.'],
    'FIN' => ['Helsinki', '5.5 million', 'Euro', 'EUR', '€', 'Finnish, Swedish', 'UTC+2', '+358', 'Type C, F', 'Saunas, Nokia, Santa Claus, Northern Lights, and thousands of lakes.'],
    'ISL' => ['Reykjavik', '370,000', 'Icelandic Króna', 'ISK', 'kr', 'Icelandic', 'UTC0', '+354', 'Type C, F', 'Geysers, Blue Lagoon, Northern Lights, midnight sun, and volcanic landscapes.'],
    'POL' => ['Warsaw', '38 million', 'Polish Złoty', 'PLN', 'zł', 'Polish', 'UTC+1', '+48', 'Type C, E', 'Medieval Krakow, Auschwitz memorial, pierogies, vodka, and Chopin.'],
    'CZE' => ['Prague', '10.7 million', 'Czech Koruna', 'CZK', 'Kč', 'Czech', 'UTC+1', '+420', 'Type C, E', 'Prague Castle, beer culture, Bohemian crystal, Charles Bridge, and Kafka.'],
    'HUN' => ['Budapest', '9.7 million', 'Hungarian Forint', 'HUF', 'Ft', 'Hungarian', 'UTC+1', '+36', 'Type C, F', 'Thermal baths, Danube River, goulash, paprika, and ruin bars.'],
    'SK' => ['Bratislava', '5.5 million', 'Euro', 'EUR', '€', 'Slovak', 'UTC+1', '+421', 'Type C, E', 'Tatra Mountains, castles, folk traditions, and affordable skiing.'],
    'SI' => ['Ljubljana', '2.1 million', 'Euro', 'EUR', '€', 'Slovenian', 'UTC+1', '+386', 'Type C, F', 'Lake Bled, Julian Alps, caves, green tourism, and compact beauty.'],
    'EE' => ['Tallinn', '1.3 million', 'Euro', 'EUR', '€', 'Estonian', 'UTC+2', '+372', 'Type C, F', 'Digital society, medieval old town, e-residency, and tech startups.'],
    'EST' => ['Tallinn', '1.3 million', 'Euro', 'EUR', '€', 'Estonian', 'UTC+2', '+372', 'Type C, F', 'Digital society, medieval old town, e-residency, and tech startups.'],
    'LV' => ['Riga', '1.9 million', 'Euro', 'EUR', '€', 'Latvian', 'UTC+2', '+371', 'Type C, F', 'Art Nouveau architecture, Baltic beaches, Song Festival, and forests.'],
    'LVA' => ['Riga', '1.9 million', 'Euro', 'EUR', '€', 'Latvian', 'UTC+2', '+371', 'Type C, F', 'Art Nouveau architecture, Baltic beaches, Song Festival, and forests.'],
    'LT' => ['Vilnius', '2.8 million', 'Euro', 'EUR', '€', 'Lithuanian', 'UTC+2', '+370', 'Type C, F', 'Baroque old town, Hill of Crosses, basketball passion, and amber.'],
    'MT' => ['Valletta', '516,000', 'Euro', 'EUR', '€', 'Maltese, English', 'UTC+1', '+356', 'Type G', 'Ancient temples, Knights of Malta, azure waters, and fortifications.'],
    'CY' => ['Nicosia', '1.2 million', 'Euro', 'EUR', '€', 'Greek, Turkish', 'UTC+2', '+357', 'Type G', 'Divided capital, ancient ruins, Mediterranean beaches, and Aphrodite\'s birthplace.'],
    'CYP' => ['Nicosia', '1.2 million', 'Euro', 'EUR', '€', 'Greek, Turkish', 'UTC+2', '+357', 'Type G', 'Divided capital, ancient ruins, Mediterranean beaches, and Aphrodite\'s birthplace.'],
    'LU' => ['Luxembourg City', '630,000', 'Euro', 'EUR', '€', 'Luxembourgish, French, German', 'UTC+1', '+352', 'Type C, F', 'Banking center, EU institutions, castles, and multicultural society.'],
    'GBR' => ['London', '67 million', 'Pound Sterling', 'GBP', '£', 'English', 'UTC0', '+44', 'Type G', 'Big Ben, Royal Family, tea culture, Premier League, and global financial hub.'],
   'IRL' => ['Dublin', '5 million', 'Euro', 'EUR', '€', 'English, Irish', 'UTC0', '+353', 'Type G', 'Guinness, St. Patrick\'s Day, Celtic heritage, literary greats, and  green countryside.'],
    'RO' => ['Bucharest', '19 million', 'Romanian Leu', 'RON', 'lei', 'Romanian', 'UTC+2', '+40', 'Type C, F', 'Dracula\'s castle, Carpathian Mountains, painted monasteries, and folk traditions.'],
    'BG' => ['Sofia', '6.9 million', 'Bulgarian Lev', 'BGN', 'лв', 'Bulgarian', 'UTC+2', '+359', 'Type C, F', 'Rose Valley, Black Sea coast, Cyrillic alphabet, yogurt, and ski resorts.'],
    'HR' => ['Zagreb', '4 million', 'Euro', 'EUR', '€', 'Croatian', 'UTC+1', '+385', 'Type C, F', 'Dalmatian coast, Dubrovnik walls, Game of Thrones filming, and islands.'],
    'HRV' => ['Zagreb', '4 million', 'Euro', 'EUR', '€', 'Croatian', 'UTC+1', '+385', 'Type C, F', 'Dalmatian coast, Dubrovnik walls, Game of Thrones filming, and islands.'],
    'RS' => ['Belgrade', '6.9 million', 'Serbian Dinar', 'RSD', 'дин', 'Serbian', 'UTC+1', '+381', 'Type C, F', 'Vibrant nightlife, Danube River confluence, rakija, and historical resilience.'],
    'BA' => ['Sarajevo', '3.3 million', 'Convertible Mark', 'BAM', 'KM', 'Bosnian, Serbian, Croatian', 'UTC+1', '+387', 'Type C, F', 'Ottoman heritage, siege history, Mostar bridge, and diverse culture.'],
    'BIH' => ['Sarajevo', '3.3 million', 'Convertible Mark', 'BAM', 'KM', 'Bosnian, Serbian, Croatian', 'UTC+1', '+387', 'Type C, F', 'Ottoman heritage, siege history, Mostar bridge, and diverse culture.'],
    'MK' => ['Skopje', '2.1 million', 'Macedonian Denar', 'MKD', 'ден', 'Macedonian', 'UTC+1', '+389', 'Type C, F', 'Lake Ohrid, Alexander the Great heritage, and mountain monasteries.'],
    'AL' => ['Tirana', '2.9 million', 'Albanian Lek', 'ALL', 'L', 'Albanian', 'UTC+1', '+355', 'Type C, F', 'Albanian Riviera, bunkers, Ottoman architecture, and improving tourism.'],
    'ME' => ['Podgorica', '620,000', 'Euro', 'EUR', '€', 'Montenegrin', 'UTC+1', '+382', 'Type C, F', 'Bay of Kotor, medieval towns, mountains, and Adriatic beauty.'],
    'UA' => ['Kyiv', '41 million', 'Ukrainian Hryvnia', 'UAH', '₴', 'Ukrainian', 'UTC+2', '+380', 'Type C, F', 'Golden-domed churches, borscht, Lviv coffee culture, and resilient spirit.'],
    'BY' => ['Minsk', '9.4 million', 'Belarusian Ruble', 'BYN', 'Br', 'Belarusian, Russian', 'UTC+3', '+375', 'Type C, F', 'Soviet architecture, Mir Castle, Białowieża Forest, and clean cities.'],
    'MD' => ['Chișinău', '2.6 million', 'Moldovan Leu', 'MDL', 'L', 'Romanian', 'UTC+2', '+373', 'Type C, F', 'Wine cellars, monasteries, sunflower fields, and affordable travel.'],
    'RUS' => ['Moscow', '144 million', 'Russian Ruble', 'RUB', '₽', 'Russian', 'UTC+2 to UTC+12', '+7', 'Type C, F', 'Red Square, Trans-Siberian Railway, Hermitage, ballet, and vast territory.'],
    'LIE' => ['Vaduz', '39,000', 'Swiss Franc', 'CHF', 'CHF', 'German', 'UTC+1', '+423', 'Type C, J', 'Tiny principality, Alpine skiing, strong economy, and mountain hiking.'],
    'XKX' => ['Pristina', '1.8 million', 'Euro', 'EUR', '€', 'Albanian, Serbian', 'UTC+1', '+383', 'Type C, F', 'Young nation, Newborn monument, Rugova Gorge, and emerging identity.'],
    
    // ASIA
    'CHN' => ['Beijing', '1.4 billion', 'Chinese Yuan', 'CNY', '¥', 'Mandarin', 'UTC+8', '+86', 'Type A, C, I', 'Great Wall, Terracotta Army, pandas, ancient culture, and economic powerhouse.'],
    'JPN' => ['Tokyo', '125 million', 'Japanese Yen', 'JPY', '¥', 'Japanese', 'UTC+9', '+81', 'Type A, B', 'Mt. Fuji, cherry blossoms, sushi, anime, technology, and respectful culture.'],
    'KOR' => ['Seoul', '52 million', 'South Korean Won', 'KRW', '₩', 'Korean', 'UTC+9', '+82', 'Type C, F', 'K-pop, K-drama, Samsung, kimchi, DMZ, and technological innovation.'],
    'HKG' => ['Hong Kong', '7.5 million', 'Hong Kong Dollar', 'HKD', 'HK$', 'Cantonese, English', 'UTC+8', '+852', 'Type G', 'Skyline, dim sum, Victoria Peak, shopping paradise, and fusion culture.'],
    'MN' => ['Ulaanbaatar', '3.3 million', 'Mongolian Tögrög', 'MNT', '₮', 'Mongolian', 'UTC+8', '+976', 'Type C, E', 'Genghis Khan, nomadic lifestyle, Gobi Desert, horses, and vast steppes.'],
    'THA' => ['Bangkok', '70 million', 'Thai Baht', 'THB', '฿', 'Thai', 'UTC+7', '+66', 'Type A, B, C', 'Temples, street food, islands, Thai massage, elephants, and hospitality.'],
    'VNM' => ['Hanoi', '98 million', 'Vietnamese Đồng', 'VND', '₫', 'Vietnamese', 'UTC+7', '+84', 'Type A, C', 'Halong Bay, pho, rice paddies, war history, and motorbike culture.'],
    'SGP' => ['Singapore', '5.7 million', 'Singapore Dollar', 'SGD', 'S$', 'English, Malay, Mandarin, Tamil', 'UTC+8', '+65', 'Type G', 'Marina Bay Sands, cleanliness, hawker centers, efficiency, and multiculturalism.'],
    'MYS' => ['Kuala Lumpur', '33 million', 'Malaysian Ringgit', 'MYR', 'RM', 'Malay', 'UTC+8', '+60', 'Type G', 'Petronas Towers, diverse food, rainforests, beaches, and multi-ethnic harmony.'],
    'IDN' => ['Jakarta', '274 million', 'Indonesian Rupiah', 'IDR', 'Rp', 'Indonesian', 'UTC+7 to UTC+9', '+62', 'Type C, F', 'Bali, Borobudur, Komodo dragons, volcanoes, beaches, and archipelago diversity.'],
    'PHL' => ['Manila', '111 million', 'Philippine Peso', 'PHP', '₱', 'Filipino, English', 'UTC+8', '+63', 'Type A, B, C', 'Boracay beaches, rice terraces, jeepneys, hospitality, and island hopping.'],
    'LA' => ['Vientiane', '7.3 million', 'Lao Kip', 'LAK', '₭', 'Lao', 'UTC+7', '+856', 'Type A, B, C, E, F', 'Luang Prabang temples, Mekong River, laid-back pace, and French influence.'],
    'LAO' => ['Vientiane', '7.3 million', 'Lao Kip', 'LAK', '₭', 'Lao', 'UTC+7', '+856', 'Type A, B, C, E, F', 'Luang Prabang temples, Mekong River, laid-back pace, and French influence.'],
    'KH' => ['Phnom Penh', '17 million', 'Cambodian Riel', 'KHR', '៛', 'Khmer', 'UTC+7', '+855', 'Type A, C, G', 'Angkor Wat, Khmer history, Tonlé Sap lake, and affordable backpacking.'],
    'MM' => ['Naypyidaw', '54 million', 'Myanmar Kyat', 'MMK', 'K', 'Burmese', 'UTC+6:30', '+95', 'Type C, D, F, G', 'Bagan temples, Inle Lake, golden pagodas, and emerging destination.'],
    'BN' => ['Bandar Seri Begawan', '440,000', 'Brunei Dollar', 'BND', 'B$', 'Malay', 'UTC+8', '+673', 'Type G', 'Oil-rich sultanate, Omar Ali Saifuddien Mosque, rainforests, and pristine.'],
    'BRN' => ['Bandar Seri Begawan', '440,000', 'Brunei Dollar', 'BND', 'B$', 'Malay', 'UTC+8', '+673', 'Type G', 'Oil-rich sultanate, Omar Ali Saifuddien Mosque, rainforests, and pristine.'],
    'TLS' => ['Dili', '1.3 million', 'US Dollar', 'USD', '$', 'Tetum, Portuguese', 'UTC+9', '+670', 'Type C, E, F, I', 'Youngest nation, diving, Portuguese heritage, and developing tourism.'],
    'IND' => ['New Delhi', '1.4 billion', 'Indian Rupee', 'INR', '₹', 'Hindi, English', 'UTC+5:30', '+91', 'Type C, D, M', 'Taj Mahal, Bollywood, curry, yoga, diverse culture, and ancient wisdom.'],
    'PK' => ['Islamabad', '225 million', 'Pakistani Rupee', 'PKR', '₨', 'Urdu, English', 'UTC+5', '+92', 'Type C, D', 'K2, Karakoram Highway, hospitality, biryani, and mountain adventures.'],
    'BD' => ['Dhaka', '166 million', 'Bangladeshi Taka', 'BDT', '৳', 'Bengali', 'UTC+6', '+880', 'Type C, D, G, K', 'Sundarbans, Cox\'s Bazar beach, rickshaws, textiles, and delta life.'],
    'LKA' => ['Colombo', '22 million', 'Sri Lankan Rupee', 'LKR', 'Rs', 'Sinhala, Tamil', 'UTC+5:30', '+94', 'Type D, G, M', 'Ancient Sigiriya, tea plantations, elephants, beaches, and Buddhist heritage.'],
    'NPL' => ['Kathmandu', '30 million', 'Nepalese Rupee', 'NPR', 'Rs', 'Nepali', 'UTC+5:45', '+977', 'Type C, D, M', 'Mount Everest, trekking, sherpa culture, temples, and Himalayas.'],
    'BTN' => ['Thimphu', '770,000', 'Bhutanese Ngultrum', 'BTN', 'Nu', 'Dzongkha', 'UTC+6', '+975', 'Type D, F, G', 'Gross National Happiness, monasteries, pristine nature, and unique tourism.'],
    'MDV' => ['Malé', '540,000', 'Maldivian Rufiyaa', 'MVR', 'Rf', 'Dhivehi', 'UTC+5', '+960', 'Type D, G', 'Overwater bungalows, crystal waters, luxury resorts, diving, and island paradise.'],
    'AFG' => ['Kabul', '39 million', 'Afghan Afghani', 'AFN', '؋', 'Pashto, Dari', 'UTC+4:30', '+93', 'Type C, F', 'Ancient Silk Road, rugged mountains, complex history, and challenging situation.'],
    'KZ' => ['Astana', '19 million', 'Kazakhstani Tenge', 'KZT', '₸', 'Kazakh, Russian', 'UTC+5 to UTC+6', '+7', 'Type C, F', 'Silk Road, Baikonur Cosmodrome, steppes, nomadic heritage, and oil wealth.'],
    'KAZ' => ['Astana', '19 million', 'Kazakhstani Tenge', 'KZT', '₸', 'Kazakh, Russian', 'UTC+5 to UTC+6', '+7', 'Type C, F', 'Silk Road, Baikonur Cosmodrome, steppes, nomadic heritage, and oil wealth.'],
    'UZ' => ['Tashkent', '34 million', 'Uzbekistani Som', 'UZS', 'soʻm', 'Uzbek', 'UTC+5', '+998', 'Type C, I', 'Samarkand, Silk Road cities, Islamic architecture, and pilaf.'],
    'KG' => ['Bishkek', '6.6 million', 'Kyrgyzstani Som', 'KGS', 'с', 'Kyrgyz, Russian', 'UTC+6', '+996', 'Type C, F', 'Issyk-Kul lake, mountains, nomadic yurts, horses, and natural beauty.'],
    'KGZ' => ['Bishkek', '6.6 million', 'Kyrgyzstani Som', 'KGS', 'с', 'Kyrgyz, Russian', 'UTC+6', '+996', 'Type C, F', 'Issyk-Kul lake, mountains, nomadic yurts, horses, and natural beauty.'],
    'TJ' => ['Dushanbe', '9.5 million', 'Tajikstani Somoni', 'TJS', 'ЅМ', 'Tajik', 'UTC+5', '+992', 'Type C, F, I', 'Pamir Highway, mountains, Persian culture, and remote trekking.'],
    'TM' => ['Ashgabat', '6.1 million', 'Turkmenistani Manat', 'TMT', 'm', 'Turkmen', 'UTC+5', '+993', 'Type B, C, F', 'Gate of Hell crater, marble capital, carpet weaving, and isolation.'],
    'ARE' => ['Abu Dhabi', '10 million', 'UAE Dirham', 'AED', 'د.إ', 'Arabic', 'UTC+4', '+971', 'Type G', 'Burj Khalifa, luxury shopping, futuristic architecture, desert safaris, and oil wealth.'],
    'SAU' => ['Riyadh', '35 million', 'Saudi Riyal', 'SAR', '﷼', 'Arabic', 'UTC+3', '+966', 'Type A, B, F, G', 'Mecca, Islamic heritage, oil wealth, Vision 2030, and AlUla ruins.'],
    'QAT' => ['Doha', '2.9 million', 'Qatari Riyal', 'QAR', '﷼', 'Arabic', 'UTC+3', '+974', 'Type D, G', 'World Cup 2022, Museum of Islamic Art, natural gas wealth, and modernity.'],
    'OMN' => ['Muscat', '5.1 million', 'Omani Rial', 'OMR', '﷼', 'Arabic', 'UTC+4', '+968', 'Type C, G', 'Sultan Qaboos Mosque, frankincense, wadis, forts, and dramatic landscapes.'],
    'KWT' => ['Kuwait City', '4.3 million', 'Kuwaiti Dinar', 'KWD', 'د.ك', 'Arabic', 'UTC+3', '+965', 'Type C, G', 'Modern towers, oil wealth, liberation history, and Gulf culture.'],
    'BHR' => ['Manama', '1.7 million', 'Bahraini Dinar', 'BHD', 'د.ب', 'Arabic', 'UTC+3', '+973', 'Type G', 'Pearl diving heritage, Formula 1, liberal Gulf state, and causeway to Saudi.'],
    'JOR' => ['Amman', '10.2 million', 'Jordanian Dinar', 'JOD', 'د.ا', 'Arabic', 'UTC+2', '+962', 'Type B, C, D, F, G, J', 'Petra, Wadi Rum, Dead Sea, Roman ruins, and Bedouin hospitality.'],
    'LBN' => ['Beirut', '6.8 million', 'Lebanese Pound', 'LBP', 'ل.ل', 'Arabic, French', 'UTC+2', '+961', 'Type A, B, C, D, G', 'Ancient Phoenician sites, cedar trees, diverse cuisine, and resilient spirit.'],
    'ISR' => ['Jerusalem', '9.4 million', 'Israeli Shekel', 'ILS', '₪', 'Hebrew, Arabic', 'UTC+2', '+972', 'Type C, H, M', 'Jerusalem holy sites, Tel Aviv beaches, high-tech hub, and diverse history.'],
    'SYR' => ['Damascus', '18 million', 'Syrian Pound', 'SYP', '£', 'Arabic', 'UTC+2', '+963', 'Type C, E, L', 'Ancient Damascus, Palmyra ruins, diverse heritage, and ongoing conflict.'],
    'IRQ' => ['Baghdad', '40 million', 'Iraqi Dinar', 'IQD', 'ع.د', 'Arabic, Kurdish', 'UTC+3', '+964', 'Type C, D, G', 'Mesopotamian cradle of civilization, Babylon, oil wealth, and rebuilding.'],
    'IRN' => ['Tehran', '84 million', 'Iranian Rial', 'IRR', '﷼', 'Persian', 'UTC+3:30', '+98', 'Type C, F', 'Persian Empire heritage, Isfahan architecture, bazaars, poetry, and rich culture.'],
    'YEM' => ['Sana\'a', '30 million', 'Yemeni Rial', 'YER', '﷼', 'Arabic', 'UTC+3', '+967', 'Type A, D, G', 'Ancient Sana\'a, Socotra island, frankincense, and humanitarian challenges.'],
    'TUR' => ['Ankara', '85 million', 'Turkish Lira', 'TRY', '₺', 'Turkish', 'UTC+3', '+90', 'Type C, F', 'Istanbul, Cappadocia, Ottoman history, kebabs, tea culture, and bridging continents.'],
    'ARM' => ['Yerevan', '3 million', 'Armenian Dram', 'AMD', '֏', 'Armenian', 'UTC+4', '+374', 'Type C, F', 'First Christian nation, Ararat views, ancient monasteries, and brandy.'],
    'GE' => ['Tbilisi', '3.7 million', 'Georgian Lari', 'GEL', '₾', 'Georgian', 'UTC+4', '+995', 'Type C, F', 'Wine cradle, Caucasus mountains, unique alphabet, hospitality, and khachapuri.'],
    'GEO' => ['Tbilisi', '3.7 million', 'Georgian Lari', 'GEL', '₾', 'Georgian', 'UTC+4', '+995', 'Type C, F', 'Wine cradle, Caucasus mountains, unique alphabet, hospitality, and khachapuri.'],
    'AZ' => ['Baku', '10.1 million', 'Azerbaijani Manat', 'AZN', '₼', 'Azerbaijani', 'UTC+4', '+994', 'Type C, F', 'Flame Towers, oil wealth, Caspian Sea, carpet weaving, and fire worship heritage.'],
];

$stmt = $pdo->prepare("UPDATE countries SET capital = ?, population = ?, currency_name = ?, currency_code = ?, currency_symbol = ?, languages = ?, time_zone = ?, calling_code = ?, plug_type = ? WHERE country_code = ?");
$detailsStmt = $pdo->prepare("INSERT INTO country_details (country_id, lang_code, known_for) VALUES ((SELECT id FROM countries WHERE country_code = ?), 'en', ?) ON DUPLICATE KEY UPDATE known_for = VALUES(known_for)");

$updated = 0;
foreach ($remaining as $code => $d) {
    try {
        $stmt->execute([$d[0], $d[1], $d[2], $d[3], $d[4], $d[5], $d[6], $d[7], $d[8], $code]);
        $detailsStmt->execute([$code, $d[9]]);
        $updated++;
        echo "✓ $code\n";
    } catch (PDOException $e) {
        echo "✗ $code\n";
    }
}

echo "\n=== Updated: $updated countries ===\n";
