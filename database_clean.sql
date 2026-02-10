-- ============================================
-- Arrival Cards Database Schema
-- Version: 1.0
-- Date: February 5, 2026
-- Description: Complete database structure for multi-language visa information portal
-- ============================================

-- Drop existing database and create fresh
DROP DATABASE IF EXISTS arrivalcards;
CREATE DATABASE arrivalcards CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE arrivalcards;

-- ============================================
-- Table: languages
-- Stores available languages for the site
-- ============================================
CREATE TABLE languages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(5) NOT NULL UNIQUE,
    name VARCHAR(50) NOT NULL,
    native_name VARCHAR(50) NOT NULL,
    flag_emoji VARCHAR(10) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert supported languages
INSERT INTO languages (code, name, native_name, flag_emoji, is_active, display_order) VALUES
('en', 'English', 'English', '🇬🇧', TRUE, 1),
('es', 'Spanish', 'Español', '🇪🇸', TRUE, 2),
('zh', 'Chinese', '中文', '🇨🇳', TRUE, 3),
('fr', 'French', 'Français', '🇫🇷', TRUE, 4),
('de', 'German', 'Deutsch', '🇩🇪', TRUE, 5);

-- ============================================
-- Table: translations
-- Stores UI text translations for all languages
-- ============================================
CREATE TABLE translations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lang_code VARCHAR(5) NOT NULL,
    translation_key VARCHAR(100) NOT NULL,
    translation_value TEXT NOT NULL,
    category VARCHAR(50) DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_translation (lang_code, translation_key),
    FOREIGN KEY (lang_code) REFERENCES languages(code) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert English translations
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('en', 'site_title', 'Arrival Cards', 'general'),
('en', 'site_tagline', 'Your gateway to global visa information', 'general'),
('en', 'search_placeholder', 'Search countries...', 'search'),
('en', 'filter_by_region', 'Filter by Region', 'filter'),
('en', 'filter_by_visa_type', 'Filter by Visa Type', 'filter'),
('en', 'all_regions', 'All Regions', 'filter'),
('en', 'all_visa_types', 'All Visa Types', 'filter'),
('en', 'view_official_site', 'View Official Site', 'buttons'),
('en', 'last_updated', 'Last Updated', 'general'),
('en', 'last_verified', 'Last Verified', 'general'),
('en', 'contact_us', 'Contact Us', 'navigation'),
('en', 'home', 'Home', 'navigation'),
('en', 'privacy_policy', 'Privacy Policy', 'navigation'),
('en', 'total_countries', 'Total Countries', 'stats'),
('en', 'contact_form_title', 'Get in Touch', 'contact'),
('en', 'contact_name', 'Your Name', 'contact'),
('en', 'contact_email', 'Your Email', 'contact'),
('en', 'contact_message', 'Your Message', 'contact'),
('en', 'contact_submit', 'Send Message', 'contact'),
('en', 'contact_success', 'Thank you! Your message has been sent successfully.', 'contact'),
('en', 'contact_error', 'Sorry, there was an error sending your message. Please try again.', 'contact'),
('en', 'footer_disclaimer', 'This information is for general guidance only. Always verify current entry requirements with official government sources before traveling.', 'footer'),
('en', 'footer_copyright', '© 2026 Arrival Cards. All rights reserved.', 'footer'),
('en', 'visa_free', 'Visa Free', 'visa_types'),
('en', 'visa_on_arrival', 'Visa on Arrival', 'visa_types'),
('en', 'visa_required', 'Visa Required', 'visa_types'),
('en', 'evisa', 'eVisa', 'visa_types'),
('en', 'no_results', 'No countries found matching your search.', 'search'),
('en', 'was_this_helpful', 'Was this helpful?', 'feedback');

