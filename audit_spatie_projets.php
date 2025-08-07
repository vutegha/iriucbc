<?php
// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== AUDIT SPATIE POUR LE MODULE PROJETS ===\n";
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

// 2. Analyser les permissions pour les projets
echo "🎯 2. PERMISSIONS PROJET DANS LA DB\n";
echo str_repeat("=", 50) . "\n";

if ($spatieTablesExist['permissions']) {
    $projetPermissions = DB::table('permissions')
        ->where('name', 'LIKE', '%projet%')
        ->orWhere('name', 'LIKE', '%project%')
        ->get();
    
    echo "Permissions projet trouvées (" . $projetPermissions->count() . "):\n";
    foreach ($projetPermissions as $perm) {
        echo "  • {$perm->name} (guard: {$perm->guard_name})\n";
    }
    echo "\n";
}

// 3. Analyser les rôles qui ont des permissions projet
echo "👥 3. RÔLES AVEC PERMISSIONS PROJET\n";
echo str_repeat("=", 50) . "\n";

if ($spatieTablesExist['role_has_permissions'] && $spatieTablesExist['roles']) {
    $rolesWithProjetPerms = DB::table('roles')
        ->join('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
        ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
        ->where('permissions.name', 'LIKE', '%projet%')
        ->select('roles.name as role_name', 'permissions.name as permission_name')
        ->get();
    
    if ($rolesWithProjetPerms->count() > 0) {
        $groupedByRole = $rolesWithProjetPerms->groupBy('role_name');
        foreach ($groupedByRole as $roleName => $permissions) {
            echo "🏷️ Rôle '$roleName':\n";
            foreach ($permissions as $perm) {
                echo "   • {$perm->permission_name}\n";
            }
            echo "\n";
        }
    } else {
        echo "⚠️ Aucun rôle n'a de permissions projet assignées!\n\n";
    }
}

// 4. Problèmes détectés dans ProjetPolicy
echo "🚨 4. PROBLÈMES DANS PROJETPOLICY\n";
echo str_repeat("=", 50) . "\n";

$policyProblems = [
    "❌ Incohérence des noms de permissions:",
    "   • Policy utilise: 'view_projets', 'create_projet'",
    "   • DB contient: 'view projets', 'create projets'",
    "",
    "❌ Méthode hasRole() mal utilisée:",
    "   • hasRole(['admin']) au lieu de hasAnyRole(['admin'])",
    "   • Mélange entre 'gestionnaire_projets' et 'gestionnaire projets'",
    "",
    "❌ Guard non spécifié:",
    "   • Les permissions peuvent être sur différents guards",
    "",
    "❌ Pas de fallback pour super-admin:",
    "   • Super-admin devrait avoir tous les droits automatiquement"
];

foreach ($policyProblems as $problem) {
    echo "$problem\n";
}
echo "\n";

// 5. Recommandations de correction
echo "🛠️ 5. CORRECTIONS RECOMMANDÉES\n";
echo str_repeat("=", 50) . "\n";

echo "📝 A. UNIFORMISER LES NOMS DE PERMISSIONS:\n";
echo "   • Utiliser soit 'view_projets' soit 'view projets' partout\n";
echo "   • Recommandé: format snake_case 'view_projets'\n\n";

echo "📝 B. CORRIGER ProjetPolicy.php:\n";
echo "   • Remplacer hasRole(['admin']) par hasAnyRole(['admin'])\n";
echo "   • Ajouter guard spécifique: hasPermissionTo('permission', 'web')\n";
echo "   • Ajouter vérification super-admin systématique\n\n";

echo "📝 C. AJOUTER MIDDLEWARE DANS ProjetController:\n";
echo "   • \$this->middleware('auth')\n";
echo "   • Vérifications authorize() manquantes\n\n";

echo "📝 D. CRÉER SEEDER POUR PERMISSIONS PROJET:\n";
echo "   • Permissions standardisées\n";
echo "   • Attribution aux rôles appropriés\n\n";

// 6. Vérifier les utilisateurs avec des rôles
echo "👤 6. UTILISATEURS AVEC RÔLES PROJET\n";
echo str_repeat("=", 50) . "\n";

if ($spatieTablesExist['model_has_roles']) {
    $usersWithRoles = DB::table('users')
        ->join('model_has_roles', function($join) {
            $join->on('users.id', '=', 'model_has_roles.model_id')
                 ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
        })
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->select('users.name as user_name', 'roles.name as role_name')
        ->get();
    
    if ($usersWithRoles->count() > 0) {
        echo "Utilisateurs avec rôles:\n";
        foreach ($usersWithRoles as $userRole) {
            echo "  • {$userRole->user_name} → {$userRole->role_name}\n";
        }
    } else {
        echo "⚠️ Aucun utilisateur n'a de rôle assigné via Spatie!\n";
    }
    echo "\n";
}

// 7. Code de correction suggéré
echo "💻 7. CODE DE CORRECTION\n";
echo str_repeat("=", 50) . "\n";

echo "Exemple de ProjetPolicy corrigée:\n\n";
echo "```php\n";
echo "public function viewAny(User \$user)\n";
echo "{\n";
echo "    // Super-admin a tous les droits\n";
echo "    if (\$user->hasRole('super-admin', 'web')) {\n";
echo "        return true;\n";
echo "    }\n";
echo "    \n";
echo "    return \$user->hasPermissionTo('view_projets', 'web') ||\n";
echo "           \$user->hasAnyRole(['admin', 'gestionnaire_projets'], 'web');\n";
echo "}\n";
echo "```\n\n";

echo "=== FIN DE L'AUDIT SPATIE PROJETS ===\n";

?>
