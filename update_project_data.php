<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Projet;

$projet = Projet::where('slug', '20250711-kakjhkafhja')->first();

if ($projet) {
    $projet->budget = 125000;
    $projet->beneficiaires_hommes = 650;
    $projet->beneficiaires_femmes = 600;
    $projet->beneficiaires_total = 980;
    $projet->date_debut = '2025-07-15';
    $projet->date_fin = '2025-12-15';
    $projet->save();
    
    echo "Données mises à jour pour le projet: " . $projet->nom . PHP_EOL;
    echo "Budget: " . number_format($projet->budget) . " $" . PHP_EOL;
    echo "Bénéficiaires attendus: " . ($projet->beneficiaires_hommes + $projet->beneficiaires_femmes) . PHP_EOL;
    echo "Bénéficiaires atteints: " . $projet->beneficiaires_total . PHP_EOL;
} else {
    echo "Projet non trouvé" . PHP_EOL;
}
