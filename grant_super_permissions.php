<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Bootstrap Laravel
$app = new Application(realpath(__DIR__));

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🚀 ATTRIBUTION SUPER PERMISSIONS À sergyo.vutegha@gmail.com\n";
echo "============================================================\n\n";

try {
    // Rechercher l'utilisateur
    $user = User::where('email', 'sergyo.vutegha@gmail.com')->first();
    
    if (!$user) {
        echo "❌ Utilisateur sergyo.vutegha@gmail.com non trouvé.\n";
        echo "💡 Création de l'utilisateur...\n\n";
        
        $user = User::create([
            'name' => 'Sergyo Vutegha',
            'email' => 'sergyo.vutegha@gmail.com',
            'password' => bcrypt('SuperSecure123!'),
            'email_verified_at' => now(),
        ]);
        
        echo "✅ Utilisateur créé avec succès !\n";
        echo "📧 Email: sergyo.vutegha@gmail.com\n";
        echo "🔐 Mot de passe temporaire: SuperSecure123!\n\n";
    } else {
        echo "✅ Utilisateur trouvé: {$user->name} ({$user->email})\n\n";
    }

    // Récupérer tous les rôles existants
    $allRoles = Role::all();
    echo "📋 RÔLES DISPONIBLES (" . $allRoles->count() . "):\n";
    echo "=====================================\n";
    
    foreach ($allRoles as $role) {
        echo "  🏷️  {$role->name}\n";
    }
    echo "\n";

    // Récupérer toutes les permissions existantes
    $allPermissions = Permission::all();
    echo "🔐 PERMISSIONS DISPONIBLES (" . $allPermissions->count() . "):\n";
    echo "==========================================\n";
    
    foreach ($allPermissions as $permission) {
        echo "  🔑 {$permission->name}\n";
    }
    echo "\n";

    // Assigner TOUS les rôles à l'utilisateur
    echo "👑 ATTRIBUTION DES RÔLES...\n";
    echo "============================\n";
    
    $roleNames = $allRoles->pluck('name')->toArray();
    $user->syncRoles($roleNames);
    
    foreach ($roleNames as $roleName) {
        echo "  ✅ Rôle assigné: {$roleName}\n";
    }
    echo "\n";

    // Assigner TOUTES les permissions directement à l'utilisateur
    echo "🔑 ATTRIBUTION DES PERMISSIONS DIRECTES...\n";
    echo "==========================================\n";
    
    $permissionNames = $allPermissions->pluck('name')->toArray();
    $user->syncPermissions($permissionNames);
    
    foreach ($permissionNames as $permissionName) {
        echo "  ✅ Permission assignée: {$permissionName}\n";
    }
    echo "\n";

    // Vérification finale
    echo "🔍 VÉRIFICATION FINALE...\n";
    echo "=========================\n";
    
    // Recharger l'utilisateur avec ses relations
    $user->refresh();
    $user->load('roles', 'permissions');
    
    echo "👤 Utilisateur: {$user->name}\n";
    echo "📧 Email: {$user->email}\n";
    echo "👑 Rôles: " . $user->roles->count() . " assignés\n";
    echo "🔑 Permissions directes: " . $user->permissions->count() . " assignées\n\n";

    // Tester quelques permissions importantes
    $testPermissions = [
        'access admin',
        'manage system',
        'manage users',
        'moderate_content',
        'publish_content',
        'manage services',
        'manage actualites',
        'manage publications'
    ];

    echo "🧪 TEST DES PERMISSIONS CRITIQUES...\n";
    echo "====================================\n";
    
    foreach ($testPermissions as $permission) {
        if ($user->can($permission)) {
            echo "  ✅ {$permission}\n";
        } else {
            echo "  ❌ {$permission}\n";
        }
    }
    echo "\n";

    // Créer/Mettre à jour le rôle super admin si nécessaire
    echo "🦸 CRÉATION DU RÔLE SUPER ADMIN...\n";
    echo "==================================\n";
    
    $superAdminRole = Role::firstOrCreate([
        'name' => 'super-admin',
        'guard_name' => 'web'
    ]);
    
    // Assigner toutes les permissions au rôle super-admin
    $superAdminRole->syncPermissions($permissionNames);
    echo "  ✅ Rôle 'super-admin' créé/mis à jour avec toutes les permissions\n";
    
    // Assigner le rôle super-admin à l'utilisateur (en plus des autres)
    $user->assignRole('super-admin');
    echo "  ✅ Rôle 'super-admin' assigné à l'utilisateur\n\n";

    echo "🎉 MISSION ACCOMPLIE !\n";
    echo "======================\n";
    echo "L'utilisateur sergyo.vutegha@gmail.com a maintenant :\n";
    echo "  👑 Tous les rôles du système (" . $user->roles->count() . ")\n";
    echo "  🔑 Toutes les permissions directes (" . $user->permissions->count() . ")\n";
    echo "  🦸 Le rôle spécial 'super-admin'\n";
    echo "  💯 Accès complet à tout le système\n\n";

    echo "🔐 INFORMATIONS DE CONNEXION :\n";
    echo "==============================\n";
    echo "📧 Email: sergyo.vutegha@gmail.com\n";
    echo "🔐 Mot de passe: " . ($user->wasRecentlyCreated ? "SuperSecure123! (temporaire)" : "mot de passe existant") . "\n";
    echo "🌐 URL Admin: /admin\n\n";

    echo "⚠️  SÉCURITÉ IMPORTANTE :\n";
    echo "========================\n";
    echo "Cet utilisateur a un accès COMPLET au système.\n";
    echo "Assurez-vous que le mot de passe est sécurisé !\n\n";

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "📍 Ligne: " . $e->getLine() . "\n";
    echo "📁 Fichier: " . $e->getFile() . "\n";
}
