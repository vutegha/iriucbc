@echo off
REM Script Windows pour lancer le queue worker en permanence
REM Placez ce fichier dans c:\xampp\htdocs\projets\iriucbc\

cd /d "c:\xampp\htdocs\projets\iriucbc"

:start
echo [%date% %time%] Démarrage du queue worker...
php artisan queue:work --tries=3 --timeout=60 --sleep=3 --max-jobs=1000 --max-time=3600

echo [%date% %time%] Queue worker arrêté, redémarrage dans 5 secondes...
timeout /t 5 /nobreak >nul
goto start
