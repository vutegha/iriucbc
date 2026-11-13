<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

echo "=== TEST AVEC SIMULATION COMPLÈTE D'UNE REQUÊTE WEB ===\n\n";

// Simuler une authentification utilisateur
$user = User::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé\n";
    exit;
}

Auth::login($user);
echo "👤 Connecté en tant que: {$user->name} (ID: {$user->id})\n\n";

// Test avec des conditions qui pourraient poser problème
$testCases = [
    'Avec image uploadée simulée' => [
        'nom' => 'Test Projet Avec Image',
        'description' => 'Description de test pour le projet. Cette description fait plus de 50 caractères pour respecter la validation minimale.',
        'date_debut' => now()->format('Y-m-d'),
        'etat' => 'en cours',
        'service_id' => Service::first()->id ?? 1,
        'beneficiaires_hommes' => '100',
        'beneficiaires_femmes' => '150',
        'beneficiaires_enfants' => '200',
        'budget' => '50000.50',
        // Simulation d'un upload d'image
        'has_file_upload' => true
    ],
    
    'Avec événement ProjectCreated' => [
        'nom' => 'Test Projet Événement',
        'description' => 'Description de test pour le projet. Cette description fait plus de 50 caractères pour respecter la validation minimale.',
        'date_debut' => now()->format('Y-m-d'),
        'etat' => 'en cours',
        'service_id' => Service::first()->id ?? 1,
        'test_event' => true
    ],
    
    'Avec tous les champs remplis' => [
        'nom' => 'Test Projet Complet ' . time(),
        'description' => 'Description très détaillée de test pour le projet. Cette description fait largement plus de 50 caractères pour respecter la validation minimale et tester tous les aspects.',
        'resume' => 'Résumé du projet de test',
        'date_debut' => now()->format('Y-m-d'),
        'date_fin' => now()->addMonths(6)->format('Y-m-d'),
        'etat' => 'en cours',
        'service_id' => Service::first()->id ?? 1,
        'budget' => '125000.75',
        'beneficiaires_hommes' => '500',
        'beneficiaires_femmes' => '600',
        'beneficiaires_enfants' => '300'
    ]
];

foreach ($testCases as $testName => $testData) {
    echo "🧪 TEST: {$testName}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // Log pour tracer le test
    Log::info("=== DÉBUT DU TEST WEB: {$testName} ===", $testData);
    
    try {
        // Créer une vraie requête HTTP avec tous les headers
        $request = new \Illuminate\Http\Request();
        $request->merge($testData);
        $request->merge([
            '_token' => csrf_token(),
            '_method' => 'POST'
        ]);
        
        // Simuler les headers HTTP
        $request->headers->set('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8');
        $request->headers->set('Content-Type', 'application/x-www-form-urlencoded');
        $request->headers->set('User-Agent', 'Mozilla/5.0 (Test Browser)');
        
        // Simuler l'upload d'image si spécifié
        if (isset($testData['has_file_upload'])) {
            // Créer un faux fichier uploadé
            $uploadedFile = new \Illuminate\Http\UploadedFile(
                __FILE__, // Utiliser ce fichier PHP comme test
                'test-image.txt',
                'text/plain',
                null,
                true // Test mode
            );
            $request->files->set('image', $uploadedFile);
        }
        
        // Définir la méthode et l'URI
        $request->setMethod('POST');
        $request->server->set('REQUEST_URI', '/admin/projets');
        $request->server->set('HTTP_HOST', 'localhost');
        
        echo "📋 Données de la requête:\n";
        foreach ($testData as $key => $value) {
            if ($key !== 'has_file_upload' && $key !== 'test_event') {
                echo "   {$key}: {$value}\n";
            }
        }
        
        // Appeler le contrôleur avec le contexte complet
        $controller = new \App\Http\Controllers\Admin\ProjetController();
        
        echo "\n🚀 Exécution de la requête...\n";
        $response = $controller->store($request);
        
        echo "✅ Succès!\n";
        echo "   Type de réponse: " . get_class($response) . "\n";
        
        if (method_exists($response, 'getTargetUrl')) {
            echo "   Redirection vers: " . $response->getTargetUrl() . "\n";
        }
        
        if (method_exists($response, 'getSession')) {
            $session = $response->getSession();
            if ($session && $session->has('success')) {
                echo "   Message de succès: " . $session->get('success') . "\n";
            }
            if ($session && $session->has('error')) {
                echo "   ⚠️ Message d'erreur: " . $session->get('error') . "\n";
                
                // Vérifier si c'est notre message problématique
                $errorMsg = $session->get('error');
                if (strpos($errorMsg, 'Une erreur système est survenue') !== false ||
                    strpos($errorMsg, 'DEBUG: CATCH-FINAL-555') !== false ||
                    strpos($errorMsg, 'DEBUG: CATCH-DB-507') !== false) {
                    echo "   🎯 ERREUR CIBLE TROUVÉE!\n";
                    echo "   🔍 Message exact: {$errorMsg}\n";
                }
            }
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
            echo "   🎯 ERREUR CIBLE TROUVÉE!\n";
        }
    }
    
    echo "\n";
}

echo "📋 CONCLUSION:\n";
echo "Si aucune erreur n'a été reproduite, le problème pourrait venir de:\n";
echo "1. Conditions spécifiques du navigateur (JavaScript, CSRF, etc.)\n";
echo "2. Middleware qui interfère\n";
echo "3. Configuration spécifique de l'environnement\n";
echo "4. Permissions utilisateur spécifiques\n";
echo "5. État de la base de données\n\n";

echo "💡 SUGGESTION:\n";
echo "Tentez de créer un projet via l'interface web maintenant\n";
echo "et vérifiez si le message contient [DEBUG: CATCH-FINAL-555] ou [DEBUG: CATCH-DB-507]\n";
