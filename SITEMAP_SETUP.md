# Dynamic Sitemap Setup Guide

## Overview

The sitemap is now generated once per day by a cron job, which includes:
- ✅ Homepage (all language versions)
- ✅ All country pages (195+ countries × 7 languages)
- ✅ All static pages (best-passports, compare, faq, about, contact, privacy, terms, report-error)
- ✅ Automatic image sitemap for country flags
- ✅ hreflang tags for multi-language SEO

## Files Created

1. **admin/generate_sitemap_cron.php** - The cron script that generates sitemap.xml
2. **sitemap.xml** - Static XML file (generated daily, served to Google)
3. **robots.txt** - Updated to reference sitemap.xml

## Setup Instructions

### Option 1: Linux/cPanel Cron Job (Recommended)

#### Via cPanel:
1. Log into your cPanel
2. Go to **Cron Jobs**
3. Click **Add New Cron Job**
4. Set timing to run daily at 2 AM (adjust as needed):
   - **Minute:** 0
   - **Hour:** 2
   - **Day:** *
   - **Month:** *
   - **Weekday:** *

5. Enter the command:
```bash
curl -s https://arrivalcards.com/admin/generate_sitemap_cron.php >> /home/username/sitemap_cron.log 2>&1
```

Or using wget:
```bash
wget -q -O - https://arrivalcards.com/admin/generate_sitemap_cron.php >> /home/username/sitemap_cron.log 2>&1
```

#### Via SSH (if you have shell access):
```bash
# Open crontab
crontab -e

# Add this line (generates sitemap daily at 2 AM):
0 2 * * * curl -s https://arrivalcards.com/admin/generate_sitemap_cron.php >> /path/to/sitemap_cron.log 2>&1

# Or with wget:
0 2 * * * wget -q -O - https://arrivalcards.com/admin/generate_sitemap_cron.php >> /path/to/sitemap_cron.log 2>&1
```

### Option 2: Manual Trigger

You can manually regenerate the sitemap anytime by visiting:
```
https://arrivalcards.com/admin/generate_sitemap_cron.php
```

**Requirements:** You must be logged into the admin panel for this to work.

The script will return JSON with success confirmation:
```json
{
  "success": true,
  "message": "Sitemap generated successfully",
  "total_urls": 1847,
  "countries": 195,
  "file_size": 245123,
  "timestamp": "2026-04-15 14:32:00"
}
```

### Option 3: Add to Admin Panel (Optional)

You can add a button to regenerate the sitemap from the admin dashboard. Update `admin/index.php` to include:

```php
<?php if (isset($_POST['regenerate_sitemap'])): ?>
    <?php
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $errors[] = 'Invalid CSRF token';
        } else {
            exec('php ' . __DIR__ . '/generate_sitemap_cron.php');
            $successMessage = 'Sitemap regenerated successfully!';
        }
    ?>
<?php endif; ?>

<!-- In admin dashboard -->
<form method="POST" style="display: inline;">
    <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
    <button type="submit" name="regenerate_sitemap" class="btn btn-primary">
        🔄 Regenerate Sitemap Now
    </button>
</form>
```

## Verification

### Check Generated Sitemap

1. **Verify file exists:**
```bash
ls -lh /path/to/arrivalcards/sitemap.xml
```

2. **Check file size (should be > 100KB):**
```bash
du -h /path/to/arrivalcards/sitemap.xml
```

3. **View in browser:**
Visit: `https://arrivalcards.com/sitemap.xml`

### Monitor Cron Execution

Check the log file to see when sitemaps were generated:
```bash
tail -f /path/to/sitemap_cron.log
```

Expected output:
```
2026-04-15 02:00:05 - Sitemap generated successfully
2026-04-16 02:00:08 - Sitemap generated successfully
2026-04-17 02:00:06 - Sitemap generated successfully
```

### Submit to Google Search Console

1. Go to Google Search Console
2. Select your property (arrivalcards.com)
3. Go to **Sitemaps** in the left menu
4. Click **Add/Test Sitemap**
5. Enter: `https://arrivalcards.com/sitemap.xml`
6. Click **Submit**

### Submit to Bing Webmaster Tools

1. Go to Bing Webmaster Tools
2. Go to **Sitemaps**
3. Click **Submit Sitemap**
4. Enter: `https://arrivalcards.com/sitemap.xml`
5. Click **Submit**

## Troubleshooting

### Issue: Cron job not running

**Check:**
1. Is your hosting provider allowing cron jobs? (Some shared hosts restrict them)
2. Is the URL accessible? Try visiting it manually in a browser
3. Check cron logs in cPanel or SSH

**Solution:**
- Contact your hosting provider to enable cron jobs
- Or use an external cron service like cron-job.org

### Issue: sitemap.xml not updating

**Check:**
1. Verify cron is being executed (check log file)
2. Make sure `sitemap.xml` file has write permissions (644 or 666)
3. Check file path is correct

**Solution:**
```bash
# Set proper permissions
chmod 666 /path/to/arrivalcards/sitemap.xml

# Or regenerate manually
curl https://arrivalcards.com/admin/generate_sitemap_cron.php
```

### Issue: Large file size

The sitemap with 195+ countries × 7 languages = ~1,300+ URLs. Expected file size: 200-400KB

If larger, check:
- Are inactive countries being included? (They shouldn't be)
- Database query is returning duplicates?

## Performance Notes

- **Generation time:** ~2-5 seconds (depending on database size)
- **File size:** ~250-350 KB
- **Compression:** Recommended to gzip the file in .htaccess for faster delivery
- **Cache:** Search engines cache the sitemap, new URLs may take 24-48 hours to appear

## Advanced: Sitemap Index

For very large sites, you can create a sitemap index to split sitemaps by country range:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>https://arrivalcards.com/sitemap-countries-a-g.xml</loc>
        <lastmod>2026-04-15</lastmod>
    </sitemap>
    <sitemap>
        <loc>https://arrivalcards.com/sitemap-countries-h-n.xml</loc>
        <lastmod>2026-04-15</lastmod>
    </sitemap>
    <sitemap>
        <loc>https://arrivalcards.com/sitemap-countries-o-z.xml</loc>
        <lastmod>2026-04-15</lastmod>
    </sitemap>
</sitemapindex>
```

This is optional but recommended for enterprise SEO.

## Summary

- ✅ Cron job runs daily at 2 AM
- ✅ Generates complete sitemap with all countries and pages
- ✅ Includes multi-language hreflang tags
- ✅ Automatically updated in robots.txt
- ✅ Can be manually triggered from admin panel
- ✅ Logged for monitoring
- ✅ ~1,300+ URLs included
- ✅ Ready to submit to Google & Bing

## Questions?

If you have issues:
1. Check the sitemap_cron.log file
2. Verify the sitemap renders properly in browser
3. Contact your hosting provider about cron job support
