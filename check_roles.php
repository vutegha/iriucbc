<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Role;

$roles = Role::all();
echo "Rôles existants dans la base de données:\n";
if ($roles->count() > 0) {
    foreach ($roles as $role) {
        echo "  • {$role->name} (guard: {$role->guard_name})\n";
    }
} else {
    echo "  ⚠️ Aucun rôle trouvé!\n";
}
echo "\nTotal: " . $roles->count() . " rôles\n";
