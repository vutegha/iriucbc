<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Evenement;

// Test de création d'un événement avec les nouveaux champs
try {
    echo "=== Test de création d'événement ===\n";
    
    $evenement = new Evenement([
        'titre' => 'Test Événement Simplifié',
        'resume' => 'Résumé de test',
        'description' => 'Description détaillée de test',
        'date_evenement' => now()->addDays(7),
        'lieu' => 'Salle de test',
        'organisateur' => 'IRI-UCBC',
        'contact_email' => 'test@iri-ucbc.org',
        'contact_telephone' => '+243 123 456 789',
        'programme' => 'Programme de test',
        'type' => 'conference',
        'en_vedette' => true,
        'rapport_url' => 'https://example.com/rapport'
    ]);
    
    echo "Événement créé avec succès !\n";
    echo "Titre: " . $evenement->titre . "\n";
    echo "Type: " . $evenement->type . "\n";
    echo "En vedette: " . ($evenement->en_vedette ? 'Oui' : 'Non') . "\n";
    echo "Date: " . $evenement->date_evenement . "\n";
    
    echo "\n=== Champs supprimés (ne doivent plus exister) ===\n";
    
    // Vérifier que les champs supprimés ne sont plus dans fillable
    $fillable = $evenement->getFillable();
    $champsSupprimes = ['statut', 'adresse', 'inscription_requise', 'places_disponibles', 'date_limite_inscription', 'meta_title', 'meta_description'];
    
    foreach ($champsSupprimes as $champ) {
        if (in_array($champ, $fillable)) {
            echo "❌ ERREUR: Le champ '$champ' existe encore dans fillable\n";
        } else {
            echo "✅ OK: Le champ '$champ' a été supprimé\n";
        }
    }
    
    echo "\n=== Test terminé avec succès ! ===\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
}
