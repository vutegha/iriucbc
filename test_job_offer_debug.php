<?php
/**
 * Script de test pour débugger les offres d'emploi
 * À exécuter depuis le terminal pour vérifier la configuration
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\JobOffer;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DE DEBUG JOBOFFER ===\n\n";

// Test 1: Vérifier la structure de la table
echo "1. Structure de la table job_offers:\n";
try {
    $columns = \DB::select("DESCRIBE job_offers");
    foreach ($columns as $column) {
        echo "   {$column->Field} - {$column->Type} - {$column->Null}\n";
    }
} catch (Exception $e) {
    echo "   Erreur: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Vérifier les casts du modèle
echo "2. Casts du modèle JobOffer:\n";
try {
    $jobOffer = new JobOffer();
    $casts = $jobOffer->getCasts();
    foreach ($casts as $field => $cast) {
        echo "   $field => $cast\n";
    }
} catch (Exception $e) {
    echo "   Erreur: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Test de validation JSON
echo "3. Test de validation JSON:\n";
$testCases = [
    '[]',
    '["Exigence 1", "Exigence 2"]',
    'null',
    '',
    '{"invalid": "json"}'
];

foreach ($testCases as $testCase) {
    echo "   Test: '$testCase'\n";
    
    if (empty($testCase) || $testCase === 'null') {
        echo "     -> REJETÉ (vide ou null)\n";
        continue;
    }
    
    $decoded = json_decode($testCase, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "     -> REJETÉ (JSON invalide: " . json_last_error_msg() . ")\n";
        continue;
    }
    
    if (!is_array($decoded)) {
        echo "     -> REJETÉ (pas un array)\n";
        continue;
    }
    
    $filtered = array_filter($decoded, function($item) {
        return is_string($item) && !empty(trim($item));
    });
    
    if (empty($filtered)) {
        echo "     -> REJETÉ (aucun élément valide)\n";
        continue;
    }
    
    echo "     -> ACCEPTÉ (" . count($filtered) . " éléments valides)\n";
}

echo "\n";

// Test 4: Vérifier les dernières offres
echo "4. Dernières offres d'emploi:\n";
try {
    $offers = JobOffer::latest()->take(3)->get(['id', 'title', 'requirements', 'created_at']);
    foreach ($offers as $offer) {
        echo "   ID {$offer->id}: {$offer->title}\n";
        echo "     Requirements: " . (is_array($offer->requirements) ? json_encode($offer->requirements) : $offer->requirements) . "\n";
        echo "     Créé: {$offer->created_at}\n\n";
    }
} catch (Exception $e) {
    echo "   Erreur: " . $e->getMessage() . "\n";
}

echo "=== FIN DU TEST ===\n";
