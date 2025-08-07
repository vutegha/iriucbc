<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;

echo "=== Test Password Reset Email ===\n";

try {
    // 1. Vérifier la configuration email
    echo "1. Configuration email:\n";
    echo "   - From: " . config('mail.from.address') . "\n";
    echo "   - Name: " . config('mail.from.name') . "\n";
    echo "   - Mailer: " . config('mail.default') . "\n\n";
    
    // 2. Trouver un utilisateur
    $user = User::first();
    if (!$user) {
        echo "ERREUR: Aucun utilisateur trouvé\n";
        exit(1);
    }
    
    echo "2. Utilisateur trouvé:\n";
    echo "   - Email: " . $user->email . "\n";
    echo "   - Nom: " . $user->name . "\n\n";
    
    // 3. Tester l'envoi de l'email de reset
    echo "3. Test envoi email reset...\n";
    
    $status = Password::sendResetLink([
        'email' => $user->email
    ]);
    
    echo "   - Status: " . $status . "\n";
    
    if ($status === Password::RESET_LINK_SENT) {
        echo "   - ✅ Email envoyé avec succès\n";
    } else {
        echo "   - ❌ Échec de l'envoi: " . $status . "\n";
    }
    
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
