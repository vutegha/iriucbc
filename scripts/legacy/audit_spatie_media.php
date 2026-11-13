<?php
// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== AUDIT SPATIE POUR LE MODULE MÉDIAS ===\n";
echo "Date: " . now()->format('d/m/Y H:i:s') . "\n\n";

// 1. Vérifier la configuration Spatie
echo "🔍 1. CONFIGURATION SPATIE\n";
echo str_repeat("=", 50) . "\n";

// Vérifier si les tables Spatie existent
$spatieTablesExist = [
    'roles' => DB::getSchemaBuilder()->hasTable('roles'),
    'permissions' => DB::getSchemaBuilder()->hasTable('permissions'),
    'model_has_permissions' => DB::getSchemaBuilder()->hasTable('model_has_permissions'),
    'model_has_roles' => DB::getSchemaBuilder()->hasTable('model_has_roles'),
    'role_has_permissions' => DB::getSchemaBuilder()->hasTable('role_has_permissions'),
];

echo "📋 Tables Spatie:\n";
foreach ($spatieTablesExist as $table => $exists) {
    echo "  " . ($exists ? "✅" : "❌") . " $table\n";
}
echo "\n";

// 2. Analyser les permissions pour les médias
echo "🎯 2. PERMISSIONS MÉDIA DANS LA DB\n";
echo str_repeat("=", 50) . "\n";

if ($spatieTablesExist['permissions']) {
    $mediaPermissions = DB::table('permissions')
        ->where('name', 'LIKE', '%media%')
        ->orWhere('name', 'LIKE', '%médias%')
        ->get();
    
    echo "Permissions média trouvées (" . $mediaPermissions->count() . "):\n";
    if ($mediaPermissions->count() > 0) {
        foreach ($mediaPermissions as $perm) {
            echo "  • {$perm->name} (guard: {$perm->guard_name})\n";
        }
    } else {
        echo "  ❌ Aucune permission média trouvée!\n";
    }
    echo "\n";
    
    // Permissions nécessaires selon MediaPolicy
    $requiredPermissions = [
        'media.view',
        'media.create',
        'media.edit',
        'media.delete',
        'media.moderate',
        'media.publish',
        'media.download'
    ];
    
    echo "🔍 Vérification des permissions requises:\n";
    foreach ($requiredPermissions as $requiredPerm) {
        $exists = $mediaPermissions->where('name', $requiredPerm)->count() > 0;
        echo "  " . ($exists ? "✅" : "❌") . " $requiredPerm\n";
    }
    echo "\n";
}

// 3. Analyser les rôles qui ont des permissions média
echo "👥 3. RÔLES AVEC PERMISSIONS MÉDIA\n";
echo str_repeat("=", 50) . "\n";

