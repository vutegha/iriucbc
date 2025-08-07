<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== ANALYSE DES PERMISSIONS ADMIN ===" . PHP_EOL;

$admin = User::where('email', 'iri@ucbc.org')->first();

if (!$admin) {
    $admin = User::whereIn('email', ['admin@iriucbc.com', 'admin@iri.com'])->first();
}

if ($admin) {
    echo "✅ Admin trouvé: {$admin->name}" . PHP_EOL;
    
    // Permissions directes de l'utilisateur
    echo PHP_EOL . "📋 Permissions directes de l'utilisateur:" . PHP_EOL;
    $userPermissions = $admin->getDirectPermissions();
    if ($userPermissions->count() > 0) {
        $userPermissions->each(function($perm) {
            echo "  - {$perm->name}" . PHP_EOL;
        });
    } else {
        echo "  Aucune permission directe" . PHP_EOL;
    }
    
    // Permissions via les rôles
    echo PHP_EOL . "👤 Rôles de l'utilisateur:" . PHP_EOL;
    $roles = $admin->getRoleNames();
    $roles->each(function($roleName) {
        echo "  - {$roleName}" . PHP_EOL;
        
        $role = Role::findByName($roleName);
        $rolePermissions = $role->permissions;
        
        echo "    Permissions du rôle {$roleName}:" . PHP_EOL;
        if ($rolePermissions->count() > 0) {
            $rolePermissions->each(function($perm) {
                echo "      - {$perm->name}" . PHP_EOL;
            });
        } else {
            echo "      Aucune permission pour ce rôle" . PHP_EOL;
        }
    });
    
    // Permissions liées aux rapports existantes
    echo PHP_EOL . "📄 Permissions disponibles pour les rapports:" . PHP_EOL;
    $rapportPermissions = Permission::where('name', 'like', '%rapport%')->get();
    $rapportPermissions->each(function($perm) {
        echo "  - {$perm->name}" . PHP_EOL;
    });
    
} else {
    echo "❌ Aucun admin trouvé" . PHP_EOL;
}

echo PHP_EOL . "=== FIN DE L'ANALYSE ===" . PHP_EOL;

?>
