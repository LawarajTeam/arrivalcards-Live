<?php
/**
 * Update All Country Entry Summaries with Unique, Relevant Content
 * This script creates specific, interesting descriptions for each of the 195 countries
 */

require 'includes/config.php';

echo "=== Updating Country Entry Summaries ===\n\n";

// Comprehensive country data with unique descriptions
$countryDescriptions = [
    // Americas
    'USA' => "Home to New York, Los Angeles, and natural wonders like the Grand Canyon. Most visitors need a visa or ESTA authorization. The ESTA (Electronic System for Travel Authorization) is available for visa waiver countries and costs $21.",
    'CAN' => "From Vancouver's coastal beauty to Toronto's urban vibrancy and the Canadian Rockies, Canada requires eTA for visa-exempt travelers arriving by air. The eTA is electronically linked to your passport and valid for 5 years.",
    'MEX' => "Discover ancient Mayan ruins, pristine beaches, and vibrant Mexico City. Most tourists receive a free tourist card (FMM) valid for 180 days upon arrival. Some nationalities need to apply for a visa in advance at Mexican consulates.",
    'BRA' => "Experience Rio's beaches, Amazon rainforest, and São Paulo's bustling metropolis. Many nationals can enter visa-free for tourism up to 90 days. E-visa available for select countries, processed in 5-10 business days.",
    'ARG' => "Explore Buenos Aires tango culture, Patagonian glaciers, and world-class wine regions. Citizens of most Western countries can enter visa-free for tourism up to 90 days. Some travelers may pay a reciprocity fee at certain entry points.",
    'CHL' => "Discover the Atacama Desert, Easter Island, and the Chilean Patagonia. Most tourists can enter visa-free for up to 90 days. A tourist card (Tarjeta de Turismo) is issued free at entry points and must be kept until departure.",
    'COL' => "Visit Cartagena's colonial architecture, Bogotá's museums, and coffee region. Many nationals can enter visa-free for 90 days extendable to 180 days. Citizens of some countries need to apply for a visa at Colombian embassies.",
    'PER' => "Gateway to Machu Picchu, Lima's gastronomy, and Lake Titicaca. Most tourists can enter visa-free for 183 days. Free Andean Immigration Card issued upon arrival must be presented when departing.",
    'VEN' => "Home to Angel Falls and Caribbean coastlines. Tourist visa required for most visitors, obtained through Venezuelan consulates. Current entry requirements should be verified due to changing regulations.",
    'ECU' => "Explore the Galápagos Islands, Quito's historic center, and Amazon rainforest. Most nationals can enter visa-free for up to 90 days. Proof of onward travel and sufficient funds may be requested.",
    'BOL' => "Visit the Uyuni Salt Flats, La Paz, and Lake Titicaca. Some countries can enter visa-free for 90 days, others require a visa upon arrival ($160 USD for Americans). Yellow fever vaccination certificate required from endemic countries.",
    'BO' => "Visit the Uyuni Salt Flats, La Paz, and Lake Titicaca. Some countries can enter visa-free for 90 days, others require a visa upon arrival ($160 USD for Americans). Yellow fever vaccination certificate required from endemic countries.",
    'PRY' => "Discover Asunción and the Jesuit Missions. Most Western nationals can enter visa-free for 90 days. Entry requirements are straightforward with few restrictions for tourists.",
    'PY' => "Discover Asunción and the Jesuit Missions. Most Western nationals can enter visa-free for 90 days. Entry requirements are straightforward with few restrictions for tourists.",
    'URY' => "Experience Montevideo's beaches, Punta del Este, and colonial Colonia. Most tourists can enter visa-free for 90 days. One of South America's safest and most developed countries with simple entry procedures.",
    'UY' => "Experience Montevideo's beaches, Punta del Este, and colonial Colonia. Most tourists can enter visa-free for 90 days. One of South America's safest and most developed countries with simple entry procedures.",
    'VE' => "Home to Angel Falls and Caribbean coastlines. Tourist visa required for most visitors, obtained through Venezuelan consulates. Current entry requirements should be verified due to changing regulations.",
    'GUY' => "Gateway to pristine rainforests and Kaieteur Falls. Visa-free entry for many Commonwealth and American citizens for 90 days. Some nationalities require visa from Guyanese embassies.",
    'SUR' => "Explore tropical rainforests and Dutch colonial heritage in Paramaribo. E-visa or visa-on-arrival available for €40-50. Tourist card provided electronically before travel for approved nationalities.",
    'GUF' => "French overseas territory featuring the Guiana Space Centre. As part of France, EU/EEA citizens can enter freely. Others need a Schengen visa, with same requirements as mainland France.",
    
    // Central America & Caribbean
    'CRI' => "Discover San José, cloud forests, and stunning biodiversity. Costa Rica allows visa-free entry for many nationalities for 90 days. Eco-tourism leader with 'Pura Vida' lifestyle, stable democracy, no army.",
    'CR' => "Discover San José, cloud forests, and stunning biodiversity. Costa Rica allows visa-free entry for many nationalities for 90 days. Eco-tourism leader with 'Pura Vida' lifestyle, stable democracy, no army.",
    'PAN' => "Experience Panama City's skyline, Canal, and Caribbean beaches. Panama allows visa-free entry for many countries for 180 days. Some can get stamped tourist card on arrival. Hub connecting Americas.",
    'GTM' => "Visit colonial Antigua, Mayan ruins of Tikal, and Lake Atitlán. Guatemala allows visa-free entry for many nationalities for 90 days (CA-4 agreement with Honduras, El Salvador, Nicaragua). Rich indigenous culture.",
    'GT' => "Visit colonial Antigua, Mayan ruins of Tikal, and Lake Atitlán. Guatemala allows visa-free entry for many nationalities for 90 days (CA-4 agreement with Honduras, El Salvador, Nicaragua). Rich indigenous culture.",
    'BZ' => "Explore Great Blue Hole, Caribbean beaches, and Mayan sites. Belize allows visa-free entry for many countries for 30 days, extensible. English-speaking Central American nation with Caribbean culture.",
    'HND' => "Discover Copán ruins, Roatán diving, and Bay Islands. Honduras allows visa-free entry for many nationalities for 90 days (CA-4 agreement). Affordable Caribbean coast diving and island hopping.",
    'HN' => "Discover Copán ruins, Roatán diving, and Bay Islands. Honduras allows visa-free entry for many nationalities for 90 days (CA-4 agreement). Affordable Caribbean coast diving and island hopping.",
    'SV' => "Experience San Salvador, Ruta de las Flores, and surf beaches. El Salvador allows visa-free entry for many countries for 90 days (CA-4). Smallest Central American country, Bitcoin legal tender.",
    'NI' => "Visit Granada's colonial architecture, Ometepe Island, and volcanoes. Nicaragua allows visa-free entry or tourist card for most nationalities for 90 days (CA-4). Affordable destination with lakes and colonial cities.",
    'CUB' => "Explore Havana's vintage cars, Varadero beaches, and salsa culture. Cuba requires tourist card (visa) for most visitors, obtained through airlines or travel agencies ($25-100). U.S. citizens have specific restrictions.",
    'CU' => "Explore Havana's vintage cars, Varadero beaches, and salsa culture. Cuba requires tourist card (visa) for most visitors, obtained through airlines or travel agencies ($25-100). U.S. citizens have specific restrictions.",
    'HTI' => "Visit Port-au-Prince and Citadelle Laferrière. Haiti allows visa-free entry for many nationalities for 90 days. First independent black republic. Verify security situation before travel due to ongoing challenges.",
    'DOM' => "Experience Santo Domingo's history, Punta Cana beaches, and merengue. Dominican Republic offers free tourist card on arrival for many nationalities valid 30 days. Popular Caribbean destination with all-inclusive resorts.",
    'DO' => "Experience Santo Domingo's history, Punta Cana beaches, and merengue. Dominican Republic offers free tourist card on arrival for many nationalities valid 30 days. Popular Caribbean destination with all-inclusive resorts.",
    'JAM' => "Discover Montego Bay, Nine Mile (Bob Marley's birthplace), and Blue Mountains. Jamaica allows visa-free entry for many countries for up to 90 days. English-speaking island with rich reggae culture.",
    'JM' => "Discover Montego Bay, Nine Mile (Bob Marley's birthplace), and Blue Mountains. Jamaica allows visa-free entry for many countries for up to 90 days. English-speaking island with rich reggae culture.",
    'TT' => "Visit Port of Spain's Carnival, Maracas Beach, and bird sanctuaries. Trinidad and Tobago allows visa-free entry for many nationalities for 90 days. Twin-island nation with diverse culture and energy resources.",
    'BB' => "Experience Bridgetown, stunning beaches, and rum distilleries. Barbados allows visa-free entry for many countries for up to 6 months. English-speaking, easternmost Caribbean island, birthplace of rum.",
    'BS' => "Discover Nassau's Atlantis resort, Exuma's swimming pigs, and crystal waters. Bahamas allows visa-free entry for many nationalities for up to 90 days. 700 islands, popular cruise destination.",
    'GRD' => "Visit St. George's, spice plantations, and Grand Anse Beach. Grenada allows visa-free entry for many countries for 90 days. 'Spice Isle' of Caribbean, English-speaking, nutmeg and mace producer.",
    'DMA' => "Explore 'Nature Island's' rainforests, Boiling Lake, and waterfalls. Dominica allows visa-free entry for many nationalities for 90 days. Least developed Caribbean island with pristine nature, English-speaking.",
    
    // Europe - Schengen
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
    'SVK' => "Discover Bratislava's old town and Tatra Mountains. Slovakia is in Schengen Area and uses Euro - EU/EEA citizens enter freely. Others require Schengen visa allowing 90 days within 180-day period.",
    'SK' => "Discover Bratislava's old town and Tatra Mountains. Slovakia is in Schengen Area and uses Euro - EU/EEA citizens enter freely. Others require Schengen visa allowing 90 days within 180-day period.",
    'SVN' => "Explore Ljubljana's charm, Lake Bled, and Postojna Caves. Slovenia is a Schengen member - EU/EEA citizens enter with ID cards. Standard Schengen visa rules apply with 90-day tourism allowance.",
    'SI' => "Explore Ljubljana's charm, Lake Bled, and Postojna Caves. Slovenia is a Schengen member - EU/EEA citizens enter with ID cards. Standard Schengen visa rules apply with 90-day tourism allowance.",
    'EST' => "Visit Tallinn's medieval old town and digital innovation culture. Estonia is in Schengen Area - EU/EEA citizens enter freely. Others need Schengen visa. Known as most advanced digital society in Europe.",
    'EE' => "Visit Tallinn's medieval old town and digital innovation culture. Estonia is in Schengen Area - EU/EEA citizens enter freely. Others need Schengen visa. Known as most advanced digital society in Europe.",
    'LVA' => "Discover Riga's Art Nouveau architecture and Baltic Sea beaches. Latvia is a Schengen EU member - EU citizens need only ID cards. Standard Schengen visa requirements apply for 90-day tourism visits.",
    'LV' => "Discover Riga's Art Nouveau architecture and Baltic Sea beaches. Latvia is a Schengen EU member - EU citizens need only ID cards. Standard Schengen visa requirements apply for 90-day tourism visits.",
    'LTU' => "Experience Vilnius' baroque architecture and Hill of Crosses. Lithuania is in Schengen Area - EU/EEA citizens enter freely. Others require standard Schengen visa for tourism stays up to 90 days.",
    'LT' => "Experience Vilnius' baroque architecture and Hill of Crosses. Lithuania is in Schengen Area - EU/EEA citizens enter freely. Others require standard Schengen visa for tourism stays up to 90 days.",
    'MLT' => "Explore Valletta's fortifications, ancient temples, and Mediterranean beaches. Malta is EU and Schengen member - EU citizens enter with ID cards. Others may need Schengen visa for 90-day visits.",
    'MT' => "Explore Valletta's fortifications, ancient temples, and Mediterranean beaches. Malta is EU and Schengen member - EU citizens enter with ID cards. Others may need Schengen visa for 90-day visits.",
    'CYP' => "Visit ancient Paphos, divided Nicosia, and Troodos Mountains. Cyprus is EU member (not yet Schengen) - EU/EEA citizens enter freely. Others may need visa through e-visa portal or upon arrival for tourism.",
    'CY' => "Visit ancient Paphos, divided Nicosia, and Troodos Mountains. Cyprus is EU member (not yet Schengen) - EU/EEA citizens enter freely. Others may need visa through e-visa portal or upon arrival for tourism.",
    'LUX' => "Discover Luxembourg City's fortifications and European institutions. Luxembourg is a Schengen founder - EU/EEA citizens enter with ID cards. Standard Schengen visa requirements for 90-day visits apply.",
    'LU' => "Discover Luxembourg City's fortifications and European institutions. Luxembourg is a Schengen founder - EU/EEA citizens enter with ID cards. Standard Schengen visa requirements for 90-day visits apply.",
    
    // Europe - Non-Schengen
    'GBR' => "Visit London's landmarks, Scottish Highlands, and historic universities. Post-Brexit, UK has separate visa requirements from Schengen. Many nationals can visit visa-free for 6 months. eVisa system introduced for permitted travelers.",
    'IRL' => "Experience Dublin's literary heritage, Cliffs of Moher, and friendly pub culture. Ireland not in Schengen - has own visa requirements. Many nationalities can enter visa-free sharing arrangements with UK's Common Travel Area.",
    'ROU' => "Explore Bucharest, Transylvania's castles, and Danube Delta. Romania is EU member (joining Schengen soon) - EU citizens enter freely. Others need Romanian visa separate from standard Schengen for now.",
    'RO' => "Explore Bucharest, Transylvania's castles, and Danube Delta. Romania is EU member (joining Schengen soon) - EU citizens enter freely. Others need Romanian visa separate from standard Schengen for now.",
    'BGR' => "Discover Sofia's history, Black Sea coast, and Rila Monastery. Bulgaria is EU member (joining Schengen soon) - EU/EEA citizens enter freely. Others currently need separate Bulgarian visa, not standard Schengen.",
    'BG' => "Discover Sofia's history, Black Sea coast, and Rila Monastery. Bulgaria is EU member (joining Schengen soon) - EU/EEA citizens enter freely. Others currently need separate Bulgarian visa, not standard Schengen.",
    'HRV' => "Visit Dubrovnik's walls, Plitvice Lakes, and Dalmatian coast. Croatia joined Schengen 2023 - EU/EEA citizens enter freely. Standard Schengen visa now applies allowing 90 days across all member states.",
    'HR' => "Visit Dubrovnik's walls, Plitvice Lakes, and Dalmatian coast. Croatia joined Schengen 2023 - EU/EEA citizens enter freely. Standard Schengen visa now applies allowing 90 days across all member states.",
    'SRB' => "Experience Belgrade's nightlife, Novi Sad's culture, and monasteries. Serbia not in EU or Schengen - many nationals can enter visa-free for 90 days. E-visa available for countries requiring pre-authorization.",
    'RS' => "Experience Belgrade's nightlife, Novi Sad's culture, and monasteries. Serbia not in EU or Schengen - many nationals can enter visa-free for 90 days. E-visa available for countries requiring pre-authorization.",
    'BIH' => "Discover Sarajevo's history, Mostar's bridge, and Sutjeska National Park. Bosnia allows visa-free entry for many countries up to 90 days. Some nationalities need visa from Bosnian embassies before travel.",
    'BA' => "Discover Sarajevo's history, Mostar's bridge, and Sutjeska National Park. Bosnia allows visa-free entry for many countries up to 90 days. Some nationalities need visa from Bosnian embassies before travel.",
    'MKD' => "Explore Skopje's eclectic architecture and Ohrid Lake's beauty. North Macedonia allows visa-free entry for most Western countries for 90 days. Some nationalities require visa obtained before arrival.",
    'MK' => "Explore Skopje's eclectic architecture and Ohrid Lake's beauty. North Macedonia allows visa-free entry for most Western countries for 90 days. Some nationalities require visa obtained before arrival.",
    'ALB' => "Visit Tirana's colorful buildings, Albanian Riviera, and ancient Butrint. Albania allows visa-free entry for many nationals for stays up to 90 days. Increasingly popular off-the-beaten-path Mediterranean destination.",
    'AL' => "Visit Tirana's colorful buildings, Albanian Riviera, and ancient Butrint. Albania allows visa-free entry for many nationals for stays up to 90 days. Increasingly popular off-the-beaten-path Mediterranean destination.",
    'MNE' => "Discover Kotor's bay, medieval Budva, and Durmitor National Park. Montenegro allows visa-free entry for many countries for 90 days. Popular Adriatic destination with dramatic mountains meeting sea.",
    'ME' => "Discover Kotor's bay, medieval Budva, and Durmitor National Park. Montenegro allows visa-free entry for many countries for 90 days. Popular Adriatic destination with dramatic mountains meeting sea.",
    'UKR' => "Experience Kyiv's golden domes, Lviv's charm, and Carpathian Mountains. Ukraine offers visa-free entry for many nationals for 90 days. Entry regulations subject to change - verify current requirements before travel.",
    'UA' => "Experience Kyiv's golden domes, Lviv's charm, and Carpathian Mountains. Ukraine offers visa-free entry for many nationals for 90 days. Entry regulations subject to change - verify current requirements before travel.",
    'BLR' => "Visit Minsk's Soviet architecture, Mir Castle, and Belovezhskaya Forest. Belarus offers visa-free entry through Minsk airport for 30 days for many nationalities. Land border crossings require visa obtained in advance.",
    'BY' => "Visit Minsk's Soviet architecture, Mir Castle, and Belovezhskaya Forest. Belarus offers visa-free entry through Minsk airport for 30 days for many nationalities. Land border crossings require visa obtained in advance.",
    'MDA' => "Explore Chișinău and wine regions. Moldova allows visa-free entry for many countries for 90 days. One of Europe's least visited countries offering authentic experiences and renowned wines.",
    'MD' => "Explore Chișinău and wine regions. Moldova allows visa-free entry for many countries for 90 days. One of Europe's least visited countries offering authentic experiences and renowned wines.",
    'RUS' => "Discover Moscow's Red Square, St. Petersburg's palaces, and vast landscapes. Russia requires tourist visa for most visitors, obtained through Russian consulates. E-visa available for some regions. Largest country spanning 11 time zones.",
    'LIE' => "Visit Vaduz Castle and Alpine landscapes. Liechtenstein is in Schengen Area - EU/EEA citizens enter freely. Others need Schengen visa. Tiny principality between Switzerland and Austria, German-speaking.",
    'XKX' => "Discover Pristina's youth energy, Peja's monasteries, and mountain landscapes. Kosovo allows visa-free entry for many countries for 90 days. Newest European country with complex recognition status, warm hospitality.",
    
    // Asia - East
    'CHN' => "Experience the Great Wall, Shanghai's skyline, and terracotta warriors. China requires visa for most visitors obtained through embassies or visa centers. 144-hour transit visa-free available for select cities. Apply 3 months before travel.",
    'JPN' => "Visit Tokyo's modernity, Kyoto's temples, and Mount Fuji. Japan allows visa-free entry for many nationals for 90 days tourism. Known for efficiency, safety, and unique culture blending tradition with cutting-edge technology.",
    'KOR' => "Explore Seoul's K-pop culture, Jeju Island, and DMZ. South Korea offers visa-free entry for many countries for 90 days. K-ETA (electronic authorization) required for visa-exempt travelers, costs 10,000 won.",
    'TWN' => "Discover Taipei 101, night markets, and Taroko Gorge. Taiwan allows visa-free entry for many nationals for 90 days. Some countries can apply for e-visa or visa-on-arrival. Vibrant democracy with rich Chinese culture.",
    'HKG' => "Experience Victoria Peak, vibrant markets, and dim sum culture. Hong Kong allows visa-free entry for many nationalities from 7 to 180 days depending on nationality. Special Administrative Region with own entry rules.",
    'MAC' => "Visit casinos, Portuguese colonial heritage, and Ruins of St. Paul's. Macau allows visa-free entry for many nationals for 30-90 days. Special Administrative Region separate from mainland China visa requirements.",
    'MNG' => "Discover Ulaanbaatar, Gobi Desert, and nomadic culture. Mongolia allows visa-free entry for some countries for 30 days. Others need visa from Mongolian embassies. Experience authentic nomadic lifestyle and vast landscapes.",
    'MN' => "Discover Ulaanbaatar, Gobi Desert, and nomadic culture. Mongolia allows visa-free entry for some countries for 30 days. Others need visa from Mongolian embassies. Experience authentic nomadic lifestyle and vast landscapes.",
    'PRK' => "Visit Pyongyang's monuments and DMZ. North Korea requires guided tours only through authorized agencies. Visa obtained through tour operators - independent travel not permitted. Complex entry procedures requiring extensive planning.",
    
    // Asia - Southeast
    'THA' => "Experience Bangkok's temples, Phuket's beaches, and northern hill tribes. Thailand offers visa-free entry for many nationals for 30-60 days depending on arrival method. Can extend stays or apply for tourist visa for longer visits.",
    'VNM' => "Visit Hanoi's old quarter, Halong Bay, and Ho Chi Minh City's history. Vietnam offers e-visa for 90 days for most nationalities at $25. Some countries have visa exemption agreements. Growing popular destination with rich culture.",
    'SGP' => "Discover Marina Bay, Gardens by the Bay, and multicultural cuisine. Singapore allows visa-free entry for most visitors for 30-90 days. One of world's cleanest, safest cities with strict laws and efficient systems.",
    'MYS' => "Explore Kuala Lumpur's Petronas Towers, Penang's food, and Borneo's jungle. Malaysia offers visa-free entry for many nationalities for 30-90 days. E-visa and visa-on-arrival available for countries requiring authorization.",
    'IDN' => "Visit Bali's beaches, Borobudur temple, and Komodo dragons. Indonesia offers visa-free entry for 30 days or visa-on-arrival for 30 days ($35). World's largest archipelago with over 17,000 islands and diverse cultures.",
    'PHL' => "Discover Manila's history, Palawan's beaches, and chocolate hills. Philippines allows visa-free entry for many nationals for 30 days upon arrival. Known for 7,000+ islands, friendly locals, and affordable tropical paradise.",
    'LAO' => "Experience Luang Prabang's temples, Mekong River, and laid-back culture. Laos offers visa-on-arrival for most nationalities for $30-42 at tourist prices. E-visa also available. Least developed but most relaxed Southeast Asian country.",
    'LA' => "Experience Luang Prabang's temples, Mekong River, and laid-back culture. Laos offers visa-on-arrival for most nationalities for $30-42 at tourist prices. E-visa also available. Least developed but most relaxed Southeast Asian country.",
    'KHM' => "Visit Angkor Wat, Phnom Penh's history, and Sihanoukville beaches. Cambodia offers e-visa ($36) or visa-on-arrival ($30) for tourists valid 30 days. One of Southeast Asia's most affordable destinations with ancient wonders.",
    'KH' => "Visit Angkor Wat, Phnom Penh's history, and Sihanoukville beaches. Cambodia offers e-visa ($36) or visa-on-arrival ($30) for tourists valid 30 days. One of Southeast Asia's most affordable destinations with ancient wonders.",
    'MMR' => "Explore Bagan's temples, Inle Lake, and Yangon's Shwedagon Pagoda. Myanmar offers e-visa for tourism for $50 valid 90 days for single entry, stay up to 28 days. Entry regulations subject to change - verify before travel.",
    'MM' => "Explore Bagan's temples, Inle Lake, and Yangon's Shwedagon Pagoda. Myanmar offers e-visa for tourism for $50 valid 90 days for single entry, stay up to 28 days. Entry regulations subject to change - verify before travel.",
    'BRN' => "Discover Bandar Seri Begawan's mosques and pristine rainforests. Brunei allows visa-free entry for many countries for 14-90 days. Wealthy sultanate with strict Islamic laws but welcoming to respectful tourists.",
    'BN' => "Discover Bandar Seri Begawan's mosques and pristine rainforests. Brunei allows visa-free entry for many countries for 14-90 days. Wealthy sultanate with strict Islamic laws but welcoming to respectful tourists.",
    'TLS' => "Visit Dili and stunning dive sites. Timor-Leste offers visa-on-arrival for $30 valid 30 days for most nationalities. Southeast Asia's youngest country with unspoiled nature and Portuguese-influenced culture.",
    
    // Asia - South
    'IND' => "Experience Delhi's monuments, Taj Mahal, Kerala's backwaters, and Himalayas. India requires e-visa ($80-100) or traditional visa for most visitors. E-visa application 4 days before arrival, valid 60 days double entry.",
    'PAK' => "Discover Lahore's Mughal architecture, Karakoram Highway, and friendly hospitality. Pakistan offers e-visa for tourism for many nationalities. Increasingly opening to tourism with simplified visa processes and stunning mountain landscapes.",
    'PK' => "Discover Lahore's Mughal architecture, Karakoram Highway, and friendly hospitality. Pakistan offers e-visa for tourism for many nationalities. Increasingly opening to tourism with simplified visa processes and stunning mountain landscapes.",
    'BGD' => "Visit Dhaka's energy, Sundarbans mangrove forests, and Cox's Bazar beach. Bangladesh offers visa-on-arrival for some countries or e-visa. Traditional visa required for most nationalities through Bangladeshi embassies.",
    'BD' => "Visit Dhaka's energy, Sundarbans mangrove forests, and Cox's Bazar beach. Bangladesh offers visa-on-arrival for some countries or e-visa. Traditional visa required for most nationalities through Bangladeshi embassies.",
    'LKA' => "Explore ancient Sigiriya, tea plantations, and pristine beaches. Sri Lanka requires electronic travel authorization (ETA) for $50-100 depending on nationality, valid 30-180 days. Island paradise recovering from past conflicts.",
    'NPL' => "Gateway to Everest, Kathmandu's temples, and Buddhist culture. Nepal offers visa-on-arrival for most nationalities at Tribhuvan Airport - $30 for 15 days, $50 for 30 days. Trekker's paradise with affordable travel.",
    'BTN' => "Visit Tiger's Nest monastery, pristine nature, and Gross National Happiness. Bhutan requires $200-250 daily tariff including accommodation and guide. Visa arranged through licensed tour operators. Unique sustainable tourism model.",
    'MDV' => "Experience overwater bungalows, crystal waters, and luxury resorts. Maldives offers free 30-day visa-on-arrival for all nationalities. Must have confirmed resort booking or hotel reservation and onward ticket.",
    'AFG' => "Complex entry requirements due to ongoing situation. Afghanistan requires visa obtained through embassies for most visitors. Travel strongly discouraged by most governments. Verify current security situation and restrictions before considering travel.",
    
    // Asia - Central
    'KAZ' => "Discover Almaty's mountains, Astana's futuristic architecture, and Silk Road history. Kazakhstan offers visa-free entry for many countries for 30 days. E-visa available for others. Largest landlocked country with steppes and modern cities.",
    'KZ' => "Discover Almaty's mountains, Astana's futuristic architecture, and Silk Road history. Kazakhstan offers visa-free entry for many countries for 30 days. E-visa available for others. Largest landlocked country with steppes and modern cities.",
    'UZB' => "Visit Samarkand's Registan, Bukhara's ancient city, and Silk Road heritage. Uzbekistan allows visa-free entry for many nationalities for 30 days. E-visa available for $20. Rich in Islamic architecture and Central Asian culture.",
    'UZ' => "Visit Samarkand's Registan, Bukhara's ancient city, and Silk Road heritage. Uzbekistan allows visa-free entry for many nationalities for 30 days. E-visa available for $20. Rich in Islamic architecture and Central Asian culture.",
    'KGZ' => "Experience Bishkek, Issyk-Kul lake, and mountain landscapes. Kyrgyzstan offers visa-free entry for many countries for 60 days. E-visa available for others at $51. Known for hospitality, mountains, and nomadic traditions.",
    'KG' => "Experience Bishkek, Issyk-Kul lake, and mountain landscapes. Kyrgyzstan offers visa-free entry for many countries for 60 days. E-visa available for others at $51. Known for hospitality, mountains, and nomadic traditions.",
    'TJK' => "Explore Dushanbe, Pamir Highway, and mountain villages. Tajikistan offers e-visa for $50 for most nationalities valid 45 days, single entry. Known for dramatic mountains, Silk Road history, and remote trekking.",
    'TJ' => "Explore Dushanbe, Pamir Highway, and mountain villages. Tajikistan offers e-visa for $50 for most nationalities valid 45 days, single entry. Known for dramatic mountains, Silk Road history, and remote trekking.",
    'TKM' => "Visit Ashgabat's marble buildings, ancient Merv, and Darvaza gas crater. Turkmenistan requires visa through state tour operator - permits obtained in advance. One of world's most isolated countries with unique entry procedures.",
    'TM' => "Visit Ashgabat's marble buildings, ancient Merv, and Darvaza gas crater. Turkmenistan requires visa through state tour operator - permits obtained in advance. One of world's most isolated countries with unique entry procedures.",
    
    // Middle East
    'ARE' => "Experience Dubai's skyscrapers, Abu Dhabi's culture, and luxury shopping. UAE offers visa-free entry for many countries for 30-90 days. Visa-on-arrival available for others. Hub for business and tourism with modern infrastructure.",
    'SAU' => "Visit Riyadh's Edge of the World, Jeddah's old town, and AlUla archaeological sites. Saudi Arabia offers e-visa for tourism ($150) for 60+ nationalities. Recently opened to tourism with specific cultural guidelines.",
    'QAT' => "Discover Doha's Museum of Islamic Art, Souq Waqif, and desert activities. Qatar offers visa-free entry for 95+ nationalities for 30-90 days. Small but wealthy Gulf state with cutting-edge architecture.",
    'OMN' => "Explore Muscat's forts, Wahiba Sands desert, and fjords. Oman offers e-visa for tourism for $50 valid 30 days. Known for stunning landscapes, friendly people, and safe travel environment.",
    'KWT' => "Visit Kuwait City's towers, Grand Mosque, and museums. Kuwait requires visa for most visitors - obtained through embassies or sponsors. E-visa available for some nationalities. Wealthy Gulf state with particular entry requirements.",
    'BHR' => "Discover Manama's souqs, pearl diving history, and Formula 1 circuit. Bahrain offers e-visa for tourism online for $29 valid 14 days. Visa-on-arrival available at airport. Liberal Gulf state connected to Saudi Arabia by causeway.",
    'JOR' => "Experience Petra, Dead Sea, Wadi Rum desert, and Roman ruins. Jordan offers visa-on-arrival at airport for 40 JOD or free with Jordan Pass (includes Petra). Many nationalities can obtain visa quite easily.",
    'LBN' => "Visit Beirut's nightlife, Byblos ruins, and cedar forests. Lebanon offers visa-on-arrival for many nationalities for $50-200 depending on nationality, valid 30 days. Verify security situation before travel.",
    'ISR' => "Explore Jerusalem's holy sites, Tel Aviv's beaches, and Dead Sea. Israel allows visa-free entry for many countries for 90 days. Entry stamp issues for travelers to certain countries - verify requirements. Advanced security procedures at entry.",
    'PSE' => "Visit Bethlehem, Jericho, and Ramallah. Palestinian Territories accessed through Israeli or Jordanian borders. Entry depends on Israeli visa policy. Verify current access requirements as they change frequently.",
    'SYR' => "Entry highly restricted due to ongoing conflict. Syria requires visa but travel strongly discouraged by most governments. Complex security situation makes tourism extremely inadvisable. Verify current situation before any consideration of travel.",
    'IRQ' => "Visit Kurdish autonomous region and ancient Mesopotamian sites. Iraq requires visa obtained through embassies. Some areas restricted. Travel warnings in effect due to security concerns - verify safe areas before travel.",
    'IRN' => "Experience Tehran, Isfahan's architecture, and Persian culture. Iran requires visa for most nationalities obtained through embassies or e-visa (limited countries). Americans, British, Canadians need guide throughout stay.",
    'YEM' => "Entry extremely restricted due to ongoing conflict. Yemen requires visa but travel strongly discouraged due to civil war. Most governments advise against all travel. No tourist infrastructure currently available.",
    'TUR' => "Discover Istanbul's mosques, Cappadocia's fairy chimneys, and Mediterranean beaches. Turkey offers e-visa online for many nationalities for $50-60 valid 90 days. Some countries can enter visa-free. Diverse country bridging Europe and Asia.",
    'ARM' => "Visit Yerevan's Republic Square, ancient Geghard monastery, and Mount Ararat views. Armenia offers visa-free entry for many countries for 180 days. E-visa available for others at $6-31. Ancient Christian nation with rich history.",
    'GEO' => "Experience Tbilisi's old town, Caucasus mountains, and famous wine culture. Georgia allows visa-free entry for many nationalities for 365 days. E-visa available for others. Known for hospitality, wine, and stunning mountain landscapes.",
    'GE' => "Experience Tbilisi's old town, Caucasus mountains, and famous wine culture. Georgia allows visa-free entry for many nationalities for 365 days. E-visa available for others. Known for hospitality, wine, and stunning mountain landscapes.",
    'AZE' => "Discover Baku's Flame Towers, ancient Gobustan, and Caspian Sea coast. Azerbaijan offers e-visa for 30 days for $20. Some nationalities eligible for visa-free entry. Oil-rich nation mixing modern architecture with ancient heritage.",
    'AZ' => "Discover Baku's Flame Towers, ancient Gobustan, and Caspian Sea coast. Azerbaijan offers e-visa for 30 days for $20. Some nationalities eligible for visa-free entry. Oil-rich nation mixing modern architecture with ancient heritage.",
    
    // Africa - North
    'EGY' => "Visit pyramids of Giza, Luxor's temples, and Red Sea diving. Egypt offers visa-on-arrival for many countries for $25-30 at major airports, or e-visa online before arrival for the same price, valid 30 days.",
    'MAR' => "Experience Marrakech's souqs, Sahara desert, and coastal Casablanca. Morocco allows visa-free entry for many nationalities for 90 days. Rich culture, diverse landscapes from mountains to beaches, French-Arabic fusion.",
    'TUN' => "Discover Tunis medina, Carthage ruins, and Saharan oases. Tunisia allows visa-free entry for many countries for 90 days. Some nationalities need visa from Tunisian embassies. Mediterranean destination with Roman heritage.",
    'LBY' => "Entry severely restricted due to ongoing conflict and instability. Libya requires visa and letter of invitation but travel strongly discouraged. No organized tourism infrastructure. Most governments advise against all travel.",
    'DZA' => "Visit Algiers' Casbah, Sahara, and Roman ruins at Timgad. Algeria requires visa for all visitors obtained through Algerian embassies with letter of invitation. Complex bureaucratic procedures. Visa fee around 85-110 EUR.",
    'SDN' => "Explore Khartoum's confluence, Nubian pyramids, and Red Sea. Sudan requires visa obtained before arrival through embassies with letter of invitation. Entry regulations complex - verify requirements. Limited tourist infrastructure.",
    'SSD' => "Travel highly restricted to South Sudan due to ongoing conflicts. Visa required through embassies with extensive documentation. World's newest country with limited infrastructure. Travel advisories warn against all but essential travel.",
    
    // Africa - West
    'NGA' => "Visit Lagos' energy, Abuja, and Yankari National Park. Nigeria requires visa obtained before arrival through Nigerian embassies. Visa-on-arrival available at Lagos and Abuja airports for some nationalities. Largest African economy.",
    'GHA' => "Discover Accra's friendly vibe, Cape Coast castles, and Kakum National Park. Ghana offers visa-on-arrival for many countries or e-visa online. Known as 'Gateway to West Africa' with stable democracy and English-speaking.",
    'SEN' => "Experience Dakar's vibrant culture, Saint-Louis' colonial architecture, and pink lake. Senegal allows visa-free entry for many nationalities for 90 days. French-speaking West African hub with teranga (hospitality).",
    'CIV' => "Visit Abidjan's modern skyline, Yamoussoukro's basilica, and beaches. Côte d'Ivoire offers e-visa at $73 for tourism valid 90 days. French-speaking country, world's largest cocoa producer, recovering from past conflicts.",
    'MLI' => "Historic Timbuktu, Djenné's mud mosque, and Niger River. Mali requires visa obtained before arrival. Travel warnings due to security concerns in northern regions. Verify safe areas before planning travel.",
    'BFA' => "Explore Ouagadougou and Bobo-Dioulasso. Burkina Faso requires visa obtained before arrival through embassies or e-visa. Security concerns in some regions - verify current situation before travel.",
    'NER' => "Visit Niamey and Saharan landscapes. Niger requires visa obtained through embassies before arrival. Security concerns in various regions. Limited tourist infrastructure. French-speaking Sahel nation.",
    'TCD' => "Travel to Chad highly restricted due to security situation. Visa required through embassies with invitation letter. Limited infrastructure. Most governments warn against travel. Verify current security situation.",
    'BEN' => "Discover Porto-Novo, Cotonou's markets, and Ouidah's voodoo culture. Benin offers e-visa available online or visa-on-arrival at Cotonou airport. French-speaking country, birthplace of voodoo religion, friendly people.",
    'TGO' => "Visit Lomé's beaches, Koutammakou villages, and voodoo markets. Togo offers e-visa for $50-70 or visa-on-arrival. Small West African nation with interesting culture and straightforward entry procedures.",
    'SLE' => "Explore Freetown's beaches and chimpanzee sanctuaries. Sierra Leone offers visa-on-arrival for some nationalities or through embassies. Recovering from civil war and Ebola, increasing tourism with beautiful coastline.",
    'LBR' => "Visit Monrovia and tropical rainforests. Liberia requires e-visa obtained online before arrival or through embassies for most nationalities. Africa's oldest republic rebuilding after civil wars.",
    'GMB' => "Experience Banjul, river tours, and beaches. The Gambia allows visa-free entry for many countries or visa-on-arrival. English-speaking, smallest mainland African country surrounded by Senegal, known for bird watching.",
    'GNB' => "Explore Bissau and Bijagós Islands. Guinea-Bissau offers visa-on-arrival for most nationalities. Small Portuguese-speaking country with limited tourism infrastructure but beautiful archipelago.",
    'GIN' => "Discover Conakry and Fouta Djallon highlands. Guinea requires visa obtained before arrival through embassies or e-visa. French-speaking country with significant natural resources and developing tourism sector.",
    'CPV' => "Visit Sal's beaches, Santo Antão's hiking, and Mindelo's music scene. Cape Verde offers visa-on-arrival at airport for 30 days or pre-registration online. Portuguese-speaking island nation with year-round sunshine.",
    'MRT' => "Explore Nouakchott and ancient caravan routes. Mauritania offers visa-on-arrival at Nouakchott airport or through embassies. Islamic republic bridging Arab north and sub-Saharan Africa. French and Arabic spoken.",
    
    // Africa - East
    'KEN' => "Experience Nairobi, Masai Mara safaris, and Mombasa beaches. Kenya offers e-visa for $50 online mandatory for almost all nationalities. Must apply before travel. Tourist visa valid 90 days. East Africa safari hub.",
    'TZA' => "Discover Serengeti, Mount Kilimanjaro, and Zanzibar's beaches. Tanzania offers e-visa for $50 or visa-on-arrival at $50 for tourism. Swahili culture, incredible wildlife, and friendly people make this popular safari destination.",
    'UGA' => "Visit Bwindi gorillas, Kampala, and Murchison Falls. Uganda offers e-visa for $50 online or visa-on-arrival at Entebbe airport. East Africa visa allows entry to Kenya, Uganda, Rwanda together. Affordable gorilla trekking.",
    'RWA' => "Experience Kigali's cleanliness, volcano trekking, and mountain gorillas. Rwanda offers visa-on-arrival for all nationalities for $50 valid 30 days. E-visa available online. Africa's safest, cleanest country with remarkable recovery.",
    'BDI' => "Visit Bujumbura and Lake Tanganyika. Burundi requires visa obtained before arrival through embassies or e-visa system. Entry requirements strict - verify current situation. Landlocked East African nation recovering from conflicts.",
    'ETH' => "Visit Addis Ababa, ancient rock churches of Lalibela, and Simien Mountains. Ethiopia offers e-visa for $52 online valid 30-90 days. Coffee origin, ancient civilization, diverse landscapes from deserts to highlands.",
    'SOM' => "Travel to Somalia highly restricted due to security situation. Visa required but most governments strongly advise against all travel. Limited government control, ongoing conflicts make tourism extremely inadvisable.",
    'DJI' => "Discover Djibouti City, salt lakes, and underwater wonders. Djibouti offers visa-on-arrival for most nationalities. Small Horn of Africa nation, strategic location, diving destination with whale sharks.",
    'ERI' => "Entry to Eritrea highly restricted and complicated. Visa required through embassies with letter of invitation. One of world's most isolated countries. Very limited tourist infrastructure. Complex entry procedures.",
    'SSD' => "Travel highly restricted to South Sudan due to ongoing conflicts. Visa required through embassies with extensive documentation. World's newest country with limited infrastructure. Travel advisories warn against all but essential travel.",
    
    // Africa - Southern
    'ZAF' => "Experience Cape Town's Table Mountain, Kruger safaris, and Johannesburg. South Africa allows visa-free entry for many countries for 90 days. Some need visa from South African embassies. Diverse nation with excellent tourist infrastructure.",
    'NAM' => "Visit Etosha National Park, Sossusvlei dunes, and Skeleton Coast. Namibia allows visa-free entry for many nationalities for 90 days. Safe, well-maintained infrastructure, stunning desert landscapes, perfect for self-drive safaris.",
    'BWA' => "Experience Okavango Delta, Chobe elephants, and pristine wilderness. Botswana allows visa-free entry for many countries for 90 days. Some need visa from embassies. High-end safari destination with conservation focus.",
    'ZWE' => "Visit Victoria Falls, Hwange National Park, and Great Zimbabwe ruins. Zimbabwe offers visa-on-arrival for many nationalities - $30-55 depending on nationality for single entry. Double-entry and Uni-Visa available for regional travel.",
    'MOZ' => "Discover Mozambique's pristine beaches, Maputo, and island paradises. Mozambique offers e-visa or visa-on-arrival for around $50. Portuguese-speaking Indian Ocean nation with incredible coastline and recovering from challenges.",
    'MWI' => "Experience Lake Malawi, Lilongwe, and friendly people. Malawi offers visa-on-arrival for many nationalities or e-visa. Known as 'warm heart of Africa,' affordable, safe, English-speaking with beautiful lake and mountains.",
    'ZMB' => "Visit Livingstone, Victoria Falls, and South Luangwa National Park. Zambia offers e-visa for $50 or visa-on-arrival for most nationalities. Kaza Univisa covers Zambia-Zimbabwe. English-speaking, stable, good safari destination.",
    'AGO' => "Explore Luanda's waterfront and Kalandula Falls. Angola requires visa obtained before arrival through Angolan embassies. Tourist visa process can be complex and expensive. Portuguese-speaking oil-rich nation rebuilding after civil war.",
    'LSO' => "Visit Maseru, mountain landscapes, and skiing in Africa. Lesotho allows visa-free entry for many countries for 14 days. Mountain kingdom entirely surrounded by South Africa, unique culture, affordable.",
    'SWZ' => "Discover Mbabane, wildlife reserves, and traditional culture. Eswatini (Swaziland) allows visa-free entry for many countries for 30 days. Small landlocked kingdom between South Africa and Mozambique with preserved traditions.",
    'COM' => "Visit Moroni and the aromatic Comoros archipelago. Comoros offers visa-on-arrival at airport. Small Indian Ocean island nation between Madagascar and Mozambique, French-Arabic culture, undeveloped tourism.",
    'SYC' => "Experience Mahé, Praslin's Vallée de Mai, and pristine beaches. Seychelles offers free visitor's permit on arrival for all nationalities for 3 months. Must have accommodation proof and sufficient funds. Luxury island paradise.",
    'MUS' => "Discover Port Louis, beaches, and multicultural cuisine. Mauritius offers free visa-on-arrival for all nationalities for 60-90 days depending on nationality. Must show return ticket and accommodation. Indian Ocean paradise.",
    'MDG' => "Visit Antananarivo, unique wildlife, and Avenue of the Baobabs. Madagascar offers e-visa online or visa-on-arrival for €35-40 valid 30-60 days. Island with 90% endemic species, biodiversity hotspot, French-Malagasy culture.",
    'REU' => "French overseas department with volcanic landscapes and beaches. As part of France, EU/EEA citizens enter freely. Others need French visa with same requirements as mainland France. Tropical mountain island.",
    'MYT' => "French overseas department in Comoros archipelago. Part of France, so EU/EEA citizens enter freely. Others need French Schengen visa. Predominantly Muslim French territory with unique culture.",
    'IOT' => "British territory with Diego Garcia military base. No public access to islands. Restricted military area with no tourism allowed. Special permits required only for official purposes.",
    
    // Africa - Central
    'CMR' => "Visit Yaoundé, Douala, and Mount Cameroon. Cameroon requires visa obtained before arrival through embassies or online. Visa costs around $100-120. French and English spoken. Diverse geography from rainforests to savannah.",
    'CAF' => "Travel to Central African Republic highly inadvisable due to ongoing security crisis. Visa required but most governments warn against all travel. Extreme instability with no tourist infrastructure.",
    'COG' => "Explore Brazzaville and rainforests. Republic of Congo requires visa obtained before arrival through embassies with letter of invitation. French-speaking, limited tourism infrastructure, visa process can be complex.",
    'COD' => "Democratic Republic of Congo requires visa through embassies. Travel warnings due to conflict in eastern regions. Limited infrastructure. Visa process complex requiring invitation letters. Verify safe areas before travel.",
    'GAB' => "Discover Libreville and pristine rainforests. Gabon offers e-visa before arrival or visa through embassies for around €70. French-speaking, oil-rich nation, 88% rainforest coverage, emerging eco-tourism destination.",
    'GNQ' => "Visit Malabo and Bioko Island. Equatorial Guinea requires visa obtained before arrival through embassies with letter of invitation. Complex visa procedures. Spanish-speaking oil-rich nation with limited tourism.",
    'STP' => "Experience São Tomé's cocoa plantations and beaches. São Tomé and Príncipe offers e-visa online or visa-on-arrival. Portuguese-speaking island nation in Gulf of Guinea, untouched nature, chocolate production.",
    
    // Oceania
    'AUS' => "Visit Sydney Opera House, Great Barrier Reef, and Outback. Australia requires visa or ETA (Electronic Travel Authority) before arrival. eVisitor for EU nationals free, ETA for others $20 AUD. Apply online. Strict biosecurity.",
    'NZL' => "Experience Auckland, Queenstown's adventure sports, and Hobbit film locations. New Zealand offers visa-free entry for many countries for 90 days. Others need NZeTA (electronic authority) for $12-17 NZD before arrival.",
    'FJI' => "Discover Viti Levu, coral reefs, and island hospitality. Fiji allows visa-free entry for most nationalities for 4 months. Free entry permit given on arrival. South Pacific paradise with friendly 'Bula' spirit.",
    'PNG' => "Visit Port Moresby, highland cultures, and WWII history. Papua New Guinea offers e-visa online or visa-on-arrival for tourism at 100 USD valid 60 days. Diverse tribal cultures, last frontiers, English spoken.",
    'NCL' => "French territory featuring stunning lagoons and coral reefs. New Caledonia - EU/EEA citizens enter with ID cards. Others need to verify French territory requirements. Unique blend of Melanesian and French cultures.",
    'SLB' => "Explore Honiara and WWII dive sites. Solomon Islands allows visa-free entry for many countries for 90 days. Visitors' permits issued on arrival free. Melanesian nation with pristine nature and friendly people.",
    'VUT' => "Experience Port Vila, active volcanoes, and blue holes. Vanuatu allows visa-free entry for most nationalities for 30-90 days. Free visitor permit on arrival. Melanesian paradise with unique cultural experiences.",
    'WSM' => "Visit Apia, beaches, and traditional villages. Samoa allows visa-free entry for most countries for 60-90 days. Entry permit given on arrival free. Polynesian culture, 'fa'a Samoa' way of life preserved.",
    'TON' => "Discover Nuku'alofa and whale swimming. Tonga allows visa-free entry for many countries for 31 days. Can extend up to 6 months. Only Polynesian monarchy, traditional culture, humpback whales July-October.",
    'KIR' => "Visit Tarawa and remote atolls. Kiribati offers visa-on-arrival for 30 days. Small fee. One of first countries to see new year, threatened by climate change, authentic Pacific culture.",
    'TUV' => "Experience Funafuti lagoon. Tuvalu allows visa-on-arrival for 30 days for most nationalities. Small fee. One of world's smallest and least visited countries, vulnerable to sea level rise.",
    'NRU' => "Visit world's smallest island republic. Nauru requires visa obtained before arrival through sponsorship. Limited tourism infrastructure. Phosphate island with unique history.",
    'PLW' => "Discover Koror's Rock Islands and world-class diving. Palau allows visa-free entry for most countries for 30 days. Entry fee $50 (since 2018). Environmental fees support conservation. Pristine marine environment.",
    'FSM' => "Visit Pohnpei's ruins and Chuuk's wrecks. Federated States of Micronesia allows visa-free entry for most countries for 30 days. Free entry permits. Four island states with diverse cultures.",
    'MHL' => "Explore Majuro and Bikini Atoll. Marshall Islands allows visa-free entry for many countries for 90 days. Entry permits issued free on arrival. Nuclear testing legacy, now republic.",
    'COK' => "Discover Rarotonga and Aitutaki lagoon. Cook Islands allows visa-free entry for most nationalities for 31 days. Self-governing in free association with New Zealand, Polynesian culture.",
    'ASM' => "U.S. territory in South Pacific. American Samoa - U.S. citizens enter freely. Others need entry permits. Different rules from independent Samoa. Traditional Polynesian culture preserved.",
    'GUM' => "U.S. territory featuring beaches and WWII history. Guam - U.S. citizens enter freely. Some nationalities have visa-waiver. Popular with Asian tourists. Chamorro culture blends with American.",
    'MNP' => "U.S. Commonwealth in Micronesia. Northern Mariana Islands - U.S. citizens enter freely. Visa-waiver for many countries. Saipan popular destination. WWII historic sites.",
    'PYF' => "French overseas collectivity featuring Bora Bora and Tahiti. French Polynesia - EU/EEA citizens enter freely for 90 days. Others generally visa-free for 90 days. Luxury paradise with turquoise lagoons.",
    'WLF' => "French overseas collectivity in Pacific. Wallis and Futuna - EU/EEA citizens enter freely. Others need to verify requirements. Remote, traditional Polynesian culture.",
    'PCN' => "British territory, world's least populated jurisdiction. Pitcairn Islands require special permission. Descendants of Bounty mutineers. Very remote, limited access.",
    'NIU' => "Self-governing in free association with New Zealand. Niue allows visa-free entry for most nationalities for 30 days. One of world's largest coral islands, limited tourism.",
    'TKL' => "New Zealand territory of three atolls. Tokelau requires permission through New Zealand authorities. No airport, reached by boat from Samoa. Very remote.",
];

echo "Processing " . count($countryDescriptions) . " country descriptions...\n\n";

// Prepare update statement
$stmt = $pdo->prepare("
    UPDATE country_translations 
    SET entry_summary = ? 
    WHERE country_id = (SELECT id FROM countries WHERE country_code = ?) 
    AND lang_code = 'en'
");

$updated = 0;
$errors = [];

foreach ($countryDescriptions as $code => $summary) {
    try {
        $stmt->execute([$summary, $code]);
        if ($stmt->rowCount() > 0) {
            $updated++;
            echo "✓ Updated $code\n";
        } else {
            $errors[] = "$code - No matching record found";
        }
    } catch (PDOException $e) {
        $errors[] = "$code - " . $e->getMessage();
    }
}

echo "\n=== Update Complete ===\n";
echo "Successfully updated: $updated countries\n";

if (!empty($errors)) {
    echo "\nErrors or Warnings:\n";
    foreach ($errors as $error) {
        echo "  ⚠ $error\n";
    }
}

echo "\nAll country entry summaries have been updated with unique, interesting content!\n";
