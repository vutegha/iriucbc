#!/usr/bin/env pwsh

Write-Host "=== Test Simple Action Publier ===" -ForegroundColor Green

Write-Host "`n1. Test des permissions utilisateur..." -ForegroundColor Yellow
$userTest = @'
<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
$user = User::first();
if ($user) {
    echo "Utilisateur: " . $user->name . "\n";
    echo "Peut modérer: " . ($user->canModerate() ? "OUI" : "NON") . "\n";
    if (!$user->canModerate()) {
        $user->assignRole('admin');
        echo "Rôle admin assigné\n";
        echo "Peut maintenant modérer: " . ($user->canModerate() ? "OUI" : "NON") . "\n";
    }
} else {
    echo "Aucun utilisateur trouvé\n";
}
?>
'@

$userTest | Out-File -FilePath "quick_test.php" -Encoding UTF8
php quick_test.php
Remove-Item "quick_test.php"

Write-Host "`n2. Vérification des fonctions JavaScript..." -ForegroundColor Yellow
$jsExists = Get-Content "resources/views/admin/publication/index.blade.php" | Select-String "function publishPublication"
if ($jsExists) {
    Write-Host "✓ Fonction publishPublication trouvée" -ForegroundColor Green
} else {
    Write-Host "✗ Fonction publishPublication manquante" -ForegroundColor Red
}

Write-Host "`n3. Test des routes..." -ForegroundColor Yellow
try {
    $publishRoute = php artisan route:list | Select-String "publish.*POST.*publication"
    if ($publishRoute) {
        Write-Host "✓ Route de publication trouvée:" -ForegroundColor Green
        Write-Host "  $publishRoute" -ForegroundColor White
    } else {
        Write-Host "✗ Route de publication non trouvée" -ForegroundColor Red
    }
} catch {
    Write-Host "⚠ Erreur vérification routes" -ForegroundColor Yellow
}

Write-Host "`n=== Instructions de test ===" -ForegroundColor Cyan
Write-Host "1. Ouvrez votre navigateur sur http://localhost/admin/publication" -ForegroundColor White
Write-Host "2. Connectez-vous avec un compte admin" -ForegroundColor White  
Write-Host "3. Sur une publication, cliquez le menu (...)" -ForegroundColor White
Write-Host "4. Cliquez Publier/Dépublier" -ForegroundColor White
Write-Host "5. Ouvrez F12 > Console pour voir les erreurs" -ForegroundColor White

Write-Host "`nSi l'erreur persiste:" -ForegroundColor Yellow
Write-Host "- Vérifiez que l'utilisateur a le rôle 'admin'" -ForegroundColor White
Write-Host "- Vérifiez la console JavaScript (F12)" -ForegroundColor White
Write-Host "- Vérifiez les logs Laravel" -ForegroundColor White

Write-Host "`nTest terminé!" -ForegroundColor Green
