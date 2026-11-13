<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Models\JobOffer;
use Illuminate\Support\Facades\DB;

echo "=== DEBUG JOB OFFERS ===\n";

try {
    // Vérifier si la table existe
    $count = DB::table('job_offers')->count();
    echo "Nombre d'offres d'emploi: $count\n\n";
    
    if ($count > 0) {
        // Récupérer la première offre avec toutes les colonnes
        $firstJob = DB::table('job_offers')->first();
        
        echo "Structure de la première offre:\n";
        foreach ($firstJob as $column => $value) {
            echo "- $column: " . gettype($value) . " => " . (is_string($value) ? substr($value, 0, 100) : $value) . "\n";
        }
        
        echo "\n=== TEST AVEC ELOQUENT ===\n";
        
        // Test avec le modèle Eloquent
        $jobOffer = JobOffer::first();
        
        if ($jobOffer) {
            echo "Title: " . $jobOffer->title . "\n";
            echo "Requirements type: " . gettype($jobOffer->requirements) . "\n";
            echo "Requirements value: ";
            
            if (is_array($jobOffer->requirements)) {
                echo "Array with " . count($jobOffer->requirements) . " items:\n";
                foreach ($jobOffer->requirements as $req) {
                    echo "  - $req\n";
                }
            } else {
                echo $jobOffer->requirements . "\n";
            }
            
            // Test des autres champs cast
            echo "\nCriteria type: " . gettype($jobOffer->criteria) . "\n";
            echo "Criteria value: ";
            if (is_array($jobOffer->criteria)) {
                echo "Array with " . count($jobOffer->criteria) . " items\n";
            } else {
                echo $jobOffer->criteria . "\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DEBUG ===\n";
