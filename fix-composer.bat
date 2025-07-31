@echo off
REM Fix Composer platform requirements permanently
echo Fixing Composer platform requirements with PHP 8.2...

REM Set PATH to use XAMPP PHP 8.2
set "PATH=C:\xampp\php;%PATH%"

echo Current PHP version:
php --version
echo.

echo Clearing Composer cache...
C:\xampp\php\php.exe composer.phar clear-cache

echo Regenerating autoloader...
C:\xampp\php\php.exe composer.phar dump-autoload --optimize

echo Installing dependencies with PHP 8.2...
C:\xampp\php\php.exe composer.phar install --ignore-platform-req=ext-imagick

echo.
echo ✅ Composer platform requirements fixed!
echo You can now use normal Laravel commands with XAMPP PHP 8.2

pause
