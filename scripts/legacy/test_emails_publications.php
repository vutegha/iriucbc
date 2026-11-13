<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Events\PublicationFeaturedCreated;
use App\Models\Publication;

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST SYSTÈME EMAIL PUBLICATIONS ===\n\n";

try {
    // 1. Vérifier qu'une publication en vedette existe
    $publication = Publication::where('en_vedette', true)->where('is_published', true)->first();
    
    if (!$publication) {
        echo "❌ Aucune publication en vedette publiée trouvée.\n";
        echo "Créons une publication de test...\n\n";
        
        // Créer une publication de test
        $publication = Publication::create([
            'titre' => 'Test Publication Newsletter - ' . now()->format('Y-m-d H:i:s'),
            'slug' => 'test-publication-newsletter-' . time(),
            'resume' => 'Ceci est une publication de test pour vérifier le système d\'emails.',
            'en_vedette' => true,
            'is_published' => true,
            'published_at' => now(),
            'published_by' => 1, // Supposons que l'utilisateur ID 1 existe
            'user_id' => 1
        ]);
        
        echo "✅ Publication de test créée (ID: {$publication->id})\n\n";
    } else {
        echo "✅ Publication en vedette trouvée: {$publication->titre} (ID: {$publication->id})\n\n";
    }

    // 2. Vérifier les abonnés
    $subscribers = DB::table('newsletters')
        ->where('actif', true)
        ->get();
    
    echo "📧 Abonnés newsletter actifs: " . $subscribers->count() . "\n";
    foreach ($subscribers as $subscriber) {
        $preferences = json_decode($subscriber->preferences, true) ?? [];
        $publicationPref = $preferences['publications'] ?? false;
        echo "   - {$subscriber->email}: publications=" . ($publicationPref ? 'OUI' : 'NON') . "\n";
    }
    echo "\n";

    // 3. Tester l'événement manuellement
    echo "🔥 Test déclenchement événement PublicationFeaturedCreated...\n";
    
    try {
        event(new PublicationFeaturedCreated($publication));
        echo "✅ Événement déclenché avec succès!\n\n";
        
        // Attendre un peu et vérifier les jobs
        sleep(2);
        $jobsCount = DB::table('jobs')->count();
        echo "📋 Jobs en queue: $jobsCount\n";
        
        if ($jobsCount > 0) {
            echo "📧 Des emails sont en attente d'envoi!\n";
            echo "Pour traiter les jobs: php artisan queue:work\n";
        } else {
            echo "⚠️ Aucun job créé - vérifier la configuration\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur lors du déclenchement: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
    }

    // 4. Vérifier les listeners
    echo "\n🎧 Vérification des listeners:\n";
    $events = app()->make('events');
    $listeners = $events->getListeners(PublicationFeaturedCreated::class);
    
    if (empty($listeners)) {
        echo "❌ Aucun listener enregistré!\n";
        echo "Vérifier EventServiceProvider.php\n";
    } else {
        foreach ($listeners as $listener) {
            if (is_string($listener)) {
                echo "✅ Listener: $listener\n";
            } else if (is_callable($listener)) {
                echo "✅ Listener callable trouvé\n";
            } else {
                echo "✅ Listener: " . get_class($listener) . "\n";
            }
        }
    }

} catch (Exception $e) {
    echo "❌ Erreur critique: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DU TEST ===\n";
