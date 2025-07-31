# Test de la fonctionnalité de modération et des checkboxes
Write-Host "=== TEST MODÉRATION ET CHECKBOXES ===" -ForegroundColor Green

# Vérifier si le serveur Laravel est en cours d'exécution
$serverRunning = Get-Process | Where-Object { $_.ProcessName -eq "php" -and $_.CommandLine -like "*artisan serve*" }

if (-not $serverRunning) {
    Write-Host "Démarrage du serveur Laravel..." -ForegroundColor Yellow
    Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd 'c:\xampp\htdocs\projets\iriucbc'; php artisan serve"
    Start-Sleep -Seconds 3
}

Write-Host "Serveur Laravel en cours d'exécution" -ForegroundColor Green

# URLs de test
$baseUrl = "http://localhost:8000"
$adminUrl = "$baseUrl/admin"
$publicationUrl = "$adminUrl/publication"

Write-Host "`nOuverture des pages de test..." -ForegroundColor Yellow

# Ouvrir les pages importantes pour les tests
Write-Host "1. Page d'administration: $adminUrl" -ForegroundColor Cyan
Write-Host "2. Liste des publications: $publicationUrl" -ForegroundColor Cyan

# Instructions pour le test
Write-Host "`n=== INSTRUCTIONS DE TEST ===" -ForegroundColor Green
Write-Host "1. Connectez-vous avec un compte admin/moderator" -ForegroundColor White
Write-Host "2. Allez dans Publications > Voir une publication" -ForegroundColor White
Write-Host "3. Vérifiez la section 'Actions de Modération'" -ForegroundColor White
Write-Host "4. Testez les boutons Approuver/Dépublier" -ForegroundColor White
Write-Host "5. Créez/Modifiez une publication pour tester les checkboxes" -ForegroundColor White

Write-Host "`n=== POINTS À VÉRIFIER ===" -ForegroundColor Green
Write-Host "✓ Section 'Actions de Modération' visible pour admin/moderator" -ForegroundColor White
Write-Host "✓ Boutons d'action (Approuver/Dépublier) fonctionnels" -ForegroundColor White
Write-Host "✓ Modal de commentaire s'ouvre correctement" -ForegroundColor White
Write-Host "✓ Checkboxes 'À la une' et 'En vedette' fonctionnelles" -ForegroundColor White
Write-Host "✓ Valeurs des checkboxes sauvegardées en base de données" -ForegroundColor White
Write-Host "✓ Permissions respectées (actions visibles selon le rôle)" -ForegroundColor White

Write-Host "`nAppuyez sur une touche pour ouvrir le navigateur..." -ForegroundColor Yellow
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

# Ouvrir le navigateur
Start-Process $publicationUrl

Write-Host "`nTest terminé. Vérifiez manuellement les fonctionnalités." -ForegroundColor Green
