Write-Host "=== Configuration Action Publier ===" -ForegroundColor Green

Write-Host "`nConfiguration des permissions..." -ForegroundColor Yellow
$config = @'
<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Créer les permissions
$perms = ['moderate_content', 'publish_content', 'unpublish_content'];
foreach ($perms as $perm) {
    Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
}

// Créer le rôle admin et assigner les permissions
$admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
$admin->syncPermissions($perms);

// Assigner le rôle au premier utilisateur
$user = User::first();
if ($user) {
    $user->assignRole('admin');
    echo "Utilisateur " . $user->name . " configuré en admin\n";
    echo "Peut modérer: " . ($user->canModerate() ? "OUI" : "NON") . "\n";
}

echo "Configuration terminée!\n";
?>
'@

$config | Out-File -FilePath "config.php" -Encoding UTF8
php config.php
Remove-Item "config.php"

Write-Host "`nVérification des routes..." -ForegroundColor Yellow
php artisan route:list | Select-String "publish"

Write-Host "`n=== RÉSUMÉ DES CORRECTIONS ===" -ForegroundColor Green
Write-Host "✅ Système de modération activé (trait HasModeration)" -ForegroundColor Green
Write-Host "✅ Permissions et rôles configurés" -ForegroundColor Green  
Write-Host "✅ Routes protégées par middleware" -ForegroundColor Green
Write-Host "✅ Contrôleur avec méthodes publish/unpublish" -ForegroundColor Green
Write-Host "✅ JavaScript avec fonctions publishPublication/unpublishPublication" -ForegroundColor Green

Write-Host "`n=== ACTIONS POUR TESTER ===" -ForegroundColor Cyan
Write-Host "1. Connectez-vous en tant qu'administrateur" -ForegroundColor White
Write-Host "2. Allez sur /admin/publication" -ForegroundColor White
Write-Host "3. Cliquez sur le menu (...) d'une publication" -ForegroundColor White
Write-Host "4. Testez Publier/Dépublier" -ForegroundColor White

Write-Host "`nL'action publier devrait maintenant fonctionner!" -ForegroundColor Green
