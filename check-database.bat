@echo off
REM Database Check Script for IRI Laravel Project
echo ================================
echo Database Status Check
echo ================================
echo.

REM Set PATH to use XAMPP binaries
set "PATH=C:\xampp\mysql\bin;C:\xampp\php;%PATH%"

echo Checking MySQL connection...
mysql -u root -e "SELECT 'MySQL is accessible!' as status;" 2>nul
if errorlevel 1 (
    echo ❌ MySQL is not running or not accessible
    echo Please start XAMPP MySQL service
    goto :end
)

echo ✅ MySQL is running!
echo.

echo Checking if database 'iriadmin' exists...
mysql -u root -e "SHOW DATABASES LIKE 'iriadmin';" 2>nul | find "iriadmin" >nul
if errorlevel 1 (
    echo ❌ Database 'iriadmin' does NOT exist
    echo.
    echo Available databases:
    mysql -u root -e "SHOW DATABASES;"
    echo.
    echo To create the database, run: .\setup-database.bat
) else (
    echo ✅ Database 'iriadmin' exists!
    echo.
    echo Checking tables in database...
    mysql -u root -e "USE iriadmin; SHOW TABLES;" 2>nul
    if errorlevel 1 (
        echo ⚠️  Could not access tables (database might be empty)
        echo Run: .\laravel.bat migrate
    ) else (
        echo ✅ Database has tables!
    )
)

:end
echo.
pause
