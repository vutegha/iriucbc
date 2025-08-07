<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Service;
use Illuminate\Support\Facades\Log;

echo "=== TESTS AVEC DONNÉES PROBLÉMATIQUES POUR REPRODUIRE L'ERREUR ===\n\n";

$testCases = [
    'Service inexistant' => [
        'nom' => 'Test Projet Service Inexistant',
        'description' => 'Description de test pour le projet. Cette description fait plus de 50 caractères pour respecter la validation minimale.',
        'date_debut' => now()->format('Y-m-d'),
        'etat' => 'en cours',
        'service_id' => 99999 // Service qui n'existe pas
    ],
    
    'Nom trop long' => [
        'nom' => str_repeat('A', 300), // Nom de 300 caractères (max 255)
        'description' => 'Description de test pour le projet. Cette description fait plus de 50 caractères pour respecter la validation minimale.',
        'date_debut' => now()->format('Y-m-d'),
        'etat' => 'en cours',
        'service_id' => Service::first()->id ?? 1
    ],
    
    'Description trop courte' => [
        'nom' => 'Test Projet Description Courte',
        'description' => 'Court', // Moins de 50 caractères
        'date_debut' => now()->format('Y-m-d'),
        'etat' => 'en cours',
        'service_id' => Service::first()->id ?? 1
    ],
    
    'Date invalide' => [
        'nom' => 'Test Projet Date Invalide',
        'description' => 'Description de test pour le projet. Cette description fait plus de 50 caractères pour respecter la validation minimale.',
        'date_debut' => 'date-invalide',
        'etat' => 'en cours',
        'service_id' => Service::first()->id ?? 1
    ],
    
    'État invalide' => [
        'nom' => 'Test Projet État Invalide',
        'description' => 'Description de test pour le projet. Cette description fait plus de 50 caractères pour respecter la validation minimale.',
        'date_debut' => now()->format('Y-m-d'),
        'etat' => 'état_inexistant',
        'service_id' => Service::first()->id ?? 1
    ],
    
    'Budget invalide' => [
        'nom' => 'Test Projet Budget Invalide',
        'description' => 'Description de test pour le projet. Cette description fait plus de 50 caractères pour respecter la validation minimale.',
        'date_debut' => now()->format('Y-m-d'),
        'etat' => 'en cours',
        'service_id' => Service::first()->id ?? 1,
        'budget' => 'pas_un_nombre'
    ],
    
    'Données avec caractères spéciaux' => [
        'nom' => 'Test <script>alert("hack")</script>',
        'description' => 'Description de test pour le projet. Cette description fait plus de 50 caractères pour respecter la validation minimale.',
        'date_debut' => now()->format('Y-m-d'),
        'etat' => 'en cours',
        'service_id' => Service::first()->id ?? 1
    ]
];

foreach ($testCases as $testName => $testData) {
    echo "🧪 TEST: {$testName}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Log pour tracer le test
    Log::info("=== DÉBUT DU TEST: {$testName} ===");
    
    try {
        // Simuler une requête HTTP
        $request = new \Illuminate\Http\Request();
        $request->merge($testData);
        $request->merge(['_token' => csrf_token()]);
        
        // Créer une instance du contrôleur
        $controller = new \App\Http\Controllers\Admin\ProjetController();
        
        // Appeler la méthode store
        $response = $controller->store($request);
        
        echo "✅ Succès inattendu pour: {$testName}\n";
        
        if (method_exists($response, 'getTargetUrl')) {
            echo "🔗 Redirection vers: " . $response->getTargetUrl() . "\n";
        }
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        echo "📋 Erreur de validation (attendue):\n";
        foreach ($e->errors() as $field => $errors) {
            echo "   {$field}: " . implode(', ', $errors) . "\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Exception capturée:\n";
        echo "   Type: " . get_class($e) . "\n";
        echo "   Message: " . $e->getMessage() . "\n";
        echo "   Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
        
        // Vérifier si c'est notre message d'erreur problématique
        if (strpos($e->getMessage(), 'Une erreur système est survenue') !== false ||
            strpos($e->getMessage(), 'DEBUG: CATCH-FINAL-555') !== false ||
            strpos($e->getMessage(), 'DEBUG: CATCH-DB-507') !== false) {
            echo "🎯 ERREUR CIBLE TROUVÉE! C'est probablement cette condition qui cause le problème.\n";
        }
    }
    
    echo "\n";
}

echo "📜 RÉSUMÉ:\n";
echo "Si l'une des erreurs ci-dessus montre [DEBUG: CATCH-FINAL-555] ou [DEBUG: CATCH-DB-507],\n";
echo "nous avons identifié la condition qui déclenche l'erreur générique.\n\n";

echo "📋 PROCHAINES ÉTAPES:\n";
echo "1. Identifier quelle condition déclenche l'erreur générique\n";
echo "2. Ajuster la logique de validation ou de gestion d'erreur\n";
echo "3. Tester en conditions réelles avec le formulaire web\n";
