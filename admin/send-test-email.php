<?php
/**
 * Admin: Send Test Email
 * Sends a test email to ADMIN_EMAIL to verify mail configuration
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$sent   = false;
$error  = '';

if (isset($_GET['send'])) {
    $subject = '✅ Test Email – Arrival Cards Mail Config Check';
    $body = "
<!DOCTYPE html><html><head><meta charset='UTF-8'>
<style>body{font-family:Arial,sans-serif;color:#333;} .box{max-width:500px;margin:40px auto;padding:30px;border:1px solid #e2e8f0;border-radius:8px;background:#f8fafc;} h2{color:#2563eb;} .ok{color:#16a34a;font-weight:bold;font-size:18px;}</style>
</head><body>
<div class='box'>
    <h2>Arrival Cards – Test Email</h2>
    <p class='ok'>&#10003; Mail is working correctly!</p>
    <p>This test was triggered from the admin panel.</p>
    <hr style='border:none;border-top:1px solid #e2e8f0;margin:20px 0'>
    <p style='font-size:12px;color:#64748b'>
        Sent to: <strong>" . htmlspecialchars(ADMIN_EMAIL) . "</strong><br>
        From: <strong>" . htmlspecialchars(SMTP_FROM_NAME . ' &lt;' . SMTP_FROM . '&gt;') . "</strong><br>
        Time: <strong>" . date('F j, Y \a\t g:i:s A') . "</strong><br>
        Server: <strong>" . htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'unknown') . "</strong>
    </p>
</div>
</body></html>";

    if (sendEmail(ADMIN_EMAIL, $subject, $body)) {
        $sent = true;
    } else {
        $error = 'mail() returned false – check your server\'s mail/SMTP configuration.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Test Email – Admin</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>
        .test-card { max-width: 520px; margin: 60px auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 40px; text-align: center; }
        .test-card h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        .test-card p  { color: #64748b; margin-bottom: 1.5rem; }
        .alert-success { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; border-radius:8px; padding:14px 18px; margin-bottom:1.5rem; }
        .alert-error   { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; border-radius:8px; padding:14px 18px; margin-bottom:1.5rem; }
        .btn-send { display:inline-flex; align-items:center; gap:8px; background:#2563eb; color:#fff; padding:12px 28px; border-radius:8px; text-decoration:none; font-weight:600; font-size:15px; }
        .btn-send:hover { background:#1e40af; }
        .meta { font-size:13px; color:#94a3b8; margin-top:1.5rem; }
    </style>
</head>
<body>
<?php include __DIR__ . '/includes/admin_header.php'; ?>
<div class="test-card">
    <h1>📧 Send Test Email</h1>
    <p>Sends a test message to <strong><?php echo e(ADMIN_EMAIL); ?></strong> to confirm your mail setup is working.</p>

    <?php if ($sent): ?>
        <div class="alert-success">
            ✅ Email sent successfully to <strong><?php echo e(ADMIN_EMAIL); ?></strong>! Check your inbox.
        </div>
    <?php elseif ($error): ?>
        <div class="alert-error">
            ❌ Failed to send: <?php echo e($error); ?>
        </div>
    <?php endif; ?>

    <a href="?send=1" class="btn-send">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.37 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 5.53 5.53l1.62-1.85a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        Send Test Email Now
    </a>

    <p class="meta">
        SMTP_FROM: <?php echo e(SMTP_FROM); ?><br>
        SMTP_HOST: <?php echo e(SMTP_HOST); ?>:<?php echo e(SMTP_PORT); ?>
    </p>
</div>
</body>
</html>
