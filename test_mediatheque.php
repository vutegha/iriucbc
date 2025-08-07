<?php

require_once 'vendor/autoload.php';

use App\Models\Media;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "=== TEST MEDIATHEQUE CKEDITOR ===\n\n";
    
    // Test du scope published
    echo "1. Test du scope Media::published()\n";
    $publishedMedias = Media::published()->get();
    echo "Nombre de médias publiés: " . $publishedMedias->count() . "\n\n";
    
    foreach ($publishedMedias as $media) {
        echo "- ID: {$media->id}, Titre: {$media->titre}, Status: {$media->status}, Public: " . ($media->is_public ? 'Oui' : 'Non') . "\n";
    }
    
    // Test de la méthode list du contrôleur (simulation)
    echo "\n2. Test de la méthode list() simulée\n";
    $medias = Media::published()
                   ->where(function($query) {
                       $query->where('type', 'image')
                             ->orWhere('medias', 'like', '%.jpg')
                             ->orWhere('medias', 'like', '%.jpeg')
                             ->orWhere('medias', 'like', '%.png')
                             ->orWhere('medias', 'like', '%.gif')
                             ->orWhere('medias', 'like', '%.webp');
                   })
                   ->latest()
                   ->get()
                   ->map(function ($media) {
                       return [
                           'id' => $media->id,
                           'url' => asset('storage/' . $media->medias),
                           'name' => $media->titre ?: basename($media->medias),
                           'alt' => $media->titre ?: 'Image'
                       ];
                   });

    echo "Nombre d'images publiées pour CKEditor: " . $medias->count() . "\n\n";
    
    foreach ($medias as $media) {
        echo "- ID: {$media['id']}, Nom: {$media['name']}, URL: {$media['url']}\n";
    }
    
    echo "\n=== Format JSON pour la réponse ===\n";
    echo json_encode($medias, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
