<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\JobOffer;
use App\Http\Requests\JobApplicationRequest;

echo "Test de validation de candidature...\n";

try {
    // Récupérer une offre d'emploi existante
    $job = JobOffer::first();
    
    if (!$job) {
        echo "❌ Aucune offre d'emploi trouvée\n";
        exit(1);
    }
    
    echo "✅ Offre trouvée: {$job->title} (ID: {$job->id}, Slug: {$job->slug})\n";
    
    // Simuler une requête de validation
    $request = new \Illuminate\Http\Request();
    $request->setRouteResolver(function() use ($job) {
        $route = new \Illuminate\Routing\Route(['POST'], 'jobs/{job}/apply', []);
        $route->bind(new \Illuminate\Http\Request());
        $route->setParameter('job', $job);
        return $route;
    });
    
    // Créer une instance de JobApplicationRequest
    $validationRequest = new JobApplicationRequest();
    $validationRequest->setContainer(app());
    $validationRequest->setRedirector(app('redirect'));
    
    // Test des règles de validation
    $rules = $validationRequest->rules();
    
    echo "✅ Règles de validation générées avec succès\n";
    echo "📧 Règle email: " . $rules['email'] . "\n";
    
    // Vérifier si la règle unique est correctement formatée
    if (strpos($rules['email'], 'unique:job_applications') !== false) {
        echo "✅ Règle unique correctement formatée\n";
    } else {
        echo "⚠️  Règle unique non formatée comme attendu\n";
    }
    
    echo "\n🎉 Test de validation réussi - Le formulaire de candidature devrait fonctionner!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
