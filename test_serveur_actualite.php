<?php

/**
 * Test rapide pour vérifier si le serveur peut recevoir des POST
 */

echo "=== TEST SERVEUR ACTUALITÉ ===\n\n";

// Test 1: Vérifier que les routes existent
echo "1. Vérification des routes...\n";

try {
    $routes = [
        'admin.actualite.index' => 'Liste des actualités',
        'admin.actualite.create' => 'Créer une actualité', 
        'admin.actualite.store' => 'Enregistrer actualité',
    ];
    
    foreach ($routes as $routeName => $description) {
        try {
            $url = route($routeName);
            echo "   ✅ $routeName: $url\n";
        } catch (Exception $e) {
            echo "   ❌ $routeName: ERREUR - " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Erreur générale routes: " . $e->getMessage() . "\n";
}

echo "\n2. Test du contrôleur...\n";
try {
    $controller = new \App\Http\Controllers\Admin\ActualiteController();
    echo "   ✅ ActualiteController accessible\n";
} catch (Exception $e) {
    echo "   ❌ ActualiteController: " . $e->getMessage() . "\n";
}

echo "\n3. Test du modèle...\n";
try {
    $actualite = new \App\Models\Actualite();
    echo "   ✅ Modèle Actualite accessible\n";
} catch (Exception $e) {
    echo "   ❌ Modèle Actualite: " . $e->getMessage() . "\n";
}

echo "\n4. Test de la base de données...\n";
try {
    $count = \App\Models\Actualite::count();
    echo "   ✅ Base de données OK - $count actualités trouvées\n";
} catch (Exception $e) {
    echo "   ❌ Base de données: " . $e->getMessage() . "\n";
}

echo "\n5. Test CSRF token...\n";
try {
    $token = csrf_token();
    echo "   ✅ CSRF token généré: " . substr($token, 0, 10) . "...\n";
} catch (Exception $e) {
    echo "   ❌ CSRF token: " . $e->getMessage() . "\n";
}

echo "\n=== RÉSUMÉ ===\n";
echo "Si tous les tests sont ✅, le problème vient du JavaScript côté client.\n";
echo "Si des tests sont ❌, le problème vient du serveur.\n\n";

// Test simulation d'une requête POST
echo "6. Simulation requête POST...\n";
try {
    $request = new \Illuminate\Http\Request([
        'titre' => 'Test depuis script',
        'texte' => 'Contenu de test pour vérifier le POST',
        '_token' => csrf_token()
    ]);
    
    echo "   ✅ Requête POST simulée créée\n";
    echo "   📝 Données: titre=" . $request->get('titre') . "\n";
} catch (Exception $e) {
    echo "   ❌ Simulation POST: " . $e->getMessage() . "\n";
}

echo "\nTerminé.\n";
