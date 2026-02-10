# ============================================
# Quick Deploy to Production Server via FTP
# ============================================

Write-Host "`n===========================================`n" -ForegroundColor Cyan
Write-Host "  Deploying to arrivalcards.com`n" -ForegroundColor Cyan
Write-Host "===========================================`n" -ForegroundColor Cyan

$ftpServer = "ftp://101.0.92.142"
$ftpUsername = "arrivalcards"
$ftpPassword = "Ijmb)%v]If"

# Files to upload
$filesToUpload = @(
    @{Local=".\PRODUCTION_READY\admin\adsense.php"; Remote="/public_html/admin/adsense.php"},
    @{Local=".\PRODUCTION_READY\admin\verify_adsense.php"; Remote="/public_html/admin/verify_adsense.php"},
    @{Local=".\PRODUCTION_READY\index.php"; Remote="/public_html/index.php"},
    @{Local=".\PRODUCTION_READY\includes\functions.php"; Remote="/public_html/includes/functions.php"},
    @{Local=".\PRODUCTION_READY\includes\header.php"; Remote="/public_html/includes/header.php"},
    @{Local=".\PRODUCTION_READY\includes\footer.php"; Remote="/public_html/includes/footer.php"},
    @{Local=".\PRODUCTION_READY\assets\css\style.css"; Remote="/public_html/assets/css/style.css"},
    @{Local=".\PRODUCTION_READY\run_database_optimization.php"; Remote="/public_html/run_database_optimization.php"}
)

$uploadedCount = 0
$failedCount = 0

foreach ($file in $filesToUpload) {
    $localFile = $file.Local
    $remotePath = $file.Remote
    
    if (Test-Path $localFile) {
        Write-Host "Uploading: $localFile" -ForegroundColor Yellow
        
        try {
            $ftpUri = "$ftpServer$remotePath"
            $webclient = New-Object System.Net.WebClient
            $webclient.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
            $webclient.UploadFile($ftpUri, $localFile)
            
            Write-Host "  SUCCESS" -ForegroundColor Green
            $uploadedCount++
        }
        catch {
            Write-Host "  FAILED: $($_.Exception.Message)" -ForegroundColor Red
            $failedCount++
        }
    }
    else {
        Write-Host "  File not found: $localFile" -ForegroundColor Red
        $failedCount++
    }
    Write-Host ""
}

Write-Host "`n===========================================`n" -ForegroundColor Cyan
Write-Host "  Deployment Summary`n" -ForegroundColor Cyan
Write-Host "===========================================`n" -ForegroundColor Cyan
Write-Host "Uploaded: $uploadedCount files" -ForegroundColor Green
Write-Host "Failed: $failedCount files" -ForegroundColor $(if ($failedCount -eq 0) { "Green" } else { "Red" })
Write-Host "`n===========================================`n" -ForegroundColor Cyan

if ($uploadedCount -gt 0) {
    Write-Host "Deployment complete!" -ForegroundColor Green
    Write-Host "Visit: https://arrivalcards.com/admin/adsense.php" -ForegroundColor Cyan
    Write-Host ""
}
else {
    Write-Host "No files were uploaded" -ForegroundColor Yellow
}
