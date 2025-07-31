<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== SCRIPT DE CORRECTION DES PERMISSIONS - sergyo.vutegha@gmail.com ===\n\n";

$user = User::where('email', 'sergyo.vutegha@gmail.com')->first();

if (!$user) {
    echo "❌ Utilisateur non trouvé.\n";
    exit;
}

echo "👤 Utilisateur: {$user->name} ({$user->email})\n";
echo "📊 État actuel:\n";
echo "  - Rôles: " . $user->roles->count() . "\n";
echo "  - Permissions directes: " . $user->permissions->count() . "\n";
echo "  - Statut: " . ($user->is_active ? 'Actif' : 'Inactif') . "\n\n";

// Fonction pour demander confirmation
function askConfirmation($message) {
    echo $message . " (y/N): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    fclose($handle);
    return trim(strtolower($line)) === 'y';
}

// 1. Proposition de nettoyage des permissions
echo "🧹 PROPOSITION DE NETTOYAGE:\n\n";

echo "1. SUPPRIMER TOUTES LES PERMISSIONS DIRECTES (" . $user->permissions->count() . " permissions)\n";
if (askConfirmation("   Procéder?")) {
    $user->permissions()->detach();
    echo "   ✅ Permissions directes supprimées\n";
} else {
    echo "   ⏭️ Ignoré\n";
}

echo "\n2. SUPPRIMER TOUS LES RÔLES EXISTANTS (" . $user->roles->count() . " rôles)\n";
if (askConfirmation("   Procéder?")) {
    $user->roles()->detach();
    echo "   ✅ Rôles supprimés\n";
} else {
    echo "   ⏭️ Ignoré\n";
}

// 2. Proposition de nouveaux rôles appropriés
echo "\n🎭 ATTRIBUTION D'UN RÔLE APPROPRIÉ:\n";
echo "Choisissez le niveau d'accès approprié:\n";
echo "1. Utilisateur standard (user)\n";
echo "2. Éditeur de contenu (editor)\n";
echo "3. Modérateur (moderator)\n";
echo "4. Gestionnaire de projets (gestionnaire_projets)\n";
echo "5. Administrateur (admin)\n";
echo "6. Aucun rôle pour l'instant\n";

echo "Votre choix (1-6): ";
$handle = fopen("php://stdin", "r");
$choice = trim(fgets($handle));
fclose($handle);

$roleMap = [
    '1' => 'user',
    '2' => 'editor', 
    '3' => 'moderator',
    '4' => 'gestionnaire_projets',
    '5' => 'admin',
    '6' => null
];

if (isset($roleMap[$choice])) {
    if ($roleMap[$choice]) {
        $role = Role::where('name', $roleMap[$choice])->first();
        if ($role) {
            $user->assignRole($role);
            echo "   ✅ Rôle '{$roleMap[$choice]}' assigné\n";
        } else {
            echo "   ❌ Rôle '{$roleMap[$choice]}' non trouvé\n";
        }
    } else {
        echo "   ✅ Aucun rôle assigné\n";
    }
} else {
    echo "   ❌ Choix invalide\n";
}

// 3. Gestion du statut du compte
echo "\n🔐 GESTION DU STATUT DU COMPTE:\n";
echo "Le compte est actuellement: " . ($user->is_active ? 'ACTIF' : 'INACTIF') . "\n";

if (!$user->is_active) {
    if (askConfirmation("Activer le compte?")) {
        $user->is_active = true;
        $user->save();
        echo "   ✅ Compte activé\n";
    } else {
        echo "   ⏭️ Compte resté inactif\n";
    }
} else {
    if (askConfirmation("Désactiver le compte par sécurité?")) {
        $user->is_active = false;
        $user->save();
        echo "   ✅ Compte désactivé\n";
    } else {
        echo "   ⏭️ Compte resté actif\n";
    }
}

// 4. Gestion de la vérification email
echo "\n📧 VÉRIFICATION EMAIL:\n";
echo "Email vérifié: " . ($user->email_verified_at ? 'OUI' : 'NON') . "\n";

if (!$user->email_verified_at) {
    if (askConfirmation("Marquer l'email comme vérifié?")) {
        $user->email_verified_at = now();
        $user->save();
        echo "   ✅ Email marqué comme vérifié\n";
    } else {
        echo "   ⏭️ Email resté non vérifié\n";
    }
}

// 5. Résumé final
echo "\n📋 RÉSUMÉ POST-CORRECTION:\n";
$user->refresh(); // Recharger les données

echo "  - Rôles: " . $user->roles->count() . "\n";
foreach ($user->roles as $role) {
    echo "    • {$role->name}\n";
}

echo "  - Permissions directes: " . $user->permissions->count() . "\n";
echo "  - Total permissions via rôles: " . $user->getAllPermissions()->count() . "\n";
echo "  - Statut: " . ($user->is_active ? 'Actif' : 'Inactif') . "\n";
echo "  - Email vérifié: " . ($user->email_verified_at ? 'Oui' : 'Non') . "\n";

// 6. Génération d'un log d'audit
echo "\n📝 GÉNÉRATION DU LOG D'AUDIT:\n";
$logEntry = [
    'timestamp' => now()->toISOString(),
    'user_id' => $user->id,
    'user_email' => $user->email,
    'action' => 'permission_audit_correction',
    'changes' => [
        'roles_after' => $user->roles->pluck('name')->toArray(),
        'direct_permissions_count' => $user->permissions->count(),
        'total_permissions_count' => $user->getAllPermissions()->count(),
        'account_active' => $user->is_active,
        'email_verified' => $user->email_verified_at ? true : false,
    ],
    'performed_by' => 'system_audit_script'
];

file_put_contents(
    'audit_log_sergyo_' . date('Y-m-d_H-i-s') . '.json',
    json_encode($logEntry, JSON_PRETTY_PRINT)
);

echo "✅ Log d'audit sauvegardé: audit_log_sergyo_" . date('Y-m-d_H-i-s') . ".json\n";

echo "\n🎯 RECOMMANDATIONS FINALES:\n";
echo "1. Surveiller l'activité de ce compte si activé\n";
echo "2. Réviser périodiquement les permissions\n";
echo "3. Documenter la justification métier des accès accordés\n";
echo "4. Implémenter un processus d'approbation pour les rôles sensibles\n";

echo "\n=== CORRECTION TERMINÉE ===\n";
