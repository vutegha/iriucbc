<?php

// Test rapide pour vérifier la correction array_combine()
require_once 'vendor/autoload.php';

// Simulation de l'environnement Laravel
$app = require_once 'bootstrap/app.php';

// Test Collection vs Array
echo "=== Test array_combine() Fix ===\n";

// Simulation du problème
$collection = collect([2023, 2024, 2025]);
echo "Collection: " . get_class($collection) . "\n";

// Test direct avec Collection (doit échouer)
try {
    $result = array_combine($collection, $collection);
    echo "ERREUR: array_combine() avec Collection a fonctionné (ne devrait pas)\n";
} catch (TypeError $e) {
    echo "✓ ATTENDU: " . $e->getMessage() . "\n";
}

// Test avec toArray() (doit fonctionner)
try {
    $array = $collection->toArray();
    echo "Array: " . gettype($array) . "\n";
    $result = array_combine($array, $array);
    echo "✓ CORRIGÉ: array_combine() avec toArray() fonctionne\n";
    echo "Résultat: " . json_encode($result) . "\n";
} catch (TypeError $e) {
    echo "✗ ERREUR: " . $e->getMessage() . "\n";
}

echo "\n=== Test terminé ===\n";
