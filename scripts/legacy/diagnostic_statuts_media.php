<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Media;
use Illuminate\Support\Facades\Gate;

echo "=== DIAGNOSTIC DES STATUTS DE MÉDIAS ===\n\n";

$user = User::where('email', 'admin@ucbc.org')->first();
auth()->login($user);

echo "✅ Utilisateur authentifié: {$user->name}\n\n";

// Vérifier tous les médias et leurs statuts
echo "=== TOUS LES MÉDIAS DANS LA BASE ===\n";
$allMedias = Media::all();
foreach ($allMedias as $media) {
    echo "ID: {$media->id} | Titre: {$media->titre} | Status: '{$media->status}'\n";
}

echo "\n=== LOGIQUE CONDITIONNELLE DES ACTIONS ===\n";
$testMedia = Media::first();
if ($testMedia) {
    echo "Média de test: {$testMedia->titre} (Status: '{$testMedia->status}')\n\n";
    
    // Tester chaque condition de la vue
    echo "Conditions de la vue show.blade.php:\n";
    
    echo "1. @can('moderate', \$media): " . (Gate::allows('moderate', $testMedia) ? '✅ TRUE' : '❌ FALSE') . "\n";
    
    echo "2. Conditions de statut:\n";
    echo "   - \$media->status === 'pending': " . ($testMedia->status === 'pending' ? '✅ TRUE' : '❌ FALSE') . "\n";
    echo "   - \$media->status === 'approved': " . ($testMedia->status === 'approved' ? '✅ TRUE' : '❌ FALSE') . "\n";
    echo "   - \$media->status === 'published': " . ($testMedia->status === 'published' ? '✅ TRUE' : '❌ FALSE') . "\n";
    echo "   - \$media->status === 'rejected': " . ($testMedia->status === 'rejected' ? '✅ TRUE' : '❌ FALSE') . "\n";
    
    echo "\n=== SIMULATION DES BOUTONS VISIBLES ===\n";
    $canModerate = Gate::allows('moderate', $testMedia);
    echo "Section modération visible: " . ($canModerate ? '✅ OUI' : '❌ NON') . "\n";
    
    if ($canModerate) {
        switch ($testMedia->status) {
            case 'pending':
                echo "Boutons visibles: Approuver, Rejeter\n";
                break;
            case 'approved':
                echo "Boutons visibles: Publier, Rejeter\n";
                break;
            case 'published':
                echo "Boutons visibles: Dépublier\n";
                break;
            case 'rejected':
                echo "Boutons visibles: Réapprouver\n";
                break;
            default:
                echo "❌ AUCUN BOUTON VISIBLE - Status '{$testMedia->status}' non reconnu!\n";
                echo "Statuts valides: pending, approved, published, rejected\n";
        }
    }
    
    // Créer des médias de test avec différents statuts
    echo "\n=== CRÉATION DE MÉDIAS DE TEST ===\n";
    $statuses = ['pending', 'approved', 'published', 'rejected'];
    
    foreach ($statuses as $status) {
        $existingMedia = Media::where('status', $status)->first();
        if (!$existingMedia) {
            $newMedia = new Media();
            $newMedia->titre = "Test {$status}";
            $newMedia->medias = "test_{$status}.jpg";
            $newMedia->status = $status;
            $newMedia->is_public = false;
            $newMedia->created_by = $user->id;
            $newMedia->save();
            echo "✅ Créé média de test avec status '{$status}' (ID: {$newMedia->id})\n";
        } else {
            echo "✅ Média avec status '{$status}' existe déjà (ID: {$existingMedia->id})\n";
        }
    }
    
    echo "\n=== TEST FINAL AVEC CHAQUE STATUT ===\n";
    foreach ($statuses as $status) {
        $mediaWithStatus = Media::where('status', $status)->first();
        if ($mediaWithStatus) {
            $canModerate = Gate::allows('moderate', $mediaWithStatus);
            echo "Status '{$status}' - Can moderate: " . ($canModerate ? '✅' : '❌') . " - URL: /admin/media/{$mediaWithStatus->id}\n";
        }
    }
}

echo "\n=== CONSEILS DE DÉBOGAGE ===\n";
echo "1. Visitez /admin/media/{ID} où ID est un média avec status 'pending'\n";
echo "2. Vérifiez que vous êtes bien connecté comme admin@ucbc.org\n";
echo "3. Ouvrez les outils de développement pour voir s'il y a des erreurs JS\n";
echo "4. Vérifiez le cache Laravel avec: php artisan cache:clear\n";
