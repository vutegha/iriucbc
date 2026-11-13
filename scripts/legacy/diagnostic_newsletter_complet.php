<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DIAGNOSTIC PUBLICATION NEWSLETTER ===\n\n";

try {
    // 1. Vérifier qu'on a des publications
    echo "📰 VÉRIFICATION PUBLICATIONS:\n";
    $publications = \App\Models\Publication::take(3)->get();
    echo "   Nombre de publications: " . $publications->count() . "\n";
    
    if ($publications->count() > 0) {
        foreach ($publications as $pub) {
            echo "   - ID: {$pub->id}, Titre: " . substr($pub->titre, 0, 40) . "...\n";
        }
    }
    echo "\n";

    // 2. Vérifier les newsletters/abonnés
    echo "👥 VÉRIFICATION NEWSLETTERS:\n";
    $newsletters = \App\Models\Newsletter::all();
    echo "   Nombre d'abonnés: " . $newsletters->count() . "\n";
    
    foreach ($newsletters as $newsletter) {
        $pref = $newsletter->preferences ?? [];
        $pub_pref = isset($pref['publications']) ? ($pref['publications'] ? 'OUI' : 'NON') : 'N/A';
        echo "   - Email: {$newsletter->email}, Publications: $pub_pref\n";
    }
    echo "\n";

    // 3. Test du listener manuellement
    echo "🎯 TEST LISTENER MANUELLEMENT:\n";
    if ($publications->count() > 0 && $newsletters->count() > 0) {
        $publication = $publications->first();
        
        echo "   Publication de test: " . substr($publication->titre, 0, 30) . "...\n";
        
        // Créer l'événement
        $event = new \App\Events\PublicationFeaturedCreated($publication);
        
        // Créer le listener
        $listener = new \App\Listeners\SendNewsletterEmail();
        
        echo "   Exécution du listener...\n";
        $listener->handle($event);
        
        echo "   ✅ Listener exécuté sans erreur\n";
    } else {
        echo "   ❌ Pas de données pour tester\n";
    }
    echo "\n";

    // 4. Test événement complet
    echo "🚀 TEST ÉVÉNEMENT COMPLET:\n";
    if ($publications->count() > 0) {
        $publication = $publications->first();
        
        echo "   Déclenchement de l'événement PublicationFeaturedCreated...\n";
        event(new \App\Events\PublicationFeaturedCreated($publication));
        
        echo "   ✅ Événement déclenché\n";
    }
    echo "\n";

    // 5. Test direct de la classe Mail
    echo "📧 TEST CLASSE MAIL DIRECTEMENT:\n";
    if ($publications->count() > 0 && $newsletters->count() > 0) {
        $publication = $publications->first();
        $newsletter = $newsletters->first();
        
        echo "   Création de l'objet PublicationNewsletter...\n";
        $mail = new \App\Mail\PublicationNewsletter($publication, $newsletter);
        
        echo "   Tentative d'envoi...\n";
        \Illuminate\Support\Facades\Mail::to($newsletter->email)->send($mail);
        
        echo "   ✅ Mail envoyé à {$newsletter->email}\n";
    }

} catch (\Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "📁 Fichier: " . $e->getFile() . "\n";
    echo "📍 Ligne: " . $e->getLine() . "\n";
    echo "🔍 Trace (première partie):\n";
    $trace = explode("\n", $e->getTraceAsString());
    foreach (array_slice($trace, 0, 5) as $line) {
        echo "   " . $line . "\n";
    }
}

echo "\n=== FIN DIAGNOSTIC ===\n";
