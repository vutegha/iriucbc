<?php

// Test rapide des permissions
echo "Test des permissions rapport...\n";

try {
    require_once __DIR__ . '/vendor/autoload.php';
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "Permissions rapport existantes:\n";
    $permissions = \Spatie\Permission\Models\Permission::where('name', 'like', '%rapport%')->pluck('name');
    foreach ($permissions as $perm) {
        echo "- $perm\n";
    }
    
    // Tester les permissions spécifiques
    $testPermissions = [
        'view_rapports',
        'create_rapports', 
        'update_rapports',
        'delete_rapports',
        'publish_rapports',
        'unpublish_rapports',
        'moderate_rapports'
    ];
    
    echo "\nVérification des permissions requises:\n";
    foreach ($testPermissions as $perm) {
        $exists = \Spatie\Permission\Models\Permission::where('name', $perm)->exists();
        echo "- $perm: " . ($exists ? '✅' : '❌') . "\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

echo "Test terminé.\n";
