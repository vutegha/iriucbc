<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test des routes avec slugs pour les offres d'emploi ===\n\n";

// Test de récupération d'une offre par slug
try {
    $jobOffer = App\Models\JobOffer::first();
    if ($jobOffer) {
        echo "✅ Offre trouvée: {$jobOffer->title}\n";
        echo "   ID: {$jobOffer->id}\n";
        echo "   Slug: {$jobOffer->slug}\n";
        
        // Test de getRouteKeyName
        echo "   Route Key: " . $jobOffer->getRouteKeyName() . "\n";
        
        // Test des méthodes findBySlug
        $foundBySlug = App\Models\JobOffer::findBySlug($jobOffer->slug);
        echo "   ✅ findBySlug fonctionne: " . ($foundBySlug->id === $jobOffer->id ? 'Oui' : 'Non') . "\n";
        
        // Test des URLs
        echo "   URL: " . route('admin.job-offers.show', $jobOffer) . "\n";
        echo "   URL directe avec slug: " . route('admin.job-offers.show', $jobOffer->slug) . "\n";
        
    } else {
        echo "❌ Aucune offre d'emploi trouvée dans la base de données\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
}

echo "\n=== Test terminé ===\n";
