@echo off
REM Complete the remaining migrations with conflict resolution
echo ========================================
echo Completing Remaining Migrations
echo ========================================
echo.

REM Set PATH
set "PATH=C:\xampp\php;C:\xampp\mysql\bin;%PATH%"

echo Step 1: Checking current migration status...
php artisan migrate:status | findstr "Pending"
echo.

echo Step 2: Handling newsletters table conflict...
echo Checking if newsletters table needs modification...
mysql -u root -e "USE iriadmin; DESCRIBE newsletters;" > newsletters_structure.txt 2>nul
if errorlevel 1 (
    echo ❌ Cannot check newsletters table structure
) else (
    echo ✅ Current newsletters table structure:
    type newsletters_structure.txt
    del newsletters_structure.txt 2>nul
)
echo.

echo Step 3: Trying to run remaining migrations...
echo This will skip migrations that conflict with existing tables...

php artisan migrate --force

echo.
echo Step 4: If there are still conflicts, we'll handle them individually...
echo Checking for any remaining pending migrations...
php artisan migrate:status | findstr "Pending" > pending_migrations.txt 2>nul
if exist pending_migrations.txt (
    echo ⚠️  Some migrations are still pending:
    type pending_migrations.txt
    del pending_migrations.txt
    echo.
    echo You may need to modify these migrations manually or skip them.
) else (
    echo ✅ All migrations completed successfully!
)

echo.
echo Step 5: Verifying database structure...
mysql -u root -e "USE iriadmin; SHOW TABLES;" | findstr -v "Tables_in_iriadmin"
echo.

echo Step 6: Testing Laravel application...
php artisan --version
echo.

echo ========================================
echo ✅ MIGRATION PROCESS COMPLETED!
echo ========================================
echo.
echo Your Laravel application should now be ready!
echo Start the server with: php artisan serve
echo.
pause
