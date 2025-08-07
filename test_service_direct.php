<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST SERVICE EMAIL DIRECT ===\n\n";

try {
    $publication = \App\Models\Publication::first();
    $subscriber = \App\Models\Newsletter::where('email', 's.vutegha@gmail.com')->first();
    
    if (!$publication || !$subscriber) {
        echo "❌ Données manquantes\n";
        exit;
    }
    
    echo "📰 Publication: " . substr($publication->titre, 0, 40) . "...\n";
    echo "👤 Subscriber: " . $subscriber->email . "\n\n";

    echo "📧 Test service email direct...\n";
    
    $directService = new \App\Services\DirectEmailService();
    
    $startTime = microtime(true);
    $result = $directService->sendPublicationNewsletter($publication, $subscriber);
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime), 2);
    
    if ($result) {
        echo "✅ Email direct envoyé en {$duration}s!\n\n";
    } else {
        echo "❌ Échec email direct\n\n";
    }

    echo "🎯 Test listener hybride...\n";
    
    $event = new \App\Events\PublicationFeaturedCreated($publication);
    $listener = new \App\Listeners\SendNewsletterEmail();
    
    $startTime = microtime(true);
    $listener->handle($event);
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime), 2);
    
    echo "✅ Listener hybride exécuté en {$duration}s!\n";

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== FIN TEST ===\n";
