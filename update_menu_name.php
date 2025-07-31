<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Service;

echo "=== Mise à jour du nom de menu ===\n";

$service = Service::first();
if ($service) {
    echo "Service: {$service->nom}\n";
    echo "Nom menu actuel: '" . ($service->nom_menu ?: 'Vide') . "'\n";
    
    $service->update(['nom_menu' => 'GRN']);
    $service->refresh();
    
    echo "Nouveau nom menu: '" . ($service->nom_menu ?: 'Vide') . "'\n";
    echo "Le service devrait maintenant avoir un nom de menu court et valide.\n";
}
