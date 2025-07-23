#!/usr/bin/env pwsh

Write-Host "=== Diagnostic Action Publier/Dépublier ===" -ForegroundColor Green

Write-Host "`n1. Vérification des routes de modération:" -ForegroundColor Yellow
try {
    $routes = php artisan route:list --path=publication | Select-String "publish"
    if ($routes) {
        Write-Host "✓ Routes de publication détectées:" -ForegroundColor Green
        $routes | ForEach-Object { Write-Host "  $_" -ForegroundColor White }
    } else {
        Write-Host "✗ Aucune route de publication trouvée" -ForegroundColor Red
    }
} catch {
    Write-Host "⚠ Erreur lors de la vérification des routes" -ForegroundColor Yellow
}

Write-Host "`n2. Test des méthodes de modération:" -ForegroundColor Yellow
$testScript = @'
<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Publication;
use App\Models\User;

echo "=== Test Système de Modération ===\n";

// 1. Test de l'existence des méthodes
echo "1. Vérification des méthodes sur Publication:\n";
$publication = new Publication();
$methods = ['publish', 'unpublish', 'isPendingModeration'];
foreach ($methods as $method) {
    $exists = method_exists($publication, $method);
    echo "   - $method: " . ($exists ? "✓ Existe" : "✗ Manquante") . "\n";
}

// 2. Test des permissions utilisateur
echo "\n2. Vérification des permissions utilisateur:\n";
try {
    $users = User::limit(3)->get();
    foreach ($users as $user) {
        $canModerate = method_exists($user, 'canModerate') ? $user->canModerate() : 'Méthode inexistante';
        echo "   - {$user->name}: Peut modérer = " . ($canModerate === true ? "✓ Oui" : ($canModerate === false ? "✗ Non" : $canModerate)) . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ Erreur: " . $e->getMessage() . "\n";
}

// 3. Test d'une publication
echo "\n3. Test avec une publication réelle:\n";
try {
    $publication = Publication::first();
    if ($publication) {
        echo "   - Publication: {$publication->titre}\n";
        echo "   - État actuel: " . ($publication->is_published ? "Publiée" : "En attente") . "\n";
        echo "   - Trait HasModeration: " . (method_exists($publication, 'publish') ? "✓ Appliqué" : "✗ Manquant") . "\n";
    } else {
        echo "   ⚠ Aucune publication trouvée en base\n";
    }
} catch (Exception $e) {
    echo "   ✗ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Fin du test ===\n";
?>
'@

$testScript | Out-File -FilePath "test_moderation_debug.php" -Encoding UTF8
try {
    php test_moderation_debug.php
} catch {
    Write-Host "⚠ Erreur lors de l'exécution du test PHP" -ForegroundColor Yellow
} finally {
    Remove-Item "test_moderation_debug.php" -ErrorAction SilentlyContinue
}

Write-Host "`n3. Vérification des middlewares de modération:" -ForegroundColor Yellow
$middleware = Get-Content "app/Http/Middleware/CanModerate.php" -ErrorAction SilentlyContinue
if ($middleware) {
    Write-Host "✓ Middleware CanModerate trouvé" -ForegroundColor Green
    $routeMiddleware = Select-String -Path "app/Http/Kernel.php" -Pattern "CanModerate" -ErrorAction SilentlyContinue
    if ($routeMiddleware) {
        Write-Host "✓ Middleware enregistré dans Kernel" -ForegroundColor Green
    } else {
        Write-Host "⚠ Middleware non enregistré dans Kernel" -ForegroundColor Yellow
    }
} else {
    Write-Host "✗ Middleware CanModerate introuvable" -ForegroundColor Red
}

Write-Host "`n4. Vérification JavaScript frontend:" -ForegroundColor Yellow
$jsContent = Get-Content "resources/views/admin/publication/index.blade.php" | Select-String "publishPublication|unpublishPublication"
if ($jsContent) {
    Write-Host "✓ Fonctions JavaScript trouvées:" -ForegroundColor Green
    $jsContent | ForEach-Object { Write-Host "  $_" -ForegroundColor White }
} else {
    Write-Host "✗ Fonctions JavaScript manquantes" -ForegroundColor Red
}

Write-Host "`n5. Vérification CSRF Token:" -ForegroundColor Yellow
$csrfCheck = Get-Content "resources/views/layouts/admin.blade.php" | Select-String "csrf-token"
if ($csrfCheck) {
    Write-Host "✓ Meta CSRF token configuré" -ForegroundColor Green
} else {
    Write-Host "⚠ Meta CSRF token potentiellement manquant" -ForegroundColor Yellow
}

Write-Host "`n=== Recommandations ===" -ForegroundColor Cyan
Write-Host "1. Vérifiez que l'utilisateur connecté a les permissions de modération" -ForegroundColor White
Write-Host "2. Ouvrez la console du navigateur pour voir les erreurs JavaScript" -ForegroundColor White
Write-Host "3. Vérifiez les logs Laravel (storage/logs/laravel.log)" -ForegroundColor White
Write-Host "4. Testez avec: php artisan tinker puis: auth()->user()->canModerate()" -ForegroundColor White

Write-Host "`nDiagnostic terminé!" -ForegroundColor Green
