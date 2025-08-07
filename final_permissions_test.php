<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Rapport;

echo "=== TEST FINAL DES PERMISSIONS RAPPORT/SHOW ===" . PHP_EOL;

$admin = User::where('email', 'iri@ucbc.org')->first();
if (!$admin) {
    $admin = User::whereIn('email', ['admin@iriucbc.com', 'admin@iri.com'])->first();
}

if ($admin) {
    echo "✅ Admin trouvé: {$admin->name}" . PHP_EOL;
    
    auth()->login($admin);
    
    $rapport = Rapport::first();
    if ($rapport) {
        echo "✅ Rapport de test: " . substr($rapport->titre, 0, 30) . "..." . PHP_EOL;
        echo "Slug: {$rapport->slug}" . PHP_EOL;
        echo PHP_EOL;
        
        // Test des permissions utilisées dans show.blade.php
        echo "Permissions testées:" . PHP_EOL;
        echo "- update_rapport: " . ($admin->can('update_rapport') ? '✅' : '❌') . PHP_EOL;
        echo "- delete_rapport: " . ($admin->can('delete_rapport') ? '✅' : '❌') . PHP_EOL;
        echo "- publish rapports: " . ($admin->can('publish rapports') ? '✅' : '❌') . PHP_EOL;
        echo "- unpublish rapports: " . ($admin->can('unpublish rapports') ? '✅' : '❌') . PHP_EOL;
        echo "- moderate (policy): " . ($admin->can('moderate', $rapport) ? '✅' : '❌') . PHP_EOL;
        
    } else {
        echo "❌ Aucun rapport trouvé" . PHP_EOL;
    }
} else {
    echo "❌ Admin non trouvé" . PHP_EOL;
}

echo PHP_EOL . "=== FIN DU TEST ===" . PHP_EOL;

?>
