<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== AUDIT COHÉRENCE PERMISSIONS DB ↔ POLICIES ===\n";
echo "Date: " . now()->format('d/m/Y H:i:s') . "\n\n";

// 1. Récupérer toutes les permissions de la base de données
echo "📊 1. PERMISSIONS DANS LA BASE DE DONNÉES\n";
echo str_repeat("=", 60) . "\n";

$dbPermissions = DB::table('permissions')
    ->where('name', 'LIKE', '%actualite%')
    ->orWhere('name', 'LIKE', '%publication%')
    ->orWhere('name', 'LIKE', '%projet%')
    ->orderBy('name')
    ->get();

$dbPermissionsByModel = [];
foreach ($dbPermissions as $perm) {
    if (strpos($perm->name, 'actualite') !== false) {
        $dbPermissionsByModel['actualites'][] = $perm->name;
    } elseif (strpos($perm->name, 'publication') !== false) {
        $dbPermissionsByModel['publications'][] = $perm->name;
    } elseif (strpos($perm->name, 'projet') !== false) {
        $dbPermissionsByModel['projets'][] = $perm->name;
    }
}

foreach ($dbPermissionsByModel as $model => $permissions) {
    echo "📋 $model (" . count($permissions) . " permissions):\n";
    foreach ($permissions as $perm) {
        echo "  • $perm\n";
    }
    echo "\n";
}

// 2. Analyser les policies pour extraire les permissions utilisées
echo "📋 2. PERMISSIONS DANS LES POLICIES\n";
echo str_repeat("=", 60) . "\n";

$policyFiles = [
    'ActualitePolicy.php' => app_path('Policies/ActualitePolicy.php'),
    'PublicationPolicy.php' => app_path('Policies/PublicationPolicy.php'),
    'ProjetPolicy.php' => app_path('Policies/ProjetPolicy.php'),
];

$policyPermissions = [];

foreach ($policyFiles as $policyName => $filePath) {
    echo "🔍 Analyse de $policyName:\n";
    
    if (!file_exists($filePath)) {
        echo "  ❌ Fichier introuvable: $filePath\n\n";
        continue;
    }
    
    $content = file_get_contents($filePath);
    
    // Extraire les permissions avec hasPermissionTo
    preg_match_all("/hasPermissionTo\s*\(\s*['\"]([^'\"]+)['\"]/", $content, $matches);
    
    if (!empty($matches[1])) {
        $policyPermissions[$policyName] = array_unique($matches[1]);
        echo "  📝 Permissions trouvées (" . count($policyPermissions[$policyName]) . "):\n";
        foreach ($policyPermissions[$policyName] as $perm) {
            echo "    • $perm\n";
        }
    } else {
        echo "  ⚠️ Aucune permission hasPermissionTo() trouvée\n";
        $policyPermissions[$policyName] = [];
    }
    echo "\n";
}

// 3. Vérifier la cohérence entre DB et Policies
echo "🔍 3. ANALYSE DE COHÉRENCE DB ↔ POLICIES\n";
echo str_repeat("=", 60) . "\n";

$modelPolicyMap = [
    'actualites' => 'ActualitePolicy.php',
    'publications' => 'PublicationPolicy.php', 
    'projets' => 'ProjetPolicy.php'
];

$totalInconsistencies = 0;

