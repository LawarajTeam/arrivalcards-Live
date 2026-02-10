<?php
/**
 * Final Update - Updates only existing country codes with unique descriptions
 */

require 'includes/config.php';

echo "=== Final Country Entry Summary Update ===\n\n";

// All country descriptions
$descriptions = [
    'USA' => "Home to New York, Los Angeles, and natural wonders like the Grand Canyon. Most visitors need a visa or ESTA authorization. The ESTA (Electronic System for Travel Authorization) is available for visa waiver countries and costs $21.",
    'CAN' => "From Vancouver's coastal beauty to Toronto's urban vibrancy and the Canadian Rockies, Canada requires eTA for visa-exempt travelers arriving by air. The eTA is electronically linked to your passport and valid for 5 years.",
    'MEX' => "Discover ancient Mayan ruins, pristine beaches, and vibrant Mexico City. Most tourists receive a free tourist card (FMM) valid for 180 days upon arrival. Some nationalities need to apply for a visa in advance at Mexican consulates.",
    'BRA' => "Experience Rio's beaches, Amazon rainforest, and São Paulo's bustling metropolis. Many nationals can enter visa-free for tourism up to 90 days. E-visa available for select countries, processed in 5-10 business days.",
    'ARG' => "Explore Buenos Aires tango culture, Patagonian glaciers, and world-class wine regions. Citizens of most Western countries can enter visa-free for tourism up to 90 days. Some travelers may pay a reciprocity fee at certain entry points.",
    'CHL' => "Discover the Atacama Desert, Easter Island, and the Chilean Patagonia. Most tourists can enter visa-free for up to 90 days. A tourist card (Tarjeta de Turismo) is issued free at entry points and must be kept until departure.",
    'COL' => "Visit Cartagena's colonial architecture, Bogotá's museums, and coffee region. Many nationals can enter visa-free for 90 days extendable to 180 days. Citizens of some countries need to apply for a visa at Colombian embassies.",
    'PER' => "Gateway to Machu Picchu, Lima's gastronomy, and Lake Titicaca. Most tourists can enter visa-free for 183 days. Free Andean Immigration Card issued upon arrival must be presented when departing.",
    'VE' => "Home to Angel Falls and Caribbean coastlines. Tourist visa required for most visitors, obtained through Venezuelan consulates. Current entry requirements should be verified due to changing regulations.",
    'ECU' => "Explore the Galápagos Islands, Quito's historic center, and Amazon rainforest. Most nationals can enter visa-free for up to 90 days. Proof of onward travel and sufficient funds may be requested.",
    'BO' => "Visit the Uyuni Salt Flats, La Paz, and Lake Titicaca. Some countries can enter visa-free for 90 days, others require a visa upon arrival ($160 USD for Americans). Yellow fever vaccination certificate required from endemic countries.",
    'BOL' => "Visit the Uyuni Salt Flats, La Paz, and Lake Titicaca. Some countries can enter visa-free for 90 days, others require a visa upon arrival ($160 USD for Americans). Yellow fever vaccination certificate required from endemic countries.",
    'PY' => "Discover Asunción and the Jesuit Missions. Most Western nationals can enter visa-free for 90 days. Entry requirements are straightforward with few restrictions for tourists.",
    'UY' => "Experience Montevideo's beaches, Punta del Este, and colonial Colonia. Most tourists can enter visa-free for 90 days. One of South America's safest and most developed countries with simple entry procedures.",
    'GUY' => "Gateway to pristine rainforests and Kaieteur Falls. Visa-free entry for many Commonwealth and American citizens for 90 days. Some nationalities require visa from Guyanese embassies.",
    'CR' => "Discover San José, cloud forests, and stunning biodiversity. Costa Rica allows visa-free entry for many nationalities for 90 days. Eco-tourism leader with 'Pura Vida' lifestyle, stable democracy, no army.",
    'CRI' => "Discover San José, cloud forests, and stunning biodiversity. Costa Rica allows visa-free entry for many nationalities for 90 days. Eco-tourism leader with 'Pura Vida' lifestyle, stable democracy, no army.",
    'PAN' => "Experience Panama City's skyline, Canal, and Caribbean beaches. Panama allows visa-free entry for many countries for 180 days. Some can get stamped tourist card on arrival. Hub connecting Americas.",
    'GT' => "Visit colonial Antigua, Mayan ruins of Tikal, and Lake Atitlán. Guatemala allows visa-free entry for many nationalities for 90 days (CA-4 agreement with Honduras, El Salvador, Nicaragua). Rich indigenous culture.",
    'GTM' => "Visit colonial Antigua, Mayan ruins of Tikal, and Lake Atitlán. Guatemala allows visa-free entry for many nationalities for 90 days (CA-4 agreement with Honduras, El Salvador, Nicaragua). Rich indigenous culture.",
    'BZ' => "Explore Great Blue Hole, Caribbean beaches, and Mayan sites. Belize allows visa-free entry for many countries for 30 days, extensible. English-speaking Central American nation with Caribbean culture.",
    'HN' => "Discover Copán ruins, Roatán diving, and Bay Islands. Honduras allows visa-free entry for many nationalities for 90 days (CA-4 agreement). Affordable Caribbean coast diving and island hopping.",
    'HND' => "Discover Copán ruins, Roatán diving, and Bay Islands. Honduras allows visa-free entry for many nationalities for 90 days (CA-4 agreement). Affordable Caribbean coast diving and island hopping.",
    'SV' => "Experience San Salvador, Ruta de las Flores, and surf beaches. El Salvador allows visa-free entry for many countries for 90 days (CA-4). Smallest Central American country, Bitcoin legal tender.",
    'NI' => "Visit Granada's colonial architecture, Ometepe Island, and volcanoes. Nicaragua allows visa-free entry or tourist card for most nationalities for 90 days (CA-4). Affordable destination with lakes and colonial cities.",
    'CU' => "Explore Havana's vintage cars, Varadero beaches, and salsa culture. Cuba requires tourist card (visa) for most visitors, obtained through airlines or travel agencies ($25-100). U.S. citizens have specific restrictions.",
    'CUB' => "Explore Havana's vintage cars, Varadero beaches, and salsa culture. Cuba requires tourist card (visa) for most visitors, obtained through airlines or travel agencies ($25-100). U.S. citizens have specific restrictions.",
    'HTI' => "Visit Port-au-Prince and Citadelle Laferrière. Haiti allows visa-free entry for many nationalities for 90 days. First independent black republic. Verify security situation before travel due to ongoing challenges.",
    'DO' => "Experience Santo Domingo's history, Punta Cana beaches, and merengue. Dominican Republic offers free tourist card on arrival for many nationalities valid 30 days. Popular Caribbean destination with all-inclusive resorts.",
    'DOM' => "Experience Santo Domingo's history, Punta Cana beaches, and merengue. Dominican Republic offers free tourist card on arrival for many nationalities valid 30 days. Popular Caribbean destination with all-inclusive resorts.",
    'JM' => "Discover Montego Bay, Nine Mile (Bob Marley's birthplace), and Blue Mountains. Jamaica allows visa-free entry for many countries for up to 90 days. English-speaking island with rich reggae culture.",
    'JAM' => "Discover Montego Bay, Nine Mile (Bob Marley's birthplace), and Blue Mountains. Jamaica allows visa-free entry for many countries for up to 90 days. English-speaking island with rich reggae culture.",
    'TT' => "Visit Port of Spain's Carnival, Maracas Beach, and bird sanctuaries. Trinidad and Tobago allows visa-free entry for many nationalities for 90 days. Twin-island nation with diverse culture and energy resources.",
    'BB' => "Experience Bridgetown, stunning beaches, and rum distilleries. Barbados allows visa-free entry for many countries for up to 6 months. English-speaking, easternmost Caribbean island, birthplace of rum.",
    'BS' => "Discover Nassau's Atlantis resort, Exuma's swimming pigs, and crystal waters. Bahamas allows visa-free entry for many nationalities for up to 90 days. 700 islands, popular cruise destination.",
    'GRD' => "Visit St. George's, spice plantations, and Grand Anse Beach. Grenada allows visa-free entry for many countries for 90 days. 'Spice Isle' of Caribbean, English-speaking, nutmeg and mace producer.",
    'DMA' => "Explore 'Nature Island's' rainforests, Boiling Lake, and waterfalls. Dominica allows visa-free entry for many nationalities for 90 days. Least developed Caribbean island with pristine nature, English-speaking.",
    'FRA' => "Visit the Eiffel Tower, French Riviera, and Loire Valley châteaux. France is a Schengen member - EU/EEA citizens enter freely, others may need a Schengen visa allowing 90 days in 180-day period across 27 European countries.",
    'DEU' => "Explore Berlin's history, Munich's beer gardens, and the Black Forest. As a Schengen member, EU/EEA citizens enter freely. Other nationals may need a Schengen visa permitting 90 days across Schengen zone within any 180-day period.",
    'ESP' => "Experience Barcelona's architecture, Madrid's museums, and Andalusian beaches. Spain is in the Schengen Area - visa requirements same as other Schengen countries. EU/EEA citizens can enter with just an ID card.",
    'ITA' => "Discover Rome's ancient ruins, Venice's canals, and Tuscany's vineyards. Italy is a Schengen member requiring EU/EEA citizens only ID cards. Non-EU visitors may need Schengen visa for stays up to 90 days per 180-day period.",
    'NLD' => "Visit Amsterdam's canals, tulip fields, and world-class museums. Netherlands is a Schengen country allowing EU/EEA citizens free entry. Multiple-entry Schengen visas valid for entire Schengen Area are required for many nationalities.",
    'BEL' => "Explore Brussels' Grand Place, Bruges' medieval charm, and Antwerp's diamonds. Belgium is part of Schengen Area - EU citizens need only ID cards. Others require Schengen visa permitting 90-day stays across 27 member countries.",
    'AUT' => "Discover Vienna's classical music heritage, Salzburg's Alps, and skiing resorts. Austria is a Schengen member - EU/EEA citizens enter freely. Standard Schengen visa requirements apply for other nationals with 90-day maximum stays.",
    'CHE' => "Experience the Swiss Alps, Geneva's international ambiance, and pristine lakes. Not EU but in Schengen Area - EU citizens enter freely. Others need Schengen visa. Swiss hospitality and efficiency make travel smooth.",
    'GRC' => "Visit Athens' Acropolis, Santorini's sunsets, and ancient archaeological sites. Greece is in Schengen Area - EU/EEA citizens enter with ID cards. Others may need Schengen visa for 90-day tourism visits.",
    'PRT' => "Explore Lisbon's hills, Porto's port wine cellars, and Algarve beaches. Portugal is a Schengen member allowing EU/EEA citizens free entry. Non-EU nationals may need Schengen visa valid across all member states.",
    'SWE' => "Discover Stockholm's archipelago, Northern Lights, and innovative design culture. Sweden is part of Schengen Area - EU/EEA citizens enter freely. Others may need 90-day Schengen visa covering all 27 member countries.",
    'NOR' => "Experience Oslo's Viking heritage, spectacular fjords, and midnight sun. Norway is in Schengen (not EU) - EU/EEA citizens enter freely. Others need Schengen visa. One of world's most expensive countries.",
    'DNK' => "Visit Copenhagen's Nyhavn, Legoland, and hygge culture. Denmark is a Schengen member - EU/EEA citizens need only ID cards. Standard Schengen visa rules apply for other nationals with 90-day limits.",
    'FIN' => "Experience Helsinki's design, Lapland's wilderness, and saunas. Finland is in Schengen Area - EU/EEA citizens enter with ID cards. Others require Schengen visa for stays up to 90 days per 180-day period.",
    'ISL' => "Discover Reykjavik, Blue Lagoon, and dramatic volcanic landscapes. Iceland is in Schengen (not EU) - EU/EEA citizens enter freely. Others need Schengen visa. Unique geology and Northern Lights attract millions annually.",
    'POL' => "Explore Warsaw's rebuilding, Krakow's medieval center, and Auschwitz memorial. Poland is a Schengen EU member - EU citizens enter with ID cards. Others may need Schengen visa for 90-day tourism stays.",
    'CZE' => "Visit Prague's fairy-tale architecture, Bohemian castles, and world-famous beer culture. Czech Republic is in Schengen Area - EU/EEA citizens enter freely. Others need standard Schengen visa for 90-day visits.",
    'HUN' => "Experience Budapest's thermal baths, Parliament building, and ruin bars. Hungary is a Schengen member - EU/EEA citizens need only passports or ID cards. Standard Schengen visa requirements apply to other nationals.",
    'SK' => "Discover Bratislava's old town and Tatra Mountains. Slovakia is in Schengen Area and uses Euro - EU/EEA citizens enter freely. Others require Schengen visa allowing 90 days within 180-day period.",
    'SI' => "Explore Ljubljana's charm, Lake Bled, and Postojna Caves. Slovenia is a Schengen member - EU/EEA citizens enter with ID cards. Standard Schengen visa rules apply with 90-day tourism allowance.",
    'EE' => "Visit Tallinn's medieval old town and digital innovation culture. Estonia is in Schengen Area - EU/EEA citizens enter freely. Others need Schengen visa. Known as most advanced digital society in Europe.",
    'EST' => "Visit Tallinn's medieval old town and digital innovation culture. Estonia is in Schengen Area - EU/EEA citizens enter freely. Others need Schengen visa. Known as most advanced digital society in Europe.",
    'LV' => "Discover Riga's Art Nouveau architecture and Baltic Sea beaches. Latvia is a Schengen EU member - EU citizens need only ID cards. Standard Schengen visa requirements apply for 90-day tourism visits.",
    'LVA' => "Discover Riga's Art Nouveau architecture and Baltic Sea beaches. Latvia is a Schengen EU member - EU citizens need only ID cards. Standard Schengen visa requirements apply for 90-day tourism visits.",
    'LT' => "Experience Vilnius' baroque architecture and Hill of Crosses. Lithuania is in Schengen Area - EU/EEA citizens enter freely. Others require standard Schengen visa for tourism stays up to 90 days.",
    'MT' => "Explore Valletta's fortifications, ancient temples, and Mediterranean beaches. Malta is EU and Schengen member - EU citizens enter with ID cards. Others may need Schengen visa for 90-day visits.",
    'CY' => "Visit ancient Paphos, divided Nicosia, and Troodos Mountains. Cyprus is EU member (not yet Schengen) - EU/EEA citizens enter freely. Others may need visa through e-visa portal or upon arrival for tourism.",
    'CYP' => "Visit ancient Paphos, divided Nicosia, and Troodos Mountains. Cyprus is EU member (not yet Schengen) - EU/EEA citizens enter freely. Others may need visa through e-visa portal or upon arrival for tourism.",
    'LU' => "Discover Luxembourg City's fortifications and European institutions. Luxembourg is a Schengen founder - EU/EEA citizens enter with ID cards. Standard Schengen visa requirements for 90-day visits apply.",
    'GBR' => "Visit London's landmarks, Scottish Highlands, and historic universities. Post-Brexit, UK has separate visa requirements from Schengen. Many nationals can visit visa-free for 6 months. eVisa system introduced for permitted travelers.",
    'IRL' => "Experience Dublin's literary heritage, Cliffs of Moher, and friendly pub culture. Ireland not in Schengen - has own visa requirements. Many nationalities can enter visa-free sharing arrangements with UK's Common Travel Area.",
    'RO' => "Explore Bucharest, Transylvania's castles, and Danube Delta. Romania is EU member (joining Schengen soon) - EU citizens enter freely. Others need Romanian visa separate from standard Schengen for now.",
    'BG' => "Discover Sofia's history, Black Sea coast, and Rila Monastery. Bulgaria is EU member (joining Schengen soon) - EU/EEA citizens enter freely. Others currently need separate Bulgarian visa, not standard Schengen.",
    'HR' => "Visit Dubrovnik's walls, Plitvice Lakes, and Dalmatian coast. Croatia joined Schengen 2023 - EU/EEA citizens enter freely. Standard Schengen visa now applies allowing 90 days across all member states.",
    'HRV' => "Visit Dubrovnik's walls, Plitvice Lakes, and Dalmatian coast. Croatia joined Schengen 2023 - EU/EEA citizens enter freely. Standard Schengen visa now applies allowing 90 days across all member states.",
    'RS' => "Experience Belgrade's nightlife, Novi Sad's culture, and monasteries. Serbia not in EU or Schengen - many nationals can enter visa-free for 90 days. E-visa available for countries requiring pre-authorization.",
    'BA' => "Discover Sarajevo's history, Mostar's bridge, and Sutjeska National Park. Bosnia allows visa-free entry for many countries up to 90 days. Some nationalities need visa from Bosnian embassies before travel.",
    'BIH' => "Discover Sarajevo's history, Mostar's bridge, and Sutjeska National Park. Bosnia allows visa-free entry for many countries up to 90 days. Some nationalities need visa from Bosnian embassies before travel.",
    'MK' => "Explore Skopje's eclectic architecture and Ohrid Lake's beauty. North Macedonia allows visa-free entry for most Western countries for 90 days. Some nationalities require visa obtained before arrival.",
    'AL' => "Visit Tirana's colorful buildings, Albanian Riviera, and ancient Butrint. Albania allows visa-free entry for many nationals for stays up to 90 days. Increasingly popular off-the-beaten-path Mediterranean destination.",
    'ME' => "Discover Kotor's bay, medieval Budva, and Durmitor National Park. Montenegro allows visa-free entry for many countries for 90 days. Popular Adriatic destination with dramatic mountains meeting sea.",
    'UA' => "Experience Kyiv's golden domes, Lviv's charm, and Carpathian Mountains. Ukraine offers visa-free entry for many nationals for 90 days. Entry regulations subject to change - verify current requirements before travel.",
    'BY' => "Visit Minsk's Soviet architecture, Mir Castle, and Belovezhskaya Forest. Belarus offers visa-free entry through Minsk airport for 30 days for many nationalities. Land border crossings require visa obtained in advance.",
    'MD' => "Explore Chișinău and wine regions. Moldova allows visa-free entry for many countries for 90 days. One of Europe's least visited countries offering authentic experiences and renowned wines.",
    'RUS' => "Discover Moscow's Red Square, St. Petersburg's palaces, and vast landscapes. Russia requires tourist visa for most visitors, obtained through Russian consulates. E-visa available for some regions. Largest country spanning 11 time zones.",
    'LIE' => "Visit Vaduz Castle and Alpine landscapes. Liechtenstein is in Schengen Area - EU/EEA citizens enter freely. Others need Schengen visa. Tiny principality between Switzerland and Austria, German-speaking.",
    'XKX' => "Discover Pristina's youth energy, Peja's monasteries, and mountain landscapes. Kosovo allows visa-free entry for many countries for 90 days. Newest European country with complex recognition status, warm hospitality.",
    'CHN' => "Experience the Great Wall, Shanghai's skyline, and terracotta warriors. China requires visa for most visitors obtained through embassies or visa centers. 144-hour transit visa-free available for select cities. Apply 3 months before travel.",
    'JPN' => "Visit Tokyo's modernity, Kyoto's temples, and Mount Fuji. Japan allows visa-free entry for many nationals for 90 days tourism. Known for efficiency, safety, and unique culture blending tradition with cutting-edge technology.",
    'KOR' => "Explore Seoul's K-pop culture, Jeju Island, and DMZ. South Korea offers visa-free entry for many countries for 90 days. K-ETA (electronic authorization) required for visa-exempt travelers, costs 10,000 won.",
    'HKG' => "Experience Victoria Peak, vibrant markets, and dim sum culture. Hong Kong allows visa-free entry for many nationalities from 7 to 180 days depending on nationality. Special Administrative Region with own entry rules.",
    'MN' => "Discover Ulaanbaatar, Gobi Desert, and nomadic culture. Mongolia allows visa-free entry for some countries for 30 days. Others need visa from Mongolian embassies. Experience authentic nomadic lifestyle and vast landscapes.",
    'THA' => "Experience Bangkok's temples, Phuket's beaches, and northern hill tribes. Thailand offers visa-free entry for many nationals for 30-60 days depending on arrival method. Can extend stays or apply for tourist visa for longer visits.",
    'VNM' => "Visit Hanoi's old quarter, Halong Bay, and Ho Chi Minh City's history. Vietnam offers e-visa for 90 days for most nationalities at $25. Some countries have visa exemption agreements. Growing popular destination with rich culture.",
    'SGP' => "Discover Marina Bay, Gardens by the Bay, and multicultural cuisine. Singapore allows visa-free entry for most visitors for 30-90 days. One of world's cleanest, safest cities with strict laws and efficient systems.",
    'MYS' => "Explore Kuala Lumpur's Petronas Towers, Penang's food, and Borneo's jungle. Malaysia offers visa-free entry for many nationalities for 30-90 days. E-visa and visa-on-arrival available for countries requiring authorization.",
    'IDN' => "Visit Bali's beaches, Borobudur temple, and Komodo dragons. Indonesia offers visa-free entry for 30 days or visa-on-arrival for 30 days ($35). World's largest archipelago with over 17,000 islands and diverse cultures.",
    'PHL' => "Discover Manila's history, Palawan's beaches, and chocolate hills. Philippines allows visa-free entry for many nationals for 30 days upon arrival. Known for 7,000+ islands, friendly locals, and affordable tropical paradise.",
    'LA' => "Experience Luang Prabang's temples, Mekong River, and laid-back culture. Laos offers visa-on-arrival for most nationalities for $30-42 at tourist prices. E-visa also available. Least developed but most relaxed Southeast Asian country.",
    'LAO' => "Experience Luang Prabang's temples, Mekong River, and laid-back culture. Laos offers visa-on-arrival for most nationalities for $30-42 at tourist prices. E-visa also available. Least developed but most relaxed Southeast Asian country.",
    'KH' => "Visit Angkor Wat, Phnom Penh's history, and Sihanoukville beaches. Cambodia offers e-visa ($36) or visa-on-arrival ($30) for tourists valid 30 days. One of Southeast Asia's most affordable destinations with ancient wonders.",
    'MM' => "Explore Bagan's temples, Inle Lake, and Yangon's Shwedagon Pagoda. Myanmar offers e-visa for tourism for $50 valid 90 days for single entry, stay up to 28 days. Entry regulations subject to change - verify before travel.",
    'BN' => "Discover Bandar Seri Begawan's mosques and pristine rainforests. Brunei allows visa-free entry for many countries for 14-90 days. Wealthy sultanate with strict Islamic laws but welcoming to respectful tourists.",
    'BRN' => "Discover Bandar Seri Begawan's mosques and pristine rainforests. Brunei allows visa-free entry for many countries for 14-90 days. Wealthy sultanate with strict Islamic laws but welcoming to respectful tourists.",
    'AM' => "Visit Yerevan's Republic Square, ancient Geghard monastery, and Mount Ararat views. Armenia offers visa-free entry for many countries for 180 days. E-visa available for others at $6-31. Ancient Christian nation with rich history.",
    'GE' => "Experience Tbilisi's old town, Caucasus mountains, and famous wine culture. Georgia allows visa-free entry for many nationalities for 365 days. E-visa available for others. Known for hospitality, wine, and stunning mountain landscapes.",
];

// Get all DB countries
$stmt = $pdo->query("SELECT c.id, c.country_code, ct.country_name 
                     FROM countries c 
                     JOIN country_translations ct ON c.id = ct.country_id 
                     WHERE ct.lang_code = 'en'
                     ORDER BY ct.country_name");
$dbCountries = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Starting update for " . count($dbCountries) . " database countries...\n\n";

$updated = 0;
$skipped = 0;

foreach ($dbCountries as $country) {
    $code = $country['country_code'];
    
    if (isset($descriptions[$code])) {
        $stmt = $pdo->prepare("UPDATE country_translations 
                               SET entry_summary = ? 
                               WHERE country_id = ? AND lang_code = 'en'");
        $stmt->execute([$descriptions[$code], $country['id']]);
        
        if ($stmt->rowCount() > 0) {
            $updated++;
            echo "✓ {$country['country_name']} ($code)\n";
        }
    } else {
        $skipped++;
        echo "⊘ {$country['country_name']} ($code) - no description\n";
    }
}

echo "\n=== Complete ===\n";
echo "Updated: $updated\n";
echo "Skipped: $skipped\n";
