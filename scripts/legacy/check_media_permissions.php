<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "=== Vérification des permissions média ===\n";

// Trouver l'utilisateur admin@ucbc.org
$user = User::where('email', 'admin@ucbc.org')->first();
if (!$user) {
    echo "❌ Utilisateur admin@ucbc.org introuvable\n";
    
    // Lister tous les utilisateurs avec email contenant admin
    echo "\n=== Utilisateurs admin disponibles ===\n";
    $adminUsers = User::where('email', 'like', '%admin%')->get();
    foreach ($adminUsers as $adminUser) {
        echo "- {$adminUser->name} ({$adminUser->email})\n";
    }
    exit(1);
}

echo "✅ Utilisateur trouvé: {$user->name} ({$user->email})\n";
echo "Rôles: " . $user->getRoleNames()->implode(', ') . "\n\n";

// Vérifier les permissions média
echo "=== Permissions média existantes ===\n";
$mediaPermissions = Permission::where('name', 'like', '%media%')->get();
foreach ($mediaPermissions as $permission) {
    $hasPermission = $user->hasPermissionTo($permission->name, 'web') ? '✅' : '❌';
    echo "{$hasPermission} {$permission->name}\n";
}

echo "\n=== Test des politiques ===\n";
$policy = new App\Policies\MediaPolicy();

// Test modération
$canModerate = $policy->moderate($user);
echo ($canModerate ? '✅' : '❌') . " moderate()\n";

// Test publication  
$media = App\Models\Media::first();
if ($media) {
    $canPublish = $policy->publish($user, $media);
    echo ($canPublish ? '✅' : '❌') . " publish()\n";
}

echo "\n=== Permissions spécifiques recherchées ===\n";
$searchPermissions = ['moderate_media', 'moderate_medias', 'update_media', 'delete_media'];
foreach ($searchPermissions as $perm) {
    $exists = Permission::where('name', $perm)->exists();
    $hasIt = $user->hasPermissionTo($perm, 'web');
    echo "$perm: " . ($exists ? 'Existe' : 'N\'existe pas') . " | " . ($hasIt ? 'Accordée' : 'Non accordée') . "\n";
}

echo "\n=== Tous les rôles et permissions ===\n";
$allRoles = Role::all();
foreach ($allRoles as $role) {
    echo "Rôle: {$role->name}\n";
    $rolePermissions = $role->permissions->pluck('name');
    foreach ($rolePermissions as $perm) {
        if (str_contains($perm, 'media')) {
            echo "  - $perm\n";
        }
    }
}
