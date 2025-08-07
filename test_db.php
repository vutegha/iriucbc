<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Testing database connection...\n";
    
    // Test basic connection
    $pdo = DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
    
    // Test categories
    $categories = App\Models\Categorie::all();
    echo "✓ Categories loaded: " . $categories->count() . " found\n";
    
    foreach($categories as $category) {
        echo "  - " . $category->nom . "\n";
    }
    
} catch(Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
