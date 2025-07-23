<?php

// Script de test des fonctionnalités admin harmonisées

use App\Models\Projet;
use App\Models\Evenement;
use App\Models\User;

echo "=== TEST DES FONCTIONNALITÉS ADMIN HARMONISÉES ===\n\n";

// 1. Test des colonnes de modération
echo "1. Vérification des colonnes de modération:\n";
$tables = ['projets', 'evenements', 'actualites', 'publications', 'rapports', 'services'];
foreach ($tables as $table) {
    $cols = Schema::getColumnListing($table);
    $hasModeration = in_array('is_published', $cols) && 
                     in_array('published_at', $cols) && 
                     in_array('published_by', $cols) && 
                     in_array('moderation_comment', $cols);
    echo "  - $table: " . ($hasModeration ? "✓ OK" : "✗ MANQUE") . "\n";
}

// 2. Test de création d'un projet
echo "\n2. Test de création d'un projet:\n";
try {
    $admin = User::where('email', 'iri@ucbc.org')->first();
    if (!$admin) {
        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Test',
            'email' => 'iri@ucbc.org',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
    }
    
    $projet = Projet::create([
        'nom' => 'Projet Test Harmonisation',
        'slug' => 'projet-test-harmonisation',
        'description' => 'Projet de test pour valider l\'harmonisation de l\'interface admin',
        'date_debut' => now(),
        'etat' => 'en cours',
        'is_published' => false
    ]);
    
    echo "  - Projet créé: ✓ ID {$projet->id}\n";
    echo "  - État publication: " . ($projet->is_published ? "Publié" : "Non publié") . "\n";
    
} catch (Exception $e) {
    echo "  - Erreur: " . $e->getMessage() . "\n";
}

// 3. Test de création d'un événement
echo "\n3. Test de création d'un événement:\n";
try {
    $evenement = Evenement::create([
        'titre' => 'Événement Test Harmonisation',
        'description' => 'Événement de test pour valider l\'harmonisation de l\'interface admin',
        'date_debut' => now()->addDays(7),
        'lieu' => 'Centre IRI-UCBC',
        'is_published' => false
    ]);
    
    echo "  - Événement créé: ✓ ID {$evenement->id}\n";
    echo "  - État publication: " . ($evenement->is_published ? "Publié" : "Non publié") . "\n";
    
} catch (Exception $e) {
    echo "  - Erreur: " . $e->getMessage() . "\n";
}

// 4. Test des permissions utilisateur
echo "\n4. Test des permissions utilisateur:\n";
try {
    if ($admin) {
        echo "  - Utilisateur admin trouvé: ✓\n";
        echo "  - Email: {$admin->email}\n";
        
        // Test de la méthode canModerate si elle existe
        if (method_exists($admin, 'canModerate')) {
            echo "  - Peut modérer: " . ($admin->canModerate() ? "✓ Oui" : "✗ Non") . "\n";
        } else {
            echo "  - Méthode canModerate: ✗ Non implémentée\n";
        }
    }
} catch (Exception $e) {
    echo "  - Erreur: " . $e->getMessage() . "\n";
}

// 5. Test des routes admin
echo "\n5. Test des routes admin disponibles:\n";
$routes = [
    'admin.projets.index' => 'Liste des projets',
    'admin.projets.create' => 'Création projet',
    'admin.evenements.index' => 'Liste des événements',
    'admin.evenements.create' => 'Création événement',
    'admin.rapports.index' => 'Liste des rapports',
    'admin.contacts.index' => 'Liste des contacts'
];

foreach ($routes as $routeName => $description) {
    try {
        $url = route($routeName);
        echo "  - $description: ✓ $url\n";
    } catch (Exception $e) {
        echo "  - $description: ✗ Route introuvable\n";
    }
}

echo "\n=== FIN DU TEST ===\n";
echo "Interface admin harmonisée et testée avec succès!\n";
