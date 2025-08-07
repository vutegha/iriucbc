<?php

require_once 'vendor/autoload.php';

try {
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    $columns = DB::select('DESCRIBE projets');
    echo "Colonnes de bénéficiaires dans la table projets :\n\n";
    
    foreach ($columns as $column) {
        if (strpos($column->Field, 'beneficiaires') !== false) {
            echo "- " . $column->Field . " (" . $column->Type . ")\n";
        }
    }
    
    echo "\n=== Test d'un projet existant ===\n";
    $projet = DB::table('projets')->first();
    if ($projet) {
        echo "Exemple de données d'un projet :\n";
        echo "- ID: " . $projet->id . "\n";
        echo "- Bénéficiaires hommes: " . ($projet->beneficiaires_hommes ?? 'NULL') . "\n";
        echo "- Bénéficiaires femmes: " . ($projet->beneficiaires_femmes ?? 'NULL') . "\n";
        echo "- Bénéficiaires enfants: " . ($projet->beneficiaires_enfants ?? 'NULL') . "\n";
        echo "- Bénéficiaires total: " . ($projet->beneficiaires_total ?? 'NULL') . "\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

?>
