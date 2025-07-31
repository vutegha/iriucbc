@echo off
REM Laravel Serve wrapper using XAMPP PHP 8.2
echo Starting Laravel development server...
echo Server will be available at: http://127.0.0.1:8000
echo Press Ctrl+C to stop the server
echo.
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000
