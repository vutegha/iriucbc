<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Rapport;
use App\Models\User;

echo "=== TEST DU SYSTÈME DE MODÉRATION DES RAPPORTS ===" . PHP_EOL;
echo "Date du test: " . now()->format('Y-m-d H:i:s') . PHP_EOL;
echo "===============================================" . PHP_EOL . PHP_EOL;

// Récupérer le premier rapport pour test
$rapport = Rapport::first();

if (!$rapport) {
    echo "❌ Aucun rapport trouvé pour les tests" . PHP_EOL;
    exit;
}

echo "🔍 Rapport de test: " . $rapport->titre . PHP_EOL;
echo "📋 ID: " . $rapport->id . PHP_EOL;
echo "🔗 Slug: " . $rapport->slug . PHP_EOL;
echo "========================================" . PHP_EOL . PHP_EOL;

echo "📊 ÉTAT INITIAL:" . PHP_EOL;
echo "---------------" . PHP_EOL;
echo "is_published: " . ($rapport->is_published ? 'true' : 'false') . PHP_EOL;
echo "published_at: " . ($rapport->published_at ? $rapport->published_at->format('Y-m-d H:i:s') : 'null') . PHP_EOL;
echo "published_by: " . ($rapport->published_by ? $rapport->published_by : 'null') . PHP_EOL;
echo "moderation_comment: " . ($rapport->moderation_comment ? $rapport->moderation_comment : 'null') . PHP_EOL;
echo "publication_status: " . $rapport->publication_status . PHP_EOL;

// Créer un utilisateur test ou récupérer le premier
$user = User::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé pour les tests" . PHP_EOL;
    exit;
}

echo PHP_EOL . "👤 Utilisateur de test: " . $user->name . " (ID: " . $user->id . ")" . PHP_EOL;

// TEST 1: Publication
echo PHP_EOL . "🔄 TEST 1: PUBLICATION" . PHP_EOL;
echo "----------------------" . PHP_EOL;

try {
    $rapport->publish($user, 'Test de publication automatique');
    $rapport->refresh(); // Recharger depuis la DB
    
    echo "✅ Publication réussie!" . PHP_EOL;
    echo "is_published: " . ($rapport->is_published ? 'true' : 'false') . PHP_EOL;
    echo "published_at: " . ($rapport->published_at ? $rapport->published_at->format('Y-m-d H:i:s') : 'null') . PHP_EOL;
    echo "published_by: " . ($rapport->published_by ? $rapport->published_by : 'null') . PHP_EOL;
    echo "moderation_comment: " . ($rapport->moderation_comment ? $rapport->moderation_comment : 'null') . PHP_EOL;
    echo "publication_status: " . $rapport->publication_status . PHP_EOL;
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de la publication: " . $e->getMessage() . PHP_EOL;
}

// Attendre 2 secondes
sleep(2);

// TEST 2: Dépublication
echo PHP_EOL . "🔄 TEST 2: DÉPUBLICATION" . PHP_EOL;
echo "------------------------" . PHP_EOL;

try {
    $rapport->unpublish('Test de dépublication automatique');
    $rapport->refresh(); // Recharger depuis la DB
    
    echo "✅ Dépublication réussie!" . PHP_EOL;
    echo "is_published: " . ($rapport->is_published ? 'true' : 'false') . PHP_EOL;
    echo "published_at: " . ($rapport->published_at ? $rapport->published_at->format('Y-m-d H:i:s') : 'null') . PHP_EOL;
    echo "published_by: " . ($rapport->published_by ? $rapport->published_by : 'null') . PHP_EOL;
    echo "moderation_comment: " . ($rapport->moderation_comment ? $rapport->moderation_comment : 'null') . PHP_EOL;
    echo "publication_status: " . $rapport->publication_status . PHP_EOL;
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de la dépublication: " . $e->getMessage() . PHP_EOL;
}

// Attendre 2 secondes
sleep(2);

// TEST 3: Re-publication
echo PHP_EOL . "🔄 TEST 3: RE-PUBLICATION" . PHP_EOL;
echo "-------------------------" . PHP_EOL;

try {
    $rapport->publish($user, 'Test de re-publication automatique');
    $rapport->refresh(); // Recharger depuis la DB
    
    echo "✅ Re-publication réussie!" . PHP_EOL;
    echo "is_published: " . ($rapport->is_published ? 'true' : 'false') . PHP_EOL;
    echo "published_at: " . ($rapport->published_at ? $rapport->published_at->format('Y-m-d H:i:s') : 'null') . PHP_EOL;
    echo "published_by: " . ($rapport->published_by ? $rapport->published_by : 'null') . PHP_EOL;
    echo "moderation_comment: " . ($rapport->moderation_comment ? $rapport->moderation_comment : 'null') . PHP_EOL;
    echo "publication_status: " . $rapport->publication_status . PHP_EOL;
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de la re-publication: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "🔍 VÉRIFICATION EN BASE DE DONNÉES:" . PHP_EOL;
echo "-----------------------------------" . PHP_EOL;

// Récupérer directement depuis la DB
$rapportFromDB = Rapport::find($rapport->id);
echo "DB is_published: " . ($rapportFromDB->is_published ? 'true' : 'false') . PHP_EOL;
echo "DB published_at: " . ($rapportFromDB->published_at ? $rapportFromDB->published_at->format('Y-m-d H:i:s') : 'null') . PHP_EOL;
echo "DB published_by: " . ($rapportFromDB->published_by ? $rapportFromDB->published_by : 'null') . PHP_EOL;
echo "DB moderation_comment: " . ($rapportFromDB->moderation_comment ? $rapportFromDB->moderation_comment : 'null') . PHP_EOL;

echo PHP_EOL . "=== FIN DES TESTS ===" . PHP_EOL;

?>
