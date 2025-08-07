<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "=== CORRECTION PERMISSIONS RAPPORT ===\n";

// 1. Vérifier et créer la permission manquante unpublish_rapports
echo "\n1. VÉRIFICATION DE LA PERMISSION unpublish_rapports:\n";
$unpublishPermission = Permission::where('name', 'unpublish_rapports')->first();
if (!$unpublishPermission) {
    echo "   ❌ Permission unpublish_rapports manquante, création...\n";
    $unpublishPermission = Permission::create([
        'name' => 'unpublish_rapports',
        'guard_name' => 'web'
    ]);
    echo "   ✅ Permission unpublish_rapports créée\n";
} else {
    echo "   ✅ Permission unpublish_rapports existe déjà\n";
}

// 2. Assigner les permissions aux rôles appropriés
echo "\n2. ASSIGNATION DES PERMISSIONS AUX RÔLES:\n";

$adminRole = Role::where('name', 'admin')->first();
$superAdminRole = Role::where('name', 'super-admin')->first();
$moderateurRole = Role::where('name', 'moderateur')->first();

if ($adminRole) {
    if (!$adminRole->hasPermissionTo('unpublish_rapports')) {
        $adminRole->givePermissionTo('unpublish_rapports');
        echo "   ✅ Permission unpublish_rapports assignée au rôle admin\n";
    }
}

if ($superAdminRole) {
    if (!$superAdminRole->hasPermissionTo('unpublish_rapports')) {
        $superAdminRole->givePermissionTo('unpublish_rapports');
        echo "   ✅ Permission unpublish_rapports assignée au rôle super-admin\n";
    }
}

if ($moderateurRole) {
    if (!$moderateurRole->hasPermissionTo('unpublish_rapports')) {
        $moderateurRole->givePermissionTo('unpublish_rapports');
        echo "   ✅ Permission unpublish_rapports assignée au rôle moderateur\n";
    }
}

// 3. Vérification finale
echo "\n3. VÉRIFICATION FINALE DES PERMISSIONS RAPPORT:\n";
$rapportPermissions = Permission::where('name', 'like', '%rapport%')->pluck('name')->sort();
foreach ($rapportPermissions as $perm) {
    echo "   ✅ {$perm}\n";
}

echo "\n=== CORRECTION TERMINÉE ===\n";
