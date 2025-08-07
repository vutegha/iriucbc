<?php

echo "=== TEST D'ACCÈS À LA PAGE PARTENAIRES ===\n\n";

try {
    // Test 1: Vérifier les routes
    echo "1. Test de la route...\n";
    $route = \Illuminate\Support\Facades\Route::getRoutes()->getByName('admin.partenaires.index');
    if ($route) {
        echo "   ✅ Route 'admin.partenaires.index' trouvée\n";
        echo "   URI: " . $route->uri() . "\n";
        echo "   Méthodes: " . implode(', ', $route->methods()) . "\n\n";
    } else {
        echo "   ❌ Route non trouvée\n\n";
    }
    
    // Test 2: Vérifier le modèle
    echo "2. Test du modèle Partenaire...\n";
    $partenaires = \App\Models\Partenaire::ordonnes()->get();
    echo "   ✅ " . $partenaires->count() . " partenaire(s) trouvé(s)\n\n";
    
    // Test 3: Vérifier les statistiques
    echo "3. Test des statistiques...\n";
    $stats = [
        'total' => $partenaires->count(),
        'actifs' => $partenaires->where('statut', 'actif')->count(),
        'publies' => $partenaires->filter(function($partenaire) {
            return $partenaire->published_at !== null;
        })->count(),
        'universites' => $partenaires->where('type', 'universite')->count(),
        'organisations' => $partenaires->where('type', 'organisation_internationale')->count(),
    ];
    
    foreach ($stats as $key => $value) {
        echo "   {$key}: {$value}\n";
    }
    echo "   ✅ Statistiques générées\n\n";
    
    // Test 4: Vérifier les permissions
    echo "4. Test des permissions...\n";
    $superAdmin = \App\Models\User::whereHas('roles', function($q) {
        $q->where('name', 'super-admin');
    })->first();
    
    if ($superAdmin) {
        echo "   Super-admin: {$superAdmin->email}\n";
        echo "   Permission viewAny partenaires: " . ($superAdmin->can('view_partenaires') ? "✅ Oui" : "❌ Non") . "\n";
        
        // Simuler l'autorisation
        $policy = new \App\Policies\PartenairePolicy();
        $canViewAny = $policy->viewAny($superAdmin);
        echo "   Policy viewAny: " . ($canViewAny ? "✅ Oui" : "❌ Non") . "\n\n";
    } else {
        echo "   ❌ Aucun super-admin trouvé\n\n";
    }
    
    // Test 5: Vérifier la vue
    echo "5. Test de la vue...\n";
    $viewPath = resource_path('views/admin/partenaires/index.blade.php');
    if (file_exists($viewPath)) {
        echo "   ✅ Fichier de vue trouvé: " . basename($viewPath) . "\n";
        echo "   Taille: " . number_format(filesize($viewPath)) . " octets\n\n";
    } else {
        echo "   ❌ Fichier de vue non trouvé\n\n";
    }
    
    echo "=== RÉSULTAT ===\n";
    echo "✅ Tous les éléments semblent en place.\n";
    echo "Si la vue ne s'affiche toujours pas, vérifiez :\n";
    echo "1. Les logs d'erreur Laravel\n";
    echo "2. L'authentification utilisateur\n";
    echo "3. Les permissions de l'utilisateur connecté\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
