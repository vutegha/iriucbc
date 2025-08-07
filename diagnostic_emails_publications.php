<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DIAGNOSTIC SYSTÈME EMAIL PUBLICATIONS ===\n\n";

try {
    // 1. Vérifier la configuration email
    echo "1. Configuration Email:\n";
    echo "   - MAIL_MAILER: " . config('mail.default') . "\n";
    echo "   - MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
    echo "   - MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
    echo "   - MAIL_FROM: " . config('mail.from.address') . "\n\n";

    // 2. Vérifier la configuration des queues
    echo "2. Configuration Queue:\n";
    echo "   - QUEUE_CONNECTION: " . config('queue.default') . "\n";
    echo "   - Database Connection: " . config('database.default') . "\n\n";

    // 3. Vérifier les tables nécessaires
    echo "3. Vérification des tables:\n";
    $tables = ['jobs', 'failed_jobs', 'newsletters', 'publications'];
    foreach ($tables as $table) {
        try {
            $count = DB::table($table)->count();
            echo "   ✓ Table '$table' existe ($count enregistrements)\n";
        } catch (Exception $e) {
            echo "   ✗ Table '$table' manquante: " . $e->getMessage() . "\n";
        }
    }
    echo "\n";

    // 4. Vérifier les abonnés newsletter
    echo "4. Abonnés Newsletter:\n";
    try {
        $totalSubscribers = DB::table('newsletters')->where('is_active', true)->count();
        $publicationSubscribers = DB::table('newsletters')
            ->where('is_active', true)
            ->where('preferences->publications', true)
            ->count();
        
        echo "   - Total abonnés actifs: $totalSubscribers\n";
        echo "   - Abonnés publications: $publicationSubscribers\n\n";
    } catch (Exception $e) {
        echo "   ✗ Erreur: " . $e->getMessage() . "\n\n";
    }

    // 5. Vérifier les publications en vedette
    echo "5. Publications en vedette:\n";
    try {
        $featuredPublications = DB::table('publications')
            ->where('is_featured', true)
            ->where('is_published', true)
            ->count();
        echo "   - Publications en vedette publiées: $featuredPublications\n\n";
    } catch (Exception $e) {
        echo "   ✗ Erreur: " . $e->getMessage() . "\n\n";
    }

    // 6. Vérifier les événements et listeners
    echo "6. Configuration Événements:\n";
    $events = app()->make('events');
    $hasListener = $events->hasListeners(\App\Events\PublicationFeaturedCreated::class);
    echo "   - PublicationFeaturedCreated a des listeners: " . ($hasListener ? "✓ Oui" : "✗ Non") . "\n";
    
    if ($hasListener) {
        $listeners = $events->getListeners(\App\Events\PublicationFeaturedCreated::class);
        foreach ($listeners as $listener) {
            echo "     -> " . get_class($listener) . "\n";
        }
    }
    echo "\n";

    // 7. Test de connexion email
    echo "7. Test connexion SMTP:\n";
    try {
        $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
            config('mail.mailers.smtp.host'),
            config('mail.mailers.smtp.port'),
            config('mail.mailers.smtp.encryption') === 'ssl'
        );
        $transport->setUsername(config('mail.mailers.smtp.username'));
        $transport->setPassword(config('mail.mailers.smtp.password'));
        
        echo "   ✓ Configuration SMTP semble correcte\n";
    } catch (Exception $e) {
        echo "   ✗ Problème SMTP: " . $e->getMessage() . "\n";
    }
    echo "\n";

    // 8. Vérifier les jobs en attente
    echo "8. Jobs en attente:\n";
    try {
        $pendingJobs = DB::table('jobs')->count();
        echo "   - Jobs en attente: $pendingJobs\n";
        
        if ($pendingJobs > 0) {
            $jobsByPayload = DB::table('jobs')
                ->selectRaw('LEFT(payload, 100) as payload_preview, COUNT(*) as count')
                ->groupBy('payload_preview')
                ->get();
            
            foreach ($jobsByPayload as $job) {
                echo "     -> $job->count job(s): " . substr($job->payload_preview, 0, 80) . "...\n";
            }
        }
        
        $failedJobs = DB::table('failed_jobs')->count();
        echo "   - Jobs échoués: $failedJobs\n";
    } catch (Exception $e) {
        echo "   ✗ Erreur: " . $e->getMessage() . "\n";
    }
    echo "\n";

    // 9. Recommandations
    echo "9. Recommandations:\n";
    
    if (!$hasListener) {
        echo "   ⚠ Vérifier que les listeners sont bien enregistrés dans EventServiceProvider\n";
    }
    
    try {
        $pendingJobs = DB::table('jobs')->count();
        if ($pendingJobs > 0) {
            echo "   ⚠ Il y a $pendingJobs jobs en attente. Lancer: php artisan queue:work\n";
        } else {
            echo "   ✓ Aucun job en attente\n";
        }
    } catch (Exception $e) {
        echo "   ⚠ Créer les tables manquantes: php artisan migrate\n";
    }

} catch (Exception $e) {
    echo "Erreur critique: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DU DIAGNOSTIC ===\n";
