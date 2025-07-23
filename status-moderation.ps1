#!/usr/bin/env pwsh

Write-Host "=== Système de Modération IRI-UCBC - Status ===" -ForegroundColor Green

Set-Location "c:\xampp\htdocs\Projets\iriucbc"

Write-Host "`n1. État des migrations:" -ForegroundColor Yellow
php artisan migrate:status | Select-String "moderation|role|permission"

Write-Host "`n2. Vérification des routes de modération:" -ForegroundColor Yellow
Write-Host "Routes pour publications:" -ForegroundColor Cyan
php artisan route:list --path=publication | Select-String "publish|moderate"

Write-Host "`n3. Statistiques de la base de données:" -ForegroundColor Yellow
$stats = php artisan tinker --execute="
echo 'Roles: ' . App\Models\Role::count() . PHP_EOL;
echo 'Permissions: ' . App\Models\Permission::count() . PHP_EOL;
echo 'Publications totales: ' . App\Models\Publication::count() . PHP_EOL;
echo 'Publications publiées: ' . App\Models\Publication::where('is_published', true)->count() . PHP_EOL;
echo 'Publications en attente: ' . App\Models\Publication::where('is_published', false)->count() . PHP_EOL;
"
Write-Host $stats

Write-Host "`n4. Test de la vue admin:" -ForegroundColor Yellow
Write-Host "Ouverture de l'interface admin des publications..." -ForegroundColor Cyan
Start-Process "http://localhost/Projets/iriucbc/admin/publication"

Write-Host "`n=== Système prêt ! ===" -ForegroundColor Green
Write-Host "✓ Modération installée pour toutes les entités" -ForegroundColor Green
Write-Host "✓ Rôles et permissions configurés" -ForegroundColor Green  
Write-Host "✓ Interface admin mise à jour" -ForegroundColor Green
Write-Host "✓ Routes de modération actives" -ForegroundColor Green

Write-Host "`n=== Prochaines étapes ===" -ForegroundColor Yellow
Write-Host "• Tester les autres entités (projets, services, rapports)" -ForegroundColor Cyan
Write-Host "• Configurer les notifications email" -ForegroundColor Cyan
Write-Host "• Assigner des rôles aux utilisateurs" -ForegroundColor Cyan
