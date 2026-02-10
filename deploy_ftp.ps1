# ============================================
# Automated FTP Upload Script
# Deploy to www.arrivalcards.com
# ============================================

$ftpServer = "101.0.92.142"
$ftpUsername = "arrivalcards"
$ftpPassword = "Ijmb)%v]If"
$localPath = ".\PRODUCTION_READY"
$remotePath = "/public_html"  # Change if different

Write-Host "===========================================`n" -ForegroundColor Cyan
Write-Host "  FTP Upload to www.arrivalcards.com`n" -ForegroundColor Cyan
Write-Host "===========================================`n" -ForegroundColor Cyan

# Check if WinSCP is installed (more reliable than native PowerShell FTP)
$winscpPath = "C:\Program Files (x86)\WinSCP\WinSCP.com"

if (Test-Path $winscpPath) {
    Write-Host "Using WinSCP for upload...`n" -ForegroundColor Green
    
    # Create WinSCP script
    $scriptContent = @"
open ftp://${ftpUsername}:${ftpPassword}@${ftpServer}
cd $remotePath
lcd $localPath
synchronize remote -delete
exit
"@
    
    $scriptPath = ".\winscp_upload.txt"
    Set-Content -Path $scriptPath -Value $scriptContent
    
    # Execute WinSCP
    & $winscpPath /script=$scriptPath
    
    Remove-Item $scriptPath
    
} else {
    Write-Host "WinSCP not found. Using native PowerShell FTP...`n" -ForegroundColor Yellow
    Write-Host "Note: For large files, consider using FileZilla or WinSCP`n" -ForegroundColor Yellow
    
    # Manual FTP upload using WebClient
    $files = Get-ChildItem -Path $localPath -Recurse -File
    $totalFiles = $files.Count
    $current = 0
    
    foreach ($file in $files) {
        $current++
        $relativePath = $file.FullName.Substring((Get-Item $localPath).FullName.Length + 1)
        $remoteFile = "$remotePath/$($relativePath.Replace('\', '/'))"
        $ftpUri = "ftp://$ftpServer$remoteFile"
        
        Write-Host "[$current/$totalFiles] Uploading: $relativePath" -ForegroundColor Cyan
        
        try {
            # Create directory structure if needed
            $remoteDir = $remoteFile.Substring(0, $remoteFile.LastIndexOf('/'))
            
            # Upload file
            $webclient = New-Object System.Net.WebClient
            $webclient.Credentials = New-Object System.Net.NetworkCredential($ftpUsername, $ftpPassword)
            $webclient.UploadFile($ftpUri, $file.FullName)
            $webclient.Dispose()
            
            Write-Host "  ✓ Success" -ForegroundColor Green
        }
        catch {
            Write-Host "  × Failed: $($_.Exception.Message)" -ForegroundColor Red
        }
    }
}

Write-Host "`n===========================================`n" -ForegroundColor Cyan
Write-Host "Upload completed!`n" -ForegroundColor Green
Write-Host "Next steps:`n" -ForegroundColor Yellow
Write-Host "1. Login to cPanel: https://101.0.92.142:2083" -ForegroundColor White
Write-Host "2. Create MySQL database via 'MySQL Databases'" -ForegroundColor White
Write-Host "3. Import database_complete.sql via phpMyAdmin" -ForegroundColor White
Write-Host "4. Edit includes/config.php with database credentials" -ForegroundColor White
Write-Host "5. Visit: https://www.arrivalcards.com/admin/system-test.php`n" -ForegroundColor White
Write-Host "===========================================`n" -ForegroundColor Cyan
