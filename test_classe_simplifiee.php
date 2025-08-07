<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST AVEC CLASSE SIMPLIFIÉE ===\n\n";

try {
    $publication = \App\Models\Publication::first();
    $subscriber = \App\Models\Newsletter::where('email', 's.vutegha@gmail.com')->first();
    
    if (!$publication || !$subscriber) {
        echo "❌ Données manquantes\n";
        exit;
    }
    
    echo "📰 Publication: " . substr($publication->titre, 0, 40) . "...\n";
    echo "👤 Subscriber: " . $subscriber->email . "\n\n";

    echo "📧 Test avec PublicationNewsletterSimple...\n";
    
    $startTime = microtime(true);
    
    // Utiliser la nouvelle classe simplifiée
    \Illuminate\Support\Facades\Mail::to($subscriber->email)
        ->send(new \App\Mail\PublicationNewsletterSimple($publication, $subscriber));
    
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime), 2);
    
    echo "✅ Email envoyé en {$duration}s avec la classe simplifiée!\n\n";

    // Test du listener avec la classe modifiée
    echo "🎯 Test du listener modifié...\n";
    
    $event = new \App\Events\PublicationFeaturedCreated($publication);
    $listener = new \App\Listeners\SendNewsletterEmail();
    
    $startTime = microtime(true);
    $listener->handle($event);
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime), 2);
    
    echo "✅ Listener exécuté en {$duration}s!\n";

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    // Afficher une partie de la trace
    $trace = explode("\n", $e->getTraceAsString());
    echo "🔍 Trace:\n";
    foreach (array_slice($trace, 0, 3) as $line) {
        echo "   " . $line . "\n";
    }
}

echo "\n=== FIN TEST ===\n";
