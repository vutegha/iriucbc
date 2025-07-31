@echo off
REM Add XAMPP PHP 8.2 to System Environment Variables
echo ================================================
echo Adding XAMPP PHP 8.2 to System PATH
echo ================================================
echo.

echo Current PHP version being used:
php --version 2>nul || echo "No PHP found in current PATH"
echo.

echo Adding XAMPP PHP to system PATH...
echo This requires administrator privileges.
echo.

REM Add XAMPP PHP to system PATH permanently
echo Adding C:\xampp\php to system PATH...

REM Use PowerShell to modify system environment variables
powershell -Command "& {$env:Path = [Environment]::GetEnvironmentVariable('Path','Machine'); if ($env:Path -notlike '*C:\xampp\php*') { $newPath = 'C:\xampp\php;' + $env:Path; [Environment]::SetEnvironmentVariable('Path', $newPath, 'Machine'); Write-Host 'XAMPP PHP added to system PATH successfully!' -ForegroundColor Green } else { Write-Host 'XAMPP PHP already in system PATH' -ForegroundColor Yellow }}"

if errorlevel 1 (
    echo ❌ Failed to add to system PATH
    echo Please run this script as Administrator
    echo.
    echo Alternative: Manual steps below
    echo 1. Press Win + R, type: sysdm.cpl
    echo 2. Click "Environment Variables"
    echo 3. Under "System variables", find "Path" and click "Edit"
    echo 4. Click "New" and add: C:\xampp\php
    echo 5. Click "OK" on all dialogs
    echo 6. Restart your command prompt
) else (
    echo ✅ XAMPP PHP added to system PATH!
    echo.
    echo Please restart your command prompt or VS Code for changes to take effect.
    echo.
    echo After restart, you should be able to use:
    echo - php artisan serve
    echo - php --version
    echo - composer commands
)

echo.
echo Current system PATH preview:
powershell -Command "& {$env:Path = [Environment]::GetEnvironmentVariable('Path','Machine'); $paths = $env:Path -split ';'; foreach($path in $paths[0..9]) { if($path -like '*php*') { Write-Host $path -ForegroundColor Green } else { Write-Host $path } }; if($paths.Length -gt 10) { Write-Host '... and more' }}"

echo.
pause
