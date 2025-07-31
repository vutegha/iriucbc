<?php

require_once 'vendor/autoload.php';

// Configuration Laravel basique
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Ajout Manuel des Colonnes Manquantes ===\n\n";

try {
    // Vérifier si la table newsletters existe
    $tables = DB::select("SHOW TABLES LIKE 'newsletters'");
    
    if (empty($tables)) {
        echo "❌ La table 'newsletters' n'existe pas.\n";
        echo "Création de la table complète...\n";
        
        DB::statement("
            CREATE TABLE newsletters (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                email varchar(255) NOT NULL,
                nom varchar(255) DEFAULT NULL,
                token varchar(255) NOT NULL,
                actif tinyint(1) NOT NULL DEFAULT 1,
                confirme_a timestamp NULL DEFAULT NULL,
                preferences json DEFAULT NULL,
                last_email_sent timestamp NULL DEFAULT NULL,
                emails_sent_count int(11) DEFAULT 0,
                unsubscribe_reason text DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY newsletters_email_unique (email),
                UNIQUE KEY newsletters_token_unique (token)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        echo "✅ Table 'newsletters' créée avec succès.\n";
        
    } else {
        echo "✅ Table 'newsletters' existe.\n";
        
        // Vérifier et ajouter les colonnes manquantes une par une
        $columnsToAdd = [
            'preferences' => 'ALTER TABLE newsletters ADD COLUMN preferences JSON DEFAULT NULL',
            'last_email_sent' => 'ALTER TABLE newsletters ADD COLUMN last_email_sent TIMESTAMP NULL DEFAULT NULL',
            'emails_sent_count' => 'ALTER TABLE newsletters ADD COLUMN emails_sent_count INT DEFAULT 0',
            'unsubscribe_reason' => 'ALTER TABLE newsletters ADD COLUMN unsubscribe_reason TEXT DEFAULT NULL'
        ];
        
        foreach ($columnsToAdd as $columnName => $sql) {
            try {
                // Vérifier si la colonne existe déjà
                $columnExists = DB::select("SHOW COLUMNS FROM newsletters LIKE '$columnName'");
                
                if (empty($columnExists)) {
                    echo "Ajout de la colonne '$columnName'...\n";
                    DB::statement($sql);
                    echo "✅ Colonne '$columnName' ajoutée.\n";
                } else {
                    echo "⚠️  Colonne '$columnName' existe déjà.\n";
                }
                
            } catch (\Exception $e) {
                echo "❌ Erreur lors de l'ajout de '$columnName': " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\n=== Vérification Finale ===\n";
    $columns = DB::select("DESCRIBE newsletters");
    echo "Colonnes disponibles:\n";
    foreach ($columns as $column) {
        echo "   ✅ {$column->Field} ({$column->Type})\n";
    }
    
    echo "\n✅ Structure de la table mise à jour avec succès !\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
