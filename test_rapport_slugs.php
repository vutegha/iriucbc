<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Rapport;

echo "=== TEST DES SLUGS DE RAPPORTS ===" . PHP_EOL;

$rapports = Rapport::take(3)->get(['id', 'titre', 'slug']);

foreach ($rapports as $rapport) {
    echo "ID: {$rapport->id}" . PHP_EOL;
    echo "Titre: {$rapport->titre}" . PHP_EOL;
    echo "Slug: {$rapport->slug}" . PHP_EOL;
    echo "---" . PHP_EOL;
}

echo PHP_EOL . "=== FIN DU TEST ===" . PHP_EOL;

?>
