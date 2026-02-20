<?php
/**
 * Report Error Page
 * Allow users to report incorrect links or information
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Report an Error';
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $country_name = trim($_POST['country_name'] ?? '');
    $error_type = trim($_POST['error_type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $correct_url = trim($_POST['correct_url'] ?? '');
    $user_email = trim($_POST['user_email'] ?? '');
    
    // Validation
    $errors = [];
    if (empty($country_name)) $errors[] = 'Country name is required';
    if (empty($error_type)) $errors[] = 'Error type is required';
    if (empty($description)) $errors[] = 'Description is required';
    
    if (empty($errors)) {
        try {
            // Insert into contact_submissions table
            $stmt = $pdo->prepare("
                INSERT INTO contact_submissions (name, email, subject, message, submitted_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            
            $subject = "Error Report: $error_type - $country_name";
            $message_text = "Country: $country_name\n";
            $message_text .= "Error Type: $error_type\n";
            $message_text .= "Description: $description\n";
            if (!empty($correct_url)) {
                $message_text .= "Suggested Correct URL: $correct_url\n";
            }
            if (!empty($user_email)) {
                $message_text .= "Contact Email: $user_email\n";
            }
            
            $stmt->execute([
                'Error Reporter',
                $user_email ?: 'noreply@arrivalcards.com',
                $subject,
                $message_text
            ]);
            
            $message = 'Thank you! Your error report has been submitted successfully.';
            $messageType = 'success';
            
            // Clear form
            $_POST = [];
            
        } catch (PDOException $e) {
            $message = 'Sorry, there was an error submitting your report. Please try again.';
            $messageType = 'error';
        }
    } else {
        $message = implode(', ', $errors);
        $messageType = 'error';
    }
}

// Get all countries for dropdown
$countries = getCountries();
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<section class="page-header">
    <div class="container">
        <h1>Report an Error</h1>
        <p>Help us keep our information accurate by reporting incorrect links or outdated information</p>
    </div>
</section>

<section class="report-error-section">
    <div class="container">
        <div class="report-form-container">
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo e($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="report-form">
                <div class="form-group">
                    <label for="country_name">Country *</label>
                    <select name="country_name" id="country_name" required class="form-control">
                        <option value="">-- Select Country --</option>
                        <?php 
                        $preselectedCountry = $_POST['country_name'] ?? $_GET['country'] ?? '';
                        foreach ($countries as $country): ?>
                            <option value="<?php echo e($country['country_name']); ?>" 
                                <?php echo ($preselectedCountry === $country['country_name']) ? 'selected' : ''; ?>>
                                <?php echo e($country['country_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="error_type">Type of Error *</label>
                    <select name="error_type" id="error_type" required class="form-control">
                        <option value="">-- Select Error Type --</option>
                        <option value="Broken Link" <?php echo (isset($_POST['error_type']) && $_POST['error_type'] === 'Broken Link') ? 'selected' : ''; ?>>Broken Link (404 Error)</option>
                        <option value="Wrong URL" <?php echo (isset($_POST['error_type']) && $_POST['error_type'] === 'Wrong URL') ? 'selected' : ''; ?>>Wrong URL</option>
                        <option value="Outdated Information" <?php echo (isset($_POST['error_type']) && $_POST['error_type'] === 'Outdated Information') ? 'selected' : ''; ?>>Outdated Information</option>
                        <option value="Incorrect Visa Type" <?php echo (isset($_POST['error_type']) && $_POST['error_type'] === 'Incorrect Visa Type') ? 'selected' : ''; ?>>Incorrect Visa Type</option>
                        <option value="Other" <?php echo (isset($_POST['error_type']) && $_POST['error_type'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Description of Error *</label>
                    <textarea name="description" id="description" rows="5" required class="form-control" 
                        placeholder="Please describe the error you found..."><?php echo e($_POST['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="correct_url">Correct URL (if known)</label>
                    <input type="url" name="correct_url" id="correct_url" class="form-control" 
                        placeholder="https://..." value="<?php echo e($_POST['correct_url'] ?? ''); ?>">
                    <small class="form-text">If you know the correct URL, please provide it</small>
                </div>
                
                <div class="form-group">
                    <label for="user_email">Your Email (optional)</label>
                    <input type="email" name="user_email" id="user_email" class="form-control" 
                        placeholder="your@email.com" value="<?php echo e($_POST['user_email'] ?? ''); ?>">
                    <small class="form-text">In case we need to follow up with you</small>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg">Submit Report</button>
            </form>
            
            <div class="info-box">
                <h3>Why Report Errors?</h3>
                <p>Your reports help us maintain accurate and up-to-date visa information for travelers worldwide. We review all submissions and update our database accordingly.</p>
                <p><strong>Note:</strong> We typically review and respond to reports within 48 hours.</p>
            </div>
        </div>
    </div>
</section>

<style>
.page-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: var(--spacing-2xl) 0;
    text-align: center;
}

.page-header h1 {
    color: white;
    margin-bottom: var(--spacing-sm);
}

.page-header p {
    font-size: var(--font-size-lg);
    opacity: 0.95;
}

.report-error-section {
    padding: var(--spacing-2xl) 0;
}

.report-form-container {
    max-width: 700px;
    margin: 0 auto;
}

.report-form {
    background: var(--bg-primary);
    padding: var(--spacing-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    margin-bottom: var(--spacing-xl);
}

.form-group {
    margin-bottom: var(--spacing-lg);
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--text-primary);
}

.form-control {
    width: 100%;
    padding: var(--spacing-md);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: var(--font-size-base);
    font-family: inherit;
    transition: border-color var(--transition-fast);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

.form-text {
    display: block;
    margin-top: var(--spacing-xs);
    font-size: var(--font-size-sm);
    color: var(--text-secondary);
}

.btn {
    display: inline-block;
    padding: var(--spacing-md) var(--spacing-xl);
    border: none;
    border-radius: var(--radius-md);
    font-size: var(--font-size-base);
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: all var(--transition-fast);
    text-decoration: none;
}

.btn-primary {
    background-color: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-lg {
    padding: var(--spacing-md) var(--spacing-2xl);
    font-size: var(--font-size-lg);
}

.alert {
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-lg);
}

.alert-success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #10b981;
}

.alert-error {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #ef4444;
}

.info-box {
    background: var(--bg-secondary);
    padding: var(--spacing-lg);
    border-radius: var(--radius-md);
    border-left: 4px solid var(--primary-color);
}

.info-box h3 {
    margin-bottom: var(--spacing-md);
    color: var(--primary-color);
}

.info-box p {
    margin-bottom: var(--spacing-sm);
    color: var(--text-secondary);
}

.info-box p:last-child {
    margin-bottom: 0;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
