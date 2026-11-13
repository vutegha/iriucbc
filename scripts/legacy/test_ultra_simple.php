<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST ULTRA SIMPLE ===\n\n";

// Test 1: Juste récupérer les données
echo "📊 Test 1 - Récupération données:\n";
try {
    $publication = \App\Models\Publication::first();
    echo "   Publication ID: " . $publication->id . "\n";
    echo "   Publication titre: " . substr($publication->titre, 0, 30) . "...\n";
    
    $newsletters = \App\Models\Newsletter::active()->get();
    echo "   Newsletters actives: " . $newsletters->count() . "\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
}

// Test 2: Configuration
echo "\n⚙️ Test 2 - Configuration:\n";
try {
    echo "   MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
    echo "   MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
    echo "   MAIL_FROM: " . config('mail.from.address') . "\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur config: " . $e->getMessage() . "\n";
}

// Test 3: Création d'un objet Mail (sans envoi)
echo "\n📧 Test 3 - Création objet Mail:\n";
try {
    $publication = \App\Models\Publication::first();
    $newsletter = \App\Models\Newsletter::first();
    
    echo "   Création PublicationNewsletterSimple...\n";
    $mailObject = new \App\Mail\PublicationNewsletterSimple($publication, $newsletter);
    echo "   ✅ Objet mail créé\n";
    
    echo "   Test envelope...\n";
    $envelope = $mailObject->envelope();
    echo "   ✅ Envelope: " . $envelope->subject . "\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur création mail: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

// Test 4: Test événement (sans envoi)
echo "\n🎯 Test 4 - Création événement:\n";
try {
    $publication = \App\Models\Publication::first();
    echo "   Création PublicationFeaturedCreated...\n";
    $event = new \App\Events\PublicationFeaturedCreated($publication);
    echo "   ✅ Événement créé\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur événement: " . $e->getMessage() . "\n";
}

echo "\n✅ Tests de base terminés\n";
echo "=== FIN TEST ===\n";
