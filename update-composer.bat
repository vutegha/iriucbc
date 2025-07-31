@echo off
REM Composer Update with XAMPP PHP 8.2
echo ========================================
echo Running Composer Update
echo ========================================
echo.

REM Set PATH to prioritize XAMPP PHP
set "PATH=C:\xampp\php;%PATH%"

echo Current PHP version:
C:\xampp\php\php.exe --version
echo.

echo Checking Composer version:
C:\xampp\php\php.exe composer.phar --version
echo.

echo Running composer update...
echo This may take several minutes...
echo.

C:\xampp\php\php.exe composer.phar update --ignore-platform-req=ext-imagick --with-dependencies --verbose

if errorlevel 1 (
    echo.
    echo ❌ Composer update failed!
    echo Try running with different options:
    echo 1. .\composer.bat update --no-dev
    echo 2. .\composer.bat update --ignore-platform-reqs
    pause
    exit /b 1
) else (
    echo.
    echo ✅ Composer update completed successfully!
    echo.
    echo Testing Laravel...
    C:\xampp\php\php.exe artisan --version
    echo.
    echo You can now run:
    echo - php artisan migrate
    echo - php artisan serve
)

echo.
pause
