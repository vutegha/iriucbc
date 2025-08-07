<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Media;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

echo "=== DIAGNOSTIC COMPLET DES PERMISSIONS MÉDIA ===\n\n";

// 1. Vérifier l'utilisateur
$user = User::where('email', 'admin@ucbc.org')->first();
if (!$user) {
    echo "❌ Utilisateur admin@ucbc.org introuvable\n";
    
    // Chercher d'autres utilisateurs admin
    $adminUsers = User::where('email', 'like', '%admin%')->get();
    echo "Utilisateurs avec 'admin' dans l'email:\n";
    foreach ($adminUsers as $adminUser) {
        echo "- {$adminUser->name} ({$adminUser->email}) - Rôles: " . $adminUser->getRoleNames()->implode(', ') . "\n";
    }
    exit(1);
}

echo "✅ Utilisateur trouvé: {$user->name} ({$user->email})\n";
echo "ID: {$user->id}\n";
echo "Rôles: " . $user->getRoleNames()->implode(', ') . "\n\n";

// 2. Vérifier l'enregistrement de la politique
echo "=== VÉRIFICATION DE L'ENREGISTREMENT DES POLITIQUES ===\n";
$policies = Gate::policies();
echo "Politiques enregistrées:\n";
foreach ($policies as $model => $policy) {
    if (str_contains($model, 'Media')) {
        echo "- {$model} => {$policy}\n";
    }
}

// Vérifier si MediaPolicy est bien enregistrée
$mediaPolicy = Gate::getPolicyFor(App\Models\Media::class);
echo "Politique pour App\\Models\\Media: " . ($mediaPolicy ? get_class($mediaPolicy) : 'Non trouvée') . "\n\n";

// 3. Trouver un média de test
$media = Media::first();
if (!$media) {
    echo "❌ Aucun média trouvé dans la base de données\n";
    echo "Créons un média de test...\n";
    
    $media = new Media();
    $media->titre = 'Test Media';
    $media->medias = 'test.jpg';
    $media->status = 'pending';
    $media->is_public = false;
    $media->created_by = $user->id;
    $media->save();
    
    echo "✅ Média de test créé avec ID: {$media->id}\n";
} else {
    echo "✅ Média de test trouvé: {$media->titre} (ID: {$media->id}, Status: {$media->status})\n";
}

// 4. Authentifier l'utilisateur
auth()->login($user);
echo "✅ Utilisateur authentifié\n\n";

// 5. Tests détaillés des permissions
echo "=== TESTS DÉTAILLÉS DES PERMISSIONS ===\n";

// Test avec la politique directement
$policy = new App\Policies\MediaPolicy();
echo "Tests avec politique directe:\n";
$directTests = [
    'viewAny' => $policy->viewAny($user),
    'view' => $policy->view($user, $media),
    'create' => $policy->create($user),
    'update' => $policy->update($user, $media),
    'delete' => $policy->delete($user, $media),
    'moderate' => $policy->moderate($user, $media),
    'approve' => $policy->approve($user, $media),
    'reject' => $policy->reject($user, $media),
    'publish' => $policy->publish($user, $media),
];

foreach ($directTests as $method => $result) {
    $status = $result ? '✅' : '❌';
    echo "  {$status} {$method}()\n";
}

echo "\nTests avec Gate (équivalent @can dans les vues):\n";
$gateTests = [
    'moderate' => Gate::allows('moderate', $media),
    'update' => Gate::allows('update', $media),
    'delete' => Gate::allows('delete', $media),
    'approve' => Gate::allows('approve', $media),
    'reject' => Gate::allows('reject', $media),
    'publish' => Gate::allows('publish', $media),
];

foreach ($gateTests as $gate => $result) {
    $status = $result ? '✅' : '❌';
    echo "  {$status} Gate::allows('{$gate}', \$media)\n";
}

// 6. Vérifier les permissions Spatie directement
echo "\n=== VÉRIFICATION DES PERMISSIONS SPATIE ===\n";
$spatiePermissions = [
    'moderate_media',
    'moderate_medias', 
    'update_media',
    'delete_media',
    'approve_media',
    'reject_media',
    'publish_media'
];

foreach ($spatiePermissions as $perm) {
    $hasPermission = $user->hasPermissionTo($perm, 'web');
    $status = $hasPermission ? '✅' : '❌';
    echo "  {$status} {$perm}\n";
}

// 7. Vérifier la structure de la base de données
echo "\n=== VÉRIFICATION DE LA BASE DE DONNÉES ===\n";
echo "Structure de la table medias:\n";
$columns = DB::select("DESCRIBE medias");
foreach ($columns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";
}

echo "\nStatus du média de test: {$media->status}\n";
echo "Créé par: {$media->created_by}\n";
echo "Is public: " . ($media->is_public ? 'true' : 'false') . "\n";

// 8. Test spécifique pour la condition @can('moderate', $media)
echo "\n=== TEST SPÉCIFIQUE POUR @can('moderate', \$media) ===\n";
try {
    $canModerate = Gate::forUser($user)->allows('moderate', $media);
    echo "Gate::forUser(\$user)->allows('moderate', \$media): " . ($canModerate ? '✅ TRUE' : '❌ FALSE') . "\n";
} catch (Exception $e) {
    echo "❌ ERREUR lors du test Gate: " . $e->getMessage() . "\n";
}

// 9. Vérifier si l'AuthServiceProvider enregistre bien la politique
echo "\n=== VÉRIFICATION DE L'AUTHSERVICEPROVIDER ===\n";
$authServiceProvider = file_get_contents(app_path('Providers/AuthServiceProvider.php'));
if (str_contains($authServiceProvider, 'Media::class') && str_contains($authServiceProvider, 'MediaPolicy::class')) {
    echo "✅ MediaPolicy semble être enregistrée dans AuthServiceProvider\n";
} else {
    echo "❌ MediaPolicy pourrait ne pas être enregistrée dans AuthServiceProvider\n";
    echo "Vérifiez le fichier app/Providers/AuthServiceProvider.php\n";
}

echo "\n=== RÉSUMÉ DU DIAGNOSTIC ===\n";
echo "Si les tests Gate montrent ❌, le problème est dans l'enregistrement des politiques.\n";
echo "Si les tests Gate montrent ✅, le problème est ailleurs (cache, sessions, etc.)\n";
