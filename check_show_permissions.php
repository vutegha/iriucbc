<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Permission;

echo "=== VÉRIFICATION DES PERMISSIONS UTILISÉES DANS RAPPORT/SHOW ===" . PHP_EOL;

$permissionsToCheck = [
    'update_rapport',
    'delete_rapport', 
    'publish rapports',
    'unpublish rapports'
];

foreach ($permissionsToCheck as $permissionName) {
    $exists = Permission::where('name', $permissionName)->exists();
    $status = $exists ? '✅' : '❌';
    echo "{$status} {$permissionName}" . PHP_EOL;
}

echo PHP_EOL . "=== FIN DE LA VÉRIFICATION ===" . PHP_EOL;

?>
