@echo off
REM Multiple Composer Update Options
echo ========================================
echo Composer Update Options
echo ========================================
echo.
echo Please choose an option:
echo.
echo 1. Standard update (recommended)
echo 2. Update without dev dependencies  
echo 3. Update ignoring all platform requirements
echo 4. Force update (removes lock file)
echo 5. Update specific packages only
echo.
set /p choice="Enter your choice (1-5): "

REM Set PATH for XAMPP PHP
set "PATH=C:\xampp\php;%PATH%"

echo.
echo Using PHP version:
C:\xampp\php\php.exe --version | findstr "PHP"
echo.

if "%choice%"=="1" (
    echo Running standard composer update...
    C:\xampp\php\php.exe composer.phar update --ignore-platform-req=ext-imagick --with-dependencies
) else if "%choice%"=="2" (
    echo Running composer update without dev dependencies...
    C:\xampp\php\php.exe composer.phar update --no-dev --ignore-platform-req=ext-imagick
) else if "%choice%"=="3" (
    echo Running composer update ignoring all platform requirements...
    C:\xampp\php\php.exe composer.phar update --ignore-platform-reqs
) else if "%choice%"=="4" (
    echo Force update - removing composer.lock...
    if exist "composer.lock" del "composer.lock"
    C:\xampp\php\php.exe composer.phar install --ignore-platform-req=ext-imagick
) else if "%choice%"=="5" (
    set /p packages="Enter package names (e.g., laravel/framework spatie/laravel-permission): "
    echo Updating specific packages: %packages%
    C:\xampp\php\php.exe composer.phar update %packages% --ignore-platform-req=ext-imagick
) else (
    echo Invalid choice. Running standard update...
    C:\xampp\php\php.exe composer.phar update --ignore-platform-req=ext-imagick --with-dependencies
)

echo.
echo Composer update process completed.
echo.
echo Testing Laravel...
C:\xampp\php\php.exe artisan --version >nul 2>&1
if errorlevel 1 (
    echo ⚠️  Laravel test failed - there might be issues
) else (
    echo ✅ Laravel is working correctly!
)

echo.
pause
