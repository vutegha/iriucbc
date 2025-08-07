<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Media;

echo "=== MISE À JOUR DES STATUTS DES MÉDIAS ===\n";

// Mettre à jour tous les médias 'active' vers 'published'
$updated = Media::where('status', 'active')->update(['status' => 'published']);

echo "✅ {$updated} médias mis à jour de 'active' vers 'published'\n";

// Vérifier les statuts après mise à jour
echo "\n=== VÉRIFICATION POST-MISE À JOUR ===\n";
$allMedias = Media::all();
foreach ($allMedias as $media) {
    echo "ID: {$media->id} | Titre: {$media->titre} | Status: '{$media->status}'\n";
}

echo "\n=== RAPPEL DES ACTIONS DISPONIBLES ===\n";
echo "Status 'pending' → Boutons: Approuver, Rejeter\n";
echo "Status 'approved' → Boutons: Publier, Rejeter\n";
echo "Status 'published' → Boutons: Dépublier\n";
echo "Status 'rejected' → Boutons: Réapprouver\n";

echo "\nMaintenant, visitez /admin/media/1 pour voir les actions de modération!\n";
