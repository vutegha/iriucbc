<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

echo "=== DIAGNOSTIC 403 /admin/users ===\n\n";

// 1. Vérifier l'utilisateur admin@ucbc.org
$admin = User::where('email', 'admin@ucbc.org')->first();

if (!$admin) {
    echo "❌ Utilisateur admin@ucbc.org introuvable!\n";
    exit;
}

echo "👤 UTILISATEUR: {$admin->name} ({$admin->email})\n";
echo "🏷️  RÔLES: " . $admin->roles->pluck('name')->implode(', ') . "\n\n";

// 2. Vérifier les permissions liées aux users
echo "🔍 PERMISSIONS USERS:\n";
$userPermissions = DB::table('permissions')
    ->where('name', 'LIKE', '%user%')
    ->orderBy('name')
    ->get();

foreach ($userPermissions as $perm) {
    $hasPermission = $admin->hasPermissionTo($perm->name);
    echo "  " . ($hasPermission ? "✅" : "❌") . " {$perm->name}\n";
}

// 3. Vérifier toutes les permissions de l'admin
echo "\n📋 TOUTES LES PERMISSIONS DE L'ADMIN:\n";
$allPermissions = $admin->getAllPermissions();
echo "Total: " . $allPermissions->count() . " permissions\n";

$userRelatedPerms = $allPermissions->filter(function($perm) {
    return str_contains($perm->name, 'user') || str_contains($perm->name, 'admin');
});

if ($userRelatedPerms->count() > 0) {
    echo "Permissions liées aux users/admin:\n";
    foreach ($userRelatedPerms as $perm) {
        echo "  ✅ {$perm->name}\n";
    }
} else {
    echo "❌ Aucune permission liée aux users trouvée!\n";
}

// 4. Vérifier le controller Users
echo "\n🎯 DIAGNOSTIC CONTROLLER:\n";

// Chercher le controller Users
$controllerPaths = [
    'app/Http/Controllers/Admin/UserController.php',
    'app/Http/Controllers/UserController.php',
    'app/Http/Controllers/Admin/UsersController.php'
];

$controllerFound = false;
foreach ($controllerPaths as $path) {
    if (file_exists($path)) {
        echo "✅ Controller trouvé: $path\n";
        $controllerFound = true;
        
        // Lire le controller pour voir les middlewares/permissions
        $content = file_get_contents($path);
        
        // Chercher les middlewares de permissions
        if (preg_match_all('/middleware\([\'"]([^\'"]*)[\'"]/', $content, $matches)) {
            echo "  Middlewares: " . implode(', ', array_unique($matches[1])) . "\n";
        }
        
        // Chercher les can() dans le controller
        if (preg_match_all('/@can\([\'"]([^\'"]*)[\'"]/', $content, $matches)) {
            echo "  Permissions requises: " . implode(', ', array_unique($matches[1])) . "\n";
        }
        
        // Chercher authorize() calls
        if (preg_match_all('/authorize\([\'"]([^\'"]*)[\'"]/', $content, $matches)) {
            echo "  Authorize calls: " . implode(', ', array_unique($matches[1])) . "\n";
        }
        
        break;
    }
}

if (!$controllerFound) {
    echo "❌ Aucun controller Users trouvé!\n";
}

// 5. Vérifier les routes
echo "\n🛣️  ROUTES ADMIN:\n";
if (file_exists('routes/web.php')) {
    $routes = file_get_contents('routes/web.php');
    
    // Chercher les routes admin/users
    if (strpos($routes, '/admin/users') !== false || strpos($routes, 'admin/user') !== false) {
        echo "✅ Routes admin/users trouvées dans web.php\n";
        
        // Extraire les lignes contenant admin/user
        $lines = explode("\n", $routes);
        foreach ($lines as $line) {
            if (strpos($line, 'admin/user') !== false) {
                echo "  📝 " . trim($line) . "\n";
            }
        }
    } else {
        echo "❌ Aucune route admin/users trouvée dans web.php\n";
    }
}

// 6. Recommandations
echo "\n💡 RECOMMANDATIONS:\n";

if ($userRelatedPerms->count() == 0) {
    echo "1. ❗ L'utilisateur n'a aucune permission liée aux users\n";
    echo "   → Exécuter create_missing_permissions.php\n";
}

if (!$controllerFound) {
    echo "2. ❗ Controller Users introuvable\n";
    echo "   → Vérifier l'existence du controller\n";
}

echo "3. 🔧 Actions suggérées:\n";
echo "   a) Vérifier que view_users permission existe\n";
echo "   b) Vérifier les middlewares du controller\n";
echo "   c) Vérifier les routes admin\n";
echo "   d) Clear cache: php artisan route:clear\n";

echo "\n" . str_repeat("=", 50) . "\n";
