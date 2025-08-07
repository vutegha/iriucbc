<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST LISTENER AVEC SEND AU LIEU DE QUEUE ===\n\n";

try {
    echo "📰 Récupération d'une publication...\n";
    $publication = \App\Models\Publication::first();
    
    if (!$publication) {
        echo "❌ Aucune publication trouvée\n";
        exit;
    }
    
    echo "   Publication: " . substr($publication->titre, 0, 40) . "...\n\n";

    echo "👥 Récupération des abonnés actifs...\n";
    $subscribers = \App\Models\Newsletter::active()
        ->whereJsonContains('preferences->publications', true)
        ->get();
    
    echo "   Abonnés trouvés: " . $subscribers->count() . "\n";
    
    foreach ($subscribers as $subscriber) {
        echo "   - " . $subscriber->email . "\n";
    }
    echo "\n";

    if ($subscribers->count() === 0) {
        echo "❌ Aucun abonné actif avec préférence publications\n";
        exit;
    }

    echo "📧 Envoi avec Mail::send (au lieu de queue)...\n";
    
    $emailCount = 0;
    foreach ($subscribers as $subscriber) {
        try {
            echo "   Envoi à {$subscriber->email}... ";
            
            \Illuminate\Support\Facades\Mail::to($subscriber->email)
                ->send(new \App\Mail\PublicationNewsletter($publication, $subscriber));
            
            echo "✅\n";
            $emailCount++;
            
        } catch (\Exception $e) {
            echo "❌ Erreur: " . $e->getMessage() . "\n";
        }
    }

    echo "\n🎉 RÉSULTAT: $emailCount emails envoyés sur " . $subscribers->count() . " abonnés\n";

} catch (\Exception $e) {
    echo "❌ ERREUR GÉNÉRALE: " . $e->getMessage() . "\n";
    echo "📁 Fichier: " . $e->getFile() . "\n";
    echo "📍 Ligne: " . $e->getLine() . "\n";
}

echo "\n=== FIN TEST ===\n";
