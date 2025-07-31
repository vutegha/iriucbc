# Add XAMPP PHP 8.2 to System Environment Variables (PowerShell)
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "Adding XAMPP PHP 8.2 to System PATH" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Check if running as administrator
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator")

if (-not $isAdmin) {
    Write-Host "❌ This script requires administrator privileges" -ForegroundColor Red
    Write-Host "Right-click on PowerShell and select 'Run as Administrator'" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Alternative: Manual method" -ForegroundColor Yellow
    Write-Host "1. Press Win + X, select 'System'" -ForegroundColor White
    Write-Host "2. Click 'Advanced system settings'" -ForegroundColor White
    Write-Host "3. Click 'Environment Variables'" -ForegroundColor White
    Write-Host "4. Under 'System variables', find 'Path' and click 'Edit'" -ForegroundColor White
    Write-Host "5. Click 'New' and add: C:\xampp\php" -ForegroundColor White
    Write-Host "6. Click 'OK' on all dialogs" -ForegroundColor White
    Read-Host "Press Enter to exit"
    exit
}

Write-Host "✅ Running with administrator privileges" -ForegroundColor Green
Write-Host ""

# Get current system PATH
$currentPath = [Environment]::GetEnvironmentVariable('Path', 'Machine')

# Check if XAMPP PHP is already in PATH
if ($currentPath -like "*C:\xampp\php*") {
    Write-Host "✅ XAMPP PHP is already in system PATH" -ForegroundColor Green
} else {
    Write-Host "Adding XAMPP PHP to system PATH..." -ForegroundColor Yellow
    
    # Verify XAMPP PHP exists
    if (Test-Path "C:\xampp\php\php.exe") {
        Write-Host "✅ XAMPP PHP executable found" -ForegroundColor Green
        
        # Add XAMPP PHP to the beginning of PATH
        $newPath = "C:\xampp\php;" + $currentPath
        
        try {
            [Environment]::SetEnvironmentVariable('Path', $newPath, 'Machine')
            Write-Host "✅ XAMPP PHP added to system PATH successfully!" -ForegroundColor Green
        } catch {
            Write-Host "❌ Failed to modify system PATH: $($_.Exception.Message)" -ForegroundColor Red
        }
    } else {
        Write-Host "❌ XAMPP PHP not found at C:\xampp\php\php.exe" -ForegroundColor Red
        Write-Host "Please verify XAMPP is installed correctly" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "Current PHP-related paths in system PATH:" -ForegroundColor Cyan
$updatedPath = [Environment]::GetEnvironmentVariable('Path', 'Machine')
$paths = $updatedPath -split ';'
foreach ($path in $paths) {
    if ($path -like "*php*") {
        Write-Host $path -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "✅ SETUP COMPLETED!" -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Important: Restart your applications to use the new PATH:" -ForegroundColor Yellow
Write-Host "1. Close VS Code" -ForegroundColor White
Write-Host "2. Close all command prompts/PowerShell windows" -ForegroundColor White
Write-Host "3. Reopen VS Code" -ForegroundColor White
Write-Host "4. Test with: php --version" -ForegroundColor White
Write-Host ""

Read-Host "Press Enter to continue"
