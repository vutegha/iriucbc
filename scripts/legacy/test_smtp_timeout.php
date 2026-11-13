<?php

require_once 'vendor/autoload.php';

// Configuration timeout stricte
ini_set('default_socket_timeout', 10);
ini_set('max_execution_time', 30);

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST CONFIGURATION SMTP ===\n\n";

// Afficher la configuration complete
echo "📧 Configuration actuelle:\n";
echo "   MAIL_MAILER: " . env('MAIL_MAILER') . "\n";
echo "   MAIL_HOST: " . env('MAIL_HOST') . "\n";
echo "   MAIL_PORT: " . env('MAIL_PORT') . "\n";
echo "   MAIL_USERNAME: " . env('MAIL_USERNAME') . "\n";
echo "   MAIL_ENCRYPTION: " . env('MAIL_ENCRYPTION') . "\n";
echo "   MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS') . "\n\n";

// Configuration Laravel
echo "📧 Configuration Laravel:\n";
echo "   default: " . config('mail.default') . "\n";
echo "   host: " . config('mail.mailers.smtp.host') . "\n";
echo "   port: " . config('mail.mailers.smtp.port') . "\n";
echo "   username: " . config('mail.mailers.smtp.username') . "\n";
echo "   encryption: " . config('mail.mailers.smtp.encryption') . "\n";
echo "   from.address: " . config('mail.from.address') . "\n\n";

// Test connexion simple avec fsockopen
echo "🔌 Test connexion socket (timeout 10s):\n";
$host = config('mail.mailers.smtp.host');
$port = config('mail.mailers.smtp.port');

$startTime = microtime(true);
$socket = @fsockopen($host, $port, $errno, $errstr, 10);
$endTime = microtime(true);

if ($socket) {
    echo "   ✅ Connexion réussie en " . round(($endTime - $startTime), 2) . "s\n";
    
    // Lire la réponse du serveur
    $response = fgets($socket, 1024);
    echo "   📝 Réponse serveur: " . trim($response) . "\n";
    fclose($socket);
} else {
    echo "   ❌ Connexion échouée: $errstr ($errno)\n";
}

echo "\n🚀 Test envoi avec timeout strict:\n";

try {
    // Forcer un timeout sur Mail
    $startTime = microtime(true);
    
    $result = \Illuminate\Support\Facades\Mail::raw('Test timeout', function ($message) {
        $message->to('s.vutegha@gmail.com')
                ->subject('Test Timeout - ' . now())
                ->from(config('mail.from.address'), 'IRI Timeout Test');
    });
    
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime), 2);
    
    echo "   ✅ Email envoyé en {$duration}s\n";
    echo "   📝 Résultat: ";
    var_dump($result);
    
} catch (\Exception $e) {
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime), 2);
    
    echo "   ❌ Erreur après {$duration}s: " . $e->getMessage() . "\n";
    echo "   📝 Type: " . get_class($e) . "\n";
}

echo "\n=== FIN TEST ===\n";
