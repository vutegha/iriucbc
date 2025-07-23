<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Role;

try {
    // Créer un utilisateur admin de test
    $user = User::create([
        'name' => 'Admin Test',
        'email' => 'iri@ucbc.org',
        'password' => bcrypt('password123')
    ]);
    
    // Assigner le rôle admin
    $adminRole = Role::where('name', 'admin')->first();
    if ($adminRole) {
        $user->roles()->attach($adminRole);
        echo "✓ Utilisateur créé: {$user->name} ({$user->email}) avec rôle admin\n";
    } else {
        echo "✓ Utilisateur créé: {$user->name} ({$user->email}) sans rôle\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
