<?php

// Test rapide de la page admin newsletter
echo "Test de la page admin newsletter...\n";

// Vérification de la structure des fichiers
$adminController = __DIR__ . '/app/Http/Controllers/Admin/NewsletterController.php';
$adminView = __DIR__ . '/resources/views/admin/newsletter/index.blade.php';

if (file_exists($adminController)) {
    echo "✓ Contrôleur Admin Newsletter trouvé\n";
} else {
    echo "✗ Contrôleur Admin Newsletter manquant\n";
}

if (file_exists($adminView)) {
    echo "✓ Vue Admin Newsletter trouvée\n";
    
    // Vérifier l'encodage
    $content = file_get_contents($adminView);
    if (strpos($content, 'Ã') !== false) {
        echo "✗ Problèmes d'encodage détectés dans la vue\n";
    } else {
        echo "✓ Encodage de la vue OK\n";
    }
    
    // Vérifier la structure Blade
    if (strpos($content, '@extends') !== false && strpos($content, '@section') !== false) {
        echo "✓ Structure Blade correcte\n";
    } else {
        echo "✗ Structure Blade incorrecte\n";
    }
} else {
    echo "✗ Vue Admin Newsletter manquante\n";
}

echo "\nTest terminé.\n";
