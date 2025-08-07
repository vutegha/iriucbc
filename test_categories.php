<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $categories = App\Models\Categorie::all();
    echo "Categories found: " . $categories->count() . "\n";
    foreach($categories as $cat) {
        echo "- " . $cat->nom . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
