# 📤 MANUAL UPLOAD INSTRUCTIONS

## Files Ready to Upload (in PRODUCTION_READY folder)

All files are committed to Git and ready in the PRODUCTION_READY folder:

✅ `analytics_tables.sql`
✅ `add_view_count.sql`  
✅ `setup_analytics.php`
✅ `add_north_korea.php`
✅ `add_north_korea_cli.php`

---

## Upload Method 1: File Manager (cPanel/Hostinger)

1. **Login to hosting control panel**
   - URL: https://hpanel.hostinger.com (or your hosting provider)
   - Login with your credentials

2. **Navigate to File Manager**
   - Go to File Manager or Files section
   - Navigate to: `/domains/arrivalcards.com/public_html/`

3. **Upload files:**
   - Upload to root directory:
     * `analytics_tables.sql`
     * `add_view_count.sql`
     * `add_north_korea.php`
     * `add_north_korea_cli.php`
   
   - Upload to `/admin/` directory:
     * `setup_analytics.php`

---

## Upload Method 2: WinSCP/FileZilla

### WinSCP
1. Download: https://winscp.net/eng/download.php
2. Install and open
3. New Site:
   - **File protocol**: FTP
   - **Host**: 101.0.92.142
   - **Port**: 21
   - **Username**: arrivalcards (or u421261620.csantro)
   - **Password**: Check SERVER_CREDENTIALS.txt
4. Login and drag files to `/domains/arrivalcards.com/public_html/`

### FileZilla
1. Download: https://filezilla-project.org/
2. Quick Connect:
   - **Host**: ftp://101.0.92.142
   - **Username**: arrivalcards
   - **Password**: From SERVER_CREDENTIALS.txt
   - **Port**: 21
3. Upload files from PRODUCTION_READY folder

---

## After Upload - Execute Scripts

### Step 1: Setup Analytics Tables
Visit: https://arrivalcards.com/admin/setup_analytics.php

Expected result:
- ✅ page_views table created
- ✅ visitor_sessions table created  
- ✅ view_count column added

### Step 2: Add North Korea
Visit: https://arrivalcards.com/add_north_korea.php

Expected result:
- ✅ North Korea country record created (ID: 207)
- ✅ 7 language translations added (EN, ES, ZH, FR, DE, IT, AR)

---

## Verify Everything Works

1. **Homepage**: https://arrivalcards.com/
   - Search for "North Korea" or filter by Asia
   - Should see 🇰🇵 North Korea card

2. **Country Details**: Click "View Details" on North Korea card
   - Should show comprehensive entry requirements
   - Test language switching

3. **Analytics Dashboard**: https://arrivalcards.com/admin/analytics.php
   - Should show dashboard without errors
   - Data will populate as visitors come

---

## Git Status

✅ All changes committed to repository:
- Commit: 3b994d4 - Analytics improvements
- Commit: dde109d - North Korea field fixes
- Pushed to: https://github.com/LawarajTeam/arrivalcards-Live.git

---

## Quick Summary

**What's Fixed:**
1. ✅ Analytics SQL - Compatible with production MySQL
2. ✅ North Korea fields - Shortened to fit column limits
3. ✅ Debug output - Better error messages in setup script

**What's Being Added:**
1. 📊 Complete analytics system (page views, sessions, tracking)
2. 🇰🇵 North Korea with comprehensive travel warnings
3. 🚨 Critical detention risk information for DPRK

**Ready to Go!** Just upload the 5 files and run the 2 setup URLs above. 🚀
