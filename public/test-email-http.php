<?php

// Test d'envoi d'email via HTTP (navigateur)
require_once '../vendor/autoload.php';

// Démarrer Laravel
$app = require_once '../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<h1>Test Email via HTTP</h1>";
echo "<p>Temps de démarrage: " . date('H:i:s') . "</p>";

try {
    echo "<h2>Configuration Email:</h2>";
    echo "<ul>";
    echo "<li>Host: " . config('mail.mailers.smtp.host') . "</li>";
    echo "<li>Port: " . config('mail.mailers.smtp.port') . "</li>";
    echo "<li>Encryption: " . config('mail.mailers.smtp.encryption') . "</li>";
    echo "<li>Username: " . config('mail.mailers.smtp.username') . "</li>";
    echo "</ul>";

    echo "<h2>Test envoi email...</h2>";
    echo "<p>Début: " . date('H:i:s') . "</p>";
    
    $startTime = microtime(true);
    
    \Illuminate\Support\Facades\Mail::raw('Test depuis HTTP - ' . now(), function ($message) {
        $message->to('s.vutegha@gmail.com')
                ->subject('Test HTTP - ' . now())
                ->from(config('mail.from.address'), 'IRI HTTP Test');
    });
    
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime), 2);
    
    echo "<p style='color: green;'>✅ Email envoyé avec succès en {$duration}s!</p>";
    echo "<p>Fin: " . date('H:i:s') . "</p>";

} catch (\Exception $e) {
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime), 2);
    
    echo "<p style='color: red;'>❌ Erreur après {$duration}s: " . $e->getMessage() . "</p>";
    echo "<p>Type d'erreur: " . get_class($e) . "</p>";
    echo "<p>Fichier: " . $e->getFile() . ":" . $e->getLine() . "</p>";
}

echo "<h2>Test Newsletter</h2>";

try {
    $publication = \App\Models\Publication::first();
    $subscriber = \App\Models\Newsletter::first();
    
    if ($publication && $subscriber) {
        echo "<p>Publication: " . htmlspecialchars($publication->titre) . "</p>";
        echo "<p>Subscriber: " . htmlspecialchars($subscriber->email) . "</p>";
        
        echo "<p>Envoi newsletter...</p>";
        $startTime = microtime(true);
        
        \Illuminate\Support\Facades\Mail::to($subscriber->email)
            ->send(new \App\Mail\PublicationNewsletter($publication, $subscriber));
        
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime), 2);
        
        echo "<p style='color: green;'>✅ Newsletter envoyée en {$duration}s!</p>";
    } else {
        echo "<p style='color: orange;'>Pas de données pour tester la newsletter</p>";
    }
} catch (\Exception $e) {
    echo "<p style='color: red;'>❌ Erreur newsletter: " . $e->getMessage() . "</p>";
}

echo "<p>Test terminé: " . date('H:i:s') . "</p>";
