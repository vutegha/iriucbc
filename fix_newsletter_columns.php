<?php

// Script pour ajouter manuellement les colonnes manquantes à la table newsletters
require_once 'vendor/autoload.php';

// Chargement de l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Ajout des colonnes manquantes à la table newsletters ===\n\n";

try {
    // Vérifier si la table newsletters existe
    if (!Schema::hasTable('newsletters')) {
        echo "❌ La table 'newsletters' n'existe pas.\n";
        echo "Veuillez d'abord créer la table de base avec : php artisan migrate\n";
        exit(1);
    }

    echo "✅ Table 'newsletters' trouvée\n";

    // Vérifier les colonnes existantes
    $columns = Schema::getColumnListing('newsletters');
    echo "Colonnes existantes : " . implode(', ', $columns) . "\n\n";

    // Colonnes à ajouter
    $columnsToAdd = [
        'preferences' => 'JSON NULL',
        'last_email_sent' => 'TIMESTAMP NULL',
        'emails_sent_count' => 'INT DEFAULT 0',
        'unsubscribe_reason' => 'VARCHAR(255) NULL'
    ];

    foreach ($columnsToAdd as $columnName => $columnDefinition) {
        if (!in_array($columnName, $columns)) {
            echo "Ajout de la colonne '$columnName'...\n";
            
            $sql = "ALTER TABLE newsletters ADD COLUMN $columnName $columnDefinition";
            DB::statement($sql);
            
            echo "✅ Colonne '$columnName' ajoutée\n";
        } else {
            echo "⚠️ Colonne '$columnName' existe déjà\n";
        }
    }

    echo "\n=== Vérification finale ===\n";
    $newColumns = Schema::getColumnListing('newsletters');
    echo "Colonnes après modification : " . implode(', ', $newColumns) . "\n";

    // Test d'insertion
    echo "\n=== Test d'insertion ===\n";
    $testEmail = 'test_' . time() . '@example.com';
    
    $inserted = DB::table('newsletters')->insert([
        'email' => $testEmail,
        'nom' => 'Test User',
        'token' => bin2hex(random_bytes(16)),
        'actif' => 1,
        'preferences' => json_encode(['actualites' => true]),
        'created_at' => now(),
        'updated_at' => now()
    ]);

    if ($inserted) {
        echo "✅ Test d'insertion réussi avec l'email : $testEmail\n";
        
        // Nettoyer le test
        DB::table('newsletters')->where('email', $testEmail)->delete();
        echo "✅ Données de test nettoyées\n";
    } else {
        echo "❌ Échec du test d'insertion\n";
    }

    echo "\n🎉 Migration manuelle terminée avec succès !\n";

} catch (\Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "Trace : " . $e->getTraceAsString() . "\n";
}

function now() {
    return date('Y-m-d H:i:s');
}
