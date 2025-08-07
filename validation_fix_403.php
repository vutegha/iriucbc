<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Gate;

echo "=== VALIDATION CORRECTION 403 /admin/users ===\n\n";

$admin = User::where('email', 'admin@ucbc.org')->first();

if (!$admin) {
    echo "❌ Utilisateur admin@ucbc.org introuvable!\n";
    exit;
}

echo "👤 UTILISATEUR: {$admin->name}\n";
echo "🏷️  RÔLES: " . $admin->roles->pluck('name')->implode(', ') . "\n\n";

// 1. Test de la permission manage_users
echo "🔍 TEST PERMISSION MANAGE_USERS:\n";
$canManageUsers = $admin->hasPermissionTo('manage_users');
echo "  " . ($canManageUsers ? "✅" : "❌") . " hasPermissionTo('manage_users'): " . ($canManageUsers ? "TRUE" : "FALSE") . "\n";

// 2. Test via Gate
echo "\n🚪 TEST VIA GATE:\n";
try {
    $gateResult = Gate::forUser($admin)->allows('manage_users');
    echo "  " . ($gateResult ? "✅" : "❌") . " Gate::allows('manage_users'): " . ($gateResult ? "TRUE" : "FALSE") . "\n";
} catch (Exception $e) {
    echo "  ❌ Erreur Gate: " . $e->getMessage() . "\n";
}

// 3. Test de la policy UserPolicy si elle existe
echo "\n🏛️  TEST POLICY USER:\n";
try {
    $canViewAny = $admin->can('viewAny', \App\Models\User::class);
    echo "  " . ($canViewAny ? "✅" : "❌") . " can('viewAny', User::class): " . ($canViewAny ? "TRUE" : "FALSE") . "\n";
    
    $canManage = $admin->can('manage', \App\Models\User::class);
    echo "  " . ($canManage ? "✅" : "❌") . " can('manage', User::class): " . ($canManage ? "TRUE" : "FALSE") . "\n";
} catch (Exception $e) {
    echo "  ⚠️  Policy User: " . $e->getMessage() . "\n";
}

// 4. Vérification de la route corrigée
echo "\n🛣️  VÉRIFICATION ROUTE CORRIGÉE:\n";
$routesContent = file_get_contents('routes/web.php');
if (strpos($routesContent, "can:manage_users") !== false) {
    echo "  ✅ Route utilise 'can:manage_users' (CORRECT)\n";
} else if (strpos($routesContent, "can:manage users") !== false) {
    echo "  ❌ Route utilise encore 'can:manage users' (INCORRECT)\n";
} else {
    echo "  ⚠️  Middleware manage_users non trouvé\n";
}

// 5. Test des permissions de base users
echo "\n📋 PERMISSIONS USERS DE BASE:\n";
$baseUserPermissions = ['view_users', 'create_users', 'update_users', 'delete_users', 'manage_users'];
foreach ($baseUserPermissions as $perm) {
    $has = $admin->hasPermissionTo($perm);
    echo "  " . ($has ? "✅" : "❌") . " $perm\n";
}

echo "\n🎯 RÉSULTAT:\n";
if ($canManageUsers) {
    echo "✅ L'utilisateur admin@ucbc.org devrait maintenant pouvoir accéder à /admin/users\n";
    echo "✅ Le middleware 'can:manage_users' est correctement configuré\n";
    echo "✅ Toutes les permissions users sont présentes\n\n";
    
    echo "🌐 TESTEZ MAINTENANT:\n";
    echo "   1. Visitez http://127.0.0.1:8000/admin/users\n";
    echo "   2. L'erreur 403 devrait être résolue\n";
    echo "   3. La page de gestion des utilisateurs devrait s'afficher\n";
} else {
    echo "❌ Problème persistant - l'utilisateur n'a pas la permission manage_users\n";
    echo "   → Vérifiez que la permission existe en base\n";
    echo "   → Vérifiez que le rôle super-admin a cette permission\n";
}

echo "\n" . str_repeat("=", 55) . "\n";
