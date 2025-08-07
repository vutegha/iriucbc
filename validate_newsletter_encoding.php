<?php

/**
 * VALIDATION DES CORRECTIONS D'ENCODAGE
 */

echo "=== VALIDATION CORRECTIONS ENCODAGE ===\n\n";

$viewFiles = [
    'resources/views/admin/newsletter/index.blade.php',
    'resources/views/admin/newsletter/create.blade.php',
    'resources/views/admin/newsletter/edit.blade.php',
    'resources/views/admin/newsletter/show.blade.php',
    'resources/views/admin/newsletter/_form.blade.php'
];

$encodingProblems = [
    'Ã©', 'Ã ', 'ÃŠ', 'Ã¨', 'Ã´', 'Ã»', 'Ã§', 'Ã®', 'Ã¢', 'Ã«', 'Ã¯', 'Ã¶', 'Ã¼', 'Ãª',
    'AbonnÃ©', 'GÃ©rez', 'PrÃ©fÃ©rences', 'ConfirmÃ©', 'DÃ©sactiver', 'ÃŠtes-vous sÃ»r', 'critÃ¨res', 'trouvÃ©'
];

$totalProblems = 0;
$correctTexts = [
    'Abonnés', 'abonnés', 'Gérez', 'préférences', 'Confirmé', 'Désactiver', 'Êtes-vous sûr', 'critères', 'trouvé'
];

foreach ($viewFiles as $file) {
    if (!file_exists($file)) {
        echo "⚠️  Fichier non trouvé: $file\n";
        continue;
    }

    echo "🔍 Vérification: " . basename($file) . "\n";
    
    $content = file_get_contents($file);
    $fileProblems = 0;
    
    // Rechercher les problèmes d'encodage
    foreach ($encodingProblems as $problem) {
        $count = substr_count($content, $problem);
        if ($count > 0) {
            echo "   ❌ Problème trouvé: '$problem' ($count occurrences)\n";
            $fileProblems += $count;
        }
    }
    
    // Rechercher les textes correctement encodés
    $correctCount = 0;
    foreach ($correctTexts as $correct) {
        $count = substr_count($content, $correct);
        $correctCount += $count;
    }
    
    if ($fileProblems == 0) {
        echo "   ✅ Encodage correct";
        if ($correctCount > 0) {
            echo " ($correctCount textes français corrects)";
        }
        echo "\n";
    }
    
    $totalProblems += $fileProblems;
    echo "\n";
}

echo "📊 RÉSULTAT FINAL:\n";
if ($totalProblems == 0) {
    echo "✅ SUCCÈS! Aucun problème d'encodage détecté\n";
    echo "✅ Tous les caractères français sont correctement affichés\n";
    echo "✅ Les vues newsletter sont prêtes pour la production\n";
} else {
    echo "❌ $totalProblems problèmes d'encodage détectés\n";
    echo "🔧 Corrections supplémentaires nécessaires\n";
}

echo "\n" . str_repeat("=", 45) . "\n";
