<?php
// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

echo "=== AUDIT COMPLET DES PERMISSIONS - IRI UCBC ===\n";
echo "Date: " . now()->format('d/m/Y H:i:s') . "\n\n";

// 1. ANALYSE DE LA STRUCTURE DES PERMISSIONS
echo "🔍 1. STRUCTURE DES PERMISSIONS\n";
echo str_repeat("=", 50) . "\n";

try {
    // Vérifier les tables de permissions
    $tablesExist = [
        'users' => DB::getSchemaBuilder()->hasTable('users'),
        'roles' => DB::getSchemaBuilder()->hasTable('roles'),
        'permissions' => DB::getSchemaBuilder()->hasTable('permissions'),
        'role_user' => DB::getSchemaBuilder()->hasTable('role_user'),
        'permission_role' => DB::getSchemaBuilder()->hasTable('permission_role'),
    ];

    echo "📋 Tables de permissions:\n";
    foreach ($tablesExist as $table => $exists) {
        echo "  " . ($exists ? "✅" : "❌") . " $table\n";
    }
    echo "\n";

    if (!$tablesExist['users']) {
        echo "❌ ERREUR CRITIQUE: Table 'users' manquante!\n";
        exit;
    }

    // 2. ANALYSE DES UTILISATEURS
    echo "👥 2. ANALYSE DES UTILISATEURS\n";
    echo str_repeat("=", 50) . "\n";

    $users = DB::table('users')->get();
    echo "Total utilisateurs: " . $users->count() . "\n\n";

    echo "📊 Répartition par rôle:\n";
    if ($tablesExist['roles'] && $tablesExist['role_user']) {
        $usersByRole = DB::table('users')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->select('roles.name as role_name', DB::raw('COUNT(users.id) as user_count'))
            ->groupBy('roles.name')
            ->get();

        foreach ($usersByRole as $role) {
            echo "  • " . ($role->role_name ?? 'Sans rôle') . ": " . $role->user_count . " utilisateur(s)\n";
        }

        // Utilisateurs sans rôle
        $usersWithoutRole = DB::table('users')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->whereNull('role_user.user_id')
            ->count();
        
        if ($usersWithoutRole > 0) {
            echo "  ⚠️  Utilisateurs sans rôle: $usersWithoutRole\n";
        }
    } else {
        echo "  ⚠️  Système de rôles non configuré\n";
    }

    echo "\n";

    // 3. ANALYSE DES RÔLES
    if ($tablesExist['roles']) {
        echo "🏷️ 3. ANALYSE DES RÔLES\n";
        echo str_repeat("=", 50) . "\n";

        $roles = DB::table('roles')->get();
        echo "Total rôles: " . $roles->count() . "\n\n";

        foreach ($roles as $role) {
            echo "📋 Rôle: {$role->name}\n";
            echo "   ID: {$role->id}\n";
            if (isset($role->description)) {
                echo "   Description: {$role->description}\n";
            }
            
            // Permissions du rôle
            if ($tablesExist['permission_role'] && $tablesExist['permissions']) {
                $rolePermissions = DB::table('permissions')
                    ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
                    ->where('permission_role.role_id', $role->id)
                    ->pluck('permissions.name');

                echo "   Permissions (" . $rolePermissions->count() . "):\n";
                foreach ($rolePermissions as $permission) {
                    echo "     • $permission\n";
                }
            }
            echo "\n";
        }
    }

    // 4. ANALYSE DES PERMISSIONS
    if ($tablesExist['permissions']) {
        echo "🔐 4. ANALYSE DES PERMISSIONS\n";
        echo str_repeat("=", 50) . "\n";

        $permissions = DB::table('permissions')->get();
        echo "Total permissions: " . $permissions->count() . "\n\n";

        // Grouper par type/module
        $permissionsByModule = [];
        foreach ($permissions as $permission) {
            $module = explode('-', $permission->name)[0] ?? 'autres';
            $permissionsByModule[$module][] = $permission;
        }

        foreach ($permissionsByModule as $module => $modulePermissions) {
            echo "📦 Module: " . strtoupper($module) . " (" . count($modulePermissions) . " permissions)\n";
            foreach ($modulePermissions as $perm) {
                echo "   • {$perm->name}\n";
            }
            echo "\n";
        }
    }

    // 5. ANALYSE DES GATES ET POLICIES
    echo "🚪 5. ANALYSE DES GATES ET POLICIES\n";
    echo str_repeat("=", 50) . "\n";

    // Rechercher les Gates dans le code
    $gateFiles = [
        'app/Providers/AuthServiceProvider.php',
        'app/Http/Controllers/Admin/ProjetController.php'
    ];

    foreach ($gateFiles as $file) {
        if (file_exists($file)) {
            $content = file_get_contents($file);
            preg_match_all('/Gate::define\([\'"]([^\'"]+)[\'"]/', $content, $matches);
            if (!empty($matches[1])) {
                echo "📄 $file:\n";
                foreach ($matches[1] as $gate) {
                    echo "   • Gate: $gate\n";
                }
                echo "\n";
            }
        }
    }

    // Rechercher les Policies
    $policyDir = 'app/Policies';
    if (is_dir($policyDir)) {
        $policies = glob($policyDir . '/*.php');
        echo "📋 Policies trouvées:\n";
        foreach ($policies as $policy) {
            echo "   • " . basename($policy) . "\n";
        }
        echo "\n";
    }

    // 6. VÉRIFICATION DES MIDDLEWARES
    echo "🔒 6. ANALYSE DES MIDDLEWARES DE SÉCURITÉ\n";
    echo str_repeat("=", 50) . "\n";

    $middlewareFiles = [
        'app/Http/Kernel.php',
        'app/Http/Middleware'
    ];

    foreach ($middlewareFiles as $file) {
        if (file_exists($file)) {
            if (is_file($file)) {
                $content = file_get_contents($file);
                echo "📄 " . basename($file) . ":\n";
                
                // Rechercher les middlewares d'auth
                if (strpos($content, 'auth') !== false) {
                    echo "   ✅ Middleware auth détecté\n";
                }
                if (strpos($content, 'can:') !== false) {
                    echo "   ✅ Middleware de permissions détecté\n";
                }
                if (strpos($content, 'role:') !== false) {
                    echo "   ✅ Middleware de rôles détecté\n";
                }
                echo "\n";
            }
        }
    }

    // 7. PROBLÈMES POTENTIELS
    echo "⚠️ 7. PROBLÈMES POTENTIELS DÉTECTÉS\n";
    echo str_repeat("=", 50) . "\n";

    $issues = [];

    // Utilisateurs sans email vérifié
    $unverifiedUsers = DB::table('users')->whereNull('email_verified_at')->count();
    if ($unverifiedUsers > 0) {
        $issues[] = "🔴 $unverifiedUsers utilisateur(s) avec email non vérifié";
    }

    // Utilisateurs sans rôle
    if ($tablesExist['role_user'] && $usersWithoutRole > 0) {
        $issues[] = "🔴 $usersWithoutRole utilisateur(s) sans rôle assigné";
    }

    // Permissions orphelines
    if ($tablesExist['permissions'] && $tablesExist['permission_role']) {
        $orphanPermissions = DB::table('permissions')
            ->leftJoin('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
            ->whereNull('permission_role.permission_id')
            ->count();
        
        if ($orphanPermissions > 0) {
            $issues[] = "🟡 $orphanPermissions permission(s) non assignée(s) à un rôle";
        }
    }

    // Rôles sans permissions
    if ($tablesExist['roles'] && $tablesExist['permission_role']) {
        $rolesWithoutPermissions = DB::table('roles')
            ->leftJoin('permission_role', 'roles.id', '=', 'permission_role.role_id')
            ->whereNull('permission_role.role_id')
            ->count();
        
        if ($rolesWithoutPermissions > 0) {
            $issues[] = "🟡 $rolesWithoutPermissions rôle(s) sans permissions";
        }
    }

    if (empty($issues)) {
        echo "✅ Aucun problème majeur détecté\n";
    } else {
        foreach ($issues as $issue) {
            echo "$issue\n";
        }
    }

    echo "\n";

    // 8. RECOMMANDATIONS
    echo "💡 8. RECOMMANDATIONS\n";
    echo str_repeat("=", 50) . "\n";
    
    echo "🔧 Actions recommandées:\n";
    echo "1. Vérifier que tous les utilisateurs actifs ont un rôle\n";
    echo "2. Revoir les permissions non assignées\n";
    echo "3. Implémenter la vérification d'email si nécessaire\n";
    echo "4. Documenter les rôles et permissions\n";
    echo "5. Mettre en place des tests automatisés de permissions\n";
    echo "6. Auditer régulièrement les accès\n\n";

    echo "📋 Structure recommandée:\n";
    echo "• Super Admin: toutes permissions\n";
    echo "• Admin: gestion projets + utilisateurs\n";
    echo "• Modérateur: modération contenu\n";
    echo "• Editeur: création/édition contenu\n";
    echo "• Utilisateur: lecture seule\n\n";

} catch (Exception $e) {
    echo "❌ ERREUR lors de l'audit: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "=== FIN DE L'AUDIT ===\n";
echo "Rapport généré le: " . now()->format('d/m/Y à H:i:s') . "\n";
?>
