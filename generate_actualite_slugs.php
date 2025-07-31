<?php
// Script pour générer les slugs manquants pour les actualités

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Models\Actualite;
use Illuminate\Support\Str;

echo "=== Génération des slugs manquants pour les actualités ===\n\n";

try {
    // Trouver toutes les actualités sans slug
    $actualitesSansSlug = Actualite::whereNull('slug')->orWhere('slug', '')->get();
    
    echo "Trouvé " . $actualitesSansSlug->count() . " actualité(s) sans slug.\n\n";
    
    if ($actualitesSansSlug->count() > 0) {
        foreach ($actualitesSansSlug as $actualite) {
            $originalSlug = $actualite->slug;
            
            // Générer un nouveau slug
            $date = $actualite->created_at ? $actualite->created_at->format('Ymd') : now()->format('Ymd');
            $nouveauSlug = $date . '-' . Str::slug($actualite->nom ?? $actualite->titre);
            
            // Vérifier l'unicité
            $counter = 1;
            $baseSlug = $nouveauSlug;
            while (Actualite::where('slug', $nouveauSlug)->where('id', '!=', $actualite->id)->exists()) {
                $nouveauSlug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            // Sauvegarder
            $actualite->slug = $nouveauSlug;
            $actualite->save();
            
            echo "✅ Actualité ID {$actualite->id}: '{$actualite->titre}'\n";
            echo "   Ancien slug: " . ($originalSlug ?: 'VIDE') . "\n";
            echo "   Nouveau slug: {$nouveauSlug}\n\n";
        }
        
        echo "✅ Tous les slugs ont été générés avec succès !\n";
    } else {
        echo "✅ Toutes les actualités ont déjà un slug.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
