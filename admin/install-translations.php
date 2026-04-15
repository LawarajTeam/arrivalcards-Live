<?php
/**
 * Admin - Install Missing Translations
 * Adds translations for Compare Passports and Best Passports pages
 * Safe to run multiple times (uses ON DUPLICATE KEY UPDATE)
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$pageTitle = 'Install Translations';
$results = [];
$installed = 0;
$updated = 0;
$errors = [];

// All translations to install
function getTranslations() {
    return [
        // =============================================
        // COMPARE PASSPORTS (cp_) - English
        // =============================================
        ['en', 'cp_page_title', 'Compare Passports - Visa Requirements Side by Side | Arrival Cards', 'compare'],
        ['en', 'cp_page_description', 'Compare visa requirements between two passports side-by-side. See which passport gives you better travel freedom and easier access to countries worldwide.', 'compare'],
        ['en', 'cp_page_keywords', 'compare passports, passport comparison, visa requirements comparison, travel freedom, passport ranking, visa free countries comparison', 'compare'],
        ['en', 'cp_hero_title', 'Compare Passports', 'compare'],
        ['en', 'cp_hero_subtitle', 'See side-by-side visa requirements and travel freedom differences', 'compare'],
        ['en', 'cp_howto_title', 'How to Compare Passports', 'compare'],
        ['en', 'cp_howto_intro', 'Use the dropdowns above to select two passports and click Compare to see a full side-by-side visa breakdown.', 'compare'],
        ['en', 'cp_howto_step1_title', 'Choose Your First Passport', 'compare'],
        ['en', 'cp_howto_step1_text', 'Select the first passport from the left-hand dropdown.', 'compare'],
        ['en', 'cp_howto_step2_title', 'Choose a Second Passport', 'compare'],
        ['en', 'cp_howto_step2_text', 'Select a different passport from the right-hand dropdown to compare it against the first.', 'compare'],
        ['en', 'cp_howto_step3_title', 'Click Compare', 'compare'],
        ['en', 'cp_howto_step3_text', 'Hit the Compare Passports button to instantly see visa requirements, costs, and processing times for every destination — side by side.', 'compare'],
        ['en', 'cp_howto_tip_label', 'Tip:', 'compare'],
        ['en', 'cp_howto_tip_text', 'Only passports with bilateral visa data in our database are available for comparison.', 'compare'],
        ['en', 'cp_first_passport', 'First Passport', 'compare'],
        ['en', 'cp_second_passport', 'Second Passport', 'compare'],
        ['en', 'cp_select_passport', 'Select a passport...', 'compare'],
        ['en', 'cp_destinations', 'destinations', 'compare'],
        ['en', 'cp_compare_btn', 'Compare Passports', 'compare'],
        ['en', 'cp_visa_free', 'Visa Free', 'compare'],
        ['en', 'cp_visa_on_arrival', 'Visa on Arrival', 'compare'],
        ['en', 'cp_easy_access', 'Easy Access', 'compare'],
        ['en', 'cp_evisa_required', 'eVisa Required', 'compare'],
        ['en', 'cp_visa_required', 'Visa Required', 'compare'],
        ['en', 'cp_avg_cost', 'Avg. Cost', 'compare'],
        ['en', 'cp_avg_processing', 'Avg. Processing', 'compare'],
        ['en', 'cp_days', 'days', 'compare'],
        ['en', 'cp_better_access', 'Better Access', 'compare'],
        ['en', 'cp_equal_access', 'Equal Access', 'compare'],
        ['en', 'cp_dest_comparison', 'Destination-by-Destination Comparison', 'compare'],
        ['en', 'cp_th_destination', 'Destination', 'compare'],
        ['en', 'cp_th_advantage', 'Advantage', 'compare'],
        ['en', 'cp_better', 'Better', 'compare'],

        // COMPARE PASSPORTS - Spanish
        ['es', 'cp_page_title', 'Comparar Pasaportes - Requisitos de Visa | Arrival Cards', 'compare'],
        ['es', 'cp_page_description', 'Compare los requisitos de visa entre dos pasaportes. Vea qué pasaporte le da mejor libertad de viaje.', 'compare'],
        ['es', 'cp_page_keywords', 'comparar pasaportes, comparación de pasaportes, requisitos de visa, libertad de viaje', 'compare'],
        ['es', 'cp_hero_title', 'Comparar Pasaportes', 'compare'],
        ['es', 'cp_hero_subtitle', 'Vea las diferencias en requisitos de visa y libertad de viaje', 'compare'],
        ['es', 'cp_howto_title', 'Cómo Comparar Pasaportes', 'compare'],
        ['es', 'cp_howto_intro', 'Compare los requisitos de visa entre dos pasaportes en pocos pasos.', 'compare'],
        ['es', 'cp_howto_step1_title', 'Seleccione el Primer Pasaporte', 'compare'],
        ['es', 'cp_howto_step1_text', 'Elija su primer pasaporte del menú desplegable.', 'compare'],
        ['es', 'cp_howto_step2_title', 'Seleccione el Segundo Pasaporte', 'compare'],
        ['es', 'cp_howto_step2_text', 'Elija un segundo pasaporte para comparar con el primero.', 'compare'],
        ['es', 'cp_howto_step3_title', 'Ver Resultados', 'compare'],
        ['es', 'cp_howto_step3_text', 'Haga clic en Comparar para ver los requisitos de visa detallados para cada destino.', 'compare'],
        ['es', 'cp_howto_tip_label', 'Consejo:', 'compare'],
        ['es', 'cp_howto_tip_text', 'Puede comparar cualquier par de pasaportes que tengan datos de visa en nuestra base de datos.', 'compare'],
        ['es', 'cp_first_passport', 'Primer Pasaporte', 'compare'],
        ['es', 'cp_second_passport', 'Segundo Pasaporte', 'compare'],
        ['es', 'cp_select_passport', 'Seleccione un pasaporte...', 'compare'],
        ['es', 'cp_destinations', 'destinos', 'compare'],
        ['es', 'cp_compare_btn', 'Comparar Pasaportes', 'compare'],
        ['es', 'cp_visa_free', 'Sin Visa', 'compare'],
        ['es', 'cp_visa_on_arrival', 'Visa a la Llegada', 'compare'],
        ['es', 'cp_easy_access', 'Acceso Fácil', 'compare'],
        ['es', 'cp_evisa_required', 'eVisa Requerida', 'compare'],
        ['es', 'cp_visa_required', 'Visa Requerida', 'compare'],
        ['es', 'cp_avg_cost', 'Costo Prom.', 'compare'],
        ['es', 'cp_avg_processing', 'Procesamiento Prom.', 'compare'],
        ['es', 'cp_days', 'días', 'compare'],
        ['es', 'cp_better_access', 'Mejor Acceso', 'compare'],
        ['es', 'cp_equal_access', 'Acceso Igual', 'compare'],
        ['es', 'cp_dest_comparison', 'Comparación Destino por Destino', 'compare'],
        ['es', 'cp_th_destination', 'Destino', 'compare'],
        ['es', 'cp_th_advantage', 'Ventaja', 'compare'],
        ['es', 'cp_better', 'Mejor', 'compare'],

        // COMPARE PASSPORTS - Chinese
        ['zh', 'cp_page_title', '护照对比 - 签证要求对比 | Arrival Cards', 'compare'],
        ['zh', 'cp_page_description', '并排比较两本护照的签证要求，查看哪本护照提供更好的旅行自由度。', 'compare'],
        ['zh', 'cp_page_keywords', '护照对比, 签证要求对比, 旅行自由度, 护照排名', 'compare'],
        ['zh', 'cp_hero_title', '护照对比', 'compare'],
        ['zh', 'cp_hero_subtitle', '并排查看签证要求和旅行自由度差异', 'compare'],
        ['zh', 'cp_howto_title', '如何比较护照', 'compare'],
        ['zh', 'cp_howto_intro', '只需几步即可比较两本护照的签证要求。', 'compare'],
        ['zh', 'cp_howto_step1_title', '选择第一本护照', 'compare'],
        ['zh', 'cp_howto_step1_text', '从下拉菜单中选择您的第一本护照。', 'compare'],
        ['zh', 'cp_howto_step2_title', '选择第二本护照', 'compare'],
        ['zh', 'cp_howto_step2_text', '选择要与第一本护照进行比较的第二本护照。', 'compare'],
        ['zh', 'cp_howto_step3_title', '查看结果', 'compare'],
        ['zh', 'cp_howto_step3_text', '点击比较按钮，查看每个目的地的详细签证要求。', 'compare'],
        ['zh', 'cp_howto_tip_label', '提示：', 'compare'],
        ['zh', 'cp_howto_tip_text', '您可以比较数据库中有签证数据的任意两本护照。', 'compare'],
        ['zh', 'cp_first_passport', '第一本护照', 'compare'],
        ['zh', 'cp_second_passport', '第二本护照', 'compare'],
        ['zh', 'cp_select_passport', '选择护照...', 'compare'],
        ['zh', 'cp_destinations', '个目的地', 'compare'],
        ['zh', 'cp_compare_btn', '比较护照', 'compare'],
        ['zh', 'cp_visa_free', '免签', 'compare'],
        ['zh', 'cp_visa_on_arrival', '落地签', 'compare'],
        ['zh', 'cp_easy_access', '简易入境', 'compare'],
        ['zh', 'cp_evisa_required', '需要电子签', 'compare'],
        ['zh', 'cp_visa_required', '需要签证', 'compare'],
        ['zh', 'cp_avg_cost', '平均费用', 'compare'],
        ['zh', 'cp_avg_processing', '平均处理时间', 'compare'],
        ['zh', 'cp_days', '天', 'compare'],
        ['zh', 'cp_better_access', '更好的入境条件', 'compare'],
        ['zh', 'cp_equal_access', '入境条件相同', 'compare'],
        ['zh', 'cp_dest_comparison', '逐一目的地对比', 'compare'],
        ['zh', 'cp_th_destination', '目的地', 'compare'],
        ['zh', 'cp_th_advantage', '优势', 'compare'],
        ['zh', 'cp_better', '更好', 'compare'],

        // COMPARE PASSPORTS - French
        ['fr', 'cp_page_title', 'Comparer les Passeports - Exigences de Visa | Arrival Cards', 'compare'],
        ['fr', 'cp_page_description', 'Comparez les exigences de visa entre deux passeports côte à côte.', 'compare'],
        ['fr', 'cp_page_keywords', 'comparer passeports, comparaison passeports, exigences visa, liberté de voyage', 'compare'],
        ['fr', 'cp_hero_title', 'Comparer les Passeports', 'compare'],
        ['fr', 'cp_hero_subtitle', 'Comparez les exigences de visa et les différences de liberté de voyage', 'compare'],
        ['fr', 'cp_howto_title', 'Comment Comparer les Passeports', 'compare'],
        ['fr', 'cp_howto_intro', 'Comparez les exigences de visa entre deux passeports en quelques étapes.', 'compare'],
        ['fr', 'cp_howto_step1_title', 'Sélectionnez le Premier Passeport', 'compare'],
        ['fr', 'cp_howto_step1_text', 'Choisissez votre premier passeport dans le menu déroulant.', 'compare'],
        ['fr', 'cp_howto_step2_title', 'Sélectionnez le Deuxième Passeport', 'compare'],
        ['fr', 'cp_howto_step2_text', 'Choisissez un deuxième passeport à comparer avec le premier.', 'compare'],
        ['fr', 'cp_howto_step3_title', 'Voir les Résultats', 'compare'],
        ['fr', 'cp_howto_step3_text', 'Cliquez sur Comparer pour voir les exigences de visa détaillées pour chaque destination.', 'compare'],
        ['fr', 'cp_howto_tip_label', 'Astuce :', 'compare'],
        ['fr', 'cp_howto_tip_text', 'Vous pouvez comparer toute paire de passeports ayant des données de visa dans notre base de données.', 'compare'],
        ['fr', 'cp_first_passport', 'Premier Passeport', 'compare'],
        ['fr', 'cp_second_passport', 'Deuxième Passeport', 'compare'],
        ['fr', 'cp_select_passport', 'Sélectionnez un passeport...', 'compare'],
        ['fr', 'cp_destinations', 'destinations', 'compare'],
        ['fr', 'cp_compare_btn', 'Comparer les Passeports', 'compare'],
        ['fr', 'cp_visa_free', 'Sans Visa', 'compare'],
        ['fr', 'cp_visa_on_arrival', "Visa à l'Arrivée", 'compare'],
        ['fr', 'cp_easy_access', 'Accès Facile', 'compare'],
        ['fr', 'cp_evisa_required', 'eVisa Requis', 'compare'],
        ['fr', 'cp_visa_required', 'Visa Requis', 'compare'],
        ['fr', 'cp_avg_cost', 'Coût Moyen', 'compare'],
        ['fr', 'cp_avg_processing', 'Traitement Moyen', 'compare'],
        ['fr', 'cp_days', 'jours', 'compare'],
        ['fr', 'cp_better_access', 'Meilleur Accès', 'compare'],
        ['fr', 'cp_equal_access', 'Accès Égal', 'compare'],
        ['fr', 'cp_dest_comparison', 'Comparaison Destination par Destination', 'compare'],
        ['fr', 'cp_th_destination', 'Destination', 'compare'],
        ['fr', 'cp_th_advantage', 'Avantage', 'compare'],
        ['fr', 'cp_better', 'Mieux', 'compare'],

        // COMPARE PASSPORTS - German
        ['de', 'cp_page_title', 'Reisepässe Vergleichen - Visa-Anforderungen | Arrival Cards', 'compare'],
        ['de', 'cp_page_description', 'Vergleichen Sie die Visa-Anforderungen zweier Reisepässe nebeneinander.', 'compare'],
        ['de', 'cp_page_keywords', 'Reisepässe vergleichen, Passvergleich, Visa-Anforderungen, Reisefreiheit', 'compare'],
        ['de', 'cp_hero_title', 'Reisepässe Vergleichen', 'compare'],
        ['de', 'cp_hero_subtitle', 'Visa-Anforderungen und Reisefreiheit im Vergleich', 'compare'],
        ['de', 'cp_howto_title', 'So Vergleichen Sie Reisepässe', 'compare'],
        ['de', 'cp_howto_intro', 'Vergleichen Sie die Visa-Anforderungen zweier Reisepässe in wenigen Schritten.', 'compare'],
        ['de', 'cp_howto_step1_title', 'Ersten Reisepass Wählen', 'compare'],
        ['de', 'cp_howto_step1_text', 'Wählen Sie Ihren ersten Reisepass aus dem Dropdown-Menü.', 'compare'],
        ['de', 'cp_howto_step2_title', 'Zweiten Reisepass Wählen', 'compare'],
        ['de', 'cp_howto_step2_text', 'Wählen Sie einen zweiten Reisepass zum Vergleich.', 'compare'],
        ['de', 'cp_howto_step3_title', 'Ergebnisse Ansehen', 'compare'],
        ['de', 'cp_howto_step3_text', 'Klicken Sie auf Vergleichen, um detaillierte Visa-Anforderungen für jedes Reiseziel zu sehen.', 'compare'],
        ['de', 'cp_howto_tip_label', 'Tipp:', 'compare'],
        ['de', 'cp_howto_tip_text', 'Sie können jedes Paar von Reisepässen vergleichen, für die Visa-Daten vorliegen.', 'compare'],
        ['de', 'cp_first_passport', 'Erster Reisepass', 'compare'],
        ['de', 'cp_second_passport', 'Zweiter Reisepass', 'compare'],
        ['de', 'cp_select_passport', 'Reisepass auswählen...', 'compare'],
        ['de', 'cp_destinations', 'Reiseziele', 'compare'],
        ['de', 'cp_compare_btn', 'Reisepässe Vergleichen', 'compare'],
        ['de', 'cp_visa_free', 'Visumfrei', 'compare'],
        ['de', 'cp_visa_on_arrival', 'Visum bei Ankunft', 'compare'],
        ['de', 'cp_easy_access', 'Leichter Zugang', 'compare'],
        ['de', 'cp_evisa_required', 'eVisum Erforderlich', 'compare'],
        ['de', 'cp_visa_required', 'Visum Erforderlich', 'compare'],
        ['de', 'cp_avg_cost', 'Durchschn. Kosten', 'compare'],
        ['de', 'cp_avg_processing', 'Durchschn. Bearbeitung', 'compare'],
        ['de', 'cp_days', 'Tage', 'compare'],
        ['de', 'cp_better_access', 'Besserer Zugang', 'compare'],
        ['de', 'cp_equal_access', 'Gleicher Zugang', 'compare'],
        ['de', 'cp_dest_comparison', 'Vergleich nach Reiseziel', 'compare'],
        ['de', 'cp_th_destination', 'Reiseziel', 'compare'],
        ['de', 'cp_th_advantage', 'Vorteil', 'compare'],
        ['de', 'cp_better', 'Besser', 'compare'],

        // COMPARE PASSPORTS - Italian
        ['it', 'cp_page_title', 'Confronta Passaporti - Requisiti Visto | Arrival Cards', 'compare'],
        ['it', 'cp_page_description', 'Confronta i requisiti del visto tra due passaporti fianco a fianco.', 'compare'],
        ['it', 'cp_page_keywords', 'confronta passaporti, confronto passaporti, requisiti visto, libertà di viaggio', 'compare'],
        ['it', 'cp_hero_title', 'Confronta Passaporti', 'compare'],
        ['it', 'cp_hero_subtitle', 'Confronta i requisiti del visto e le differenze nella libertà di viaggio', 'compare'],
        ['it', 'cp_howto_title', 'Come Confrontare i Passaporti', 'compare'],
        ['it', 'cp_howto_intro', 'Confronta i requisiti del visto tra due passaporti in pochi passaggi.', 'compare'],
        ['it', 'cp_howto_step1_title', 'Seleziona il Primo Passaporto', 'compare'],
        ['it', 'cp_howto_step1_text', 'Scegli il primo passaporto dal menu a discesa.', 'compare'],
        ['it', 'cp_howto_step2_title', 'Seleziona il Secondo Passaporto', 'compare'],
        ['it', 'cp_howto_step2_text', 'Scegli un secondo passaporto da confrontare con il primo.', 'compare'],
        ['it', 'cp_howto_step3_title', 'Visualizza i Risultati', 'compare'],
        ['it', 'cp_howto_step3_text', 'Clicca su Confronta per vedere i requisiti dettagliati del visto per ogni destinazione.', 'compare'],
        ['it', 'cp_howto_tip_label', 'Suggerimento:', 'compare'],
        ['it', 'cp_howto_tip_text', 'Puoi confrontare qualsiasi coppia di passaporti che disponga di dati sui visti nel nostro database.', 'compare'],
        ['it', 'cp_first_passport', 'Primo Passaporto', 'compare'],
        ['it', 'cp_second_passport', 'Secondo Passaporto', 'compare'],
        ['it', 'cp_select_passport', 'Seleziona un passaporto...', 'compare'],
        ['it', 'cp_destinations', 'destinazioni', 'compare'],
        ['it', 'cp_compare_btn', 'Confronta Passaporti', 'compare'],
        ['it', 'cp_visa_free', 'Senza Visto', 'compare'],
        ['it', 'cp_visa_on_arrival', "Visto all'Arrivo", 'compare'],
        ['it', 'cp_easy_access', 'Accesso Facile', 'compare'],
        ['it', 'cp_evisa_required', 'eVisa Richiesto', 'compare'],
        ['it', 'cp_visa_required', 'Visto Richiesto', 'compare'],
        ['it', 'cp_avg_cost', 'Costo Medio', 'compare'],
        ['it', 'cp_avg_processing', 'Elaborazione Media', 'compare'],
        ['it', 'cp_days', 'giorni', 'compare'],
        ['it', 'cp_better_access', 'Accesso Migliore', 'compare'],
        ['it', 'cp_equal_access', 'Accesso Uguale', 'compare'],
        ['it', 'cp_dest_comparison', 'Confronto Destinazione per Destinazione', 'compare'],
        ['it', 'cp_th_destination', 'Destinazione', 'compare'],
        ['it', 'cp_th_advantage', 'Vantaggio', 'compare'],
        ['it', 'cp_better', 'Migliore', 'compare'],

        // COMPARE PASSPORTS - Arabic
        ['ar', 'cp_page_title', 'مقارنة جوازات السفر - متطلبات التأشيرة | Arrival Cards', 'compare'],
        ['ar', 'cp_page_description', 'قارن متطلبات التأشيرة بين جوازي سفر جنبًا إلى جنب.', 'compare'],
        ['ar', 'cp_page_keywords', 'مقارنة جوازات السفر, متطلبات التأشيرة, حرية السفر', 'compare'],
        ['ar', 'cp_hero_title', 'مقارنة جوازات السفر', 'compare'],
        ['ar', 'cp_hero_subtitle', 'عرض متطلبات التأشيرة والاختلافات في حرية السفر جنبًا إلى جنب', 'compare'],
        ['ar', 'cp_howto_title', 'كيفية مقارنة جوازات السفر', 'compare'],
        ['ar', 'cp_howto_intro', 'قارن متطلبات التأشيرة بين جوازي سفر في خطوات بسيطة.', 'compare'],
        ['ar', 'cp_howto_step1_title', 'اختر جواز السفر الأول', 'compare'],
        ['ar', 'cp_howto_step1_text', 'اختر جواز سفرك الأول من القائمة المنسدلة.', 'compare'],
        ['ar', 'cp_howto_step2_title', 'اختر جواز السفر الثاني', 'compare'],
        ['ar', 'cp_howto_step2_text', 'اختر جواز سفر ثانٍ للمقارنة مع الأول.', 'compare'],
        ['ar', 'cp_howto_step3_title', 'عرض النتائج', 'compare'],
        ['ar', 'cp_howto_step3_text', 'انقر على مقارنة لعرض متطلبات التأشيرة التفصيلية لكل وجهة.', 'compare'],
        ['ar', 'cp_howto_tip_label', 'نصيحة:', 'compare'],
        ['ar', 'cp_howto_tip_text', 'يمكنك مقارنة أي زوج من جوازات السفر التي تتوفر لها بيانات تأشيرة في قاعدة بياناتنا.', 'compare'],
        ['ar', 'cp_first_passport', 'جواز السفر الأول', 'compare'],
        ['ar', 'cp_second_passport', 'جواز السفر الثاني', 'compare'],
        ['ar', 'cp_select_passport', 'اختر جواز سفر...', 'compare'],
        ['ar', 'cp_destinations', 'وجهات', 'compare'],
        ['ar', 'cp_compare_btn', 'مقارنة جوازات السفر', 'compare'],
        ['ar', 'cp_visa_free', 'بدون تأشيرة', 'compare'],
        ['ar', 'cp_visa_on_arrival', 'تأشيرة عند الوصول', 'compare'],
        ['ar', 'cp_easy_access', 'دخول سهل', 'compare'],
        ['ar', 'cp_evisa_required', 'تأشيرة إلكترونية مطلوبة', 'compare'],
        ['ar', 'cp_visa_required', 'تأشيرة مطلوبة', 'compare'],
        ['ar', 'cp_avg_cost', 'متوسط التكلفة', 'compare'],
        ['ar', 'cp_avg_processing', 'متوسط المعالجة', 'compare'],
        ['ar', 'cp_days', 'أيام', 'compare'],
        ['ar', 'cp_better_access', 'وصول أفضل', 'compare'],
        ['ar', 'cp_equal_access', 'وصول متساوٍ', 'compare'],
        ['ar', 'cp_dest_comparison', 'مقارنة وجهة بوجهة', 'compare'],
        ['ar', 'cp_th_destination', 'الوجهة', 'compare'],
        ['ar', 'cp_th_advantage', 'الميزة', 'compare'],
        ['ar', 'cp_better', 'أفضل', 'compare'],

        // =============================================
        // BEST PASSPORTS (bp_) - English
        // =============================================
        ['en', 'bp_page_title', 'Best Passports in the World - Passport Power Ranking | Arrival Cards', 'best-passports'],
        ['en', 'bp_page_description', 'Discover the most powerful passports in the world ranked by visa-free access. Compare passport strength and travel freedom.', 'best-passports'],
        ['en', 'bp_page_keywords', 'best passports, passport ranking, most powerful passports, visa free access, passport index, travel freedom, passport power', 'best-passports'],
        ['en', 'bp_hero_title', 'Best Passports in the World', 'best-passports'],
        ['en', 'bp_hero_subtitle', 'Passports ranked by visa-free travel access and global mobility', 'best-passports'],
        ['en', 'bp_passports_ranked', 'Passports Ranked', 'best-passports'],
        ['en', 'bp_number_one_passport', '#1 Passport', 'best-passports'],
        ['en', 'bp_avg_visa_free', 'Avg. Visa-Free', 'best-passports'],
        ['en', 'bp_about_title', 'About the Passport Ranking', 'best-passports'],
        ['en', 'bp_about_text', 'Our passport ranking is based on the number of destinations each passport holder can access without a prior visa. This includes visa-free entry, visa on arrival, and eTA/eVisa destinations. The ranking reflects real-world travel freedom based on verified bilateral visa data.', 'best-passports'],
        ['en', 'bp_th_rank', 'Rank', 'best-passports'],
        ['en', 'bp_th_country', 'Country', 'best-passports'],
        ['en', 'bp_th_visa_free_score', 'Visa-Free Score', 'best-passports'],
        ['en', 'bp_th_visa_free_dest', 'Visa-Free Destinations', 'best-passports'],
        ['en', 'bp_th_details', 'Details', 'best-passports'],
        ['en', 'bp_destinations', 'destinations', 'best-passports'],
        ['en', 'bp_visa_free', 'visa-free', 'best-passports'],
        ['en', 'bp_free', 'Free', 'best-passports'],
        ['en', 'bp_voa', 'VoA', 'best-passports'],
        ['en', 'bp_evisa', 'eVisa', 'best-passports'],
        ['en', 'bp_req', 'Required', 'best-passports'],
        ['en', 'bp_view_details', 'View Details', 'best-passports'],
        ['en', 'bp_howto_title', 'How is the Ranking Calculated?', 'best-passports'],
        ['en', 'bp_howto_text', 'The passport ranking is calculated based on bilateral visa requirements data in our database. Each passport is scored by the number of countries its holders can visit without needing a traditional visa. Visa-free, visa on arrival, and eVisa destinations are all counted toward the score.', 'best-passports'],
        ['en', 'bp_sources_title', 'Sources & Methodology', 'best-passports'],
        ['en', 'bp_data_sources', 'Data Sources', 'best-passports'],
        ['en', 'bp_source_henley', 'Global passport power ranking', 'best-passports'],
        ['en', 'bp_source_iata', 'International Air Transport Association travel data', 'best-passports'],
        ['en', 'bp_source_govt', 'Official government immigration websites', 'best-passports'],
        ['en', 'bp_source_smartraveller', 'Australian Government travel advisory', 'best-passports'],
        ['en', 'bp_methodology', 'Methodology', 'best-passports'],
        ['en', 'bp_method_1', 'Score = Visa-free + Visa on Arrival + eVisa destinations', 'best-passports'],
        ['en', 'bp_method_2', 'Data verified against official government sources', 'best-passports'],
        ['en', 'bp_method_3', 'Rankings updated regularly as policies change', 'best-passports'],
        ['en', 'bp_method_4', 'Only active bilateral visa agreements are counted', 'best-passports'],
        ['en', 'bp_method_5', 'Transit visas and restricted entry are excluded', 'best-passports'],
        ['en', 'bp_disclaimer', 'Disclaimer: Rankings are based on available data and may not reflect the very latest policy changes. Always verify with official sources before traveling.', 'best-passports'],
        ['en', 'bp_last_updated', 'Last updated', 'best-passports'],

        // BEST PASSPORTS - Spanish
        ['es', 'bp_page_title', 'Mejores Pasaportes del Mundo - Ranking | Arrival Cards', 'best-passports'],
        ['es', 'bp_page_description', 'Descubra los pasaportes más poderosos del mundo clasificados por acceso sin visa.', 'best-passports'],
        ['es', 'bp_page_keywords', 'mejores pasaportes, ranking pasaportes, pasaportes más poderosos, acceso sin visa', 'best-passports'],
        ['es', 'bp_hero_title', 'Mejores Pasaportes del Mundo', 'best-passports'],
        ['es', 'bp_hero_subtitle', 'Pasaportes clasificados por acceso de viaje sin visa y movilidad global', 'best-passports'],
        ['es', 'bp_passports_ranked', 'Pasaportes Clasificados', 'best-passports'],
        ['es', 'bp_number_one_passport', 'Pasaporte #1', 'best-passports'],
        ['es', 'bp_avg_visa_free', 'Prom. Sin Visa', 'best-passports'],
        ['es', 'bp_about_title', 'Sobre el Ranking de Pasaportes', 'best-passports'],
        ['es', 'bp_about_text', 'Nuestro ranking de pasaportes se basa en el número de destinos a los que cada titular puede acceder sin visa previa. Incluye entrada sin visa, visa a la llegada y destinos con eVisa.', 'best-passports'],
        ['es', 'bp_th_rank', 'Posición', 'best-passports'],
        ['es', 'bp_th_country', 'País', 'best-passports'],
        ['es', 'bp_th_visa_free_score', 'Puntuación Sin Visa', 'best-passports'],
        ['es', 'bp_th_visa_free_dest', 'Destinos Sin Visa', 'best-passports'],
        ['es', 'bp_th_details', 'Detalles', 'best-passports'],
        ['es', 'bp_destinations', 'destinos', 'best-passports'],
        ['es', 'bp_visa_free', 'sin visa', 'best-passports'],
        ['es', 'bp_free', 'Libre', 'best-passports'],
        ['es', 'bp_voa', 'VLL', 'best-passports'],
        ['es', 'bp_evisa', 'eVisa', 'best-passports'],
        ['es', 'bp_req', 'Requerida', 'best-passports'],
        ['es', 'bp_view_details', 'Ver Detalles', 'best-passports'],
        ['es', 'bp_howto_title', '¿Cómo se Calcula el Ranking?', 'best-passports'],
        ['es', 'bp_howto_text', 'El ranking se calcula en base a los datos de requisitos de visa bilaterales. Cada pasaporte se puntúa por el número de países que sus titulares pueden visitar sin visa tradicional.', 'best-passports'],
        ['es', 'bp_sources_title', 'Fuentes y Metodología', 'best-passports'],
        ['es', 'bp_data_sources', 'Fuentes de Datos', 'best-passports'],
        ['es', 'bp_source_henley', 'Ranking global de poder de pasaportes', 'best-passports'],
        ['es', 'bp_source_iata', 'Datos de viaje de la Asociación Internacional de Transporte Aéreo', 'best-passports'],
        ['es', 'bp_source_govt', 'Sitios web oficiales de inmigración gubernamental', 'best-passports'],
        ['es', 'bp_source_smartraveller', 'Asesoría de viaje del Gobierno Australiano', 'best-passports'],
        ['es', 'bp_methodology', 'Metodología', 'best-passports'],
        ['es', 'bp_method_1', 'Puntuación = Sin visa + Visa a la llegada + Destinos eVisa', 'best-passports'],
        ['es', 'bp_method_2', 'Datos verificados con fuentes gubernamentales oficiales', 'best-passports'],
        ['es', 'bp_method_3', 'Rankings actualizados regularmente según cambien las políticas', 'best-passports'],
        ['es', 'bp_method_4', 'Solo se cuentan acuerdos bilaterales de visa activos', 'best-passports'],
        ['es', 'bp_method_5', 'Se excluyen visas de tránsito y entradas restringidas', 'best-passports'],
        ['es', 'bp_disclaimer', 'Aviso: Los rankings se basan en datos disponibles y pueden no reflejar los últimos cambios. Verifique siempre con fuentes oficiales antes de viajar.', 'best-passports'],
        ['es', 'bp_last_updated', 'Última actualización', 'best-passports'],

        // BEST PASSPORTS - Chinese
        ['zh', 'bp_page_title', '全球最佳护照排名 | Arrival Cards', 'best-passports'],
        ['zh', 'bp_page_description', '发现全球最强大的护照排名，按免签入境国家数量排列。', 'best-passports'],
        ['zh', 'bp_page_keywords', '最佳护照, 护照排名, 最强护照, 免签入境, 旅行自由', 'best-passports'],
        ['zh', 'bp_hero_title', '全球最佳护照排名', 'best-passports'],
        ['zh', 'bp_hero_subtitle', '按免签旅行和全球流动性排列的护照排名', 'best-passports'],
        ['zh', 'bp_passports_ranked', '已排名护照', 'best-passports'],
        ['zh', 'bp_number_one_passport', '第一名护照', 'best-passports'],
        ['zh', 'bp_avg_visa_free', '平均免签', 'best-passports'],
        ['zh', 'bp_about_title', '关于护照排名', 'best-passports'],
        ['zh', 'bp_about_text', '我们的护照排名基于每本护照持有人无需事先签证即可进入的目的地数量。包括免签入境、落地签和电子签目的地。', 'best-passports'],
        ['zh', 'bp_th_rank', '排名', 'best-passports'],
        ['zh', 'bp_th_country', '国家', 'best-passports'],
        ['zh', 'bp_th_visa_free_score', '免签分数', 'best-passports'],
        ['zh', 'bp_th_visa_free_dest', '免签目的地', 'best-passports'],
        ['zh', 'bp_th_details', '详情', 'best-passports'],
        ['zh', 'bp_destinations', '个目的地', 'best-passports'],
        ['zh', 'bp_visa_free', '免签', 'best-passports'],
        ['zh', 'bp_free', '免签', 'best-passports'],
        ['zh', 'bp_voa', '落地签', 'best-passports'],
        ['zh', 'bp_evisa', '电子签', 'best-passports'],
        ['zh', 'bp_req', '需签证', 'best-passports'],
        ['zh', 'bp_view_details', '查看详情', 'best-passports'],
        ['zh', 'bp_howto_title', '排名如何计算？', 'best-passports'],
        ['zh', 'bp_howto_text', '护照排名基于双边签证要求数据计算。每本护照按其持有人无需传统签证即可访问的国家数量评分。', 'best-passports'],
        ['zh', 'bp_sources_title', '来源与方法论', 'best-passports'],
        ['zh', 'bp_data_sources', '数据来源', 'best-passports'],
        ['zh', 'bp_source_henley', '全球护照实力排名', 'best-passports'],
        ['zh', 'bp_source_iata', '国际航空运输协会旅行数据', 'best-passports'],
        ['zh', 'bp_source_govt', '各国政府官方移民网站', 'best-passports'],
        ['zh', 'bp_source_smartraveller', '澳大利亚政府旅行建议', 'best-passports'],
        ['zh', 'bp_methodology', '方法论', 'best-passports'],
        ['zh', 'bp_method_1', '分数 = 免签 + 落地签 + 电子签目的地', 'best-passports'],
        ['zh', 'bp_method_2', '数据经官方政府来源验证', 'best-passports'],
        ['zh', 'bp_method_3', '排名随政策变化定期更新', 'best-passports'],
        ['zh', 'bp_method_4', '仅统计有效的双边签证协议', 'best-passports'],
        ['zh', 'bp_method_5', '不包括过境签证和受限入境', 'best-passports'],
        ['zh', 'bp_disclaimer', '免责声明：排名基于可用数据，可能未反映最新政策变化。旅行前请务必核实。', 'best-passports'],
        ['zh', 'bp_last_updated', '最后更新', 'best-passports'],

        // BEST PASSPORTS - French
        ['fr', 'bp_page_title', 'Meilleurs Passeports au Monde - Classement | Arrival Cards', 'best-passports'],
        ['fr', 'bp_page_description', 'Découvrez les passeports les plus puissants au monde classés par accès sans visa.', 'best-passports'],
        ['fr', 'bp_page_keywords', 'meilleurs passeports, classement passeports, passeports puissants, accès sans visa', 'best-passports'],
        ['fr', 'bp_hero_title', 'Meilleurs Passeports au Monde', 'best-passports'],
        ['fr', 'bp_hero_subtitle', 'Passeports classés par accès de voyage sans visa et mobilité mondiale', 'best-passports'],
        ['fr', 'bp_passports_ranked', 'Passeports Classés', 'best-passports'],
        ['fr', 'bp_number_one_passport', 'Passeport #1', 'best-passports'],
        ['fr', 'bp_avg_visa_free', 'Moy. Sans Visa', 'best-passports'],
        ['fr', 'bp_about_title', 'À Propos du Classement', 'best-passports'],
        ['fr', 'bp_about_text', "Notre classement est basé sur le nombre de destinations accessibles sans visa préalable. Cela inclut l'entrée sans visa, le visa à l'arrivée et les destinations eVisa.", 'best-passports'],
        ['fr', 'bp_th_rank', 'Rang', 'best-passports'],
        ['fr', 'bp_th_country', 'Pays', 'best-passports'],
        ['fr', 'bp_th_visa_free_score', 'Score Sans Visa', 'best-passports'],
        ['fr', 'bp_th_visa_free_dest', 'Destinations Sans Visa', 'best-passports'],
        ['fr', 'bp_th_details', 'Détails', 'best-passports'],
        ['fr', 'bp_destinations', 'destinations', 'best-passports'],
        ['fr', 'bp_visa_free', 'sans visa', 'best-passports'],
        ['fr', 'bp_free', 'Libre', 'best-passports'],
        ['fr', 'bp_voa', 'VàA', 'best-passports'],
        ['fr', 'bp_evisa', 'eVisa', 'best-passports'],
        ['fr', 'bp_req', 'Requis', 'best-passports'],
        ['fr', 'bp_view_details', 'Voir Détails', 'best-passports'],
        ['fr', 'bp_howto_title', 'Comment le Classement est-il Calculé ?', 'best-passports'],
        ['fr', 'bp_howto_text', 'Le classement est calculé sur la base des données bilatérales de visa. Chaque passeport est noté selon le nombre de pays accessibles sans visa traditionnel.', 'best-passports'],
        ['fr', 'bp_sources_title', 'Sources et Méthodologie', 'best-passports'],
        ['fr', 'bp_data_sources', 'Sources de Données', 'best-passports'],
        ['fr', 'bp_source_henley', 'Classement mondial de la puissance des passeports', 'best-passports'],
        ['fr', 'bp_source_iata', "Données de voyage de l'Association Internationale du Transport Aérien", 'best-passports'],
        ['fr', 'bp_source_govt', "Sites officiels d'immigration gouvernementaux", 'best-passports'],
        ['fr', 'bp_source_smartraveller', 'Conseils de voyage du gouvernement australien', 'best-passports'],
        ['fr', 'bp_methodology', 'Méthodologie', 'best-passports'],
        ['fr', 'bp_method_1', "Score = Sans visa + Visa à l'arrivée + Destinations eVisa", 'best-passports'],
        ['fr', 'bp_method_2', 'Données vérifiées auprès de sources gouvernementales officielles', 'best-passports'],
        ['fr', 'bp_method_3', 'Classements mis à jour régulièrement', 'best-passports'],
        ['fr', 'bp_method_4', 'Seuls les accords de visa bilatéraux actifs sont comptés', 'best-passports'],
        ['fr', 'bp_method_5', 'Les visas de transit et les entrées restreintes sont exclus', 'best-passports'],
        ['fr', 'bp_disclaimer', 'Avertissement : Les classements sont basés sur les données disponibles. Vérifiez toujours auprès des sources officielles avant de voyager.', 'best-passports'],
        ['fr', 'bp_last_updated', 'Dernière mise à jour', 'best-passports'],

        // BEST PASSPORTS - German
        ['de', 'bp_page_title', 'Beste Reisepässe der Welt - Ranking | Arrival Cards', 'best-passports'],
        ['de', 'bp_page_description', 'Entdecken Sie die mächtigsten Reisepässe der Welt, gerankt nach visumfreiem Zugang.', 'best-passports'],
        ['de', 'bp_page_keywords', 'beste Reisepässe, Pass-Ranking, mächtigste Reisepässe, visumfreier Zugang', 'best-passports'],
        ['de', 'bp_hero_title', 'Beste Reisepässe der Welt', 'best-passports'],
        ['de', 'bp_hero_subtitle', 'Reisepässe gerankt nach visumfreiem Reisezugang und globaler Mobilität', 'best-passports'],
        ['de', 'bp_passports_ranked', 'Reisepässe Gerankt', 'best-passports'],
        ['de', 'bp_number_one_passport', '#1 Reisepass', 'best-passports'],
        ['de', 'bp_avg_visa_free', 'Durchschn. Visumfrei', 'best-passports'],
        ['de', 'bp_about_title', 'Über das Ranking', 'best-passports'],
        ['de', 'bp_about_text', 'Unser Ranking basiert auf der Anzahl der Reiseziele, die ohne vorheriges Visum zugänglich sind. Dazu gehören visumfreie Einreise, Visum bei Ankunft und eVisum-Ziele.', 'best-passports'],
        ['de', 'bp_th_rank', 'Rang', 'best-passports'],
        ['de', 'bp_th_country', 'Land', 'best-passports'],
        ['de', 'bp_th_visa_free_score', 'Visumfrei-Score', 'best-passports'],
        ['de', 'bp_th_visa_free_dest', 'Visumfreie Ziele', 'best-passports'],
        ['de', 'bp_th_details', 'Details', 'best-passports'],
        ['de', 'bp_destinations', 'Reiseziele', 'best-passports'],
        ['de', 'bp_visa_free', 'visumfrei', 'best-passports'],
        ['de', 'bp_free', 'Frei', 'best-passports'],
        ['de', 'bp_voa', 'VbA', 'best-passports'],
        ['de', 'bp_evisa', 'eVisum', 'best-passports'],
        ['de', 'bp_req', 'Erforderlich', 'best-passports'],
        ['de', 'bp_view_details', 'Details Ansehen', 'best-passports'],
        ['de', 'bp_howto_title', 'Wie wird das Ranking berechnet?', 'best-passports'],
        ['de', 'bp_howto_text', 'Das Ranking wird anhand bilateraler Visadaten berechnet. Jeder Pass wird danach bewertet, wie viele Länder ohne traditionelles Visum besucht werden können.', 'best-passports'],
        ['de', 'bp_sources_title', 'Quellen & Methodik', 'best-passports'],
        ['de', 'bp_data_sources', 'Datenquellen', 'best-passports'],
        ['de', 'bp_source_henley', 'Globales Reisepass-Stärke-Ranking', 'best-passports'],
        ['de', 'bp_source_iata', 'Reisedaten der Internationalen Luftverkehrs-Vereinigung', 'best-passports'],
        ['de', 'bp_source_govt', 'Offizielle Einwanderungswebsites der Regierungen', 'best-passports'],
        ['de', 'bp_source_smartraveller', 'Reisehinweise der australischen Regierung', 'best-passports'],
        ['de', 'bp_methodology', 'Methodik', 'best-passports'],
        ['de', 'bp_method_1', 'Score = Visumfrei + Visum bei Ankunft + eVisum-Ziele', 'best-passports'],
        ['de', 'bp_method_2', 'Daten gegenüber offiziellen Regierungsquellen verifiziert', 'best-passports'],
        ['de', 'bp_method_3', 'Rankings werden regelmäßig aktualisiert', 'best-passports'],
        ['de', 'bp_method_4', 'Nur aktive bilaterale Visaabkommen werden gezählt', 'best-passports'],
        ['de', 'bp_method_5', 'Transitvisa und eingeschränkte Einreisen sind ausgeschlossen', 'best-passports'],
        ['de', 'bp_disclaimer', 'Haftungsausschluss: Rankings basieren auf verfügbaren Daten. Überprüfen Sie vor der Reise immer offizielle Quellen.', 'best-passports'],
        ['de', 'bp_last_updated', 'Letzte Aktualisierung', 'best-passports'],

        // BEST PASSPORTS - Italian
        ['it', 'bp_page_title', 'Migliori Passaporti al Mondo - Classifica | Arrival Cards', 'best-passports'],
        ['it', 'bp_page_description', 'Scopri i passaporti più potenti al mondo classificati per accesso senza visto.', 'best-passports'],
        ['it', 'bp_page_keywords', 'migliori passaporti, classifica passaporti, passaporti potenti, accesso senza visto', 'best-passports'],
        ['it', 'bp_hero_title', 'Migliori Passaporti al Mondo', 'best-passports'],
        ['it', 'bp_hero_subtitle', 'Passaporti classificati per accesso di viaggio senza visto e mobilità globale', 'best-passports'],
        ['it', 'bp_passports_ranked', 'Passaporti Classificati', 'best-passports'],
        ['it', 'bp_number_one_passport', 'Passaporto #1', 'best-passports'],
        ['it', 'bp_avg_visa_free', 'Media Senza Visto', 'best-passports'],
        ['it', 'bp_about_title', 'Informazioni sulla Classifica', 'best-passports'],
        ['it', 'bp_about_text', "La nostra classifica si basa sul numero di destinazioni accessibili senza visto preventivo. Include ingresso senza visto, visto all'arrivo e destinazioni eVisa.", 'best-passports'],
        ['it', 'bp_th_rank', 'Posizione', 'best-passports'],
        ['it', 'bp_th_country', 'Paese', 'best-passports'],
        ['it', 'bp_th_visa_free_score', 'Punteggio Senza Visto', 'best-passports'],
        ['it', 'bp_th_visa_free_dest', 'Destinazioni Senza Visto', 'best-passports'],
        ['it', 'bp_th_details', 'Dettagli', 'best-passports'],
        ['it', 'bp_destinations', 'destinazioni', 'best-passports'],
        ['it', 'bp_visa_free', 'senza visto', 'best-passports'],
        ['it', 'bp_free', 'Libero', 'best-passports'],
        ['it', 'bp_voa', 'VaA', 'best-passports'],
        ['it', 'bp_evisa', 'eVisa', 'best-passports'],
        ['it', 'bp_req', 'Richiesto', 'best-passports'],
        ['it', 'bp_view_details', 'Vedi Dettagli', 'best-passports'],
        ['it', 'bp_howto_title', 'Come Viene Calcolata la Classifica?', 'best-passports'],
        ['it', 'bp_howto_text', 'La classifica viene calcolata sulla base dei dati bilaterali sui visti. Ogni passaporto viene valutato in base al numero di paesi visitabili senza visto tradizionale.', 'best-passports'],
        ['it', 'bp_sources_title', 'Fonti e Metodologia', 'best-passports'],
        ['it', 'bp_data_sources', 'Fonti dei Dati', 'best-passports'],
        ['it', 'bp_source_henley', 'Classifica globale della potenza dei passaporti', 'best-passports'],
        ['it', 'bp_source_iata', "Dati di viaggio dell'Associazione Internazionale del Trasporto Aereo", 'best-passports'],
        ['it', 'bp_source_govt', "Siti web ufficiali dell'immigrazione governativa", 'best-passports'],
        ['it', 'bp_source_smartraveller', 'Avvisi di viaggio del governo australiano', 'best-passports'],
        ['it', 'bp_methodology', 'Metodologia', 'best-passports'],
        ['it', 'bp_method_1', "Punteggio = Senza visto + Visto all'arrivo + Destinazioni eVisa", 'best-passports'],
        ['it', 'bp_method_2', 'Dati verificati con fonti governative ufficiali', 'best-passports'],
        ['it', 'bp_method_3', 'Classifiche aggiornate regolarmente', 'best-passports'],
        ['it', 'bp_method_4', 'Solo accordi bilaterali attivi vengono conteggiati', 'best-passports'],
        ['it', 'bp_method_5', 'Visti di transito e ingressi limitati sono esclusi', 'best-passports'],
        ['it', 'bp_disclaimer', 'Avvertenza: Le classifiche si basano sui dati disponibili. Verificare sempre con le fonti ufficiali prima di viaggiare.', 'best-passports'],
        ['it', 'bp_last_updated', 'Ultimo aggiornamento', 'best-passports'],

        // BEST PASSPORTS - Arabic
        ['ar', 'bp_page_title', 'أفضل جوازات السفر في العالم - التصنيف | Arrival Cards', 'best-passports'],
        ['ar', 'bp_page_description', 'اكتشف أقوى جوازات السفر في العالم مرتبة حسب الدخول بدون تأشيرة.', 'best-passports'],
        ['ar', 'bp_page_keywords', 'أفضل جوازات السفر, تصنيف جوازات السفر, أقوى جوازات السفر, دخول بدون تأشيرة', 'best-passports'],
        ['ar', 'bp_hero_title', 'أفضل جوازات السفر في العالم', 'best-passports'],
        ['ar', 'bp_hero_subtitle', 'جوازات السفر مرتبة حسب السفر بدون تأشيرة والتنقل العالمي', 'best-passports'],
        ['ar', 'bp_passports_ranked', 'جوازات سفر مصنفة', 'best-passports'],
        ['ar', 'bp_number_one_passport', 'جواز السفر رقم 1', 'best-passports'],
        ['ar', 'bp_avg_visa_free', 'متوسط بدون تأشيرة', 'best-passports'],
        ['ar', 'bp_about_title', 'عن التصنيف', 'best-passports'],
        ['ar', 'bp_about_text', 'يعتمد تصنيفنا على عدد الوجهات التي يمكن لحامل كل جواز سفر الوصول إليها بدون تأشيرة مسبقة. يشمل الدخول بدون تأشيرة والتأشيرة عند الوصول والتأشيرة الإلكترونية.', 'best-passports'],
        ['ar', 'bp_th_rank', 'المرتبة', 'best-passports'],
        ['ar', 'bp_th_country', 'الدولة', 'best-passports'],
        ['ar', 'bp_th_visa_free_score', 'نقاط بدون تأشيرة', 'best-passports'],
        ['ar', 'bp_th_visa_free_dest', 'وجهات بدون تأشيرة', 'best-passports'],
        ['ar', 'bp_th_details', 'التفاصيل', 'best-passports'],
        ['ar', 'bp_destinations', 'وجهات', 'best-passports'],
        ['ar', 'bp_visa_free', 'بدون تأشيرة', 'best-passports'],
        ['ar', 'bp_free', 'مجاني', 'best-passports'],
        ['ar', 'bp_voa', 'عند الوصول', 'best-passports'],
        ['ar', 'bp_evisa', 'إلكترونية', 'best-passports'],
        ['ar', 'bp_req', 'مطلوبة', 'best-passports'],
        ['ar', 'bp_view_details', 'عرض التفاصيل', 'best-passports'],
        ['ar', 'bp_howto_title', 'كيف يتم حساب التصنيف؟', 'best-passports'],
        ['ar', 'bp_howto_text', 'يتم حساب التصنيف بناءً على بيانات متطلبات التأشيرة الثنائية. يتم تقييم كل جواز سفر حسب عدد الدول التي يمكن زيارتها بدون تأشيرة تقليدية.', 'best-passports'],
        ['ar', 'bp_sources_title', 'المصادر والمنهجية', 'best-passports'],
        ['ar', 'bp_data_sources', 'مصادر البيانات', 'best-passports'],
        ['ar', 'bp_source_henley', 'تصنيف عالمي لقوة جوازات السفر', 'best-passports'],
        ['ar', 'bp_source_iata', 'بيانات السفر من الاتحاد الدولي للنقل الجوي', 'best-passports'],
        ['ar', 'bp_source_govt', 'المواقع الرسمية للهجرة الحكومية', 'best-passports'],
        ['ar', 'bp_source_smartraveller', 'نصائح السفر من الحكومة الأسترالية', 'best-passports'],
        ['ar', 'bp_methodology', 'المنهجية', 'best-passports'],
        ['ar', 'bp_method_1', 'النقاط = بدون تأشيرة + تأشيرة عند الوصول + وجهات التأشيرة الإلكترونية', 'best-passports'],
        ['ar', 'bp_method_2', 'البيانات موثقة من مصادر حكومية رسمية', 'best-passports'],
        ['ar', 'bp_method_3', 'يتم تحديث التصنيفات بانتظام', 'best-passports'],
        ['ar', 'bp_method_4', 'يتم احتساب اتفاقيات التأشيرة الثنائية النشطة فقط', 'best-passports'],
        ['ar', 'bp_method_5', 'تُستثنى تأشيرات العبور والدخول المقيد', 'best-passports'],
        ['ar', 'bp_disclaimer', 'إخلاء مسؤولية: تستند التصنيفات إلى البيانات المتاحة. تحقق دائمًا من المصادر الرسمية قبل السفر.', 'best-passports'],
        ['ar', 'bp_last_updated', 'آخر تحديث', 'best-passports'],
    ];
}

// Run installation if confirmed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install'])) {
    $translations = getTranslations();
    
    $stmt = $pdo->prepare("
        INSERT INTO translations (lang_code, translation_key, translation_value, category) 
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value)
    ");
    
    foreach ($translations as $t) {
        try {
            $stmt->execute($t);
            // Check if it was an insert or update
            if ($pdo->lastInsertId() > 0) {
                $installed++;
            } else {
                $updated++;
            }
        } catch (PDOException $e) {
            $errors[] = "Error on [{$t[0]}] {$t[1]}: " . $e->getMessage();
        }
    }
}

// Count existing cp_ and bp_ translations
$stmt = $pdo->query("SELECT COUNT(*) FROM translations WHERE translation_key LIKE 'cp_%'");
$existingCp = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM translations WHERE translation_key LIKE 'bp_%'");
$existingBp = $stmt->fetchColumn();

$totalTranslations = count(getTranslations());
?>
<!DOCTYPE html>
<html>
<head>
    <title>Install Translations - Admin</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/admin.css">
    <style>
        .installer { max-width: 800px; margin: 2rem auto; padding: 2rem; }
        .card { background: white; border-radius: 12px; padding: 2rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin: 1.5rem 0; }
        .stat { text-align: center; padding: 1rem; background: #f3f4f6; border-radius: 8px; }
        .stat-num { font-size: 2rem; font-weight: bold; color: #667eea; }
        .stat-label { font-size: 0.85rem; color: #6b7280; margin-top: 0.25rem; }
        .btn-install { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem 2.5rem; border: none; border-radius: 8px; font-size: 1.1rem; font-weight: 600; cursor: pointer; text-decoration: none; }
        .btn-install:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-install:disabled { opacity: 0.5; cursor: not-allowed; }
        .success { background: #d1fae5; color: #065f46; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; }
        .error-list { background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-top: 1rem; }
        .back-link { color: #667eea; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
<div class="installer">
    <a href="<?php echo APP_URL; ?>/admin/" class="back-link">← Back to Admin</a>
    
    <div class="card">
        <h1>🌐 Install Missing Translations</h1>
        <p style="color: #6b7280; margin-top: 0.5rem;">
            This will add all missing translations for the <strong>Compare Passports</strong> and <strong>Best Passports</strong> pages across all 7 languages.
        </p>
        
        <div class="stats">
            <div class="stat">
                <div class="stat-num"><?php echo $existingCp; ?></div>
                <div class="stat-label">Existing cp_ keys</div>
            </div>
            <div class="stat">
                <div class="stat-num"><?php echo $existingBp; ?></div>
                <div class="stat-label">Existing bp_ keys</div>
            </div>
            <div class="stat">
                <div class="stat-num"><?php echo $totalTranslations; ?></div>
                <div class="stat-label">Total to install</div>
            </div>
            <div class="stat">
                <div class="stat-num">7</div>
                <div class="stat-label">Languages</div>
            </div>
        </div>
        
        <?php if ($installed > 0 || $updated > 0): ?>
            <div class="success">
                <h3>✅ Installation Complete!</h3>
                <p><strong><?php echo $installed; ?></strong> new translations inserted</p>
                <p><strong><?php echo $updated; ?></strong> existing translations updated</p>
                <p style="margin-top: 1rem;">
                    <a href="<?php echo APP_URL; ?>/compare-passports" style="color: #065f46; font-weight: 600;">→ Check Compare Passports page</a>
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <a href="<?php echo APP_URL; ?>/best-passports" style="color: #065f46; font-weight: 600;">→ Check Best Passports page</a>
                </p>
            </div>
            <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <h4>Errors (<?php echo count($errors); ?>):</h4>
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo htmlspecialchars($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <form method="POST" style="margin-top: 1.5rem;">
                <p style="margin-bottom: 1rem; color: #374151;">
                    <strong>Languages:</strong> English, Spanish, Chinese, French, German, Italian, Arabic<br>
                    <strong>Pages:</strong> Compare Passports (34 keys), Best Passports (37 keys)<br>
                    <strong>Safe:</strong> Existing translations will be updated, not duplicated.
                </p>
                <button type="submit" name="install" value="1" class="btn-install">
                    🚀 Install All Translations Now
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
