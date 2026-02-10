<?php
require 'includes/config.php';

$stmt = $pdo->prepare('INSERT INTO translations (lang_code, translation_key, translation_value) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)');

$translations = [
    // Country page translations
    ['en', 'about', 'About'],
    ['es', 'about', 'Acerca de'],
    ['zh', 'about', '关于'],
    ['fr', 'about', 'À propos'],
    ['de', 'about', 'Über'],
    ['it', 'about', 'Informazioni'],
    ['ar', 'about', 'حول'],
    
    ['en', 'is_known_for', 'is known for'],
    ['es', 'is_known_for', 'es conocido por'],
    ['zh', 'is_known_for', '以...而闻名'],
    ['fr', 'is_known_for', 'est connu pour'],
    ['de', 'is_known_for', 'ist bekannt für'],
    ['it', 'is_known_for', 'è conosciuto per'],
    ['ar', 'is_known_for', 'معروف بـ'],
    
    ['en', 'visa_requirements', 'Visa Requirements'],
    ['es', 'visa_requirements', 'Requisitos de Visa'],
    ['zh', 'visa_requirements', '签证要求'],
    ['fr', 'visa_requirements', 'Exigences de visa'],
    ['de', 'visa_requirements', 'Visabestimmungen'],
    ['it', 'visa_requirements', 'Requisiti per il visto'],
    ['ar', 'visa_requirements', 'متطلبات التأشيرة'],
    
    ['en', 'requirements_details', 'Requirements Details'],
    ['es', 'requirements_details', 'Detalles de Requisitos'],
    ['zh', 'requirements_details', '要求详情'],
    ['fr', 'requirements_details', 'Détails des exigences'],
    ['de', 'requirements_details', 'Anforderungsdetails'],
    ['it', 'requirements_details', 'Dettagli dei requisiti'],
    ['ar', 'requirements_details', 'تفاصيل المتطلبات'],
    
    ['en', 'travel_tips', 'Travel Tips'],
    ['es', 'travel_tips', 'Consejos de Viaje'],
    ['zh', 'travel_tips', '旅行提示'],
    ['fr', 'travel_tips', 'Conseils de voyage'],
    ['de', 'travel_tips', 'Reisetipps'],
    ['it', 'travel_tips', 'Consigli di viaggio'],
    ['ar', 'travel_tips', 'نصائح السفر'],
    
    ['en', 'major_airports', 'Major Airports'],
    ['es', 'major_airports', 'Aeropuertos Principales'],
    ['zh', 'major_airports', '主要机场'],
    ['fr', 'major_airports', 'Aéroports principaux'],
    ['de', 'major_airports', 'Hauptflughäfen'],
    ['it', 'major_airports', 'Aeroporti principali'],
    ['ar', 'major_airports', 'المطارات الرئيسية'],
    
    ['en', 'visit_website', 'Visit Website'],
    ['es', 'visit_website', 'Visitar Sitio Web'],
    ['zh', 'visit_website', '访问网站'],
    ['fr', 'visit_website', 'Visiter le site'],
    ['de', 'visit_website', 'Website besuchen'],
    ['it', 'visit_website', 'Visita il sito'],
    ['ar', 'visit_website', 'زيارة الموقع'],
    
    ['en', 'quick_facts', 'Quick Facts'],
    ['es', 'quick_facts', 'Datos Rápidos'],
    ['zh', 'quick_facts', '快速事实'],
    ['fr', 'quick_facts', 'Faits rapides'],
    ['de', 'quick_facts', 'Schnellfakten'],
    ['it', 'quick_facts', 'Fatti rapidi'],
    ['ar', 'quick_facts', 'حقائق سريعة'],
    
    ['en', 'capital', 'Capital'],
    ['es', 'capital', 'Capital'],
    ['zh', 'capital', '首都'],
    ['fr', 'capital', 'Capitale'],
    ['de', 'capital', 'Hauptstadt'],
    ['it', 'capital', 'Capitale'],
    ['ar', 'capital', 'العاصمة'],
    
    ['en', 'population', 'Population'],
    ['es', 'population', 'Población'],
    ['zh', 'population', '人口'],
    ['fr', 'population', 'Population'],
    ['de', 'population', 'Bevölkerung'],
    ['it', 'population', 'Popolazione'],
    ['ar', 'population', 'السكان'],
    
    ['en', 'currency', 'Currency'],
    ['es', 'currency', 'Moneda'],
    ['zh', 'currency', '货币'],
    ['fr', 'currency', 'Devise'],
    ['de', 'currency', 'Währung'],
    ['it', 'currency', 'Valuta'],
    ['ar', 'currency', 'العملة'],
    
    ['en', 'time_zone', 'Time Zone'],
    ['es', 'time_zone', 'Zona Horaria'],
    ['zh', 'time_zone', '时区'],
    ['fr', 'time_zone', 'Fuseau horaire'],
    ['de', 'time_zone', 'Zeitzone'],
    ['it', 'time_zone', 'Fuso orario'],
    ['ar', 'time_zone', 'المنطقة الزمنية'],
    
    ['en', 'calling_code', 'Calling Code'],
    ['es', 'calling_code', 'Código de Llamada'],
    ['zh', 'calling_code', '国际区号'],
    ['fr', 'calling_code', 'Indicatif'],
    ['de', 'calling_code', 'Vorwahl'],
    ['it', 'calling_code', 'Prefisso'],
    ['ar', 'calling_code', 'رمز الاتصال'],
    
    ['en', 'plug_type', 'Plug Type'],
    ['es', 'plug_type', 'Tipo de Enchufe'],
    ['zh', 'plug_type', '插头类型'],
    ['fr', 'plug_type', 'Type de prise'],
    ['de', 'plug_type', 'Steckertyp'],
    ['it', 'plug_type', 'Tipo di spina'],
    ['ar', 'plug_type', 'نوع القابس'],
    
    ['en', 'leadership', 'Leadership'],
    ['es', 'leadership', 'Liderazgo'],
    ['zh', 'leadership', '领导层'],
    ['fr', 'leadership', 'Direction'],
    ['de', 'leadership', 'Führung'],
    ['it', 'leadership', 'Leadership'],
    ['ar', 'leadership', 'القيادة'],
    
    ['en', 'leader', 'Leader'],
    ['es', 'leader', 'Líder'],
    ['zh', 'leader', '领导人'],
    ['fr', 'leader', 'Dirigeant'],
    ['de', 'leader', 'Führer'],
    ['it', 'leader', 'Leader'],
    ['ar', 'leader', 'القائد'],
    
    ['en', 'ready_to_apply', 'Ready to Apply for Your Visa?'],
    ['es', 'ready_to_apply', '¿Listo para Solicitar su Visa?'],
    ['zh', 'ready_to_apply', '准备申请签证了吗？'],
    ['fr', 'ready_to_apply', 'Prêt à demander votre visa?'],
    ['de', 'ready_to_apply', 'Bereit, Ihr Visum zu beantragen?'],
    ['it', 'ready_to_apply', 'Pronto per richiedere il visto?'],
    ['ar', 'ready_to_apply', 'هل أنت مستعد لتقديم طلب التأشيرة؟'],
    
    ['en', 'get_official_visa_info', 'Get the latest official visa information and application details'],
    ['es', 'get_official_visa_info', 'Obtenga la información oficial más reciente sobre visas'],
    ['zh', 'get_official_visa_info', '获取最新的官方签证信息和申请详情'],
    ['fr', 'get_official_visa_info', 'Obtenez les dernières informations officielles sur les visas'],
    ['de', 'get_official_visa_info', 'Erhalten Sie die neuesten offiziellen Visa-Informationen'],
    ['it', 'get_official_visa_info', 'Ottieni le ultime informazioni ufficiali sui visti'],
    ['ar', 'get_official_visa_info', 'احصل على أحدث معلومات التأشيرة الرسمية'],
    
    ['en', 'view_details', 'View Details'],
    ['es', 'view_details', 'Ver Detalles'],
    ['zh', 'view_details', '查看详情'],
    ['fr', 'view_details', 'Voir les détails'],
    ['de', 'view_details', 'Details anzeigen'],
    ['it', 'view_details', 'Vedi dettagli'],
    ['ar', 'view_details', 'عرض التفاصيل'],
];

foreach ($translations as $t) {
    $stmt->execute($t);
}

echo "Added " . count($translations) . " translation keys\n";
echo "✅ Translation keys added successfully!\n";
