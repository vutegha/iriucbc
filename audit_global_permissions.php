<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== AUDIT GLOBAL DES PERMISSIONS - TOUS LES UTILISATEURS ===\n\n";

$users = User::with(['roles', 'permissions'])->get();
$totalUsers = $users->count();

echo "📊 STATISTIQUES GÉNÉRALES:\n";
echo "Total utilisateurs: {$totalUsers}\n\n";

$alerts = [];
$statistics = [
    'users_with_multiple_roles' => 0,
    'users_with_direct_permissions' => 0,
    'inactive_users_with_permissions' => 0,
    'unverified_users_with_admin' => 0,
    'super_privileged_users' => 0
];

echo "🔍 ANALYSE PAR UTILISATEUR:\n";
echo str_repeat("=", 80) . "\n";

foreach ($users as $user) {
    $rolesCount = $user->roles->count();
    $directPermissionsCount = $user->permissions->count();
    $totalPermissions = $user->getAllPermissions()->count();
    $hasAdminRole = $user->hasRole(['admin', 'super-admin']);
    $isActive = $user->is_active ?? true; // par défaut actif si pas de colonne
    $emailVerified = $user->email_verified_at !== null;
    
    $userAlerts = [];
    
    // Détection des anomalies
    if ($rolesCount > 2) {
        $statistics['users_with_multiple_roles']++;
        $userAlerts[] = "🚨 MULTI-RÔLES ({$rolesCount} rôles)";
    }
    
    if ($directPermissionsCount > 0) {
        $statistics['users_with_direct_permissions']++;
        $userAlerts[] = "⚠️ PERMISSIONS DIRECTES ({$directPermissionsCount})";
    }
    
    if (!$isActive && $totalPermissions > 0) {
        $statistics['inactive_users_with_permissions']++;
        $userAlerts[] = "🔒 COMPTE INACTIF AVEC PERMISSIONS";
    }
    
    if ($hasAdminRole && !$emailVerified) {
        $statistics['unverified_users_with_admin']++;
        $userAlerts[] = "📧 ADMIN NON VÉRIFIÉ";
    }
    
    if ($totalPermissions > 50) {
        $statistics['super_privileged_users']++;
        $userAlerts[] = "🎯 SUPER-PRIVILÉGIÉ ({$totalPermissions} permissions)";
    }
    
    // Affichage si des alertes sont détectées
    if (!empty($userAlerts)) {
        echo "👤 {$user->name} ({$user->email})\n";
        echo "   ID: {$user->id}\n";
        echo "   Rôles: " . $user->roles->pluck('name')->implode(', ') . "\n";
        echo "   Permissions: {$directPermissionsCount} directes + " . ($totalPermissions - $directPermissionsCount) . " via rôles = {$totalPermissions} total\n";
        echo "   Statut: " . ($isActive ? 'Actif' : 'Inactif') . " | Email: " . ($emailVerified ? 'Vérifié' : 'Non vérifié') . "\n";
        echo "   🚨 ALERTES: " . implode(' | ', $userAlerts) . "\n";
        echo str_repeat("-", 80) . "\n";
        
        $alerts[] = [
            'user' => $user,
            'alerts' => $userAlerts,
            'severity' => count($userAlerts)
        ];
    }
}

echo "\n📊 RÉSUMÉ DES ANOMALIES DÉTECTÉES:\n";
echo "• Utilisateurs avec multiples rôles: {$statistics['users_with_multiple_roles']}\n";
echo "• Utilisateurs avec permissions directes: {$statistics['users_with_direct_permissions']}\n";
echo "• Comptes inactifs avec permissions: {$statistics['inactive_users_with_permissions']}\n";
echo "• Admins non vérifiés: {$statistics['unverified_users_with_admin']}\n";
echo "• Utilisateurs super-privilégiés: {$statistics['super_privileged_users']}\n";

// Top 10 des utilisateurs les plus à risque
echo "\n🎯 TOP 10 UTILISATEURS À RISQUE:\n";
usort($alerts, function($a, $b) {
    return $b['severity'] <=> $a['severity'];
});

