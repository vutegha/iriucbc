<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "=== GESTION DES PERMISSIONS DE PUBLICATION ===\n\n";

// 1. Permissions à créer
$permissionsToCreate = [
    'publish services',
    'unpublish services', 
    'publish actualites',
    'unpublish actualites',
    'publish projets',
    'unpublish projets',
    'publish evenements',
    'unpublish evenements',
    'publish rapports',
    'unpublish rapports'
];

echo "1. Création des permissions manquantes :\n";
foreach ($permissionsToCreate as $permissionName) {
    $permission = Permission::firstOrCreate([
        'name' => $permissionName,
        'guard_name' => 'web'
    ]);
    
    if ($permission->wasRecentlyCreated) {
        echo "   ✅ Permission '$permissionName' créée\n";
    } else {
        echo "   ℹ️  Permission '$permissionName' existe déjà\n";
    }
}

// 2. Vérifier le rôle super admin
echo "\n2. Attribution au rôle Super Admin :\n";
$superAdminRole = Role::where('name', 'super admin')->first();
if ($superAdminRole) {
    echo "   ✅ Rôle 'super admin' trouvé\n";
    
    foreach ($permissionsToCreate as $permissionName) {
        if (!$superAdminRole->hasPermissionTo($permissionName)) {
            $superAdminRole->givePermissionTo($permissionName);
            echo "   ✅ Permission '$permissionName' ajoutée au super admin\n";
        } else {
            echo "   ℹ️  Permission '$permissionName' déjà assignée\n";
        }
    }
} else {
    echo "   ❌ Rôle 'super admin' NON trouvé\n";
}

// 3. Vérification finale
echo "\n3. Permissions de publication disponibles :\n";
$publishPermissions = Permission::where('name', 'like', '%publish%')
    ->where('guard_name', 'web')
    ->get();

foreach ($publishPermissions as $permission) {
    echo "   - " . $permission->name . " (guard: " . $permission->guard_name . ")\n";
}

echo "\n=== TERMINÉ ===\n";
