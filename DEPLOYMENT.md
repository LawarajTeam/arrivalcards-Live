# Production Deployment Guide

## Pre-Deployment Checklist

### 1. Database Setup
- [ ] Create MySQL database on production server
- [ ] Import `database_complete.sql` using phpMyAdmin or MySQL CLI
- [ ] Create database user with appropriate permissions
- [ ] Note database credentials (host, name, username, password)

### 2. Configuration Files

#### Create `includes/config.php` on production server:
```php
<?php
// Production Database Configuration
define('DB_HOST', 'localhost');  // Usually 'localhost' for shared hosting
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection error. Please contact support.");
}

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);  // Enable if using HTTPS
session_start();
?>
```

### 3. Admin Access
Create admin user by running this SQL in phpMyAdmin:
```sql
-- Default password is: admin123 (CHANGE THIS IMMEDIATELY!)
INSERT INTO admin_users (username, password_hash, email, full_name, is_active) 
VALUES (
    'admin', 
    '$2y$10$YourHashedPasswordHere', 
    'your@email.com', 
    'Administrator', 
    1
);
```

Or use this PHP script once (then delete it):
```php
<?php
require_once 'includes/config.php';
$password = 'YourSecurePassword123!';
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash, email, full_name) VALUES (?, ?, ?, ?)");
$stmt->execute(['admin', $hash, 'your@email.com', 'Administrator']);
echo "Admin created successfully!";
?>
```

## FTP Deployment Steps

### 1. Connect to Your Server
- **Host:** your-domain.com (or IP address)
- **Username:** Your FTP username
- **Password:** Your FTP password
- **Port:** 21 (standard FTP) or 22 (SFTP)

### 2. Upload Files
Upload ALL files from your local directory **EXCEPT**:
- `.git/` folder
- `.vscode/` folder
- `includes/config.php` (create this separately on server)
- `*.md` documentation files (optional, not needed for production)
- Test files like `test_*.php`, `check_*.php`, `setup_*.php`

### 3. Required Directories
Ensure these directories exist and are writable (chmod 755 or 775):
- `/assets/`
- `/assets/css/`
- `/assets/js/`
- `/assets/images/`
- `/assets/images/flags/`
- `/admin/`
- `/includes/`

### 4. File Permissions
Set correct permissions:
- **Directories:** 755 (or 775)
- **PHP files:** 644 (or 755 if needed)
- **config.php:** 640 (read-only for security)

### 5. Post-Upload Tasks

1. **Test database connection:**
   - Visit: `https://yourdomain.com/admin/system-test.php`
   - Verify all green checkmarks

2. **Create admin account:**
   - Run the admin creation script above
   - Delete the script immediately after

3. **Test admin panel:**
   - Visit: `https://yourdomain.com/admin/`
   - Login with your credentials
   - Check all features work

4. **Test main site:**
   - Visit: `https://yourdomain.com/`
   - Verify country cards display
   - Test search functionality
   - Check language switching
   - Test country detail pages

5. **Configure AdSense:**
   - Login to admin panel
   - Navigate to AdSense settings
   - Add your AdSense code and settings
   - Enable AdSense

## Security Checklist

- [ ] Change default admin password immediately
- [ ] Keep `includes/config.php` secure (not in git)
- [ ] Enable HTTPS/SSL certificate
- [ ] Set up regular database backups
- [ ] Update `robots.txt` if needed
- [ ] Test contact form spam protection
- [ ] Check error reporting is OFF in production
- [ ] Set secure session settings
- [ ] Block access to sensitive files in .htaccess

## .htaccess Security (already included)
The project includes `.htaccess` with:
- PHP error display off
- Index file redirection
- Security headers
- URL rewriting (if needed)

## Monitoring & Maintenance

### Regular Tasks:
1. **Database Backups:** Export database weekly
2. **Updates:** Check and update visa information
3. **Analytics:** Monitor view counts and feedback
4. **Security:** Keep PHP and MySQL updated
5. **Content:** Update "Last Verified" dates regularly

### Performance:
- Enable PHP OPcache on server
- Use CDN for assets (optional)
- Enable GZIP compression
- Monitor page load times

## Troubleshooting

### White Screen / 500 Error
- Check `includes/config.php` database credentials
- Check PHP error logs
- Verify database is imported correctly
- Check file permissions

### Can't Login to Admin
- Verify admin user exists in database
- Check `admin_users` table
- Try password reset script
- Check session permissions

### Images Not Displaying
- Verify `/assets/images/flags/` exists
- Check file permissions (755)
- Verify .htaccess isn't blocking

### Database Connection Failed
- Verify database exists
- Check credentials in config.php
- Confirm database user has permissions
- Check MySQL service is running

## Support

For issues or questions:
- GitHub Issues: https://github.com/LawarajTeam/arrivalcards-Live/issues
- Check server error logs for detailed errors

## Post-Deployment

After successful deployment:
1. ✅ Test all core functionality
2. ✅ Set up Google AdSense
3. ✅ Configure analytics
4. ✅ Add your domain to Google Search Console
5. ✅ Submit sitemap: `https://yourdomain.com/sitemap.xml.php`
6. ✅ Test on mobile devices
7. ✅ Update privacy policy with your details
8. ✅ Set up automated backups
9. ✅ Monitor error logs for first 24 hours

Your site should now be live! 🎉
