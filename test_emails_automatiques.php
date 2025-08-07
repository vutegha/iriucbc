<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST EMAILS AUTOMATIQUES (SYNC) ===\n\n";

try {
    // 1. Vérifier la configuration
    echo "Configuration queue: " . config('queue.default') . "\n\n";
    
    // 2. Créer une publication de test
    $publication = \App\Models\Publication::create([
        'titre' => 'Test Email Automatique - ' . now()->format('H:i:s'),
        'slug' => 'test-email-auto-' . time(),
        'resume' => 'Test envoi automatique d\'emails.',
        'en_vedette' => false,
        'is_published' => false,
        'user_id' => 1
    ]);
    
    echo "✅ Publication créée (ID: {$publication->id})\n";
    echo "   Titre: {$publication->titre}\n\n";

    // 3. Simuler l'authentification et publier
    $user = \App\Models\User::first();
    auth()->login($user);
    
    echo "🚀 Publication de la publication...\n";
    
    // Appliquer les mêmes modifications que le controller
    $publication->update([
        'is_published' => true,
        'published_at' => now(),
        'published_by' => auth()->id(),
        'moderation_comment' => 'Test automatique'
    ]);
    
    echo "✅ Publication mise à jour\n";
    
    // Déclencher l'événement
    echo "📧 Déclenchement de l'événement newsletter...\n";
    \App\Events\PublicationFeaturedCreated::dispatch($publication);
    
    echo "✅ Événement déclenché\n";
    
    // Avec QUEUE_CONNECTION=sync, les emails devraient être envoyés immédiatement
    echo "\n🎯 Avec QUEUE_CONNECTION=sync, les emails sont envoyés IMMÉDIATEMENT\n";
    echo "Pas besoin de queue worker séparé !\n\n";
    
    // Vérifier qu'aucun job n'est resté en attente
    $jobsRestants = DB::table('jobs')->count();
    echo "📋 Jobs restants en attente: $jobsRestants\n";
    
    if ($jobsRestants == 0) {
        echo "🎉 PARFAIT ! Aucun job en attente = emails envoyés automatiquement\n";
    } else {
        echo "⚠️ Il reste des jobs, vérifier la configuration\n";
    }

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN TEST AUTOMATIQUE ===\n";
