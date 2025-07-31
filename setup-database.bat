@echo off
REM Database Setup Script for IRI Laravel Project
echo ================================
echo IRI Laravel Database Setup
echo ================================
echo.

REM Set PATH to use XAMPP binaries
set "PATH=C:\xampp\mysql\bin;C:\xampp\php;%PATH%"

echo Step 1: Checking MySQL connection...
mysql -u root -p -e "SELECT 'MySQL is running!' as status;" 2>nul
if errorlevel 1 (
    echo ❌ MySQL is not running or not accessible
    echo Please start XAMPP and make sure MySQL is running
    echo Then run this script again
    pause
    exit /b 1
)

echo ✅ MySQL is running!
echo.

echo Step 2: Creating database 'iriadmin'...
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS iriadmin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if errorlevel 1 (
    echo ❌ Failed to create database
    pause
    exit /b 1
)

echo ✅ Database 'iriadmin' created successfully!
echo.

echo Step 3: Running Laravel migrations...
C:\xampp\php\php.exe artisan migrate --force
if errorlevel 1 (
    echo ❌ Migration failed
    pause
    exit /b 1
)

echo ✅ Migrations completed successfully!
echo.

echo Step 4: Seeding database (if seeders exist)...
C:\xampp\php\php.exe artisan db:seed --force 2>nul
if errorlevel 1 (
    echo ⚠️  No seeders found or seeding failed (this might be normal)
) else (
    echo ✅ Database seeded successfully!
)

echo.
echo ================================
echo ✅ Database setup completed!
echo ================================
echo Database: iriadmin
echo Host: 127.0.0.1:3306
echo Username: root
echo Password: (empty)
echo.
echo You can now start your Laravel application:
echo .\serve-php82.bat
echo.
pause
