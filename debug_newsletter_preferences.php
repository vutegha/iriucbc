<?php

require_once __DIR__ . '/vendor/autoload.php';

// Chargement de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Newsletter;

echo "=== Analyse des Préférences Newsletter ===\n\n";

// Récupérer tous les abonnés
$newsletters = Newsletter::all();

echo "Nombre total d'abonnés: " . $newsletters->count() . "\n";

if ($newsletters->count() > 0) {
    echo "\nAnalyse des préférences:\n";
    echo "------------------------\n";
    
    foreach ($newsletters as $newsletter) {
        echo "Email: " . $newsletter->email . "\n";
        echo "Préférences (raw): " . json_encode($newsletter->getOriginal('preferences')) . "\n";
        echo "Préférences (cast): " . json_encode($newsletter->preferences) . "\n";
        echo "Type: " . gettype($newsletter->preferences) . "\n";
        
        if (is_array($newsletter->preferences)) {
            echo "Nombre d'éléments: " . count($newsletter->preferences) . "\n";
            if (count($newsletter->preferences) > 0) {
                echo "Éléments: " . implode(', ', $newsletter->preferences) . "\n";
            }
        }
        
        echo "---\n";
    }
    
    // Statistiques
    $withPrefs = $newsletters->filter(function($n) {
        return is_array($n->preferences) && count($n->preferences) > 0;
    });
    
    echo "\nStatistiques:\n";
    echo "Abonnés avec préférences: " . $withPrefs->count() . "\n";
    echo "Abonnés sans préférences: " . ($newsletters->count() - $withPrefs->count()) . "\n";
    
} else {
    echo "Aucun abonné dans la base de données.\n";
}

echo "\n=== Fin de l'analyse ===\n";
