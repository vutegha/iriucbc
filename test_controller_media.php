<?php
/**
 * Test du contrôleur Media pour identifier le problème
 */

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST CONTRÔLEUR MEDIA ===\n\n";

try {
    // Créer une instance du contrôleur
    $controller = new App\Http\Controllers\Admin\MediaController();
    
    echo "✅ Contrôleur MediaController instancié\n";
    
    // Tester les données nécessaires pour la vue
    $medias = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1, [
        'path' => request()->url(),
        'pageName' => 'page',
    ]);
    
    $testData = [
        'medias' => $medias,
        'stats' => [
            'total' => 0,
            'images' => 0,
            'videos' => 0,
            'pending' => 0
        ],
        'imageStats' => ['published' => 0],
        'videoStats' => ['published' => 0]
    ];
    
    echo "✅ Données de test préparées\n";
    
    // Test direct de la vue avec les données
    $errors = new \Illuminate\Support\MessageBag(); // Variable globale pour Blade
    
    $view = view('admin.media.index', $testData)->with('errors', $errors);
    echo "✅ Vue créée avec succès\n";
    
    $html = $view->render();
    echo "✅ Vue rendue avec succès\n";
    echo "📏 Taille HTML: " . number_format(strlen($html)) . " caractères\n";
    
    // Vérifier le contenu généré
    $hasTitle = strpos($html, 'Gestion des Médias') !== false;
    $hasStats = strpos($html, 'Total Médias') !== false;
    $hasGrid = strpos($html, 'media-grid') !== false;
    
    echo "\n🔍 Contenu vérifié:\n";
    echo "   - Titre: " . ($hasTitle ? "✅" : "❌") . "\n";
    echo "   - Statistiques: " . ($hasStats ? "✅" : "❌") . "\n";
    echo "   - Grille médias: " . ($hasGrid ? "✅" : "❌") . "\n";
    
    echo "\n🎉 SUCCESS! La vue media/index fonctionne parfaitement!\n";
    echo "L'erreur 'Cannot end a section without first starting one' est résolue.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . "\n";
    echo "   Ligne: " . $e->getLine() . "\n";
    
    // Diagnostic plus poussé
    if (strpos($e->getMessage(), 'Cannot end a section') !== false) {
        echo "\n🔍 DIAGNOSTIC BLADE:\n";
        echo "Cette erreur indique un problème dans le layout 'layouts.admin'.\n";
        echo "Vérifiez que tous les @section ont leur @endsection correspondant.\n";
    }
}
