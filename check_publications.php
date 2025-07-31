<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking publications with slugs:\n";

$publications = App\Models\Publication::take(5)->get(['id', 'titre', 'slug']);

foreach ($publications as $pub) {
    echo "ID: {$pub->id}, Titre: " . substr($pub->titre, 0, 40) . "..., Slug: " . ($pub->slug ?: 'NULL') . "\n";
}

echo "\nTotal publications: " . App\Models\Publication::count() . "\n";
echo "Publications with slugs: " . App\Models\Publication::whereNotNull('slug')->count() . "\n";
