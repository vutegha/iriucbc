<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;

try {
    echo "🔍 PERMISSIONS EXISTANTES POUR LES AUTEURS:\n";
    echo "==========================================\n";
    
    // Chercher toutes les permissions liées aux auteurs
    $permissions = Permission::where('name', 'like', '%auteur%')
                            ->orWhere('name', 'like', '%author%')
                            ->get();
    
    if ($permissions->count() > 0) {
        foreach ($permissions as $permission) {
            echo "✅ " . $permission->name . " (guard: " . $permission->guard_name . ")\n";
        }
    } else {
        echo "❌ Aucune permission trouvée pour les auteurs\n";
        
        echo "\n📋 PERMISSIONS RECOMMANDÉES À CRÉER:\n";
        echo "===================================\n";
        $recommendedPermissions = [
            'view_auteurs',
            'create_auteurs', 
            'update_auteurs',
            'delete_auteurs',
            'export_auteurs',
            'manage_auteurs'
        ];
        
        foreach ($recommendedPermissions as $perm) {
            echo "- " . $perm . "\n";
        }
    }
    
    echo "\n🎯 TOUTES LES PERMISSIONS DU SYSTÈME:\n";
    echo "=====================================\n";
    $allPermissions = Permission::orderBy('name')->get();
    foreach ($allPermissions as $permission) {
        echo "- " . $permission->name . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Stack: " . $e->getTraceAsString() . "\n";
}
