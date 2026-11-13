<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Vérification et ajout de la colonne partner_logo...\n";

try {
    // Vérifier si la colonne existe déjà
    $columns = DB::select("SHOW COLUMNS FROM job_offers LIKE 'partner_logo'");
    
    if (empty($columns)) {
        echo "Colonne partner_logo non trouvée, ajout en cours...\n";
        DB::statement('ALTER TABLE job_offers ADD COLUMN partner_logo VARCHAR(255) NULL AFTER partner_name');
        echo "✅ Colonne partner_logo ajoutée avec succès\n";
    } else {
        echo "✅ Colonne partner_logo existe déjà\n";
    }
    
    // Vérifier la structure finale
    echo "\nStructure actuelle de la table job_offers:\n";
    $allColumns = DB::select("DESCRIBE job_offers");
    foreach ($allColumns as $col) {
        echo "  {$col->Field} - {$col->Type} - " . ($col->Null === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
