@echo off
REM Temporarily add XAMPP PHP to PATH and start Laravel server
echo Setting up PHP 8.2 environment...
set "ORIGINAL_PATH=%PATH%"
set "PATH=C:\xampp\php;%PATH%"

echo Checking PHP version:
php --version

echo.
echo Starting Laravel development server...
echo Server will be available at: http://127.0.0.1:8000
echo Press Ctrl+C to stop the server
echo.

php artisan serve --host=127.0.0.1 --port=8000

REM Restore original PATH when done
set "PATH=%ORIGINAL_PATH%"
