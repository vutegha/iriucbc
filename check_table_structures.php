<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Vérification de la structure des tables\n\n";

// Vérifier la structure de la table actualites
echo "📋 Structure de la table 'actualites':\n";
try {
    $columns = DB::select('DESCRIBE actualites');
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type}) " . 
             ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . 
             ($column->Default ? " DEFAULT {$column->Default}" : '') . "\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n📋 Structure de la table 'job_offers':\n";
try {
    $columns = DB::select('DESCRIBE job_offers');
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type}) " . 
             ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . 
             ($column->Default ? " DEFAULT {$column->Default}" : '') . "\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n📋 Structure de la table 'rapports':\n";
try {
    $columns = DB::select('DESCRIBE rapports');
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type}) " . 
             ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . 
             ($column->Default ? " DEFAULT {$column->Default}" : '') . "\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
