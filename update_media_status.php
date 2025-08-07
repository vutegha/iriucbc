<?php

require_once 'vendor/autoload.php';

use App\Models\Media;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Mise à jour des médias approuvés vers publié...\n";
    
    // Mettre les médias approuvés en statut published et publics
    $approvedMedias = Media::where('status', 'approved')->get();
    
    echo "Médias approuvés trouvés: " . $approvedMedias->count() . "\n";
    
    foreach ($approvedMedias as $media) {
        echo "Mise à jour du média ID: {$media->id}, Titre: {$media->titre}\n";
        
        $media->update([
            'status' => 'published',
            'is_public' => true
        ]);
    }
    
    echo "\nNouvel état des médias:\n";
    
    $stats = [
        'pending' => Media::where('status', 'pending')->count(),
        'approved' => Media::where('status', 'approved')->count(),
        'rejected' => Media::where('status', 'rejected')->count(),
        'published' => Media::where('status', 'published')->count(),
        'total' => Media::count(),
    ];
    
    foreach ($stats as $status => $count) {
        echo "- $status: $count\n";
    }
    
    echo "\nMédias publics: " . Media::where('is_public', true)->count() . "\n";
    echo "Médias publiés ET publics: " . Media::published()->count() . "\n";
    
    echo "\nListage des médias publiés:\n";
    $publishedMedias = Media::published()->get(['id', 'titre', 'status', 'is_public', 'medias']);
    
    foreach ($publishedMedias as $media) {
        echo "ID: {$media->id}, Titre: {$media->titre}, Fichier: {$media->medias}\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
