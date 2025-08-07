<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST DIRECT EMAIL SERVICE SEUL ===\n\n";

try {
    $publication = \App\Models\Publication::first();
    $subscriber = \App\Models\Newsletter::first();
    
    echo "📰 Publication: " . substr($publication->titre, 0, 40) . "...\n";
    echo "👤 Subscriber: " . $subscriber->email . "\n\n";

    echo "🔧 Création du DirectEmailService...\n";
    $service = new \App\Services\DirectEmailService();
    echo "✅ Service créé\n\n";

    echo "📧 Test sendPublicationNewsletter...\n";
    $result = $service->sendPublicationNewsletter($publication, $subscriber);
    
    if ($result) {
        echo "✅ Email envoyé via DirectEmailService!\n";
    } else {
        echo "❌ Échec via DirectEmailService\n";
    }

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== FIN TEST ===\n";
