<?php
/**
 * Process Callback Request
 * Validates the callback form and sends an email to the admin
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(APP_URL . '/request-callback.php');
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    setFlashMessage('Security validation failed. Please try again.', 'error');
    redirect(APP_URL . '/request-callback.php');
}

// Honeypot check (spam protection)
if (!empty($_POST['website'])) {
    setFlashMessage('Thank you! Your request has been received.', 'success');
    redirect(APP_URL . '/request-callback.php');
}

// Sanitize inputs
$name                = trim($_POST['name'] ?? '');
$phone               = trim($_POST['phone'] ?? '');
$email               = trim($_POST['email'] ?? '');
$passportCountry     = trim($_POST['passport_country'] ?? '');
$destinationCountries = trim($_POST['destination_countries'] ?? '');
$tripDetails         = trim($_POST['trip_details'] ?? '');
$timeline            = trim($_POST['timeline'] ?? '');
$comments            = trim($_POST['comments'] ?? '');

// Allowed timeline values
$allowedTimelines = [
    'within_1_week'  => 'Within 1 week – urgent!',
    '2_4_weeks'      => '2 – 4 weeks',
    '1_3_months'     => '1 – 3 months',
    '3_6_months'     => '3 – 6 months',
    '6_plus_months'  => '6+ months away',
    'not_sure'       => 'Not sure yet',
];

$errors = [];

// Validate name
if (strlen($name) < 2 || strlen($name) > 100) {
    $errors[] = 'Name must be between 2 and 100 characters.';
}

// Validate phone
if (strlen($phone) < 5 || strlen($phone) > 30) {
    $errors[] = 'Please provide a valid phone number.';
}

// Validate email
if (!isValidEmail($email)) {
    $errors[] = 'Please provide a valid email address.';
}

// Validate passport country
if (strlen($passportCountry) < 2 || strlen($passportCountry) > 100) {
    $errors[] = 'Please enter your home/passport country.';
}

// Validate destination countries
if (strlen($destinationCountries) < 2 || strlen($destinationCountries) > 200) {
    $errors[] = 'Please enter at least one destination country.';
}

// Validate trip details
if (strlen($tripDetails) < 10 || strlen($tripDetails) > 1000) {
    $errors[] = 'Trip details must be between 10 and 1000 characters.';
}

// Validate timeline
if (!array_key_exists($timeline, $allowedTimelines)) {
    $errors[] = 'Please select a travel timeline.';
}

// Validate optional comments length
if (strlen($comments) > 1000) {
    $errors[] = 'Additional comments must be 1000 characters or fewer.';
}

if (!empty($errors)) {
    setFlashMessage(implode(' ', $errors), 'error');
    redirect(APP_URL . '/request-callback.php');
}

// Rate limiting – 1 request per IP per 10 minutes
$clientIP = getClientIP();
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM contact_submissions
    WHERE ip_address = ? AND submitted_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE)
");
$stmt->execute([$clientIP]);
if ((int) $stmt->fetchColumn() >= 3) {
    setFlashMessage('You have made several requests recently. Please wait a few minutes before trying again.', 'warning');
    redirect(APP_URL . '/request-callback.php');
}

// Persist to the contact_submissions table (reuse existing table)
try {
    $summaryMessage = sprintf(
        "[CALLBACK REQUEST]\nPhone: %s\nPassport country: %s\nDestinations: %s\nTimeline: %s\nTrip details: %s\nComments: %s",
        $phone,
        $passportCountry,
        $destinationCountries,
        $allowedTimelines[$timeline],
        $tripDetails,
        $comments ?: 'N/A'
    );

    $stmt = $pdo->prepare("
        INSERT INTO contact_submissions (name, email, message, ip_address, user_agent)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $name,
        $email,
        $summaryMessage,
        $clientIP,
        $_SERVER['HTTP_USER_AGENT'] ?? '',
    ]);
    $submissionId = $pdo->lastInsertId();
} catch (PDOException $e) {
    error_log('Callback request DB error: ' . $e->getMessage());
    setFlashMessage('Something went wrong. Please try again.', 'error');
    redirect(APP_URL . '/request-callback.php');
}

// Build and send email
$timelineLabel = htmlspecialchars($allowedTimelines[$timeline]);

$emailSubject = '📞 New Visa Agent Callback Request – Arrival Cards';
$emailBody = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body  { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .wrap { max-width: 620px; margin: 0 auto; padding: 20px; }
        .hdr  { background: #2563eb; color: #fff; padding: 24px 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .hdr h2 { margin: 0; font-size: 22px; }
        .hdr p  { margin: 6px 0 0; font-size: 14px; opacity: .85; }
        .body { background: #f8fafc; padding: 24px 20px; border: 1px solid #e2e8f0; border-top: 0; }
        .field  { margin-bottom: 16px; }
        .label  { font-weight: bold; color: #1e293b; font-size: 13px; text-transform: uppercase; letter-spacing: .5px; }
        .value  { color: #334155; padding: 10px 12px; background: #fff; border-left: 4px solid #2563eb; margin-top: 4px; border-radius: 0 4px 4px 0; }
        .urgent { border-left-color: #ef4444; }
        .ftr    { text-align: center; margin-top: 20px; color: #94a3b8; font-size: 12px; }
        .badge  { display: inline-block; background: #ef4444; color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 12px; margin-top: 6px; }
    </style>
</head>
<body>
<div class='wrap'>
    <div class='hdr'>
        <h2>&#128222; Visa Agent Callback Request</h2>
        <p>A traveller has requested a callback from a visa specialist.</p>
        " . ($timeline === 'within_1_week' ? "<div class='badge'>⚡ URGENT – Travel within 1 week</div>" : "") . "
    </div>
    <div class='body'>
        <div class='field'>
            <div class='label'>Full Name</div>
            <div class='value'>" . htmlspecialchars($name) . "</div>
        </div>
        <div class='field'>
            <div class='label'>Phone (call them here)</div>
            <div class='value " . ($timeline === 'within_1_week' ? 'urgent' : '') . "'><a href='tel:" . htmlspecialchars($phone) . "'>" . htmlspecialchars($phone) . "</a></div>
        </div>
        <div class='field'>
            <div class='label'>Email</div>
            <div class='value'><a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a></div>
        </div>
        <div class='field'>
            <div class='label'>Passport / Home Country</div>
            <div class='value'>" . htmlspecialchars($passportCountry) . "</div>
        </div>
        <div class='field'>
            <div class='label'>Destination Countries</div>
            <div class='value'>" . htmlspecialchars($destinationCountries) . "</div>
        </div>
        <div class='field'>
            <div class='label'>Travel Timeline</div>
            <div class='value'>" . $timelineLabel . "</div>
        </div>
        <div class='field'>
            <div class='label'>Trip Details</div>
            <div class='value'>" . nl2br(htmlspecialchars($tripDetails)) . "</div>
        </div>
        <div class='field'>
            <div class='label'>Additional Comments</div>
            <div class='value'>" . ($comments ? nl2br(htmlspecialchars($comments)) : '<em style=\"color:#94a3b8\">None provided</em>') . "</div>
        </div>
        <div class='field'>
            <div class='label'>Submitted</div>
            <div class='value'>" . date('F j, Y \\a\\t g:i A') . " &nbsp;|&nbsp; IP: " . htmlspecialchars($clientIP) . "</div>
        </div>
    </div>
    <div class='ftr'>
        <p>Submission ID: #" . $submissionId . " &nbsp;&mdash;&nbsp; Arrival Cards callback system</p>
    </div>
</div>
</body>
</html>
";

$emailSent = sendEmail(ADMIN_EMAIL, $emailSubject, $emailBody, $email);

if (!$emailSent) {
    error_log('Failed to send callback request email. Submission ID: ' . $submissionId);
}

setFlashMessage('Thank you, ' . htmlspecialchars($name) . '! We\'ve received your request and will call you back shortly.', 'success');
redirect(APP_URL . '/request-callback.php');
