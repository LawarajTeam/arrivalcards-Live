<?php
/**
 * Add Country Descriptions and Content
 * Adds detailed descriptions, "known for", and travel tips for all countries in 7 languages
 */

require_once 'includes/config.php';

echo "=== Adding Country Content (Descriptions, Known For, Travel Tips) ===\n\n";

// Country content array with comprehensive details
$countryContent = [
    'USA' => [
        'en' => [
            'description' => 'The United States is a vast country stretching from the Atlantic Ocean to the Pacific, offering incredibly diverse landscapes, climates, and cultures. From the skyscrapers of New York City to the beaches of California, from the Grand Canyon to the Great Lakes, America is a land of endless discovery.',
            'known_for' => 'The USA is renowned for its iconic landmarks like the Statue of Liberty, Hollywood, Times Square, and the Golden Gate Bridge. It\'s also famous for its entertainment industry, technology innovation in Silicon Valley, diverse cuisine, national parks, and being a melting pot of cultures from around the world.',
            'travel_tips' => 'The US is huge, so plan your itinerary carefully. Consider regional weather variations. Tipping 15-20% is expected in restaurants. Public transportation is limited outside major cities, so renting a car is often necessary. Book accommodations and attractions in advance, especially in popular cities like New York, San Francisco, and Las Vegas.'
        ],
        'es' => [
            'description' => 'Estados Unidos es un país vasto que se extiende desde el Océano Atlántico hasta el Pacífico, ofreciendo paisajes, climas y culturas increíblemente diversos. Desde los rascacielos de Nueva York hasta las playas de California, desde el Gran Cañón hasta los Grandes Lagos, América es una tierra de descubrimiento sin fin.',
            'known_for' => 'EE.UU. es conocido por sus monumentos icónicos como la Estatua de la Libertad, Hollywood, Times Square y el Puente Golden Gate. También es famoso por su industria del entretenimiento, la innovación tecnológica en Silicon Valley, su cocina diversa, parques nacionales y por ser un crisol de culturas de todo el mundo.',
            'travel_tips' => 'EE.UU. es enorme, así que planifica tu itinerario cuidadosamente. Considera las variaciones climáticas regionales. Se espera una propina del 15-20% en restaurantes. El transporte público es limitado fuera de las grandes ciudades, por lo que alquilar un coche suele ser necesario. Reserva alojamiento y atracciones con anticipación.'
        ],
        'zh' => [
            'description' => '美国是一个广阔的国家，从大西洋延伸到太平洋，拥有极其多样化的地貌、气候和文化。从纽约市的摩天大楼到加利福尼亚的海滩，从大峡谷到五大湖，美国是一片无尽探索的土地。',
            'known_for' => '美国以其标志性地标而闻名，如自由女神像、好莱坞、时代广场和金门大桥。它还以娱乐产业、硅谷的技术创新、多样化的美食、国家公园以及来自世界各地文化的大熔炉而著称。',
            'travel_tips' => '美国很大，所以要仔细规划行程。考虑区域天气变化。餐厅预计给15-20%的小费。大城市外的公共交通有限，因此租车通常是必要的。提前预订住宿和景点，特别是在纽约、旧金山和拉斯维加斯等热门城市。'
        ],
        'fr' => [
            'description' => 'Les États-Unis sont un vaste pays s\'étendant de l\'océan Atlantique au Pacifique, offrant des paysages, des climats et des cultures incroyablement diversifiés. Des gratte-ciel de New York aux plages de Californie, du Grand Canyon aux Grands Lacs, l\'Amérique est une terre de découverte sans fin.',
            'known_for' => 'Les États-Unis sont réputés pour leurs monuments emblématiques comme la Statue de la Liberté, Hollywood, Times Square et le Golden Gate Bridge. Ils sont également célèbres pour leur industrie du divertissement, l\'innovation technologique dans la Silicon Valley, leur cuisine variée, leurs parcs nationaux et leur melting-pot de cultures du monde entier.',
            'travel_tips' => 'Les États-Unis sont immenses, alors planifiez soigneusement votre itinéraire. Considérez les variations climatiques régionales. Un pourboire de 15-20% est attendu dans les restaurants. Les transports publics sont limités en dehors des grandes villes, donc louer une voiture est souvent nécessaire. Réservez l\'hébergement et les attractions à l\'avance.'
        ],
        'de' => [
            'description' => 'Die Vereinigten Staaten sind ein riesiges Land, das sich vom Atlantischen Ozean bis zum Pazifik erstreckt und unglaublich vielfältige Landschaften, Klimazonen und Kulturen bietet. Von den Wolkenkratzern New Yorks bis zu den Stränden Kaliforniens, vom Grand Canyon bis zu den Großen Seen ist Amerika ein Land endloser Entdeckungen.',
            'known_for' => 'Die USA sind bekannt für ihre ikonischen Wahrzeichen wie die Freiheitsstatue, Hollywood, Times Square und die Golden Gate Bridge. Sie sind auch berühmt für ihre Unterhaltungsindustrie, technologische Innovation im Silicon Valley, vielfältige Küche, Nationalparks und als Schmelztiegel der Kulturen aus aller Welt.',
            'travel_tips' => 'Die USA sind riesig, also planen Sie Ihre Route sorgfältig. Berücksichtigen Sie regionale Wettervariationen. 15-20% Trinkgeld wird in Restaurants erwartet. Öffentliche Verkehrsmittel sind außerhalb großer Städte begrenzt, daher ist ein Mietwagen oft notwendig. Buchen Sie Unterkünfte und Attraktionen im Voraus.'
        ],
        'it' => [
            'description' => 'Gli Stati Uniti sono un vasto paese che si estende dall\'Oceano Atlantico al Pacifico, offrendo paesaggi, climi e culture incredibilmente diversificati. Dai grattacieli di New York alle spiagge della California, dal Grand Canyon ai Grandi Laghi, l\'America è una terra di scoperta infinita.',
            'known_for' => 'Gli USA sono rinomati per i loro monumenti iconici come la Statua della Libertà, Hollywood, Times Square e il Golden Gate Bridge. Sono anche famosi per l\'industria dell\'intrattenimento, l\'innovazione tecnologica nella Silicon Valley, la cucina variegata, i parchi nazionali e per essere un melting pot di culture da tutto il mondo.',
            'travel_tips' => 'Gli USA sono enormi, quindi pianifica attentamente il tuo itinerario. Considera le variazioni climatiche regionali. È prevista una mancia del 15-20% nei ristoranti. I trasporti pubblici sono limitati fuori dalle grandi città, quindi noleggiare un\'auto è spesso necessario. Prenota alloggi e attrazioni in anticipo.'
        ],
        'ar' => [
            'description' => 'الولايات المتحدة بلد شاسع يمتد من المحيط الأطلسي إلى المحيط الهادئ، ويقدم مناظر طبيعية ومناخات وثقافات متنوعة بشكل لا يصدق. من ناطحات السحاب في مدينة نيويورك إلى شواطئ كاليفورنيا، ومن جراند كانيون إلى البحيرات العظمى، أمريكا هي أرض الاكتشاف اللامتناهي.',
            'known_for' => 'تشتهر الولايات المتحدة بمعالمها الشهيرة مثل تمثال الحرية وهوليوود وتايمز سكوير وجسر البوابة الذهبية. وهي أيضًا مشهورة بصناعة الترفيه والابتكار التكنولوجي في وادي السيليكون والمطبخ المتنوع والحدائق الوطنية وكونها بوتقة انصهار للثقافات من جميع أنحاء العالم.',
            'travel_tips' => 'الولايات المتحدة ضخمة، لذا خطط لخط سير رحلتك بعناية. ضع في اعتبارك الاختلافات المناخية الإقليمية. يُتوقع إعطاء بقشيش بنسبة 15-20٪ في المطاعم. وسائل النقل العام محدودة خارج المدن الكبرى، لذا فإن استئجار سيارة غالبًا ما يكون ضروريًا. احجز الإقامة والمعالم مسبقًا.'
        ]
    ],
    
    'GBR' => [
        'en' => [
            'description' => 'The United Kingdom, comprising England, Scotland, Wales, and Northern Ireland, is steeped in history and tradition while remaining thoroughly modern. From London\'s world-class museums and theaters to Scotland\'s dramatic highlands, from medieval castles to cutting-edge architecture, the UK offers a perfect blend of old and new.',
            'known_for' => 'The UK is famous for its royal family, historic landmarks like Big Ben, Tower Bridge, and Buckingham Palace, world-renowned universities (Oxford and Cambridge), the English Premier League, afternoon tea, the Beatles, Harry Potter, Shakespeare, and its significant contributions to literature, music, and science.',
            'travel_tips' => 'Weather can be unpredictable—bring layers and a waterproof jacket. The London Underground (Tube) is efficient but can be expensive. Consider getting an Oyster card for London travel. Pubs close earlier than in many countries. Tipping is generally 10-15% in restaurants but not mandatory. Book popular attractions like the Tower of London in advance to avoid queues.'
        ],
        'es' => [
            'description' => 'El Reino Unido, que comprende Inglaterra, Escocia, Gales e Irlanda del Norte, está impregnado de historia y tradición mientras permanece completamente moderno. Desde los museos y teatros de clase mundial de Londres hasta las dramáticas tierras altas de Escocia, desde castillos medievales hasta arquitectura vanguardista, el Reino Unido ofrece una mezcla perfecta de lo antiguo y lo nuevo.',
            'known_for' => 'El Reino Unido es famoso por su familia real, monumentos históricos como el Big Ben, Tower Bridge y Buckingham Palace, universidades de renombre mundial (Oxford y Cambridge), la Premier League inglesa, el té de la tarde, los Beatles, Harry Potter, Shakespeare y sus importantes contribuciones a la literatura, la música y la ciencia.',
            'travel_tips' => 'El clima puede ser impredecible: lleva capas y una chaqueta impermeable. El metro de Londres (Tube) es eficiente pero puede ser caro. Considera obtener una tarjeta Oyster para viajar por Londres. Los pubs cierran más temprano que en muchos países. La propina es generalmente del 10-15% en restaurantes pero no es obligatoria.'
        ],
        'zh' => [
            'description' => '英国由英格兰、苏格兰、威尔士和北爱尔兰组成，历史悠久且传统深厚，同时又完全现代化。从伦敦的世界级博物馆和剧院到苏格兰壮丽的高地，从中世纪城堡到尖端建筑，英国提供了新旧完美融合。',
            'known_for' => '英国以其皇室、历史地标如大本钟、塔桥和白金汉宫、世界知名大学（牛津和剑桥）、英超联赛、下午茶、披头士、哈利波特、莎士比亚以及对文学、音乐和科学的重大贡献而闻名。',
            'travel_tips' => '天气可能难以预测——带上多层衣物和防水夹克。伦敦地铁（Tube）很高效但可能很贵。考虑购买Oyster卡用于伦敦旅行。酒吧关门时间比许多国家早。餐厅小费通常为10-15%，但不是强制性的。提前预订热门景点如伦敦塔以避免排队。'
        ],
        'fr' => [
            'description' => 'Le Royaume-Uni, comprenant l\'Angleterre, l\'Écosse, le Pays de Galles et l\'Irlande du Nord, est imprégné d\'histoire et de tradition tout en restant résolument moderne. Des musées et théâtres de classe mondiale de Londres aux hautes terres spectaculaires d\'Écosse, des châteaux médiévaux à l\'architecture d\'avant-garde, le Royaume-Uni offre un mélange parfait d\'ancien et de nouveau.',
            'known_for' => 'Le Royaume-Uni est célèbre pour sa famille royale, ses monuments historiques comme Big Ben, Tower Bridge et Buckingham Palace, ses universités de renommée mondiale (Oxford et Cambridge), la Premier League anglaise, le thé de l\'après-midi, les Beatles, Harry Potter, Shakespeare et ses contributions importantes à la littérature, la musique et la science.',
            'travel_tips' => 'Le temps peut être imprévisible—apportez des couches et une veste imperméable. Le métro de Londres (Tube) est efficace mais peut être cher. Envisagez d\'obtenir une carte Oyster pour voyager à Londres. Les pubs ferment plus tôt que dans de nombreux pays. Le pourboire est généralement de 10-15% dans les restaurants mais n\'est pas obligatoire.'
        ],
        'de' => [
            'description' => 'Das Vereinigte Königreich, bestehend aus England, Schottland, Wales und Nordirland, ist reich an Geschichte und Tradition und bleibt gleichzeitig vollständig modern. Von Londons Weltklasse-Museen und Theatern bis zu Schottlands dramatischen Highlands, von mittelalterlichen Burgen bis zu modernster Architektur bietet das UK eine perfekte Mischung aus Alt und Neu.',
            'known_for' => 'Das UK ist berühmt für seine königliche Familie, historische Wahrzeichen wie Big Ben, Tower Bridge und Buckingham Palace, weltbekannte Universitäten (Oxford und Cambridge), die englische Premier League, Afternoon Tea, die Beatles, Harry Potter, Shakespeare und seine bedeutenden Beiträge zu Literatur, Musik und Wissenschaft.',
            'travel_tips' => 'Das Wetter kann unberechenbar sein—bringen Sie Schichten und eine wasserdichte Jacke mit. Die Londoner U-Bahn (Tube) ist effizient, aber kann teuer sein. Erwägen Sie eine Oyster Card für Reisen in London. Pubs schließen früher als in vielen Ländern. Trinkgeld ist in Restaurants generell 10-15%, aber nicht obligatorisch.'
        ],
        'it' => [
            'description' => 'Il Regno Unito, composto da Inghilterra, Scozia, Galles e Irlanda del Nord, è intriso di storia e tradizione pur rimanendo completamente moderno. Dai musei e teatri di classe mondiale di Londra alle spettacolari highlands scozzesi, dai castelli medievali all\'architettura all\'avanguardia, il Regno Unito offre una perfetta fusione tra antico e nuovo.',
            'known_for' => 'Il Regno Unito è famoso per la sua famiglia reale, monumenti storici come il Big Ben, il Tower Bridge e Buckingham Palace, università di fama mondiale (Oxford e Cambridge), la Premier League inglese, il tè pomeridiano, i Beatles, Harry Potter, Shakespeare e i suoi significativi contributi a letteratura, musica e scienza.',
            'travel_tips' => 'Il tempo può essere imprevedibile—porta strati e una giacca impermeabile. La metropolitana di Londra (Tube) è efficiente ma può essere costosa. Considera di ottenere una Oyster card per viaggiare a Londra. I pub chiudono prima che in molti paesi. La mancia è generalmente del 10-15% nei ristoranti ma non obbligatoria.'
        ],
        'ar' => [
            'description' => 'المملكة المتحدة، التي تضم إنجلترا واسكتلندا وويلز وأيرلندا الشمالية، غارقة في التاريخ والتقاليد بينما تظل حديثة تمامًا. من المتاحف والمسارح ذات المستوى العالمي في لندن إلى المرتفعات الاسكتلندية الدرامية، من القلاع في العصور الوسطى إلى الهندسة المعمارية المتطورة، تقدم المملكة المتحدة مزيجًا مثاليًا من القديم والجديد.',
            'known_for' => 'تشتهر المملكة المتحدة بعائلتها المالكة والمعالم التاريخية مثل بيج بن وجسر البرج وقصر باكنغهام والجامعات ذات الشهرة العالمية (أكسفورد وكامبريدج) والدوري الإنجليزي الممتاز وشاي بعد الظهر والبيتلز وهاري بوتر وشكسبير ومساهماتها الكبيرة في الأدب والموسيقى والعلوم.',
            'travel_tips' => 'يمكن أن يكون الطقس غير متوقع - أحضر طبقات وسترة مقاومة للماء. مترو أنفاق لندن (Tube) فعال لكنه قد يكون مكلفًا. فكر في الحصول على بطاقة Oyster للسفر في لندن. تغلق الحانات في وقت مبكر عن كثير من البلدان. البقشيش عمومًا 10-15٪ في المطاعم ولكنه ليس إلزاميًا.'
        ]
    ],
    
    'AUS' => [
        'en' => [
            'description' => 'Australia is a land of natural wonders and outdoor adventures, from the Great Barrier Reef to the Outback, from pristine beaches to ancient rainforests. Its cities combine modern sophistication with a relaxed, beach-loving culture. Home to unique wildlife found nowhere else on Earth, Australia offers unforgettable experiences for every type of traveler.',
            'known_for' => 'Australia is renowned for its iconic landmarks like the Sydney Opera House and Harbour Bridge, the Great Barrier Reef, Uluru (Ayers Rock), stunning beaches, unique wildlife (kangaroos, koalas, platypuses), world-class wine regions, the Outback, surfing culture, and its laid-back, friendly people.',
            'travel_tips' => 'Australia is massive—distances between cities are huge, so plan flights accordingly. The sun is intense; always use sunscreen (SPF 30+). Tipping is not expected but appreciated. Swimming is only safe at patrolled beaches between the flags. Be aware of dangerous wildlife and follow safety signs. Book tours to the Great Barrier Reef and Uluru well in advance.'
        ],
        'es' => [
            'description' => 'Australia es una tierra de maravillas naturales y aventuras al aire libre, desde la Gran Barrera de Coral hasta el Outback, desde playas vírgenes hasta bosques tropicales antiguos. Sus ciudades combinan sofisticación moderna con una cultura relajada y amante de la playa. Hogar de vida silvestre única que no se encuentra en ningún otro lugar de la Tierra, Australia ofrece experiencias inolvidables para todo tipo de viajero.',
            'known_for' => 'Australia es conocida por sus monumentos icónicos como la Ópera de Sídney y el Puente del Puerto, la Gran Barrera de Coral, Uluru (Ayers Rock), playas impresionantes, vida silvestre única (canguros, koalas, ornitorrincos), regiones vinícolas de clase mundial, el Outback, cultura del surf y su gente amigable y relajada.',
            'travel_tips' => 'Australia es enorme: las distancias entre ciudades son enormes, así que planifica los vuelos en consecuencia. El sol es intenso; siempre usa protector solar (FPS 30+). No se espera propina pero se aprecia. Nadar solo es seguro en playas patrulladas entre las banderas. Ten cuidado con la vida silvestre peligrosa y sigue las señales de seguridad.'
        ],
        'zh' => [
            'description' => '澳大利亚是一片自然奇观和户外探险之地，从大堡礁到内陆地区，从原始海滩到古老的雨林。其城市将现代精致与轻松的海滩文化相结合。作为地球上独一无二的野生动物的家园，澳大利亚为每种类型的旅行者提供难忘的体验。',
            'known_for' => '澳大利亚以其标志性地标而闻名，如悉尼歌剧院和海港大桥、大堡礁、乌鲁鲁（艾尔斯岩）、令人惊叹的海滩、独特的野生动物（袋鼠、考拉、鸭嘴兽）、世界级葡萄酒产区、内陆地区、冲浪文化以及其悠闲友好的人民。',
            'travel_tips' => '澳大利亚很大——城市之间的距离很大，所以相应地计划航班。阳光强烈；始终使用防晒霜（SPF 30+）。不期望小费但受欢迎。只有在旗帜之间的巡逻海滩游泳才安全。注意危险的野生动物并遵循安全标志。提前预订大堡礁和乌鲁鲁的旅游。'
        ],
        'fr' => [
            'description' => 'L\'Australie est une terre de merveilles naturelles et d\'aventures en plein air, de la Grande Barrière de Corail à l\'Outback, des plages immaculées aux forêts tropicales anciennes. Ses villes combinent sophistication moderne et culture décontractée et amoureuse de la plage. Abritant une faune unique introuvable ailleurs sur Terre, l\'Australie offre des expériences inoubliables pour tous les types de voyageurs.',
            'known_for' => 'L\'Australie est réputée pour ses monuments emblématiques comme l\'Opéra de Sydney et le Harbour Bridge, la Grande Barrière de Corail, Uluru (Ayers Rock), des plages magnifiques, une faune unique (kangourous, koalas, ornithorynques), des régions viticoles de classe mondiale, l\'Outback, la culture du surf et ses habitants détendus et amicaux.',
            'travel_tips' => 'L\'Australie est immense—les distances entre les villes sont énormes, alors planifiez les vols en conséquence. Le soleil est intense; utilisez toujours de la crème solaire (FPS 30+). Le pourboire n\'est pas attendu mais apprécié. La natation n\'est sûre que sur les plages surveillées entre les drapeaux. Soyez conscient de la faune dangereuse et suivez les panneaux de sécurité.'
        ],
        'de' => [
            'description' => 'Australien ist ein Land voller Naturwunder und Outdoor-Abenteuer, vom Great Barrier Reef bis zum Outback, von unberührten Stränden bis zu uralten Regenwäldern. Seine Städte vereinen moderne Raffinesse mit einer entspannten, strandliebenden Kultur. Als Heimat einzigartiger Wildtiere, die nirgendwo sonst auf der Erde zu finden sind, bietet Australien unvergessliche Erlebnisse für jeden Reisetyp.',
            'known_for' => 'Australien ist bekannt für seine ikonischen Wahrzeichen wie das Sydney Opera House und die Harbour Bridge, das Great Barrier Reef, Uluru (Ayers Rock), atemberaubende Strände, einzigartige Tierwelt (Kängurus, Koalas, Schnabeltiere), Weltklasse-Weinregionen, das Outback, Surfkultur und seine entspannten, freundlichen Menschen.',
            'travel_tips' => 'Australien ist riesig—die Entfernungen zwischen Städten sind enorm, also planen Sie Flüge entsprechend. Die Sonne ist intensiv; verwenden Sie immer Sonnencreme (LSF 30+). Trinkgeld wird nicht erwartet, aber geschätzt. Schwimmen ist nur an überwachten Stränden zwischen den Flaggen sicher. Seien Sie sich der gefährlichen Tierwelt bewusst und befolgen Sie Sicherheitsschilder.'
        ],
        'it' => [
            'description' => 'L\'Australia è una terra di meraviglie naturali e avventure all\'aperto, dalla Grande Barriera Corallina all\'Outback, dalle spiagge incontaminate alle antiche foreste pluviali. Le sue città combinano sofisticatezza moderna con una cultura rilassata e amante della spiaggia. Casa di fauna selvatica unica che non si trova da nessun\'altra parte sulla Terra, l\'Australia offre esperienze indimenticabili per ogni tipo di viaggiatore.',
            'known_for' => 'L\'Australia è rinomata per i suoi monumenti iconici come l\'Opera House di Sydney e l\'Harbour Bridge, la Grande Barriera Corallina, Uluru (Ayers Rock), spiagge mozzafiato, fauna selvatica unica (canguri, koala, ornitorinchi), regioni vinicole di classe mondiale, l\'Outback, la cultura del surf e le sue persone rilassate e amichevoli.',
            'travel_tips' => 'L\'Australia è enorme—le distanze tra le città sono enormi, quindi pianifica i voli di conseguenza. Il sole è intenso; usa sempre la crema solare (SPF 30+). La mancia non è prevista ma apprezzata. Nuotare è sicuro solo nelle spiagge sorvegliate tra le bandiere. Sii consapevole della fauna selvatica pericolosa e segui i segnali di sicurezza.'
        ],
        'ar' => [
            'description' => 'أستراليا هي أرض العجائب الطبيعية والمغامرات في الهواء الطلق، من الحاجز المرجاني العظيم إلى المناطق النائية، من الشواطئ النقية إلى الغابات المطيرة القديمة. تجمع مدنها بين التطور الحديث والثقافة المسترخية المحبة للشاطئ. موطن الحياة البرية الفريدة التي لا توجد في أي مكان آخر على الأرض، تقدم أستراليا تجارب لا تُنسى لكل نوع من المسافرين.',
            'known_for' => 'تشتهر أستراليا بمعالمها الشهيرة مثل دار أوبرا سيدني وجسر الميناء والحاجز المرجاني العظيم وأولورو (صخرة آيرز) والشواطئ المذهلة والحياة البرية الفريدة (الكنغر والكوالا وخلد الماء) ومناطق النبيذ ذات المستوى العالمي والمناطق النائية وثقافة ركوب الأمواج وشعبها الودود والمسترخي.',
            'travel_tips' => 'أستراليا ضخمة - المسافات بين المدن هائلة، لذا خطط للرحلات الجوية وفقًا لذلك. الشمس شديدة؛ استخدم دائمًا واقي الشمس (SPF 30+). البقشيش غير متوقع ولكنه موضع تقدير. السباحة آمنة فقط في الشواطئ المراقبة بين الأعلام. كن على دراية بالحياة البرية الخطيرة واتبع علامات السلامة.'
        ]
    ],
    
    'CAN' => [
        'en' => [
            'description' => 'Canada is the world\'s second-largest country, offering vast wilderness, cosmopolitan cities, and breathtaking natural beauty. From the Rocky Mountains to Niagara Falls, from the French charm of Quebec to the Pacific Coast, Canada provides diverse experiences across its ten provinces and three territories. Known for its multicultural society, friendly people, and pristine nature.',
            'known_for' => 'Canada is famous for Niagara Falls, the Rocky Mountains, CN Tower, pristine national parks (Banff, Jasper), maple syrup, ice hockey, poutine, multiculturalism, the Northern Lights, French-speaking Quebec City, Vancouver\'s natural beauty, and being one of the world\'s most welcoming and peaceful nations.',
            'travel_tips' => 'Canada is huge—factor in travel time between cities. Weather varies dramatically by region and season; winters can be extremely cold. The official languages are English and French. Tipping 15-20% is customary in restaurants. National parks require entry passes. Book Rocky Mountain accommodations months in advance. The best times to visit are summer (June-August) or fall for foliage (September-October).'
        ],
        'es' => [
            'description' => 'Canadá es el segundo país más grande del mundo, ofreciendo vastos desiertos, ciudades cosmopolitas y una belleza natural impresionante. Desde las Montañas Rocosas hasta las Cataratas del Niágara, desde el encanto francés de Quebec hasta la costa del Pacífico, Canadá ofrece experiencias diversas en sus diez provincias y tres territorios. Conocido por su sociedad multicultural, gente amable y naturaleza prístina.',
            'known_for' => 'Canadá es famoso por las Cataratas del Niágara, las Montañas Rocosas, la Torre CN, parques nacionales prístinos (Banff, Jasper), jarabe de arce, hockey sobre hielo, poutine, multiculturalismo, las Auroras Boreales, la ciudad de Quebec de habla francesa, la belleza natural de Vancouver y ser una de las naciones más acogedoras y pacíficas del mundo.',
            'travel_tips' => 'Canadá es enorme: tenga en cuenta el tiempo de viaje entre ciudades. El clima varía dramáticamente según la región y la estación; los inviernos pueden ser extremadamente fríos. Los idiomas oficiales son inglés y francés. Es costumbre dar propina del 15-20% en restaurantes. Los parques nacionales requieren pases de entrada. Reserve alojamiento en las Montañas Rocosas con meses de anticipación.'
        ],
        'zh' => [
            'description' => '加拿大是世界第二大国家，提供广阔的荒野、国际化的城市和令人惊叹的自然美景。从落基山脉到尼亚加拉大瀑布，从魁北克的法国魅力到太平洋海岸，加拿大在其十个省和三个地区提供多样化的体验。以其多元文化社会、友好的人民和原始的大自然而闻名。',
            'known_for' => '加拿大以尼亚加拉大瀑布、落基山脉、CN塔、原始国家公园（班夫、贾斯珀）、枫糖浆、冰球、肉汁奶酪薯条、多元文化主义、北极光、说法语的魁北克市、温哥华的自然美景以及成为世界上最热情和和平的国家之一而闻名。',
            'travel_tips' => '加拿大很大——考虑城市之间的旅行时间。天气因地区和季节而异；冬季可能极冷。官方语言是英语和法语。餐厅习惯给15-20%的小费。国家公园需要入场通行证。提前几个月预订落基山脉的住宿。最佳游览时间是夏季（6月至8月）或秋季赏枫（9月至10月）。'
        ],
        'fr' => [
            'description' => 'Le Canada est le deuxième plus grand pays du monde, offrant de vastes étendues sauvages, des villes cosmopolites et une beauté naturelle à couper le souffle. Des Montagnes Rocheuses aux chutes du Niagara, du charme français du Québec à la côte du Pacifique, le Canada offre des expériences diverses dans ses dix provinces et trois territoires. Connu pour sa société multiculturelle, ses habitants sympathiques et sa nature immaculée.',
            'known_for' => 'Le Canada est célèbre pour les chutes du Niagara, les Montagnes Rocheuses, la Tour CN, les parcs nationaux immaculés (Banff, Jasper), le sirop d\'érable, le hockey sur glace, la poutine, le multiculturalisme, les aurores boréales, la ville de Québec francophone, la beauté naturelle de Vancouver et le fait d\'être l\'une des nations les plus accueillantes et paisibles du monde.',
            'travel_tips' => 'Le Canada est immense—tenez compte du temps de voyage entre les villes. Le temps varie considérablement selon la région et la saison; les hivers peuvent être extrêmement froids. Les langues officielles sont l\'anglais et le français. Un pourboire de 15-20% est habituel dans les restaurants. Les parcs nationaux nécessitent des laissez-passer. Réservez l\'hébergement dans les Rocheuses des mois à l\'avance.'
        ],
        'de' => [
            'description' => 'Kanada ist das zweitgrößte Land der Welt und bietet weite Wildnis, kosmopolitische Städte und atemberaubende Naturschönheit. Von den Rocky Mountains bis zu den Niagarafällen, vom französischen Charme Quebecs bis zur Pazifikküste bietet Kanada vielfältige Erlebnisse in seinen zehn Provinzen und drei Territorien. Bekannt für seine multikulturelle Gesellschaft, freundliche Menschen und unberührte Natur.',
            'known_for' => 'Kanada ist berühmt für die Niagarafälle, die Rocky Mountains, den CN Tower, unberührte Nationalparks (Banff, Jasper), Ahornsirup, Eishockey, Poutine, Multikulturalismus, die Nordlichter, die französischsprachige Stadt Quebec, Vancouvers natürliche Schönheit und dafür, eine der gastfreundlichsten und friedlichsten Nationen der Welt zu sein.',
            'travel_tips' => 'Kanada ist riesig—berücksichtigen Sie die Reisezeit zwischen Städten. Das Wetter variiert dramatisch nach Region und Jahreszeit; Winter können extrem kalt sein. Die offiziellen Sprachen sind Englisch und Französisch. 15-20% Trinkgeld sind in Restaurants üblich. Nationalparks erfordern Eintrittskarten. Buchen Sie Unterkünfte in den Rocky Mountains Monate im Voraus.'
        ],
        'it' => [
            'description' => 'Il Canada è il secondo paese più grande del mondo, offrendo vaste distese selvagge, città cosmopolite e una bellezza naturale mozzafiato. Dalle Montagne Rocciose alle Cascate del Niagara, dal fascino francese del Quebec alla costa del Pacifico, il Canada offre esperienze diverse nelle sue dieci province e tre territori. Noto per la sua società multiculturale, la gente amichevole e la natura incontaminata.',
            'known_for' => 'Il Canada è famoso per le Cascate del Niagara, le Montagne Rocciose, la CN Tower, i parchi nazionali incontaminati (Banff, Jasper), lo sciroppo d\'acero, l\'hockey su ghiaccio, la poutine, il multiculturalismo, l\'Aurora Boreale, la città del Quebec francofona, la bellezza naturale di Vancouver e per essere una delle nazioni più accoglienti e pacifiche del mondo.',
            'travel_tips' => 'Il Canada è enorme—considera il tempo di viaggio tra le città. Il tempo varia drammaticamente per regione e stagione; gli inverni possono essere estremamente freddi. Le lingue ufficiali sono inglese e francese. È consuetudine lasciare una mancia del 15-20% nei ristoranti. I parchi nazionali richiedono pass di ingresso. Prenota l\'alloggio nelle Montagne Rocciose con mesi di anticipo.'
        ],
        'ar' => [
            'description' => 'كندا هي ثاني أكبر دولة في العالم، وتوفر برية شاسعة ومدن عالمية وجمال طبيعي خلاب. من جبال روكي إلى شلالات نياجرا، من سحر كيبيك الفرنسي إلى ساحل المحيط الهادئ، توفر كندا تجارب متنوعة عبر مقاطعاتها العشر وأقاليمها الثلاثة. معروفة بمجتمعها المتعدد الثقافات والناس الودودين والطبيعة النقية.',
            'known_for' => 'تشتهر كندا بشلالات نياجرا وجبال روكي وبرج CN والحدائق الوطنية البكر (بانف وجاسبر) وشراب القيقب وهوكي الجليد وبوتين والتعددية الثقافية والأضواء الشمالية ومدينة كيبيك الناطقة بالفرنسية وجمال فانكوفر الطبيعي وكونها واحدة من أكثر الدول ترحيباً وسلاماً في العالم.',
            'travel_tips' => 'كندا ضخمة - ضع في اعتبارك وقت السفر بين المدن. يختلف الطقس بشكل كبير حسب المنطقة والموسم؛ يمكن أن تكون فصول الشتاء باردة للغاية. اللغتان الرسميتان هما الإنجليزية والفرنسية. من المعتاد إعطاء بقشيش بنسبة 15-20٪ في المطاعم. تتطلب الحدائق الوطنية تصاريح دخول. احجز أماكن الإقامة في جبال روكي قبل أشهر.'
        ]
    ]
];

