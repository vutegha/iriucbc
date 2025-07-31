@echo off
REM Manual migration without schema loading
echo ========================================
echo Manual Laravel Migration Setup
echo ========================================
echo.

REM Set PATH to include MySQL
set "PATH=C:\xampp\php;C:\xampp\mysql\bin;%PATH%"

echo Step 1: Removing the problematic schema file temporarily...
if exist "database\schema\mysql-schema.sql" (
    ren "database\schema\mysql-schema.sql" "mysql-schema.sql.disabled"
    echo ✅ Schema file temporarily disabled
)

echo.
echo Step 2: Running fresh migration...
php artisan migrate:fresh --force

echo.
echo Step 3: Checking if migration was successful...
php artisan migrate:status

echo.
echo Step 4: Restoring schema file...
if exist "database\schema\mysql-schema.sql.disabled" (
    ren "database\schema\mysql-schema.sql.disabled" "mysql-schema.sql"
    echo ✅ Schema file restored
)

echo.
echo Step 5: Testing Laravel...
php artisan --version

echo.
echo ========================================
echo ✅ MANUAL MIGRATION COMPLETED!
echo ========================================
echo.
pause
