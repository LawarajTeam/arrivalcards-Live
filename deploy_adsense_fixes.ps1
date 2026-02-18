# Deploy AdSense Fixes to Production
# This script uploads the new pages to fix "Low Value Content" issue

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  AdSense Fix Deployment Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Load FTP credentials
if (Test-Path "SERVER_CREDENTIALS.txt") {
    Write-Host "✓ Loading FTP credentials..." -ForegroundColor Green
    $credentials = Get-Content "SERVER_CREDENTIALS.txt"
    Write-Host "  Please configure FTP details in this script" -ForegroundColor Yellow
} else {
    Write-Host "⚠ SERVER_CREDENTIALS.txt not found" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Files that need to be uploaded:" -ForegroundColor Cyan
Write-Host "  1. about.php" -ForegroundColor White
Write-Host "  2. terms.php" -ForegroundColor White
Write-Host "  3. faq.php" -ForegroundColor White  
Write-Host "  4. includes/footer.php (updated)" -ForegroundColor White
Write-Host ""

# Check if files exist
$files = @("about.php", "terms.php", "faq.php", "includes\footer.php")
$allExist = $true

foreach ($file in $files) {
    if (Test-Path $file) {
        Write-Host "  ✓ $file exists" -ForegroundColor Green
    } else {
        Write-Host "  ✗ $file NOT FOUND" -ForegroundColor Red
        $allExist = $false
    }
}

Write-Host ""

if (-not $allExist) {
    Write-Host "ERROR: Some files are missing!" -ForegroundColor Red
    exit 1
}

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Manual Upload Instructions" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. Open your FTP client (FileZilla, WinSCP, etc.)" -ForegroundColor White
Write-Host "2. Connect to your hosting server" -ForegroundColor White
Write-Host "3. Navigate to your website root directory (usually public_html)" -ForegroundColor White
Write-Host "4. Upload these files:" -ForegroundColor White
Write-Host ""
Write-Host "   about.php → /public_html/about.php" -ForegroundColor Yellow
Write-Host "   terms.php → /public_html/terms.php" -ForegroundColor Yellow
Write-Host "   faq.php → /public_html/faq.php" -ForegroundColor Yellow
Write-Host "   includes/footer.php → /public_html/includes/footer.php" -ForegroundColor Yellow
Write-Host ""
Write-Host "5. After upload, test these URLs:" -ForegroundColor White
Write-Host "   https://arrivalcards.com/about.php" -ForegroundColor Cyan
Write-Host "   https://arrivalcards.com/terms.php" -ForegroundColor Cyan
Write-Host "   https://arrivalcards.com/faq.php" -ForegroundColor Cyan
Write-Host ""

$deploy = Read-Host "Do you want to copy files to a deploy folder? (y/n)"

if ($deploy -eq "y" -or $deploy -eq "Y") {
    $deployFolder = "adsense_fix_deploy"
    
    if (-not (Test-Path $deployFolder)) {
        New-Item -ItemType Directory -Path $deployFolder | Out-Null
        New-Item -ItemType Directory -Path "$deployFolder\includes" | Out-Null
    }
    
    Write-Host ""
    Write-Host "Copying files to $deployFolder folder..." -ForegroundColor Cyan
    
    Copy-Item "about.php" "$deployFolder\about.php"
    Copy-Item "terms.php" "$deployFolder\terms.php"
    Copy-Item "faq.php" "$deployFolder\faq.php"
    Copy-Item "includes\footer.php" "$deployFolder\includes\footer.php"
    
    Write-Host "✓ Files copied successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Deploy folder location:" -ForegroundColor Yellow
    Write-Host "  $(Get-Location)\$deployFolder" -ForegroundColor White
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Next Steps (IMPORTANT!)" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. Upload the files to your server" -ForegroundColor Yellow
Write-Host "2. Test all pages load correctly" -ForegroundColor Yellow
Write-Host "3. Wait 72 hours for Google to recrawl" -ForegroundColor Yellow
Write-Host "4. Request AdSense review" -ForegroundColor Yellow
Write-Host ""
Write-Host "For detailed instructions, read:" -ForegroundColor Cyan
Write-Host "  ADSENSE_FIX_GUIDE.md" -ForegroundColor White
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Script completed!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
