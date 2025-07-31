@echo off
REM Quick fix for readonly keyword issue
echo Applying quick fix for readonly keyword error...

REM Backup the problematic file
if exist "vendor\sebastian\version\src\Version.php" (
    copy "vendor\sebastian\version\src\Version.php" "vendor\sebastian\version\src\Version.php.backup" >nul
    echo ✅ Backup created
)

REM Use XAMPP PHP to regenerate vendor
set "PATH=C:\xampp\php;%PATH%"

echo Regenerating Composer dependencies...
C:\xampp\php\php.exe composer.phar dump-autoload --optimize

echo Testing fix...
C:\xampp\php\php.exe artisan --version

echo.
echo If this doesn't work, run: .\fix-readonly-error.bat
pause
