@echo off
REM Complete Database Setup and Migration Script
echo ========================================
echo IRI Laravel - Complete Database Setup
echo ========================================
echo.

REM Set PATH to use XAMPP binaries
set "PATH=C:\xampp\mysql\bin;C:\xampp\php;%PATH%"

echo Step 1: Testing MySQL connection...
mysql -u root -e "SELECT 'MySQL Connected!' as status;" 2>nul
if errorlevel 1 (
    echo ❌ MySQL is not running!
    echo Please start XAMPP and make sure MySQL is running
    echo Then run this script again
    pause
    exit /b 1
)
echo ✅ MySQL is running!
echo.

echo Step 2: Creating database 'iriadmin'...
mysql -u root -e "CREATE DATABASE IF NOT EXISTS iriadmin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul
if errorlevel 1 (
    echo ❌ Failed to create database
    pause
    exit /b 1
)
echo ✅ Database 'iriadmin' created successfully!
echo.

echo Step 3: Verifying database exists...
mysql -u root -e "USE iriadmin; SELECT 'Database is accessible!' as status;" 2>nul
if errorlevel 1 (
    echo ❌ Cannot access database
    pause
    exit /b 1
)
echo ✅ Database is accessible!
echo.

echo Step 4: Running Laravel migrations...
echo This may take a moment...
C:\xampp\php\php.exe artisan migrate --force
if errorlevel 1 (
    echo ❌ Migration failed!
    echo Please check the error messages above
    pause
    exit /b 1
)
echo ✅ All migrations completed successfully!
echo.

echo Step 5: Verifying tables were created...
mysql -u root -e "USE iriadmin; SHOW TABLES;" > tables_list.txt 2>nul
if errorlevel 1 (
    echo ⚠️  Could not verify tables
) else (
    echo ✅ Tables created successfully:
    type tables_list.txt
    del tables_list.txt 2>nul
)
echo.

echo Step 6: Testing Laravel database connection...
C:\xampp\php\php.exe artisan tinker --execute="try { echo 'Testing connection...'; DB::connection()->getPdo(); echo 'Laravel can connect to database successfully!'; } catch(Exception $e) { echo 'Connection test failed: ' . $e->getMessage(); }" 2>nul
echo.

echo ========================================
echo ✅ SETUP COMPLETED SUCCESSFULLY!
echo ========================================
echo Database: iriadmin
echo Host: 127.0.0.1:3306
echo Username: root
echo Password: (empty)
echo.
echo Your Laravel application is now ready!
echo Start the development server with:
echo .\serve-php82.bat
echo.
pause
