<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Permission;

echo "=== LISTE DES PERMISSIONS ===" . PHP_EOL;
echo PHP_EOL;

$permissions = Permission::all();
echo "Total: " . $permissions->count() . " permissions" . PHP_EOL;
echo PHP_EOL;

foreach ($permissions as $permission) {
    echo "• " . $permission->name . PHP_EOL;
}

echo PHP_EOL;
echo "✅ Liste terminée!" . PHP_EOL;
