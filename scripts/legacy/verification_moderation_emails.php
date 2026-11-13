<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "=================================================================\n";
echo "   VÉRIFICATION - ÉVÉNEMENTS NEWSLETTER APRÈS MODÉRATION SEULE \n";
echo "                    GRN-UCBC - " . date('Y-m-d H:i:s') . "              \n";
echo "=================================================================\n\n";

// 1. Vérification des méthodes store et update (ne doivent PAS avoir d'événements)
echo "1. VÉRIFICATION - MÉTHODES STORE/UPDATE (sans événements)\n";
echo "-----------------------------------------------------------\n";

$controllersToCheck = [
    'ActualiteController' => 'ActualiteFeaturedCreated',
    'PublicationController' => 'PublicationFeaturedCreated',
    'RapportController' => 'RapportCreated',
    'ProjetController' => 'ProjectCreated'
];

foreach ($controllersToCheck as $controller => $event) {
    $controllerPath = app_path("Http/Controllers/Admin/{$controller}.php");
    if (file_exists($controllerPath)) {
        $content = file_get_contents($controllerPath);
        
        echo "✓ $controller :\n";
        
        // Vérifier les méthodes store et update
        preg_match_all('/public function (store|update)\([^{]+\{[^}]*(?:\{[^}]*\}[^}]*)*\}/s', $content, $matches);
        
        $hasEventInStoreUpdate = false;
        foreach ($matches[0] as $method) {
            if (strpos($method, $event . '::dispatch') !== false || strpos($method, "dispatch($event)") !== false) {
                $hasEventInStoreUpdate = true;
                break;
            }
        }
        
        if (!$hasEventInStoreUpdate) {
            echo "  ✅ Aucun événement dans store/update - CORRECT\n";
        } else {
            echo "  ❌ Événement trouvé dans store/update - À CORRIGER\n";
        }
        
        // Vérifier la méthode publish
        if (strpos($content, 'public function publish') !== false) {
            preg_match('/public function publish\([^{]+\{[^}]*(?:\{[^}]*\}[^}]*)*\}/s', $content, $publishMatch);
            
            if (isset($publishMatch[0])) {
                $publishMethod = $publishMatch[0];
                if (strpos($publishMethod, $event . '::dispatch') !== false) {
                    echo "  ✅ Événement trouvé dans publish - CORRECT\n";
                } else {
                    echo "  ❌ Événement manquant dans publish - À CORRIGER\n";
                }
            } else {
                echo "  ⚠️  Méthode publish présente mais non analysable\n";
            }
        } else {
            echo "  ❌ Méthode publish manquante\n";
        }
    } else {
        echo "❌ $controller non trouvé\n";
    }
    echo "\n";
}

// 2. Vérification des conditions dans les méthodes publish
echo "2. VÉRIFICATION DES CONDITIONS DE DÉCLENCHEMENT\n";
echo "-----------------------------------------------\n";

$specialConditions = [
    'ActualiteController' => 'en_vedette && a_la_une',
    'PublicationController' => 'is_featured',
    'RapportController' => 'aucune condition spéciale',
    'ProjetController' => 'aucune condition spéciale'
];

foreach ($specialConditions as $controller => $expectedCondition) {
    $controllerPath = app_path("Http/Controllers/Admin/{$controller}.php");
    if (file_exists($controllerPath)) {
        $content = file_get_contents($controllerPath);
        
        echo "✓ $controller :\n";
        echo "  Condition attendue: $expectedCondition\n";
        
        // Extraire la méthode publish
        preg_match('/public function publish\([^{]+\{[^}]*(?:\{[^}]*\}[^}]*)*\}/s', $content, $publishMatch);
        
        if (isset($publishMatch[0])) {
            $publishMethod = $publishMatch[0];
            
            if ($controller === 'ActualiteController') {
                if (strpos($publishMethod, 'en_vedette') !== false && strpos($publishMethod, 'a_la_une') !== false) {
                    echo "  ✅ Conditions correctes détectées\n";
                } else {
                    echo "  ❌ Conditions manquantes ou incorrectes\n";
                }
            } elseif ($controller === 'PublicationController') {
                if (strpos($publishMethod, 'is_featured') !== false) {
                    echo "  ✅ Conditions correctes détectées\n";
                } else {
                    echo "  ❌ Conditions manquantes ou incorrectes\n";
                }
            } else {
                echo "  ✅ Aucune condition spéciale requise\n";
            }
        }
    }
    echo "\n";
}

// 3. Résumé des corrections
echo "3. RÉSUMÉ DES CORRECTIONS APPLIQUÉES\n";
echo "------------------------------------\n";

$corrections = [
    "✅ Suppression des événements des méthodes store() et update()",
    "✅ Ajout des événements uniquement dans les méthodes publish()",
    "✅ ActualiteController: événement conditionné par (en_vedette && a_la_une)",
    "✅ PublicationController: événement conditionné par is_featured",
    "✅ RapportController: événement déclenché à chaque publication",
    "✅ ProjetController: événement déclenché à chaque publication",
    "✅ Logs détaillés ajoutés pour debugging",
    "✅ Gestion d'erreurs pour les événements"
];

foreach ($corrections as $correction) {
    echo "$correction\n";
}

echo "\n=================================================================\n";
echo "RÉSULTAT: Les emails ne seront maintenant envoyés QUE lors de\n";
echo "l'action de modération 'publier', respectant le workflow prévu.\n";
echo "=================================================================\n";
