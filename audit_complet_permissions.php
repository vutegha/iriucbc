<?php
// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

echo "=== AUDIT COMPLET DES PERMISSIONS - VUES vs POLICIES vs DB ===\n";
echo "Date: " . now()->format('d/m/Y H:i:s') . "\n\n";

// 1. Analyser toutes les permissions dans la DB
echo "🗄️ 1. PERMISSIONS DANS LA BASE DE DONNÉES\n";
echo str_repeat("=", 60) . "\n";

$allPermissions = DB::table('permissions')->orderBy('name')->get();
$permissionsByModel = [];

foreach ($allPermissions as $perm) {
    $parts = explode('_', $perm->name);
    if (count($parts) >= 2) {
        $action = $parts[0];
        $model = implode('_', array_slice($parts, 1));
        $permissionsByModel[$model][] = $perm->name;
    }
}

echo "📊 Permissions par modèle (" . count($permissionsByModel) . " modèles):\n";
foreach ($permissionsByModel as $model => $permissions) {
    echo "🏷️ $model (" . count($permissions) . "):\n";
    foreach ($permissions as $perm) {
        echo "   • $perm\n";
    }
    echo "\n";
}

// 2. Scanner les vues pour trouver les @can
echo "👁️ 2. DIRECTIVES @can DANS LES VUES\n";
echo str_repeat("=", 60) . "\n";

$viewsPath = resource_path('views');
$canDirectives = [];

function scanForCanDirectives($directory, &$canDirectives) {
    $files = File::allFiles($directory);
    
    foreach ($files as $file) {
        if ($file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            $relativePath = str_replace(resource_path('views/'), '', $file->getPathname());
            
            // Chercher @can, @cannot, @canany
            preg_match_all('/@(can|cannot|canany)\s*\(\s*[\'\"](.*?)[\'\"]/', $content, $matches);
            
            if (!empty($matches[2])) {
                foreach ($matches[2] as $index => $permission) {
                    $directive = $matches[1][$index];
                    $canDirectives[$relativePath][] = [
                        'directive' => $directive,
                        'permission' => $permission
                    ];
                }
            }
        }
    }
}

if (File::exists($viewsPath)) {
    scanForCanDirectives($viewsPath, $canDirectives);
    
    echo "📁 Directives trouvées dans les vues:\n";
    foreach ($canDirectives as $viewFile => $directives) {
        echo "📄 $viewFile:\n";
        foreach ($directives as $dir) {
            echo "   @{$dir['directive']}('{$dir['permission']}')\n";
        }
        echo "\n";
    }
} else {
    echo "❌ Dossier views non trouvé!\n\n";
}

// 3. Scanner les policies
echo "🛡️ 3. PERMISSIONS DANS LES POLICIES\n";
echo str_repeat("=", 60) . "\n";

$policiesPath = app_path('Policies');
$policyPermissions = [];

if (File::exists($policiesPath)) {
    $policyFiles = File::allFiles($policiesPath);
    
    foreach ($policyFiles as $file) {
        $content = file_get_contents($file->getPathname());
        $className = pathinfo($file->getFilename(), PATHINFO_FILENAME);
        
        // Chercher hasPermissionTo
        preg_match_all('/hasPermissionTo\s*\(\s*[\'\"](.*?)[\'\"]/', $content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $permission) {
                $policyPermissions[$className][] = $permission;
            }
        }
        
        // Chercher hasAnyRole ou hasRole
        preg_match_all('/has(Any)?Role\s*\(\s*[\'\"](.*?)[\'\"]/', $content, $roleMatches);
        if (!empty($roleMatches[2])) {
            foreach ($roleMatches[2] as $role) {
                $policyPermissions[$className]['roles'][] = $role;
            }
        }
    }
    
    echo "🏛️ Permissions trouvées dans les policies:\n";
    foreach ($policyPermissions as $policy => $data) {
        echo "📜 $policy:\n";
        if (isset($data['roles'])) {
            echo "   Rôles: " . implode(', ', $data['roles']) . "\n";
            unset($data['roles']);
        }
        foreach ($data as $perms) {
            if (is_array($perms)) {
                foreach ($perms as $perm) {
                    echo "   • $perm\n";
                }
            }
        }
        echo "\n";
    }
} else {
    echo "❌ Dossier Policies non trouvé!\n\n";
}

