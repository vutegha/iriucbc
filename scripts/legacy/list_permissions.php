<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Permission;

echo "=== PERMISSIONS EXISTANTES ===" . PHP_EOL;

$permissions = Permission::all(['name']);

if ($permissions->count() > 0) {
    $permissions->each(function($permission) {
        echo "- " . $permission->name . PHP_EOL;
    });
} else {
    echo "❌ Aucune permission trouvée" . PHP_EOL;
}

echo PHP_EOL . "Total: " . $permissions->count() . " permissions" . PHP_EOL;

?>
