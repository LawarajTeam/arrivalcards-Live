<?php
/**
 * Admin Login Page
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already logged in
if (isAdminLoggedIn()) {
    redirect(APP_URL . '/admin/index.php');
}

$error = '';

// Process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $robotCheck = $_POST['robot_check'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } elseif ($robotCheck !== 'human') {
        $error = 'Please confirm you are not a robot.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? AND is_active = 1");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Login successful
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_email'] = $admin['email'];
                
                // Update last login
                $updateStmt = $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
                $updateStmt->execute([$admin['id']]);
                
                // Log the login
                logAdminAction('Admin Login');
                
                redirect(APP_URL . '/admin/index.php');
            } else {
                $error = 'Invalid username or password.';
                // Log failed attempt
                error_log("Failed login attempt for username: $username from IP: " . getClientIP());
            }
        } catch (PDOException $e) {
            $error = 'An error occurred. Please try again.';
            error_log('Admin login error: ' . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Arrival Cards</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        }
        .login-container {
            background: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-logo {
            width: 200px;
            margin-bottom: 1rem;
        }
        .login-error {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #ef4444;
        }
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-secondary);
            padding: 4px;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }
        .password-toggle:hover {
            color: var(--primary-color);
        }
        .robot-check {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 1.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .robot-check:hover {
            border-color: var(--primary-color);
            background: #eff6ff;
        }
        .robot-check input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .robot-check label {
            cursor: pointer;
            font-weight: 500;
            user-select: none;
        }
        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-secondary);
            padding: 4px;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }
        .password-toggle:hover {
            color: var(--primary-color);
        }
        .robot-check {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 1.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .robot-check:hover {
            border-color: var(--primary-color);
            background: #eff6ff;
        }
        .robot-check input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .robot-check label {
            cursor: pointer;
            font-weight: 500;
            user-select: none;
        }
    </style>
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-header">
            <img src="<?php echo APP_URL; ?>/assets/images/logo.svg" alt="Arrival Cards" class="login-logo">
            <h1 style="margin-bottom: 0.5rem;">Admin Login</h1>
            <p style="color: var(--text-secondary);">Access the administrative panel</p>
        </div>
        
        <?php if ($error): ?>
            <div class="login-error">
                <?php echo e($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    class="form-input"
                    required
                    autofocus
                    value="<?php echo e($_POST['username'] ?? ''); ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="password-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input"
                        required
                        style="padding-right: 45px;"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Show password">
                        <svg id="eyeIcon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="robot-check" onclick="document.getElementById('robotCheckbox').click();">
                <input 
                    type="checkbox" 
                    id="robotCheckbox" 
                    name="robot_check" 
                    value="human"
                    required
                    onclick="event.stopPropagation();"
                >
                <label for="robotCheckbox" onclick="event.stopPropagation();">I'm not a robot 🤖</label>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Sign In
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </button>
        </form>
        
        <div class="back-link">
            <a href="<?php echo APP_URL; ?>/index.php" style="color: var(--primary-color);">
                ← Back to Website
            </a>
        </div>
        
        <p style="text-align: center; margin-top: 2rem; font-size: 0.875rem; color: var(--text-light);">
            Default credentials: admin / admin123<br>
            <strong>Please change these immediately!</strong>
        </p>
    </div>
    
    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = '<path d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"/><path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>';
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = '<path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>';
        }
    }
    </script>
</body>
</html>
