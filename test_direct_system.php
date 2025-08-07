<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST DIRECT DU SYSTÈME ===\n\n";

try {
    // 1. Test direct de la classe Mail
    $publication = \App\Models\Publication::where('en_vedette', true)->first();
    $subscriber = \App\Models\Newsletter::where('actif', true)->first();
    
    if (!$publication || !$subscriber) {
        echo "❌ Publication ou abonné manquant\n";
        exit;
    }
    
    echo "Test avec:\n";
    echo "- Publication: {$publication->titre}\n";
    echo "- Abonné: {$subscriber->email}\n\n";
    
    // 2. Test de création de l'objet Mail
    echo "Création de l'objet PublicationNewsletter...\n";
    $mailObject = new \App\Mail\PublicationNewsletter($publication, $subscriber);
    echo "✅ Objet Mail créé\n\n";
    
    // 3. Test du listener directement
    echo "Test du listener SendNewsletterEmail...\n";
    $listener = new \App\Listeners\SendNewsletterEmail();
    $event = new \App\Events\PublicationFeaturedCreated($publication);
    
    echo "Appel listener->handle(\$event)...\n";
    ob_start(); // Capturer toute sortie
    
    try {
        $listener->handle($event);
        $output = ob_get_clean();
        echo "✅ Listener exécuté sans erreur\n";
        if ($output) {
            echo "Sortie: $output\n";
        }
    } catch (Exception $e) {
        ob_get_clean();
        echo "❌ Erreur dans le listener: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
    }
    
    // 4. Vérifier les jobs après appel direct
    $jobsCount = DB::table('jobs')->count();
    echo "Jobs après appel direct: $jobsCount\n\n";
    
    // 5. Test manuel de Mail::queue
    echo "Test direct de Mail::queue...\n";
    try {
        \Illuminate\Support\Facades\Mail::to($subscriber->email)
            ->queue(new \App\Mail\PublicationNewsletter($publication, $subscriber));
        
        $jobsCount = DB::table('jobs')->count();
        echo "✅ Mail::queue exécuté, Jobs: $jobsCount\n";
        
        if ($jobsCount > 0) {
            echo "\n🎉 SUCCÈS! Le système fonctionne.\n";
            echo "Le problème était l'enregistrement des listeners.\n\n";
            
            // Afficher les jobs
            $jobs = DB::table('jobs')->select('id', 'queue', 'payload')->get();
            foreach ($jobs as $job) {
                $payload = json_decode($job->payload, true);
                echo "Job {$job->id}: " . ($payload['displayName'] ?? 'Unknown') . "\n";
            }
        }
    } catch (Exception $e) {
        echo "❌ Erreur Mail::queue: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN TEST DIRECT ===\n";
