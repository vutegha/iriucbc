<?php
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test des rapports
echo "=== TEST DES RAPPORTS ===\n";

try {
    $rapports = App\Models\Rapport::with('categorie')->get();
    echo "Nombre de rapports trouvés: " . $rapports->count() . "\n\n";
    
    foreach($rapports as $rapport) {
        echo "ID: " . $rapport->id . "\n";
        echo "Titre: " . $rapport->titre . "\n";
        echo "Fichier: " . ($rapport->fichier ?? 'Aucun') . "\n";
        echo "Categorie: " . ($rapport->categorie->nom ?? 'Aucune') . "\n";
        echo "Date creation: " . $rapport->created_at . "\n";
        echo "---\n";
    }
    
    // Test des catégories
    echo "\n=== CATEGORIES ===\n";
    $categories = App\Models\Categorie::all();
    echo "Nombre de catégories: " . $categories->count() . "\n";
    foreach($categories as $cat) {
        echo "- " . $cat->nom . "\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
