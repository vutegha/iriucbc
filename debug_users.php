<?php

// Test simple pour vérifier les utilisateurs et permissions
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test des utilisateurs et permissions ===\n\n";

try {
    // Vérifier les utilisateurs
    $users = \App\Models\User::all();
    echo "Nombre d'utilisateurs: " . $users->count() . "\n\n";
    
    foreach ($users as $user) {
        echo "Utilisateur: " . $user->name . " (" . $user->email . ")\n";
        
        try {
            $canModerate = $user->canModerate();
            echo "  - Peut modérer: " . ($canModerate ? 'Oui' : 'Non') . "\n";
        } catch (Exception $e) {
            echo "  - Erreur canModerate(): " . $e->getMessage() . "\n";
        }
        
        try {
            $roles = $user->getRoleNames();
            echo "  - Rôles: " . $roles->implode(', ') . "\n";
        } catch (Exception $e) {
            echo "  - Erreur rôles: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    // Test de l'authentification
    echo "=== Test authentification ===\n";
    echo "Utilisateur authentifié: " . (auth()->check() ? 'Oui' : 'Non') . "\n";
    if (auth()->check()) {
        echo "Utilisateur actuel: " . auth()->user()->name . "\n";
        echo "Peut modérer: " . (auth()->user()->canModerate() ? 'Oui' : 'Non') . "\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