-- Insert Spanish translations
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('es', 'site_title', 'Tarjetas de Llegada', 'general'),
('es', 'site_tagline', 'Tu puerta de entrada a información global de visas', 'general'),
('es', 'search_placeholder', 'Buscar países...', 'search'),
('es', 'filter_by_region', 'Filtrar por Región', 'filter'),
('es', 'filter_by_visa_type', 'Filtrar por Tipo de Visa', 'filter'),
('es', 'all_regions', 'Todas las Regiones', 'filter'),
('es', 'all_visa_types', 'Todos los Tipos de Visa', 'filter'),
('es', 'view_official_site', 'Ver Sitio Oficial', 'buttons'),
('es', 'last_updated', 'Última Actualización', 'general'),
('es', 'last_verified', 'Última Verificación', 'general'),
('es', 'contact_us', 'Contáctenos', 'navigation'),
('es', 'home', 'Inicio', 'navigation'),
('es', 'privacy_policy', 'Política de Privacidad', 'navigation'),
('es', 'total_countries', 'Total de Países', 'stats'),
('es', 'contact_form_title', 'Ponte en Contacto', 'contact'),
('es', 'contact_name', 'Tu Nombre', 'contact'),
('es', 'contact_email', 'Tu Correo', 'contact'),
('es', 'contact_message', 'Tu Mensaje', 'contact'),
('es', 'contact_submit', 'Enviar Mensaje', 'contact'),
('es', 'contact_success', '¡Gracias! Tu mensaje ha sido enviado con éxito.', 'contact'),
('es', 'contact_error', 'Lo sentimos, hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo.', 'contact'),
('es', 'footer_disclaimer', 'Esta información es solo una guía general. Siempre verifique los requisitos de entrada actuales con fuentes gubernamentales oficiales antes de viajar.', 'footer'),
('es', 'footer_copyright', '© 2026 Tarjetas de Llegada. Todos los derechos reservados.', 'footer'),
('es', 'visa_free', 'Sin Visa', 'visa_types'),
('es', 'visa_on_arrival', 'Visa a la Llegada', 'visa_types'),
('es', 'visa_required', 'Visa Requerida', 'visa_types'),
('es', 'evisa', 'eVisa', 'visa_types'),
('es', 'no_results', 'No se encontraron países que coincidan con tu búsqueda.', 'search'),
('es', 'was_this_helpful', '¿Fue esto útil?', 'feedback');

-- Insert Chinese translations
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('zh', 'site_title', '入境卡', 'general'),
('zh', 'site_tagline', '您的全球签证信息门户', 'general'),
('zh', 'search_placeholder', '搜索国家...', 'search'),
('zh', 'filter_by_region', '按地区筛选', 'filter'),
('zh', 'filter_by_visa_type', '按签证类型筛选', 'filter'),
('zh', 'all_regions', '所有地区', 'filter'),
('zh', 'all_visa_types', '所有签证类型', 'filter'),
('zh', 'view_official_site', '查看官方网站', 'buttons'),
('zh', 'last_updated', '最后更新', 'general'),
('zh', 'last_verified', '最后验证', 'general'),
('zh', 'contact_us', '联系我们', 'navigation'),
('zh', 'home', '首页', 'navigation'),
('zh', 'privacy_policy', '隐私政策', 'navigation'),
('zh', 'total_countries', '国家总数', 'stats'),
('zh', 'contact_form_title', '联系我们', 'contact'),
('zh', 'contact_name', '您的姓名', 'contact'),
('zh', 'contact_email', '您的邮箱', 'contact'),
('zh', 'contact_message', '您的留言', 'contact'),
('zh', 'contact_submit', '发送消息', 'contact'),
('zh', 'contact_success', '谢谢！您的消息已成功发送。', 'contact'),
('zh', 'contact_error', '抱歉，发送消息时出错。请重试。', 'contact'),
('zh', 'footer_disclaimer', '此信息仅供一般指导。旅行前请务必向官方政府来源核实当前的入境要求。', 'footer'),
('zh', 'footer_copyright', '© 2026 入境卡。版权所有。', 'footer'),
('zh', 'visa_free', '免签证', 'visa_types'),
('zh', 'visa_on_arrival', '落地签', 'visa_types'),
('zh', 'visa_required', '需要签证', 'visa_types'),
('zh', 'evisa', '电子签证', 'visa_types'),
('zh', 'no_results', '未找到匹配的国家。', 'search'),
('zh', 'was_this_helpful', '这有帮助吗？', 'feedback');

