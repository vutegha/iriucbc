<?php

// Test de connectivité SMTP simple
echo "=== TEST CONNECTIVITÉ SMTP ===\n";

$host = 'iri.ledinitiatives.com';
$port = 465;
$timeout = 30;

echo "Test de connexion vers $host:$port...\n";

$context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ]
]);

$socket = @stream_socket_client(
    "ssl://$host:$port",
    $errno,
    $errstr,
    $timeout,
    STREAM_CLIENT_CONNECT,
    $context
);

if ($socket) {
    echo "✅ Connexion SSL réussie sur port $port\n";
    
    // Lire la réponse du serveur SMTP
    $response = fgets($socket, 1024);
    echo "Réponse serveur: " . trim($response) . "\n";
    
    // Envoyer EHLO pour tester
    fwrite($socket, "EHLO localhost\r\n");
    $response = fgets($socket, 1024);
    echo "Réponse EHLO: " . trim($response) . "\n";
    
    fclose($socket);
} else {
    echo "❌ Impossible de se connecter: $errstr ($errno)\n";
    
    // Test sur port 587 alternatif
    echo "\nTest port alternatif 587...\n";
    $socket2 = @stream_socket_client(
        "tcp://$host:587",
        $errno2,
        $errstr2,
        $timeout
    );
    
    if ($socket2) {
        echo "✅ Connexion TCP réussie sur port 587\n";
        fclose($socket2);
    } else {
        echo "❌ Port 587 aussi inaccessible: $errstr2 ($errno2)\n";
    }
}

echo "\n=== FIN TEST ===\n";
