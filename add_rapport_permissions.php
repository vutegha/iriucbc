<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== AJOUT DES PERMISSIONS RAPPORTS AU RÔLE ADMIN ===" . PHP_EOL;

$adminRole = Role::findByName('admin');

if ($adminRole) {
    echo "✅ Rôle admin trouvé" . PHP_EOL;
    
    // Permissions à ajouter
    $permissionsToAdd = [
        'view_rapports',
        'view_rapport', 
        'create_rapport',
        'update_rapport',
        'delete_rapport',
        'publish rapports',
        'unpublish rapports'
    ];
    
    foreach ($permissionsToAdd as $permissionName) {
        $permission = Permission::where('name', $permissionName)->first();
        if ($permission) {
            if (!$adminRole->hasPermissionTo($permission)) {
                $adminRole->givePermissionTo($permission);
                echo "✅ Permission '{$permissionName}' ajoutée au rôle admin" . PHP_EOL;
            } else {
                echo "ℹ️ Permission '{$permissionName}' déjà assignée" . PHP_EOL;
            }
        } else {
            echo "❌ Permission '{$permissionName}' non trouvée" . PHP_EOL;
        }
    }
    
    echo PHP_EOL . "🔄 Rechargement des permissions..." . PHP_EOL;
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    
    echo "✅ Permissions mises à jour avec succès !" . PHP_EOL;
    
} else {
    echo "❌ Rôle admin non trouvé" . PHP_EOL;
}

echo PHP_EOL . "=== FIN DE LA MISE À JOUR ===" . PHP_EOL;

?>
