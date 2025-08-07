<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Rapport;

echo "=== TEST DES PERMISSIONS RAPPORTS ===" . PHP_EOL;

// Récupérer un utilisateur admin
$admin = User::where('email', 'iri@ucbc.org')->first();

if (!$admin) {
    // Essayer d'autres emails possibles
    $admin = User::whereIn('email', ['admin@iriucbc.com', 'admin@iri.com'])->first();
}

if (!$admin) {
    echo "❌ Utilisateur admin non trouvé" . PHP_EOL;
    echo "Utilisateurs disponibles:" . PHP_EOL;
    User::take(5)->get(['id', 'name', 'email'])->each(function($u) {
        echo "- {$u->name} ({$u->email})" . PHP_EOL;
    });
} else {

echo "=== TEST DES PERMISSIONS RAPPORTS ===" . PHP_EOL;

// Récupérer un utilisateur admin
$admin = User::where('email', 'admin@iriucbc.com')->first();

if (!$admin) {
    echo "❌ Utilisateur admin non trouvé" . PHP_EOL;
    echo "Utilisateurs disponibles:" . PHP_EOL;
    User::take(3)->get(['id', 'name', 'email'])->each(function($u) {
        echo "- {$u->name} ({$u->email})" . PHP_EOL;
    });
} else {
    echo "✅ Utilisateur admin trouvé: {$admin->name}" . PHP_EOL;
    
    // Test des rôles
    echo "Rôles: " . $admin->getRoleNames()->implode(', ') . PHP_EOL;
    
    // Test des permissions directes
    echo "Permissions: " . $admin->getPermissionNames()->implode(', ') . PHP_EOL;
    
    // Test des permissions via politique
    auth()->login($admin);
    
    $rapport = Rapport::first();
    if ($rapport) {
        echo "✅ Rapport de test trouvé: {$rapport->titre}" . PHP_EOL;
        
        // Test des permissions
        $canView = $admin->can('view', $rapport);
        $canCreate = $admin->can('create', Rapport::class);
        $canUpdate = $admin->can('update', $rapport);
        $canDelete = $admin->can('delete', $rapport);
        
        echo "Permissions sur le rapport:" . PHP_EOL;
        echo "- View: " . ($canView ? '✅' : '❌') . PHP_EOL;
        echo "- Create: " . ($canCreate ? '✅' : '❌') . PHP_EOL;
        echo "- Update: " . ($canUpdate ? '✅' : '❌') . PHP_EOL;
        echo "- Delete: " . ($canDelete ? '✅' : '❌') . PHP_EOL;
    } else {
        echo "❌ Aucun rapport trouvé dans la base de données" . PHP_EOL;
    }
}

echo PHP_EOL . "=== FIN DU TEST ===" . PHP_EOL;

?>
