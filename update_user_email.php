<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

try {
    $user = User::where('email', 'admin@iri-ucbc.org')->first();
    if ($user) {
        $user->email = 'iri@ucbc.org';
        $user->save();
        echo "✓ Email mis à jour: {$user->email}\n";
    } else {
        echo "! Utilisateur non trouvé avec l'email admin@iri-ucbc.org\n";
    }
    
    // Vérifier tous les utilisateurs
    $users = User::all();
    echo "\n✓ Utilisateurs dans la base:\n";
    foreach ($users as $u) {
        echo "  - {$u->name} ({$u->email})\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
