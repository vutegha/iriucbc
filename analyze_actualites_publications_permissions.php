<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ANALYSE DES PERMISSIONS ACTUALITES & PUBLICATIONS ===\n";
echo "Date: " . now()->format('d/m/Y H:i:s') . "\n\n";

// 1. Permissions pour les actualités
echo "📰 PERMISSIONS POUR ACTUALITES\n";
echo str_repeat("=", 50) . "\n";

$actualitePerms = DB::table('permissions')
    ->where('name', 'LIKE', '%actualite%')
    ->orderBy('name')
    ->get();

echo "Permissions trouvées (" . $actualitePerms->count() . "):\n";
foreach ($actualitePerms as $perm) {
    echo "  • {$perm->name} (guard: {$perm->guard_name})\n";
}
echo "\n";

// 2. Permissions pour les publications
echo "📚 PERMISSIONS POUR PUBLICATIONS\n";
echo str_repeat("=", 50) . "\n";

$publicationPerms = DB::table('permissions')
    ->where('name', 'LIKE', '%publication%')
    ->orderBy('name')
    ->get();

echo "Permissions trouvées (" . $publicationPerms->count() . "):\n";
foreach ($publicationPerms as $perm) {
    echo "  • {$perm->name} (guard: {$perm->guard_name})\n";
}
echo "\n";

// 3. Rôles avec permissions actualités
echo "👥 RÔLES AVEC PERMISSIONS ACTUALITES\n";
echo str_repeat("=", 50) . "\n";

$rolesActualites = DB::table('roles')
    ->join('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
    ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
    ->where('permissions.name', 'LIKE', '%actualite%')
    ->select('roles.name as role_name', 'permissions.name as permission_name')
    ->orderBy('roles.name')
    ->orderBy('permissions.name')
    ->get();

if ($rolesActualites->count() > 0) {
    $groupedActualites = $rolesActualites->groupBy('role_name');
    foreach ($groupedActualites as $roleName => $permissions) {
        echo "🏷️ Rôle '$roleName':\n";
        foreach ($permissions as $perm) {
            echo "   • {$perm->permission_name}\n";
        }
        echo "\n";
    }
} else {
    echo "⚠️ Aucun rôle n'a de permissions actualité assignées!\n\n";
}

// 4. Rôles avec permissions publications
echo "👥 RÔLES AVEC PERMISSIONS PUBLICATIONS\n";
echo str_repeat("=", 50) . "\n";

$rolesPublications = DB::table('roles')
    ->join('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
    ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
    ->where('permissions.name', 'LIKE', '%publication%')
    ->select('roles.name as role_name', 'permissions.name as permission_name')
    ->orderBy('roles.name')
    ->orderBy('permissions.name')
    ->get();

if ($rolesPublications->count() > 0) {
    $groupedPublications = $rolesPublications->groupBy('role_name');
    foreach ($groupedPublications as $roleName => $permissions) {
        echo "🏷️ Rôle '$roleName':\n";
        foreach ($permissions as $perm) {
            echo "   • {$perm->permission_name}\n";
        }
        echo "\n";
    }
} else {
    echo "⚠️ Aucun rôle n'a de permissions publication assignées!\n\n";
}

// 5. Comparaison des structures de permissions
echo "🔍 COMPARAISON DES STRUCTURES\n";
echo str_repeat("=", 50) . "\n";

echo "📊 RÉSUMÉ:\n";
echo "  Actualités: {$actualitePerms->count()} permissions\n";
echo "  Publications: {$publicationPerms->count()} permissions\n\n";

echo "📋 ACTIONS DISPONIBLES:\n";

// Extraire les actions des actualités
$actualiteActions = [];
foreach ($actualitePerms as $perm) {
    if (strpos($perm->name, '_') !== false) {
        $parts = explode('_', $perm->name);
        if (count($parts) >= 2) {
            $action = $parts[0];
            if (!in_array($action, $actualiteActions)) {
                $actualiteActions[] = $action;
            }
        }
    }
}

// Extraire les actions des publications
$publicationActions = [];
foreach ($publicationPerms as $perm) {
    if (strpos($perm->name, '_') !== false) {
        $parts = explode('_', $perm->name);
        if (count($parts) >= 2) {
            $action = $parts[0];
            if (!in_array($action, $publicationActions)) {
                $publicationActions[] = $action;
            }
        }
    }
}

echo "  Actualités: " . implode(', ', $actualiteActions) . "\n";
echo "  Publications: " . implode(', ', $publicationActions) . "\n\n";

// Vérifier la cohérence
$commonActions = array_intersect($actualiteActions, $publicationActions);
$onlyActualites = array_diff($actualiteActions, $publicationActions);
$onlyPublications = array_diff($publicationActions, $actualiteActions);

echo "🎯 COHÉRENCE DES ACTIONS:\n";
echo "  Actions communes: " . implode(', ', $commonActions) . "\n";
if (!empty($onlyActualites)) {
    echo "  Uniquement actualités: " . implode(', ', $onlyActualites) . "\n";
}
if (!empty($onlyPublications)) {
    echo "  Uniquement publications: " . implode(', ', $onlyPublications) . "\n";
}

echo "\n" . str_repeat("=", 55) . "\n";