-- Insert French translations
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('fr', 'site_title', 'Cartes d\'Arrivée', 'general'),
('fr', 'site_tagline', 'Votre passerelle vers l\'information mondiale sur les visas', 'general'),
('fr', 'search_placeholder', 'Rechercher des pays...', 'search'),
('fr', 'filter_by_region', 'Filtrer par Région', 'filter'),
('fr', 'filter_by_visa_type', 'Filtrer par Type de Visa', 'filter'),
('fr', 'all_regions', 'Toutes les Régions', 'filter'),
('fr', 'all_visa_types', 'Tous les Types de Visa', 'filter'),
('fr', 'view_official_site', 'Voir le Site Officiel', 'buttons'),
('fr', 'last_updated', 'Dernière Mise à Jour', 'general'),
('fr', 'last_verified', 'Dernière Vérification', 'general'),
('fr', 'contact_us', 'Nous Contacter', 'navigation'),
('fr', 'home', 'Accueil', 'navigation'),
('fr', 'privacy_policy', 'Politique de Confidentialité', 'navigation'),
('fr', 'total_countries', 'Total des Pays', 'stats'),
('fr', 'contact_form_title', 'Contactez-nous', 'contact'),
('fr', 'contact_name', 'Votre Nom', 'contact'),
('fr', 'contact_email', 'Votre Email', 'contact'),
('fr', 'contact_message', 'Votre Message', 'contact'),
('fr', 'contact_submit', 'Envoyer le Message', 'contact'),
('fr', 'contact_success', 'Merci ! Votre message a été envoyé avec succès.', 'contact'),
('fr', 'contact_error', 'Désolé, une erreur s\'est produite lors de l\'envoi de votre message. Veuillez réessayer.', 'contact'),
('fr', 'footer_disclaimer', 'Ces informations sont fournies à titre indicatif uniquement. Vérifiez toujours les conditions d\'entrée actuelles auprès des sources gouvernementales officielles avant de voyager.', 'footer'),
('fr', 'footer_copyright', '© 2026 Cartes d\'Arrivée. Tous droits réservés.', 'footer'),
('fr', 'visa_free', 'Sans Visa', 'visa_types'),
('fr', 'visa_on_arrival', 'Visa à l\'Arrivée', 'visa_types'),
('fr', 'visa_required', 'Visa Requis', 'visa_types'),
('fr', 'evisa', 'eVisa', 'visa_types'),
('fr', 'no_results', 'Aucun pays trouvé correspondant à votre recherche.', 'search'),
('fr', 'was_this_helpful', 'Cela vous a-t-il aidé ?', 'feedback');

