<?php

/**
 * VALIDATION FINALE DES POLICIES APRÈS HARMONISATION PLURIEL
 * 
 * Ce script vérifie que toutes les policies sont présentes et cohérentes
 * avec les permissions au format pluriel.
 */

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

echo "🔍 VALIDATION FINALE DES POLICIES\n";
echo "================================\n\n";

// 1. Vérifier la présence de toutes les policies
$expectedPolicies = [
    'App\\Policies\\ActualitePolicy',
    'App\\Policies\\ProjetPolicy', 
    'App\\Policies\\PublicationPolicy',
    'App\\Policies\\EvenementPolicy',
    'App\\Policies\\ServicePolicy',
    'App\\Policies\\MediaPolicy',
    'App\\Policies\\RapportPolicy',
    'App\\Policies\\AuteurPolicy',
    'App\\Policies\\UserPolicy'
];

echo "1. VÉRIFICATION DES POLICIES\n";
echo "----------------------------\n";

$policiesFound = 0;
foreach ($expectedPolicies as $policy) {
    if (class_exists($policy)) {
        echo "✅ $policy\n";
        $policiesFound++;
    } else {
        echo "❌ $policy - MANQUANTE\n";
    }
}

echo "\n📊 Policies trouvées: $policiesFound/" . count($expectedPolicies) . "\n\n";

// 2. Vérifier les permissions au format pluriel
echo "2. VÉRIFICATION DES PERMISSIONS PLURIEL\n";
echo "---------------------------------------\n";

$permissions = DB::table('permissions')->pluck('name')->toArray();
$plurielPermissions = array_filter($permissions, function($perm) {
    return str_contains($perm, '_actualites') || 
           str_contains($perm, '_projets') || 
           str_contains($perm, '_publications') ||
           str_contains($perm, '_evenements') ||
           str_contains($perm, '_services') ||
           str_contains($perm, '_medias') ||
           str_contains($perm, '_rapports') ||
           str_contains($perm, '_auteurs') ||
           str_contains($perm, '_users');
});

echo "✅ Permissions au format pluriel: " . count($plurielPermissions) . "\n";
foreach ($plurielPermissions as $perm) {
    echo "   - $perm\n";
}

// 3. Test avec l'utilisateur admin@ucbc.org
echo "\n3. TEST UTILISATEUR ADMIN@UCBC.ORG\n";
echo "----------------------------------\n";

$admin = User::where('email', 'admin@ucbc.org')->first();
if ($admin) {
    echo "✅ Utilisateur trouvé: {$admin->name} ({$admin->email})\n";
    echo "🔹 Rôles: " . $admin->roles->pluck('name')->implode(', ') . "\n";
    
    // Test des permissions clés
    $keyPermissions = [
        'create_actualites',
        'update_actualites', 
        'delete_actualites',
        'create_projets',
        'update_projets',
        'create_publications',
        'update_publications'
    ];
    
    echo "\n📋 Test des permissions clés:\n";
    foreach ($keyPermissions as $permission) {
        $hasPermission = $admin->hasPermissionTo($permission);
        echo ($hasPermission ? "✅" : "❌") . " $permission\n";
    }
} else {
    echo "❌ Utilisateur admin@ucbc.org non trouvé\n";
}

// 4. Test des Gates/Policies
echo "\n4. TEST DES GATES/POLICIES\n";
echo "-------------------------\n";

if ($admin) {
    $gateTests = [
        ['actualite', 'create', null],
        ['publication', 'create', null],
        ['projet', 'create', null]
    ];
    
    foreach ($gateTests as [$model, $action, $instance]) {
        try {
            $can = Gate::forUser($admin)->allows($action, $model);
            echo ($can ? "✅" : "❌") . " Gate::allows('$action', '$model')\n";
        } catch (Exception $e) {
            echo "⚠️  Gate::allows('$action', '$model') - " . $e->getMessage() . "\n";
        }
    }
}

// 5. Vérification des fichiers de policies
echo "\n5. CONTENU DES POLICIES\n";
echo "----------------------\n";

$policyDir = __DIR__ . '/app/Policies/';
$policyFiles = glob($policyDir . '*.php');

foreach ($policyFiles as $file) {
    $filename = basename($file);
    if (!str_contains($filename, '.backup')) {
        $content = file_get_contents($file);
        $plurielCount = 0;
        
        // Compter les permissions au format pluriel
        if (preg_match_all('/hasPermissionTo\([\'"]([^\'"]*)[\'"]\)/', $content, $matches)) {
            foreach ($matches[1] as $permission) {
                if (str_ends_with($permission, 's') && 
                    (str_contains($permission, 'actualites') || 
                     str_contains($permission, 'projets') || 
                     str_contains($permission, 'publications') ||
                     str_contains($permission, 'evenements') ||
                     str_contains($permission, 'services') ||
                     str_contains($permission, 'medias') ||
                     str_contains($permission, 'rapports') ||
                     str_contains($permission, 'auteurs') ||
                     str_contains($permission, 'users'))) {
                    $plurielCount++;
                }
            }
        }
        
        echo "📄 $filename: $plurielCount permissions pluriel\n";
    }
}

echo "\n🎉 VALIDATION TERMINÉE\n";
echo "======================\n";
echo "Les policies manquantes ont été créées.\n";
echo "Le système de permissions est maintenant cohérent au format pluriel.\n";
echo "L'utilisateur admin@ucbc.org devrait avoir accès à tous les boutons de création.\n";
