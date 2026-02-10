# Simple FTP Upload using WinSCP Script
# Run this to deploy all files

$localFolder = ".\PRODUCTION_READY"
$winscp = @"
open ftp://arrivalcards:Ijmb)%v]If@101.0.92.142
cd /public_html
lcd $localFolder
put *
exit
"@

$scriptFile = ".\ftp_upload.txt"
$winscp | Out-File -FilePath $scriptFile -Encoding ASCII

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "FTP Upload Script Created" -ForegroundColor Green
Write-Host "========================================`n" -ForegroundColor Cyan

Write-Host "MANUAL UPLOAD STEPS:`n" -ForegroundColor Yellow

Write-Host "1. Download WinSCP: https://winscp.net/download/WinSCP-6.3.2-Setup.exe`n" -ForegroundColor White

Write-Host "2. Install and open WinSCP`n" -ForegroundColor White

Write-Host "3. Enter connection details:" -ForegroundColor White
Write-Host "   Host: 101.0.92.142" -ForegroundColor Cyan
Write-Host "   Username: arrivalcards" -ForegroundColor Cyan
Write-Host "   Password: Ijmb)%v]If" -ForegroundColor Cyan
Write-Host "   Protocol: FTP`n" -ForegroundColor Cyan

Write-Host "4. Click Login`n" -ForegroundColor White

Write-Host "5. Navigate to /public_html on the right panel`n" -ForegroundColor White

Write-Host "6. Navigate to PRODUCTION_READY folder on the left panel`n" -ForegroundColor White

Write-Host "7. Select all files (Ctrl+A) and drag to right panel`n" -ForegroundColor White

Write-Host "8. Wait for upload to complete`n" -ForegroundColor White

Write-Host "========================================`n" -ForegroundColor Cyan

$openWinSCP = Read-Host "Open WinSCP download page? (Y/N)"
if ($openWinSCP -eq 'Y' -or $openWinSCP -eq 'y') {
    Start-Process "https://winscp.net/eng/download.php"
}

Remove-Item $scriptFile -ErrorAction SilentlyContinue
