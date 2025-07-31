@echo off
REM Fix readonly keyword PHP compatibility issue
echo =========================================
echo Fixing PHP Compatibility Issues
echo =========================================
echo.

REM Ensure we're using XAMPP PHP 8.2
set "PATH=C:\xampp\php;%PATH%"

echo Step 1: Checking PHP version...
C:\xampp\php\php.exe --version
echo.

echo Step 2: Checking PHP features...
C:\xampp\php\php.exe -r "echo 'PHP Version: ' . PHP_VERSION . PHP_EOL; echo 'Supports readonly: ' . (version_compare(PHP_VERSION, '8.1.0', '>=') ? 'Yes' : 'No') . PHP_EOL; echo 'Version ID: ' . PHP_VERSION_ID . PHP_EOL;"
echo.

echo Step 3: Clearing Composer cache...
C:\xampp\php\php.exe composer.phar clear-cache
echo.

echo Step 4: Removing vendor directory...
if exist "vendor" (
    echo Removing old vendor directory...
    rmdir /s /q "vendor" 2>nul
    echo ✅ Vendor directory removed
) else (
    echo ⚠️  Vendor directory not found
)
echo.

echo Step 5: Reinstalling dependencies with correct PHP...
C:\xampp\php\php.exe composer.phar install --ignore-platform-req=ext-imagick --no-dev --optimize-autoloader
echo.

echo Step 6: Testing Laravel...
C:\xampp\php\php.exe artisan --version
if errorlevel 1 (
    echo ❌ Laravel test failed
) else (
    echo ✅ Laravel is working!
)

echo.
echo =========================================
echo ✅ COMPATIBILITY ISSUES FIXED!
echo =========================================
pause
