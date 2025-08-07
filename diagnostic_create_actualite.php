<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DIAGNOSTIC PERMISSION CREATE ACTUALITE ===\n";
echo "Date: " . now()->format('d/m/Y H:i:s') . "\n\n";

// 1. Vérifier les permissions en base de données
echo "🔍 1. PERMISSIONS ACTUALITE EN BASE\n";
echo str_repeat("=", 50) . "\n";

$actualitePermissions = DB::table('permissions')
    ->where('name', 'LIKE', '%actualite%')
    ->orderBy('name')
    ->get();

echo "Permissions trouvées (" . $actualitePermissions->count() . "):\n";
foreach ($actualitePermissions as $perm) {
    echo "  • {$perm->name}\n";
}
echo "\n";

// 2. Tester l'utilisateur admin@ucbc.org
echo "👤 2. TEST UTILISATEUR admin@ucbc.org\n";
echo str_repeat("=", 50) . "\n";

$user = \App\Models\User::where('email', 'admin@ucbc.org')->first();

if ($user) {
    echo "Utilisateur trouvé: {$user->name}\n\n";
    
    // Tester les différentes variantes
    $permissionsToTest = [
        'create_actualite',      // Singulier (correct en DB)
        'create_actualites',     // Pluriel (utilisé dans la vue)
        'view_actualites',       // Pour comparaison
    ];
    
    echo "🧪 Tests de permissions:\n";
    foreach ($permissionsToTest as $perm) {
        $hasPermission = $user->hasPermissionTo($perm, 'web');
        echo "  " . ($hasPermission ? "✅" : "❌") . " $perm\n";
    }
    echo "\n";
    
    // Tester via policy
    echo "🏛️ Test via ActualitePolicy:\n";
    try {
        $canCreate = $user->can('create', \App\Models\Actualite::class);
        echo "  " . ($canCreate ? "✅" : "❌") . " can('create', Actualite::class)\n";
    } catch (Exception $e) {
        echo "  ❌ Erreur policy: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
} else {
    echo "❌ Utilisateur admin@ucbc.org introuvable!\n\n";
}

// 3. Analyser la vue
echo "👁️ 3. ANALYSE DE LA VUE\n";
echo str_repeat("=", 50) . "\n";

$viewPath = resource_path('views/admin/actualite/index.blade.php');

if (file_exists($viewPath)) {
    $content = file_get_contents($viewPath);
    
    // Chercher les directives @can problématiques
    preg_match_all("/@can\s*\(\s*['\"]([^'\"]*actualite[^'\"]*)['\"]]/", $content, $matches);
    
    if (!empty($matches[1])) {
        echo "Directives @can trouvées:\n";
        $uniquePermissions = array_unique($matches[1]);
        foreach ($uniquePermissions as $perm) {
            $isCorrect = in_array($perm, ['create_actualite', 'view_actualites', 'update_actualite', 'delete_actualite', 'moderate_actualites', 'publish_actualites', 'unpublish_actualites']);
            echo "  " . ($isCorrect ? "✅" : "❌") . " @can('$perm')\n";
        }
        echo "\n";
    }
    
    // Identifier les problèmes spécifiques
    echo "🚨 PROBLÈMES DÉTECTÉS:\n";
    if (strpos($content, "create_actualites") !== false) {
        echo "  ❌ Utilisation de 'create_actualites' au lieu de 'create_actualite'\n";
    }
    
    if (strpos($content, "update_actualites") !== false && strpos($content, "update_actualite") === false) {
        echo "  ❌ Utilisation de 'update_actualites' au lieu de 'update_actualite'\n";
    }
    
    if (strpos($content, "delete_actualites") !== false && strpos($content, "delete_actualite") === false) {
        echo "  ❌ Utilisation de 'delete_actualites' au lieu de 'delete_actualite'\n";
    }
    
} else {
    echo "❌ Fichier vue introuvable: $viewPath\n";
}

echo "\n";

// 4. Solutions
echo "🛠️ 4. SOLUTIONS\n";
echo str_repeat("=", 50) . "\n";

echo "📝 Option 1 - Corriger la vue (RECOMMANDÉ):\n";
echo "  • Remplacer @can('create_actualites') par @can('create_actualite')\n";
echo "  • Uniformiser toutes les permissions singulières/plurielles\n\n";

echo "📝 Option 2 - Ajouter permission plurielle en DB:\n";
echo "  • Créer 'create_actualites' en DB pour compatibilité\n";
echo "  • Moins recommandé car casse la cohérence\n\n";

echo "📝 Option 3 - Utiliser les policies:\n";
echo "  • @can('create', App\\Models\\Actualite::class)\n";
echo "  • Plus robuste et indépendant des noms de permissions\n\n";

// 5. Générer la correction automatique
echo "💻 5. CORRECTION AUTOMATIQUE\n";
echo str_repeat("=", 50) . "\n";

$corrections = [
    "create_actualites" => "create_actualite",
    "update_actualites" => "update_actualite", 
    "delete_actualites" => "delete_actualite",
];

echo "Script de correction pour la vue:\n\n";
foreach ($corrections as $wrong => $correct) {
    echo "Remplacer: @can('$wrong') → @can('$correct')\n";
}

echo "\nVoulez-vous appliquer ces corrections? (Y/N)\n";

echo "\n" . str_repeat("=", 55) . "\n";
