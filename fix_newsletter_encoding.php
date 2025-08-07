<?php

/**
 * CORRECTION AUTOMATIQUE DES PROBLÈMES D'ENCODAGE
 * DANS LES VUES NEWSLETTER
 */

echo "=== CORRECTION ENCODAGE VUES NEWSLETTER ===\n\n";

// Mappings des caractères mal encodés vers les bons caractères
$encodingFixes = [
    'Ã©' => 'é',
    'Ã ' => 'à', 
    'ÃŠ' => 'Ê',
    'Ã¨' => 'è',
    'Ã´' => 'ô',
    'Ã»' => 'û',
    'Ã§' => 'ç',
    'Ã®' => 'î',
    'Ã¢' => 'â',
    'Ã«' => 'ë',
    'Ã¯' => 'ï',
    'Ã¶' => 'ö',
    'Ã¼' => 'ü',
    'Ãª' => 'ê',
    'AbonnÃ©' => 'Abonné',
    'AbonnÃ©s' => 'Abonnés',
    'abonnÃ©' => 'abonné',
    'abonnÃ©s' => 'abonnés',
    'GÃ©rez' => 'Gérez',
    'PrÃ©fÃ©rences' => 'Préférences',
    'prÃ©fÃ©rences' => 'préférences',
    'prÃ©fÃ©rence' => 'préférence',
    'ConfirmÃ©' => 'Confirmé',
    'ConfirmÃ©s' => 'Confirmés',
    'DÃ©sactiver' => 'Désactiver',
    'ÃŠtes-vous sÃ»r' => 'Êtes-vous sûr',
    'critÃ¨res' => 'critères',
    'trouvÃ©' => 'trouvé'
];

// Fichiers à traiter
$viewFiles = [
    'resources/views/admin/newsletter/index.blade.php',
    'resources/views/admin/newsletter/create.blade.php', 
    'resources/views/admin/newsletter/edit.blade.php',
    'resources/views/admin/newsletter/show.blade.php',
    'resources/views/admin/newsletter/_form.blade.php'
];

$totalChanges = 0;

foreach ($viewFiles as $file) {
    if (!file_exists($file)) {
        echo "⚠️  Fichier non trouvé: $file\n";
        continue;
    }

    echo "🔧 Traitement: $file\n";
    
    $content = file_get_contents($file);
    $originalContent = $content;
    $fileChanges = 0;
    
    foreach ($encodingFixes as $wrong => $correct) {
        $count = substr_count($content, $wrong);
        if ($count > 0) {
            $content = str_replace($wrong, $correct, $content);
            $fileChanges += $count;
            echo "   ✅ '$wrong' → '$correct' ($count occurrences)\n";
        }
    }
    
    if ($fileChanges > 0) {
        file_put_contents($file, $content);
        echo "   📝 $fileChanges corrections appliquées\n";
        $totalChanges += $fileChanges;
    } else {
        echo "   ✅ Aucune correction nécessaire\n";
    }
    
    echo "\n";
}

echo "🎉 RÉSULTAT FINAL:\n";
echo "Total des corrections: $totalChanges\n";
echo "Fichiers traités: " . count($viewFiles) . "\n";

if ($totalChanges > 0) {
    echo "\n✅ Tous les problèmes d'encodage ont été corrigés!\n";
    echo "Les vues newsletter affichent maintenant correctement les caractères accentués.\n";
} else {
    echo "\n✅ Aucune correction nécessaire - l'encodage est déjà correct!\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
