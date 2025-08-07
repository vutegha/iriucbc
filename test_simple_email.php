<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST EMAIL SIMPLE ===\n";

try {
    echo "Configuration email:\n";
    echo "- Mailer: " . config('mail.default') . "\n";
    echo "- Host: " . config('mail.mailers.smtp.host') . "\n";
    echo "- Port: " . config('mail.mailers.smtp.port') . "\n";
    echo "- From: " . config('mail.from.address') . "\n\n";

    echo "Tentative d'envoi...\n";
    
    $result = \Illuminate\Support\Facades\Mail::raw('Test simple', function ($message) {
        $message->to('s.vutegha@gmail.com')
                ->subject('Test Simple - ' . now())
                ->from(config('mail.from.address'));
    });
    
    echo "Résultat envoi: ";
    var_dump($result);
    
    echo "\n✅ Email envoyé avec succès!\n";

} catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
    echo "❌ Erreur transport SMTP: " . $e->getMessage() . "\n";
} catch (\Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
}

echo "\n=== FIN TEST ===\n";
