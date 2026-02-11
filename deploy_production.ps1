# Deploy to Production
# Uploads all fixed files to production server

Write-Host "`n================================" -ForegroundColor Cyan
Write-Host "DEPLOYING TO PRODUCTION" -ForegroundColor Cyan
Write-Host "Server: arrivalcards.com" -ForegroundColor Cyan
Write-Host "================================`n" -ForegroundColor Cyan

$files = @(
    @{Local="PRODUCTION_READY\analytics_tables.sql"; Remote="analytics_tables.sql"},
    @{Local="PRODUCTION_READY\add_view_count.sql"; Remote="add_view_count.sql"},
    @{Local="PRODUCTION_READY\setup_analytics.php"; Remote="admin/setup_analytics.php"},
    @{Local="PRODUCTION_READY\add_north_korea.php"; Remote="add_north_korea.php"},
    @{Local="PRODUCTION_READY\add_north_korea_cli.php"; Remote="add_north_korea_cli.php"}
)

Write-Host "FILES TO UPLOAD:" -ForegroundColor Yellow
foreach ($file in $files) {
    if (Test-Path $file.Local) {
        Write-Host "  [OK] $($file.Local) -> $($file.Remote)" -ForegroundColor Green
    } else {
        Write-Host "  [MISSING] $($file.Local)" -ForegroundColor Red
    }
}

Write-Host "`n================================" -ForegroundColor Cyan
Write-Host "UPLOAD METHOD" -ForegroundColor Cyan
Write-Host "================================`n" -ForegroundColor Cyan

Write-Host "OPTION 1: Manual Upload via WinSCP (Recommended)" -ForegroundColor Yellow
Write-Host "1. Open WinSCP" -ForegroundColor White
Write-Host "2. Connect to:" -ForegroundColor White
Write-Host "   Host: 101.0.92.142" -ForegroundColor Cyan
Write-Host "   Username: arrivalcards (or u421261620.csantro)" -ForegroundColor Cyan
Write-Host "   Password: (check SERVER_CREDENTIALS.txt)" -ForegroundColor Cyan
Write-Host "   Protocol: FTP`n" -ForegroundColor Cyan
Write-Host "3. Upload from PRODUCTION_READY folder:" -ForegroundColor White
Write-Host "   - analytics_tables.sql" -ForegroundColor Cyan
Write-Host "   - add_view_count.sql" -ForegroundColor Cyan
Write-Host "   - add_north_korea.php" -ForegroundColor Cyan
Write-Host "   - add_north_korea_cli.php" -ForegroundColor Cyan
Write-Host "   - setup_analytics.php (to /admin/ folder)`n" -ForegroundColor Cyan

Write-Host "`nOPTION 2: Try Automated FTP Upload" -ForegroundColor Yellow
$tryAuto = Read-Host "Try automated FTP upload? (Y/N)"

if ($tryAuto -eq 'Y' -or $tryAuto -eq 'y') {
    Write-Host "`nAttempting FTP upload..." -ForegroundColor Yellow
    
    $ftpServer = "101.0.92.142"
    $ftpUsername = "u421261620.csantro"
    $ftpPassword = "Casperthe3rd!"
    $ftpBasePath = "/domains/arrivalcards.com/public_html"
    
    foreach ($file in $files) {
        if (Test-Path $file.Local) {
            Write-Host "`nUploading: $($file.Local)" -ForegroundColor Cyan
            $ftpUri = "ftp://$ftpServer$ftpBasePath/$($file.Remote)"
            
            try {
                $webclient = New-Object System.Net.WebClient
                $webclient.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
                $webclient.UploadFile($ftpUri, $file.Local)
                Write-Host "  [SUCCESS] Uploaded to: $($file.Remote)" -ForegroundColor Green
                $webclient.Dispose()
            }
            catch {
                Write-Host "  [FAILED] $($_.Exception.Message)" -ForegroundColor Red
            }
        }
    }
}

Write-Host "`n================================" -ForegroundColor Cyan
Write-Host "AFTER UPLOAD - RUN THESE:" -ForegroundColor Yellow
Write-Host "================================`n" -ForegroundColor Cyan

Write-Host "1. Setup Analytics:" -ForegroundColor White
Write-Host "   https://arrivalcards.com/admin/setup_analytics.php`n" -ForegroundColor Cyan

Write-Host "2. Add North Korea:" -ForegroundColor White
Write-Host "   https://arrivalcards.com/add_north_korea.php`n" -ForegroundColor Cyan

Write-Host "3. Verify:" -ForegroundColor White
Write-Host "   https://arrivalcards.com/ (check for North Korea card)" -ForegroundColor Cyan
Write-Host "   https://arrivalcards.com/admin/analytics.php (check dashboard)`n" -ForegroundColor Cyan

Write-Host "================================" -ForegroundColor Green
Write-Host "Ready to Deploy!" -ForegroundColor Green
Write-Host "================================`n" -ForegroundColor Green
