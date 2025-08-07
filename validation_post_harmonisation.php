<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

echo "=== VALIDATION POST-HARMONISATION PLURIEL ===\n";
echo "Date: " . now()->format('d/m/Y H:i:s') . "\n\n";

// 1. Vérifier les permissions en base de données
echo "📊 1. PERMISSIONS EN BASE (FORMAT PLURIEL)\n";
echo str_repeat("=", 60) . "\n";

$pluralPermissions = DB::table('permissions')
    ->where('name', 'LIKE', '%actualites%')
    ->orWhere('name', 'LIKE', '%projets%')
    ->orWhere('name', 'LIKE', '%publications%')
    ->orderBy('name')
    ->get();

echo "Permissions plurielles trouvées (" . $pluralPermissions->count() . "):\n";
foreach ($pluralPermissions as $perm) {
    echo "  ✅ {$perm->name}\n";
}
echo "\n";

// 2. Tester l'utilisateur admin@ucbc.org
echo "👤 2. TEST UTILISATEUR admin@ucbc.org\n";
echo str_repeat("=", 60) . "\n";

$user = User::where('email', 'admin@ucbc.org')->first();

if ($user) {
    echo "Utilisateur: {$user->name}\n";
    echo "Rôles: " . $user->getRoleNames()->implode(', ') . "\n\n";
    
    // Tester les permissions critiques
    $criticalPermissions = [
        'create_actualites',    // Celui qui posait problème
        'update_actualites',
        'delete_actualites',
        'create_projets',
        'create_publications',
        'view_actualites',
        'view_projets',
        'view_publications'
    ];
    
    echo "🧪 Tests des permissions critiques:\n";
    $passedTests = 0;
    foreach ($criticalPermissions as $perm) {
        $hasPermission = $user->hasPermissionTo($perm, 'web');
        echo "  " . ($hasPermission ? "✅" : "❌") . " $perm\n";
        if ($hasPermission) $passedTests++;
    }
    
    echo "\nRésultat: $passedTests/" . count($criticalPermissions) . " permissions validées\n\n";
    
    // Tester via les policies
    echo "🏛️ Tests via policies:\n";
    try {
        $canCreateActualite = $user->can('create', \App\Models\Actualite::class);
        echo "  " . ($canCreateActualite ? "✅" : "❌") . " can('create', Actualite::class)\n";
        
        $canCreateProjet = $user->can('create', \App\Models\Projet::class);
        echo "  " . ($canCreateProjet ? "✅" : "❌") . " can('create', Projet::class)\n";
        
        $canCreatePublication = $user->can('create', \App\Models\Publication::class);
        echo "  " . ($canCreatePublication ? "✅" : "❌") . " can('create', Publication::class)\n";
        
    } catch (Exception $e) {
        echo "  ❌ Erreur policy: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
} else {
    echo "❌ Utilisateur admin@ucbc.org introuvable!\n\n";
}

// 3. Vérifier les vues
echo "👁️ 3. VÉRIFICATION DES VUES\n";
echo str_repeat("=", 60) . "\n";

$viewPath = resource_path('views/admin/actualite/index.blade.php');

if (file_exists($viewPath)) {
    $content = file_get_contents($viewPath);
    
    // Vérifier le bouton Create
    if (strpos($content, "@can('create_actualites')") !== false) {
        echo "✅ Vue actualite/index.blade.php utilise 'create_actualites'\n";
    } else {
        echo "❌ Vue actualite/index.blade.php n'utilise PAS 'create_actualites'\n";
    }
    
    // Vérifier d'autres permissions
    $viewPermissions = [];
    preg_match_all("/@can\s*\(\s*['\"]([^'\"]*)['\"]]/", $content, $matches);
    if (!empty($matches[1])) {
        $viewPermissions = array_unique($matches[1]);
    }
    
    echo "Permissions trouvées dans la vue:\n";
    foreach ($viewPermissions as $perm) {
        if (strpos($perm, 'actualite') !== false) {
            $isPlural = strpos($perm, 'actualites') !== false;
            echo "  " . ($isPlural ? "✅" : "❌") . " @can('$perm')\n";
        }
    }
    echo "\n";
} else {
    echo "❌ Vue introuvable: $viewPath\n\n";
}

// 4. Résumé et recommandations
echo "📋 4. RÉSUMÉ FINAL\n";
echo str_repeat("=", 60) . "\n";

// Vérifier la cohérence globale
$dbPlural = $pluralPermissions->count() >= 18; // Au moins les 3 modules principaux
$userHasAccess = isset($passedTests) && $passedTests >= 6;
$viewsUpdated = strpos($content ?? '', 'create_actualites') !== false;

echo "État de l'harmonisation:\n";
echo "  " . ($dbPlural ? "✅" : "❌") . " Base de données (format pluriel)\n";
echo "  " . ($userHasAccess ? "✅" : "❌") . " Permissions utilisateur\n";
echo "  " . ($viewsUpdated ? "✅" : "❌") . " Vues mises à jour\n\n";

if ($dbPlural && $userHasAccess && $viewsUpdated) {
    echo "🎉 HARMONISATION RÉUSSIE!\n";
    echo "L'utilisateur admin@ucbc.org devrait maintenant voir le bouton 'Create'\n";
    echo "et avoir accès à toutes les fonctionnalités.\n";
} else {
    echo "⚠️ Harmonisation incomplète, vérifications nécessaires.\n";
    
    if (!$viewsUpdated) {
        echo "\n🔧 ACTION REQUISE:\n";
        echo "Les vues n'ont pas été mises à jour automatiquement.\n";
        echo "Mise à jour manuelle nécessaire:\n";
        echo "- Remplacer @can('create_actualite') par @can('create_actualites')\n";
        echo "- Et autres permissions similaires\n";
    }
}

echo "\n" . str_repeat("=", 65) . "\n";