// Prepare statements
$stmtInsert = $pdo->prepare("INSERT INTO country_details (country_id, lang_code, description, known_for, travel_tips) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE description = VALUES(description), known_for = VALUES(known_for), travel_tips = VALUES(travel_tips)");

$added = 0;

// Get all countries
$stmt = $pdo->query("SELECT countries.id, countries.country_code FROM countries ORDER BY countries.id");
$countries = $stmt->fetchAll();

foreach ($countries as $country) {
    $countryCode = $country['country_code'];
    $countryId = $country['id'];
    
    // Check if we have custom content for this country
    if (isset($countryContent[$countryCode])) {
        $content = $countryContent[$countryCode];
        
        // Add content for all 7 languages
        foreach ($content as $langCode => $texts) {
            $stmtInsert->execute([
                $countryId,
                $langCode,
                $texts['description'],
                $texts['known_for'],
                $texts['travel_tips']
            ]);
            $added++;
            echo "✓ Added content for $countryCode in $langCode\n";
        }
    } else {
        // Add generic content for countries without custom content
        $genericContent = [
            'en' => [
                'description' => "Discover the unique charm and beauty of this destination, with its rich culture, fascinating history, and welcoming people.",
                'known_for' => "This country offers a blend of cultural heritage, natural wonders, and authentic experiences that make it a memorable destination for travelers.",
                'travel_tips' => "Research visa requirements in advance. Respect local customs and traditions. Learn a few basic phrases in the local language. Check health and safety advisories before traveling."
            ],
            'es' => [
                'description' => "Descubre el encanto único y la belleza de este destino, con su rica cultura, historia fascinante y gente acogedora.",
                'known_for' => "Este país ofrece una mezcla de patrimonio cultural, maravillas naturales y experiencias auténticas que lo convierten en un destino memorable para los viajeros.",
                'travel_tips' => "Investiga los requisitos de visa con anticipación. Respeta las costumbres y tradiciones locales. Aprende algunas frases básicas en el idioma local. Verifica los avisos de salud y seguridad antes de viajar."
            ],
            'zh' => [
                'description' => "探索这个目的地的独特魅力和美丽，拥有丰富的文化、迷人的历史和热情好客的人民。",
                'known_for' => "这个国家融合了文化遗产、自然奇观和真实体验，使其成为旅行者难忘的目的地。",
                'travel_tips' => "提前研究签证要求。尊重当地习俗和传统。学习一些当地语言的基本短语。旅行前检查健康和安全建议。"
            ],
            'fr' => [
                'description' => "Découvrez le charme unique et la beauté de cette destination, avec sa riche culture, son histoire fascinante et ses habitants accueillants.",
                'known_for' => "Ce pays offre un mélange de patrimoine culturel, de merveilles naturelles et d\'expériences authentiques qui en font une destination mémorable pour les voyageurs.",
                'travel_tips' => "Renseignez-vous à l\'avance sur les exigences en matière de visa. Respectez les coutumes et traditions locales. Apprenez quelques phrases de base dans la langue locale. Vérifiez les avis de santé et de sécurité avant de voyager."
            ],
            'de' => [
                'description' => "Entdecken Sie den einzigartigen Charme und die Schönheit dieses Reiseziels mit seiner reichen Kultur, faszinierenden Geschichte und gastfreundlichen Menschen.",
                'known_for' => "Dieses Land bietet eine Mischung aus kulturellem Erbe, Naturwundern und authentischen Erlebnissen, die es zu einem unvergesslichen Reiseziel für Reisende machen.",
                'travel_tips' => "Informieren Sie sich im Voraus über Visabestimmungen. Respektieren Sie lokale Bräuche und Traditionen. Lernen Sie einige grundlegende Ausdrücke in der Landessprache. Überprüfen Sie Gesundheits- und Sicherheitshinweise vor der Reise."
            ],
            'it' => [
                'description' => "Scopri il fascino unico e la bellezza di questa destinazione, con la sua ricca cultura, storia affascinante e persone accoglienti.",
                'known_for' => "Questo paese offre un mix di patrimonio culturale, meraviglie naturali ed esperienze autentiche che lo rendono una destinazione memorabile per i viaggiatori.",
                'travel_tips' => "Ricerca i requisiti per il visto in anticipo. Rispetta le usanze e le tradizioni locali. Impara alcune frasi di base nella lingua locale. Verifica gli avvisi su salute e sicurezza prima di viaggiare."
            ],
            'ar' => [
                'description' => "اكتشف السحر الفريد وجمال هذه الوجهة، مع ثقافتها الغنية وتاريخها الرائع والناس المرحبين.",
                'known_for' => "يقدم هذا البلد مزيجًا من التراث الثقافي والعجائب الطبيعية والتجارب الأصيلة التي تجعله وجهة لا تُنسى للمسافرين.",
                'travel_tips' => "ابحث عن متطلبات التأشيرة مسبقًا. احترم العادات والتقاليد المحلية. تعلم بعض العبارات الأساسية باللغة المحلية. تحقق من التنبيهات الصحية والأمنية قبل السفر."
            ]
        ];
        
        foreach ($genericContent as $langCode => $texts) {
            $stmtInsert->execute([
                $countryId,
                $langCode,
                $texts['description'],
                $texts['known_for'],
                $texts['travel_tips']
            ]);
        }
        $added++;
    }
}

echo "\n✅ Added content for $added countries in 7 languages\n";
echo "✅ Country descriptions, 'known for', and travel tips are now available!\n";
