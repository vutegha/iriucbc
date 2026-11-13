<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

echo "=== ANALYSE DES PERMISSIONS: admin@ucbc.org ===\n";
echo "Date: " . now()->format('d/m/Y H:i:s') . "\n\n";

// 1. Trouver l'utilisateur
echo "👤 1. RECHERCHE DE L'UTILISATEUR\n";
echo str_repeat("=", 50) . "\n";

$user = User::where('email', 'admin@ucbc.org')->first();

if (!$user) {
    echo "❌ Utilisateur admin@ucbc.org introuvable!\n\n";
    
    // Afficher les utilisateurs disponibles
    echo "📋 Utilisateurs disponibles:\n";
    $users = User::select('id', 'name', 'email')->get();
    foreach ($users as $u) {
        echo "  • {$u->name} ({$u->email}) [ID: {$u->id}]\n";
    }
    exit;
} else {
    echo "✅ Utilisateur trouvé:\n";
    echo "  • ID: {$user->id}\n";
    echo "  • Nom: {$user->name}\n";
    echo "  • Email: {$user->email}\n";
    echo "  • Créé le: " . $user->created_at->format('d/m/Y H:i') . "\n\n";
}

// 2. Analyser les rôles de l'utilisateur
echo "🏷️ 2. RÔLES ASSIGNÉS\n";
echo str_repeat("=", 50) . "\n";

$userRoles = $user->getRoleNames();

if ($userRoles->count() > 0) {
    echo "Rôles assignés (" . $userRoles->count() . "):\n";
    foreach ($userRoles as $role) {
        echo "  • $role\n";
    }
    echo "\n";
} else {
    echo "⚠️ Aucun rôle assigné à cet utilisateur!\n\n";
}

// 3. Analyser les permissions directes
echo "🔐 3. PERMISSIONS DIRECTES\n";
echo str_repeat("=", 50) . "\n";

$directPermissions = $user->getDirectPermissions();

if ($directPermissions->count() > 0) {
    echo "Permissions directes (" . $directPermissions->count() . "):\n";
    foreach ($directPermissions as $perm) {
        echo "  • {$perm->name} (guard: {$perm->guard_name})\n";
    }
    echo "\n";
} else {
    echo "ℹ️ Aucune permission directe assignée\n\n";
}

// 4. Analyser toutes les permissions (via rôles + directes)
echo "🎯 4. TOUTES LES PERMISSIONS\n";
echo str_repeat("=", 50) . "\n";

$allPermissions = $user->getAllPermissions();

if ($allPermissions->count() > 0) {
    echo "Total des permissions (" . $allPermissions->count() . "):\n";
    
    // Grouper par modèle
    $permissionsByModel = [
        'actualites' => [],
        'publications' => [],
        'projets' => [],
        'autres' => []
    ];
    
    foreach ($allPermissions as $perm) {
        if (strpos($perm->name, 'actualite') !== false) {
            $permissionsByModel['actualites'][] = $perm->name;
        } elseif (strpos($perm->name, 'publication') !== false) {
            $permissionsByModel['publications'][] = $perm->name;
        } elseif (strpos($perm->name, 'projet') !== false) {
            $permissionsByModel['projets'][] = $perm->name;
        } else {
            $permissionsByModel['autres'][] = $perm->name;
        }
    }
    
    foreach ($permissionsByModel as $model => $permissions) {
        if (!empty($permissions)) {
            echo "\n📋 " . strtoupper($model) . " (" . count($permissions) . "):\n";
            foreach ($permissions as $perm) {
                echo "  • $perm\n";
            }
        }
    }
    echo "\n";
} else {
    echo "❌ Aucune permission trouvée!\n\n";
}

// 5. Tester des permissions spécifiques pour actualités et publications
echo "🧪 5. TESTS DE PERMISSIONS SPÉCIFIQUES\n";
echo str_repeat("=", 50) . "\n";

$testPermissions = [
    'Actualités' => [
        'view_actualites',
        'create_actualite', 
        'update_actualite',
        'delete_actualite',
        'moderate_actualites',
        'publish_actualites',
        'unpublish_actualites'
    ],
    'Publications' => [
        'view_publications',
        'create_publication',
        'update_publication', 
        'delete_publication',
        'moderate_publications',
        'publish_publications',
        'unpublish_publications'
    ],
    'Projets' => [
        'view_projets',
        'create_projet',
        'update_projet',
        'delete_projet', 
        'moderate_projets',
        'publish_projets',
        'unpublish_projets'
    ]
];

foreach ($testPermissions as $module => $permissions) {
    echo "📊 $module:\n";
    $hasCount = 0;
    foreach ($permissions as $perm) {
        $hasPermission = $user->hasPermissionTo($perm, 'web');
        echo "  " . ($hasPermission ? "✅" : "❌") . " $perm\n";
        if ($hasPermission) $hasCount++;
    }
    echo "  → Accès: $hasCount/" . count($permissions) . " permissions\n\n";
}

// 6. Vérifier les rôles hiérarchiques
echo "🏆 6. VÉRIFICATION DES RÔLES HIÉRARCHIQUES\n";
echo str_repeat("=", 50) . "\n";

$roleChecks = [
    'super-admin' => $user->hasRole('super-admin', 'web'),
    'admin' => $user->hasRole('admin', 'web'), 
    'moderator' => $user->hasRole('moderator', 'web'),
    'gestionnaire_projets' => $user->hasRole('gestionnaire_projets', 'web'),
    'contributeur' => $user->hasRole('contributeur', 'web'),
    'user' => $user->hasRole('user', 'web')
];

foreach ($roleChecks as $role => $hasRole) {
    echo "  " . ($hasRole ? "✅" : "❌") . " $role\n";
}
echo "\n";

// 7. Recommandations
echo "💡 7. ANALYSE ET RECOMMANDATIONS\n";
echo str_repeat("=", 50) . "\n";

$totalPermissions = $allPermissions->count();
$hasAdminRole = $user->hasRole('admin', 'web');
$hasSuperAdminRole = $user->hasRole('super-admin', 'web');

echo "📈 STATISTIQUES:\n";
echo "  • Total permissions: $totalPermissions\n";
echo "  • Rôles assignés: " . $userRoles->count() . "\n";
echo "  • Est admin: " . ($hasAdminRole ? "Oui" : "Non") . "\n";
echo "  • Est super-admin: " . ($hasSuperAdminRole ? "Oui" : "Non") . "\n\n";

echo "🎯 ACCÈS AUX MODULES:\n";
if ($totalPermissions >= 15) {
    echo "  ✅ Accès complet aux principales fonctionnalités\n";
} elseif ($totalPermissions >= 10) {
    echo "  🟡 Accès partiel aux fonctionnalités\n";
} else {
    echo "  ❌ Accès limité aux fonctionnalités\n";
}

if ($hasSuperAdminRole) {
    echo "  🔥 Super-admin: Accès total à toutes les fonctionnalités\n";
} elseif ($hasAdminRole) {
    echo "  👑 Admin: Accès étendu aux fonctionnalités principales\n";
}

echo "\n" . str_repeat("=", 55) . "\n";
