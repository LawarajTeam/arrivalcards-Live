<?php
/**
 * Admin - Edit Country (Placeholder)
 * TODO: Implement full edit with all 5 language translations
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

setFlashMessage('Edit country feature coming soon! For now, edit countries directly via database or update this file.', 'info');
redirect(APP_URL . '/admin/countries.php');
