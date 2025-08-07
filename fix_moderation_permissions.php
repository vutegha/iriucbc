<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Permission;

echo "=== VÉRIFICATION ET CORRECTION DES PERMISSIONS DE MODÉRATION ===" . PHP_EOL;
echo "Date: " . now()->format('Y-m-d H:i:s') . PHP_EOL;
echo "===============================================================" . PHP_EOL . PHP_EOL;

// Récupérer l'utilisateur admin
$user = User::first();

if (!$user) {
    echo "❌ Aucun utilisateur trouvé" . PHP_EOL;
    exit;
}

echo "👤 Utilisateur: " . $user->name . " (ID: " . $user->id . ")" . PHP_EOL;

// Vérifier les permissions nécessaires pour la modération
$requiredPermissions = [
    'publish rapports',
    'unpublish rapports', 
    'moderate rapports',
    'delete_rapport',
    'update_rapport',
    'view_rapport'
];

echo PHP_EOL . "🔍 VÉRIFICATION DES PERMISSIONS:" . PHP_EOL;
echo "--------------------------------" . PHP_EOL;

foreach ($requiredPermissions as $permission) {
    $exists = Permission::where('name', $permission)->where('guard_name', 'web')->exists();
    $userHas = $user->hasPermissionTo($permission);
    
    echo "Permission: {$permission}" . PHP_EOL;
    echo "  Existe: " . ($exists ? '✅' : '❌') . PHP_EOL;
    echo "  Utilisateur l'a: " . ($userHas ? '✅' : '❌') . PHP_EOL;
    
    if (!$exists) {
        // Créer la permission manquante
        Permission::create(['name' => $permission, 'guard_name' => 'web']);
        echo "  ➕ Permission créée!" . PHP_EOL;
    }
    
    if (!$userHas) {
        // Donner la permission à l'utilisateur
        $user->givePermissionTo($permission);
        echo "  ➕ Permission accordée à l'utilisateur!" . PHP_EOL;
    }
    
    echo PHP_EOL;
}

// Vérifier les permissions finales
echo "🏁 ÉTAT FINAL DES PERMISSIONS:" . PHP_EOL;
echo "-----------------------------" . PHP_EOL;

$user->refresh(); // Recharger l'utilisateur
foreach ($requiredPermissions as $permission) {
    $hasIt = $user->hasPermissionTo($permission);
    echo $permission . ": " . ($hasIt ? '✅' : '❌') . PHP_EOL;
}

echo PHP_EOL . "🔧 NETTOYAGE DU CACHE DES PERMISSIONS:" . PHP_EOL;
echo "-------------------------------------" . PHP_EOL;

// Nettoyer le cache des permissions
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
echo "✅ Cache des permissions nettoyé" . PHP_EOL;

echo PHP_EOL . "=== TERMINÉ ===" . PHP_EOL;

?>
