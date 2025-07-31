@echo off
REM Diagnostic script for PHP readonly error
echo ==========================================
echo PHP Readonly Error Diagnostic
echo ==========================================
echo.

echo === PATH Analysis ===
echo Current PATH:
echo %PATH%
echo.

echo === PHP Executable Locations ===
echo Checking NetMake PHP:
if exist "C:\Program Files\NetMake\v9-php81\components\php\php.exe" (
    echo ✅ NetMake PHP found
    "C:\Program Files\NetMake\v9-php81\components\php\php.exe" --version
) else (
    echo ❌ NetMake PHP not found
)
echo.

echo Checking XAMPP PHP:
if exist "C:\xampp\php\php.exe" (
    echo ✅ XAMPP PHP found
    C:\xampp\php\php.exe --version
) else (
    echo ❌ XAMPP PHP not found
)
echo.

echo === Which PHP is being used by default ===
php --version 2>nul || echo "No default PHP found in PATH"
echo.

echo === Checking vendor directory ===
if exist "vendor" (
    echo ✅ Vendor directory exists
    dir vendor /b | findstr "sebastian"
) else (
    echo ❌ Vendor directory not found
)
echo.

echo === Recommended Solutions ===
echo 1. Run: .\fix-readonly-error.bat (complete reinstall)
echo 2. Run: .\quick-readonly-fix.bat (quick fix)
echo 3. Use: .\laravel.bat commands (uses XAMPP PHP)
echo.
pause
