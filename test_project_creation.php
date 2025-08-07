<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Service;
use Illuminate\Support\Facades\Log;

echo "=== TEST DE CRÉATION DE PROJET POUR IDENTIFIER L'ERREUR ===\n\n";

// Vider les logs précédents
Log::info('=== DÉBUT DU TEST DE CRÉATION DE PROJET ===');

echo "🧪 TEST 1: Création avec données minimales valides\n";

// Préparer des données de test minimales
$testData = [
    'nom' => 'Test Projet ' . time(),
    'description' => 'Description de test pour le projet. Cette description fait plus de 50 caractères pour respecter la validation minimale.',
    'date_debut' => now()->format('Y-m-d'),
    'etat' => 'en cours'
];

// Vérifier qu'il y a au moins un service
$service = Service::first();
if ($service) {
    $testData['service_id'] = $service->id;
    echo "✓ Service trouvé: {$service->nom} (ID: {$service->id})\n";
} else {
    echo "❌ Aucun service trouvé - création d'un service de test\n";
    $service = Service::create([
        'nom' => 'Service Test',
        'description' => 'Service créé pour les tests'
    ]);
    $testData['service_id'] = $service->id;
}

echo "📋 Données de test:\n";
foreach ($testData as $key => $value) {
    echo "   {$key}: {$value}\n";
}

echo "\n🚀 Tentative de création...\n";

try {
    // Simuler une requête HTTP
    $request = new \Illuminate\Http\Request();
    $request->merge($testData);
    $request->merge(['_token' => csrf_token()]);
    
    // Créer une instance du contrôleur
    $controller = new \App\Http\Controllers\Admin\ProjetController();
    
    // Appeler la méthode store
    $response = $controller->store($request);
    
    echo "✅ Succès! Type de réponse: " . get_class($response) . "\n";
    
    if (method_exists($response, 'getTargetUrl')) {
        echo "🔗 Redirection vers: " . $response->getTargetUrl() . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Exception capturée:\n";
    echo "   Type: " . get_class($e) . "\n";
    echo "   Message: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n📜 VÉRIFICATION DES LOGS:\n";

// Lire les logs récents
try {
    $logPath = storage_path('logs/laravel.log');
    if (file_exists($logPath)) {
        $logContent = file_get_contents($logPath);
        $recentLogs = substr($logContent, -5000); // 5000 derniers caractères
        
        if (strpos($recentLogs, 'Exception non catégorisée lors de la création de projet') !== false) {
            echo "🔍 Exception non catégorisée trouvée dans les logs!\n";
            
            // Extraire les détails de l'exception
            $lines = explode("\n", $recentLogs);
            foreach ($lines as $line) {
                if (strpos($line, 'exception_class') !== false || 
                    strpos($line, 'exception_message') !== false ||
                    strpos($line, 'Exception non catégorisée') !== false) {
                    echo "   " . trim($line) . "\n";
                }
            }
        } else {
            echo "ℹ️ Aucune exception non catégorisée trouvée dans les logs récents\n";
        }
    } else {
        echo "❌ Fichier de log non trouvé\n";
    }
} catch (\Exception $e) {
    echo "❌ Erreur lors de la lecture des logs: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DU TEST ===\n";
echo "Si vous voyez un message avec [DEBUG: CATCH-FINAL-555] ou [DEBUG: CATCH-DB-507],\n";
echo "cela nous indiquera exactement quel catch block est responsable de l'erreur.\n";
