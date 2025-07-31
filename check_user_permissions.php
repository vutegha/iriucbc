<?php

require_once 'bootstrap/app.php';

use App\Models\User;

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('email', 'sergyo.vutegha@gmail.com')->first();

if ($user) {
    echo "Utilisateur trouvé: " . $user->name . PHP_EOL;
    echo "Email: " . $user->email . PHP_EOL;
    echo "Super Admin: " . ($user->isSuperAdmin() ? 'OUI' : 'NON') . PHP_EOL;
    
    echo PHP_EOL . "=== PERMISSIONS ACTUALITÉS ===" . PHP_EOL;
    echo "update actualites: " . ($user->can('update actualites') ? 'OUI' : 'NON') . PHP_EOL;
    echo "view actualites: " . ($user->can('view actualites') ? 'OUI' : 'NON') . PHP_EOL;
    echo "delete actualites: " . ($user->can('delete actualites') ? 'OUI' : 'NON') . PHP_EOL;
    
    echo PHP_EOL . "=== PERMISSIONS ÉVÉNEMENTS ===" . PHP_EOL;
    echo "update evenements: " . ($user->can('update evenements') ? 'OUI' : 'NON') . PHP_EOL;
    echo "view evenements: " . ($user->can('view evenements') ? 'OUI' : 'NON') . PHP_EOL;
    echo "delete evenements: " . ($user->can('delete evenements') ? 'OUI' : 'NON') . PHP_EOL;
    
    echo PHP_EOL . "=== RÔLES ===" . PHP_EOL;
    foreach ($user->roles as $role) {
        echo "Rôle: " . $role->name . PHP_EOL;
    }
    
    echo PHP_EOL . "=== TOUTES LES PERMISSIONS ===" . PHP_EOL;
    $permissions = $user->getAllPermissions();
    foreach ($permissions as $permission) {
        echo "- " . $permission->name . PHP_EOL;
    }
    
} else {
    echo "Utilisateur non trouvé!" . PHP_EOL;
}
