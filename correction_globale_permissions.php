<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== CORRECTION GLOBALE DES PERMISSIONS - SYSTÈME COMPLET ===\n\n";

// Fonction pour demander confirmation
function askConfirmation($message) {
    echo $message . " (y/N): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    fclose($handle);
    return trim(strtolower($line)) === 'y';
}

// 1. Récupération des utilisateurs problématiques
$problematicUsers = User::with(['roles', 'permissions'])
    ->whereHas('permissions') // Users avec permissions directes
    ->orWhereHas('roles', function($query) {
        $query->havingRaw('COUNT(*) > 2'); // Users avec plus de 2 rôles
    })
    ->get();

echo "🔍 UTILISATEURS PROBLÉMATIQUES IDENTIFIÉS: " . $problematicUsers->count() . "\n\n";

foreach ($problematicUsers as $user) {
    echo "👤 {$user->name} ({$user->email})\n";
    echo "   Rôles: " . $user->roles->count() . " (" . $user->roles->pluck('name')->implode(', ') . ")\n";
    echo "   Permissions directes: " . $user->permissions->count() . "\n";
    echo "   Email vérifié: " . ($user->email_verified_at ? 'Oui' : 'Non') . "\n";
    echo "   Statut: " . ($user->is_active ?? true ? 'Actif' : 'Inactif') . "\n\n";
}

if (!askConfirmation("Procéder à la correction automatique?")) {
    echo "❌ Correction annulée par l'utilisateur.\n";
    exit;
}

$corrections = [];

// 2. PHASE 1: Suppression des permissions directes
echo "\n🧹 PHASE 1: SUPPRESSION DES PERMISSIONS DIRECTES\n";
$usersWithDirectPermissions = User::whereHas('permissions')->get();

foreach ($usersWithDirectPermissions as $user) {
    $permissionCount = $user->permissions->count();
    echo "  • {$user->name}: suppression de {$permissionCount} permissions directes... ";
    
    $user->permissions()->detach();
    echo "✅\n";
    
    $corrections[] = [
        'user_id' => $user->id,
        'action' => 'removed_direct_permissions',
        'count' => $permissionCount
    ];
}

// 3. PHASE 2: Simplification des rôles multiples
echo "\n🎭 PHASE 2: SIMPLIFICATION DES RÔLES MULTIPLES\n";

// Définition des hiérarchies de rôles (du plus élevé au plus bas)
$roleHierarchy = [
    'super-admin' => 6,
    'admin' => 5,
    'gestionnaire_projets' => 4,
    'moderator' => 3,
    'editor' => 2,
    'user' => 1
];

$usersWithMultipleRoles = User::whereHas('roles', function($query) {
    $query->havingRaw('COUNT(*) > 1');
})->with('roles')->get();

foreach ($usersWithMultipleRoles as $user) {
    $currentRoles = $user->roles->pluck('name')->toArray();
    echo "  • {$user->name}: rôles actuels [" . implode(', ', $currentRoles) . "]... ";
    
    // Trouver le rôle le plus élevé
    $highestRole = null;
    $highestLevel = 0;
    
    foreach ($currentRoles as $roleName) {
        if (isset($roleHierarchy[$roleName]) && $roleHierarchy[$roleName] > $highestLevel) {
            $highestLevel = $roleHierarchy[$roleName];
            $highestRole = $roleName;
        }
    }
    
    if ($highestRole) {
        // Supprimer tous les rôles
        $user->roles()->detach();
        
        // Assigner uniquement le rôle le plus élevé
        $user->assignRole($highestRole);
        echo "conservé uniquement '{$highestRole}' ✅\n";
        
        $corrections[] = [
            'user_id' => $user->id,
            'action' => 'simplified_roles',
            'from' => $currentRoles,
            'to' => $highestRole
        ];
    } else {
        echo "aucun rôle reconnu ⚠️\n";
    }
}

// 4. PHASE 3: Gestion des comptes non vérifiés avec privilèges
echo "\n📧 PHASE 3: COMPTES ADMINISTRATEURS NON VÉRIFIÉS\n";

$unverifiedAdmins = User::whereNull('email_verified_at')
    ->whereHas('roles', function($query) {
        $query->whereIn('name', ['admin', 'super-admin', 'moderator']);
    })->get();