foreach ($modelPolicyMap as $model => $policyFile) {
    echo "🎯 MODÈLE: " . strtoupper($model) . "\n";
    echo str_repeat("-", 40) . "\n";
    
    $dbPerms = $dbPermissionsByModel[$model] ?? [];
    $policyPerms = $policyPermissions[$policyFile] ?? [];
    
    // Permissions en DB mais pas dans Policy
    $dbOnly = array_diff($dbPerms, $policyPerms);
    if (!empty($dbOnly)) {
        echo "❌ Permissions en DB mais PAS utilisées dans Policy:\n";
        foreach ($dbOnly as $perm) {
            echo "   • $perm\n";
            $totalInconsistencies++;
        }
        echo "\n";
    }
    
    // Permissions dans Policy mais pas en DB
    $policyOnly = array_diff($policyPerms, $dbPerms);
    if (!empty($policyOnly)) {
        echo "❌ Permissions utilisées dans Policy mais PAS en DB:\n";
        foreach ($policyOnly as $perm) {
            echo "   • $perm\n";
            $totalInconsistencies++;
        }
        echo "\n";
    }
    
    // Permissions cohérentes
    $consistent = array_intersect($dbPerms, $policyPerms);
    if (!empty($consistent)) {
        echo "✅ Permissions cohérentes (" . count($consistent) . "):\n";
        foreach ($consistent as $perm) {
            echo "   • $perm\n";
        }
        echo "\n";
    }
    
    if (empty($dbOnly) && empty($policyOnly)) {
        echo "✅ PARFAITEMENT COHÉRENT!\n\n";
    }
    
    echo str_repeat("-", 40) . "\n\n";
}

// 4. Vérifier les méthodes des policies
echo "🔧 4. ANALYSE DES MÉTHODES DANS LES POLICIES\n";
echo str_repeat("=", 60) . "\n";

foreach ($policyFiles as $policyName => $filePath) {
    if (!file_exists($filePath)) continue;
    
    echo "📋 $policyName:\n";
    $content = file_get_contents($filePath);
    
    // Vérifier les méthodes standard
    $standardMethods = ['viewAny', 'view', 'create', 'update', 'delete', 'restore', 'forceDelete'];
    $foundMethods = [];
    
    foreach ($standardMethods as $method) {
        if (preg_match("/function\s+$method\s*\(/", $content)) {
            $foundMethods[] = $method;
        }
    }
    
    // Vérifier les méthodes personnalisées
    preg_match_all("/public\s+function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(/", $content, $allMethods);
    $customMethods = array_diff($allMethods[1], $standardMethods, ['__construct']);
    
    echo "  ✅ Méthodes standard: " . implode(', ', $foundMethods) . "\n";
    if (!empty($customMethods)) {
        echo "  🔧 Méthodes personnalisées: " . implode(', ', $customMethods) . "\n";
    }
    
    // Vérifier la présence de super-admin
    if (strpos($content, 'super-admin') !== false) {
        echo "  ✅ Gestion super-admin présente\n";
    } else {
        echo "  ⚠️ Gestion super-admin manquante\n";
    }
    
    // Vérifier l'utilisation du guard
    if (strpos($content, "'web'") !== false) {
        echo "  ✅ Guard 'web' spécifié\n";
    } else {
        echo "  ⚠️ Guard non spécifié\n";
    }
    
    echo "\n";
}

// 5. Résumé final
echo "📊 5. RÉSUMÉ DE LA COHÉRENCE\n";
echo str_repeat("=", 60) . "\n";

if ($totalInconsistencies == 0) {
    echo "🎉 PARFAITEMENT COHÉRENT!\n";
    echo "   Toutes les permissions DB correspondent aux policies\n\n";
} else {
    echo "⚠️ INCOHÉRENCES DÉTECTÉES: $totalInconsistencies\n";
    echo "   Des corrections sont nécessaires\n\n";
}

echo "📋 STATISTIQUES:\n";
echo "  • Permissions totales en DB: " . $dbPermissions->count() . "\n";
echo "  • Policies analysées: " . count($policyFiles) . "\n";
echo "  • Incohérences trouvées: $totalInconsistencies\n\n";

// 6. Recommandations
echo "🛠️ 6. RECOMMANDATIONS\n";
echo str_repeat("=", 60) . "\n";

if ($totalInconsistencies > 0) {
    echo "📝 ACTIONS RECOMMANDÉES:\n";
    echo "  1. Synchroniser les permissions manquantes\n";
    echo "  2. Supprimer les permissions inutilisées\n";
    echo "  3. Vérifier la logique des policies\n";
    echo "  4. Tester les autorisations\n\n";
}

echo "🔍 PROCHAINE ÉTAPE:\n";
echo "  → Analyser l'utilisateur admin@ucbc.org avec ces permissions validées\n\n";

echo str_repeat("=", 65) . "\n";
