<?php

require_once 'vendor/autoload.php';

// Démarrer Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST SERVICE DIRECT EMAIL ===\n\n";

try {
    // Test du service direct
    echo "🔧 Test DirectEmailService...\n";
    
    if (!class_exists('\App\Services\DirectEmailService')) {
        echo "❌ DirectEmailService n'existe pas encore\n";
    } else {
        $directService = new \App\Services\DirectEmailService();
        
        $publication = \App\Models\Publication::first();
        $newsletter = \App\Models\Newsletter::first();
        
        echo "   📧 Tentative d'envoi direct...\n";
        $result = $directService->sendPublicationEmail($publication, $newsletter);
        
        if ($result) {
            echo "   ✅ Envoi direct réussi\n";
        } else {
            echo "   ❌ Envoi direct échoué\n";
        }
    }

    // Test simple avec SwiftMailer natif
    echo "\n📨 Test SwiftMailer natif...\n";
    
    try {
        // Configuration directe SwiftMailer
        $transport = (new Swift_SmtpTransport(
            config('mail.mailers.smtp.host'), 
            config('mail.mailers.smtp.port'),
            config('mail.mailers.smtp.encryption')
        ))
        ->setUsername(config('mail.mailers.smtp.username'))
        ->setPassword(config('mail.mailers.smtp.password'));

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message('Test Direct Swift'))
        ->setFrom([config('mail.from.address') => config('mail.from.name')])
        ->setTo(['s.vutegha@gmail.com'])
        ->setBody('Test email direct via SwiftMailer');

        echo "   📤 Envoi via SwiftMailer...\n";
        $result = $mailer->send($message);
        
        echo "   ✅ SwiftMailer: $result email(s) envoyé(s)\n";

    } catch (Exception $e) {
        echo "   ❌ Erreur SwiftMailer: " . $e->getMessage() . "\n";
    }

    // Test avec PHPMailer
    echo "\n📮 Test PHPMailer...\n";
    
    try {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = config('mail.mailers.smtp.host');
        $mail->SMTPAuth = true;
        $mail->Username = config('mail.mailers.smtp.username');
        $mail->Password = config('mail.mailers.smtp.password');
        $mail->SMTPSecure = config('mail.mailers.smtp.encryption');
        $mail->Port = config('mail.mailers.smtp.port');

        $mail->setFrom(config('mail.from.address'), config('mail.from.name'));
        $mail->addAddress('s.vutegha@gmail.com');

        $mail->Subject = 'Test PHPMailer Direct';
        $mail->Body = 'Test email direct via PHPMailer';

        echo "   📤 Envoi via PHPMailer...\n";
        $mail->send();
        echo "   ✅ PHPMailer: Email envoyé avec succès\n";

    } catch (Exception $e) {
        echo "   ❌ Erreur PHPMailer: {$mail->ErrorInfo}\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
}

echo "\n=== FIN TEST ===\n";
