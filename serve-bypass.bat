@echo off
REM Laravel server with platform check bypass
echo Starting Laravel with PHP 8.2...

REM Temporarily rename platform check file
if exist "vendor\composer\platform_check.php" (
    ren "vendor\composer\platform_check.php" "platform_check.php.disabled"
    echo Platform check temporarily disabled
)

REM Set PATH to use XAMPP PHP
set "PATH=C:\xampp\php;%PATH%"

echo PHP Version:
php --version
echo.

echo Starting Laravel development server...
echo Server available at: http://127.0.0.1:8000
echo Press Ctrl+C to stop
echo.

php artisan serve --host=127.0.0.1 --port=8000

REM Restore platform check file when done
if exist "vendor\composer\platform_check.php.disabled" (
    ren "vendor\composer\platform_check.php.disabled" "platform_check.php"
    echo Platform check restored
)
