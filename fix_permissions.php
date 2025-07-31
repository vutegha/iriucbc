<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

echo "=== CORRECTION DES PERMISSIONS SUPER ADMIN ===\n\n";

// 1. Vérifier les permissions existantes
echo "1. Permissions existantes :\n";
$permissions = Permission::all();
foreach ($permissions as $permission) {
    echo "   - " . $permission->name . "\n";
}

// 2. Vérifier le rôle super admin
echo "\n2. Rôle Super Admin :\n";
$superAdminRole = Role::where('name', 'super admin')->first();
if ($superAdminRole) {
    echo "   ✅ Rôle 'super admin' trouvé\n";
    echo "   Permissions actuelles du rôle :\n";
    foreach ($superAdminRole->permissions as $permission) {
        echo "   - " . $permission->name . "\n";
    }
} else {
    echo "   ❌ Rôle 'super admin' NON trouvé\n";
}

// 3. Assigner les permissions manquantes
echo "\n3. Assignation des permissions :\n";
$permissionsToAdd = ['update actualites', 'update evenements'];

foreach ($permissionsToAdd as $permissionName) {
    $permission = Permission::where('name', $permissionName)->first();
    if ($permission && $superAdminRole) {
        if (!$superAdminRole->hasPermissionTo($permissionName)) {
            $superAdminRole->givePermissionTo($permissionName);
            echo "   ✅ Permission '$permissionName' ajoutée\n";
        } else {
            echo "   ℹ️  Permission '$permissionName' déjà présente\n";
        }
    } else {
        echo "   ❌ Permission '$permissionName' ou rôle introuvable\n";
    }
}

// 4. Vérification finale
echo "\n4. Vérification finale :\n";
$user = User::where('email', 'sergyo.vutegha@gmail.com')->first();
if ($user) {
    echo "   Utilisateur : " . $user->name . "\n";
    echo "   Email : " . $user->email . "\n";
    echo "   Permissions :\n";
    foreach ($permissionsToAdd as $permissionName) {
        $hasPermission = $user->can($permissionName) ? '✅ OUI' : '❌ NON';
        echo "   - $permissionName : $hasPermission\n";
    }
}

echo "\n=== TERMINÉ ===\n";
