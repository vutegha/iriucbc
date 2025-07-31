<?php
// Script pour nettoyer les actualités problématiques

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Models\Actualite;
use Illuminate\Support\Str;

echo "=== Nettoyage des actualités problématiques ===\n\n";

try {
    // 1. Trouver toutes les actualités sans titre
    $actualitesSansTitre = Actualite::whereNull('titre')->orWhere('titre', '')->get();
    
    echo "Trouvé " . $actualitesSansTitre->count() . " actualité(s) sans titre.\n";
    
    if ($actualitesSansTitre->count() > 0) {
        foreach ($actualitesSansTitre as $actualite) {
            echo "❌ Actualité ID {$actualite->id} sans titre - SUPPRESSION\n";
            $actualite->delete();
        }
        echo "✅ Actualités sans titre supprimées.\n\n";
    }
    
    // 2. Trouver toutes les actualités avec slug vide ou invalide
    $actualitesSlugInvalide = Actualite::where(function($query) {
        $query->whereNull('slug')
              ->orWhere('slug', '')
              ->orWhere('slug', 'like', '%-');
    })->get();
    
    echo "Trouvé " . $actualitesSlugInvalide->count() . " actualité(s) avec slug invalide.\n";
    
    if ($actualitesSlugInvalide->count() > 0) {
        foreach ($actualitesSlugInvalide as $actualite) {
            if (!empty($actualite->titre)) {
                $ancienSlug = $actualite->slug;
                
                // Générer un nouveau slug
                $date = $actualite->created_at ? $actualite->created_at->format('Ymd') : now()->format('Ymd');
                $nouveauSlug = $date . '-' . Str::slug($actualite->titre);
                
                // Vérifier l'unicité
                $counter = 1;
                $baseSlug = $nouveauSlug;
                while (Actualite::where('slug', $nouveauSlug)->where('id', '!=', $actualite->id)->exists()) {
                    $nouveauSlug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                
                $actualite->slug = $nouveauSlug;
                $actualite->save();
                
                echo "✅ Actualité ID {$actualite->id}: '{$actualite->titre}'\n";
                echo "   Ancien slug: " . ($ancienSlug ?: 'VIDE') . "\n";
                echo "   Nouveau slug: {$nouveauSlug}\n\n";
            } else {
                echo "❌ Actualité ID {$actualite->id} sans titre - SUPPRESSION\n";
                $actualite->delete();
            }
        }
    }
    
    echo "✅ Nettoyage terminé avec succès !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
