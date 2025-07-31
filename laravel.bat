@echo off
REM Laravel Artisan wrapper using XAMPP PHP 8.2
REM This ensures we always use the correct PHP version

REM Add XAMPP PHP to PATH for this session
set "PATH=C:\xampp\php;%PATH%"

REM Run artisan with explicit PHP path for reliability
C:\xampp\php\php.exe artisan %*
