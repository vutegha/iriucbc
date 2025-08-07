<?php

require_once 'bootstrap/app.php';

use App\Models\Auteur;

$app = app();

try {
    echo "Test de récupération des auteurs:\n";
    
    $auteurs = Auteur::all();
    
    foreach ($auteurs as $auteur) {
        echo "- " . $auteur->nom . " " . $auteur->prenom . " (" . $auteur->email . ")\n";
    }
    
    echo "Total: " . $auteurs->count() . " auteurs\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
