<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    // Vérifier si la table evenements existe
    $columns = Schema::getColumnListing('evenements');
    echo "Colonnes de la table evenements:\n";
    foreach($columns as $column) {
        echo "- $column\n";
    }
    
    // Vérifier si les colonnes spécifiques existent
    $checkColumns = ['resume', 'en_vedette'];
    echo "\nVérification des colonnes spécifiques:\n";
    foreach($checkColumns as $col) {
        $exists = in_array($col, $columns) ? 'OUI' : 'NON';
        echo "- $col: $exists\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
