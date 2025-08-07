<?php

// Script de diagnostic complet du système de reset password
echo "=== DIAGNOSTIC PASSWORD RESET ===\n";

try {
    // 1. Test de l'environnement Laravel
    if (!file_exists('vendor/autoload.php')) {
        echo "❌ Vendor autoload non trouvé\n";
        exit(1);
    }
    
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "✅ Laravel initialisé\n";
    
    // 2. Test de la configuration email
    echo "\n--- Configuration Email ---\n";
    echo "Mailer: " . config('mail.default') . "\n";
    echo "Host: " . config('mail.mailers.smtp.host') . "\n";
    echo "Port: " . config('mail.mailers.smtp.port') . "\n";
    echo "From: " . config('mail.from.address') . "\n";
    echo "Name: " . config('mail.from.name') . "\n";
    
    // 3. Test de la classe ResetPasswordMail
    echo "\n--- Test ResetPasswordMail ---\n";
    $testEmail = 'test@example.com';
    $testToken = 'test-token-123456';
    
    $mail = new \App\Mail\ResetPasswordMail($testToken, $testEmail);
    echo "✅ Instance ResetPasswordMail créée\n";
    
    // 4. Test de construction du mail
    try {
        $mailObject = $mail->build();
        echo "✅ Mail construit sans erreur\n";
        
        // Vérifier les propriétés
        if (method_exists($mailObject, 'to') && !empty($mailObject->to)) {
            echo "✅ Destinataire défini\n";
        } else {
            echo "❌ Pas de destinataire défini\n";
        }
        
        if (method_exists($mailObject, 'from') && !empty($mailObject->from)) {
            echo "✅ Expéditeur défini\n";
        } else {
            echo "❌ Pas d'expéditeur défini\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur construction mail: " . $e->getMessage() . "\n";
    }
    
    // 5. Test avec un utilisateur réel
    echo "\n--- Test avec utilisateur réel ---\n";
    $user = \App\Models\User::first();
    
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé\n";
    } else {
        echo "✅ Utilisateur trouvé: " . $user->email . "\n";
        
        // Test de la notification
        try {
            $notification = new \App\Notifications\CustomResetPasswordNotification($testToken);
            echo "✅ Notification créée\n";
            
            $mailFromNotification = $notification->toMail($user);
            echo "✅ Mail généré depuis notification\n";
            
        } catch (Exception $e) {
            echo "❌ Erreur notification: " . $e->getMessage() . "\n";
        }
    }
    
    // 6. Test du Password Broker
    echo "\n--- Test Password Broker ---\n";
    try {
        $broker = app('auth.password.broker');
        echo "✅ Password broker initialisé\n";
        
        if ($user) {
            // Ne pas vraiment envoyer, juste tester la logique
            echo "✅ Prêt pour test d'envoi\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur password broker: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== FIN DIAGNOSTIC ===\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR FATALE: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
