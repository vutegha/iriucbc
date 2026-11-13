<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Structure de la table media:\n";
    $columns = Schema::getColumnListing('media');
    
    foreach ($columns as $column) {
        echo "- $column\n";
    }
    
    echo "\nVérification des colonnes nécessaires:\n";
    
    $requiredColumns = ['status', 'is_public', 'moderated_by', 'moderated_at', 'created_by'];
    
    foreach ($requiredColumns as $col) {
        if (in_array($col, $columns)) {
            echo "✓ $col existe\n";
        } else {
            echo "✗ $col MANQUANTE\n";
        }
    }
    
    // Test d'une requête
    echo "\nTest d'une requête published():\n";
    $count = App\Models\Media::published()->count();
    echo "Nombre de médias publiés: $count\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
