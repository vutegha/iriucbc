<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ENREGISTREMENT MANUEL DES LISTENERS ===\n\n";

try {
    // Enregistrer manuellement les listeners
    $events = app('events');
    
    echo "Enregistrement des listeners...\n";
    
    $events->listen(
        \App\Events\PublicationFeaturedCreated::class,
        \App\Listeners\SendNewsletterEmail::class
    );
    
    $events->listen(
        \App\Events\ActualiteFeaturedCreated::class,
        \App\Listeners\SendNewsletterEmail::class
    );
    
    $events->listen(
        \App\Events\ProjectCreated::class,
        \App\Listeners\SendNewsletterEmail::class
    );
    
    $events->listen(
        \App\Events\RapportCreated::class,
        \App\Listeners\SendNewsletterEmail::class
    );
    
    echo "✅ Listeners enregistrés!\n\n";
    
    // Vérifier que les listeners sont maintenant présents
    $listeners = $events->getListeners(\App\Events\PublicationFeaturedCreated::class);
    echo "Listeners pour PublicationFeaturedCreated: " . count($listeners) . "\n";
    
    // Test avec une publication
    $publication = \App\Models\Publication::where('en_vedette', true)->first();
    
    if ($publication) {
        echo "Test avec publication: {$publication->titre}\n\n";
        
        echo "Déclenchement de l'événement...\n";
        $event = new \App\Events\PublicationFeaturedCreated($publication);
        event($event);
        
        // Attendre et vérifier les jobs
        sleep(1);
        $jobsCount = DB::table('jobs')->count();
        echo "✅ Jobs créés: $jobsCount\n";
        
        if ($jobsCount > 0) {
            echo "\n📧 SUCCÈS! Des emails sont en queue d'envoi.\n";
            echo "Pour traiter les emails: php artisan queue:work\n\n";
            
            // Afficher les détails des jobs
            $jobs = DB::table('jobs')->get();
            foreach ($jobs as $job) {
                $payload = json_decode($job->payload, true);
                $mailClass = $payload['data']['commandName'] ?? 'Unknown';
                echo "Job: $mailClass\n";
            }
        } else {
            echo "❌ Aucun job créé\n";
        }
    } else {
        echo "❌ Aucune publication en vedette trouvée\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN TEST ===\n";
