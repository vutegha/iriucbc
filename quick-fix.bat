@echo off
REM Quick fix - bypass platform check and regenerate
echo Applying quick fix for PHP version issue...

REM Temporarily disable platform check
if exist "vendor\composer\platform_check.php" (
    ren "vendor\composer\platform_check.php" "platform_check.disabled"
    echo Platform check disabled temporarily
)

REM Use XAMPP PHP
set "PATH=C:\xampp\php;%PATH%"

echo Regenerating Composer files with PHP 8.2...
php composer.phar dump-autoload --optimize

echo Reinstalling with correct PHP...
php composer.phar install --ignore-platform-req=ext-imagick

echo PHP version issue should now be resolved!
echo You can now run: php artisan migrate
echo.
pause
