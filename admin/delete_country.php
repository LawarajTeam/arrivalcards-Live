<?php
/**
 * Admin - Delete Country
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        // Get country name before deleting
        $stmt = $pdo->prepare("SELECT ct.country_name FROM countries c 
                               LEFT JOIN country_translations ct ON c.id = ct.country_id 
                               WHERE c.id = ? AND ct.lang_code = 'en' LIMIT 1");
        $stmt->execute([$id]);
        $country = $stmt->fetch();
        
        // Delete country (cascade will handle translations)
        $stmt = $pdo->prepare("DELETE FROM countries WHERE id = ?");
        $stmt->execute([$id]);
        
        logAdminAction('Deleted country', 'countries', $id, $country ? $country['country_name'] : null);
        setFlashMessage('Country deleted successfully.', 'success');
    } catch (PDOException $e) {
        error_log('Delete country error: ' . $e->getMessage());
        setFlashMessage('Error deleting country.', 'error');
    }
}

redirect(APP_URL . '/admin/countries.php');
