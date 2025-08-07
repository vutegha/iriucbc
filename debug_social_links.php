<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\Schema;
use App\Models\SocialLink;
use App\Models\User;

try {
    echo "=== DEBUG SOCIAL LINKS ===\n\n";
    
    // Test 1: Vérifier si la table existe
    echo "1. Table social_links existe: " . (Schema::hasTable('social_links') ? 'OUI' : 'NON') . "\n";
    
    // Test 2: Vérifier les colonnes
    if (Schema::hasTable('social_links')) {
        $columns = Schema::getColumnListing('social_links');
        echo "   Colonnes: " . implode(', ', $columns) . "\n";
    }
    
    // Test 3: Test de création basique
    echo "\n2. Test de création d'un lien social:\n";
    $testData = [
        'platform' => 'Facebook',
        'name' => 'Notre page Facebook',
        'url' => 'https://facebook.com/test',
        'icon' => 'fab fa-facebook',
        'is_active' => true,
        'order' => 1
    ];
    
    $socialLink = SocialLink::create($testData);
    echo "   Lien créé avec ID: " . $socialLink->id . "\n";
    
    // Test 4: Vérifier l'utilisateur connecté (si session existe)
    $user = User::find(1); // Assumons user ID 1
    if ($user) {
        echo "\n3. Permissions utilisateur (ID: $user->id):\n";
        $socialPermissions = $user->permissions->filter(function($p) {
            return str_contains($p->name, 'social');
        });
        foreach($socialPermissions as $perm) {
            echo "   - " . $perm->name . "\n";
        }
    }
    
    echo "\n=== TEST TERMINÉ AVEC SUCCÈS ===\n";
    
} catch (\Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