if ($spatieTablesExist['role_has_permissions'] && $spatieTablesExist['roles']) {
    $rolesWithMediaPerms = DB::table('roles')
        ->join('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
        ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
        ->where('permissions.name', 'LIKE', '%media%')
        ->select('roles.name as role_name', 'permissions.name as permission_name')
        ->get();
    
    if ($rolesWithMediaPerms->count() > 0) {
        $groupedByRole = $rolesWithMediaPerms->groupBy('role_name');
        foreach ($groupedByRole as $roleName => $permissions) {
            echo "🏷️ Rôle '$roleName':\n";
            foreach ($permissions as $perm) {
                echo "   • {$perm->permission_name}\n";
            }
            echo "\n";
        }
    } else {
        echo "⚠️ Aucun rôle n'a de permissions média assignées!\n\n";
    }
}

// 4. Vérifier les rôles existants
echo "📋 4. RÔLES EXISTANTS\n";
echo str_repeat("=", 50) . "\n";

if ($spatieTablesExist['roles']) {
    $roles = DB::table('roles')->get();
    echo "Rôles dans la base (" . $roles->count() . "):\n";
    foreach ($roles as $role) {
        echo "  • {$role->name} (guard: {$role->guard_name})\n";
    }
    echo "\n";
    
    // Rôles requis par MediaPolicy
    $requiredRoles = ['super-admin', 'admin', 'moderator', 'editor'];
    echo "🔍 Vérification des rôles requis:\n";
    foreach ($requiredRoles as $requiredRole) {
        $exists = $roles->where('name', $requiredRole)->count() > 0;
        echo "  " . ($exists ? "✅" : "❌") . " $requiredRole\n";
    }
    echo "\n";
}

// 5. Vérifier les utilisateurs avec des rôles
echo "👤 5. UTILISATEURS AVEC RÔLES\n";
echo str_repeat("=", 50) . "\n";

if ($spatieTablesExist['model_has_roles']) {
    $usersWithRoles = DB::table('users')
        ->join('model_has_roles', function($join) {
            $join->on('users.id', '=', 'model_has_roles.model_id')
                 ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
        })
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->select('users.name as user_name', 'users.email', 'roles.name as role_name')
        ->get();
    
    if ($usersWithRoles->count() > 0) {
        echo "Utilisateurs avec rôles (" . $usersWithRoles->count() . "):\n";
        foreach ($usersWithRoles as $userRole) {
            echo "  • {$userRole->user_name} ({$userRole->email}) → {$userRole->role_name}\n";
        }
    } else {
        echo "⚠️ Aucun utilisateur n'a de rôle assigné via Spatie!\n";
    }
    echo "\n";
}

// 6. Vérifier la table media
echo "🗃️ 6. TABLE MEDIA\n";
echo str_repeat("=", 50) . "\n";

$mediaTableExists = DB::getSchemaBuilder()->hasTable('media');
echo "Table 'media' existe: " . ($mediaTableExists ? "✅" : "❌") . "\n";

if ($mediaTableExists) {
    $mediaColumns = DB::getSchemaBuilder()->getColumnListing('media');
    echo "Colonnes dans la table media:\n";
    foreach ($mediaColumns as $column) {
        echo "  • $column\n";
    }
    echo "\n";
    
    // Vérifier les colonnes de modération
    $moderationColumns = ['status', 'is_public', 'moderated_by', 'moderated_at', 'created_by'];
    echo "🔍 Colonnes de modération:\n";
    foreach ($moderationColumns as $column) {
        $exists = in_array($column, $mediaColumns);
        echo "  " . ($exists ? "✅" : "❌") . " $column\n";
    }
    echo "\n";
    
    // Compter les médias
    $mediaCount = DB::table('media')->count();
    echo "Nombre de médias en base: $mediaCount\n";
    
    if ($mediaCount > 0) {
        $statusCounts = DB::table('media')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        echo "Répartition par statut:\n";
        foreach ($statusCounts as $status) {
            echo "  • " . ($status->status ?? 'NULL') . ": {$status->count}\n";
        }
    }
    echo "\n";
}

// 7. Diagnostic des problèmes
echo "🚨 7. DIAGNOSTIC\n";
echo str_repeat("=", 50) . "\n";

$problems = [];
$solutions = [];

// Vérifier permissions
if (!$spatieTablesExist['permissions']) {
    $problems[] = "❌ Tables Spatie manquantes";
    $solutions[] = "➤ Publier les migrations Spatie: php artisan vendor:publish --provider=\"Spatie\\Permission\\PermissionServiceProvider\"";
} else {
    $mediaPermissionsCount = DB::table('permissions')->where('name', 'LIKE', '%media%')->count();
    if ($mediaPermissionsCount == 0) {
        $problems[] = "❌ Aucune permission média n'existe";
        $solutions[] = "➤ Exécuter le seeder: php artisan db:seed --class=MediaPermissionsSeeder";
    }
}

// Vérifier rôles
if ($spatieTablesExist['roles']) {
    $adminRole = DB::table('roles')->where('name', 'admin')->first();
    if (!$adminRole) {
        $problems[] = "❌ Rôle 'admin' manquant";
        $solutions[] = "➤ Créer les rôles de base avec un seeder";
    }
}

// Vérifier table media
if (!$mediaTableExists) {
    $problems[] = "❌ Table 'media' n'existe pas";
    $solutions[] = "➤ Exécuter les migrations: php artisan migrate";
} else {
    $mediaColumns = DB::getSchemaBuilder()->getColumnListing('media');
    if (!in_array('status', $mediaColumns)) {
        $problems[] = "❌ Colonnes de modération manquantes dans table media";
        $solutions[] = "➤ Exécuter la migration de modération: php artisan migrate";
    }
}

if (count($problems) > 0) {
    echo "PROBLÈMES DÉTECTÉS:\n";
    foreach ($problems as $problem) {
        echo "$problem\n";
    }
    echo "\n";
    
    echo "SOLUTIONS RECOMMANDÉES:\n";
    foreach ($solutions as $solution) {
        echo "$solution\n";
    }
} else {
    echo "✅ Aucun problème détecté! Le système de permissions média semble correctement configuré.\n";
}

echo "\n=== FIN DE L'AUDIT SPATIE MÉDIAS ===\n";

?>
