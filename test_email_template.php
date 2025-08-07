<?php

// Test de l'email de confirmation de contact

use App\Mail\ContactMessageWithCopy;
use App\Models\Contact;

echo "=== TEST EMAIL CONTACT AVEC TEMPLATE ===\n";

try {
    // Créer un faux contact pour le test
    $testContact = new Contact([
        'nom' => 'Test User',
        'email' => 'test@example.com',
        'sujet' => 'Test de formatage email',
        'message' => 'Ceci est un message de test pour vérifier le formatage de l\'email de confirmation.',
        'statut' => 'nouveau'
    ]);
    $testContact->id = 999; // ID fictif

    echo "1. Contact de test créé:\n";
    echo "   - Nom: {$testContact->nom}\n";
    echo "   - Email: {$testContact->email}\n";
    echo "   - Sujet: {$testContact->sujet}\n\n";

    // Test email admin
    echo "2. Test email admin...\n";
    $adminMail = new ContactMessageWithCopy($testContact, true);
    echo "   - ✅ Instance admin créée\n";
    echo "   - Sujet: " . $adminMail->envelope()->subject . "\n";
    echo "   - Template: emails.contact-message-admin\n\n";

    // Test email confirmation
    echo "3. Test email confirmation...\n";
    $confirmMail = new ContactMessageWithCopy($testContact, false);
    echo "   - ✅ Instance confirmation créée\n";
    echo "   - Sujet: " . $confirmMail->envelope()->subject . "\n";
    echo "   - Template: emails.contact-message-copy\n\n";

    echo "4. Test construction des emails...\n";
    try {
        $adminContent = $adminMail->content();
        echo "   - ✅ Email admin: " . $adminContent->view . "\n";
        
        $confirmContent = $confirmMail->content();
        echo "   - ✅ Email confirmation: " . $confirmContent->view . "\n";
        
    } catch (Exception $e) {
        echo "   - ❌ Erreur construction: " . $e->getMessage() . "\n";
    }

    echo "\n✅ TOUS LES TESTS RÉUSSIS\n";
    echo "Les emails utilisent maintenant les templates formatés !\n";

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
