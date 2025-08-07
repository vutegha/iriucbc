<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUG ACTION PUBLISH RÉELLE ===\n\n";

try {
    // 1. Trouver une publication non publiée pour test
    $publication = \App\Models\Publication::where('is_published', false)->first();
    
    if (!$publication) {
        // Créer une publication de test
        $publication = \App\Models\Publication::create([
            'titre' => 'Test Publication Debug - ' . now()->format('Y-m-d H:i:s'),
            'slug' => 'test-publication-debug-' . time(),
            'resume' => 'Publication de test pour débugger le système d\'emails.',
            'en_vedette' => false,
            'is_published' => false,
            'user_id' => 1
        ]);
        echo "✅ Publication de test créée (ID: {$publication->id})\n";
    } else {
        echo "✅ Publication trouvée: {$publication->titre} (ID: {$publication->id})\n";
    }
    
    echo "   - Statut avant: is_published = " . ($publication->is_published ? 'true' : 'false') . "\n";
    echo "   - En vedette: " . ($publication->en_vedette ? 'true' : 'false') . "\n\n";

    // 2. Vérifier les jobs avant
    $jobsBefore = DB::table('jobs')->count();
    echo "📋 Jobs avant action: $jobsBefore\n\n";

    // 3. Simuler exactement ce que fait le controller publish()
    echo "🚀 Simulation de l'action publish() du controller...\n";
    
    // Simuler l'authentification (nécessaire pour auth()->id())
    $user = \App\Models\User::first();
    if ($user) {
        auth()->login($user);
        echo "✅ Utilisateur authentifié: {$user->name} (ID: {$user->id})\n";
    }
    
    // Exécuter exactement le même code que le controller
    $publication->update([
        'is_published' => true,
        'published_at' => now(),
        'published_by' => auth()->id(),
        'moderation_comment' => 'Test depuis script debug'
    ]);
    
    echo "✅ Publication mise à jour\n";
    echo "   - is_published: " . ($publication->fresh()->is_published ? 'true' : 'false') . "\n";
    echo "   - published_at: " . ($publication->fresh()->published_at ? $publication->fresh()->published_at->format('Y-m-d H:i:s') : 'null') . "\n";
    echo "   - published_by: " . ($publication->fresh()->published_by ?: 'null') . "\n\n";

    // Déclencher l'événement exactement comme le controller
    try {
        \App\Events\PublicationFeaturedCreated::dispatch($publication);
        echo "✅ Événement PublicationFeaturedCreated déclenché\n";
        
        \Illuminate\Support\Facades\Log::info('TEST DEBUG - Événement déclenché depuis script', [
            'publication_id' => $publication->id,
            'titre' => $publication->titre,
            'en_vedette' => $publication->en_vedette,
            'a_la_une' => $publication->a_la_une
        ]);
        
    } catch (\Exception $e) {
        echo "❌ Erreur lors du déclenchement: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
    }

    // 4. Vérifier les jobs après
    sleep(2);
    $jobsAfter = DB::table('jobs')->count();
    echo "\n📋 Jobs après action: $jobsAfter\n";
    $newJobs = $jobsAfter - $jobsBefore;
    echo "📧 Nouveaux jobs créés: $newJobs\n\n";

    // 5. Vérifier les abonnés qui devraient recevoir l'email
    $subscribers = \App\Models\Newsletter::active()
        ->whereJsonContains('preferences->publications', true)
        ->get();
    
    echo "👥 Abonnés avec préférence publications: {$subscribers->count()}\n";
    foreach ($subscribers as $subscriber) {
        echo "   - {$subscriber->email}\n";
    }

    if ($newJobs > 0) {
        echo "\n🎉 SUCCÈS! Le système fonctionne depuis le script de debug\n";
        echo "Le problème est peut-être dans l'interface web spécifiquement.\n\n";
        
        echo "📝 Vérifications supplémentaires à faire:\n";
        echo "1. Vérifier les logs Laravel: storage/logs/laravel.log\n";
        echo "2. Vérifier les erreurs JavaScript dans la console du navigateur\n";
        echo "3. Vérifier que la route publish est bien appelée (F12 > Network)\n";
        echo "4. Tester avec une publication ayant en_vedette = true\n";
    } else {
        echo "\n❌ Aucun job créé même depuis le script\n";
        echo "Le problème est dans la configuration des listeners.\n";
    }

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DEBUG ===\n";
