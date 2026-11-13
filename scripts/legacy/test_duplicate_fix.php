<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\JobOffer;

echo "Test de duplication d'offre d'emploi...\n";

try {
    // Trouvons une offre existante
    $existingOffer = JobOffer::first();
    
    if (!$existingOffer) {
        echo "❌ Aucune offre d'emploi trouvée pour le test\n";
        exit(1);
    }
    
    echo "✅ Offre trouvée: {$existingOffer->title}\n";
    
    // Test de duplication
    $newJobOffer = $existingOffer->replicate();
    $newJobOffer->title = $existingOffer->title . ' (Test Copie)';
    $newJobOffer->slug = null; // Will be auto-generated
    $newJobOffer->status = 'draft';
    $newJobOffer->application_deadline = now()->addMonths(1); // Utilisation de application_deadline
    $newJobOffer->applications_count = 0;
    $newJobOffer->views_count = 0;
    
    // Tentative de sauvegarde
    $newJobOffer->save();
    
    echo "✅ Duplication réussie! ID: {$newJobOffer->id}\n";
    echo "✅ Nouvelle deadline: {$newJobOffer->application_deadline->format('Y-m-d')}\n";
    
    // Nettoyage - suppression de l'offre de test
    $newJobOffer->delete();
    echo "✅ Offre de test supprimée\n";
    
    echo "\n🎉 Test de duplication réussi - Erreur 'deadline' corrigée!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
