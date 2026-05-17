<?php
/**
 * ONE-TIME admin password reset utility.
 * DELETE THIS FILE immediately after use.
 * Protected by a hard-coded token in the URL.
 */

// Hard-coded access token — must match URL param ?token=
define('ACCESS_TOKEN', 'AC-reset-2026-X9k7');

if (($_GET['token'] ?? '') !== ACCESS_TOKEN) {
    http_response_code(403);
    exit('403 Forbidden');
}

require_once __DIR__ . '/../includes/config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username    = trim($_POST['username'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';

    if (strlen($username) < 1 || strlen($newPassword) < 8) {
        $message = 'Username required; password must be 8+ characters.';
    } else {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE username = ?");
        $rows = $stmt->execute([$hash, $username]);

        if ($stmt->rowCount() > 0) {
            $message = '✅ Password updated for user <strong>' . htmlspecialchars($username) . '</strong>. <a href="/admin/login.php">Go to Login →</a>';
        } else {
            // User doesn't exist — create one
            $insert = $pdo->prepare("INSERT INTO admin_users (username, password_hash, is_active) VALUES (?, ?, 1)");
            $insert->execute([$username, $hash]);
            $message = '✅ Admin user <strong>' . htmlspecialchars($username) . '</strong> created. <a href="/admin/login.php">Go to Login →</a>';
        }
    }
}

// List existing admin usernames (no passwords)
$users = $pdo->query("SELECT username, is_active, last_login FROM admin_users")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Password Reset</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 500px; margin: 3rem auto; padding: 0 1rem; }
        h1 { color: #dc2626; }
        .warn { background: #fef3c7; padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: .9rem; }
        label { display: block; margin-top: .75rem; font-weight: 600; font-size: .9rem; }
        input[type=text], input[type=password] { width: 100%; padding: .5rem; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; margin-top: .25rem; }
        button { margin-top: 1rem; background: #dc2626; color: #fff; border: none; padding: .6rem 1.4rem; border-radius: 6px; cursor: pointer; font-size: 1rem; }
        .msg { margin-top: 1rem; padding: .75rem 1rem; background: #d1fae5; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; font-size: .85rem; }
        th, td { text-align: left; padding: .4rem .6rem; border-bottom: 1px solid #e5e7eb; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
<h1>⚠ Admin Password Reset</h1>
<div class="warn">Delete this file immediately after use!</div>

<?php if ($message): ?>
    <div class="msg"><?= $message ?></div>
<?php endif; ?>

<form method="POST" action="?token=<?= htmlspecialchars(ACCESS_TOKEN) ?>">
    <label>Admin Username
        <input type="text" name="username" required autocomplete="off">
    </label>
    <label>New Password (8+ chars)
        <input type="password" name="new_password" required minlength="8">
    </label>
    <button type="submit">Set Password</button>
</form>

<?php if ($users): ?>
<table>
    <tr><th>Username</th><th>Active</th><th>Last Login</th></tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= $u['is_active'] ? 'Yes' : 'No' ?></td>
        <td><?= htmlspecialchars($u['last_login'] ?? '—') ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
</body>
</html>
