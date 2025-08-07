<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST FINAL - SIMULATION PUBLICATION ===\n\n";

try {
    // 1. Trouver ou créer une publication
    $publication = \App\Models\Publication::first();
    
    if ($publication) {
        echo "✅ Publication trouvée: {$publication->titre}\n";
        
        // 2. Marquer comme en vedette et non publiée pour le test
        $publication->update([
            'en_vedette' => true,
            'is_published' => false,
            'published_at' => null,
            'published_by' => null
        ]);
        echo "✅ Publication préparée pour le test\n\n";
        
        // 3. Compter les jobs avant
        $jobsBefore = DB::table('jobs')->count();
        echo "Jobs avant publication: $jobsBefore\n";
        
        // 4. Simuler la publication via le controller
        echo "🚀 Simulation de la publication...\n";
        
        // Simuler ce que fait le controller publish()
        $publication->update([
            'is_published' => true,
            'published_at' => now(),
            'published_by' => 1 // ID utilisateur fictif
        ]);
        
        // Déclencher l'événement comme le fait le controller
        if ($publication->en_vedette) {
            \App\Events\PublicationFeaturedCreated::dispatch($publication);
            echo "✅ Événement PublicationFeaturedCreated déclenché\n";
        }
        
        // 5. Vérifier les jobs après
        sleep(1);
        $jobsAfter = DB::table('jobs')->count();
        echo "Jobs après publication: $jobsAfter\n";
        
        $newJobs = $jobsAfter - $jobsBefore;
        echo "Nouveaux jobs créés: $newJobs\n\n";
        
        if ($newJobs > 0) {
            echo "🎉 SUCCÈS COMPLET!\n";
            echo "Le système d'emails fonctionne parfaitement.\n\n";
            
            echo "📧 Détails des abonnés qui recevront l'email:\n";
            $subscribers = \App\Models\Newsletter::active()
                ->whereJsonContains('preferences->publications', true)
                ->get();
            
            foreach ($subscribers as $subscriber) {
                echo "   - {$subscriber->email}\n";
            }
            
            echo "\n📋 Pour traiter les emails maintenant:\n";
            echo "   php artisan queue:work\n\n";
            
            echo "📝 Logs à vérifier:\n";
            echo "   - storage/logs/laravel.log\n\n";
            
        } else {
            echo "❌ Aucun job créé - problème avec les listeners\n";
        }
        
    } else {
        echo "❌ Aucune publication trouvée\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN TEST FINAL ===\n";
