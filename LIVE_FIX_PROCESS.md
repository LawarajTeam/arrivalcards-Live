# How to Run the Visa Data Fix on Live Server

## What This Fixes
1. **visa_type** corrected for all 192 countries (Australian passport perspective)
2. **94 country-specific overrides** replacing boilerplate text with real data
3. **Fake URLs** (`www.gov.xxx/...`) replaced with real government URLs

---

## Process (5 minutes)

### Step 1 — Back Up Your Database
1. Log into **cPanel** at your hosting provider
2. Open **phpMyAdmin**
3. Click your database (`arrivalcards_db`) in the left sidebar
4. Click **Export** tab → Quick method → **Go**
5. Save the `.sql` file somewhere safe

### Step 2 — Upload the Fix Script
1. In **cPanel**, open **File Manager**
2. Navigate to `public_html/`
3. Click **Upload** → upload `run_visa_fix.php` from your local project folder
4. Confirm the file appears in `public_html/` alongside `index.php`

### Step 3 — Run the Fix
1. Open your browser and go to:
   ```
   https://www.arrivalcards.com/run_visa_fix.php
   ```
2. Enter the security code: `FixVisaData2026!`
   *(Change this in the file before uploading if you prefer a different code)*
3. **Preview page** — review the current state of your data
4. Click **"Apply All Fixes Now"** and confirm
5. Wait for the execution log to appear (takes a few seconds)
6. Verify the spot-check table shows correct data for FRA, DEU, GBR, USA, etc.

### Step 4 — Delete the Fix Script
**Do this immediately after running:**
1. Go back to **cPanel → File Manager → public_html/**
2. Right-click `run_visa_fix.php` → **Delete**
3. Confirm deletion

### Step 5 — Verify on the Live Site
Visit these pages and check the data looks correct:
- https://www.arrivalcards.com/country.php?code=FRA (should show visa_free, Schengen 90 days)
- https://www.arrivalcards.com/country.php?code=GBR (should show visa_free, 6 months)
- https://www.arrivalcards.com/country.php?code=USA (should show evisa/ESTA, 90 days VWP)
- https://www.arrivalcards.com/country.php?code=CHN (should show visa_required)
- https://www.arrivalcards.com/country.php?code=NZL (should show visa_free, indefinite)

---

## If Something Goes Wrong
The fix runs inside a database transaction. If any error occurs, **all changes are automatically rolled back** — your data stays untouched.

To manually restore from backup:
1. Open **phpMyAdmin**
2. Click your database
3. Click **Import** tab → Choose the backup `.sql` file from Step 1 → **Go**

---

## Alternative: Import SQL File via phpMyAdmin
If you prefer not to use the PHP script, you can import the raw SQL file instead:

1. Open **phpMyAdmin** → select `arrivalcards_db`
2. Click **Import** tab
3. Choose File → select `fix_visa_data_australian.sql`
4. Click **Go**
5. Run the verification queries at the bottom of the SQL file in the **SQL** tab
