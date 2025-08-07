<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST CONNEXION SMTP ===\n";

$host = config('mail.mailers.smtp.host');
$port = config('mail.mailers.smtp.port');
$username = config('mail.mailers.smtp.username');

echo "Host: $host\n";
echo "Port: $port\n";
echo "Username: $username\n\n";

// Test de connexion socket
echo "Test connexion socket...\n";
$socket = @fsockopen($host, $port, $errno, $errstr, 10);

if ($socket) {
    echo "✅ Connexion socket réussie\n";
    fclose($socket);
} else {
    echo "❌ Connexion socket échouée: $errstr ($errno)\n";
}

// Test avec SwiftMailer Transport (plus simple)
echo "\nTest transport Swift...\n";
try {
    // Configuration manuelle du transport
    $transport = new \Swift_SmtpTransport($host, $port, 'ssl');
    $transport->setUsername($username);
    $transport->setPassword(config('mail.mailers.smtp.password'));
    $transport->setTimeout(30);

    echo "Transport configuré, test démarrage...\n";
    $transport->start();
    echo "✅ Transport Swift démarré avec succès\n";
    $transport->stop();

} catch (\Swift_TransportException $e) {
    echo "❌ Erreur transport Swift: " . $e->getMessage() . "\n";
} catch (\Exception $e) {
    echo "❌ Erreur générale Swift: " . $e->getMessage() . "\n";
}

echo "\n=== FIN TEST ===\n";
