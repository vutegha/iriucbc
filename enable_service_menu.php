<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Service;

echo "=== Activation du service dans le menu ===\n";

$service = Service::first();
if ($service) {
    echo "Service: {$service->nom}\n";
    echo "Avant: show_in_menu = " . ($service->show_in_menu ? 'true' : 'false') . "\n";
    
    $service->update(['show_in_menu' => true]);
    $service->refresh();
    
    echo "Après: show_in_menu = " . ($service->show_in_menu ? 'true' : 'false') . "\n";
    echo "Le service devrait maintenant apparaître dans le menu.\n";
}
