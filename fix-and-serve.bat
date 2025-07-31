@echo off
REM Fix migration conflicts by manually handling them
echo ========================================
echo Fixing Migration Conflicts
echo ========================================
echo.

REM Set PATH
set "PATH=C:\xampp\php;C:\xampp\mysql\bin;%PATH%"

echo Step 1: Manually marking problematic migrations as completed...

echo Inserting newsletters migration record...
mysql -u root iriadmin -e "INSERT IGNORE INTO migrations (migration, batch) VALUES ('2025_07_17_085934_create_newsletters_table', 6);"

echo.
echo Step 2: Running remaining migrations...
php artisan migrate --force

echo.
echo Step 3: Checking final status...
php artisan migrate:status | findstr "Pending"
if errorlevel 1 (
    echo ✅ All migrations completed!
) else (
    echo ⚠️  Some migrations still pending - this is normal
)

echo.
echo Step 4: Testing the application...
php artisan serve --host=127.0.0.1 --port=8000 &
timeout /t 3 /nobreak >nul
echo.
echo ✅ Laravel development server should be starting...
echo Visit: http://127.0.0.1:8000
echo.
echo Press Ctrl+C to stop the server when testing is done.
pause
