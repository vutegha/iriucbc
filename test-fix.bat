@echo off
REM Test if the PHP version fix is working
echo Testing Laravel after PHP version fix...
echo.

echo Current PHP version being used:
C:\xampp\php\php.exe --version
echo.

echo Testing Laravel artisan:
C:\xampp\php\php.exe artisan --version
echo.

if errorlevel 1 (
    echo ❌ Laravel test failed
) else (
    echo ✅ Laravel is working correctly!
    echo.
    echo Now you can run:
    echo .\laravel.bat migrate
    echo .\laravel.bat serve
)

echo.
pause
