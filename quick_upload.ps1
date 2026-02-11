# Quick upload single file to production
param(
    [string]$FilePath = "country.php"
)

$ErrorActionPreference = 'Stop'

# FTP credentials
$ftpServer = "101.0.92.142"
$ftpUsername = "arrivalcards"
$ftpPassword = "Ijmb)%v]If"

$localFile = Get-Item $FilePath -ErrorAction Stop
$remotePath = "/public_html/$($localFile.Name)"

Write-Host "`nUploading $($localFile.Name) to production..." -ForegroundColor Cyan

try {
    $ftpRequest = [System.Net.FtpWebRequest]::Create("ftp://$ftpServer$remotePath")
    $ftpRequest.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
    $ftpRequest.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
    $ftpRequest.UseBinary = $true
    $ftpRequest.KeepAlive = $false
    
    # Read file
    $fileContent = [System.IO.File]::ReadAllBytes($localFile.FullName)
    $ftpRequest.ContentLength = $fileContent.Length
    
    # Upload
    $requestStream = $ftpRequest.GetRequestStream()
    $requestStream.Write($fileContent, 0, $fileContent.Length)
    $requestStream.Close()
    
    # Get response
    $response = $ftpRequest.GetResponse()
    
    Write-Host "[OK] Successfully uploaded $($localFile.Name)" -ForegroundColor Green
    Write-Host "  Status: $($response.StatusDescription)" -ForegroundColor Gray
    
    $response.Close()
    
    Write-Host "`nView at: https://arrivalcards.com/$($localFile.Name)" -ForegroundColor Yellow
}
catch {
    Write-Host "[ERROR] $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}
