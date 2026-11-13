<?php

// Test direct du controller SocialLink
require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Models\User;
use App\Models\SocialLink;

try {
    // Bootstrap Laravel
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "=== TEST CRÉATION LIEN SOCIAL ===\n\n";
    
    // Authentifier l'utilisateur ID 1
    $user = User::find(1);
    if (!$user) {
        throw new Exception("Utilisateur ID 1 non trouvé");
    }
    
    auth()->login($user);
    echo "✓ Utilisateur authentifié: " . $user->name . "\n";
    
    // Vérifier les permissions
    if (!$user->can('create_social_links')) {
        throw new Exception("L'utilisateur n'a pas la permission create_social_links");
    }
    echo "✓ Permission create_social_links vérifiée\n";
    
    // Créer une fausse requête
    $requestData = [
        'platform' => 'Facebook Test',
        'name' => 'Notre page Facebook Test',
        'url' => 'https://facebook.com/test-iri',
        'icon' => 'fab fa-facebook',
        'is_active' => '1',
        'order' => '10'
    ];
    
    // Simuler une Request HTTP
    $request = Request::create('/admin/social-links', 'POST', $requestData);
    $request->headers->set('Content-Type', 'application/x-www-form-urlencoded');
    
    echo "✓ Request créée avec les données:\n";
    foreach ($requestData as $key => $value) {
        echo "   $key: $value\n";
    }
    
    // Instancier le controller et appeler store
    $controller = new SocialLinkController();
    echo "\n⏳ Appel de la méthode store...\n";
    
    $response = $controller->store($request);
    
    echo "✅ Lien social créé avec succès!\n";
    echo "Type de réponse: " . get_class($response) . "\n";
    
    // Vérifier en base
    $lastLink = SocialLink::latest()->first();
    echo "✓ Dernier lien créé: " . $lastLink->platform . " - " . $lastLink->name . "\n";
    
} catch (\Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
