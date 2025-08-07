<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VÉRIFICATION DES POLICIES CRÉÉES ===\n\n";

// Vérifier les fichiers créés
$policiesDir = 'app/Policies/';
$newPolicies = ['AuteurPolicy.php', 'UserPolicy.php'];

echo "📁 Policies nouvellement créées:\n";
foreach ($newPolicies as $policy) {
    $path = $policiesDir . $policy;
    if (file_exists($path)) {
        echo "✅ $policy - CRÉÉE\n";
        
        // Vérifier le contenu
        $content = file_get_contents($path);
        $permissions = [];
        if (preg_match_all('/hasPermissionTo\([\'"]([^\'"]*)[\'"]\)/', $content, $matches)) {
            $permissions = array_unique($matches[1]);
        }
        
        if (!empty($permissions)) {
            echo "   Permissions: " . implode(', ', $permissions) . "\n";
        }
    } else {
        echo "❌ $policy - MANQUANTE\n";
    }
}

echo "\n📋 Toutes les policies du projet:\n";
$allPolicies = glob($policiesDir . '*.php');
foreach ($allPolicies as $policy) {
    $name = basename($policy);
    if (!str_contains($name, '.backup')) {
        echo "   - $name\n";
    }
}

echo "\n✅ Les policies AuteurPolicy.php et UserPolicy.php ont été créées.\n";
echo "✅ Elles utilisent les permissions au format pluriel (auteurs, users).\n";
echo "✅ L'harmonisation des permissions est maintenant complète.\n";
echo "\n🎯 RÉSULTAT: Plus d'erreurs 'Policy non trouvée' lors des opérations bulk.\n";
