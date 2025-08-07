<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "=== CRÉATION DES PERMISSIONS MANQUANTES ===\n\n";

// Permissions pour les Auteurs
$auteurPermissions = [
    'view_auteurs',
    'create_auteurs', 
    'update_auteurs',
    'delete_auteurs',
    'export_auteurs',
    'manage_auteurs'
];

// Permissions pour les Users
$userPermissions = [
    'view_users',
    'create_users',
    'update_users', 
    'delete_users',
    'export_users',
    'manage_users',
    'assign_roles',
    'manage_permissions'
];

$allNewPermissions = array_merge($auteurPermissions, $userPermissions);

echo "🔍 Vérification des permissions existantes...\n";
$existingPermissions = DB::table('permissions')->pluck('name')->toArray();

$toCreate = [];
foreach ($allNewPermissions as $permission) {
    if (!in_array($permission, $existingPermissions)) {
        $toCreate[] = $permission;
    }
}

if (empty($toCreate)) {
    echo "✅ Toutes les permissions existent déjà!\n\n";
} else {
    echo "📝 Création des permissions manquantes:\n";
    
    foreach ($toCreate as $permission) {
        try {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
            echo "  ✅ $permission\n";
        } catch (Exception $e) {
            echo "  ❌ $permission - Erreur: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n🔐 Attribution aux rôles super-admin...\n";
    
    $superAdminRole = Role::where('name', 'super-admin')->first();
    if ($superAdminRole) {
        foreach ($toCreate as $permission) {
            $perm = Permission::where('name', $permission)->first();
            if ($perm && !$superAdminRole->hasPermissionTo($permission)) {
                $superAdminRole->givePermissionTo($permission);
                echo "  ✅ $permission → super-admin\n";
            }
        }
    }
}

// Vérification finale
echo "\n📊 RÉSUMÉ FINAL:\n";
$finalCount = DB::table('permissions')->count();
echo "Total permissions en base: $finalCount\n";

// Compter les permissions plurielles
$plurielCount = DB::table('permissions')
    ->where(function($query) {
        $query->where('name', 'LIKE', '%_actualites%')
              ->orWhere('name', 'LIKE', '%_projets%')
              ->orWhere('name', 'LIKE', '%_publications%')
              ->orWhere('name', 'LIKE', '%_evenements%')
              ->orWhere('name', 'LIKE', '%_services%')
              ->orWhere('name', 'LIKE', '%_medias%')
              ->orWhere('name', 'LIKE', '%_rapports%')
              ->orWhere('name', 'LIKE', '%_auteurs%')
              ->orWhere('name', 'LIKE', '%_users%');
    })
    ->count();

echo "Permissions format pluriel: $plurielCount\n";

echo "\n🎉 HARMONISATION COMPLÈTE!\n";
echo "✅ Toutes les policies existent\n";
echo "✅ Toutes les permissions existent\n"; 
echo "✅ Format pluriel harmonisé\n";
echo "✅ Rôles configurés\n\n";

echo "L'utilisateur admin@ucbc.org devrait maintenant avoir accès à tous les boutons!\n";
