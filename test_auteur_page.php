<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AuteurController;

// Simuler une requête
try {
    echo "Test d'accès à la page auteur...\n";
    
    // Vérifier que le modèle Auteur existe
    if (class_exists('App\Models\Auteur')) {
        echo "✓ Modèle Auteur trouvé\n";
    } else {
        echo "✗ Modèle Auteur non trouvé\n";
    }
    
    // Vérifier que le contrôleur existe
    if (class_exists('App\Http\Controllers\Admin\AuteurController')) {
        echo "✓ Contrôleur AuteurController trouvé\n";
    } else {
        echo "✗ Contrôleur AuteurController non trouvé\n";
    }
    
    echo "Test terminé avec succès\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
