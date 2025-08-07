<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    // Vérifier si la colonne existe déjà
    if (!Schema::hasColumn('projets', 'beneficiaires_enfants')) {
        Schema::table('projets', function (Blueprint $table) {
            $table->integer('beneficiaires_enfants')->nullable()->default(0)->after('beneficiaires_femmes');
        });
        echo "✅ Colonne 'beneficiaires_enfants' ajoutée avec succès à la table 'projets'.\n";
    } else {
        echo "ℹ️ La colonne 'beneficiaires_enfants' existe déjà dans la table 'projets'.\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur lors de l'ajout de la colonne : " . $e->getMessage() . "\n";
}

// Vérifier la structure finale
$columns = Schema::getColumnListing('projets');
echo "\n📋 Colonnes dans la table 'projets' :\n";
foreach ($columns as $column) {
    if (strpos($column, 'beneficiaires') !== false) {
        echo "   - $column\n";
    }
}
