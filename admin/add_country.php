<?php
/**
 * Admin - Add New Country (Placeholder)
 * TODO: Implement full CRUD with all 5 language translations
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

// This is a placeholder - full implementation would include forms for all 5 languages
setFlashMessage('Add country feature coming soon! For now, add countries directly via database or update this file.', 'info');
redirect(APP_URL . '/admin/countries.php');
