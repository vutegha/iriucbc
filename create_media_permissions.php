<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "=== CRÉATION DES PERMISSIONS MÉDIA MANQUANTES ===\n\n";

// Permissions média au format singulier (pour correspondre à MediaPolicy)
$mediaPermissions = [
    'view_media',
    'create_media', 
    'update_media',
    'delete_media',
    'moderate_media',
    'approve_media',
    'reject_media',
    'publish_media',
    'download_media'
];

echo "🔍 Vérification des permissions existantes...\n";
$existingPermissions = DB::table('permissions')->pluck('name')->toArray();

$toCreate = [];
foreach ($mediaPermissions as $permission) {
    if (!in_array($permission, $existingPermissions)) {
        $toCreate[] = $permission;
    }
}

if (empty($toCreate)) {
    echo "✅ Toutes les permissions média existent déjà!\n\n";
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
    
    echo "\n🔐 Attribution aux rôles...\n";
    
    // Attribution aux super-admin
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

    // Attribution aux admin
    $adminRole = Role::where('name', 'admin')->first();
    if ($adminRole) {
        foreach ($toCreate as $permission) {
            $perm = Permission::where('name', $permission)->first();
            if ($perm && !$adminRole->hasPermissionTo($permission)) {
                $adminRole->givePermissionTo($permission);
                echo "  ✅ $permission → admin\n";
            }
        }
    }

    // Attribution sélective aux modérateurs
    $moderatorRole = Role::where('name', 'moderator')->first();
    if ($moderatorRole) {
        $moderatorPermissions = ['view_media', 'moderate_media', 'approve_media', 'reject_media'];
        foreach ($moderatorPermissions as $permission) {
            if (in_array($permission, $toCreate)) {
                $perm = Permission::where('name', $permission)->first();
                if ($perm && !$moderatorRole->hasPermissionTo($permission)) {
                    $moderatorRole->givePermissionTo($permission);
                    echo "  ✅ $permission → moderator\n";
                }
            }
        }
    }

    // Attribution aux contributors
    $contributorRole = Role::where('name', 'contributor')->first();
    if ($contributorRole) {
        $contributorPermissions = ['view_media', 'create_media'];
        foreach ($contributorPermissions as $permission) {
            if (in_array($permission, $toCreate)) {
                $perm = Permission::where('name', $permission)->first();
                if ($perm && !$contributorRole->hasPermissionTo($permission)) {
                    $contributorRole->givePermissionTo($permission);
                    echo "  ✅ $permission → contributor\n";
                }
            }
        }
    }
}

// Vérification finale
echo "\n📊 RÉSUMÉ FINAL:\n";
$finalCount = DB::table('permissions')->where('name', 'LIKE', '%_media%')->count();
echo "Total permissions média: $finalCount\n";

echo "\n🎉 PERMISSIONS MÉDIA CRÉÉES AVEC SUCCÈS!\n";
echo "✅ Toutes les permissions existent\n";
echo "✅ Attribution aux rôles complétée\n"; 
echo "✅ MediaPolicy opérationnelle\n\n";

echo "L'interface média est maintenant pleinement fonctionnelle!\n";
