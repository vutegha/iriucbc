<?php
// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== CORRECTION DU SYSTÈME DE PERMISSIONS ===\n";
echo "Date: " . now()->format('d/m/Y H:i:s') . "\n\n";

try {
    // 1. Créer les tables manquantes si elles n'existent pas
    echo "🔧 1. CRÉATION DES TABLES DE LIAISON\n";
    
    if (!Schema::hasTable('role_user')) {
        echo "Création de la table role_user...\n";
        Schema::create('role_user', function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
            $table->unique(['user_id', 'role_id']);
            $table->index('user_id');
            $table->index('role_id');
        });
        echo "✅ Table role_user créée\n";
    } else {
        echo "✅ Table role_user existe déjà\n";
    }
    
    if (!Schema::hasTable('permission_role')) {
        echo "Création de la table permission_role...\n";
        Schema::create('permission_role', function ($table) {
            $table->id();
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
            $table->unique(['permission_id', 'role_id']);
            $table->index('permission_id');
            $table->index('role_id');
        });
        echo "✅ Table permission_role créée\n";
    } else {
        echo "✅ Table permission_role existe déjà\n";
    }
    
    echo "\n";
    
    // 2. Nettoyer les rôles en doublon
    echo "🧹 2. NETTOYAGE DES RÔLES\n";
    
    // Supprimer le rôle en doublon "super admin" (garder "super-admin")
    $duplicateRole = DB::table('roles')->where('name', 'super admin')->first();
    if ($duplicateRole) {
        DB::table('roles')->where('id', $duplicateRole->id)->delete();
        echo "✅ Rôle en doublon 'super admin' supprimé\n";
    }
    
    // Vérifier les rôles finaux
    $roles = DB::table('roles')->pluck('name', 'id');
    echo "Rôles disponibles:\n";
    foreach ($roles as $id => $name) {
        echo "  • $name (ID: $id)\n";
    }
    echo "\n";
    
    // 3. Assigner des rôles aux utilisateurs
    echo "👥 3. ASSIGNATION DES RÔLES AUX UTILISATEURS\n";
    
    $users = DB::table('users')->get();
    $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
    $superAdminRoleId = DB::table('roles')->where('name', 'super-admin')->value('id');
    $userRoleId = DB::table('roles')->where('name', 'user')->value('id');
    
    foreach ($users as $user) {
        // Vérifier si l'utilisateur a déjà un rôle
        $hasRole = DB::table('role_user')->where('user_id', $user->id)->exists();
        
        if (!$hasRole) {
            $roleId = $userRoleId; // Par défaut: utilisateur normal
            
            // Assigner super-admin au premier utilisateur
            if ($user->id == 1) {
                $roleId = $superAdminRoleId ?: $adminRoleId;
            }
            // Assigner admin aux utilisateurs 2-5
            elseif ($user->id >= 2 && $user->id <= 5) {
                $roleId = $adminRoleId;
            }
            
            if ($roleId) {
                DB::table('role_user')->insert([
                    'user_id' => $user->id,
                    'role_id' => $roleId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $roleName = DB::table('roles')->where('id', $roleId)->value('name');
                echo "✅ {$user->name} → Rôle '$roleName'\n";
            }
        } else {
            echo "⚪ {$user->name} → Rôle déjà assigné\n";
        }
    }
    
    echo "\n";
    
    // 4. Assigner des permissions de base aux rôles
    echo "🔐 4. ASSIGNATION DES PERMISSIONS AUX RÔLES\n";
    
    // Permissions essentielles par rôle
    $rolePermissions = [
        'super-admin' => [
            'access admin', 'manage system', 'manage users', 'view logs',
            'manage projets', 'moderate projets', 'publish projets',
            'manage actualites', 'moderate actualites', 'publish actualites'
        ],
        'admin' => [
            'access admin', 'manage users', 'manage projets', 'moderate projets',
            'manage actualites', 'moderate actualites', 'manage media'
        ],
        'moderator' => [
            'access admin', 'moderate projets', 'moderate actualites',
            'view projets', 'view actualites'
        ],
        'editor' => [
            'access admin', 'create projets', 'edit projets', 'view projets',
            'create actualites', 'edit actualites', 'view actualites'
        ],
        'user' => [
            'view projets', 'view actualites'
        ]
    ];
    
    foreach ($rolePermissions as $roleName => $permissionNames) {
        $role = DB::table('roles')->where('name', $roleName)->first();
        if (!$role) continue;
        
        echo "Rôle '$roleName':\n";
        
        foreach ($permissionNames as $permissionName) {
            $permission = DB::table('permissions')->where('name', $permissionName)->first();
            if (!$permission) continue;
            
            // Vérifier si la permission est déjà assignée
            $exists = DB::table('permission_role')
                ->where('permission_id', $permission->id)
                ->where('role_id', $role->id)
                ->exists();
            
            if (!$exists) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permission->id,
                    'role_id' => $role->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                echo "  ✅ $permissionName\n";
            } else {
                echo "  ⚪ $permissionName (déjà assignée)\n";
            }
        }
        echo "\n";
    }
    
    // 5. Vérification finale
    echo "🔍 5. VÉRIFICATION FINALE\n";
    
    $usersWithRoles = DB::table('users')
        ->join('role_user', 'users.id', '=', 'role_user.user_id')
        ->join('roles', 'role_user.role_id', '=', 'roles.id')
        ->select('users.name', 'roles.name as role_name')
        ->get();
    
    echo "Utilisateurs avec rôles:\n";
    foreach ($usersWithRoles as $userRole) {
        echo "  • {$userRole->name} → {$userRole->role_name}\n";
    }
    
    echo "\n";
    
    $rolesWithPermissions = DB::table('roles')
        ->join('permission_role', 'roles.id', '=', 'permission_role.role_id')
        ->select('roles.name', DB::raw('COUNT(permission_role.permission_id) as permission_count'))
        ->groupBy('roles.id', 'roles.name')
        ->get();
    
    echo "Rôles avec permissions:\n";
    foreach ($rolesWithPermissions as $rolePerms) {
        echo "  • {$rolePerms->name} → {$rolePerms->permission_count} permission(s)\n";
    }
    
    echo "\n✅ SYSTÈME DE PERMISSIONS CONFIGURÉ AVEC SUCCÈS!\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== CONFIGURATION TERMINÉE ===\n";
?>