-- Insert German translations
INSERT INTO translations (lang_code, translation_key, translation_value, category) VALUES
('de', 'site_title', 'Ankunftskarten', 'general'),
('de', 'site_tagline', 'Ihr Zugang zu globalen Visa-Informationen', 'general'),
('de', 'search_placeholder', 'Länder suchen...', 'search'),
('de', 'filter_by_region', 'Nach Region Filtern', 'filter'),
('de', 'filter_by_visa_type', 'Nach Visum-Typ Filtern', 'filter'),
('de', 'all_regions', 'Alle Regionen', 'filter'),
('de', 'all_visa_types', 'Alle Visum-Typen', 'filter'),
('de', 'view_official_site', 'Offizielle Seite Ansehen', 'buttons'),
('de', 'last_updated', 'Zuletzt Aktualisiert', 'general'),
('de', 'last_verified', 'Zuletzt Überprüft', 'general'),
('de', 'contact_us', 'Kontaktieren Sie Uns', 'navigation'),
('de', 'home', 'Startseite', 'navigation'),
('de', 'privacy_policy', 'Datenschutz', 'navigation'),
('de', 'total_countries', 'Gesamtzahl der Länder', 'stats'),
('de', 'contact_form_title', 'Kontaktieren Sie Uns', 'contact'),
('de', 'contact_name', 'Ihr Name', 'contact'),
('de', 'contact_email', 'Ihre E-Mail', 'contact'),
('de', 'contact_message', 'Ihre Nachricht', 'contact'),
('de', 'contact_submit', 'Nachricht Senden', 'contact'),
('de', 'contact_success', 'Vielen Dank! Ihre Nachricht wurde erfolgreich gesendet.', 'contact'),
('de', 'contact_error', 'Entschuldigung, beim Senden Ihrer Nachricht ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 'contact'),
('de', 'footer_disclaimer', 'Diese Informationen dienen nur zur allgemeinen Orientierung. Überprüfen Sie vor der Reise immer die aktuellen Einreisebestimmungen bei offiziellen Regierungsquellen.', 'footer'),
('de', 'footer_copyright', '© 2026 Ankunftskarten. Alle Rechte vorbehalten.', 'footer'),
('de', 'visa_free', 'Visumfrei', 'visa_types'),
('de', 'visa_on_arrival', 'Visum bei Ankunft', 'visa_types'),
('de', 'visa_required', 'Visum Erforderlich', 'visa_types'),
('de', 'evisa', 'eVisum', 'visa_types'),
('de', 'no_results', 'Keine Länder gefunden, die Ihrer Suche entsprechen.', 'search'),
('de', 'was_this_helpful', 'War das hilfreich?', 'feedback');

-- ============================================
-- Table: countries
-- Stores country information (language-independent data)
-- ============================================
CREATE TABLE countries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    country_code VARCHAR(3) NOT NULL UNIQUE COMMENT 'ISO 3166-1 alpha-3 code',
    flag_emoji VARCHAR(10) NOT NULL,
    region VARCHAR(50) NOT NULL,
    official_url VARCHAR(500) NOT NULL,
    visa_type ENUM('visa_free', 'visa_on_arrival', 'visa_required', 'evisa') NOT NULL,
    last_updated DATE NOT NULL,
    helpful_yes INT DEFAULT 0 COMMENT 'Number of helpful votes',
    helpful_no INT DEFAULT 0 COMMENT 'Number of not helpful votes',
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_region (region),
    INDEX idx_visa_type (visa_type),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: country_translations
-- Stores country content in multiple languages
-- ============================================
CREATE TABLE country_translations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    country_id INT NOT NULL,
    lang_code VARCHAR(5) NOT NULL,
    country_name VARCHAR(100) NOT NULL,
    entry_summary TEXT NOT NULL COMMENT 'Brief overview of entry requirements',
    visa_requirements TEXT COMMENT 'Detailed visa information',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_country_lang (country_id, lang_code),
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE,
    FOREIGN KEY (lang_code) REFERENCES languages(code) ON DELETE CASCADE,
    INDEX idx_country_name (country_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: contact_submissions
-- Stores contact form submissions
-- ============================================
CREATE TABLE contact_submissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    is_read BOOLEAN DEFAULT FALSE,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_submitted (submitted_at),
    INDEX idx_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: admin_users
-- Stores admin user credentials
-- ============================================
CREATE TABLE admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(150) NOT NULL,
    full_name VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123 - CHANGE THIS!)
INSERT INTO admin_users (username, password_hash, email, full_name) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'me@carlosantoro.com', 'Administrator');

