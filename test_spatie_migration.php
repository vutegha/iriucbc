<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test du système Spatie après migration ===\n\n";

try {
    // 1. Vérifier l'utilisateur admin
    $user = \App\Models\User::where('email', 'iri@ucbc.org')->first();
    if (!$user) {
        echo "❌ Utilisateur iri@ucbc.org non trouvé\n";
        exit(1);
    }

    echo "👤 Utilisateur: {$user->name} ({$user->email})\n";

    // 2. Vérifier les rôles
    $roles = $user->getRoleNames();
    echo "🏷️ Rôles actuels: " . $roles->implode(', ') . "\n";

    // 3. Assigner le rôle admin si nécessaire
    if (!$user->hasRole('admin')) {
        echo "🔧 Attribution du rôle admin...\n";
        $user->assignRole('admin');
        $roles = $user->getRoleNames();
        echo "✅ Rôles après assignation: " . $roles->implode(', ') . "\n";
    }

    // 4. Tester les permissions
    echo "\n📋 Test des permissions:\n";
    echo "  - Peut modérer: " . ($user->canModerate() ? 'Oui' : 'Non') . "\n";
    echo "  - Peut accéder admin: " . ($user->canAccessAdmin() ? 'Oui' : 'Non') . "\n";
    echo "  - Peut gérer utilisateurs: " . ($user->canManageUsers() ? 'Oui' : 'Non') . "\n";

    // 5. Lister toutes les permissions de l'utilisateur
    echo "\n🔑 Permissions spécifiques:\n";
    $permissions = $user->getPermissionsViaRoles();
    foreach ($permissions as $permission) {
        echo "  - {$permission->name}\n";
    }

    // 6. Vérifier les tables
    echo "\n📊 Vérification des tables:\n";
    $rolesCount = \Spatie\Permission\Models\Role::count();
    $permissionsCount = \Spatie\Permission\Models\Permission::count();
    echo "  - Rôles: {$rolesCount}\n";
    echo "  - Permissions: {$permissionsCount}\n";

    // 7. Supprimer l'ancien champ role si il existe encore
    if (Schema::hasColumn('users', 'role')) {
        echo "\n🗑️ Suppression de l'ancien champ 'role'...\n";
        Schema::table('users', function ($table) {
            $table->dropColumn('role');
        });
        echo "✅ Ancien champ 'role' supprimé\n";
    } else {
        echo "\n✅ Ancien champ 'role' déjà supprimé\n";
    }

    echo "\n🎉 Migration vers Spatie terminée avec succès !\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
