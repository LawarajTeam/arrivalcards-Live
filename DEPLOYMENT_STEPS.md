# ============================================
# DEPLOYMENT GUIDE - www.arrivalcards.com
# Quick Step-by-Step Instructions
# ============================================

## METHOD 1: FileZilla (RECOMMENDED - Easiest)

### Step 1: Download & Install FileZilla
- Download: https://filezilla-project.org/download.php?type=client
- Install FileZilla Client (free)

### Step 2: Connect to Server
Open FileZilla and enter:
- **Host:** 101.0.92.142
- **Username:** arrivalcards
- **Password:** Ijmb)%v]If
- **Port:** 21
- Click **"Quickconnect"**

### Step 3: Upload Files
1. Left panel: Navigate to your `PRODUCTION_READY` folder
2. Right panel: Navigate to `/public_html/` (or `/www/` or `/httpdocs/`)
3. Select all files in left panel
4. Right-click → Upload
5. Wait for upload to complete (check bottom panel for progress)

---

## METHOD 2: Automated PowerShell Script

### Run the automated upload:
```powershell
.\deploy_ftp.ps1
```

This will upload all files from PRODUCTION_READY folder automatically.

---

## AFTER UPLOAD - Database Setup

### Step 1: Access cPanel
- URL: https://101.0.92.142:2083
- Username: arrivalcards
- Password: Ijmb)%v]If
- Click **Login**

### Step 2: Create Database
1. Find and click **"MySQL Databases"**
2. Under "Create New Database":
   - Database Name: `arrivalcards_db` (or any name)
   - Click **"Create Database"**
3. Scroll down to "Add New User":
   - Username: `arrivalcards_user`
   - Password: Create a strong password
   - Click **"Create User"**
4. Scroll to "Add User To Database":
   - Select your user and database
   - Click **"Add"**
   - Check **"ALL PRIVILEGES"**
   - Click **"Make Changes"**

### Step 3: Import Database
1. In cPanel, click **"phpMyAdmin"**
2. Click on your database name in left sidebar
3. Click **"Import"** tab at top
4. Click **"Choose File"**
5. Select `database_complete.sql` from your computer
6. Scroll down and click **"Import"**
7. Wait for success message

### Step 4: Configure Database Connection
You have two options:

**OPTION A: Edit via cPanel File Manager:**
1. In cPanel, click **"File Manager"**
2. Navigate to `/public_html/includes/`
3. Right-click on `config.php` → Edit
4. Replace these values:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'arrivalcards_db');  // Your database name
   define('DB_USER', 'arrivalcards_user'); // Your database user
   define('DB_PASS', 'YourPassword');      // Your database password
   ```
5. Click **"Save Changes"**

**OPTION B: Edit via FileZilla:**
1. Connect via FileZilla
2. Navigate to `/public_html/includes/`
3. Download `config.php` to your computer
4. Edit with Notepad
5. Update database credentials
6. Upload back to server

---

## TESTING YOUR SITE

### Step 1: Test Database Connection
Visit: **https://www.arrivalcards.com/admin/system-test.php**
- All checks should be green ✓
- If any are red, check database credentials

### Step 2: Create Admin User
Back in phpMyAdmin:
1. Click your database name
2. Click **"SQL"** tab
3. Paste this query (CHANGE THE PASSWORD HASH):
   ```sql
   INSERT INTO admin_users (username, password_hash, email, full_name, is_active) 
   VALUES (
       'admin', 
       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
       'your@email.com',
       'Administrator',
       1
   );
   ```
4. Click **"Go"**

To generate your own password hash:
- Create a file `create_hash.php` with:
  ```php
  <?php
  echo password_hash('YourPassword123', PASSWORD_DEFAULT);
  ?>
  ```
- Upload it, visit it in browser, copy the hash
- Delete the file after

### Step 3: Login to Admin Panel
Visit: **https://www.arrivalcards.com/admin/**
- Username: admin
- Password: (the one you used in hash)
- **CHANGE PASSWORD IMMEDIATELY after first login**

### Step 4: Test Main Site
Visit: **https://www.arrivalcards.com/**
- Country cards should display
- Search should work
- Language switching should work
- Click on a country to test detail page

---

## CONFIGURE ADSENSE

1. Login to admin panel
2. Click **"AdSense"** in navigation
3. Add your Google AdSense Publisher ID
4. Add Ad Slot IDs for 6 positions
5. Set ad frequency
6. Check **"Enable AdSense"**
7. Click **"Save Settings"**

---

## SECURITY CHECKLIST

- [x] Default credentials removed from login page display
- [x] Brute force protection added (5 attempts, 15-min lockout)
- [x] Session fixation protection (session_regenerate_id on login)
- [x] CSRF token added to login form
- [x] CSRF tokens rotate after use (replay attack prevention)
- [x] Delete country requires POST + CSRF (no GET-based deletion)
- [x] admin_visa_data.php uses proper admin auth (hardcoded password removed)
- [x] SameSite=Lax cookie attribute added
- [x] getClientIP() hardened (only trusts REMOTE_ADDR unless configured)
- [x] All .sql, .log, .md files blocked via .htaccess
- [x] CSP updated with Google AdSense/Analytics domains
- [x] Permissions-Policy header added
- [ ] Changed admin password from default on production
- [ ] Database password is strong on production
- [ ] HTTPS is enabled on domain (uncomment .htaccess rules)
- [ ] Test contact form
- [ ] Test feedback buttons
- [ ] Submit sitemap to Google: https://www.arrivalcards.com/sitemap.xml.php
- [ ] Update privacy policy with your contact details

---

## TROUBLESHOOTING

### White Screen / 500 Error
- Check database credentials in `includes/config.php`
- Check error logs in cPanel

### Can't Login to Admin
- Verify admin user exists in `admin_users` table
- Reset password using phpMyAdmin

### Country Cards Not Showing
- Check database was imported correctly
- Check `countries` and `country_translations` tables have data

### Permission Errors
- In cPanel File Manager, select `includes/config.php`
- Click **Permissions**
- Set to 644

---

## YOUR SITE IS LIVE! 🎉

**Main Site:** https://www.arrivalcards.com
**Admin Panel:** https://www.arrivalcards.com/admin/

## Next Steps:
1. Set up automated database backups in cPanel
2. Monitor error logs for first 24 hours
3. Test on mobile devices
4. Submit to Google Search Console
5. Configure Google Analytics (if desired)

---

## SUPPORT

If you encounter issues:
1. Check cPanel error logs
2. Check `/error.log` file in your site root
3. Visit system test page for diagnostics
4. Verify all database tables were imported

Document created: February 10, 2026