$topRisks = array_slice($alerts, 0, 10);
foreach ($topRisks as $index => $alert) {
    $user = $alert['user'];
    echo ($index + 1) . ". {$user->name} ({$user->email}) - " . $alert['severity'] . " alertes\n";
    echo "   " . implode(' | ', $alert['alerts']) . "\n";
}

// Analyse des rôles
echo "\n🎭 ANALYSE DES RÔLES:\n";
$roleStats = [];
foreach (Role::all() as $role) {
    $userCount = $role->users()->count();
    $permissionCount = $role->permissions()->count();
    $roleStats[] = [
        'name' => $role->name,
        'users' => $userCount,
        'permissions' => $permissionCount
    ];
}

usort($roleStats, function($a, $b) {
    return $b['users'] <=> $a['users'];
});

foreach ($roleStats as $stat) {
    echo "• {$stat['name']}: {$stat['users']} utilisateurs, {$stat['permissions']} permissions\n";
}

// Recommandations de sécurité
echo "\n🛡️ RECOMMANDATIONS DE SÉCURITÉ:\n";

if ($statistics['users_with_direct_permissions'] > 0) {
    echo "1. ÉLIMINER LES PERMISSIONS DIRECTES\n";
    echo "   - {$statistics['users_with_direct_permissions']} utilisateurs ont des permissions directes\n";
    echo "   - Utiliser uniquement les rôles pour gérer les permissions\n\n";
}

if ($statistics['users_with_multiple_roles'] > 0) {
    echo "2. SIMPLIFIER LES RÔLES MULTIPLES\n";
    echo "   - {$statistics['users_with_multiple_roles']} utilisateurs ont plusieurs rôles\n";
    echo "   - Créer des rôles composites si nécessaire\n\n";
}

if ($statistics['inactive_users_with_permissions'] > 0) {
    echo "3. NETTOYER LES COMPTES INACTIFS\n";
    echo "   - {$statistics['inactive_users_with_permissions']} comptes inactifs ont des permissions\n";
    echo "   - Retirer toutes les permissions des comptes inactifs\n\n";
}

if ($statistics['unverified_users_with_admin'] > 0) {
    echo "4. VÉRIFIER LES COMPTES ADMINISTRATEURS\n";
    echo "   - {$statistics['unverified_users_with_admin']} admins n'ont pas vérifié leur email\n";
    echo "   - Suspendre les privilèges jusqu'à vérification\n\n";
}

echo "5. MISE EN PLACE DE CONTRÔLES\n";
echo "   - Audit périodique des permissions (mensuel)\n";
echo "   - Processus d'approbation pour les rôles sensibles\n";
echo "   - Logging des changements de permissions\n";
echo "   - Révision annuelle des accès utilisateurs\n\n";

// Génération du rapport complet
$reportData = [
    'audit_date' => now()->toISOString(),
    'total_users' => $totalUsers,
    'statistics' => $statistics,
    'top_risks' => array_map(function($alert) {
        return [
            'user_id' => $alert['user']->id,
            'email' => $alert['user']->email,
            'severity' => $alert['severity'],
            'alerts' => $alert['alerts']
        ];
    }, $topRisks),
    'role_distribution' => $roleStats
];

$reportFile = 'audit_global_permissions_' . date('Y-m-d_H-i-s') . '.json';
file_put_contents($reportFile, json_encode($reportData, JSON_PRETTY_PRINT));

echo "📋 RAPPORT COMPLET SAUVEGARDÉ: {$reportFile}\n";

// Script de correction automatique recommandé
echo "\n🔧 SCRIPTS DE CORRECTION DISPONIBLES:\n";
echo "1. correction_permissions_sergyo.php - Correction spécifique pour sergyo.vutegha@gmail.com\n";
echo "2. Créer correction_permissions_globale.php pour correction en lot\n";

echo "\n=== AUDIT GLOBAL TERMINÉ ===\n";
