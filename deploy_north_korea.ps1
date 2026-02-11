# FTP Connection Details
$ftpServer = "101.0.92.142"
$ftpUsername = "u421261620.csantro"
$ftpPassword = "Casperthe3rd!"
$ftpRemotePath = "/domains/arrivalcards.com/public_html"

# Files to upload
$filesToUpload = @(
    "add_north_korea.php",
    "add_north_korea_cli.php"
)

Write-Host "================================" -ForegroundColor Cyan
Write-Host "Deploying North Korea Scripts" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

foreach ($file in $filesToUpload) {
    if (Test-Path $file) {
        Write-Host "Uploading: $file" -ForegroundColor Yellow
        
        # Create WebClient
        $webclient = New-Object System.Net.WebClient
        $webclient.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
        
        # Upload file
        $uri = "ftp://$ftpServer$ftpRemotePath/$file"
        try {
            $webclient.UploadFile($uri, $file)
            Write-Host "  [OK] Uploaded successfully" -ForegroundColor Green
        }
        catch {
            Write-Host "  [ERROR] Failed: $_" -ForegroundColor Red
        }
        
        $webclient.Dispose()
    }
    else {
        Write-Host "  [ERROR] File not found: $file" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "================================" -ForegroundColor Cyan
Write-Host "Deployment Complete!" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next: Run add_north_korea_cli.php on production:" -ForegroundColor Yellow
Write-Host "  ssh to server and run: php add_north_korea_cli.php" -ForegroundColor White
Write-Host "  Or visit: https://arrivalcards.com/add_north_korea.php" -ForegroundColor White
