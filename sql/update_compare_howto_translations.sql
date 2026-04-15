-- ============================================================
-- Update Compare Passports "How to Use" English Translations
-- Run on production DB to fix step wording after layout fix
-- ============================================================

UPDATE translations SET translation_value = 'Use the dropdowns above to select two passports and click Compare to see a full side-by-side visa breakdown.'
WHERE lang_code = 'en' AND translation_key = 'cp_howto_intro';

UPDATE translations SET translation_value = 'Choose Your First Passport'
WHERE lang_code = 'en' AND translation_key = 'cp_howto_step1_title';

UPDATE translations SET translation_value = 'Select the first passport from the left-hand dropdown.'
WHERE lang_code = 'en' AND translation_key = 'cp_howto_step1_text';

UPDATE translations SET translation_value = 'Choose a Second Passport'
WHERE lang_code = 'en' AND translation_key = 'cp_howto_step2_title';

UPDATE translations SET translation_value = 'Select a different passport from the right-hand dropdown to compare it against the first.'
WHERE lang_code = 'en' AND translation_key = 'cp_howto_step2_text';

UPDATE translations SET translation_value = 'Click Compare'
WHERE lang_code = 'en' AND translation_key = 'cp_howto_step3_title';

UPDATE translations SET translation_value = 'Hit the Compare Passports button to instantly see visa requirements, costs, and processing times for every destination — side by side.'
WHERE lang_code = 'en' AND translation_key = 'cp_howto_step3_text';

UPDATE translations SET translation_value = 'Only passports with bilateral visa data in our database are available for comparison.'
WHERE lang_code = 'en' AND translation_key = 'cp_howto_tip_text';
