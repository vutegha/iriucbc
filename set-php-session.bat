@echo off
REM Temporary PATH modification for current session only
echo ================================================
echo Setting XAMPP PHP 8.2 for Current Session
echo ================================================
echo.

echo Before change:
php --version 2>nul || echo "No PHP found in PATH"
echo.

echo Adding XAMPP PHP to current session PATH...
set "PATH=C:\xampp\php;%PATH%"

echo After change:
php --version
echo.

echo ✅ XAMPP PHP 8.2 is now active for this session!
echo.
echo You can now use:
echo - php artisan serve
echo - php --version  
echo - composer commands
echo.
echo Note: This change is temporary and will reset when you close this window.
echo For permanent changes, run: add-php-to-path.bat (as Administrator)
echo.
pause