foreach ($unverifiedAdmins as $user) {
    echo "  • {$user->name} ({$user->email}): compte admin non vérifié\n";
    
    if (askConfirmation("    Suspendre les privilèges jusqu'à vérification?")) {
        // Sauvegarder les rôles actuels
        $currentRoles = $user->roles->pluck('name')->toArray();
        
        // Retirer tous les rôles privilégiés
        $user->roles()->detach();
        
        // Assigner uniquement le rôle user
        $user->assignRole('user');
        
        echo "    ✅ Privilèges suspendus, rôle 'user' assigné\n";
        
        $corrections[] = [
            'user_id' => $user->id,
            'action' => 'suspended_unverified_admin',
            'suspended_roles' => $currentRoles
        ];
    } else {
        echo "    ⏭️ Ignoré\n";
    }
}

// 5. PHASE 4: Audit des comptes inactifs
echo "\n🔒 PHASE 4: NETTOYAGE DES COMPTES INACTIFS\n";

// Vérifier si la colonne is_active existe
$hasActiveColumn = Schema::hasColumn('users', 'is_active');

if ($hasActiveColumn) {
    $inactiveUsersWithPermissions = User::where('is_active', false)
        ->where(function($query) {
            $query->whereHas('roles')->orWhereHas('permissions');
        })->get();
    
    foreach ($inactiveUsersWithPermissions as $user) {
        echo "  • {$user->name}: compte inactif avec permissions... ";
        
        $user->roles()->detach();
        $user->permissions()->detach();
        echo "✅ permissions supprimées\n";
        
        $corrections[] = [
            'user_id' => $user->id,
            'action' => 'cleaned_inactive_account'
        ];
    }
} else {
    echo "  ⚠️ Colonne 'is_active' non trouvée, phase ignorée\n";
}

// 6. GÉNÉRATION DU RAPPORT DE CORRECTION
echo "\n📋 GÉNÉRATION DU RAPPORT DE CORRECTION\n";

$report = [
    'correction_date' => now()->toISOString(),
    'total_corrections' => count($corrections),
    'corrections_by_type' => [],
    'detailed_corrections' => $corrections
];

// Grouper par type de correction
foreach ($corrections as $correction) {
    $type = $correction['action'];
    if (!isset($report['corrections_by_type'][$type])) {
        $report['corrections_by_type'][$type] = 0;
    }
    $report['corrections_by_type'][$type]++;
}

$reportFile = 'correction_globale_permissions_' . date('Y-m-d_H-i-s') . '.json';
file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT));

echo "✅ Rapport de correction sauvegardé: {$reportFile}\n";

// 7. ÉTAT FINAL DU SYSTÈME
echo "\n🎯 ÉTAT FINAL DU SYSTÈME\n";

$finalStats = [
    'total_users' => User::count(),
    'users_with_direct_permissions' => User::whereHas('permissions')->count(),
    'users_with_multiple_roles' => User::whereHas('roles', function($query) {
        $query->havingRaw('COUNT(*) > 1');
    })->count(),
    'unverified_admins' => User::whereNull('email_verified_at')
        ->whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'super-admin']);
        })->count()
];

echo "• Utilisateurs total: {$finalStats['total_users']}\n";
echo "• Avec permissions directes: {$finalStats['users_with_direct_permissions']}\n";
echo "• Avec rôles multiples: {$finalStats['users_with_multiple_roles']}\n";
echo "• Admins non vérifiés: {$finalStats['unverified_admins']}\n";

// 8. RECOMMANDATIONS POST-CORRECTION
echo "\n🛡️ RECOMMANDATIONS POST-CORRECTION\n";
echo "1. Exécuter un nouvel audit global pour vérifier les corrections\n";
echo "2. Implémenter un système de logging des changements de permissions\n";
echo "3. Créer un processus d'approbation pour les rôles sensibles\n";
echo "4. Planifier des audits périodiques (mensuel recommandé)\n";
echo "5. Former les administrateurs sur les bonnes pratiques de sécurité\n";

echo "\n✅ CORRECTION GLOBALE TERMINÉE\n";
echo "📁 Fichiers générés:\n";
echo "   - {$reportFile}\n";
echo "   - audit_global_permissions_*.json (audit précédent)\n";

echo "\n🔄 COMMANDE POUR VÉRIFICATION:\n";
echo "php audit_global_permissions.php\n";
