-- =============================================================
-- Compare Passports Page Translations (compare-passports.php)
-- All 7 languages: en, es, zh, fr, de, it, ar
-- =============================================================

-- Page meta
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_page_title', 'Compare Passports - Side-by-Side Visa Requirements | Arrival Cards', 'compare_passports'),
('es', 'cp_page_title', 'Comparar Pasaportes - Requisitos de Visa Lado a Lado | Arrival Cards', 'compare_passports'),
('zh', 'cp_page_title', '比较护照 - 签证要求并排对比 | Arrival Cards', 'compare_passports'),
('fr', 'cp_page_title', 'Comparer les Passeports - Exigences de Visa Côte à Côte | Arrival Cards', 'compare_passports'),
('de', 'cp_page_title', 'Reisepässe Vergleichen - Visaanforderungen im Vergleich | Arrival Cards', 'compare_passports'),
('it', 'cp_page_title', 'Confronta Passaporti - Requisiti Visto a Confronto | Arrival Cards', 'compare_passports'),
('ar', 'cp_page_title', 'مقارنة جوازات السفر - متطلبات التأشيرة جنباً إلى جنب | Arrival Cards', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_page_description', 'Compare visa requirements between two passports. See which passport offers better travel freedom, visa-free access, and lower costs for your dream destinations.', 'compare_passports'),
('es', 'cp_page_description', 'Compare los requisitos de visa entre dos pasaportes. Vea qué pasaporte ofrece mejor libertad de viaje, acceso sin visa y costos más bajos para sus destinos soñados.', 'compare_passports'),
('zh', 'cp_page_description', '比较两本护照的签证要求。了解哪本护照提供更好的旅行自由、免签入境和更低的费用。', 'compare_passports'),
('fr', 'cp_page_description', 'Comparez les exigences de visa entre deux passeports. Découvrez quel passeport offre une meilleure liberté de voyage, un accès sans visa et des coûts réduits pour vos destinations de rêve.', 'compare_passports'),
('de', 'cp_page_description', 'Vergleichen Sie die Visaanforderungen zweier Reisepässe. Sehen Sie, welcher Reisepass bessere Reisefreiheit, visafreien Zugang und niedrigere Kosten bietet.', 'compare_passports'),
('it', 'cp_page_description', 'Confronta i requisiti di visto tra due passaporti. Scopri quale passaporto offre maggiore libertà di viaggio, accesso senza visto e costi inferiori per le tue destinazioni da sogno.', 'compare_passports'),
('ar', 'cp_page_description', 'قارن متطلبات التأشيرة بين جوازي سفر. اكتشف أي جواز سفر يوفر حرية سفر أفضل ودخول بدون تأشيرة وتكاليف أقل لوجهاتك المفضلة.', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_page_keywords', 'passport comparison, compare passports, visa requirements comparison, passport power, travel freedom, visa-free countries, passport ranking comparison', 'compare_passports'),
('es', 'cp_page_keywords', 'comparación de pasaportes, comparar pasaportes, comparación requisitos de visa, poder del pasaporte, libertad de viaje, países sin visa', 'compare_passports'),
('zh', 'cp_page_keywords', '护照比较, 比较护照, 签证要求对比, 护照实力, 旅行自由, 免签国家, 护照排名对比', 'compare_passports'),
('fr', 'cp_page_keywords', 'comparaison passeports, comparer passeports, comparaison exigences visa, puissance passeport, liberté voyage, pays sans visa', 'compare_passports'),
('de', 'cp_page_keywords', 'Reisepass-Vergleich, Pässe vergleichen, Visaanforderungen Vergleich, Passstärke, Reisefreiheit, visafreie Länder', 'compare_passports'),
('it', 'cp_page_keywords', 'confronto passaporti, confrontare passaporti, confronto requisiti visto, potere passaporto, libertà viaggio, paesi senza visto', 'compare_passports'),
('ar', 'cp_page_keywords', 'مقارنة جوازات السفر, مقارنة متطلبات التأشيرة, قوة جواز السفر, حرية السفر, دول بدون تأشيرة', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- Hero section
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_hero_title', 'Compare Passports', 'compare_passports'),
('es', 'cp_hero_title', 'Comparar Pasaportes', 'compare_passports'),
('zh', 'cp_hero_title', '比较护照', 'compare_passports'),
('fr', 'cp_hero_title', 'Comparer les Passeports', 'compare_passports'),
('de', 'cp_hero_title', 'Reisepässe Vergleichen', 'compare_passports'),
('it', 'cp_hero_title', 'Confronta Passaporti', 'compare_passports'),
('ar', 'cp_hero_title', 'مقارنة جوازات السفر', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_hero_subtitle', 'See side-by-side visa requirements and travel freedom differences', 'compare_passports'),
('es', 'cp_hero_subtitle', 'Vea los requisitos de visa y las diferencias de libertad de viaje lado a lado', 'compare_passports'),
('zh', 'cp_hero_subtitle', '并排查看签证要求和旅行自由度差异', 'compare_passports'),
('fr', 'cp_hero_subtitle', 'Consultez les exigences de visa et les différences de liberté de voyage côte à côte', 'compare_passports'),
('de', 'cp_hero_subtitle', 'Visaanforderungen und Reisefreiheitsunterschiede im direkten Vergleich', 'compare_passports'),
('it', 'cp_hero_subtitle', 'Confronta i requisiti di visto e le differenze di libertà di viaggio fianco a fianco', 'compare_passports'),
('ar', 'cp_hero_subtitle', 'شاهد متطلبات التأشيرة واختلافات حرية السفر جنباً إلى جنب', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- Selector section
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_first_passport', 'First Passport', 'compare_passports'),
('es', 'cp_first_passport', 'Primer Pasaporte', 'compare_passports'),
('zh', 'cp_first_passport', '第一本护照', 'compare_passports'),
('fr', 'cp_first_passport', 'Premier Passeport', 'compare_passports'),
('de', 'cp_first_passport', 'Erster Reisepass', 'compare_passports'),
('it', 'cp_first_passport', 'Primo Passaporto', 'compare_passports'),
('ar', 'cp_first_passport', 'الجواز الأول', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_second_passport', 'Second Passport', 'compare_passports'),
('es', 'cp_second_passport', 'Segundo Pasaporte', 'compare_passports'),
('zh', 'cp_second_passport', '第二本护照', 'compare_passports'),
('fr', 'cp_second_passport', 'Deuxième Passeport', 'compare_passports'),
('de', 'cp_second_passport', 'Zweiter Reisepass', 'compare_passports'),
('it', 'cp_second_passport', 'Secondo Passaporto', 'compare_passports'),
('ar', 'cp_second_passport', 'الجواز الثاني', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_select_passport', 'Select a passport...', 'compare_passports'),
('es', 'cp_select_passport', 'Seleccione un pasaporte...', 'compare_passports'),
('zh', 'cp_select_passport', '选择护照...', 'compare_passports'),
('fr', 'cp_select_passport', 'Sélectionner un passeport...', 'compare_passports'),
('de', 'cp_select_passport', 'Reisepass auswählen...', 'compare_passports'),
('it', 'cp_select_passport', 'Seleziona un passaporto...', 'compare_passports'),
('ar', 'cp_select_passport', 'اختر جواز سفر...', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_destinations', 'destinations', 'compare_passports'),
('es', 'cp_destinations', 'destinos', 'compare_passports'),
('zh', 'cp_destinations', '个目的地', 'compare_passports'),
('fr', 'cp_destinations', 'destinations', 'compare_passports'),
('de', 'cp_destinations', 'Ziele', 'compare_passports'),
('it', 'cp_destinations', 'destinazioni', 'compare_passports'),
('ar', 'cp_destinations', 'وجهة', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_compare_btn', 'Compare Passports', 'compare_passports'),
('es', 'cp_compare_btn', 'Comparar Pasaportes', 'compare_passports'),
('zh', 'cp_compare_btn', '比较护照', 'compare_passports'),
('fr', 'cp_compare_btn', 'Comparer les Passeports', 'compare_passports'),
('de', 'cp_compare_btn', 'Reisepässe Vergleichen', 'compare_passports'),
('it', 'cp_compare_btn', 'Confronta Passaporti', 'compare_passports'),
('ar', 'cp_compare_btn', 'مقارنة جوازات السفر', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- Stats labels
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_visa_free', 'Visa-Free', 'compare_passports'),
('es', 'cp_visa_free', 'Sin Visa', 'compare_passports'),
('zh', 'cp_visa_free', '免签', 'compare_passports'),
('fr', 'cp_visa_free', 'Sans Visa', 'compare_passports'),
('de', 'cp_visa_free', 'Visafrei', 'compare_passports'),
('it', 'cp_visa_free', 'Senza Visto', 'compare_passports'),
('ar', 'cp_visa_free', 'بدون تأشيرة', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_visa_on_arrival', 'Visa on Arrival', 'compare_passports'),
('es', 'cp_visa_on_arrival', 'Visa a la Llegada', 'compare_passports'),
('zh', 'cp_visa_on_arrival', '落地签', 'compare_passports'),
('fr', 'cp_visa_on_arrival', 'Visa à l''Arrivée', 'compare_passports'),
('de', 'cp_visa_on_arrival', 'Visum bei Ankunft', 'compare_passports'),
('it', 'cp_visa_on_arrival', 'Visto all''Arrivo', 'compare_passports'),
('ar', 'cp_visa_on_arrival', 'تأشيرة عند الوصول', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_easy_access', 'Easy Access Total', 'compare_passports'),
('es', 'cp_easy_access', 'Total Acceso Fácil', 'compare_passports'),
('zh', 'cp_easy_access', '便捷入境总数', 'compare_passports'),
('fr', 'cp_easy_access', 'Total Accès Facile', 'compare_passports'),
('de', 'cp_easy_access', 'Einfacher Zugang Gesamt', 'compare_passports'),
('it', 'cp_easy_access', 'Totale Accesso Facile', 'compare_passports'),
('ar', 'cp_easy_access', 'إجمالي الوصول السهل', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_evisa_required', 'eVisa Required', 'compare_passports'),
('es', 'cp_evisa_required', 'eVisa Requerida', 'compare_passports'),
('zh', 'cp_evisa_required', '需电子签证', 'compare_passports'),
('fr', 'cp_evisa_required', 'eVisa Requis', 'compare_passports'),
('de', 'cp_evisa_required', 'eVisum Erforderlich', 'compare_passports'),
('it', 'cp_evisa_required', 'eVisto Richiesto', 'compare_passports'),
('ar', 'cp_evisa_required', 'تأشيرة إلكترونية مطلوبة', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_visa_required', 'Visa Required', 'compare_passports'),
('es', 'cp_visa_required', 'Visa Requerida', 'compare_passports'),
('zh', 'cp_visa_required', '需要签证', 'compare_passports'),
('fr', 'cp_visa_required', 'Visa Requis', 'compare_passports'),
('de', 'cp_visa_required', 'Visum Erforderlich', 'compare_passports'),
('it', 'cp_visa_required', 'Visto Richiesto', 'compare_passports'),
('ar', 'cp_visa_required', 'تأشيرة مطلوبة', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_avg_cost', 'Avg Cost', 'compare_passports'),
('es', 'cp_avg_cost', 'Costo Promedio', 'compare_passports'),
('zh', 'cp_avg_cost', '平均费用', 'compare_passports'),
('fr', 'cp_avg_cost', 'Coût Moyen', 'compare_passports'),
('de', 'cp_avg_cost', 'Durchschn. Kosten', 'compare_passports'),
('it', 'cp_avg_cost', 'Costo Medio', 'compare_passports'),
('ar', 'cp_avg_cost', 'متوسط التكلفة', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_avg_processing', 'Avg Processing', 'compare_passports'),
('es', 'cp_avg_processing', 'Procesamiento Promedio', 'compare_passports'),
('zh', 'cp_avg_processing', '平均处理时间', 'compare_passports'),
('fr', 'cp_avg_processing', 'Traitement Moyen', 'compare_passports'),
('de', 'cp_avg_processing', 'Durchschn. Bearbeitung', 'compare_passports'),
('it', 'cp_avg_processing', 'Elaborazione Media', 'compare_passports'),
('ar', 'cp_avg_processing', 'متوسط المعالجة', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_days', 'days', 'compare_passports'),
('es', 'cp_days', 'días', 'compare_passports'),
('zh', 'cp_days', '天', 'compare_passports'),
('fr', 'cp_days', 'jours', 'compare_passports'),
('de', 'cp_days', 'Tage', 'compare_passports'),
('it', 'cp_days', 'giorni', 'compare_passports'),
('ar', 'cp_days', 'أيام', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- Winner/comparison labels
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_better_access', 'Better Access', 'compare_passports'),
('es', 'cp_better_access', 'Mejor Acceso', 'compare_passports'),
('zh', 'cp_better_access', '更好的入境权', 'compare_passports'),
('fr', 'cp_better_access', 'Meilleur Accès', 'compare_passports'),
('de', 'cp_better_access', 'Besserer Zugang', 'compare_passports'),
('it', 'cp_better_access', 'Accesso Migliore', 'compare_passports'),
('ar', 'cp_better_access', 'وصول أفضل', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_equal_access', 'Equal Access', 'compare_passports'),
('es', 'cp_equal_access', 'Acceso Igual', 'compare_passports'),
('zh', 'cp_equal_access', '同等入境权', 'compare_passports'),
('fr', 'cp_equal_access', 'Accès Égal', 'compare_passports'),
('de', 'cp_equal_access', 'Gleicher Zugang', 'compare_passports'),
('it', 'cp_equal_access', 'Accesso Uguale', 'compare_passports'),
('ar', 'cp_equal_access', 'وصول متساوٍ', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- Destinations table
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_dest_comparison', 'Destination-by-Destination Comparison', 'compare_passports'),
('es', 'cp_dest_comparison', 'Comparación Destino por Destino', 'compare_passports'),
('zh', 'cp_dest_comparison', '逐个目的地对比', 'compare_passports'),
('fr', 'cp_dest_comparison', 'Comparaison Destination par Destination', 'compare_passports'),
('de', 'cp_dest_comparison', 'Ziel-für-Ziel-Vergleich', 'compare_passports'),
('it', 'cp_dest_comparison', 'Confronto Destinazione per Destinazione', 'compare_passports'),
('ar', 'cp_dest_comparison', 'مقارنة وجهة بوجهة', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_th_destination', 'Destination', 'compare_passports'),
('es', 'cp_th_destination', 'Destino', 'compare_passports'),
('zh', 'cp_th_destination', '目的地', 'compare_passports'),
('fr', 'cp_th_destination', 'Destination', 'compare_passports'),
('de', 'cp_th_destination', 'Reiseziel', 'compare_passports'),
('it', 'cp_th_destination', 'Destinazione', 'compare_passports'),
('ar', 'cp_th_destination', 'الوجهة', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_th_advantage', 'Advantage', 'compare_passports'),
('es', 'cp_th_advantage', 'Ventaja', 'compare_passports'),
('zh', 'cp_th_advantage', '优势', 'compare_passports'),
('fr', 'cp_th_advantage', 'Avantage', 'compare_passports'),
('de', 'cp_th_advantage', 'Vorteil', 'compare_passports'),
('it', 'cp_th_advantage', 'Vantaggio', 'compare_passports'),
('ar', 'cp_th_advantage', 'الميزة', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'cp_better', 'Better', 'compare_passports'),
('es', 'cp_better', 'Mejor', 'compare_passports'),
('zh', 'cp_better', '更好', 'compare_passports'),
('fr', 'cp_better', 'Mieux', 'compare_passports'),
('de', 'cp_better', 'Besser', 'compare_passports'),
('it', 'cp_better', 'Migliore', 'compare_passports'),
('ar', 'cp_better', 'أفضل', 'compare_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- Done!
SELECT 'Compare Passports translations inserted successfully!' AS status;
