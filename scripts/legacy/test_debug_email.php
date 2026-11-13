<?php

require_once 'vendor/autoload.php';
ini_set('default_socket_timeout', 30);
ini_set('max_execution_time', 60);

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST EMAIL AVEC DEBUG ===\n";

try {
    // Activer le mode debug pour l'email
    config(['mail.debug' => true]);
    
    echo "Tentative d'envoi avec timeout réduit...\n";
    
    // Utiliser un callback pour capturer les erreurs
    \Illuminate\Support\Facades\Mail::raw('Test debug email', function ($message) {
        $message->to('s.vutegha@gmail.com')
                ->subject('Test Debug - ' . now())
                ->from('info@iri.ledinitiatives.com', 'IRI Test');
    });
    
    echo "✅ Email envoyé!\n";

} catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
    echo "❌ Erreur transport: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Type: " . get_class($e) . "\n";
    echo "Code: " . $e->getCode() . "\n";
}

echo "\n=== TEST ALTERNATIF ===\n";

// Test avec une approche différente
try {
    echo "Test avec queue sync...\n";
    
    // Forcer le mode sync
    config(['queue.default' => 'sync']);
    
    // Tester via queue
    \Illuminate\Support\Facades\Mail::queue(
        new \Illuminate\Mail\Markdown('emails.test', ['content' => 'Test queue']),
        'email_data'
    );
    
    echo "✅ Email queue sync envoyé!\n";

} catch (\Exception $e) {
    echo "❌ Erreur queue: " . $e->getMessage() . "\n";
}

echo "\n=== FIN TEST ===\n";
