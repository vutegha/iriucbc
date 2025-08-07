<?php

require_once 'vendor/autoload.php';

use App\Models\Media;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "État actuel des médias:\n";
    
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
    echo "Médias privés: " . Media::where('is_public', false)->count() . "\n";
    
    // Vérifier les médias sans statut défini ou avec un statut ancien
    $mediasWithoutStatus = Media::whereNull('status')->orWhere('status', '')->count();
    echo "\nMédias sans statut: $mediasWithoutStatus\n";
    
    if ($mediasWithoutStatus > 0) {
        echo "\nMise à jour des médias sans statut...\n";
        
        // Mettre à jour les médias sans statut vers 'published' s'ils sont publics
        $updated = Media::whereNull('status')
            ->orWhere('status', '')
            ->update([
                'status' => 'published',
                'is_public' => true
            ]);
            
        echo "Médias mis à jour: $updated\n";
    }
    
    echo "\nListage des premiers médias:\n";
    $medias = Media::take(5)->get(['id', 'titre', 'status', 'is_public', 'medias']);
    
    foreach ($medias as $media) {
        echo "ID: {$media->id}, Titre: {$media->titre}, Status: {$media->status}, Public: " . ($media->is_public ? 'Oui' : 'Non') . ", Fichier: {$media->medias}\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
