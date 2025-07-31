@echo off
echo === DÉMARRAGE DU TEST COMPLET DU SYSTÈME EMAIL ===
echo.

echo 1. Vérification que XAMPP est démarré...
echo.

REM Vérifier si Apache est en cours d'exécution
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo ✓ Apache est démarré
) else (
    echo ✗ Apache n'est pas démarré
    echo Veuillez démarrer XAMPP Apache avant de continuer
    pause
    exit /b 1
)

REM Vérifier si MySQL est en cours d'exécution  
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo ✓ MySQL est démarré
) else (
    echo ✗ MySQL n'est pas démarré
    echo Veuillez démarrer XAMPP MySQL avant de continuer
    pause
    exit /b 1
)

echo.
echo 2. Nettoyage des logs...
php clear_logs.php

echo.
echo 3. Test de la configuration email...
php check_email_keys.php

echo.
echo 4. Ouverture du formulaire de contact...
start http://localhost/projets/iriucbc/public/contact

echo.
echo ============================================
echo INSTRUCTIONS :
echo 1. Une page de contact s'ouvre dans votre navigateur
echo 2. Remplissez et soumettez le formulaire
echo 3. Revenez ici et appuyez sur une touche
echo ============================================
pause

echo.
echo 5. Vérification des logs après soumission...
php show_logs.php

echo.
echo 6. Vérification en base de données...
php check_contact_db.php

echo.
echo === FIN DU TEST ===
pause
