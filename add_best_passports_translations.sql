-- =============================================================
-- Best Passports Page Translations (best-passports.php)
-- All 7 languages: en, es, zh, fr, de, it, ar
-- =============================================================

-- Page meta
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_page_title', 'Best Passports in the World 2026 - Global Ranking | Arrival Cards', 'best_passports'),
('es', 'bp_page_title', 'Mejores Pasaportes del Mundo 2026 - Ranking Global | Arrival Cards', 'best_passports'),
('zh', 'bp_page_title', '2026年世界最佳护照 - 全球排名 | Arrival Cards', 'best_passports'),
('fr', 'bp_page_title', 'Meilleurs Passeports du Monde 2026 - Classement Mondial | Arrival Cards', 'best_passports'),
('de', 'bp_page_title', 'Beste Reisepässe der Welt 2026 - Globales Ranking | Arrival Cards', 'best_passports'),
('it', 'bp_page_title', 'Migliori Passaporti del Mondo 2026 - Classifica Globale | Arrival Cards', 'best_passports'),
('ar', 'bp_page_title', 'أفضل جوازات السفر في العالم 2026 - التصنيف العالمي | Arrival Cards', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_page_description', 'Comprehensive ranking of the world''s most powerful passports by visa-free access. Compare passport strength, find which countries offer the best travel freedom, and see detailed visa statistics for 196 passports.', 'best_passports'),
('es', 'bp_page_description', 'Ranking completo de los pasaportes más poderosos del mundo por acceso sin visa. Compare la fortaleza de los pasaportes, descubra qué países ofrecen la mejor libertad de viaje y vea estadísticas detalladas de visas para 196 pasaportes.', 'best_passports'),
('zh', 'bp_page_description', '按免签入境数量排列的全球最强护照综合排名。比较护照实力，了解哪些国家提供最佳旅行自由度，查看196本护照的详细签证统计数据。', 'best_passports'),
('fr', 'bp_page_description', 'Classement complet des passeports les plus puissants du monde par accès sans visa. Comparez la puissance des passeports, découvrez quels pays offrent la meilleure liberté de voyage et consultez les statistiques détaillées de visas pour 196 passeports.', 'best_passports'),
('de', 'bp_page_description', 'Umfassendes Ranking der stärksten Reisepässe der Welt nach visafreiem Zugang. Vergleichen Sie die Passstärke, finden Sie heraus, welche Länder die beste Reisefreiheit bieten, und sehen Sie detaillierte Visa-Statistiken für 196 Pässe.', 'best_passports'),
('it', 'bp_page_description', 'Classifica completa dei passaporti più potenti del mondo per accesso senza visto. Confronta la forza dei passaporti, scopri quali paesi offrono la migliore libertà di viaggio e consulta le statistiche dettagliate sui visti per 196 passaporti.', 'best_passports'),
('ar', 'bp_page_description', 'تصنيف شامل لأقوى جوازات السفر في العالم حسب الدخول بدون تأشيرة. قارن قوة جوازات السفر، واكتشف الدول التي توفر أفضل حرية سفر، واطلع على إحصائيات التأشيرات المفصلة لـ 196 جواز سفر.', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_page_keywords', 'best passports, passport ranking, strongest passport, most powerful passport, visa free countries, passport index, global mobility, travel freedom, passport power', 'best_passports'),
('es', 'bp_page_keywords', 'mejores pasaportes, ranking de pasaportes, pasaporte más fuerte, pasaporte más poderoso, países sin visa, índice de pasaportes, movilidad global, libertad de viaje', 'best_passports'),
('zh', 'bp_page_keywords', '最佳护照, 护照排名, 最强护照, 最有力护照, 免签国家, 护照指数, 全球流动性, 旅行自由', 'best_passports'),
('fr', 'bp_page_keywords', 'meilleurs passeports, classement passeports, passeport le plus fort, passeport le plus puissant, pays sans visa, indice passeport, mobilité mondiale, liberté de voyage', 'best_passports'),
('de', 'bp_page_keywords', 'beste Reisepässe, Reisepass-Ranking, stärkster Reisepass, mächtigster Reisepass, visafreie Länder, Passindex, globale Mobilität, Reisefreiheit', 'best_passports'),
('it', 'bp_page_keywords', 'migliori passaporti, classifica passaporti, passaporto più forte, passaporto più potente, paesi senza visto, indice passaporti, mobilità globale, libertà di viaggio', 'best_passports'),
('ar', 'bp_page_keywords', 'أفضل جوازات السفر, تصنيف جوازات السفر, أقوى جواز سفر, دول بدون تأشيرة, مؤشر جوازات السفر, التنقل العالمي, حرية السفر', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- Hero section
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_hero_title', 'Best Passports in the World 2026', 'best_passports'),
('es', 'bp_hero_title', 'Mejores Pasaportes del Mundo 2026', 'best_passports'),
('zh', 'bp_hero_title', '2026年世界最佳护照', 'best_passports'),
('fr', 'bp_hero_title', 'Meilleurs Passeports du Monde 2026', 'best_passports'),
('de', 'bp_hero_title', 'Beste Reisepässe der Welt 2026', 'best_passports'),
('it', 'bp_hero_title', 'Migliori Passaporti del Mondo 2026', 'best_passports'),
('ar', 'bp_hero_title', 'أفضل جوازات السفر في العالم 2026', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_hero_subtitle', 'Discover which passports offer the greatest travel freedom', 'best_passports'),
('es', 'bp_hero_subtitle', 'Descubra qué pasaportes ofrecen la mayor libertad de viaje', 'best_passports'),
('zh', 'bp_hero_subtitle', '了解哪些护照提供最大的旅行自由度', 'best_passports'),
('fr', 'bp_hero_subtitle', 'Découvrez quels passeports offrent la plus grande liberté de voyage', 'best_passports'),
('de', 'bp_hero_subtitle', 'Entdecken Sie, welche Reisepässe die größte Reisefreiheit bieten', 'best_passports'),
('it', 'bp_hero_subtitle', 'Scopri quali passaporti offrono la massima libertà di viaggio', 'best_passports'),
('ar', 'bp_hero_subtitle', 'اكتشف أي جوازات السفر توفر أكبر حرية سفر', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- Stats cards
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_passports_ranked', 'Passports Ranked', 'best_passports'),
('es', 'bp_passports_ranked', 'Pasaportes Clasificados', 'best_passports'),
('zh', 'bp_passports_ranked', '护照已排名', 'best_passports'),
('fr', 'bp_passports_ranked', 'Passeports Classés', 'best_passports'),
('de', 'bp_passports_ranked', 'Pässe im Ranking', 'best_passports'),
('it', 'bp_passports_ranked', 'Passaporti Classificati', 'best_passports'),
('ar', 'bp_passports_ranked', 'جوازات سفر مصنفة', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_number_one_passport', '#1 Passport (Visa-Free)', 'best_passports'),
('es', 'bp_number_one_passport', '#1 Pasaporte (Sin Visa)', 'best_passports'),
('zh', 'bp_number_one_passport', '#1 护照（免签）', 'best_passports'),
('fr', 'bp_number_one_passport', '#1 Passeport (Sans Visa)', 'best_passports'),
('de', 'bp_number_one_passport', '#1 Reisepass (Visafrei)', 'best_passports'),
('it', 'bp_number_one_passport', '#1 Passaporto (Senza Visto)', 'best_passports'),
('ar', 'bp_number_one_passport', '#1 جواز سفر (بدون تأشيرة)', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_avg_visa_free', 'Avg Visa-Free Access', 'best_passports'),
('es', 'bp_avg_visa_free', 'Acceso Promedio Sin Visa', 'best_passports'),
('zh', 'bp_avg_visa_free', '平均免签入境数', 'best_passports'),
('fr', 'bp_avg_visa_free', 'Accès Moyen Sans Visa', 'best_passports'),
('de', 'bp_avg_visa_free', 'Durchschn. Visafreier Zugang', 'best_passports'),
('it', 'bp_avg_visa_free', 'Accesso Medio Senza Visto', 'best_passports'),
('ar', 'bp_avg_visa_free', 'متوسط الدخول بدون تأشيرة', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- About This Ranking
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_about_title', 'About This Ranking', 'best_passports'),
('es', 'bp_about_title', 'Acerca de Este Ranking', 'best_passports'),
('zh', 'bp_about_title', '关于此排名', 'best_passports'),
('fr', 'bp_about_title', 'À Propos de Ce Classement', 'best_passports'),
('de', 'bp_about_title', 'Über Dieses Ranking', 'best_passports'),
('it', 'bp_about_title', 'Informazioni su Questa Classifica', 'best_passports'),
('ar', 'bp_about_title', 'حول هذا التصنيف', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_about_text', 'Passport rankings based on the <strong>Henley Passport Index</strong>, which measures the number of destinations each passport can access visa-free or with visa on arrival. Data sourced from IATA and verified against official government immigration websites. The higher the visa-free score, the more powerful the passport.', 'best_passports'),
('es', 'bp_about_text', 'Rankings de pasaportes basados en el <strong>Índice de Pasaportes Henley</strong>, que mide el número de destinos a los que cada pasaporte puede acceder sin visa o con visa a la llegada. Datos obtenidos de IATA y verificados con los sitios web oficiales de inmigración gubernamental. Cuanto mayor sea la puntuación sin visa, más poderoso es el pasaporte.', 'best_passports'),
('zh', 'bp_about_text', '护照排名基于<strong>亨利护照指数</strong>，该指数衡量每本护照可免签或落地签入境的目的地数量。数据来源于IATA，并经官方政府移民网站验证。免签分数越高，护照越强大。', 'best_passports'),
('fr', 'bp_about_text', 'Classement des passeports basé sur l''<strong>Indice Henley des Passeports</strong>, qui mesure le nombre de destinations accessibles sans visa ou avec visa à l''arrivée pour chaque passeport. Données provenant de l''IATA et vérifiées auprès des sites officiels d''immigration gouvernementaux. Plus le score sans visa est élevé, plus le passeport est puissant.', 'best_passports'),
('de', 'bp_about_text', 'Passranking basierend auf dem <strong>Henley Passport Index</strong>, der die Anzahl der Reiseziele misst, die jeder Reisepass visafrei oder mit Visum bei Ankunft erreichen kann. Daten stammen von der IATA und wurden mit offiziellen Regierungs-Einwanderungswebseiten abgeglichen. Je höher der visafreie Score, desto stärker der Reisepass.', 'best_passports'),
('it', 'bp_about_text', 'Classifica dei passaporti basata sull''<strong>Indice Henley dei Passaporti</strong>, che misura il numero di destinazioni accessibili senza visto o con visto all''arrivo per ogni passaporto. Dati provenienti dalla IATA e verificati con i siti ufficiali dell''immigrazione governativa. Più alto è il punteggio senza visto, più potente è il passaporto.', 'best_passports'),
('ar', 'bp_about_text', 'تصنيف جوازات السفر بناءً على <strong>مؤشر هنلي لجوازات السفر</strong>، الذي يقيس عدد الوجهات التي يمكن لكل جواز سفر الوصول إليها بدون تأشيرة أو بتأشيرة عند الوصول. البيانات مصدرها IATA وتم التحقق منها مع المواقع الرسمية للهجرة الحكومية. كلما ارتفعت درجة الدخول بدون تأشيرة، كان جواز السفر أقوى.', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- Table headers
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_th_rank', 'Rank', 'best_passports'),
('es', 'bp_th_rank', 'Rango', 'best_passports'),
('zh', 'bp_th_rank', '排名', 'best_passports'),
('fr', 'bp_th_rank', 'Rang', 'best_passports'),
('de', 'bp_th_rank', 'Rang', 'best_passports'),
('it', 'bp_th_rank', 'Posizione', 'best_passports'),
('ar', 'bp_th_rank', 'المرتبة', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_th_country', 'Country', 'best_passports'),
('es', 'bp_th_country', 'País', 'best_passports'),
('zh', 'bp_th_country', '国家', 'best_passports'),
('fr', 'bp_th_country', 'Pays', 'best_passports'),
('de', 'bp_th_country', 'Land', 'best_passports'),
('it', 'bp_th_country', 'Paese', 'best_passports'),
('ar', 'bp_th_country', 'البلد', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_th_visa_free_score', 'Visa-Free Score', 'best_passports'),
('es', 'bp_th_visa_free_score', 'Puntuación Sin Visa', 'best_passports'),
('zh', 'bp_th_visa_free_score', '免签分数', 'best_passports'),
('fr', 'bp_th_visa_free_score', 'Score Sans Visa', 'best_passports'),
('de', 'bp_th_visa_free_score', 'Visafrei-Score', 'best_passports'),
('it', 'bp_th_visa_free_score', 'Punteggio Senza Visto', 'best_passports'),
('ar', 'bp_th_visa_free_score', 'درجة بدون تأشيرة', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_th_visa_free_dest', 'Visa-Free Destinations', 'best_passports'),
('es', 'bp_th_visa_free_dest', 'Destinos Sin Visa', 'best_passports'),
('zh', 'bp_th_visa_free_dest', '免签目的地', 'best_passports'),
('fr', 'bp_th_visa_free_dest', 'Destinations Sans Visa', 'best_passports'),
('de', 'bp_th_visa_free_dest', 'Visafreie Ziele', 'best_passports'),
('it', 'bp_th_visa_free_dest', 'Destinazioni Senza Visto', 'best_passports'),
('ar', 'bp_th_visa_free_dest', 'وجهات بدون تأشيرة', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_th_details', 'Details', 'best_passports'),
('es', 'bp_th_details', 'Detalles', 'best_passports'),
('zh', 'bp_th_details', '详情', 'best_passports'),
('fr', 'bp_th_details', 'Détails', 'best_passports'),
('de', 'bp_th_details', 'Details', 'best_passports'),
('it', 'bp_th_details', 'Dettagli', 'best_passports'),
('ar', 'bp_th_details', 'التفاصيل', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- Table row labels
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_destinations', 'destinations', 'best_passports'),
('es', 'bp_destinations', 'destinos', 'best_passports'),
('zh', 'bp_destinations', '个目的地', 'best_passports'),
('fr', 'bp_destinations', 'destinations', 'best_passports'),
('de', 'bp_destinations', 'Ziele', 'best_passports'),
('it', 'bp_destinations', 'destinazioni', 'best_passports'),
('ar', 'bp_destinations', 'وجهة', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_visa_free', 'visa-free', 'best_passports'),
('es', 'bp_visa_free', 'sin visa', 'best_passports'),
('zh', 'bp_visa_free', '免签', 'best_passports'),
('fr', 'bp_visa_free', 'sans visa', 'best_passports'),
('de', 'bp_visa_free', 'visafrei', 'best_passports'),
('it', 'bp_visa_free', 'senza visto', 'best_passports'),
('ar', 'bp_visa_free', 'بدون تأشيرة', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_free', 'free', 'best_passports'),
('es', 'bp_free', 'libre', 'best_passports'),
('zh', 'bp_free', '免签', 'best_passports'),
('fr', 'bp_free', 'libre', 'best_passports'),
('de', 'bp_free', 'frei', 'best_passports'),
('it', 'bp_free', 'libero', 'best_passports'),
('ar', 'bp_free', 'حر', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_voa', 'VoA', 'best_passports'),
('es', 'bp_voa', 'VLL', 'best_passports'),
('zh', 'bp_voa', '落地签', 'best_passports'),
('fr', 'bp_voa', 'VàA', 'best_passports'),
('de', 'bp_voa', 'VbA', 'best_passports'),
('it', 'bp_voa', 'VaA', 'best_passports'),
('ar', 'bp_voa', 'عند الوصول', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_evisa', 'eVisa', 'best_passports'),
('es', 'bp_evisa', 'eVisa', 'best_passports'),
('zh', 'bp_evisa', '电子签证', 'best_passports'),
('fr', 'bp_evisa', 'eVisa', 'best_passports'),
('de', 'bp_evisa', 'eVisum', 'best_passports'),
('it', 'bp_evisa', 'eVisto', 'best_passports'),
('ar', 'bp_evisa', 'تأشيرة إلكترونية', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_req', 'req.', 'best_passports'),
('es', 'bp_req', 'req.', 'best_passports'),
('zh', 'bp_req', '需签证', 'best_passports'),
('fr', 'bp_req', 'req.', 'best_passports'),
('de', 'bp_req', 'erf.', 'best_passports'),
('it', 'bp_req', 'rich.', 'best_passports'),
('ar', 'bp_req', 'مطلوبة', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_view_details', 'View details', 'best_passports'),
('es', 'bp_view_details', 'Ver detalles', 'best_passports'),
('zh', 'bp_view_details', '查看详情', 'best_passports'),
('fr', 'bp_view_details', 'Voir les détails', 'best_passports'),
('de', 'bp_view_details', 'Details ansehen', 'best_passports'),
('it', 'bp_view_details', 'Vedi dettagli', 'best_passports'),
('ar', 'bp_view_details', 'عرض التفاصيل', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- How to Read section
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_howto_title', 'How to Read This Table', 'best_passports'),
('es', 'bp_howto_title', 'Cómo Leer Esta Tabla', 'best_passports'),
('zh', 'bp_howto_title', '如何阅读此表格', 'best_passports'),
('fr', 'bp_howto_title', 'Comment Lire Ce Tableau', 'best_passports'),
('de', 'bp_howto_title', 'So Lesen Sie Diese Tabelle', 'best_passports'),
('it', 'bp_howto_title', 'Come Leggere Questa Tabella', 'best_passports'),
('ar', 'bp_howto_title', 'كيفية قراءة هذا الجدول', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_howto_text', '<strong>Rank:</strong> Henley Passport Index position (shared ranks indicate equal visa-free access).<br><strong>Visa-Free Score:</strong> Total destinations accessible without an advance visa (visa-free + visa on arrival).<br><strong>Details:</strong> Where available, shows our detailed breakdown — <span style="color: #28a745;">● Visa-free</span> | <span style="color: #17a2b8;">● VoA</span> (visa at airport) | <span style="color: #ffc107;">● eVisa</span> (apply online) | <span style="color: #dc3545;">● Visa required</span> (embassy application).<br>Click a country name to view its full visa requirements for Australian passport holders.', 'best_passports'),
('es', 'bp_howto_text', '<strong>Rango:</strong> Posición en el Índice de Pasaportes Henley (rangos compartidos indican acceso sin visa igual).<br><strong>Puntuación Sin Visa:</strong> Total de destinos accesibles sin visa previa (sin visa + visa a la llegada).<br><strong>Detalles:</strong> Donde esté disponible, muestra nuestro desglose detallado — <span style="color: #28a745;">● Sin visa</span> | <span style="color: #17a2b8;">● VLL</span> (visa en el aeropuerto) | <span style="color: #ffc107;">● eVisa</span> (solicitar en línea) | <span style="color: #dc3545;">● Visa requerida</span> (solicitud en embajada).<br>Haga clic en el nombre de un país para ver sus requisitos completos de visa para titulares de pasaporte australiano.', 'best_passports'),
('zh', 'bp_howto_text', '<strong>排名：</strong>亨利护照指数排位（相同排名表示免签入境数量相同）。<br><strong>免签分数：</strong>无需提前办理签证即可进入的目的地总数（免签+落地签）。<br><strong>详情：</strong>如有数据，显示详细分类 — <span style="color: #28a745;">● 免签</span> | <span style="color: #17a2b8;">● 落地签</span>（机场办理） | <span style="color: #ffc107;">● 电子签证</span>（在线申请） | <span style="color: #dc3545;">● 需要签证</span>（使馆申请）。<br>点击国家名称查看澳大利亚护照持有人的完整签证要求。', 'best_passports'),
('fr', 'bp_howto_text', '<strong>Rang :</strong> Position dans l''Indice Henley des Passeports (les rangs partagés indiquent un accès sans visa égal).<br><strong>Score Sans Visa :</strong> Total des destinations accessibles sans visa préalable (sans visa + visa à l''arrivée).<br><strong>Détails :</strong> Lorsque disponible, affiche notre ventilation détaillée — <span style="color: #28a745;">● Sans visa</span> | <span style="color: #17a2b8;">● VàA</span> (visa à l''aéroport) | <span style="color: #ffc107;">● eVisa</span> (demande en ligne) | <span style="color: #dc3545;">● Visa requis</span> (demande à l''ambassade).<br>Cliquez sur le nom d''un pays pour voir les exigences complètes de visa pour les titulaires de passeport australien.', 'best_passports'),
('de', 'bp_howto_text', '<strong>Rang:</strong> Position im Henley Passport Index (geteilte Ränge bedeuten gleichen visafreien Zugang).<br><strong>Visafrei-Score:</strong> Gesamtzahl der Reiseziele ohne Vorausvisum (visafrei + Visum bei Ankunft).<br><strong>Details:</strong> Wo verfügbar, zeigt unsere detaillierte Aufschlüsselung — <span style="color: #28a745;">● Visafrei</span> | <span style="color: #17a2b8;">● VbA</span> (Visum am Flughafen) | <span style="color: #ffc107;">● eVisum</span> (Online-Antrag) | <span style="color: #dc3545;">● Visum erforderlich</span> (Botschaftsantrag).<br>Klicken Sie auf einen Ländernamen, um die vollständigen Visaanforderungen für australische Passinhaber anzuzeigen.', 'best_passports'),
('it', 'bp_howto_text', '<strong>Posizione:</strong> Posizione nell''Indice Henley dei Passaporti (posizioni condivise indicano pari accesso senza visto).<br><strong>Punteggio Senza Visto:</strong> Destinazioni totali accessibili senza visto anticipato (senza visto + visto all''arrivo).<br><strong>Dettagli:</strong> Dove disponibile, mostra la nostra ripartizione dettagliata — <span style="color: #28a745;">● Senza visto</span> | <span style="color: #17a2b8;">● VaA</span> (visto in aeroporto) | <span style="color: #ffc107;">● eVisto</span> (richiesta online) | <span style="color: #dc3545;">● Visto richiesto</span> (domanda all''ambasciata).<br>Clicca sul nome di un paese per vedere i requisiti completi per i titolari di passaporto australiano.', 'best_passports'),
('ar', 'bp_howto_text', '<strong>المرتبة:</strong> الموقع في مؤشر هنلي لجوازات السفر (المراتب المشتركة تشير إلى وصول متساوٍ بدون تأشيرة).<br><strong>درجة بدون تأشيرة:</strong> إجمالي الوجهات التي يمكن الوصول إليها بدون تأشيرة مسبقة (بدون تأشيرة + تأشيرة عند الوصول).<br><strong>التفاصيل:</strong> حيث تتوفر البيانات، يظهر التفصيل الكامل — <span style="color: #28a745;">● بدون تأشيرة</span> | <span style="color: #17a2b8;">● عند الوصول</span> (تأشيرة في المطار) | <span style="color: #ffc107;">● تأشيرة إلكترونية</span> (تقديم عبر الإنترنت) | <span style="color: #dc3545;">● تأشيرة مطلوبة</span> (طلب في السفارة).<br>انقر على اسم الدولة لعرض متطلبات التأشيرة الكاملة لحاملي جواز السفر الأسترالي.', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- Sources & Methodology section
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_sources_title', 'Sources & Methodology', 'best_passports'),
('es', 'bp_sources_title', 'Fuentes y Metodología', 'best_passports'),
('zh', 'bp_sources_title', '数据来源与方法论', 'best_passports'),
('fr', 'bp_sources_title', 'Sources et Méthodologie', 'best_passports'),
('de', 'bp_sources_title', 'Quellen und Methodik', 'best_passports'),
('it', 'bp_sources_title', 'Fonti e Metodologia', 'best_passports'),
('ar', 'bp_sources_title', 'المصادر والمنهجية', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_data_sources', 'Data Sources', 'best_passports'),
('es', 'bp_data_sources', 'Fuentes de Datos', 'best_passports'),
('zh', 'bp_data_sources', '数据来源', 'best_passports'),
('fr', 'bp_data_sources', 'Sources de Données', 'best_passports'),
('de', 'bp_data_sources', 'Datenquellen', 'best_passports'),
('it', 'bp_data_sources', 'Fonti dei Dati', 'best_passports'),
('ar', 'bp_data_sources', 'مصادر البيانات', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_source_henley', 'Global visa-free rankings, produced by Henley & Partners using IATA data', 'best_passports'),
('es', 'bp_source_henley', 'Rankings globales sin visa, producidos por Henley & Partners usando datos de IATA', 'best_passports'),
('zh', 'bp_source_henley', '全球免签排名，由Henley & Partners使用IATA数据制作', 'best_passports'),
('fr', 'bp_source_henley', 'Classements mondiaux sans visa, produits par Henley & Partners avec les données IATA', 'best_passports'),
('de', 'bp_source_henley', 'Globale visafreie Rankings, erstellt von Henley & Partners mit IATA-Daten', 'best_passports'),
('it', 'bp_source_henley', 'Classifiche globali senza visto, prodotte da Henley & Partners con dati IATA', 'best_passports'),
('ar', 'bp_source_henley', 'التصنيفات العالمية بدون تأشيرة، من إنتاج هنلي وشركاه باستخدام بيانات IATA', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_source_iata', 'Bilateral visa requirement data for airlines', 'best_passports'),
('es', 'bp_source_iata', 'Datos bilaterales de requisitos de visa para aerolíneas', 'best_passports'),
('zh', 'bp_source_iata', '航空公司双边签证要求数据', 'best_passports'),
('fr', 'bp_source_iata', 'Données bilatérales sur les exigences de visa pour les compagnies aériennes', 'best_passports'),
('de', 'bp_source_iata', 'Bilaterale Visadaten für Fluggesellschaften', 'best_passports'),
('it', 'bp_source_iata', 'Dati bilaterali sui requisiti di visto per le compagnie aeree', 'best_passports'),
('ar', 'bp_source_iata', 'بيانات متطلبات التأشيرة الثنائية لشركات الطيران', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_source_govt', 'Official government immigration websites — Visa policies, fees, and processing times verified per country', 'best_passports'),
('es', 'bp_source_govt', 'Sitios web oficiales de inmigración gubernamental — Políticas de visa, tarifas y tiempos de procesamiento verificados por país', 'best_passports'),
('zh', 'bp_source_govt', '各国官方移民网站 — 按国家验证的签证政策、费用和处理时间', 'best_passports'),
('fr', 'bp_source_govt', 'Sites officiels d''immigration gouvernementaux — Politiques de visa, frais et délais de traitement vérifiés par pays', 'best_passports'),
('de', 'bp_source_govt', 'Offizielle Regierungs-Einwanderungswebseiten — Visarichtlinien, Gebühren und Bearbeitungszeiten pro Land verifiziert', 'best_passports'),
('it', 'bp_source_govt', 'Siti web ufficiali dell''immigrazione governativa — Politiche sui visti, tariffe e tempi di elaborazione verificati per paese', 'best_passports'),
('ar', 'bp_source_govt', 'المواقع الرسمية للهجرة الحكومية — سياسات التأشيرات والرسوم وأوقات المعالجة مُتحقق منها لكل دولة', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_source_smartraveller', 'Australian Government travel advisories', 'best_passports'),
('es', 'bp_source_smartraveller', 'Avisos de viaje del Gobierno Australiano', 'best_passports'),
('zh', 'bp_source_smartraveller', '澳大利亚政府旅行建议', 'best_passports'),
('fr', 'bp_source_smartraveller', 'Avis de voyage du gouvernement australien', 'best_passports'),
('de', 'bp_source_smartraveller', 'Reisehinweise der australischen Regierung', 'best_passports'),
('it', 'bp_source_smartraveller', 'Avvisi di viaggio del governo australiano', 'best_passports'),
('ar', 'bp_source_smartraveller', 'إرشادات السفر من الحكومة الأسترالية', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_methodology', 'Methodology', 'best_passports'),
('es', 'bp_methodology', 'Metodología', 'best_passports'),
('zh', 'bp_methodology', '方法论', 'best_passports'),
('fr', 'bp_methodology', 'Méthodologie', 'best_passports'),
('de', 'bp_methodology', 'Methodik', 'best_passports'),
('it', 'bp_methodology', 'Metodologia', 'best_passports'),
('ar', 'bp_methodology', 'المنهجية', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_method_1', '<strong>"Easy Access"</strong> score = visa-free + visa on arrival destinations', 'best_passports'),
('es', 'bp_method_1', 'Puntuación de <strong>"Acceso Fácil"</strong> = destinos sin visa + visa a la llegada', 'best_passports'),
('zh', 'bp_method_1', '<strong>"便捷入境"</strong>分数 = 免签 + 落地签目的地数量', 'best_passports'),
('fr', 'bp_method_1', 'Score d''<strong>"Accès Facile"</strong> = destinations sans visa + visa à l''arrivée', 'best_passports'),
('de', 'bp_method_1', '<strong>"Einfacher Zugang"</strong>-Score = visafreie + Visum-bei-Ankunft-Ziele', 'best_passports'),
('it', 'bp_method_1', 'Punteggio <strong>"Accesso Facile"</strong> = destinazioni senza visto + visto all''arrivo', 'best_passports'),
('ar', 'bp_method_1', 'درجة <strong>"الوصول السهل"</strong> = وجهات بدون تأشيرة + تأشيرة عند الوصول', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_method_2', '<strong>Global Rank</strong> is from the Henley Passport Index, which counts passport-free destinations for each nationality', 'best_passports'),
('es', 'bp_method_2', 'El <strong>Rango Global</strong> es del Índice de Pasaportes Henley, que cuenta destinos sin pasaporte para cada nacionalidad', 'best_passports'),
('zh', 'bp_method_2', '<strong>全球排名</strong>来自亨利护照指数，该指数统计每个国籍的免护照目的地数量', 'best_passports'),
('fr', 'bp_method_2', 'Le <strong>Rang Mondial</strong> provient de l''Indice Henley des Passeports, qui compte les destinations sans passeport pour chaque nationalité', 'best_passports'),
('de', 'bp_method_2', 'Der <strong>Globale Rang</strong> stammt vom Henley Passport Index, der passfreie Reiseziele pro Nationalität zählt', 'best_passports'),
('it', 'bp_method_2', 'La <strong>Classifica Globale</strong> proviene dall''Indice Henley dei Passaporti, che conta le destinazioni senza passaporto per ogni nazionalità', 'best_passports'),
('ar', 'bp_method_2', '<strong>التصنيف العالمي</strong> من مؤشر هنلي لجوازات السفر، الذي يحصي الوجهات بدون جواز سفر لكل جنسية', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_method_3', 'Rankings are updated periodically as visa policies change', 'best_passports'),
('es', 'bp_method_3', 'Los rankings se actualizan periódicamente según cambian las políticas de visa', 'best_passports'),
('zh', 'bp_method_3', '排名会随签证政策变化定期更新', 'best_passports'),
('fr', 'bp_method_3', 'Les classements sont mis à jour périodiquement selon l''évolution des politiques de visa', 'best_passports'),
('de', 'bp_method_3', 'Rankings werden regelmäßig aktualisiert, wenn sich Visarichtlinien ändern', 'best_passports'),
('it', 'bp_method_3', 'Le classifiche vengono aggiornate periodicamente al variare delle politiche sui visti', 'best_passports'),
('ar', 'bp_method_3', 'يتم تحديث التصنيفات دورياً مع تغير سياسات التأشيرات', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_method_4', 'Our database tracks 196 countries/territories with bilateral visa data', 'best_passports'),
('es', 'bp_method_4', 'Nuestra base de datos rastrea 196 países/territorios con datos bilaterales de visa', 'best_passports'),
('zh', 'bp_method_4', '我们的数据库跟踪196个国家/地区的双边签证数据', 'best_passports'),
('fr', 'bp_method_4', 'Notre base de données suit 196 pays/territoires avec des données bilatérales de visa', 'best_passports'),
('de', 'bp_method_4', 'Unsere Datenbank erfasst 196 Länder/Gebiete mit bilateralen Visadaten', 'best_passports'),
('it', 'bp_method_4', 'Il nostro database monitora 196 paesi/territori con dati bilaterali sui visti', 'best_passports'),
('ar', 'bp_method_4', 'تتبع قاعدة بياناتنا 196 دولة/إقليم مع بيانات التأشيرات الثنائية', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_method_5', 'eVisa destinations are listed separately as they require advance application', 'best_passports'),
('es', 'bp_method_5', 'Los destinos con eVisa se listan por separado ya que requieren solicitud anticipada', 'best_passports'),
('zh', 'bp_method_5', '电子签证目的地单独列出，因为需要提前申请', 'best_passports'),
('fr', 'bp_method_5', 'Les destinations eVisa sont listées séparément car elles nécessitent une demande préalable', 'best_passports'),
('de', 'bp_method_5', 'eVisum-Ziele werden separat aufgeführt, da sie einen Vorantrag erfordern', 'best_passports'),
('it', 'bp_method_5', 'Le destinazioni eVisto sono elencate separatamente poiché richiedono una domanda anticipata', 'best_passports'),
('ar', 'bp_method_5', 'وجهات التأشيرة الإلكترونية مدرجة بشكل منفصل لأنها تتطلب تقديم طلب مسبق', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- Disclaimer
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_disclaimer', '<strong>Disclaimer:</strong> Visa policies change frequently. This ranking is for informational purposes only and should not be considered legal advice. Always verify the latest entry requirements with the destination country''s official immigration authority or your nearest embassy before travelling.', 'best_passports'),
('es', 'bp_disclaimer', '<strong>Aviso Legal:</strong> Las políticas de visa cambian con frecuencia. Este ranking es solo con fines informativos y no debe considerarse asesoramiento legal. Siempre verifique los últimos requisitos de entrada con la autoridad de inmigración oficial del país de destino o su embajada más cercana antes de viajar.', 'best_passports'),
('zh', 'bp_disclaimer', '<strong>免责声明：</strong>签证政策经常变化。此排名仅供参考，不应视为法律建议。旅行前请务必向目的地国家的官方移民机构或最近的大使馆核实最新入境要求。', 'best_passports'),
('fr', 'bp_disclaimer', '<strong>Avertissement :</strong> Les politiques de visa changent fréquemment. Ce classement est uniquement à titre informatif et ne doit pas être considéré comme un avis juridique. Vérifiez toujours les dernières exigences d''entrée auprès de l''autorité d''immigration officielle du pays de destination ou de votre ambassade la plus proche avant de voyager.', 'best_passports'),
('de', 'bp_disclaimer', '<strong>Haftungsausschluss:</strong> Visarichtlinien ändern sich häufig. Dieses Ranking dient nur zu Informationszwecken und sollte nicht als Rechtsberatung angesehen werden. Überprüfen Sie immer die aktuellsten Einreisebestimmungen bei der offiziellen Einwanderungsbehörde des Ziellandes oder Ihrer nächsten Botschaft, bevor Sie reisen.', 'best_passports'),
('it', 'bp_disclaimer', '<strong>Avvertenza:</strong> Le politiche sui visti cambiano frequentemente. Questa classifica è solo a scopo informativo e non deve essere considerata consulenza legale. Verificare sempre i requisiti di ingresso più recenti presso l''autorità ufficiale per l''immigrazione del paese di destinazione o l''ambasciata più vicina prima di viaggiare.', 'best_passports'),
('ar', 'bp_disclaimer', '<strong>إخلاء المسؤولية:</strong> تتغير سياسات التأشيرات بشكل متكرر. هذا التصنيف لأغراض إعلامية فقط ولا ينبغي اعتباره استشارة قانونية. تحقق دائماً من أحدث متطلبات الدخول لدى هيئة الهجرة الرسمية في بلد الوجهة أو أقرب سفارة قبل السفر.', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'bp_last_updated', 'Last updated', 'best_passports'),
('es', 'bp_last_updated', 'Última actualización', 'best_passports'),
('zh', 'bp_last_updated', '最后更新', 'best_passports'),
('fr', 'bp_last_updated', 'Dernière mise à jour', 'best_passports'),
('de', 'bp_last_updated', 'Letzte Aktualisierung', 'best_passports'),
('it', 'bp_last_updated', 'Ultimo aggiornamento', 'best_passports'),
('ar', 'bp_last_updated', 'آخر تحديث', 'best_passports')
ON DUPLICATE KEY UPDATE translation_value = VALUES(translation_value);

-- Done!
SELECT 'Best Passports translations inserted successfully!' AS status;
