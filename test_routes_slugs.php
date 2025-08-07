<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

use App\Models\Rapport;

echo "=== TEST DES ROUTES AVEC SLUGS ===" . PHP_EOL;

$rapport = Rapport::first();

if ($rapport) {
    echo "Test du rapport:" . PHP_EOL;
    echo "ID: {$rapport->id}" . PHP_EOL;
    echo "Slug: {$rapport->slug}" . PHP_EOL;
    echo "Titre: " . substr($rapport->titre, 0, 50) . "..." . PHP_EOL;
    echo PHP_EOL;
    
    // Test de la route show avec ID (devrait ne plus fonctionner)
    echo "Test route avec ID: /admin/rapports/{$rapport->id}" . PHP_EOL;
    $request = \Illuminate\Http\Request::create("/admin/rapports/{$rapport->id}", 'GET');
    try {
        $response = $kernel->handle($request);
        echo "Status: " . $response->getStatusCode() . PHP_EOL;
    } catch (\Exception $e) {
        echo "Erreur: " . $e->getMessage() . PHP_EOL;
    }
    
    echo PHP_EOL;
    
    // Test de la route show avec slug (devrait fonctionner)
    echo "Test route avec slug: /admin/rapports/{$rapport->slug}" . PHP_EOL;
    $request = \Illuminate\Http\Request::create("/admin/rapports/{$rapport->slug}", 'GET');
    try {
        $response = $kernel->handle($request);
        echo "Status: " . $response->getStatusCode() . PHP_EOL;
    } catch (\Exception $e) {
        echo "Erreur: " . $e->getMessage() . PHP_EOL;
    }
} else {
    echo "Aucun rapport trouvé pour les tests" . PHP_EOL;
}

echo PHP_EOL . "=== FIN DU TEST ===" . PHP_EOL;

?>
