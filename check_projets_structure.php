<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Structure de la table 'projets':\n";
try {
    $columns = DB::select('DESCRIBE projets');
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type})\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
