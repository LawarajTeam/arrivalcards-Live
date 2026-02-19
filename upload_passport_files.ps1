# Simple FTP Upload for Passport Expansion Files
Write-Host "`nUploading passport expansion files..." -ForegroundColor Cyan

$ftpServer = "101.0.92.142"
$ftpUsername = "arrivalcards"
$ftpPassword = 'Ijmb)%v]If'

$files = @("admin_visa_data.php", "import_priority_passports.php")

foreach ($file in $files) {
    Write-Host "Uploading $file..." -ForegroundColor Yellow
    
    $ftpUri = "ftp://$ftpServer/$file"
    
    try {
        $webclient = New-Object System.Net.WebClient
        $webclient.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
        $webclient.UploadFile($ftpUri, $file)
        $webclient.Dispose()
        
        Write-Host "  Success: $file uploaded" -ForegroundColor Green
    }
    catch {
        Write-Host "  Failed: $($_.Exception.Message)" -ForegroundColor Red
    }
}

Write-Host "`nUpload complete!" -ForegroundColor Green
Write-Host "`nNext: Visit https://arrivalcards.com/import_priority_passports.php" -ForegroundColor Cyan
