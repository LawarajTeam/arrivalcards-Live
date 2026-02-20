# Upload Best Passports Page
Write-Host "Deploying Best Passports feature..." -ForegroundColor Cyan

$ftpServer = "101.0.92.142"
$ftpUsername = "arrivalcards"
$ftpPassword = 'Ijmb)%v]If'

# Upload main page
Write-Host "1. Uploading best-passports.php..." -ForegroundColor Yellow
try {
    $webclient = New-Object System.Net.WebClient
    $webclient.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
    $webclient.UploadFile("ftp://$ftpServer/best-passports.php", "best-passports.php")
    $webclient.Dispose()
    Write-Host "  Success" -ForegroundColor Green
} catch {
    Write-Host "  Failed: $($_.Exception.Message)" -ForegroundColor Red
}

# Upload header.php
Write-Host "2. Uploading includes/header.php..." -ForegroundColor Yellow
try {
    $content = Get-Content "includes\header.php" -Raw -Encoding UTF8
    $bytes = [System.Text.Encoding]::UTF8.GetBytes($content)
    
    $ftpRequest = [System.Net.FtpWebRequest]::Create("ftp://$ftpServer/includes/header.php")
    $ftpRequest.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
    $ftpRequest.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
    $ftpRequest.UseBinary = $true
    $ftpRequest.UsePassive = $true
    $ftpRequest.ContentLength = $bytes.Length
    
    $requestStream = $ftpRequest.GetRequestStream()
    $requestStream.Write($bytes, 0, $bytes.Length)
    $requestStream.Close()
    
    $response = $ftpRequest.GetResponse()
    Write-Host "  Success - $($response.StatusDescription)" -ForegroundColor Green
    $response.Close()
} catch {
    Write-Host "  Failed: $($_.Exception.Message)" -ForegroundColor Red
}

# Upload style.css
Write-Host "3. Uploading assets/css/style.css..." -ForegroundColor Yellow
try {
    $content = Get-Content "assets\css\style.css" -Raw -Encoding UTF8
    $bytes = [System.Text.Encoding]::UTF8.GetBytes($content)
    
    $ftpRequest = [System.Net.FtpWebRequest]::Create("ftp://$ftpServer/assets/css/style.css")
    $ftpRequest.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
    $ftpRequest.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
    $ftpRequest.UseBinary = $true
    $ftpRequest.UsePassive = $true
    $ftpRequest.ContentLength = $bytes.Length
    
    $requestStream = $ftpRequest.GetRequestStream()
    $requestStream.Write($bytes, 0, $bytes.Length)
    $requestStream.Close()
    
    $response = $ftpRequest.GetResponse()
    Write-Host "  Success - $($response.StatusDescription)" -ForegroundColor Green
    $response.Close()
} catch {
    Write-Host "  Failed: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Deployment Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Test URL: https://arrivalcards.com/best-passports.php" -ForegroundColor Yellow
