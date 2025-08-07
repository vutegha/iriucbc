<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Projet;
use Illuminate\Support\Facades\DB;

try {
    // Test 1: Vérifier si le modèle peut accéder à la colonne
    echo "🔍 Test 1: Vérification de l'accès au modèle Projet\n";
    
    $fillableFields = (new Projet())->getFillable();
    echo "Champs fillable: " . implode(', ', $fillableFields) . "\n";
    
    if (in_array('beneficiaires_enfants', $fillableFields)) {
        echo "✅ 'beneficiaires_enfants' est dans les champs fillable\n";
    } else {
        echo "❌ 'beneficiaires_enfants' n'est PAS dans les champs fillable\n";
    }
    
    // Test 2: Test de mise à jour directe avec DB
    echo "\n🔍 Test 2: Test de mise à jour avec DB raw\n";
    
    $result = DB::statement("
        UPDATE projets 
        SET beneficiaires_enfants = 0 
        WHERE beneficiaires_enfants IS NULL 
        LIMIT 1
    ");
    
    if ($result) {
        echo "✅ Mise à jour directe avec DB réussie\n";
    } else {
        echo "❌ Échec de la mise à jour directe\n";
    }
    
    // Test 3: Test avec Eloquent
    echo "\n🔍 Test 3: Test avec Eloquent\n";
    
    $projet = Projet::first();
    if ($projet) {
        echo "Projet trouvé: {$projet->nom}\n";
        
        $oldValue = $projet->beneficiaires_enfants;
        $projet->beneficiaires_enfants = 0;
        
        if ($projet->save()) {
            echo "✅ Mise à jour Eloquent réussie (ancienne valeur: $oldValue, nouvelle: 0)\n";
        } else {
            echo "❌ Échec de la mise à jour Eloquent\n";
        }
    } else {
        echo "❌ Aucun projet trouvé pour le test\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur lors des tests : " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
