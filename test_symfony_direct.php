<?php

require_once 'vendor/autoload.php';

echo "=== TEST SYMFONY MAILER DIRECT ===\n\n";

try {
    // Configuration directe avec Symfony Mailer
    $host = 'iri.ledinitiatives.com';
    $port = 465;
    $username = 'info@iri.ledinitiatives.com';
    $password = '@Congo1960';
    
    echo "📧 Configuration:\n";
    echo "   Host: $host:$port\n";
    echo "   Username: $username\n";
    echo "   Encryption: SSL\n\n";

    echo "🔧 Création du transport Symfony...\n";
    
    // Créer le transport SMTP avec Symfony
    $dsn = "smtp://$username:" . urlencode($password) . "@$host:$port?encryption=ssl";
    echo "   DSN: " . str_replace($password, '***', $dsn) . "\n";
    
    $transport = \Symfony\Component\Mailer\Transport::fromDsn($dsn);
    
    echo "✅ Transport créé\n\n";

    echo "📝 Création du message...\n";
    
    $message = (new \Symfony\Component\Mime\Email())
        ->from($username)
        ->to('s.vutegha@gmail.com')
        ->subject('Test Symfony Direct - ' . date('Y-m-d H:i:s'))
        ->text('Test email envoyé directement via Symfony Mailer')
        ->html('<h1>Test Email</h1><p>Test email envoyé directement via Symfony Mailer</p>');
    
    echo "✅ Message créé\n\n";

    echo "🚀 Envoi du message...\n";
    
    $mailer = new \Symfony\Component\Mailer\Mailer($transport);
    $mailer->send($message);
    
    echo "✅ Email envoyé avec succès via Symfony!\n";

} catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
    echo "❌ Erreur transport Symfony: " . $e->getMessage() . "\n";
} catch (\Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== FIN TEST SYMFONY ===\n";
