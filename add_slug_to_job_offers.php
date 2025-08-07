<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Str;

echo "Ajout de la colonne slug et génération des slugs...\n";

try {
    // Vérifier si la colonne existe déjà
    $columns = DB::select("SHOW COLUMNS FROM job_offers LIKE 'slug'");
    
    if (empty($columns)) {
        echo "Ajout de la colonne slug...\n";
        DB::statement('ALTER TABLE job_offers ADD COLUMN slug VARCHAR(255) UNIQUE NULL AFTER title');
        DB::statement('ALTER TABLE job_offers ADD INDEX idx_job_offers_slug (slug)');
        echo "✅ Colonne slug ajoutée\n";
    } else {
        echo "✅ Colonne slug existe déjà\n";
    }
    
    // Générer les slugs pour les offres existantes qui n'en ont pas
    echo "Génération des slugs pour les offres existantes...\n";
    
    $jobOffers = DB::select("SELECT id, title FROM job_offers WHERE slug IS NULL OR slug = ''");
    
    foreach ($jobOffers as $offer) {
        $baseSlug = Str::slug($offer->title);
        $slug = $baseSlug;
        $counter = 1;
        
        // Vérifier l'unicité du slug
        while (DB::selectOne("SELECT id FROM job_offers WHERE slug = ? AND id != ?", [$slug, $offer->id])) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        DB::update("UPDATE job_offers SET slug = ? WHERE id = ?", [$slug, $offer->id]);
        echo "  Offre ID {$offer->id}: '{$offer->title}' -> '{$slug}'\n";
    }
    
    echo "✅ Slugs générés avec succès\n";
    
    // Vérifier le résultat
    $totalOffers = DB::selectOne("SELECT COUNT(*) as count FROM job_offers")->count;
    $offersWithSlug = DB::selectOne("SELECT COUNT(*) as count FROM job_offers WHERE slug IS NOT NULL AND slug != ''")->count;
    
    echo "\nRésumé:\n";
    echo "  Total offres: $totalOffers\n";
    echo "  Avec slug: $offersWithSlug\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
