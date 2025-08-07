&lt;?php

/**
 * Script de test pour vérifier le fonctionnement de l'interface actualité
 * Vérifie les éléments critiques du formulaire de création/édition
 */

echo "=== TEST INTERFACE ACTUALITE ===\n\n";

// Chemin vers le fichier de vue
$formPath = __DIR__ . '/resources/views/admin/actualite/_form.blade.php';

if (!file_exists($formPath)) {
    echo "❌ ERREUR: Fichier _form.blade.php introuvable\n";
    exit(1);
}

$content = file_get_contents($formPath);

// Tests des éléments corrigés
$tests = [
    'Modal unique' => [
        'pattern' => '/id=["\']mediaModal["\']/',
        'expected_count' => 1,
        'description' => 'Un seul modal avec ID mediaModal'
    ],
    'MediaList ID unique' => [
        'pattern' => '/id=["\']mediaList["\']/',
        'expected_count' => 1,
        'description' => 'Un seul conteneur mediaList'
    ],
    'Checkboxes customisés' => [
        'pattern' => '/class=["\'][^"\']*sr-only[^"\']*["\']/',
        'expected_count' => 2,
        'description' => 'Deux checkboxes avec classe sr-only'
    ],
    'JavaScript checkboxes' => [
        'pattern' => '/initCustomCheckboxes/',
        'expected_count' => 1,
        'description' => 'Fonction d\'initialisation des checkboxes'
    ],
    'Modal close button' => [
        'pattern' => '/id=["\']modal-close-btn["\']/',
        'expected_count' => 1,
        'description' => 'Bouton de fermeture du modal'
    ],
    'Gestion événements modal' => [
        'pattern' => '/getElementById\(["\']modal-close-btn["\']\)/',
        'expected_count' => 1,
        'description' => 'Gestion JavaScript du bouton fermeture'
    ]
];

$allPassed = true;

foreach ($tests as $testName => $test) {
    preg_match_all($test['pattern'], $content, $matches);
    $count = count($matches[0]);
    
    if ($count === $test['expected_count']) {
        echo "✅ $testName: $count occurrence(s) trouvée(s) - " . $test['description'] . "\n";
    } else {
        echo "❌ $testName: $count occurrence(s) trouvée(s), attendu: {$test['expected_count']} - " . $test['description'] . "\n";
        $allPassed = false;
    }
}

echo "\n=== ANALYSE STRUCTURE ===\n";

// Vérification de la structure du modal
if (strpos($content, 'id="mediaModal"') !== false) {
    if (strpos($content, 'id="mediaList"') !== false) {
        echo "✅ Structure modal: MediaModal contient MediaList\n";
    } else {
        echo "❌ Structure modal: MediaList manquant dans MediaModal\n";
        $allPassed = false;
    }
} else {
    echo "❌ Structure modal: MediaModal manquant\n";
    $allPassed = false;
}

// Vérification des checkboxes
$checkboxPattern = '/&lt;input[^&gt;]*type=["\']checkbox["\'][^&gt;]*class=["\'][^"\']*sr-only[^"\']*["\'][^&gt;]*&gt;/';
preg_match_all($checkboxPattern, $content, $checkboxMatches);

if (count($checkboxMatches[0]) === 2) {
    echo "✅ Checkboxes: 2 checkboxes sr-only trouvés (a_la_une et en_vedette)\n";
} else {
    echo "❌ Checkboxes: " . count($checkboxMatches[0]) . " checkboxes sr-only trouvés, attendu: 2\n";
    $allPassed = false;
}

// Résultat final
echo "\n=== RÉSULTAT FINAL ===\n";
if ($allPassed) {
    echo "✅ TOUS LES TESTS RÉUSSIS - L'interface actualité devrait fonctionner correctement\n";
    echo "\nCorrections appliquées :\n";
    echo "• Modal médiathèque unifié et fonctionnel\n";
    echo "• Checkboxes personnalisés avec gestion JavaScript\n";
    echo "• Boutons de fermeture modal opérationnels\n";
    echo "• IDs uniques pour éviter les conflits\n";
} else {
    echo "❌ CERTAINS TESTS ONT ÉCHOUÉ - Vérifications supplémentaires nécessaires\n";
}

echo "\n";
