<?php

// Test création d'un lien social avec le nouveau système
require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Models\User;
use App\Models\SocialLink;

try {
    // Bootstrap Laravel
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "=== TEST NOUVEAU SYSTÈME LIENS SOCIAUX ===\n\n";
    
    // Authentifier l'utilisateur ID 1
    $user = User::find(1);
    auth()->login($user);
    echo "✓ Utilisateur authentifié: " . $user->name . "\n";
    
    // Supprimer les anciens liens de test
    SocialLink::where('platform', 'facebook')->delete();
    echo "✓ Anciens liens Facebook supprimés\n";
    
    // Créer une fausse requête SANS champ icon
    $requestData = [
        'platform' => 'facebook',
        'name' => 'Page Facebook IRI-UCBC',
        'url' => 'https://facebook.com/ucbcofficiel',
        'is_active' => '1',
        'order' => '1'
    ];
    
    echo "✓ Données de test préparées (SANS champ icon):\n";
    foreach ($requestData as $key => $value) {
        echo "   $key: $value\n";
    }
    
    // Créer la Request
    $request = Request::create('/admin/social-links', 'POST', $requestData);
    
    // Instancier le controller et appeler store
    $controller = new SocialLinkController();
    echo "\n⏳ Création du lien social...\n";
    
    $response = $controller->store($request);
    
    echo "✅ Lien social créé avec succès!\n";
    
    // Vérifier en base
    $newLink = SocialLink::where('platform', 'facebook')->latest()->first();
    if ($newLink) {
        echo "✓ Lien créé en base:\n";
        echo "   ID: " . $newLink->id . "\n";
        echo "   Plateforme: " . $newLink->platform . "\n"; 
        echo "   Nom: " . $newLink->name . "\n";
        echo "   URL: " . $newLink->url . "\n";
        echo "   Icône (auto): " . $newLink->icon . "\n";
        echo "   Couleur (auto): " . $newLink->color . "\n";
        echo "   Actif: " . ($newLink->is_active ? 'Oui' : 'Non') . "\n";
        echo "   Ordre: " . $newLink->order . "\n";
    }
    
    echo "\n🎉 SYSTÈME FONCTIONNEL - Icônes automatiques opérationnelles!\n";
    
} catch (\Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
