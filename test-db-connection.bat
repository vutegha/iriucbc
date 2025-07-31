@echo off
REM Laravel Database Connection Test
echo ================================
echo Laravel Database Connection Test
echo ================================
echo.

REM Set PATH to use XAMPP PHP
set "PATH=C:\xampp\php;%PATH%"

echo Testing Laravel database connection...
echo.

C:\xampp\php\php.exe artisan tinker --execute="echo 'Database: ' . config('database.connections.mysql.database'); echo 'Host: ' . config('database.connections.mysql.host'); try { DB::connection()->getPdo(); echo 'Status: ✅ Connected successfully!'; } catch(Exception $e) { echo 'Status: ❌ Connection failed: ' . $e->getMessage(); }"

echo.
pause
