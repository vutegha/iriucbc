<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\ProjetController;

echo "=== Test ProjetController index() ===\n";

try {
    // Créer une instance du contrôleur
    $controller = new ProjetController();
    
    // Créer une requête simulée
    $request = Request::create('/admin/projets', 'GET');
    
    // Appeler la méthode index()
    $response = $controller->index($request);
    
    echo "✓ ProjetController->index() exécuté sans erreur\n";
    echo "Type de réponse: " . get_class($response) . "\n";
    
    // Vérifier les données de la vue
    $viewData = $response->getData();
    
    if (isset($viewData['anneesDisponibles'])) {
        $anneesType = gettype($viewData['anneesDisponibles']);
        echo "Type de anneesDisponibles: $anneesType\n";
        
        if ($anneesType === 'array') {
            echo "✓ anneesDisponibles est maintenant un array (correction réussie)\n";
            
            // Test array_combine()
            if (!empty($viewData['anneesDisponibles'])) {
                $testResult = array_combine($viewData['anneesDisponibles'], $viewData['anneesDisponibles']);
                echo "✓ array_combine() fonctionne correctement\n";
            } else {
                echo "ℹ anneesDisponibles est vide (normal si pas de données)\n";
            }
        } else {
            echo "✗ anneesDisponibles n'est pas un array: $anneesType\n";
        }
    } else {
        echo "✗ anneesDisponibles non trouvé dans les données de la vue\n";
    }
    
} catch (Exception $e) {
    echo "✗ ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== Test terminé ===\n";
