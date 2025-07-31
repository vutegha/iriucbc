<?php

use App\Models\User;

$user = User::where('email', 'sergyo.vutegha@gmail.com')->first();

if ($user) {
    echo "Utilisateur trouvé: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Super Admin: " . ($user->isSuperAdmin() ? 'OUI' : 'NON') . "\n";
    
    echo "\n=== PERMISSIONS ACTUALITÉS ===\n";
    echo "update actualites: " . ($user->can('update actualites') ? 'OUI' : 'NON') . "\n";
    echo "view actualites: " . ($user->can('view actualites') ? 'OUI' : 'NON') . "\n";
    echo "delete actualites: " . ($user->can('delete actualites') ? 'OUI' : 'NON') . "\n";
    
    echo "\n=== PERMISSIONS ÉVÉNEMENTS ===\n";
    echo "update evenements: " . ($user->can('update evenements') ? 'OUI' : 'NON') . "\n";
    echo "view evenements: " . ($user->can('view evenements') ? 'OUI' : 'NON') . "\n";
    echo "delete evenements: " . ($user->can('delete evenements') ? 'OUI' : 'NON') . "\n";
    
    echo "\n=== RÔLES ===\n";
    foreach ($user->roles as $role) {
        echo "Rôle: " . $role->name . "\n";
    }
    
} else {
    echo "Utilisateur non trouvé!\n";
}
