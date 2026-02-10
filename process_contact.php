<?php
/**
 * Process Contact Form
 * Handles form submission, validation, saves to database, and sends email
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(APP_URL . '/contact.php');
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    setFlashMessage('Security validation failed. Please try again.', 'error');
    redirect(APP_URL . '/contact.php');
}

// Check honeypot field (spam protection)
if (!empty($_POST['website'])) {
    // This is likely a bot
    setFlashMessage(t('contact_success'), 'success');
    redirect(APP_URL . '/contact.php');
}

// Sanitize and validate inputs
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];

// Validate name
if (strlen($name) < 2 || strlen($name) > 100) {
    $errors[] = 'Name must be between 2 and 100 characters.';
}

// Validate email
if (!isValidEmail($email)) {
    $errors[] = 'Please provide a valid email address.';
}

// Validate message
if (strlen($message) < 10 || strlen($message) > 2000) {
    $errors[] = 'Message must be between 10 and 2000 characters.';
}

// If there are validation errors
if (!empty($errors)) {
    setFlashMessage(implode(' ', $errors), 'error');
    redirect(APP_URL . '/contact.php');
}

// Rate limiting: Check if user has submitted recently
$clientIP = getClientIP();
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM contact_submissions 
    WHERE ip_address = ? AND submitted_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
");
$stmt->execute([$clientIP]);
$recentSubmissions = $stmt->fetchColumn();

if ($recentSubmissions > 0) {
    setFlashMessage('Please wait a few minutes before submitting another message.', 'warning');
    redirect(APP_URL . '/contact.php');
}

// Save to database
try {
    $stmt = $pdo->prepare("
        INSERT INTO contact_submissions (name, email, message, ip_address, user_agent)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $name,
        $email,
        $message,
        $clientIP,
        $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
    
    $submissionId = $pdo->lastInsertId();
    
} catch (PDOException $e) {
    error_log('Contact form database error: ' . $e->getMessage());
    setFlashMessage(t('contact_error'), 'error');
    redirect(APP_URL . '/contact.php');
}

// Send email notification
$emailSubject = 'New Contact Form Submission - Arrival Cards';
$emailBody = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #2563eb; color: white; padding: 20px; text-align: center; }
        .content { background-color: #f8fafc; padding: 20px; border: 1px solid #e2e8f0; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #1e293b; }
        .value { color: #475569; padding: 10px; background-color: white; border-left: 3px solid #2563eb; margin-top: 5px; }
        .footer { text-align: center; margin-top: 20px; color: #64748b; font-size: 12px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>New Contact Form Submission</h2>
        </div>
        <div class='content'>
            <div class='field'>
                <div class='label'>From:</div>
                <div class='value'>" . htmlspecialchars($name) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Email:</div>
                <div class='value'><a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a></div>
            </div>
            <div class='field'>
                <div class='label'>Message:</div>
                <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Submitted:</div>
                <div class='value'>" . date('F j, Y g:i A') . "</div>
            </div>
            <div class='field'>
                <div class='label'>IP Address:</div>
                <div class='value'>" . htmlspecialchars($clientIP) . "</div>
            </div>
        </div>
        <div class='footer'>
            <p>Submission ID: #" . $submissionId . "</p>
            <p>This is an automated message from Arrival Cards contact form.</p>
        </div>
    </div>
</body>
</html>
";

$emailSent = sendEmail(ADMIN_EMAIL, $emailSubject, $emailBody, $email);

if (!$emailSent) {
    error_log('Failed to send contact form email notification');
    // Don't show error to user as the message is saved in database
}

// Success!
setFlashMessage(t('contact_success'), 'success');
redirect(APP_URL . '/index.php');
