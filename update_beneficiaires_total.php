<?php

require_once 'vendor/autoload.php';

try {
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    $projets = DB::table('projets')->get();
    $updated = 0;

    echo "=== Recalcul du total des bénéficiaires ===\n\n";

    foreach ($projets as $projet) {
        $hommes = (int) ($projet->beneficiaires_hommes ?? 0);
        $femmes = (int) ($projet->beneficiaires_femmes ?? 0);
        $enfants = (int) ($projet->beneficiaires_enfants ?? 0);
        $nouveau_total = $hommes + $femmes + $enfants;
        
        echo "Projet ID {$projet->id}:\n";
        echo "  Hommes: {$hommes}\n";
        echo "  Femmes: {$femmes}\n";
        echo "  Enfants: {$enfants}\n";
        echo "  Ancien total: {$projet->beneficiaires_total}\n";
        echo "  Nouveau total: {$nouveau_total}\n";
        
        if ($nouveau_total !== (int) $projet->beneficiaires_total) {
            DB::table('projets')
                ->where('id', $projet->id)
                ->update(['beneficiaires_total' => $nouveau_total]);
            $updated++;
            echo "  ✅ Mis à jour\n";
        } else {
            echo "  ✅ Déjà correct\n";
        }
        echo "\n";
    }

    echo "Mise à jour terminée. {$updated} projet(s) mis à jour.\n";

} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

?>
