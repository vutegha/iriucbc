<?php

use Illuminate\Support\Facades\Schema;
use App\Models\SocialLink;
use App\Models\User;

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
try {
    $testData = [
        'platform' => 'Facebook Test',
        'name' => 'Notre page Facebook Test',
        'url' => 'https://facebook.com/test123',
        'icon' => 'fab fa-facebook',
        'is_active' => true,
        'order' => 999
    ];
    
    $socialLink = SocialLink::create($testData);
    echo "   Lien créé avec ID: " . $socialLink->id . "\n";
    
    // Nettoyer
    $socialLink->delete();
    echo "   Lien supprimé (nettoyage)\n";
    
} catch (\Exception $e) {
    echo "   ERREUR lors de la création: " . $e->getMessage() . "\n";
}

echo "\n=== TEST TERMINÉ ===\n";
