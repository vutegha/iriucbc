<?php

require_once 'vendor/autoload.php';

// Configuration Laravel basique pour accéder à la DB
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Diagnostic Table Newsletters ===\n\n";

try {
    // 1. Vérifier si la table existe
    $tableExists = Schema::hasTable('newsletters');
    echo "1. Table 'newsletters' existe: " . ($tableExists ? "✅ OUI" : "❌ NON") . "\n\n";
    
    if ($tableExists) {
        // 2. Afficher la structure de la table
        echo "2. Structure actuelle de la table:\n";
        $columns = DB::select("DESCRIBE newsletters");
        foreach ($columns as $column) {
            echo "   - {$column->Field} ({$column->Type}) " . 
                 ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . 
                 ($column->Default ? " DEFAULT '{$column->Default}'" : '') . "\n";
        }
        echo "\n";
        
        // 3. Vérifier spécifiquement la colonne preferences
        $hasPreferences = Schema::hasColumn('newsletters', 'preferences');
        echo "3. Colonne 'preferences' existe: " . ($hasPreferences ? "✅ OUI" : "❌ NON") . "\n";
        
        // 4. Autres colonnes importantes
        $importantColumns = ['last_email_sent', 'emails_sent_count', 'unsubscribe_reason'];
        echo "4. Autres colonnes importantes:\n";
        foreach ($importantColumns as $col) {
            $exists = Schema::hasColumn('newsletters', $col);
            echo "   - $col: " . ($exists ? "✅ OUI" : "❌ NON") . "\n";
        }
        echo "\n";
        
        // 5. Compter les enregistrements
        $count = DB::table('newsletters')->count();
        echo "5. Nombre d'enregistrements: $count\n\n";
        
        if ($count > 0) {
            echo "6. Échantillon des données:\n";
            $sample = DB::table('newsletters')->limit(3)->get();
            foreach ($sample as $row) {
                echo "   - ID: {$row->id}, Email: {$row->email}, Actif: " . ($row->actif ? 'OUI' : 'NON') . "\n";
            }
        }
    }
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de l'accès à la base de données:\n";
    echo "   Message: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== Recommandations ===\n";
echo "Si la colonne 'preferences' n'existe pas:\n";
echo "1. Exécuter: php artisan migrate --force\n";
echo "2. Ou créer manuellement: ALTER TABLE newsletters ADD COLUMN preferences JSON NULL;\n";
echo "3. Vérifier le fichier de migration dans database/migrations/\n";
