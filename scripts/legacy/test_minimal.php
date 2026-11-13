<?php

echo "=== TEST MINIMAL PHP ===\n";

// Test 1: PHP de base
echo "PHP Version: " . PHP_VERSION . "\n";

// Test 2: Autoloader
echo "Test autoloader...\n";
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
    echo "✅ Autoloader chargé\n";
} else {
    echo "❌ vendor/autoload.php non trouvé\n";
    exit;
}

// Test 3: Laravel bootstrap
echo "Test Laravel bootstrap...\n";
try {
    if (file_exists('bootstrap/app.php')) {
        $app = require_once 'bootstrap/app.php';
        echo "✅ Laravel app créée\n";
        
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        echo "✅ Laravel bootstrappé\n";
        
    } else {
        echo "❌ bootstrap/app.php non trouvé\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur bootstrap: " . $e->getMessage() . "\n";
}

// Test 4: Configuration simple
echo "Test config...\n";
try {
    $appName = config('app.name');
    echo "✅ APP_NAME: $appName\n";
} catch (Exception $e) {
    echo "❌ Erreur config: " . $e->getMessage() . "\n";
}

echo "=== FIN TEST MINIMAL ===\n";
