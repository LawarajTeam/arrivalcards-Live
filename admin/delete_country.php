<?php
/**
 * Admin - Delete Country
 * Requires POST request with CSRF token for security
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

// Only accept POST requests to prevent CSRF via link/image attacks
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlashMessage('Invalid request method.', 'error');
    redirect(APP_URL . '/admin/countries.php');
}

// Verify CSRF token
$csrfToken = $_POST['csrf_token'] ?? '';
if (!verifyCSRFToken($csrfToken)) {
    setFlashMessage('Invalid or expired security token. Please try again.', 'error');
    redirect(APP_URL . '/admin/countries.php');
}

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = (int)$_POST['id'];
    
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
} else {
    setFlashMessage('Invalid country ID.', 'error');
}

redirect(APP_URL . '/admin/countries.php');
