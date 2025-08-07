<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST EMAIL RAW VS MAILABLE ===\n\n";

$testEmail = 's.vutegha@gmail.com';

try {
    // Test 1: Email raw simple
    echo "🔥 TEST 1 - Email raw simple:\n";
    
    \Illuminate\Support\Facades\Mail::raw('Test raw simple - ' . now(), function ($message) use ($testEmail) {
        $message->to($testEmail)
                ->subject('Test Raw Simple')
                ->from('info@iri.ledinitiatives.com', 'IRI Test');
    });
    
    echo "   ✅ Email raw envoyé\n\n";

    // Test 2: Email avec vue simple
    echo "🔥 TEST 2 - Email avec vue simple:\n";
    
    \Illuminate\Support\Facades\Mail::send('emails.test-simple', ['content' => 'Test vue'], function ($message) use ($testEmail) {
        $message->to($testEmail)
                ->subject('Test Vue Simple')
                ->from('info@iri.ledinitiatives.com', 'IRI Test');
    });
    
    echo "   ✅ Email vue simple envoyé\n\n";

} catch (\Exception $e) {
    echo "❌ Erreur dans les tests de base: " . $e->getMessage() . "\n\n";
}

// Test 3: Email avec la classe PublicationNewsletter mais sans route
try {
    echo "🔥 TEST 3 - PublicationNewsletter modifiée:\n";
    
    $publication = \App\Models\Publication::first();
    $subscriber = \App\Models\Newsletter::first();
    
    if ($publication && $subscriber) {
        // Créer une version simplifiée temporaire
        $content = "
            <h1>Test Newsletter</h1>
            <h2>{$publication->titre}</h2>
            <p>Email: {$subscriber->email}</p>
            <p>Test simple sans route</p>
        ";
        
        \Illuminate\Support\Facades\Mail::html($content, function ($message) use ($testEmail, $publication) {
            $message->to($testEmail)
                    ->subject('Newsletter Test - ' . $publication->titre)
                    ->from('info@iri.ledinitiatives.com', 'IRI Newsletter');
        });
        
        echo "   ✅ Email newsletter simple envoyé\n\n";
    }

} catch (\Exception $e) {
    echo "❌ Erreur newsletter simple: " . $e->getMessage() . "\n\n";
}

echo "=== FIN TESTS ===\n";
