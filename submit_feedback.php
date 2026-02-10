<?php
/**
 * Country Feedback Handler
 * Allows users to rate country information as helpful or not helpful
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get input
$countryId = isset($_POST['country_id']) ? (int)$_POST['country_id'] : 0;
$feedbackType = isset($_POST['type']) ? $_POST['type'] : '';
$clientIP = getClientIP();

// Validate input
if ($countryId <= 0 || !in_array($feedbackType, ['helpful', 'not_helpful'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

try {
    // Check if user already voted for this country
    $stmt = $pdo->prepare("SELECT feedback_type FROM country_feedback WHERE country_id = ? AND ip_address = ?");
    $stmt->execute([$countryId, $clientIP]);
    $existingVote = $stmt->fetch();
    
    if ($existingVote) {
        // User already voted - check if changing vote
        if ($existingVote['feedback_type'] === $feedbackType) {
            echo json_encode([
                'success' => false, 
                'message' => 'You have already voted on this country'
            ]);
            exit;
        }
        
        // User is changing their vote
        // Decrement old vote
        $oldColumn = $existingVote['feedback_type'] === 'helpful' ? 'helpful_yes' : 'helpful_no';
        $stmt = $pdo->prepare("UPDATE countries SET $oldColumn = GREATEST($oldColumn - 1, 0) WHERE id = ?");
        $stmt->execute([$countryId]);
        
        // Update feedback record
        $stmt = $pdo->prepare("UPDATE country_feedback SET feedback_type = ?, created_at = NOW() WHERE country_id = ? AND ip_address = ?");
        $stmt->execute([$feedbackType, $countryId, $clientIP]);
    } else {
        // New vote
        $stmt = $pdo->prepare("INSERT INTO country_feedback (country_id, ip_address, feedback_type) VALUES (?, ?, ?)");
        $stmt->execute([$countryId, $clientIP, $feedbackType]);
    }
    
    // Increment appropriate counter
    $column = $feedbackType === 'helpful' ? 'helpful_yes' : 'helpful_no';
    $stmt = $pdo->prepare("UPDATE countries SET $column = $column + 1 WHERE id = ?");
    $stmt->execute([$countryId]);
    
    // Get updated counts
    $stmt = $pdo->prepare("SELECT helpful_yes, helpful_no FROM countries WHERE id = ?");
    $stmt->execute([$countryId]);
    $counts = $stmt->fetch();
    
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your feedback!',
        'helpful_yes' => (int)$counts['helpful_yes'],
        'helpful_no' => (int)$counts['helpful_no'],
        'changed_vote' => isset($existingVote)
    ]);
    
} catch (PDOException $e) {
    error_log('Feedback error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred. Please try again.'
    ]);
}
