# Deploy ALL files to production from GitHub
# Complete production deployment script

$ErrorActionPreference = 'Stop'

# FTP credentials
$ftpServer = "101.0.92.142"
$ftpUsername = "arrivalcards"
$ftpPassword = "Ijmb)%v]If"

Write-Host "`n======================================" -ForegroundColor Cyan
Write-Host "PRODUCTION DEPLOYMENT - FULL SYNC" -ForegroundColor Cyan
Write-Host "======================================`n" -ForegroundColor Cyan

# Files to deploy (relative path -> remote path)
$files = @{
    "index.php" = "/public_html/index.php"
    "country.php" = "/public_html/country.php"
    "sitemap.xml.php" = "/public_html/sitemap.xml.php"
    "contact.php" = "/public_html/contact.php"
    "privacy.php" = "/public_html/privacy.php"
    "404.php" = "/public_html/404.php"
    "robots.txt" = "/public_html/robots.txt"
    "process_contact.php" = "/public_html/process_contact.php"
    "report-error.php" = "/public_html/report-error.php"
    "submit_feedback.php" = "/public_html/submit_feedback.php"
    "set_language.php" = "/public_html/set_language.php"
    
    # Admin files
    "admin\index.php" = "/public_html/admin/index.php"
    "admin\login.php" = "/public_html/admin/login.php"
    "admin\logout.php" = "/public_html/admin/logout.php"
    "admin\countries.php" = "/public_html/admin/countries.php"
    "admin\add_country.php" = "/public_html/admin/add_country.php"
    "admin\edit_country.php" = "/public_html/admin/edit_country.php"
    "admin\delete_country.php" = "/public_html/admin/delete_country.php"
    "admin\contacts.php" = "/public_html/admin/contacts.php"
    "admin\adsense.php" = "/public_html/admin/adsense.php"
    "admin\analytics.php" = "/public_html/admin/analytics.php"
    "admin\system-test.php" = "/public_html/admin/system-test.php"
    "admin\setup_analytics.php" = "/public_html/admin/setup_analytics.php"
    
    # Includes
    "includes\config.php" = "/public_html/includes/config.php"
    "includes\functions.php" = "/public_html/includes/functions.php"
    "includes\header.php" = "/public_html/includes/header.php"
    "includes\footer.php" = "/public_html/includes/footer.php"
    "includes\adsense_functions.php" = "/public_html/includes/adsense_functions.php"
    
    # Admin includes
    "admin\includes\header.php" = "/public_html/admin/includes/header.php"
    "admin\includes\footer.php" = "/public_html/admin/includes/footer.php"
    "admin\includes\auth.php" = "/public_html/admin/includes/auth.php"
    
    # Database & Setup scripts
    "add_north_korea.php" = "/public_html/add_north_korea.php"
    "add_north_korea_cli.php" = "/public_html/add_north_korea_cli.php"
}

$totalFiles = $files.Count
$current = 0
$successful = 0
$failed = 0

Write-Host "Deploying $totalFiles files...`n" -ForegroundColor Yellow

foreach ($localPath in $files.Keys) {
    $current++
    $remotePath = $files[$localPath]
    
    if (-not (Test-Path $localPath)) {
        Write-Host "[$current/$totalFiles] SKIP: $localPath (not found)" -ForegroundColor Yellow
        continue
    }
    
    Write-Host "[$current/$totalFiles] Uploading: $localPath" -ForegroundColor Cyan
    
    try {
        $ftpRequest = [System.Net.FtpWebRequest]::Create("ftp://$ftpServer$remotePath")
        $ftpRequest.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
        $ftpRequest.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
        $ftpRequest.UseBinary = $true
        $ftpRequest.KeepAlive = $false
        
        $fileContent = [System.IO.File]::ReadAllBytes($localPath)
        $ftpRequest.ContentLength = $fileContent.Length
        
        $requestStream = $ftpRequest.GetRequestStream()
        $requestStream.Write($fileContent, 0, $fileContent.Length)
        $requestStream.Close()
        
        $response = $ftpRequest.GetResponse()
        $response.Close()
        
        Write-Host "    [OK] Uploaded successfully" -ForegroundColor Green
        $successful++
    }
    catch {
        Write-Host "    [FAILED] $($_.Exception.Message)" -ForegroundColor Red
        $failed++
    }
}

Write-Host "`n======================================" -ForegroundColor Cyan
Write-Host "DEPLOYMENT SUMMARY" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan
Write-Host "Total files: $totalFiles" -ForegroundColor White
Write-Host "Successful: $successful" -ForegroundColor Green
Write-Host "Failed: $failed" -ForegroundColor $(if ($failed -gt 0) { "Red" } else { "Green" })

if ($successful -gt 0) {
    Write-Host "`n======================================" -ForegroundColor Cyan
    Write-Host "POST-DEPLOYMENT STEPS" -ForegroundColor Yellow
    Write-Host "======================================`n" -ForegroundColor Cyan
    
    Write-Host "1. Setup Analytics (if not done):" -ForegroundColor White
    Write-Host "   https://arrivalcards.com/admin/setup_analytics.php`n" -ForegroundColor Cyan
    
    Write-Host "2. Add North Korea (if not done):" -ForegroundColor White
    Write-Host "   https://arrivalcards.com/add_north_korea.php`n" -ForegroundColor Cyan
    
    Write-Host "3. Verify Site:" -ForegroundColor White
    Write-Host "   https://arrivalcards.com/" -ForegroundColor Cyan
    Write-Host "   https://arrivalcards.com/country.php?id=208 (North Korea)" -ForegroundColor Cyan
    Write-Host "   https://arrivalcards.com/sitemap.xml.php" -ForegroundColor Cyan
    Write-Host "   https://arrivalcards.com/admin/analytics.php`n" -ForegroundColor Cyan
    
    Write-Host "4. Database Updates:" -ForegroundColor White
    Write-Host "   If you made database changes, import SQL via phpMyAdmin" -ForegroundColor Gray
    Write-Host "   Or run setup scripts via browser`n" -ForegroundColor Gray
}

Write-Host "======================================" -ForegroundColor Green
Write-Host "Deployment Complete!" -ForegroundColor Green
Write-Host "======================================`n" -ForegroundColor Green
