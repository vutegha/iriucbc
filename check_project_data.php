<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Projet;

$projet = Projet::where('slug', '20250711-kakjhkafhja')->first();

if ($projet) {
    echo "Projet trouvé: " . $projet->nom . PHP_EOL;
    echo "Budget: " . ($projet->budget ?? 'NULL') . PHP_EOL;
    echo "Bénéficiaires hommes: " . ($projet->beneficiaires_hommes ?? 'NULL') . PHP_EOL;
    echo "Bénéficiaires femmes: " . ($projet->beneficiaires_femmes ?? 'NULL') . PHP_EOL;
    echo "Bénéficiaires total: " . ($projet->beneficiaires_total ?? 'NULL') . PHP_EOL;
    echo "État: " . ($projet->etat ?? 'NULL') . PHP_EOL;
    
    // Vérifier la structure de la table
    echo "\nColonnes disponibles:" . PHP_EOL;
    $columns = Schema::getColumnListing('projets');
    foreach ($columns as $column) {
        echo "- " . $column . PHP_EOL;
    }
} else {
    echo "Projet non trouvé avec le slug: 20250711-kakjhkafhja" . PHP_EOL;
    
    // Lister tous les projets disponibles
    echo "\nProjets disponibles:" . PHP_EOL;
    $projets = Projet::all(['id', 'nom', 'slug']);
    foreach ($projets as $p) {
        echo "- ID: {$p->id}, Nom: {$p->nom}, Slug: {$p->slug}" . PHP_EOL;
    }
}
