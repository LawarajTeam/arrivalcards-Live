# Deploy Passport Data Expansion to Production
# Upload admin panel and bulk import script

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "DEPLOYING PASSPORT DATA EXPANSION" -ForegroundColor Cyan
Write-Host "Server: arrivalcards.com (101.0.92.142)" -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

$ftpServer = "ftp://101.0.92.142"
$ftpUsername = "arrivalcards"
$ftpPassword = "Ijmb)%v]If)"

$files = @(
    @{Local="admin_visa_data.php"; Remote="/admin_visa_data.php"},
    @{Local="import_priority_passports.php"; Remote="/import_priority_passports.php"}
)

Write-Host "FILES TO UPLOAD:" -ForegroundColor Yellow
foreach ($file in $files) {
    if (Test-Path $file.Local) {
        Write-Host "  ✓ $($file.Local)" -ForegroundColor Green
    } else {
        Write-Host "  ✗ $($file.Local) - FILE NOT FOUND!" -ForegroundColor Red
        exit 1
    }
}

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "UPLOADING FILES..." -ForegroundColor Cyan
Write-Host "========================================`n" -ForegroundColor Cyan

foreach ($file in $files) {
    Write-Host "Uploading $($file.Local)..." -ForegroundColor Yellow
    
    $ftpUri = "$ftpServer$($file.Remote)"
    $localPath = $file.Local
    
    try {
        # Read file content
        $fileContent = [System.IO.File]::ReadAllBytes($localPath)
        
        # Create FTP request
        $ftpRequest = [System.Net.FtpWebRequest]::Create($ftpUri)
        $ftpRequest.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
        $ftpRequest.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
        $ftpRequest.UseBinary = $true
        $ftpRequest.UsePassive = $true
        
        # Upload file
        $ftpRequest.ContentLength = $fileContent.Length
        $requestStream = $ftpRequest.GetRequestStream()
        $requestStream.Write($fileContent, 0, $fileContent.Length)
        $requestStream.Close()
        
        # Get response
        $response = $ftpRequest.GetResponse()
        Write-Host "  ✓ Uploaded $($file.Local) ($($fileContent.Length) bytes) - $($response.StatusDescription)" -ForegroundColor Green
        $response.Close()
        
    } catch {
        Write-Host "  ✗ ERROR uploading $($file.Local): $($_.Exception.Message)" -ForegroundColor Red
        exit 1
    }
}

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "UPLOAD COMPLETE!" -ForegroundColor Green
Write-Host "========================================`n" -ForegroundColor Cyan

Write-Host "NEXT STEPS:" -ForegroundColor Yellow
Write-Host "1. Run import script:" -ForegroundColor White
Write-Host "   https://arrivalcards.com/import_priority_passports.php" -ForegroundColor Cyan
Write-Host "`n2. Access admin panel:" -ForegroundColor White
Write-Host "   https://arrivalcards.com/admin_visa_data.php" -ForegroundColor Cyan
Write-Host "   Password: arrivalcards2026" -ForegroundColor Cyan
Write-Host "`n3. Test expanded passports:" -ForegroundColor White
Write-Host "   - Select Japan passport (8 destinations)" -ForegroundColor Cyan
Write-Host "   - Select Germany passport (8 destinations)" -ForegroundColor Cyan
Write-Host "   - Select Brazil passport (7 destinations)" -ForegroundColor Cyan
Write-Host "   - Select Mexico passport (7 destinations)" -ForegroundColor Cyan
Write-Host "`n4. Verify database records:" -ForegroundColor White
Write-Host "   Should show 104 total bilateral records (29 old + 75 new)" -ForegroundColor Cyan
Write-Host "`n========================================`n" -ForegroundColor Cyan
