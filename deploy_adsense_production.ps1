# ============================================
# Deploy AdSense Fix Files to Production
# Upload to www.arrivalcards.com
# ============================================

$ftpServer = "101.0.92.142"
$ftpUsername = "arrivalcards"
$ftpPassword = 'Ijmb)%v]If'
$remotePath = "/public_html"

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  Deploying AdSense Fix to Production" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# Files to upload
$filesToUpload = @(
    @{Local="about.php"; Remote="/public_html/about.php"},
    @{Local="terms.php"; Remote="/public_html/terms.php"},
    @{Local="faq.php"; Remote="/public_html/faq.php"},
    @{Local="includes\footer.php"; Remote="/public_html/includes/footer.php"}
)

Write-Host "Files to upload:" -ForegroundColor Yellow
foreach ($file in $filesToUpload) {
    if (Test-Path $file.Local) {
        Write-Host "  Success: $($file.Local)" -ForegroundColor Green
    } else {
        Write-Host "  Missing: $($file.Local) - NOT FOUND" -ForegroundColor Red
    }
}
Write-Host ""

$proceed = Read-Host "Proceed with upload to production? (y/n)"

if ($proceed -ne "y" -and $proceed -ne "Y") {
    Write-Host "Upload cancelled." -ForegroundColor Yellow
    exit
}

Write-Host ""
Write-Host "Starting upload..." -ForegroundColor Cyan
Write-Host ""

$successCount = 0
$failCount = 0

foreach ($file in $filesToUpload) {
    if (-not (Test-Path $file.Local)) {
        Write-Host "  Skipping $($file.Local) - file not found" -ForegroundColor Red
        $failCount++
        continue
    }
    
    Write-Host "Uploading: $($file.Local) -> $($file.Remote)" -ForegroundColor Cyan
    
    try {
        $ftpUri = "ftp://$ftpServer$($file.Remote)"
        
        # Create WebClient
        $webclient = New-Object System.Net.WebClient
        $webclient.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
        
        # Upload file
        $uri = [System.Uri]::new($ftpUri)
        $webclient.UploadFile($uri, "STOR", (Resolve-Path $file.Local))
        $webclient.Dispose()
        
        Write-Host "  Success!" -ForegroundColor Green
        $successCount++
    }
    catch {
        Write-Host "  Failed: $($_.Exception.Message)" -ForegroundColor Red
        $failCount++
    }
    
    Write-Host ""
}

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "Deployment Summary:" -ForegroundColor Yellow
Write-Host "  Successful: $successCount" -ForegroundColor Green
if ($failCount -gt 0) {
    Write-Host "  Failed: $failCount" -ForegroundColor Red
}
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

if ($successCount -gt 0) {
    Write-Host "Files uploaded successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Next Steps:" -ForegroundColor Yellow
    Write-Host "1. Test the pages:" -ForegroundColor White
    Write-Host "   - https://www.arrivalcards.com/about.php" -ForegroundColor Cyan
    Write-Host "   - https://www.arrivalcards.com/terms.php" -ForegroundColor Cyan
    Write-Host "   - https://www.arrivalcards.com/faq.php" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "2. Verify footer links appear on all pages" -ForegroundColor White
    Write-Host ""
    Write-Host "3. Wait 72 hours for Google to recrawl" -ForegroundColor White
    Write-Host ""
    Write-Host "4. Request AdSense review" -ForegroundColor White
    Write-Host ""
    Write-Host "See ADSENSE_FIX_CHECKLIST.md for details" -ForegroundColor Cyan
} else {
    Write-Host "Upload failed. Please check errors above." -ForegroundColor Red
}

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
