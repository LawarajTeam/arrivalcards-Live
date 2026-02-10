# ============================================
# Production Deployment Preparation Script
# Creates a clean copy of files ready for FTP upload
# ============================================

Write-Host "===========================================`n" -ForegroundColor Cyan
Write-Host "  Arrival Cards - Production Prep`n" -ForegroundColor Cyan
Write-Host "===========================================`n" -ForegroundColor Cyan

$sourceDir = Get-Location
$prodDir = Join-Path $sourceDir "PRODUCTION_READY"

# Create production directory
if (Test-Path $prodDir) {
    Write-Host "Removing existing PRODUCTION_READY folder..." -ForegroundColor Yellow
    Remove-Item $prodDir -Recurse -Force
}

Write-Host "Creating PRODUCTION_READY folder...`n" -ForegroundColor Green
New-Item -ItemType Directory -Path $prodDir | Out-Null

# Files and folders to EXCLUDE from production
$excludePatterns = @(
    '.git',
    '.vscode',
    '.idea',
    'node_modules',
    'PRODUCTION_READY',
    '*.md',
    'test_*.php',
    'check_*.php',
    'setup_*.php',
    'verify_*.php',
    'populate_*.php',
    'update_*.php',
    'complete_*.php',
    'export_*.php',
    'smart_*.php',
    'task*.php',
    'validate_*.php',
    'one_day_*.php',
    'remove_*.php',
    'review_*.php',
    'list_*.php',
    'get_*.php',
    'final_*.php',
    'add_country_*.php',
    'visa_*.php',
    'country_codes.txt',
    'visa_research_template.txt',
    'country-pages-dashboard.html',
    'prepare_production.ps1',
    'database.sql',
    'database_clean.sql',
    'create_admin.sql',
    'config.production.php',
    '.env.example'
)

# Get all files
Write-Host "Copying files to PRODUCTION_READY folder...`n" -ForegroundColor Cyan

$allItems = Get-ChildItem -Path $sourceDir -Recurse -File

$copiedCount = 0
$skippedCount = 0

foreach ($item in $allItems) {
    $relativePath = $item.FullName.Substring($sourceDir.Path.Length + 1)
    
    # Check if file should be excluded
    $shouldExclude = $false
    foreach ($pattern in $excludePatterns) {
        if ($relativePath -like "*$pattern*") {
            $shouldExclude = $true
            break
        }
    }
    
    # Skip config.php (will be created on server)
    if ($relativePath -eq "includes\config.php") {
        $shouldExclude = $true
    }
    
    if (!$shouldExclude) {
        $destPath = Join-Path $prodDir $relativePath
        $destFolder = Split-Path $destPath -Parent
        
        # Create destination folder if needed
        if (!(Test-Path $destFolder)) {
            New-Item -ItemType Directory -Path $destFolder -Force | Out-Null
        }
        
        # Copy file
        Copy-Item $item.FullName -Destination $destPath -Force
        $copiedCount++
    } else {
        $skippedCount++
    }
}

Write-Host "`n===========================================`n" -ForegroundColor Cyan
Write-Host "✓ Production files ready!`n" -ForegroundColor Green
Write-Host "  Location: $prodDir`n" -ForegroundColor White
Write-Host "  Files copied: $copiedCount" -ForegroundColor Green
Write-Host "  Files excluded: $skippedCount`n" -ForegroundColor Yellow

Write-Host "===========================================`n" -ForegroundColor Cyan
Write-Host "NEXT STEPS:`n" -ForegroundColor Yellow
Write-Host "1. Upload ALL files from PRODUCTION_READY folder via FTP" -ForegroundColor White
Write-Host "2. Import database_complete.sql in phpMyAdmin" -ForegroundColor White
Write-Host "3. Create includes/config.php on server (use config.production.php as template)" -ForegroundColor White
Write-Host "4. Create admin user (use create_admin.sql)" -ForegroundColor White
Write-Host "5. Test site: https://yourdomain.com/" -ForegroundColor White
Write-Host "6. Test admin: https://yourdomain.com/admin/`n" -ForegroundColor White

Write-Host "===========================================`n" -ForegroundColor Cyan
Write-Host "IMPORTANT FILES:`n" -ForegroundColor Red
Write-Host "  database_complete.sql - Import this to your database" -ForegroundColor White
Write-Host "  config.production.php - Template for your config.php" -ForegroundColor White
Write-Host "  create_admin.sql - Create your admin user" -ForegroundColor White
Write-Host "  DEPLOYMENT.md - Full deployment guide`n" -ForegroundColor White