// 4. Analyser les incohérences
echo "⚠️ 4. ANALYSE DES INCOHÉRENCES\n";
echo str_repeat("=", 60) . "\n";

// Créer une liste unique de toutes les permissions utilisées
$allUsedPermissions = [];

// Depuis les vues
foreach ($canDirectives as $viewFile => $directives) {
    foreach ($directives as $dir) {
        $allUsedPermissions['vues'][] = $dir['permission'];
    }
}

// Depuis les policies
foreach ($policyPermissions as $policy => $data) {
    if (isset($data['roles'])) {
        unset($data['roles']);
    }
    foreach ($data as $perms) {
        if (is_array($perms)) {
            foreach ($perms as $perm) {
                $allUsedPermissions['policies'][] = $perm;
            }
        }
    }
}

// Permissions DB
$dbPermissions = $allPermissions->pluck('name')->toArray();
$allUsedPermissions['database'] = $dbPermissions;

// Détecter les incohérences
echo "🔍 Incohérences détectées:\n\n";

// Permissions dans les vues mais pas dans la DB
if (isset($allUsedPermissions['vues'])) {
    $vuesUnique = array_unique($allUsedPermissions['vues']);
    $vuesPasEnDB = array_diff($vuesUnique, $dbPermissions);
    
    if (!empty($vuesPasEnDB)) {
        echo "❌ Permissions dans les VUES mais PAS dans la DB:\n";
        foreach ($vuesPasEnDB as $perm) {
            echo "   • $perm\n";
        }
        echo "\n";
    }
}

// Permissions dans les policies mais pas dans la DB
if (isset($allUsedPermissions['policies'])) {
    $policiesUnique = array_unique($allUsedPermissions['policies']);
    $policiesPasEnDB = array_diff($policiesUnique, $dbPermissions);
    
    if (!empty($policiesPasEnDB)) {
        echo "❌ Permissions dans les POLICIES mais PAS dans la DB:\n";
        foreach ($policiesPasEnDB as $perm) {
            echo "   • $perm\n";
        }
        echo "\n";
    }
}

// Permissions dans la DB mais pas utilisées
$permissionsUtilisees = array_merge(
    $allUsedPermissions['vues'] ?? [],
    $allUsedPermissions['policies'] ?? []
);
$permissionsInutilisees = array_diff($dbPermissions, array_unique($permissionsUtilisees));

if (!empty($permissionsInutilisees)) {
    echo "⚠️ Permissions dans la DB mais PAS utilisées:\n";
    foreach ($permissionsInutilisees as $perm) {
        echo "   • $perm\n";
    }
    echo "\n";
}

// 5. Recommandations
echo "💡 5. RECOMMANDATIONS ET BONNES PRATIQUES\n";
echo str_repeat("=", 60) . "\n";

echo "📋 RÈGLES DE NOMMAGE RECOMMANDÉES:\n";
echo "   • Format: {action}_{model} (snake_case)\n";
echo "   • Actions standards: view, create, update, delete, moderate, publish\n";
echo "   • Modèles au pluriel: projets, actualites, publications, etc.\n";
echo "   • Exemples: view_projets, create_actualite, moderate_publication\n\n";

echo "🏗️ STRUCTURE RECOMMANDÉE:\n";
echo "   1. Permissions granulaires par action/modèle\n";
echo "   2. Rôles hiérarchiques (user < contributor < moderator < admin < super-admin)\n";
echo "   3. Super-admin a TOUS les droits automatiquement\n";
echo "   4. Guards explicites ('web' pour l'interface web)\n\n";

echo "🔧 PLAN DE CORRECTION:\n";
echo "   1. Standardiser toutes les permissions en snake_case\n";
echo "   2. Mettre à jour les policies pour utiliser les noms standardisés\n";
echo "   3. Ajouter la vérification super-admin systématique\n";
echo "   4. Garder les vues inchangées (référence de vérité)\n";
echo "   5. Synchroniser la DB avec les permissions utilisées\n\n";

echo "=== FIN DE L'AUDIT COMPLET ===\n";
?>
