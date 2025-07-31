<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== AUDIT DES PERMISSIONS POUR sergyo.vutegha@gmail.com ===\n\n";

// 1. Vérifier l'existence de l'utilisateur
$user = User::where('email', 'sergyo.vutegha@gmail.com')->first();

if (!$user) {
    echo "❌ UTILISATEUR NON TROUVÉ\n";
    echo "L'email sergyo.vutegha@gmail.com n'existe pas dans la base de données.\n\n";
    
    // Afficher tous les utilisateurs existants
    echo "📋 UTILISATEURS EXISTANTS :\n";
    $users = User::all();
    foreach ($users as $u) {
        echo "  - {$u->name} ({$u->email}) - " . ($u->is_active ? 'Actif' : 'Inactif') . "\n";
    }
    exit;
}

echo "✅ UTILISATEUR TROUVÉ\n";
echo "Nom: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "ID: {$user->id}\n";
echo "Statut: " . ($user->is_active ? 'Actif' : 'Inactif') . "\n";
echo "Email vérifié: " . ($user->email_verified_at ? 'Oui (' . $user->email_verified_at . ')' : 'Non') . "\n";
echo "Créé le: {$user->created_at}\n";
echo "Dernière mise à jour: {$user->updated_at}\n\n";

// 2. Vérifier les rôles assignés
echo "🔑 RÔLES ASSIGNÉS :\n";
$roles = $user->roles;
if ($roles->count() > 0) {
    foreach ($roles as $role) {
        echo "  - {$role->name} (Garde: {$role->guard_name})\n";
    }
} else {
    echo "  ❌ Aucun rôle assigné\n";
}
echo "\n";

// 3. Vérifier les permissions directes
echo "🛡️ PERMISSIONS DIRECTES :\n";
$directPermissions = $user->permissions;
if ($directPermissions->count() > 0) {
    foreach ($directPermissions as $permission) {
        echo "  - {$permission->name} (Garde: {$permission->guard_name})\n";
    }
} else {
    echo "  ⚠️ Aucune permission directe\n";
}
echo "\n";

// 4. Vérifier toutes les permissions (rôles + directes)
echo "🔐 TOUTES LES PERMISSIONS (via rôles + directes) :\n";
$allPermissions = $user->getAllPermissions();
if ($allPermissions->count() > 0) {
    $groupedPermissions = [];
    foreach ($allPermissions as $permission) {
        $parts = explode(' ', $permission->name);
        $action = $parts[0] ?? 'other';
        $resource = $parts[1] ?? 'general';
        
        if (!isset($groupedPermissions[$resource])) {
            $groupedPermissions[$resource] = [];
        }
        $groupedPermissions[$resource][] = $action;
    }
    
    foreach ($groupedPermissions as $resource => $actions) {
        echo "  📁 {$resource}:\n";
        foreach ($actions as $action) {
            echo "    ✓ {$action}\n";
        }
    }
} else {
    echo "  ❌ Aucune permission\n";
}
echo "\n";

// 5. Tester des permissions spécifiques importantes
echo "🧪 TEST DE PERMISSIONS CRITIQUES :\n";
$criticalPermissions = [
    'view admin',
    'manage users',
    'create users',
    'update users',
    'delete users',
    'view evenements',
    'create evenements',
    'update evenements',
    'delete evenements',
    'moderate evenements',
    'publish evenements',
    'view services',
    'create services',
    'update services',
    'delete services',
    'view projets',
    'create projets',
    'update projets',
    'delete projets',
    'view actualites',
    'create actualites',
    'update actualites',
    'delete actualites',
    'moderate actualites',
    'publish actualites',
];

foreach ($criticalPermissions as $permission) {
    $hasPermission = $user->can($permission);
    $status = $hasPermission ? '✅' : '❌';
    echo "  {$status} {$permission}\n";
}
echo "\n";

// 6. Vérifier les restrictions spécifiques
echo "🚫 VÉRIFICATION DES RESTRICTIONS :\n";
echo "  - Compte actif: " . ($user->is_active ? '✅ Oui' : '❌ Non') . "\n";
echo "  - Email vérifié: " . ($user->email_verified_at ? '✅ Oui' : '❌ Non') . "\n";

// Vérifier s'il y a des restrictions de connexion
if (method_exists($user, 'login_attempts')) {
    echo "  - Tentatives de connexion: {$user->login_attempts}\n";
}

if (method_exists($user, 'locked_until')) {
    echo "  - Verrouillé jusqu'à: " . ($user->locked_until ? $user->locked_until : 'Non verrouillé') . "\n";
}

// 7. Informations sur la structure des permissions
echo "\n📊 STATISTIQUES DU SYSTÈME DE PERMISSIONS :\n";
echo "  - Total des rôles: " . Role::count() . "\n";
echo "  - Total des permissions: " . Permission::count() . "\n";
echo "  - Total des utilisateurs: " . User::count() . "\n";

// 8. Lister tous les rôles disponibles
echo "\n🎭 RÔLES DISPONIBLES DANS LE SYSTÈME :\n";
$allRoles = Role::all();
foreach ($allRoles as $role) {
    $permCount = $role->permissions->count();
    echo "  - {$role->name} ({$permCount} permissions)\n";
}

// 9. Recommandations de sécurité
echo "\n💡 RECOMMANDATIONS DE SÉCURITÉ :\n";

if (!$user->is_active) {
    echo "  ⚠️  Compte inactif - L'utilisateur ne peut pas se connecter\n";
}

if (!$user->email_verified_at) {
    echo "  ⚠️  Email non vérifié - Fonctionnalités limitées possibles\n";
}

if ($roles->count() === 0 && $directPermissions->count() === 0) {
    echo "  🚨 CRITIQUE: Aucune permission assignée - L'utilisateur ne peut rien faire\n";
}

if ($user->can('manage users') || $user->can('view admin')) {
    echo "  ⚠️  Accès administrateur détecté - Surveiller l'activité\n";
}

echo "\n=== FIN DE L'AUDIT ===\n";
