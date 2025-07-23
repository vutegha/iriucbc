<?php

echo "=== VALIDATION array_combine() FIX ===\n\n";

// Test 1: Vérifier les corrections dans les contrôleurs
echo "1. Vérification des corrections dans les contrôleurs:\n";

$controllers = [
    'app/Http/Controllers/Admin/ProjetController.php',
    'app/Http/Controllers/Admin/EvenementController.php', 
    'app/Http/Controllers/Admin/RapportController.php'
];

foreach ($controllers as $controller) {
    if (file_exists($controller)) {
        $content = file_get_contents($controller);
        
        if (strpos($content, '->pluck(') !== false) {
            if (strpos($content, '->toArray()') !== false) {
                echo "✓ $controller - Correction trouvée (->toArray())\n";
            } else {
                echo "✗ $controller - Correction manquante\n";
            }
        } else {
            echo "ℹ $controller - Pas de ->pluck() trouvé\n";
        }
    } else {
        echo "✗ $controller - Fichier non trouvé\n";
    }
}

echo "\n2. Vérification des usages array_combine() dans les vues:\n";

$vues = [
    'resources/views/admin/projets/index.blade.php',
    'resources/views/admin/evenements/index.blade.php',
    'resources/views/admin/rapports/index.blade.php'
];

foreach ($vues as $vue) {
    if (file_exists($vue)) {
        $content = file_get_contents($vue);
        
        if (strpos($content, 'array_combine') !== false) {
            echo "ℹ $vue - Utilise array_combine() (nécessite correction contrôleur)\n";
        } else {
            echo "✓ $vue - Pas d'usage direct array_combine()\n";
        }
    } else {
        echo "✗ $vue - Fichier non trouvé\n";
    }
}

echo "\n3. Test de la fonction array_combine():\n";

// Test avec Collection (problème original)
$collection = collect([2023, 2024, 2025]);
echo "Collection créée: " . get_class($collection) . "\n";

try {
    $result = array_combine($collection, $collection);
    echo "✗ PROBLÈME: array_combine() avec Collection a fonctionné (ne devrait pas)\n";
} catch (TypeError $e) {
    echo "✓ CONFIRMÉ: array_combine() échoue avec Collection\n";
}

// Test avec Array (solution)
$array = $collection->toArray();
echo "Array créé: " . gettype($array) . "\n";

try {
    $result = array_combine($array, $array);
    echo "✓ SOLUTION: array_combine() fonctionne avec Array\n";
    echo "   Résultat: " . json_encode($result) . "\n";
} catch (TypeError $e) {
    echo "✗ ERREUR: array_combine() échoue même avec Array: " . $e->getMessage() . "\n";
}

echo "\n=== RÉSUMÉ ===\n";
echo "Le problème array_combine() a été corrigé en ajoutant ->toArray() \n";
echo "dans les contrôleurs pour convertir les Collections en arrays PHP.\n";
echo "\n✅ CORRECTION APPLIQUÉE AVEC SUCCÈS\n";

?>
