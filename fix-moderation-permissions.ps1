#!/usr/bin/env pwsh

Write-Host "=== Configuration des Permissions de Modération ===" -ForegroundColor Green

Write-Host "`n1. Création des permissions de modération..." -ForegroundColor Yellow
$setupScript = @'
<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

echo "=== Configuration Permissions Modération ===\n";

// 1. Créer les permissions si elles n'existent pas
$permissions = [
    'moderate_content' => 'Modérer le contenu',
    'publish_content' => 'Publier le contenu',
    'unpublish_content' => 'Dépublier le contenu'
];

foreach ($permissions as $name => $description) {
    $permission = Permission::firstOrCreate(
        ['name' => $name],
        ['guard_name' => 'web']
    );
    echo "✓ Permission '$name' créée/vérifiée\n";
}

// 2. Créer le rôle modérateur si il n'existe pas
$moderatorRole = Role::firstOrCreate(
    ['name' => 'moderator'],
    ['guard_name' => 'web']
);
echo "✓ Rôle 'moderator' créé/vérifié\n";

// 3. Assigner les permissions au rôle modérateur
$moderatorRole->syncPermissions(['moderate_content', 'publish_content', 'unpublish_content']);
echo "✓ Permissions assignées au rôle modérateur\n";

// 4. Créer/mettre à jour le rôle admin
$adminRole = Role::firstOrCreate(
    ['name' => 'admin'],
    ['guard_name' => 'web']
);
$adminRole->givePermissionTo(['moderate_content', 'publish_content', 'unpublish_content']);
echo "✓ Permissions assignées au rôle admin\n";

// 5. Assigner le rôle admin au premier utilisateur (généralement l'admin)
$firstUser = User::first();
if ($firstUser) {
    $firstUser->assignRole('admin');
    echo "✓ Rôle admin assigné à l'utilisateur: {$firstUser->name}\n";
    echo "   - Peut modérer: " . ($firstUser->canModerate() ? 'Oui' : 'Non') . "\n";
} else {
    echo "⚠ Aucun utilisateur trouvé\n";
}

// 6. Lister tous les utilisateurs et leurs permissions
echo "\n=== État des utilisateurs ===\n";
$users = User::limit(5)->get();
foreach ($users as $user) {
    $roles = $user->roles->pluck('name')->join(', ');
    $canModerate = $user->canModerate() ? 'Oui' : 'Non';
    echo "- {$user->name} ({$user->email})\n";
    echo "  Rôles: {$roles}\n";
    echo "  Peut modérer: {$canModerate}\n\n";
}

echo "=== Configuration terminée ===\n";
?>
'@

$setupScript | Out-File -FilePath "setup_moderation_permissions.php" -Encoding UTF8
try {
    php setup_moderation_permissions.php
} catch {
    Write-Host "⚠ Erreur lors de la configuration des permissions" -ForegroundColor Yellow
    Write-Host $_.Exception.Message -ForegroundColor Red
} finally {
    Remove-Item "setup_moderation_permissions.php" -ErrorAction SilentlyContinue
}

Write-Host "`n2. Vérification de la configuration..." -ForegroundColor Yellow
$verifyScript = @'
<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Publication;

echo "=== Vérification Configuration ===\n";

// Test utilisateur connecté
if (auth()->check()) {
    $user = auth()->user();
    echo "Utilisateur connecté: {$user->name}\n";
    echo "Peut modérer: " . ($user->canModerate() ? 'Oui' : 'Non') . "\n";
} else {
    echo "Aucun utilisateur connecté - simulons avec le premier utilisateur\n";
    $user = User::first();
    if ($user) {
        auth()->login($user);
        echo "Utilisateur simulé: {$user->name}\n";
        echo "Peut modérer: " . ($user->canModerate() ? 'Oui' : 'Non') . "\n";
    }
}

// Test d'une action de publication
$publication = Publication::first();
if ($publication && $user) {
    echo "\nTest publication:\n";
    echo "- Titre: {$publication->titre}\n";
    echo "- État actuel: " . ($publication->is_published ? 'Publiée' : 'En attente') . "\n";
    
    try {
        if (!$publication->is_published) {
            $publication->publish($user, 'Test de publication automatique');
            echo "✓ Publication réussie\n";
        } else {
            $publication->unpublish('Test de dépublication automatique');
            echo "✓ Dépublication réussie\n";
        }
    } catch (Exception $e) {
        echo "✗ Erreur: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Fin vérification ===\n";
?>
'@

$verifyScript | Out-File -FilePath "verify_moderation.php" -Encoding UTF8
try {
    php verify_moderation.php
} catch {
    Write-Host "⚠ Erreur lors de la vérification" -ForegroundColor Yellow
} finally {
    Remove-Item "verify_moderation.php" -ErrorAction SilentlyContinue
}

Write-Host "`n=== Actions à effectuer ===" -ForegroundColor Cyan
Write-Host "1. Connectez-vous avec un compte administrateur" -ForegroundColor White
Write-Host "2. Vérifiez que l'utilisateur a le rôle 'admin' ou 'moderator'" -ForegroundColor White
Write-Host "3. Testez l'action publier/dépublier dans l'interface" -ForegroundColor White
Write-Host "4. Si ça ne marche toujours pas, vérifiez la console du navigateur" -ForegroundColor White

Write-Host "`nConfiguration terminée!" -ForegroundColor Green
