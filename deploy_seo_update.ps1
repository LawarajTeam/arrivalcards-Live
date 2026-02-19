# ============================================
# Deploy SEO Updates to Production
# www.arrivalcards.com
# ============================================

$ftpServer = "101.0.92.142"
$ftpUsername = "arrivalcards"
$ftpPassword = "Ijmb)%v]If"

Write-Host "`n============================================" -ForegroundColor Cyan
Write-Host "  DEPLOYING SEO UPDATES TO PRODUCTION" -ForegroundColor Yellow
Write-Host "  Server: arrivalcards.com" -ForegroundColor Cyan
Write-Host "============================================`n" -ForegroundColor Cyan

# Files to upload
$files = @(
    @{Local="PRODUCTION_READY\index.php"; Remote="/public_html/index.php"},
    @{Local="PRODUCTION_READY\country.php"; Remote="/public_html/country.php"},
    @{Local="PRODUCTION_READY\contact.php"; Remote="/public_html/contact.php"},
    @{Local="PRODUCTION_READY\privacy.php"; Remote="/public_html/privacy.php"},
    @{Local="PRODUCTION_READY\404.php"; Remote="/public_html/404.php"},
    @{Local="PRODUCTION_READY\robots.txt"; Remote="/public_html/robots.txt"},
    @{Local="PRODUCTION_READY\includes\header.php"; Remote="/public_html/includes/header.php"},
    @{Local="PRODUCTION_READY\includes\breadcrumbs.php"; Remote="/public_html/includes/breadcrumbs.php"}
)

Write-Host "FILES TO UPLOAD:" -ForegroundColor Yellow
$allFilesExist = $true
foreach ($file in $files) {
    if (Test-Path $file.Local) {
        Write-Host "  ✓ $($file.Local)" -ForegroundColor Green
    } else {
        Write-Host "  × MISSING: $($file.Local)" -ForegroundColor Red
        $allFilesExist = $false
    }
}

if (-not $allFilesExist) {
    Write-Host "`nERROR: Some files are missing!" -ForegroundColor Red
    exit 1
}

Write-Host "`nStarting FTP upload..." -ForegroundColor Yellow
Write-Host "This may take a minute...`n" -ForegroundColor White

$successCount = 0
$failCount = 0

foreach ($file in $files) {
    $fileName = Split-Path $file.Local -Leaf
    Write-Host "Uploading $fileName..." -ForegroundColor Cyan
    
    try {
        $ftpUri = "ftp://$ftpServer$($file.Remote)"
        $webclient = New-Object System.Net.WebClient
        $webclient.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
        $webclient.UploadFile($ftpUri, $file.Local)
        $webclient.Dispose()
        
        Write-Host "  ✓ SUCCESS: $fileName uploaded" -ForegroundColor Green
        $successCount++
    }
    catch {
        Write-Host "  × FAILED: $($_.Exception.Message)" -ForegroundColor Red
        $failCount++
    }
}

Write-Host "`n============================================" -ForegroundColor Cyan
Write-Host "  DEPLOYMENT SUMMARY" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "✓ Successful: $successCount" -ForegroundColor Green
Write-Host "× Failed: $failCount" -ForegroundColor Red

if ($failCount -eq 0) {
    Write-Host "`nALL SEO FILES DEPLOYED SUCCESSFULLY!" -ForegroundColor Green
    Write-Host "`nNext steps:" -ForegroundColor Yellow
    Write-Host "1. Visit https://arrivalcards.com" -ForegroundColor White
    Write-Host "2. Test breadcrumbs on a country page" -ForegroundColor White
    Write-Host "3. Check Google Rich Results Test" -ForegroundColor White
    Write-Host "4. Submit sitemap to Search Console" -ForegroundColor White
} else {
    Write-Host "`nSome uploads failed. Please check errors above." -ForegroundColor Yellow
}

Write-Host ""
