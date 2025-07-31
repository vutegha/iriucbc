@echo off
echo === OUVERTURE DE LA PAGE DE CONTACT POUR TEST ===
echo.

REM Vider les logs d'abord
php clear_logs.php

echo.
echo Ouverture de la page de contact dans le navigateur...
start http://localhost/projets/iriucbc/public/contact

echo.
echo INSTRUCTIONS:
echo 1. La page de contact devrait s'ouvrir dans votre navigateur
echo 2. Remplissez et soumettez le formulaire
echo 3. Revenez ici et appuyez sur une touche pour voir les logs
echo.
pause

echo.
echo === AFFICHAGE DES LOGS ===
php show_logs.php

echo.
echo === VÉRIFICATION EN BASE DE DONNÉES ===
php check_contact_db.php

pause
