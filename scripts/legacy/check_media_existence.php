<?php
/**
 * Script simple pour vérifier les médias dans la base de données
 */

require_once 'vendor/autoload.php';

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VÉRIFICATION DES MÉDIAS ===\n\n";

try {
    // Compter les médias
    $count = App\Models\Media::count();
    echo "Nombre total de médias: $count\n\n";
    
    if ($count > 0) {
        echo "Premiers médias:\n";
        App\Models\Media::take(5)->get(['id', 'titre', 'status'])->each(function($media) {
            echo "ID: {$media->id} - Titre: {$media->titre} - Status: {$media->status}\n";
        });
    } else {
        echo "Aucun média trouvé dans la base de données.\n";
        echo "Création d'un média de test...\n";
        
        $media = App\Models\Media::create([
            'titre' => 'Test Media for Moderation',
            'description' => 'Media de test pour tester la modération',
            'medias' => 'test.jpg',
            'statut' => 'pending',
            'user_id' => App\Models\User::first()->id ?? 1
        ]);
        
        echo "Média de test créé avec l'ID: {$media->id}\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST DE ROUTE DIRECTE ===\n";

// Test de création d'URL de route
try {
    $testMediaId = 1;
    echo "Test de construction d'URL pour média ID $testMediaId:\n";
    
    $urls = [
        'approve' => route('admin.media.approve', $testMediaId),
        'reject' => route('admin.media.reject', $testMediaId),
        'publish' => route('admin.media.publish', $testMediaId),
        'unpublish' => route('admin.media.unpublish', $testMediaId),
    ];
    
    foreach ($urls as $action => $url) {
        echo "$action: $url\n";
    }
    
} catch (Exception $e) {
    echo "Erreur lors de la construction des URLs: " . $e->getMessage() . "\n";
}

?>
