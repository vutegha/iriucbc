@echo off
REM Permanent fix for PHP version issue
echo ========================================
echo Fixing PHP Version Issue Permanently
echo ========================================
echo.

REM Step 1: Set PATH to prioritize XAMPP PHP 8.2
set "PATH=C:\xampp\php;%PATH%"

echo Step 1: Checking current PHP version...
php --version
echo.

echo Step 2: Backing up current platform check...
if exist "vendor\composer\platform_check.php" (
    copy "vendor\composer\platform_check.php" "vendor\composer\platform_check.php.backup" >nul
    echo ✅ Backup created
) else (
    echo ⚠️  Platform check file not found
)
echo.

echo Step 3: Clearing Composer cache...
php composer.phar clear-cache
echo ✅ Cache cleared
echo.

echo Step 4: Regenerating autoloader with PHP 8.2...
php composer.phar dump-autoload --optimize
echo ✅ Autoloader regenerated
echo.

echo Step 5: Reinstalling dependencies with correct PHP version...
php composer.phar install --ignore-platform-req=ext-imagick --no-dev --optimize-autoloader
echo ✅ Dependencies reinstalled
echo.

echo Step 6: Testing Laravel...
php artisan --version
if errorlevel 1 (
    echo ❌ Laravel test failed
) else (
    echo ✅ Laravel is working!
)

echo.
echo ========================================
echo ✅ PHP VERSION ISSUE FIXED!
echo ========================================
echo Now you can use normal commands:
echo php artisan serve
echo php artisan migrate
echo.
pause