Write-Host "===========================================`n" -ForegroundColor Cyan
Write-Host "Ready to deploy! 🚀`n" -ForegroundColor Green

# Create a deployment checklist file
$checklistPath = Join-Path $prodDir "DEPLOYMENT_CHECKLIST.txt"
$checklist = "===========================================" + [Environment]::NewLine
$checklist += "DEPLOYMENT CHECKLIST" + [Environment]::NewLine
$checklist += "===========================================" + [Environment]::NewLine
$checklist += "" + [Environment]::NewLine
$checklist += "PRE-DEPLOYMENT:" + [Environment]::NewLine
$checklist += "[ ] Database created on production server" + [Environment]::NewLine
$checklist += "[ ] Database credentials ready" + [Environment]::NewLine
$checklist += "[ ] FTP access credentials ready" + [Environment]::NewLine
$checklist += "[ ] Domain/subdomain configured" + [Environment]::NewLine
$checklist += "" + [Environment]::NewLine
$checklist += "DEPLOYMENT:" + [Environment]::NewLine
$checklist += "[ ] Uploaded all files from PRODUCTION_READY folder via FTP" + [Environment]::NewLine
$checklist += "[ ] Imported database_complete.sql via phpMyAdmin" + [Environment]::NewLine
$checklist += "[ ] Created includes/config.php with production settings" + [Environment]::NewLine
$checklist += "[ ] Set file permissions - 755 for directories and 644 for files" + [Environment]::NewLine
$checklist += "[ ] Created admin user using create_admin.sql" + [Environment]::NewLine
$checklist += "" + [Environment]::NewLine
$checklist += "TESTING:" + [Environment]::NewLine
$checklist += "[ ] Test database connection: /admin/system-test.php" + [Environment]::NewLine
$checklist += "[ ] Test admin login: /admin/" + [Environment]::NewLine
$checklist += "[ ] Test main site: /" + [Environment]::NewLine
$checklist += "[ ] Test search functionality" + [Environment]::NewLine
$checklist += "[ ] Test language switching" + [Environment]::NewLine
$checklist += "[ ] Test country detail pages" + [Environment]::NewLine
$checklist += "[ ] Test contact form" + [Environment]::NewLine
$checklist += "[ ] Test feedback buttons" + [Environment]::NewLine
$checklist += "" + [Environment]::NewLine
$checklist += "SECURITY:" + [Environment]::NewLine
$checklist += "[ ] Changed default admin password" + [Environment]::NewLine
$checklist += "[ ] Removed test files if any leaked through" + [Environment]::NewLine
$checklist += "[ ] Set config.php to 640 permissions" + [Environment]::NewLine
$checklist += "[ ] Verified .htaccess is working" + [Environment]::NewLine
$checklist += "[ ] Enabled HTTPS/SSL" + [Environment]::NewLine
$checklist += "[ ] Hidden includes/config.php from web access" + [Environment]::NewLine
$checklist += "" + [Environment]::NewLine
$checklist += "POST-DEPLOYMENT:" + [Environment]::NewLine
$checklist += "[ ] Configure Google AdSense in admin panel" + [Environment]::NewLine
$checklist += "[ ] Update privacy policy with your details" + [Environment]::NewLine
$checklist += "[ ] Submit sitemap to Google Search Console" + [Environment]::NewLine
$checklist += "[ ] Set up database backup schedule" + [Environment]::NewLine
$checklist += "[ ] Monitor error logs for 24 hours" + [Environment]::NewLine
$checklist += "" + [Environment]::NewLine
$checklist += "MAINTENANCE:" + [Environment]::NewLine
$checklist += "[ ] Document admin credentials safely" + [Environment]::NewLine
$checklist += "[ ] Set calendar reminder for content updates" + [Environment]::NewLine
$checklist += "[ ] Plan for regular visa info updates" + [Environment]::NewLine
$checklist += "[ ] Monitor analytics and view counts" + [Environment]::NewLine
$checklist += "" + [Environment]::NewLine
$checklist += "===========================================" + [Environment]::NewLine
$checklist += "Site is LIVE! 🎉" + [Environment]::NewLine
$checklist += "===========================================" + [Environment]::NewLine

Set-Content -Path $checklistPath -Value $checklist

Write-Host "✓ Deployment checklist created in PRODUCTION_READY folder`n" -ForegroundColor Green
Write-Host "===========================================`n" -ForegroundColor Cyan

# Ask if they want to open the folder
$openFolder = Read-Host "Open PRODUCTION_READY folder now? (Y/N)"
if ($openFolder -eq 'Y' -or $openFolder -eq 'y') {
    explorer $prodDir
}
