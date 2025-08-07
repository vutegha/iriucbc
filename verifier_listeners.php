<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VÉRIFICATION LISTENERS ===\n\n";

try {
    $events = app()->make('events');
    $listeners = $events->getListeners(\App\Events\PublicationFeaturedCreated::class);
    
    echo "Listeners pour PublicationFeaturedCreated:\n";
    
    if (empty($listeners)) {
        echo "❌ AUCUN LISTENER TROUVÉ!\n\n";
        
        echo "Vérification de EventServiceProvider:\n";
        $provider = app(\App\Providers\EventServiceProvider::class);
        
        // Réflexion pour accéder à la propriété protégée
        $reflection = new \ReflectionClass($provider);
        $listenProperty = $reflection->getProperty('listen');
        $listenProperty->setAccessible(true);
        $listenArray = $listenProperty->getValue($provider);
        
        echo "Configuration dans \$listen:\n";
        foreach ($listenArray as $event => $eventListeners) {
            echo "  $event => [" . implode(', ', $eventListeners) . "]\n";
        }
        
        echo "\n❓ Les listeners sont configurés mais pas chargés.\n";
        echo "Solution: Enregistrer manuellement les listeners\n";
        
    } else {
        echo "✅ Listeners trouvés:\n";
        foreach ($listeners as $listener) {
            if (is_string($listener)) {
                echo "  - $listener\n";
            } else {
                echo "  - " . get_class($listener) . "\n";
            }
        }
    }
    
    // Test direct du listener
    echo "\n=== TEST DIRECT DU LISTENER ===\n";
    
    $publication = \App\Models\Publication::first();
    if ($publication) {
        $listener = new \App\Listeners\SendNewsletterEmail();
        $event = new \App\Events\PublicationFeaturedCreated($publication);
        
        echo "Test appel direct du listener...\n";
        $listener->handle($event);
        echo "✅ Listener appelé directement!\n";
        
        // Vérifier les jobs créés
        $jobsCount = DB::table('jobs')->count();
        echo "Jobs créés: $jobsCount\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN VÉRIFICATION ===\n";
