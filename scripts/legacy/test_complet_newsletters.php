<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST COMPLET TOUS LES TYPES DE NEWSLETTER ===\n\n";

try {
    // 1. Test Publications
    echo "📚 TEST PUBLICATIONS:\n";
    $publications = \App\Models\Publication::take(1)->get();
    if ($publications->count() > 0) {
        $publication = $publications->first();
        echo "   Publication trouvée: " . substr($publication->titre, 0, 40) . "...\n";
        
        $event = new \App\Events\PublicationFeaturedCreated($publication);
        echo "   Événement créé: " . get_class($event) . "\n";
    } else {
        echo "   ❌ Aucune publication trouvée\n";
    }

    // 2. Test Actualités
    echo "\n📰 TEST ACTUALITÉS:\n";
    $actualites = \App\Models\Actualite::take(1)->get();
    if ($actualites->count() > 0) {
        $actualite = $actualites->first();
        echo "   Actualité trouvée: " . substr($actualite->titre, 0, 40) . "...\n";
        
        $event = new \App\Events\ActualiteFeaturedCreated($actualite);
        echo "   Événement créé: " . get_class($event) . "\n";
    } else {
        echo "   ❌ Aucune actualité trouvée\n";
    }

    // 3. Test Projets
    echo "\n🚀 TEST PROJETS:\n";
    $projets = \App\Models\Projet::take(1)->get();
    if ($projets->count() > 0) {
        $projet = $projets->first();
        echo "   Projet trouvé: " . substr($projet->nom, 0, 40) . "...\n";
        
        $event = new \App\Events\ProjectCreated($projet);
        echo "   Événement créé: " . get_class($event) . "\n";
    } else {
        echo "   ❌ Aucun projet trouvé\n";
    }

    // 4. Test Rapports
    echo "\n📊 TEST RAPPORTS:\n";
    $rapports = \App\Models\Rapport::take(1)->get();
    if ($rapports->count() > 0) {
        $rapport = $rapports->first();
        echo "   Rapport trouvé: " . substr($rapport->titre, 0, 40) . "...\n";
        
        $event = new \App\Events\RapportCreated($rapport);
        echo "   Événement créé: " . get_class($event) . "\n";
    } else {
        echo "   ❌ Aucun rapport trouvé\n";
    }

    // 5. Test DirectEmailService pour chaque type
    echo "\n🔧 TEST DIRECTEMAILSERVICE:\n";
    $service = new \App\Services\DirectEmailService();
    $subscriber = \App\Models\Newsletter::first();
    
    if ($subscriber) {
        echo "   Subscriber test: " . $subscriber->email . "\n";
        
        if ($publications->count() > 0) {
            echo "   Test publication: ";
            $result = $service->sendPublicationNewsletter($publications->first(), $subscriber);
            echo $result ? "✅" : "❌";
            echo "\n";
        }
        
        if ($actualites->count() > 0) {
            echo "   Test actualité: ";
            $result = $service->sendActualiteNewsletter($actualites->first(), $subscriber);
            echo $result ? "✅" : "❌";
            echo "\n";
        }
        
        if ($projets->count() > 0) {
            echo "   Test projet: ";
            $result = $service->sendProjectNewsletter($projets->first(), $subscriber);
            echo $result ? "✅" : "❌";
            echo "\n";
        }
        
        if ($rapports->count() > 0) {
            echo "   Test rapport: ";
            $result = $service->sendRapportNewsletter($rapports->first(), $subscriber);
            echo $result ? "✅" : "❌";
            echo "\n";
        }
    } else {
        echo "   ❌ Aucun subscriber trouvé\n";
    }

    // 6. Test préférences subscribers
    echo "\n👥 PRÉFÉRENCES SUBSCRIBERS:\n";
    $newsletters = \App\Models\Newsletter::all();
    foreach ($newsletters as $newsletter) {
        $prefs = $newsletter->preferences ?? [];
        echo "   - {$newsletter->email}:\n";
        echo "     Publications: " . (isset($prefs['publications']) && $prefs['publications'] ? 'OUI' : 'NON') . "\n";
        echo "     Actualités: " . (isset($prefs['actualites']) && $prefs['actualites'] ? 'OUI' : 'NON') . "\n";
        echo "     Projets: " . (isset($prefs['projets']) && $prefs['projets'] ? 'OUI' : 'NON') . "\n";
        echo "     Rapports: " . (isset($prefs['rapports']) && $prefs['rapports'] ? 'OUI' : 'NON') . "\n";
    }

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== FIN TEST COMPLET ===\n";