-- ============================================
-- Table: audit_log
-- Tracks administrative changes
-- ============================================
CREATE TABLE audit_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_value TEXT,
    new_value TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_user_id) REFERENCES admin_users(id) ON DELETE SET NULL,
    INDEX idx_admin (admin_user_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: country_feedback
-- Tracks user feedback votes to prevent duplicates
-- ============================================
CREATE TABLE country_feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    country_id INT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    feedback_type ENUM('helpful', 'not_helpful') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE,
    UNIQUE KEY unique_vote (country_id, ip_address),
    INDEX idx_country (country_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Insert Sample Data: 5 Popular Countries
-- ============================================

-- FRANCE
INSERT INTO countries (country_code, flag_emoji, region, official_url, visa_type, last_updated, display_order) VALUES
('FRA', '🇫🇷', 'Europe', 'https://france-visas.gouv.fr/en', 'visa_required', '2026-02-05', 1);

SET @france_id = LAST_INSERT_ID();

INSERT INTO country_translations (country_id, lang_code, country_name, entry_summary, visa_requirements) VALUES
(@france_id, 'en', 'France', 'France is part of the Schengen Area. Citizens of many countries can enter visa-free for up to 90 days within a 180-day period for tourism or business. Non-EU/EEA nationals may require a Schengen visa.', 'EU/EEA/Swiss citizens: No visa required. US, Canada, Australia, Japan, and many others: Visa-free for up to 90 days. Other nationalities: Schengen visa required. Apply at French embassy or consulate.'),
(@france_id, 'es', 'Francia', 'Francia es parte del Área Schengen. Los ciudadanos de muchos países pueden ingresar sin visa por hasta 90 días dentro de un período de 180 días para turismo o negocios. Los nacionales no pertenecientes a la UE/EEE pueden requerir una visa Schengen.', 'Ciudadanos de la UE/EEE/Suiza: No se requiere visa. EE. UU., Canadá, Australia, Japón y muchos otros: Sin visa por hasta 90 días. Otras nacionalidades: Se requiere visa Schengen.'),
(@france_id, 'zh', '法国', '法国是申根区的一部分。许多国家的公民可以在180天内免签入境最多90天，用于旅游或商务。非欧盟/欧洲经济区公民可能需要申根签证。', '欧盟/欧洲经济区/瑞士公民：无需签证。美国、加拿大、澳大利亚、日本等：最多90天免签证。其他国籍：需要申根签证。'),
(@france_id, 'fr', 'France', 'La France fait partie de l\'espace Schengen. Les citoyens de nombreux pays peuvent entrer sans visa pour un séjour de 90 jours maximum sur une période de 180 jours pour le tourisme ou les affaires. Les ressortissants non-UE/EEE peuvent nécessiter un visa Schengen.', 'Citoyens UE/EEE/Suisse : Pas de visa requis. États-Unis, Canada, Australie, Japon et bien d\'autres : Sans visa jusqu\'à 90 jours. Autres nationalités : Visa Schengen requis.'),
(@france_id, 'de', 'Frankreich', 'Frankreich ist Teil des Schengen-Raums. Bürger vieler Länder können visumfrei für bis zu 90 Tage innerhalb eines Zeitraums von 180 Tagen für Tourismus oder Geschäfte einreisen. Nicht-EU/EWR-Bürger benötigen möglicherweise ein Schengen-Visum.', 'EU/EWR/Schweizer Bürger: Kein Visum erforderlich. USA, Kanada, Australien, Japan und viele andere: Visumfrei für bis zu 90 Tage. Andere Nationalitäten: Schengen-Visum erforderlich.');

-- UNITED STATES
INSERT INTO countries (country_code, flag_emoji, region, official_url, visa_type, last_updated, display_order) VALUES
('USA', '🇺🇸', 'North America', 'https://travel.state.gov/content/travel/en/us-visas.html', 'evisa', '2026-02-05', 2);

SET @usa_id = LAST_INSERT_ID();

INSERT INTO country_translations (country_id, lang_code, country_name, entry_summary, visa_requirements) VALUES
(@usa_id, 'en', 'United States', 'Most travelers to the United States need either a visa or ESTA (Electronic System for Travel Authorization). The Visa Waiver Program allows citizens of 40 countries to travel to the US for tourism or business for up to 90 days without a visa using ESTA.', 'Visa Waiver Program countries: ESTA required ($21, valid 2 years). Apply at https://esta.cbp.dhs.gov. Other nationalities: B-1/B-2 tourist visa required. Apply at US embassy or consulate. Canadian citizens: Generally no visa required.'),
(@usa_id, 'es', 'Estados Unidos', 'La mayoría de los viajeros a los Estados Unidos necesitan una visa o ESTA (Sistema Electrónico de Autorización de Viaje). El Programa de Exención de Visa permite a ciudadanos de 40 países viajar a EE. UU. por turismo o negocios hasta 90 días sin visa usando ESTA.', 'Países del Programa de Exención de Visa: Se requiere ESTA ($21, válido 2 años). Solicitar en https://esta.cbp.dhs.gov. Otras nacionalidades: Se requiere visa de turista B-1/B-2. Ciudadanos canadienses: Generalmente no se requiere visa.'),
(@usa_id, 'zh', '美国', '大多数前往美国的旅客需要签证或ESTA（电子旅行授权系统）。免签证计划允许40个国家的公民使用ESTA前往美国进行旅游或商务活动最多90天，无需签证。', '免签证计划国家：需要ESTA（21美元，有效期2年）。在https://esta.cbp.dhs.gov申请。其他国籍：需要B-1/B-2旅游签证。加拿大公民：通常不需要签证。'),
(@usa_id, 'fr', 'États-Unis', 'La plupart des voyageurs se rendant aux États-Unis ont besoin d\'un visa ou d\'un ESTA (Electronic System for Travel Authorization). Le programme d\'exemption de visa permet aux citoyens de 40 pays de voyager aux États-Unis pour le tourisme ou les affaires jusqu\'à 90 jours sans visa en utilisant ESTA.', 'Pays du programme d\'exemption de visa : ESTA requis (21 $, valable 2 ans). Demander sur https://esta.cbp.dhs.gov. Autres nationalités : Visa touristique B-1/B-2 requis. Citoyens canadiens : Généralement pas de visa requis.'),
(@usa_id, 'de', 'Vereinigte Staaten', 'Die meisten Reisenden in die Vereinigten Staaten benötigen entweder ein Visum oder ESTA (Electronic System for Travel Authorization). Das Visa Waiver Program ermöglicht Bürgern von 40 Ländern, ohne Visum mit ESTA für bis zu 90 Tage in die USA zu reisen.', 'Visa Waiver Program-Länder: ESTA erforderlich (21 $, 2 Jahre gültig). Beantragen unter https://esta.cbp.dhs.gov. Andere Nationalitäten: B-1/B-2-Touristenvisum erforderlich. Kanadische Bürger: In der Regel kein Visum erforderlich.');

-- JAPAN
INSERT INTO countries (country_code, flag_emoji, region, official_url, visa_type, last_updated, display_order) VALUES
('JPN', '🇯🇵', 'Asia', 'https://www.mofa.go.jp/j_info/visit/visa/index.html', 'visa_free', '2026-02-05', 3);

SET @japan_id = LAST_INSERT_ID();

INSERT INTO country_translations (country_id, lang_code, country_name, entry_summary, visa_requirements) VALUES
(@japan_id, 'en', 'Japan', 'Japan offers visa-free entry to citizens of 68 countries for short-term stays (tourism, business, visiting relatives). Most visitors can stay for up to 90 days. Registration with Visit Japan Web is recommended for smooth entry.', 'Visa-exempt countries: 15-90 days depending on nationality. Register at Visit Japan Web before arrival. Other nationalities: Visa required - apply at Japanese embassy or consulate. Working holiday visas available for select countries.'),
(@japan_id, 'es', 'Japón', 'Japón ofrece entrada sin visa a ciudadanos de 68 países para estancias cortas (turismo, negocios, visitar familiares). La mayoría de los visitantes pueden permanecer hasta 90 días. Se recomienda el registro en Visit Japan Web para una entrada sin problemas.', 'Países exentos de visa: 15-90 días según nacionalidad. Regístrese en Visit Japan Web antes de llegar. Otras nacionalidades: Se requiere visa - solicitar en embajada o consulado japonés.'),
(@japan_id, 'zh', '日本', '日本为68个国家的公民提供短期停留（旅游、商务、探亲）免签入境。大多数访客可以停留最多90天。建议在Visit Japan Web注册以便顺利入境。', '免签证国家：根据国籍可停留15-90天。抵达前在Visit Japan Web注册。其他国籍：需要签证 - 在日本大使馆或领事馆申请。'),
(@japan_id, 'fr', 'Japon', 'Le Japon offre une entrée sans visa aux citoyens de 68 pays pour des séjours de courte durée (tourisme, affaires, visite de proches). La plupart des visiteurs peuvent rester jusqu\'à 90 jours. L\'inscription sur Visit Japan Web est recommandée pour une entrée en douceur.', 'Pays exemptés de visa : 15-90 jours selon la nationalité. Inscrivez-vous sur Visit Japan Web avant l\'arrivée. Autres nationalités : Visa requis - demander à l\'ambassade ou au consulat du Japon.'),
(@japan_id, 'de', 'Japan', 'Japan bietet visumfreie Einreise für Bürger von 68 Ländern für Kurzaufenthalte (Tourismus, Geschäfte, Verwandtenbesuch). Die meisten Besucher können bis zu 90 Tage bleiben. Die Registrierung bei Visit Japan Web wird für eine reibungslose Einreise empfohlen.', 'Visumfreie Länder: 15-90 Tage je nach Nationalität. Registrieren Sie sich vor der Ankunft bei Visit Japan Web. Andere Nationalitäten: Visum erforderlich - bei japanischer Botschaft oder Konsulat beantragen.');

-- AUSTRALIA
INSERT INTO countries (country_code, flag_emoji, region, official_url, visa_type, last_updated, display_order) VALUES
('AUS', '🇦🇺', 'Oceania', 'https://immi.homeaffairs.gov.au/visas/getting-a-visa/visa-finder', 'evisa', '2026-02-05', 4);

SET @aus_id = LAST_INSERT_ID();

INSERT INTO country_translations (country_id, lang_code, country_name, entry_summary, visa_requirements) VALUES
(@aus_id, 'en', 'Australia', 'All visitors to Australia must have a valid visa or electronic travel authority before arrival. The eVisitor (subclass 651) and Electronic Travel Authority (ETA, subclass 601) are available for eligible passport holders for tourism or business visits up to 3 months.', 'eVisitor (subclass 651): Free for EU citizens. ETA (subclass 601): AUD $20 for eligible countries. Apply online at https://immi.homeaffairs.gov.au. Other nationalities: Visitor visa (subclass 600) required. New Zealand citizens: Special Category Visa (subclass 444) granted on arrival.'),
(@aus_id, 'es', 'Australia', 'Todos los visitantes a Australia deben tener una visa válida o autorización de viaje electrónica antes de la llegada. El eVisitor (subclase 651) y la Autorización Electrónica de Viaje (ETA, subclase 601) están disponibles para titulares de pasaportes elegibles para visitas de turismo o negocios de hasta 3 meses.', 'eVisitor (subclase 651): Gratis para ciudadanos de la UE. ETA (subclase 601): AUD $20 para países elegibles. Solicitar en línea. Otras nacionalidades: Se requiere visa de visitante (subclase 600). Ciudadanos de Nueva Zelanda: Visa de categoría especial otorgada a la llegada.'),
(@aus_id, 'zh', '澳大利亚', '所有前往澳大利亚的访客必须在抵达前拥有有效签证或电子旅行授权。eVisitor（子类651）和电子旅行授权（ETA，子类601）可供符合条件的护照持有人用于旅游或商务访问最多3个月。', 'eVisitor（子类651）：欧盟公民免费。ETA（子类601）：符合条件的国家需支付20澳元。在线申请。其他国籍：需要访客签证（子类600）。新西兰公民：抵达时授予特殊类别签证（子类444）。'),
(@aus_id, 'fr', 'Australie', 'Tous les visiteurs en Australie doivent avoir un visa valide ou une autorisation de voyage électronique avant l\'arrivée. L\'eVisitor (sous-classe 651) et l\'Electronic Travel Authority (ETA, sous-classe 601) sont disponibles pour les détenteurs de passeports éligibles pour des visites touristiques ou d\'affaires jusqu\'à 3 mois.', 'eVisitor (sous-classe 651) : Gratuit pour les citoyens de l\'UE. ETA (sous-classe 601) : 20 $ AUD pour les pays éligibles. Demander en ligne. Autres nationalités : Visa de visiteur (sous-classe 600) requis. Citoyens néo-zélandais : Visa de catégorie spéciale accordé à l\'arrivée.'),
(@aus_id, 'de', 'Australien', 'Alle Besucher Australiens müssen vor der Ankunft ein gültiges Visum oder eine elektronische Reisegenehmigung haben. Der eVisitor (Unterklasse 651) und die Electronic Travel Authority (ETA, Unterklasse 601) sind für berechtigte Passinhaber für Tourismus- oder Geschäftsbesuche bis zu 3 Monaten verfügbar.', 'eVisitor (Unterklasse 651): Kostenlos für EU-Bürger. ETA (Unterklasse 601): 20 AUD für berechtigte Länder. Online beantragen. Andere Nationalitäten: Besuchervisum (Unterklasse 600) erforderlich. Neuseeländische Bürger: Visum der Sonderkategorie bei Ankunft erteilt.');

-- UNITED KINGDOM
INSERT INTO countries (country_code, flag_emoji, region, official_url, visa_type, last_updated, display_order) VALUES
('GBR', '🇬🇧', 'Europe', 'https://www.gov.uk/check-uk-visa', 'evisa', '2026-02-05', 5);

SET @uk_id = LAST_INSERT_ID();

INSERT INTO country_translations (country_id, lang_code, country_name, entry_summary, visa_requirements) VALUES
(@uk_id, 'en', 'United Kingdom', 'As of 2024, most visitors to the UK need either a visa or an Electronic Travel Authorization (ETA). The UK ETA is required for visa-exempt nationals and costs £10. EU/EEA/Swiss citizens need an ETA for visits up to 6 months.', 'ETA required: £10, apply online at https://www.gov.uk/apply-electronic-travel-authorisation-eta. Valid for 2 years or until passport expires. Irish citizens: No ETA or visa required. Visa-required countries: Apply for Standard Visitor visa at UK visa application center.'),
(@uk_id, 'es', 'Reino Unido', 'Desde 2024, la mayoría de los visitantes al Reino Unido necesitan una visa o una Autorización Electrónica de Viaje (ETA). La ETA del Reino Unido es requerida para nacionales exentos de visa y cuesta £10. Los ciudadanos de la UE/EEE/Suiza necesitan una ETA para visitas de hasta 6 meses.', 'ETA requerida: £10, solicitar en línea en https://www.gov.uk/apply-electronic-travel-authorisation-eta. Válida por 2 años o hasta que expire el pasaporte. Ciudadanos irlandeses: No se requiere ETA ni visa. Países que requieren visa: Solicitar visa de visitante estándar.'),
(@uk_id, 'zh', '英国', '自2024年起，大多数前往英国的访客需要签证或电子旅行授权（ETA）。免签证国民需要英国ETA，费用为10英镑。欧盟/欧洲经济区/瑞士公民需要ETA进行最多6个月的访问。', 'ETA要求：10英镑，在https://www.gov.uk/apply-electronic-travel-authorisation-eta在线申请。有效期2年或直到护照到期。爱尔兰公民：不需要ETA或签证。需要签证的国家：在英国签证申请中心申请标准访客签证。'),
(@uk_id, 'fr', 'Royaume-Uni', 'Depuis 2024, la plupart des visiteurs au Royaume-Uni ont besoin d\'un visa ou d\'une autorisation de voyage électronique (ETA). L\'ETA britannique est requise pour les ressortissants exemptés de visa et coûte 10 £. Les citoyens de l\'UE/EEE/Suisse ont besoin d\'une ETA pour des visites jusqu\'à 6 mois.', 'ETA requise : 10 £, demander en ligne sur https://www.gov.uk/apply-electronic-travel-authorisation-eta. Valable 2 ans ou jusqu\'à expiration du passeport. Citoyens irlandais : Aucune ETA ni visa requis. Pays nécessitant un visa : Demander un visa de visiteur standard.'),
(@uk_id, 'de', 'Vereinigtes Königreich', 'Seit 2024 benötigen die meisten Besucher des Vereinigten Königreichs entweder ein Visum oder eine elektronische Reisegenehmigung (ETA). Die UK ETA ist für visumbefreite Staatsangehörige erforderlich und kostet £10. EU/EWR/Schweizer Bürger benötigen eine ETA für Besuche bis zu 6 Monaten.', 'ETA erforderlich: £10, online beantragen unter https://www.gov.uk/apply-electronic-travel-authorisation-eta. Gültig für 2 Jahre oder bis zum Ablauf des Reisepasses. Irische Bürger: Keine ETA oder Visum erforderlich. Visumpflichtige Länder: Standardbesuchervisum beantragen.');

-- ============================================
-- Create indexes for performance
-- ============================================
CREATE INDEX idx_country_active ON countries(is_active, display_order);
CREATE INDEX idx_country_region_visa ON countries(region, visa_type);

-- ============================================
-- Database setup complete
-- ============================================
