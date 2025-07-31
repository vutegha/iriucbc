Write-Host "=== DIAGNOSTIC COMPLET DU SYSTÈME D'EMAILS ===" -ForegroundColor Cyan
Write-Host ""

$projectPath = "c:\xampp\htdocs\projets\iriucbc"
Set-Location $projectPath

Write-Host "1. Test de la configuration de base..." -ForegroundColor Yellow
php test_database_config.php

Write-Host ""
Write-Host "2. Test de la configuration SMTP..." -ForegroundColor Yellow  
php test_smtp_config.php

Write-Host ""
Write-Host "3. Test du système Laravel Mail..." -ForegroundColor Yellow
php test_laravel_mail.php

Write-Host ""
Write-Host "4. Vérification des logs actuels..." -ForegroundColor Yellow
if (Test-Path "storage\logs\laravel.log") {
    $logContent = Get-Content "storage\logs\laravel.log" -Raw
    if ([string]::IsNullOrEmpty($logContent)) {
        Write-Host "   - Aucun log présent" -ForegroundColor Gray
    } else {
        Write-Host "   - Logs détectés (" + $logContent.Length + " caractères)" -ForegroundColor Green
        Write-Host "   - Dernières lignes:" -ForegroundColor Gray
        Get-Content "storage\logs\laravel.log" | Select-Object -Last 5 | ForEach-Object { Write-Host "     $_" -ForegroundColor Gray }
    }
} else {
    Write-Host "   - Fichier de log non trouvé" -ForegroundColor Red
}

Write-Host ""
Write-Host "=== RÉSULTATS DU DIAGNOSTIC ===" -ForegroundColor Cyan
Write-Host "Si tous les tests sont ✓, le problème vient du formulaire web"
Write-Host "Si un test échoue, nous avons identifié le problème"
Write-Host ""
Write-Host "PROCHAINE ÉTAPE :" -ForegroundColor Yellow
Write-Host "Allez sur http://localhost/projets/iriucbc/public/contact"
Write-Host "Soumettez le formulaire, puis exécutez :"
Write-Host "php show_logs.php" -ForegroundColor Green
Write-Host ""
