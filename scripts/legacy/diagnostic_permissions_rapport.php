<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;

echo "=== DIAGNOSTIC PERMISSIONS RAPPORT ===\n";

// 1. Lister toutes les permissions rapport existantes
echo "\n1. PERMISSIONS RAPPORT EXISTANTES:\n";
$rapportPermissions = Permission::where('name', 'like', '%rapport%')->get();
foreach ($rapportPermissions as $perm) {
    echo "   - {$perm->name}\n";
}

// 2. Lister les permissions manquantes selon les logs
echo "\n2. PERMISSIONS MANQUANTES DÉTECTÉES DANS LES LOGS:\n";
$missingPermissions = [
    'view rapports',
    'create rapports', 
    'update rapports',
    'delete rapports',
    'publish rapports',
    'unpublish rapports',
    'moderate rapports'
];

foreach ($missingPermissions as $permName) {
    $exists = Permission::where('name', $permName)->exists();
    echo "   - {$permName}: " . ($exists ? '✅ EXISTE' : '❌ MANQUANTE') . "\n";
}

// 3. Format harmonisé des permissions 
echo "\n3. FORMAT HARMONISÉ ATTENDU:\n";
$harmonizedPermissions = [
    'view rapport' => 'Voir les rapports',
    'create rapport' => 'Créer un rapport', 
    'update rapport' => 'Modifier un rapport',
    'delete rapport' => 'Supprimer un rapport',
    'publish rapport' => 'Publier un rapport',
    'unpublish rapport' => 'Dépublier un rapport',
    'moderate rapport' => 'Modérer les rapports'
];

foreach ($harmonizedPermissions as $permName => $description) {
    $exists = Permission::where('name', $permName)->exists();
    echo "   - {$permName}: " . ($exists ? '✅ EXISTE' : '❌ MANQUANTE') . "\n";
}

echo "\n=== FIN DIAGNOSTIC ===\n";
