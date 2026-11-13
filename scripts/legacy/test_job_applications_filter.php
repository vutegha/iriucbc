<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test du système de filtrage des candidatures ===\n\n";

try {
    // Trouver une offre d'emploi avec un slug
    $jobOffer = App\Models\JobOffer::first();
    if ($jobOffer) {
        echo "✅ Offre trouvée:\n";
        echo "   Titre: {$jobOffer->title}\n";
        echo "   ID: {$jobOffer->id}\n";
        echo "   Slug: {$jobOffer->slug}\n\n";
        
        // Simuler la requête avec slug
        $request = new \Illuminate\Http\Request(['job_offer' => $jobOffer->slug]);
        
        // Test de la logique de filtrage
        if (is_numeric($jobOffer->slug)) {
            echo "   Type: ID numérique\n";
        } else {
            echo "   Type: Slug (chaîne)\n";
            $foundOffer = App\Models\JobOffer::where('slug', $jobOffer->slug)->first();
            echo "   ✅ Offre trouvée par slug: " . ($foundOffer ? 'Oui' : 'Non') . "\n";
        }
        
        // Compter les candidatures pour cette offre
        $applicationsCount = App\Models\JobApplication::where('job_offer_id', $jobOffer->id)->count();
        echo "   Candidatures: {$applicationsCount}\n";
        
    } else {
        echo "❌ Aucune offre d'emploi trouvée\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Test terminé ===\n";
