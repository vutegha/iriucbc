<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Media;
use Illuminate\Support\Facades\Gate;

echo "=== Test final des permissions média pour admin@ucbc.org ===\n";

// Trouver l'utilisateur admin@ucbc.org
$user = User::where('email', 'admin@ucbc.org')->first();
if (!$user) {
    echo "❌ Utilisateur admin@ucbc.org introuvable\n";
    exit(1);
}

// Authentifier l'utilisateur
auth()->login($user);

echo "✅ Utilisateur authentifié: {$user->name} ({$user->email})\n";
echo "Rôles: " . $user->getRoleNames()->implode(', ') . "\n\n";

// Trouver un média pour les tests
$media = Media::first();
if (!$media) {
    echo "❌ Aucun média trouvé pour les tests\n";
    exit(1);
}

echo "✅ Média de test: {$media->titre} (Status: {$media->status})\n\n";

echo "=== Tests des directives @can dans les vues ===\n";

// Tests correspondant aux @can dans les vues
$canTests = [
    'Modération (@can(\'moderate\', $media))' => Gate::allows('moderate', $media),
    'Mise à jour (@can(\'update\', $media))' => Gate::allows('update', $media),
    'Suppression (@can(\'delete\', $media))' => Gate::allows('delete', $media),
    'Création (@can(\'create\', Media::class))' => Gate::allows('create', App\Models\Media::class),
    'Approbation (@can(\'approve\', $media))' => Gate::allows('approve', $media),
    'Rejet (@can(\'reject\', $media))' => Gate::allows('reject', $media),
    'Publication (@can(\'publish\', $media))' => Gate::allows('publish', $media),
];

$passed = 0;
$total = count($canTests);

foreach ($canTests as $test => $result) {
    echo ($result ? '✅' : '❌') . " {$test}\n";
    if ($result) $passed++;
}

echo "\n=== Résumé ===\n";
echo "Tests réussis: {$passed}/{$total}\n";
echo "Pourcentage de réussite: " . round(($passed / $total) * 100) . "%\n";

if ($passed === $total) {
    echo "\n🎉 PARFAIT! Toutes les permissions fonctionnent correctement.\n";
    echo "Les actions de modération devraient maintenant être visibles dans la vue show.\n";
    echo "\nActions disponibles dans resources/views/admin/media/show.blade.php:\n";
    echo "• Section modération: Visible si @can('moderate', \$media) = ✅\n";
    echo "• Bouton Modifier: Visible si @can('update', \$media) = ✅\n";
    echo "• Section suppression: Visible si @can('delete', \$media) = ✅\n";
} else {
    echo "\n⚠️  Il y a encore des problèmes de permissions à résoudre.\n";
}

echo "\n=== URLs importantes ===\n";
echo "• Index des médias: /admin/media\n";
echo "• Créer un média: /admin/media/create\n";
echo "• Voir un média: /admin/media/{id}\n";
echo "• Modifier un média: /admin/media/{id}/edit\n";
