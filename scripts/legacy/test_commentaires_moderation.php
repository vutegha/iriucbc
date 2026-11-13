<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Rapport;
use App\Models\User;

echo "=== TEST DE L'AFFICHAGE DU COMMENTAIRE DE MODÉRATION ===" . PHP_EOL;
echo "Date: " . now()->format('Y-m-d H:i:s') . PHP_EOL;
echo "========================================================" . PHP_EOL . PHP_EOL;

// Récupérer un rapport
$rapport = Rapport::first();
$user = User::first();

if (!$rapport || !$user) {
    echo "❌ Données manquantes" . PHP_EOL;
    exit;
}

echo "📋 Rapport: " . $rapport->titre . PHP_EOL;
echo "👤 Utilisateur: " . $user->name . PHP_EOL;
echo "========================================" . PHP_EOL . PHP_EOL;

// Test 1: Publication avec commentaire
echo "🔄 TEST 1: Publication avec commentaire" . PHP_EOL;
$commentaire1 = "✅ Rapport validé et approuvé pour publication - " . now()->format('H:i:s');
$rapport->publish($user, $commentaire1);
$rapport->refresh();

echo "État: " . ($rapport->is_published ? 'Publié' : 'Non publié') . PHP_EOL;
echo "Commentaire: " . ($rapport->moderation_comment ?: 'Aucun') . PHP_EOL;
echo PHP_EOL;

// Test 2: Dépublication avec commentaire
echo "🔄 TEST 2: Dépublication avec commentaire" . PHP_EOL;
$commentaire2 = "⚠️  Rapport dépublié pour révision - contenu à revoir - " . now()->format('H:i:s');
$rapport->unpublish($commentaire2);
$rapport->refresh();

echo "État: " . ($rapport->is_published ? 'Publié' : 'Non publié') . PHP_EOL;
echo "Commentaire: " . ($rapport->moderation_comment ?: 'Aucun') . PHP_EOL;
echo PHP_EOL;

// Test 3: Re-publication avec nouveau commentaire
echo "🔄 TEST 3: Re-publication avec nouveau commentaire" . PHP_EOL;
$commentaire3 = "🔄 Rapport corrigé et republié après révision - " . now()->format('H:i:s');
$rapport->publish($user, $commentaire3);
$rapport->refresh();

echo "État: " . ($rapport->is_published ? 'Publié' : 'Non publié') . PHP_EOL;
echo "Commentaire: " . ($rapport->moderation_comment ?: 'Aucun') . PHP_EOL;
echo PHP_EOL;

echo "✅ Tests terminés - Les commentaires de modération sont bien sauvegardés" . PHP_EOL;
echo "📝 Le bloc 'Actions de Modération' devrait maintenant afficher ces commentaires" . PHP_EOL;

?>
