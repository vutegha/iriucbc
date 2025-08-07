<?php

require_once 'vendor/autoload.php';

echo "=== TEST DIFFÉRENTS PORTS ET ENCRYPTION ===\n\n";

$configurations = [
    ['port' => 587, 'encryption' => 'tls', 'name' => 'Port 587 TLS'],
    ['port' => 465, 'encryption' => 'ssl', 'name' => 'Port 465 SSL'],
    ['port' => 25, 'encryption' => null, 'name' => 'Port 25 Sans encryption']
];

$host = 'iri.ledinitiatives.com';
$username = 'info@iri.ledinitiatives.com';
$password = '@Congo1960';

foreach ($configurations as $config) {
    echo "🔧 TEST: {$config['name']}\n";
    
    try {
        // Test connexion socket d'abord
        echo "   Test socket {$host}:{$config['port']}... ";
        $socket = @fsockopen($host, $config['port'], $errno, $errstr, 5);
        
        if (!$socket) {
            echo "❌ Connexion socket échouée: $errstr\n\n";
            continue;
        }
        
        echo "✅ Socket OK\n";
        fclose($socket);
        
        // Test avec Symfony
        if ($config['encryption']) {
            $dsn = "smtp://$username:" . urlencode($password) . "@$host:{$config['port']}?encryption={$config['encryption']}";
        } else {
            $dsn = "smtp://$username:" . urlencode($password) . "@$host:{$config['port']}";
        }
        
        echo "   Création transport... ";
        $transport = \Symfony\Component\Mailer\Transport::fromDsn($dsn);
        echo "✅\n";
        
        echo "   Test envoi... ";
        $message = (new \Symfony\Component\Mime\Email())
            ->from($username)
            ->to('s.vutegha@gmail.com')
            ->subject("Test {$config['name']} - " . date('H:i:s'))
            ->text('Test avec ' . $config['name']);
        
        $mailer = new \Symfony\Component\Mailer\Mailer($transport);
        
        // Timeout manuel
        $startTime = time();
        $mailer->send($message);
        $duration = time() - $startTime;
        
        echo "✅ Envoyé en {$duration}s\n";
        echo "   🎉 SUCCÈS avec {$config['name']}!\n\n";
        break; // Arrêter au premier succès
        
    } catch (\Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n\n";
    }
}

echo "=== FIN TESTS ===\n";
