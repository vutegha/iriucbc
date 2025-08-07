<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST SYSTÈME EMAIL NEWSLETTER ===\n\n";

try {
    // 1. Test email simple avec Mail::raw
    echo "📬 TEST 1 - Email simple:\n";
    $testEmail = 's.vutegha@gmail.com';
    
    \Illuminate\Support\Facades\Mail::raw('Test email simple depuis diagnostic', function ($message) use ($testEmail) {
        $message->to($testEmail)
                ->subject('Test Simple Email IRI-UCBC - ' . now())
                ->from(config('mail.from.address'), config('mail.from.name'));
    });
    
    echo "   ✅ Email simple envoyé à $testEmail\n\n";

    // 2. Test PublicationNewsletter avec données réelles
    echo "📰 TEST 2 - PublicationNewsletter:\n";
    
    $publication = \App\Models\Publication::first();
    $subscriber = \App\Models\Newsletter::where('email', $testEmail)->first();
    
    if (!$publication) {
        echo "   ❌ Aucune publication trouvée\n";
    } else {
        echo "   📰 Publication trouvée: " . substr($publication->titre, 0, 50) . "...\n";
    }
    
    if (!$subscriber) {
        echo "   ❌ Subscriber non trouvé pour $testEmail\n";
    } else {
        echo "   👤 Subscriber trouvé: $subscriber->email\n";
        echo "   📋 Préférences: " . json_encode($subscriber->preferences) . "\n";
    }
    
    if ($publication && $subscriber) {
        try {
            $mailObject = new \App\Mail\PublicationNewsletter($publication, $subscriber);
            \Illuminate\Support\Facades\Mail::to($testEmail)->send($mailObject);
            echo "   ✅ PublicationNewsletter envoyé avec succès\n\n";
        } catch (\Exception $e) {
            echo "   ❌ Erreur envoi PublicationNewsletter: " . $e->getMessage() . "\n";
            echo "   📝 Ligne: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
        }
    }

    // 3. Test du listener SendNewsletterEmail
    echo "📡 TEST 3 - Listener SendNewsletterEmail:\n";
    
    if ($publication) {
        try {
            // Créer un événement et déclencher le listener
            $event = new \App\Events\PublicationFeaturedCreated($publication);
            $listener = new \App\Listeners\SendNewsletterEmail();
            
            echo "   🎯 Tentative d'exécution du listener...\n";
            $listener->handle($event);
            echo "   ✅ Listener exécuté sans erreur\n\n";
            
        } catch (\Exception $e) {
            echo "   ❌ Erreur dans le listener: " . $e->getMessage() . "\n";
            echo "   📝 Ligne: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
        }
    }

    // 4. Test complet avec événement
    echo "🚀 TEST 4 - Événement complet:\n";
    
    if ($publication) {
        try {
            // Déclencher l'événement comme le fait le controller
            event(new \App\Events\PublicationFeaturedCreated($publication));
            echo "   ✅ Événement PublicationFeaturedCreated déclenché\n\n";
            
        } catch (\Exception $e) {
            echo "   ❌ Erreur événement: " . $e->getMessage() . "\n\n";
        }
    }

    // 5. Vérifier les newsletters actives
    echo "👥 NEWSLETTERS ACTIVES:\n";
    $newsletters = \App\Models\Newsletter::all();
    foreach ($newsletters as $newsletter) {
        $preferences = $newsletter->preferences ?? [];
        $publications = isset($preferences['publications']) ? $preferences['publications'] : 'N/A';
        echo "   - $newsletter->email (publications: $publications)\n";
    }
    echo "\n";

} catch (\Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "📝 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "🔍 Trace: " . substr($e->getTraceAsString(), 0, 1000) . "\n";
}

echo "=== FIN TEST ===\n";
