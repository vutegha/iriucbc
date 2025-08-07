<?php

require_once 'vendor/autoload.php';

use App\Models\Media;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Test de la galerie - récupération des médias publiés:\n\n";
    
    // Simuler la requête du contrôleur
    $medias = Media::published()->orderBy('created_at', 'desc')->get();
    
    echo "Nombre de médias publiés trouvés: " . $medias->count() . "\n\n";
    
    foreach ($medias as $media) {
        echo "---\n";
        echo "ID: {$media->id}\n";
        echo "Titre: {$media->titre}\n";
        echo "Type: {$media->type}\n";
        echo "Statut: {$media->status}\n";
        echo "Public: " . ($media->is_public ? 'Oui' : 'Non') . "\n";
        echo "Fichier: {$media->medias}\n";
        echo "URL: " . asset('storage/' . $media->medias) . "\n";
        
        // Vérifier si le fichier existe
        $filePath = storage_path('app/public/' . $media->medias);
        echo "Fichier existe: " . (file_exists($filePath) ? 'Oui' : 'Non') . "\n";
    }
    
    echo "\n=== Test des scopes ===\n";
    echo "Media::published()->count(): " . Media::published()->count() . "\n";
    echo "Media::pending()->count(): " . Media::pending()->count() . "\n";
    echo "Media::approved()->count(): " . Media::approved()->count() . "\n";
    echo "Media::rejected()->count(): " . Media::rejected()->count() . "\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
