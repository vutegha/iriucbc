<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Media;

echo "=== Test des corrections de permissions média ===\n";

// Trouver l'utilisateur admin@ucbc.org
$user = User::where('email', 'admin@ucbc.org')->first();
if (!$user) {
    echo "❌ Utilisateur admin@ucbc.org introuvable\n";
    exit(1);
}

echo "✅ Utilisateur trouvé: {$user->name} ({$user->email})\n";
echo "Rôles: " . $user->getRoleNames()->implode(', ') . "\n\n";

// Trouver un média pour les tests
$media = Media::first();
if (!$media) {
    echo "❌ Aucun média trouvé pour les tests\n";
    exit(1);
}

echo "✅ Média trouvé: {$media->titre} (Status: {$media->status})\n\n";

$policy = new App\Policies\MediaPolicy();

echo "=== Tests des méthodes de politique ===\n";

// Tests des permissions
$tests = [
    'viewAny' => $policy->viewAny($user),
    'view' => $policy->view($user, $media),
    'create' => $policy->create($user),
    'update' => $policy->update($user, $media),
    'delete' => $policy->delete($user, $media),
    'moderate' => $policy->moderate($user, $media),
    'approve' => $policy->approve($user, $media),
    'reject' => $policy->reject($user, $media),
    'publish' => $policy->publish($user, $media),
    'copyLink' => $policy->copyLink($user, $media),
    'download' => $policy->download($user, $media),
];

foreach ($tests as $method => $result) {
    echo ($result ? '✅' : '❌') . " {$method}()\n";
}

echo "\n=== Tests des directives Blade avec Gate ===\n";

// Simuler les tests de Gate (équivalent aux @can dans les vues)
$gateTests = [
    'moderate' => auth()->user() ? Gate::allows('moderate', $media) : false,
    'update' => auth()->user() ? Gate::allows('update', $media) : false,
    'delete' => auth()->user() ? Gate::allows('delete', $media) : false,
    'approve' => auth()->user() ? Gate::allows('approve', $media) : false,
    'reject' => auth()->user() ? Gate::allows('reject', $media) : false,
    'publish' => auth()->user() ? Gate::allows('publish', $media) : false,
];

// Authentifier l'utilisateur pour les tests Gate
auth()->login($user);

foreach ($gateTests as $gate => $result) {
    $canAccess = Gate::allows($gate, $media);
    echo ($canAccess ? '✅' : '❌') . " Gate::allows('{$gate}', \$media)\n";
}

echo "\n🎉 Tests de permissions terminés!\n";
echo "Si toutes les permissions montrent ✅, les actions de modération devraient être visibles.\n";
