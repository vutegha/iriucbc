#!/usr/bin/env pwsh

Write-Host "=== Test Final Action Publier/Dépublier ===" -ForegroundColor Green

Write-Host "`n1. Vérification de la route protégée..." -ForegroundColor Yellow
try {
    $routes = php artisan route:list --path=publication | Select-String "publish"
    Write-Host "Routes de publication:" -ForegroundColor White
    $routes | ForEach-Object { Write-Host "  $_" -ForegroundColor Cyan }
} catch {
    Write-Host "⚠ Impossible de lister les routes" -ForegroundColor Yellow
}

Write-Host "`n2. Test de l'action publier/dépublier..." -ForegroundColor Yellow
$testPublish = @'
<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Publication;
use App\Models\User;

echo "=== Test Action Publier/Dépublier ===\n";

// Simuler un utilisateur admin connecté
$admin = User::first();
if (!$admin) {
    echo "✗ Aucun utilisateur trouvé\n";
    exit;
}

auth()->login($admin);
echo "✓ Utilisateur connecté: {$admin->name}\n";
echo "✓ Peut modérer: " . ($admin->canModerate() ? 'Oui' : 'Non') . "\n";

// Test sur une publication
$publication = Publication::first();
if (!$publication) {
    echo "✗ Aucune publication trouvée\n";
    exit;
}

echo "\n--- Test Publication ---\n";
echo "Titre: {$publication->titre}\n";
echo "État initial: " . ($publication->is_published ? 'Publiée' : 'En attente') . "\n";

try {
    if ($publication->is_published) {
        // Test dépublication
        $publication->unpublish('Test de dépublication via script');
        echo "✓ Dépublication réussie\n";
        echo "Nouvel état: " . ($publication->is_published ? 'Publiée' : 'En attente') . "\n";
        
        // Republier
        $publication->publish($admin, 'Test de republication via script');
        echo "✓ Republication réussie\n";
        echo "État final: " . ($publication->is_published ? 'Publiée' : 'En attente') . "\n";
    } else {
        // Test publication
        $publication->publish($admin, 'Test de publication via script');
        echo "✓ Publication réussie\n";
        echo "Nouvel état: " . ($publication->is_published ? 'Publiée' : 'En attente') . "\n";
    }
} catch (Exception $e) {
    echo "✗ Erreur lors du test: " . $e->getMessage() . "\n";
}

echo "\n=== Test terminé ===\n";
?>
'@

$testPublish | Out-File -FilePath "test_publish_action.php" -Encoding UTF8
try {
    php test_publish_action.php
} catch {
    Write-Host "⚠ Erreur lors du test" -ForegroundColor Yellow
    Write-Host $_.Exception.Message -ForegroundColor Red
} finally {
    Remove-Item "test_publish_action.php" -ErrorAction SilentlyContinue
}

Write-Host "`n3. Vérification des boutons dans l'interface..." -ForegroundColor Yellow
$jsCheck = Get-Content "resources/views/admin/publication/index.blade.php" | Select-String "onclick.*publishPublication|onclick.*unpublishPublication"
if ($jsCheck) {
    Write-Host "✓ Boutons JavaScript trouvés:" -ForegroundColor Green
    $jsCheck | ForEach-Object { Write-Host "  $_" -ForegroundColor White }
} else {
    Write-Host "⚠ Boutons JavaScript non trouvés" -ForegroundColor Yellow
}

Write-Host "`n4. Instructions pour tester dans le navigateur:" -ForegroundColor Cyan
Write-Host "1. Connectez-vous en tant qu'administrateur" -ForegroundColor White
Write-Host "2. Allez sur la page des publications (http://localhost/admin/publication)" -ForegroundColor White
Write-Host "3. Cliquez sur les trois points (...) d'une publication" -ForegroundColor White
Write-Host "4. Essayez 'Publier' ou 'Dépublier'" -ForegroundColor White
Write-Host "5. Ouvrez F12 > Console pour voir les erreurs éventuelles" -ForegroundColor White

Write-Host "`n=== Solutions si ça ne marche toujours pas ===" -ForegroundColor Yellow
Write-Host "• Vérifiez que vous êtes connecté en tant qu'admin" -ForegroundColor White
Write-Host "• Videz le cache: php artisan cache:clear" -ForegroundColor White
Write-Host "• Videz le cache des routes: php artisan route:clear" -ForegroundColor White
Write-Host "• Vérifiez les logs: tail -f storage/logs/laravel.log" -ForegroundColor White

Write-Host "`nTest terminé!" -ForegroundColor Green
