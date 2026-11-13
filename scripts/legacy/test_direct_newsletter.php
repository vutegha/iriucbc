<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST DIRECT PUBLICATION NEWSLETTER ===\n\n";

try {
    $publication = \App\Models\Publication::first();
    $subscriber = \App\Models\Newsletter::where('email', 's.vutegha@gmail.com')->first();
    
    if (!$publication || !$subscriber) {
        echo "❌ Données manquantes\n";
        exit;
    }
    
    echo "📰 Publication: " . substr($publication->titre, 0, 40) . "...\n";
    echo "👤 Subscriber: " . $subscriber->email . "\n\n";

    echo "📧 Création de l'objet PublicationNewsletter...\n";
    $mail = new \App\Mail\PublicationNewsletter($publication, $subscriber);
    echo "✅ Objet créé\n\n";

    echo "📤 Envoi de l'email...\n";
    
    // Ajouter un timeout PHP
    ini_set('max_execution_time', 30);
    
    $startTime = microtime(true);
    
    \Illuminate\Support\Facades\Mail::to($subscriber->email)->send($mail);
    
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime), 2);
    
    echo "✅ Email envoyé en {$duration}s!\n";

} catch (\Symfony\Component\Routing\Exception\RouteNotFoundException $e) {
    echo "❌ Erreur de route: " . $e->getMessage() . "\n";
    echo "🔧 Il manque une route dans l'application\n";
} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Type: " . get_class($e) . "\n";
    echo "📁 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== FIN TEST ===\n";
