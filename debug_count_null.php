<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

echo "=== DIAGNOSTIC ERREUR count() on null ===\n\n";

use App\Models\Service;
use App\Models\Projet;

try {
    // 1. Tester les services
    echo "1. Test des services et leurs projets:\n";
    
    $services = Service::all();
    echo "Nombre de services: " . $services->count() . "\n";
    
    foreach ($services->take(5) as $service) {
        echo "\n📂 Service: {$service->nom} (ID: {$service->id})\n";
        
        // Test direct de la relation
        try {
            $projets = $service->projets;
            
            if ($projets === null) {
                echo "   ❌ PROBLÈME: \$service->projets est NULL\n";
            } else {
                echo "   ✅ Type: " . get_class($projets) . "\n";
                echo "   ✅ Nombre de projets: " . $projets->count() . "\n";
            }
        } catch (Exception $e) {
            echo "   ❌ ERREUR: " . $e->getMessage() . "\n";
        }
        
        // Test avec eager loading
        try {
            $serviceWithProjets = Service::with('projets')->find($service->id);
            $projetsEager = $serviceWithProjets->projets;
            
            if ($projetsEager === null) {
                echo "   ❌ PROBLÈME: Eager loading aussi NULL\n";
            } else {
                echo "   ✅ Eager loading OK: " . $projetsEager->count() . " projets\n";
            }
        } catch (Exception $e) {
            echo "   ❌ ERREUR Eager loading: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n2. Test des relations dans la base de données:\n";
    
    // Vérifier la structure des tables
    $totalProjets = \DB::table('projets')->count();
    echo "Total projets en base: $totalProjets\n";
    
    $projetsAvecService = \DB::table('projets')->whereNotNull('service_id')->count();
    echo "Projets avec service_id: $projetsAvecService\n";
    
    $serviceIds = \DB::table('projets')->whereNotNull('service_id')->distinct()->pluck('service_id');
    echo "Services ayant des projets: " . $serviceIds->count() . "\n";
    
    // Test avec un service spécifique qui pourrait poser problème
    echo "\n3. Test avec service spécifique:\n";
    
    $serviceAvecProjets = Service::whereHas('projets')->first();
    if ($serviceAvecProjets) {
        echo "Service avec projets trouvé: {$serviceAvecProjets->nom}\n";
        
        try {
            $count = $serviceAvecProjets->projets->count();
            echo "✅ Count OK: $count projets\n";
        } catch (Exception $e) {
            echo "❌ ERREUR sur count(): " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ Aucun service avec projets trouvé\n";
    }
    
    echo "\n4. Test protection contre null:\n";
    
    $serviceTest = Service::first();
    if ($serviceTest) {
        // Test de la protection
        $projetsProtege = $serviceTest->projets ?? collect();
        echo "✅ Avec protection (?? collect()): " . $projetsProtege->count() . "\n";
        
        // Test avec optional()
        $countOptional = optional($serviceTest->projets)->count() ?? 0;
        echo "✅ Avec optional(): $countOptional\n";
    }

} catch (Exception $e) {
    echo "❌ ERREUR GÉNÉRALE: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DU DIAGNOSTIC ===\n";

?>
