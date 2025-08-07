<?php

// Test simple de l'email
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

$email = 'test@example.com';
$token = 'test-token-123';

try {
    $mail = new ResetPasswordMail($token, $email);
    
    echo "Test de construction de l'email...\n";
    echo "Email destinataire: " . $email . "\n";
    echo "Token: " . $token . "\n";
    
    // Tester la construction du mail
    $mailData = $mail->build();
    
    echo "✅ Email construit avec succès\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
