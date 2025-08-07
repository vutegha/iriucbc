<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DIAGNOSTIC COMPLET ENVOI EMAILS ===\n\n";

try {
    // 1. Vérifier la configuration email
    echo "📧 CONFIGURATION EMAIL:\n";
    echo "   - MAIL_MAILER: " . config('mail.default') . "\n";
    echo "   - MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
    echo "   - MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
    echo "   - MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
    echo "   - MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption') . "\n";
    echo "   - MAIL_FROM: " . config('mail.from.address') . "\n\n";

    // 2. Test connexion SMTP directe
    echo "🔌 TEST CONNEXION SMTP:\n";
    try {
        $transport = \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport::create(
            sprintf('%s://%s:%s@%s:%s', 
                config('mail.mailers.smtp.encryption') === 'ssl' ? 'smtps' : 'smtp',
                config('mail.mailers.smtp.username'),
                config('mail.mailers.smtp.password'),
                config('mail.mailers.smtp.host'),
                config('mail.mailers.smtp.port')
            )
        );
        
        echo "   ✅ Configuration SMTP semble valide\n\n";
    } catch (\Exception $e) {
        echo "   ❌ Erreur SMTP: " . $e->getMessage() . "\n\n";
    }

    // 3. Test envoi email simple
    echo "📬 TEST ENVOI EMAIL SIMPLE:\n";
    $testEmail = 's.vutegha@gmail.com'; // Premier abonné
    
    try {
        \Illuminate\Support\Facades\Mail::raw('Test email depuis diagnostic', function ($message) use ($testEmail) {
            $message->to($testEmail)
                    ->subject('Test Email IRI-UCBC - ' . now())
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });
        
        echo "   ✅ Email test envoyé à $testEmail\n";
        echo "   📝 Vérifiez votre boîte mail (y compris spam)\n\n";
        
    } catch (\Exception $e) {
        echo "   ❌ Erreur envoi: " . $e->getMessage() . "\n";
        echo "   📝 Trace: " . substr($e->getTraceAsString(), 0, 500) . "...\n\n";
    }

    // 4. Test classe PublicationNewsletter
    echo "📰 TEST CLASSE PUBLICATIONNEWSLETTER:\n";
    try {
        $publication = \App\Models\Publication::first();
        $subscriber = \App\Models\Newsletter::where('email', $testEmail)->first();
        
        if ($publication && $subscriber) {
            $mailObject = new \App\Mail\PublicationNewsletter($publication, $subscriber);
            
            \Illuminate\Support\Facades\Mail::to($testEmail)->send($mailObject);
            
            echo "   ✅ Email PublicationNewsletter envoyé à $testEmail\n";
            echo "   📰 Publication: " . substr($publication->titre, 0, 50) . "...\n\n";
        } else {
            echo "   ❌ Publication ou subscriber manquant\n\n";
        }
    } catch (\Exception $e) {
        echo "   ❌ Erreur PublicationNewsletter: " . $e->getMessage() . "\n";
        echo "   📝 Trace: " . substr($e->getTraceAsString(), 0, 500) . "...\n\n";
    }

    // 5. Vérifier les failed jobs
    echo "💥 FAILED JOBS:\n";
    try {
        $failedJobs = DB::table('failed_jobs')->count();
        echo "   - Failed jobs: $failedJobs\n";
        
        if ($failedJobs > 0) {
            $lastFailed = DB::table('failed_jobs')->latest('failed_at')->first();
            echo "   - Dernière erreur: " . substr($lastFailed->exception ?? 'N/A', 0, 200) . "\n";
        }
    } catch (\Exception $e) {
        echo "   - Table failed_jobs non accessible: " . $e->getMessage() . "\n";
    }

    echo "\n";

    // 6. Logs Laravel récents
    echo "📋 LOGS LARAVEL RÉCENTS:\n";
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        $lines = file($logFile);
        $recentLines = array_slice($lines, -10);
        foreach ($recentLines as $line) {
            if (strpos($line, 'mail') !== false || strpos($line, 'email') !== false || strpos($line, 'Newsletter') !== false) {
                echo "   " . trim($line) . "\n";
            }
        }
    }

} catch (\Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DIAGNOSTIC ===\n";
