<?php

/**
 * Script pour créer les permissions relatives aux partenaires
 * 
 * À exécuter via : php create_partenaire_permissions.php
 * Ou directement dans une seeder
 */

require_once 'vendor/autoload.php';

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Configuration de l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Créer les permissions pour les partenaires
    $permissions = [
        'view_partenaires',
        'create_partenaires', 
        'update_partenaires',
        'delete_partenaires',
        'moderate_partenaires'
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
        echo "✓ Permission créée : {$permission}\n";
    }

    // Assigner les permissions aux rôles
    $rolePermissions = [
        'super-admin' => ['view_partenaires', 'create_partenaires', 'update_partenaires', 'delete_partenaires', 'moderate_partenaires'],
        'admin' => ['view_partenaires', 'create_partenaires', 'update_partenaires', 'delete_partenaires', 'moderate_partenaires'],
        'moderator' => ['view_partenaires', 'create_partenaires', 'update_partenaires', 'moderate_partenaires'],
        'editor' => ['view_partenaires', 'create_partenaires', 'update_partenaires'],
        'contributor' => ['view_partenaires']
    ];

    foreach ($rolePermissions as $roleName => $rolePermissionsList) {
        $role = Role::where('name', $roleName)->first();
        
        if ($role) {
            foreach ($rolePermissionsList as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission && !$role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                    echo "✓ Permission '{$permissionName}' assignée au rôle '{$roleName}'\n";
                }
            }
        } else {
            echo "⚠ Rôle '{$roleName}' non trouvé\n";
        }
    }

    echo "\n✅ Toutes les permissions pour les partenaires ont été créées et assignées avec succès !\n";
    
    // Afficher un résumé
    echo "\n📊 Résumé des permissions :\n";
    foreach ($permissions as $permission) {
        $perm = Permission::where('name', $permission)->first();
        if ($perm) {
            $roles = $perm->roles->pluck('name')->toArray();
            echo "- {$permission} : " . implode(', ', $roles) . "\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
