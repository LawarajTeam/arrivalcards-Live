# Deploy Passport Comparison Tool
Write-Host "Deploying Passport Comparison Tool..." -ForegroundColor Cyan

$ftpServer = "101.0.92.142"
$ftpUsername = "arrivalcards"
$ftpPassword = 'Ijmb)%v]If'

$files = @(
    "compare-passports.php",
    "includes/header.php"
)

foreach ($file in $files) {
    Write-Host "Uploading $file..." -ForegroundColor Yellow
    
    try {
        if ($file -like "includes/*" -or $file -like "assets/*") {
            $content = Get-Content $file -Raw -Encoding UTF8
            $bytes = [System.Text.Encoding]::UTF8.GetBytes($content)
            
            $ftpRequest = [System.Net.FtpWebRequest]::Create("ftp://$ftpServer/$file")
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
        } else {
            $webclient = New-Object System.Net.WebClient
            $webclient.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
            $webclient.UploadFile("ftp://$ftpServer/$file", $file)
            $webclient.Dispose()
            Write-Host "  Success" -ForegroundColor Green
        }
    } catch {
        Write-Host "  Failed: $($_.Exception.Message)" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Deployment Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Test URL: https://arrivalcards.com/compare-passports.php" -ForegroundColor Yellow
Write-Host "Example: ?passport1=USA&passport2=IND" -ForegroundColor White
