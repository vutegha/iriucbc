<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

echo "=== VÉRIFICATION PERMISSIONS ===\n\n";

// Récupérer le premier utilisateur admin
$user = User::first();
if ($user) {
    echo "Utilisateur: " . $user->name . " (ID: " . $user->id . ")\n";
    echo "Email: " . $user->email . "\n\n";
    
    // Vérifier les permissions social
    $socialPerms = $user->permissions->filter(function($p) {
        return str_contains($p->name, 'social');
    });
    
    echo "Permissions Social Links:\n";
    if ($socialPerms->count() > 0) {
        foreach($socialPerms as $perm) {
            echo "  ✓ " . $perm->name . "\n";
        }
    } else {
        echo "  ✗ Aucune permission social trouvée\n";
        
        // Ajouter les permissions manquantes
        echo "\nAjout des permissions...\n";
        $socialPermissions = [
            'view_social_links',
            'create_social_links', 
            'update_social_links',
            'delete_social_links',
            'moderate_social_links'
        ];
        
        foreach($socialPermissions as $permName) {
            $permission = Permission::firstOrCreate(['name' => $permName]);
            if (!$user->hasPermissionTo($permName)) {
                $user->givePermissionTo($permName);
                echo "  ✓ Ajouté: $permName\n";
            }
        }
    }
} else {
    echo "Aucun utilisateur trouvé\n";
}

echo "\n=== FIN VÉRIFICATION ===\n";
